<?php

namespace App\Http\Controllers;

use App\Direction;
use App\EnvoiDocument;
use App\Facture;
use App\Locataire;
use App\Parametre;
use App\Plan;
use App\Proprietaire;
use App\Services\PdfGeneratorService;
use App\Services\AfricasTalkingService;
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
                'locataires.prix_mois',
                'locataires.date_entree',
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

        $parametre  = Parametre::where('iddirection_ref', Auth::user()->iddirection_ref)->first();
        $platformCfg = \App\PlatformConfig::getConfig();
        $atConnecte = !empty($platformCfg->at_username) && !empty($platformCfg->at_api_key);
        $waConnecte = $atConnecte && !empty($platformCfg->at_whatsapp_product_id);

        return view('documents.envoyer', compact('locataires', 'facturesParLocataire', 'proprietaires', 'parametre', 'atConnecte', 'waConnecte'));
    }

    public function envoyer(Request $request)
    {
        // Accepter JSON ou form-data
        $input = $request->isJson() ? $request->json()->all() : $request->all();

        $validator = Validator::make($input, [
            'methode_envoi'           => 'required|in:email,whatsapp',
            'type_documents'          => 'required|array|min:1',
            'type_documents.*'        => 'in:contrat,quittance_mensuelle,quittance_caution,releve_proprietaire,releve_agence',
            'destinataires'           => 'required|array|min:1',
            'destinataires.*.type'    => 'required|in:locataire,proprietaire',
            'destinataires.*.id'      => 'required|integer',
            'destinataires.*.contact' => 'nullable|string',
            'message_personnalise'    => 'nullable|string|max:1000',
            'date_debut'              => 'nullable|date',
            'date_fin'                => 'nullable|date',
            'pourcentage'             => 'nullable|numeric|min:0|max:100',
            'payment_transaction_id'  => 'nullable|string',
            'country_code'            => 'nullable|string|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => implode(' ', $validator->errors()->all()),
            ]);
        }

        set_time_limit(300);

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
            if (!(new WhatsAppService())->estConnecte()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'WhatsApp non configuré. Renseignez le Token et le Phone Number ID dans Paramétrage > Communication.',
                ]);
            }
        }

        // Vérification paiement pour WhatsApp (pay-per-use)
        if ($methodeEnvoi === 'whatsapp') {
            $txnId = $input['payment_transaction_id'] ?? null;
            if (empty($txnId)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Paiement requis avant l\'envoi WhatsApp. Veuillez procéder au paiement.',
                ]);
            }
            $paymentCheck = $this->verifierPaiementMessaging($txnId);
            if (!$paymentCheck['success']) {
                return response()->json([
                    'status'  => false,
                    'message' => $paymentCheck['message'],
                ]);
            }
            // Log la transaction
            $countryCode   = strtoupper($input['country_code'] ?? 'BJ');
            $recipientCount = count($destinatairesInput);
            $rate = \App\MessagingRate::getForCountry($countryCode);
            \App\MessagingTransaction::create([
                'iddirection_ref'       => Auth::user()->iddirection_ref,
                'channel'               => 'whatsapp',
                'recipient_count'       => $recipientCount,
                'unit_cost'             => $rate ? $rate->whatsapp_unit_cost : 0,
                'total_amount'          => $rate ? $rate->whatsapp_unit_cost * $recipientCount : 0,
                'currency'              => $rate ? $rate->currency : 'XOF',
                'country_code'          => $countryCode,
                'payment_provider'      => \App\PlatformConfig::getConfig()->getActiveProvider(),
                'payment_transaction_id'=> $txnId,
                'payment_status'        => 'paid',
                'messages_sent'         => false,
            ]);
        }

        // Récupérer infos agence
        $agence    = get_annexe_details_for_invoice(get_active_annexe_id());
        $agenceNom = $agence['designation'] ?? 'Agence Immobilière';

        $pdfService = new PdfGeneratorService();
        $whatsapp   = $methodeEnvoi === 'whatsapp' ? new WhatsAppService() : null;

        // Pré-charger tous les destinataires en une seule requête
        $locataireIds    = [];
        $proprietaireIds = [];
        foreach ($destinatairesInput as $d) {
            if ($d['type'] === 'locataire') $locataireIds[]    = (int) $d['id'];
            else                             $proprietaireIds[] = (int) $d['id'];
        }
        $locatairesMap    = !empty($locataireIds)
            ? Locataire::whereIn('id', $locataireIds)->get()->keyBy('id')
            : collect();
        $proprietairesMap = !empty($proprietaireIds)
            ? Proprietaire::whereIn('id', $proprietaireIds)->get()->keyBy('id')
            : collect();

        $totalEnvois = 0;
        $reussis     = 0;
        $details     = [];
        $logEntries  = [];
        $now         = now();

        // Boucle : destinataire × document
        foreach ($destinatairesInput as $destInput) {
            $destType = $destInput['type'];
            $destId   = (int) $destInput['id'];
            $contact  = trim($destInput['contact'] ?? '');

            // Récupérer le modèle depuis le cache en mémoire
            $destinataire = $destType === 'locataire'
                ? ($locatairesMap[$destId] ?? null)
                : ($proprietairesMap[$destId] ?? null);

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
                        $logEntries[] = [
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
                            'created_at'           => $now,
                            'updated_at'           => $now,
                        ];
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
                        $logEntries[]  = [
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
                            'created_at'           => $now,
                            'updated_at'           => $now,
                        ];
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

                    if ($statut === 'success') {
                        $reussis++;
                    }

                    $logEntries[] = [
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
                        'created_at'           => $now,
                        'updated_at'           => $now,
                    ];

                    $details[] = [
                        'destinataire' => $destinataireNom,
                        'document'     => $typeDoc,
                        'statut'       => $statut,
                        'erreur'       => $messageErreur,
                    ];
                } // fin foreach $iterIds
            }
        }

        // Journaliser tous les envois en une seule requête
        if (!empty($logEntries)) {
            \Illuminate\Support\Facades\DB::table('envois_documents')->insert($logEntries);
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

    public function envoyerNotification(Request $request)
    {
        $input = $request->isJson() ? $request->json()->all() : $request->all();

        $validator = Validator::make($input, [
            'type_notification'       => 'required|in:rappel_loyer,preavis',
            'methode_envoi'           => 'required|in:email,whatsapp,sms',
            'destinataires'           => 'required|array|min:1',
            'destinataires.*.id'      => 'required|integer',
            'destinataires.*.contact' => 'nullable|string',
            'date_fin_bail'           => 'required_if:type_notification,preavis|nullable|date',
            'message_personnalise'    => 'nullable|string|max:2000',
            'payment_transaction_id'  => 'nullable|string',
            'country_code'            => 'nullable|string|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => implode(' ', $validator->errors()->all()),
            ]);
        }

        $typeNotif          = $input['type_notification'];
        $methodeEnvoi       = $input['methode_envoi'];
        $destinatairesInput = $input['destinataires'];
        $msgPerso           = $input['message_personnalise'] ?? '';
        $dateFin            = $input['date_fin_bail'] ?? null;

        $parametre = Parametre::where('iddirection_ref', Auth::user()->iddirection_ref)->first();

        // ── Vérification des limites du plan ─────────────────────────────
        $direction = Direction::find(Auth::user()->iddirection_ref);
        $plan      = $direction ? Plan::find($direction->idplan_ref) : null;

        if ($plan) {
            $debutMois  = Carbon::now()->startOfMonth();
            $nbDemandes = count($destinatairesInput);

            if ($typeNotif === 'rappel_loyer' && $plan->max_rappels_loyer !== null) {
                if ($plan->max_rappels_loyer === 0) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Votre plan ne permet pas l\'envoi de rappels de loyer. Veuillez upgrader votre abonnement.',
                    ]);
                }
                $dejaSent = EnvoiDocument::where('iddirection_ref', Auth::user()->iddirection_ref)
                    ->where('type_document', 'rappel_loyer')
                    ->where('statut', 'success')
                    ->where('created_at', '>=', $debutMois)
                    ->count();
                if ($dejaSent + $nbDemandes > $plan->max_rappels_loyer) {
                    $restant = max(0, $plan->max_rappels_loyer - $dejaSent);
                    return response()->json([
                        'status'  => false,
                        'message' => "Limite du plan atteinte : vous pouvez envoyer encore {$restant} rappel(s) de loyer ce mois-ci (quota : {$plan->max_rappels_loyer}/mois, déjà envoyés : {$dejaSent}).",
                    ]);
                }
            }

            if ($typeNotif === 'preavis' && $plan->max_preavis !== null) {
                if ($plan->max_preavis === 0) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Votre plan ne permet pas l\'envoi de préavis. Veuillez upgrader votre abonnement.',
                    ]);
                }
                $dejaSent = EnvoiDocument::where('iddirection_ref', Auth::user()->iddirection_ref)
                    ->where('type_document', 'preavis')
                    ->where('statut', 'success')
                    ->where('created_at', '>=', $debutMois)
                    ->count();
                if ($dejaSent + $nbDemandes > $plan->max_preavis) {
                    $restant = max(0, $plan->max_preavis - $dejaSent);
                    return response()->json([
                        'status'  => false,
                        'message' => "Limite du plan atteinte : vous pouvez envoyer encore {$restant} préavis ce mois-ci (quota : {$plan->max_preavis}/mois, déjà envoyés : {$dejaSent}).",
                    ]);
                }
            }
        }
        // ─────────────────────────────────────────────────────────────────

        // Vérifier si le plan autorise SMS/WhatsApp
        if ($plan) {
            if ($methodeEnvoi === 'sms' && isset($plan->sms_enabled) && !$plan->sms_enabled) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Votre plan ne permet pas l\'envoi de SMS. Veuillez upgrader votre abonnement.',
                ]);
            }
            if ($methodeEnvoi === 'whatsapp' && isset($plan->whatsapp_enabled) && !$plan->whatsapp_enabled) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Votre plan ne permet pas l\'envoi via WhatsApp. Veuillez upgrader votre abonnement.',
                ]);
            }
        }

        // Vérifier config selon méthode
        if ($methodeEnvoi === 'email') {
            if (!$parametre || empty($parametre->email_envoi)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Email d\'envoi non configuré. Veuillez le définir dans Paramétrage > Communication.',
                ]);
            }
        } elseif ($methodeEnvoi === 'whatsapp') {
            if (!(new AfricasTalkingService())->whatsappConnecte()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'WhatsApp non configuré. Renseignez le Username, l\'API Key et le Product ID WhatsApp Africa\'s Talking dans Paramétrage > Communication.',
                ]);
            }
        } elseif ($methodeEnvoi === 'sms') {
            if (!(new AfricasTalkingService())->estConnecte()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'SMS non configuré. Renseignez le Username et l\'API Key Africa\'s Talking dans Paramétrage > Communication.',
                ]);
            }
        }

        // Vérification paiement pour SMS et WhatsApp (pay-per-use)
        if (in_array($methodeEnvoi, ['sms', 'whatsapp'])) {
            $txnId = $input['payment_transaction_id'] ?? null;
            if (empty($txnId)) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Paiement requis avant l\'envoi ' . strtoupper($methodeEnvoi) . '. Veuillez procéder au paiement.',
                ]);
            }
            $paymentCheck = $this->verifierPaiementMessaging($txnId);
            if (!$paymentCheck['success']) {
                return response()->json([
                    'status'  => false,
                    'message' => $paymentCheck['message'],
                ]);
            }
            // Log la transaction
            $countryCode    = strtoupper($input['country_code'] ?? 'BJ');
            $recipientCount = count($destinatairesInput);
            $rate = \App\MessagingRate::getForCountry($countryCode);
            $unitCost = $methodeEnvoi === 'whatsapp'
                ? ($rate ? $rate->whatsapp_unit_cost : 0)
                : ($rate ? $rate->sms_unit_cost : 0);
            \App\MessagingTransaction::create([
                'iddirection_ref'        => Auth::user()->iddirection_ref,
                'channel'                => $methodeEnvoi,
                'recipient_count'        => $recipientCount,
                'unit_cost'              => $unitCost,
                'total_amount'           => $unitCost * $recipientCount,
                'currency'               => $rate ? $rate->currency : 'XOF',
                'country_code'           => $countryCode,
                'payment_provider'       => \App\PlatformConfig::getConfig()->getActiveProvider(),
                'payment_transaction_id' => $txnId,
                'payment_status'         => 'paid',
                'messages_sent'          => false,
            ]);
        }

        set_time_limit(300);

        $agence    = get_annexe_details_for_invoice(get_active_annexe_id());
        $agenceNom = $agence['designation'] ?? 'Agence Immobilière';

        $whatsapp  = $methodeEnvoi === 'whatsapp' ? new WhatsAppService() : null;
        $atService = $methodeEnvoi === 'sms' ? new AfricasTalkingService() : null;

        $moisCourant      = Carbon::now()->locale('fr')->isoFormat('MMMM YYYY');
        $dateFinFormatted = $dateFin ? Carbon::parse($dateFin)->locale('fr')->isoFormat('D MMMM YYYY') : null;

        // Pré-charger tous les locataires avec leurs jointures en une seule requête
        $destIds      = array_column($destinatairesInput, 'id');
        $locatairesMap = Locataire::join('maisons', 'locataires.maison_id', '=', 'maisons.id')
            ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
            ->whereIn('locataires.id', $destIds)
            ->select(
                'locataires.id',
                'locataires.nom',
                'locataires.prenom',
                'locataires.prix_mois',
                'maisons.nom_maison',
                'chambres.numero_chambre',
                'chambres.type_chambre'
            )
            ->get()
            ->keyBy('id');

        $totalEnvois = 0;
        $reussis     = 0;
        $details     = [];
        $logEntries  = [];
        $now         = now();

        foreach ($destinatairesInput as $destInput) {
            $totalEnvois++;
            $destId  = (int) $destInput['id'];
            $contact = trim($destInput['contact'] ?? '');

            $locataire = $locatairesMap[$destId] ?? null;

            if (!$locataire) {
                $details[] = ['destinataire' => "#{$destId}", 'statut' => 'error', 'erreur' => 'Locataire introuvable.'];
                continue;
            }

            $destinataireNom = trim($locataire->nom . ' ' . $locataire->prenom);
            $logement        = $locataire->nom_maison . ' / ' . $locataire->type_chambre . ' N°' . $locataire->numero_chambre;
            $montantLoyer    = number_format((float) $locataire->prix_mois, 0, ',', ' ');

            if (empty($contact)) {
                $champ = $methodeEnvoi === 'email' ? 'email' : 'téléphone';
                $details[] = ['destinataire' => $destinataireNom, 'statut' => 'error', 'erreur' => "Le {$champ} est vide."];
                $logEntries[] = [
                    'iddirection_ref'      => Auth::user()->iddirection_ref,
                    'destinataire_type'    => 'locataire',
                    'destinataire_id'      => $destId,
                    'destinataire_nom'     => $destinataireNom,
                    'destinataire_contact' => null,
                    'type_document'        => $typeNotif,
                    'methode_envoi'        => $methodeEnvoi,
                    'statut'               => 'error',
                    'message_erreur'       => "Le {$champ} est vide.",
                    'message_personnalise' => $msgPerso ?: null,
                    'envoye_par'           => Auth::id(),
                    'created_at'           => $now,
                    'updated_at'           => $now,
                ];
                continue;
            }

            // Construire le message WhatsApp
            if ($typeNotif === 'rappel_loyer') {
                $sujet     = 'Rappel de paiement de loyer – ' . $moisCourant;
                $messageWA = "Bonjour {$destinataireNom},\n\n"
                    . "Nous vous rappelons que votre loyer du mois de {$moisCourant} d'un montant de {$montantLoyer} XOF est dû.\n\n"
                    . "Merci de bien vouloir procéder au règlement dans les meilleurs délais.\n\n"
                    . ($msgPerso ? "{$msgPerso}\n\n" : '')
                    . "Cordialement,\n{$agenceNom}";
            } else {
                $sujet     = 'Préavis de fin de bail';
                $messageWA = "Bonjour {$destinataireNom},\n\n"
                    . "Nous vous informons que votre contrat de bail pour le logement {$logement} prend fin le {$dateFinFormatted}.\n\n"
                    . "Conformément aux termes de votre contrat, vous êtes prié(e) de libérer les lieux et de restituer les clés avant cette date.\n\n"
                    . ($msgPerso ? "{$msgPerso}\n\n" : '')
                    . "Cordialement,\n{$agenceNom}";
            }

            $statut        = 'success';
            $messageErreur = null;

            if ($methodeEnvoi === 'email') {
                try {
                    Mail::to($contact)->send(new \App\Mail\NotificationMail([
                        'type_notification'    => $typeNotif,
                        'destinataire_nom'     => $destinataireNom,
                        'destinataire_email'   => $contact,
                        'sujet'                => $sujet,
                        'mois_courant'         => $moisCourant,
                        'montant_loyer'        => $montantLoyer,
                        'logement'             => $logement,
                        'date_fin_bail'        => $dateFinFormatted,
                        'message_personnalise' => $msgPerso,
                        'agence_nom'           => $agenceNom,
                        'email_envoi'          => $parametre->email_envoi,
                    ]));
                } catch (\Exception $e) {
                    $statut        = 'error';
                    $messageErreur = $e->getMessage();
                }
            } elseif ($methodeEnvoi === 'whatsapp') {
                $result = $whatsapp->envoyerTexte($contact, $messageWA);
                if (!$result['success']) {
                    $statut        = 'error';
                    $messageErreur = $result['message'];
                }
            } else {
                // SMS via Africa's Talking
                $result = $atService->envoyerSMS($contact, $messageWA);
                if (!$result['success']) {
                    $statut        = 'error';
                    $messageErreur = $result['message'];
                }
            }

            if ($statut === 'success') {
                $reussis++;
            }

            $logEntries[] = [
                'iddirection_ref'      => Auth::user()->iddirection_ref,
                'destinataire_type'    => 'locataire',
                'destinataire_id'      => $destId,
                'destinataire_nom'     => $destinataireNom,
                'destinataire_contact' => $contact,
                'type_document'        => $typeNotif,
                'methode_envoi'        => $methodeEnvoi,
                'statut'               => $statut,
                'message_erreur'       => $messageErreur,
                'message_personnalise' => $msgPerso ?: null,
                'envoye_par'           => Auth::id(),
                'created_at'           => $now,
                'updated_at'           => $now,
            ];

            $details[] = [
                'destinataire' => $destinataireNom,
                'statut'       => $statut,
                'erreur'       => $messageErreur,
            ];
        }

        // Journaliser tous les envois en une seule requête
        if (!empty($logEntries)) {
            \Illuminate\Support\Facades\DB::table('envois_documents')->insert($logEntries);
        }

        $echouees = $totalEnvois - $reussis;
        $message  = "{$reussis} notification(s) envoyée(s) sur {$totalEnvois}.";
        if ($echouees > 0) {
            $message .= " {$echouees} échec(s).";
        }

        return response()->json([
            'status'  => $reussis > 0,
            'message' => $message,
            'details' => $details,
        ]);
    }

    /**
     * Vérifie un paiement via le prestataire actif (KKiaPay ou FedaPay).
     */
    private function verifierPaiementMessaging(string $txnId): array
    {
        $cfg      = \App\PlatformConfig::getConfig();
        $provider = $cfg->getActiveProvider();

        if ($provider === 'kkiapay') {
            $svc    = new \App\Services\KkiapayService(
                $cfg->kkiapay_public_key,
                $cfg->kkiapay_private_key,
                $cfg->kkiapay_secret_key,
                (bool) $cfg->kkiapay_sandbox
            );
            $result = $svc->verifyTransaction($txnId);
        } elseif ($provider === 'fedapay') {
            $svc    = new \App\Services\FedapayService($cfg->fedapay_secret_key, (bool) $cfg->fedapay_sandbox);
            $result = $svc->verifyTransaction($txnId);
        } else {
            return ['success' => false, 'message' => 'Aucun prestataire de paiement configuré.'];
        }

        if (!$result['success']) {
            return ['success' => false, 'message' => 'Paiement non vérifié ou invalide.'];
        }

        return ['success' => true, 'amount' => $result['amount']];
    }
}
