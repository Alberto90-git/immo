<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\RecouvrementDossier;
use App\RecouvrementRelance;
use App\RecouvrementEcheance;
use App\Locataire;
use App\Facture;
use App\Direction;
use App\Annexe;
use App\Parametre;
use App\PlatformConfig;
use App\MessagingRate;
use App\Services\AfricasTalkingService;
use App\Mail\DocumentMail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;
use PDF;

class RecouvrementController extends Controller
{
    // ── Index : tableau consolidé ──────────────────────────────────────────────

    public function index(Request $request)
    {
        $dirId = Auth::user()->iddirection_ref;

        // Dossiers existants
        $dossiers = RecouvrementDossier::with([
                'locataire.maison',
                'locataire.chambre' => fn($q) => $q->whereNull('delete_at'),
                'relances',
            ])
            ->where('iddirection_ref', $dirId)
            ->whereNotIn('statut', ['resolu', 'classe'])
            ->orderByDesc('montant_du')
            ->get();

        // Locataires avec impayés sans dossier ouvert
        $dossiersLocIds = $dossiers->pluck('locataire_id')->toArray();

        $month = now()->month;
        $year  = now()->year;

        $impayes = Locataire::with([
                'maison',
                'chambre' => fn($q) => $q->whereNull('delete_at'),
            ])
            ->where('iddirection_ref', $dirId)
            ->where('status', true)
            ->whereNotIn('id', $dossiersLocIds)
            ->whereDoesntHave('factures', function ($q) use ($month, $year) {
                $q->whereNull('delete_at')
                  ->whereRaw('EXTRACT(MONTH FROM date_paiement) = ?', [$month])
                  ->whereRaw('EXTRACT(YEAR FROM date_paiement) = ?', [$year]);
            })
            ->get()
            ->map(function ($loc) {
                $dernierPaiement = Facture::where('locataire_id', $loc->id)
                    ->whereNull('delete_at')
                    ->whereNotNull('date_paiement')
                    ->orderByDesc('date_paiement')
                    ->value('date_paiement');
                $loc->dernierPaiement  = $dernierPaiement;
                $loc->nbMoisImpayes    = $dernierPaiement
                    ? (int) Carbon::parse($dernierPaiement)->diffInMonths(now())
                    : null;
                return $loc;
            });

        $stats = [
            'total_dossiers'   => $dossiers->count(),
            'montant_total'    => $dossiers->sum('montant_du'),
            'montant_recouvre' => $dossiers->sum('montant_recouvre'),
            'en_contentieux'   => $dossiers->where('statut', 'contentieux')->count(),
        ];

        return view('recouvrement.index', compact('dossiers', 'impayes', 'stats'));
    }

    // ── Créer un dossier depuis un locataire impayé ────────────────────────────

    public function creerDossier(Request $request)
    {
        $request->validate([
            'locataire_id'        => 'required|exists:locataires,id',
            'montant_du'          => 'required|numeric|min:0',
            'nb_mois_impayes'     => 'required|integer|min:1',
            'date_dernier_paiement' => 'nullable|date',
            'notes_juridiques'    => 'nullable|string',
        ]);

        $dirId = Auth::user()->iddirection_ref;

        // Un seul dossier actif par locataire
        $exists = RecouvrementDossier::where('locataire_id', $request->locataire_id)
            ->whereNotIn('statut', ['resolu', 'classe'])
            ->first();

        if ($exists) {
            return response()->json(['status' => false, 'message' => 'Un dossier de recouvrement actif existe déjà pour ce locataire.']);
        }

        RecouvrementDossier::create([
            'iddirection_ref'       => $dirId,
            'locataire_id'          => $request->locataire_id,
            'statut'                => 'actif',
            'montant_du'            => $request->montant_du,
            'montant_recouvre'      => 0,
            'nb_mois_impayes'       => $request->nb_mois_impayes,
            'date_dernier_paiement' => $request->date_dernier_paiement,
            'notes_juridiques'      => $request->notes_juridiques,
        ]);

        return response()->json(['status' => true, 'message' => 'Dossier de recouvrement créé avec succès.', 'redirect' => route('recouvrement.index')]);
    }

    // ── Détail d'un dossier ────────────────────────────────────────────────────

    public function show($id)
    {
        $dirId   = Auth::user()->iddirection_ref;
        $dossier = RecouvrementDossier::with([
                'locataire.maison',
                'locataire.chambre' => fn($q) => $q->whereNull('delete_at'),
                'relances',
                'echeances',
            ])
            ->where('iddirection_ref', $dirId)
            ->findOrFail($id);

        // Config paiement (pour SMS payant)
        $platformConfig   = PlatformConfig::getConfig();
        $paymentEnabled   = $platformConfig->isOperational();
        $paymentProvider  = $platformConfig->getActiveProvider();
        $paymentPublicKey = $paymentEnabled ? $platformConfig->getActivePublicKey() : null;
        $paymentSandbox   = $platformConfig->getActiveSandbox();

        // Tarif SMS par défaut
        $smsRate     = MessagingRate::where('is_default', true)->first()
                    ?? MessagingRate::first();
        $smsCost     = $smsRate ? (float) $smsRate->sms_unit_cost : 0;
        $smsCurrency = $smsRate?->currency ?? 'FCFA';

        return view('recouvrement.show', compact(
            'dossier',
            'paymentEnabled', 'paymentProvider', 'paymentPublicKey', 'paymentSandbox',
            'smsCost', 'smsCurrency'
        ));
    }

    // ── Envoyer une relance ────────────────────────────────────────────────────

    public function relancer(Request $request, $id)
    {
        $request->validate([
            'type'           => ['required', 'string', 'regex:/^rappel\d+$|^mise_en_demeure$/'],
            'canal'          => 'required|in:email,sms,whatsapp,courrier',
            'message'        => 'nullable|string|max:2000',
            'transaction_id' => 'nullable|string',
        ]);

        // SMS → vérifier que le paiement a été effectué
        if ($request->canal === 'sms') {
            if (empty($request->transaction_id)) {
                return response()->json(['status' => false, 'message' => 'Un paiement est requis pour envoyer un SMS.']);
            }
            $platformConfig = PlatformConfig::getConfig();
            if ($platformConfig->isKkiapayActive()) {
                $kkiaService = new \App\Services\KkiapayService(
                    $platformConfig->kkiapay_public_key,
                    $platformConfig->kkiapay_private_key,
                    $platformConfig->kkiapay_secret_key,
                    (bool) $platformConfig->kkiapay_sandbox
                );
                $result = $kkiaService->verifyTransaction($request->transaction_id);
                if (!$result['success']) {
                    return response()->json(['status' => false, 'message' => 'Paiement SMS non vérifié. Veuillez réessayer.']);
                }
            } elseif ($platformConfig->isFedapayActive()) {
                $fedaService = new \App\Services\FedapayService(
                    $platformConfig->fedapay_secret_key,
                    (bool) $platformConfig->fedapay_sandbox
                );
                $result = $fedaService->verifyTransaction($request->transaction_id);
                if (!$result['success']) {
                    return response()->json(['status' => false, 'message' => 'Paiement SMS non vérifié. Veuillez réessayer.']);
                }
            }
        }

        $dirId   = Auth::user()->iddirection_ref;
        $dossier = RecouvrementDossier::where('iddirection_ref', $dirId)->findOrFail($id);
        $locataire = $dossier->locataire;

        $statut  = 'envoye';
        $errMsg  = null;

        try {
            if ($request->canal === 'email' && $locataire->email) {
                $this->envoyerEmail($locataire, $dossier, $request->type, $request->message);
            } elseif ($request->canal === 'sms' && $locataire->telephone) {
                $this->envoyerSms($locataire, $dossier, $request->message);
            } elseif ($request->canal === 'courrier') {
                $statut = 'simule'; // courrier = manuel, on log sans envoyer
            }
        } catch (\Exception $e) {
            $statut = 'echec';
            $errMsg = $e->getMessage();
        }

        RecouvrementRelance::create([
            'dossier_id'       => $dossier->id,
            'iddirection_ref'  => $dirId,
            'type'             => $request->type,
            'canal'            => $request->canal,
            'statut'           => $statut,
            'message'          => $request->message,
            'envoye_le'        => now(),
        ]);

        if ($statut === 'echec') {
            return response()->json(['status' => false, 'message' => 'Échec de l\'envoi : ' . $errMsg]);
        }

        return response()->json(['status' => true, 'message' => 'Relance envoyée avec succès.']);
    }

    // ── Données fraîches pour mise à jour AJAX ────────────────────────────────

    public function relancesData($id)
    {
        $dirId   = Auth::user()->iddirection_ref;
        $dossier = RecouvrementDossier::with(['relances'])
            ->where('iddirection_ref', $dirId)
            ->findOrFail($id);

        $relancesHtml = view('recouvrement.partials.relances_timeline', compact('dossier'))->render();

        $options = $dossier->options_relance;
        $prochain = $dossier->prochain_type_relance;

        return response()->json([
            'status'       => true,
            'relancesHtml' => $relancesHtml,
            'options'      => $options,
            'prochain'     => $prochain,
        ]);
    }

    // ── Générer PDF mise en demeure ────────────────────────────────────────────

    public function misEnDemeurePdf($id)
    {
        [$dossier, $pdfData] = $this->buildMisEnDemeurePdfData($id, true);
        $pdf = PDF::loadView('pdf.mise_en_demeure', $pdfData);
        return $pdf->download('mise_en_demeure_' . $dossier->locataire->nom . '_' . now()->format('Ymd') . '.pdf');
    }

    // Aperçu inline : sert le fichier signé s'il existe, sinon génère à la volée
    public function previewMisEnDemeure($id, Request $request)
    {
        $dirId   = Auth::user()->iddirection_ref;
        $dossier = RecouvrementDossier::where('iddirection_ref', $dirId)->findOrFail($id);

        $signedPath = storage_path('app/public/recouvrement/' . $dossier->id . '/mise_en_demeure_signe.pdf');

        if (file_exists($signedPath)) {
            return response()->file($signedPath, [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="mise_en_demeure.pdf"',
            ]);
        }

        // Pas encore signé → génère sans cachet
        [, $pdfData] = $this->buildMisEnDemeurePdfData($id, false);
        $pdf = PDF::loadView('pdf.mise_en_demeure', $pdfData);
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="mise_en_demeure.pdf"');
    }

    // Signer : applique le cachet et sauvegarde sous nom fixe (écrase l'ancien)
    public function signerDocument($id)
    {
        [$dossier, $pdfData] = $this->buildMisEnDemeurePdfData($id, true);

        if (empty($pdfData['annexeData']['cachet_base64'])) {
            return response()->json(['status' => false, 'message' => 'Aucun cachet électronique trouvé. Veuillez en configurer un dans le paramétrage de l\'agence principale.']);
        }

        $dir = storage_path('app/public/recouvrement/' . $dossier->id);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $pdf = PDF::loadView('pdf.mise_en_demeure', $pdfData);
        // Nom fixe → chaque signature écrase la précédente
        file_put_contents($dir . '/mise_en_demeure_signe.pdf', $pdf->output());

        return response()->json(['status' => true, 'message' => 'Document signé avec succès.']);
    }

    private function buildMisEnDemeurePdfData($id, bool $withCachet = false): array
    {
        $dirId   = Auth::user()->iddirection_ref;
        $dossier = RecouvrementDossier::with([
                'locataire.maison',
                'locataire.chambre' => fn($q) => $q->whereNull('delete_at'),
                'relances',
            ])
            ->where('iddirection_ref', $dirId)
            ->findOrFail($id);

        // Infos direction
        $direction = Direction::find($dirId);

        // Infos annexe (signature + logo)
        $annexeId = get_active_annexe_id();
        $annexe   = Annexe::find($annexeId);

        // Source principale : annexe, sinon direction
        $designation  = $annexe?->designation  ?? $direction?->designation  ?? config('app.name');
        $siegeSocial  = $annexe?->siege_social ?? $direction?->siege_social ?? '';
        $telephone    = $annexe?->telephone    ?? $direction?->telephone    ?? '';
        $email        = $annexe?->email        ?? $direction?->email        ?? '';

        // Logo en base64 (fiable avec DomPDF)
        $logoBase64   = null;
        $logoPath     = $annexe?->logo_path;
        if ($logoPath && file_exists($logoPath)) {
            $mime         = mime_content_type($logoPath);
            $logoBase64   = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Signature en base64
        $signatureBase64 = null;
        $sigPath         = $annexe?->signature_path;
        if ($sigPath && file_exists($sigPath)) {
            $mime            = mime_content_type($sigPath);
            $signatureBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($sigPath));
        }

        // Cachet électronique en base64 (depuis parametres, lié à la direction)
        $cachetBase64 = null;
        if ($withCachet) {
            $parametre  = Parametre::where('iddirection_ref', $dirId)->first();
            $cachetRaw  = $parametre?->getRawOriginal('cash_electronique_url');

            if ($cachetRaw) {
                $cachetPath = storage_path('app/public/' . $cachetRaw);
                if (file_exists($cachetPath)) {
                    $mime         = mime_content_type($cachetPath);
                    $cachetBase64 = 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($cachetPath));
                }
            }
        }

        $annexeData = [
            'designation'   => $designation,
            'siege_social'  => $siegeSocial,
            'telephone'     => $telephone,
            'email'         => $email,
            'logo_base64'   => $logoBase64,
            'sig_base64'    => $signatureBase64,
            'cachet_base64' => $cachetBase64,
        ];

        // Nom du directeur (admin de la direction)
        $directeur = \App\User::where('iddirection_ref', $dirId)
            ->where('is_admin', 1)
            ->orderBy('id', 'asc')
            ->first();
        $nomDirecteur = $directeur
            ? trim(($directeur->prenom ?? '') . ' ' . ($directeur->nom ?? ''))
            : ($designation);

        // Délai de paiement (valeur du dossier ou 8 par défaut)
        $delaiJours    = $dossier->delai_paiement ?? 8;
        $delaiEnLettres = [
            1=>'un',2=>'deux',3=>'trois',4=>'quatre',5=>'cinq',6=>'six',7=>'sept',
            8=>'huit',9=>'neuf',10=>'dix',11=>'onze',12=>'douze',13=>'treize',
            14=>'quatorze',15=>'quinze',16=>'seize',20=>'vingt',21=>'vingt et un',
            25=>'vingt-cinq',30=>'trente',45=>'quarante-cinq',60=>'soixante',
            90=>'quatre-vingt-dix',
        ];
        $delaiLettre = $delaiEnLettres[$delaiJours] ?? $delaiJours;

        return [$dossier, [
            'dossier'      => $dossier,
            'locataire'    => $dossier->locataire,
            'date'         => now()->format('d/m/Y'),
            'annexeData'   => $annexeData,
            'nomDirecteur' => $nomDirecteur,
            'siegeSocial'  => $siegeSocial,
            'delaiJours'   => $delaiJours,
            'delaiLettre'  => $delaiLettre,
        ]];
    }

    // ── Plan d'apurement ──────────────────────────────────────────────────────

    public function creerPlanApurement(Request $request, $id)
    {
        $request->validate([
            'nb_echeances'      => 'required|integer|min:1|max:36',
            'montant_echeance'  => 'required|numeric|min:0',
            'date_premiere'     => 'required|date|after_or_equal:today',
        ]);

        $dirId   = Auth::user()->iddirection_ref;
        $dossier = RecouvrementDossier::where('iddirection_ref', $dirId)->findOrFail($id);

        // Vérifier que le total du plan ne dépasse pas le montant dû
        $totalPlan = $request->nb_echeances * $request->montant_echeance;
        if ($totalPlan > $dossier->montant_du) {
            return response()->json([
                'status'  => false,
                'message' => 'Le total des échéances (' . format_price($totalPlan) . ') dépasse le montant dû (' . format_price($dossier->montant_du) . ').',
            ]);
        }

        // Supprimer les anciennes échéances non payées
        $dossier->echeances()->where('statut', '!=', 'paye')->delete();

        $date = Carbon::parse($request->date_premiere);
        for ($i = 1; $i <= $request->nb_echeances; $i++) {
            RecouvrementEcheance::create([
                'dossier_id'    => $dossier->id,
                'numero'        => $i,
                'montant'       => $request->montant_echeance,
                'date_echeance' => $date->copy(),
                'statut'        => 'en_attente',
            ]);
            $date->addMonth();
        }

        $dossier->update(['statut' => 'apurement']);

        return response()->json(['status' => true, 'message' => 'Plan d\'apurement créé (' . $request->nb_echeances . ' échéances).']);
    }

    // ── Marquer une échéance comme payée ──────────────────────────────────────

    public function marquerEcheancePaye(Request $request, $echeanceId)
    {
        $dirId    = Auth::user()->iddirection_ref;
        $echeance = RecouvrementEcheance::whereHas('dossier', function ($q) use ($dirId) {
            $q->where('iddirection_ref', $dirId);
        })->findOrFail($echeanceId);

        $echeance->update([
            'statut'         => 'paye',
            'date_paiement'  => $request->date_paiement ?? now()->toDateString(),
        ]);

        // Recalculer montant_recouvre du dossier
        $dossier = $echeance->dossier;
        $totalPaye = $dossier->echeances()->where('statut', 'paye')->sum('montant');
        $dossier->montant_recouvre = $totalPaye;

        // Si tout est payé → résolu
        if ($dossier->echeances()->where('statut', '!=', 'paye')->count() === 0) {
            $dossier->statut = 'resolu';
        }
        $dossier->save();

        return response()->json(['status' => true, 'message' => 'Échéance marquée comme payée.']);
    }

    // ── Escalade en contentieux ────────────────────────────────────────────────

    public function escaladerContentieux(Request $request, $id)
    {
        $dirId   = Auth::user()->iddirection_ref;
        $dossier = RecouvrementDossier::where('iddirection_ref', $dirId)->findOrFail($id);

        $dossier->update([
            'statut'            => 'contentieux',
            'notes_juridiques'  => $request->notes_juridiques ?? $dossier->notes_juridiques,
            'date_assignation'  => $request->date_assignation,
        ]);

        return response()->json(['status' => true, 'message' => 'Dossier escaladé en contentieux.']);
    }

    // ── Mettre à jour notes / montants ────────────────────────────────────────

    public function update(Request $request, $id)
    {
        $request->validate([
            'notes_juridiques'  => 'nullable|string',
            'montant_recouvre'  => 'nullable|numeric|min:0',
            'delai_paiement'    => 'nullable|integer|min:1|max:90',
        ]);

        $dirId   = Auth::user()->iddirection_ref;
        $dossier = RecouvrementDossier::where('iddirection_ref', $dirId)->findOrFail($id);

        $dossier->fill($request->only(['notes_juridiques', 'montant_recouvre', 'delai_paiement']));

        if ($dossier->montant_recouvre >= $dossier->montant_du) {
            $dossier->statut = 'resolu';
        }

        $dossier->save();

        return response()->json(['status' => true, 'message' => 'Dossier mis à jour.']);
    }

    // ── Clôturer un dossier ───────────────────────────────────────────────────

    public function cloturer(Request $request, $id)
    {
        $dirId   = Auth::user()->iddirection_ref;
        $dossier = RecouvrementDossier::where('iddirection_ref', $dirId)->findOrFail($id);

        $dossier->update(['statut' => $request->statut ?? 'classe']);

        return response()->json(['status' => true, 'message' => 'Dossier clôturé.', 'redirect' => route('recouvrement.index')]);
    }

    // ── Helpers privés ────────────────────────────────────────────────────────

    private function envoyerEmail($locataire, $dossier, string $type, ?string $messagePersonnalise): void
    {
        if (!$locataire->email) {
            throw new \Exception('Adresse email manquante pour ce locataire.');
        }

        // Locale de la direction
        $locale = get_direction_locale($dossier->iddirection_ref ?? null);
        App::setLocale($locale);

        $typeLabels = [
            'rappel1'         => __('mail.recouvrement.type_rappel1'),
            'rappel2'         => __('mail.recouvrement.type_rappel2'),
            'mise_en_demeure' => __('mail.recouvrement.type_demeure'),
        ];

        $sujet = $typeLabels[$type] ?? 'Relance loyer impayé';
        $nom   = $locataire->prenom . ' ' . $locataire->nom;
        $msg   = $messagePersonnalise ?: $this->messageDefaut($type, $locataire, $dossier);

        $html = view('mail.recouvrement_relance', [
            'type'      => $type,
            'sujet'     => $sujet,
            'nom'       => $nom,
            'message'   => $msg,
            'dossier'   => $dossier,
            'devise'    => get_symbole_devise(get_devise_courante($dossier->iddirection_ref ?? null)),
        ])->render();

        Mail::send([], [], function ($mail) use ($locataire, $nom, $sujet, $html) {
            $mail->to($locataire->email, $nom)
                 ->subject($sujet)
                 ->setBody($html, 'text/html');
        });
    }

    private function envoyerSms($locataire, $dossier, ?string $messagePersonnalise): void
    {
        $msg = $messagePersonnalise ?: $this->messageDefaut('rappel1', $locataire, $dossier, true);
        $service = new AfricasTalkingService();
        $service->envoyerSMS($locataire->telephone, $msg);
    }

    private function messageDefaut(string $type, $locataire, $dossier, bool $court = false): string
    {
        $montant = format_price($dossier->montant_du - $dossier->montant_recouvre, $dossier->iddirection_ref ?? null);
        $nom     = $locataire->prenom . ' ' . $locataire->nom;

        if ($court) {
            return "Bonjour {$nom}, votre loyer de {$montant} est impayé. Merci de régulariser rapidement.";
        }

        switch ($type) {
            case 'rappel1':
                return "Nous vous informons qu'un montant de {$montant} reste dû au titre de votre loyer. Nous vous invitons à régulariser cette situation dans les plus brefs délais.";
            case 'rappel2':
                return "Malgré notre précédent rappel, le montant de {$montant} reste toujours impayé. Nous vous demandons de procéder au règlement immédiat sous peine d'engager une procédure judiciaire.";
            case 'mise_en_demeure':
                return "Nous vous mettons en demeure de régler la somme de {$montant} dans un délai de 8 jours à compter de la présente. À défaut, nous serons contraints d'engager une procédure judiciaire.";
            default:
                return "Votre loyer de {$montant} est en retard. Merci de régulariser.";
        }
    }
}
