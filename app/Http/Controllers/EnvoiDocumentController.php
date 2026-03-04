<?php

namespace App\Http\Controllers;

use App\EnvoiDocument;
use App\Facture;
use App\Locataire;
use App\Parametre;
use App\Proprietaire;
use App\Services\PdfGeneratorService;
use App\Services\WhatsAppService;
use App\Mail\DocumentMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class EnvoiDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $idannexe_ref = get_active_annexe_id();

        $locataires = Locataire::whereNull('locataires.delete_at')
            ->where('locataires.status', true)
            ->where('locataires.iddirection_ref', Auth::user()->iddirection_ref)
            ->when($idannexe_ref, fn($q) => $q->where('locataires.idannexe_ref', $idannexe_ref))
            ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
            ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
            ->select(
                'locataires.id',
                'locataires.nom',
                'locataires.prenom',
                'locataires.telephone',
                'locataires.email',
                'maisons.nom_maison',
                'chambres.numero_chambre',
                'chambres.type_chambre'
            )
            ->orderBy('locataires.nom')
            ->get();

        // Ajouter les factures disponibles par locataire
        $facturesParLocataire = Facture::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->select('id', 'locataire_id', 'mois', 'montant', 'date_paiement')
            ->orderBy('date_paiement', 'desc')
            ->get()
            ->groupBy('locataire_id');

        $proprietaires = Proprietaire::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
            ->select('id', 'nom', 'prenom', 'telephone', 'email')
            ->orderBy('nom')
            ->get();

        $parametre = Parametre::where('iddirection_ref', Auth::user()->iddirection_ref)->first();

        return view('documents.envoyer', compact('locataires', 'facturesParLocataire', 'proprietaires', 'parametre'));
    }

    public function envoyer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'destinataire_type'    => 'required|in:locataire,proprietaire',
            'destinataire_id'      => 'required|integer',
            'type_document'        => 'required|string',
            'methode_envoi'        => 'required|in:email,whatsapp',
            'message_personnalise' => 'nullable|string|max:1000',
            'telephone_override'   => 'nullable|string|max:30',
            // Champs conditionnels
            'facture_id'           => 'nullable|integer',
            'date_debut'           => 'nullable|date',
            'date_fin'             => 'nullable|date',
            'pourcentage'          => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => implode(' ', $validator->errors()->all()),
            ]);
        }

        $parametre = Parametre::where('iddirection_ref', Auth::user()->iddirection_ref)->first();

        // Vérifier config selon méthode
        if ($request->methode_envoi === 'email') {
            if (!$parametre || empty($parametre->email_envoi)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Email d\'envoi non configuré. Veuillez le définir dans Paramétrage > Communication.',
                ]);
            }
        } elseif ($request->methode_envoi === 'whatsapp') {
            try {
                $serviceUrl = env('WHATSAPP_SERVICE_URL', 'http://127.0.0.1:5050');
                $svcResp    = \Illuminate\Support\Facades\Http::timeout(5)->get($serviceUrl . '/status');
                if (($svcResp->json()['status'] ?? '') !== 'connected') {
                    return response()->json([
                        'status'  => false,
                        'message' => 'WhatsApp non connecté. Scannez le QR code dans Paramétrage > Communication.',
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Service WhatsApp indisponible. Démarrez scripts/whatsapp/start.bat puis connectez WhatsApp.',
                ]);
            }
        }

        // Récupérer le destinataire
        if ($request->destinataire_type === 'locataire') {
            $destinataire = Locataire::find($request->destinataire_id);
        } else {
            $destinataire = Proprietaire::find($request->destinataire_id);
        }

        if (!$destinataire) {
            return response()->json(['status' => false, 'message' => 'Destinataire introuvable.']);
        }

        $destinataireNom = trim($destinataire->nom . ' ' . $destinataire->prenom);

        // Pour WhatsApp : utiliser le numéro saisi/corrigé dans le modal si fourni
        if ($request->methode_envoi === 'whatsapp' && !empty($request->telephone_override)) {
            $destinataireContact = $request->telephone_override;
        } else {
            $destinataireContact = $request->methode_envoi === 'email'
                ? $destinataire->email
                : $destinataire->telephone;
        }

        if (empty($destinataireContact)) {
            $champ = $request->methode_envoi === 'email' ? 'email' : 'téléphone';
            return response()->json([
                'status'  => false,
                'message' => "Le {$champ} du destinataire n'est pas renseigné.",
            ]);
        }

        // Générer le PDF
        try {
            $pdfService = new PdfGeneratorService();

            switch ($request->type_document) {
                case 'contrat':
                    $pdf = $pdfService->genererContrat((int) $request->destinataire_id);
                    break;

                case 'quittance_mensuelle':
                    if (empty($request->facture_id)) {
                        return response()->json(['status' => false, 'message' => 'Veuillez sélectionner une facture.']);
                    }
                    $pdf = $pdfService->genererQuittanceMensuelle((int) $request->facture_id);
                    break;

                case 'quittance_caution':
                    $pdf = $pdfService->genererQuittanceCaution((int) $request->destinataire_id);
                    break;

                case 'releve_proprietaire':
                    if (empty($request->date_debut) || empty($request->date_fin)) {
                        return response()->json(['status' => false, 'message' => 'Veuillez saisir les dates du relevé.']);
                    }
                    $pct = $request->pourcentage ?? 10;
                    $pdf = $pdfService->genererReleveProprietaire(
                        (int) $request->destinataire_id,
                        $request->date_debut,
                        $request->date_fin,
                        (float) $pct
                    );
                    break;

                case 'releve_agence':
                    if (empty($request->date_debut) || empty($request->date_fin)) {
                        return response()->json(['status' => false, 'message' => 'Veuillez saisir les dates du relevé.']);
                    }
                    $pct = $request->pourcentage ?? 10;
                    $pdf = $pdfService->genererReleveAgence(
                        (int) $request->destinataire_id,
                        $request->date_debut,
                        $request->date_fin,
                        (float) $pct
                    );
                    break;

                default:
                    return response()->json(['status' => false, 'message' => 'Type de document non reconnu.']);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Erreur lors de la génération du PDF : ' . $e->getMessage(),
            ]);
        }

        // Récupérer infos agence
        $agence     = get_annexe_details_for_invoice(get_active_annexe_id());
        $agenceNom  = $agence['designation'] ?? 'Agence Immobilière';

        $statut       = 'success';
        $messageErreur = null;
        $tempPath     = null;

        // Envoyer
        if ($request->methode_envoi === 'email') {
            try {
                Mail::to($destinataireContact)->send(new DocumentMail([
                    'destinataire_nom'     => $destinataireNom,
                    'destinataire_email'   => $destinataireContact,
                    'type_document_label'  => $pdf['label'],
                    'message_personnalise' => $request->message_personnalise ?? '',
                    'pdf_content'          => $pdf['content'],
                    'pdf_filename'         => $pdf['filename'],
                    'agence_nom'           => $agenceNom,
                    'email_envoi'          => $parametre->email_envoi,
                ]));
            } catch (\Exception $e) {
                $statut        = 'error';
                $messageErreur = $e->getMessage();
            }
        } else {
            $whatsapp = new WhatsAppService();
            $result   = $whatsapp->envoyerPdf(
                $pdf['content'],
                $pdf['filename'],
                $destinataireContact,
                ($request->message_personnalise ?? $pdf['label'])
            );

            if (!$result['success']) {
                $statut        = 'error';
                $messageErreur = $result['message'];
            }
            $tempPath = $result['temp_path'] ?? null;
        }

        // Journaliser
        EnvoiDocument::create([
            'iddirection_ref'      => Auth::user()->iddirection_ref,
            'destinataire_type'    => $request->destinataire_type,
            'destinataire_id'      => $request->destinataire_id,
            'destinataire_nom'     => $destinataireNom,
            'destinataire_contact' => $destinataireContact,
            'type_document'        => $request->type_document,
            'document_ref_id'      => $request->facture_id ?? null,
            'methode_envoi'        => $request->methode_envoi,
            'statut'               => $statut,
            'message_erreur'       => $messageErreur,
            'pdf_temp_path'        => $tempPath,
            'message_personnalise' => $request->message_personnalise,
            'envoye_par'           => Auth::id(),
        ]);

        if ($statut === 'success') {
            return response()->json([
                'status'  => true,
                'message' => "Document envoyé avec succès via " . ($request->methode_envoi === 'email' ? 'Email' : 'WhatsApp') . " à {$destinataireNom}.",
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Échec de l\'envoi : ' . $messageErreur,
        ]);
    }

    public function historique()
    {
        $envois = EnvoiDocument::where('iddirection_ref', Auth::user()->iddirection_ref)
            ->orderBy('created_at', 'desc')
            ->limit(200)
            ->get();

        return response()->json(['status' => true, 'data' => $envois]);
    }
}
