<?php

namespace App\Http\Controllers;

use App\Direction;
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
        // Accepter JSON ou form-data
        $input = $request->isJson() ? $request->json()->all() : $request->all();

        $validator = Validator::make($input, [
            'methode_envoi'          => 'required|in:email,whatsapp',
            'type_documents'         => 'required|array|min:1',
            'type_documents.*'       => 'in:contrat,quittance_mensuelle,quittance_caution,releve_proprietaire,releve_agence',
            'destinataires'          => 'required|array|min:1',
            'destinataires.*.type'   => 'required|in:locataire,proprietaire',
            'destinataires.*.id'     => 'required|integer',
            'destinataires.*.contact'=> 'nullable|string',
            'message_personnalise'   => 'nullable|string|max:1000',
            'date_debut'             => 'nullable|date',
            'date_fin'               => 'nullable|date',
            'pourcentage'            => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => implode(' ', $validator->errors()->all()),
            ]);
        }

        $methodeEnvoi   = $input['methode_envoi'];
        $typeDocuments  = $input['type_documents'];
        $destinatairesInput = $input['destinataires'];
        $msgPerso       = $input['message_personnalise'] ?? '';
        $dateDebut      = $input['date_debut'] ?? null;
        $dateFin        = $input['date_fin']   ?? null;
        $pourcentage    = isset($input['pourcentage']) ? (float) $input['pourcentage'] : 10;

        $parametre = Parametre::where('iddirection_ref', Auth::user()->iddirection_ref)->first();

        // Vérifier config selon méthode
        if ($methodeEnvoi === 'email') {
            if (!$parametre || empty($parametre->email_envoi)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Email d\'envoi non configuré. Veuillez le définir dans Paramétrage > Communication.',
                ]);
            }
        } elseif ($methodeEnvoi === 'whatsapp') {
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

        // Récupérer infos agence
        $agence    = get_annexe_details_for_invoice(get_active_annexe_id());
        $agenceNom = $agence['designation'] ?? 'Agence Immobilière';

        $pdfService = new PdfGeneratorService();
        $whatsapp   = $methodeEnvoi === 'whatsapp' ? new WhatsAppService() : null;

        $totalEnvois = 0;
        $reussis     = 0;
        $details     = [];

        // Boucle : destinataire × document
        foreach ($destinatairesInput as $destInput) {
            $destType = $destInput['type'];
            $destId   = (int) $destInput['id'];
            $contact  = trim($destInput['contact'] ?? '');

            // Récupérer le modèle destinataire
            if ($destType === 'locataire') {
                $destinataire = Locataire::find($destId);
            } else {
                $destinataire = Proprietaire::find($destId);
            }

            if (!$destinataire) {
                $details[] = [
                    'destinataire' => "#{$destId} ({$destType})",
                    'document'     => 'Tous',
                    'statut'       => 'error',
                    'erreur'       => 'Destinataire introuvable.',
                ];
                continue;
            }

            $destinataireNom = trim($destinataire->nom . ' ' . $destinataire->prenom);

            // Vérifier le contact
            if (empty($contact)) {
                $champ = $methodeEnvoi === 'email' ? 'email' : 'téléphone';
                $details[] = [
                    'destinataire' => $destinataireNom,
                    'document'     => 'Tous',
                    'statut'       => 'error',
                    'erreur'       => "Le {$champ} est vide.",
                ];
                continue;
            }

            foreach ($typeDocuments as $typeDoc) {

                // Pour quittance_mensuelle : boucle sur chaque facture sélectionnée
                if ($typeDoc === 'quittance_mensuelle') {
                    $factureIds = array_values(array_filter(
                        array_map('intval', $destInput['facture_ids'] ?? [])
                    ));
                    if (empty($factureIds)) {
                        $totalEnvois++;
                        $details[] = [
                            'destinataire' => $destinataireNom,
                            'document'     => 'quittance_mensuelle',
                            'statut'       => 'error',
                            'erreur'       => 'Aucun mois sélectionné.',
                        ];
                        EnvoiDocument::create([
                            'iddirection_ref'      => Auth::user()->iddirection_ref,
                            'destinataire_type'    => $destType,
                            'destinataire_id'      => $destId,
                            'destinataire_nom'     => $destinataireNom,
                            'destinataire_contact' => $contact,
                            'type_document'        => 'quittance_mensuelle',
                            'methode_envoi'        => $methodeEnvoi,
                            'statut'               => 'error',
                            'message_erreur'       => 'Aucun mois sélectionné.',
                            'message_personnalise' => $msgPerso ?: null,
                            'envoye_par'           => Auth::id(),
                        ]);
                        continue;
                    }
                    $iterIds = $factureIds;
                } else {
                    $iterIds = [null]; // Une seule itération sans facture
                }

                foreach ($iterIds as $factureId) {
                    $totalEnvois++;
                    $statut        = 'success';
                    $messageErreur = null;
                    $tempPath      = null;
                    $docRefId      = $factureId;

                    // Générer le PDF
                    try {
                        switch ($typeDoc) {
                            case 'contrat':
                                $pdf = $pdfService->genererContrat($destId);
                                break;

                            case 'quittance_mensuelle':
                                $pdf = $pdfService->genererQuittanceMensuelle($factureId);
                                break;

                            case 'quittance_caution':
                                $pdf = $pdfService->genererQuittanceCaution($destId);
                                break;

                            case 'releve_proprietaire':
                                if (empty($dateDebut) || empty($dateFin)) {
                                    throw new \Exception('Les dates du relevé sont requises.');
                                }
                                $pdf = $pdfService->genererReleveProprietaire($destId, $dateDebut, $dateFin, $pourcentage);
                                break;

                            case 'releve_agence':
                                if (empty($dateDebut) || empty($dateFin)) {
                                    throw new \Exception('Les dates du relevé sont requises.');
                                }
                                $pdf = $pdfService->genererReleveAgence($destId, $dateDebut, $dateFin, $pourcentage);
                                break;

                            default:
                                throw new \Exception('Type de document non reconnu : ' . $typeDoc);
                        }
                    } catch (\Exception $e) {
                        $statut        = 'error';
                        $messageErreur = 'Génération PDF : ' . $e->getMessage();

                        EnvoiDocument::create([
                            'iddirection_ref'      => Auth::user()->iddirection_ref,
                            'destinataire_type'    => $destType,
                            'destinataire_id'      => $destId,
                            'destinataire_nom'     => $destinataireNom,
                            'destinataire_contact' => $contact,
                            'type_document'        => $typeDoc,
                            'document_ref_id'      => $docRefId,
                            'methode_envoi'        => $methodeEnvoi,
                            'statut'               => 'error',
                            'message_erreur'       => $messageErreur,
                            'pdf_temp_path'        => null,
                            'message_personnalise' => $msgPerso ?: null,
                            'envoye_par'           => Auth::id(),
                        ]);

                        $details[] = [
                            'destinataire' => $destinataireNom,
                            'document'     => $typeDoc,
                            'statut'       => 'error',
                            'erreur'       => $messageErreur,
                        ];
                        continue;
                    }

                    // Envoyer
                    if ($methodeEnvoi === 'email') {
                        try {
                            Mail::to($contact)->send(new DocumentMail([
                                'destinataire_nom'     => $destinataireNom,
                                'destinataire_email'   => $contact,
                                'type_document_label'  => $pdf['label'],
                                'message_personnalise' => $msgPerso,
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
                        $result = $whatsapp->envoyerPdf(
                            $pdf['content'],
                            $pdf['filename'],
                            $contact,
                            ($msgPerso ?: $pdf['label'])
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
                        'destinataire_type'    => $destType,
                        'destinataire_id'      => $destId,
                        'destinataire_nom'     => $destinataireNom,
                        'destinataire_contact' => $contact,
                        'type_document'        => $typeDoc,
                        'document_ref_id'      => $docRefId,
                        'methode_envoi'        => $methodeEnvoi,
                        'statut'               => $statut,
                        'message_erreur'       => $messageErreur,
                        'pdf_temp_path'        => $tempPath,
                        'message_personnalise' => $msgPerso ?: null,
                        'envoye_par'           => Auth::id(),
                    ]);

                    if ($statut === 'success') {
                        $reussis++;
                    }

                    $details[] = [
                        'destinataire' => $destinataireNom,
                        'document'     => $typeDoc,
                        'statut'       => $statut,
                        'erreur'       => $messageErreur,
                    ];
                } // fin foreach $iterIds
            }
        }

        $echouees = $totalEnvois - $reussis;
        $message  = "{$reussis} envoi(s) réussi(s) sur {$totalEnvois}.";
        if ($echouees > 0) {
            $message .= " {$echouees} échec(s).";
        }

        return response()->json([
            'status'  => $reussis > 0,
            'message' => $message,
            'details' => $details,
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
