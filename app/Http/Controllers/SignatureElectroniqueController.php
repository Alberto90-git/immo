<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF;
use App\SignatureDemande;
use App\SignatureAudit;
use App\Locataire;

class SignatureElectroniqueController extends Controller
{
    // ─── Liste des demandes ──────────────────────────────────────────────────

    public function index()
    {
        $dirId = Auth::user()->iddirection_ref;
        $user  = Auth::user();

        $query = SignatureDemande::whereNull('delete_at')
            ->where('iddirection_ref', $dirId);

        if (!$user->hasPermissionTo('Is_admin') && $user->idannexe_ref) {
            $query->where('idannexe_ref', $user->idannexe_ref);
        }

        $demandes = $query->orderByDesc('created_at')->get();

        return view('signature.index', compact('demandes'));
    }

    // ─── Créer une demande (AJAX) ────────────────────────────────────────────

    public function store(Request $request)
    {
        $validated = $request->validate([
            'document_type'       => 'required|in:contrat,etat_des_lieux,quittance,autre',
            'document_id'         => 'nullable|integer',
            'document_titre'      => 'required|string|max:255',
            'document_description'=> 'nullable|string|max:1000',
            'signataire_nom'      => 'required|string|max:255',
            'signataire_email'    => 'nullable|email|max:255',
            'signataire_telephone'=> 'nullable|string|max:30',
            'locataire_id'        => 'nullable|integer',
            'expires_days'        => 'nullable|integer|min:1|max:60',
            'pdf_file'            => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $user  = Auth::user();
        $dirId = $user->iddirection_ref;

        if (empty($validated['signataire_email']) && empty($validated['signataire_telephone'])) {
            return response()->json([
                'status'  => false,
                'message' => __('sig.error_no_contact'),
            ]);
        }

        // Stocker le PDF fourni
        $pdfPath = null;
        $sha256  = null;
        if ($request->hasFile('pdf_file')) {
            $file    = $request->file('pdf_file');
            $sha256  = hash_file('sha256', $file->getRealPath());
            $pdfPath = $file->store("signature_docs/{$dirId}", 'local');
        }

        $expiresAt = isset($validated['expires_days'])
            ? Carbon::now()->addDays((int)$validated['expires_days'])
            : Carbon::now()->addDays(7);

        $demande = SignatureDemande::create([
            'iddirection_ref'     => $dirId,
            'idannexe_ref'        => $user->idannexe_ref,
            'document_type'       => $validated['document_type'],
            'document_id'         => $validated['document_id'] ?? null,
            'document_titre'      => $validated['document_titre'],
            'document_description'=> $validated['document_description'] ?? null,
            'pdf_path'            => $pdfPath,
            'sha256_document'     => $sha256,
            'locataire_id'        => $validated['locataire_id'] ?? null,
            'signataire_nom'      => $validated['signataire_nom'],
            'signataire_email'    => $validated['signataire_email'] ?? null,
            'signataire_telephone'=> $validated['signataire_telephone'] ?? null,
            'token'               => Str::random(64),
            'statut'              => 'en_attente',
            'expires_at'          => $expiresAt,
            'created_by'          => $user->id,
        ]);

        $this->logAudit($demande, 'created', 'admin');

        // Envoi du lien
        $envoye = false;
        $canal   = '';
        App::setLocale(get_direction_locale($dirId));

        $lien = route('signature.signer', $demande->token);

        if (!empty($demande->signataire_email)) {
            try {
                Mail::send('mail.signature_demande', [
                    'lien'     => $lien,
                    'nom'      => $demande->signataire_nom,
                    'titre'    => $demande->document_titre,
                    'expire'   => $expiresAt->format('d/m/Y à H:i'),
                ], function ($m) use ($demande, $dirId) {
                    $agence = get_annexe_details_for_invoice($demande->idannexe_ref);
                    $m->to($demande->signataire_email, $demande->signataire_nom)
                      ->subject(__('sig.mail_subject') . ' – ' . ($agence['designation'] ?? config('app.name')));
                });
                $envoye = true;
                $canal   = 'email';
                $this->logAudit($demande, 'sent_email', 'email', ['email' => $demande->signataire_email]);
            } catch (\Exception $e) {
                // Continuer
            }
        }

        if (!$envoye && !empty($demande->signataire_telephone)) {
            try {
                $smsService = app(\App\Services\AfricasTalkingService::class);
                $msg = __('sig.sms_body', [
                    'nom'  => $demande->signataire_nom,
                    'lien' => $lien,
                ]);
                $smsService->envoyerSMS($demande->signataire_telephone, $msg);
                $envoye = true;
                $canal   = 'sms';
                $this->logAudit($demande, 'sent_sms', 'sms', ['tel' => $demande->signataire_telephone]);
            } catch (\Exception $e) {
                // Continuer
            }
        }

        return response()->json([
            'status'  => true,
            'message' => $envoye
                ? __('sig.sent_via', ['canal' => $canal])
                : __('sig.link_only'),
            'lien'    => $lien,
            'id'      => $demande->id,
        ]);
    }

    // ─── Renvoi du lien (AJAX) ───────────────────────────────────────────────

    public function renvoyer(Request $request)
    {
        $demande = SignatureDemande::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->findOrFail($request->id);

        if ($demande->statut !== 'en_attente') {
            return response()->json(['status' => false, 'message' => __('sig.error_not_pending')]);
        }

        // Renouveler le token et l'expiration
        $demande->update([
            'token'      => Str::random(64),
            'expires_at' => Carbon::now()->addDays(7),
        ]);

        $dirId = Auth::user()->iddirection_ref;
        App::setLocale(get_direction_locale($dirId));
        $lien = route('signature.signer', $demande->token);

        $envoye = false;
        $canal   = '';

        if (!empty($demande->signataire_email)) {
            try {
                Mail::send('mail.signature_demande', [
                    'lien'   => $lien,
                    'nom'    => $demande->signataire_nom,
                    'titre'  => $demande->document_titre,
                    'expire' => $demande->expires_at->format('d/m/Y à H:i'),
                ], function ($m) use ($demande, $dirId) {
                    $agence = get_annexe_details_for_invoice($demande->idannexe_ref);
                    $m->to($demande->signataire_email, $demande->signataire_nom)
                      ->subject(__('sig.mail_subject') . ' – ' . ($agence['designation'] ?? config('app.name')));
                });
                $envoye = true;
                $canal   = 'email';
                $this->logAudit($demande, 'sent_email', 'email', ['resend' => true]);
            } catch (\Exception $e) {}
        }

        if (!$envoye && !empty($demande->signataire_telephone)) {
            try {
                $smsService = app(\App\Services\AfricasTalkingService::class);
                $msg = __('sig.sms_body', ['nom' => $demande->signataire_nom, 'lien' => $lien]);
                $smsService->envoyerSMS($demande->signataire_telephone, $msg);
                $envoye = true;
                $canal   = 'sms';
                $this->logAudit($demande, 'sent_sms', 'sms', ['resend' => true]);
            } catch (\Exception $e) {}
        }

        return response()->json([
            'status'  => true,
            'message' => $envoye ? __('sig.resent_via', ['canal' => $canal]) : __('sig.link_only'),
            'lien'    => $lien,
        ]);
    }

    // ─── Annuler (AJAX) ──────────────────────────────────────────────────────

    public function annuler(Request $request)
    {
        $demande = SignatureDemande::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->findOrFail($request->id);

        $demande->update(['statut' => 'annule', 'delete_at' => now()]);
        $this->logAudit($demande, 'annule', 'admin');

        return response()->json(['status' => true, 'message' => __('sig.cancelled')]);
    }

    // ─── Télécharger le certificat PDF ──────────────────────────────────────

    public function certificat($id)
    {
        $id = decrypt_id($id); abort_if(!$id, 404);
        $demande = SignatureDemande::with('audits')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->where('statut', 'signe')
            ->findOrFail($id);

        $pdf = PDF::loadView('pdf.signature_certificate', compact('demande'))
            ->setPaper('a4')
            ->setOptions(['defaultFont' => 'DejaVu Sans', 'isRemoteEnabled' => true]);

        $nom = 'Certificat_signature_' . Str::slug($demande->document_titre) . '.pdf';
        return $pdf->download($nom);
    }

    // ─── Page publique de signature ──────────────────────────────────────────

    public function signer($token)
    {
        $demande = SignatureDemande::where('token', $token)->firstOrFail();

        // Vérifier l'expiration
        if ($demande->statut === 'en_attente' && $demande->isExpire()) {
            $demande->update(['statut' => 'expire']);
        }

        if (!$demande->isSignable()) {
            return view('signature.expire', compact('demande'));
        }

        // Log de la consultation
        $this->logAudit($demande, 'viewed', 'web', [
            'ip' => request()->ip(),
        ]);

        return view('signature.signer', compact('demande'));
    }

    // ─── Confirmer la signature (POST public) ────────────────────────────────

    public function confirmerSignature(Request $request, $token)
    {
        $demande = SignatureDemande::where('token', $token)->firstOrFail();

        if (!$demande->isSignable()) {
            return response()->json(['status' => false, 'message' => __('sig.error_not_signable')]);
        }

        $request->validate([
            'signature_data' => 'required|string',
            'nom_complet'    => 'required|string|max:255',
        ]);

        // Vérifier que le nom correspond au signataire attendu
        if (mb_strtolower(trim($request->nom_complet)) !== mb_strtolower(trim($demande->signataire_nom))) {
            return response()->json(['status' => false, 'message' => __('sig.error_name_mismatch')]);
        }

        $sigData = $request->signature_data;
        if (!preg_match('/^data:image\/png;base64,[A-Za-z0-9+\/=]+$/', $sigData)) {
            return response()->json(['status' => false, 'message' => __('sig.error_invalid_sig')]);
        }

        // Générer le PDF signé
        $pdfSignePath = $this->genererPdfSigne($demande, $sigData, $request->nom_complet);
        $sha256Signe  = $pdfSignePath ? hash_file('sha256', Storage::disk('local')->path($pdfSignePath)) : null;

        $demande->update([
            'statut'         => 'signe',
            'signature_image'=> $sigData,
            'signe_at'       => now(),
            'ip_adresse'     => $request->ip(),
            'user_agent'     => $request->userAgent(),
            'pdf_signe_path' => $pdfSignePath,
            'sha256_signe'   => $sha256Signe,
        ]);

        $this->logAudit($demande, 'signed', 'web', [
            'ip'         => $request->ip(),
            'nom_complet'=> $request->nom_complet,
            'sha256'     => $sha256Signe,
        ]);

        // Envoi de l'exemplaire signé au signataire
        $this->envoyerExemplaireSigne($demande);

        return response()->json([
            'status'  => true,
            'message' => __('sig.signed_success'),
        ]);
    }

    // ─── Page de confirmation publique ──────────────────────────────────────

    public function pageConfirme($token)
    {
        $demande = SignatureDemande::where('token', $token)
            ->where('statut', 'signe')
            ->firstOrFail();

        return view('signature.confirme', compact('demande'));
    }

    // ─── Helpers privés ─────────────────────────────────────────────────────

    private function genererPdfSigne(SignatureDemande $demande, string $sigData, string $nomComplet): ?string
    {
        try {
            $pdf = PDF::loadView('pdf.signature_certificate', [
                'demande'    => $demande,
                'sigData'    => $sigData,
                'nomComplet' => $nomComplet,
                'signedAt'   => now(),
                'mode'       => 'signe',
            ])->setPaper('a4')
              ->setOptions(['defaultFont' => 'DejaVu Sans', 'isRemoteEnabled' => true]);

            $dir  = "signature_signes/{$demande->iddirection_ref}";
            $name = "signed_{$demande->id}_" . now()->format('YmdHis') . '.pdf';
            Storage::disk('local')->put("{$dir}/{$name}", $pdf->output());

            return "{$dir}/{$name}";
        } catch (\Exception $e) {
            return null;
        }
    }

    private function envoyerExemplaireSigne(SignatureDemande $demande): void
    {
        if (empty($demande->signataire_email)) {
            return;
        }

        try {
            App::setLocale(get_direction_locale($demande->iddirection_ref));

            $pdfContent = null;
            if ($demande->pdf_signe_path && Storage::disk('local')->exists($demande->pdf_signe_path)) {
                $pdfContent = Storage::disk('local')->get($demande->pdf_signe_path);
            }

            Mail::send('mail.signature_confirme', [
                'nom'   => $demande->signataire_nom,
                'titre' => $demande->document_titre,
                'date'  => $demande->signe_at->format('d/m/Y à H:i'),
            ], function ($m) use ($demande, $pdfContent) {
                $agence = get_annexe_details_for_invoice($demande->idannexe_ref);
                $m->to($demande->signataire_email, $demande->signataire_nom)
                  ->subject(__('sig.mail_signed_subject') . ' – ' . ($agence['designation'] ?? config('app.name')));

                if ($pdfContent) {
                    $m->attachData(
                        $pdfContent,
                        'document_signe_' . Str::slug($demande->document_titre) . '.pdf',
                        ['mime' => 'application/pdf']
                    );
                }
            });

            $this->logAudit($demande, 'exemplaire_envoye', 'email', ['email' => $demande->signataire_email]);
        } catch (\Exception $e) {
            // Silencieux — la signature est déjà confirmée
        }
    }

    private function logAudit(SignatureDemande $demande, string $action, string $canal = null, array $details = []): void
    {
        SignatureAudit::create([
            'signature_demande_id' => $demande->id,
            'action'               => $action,
            'canal'                => $canal,
            'ip_adresse'           => request()->ip(),
            'user_agent'           => request()->userAgent(),
            'details'              => $details ?: null,
            'created_at'           => now(),
        ]);
    }
}
