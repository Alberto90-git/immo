<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\EtatDesLieux;
use App\EtatDesLieuxPiece;
use App\EtatDesLieuxElement;
use App\EtatDesLieuxPhoto;
use App\Locataire;
use App\Maison;
use App\Chambre;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class EtatDesLieuxController extends Controller
{
    // Classement qualité (du plus mauvais au meilleur)
    const QUALITE = ['hors_service' => 1, 'mauvais' => 2, 'moyen' => 3, 'bon' => 4, 'tres_bon' => 5];

    const ELEMENTS_PAR_DEFAUT = [
        'Sol', 'Murs', 'Plafond', 'Portes & Fenêtres', 'Équipements', 'Électricité',
    ];

    const PIECES_PAR_DEFAUT = [
        'Entrée / Couloir', 'Salon / Séjour', 'Cuisine',
        'Chambre 1', 'Chambre 2', 'Salle de bain', 'WC', 'Terrasse / Balcon',
    ];

    // ─── Liste ────────────────────────────────────────────────────────────────

    public function index()
    {
        $idannexe_ref = get_active_annexe_id();

        $etats = EtatDesLieux::with(['locataire', 'maison', 'chambre'])
            ->whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
            ->when(Gate::none(['Is_admin']), fn($q) => $q->where('idannexe_ref', Auth::user()->idannexe_ref))
            ->orderByDesc('date_etat')
            ->get();

        $locataires = Locataire::whereNull('delete_at')
            ->where('status', true)
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
            ->when(Gate::none(['Is_admin']), fn($q) => $q->where('idannexe_ref', Auth::user()->idannexe_ref))
            ->orderBy('nom')
            ->get();

        $pieces_defaut   = self::PIECES_PAR_DEFAUT;
        $elements_defaut = self::ELEMENTS_PAR_DEFAUT;

        return view('etat_des_lieux.index', compact('etats', 'locataires', 'pieces_defaut', 'elements_defaut'));
    }

    // ─── Formulaire création ──────────────────────────────────────────────────

    public function create()
    {
        $idannexe_ref = get_active_annexe_id();

        $locataires = Locataire::whereNull('delete_at')
            ->where('status', true)
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
            ->when(Gate::none(['Is_admin']), fn($q) => $q->where('idannexe_ref', Auth::user()->idannexe_ref))
            ->orderBy('nom')
            ->get();

        $pieces_defaut   = self::PIECES_PAR_DEFAUT;
        $elements_defaut = self::ELEMENTS_PAR_DEFAUT;

        return view('etat_des_lieux.create', compact('locataires', 'pieces_defaut', 'elements_defaut'));
    }

    // ─── Chargement données entrée (AJAX) ────────────────────────────────────

    public function getEntreeData(Request $request)
    {
        $locataire = Locataire::whereNull('delete_at')
            ->where('id', $request->locataire_id)
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->first();

        if (!$locataire) {
            return response()->json(['status' => false, 'message' => 'Locataire introuvable.']);
        }

        $etatEntree = EtatDesLieux::with(['pieces.elements'])
            ->whereNull('delete_at')
            ->where('locataire_id', $locataire->id)
            ->where('type', 'entree')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->orderByDesc('date_etat')
            ->first();

        if (!$etatEntree) {
            return response()->json(['status' => false, 'message' => 'Aucun état d\'entrée trouvé pour ce locataire.']);
        }

        $pieces = $etatEntree->pieces->map(function ($piece) {
            return [
                'nom_piece' => $piece->nom_piece,
                'elements'  => $piece->elements->keyBy('element')->map(fn($el) => [
                    'etat'        => $el->etat,
                    'etat_label'  => $el->etat_label,
                    'description' => $el->description,
                ]),
            ];
        });

        return response()->json([
            'status'         => true,
            'etat_entree_id' => $etatEntree->id,
            'date_entree'    => $etatEntree->date_etat->format('d/m/Y'),
            'pieces'         => $pieces,
        ]);
    }

    // ─── Enregistrement ──────────────────────────────────────────────────────

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'locataire_id' => 'required|integer',
            'type'         => 'required|in:entree,sortie',
            'date_etat'    => 'required|date',
            'pieces'       => 'required|array|min:1',
        ], [
            'locataire_id.required' => 'Veuillez sélectionner un locataire.',
            'type.required'         => 'Veuillez choisir le type d\'état des lieux.',
            'date_etat.required'    => 'La date est obligatoire.',
            'pieces.required'       => 'Veuillez renseigner au moins une pièce.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->toArray(),
            ]);
        }

        // Vérification que le locataire appartient bien à la direction
        $locataire = Locataire::whereNull('delete_at')
            ->where('id', $request->locataire_id)
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->first();

        if (!$locataire) {
            return response()->json(['status' => false, 'message' => 'Locataire introuvable.']);
        }

        DB::beginTransaction();
        try {
            $idannexe_ref = get_active_annexe_id() ?? Auth::user()->idannexe_ref;

            $etat = EtatDesLieux::create([
                'iddirection_ref'  => Auth::user()->iddirection_ref,
                'idannexe_ref'     => $idannexe_ref,
                'locataire_id'     => $locataire->id,
                'maison_id'        => $locataire->maison_id,
                'chambre_id'       => $locataire->chambre_id,
                'type'             => $request->type,
                'date_etat'        => $request->date_etat,
                'etat_entree_id'   => $request->etat_entree_id ?: null,
                'statut'           => 'brouillon',
                'notes_generales'  => $request->notes_generales,
                'retenue_caution'  => 0,
                'created_by'       => Auth::id(),
            ]);

            // Données de l'état d'entrée pour comparaison
            $entreeElements = [];
            if ($request->type === 'sortie' && $request->etat_entree_id) {
                $piecesEntree = EtatDesLieuxPiece::with('elements')
                    ->where('etat_des_lieux_id', $request->etat_entree_id)
                    ->get();
                foreach ($piecesEntree as $pe) {
                    foreach ($pe->elements as $el) {
                        $entreeElements[$pe->nom_piece][$el->element] = $el->etat;
                    }
                }
            }

            $totalRetenue = 0;

            foreach ($request->input('pieces', []) as $i => $pieceData) {
                if (empty($pieceData['nom_piece']) || empty($pieceData['inclus'])) {
                    continue;
                }

                $piece = EtatDesLieuxPiece::create([
                    'etat_des_lieux_id' => $etat->id,
                    'nom_piece'         => $pieceData['nom_piece'],
                    'ordre'             => (int) $i,
                ]);

                foreach ($pieceData['elements'] ?? [] as $elementNom => $elementData) {
                    $nouvelEtat      = $elementData['etat'] ?? 'bon';
                    $montantRetenue  = (float) ($elementData['montant_retenue'] ?? 0);
                    $degradation     = false;

                    if ($request->type === 'sortie') {
                        $etatEntreePiece = $entreeElements[$pieceData['nom_piece']][$elementNom] ?? null;
                        if ($etatEntreePiece) {
                            $degradation = (self::QUALITE[$nouvelEtat] ?? 4) < (self::QUALITE[$etatEntreePiece] ?? 4);
                        }
                    }

                    // Si pas de dégradation, on ne retient rien
                    if (!$degradation) {
                        $montantRetenue = 0;
                    }
                    $totalRetenue += $montantRetenue;

                    EtatDesLieuxElement::create([
                        'piece_id'            => $piece->id,
                        'element'             => $elementNom,
                        'etat'                => $nouvelEtat,
                        'description'         => $elementData['description'] ?? null,
                        'degradation_detectee'=> $degradation,
                        'montant_retenue'     => $montantRetenue,
                    ]);
                }
            }

            // Mise à jour retenue totale
            $etat->update(['retenue_caution' => $totalRetenue]);

            DB::commit();

            $etat->load(['locataire', 'maison', 'chambre']);

            return response()->json([
                'status'   => true,
                'redirect' => route('etat_des_lieux.show', encrypt_id($etat->id)),
                'message'  => 'État des lieux créé avec succès.',
                'row'      => [
                    'id'          => $etat->id,
                    'locataire'   => trim(($etat->locataire->nom ?? '–') . ' ' . ($etat->locataire->prenom ?? '')),
                    'maison'      => ($etat->maison->nom_maison ?? '–') . ' – ' . ($etat->chambre->numero_chambre ?? '–'),
                    'type'        => $etat->type,
                    'type_label'  => $etat->type === 'entree' ? 'Entrée' : 'Sortie',
                    'date_etat'   => $etat->date_etat->format('d/m/Y'),
                    'statut_badge'=> $etat->statut_badge,
                    'show_url'    => route('etat_des_lieux.show', encrypt_id($etat->id)),
                    'pdf_url'     => route('etat_des_lieux.pdf', encrypt_id($etat->id)),
                    'can_show'    => Auth::user()->can('Consulter-etat-des-lieux'),
                    'can_pdf'     => Auth::user()->can('download-etat-des-lieux'),
                    'can_delete'  => Auth::user()->can('delete-etat-des-lieux'),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => 'Une erreur est survenue : ' . $e->getMessage()]);
        }
    }

    // ─── Détail ───────────────────────────────────────────────────────────────

    public function show($id)
    {
        $id = decrypt_id($id); abort_if(!$id, 404);
        $etat = EtatDesLieux::with([
            'pieces.elements', 'pieces.photos',
            'locataire', 'maison', 'chambre', 'etatEntree.pieces.elements',
        ])
        ->whereNull('delete_at')
        ->where('iddirection_ref', Auth::user()->iddirection_ref)
        ->findOrFail($id);

        // Construction de la map entrée pour la comparaison (type sortie)
        $entreeMap = [];
        if ($etat->type === 'sortie' && $etat->etatEntree) {
            foreach ($etat->etatEntree->pieces as $pe) {
                foreach ($pe->elements as $el) {
                    $entreeMap[$pe->nom_piece][$el->element] = $el;
                }
            }
        }

        $agence = get_annexe_details_for_invoice($etat->idannexe_ref);

        return view('etat_des_lieux.show', compact('etat', 'entreeMap', 'agence'));
    }

    // ─── Finaliser ────────────────────────────────────────────────────────────

    public function finaliser(Request $request)
    {
        $etat = EtatDesLieux::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->findOrFail($request->id);

        if ($etat->statut !== 'brouillon') {
            return response()->json(['status' => false, 'message' => 'Cet état des lieux est déjà finalisé.']);
        }

        $etat->update(['statut' => 'finalise']);

        return response()->json(['status' => true, 'message' => 'État des lieux finalisé avec succès.']);
    }

    // ─── Preview PDF inline (modal) ──────────────────────────────────────────

    public function previewPDF($id)
    {
        $id = decrypt_id($id); abort_if(!$id, 404);
        $etat = EtatDesLieux::with([
            'pieces.elements', 'pieces.photos',
            'locataire', 'maison', 'chambre', 'etatEntree.pieces.elements',
        ])
        ->whereNull('delete_at')
        ->where('iddirection_ref', Auth::user()->iddirection_ref)
        ->findOrFail($id);

        $entreeMap = [];
        if ($etat->type === 'sortie' && $etat->etatEntree) {
            foreach ($etat->etatEntree->pieces as $pe) {
                foreach ($pe->elements as $el) {
                    $entreeMap[$pe->nom_piece][$el->element] = $el;
                }
            }
        }

        $agence = get_annexe_details_for_invoice($etat->idannexe_ref);

        $pdf = PDF::loadView('pdf.etat_des_lieux', compact('etat', 'entreeMap', 'agence'))
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'DejaVu Sans', 'isRemoteEnabled' => true]);

        $nomFichier = 'Etat_des_lieux_' . $etat->type . '_' . $etat->locataire->nom . '_' . $etat->date_etat->format('d-m-Y') . '.pdf';

        return $pdf->stream($nomFichier);
    }

    // ─── Téléchargement PDF ──────────────────────────────────────────────────

    public function telechargerPDF($id)
    {
        $id = decrypt_id($id); abort_if(!$id, 404);
        $etat = EtatDesLieux::with([
            'pieces.elements', 'pieces.photos',
            'locataire', 'maison', 'chambre', 'etatEntree.pieces.elements',
        ])
        ->whereNull('delete_at')
        ->where('iddirection_ref', Auth::user()->iddirection_ref)
        ->findOrFail($id);

        $entreeMap = [];
        if ($etat->type === 'sortie' && $etat->etatEntree) {
            foreach ($etat->etatEntree->pieces as $pe) {
                foreach ($pe->elements as $el) {
                    $entreeMap[$pe->nom_piece][$el->element] = $el;
                }
            }
        }

        $agence = get_annexe_details_for_invoice($etat->idannexe_ref);

        $pdf = PDF::loadView('pdf.etat_des_lieux', compact('etat', 'entreeMap', 'agence'))
            ->setPaper('a4', 'portrait')
            ->setOptions(['defaultFont' => 'DejaVu Sans', 'isRemoteEnabled' => true]);

        $nomFichier = 'Etat_des_lieux_' . $etat->type . '_' . $etat->locataire->nom . '_' . $etat->date_etat->format('d-m-Y') . '.pdf';

        return $pdf->download($nomFichier);
    }

    // ─── Envoi lien de signature ─────────────────────────────────────────────

    public function envoyerSignature(Request $request)
    {
        $etat = EtatDesLieux::with('locataire')
            ->whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->findOrFail($request->id);

        if ($etat->statut === 'brouillon') {
            return response()->json(['status' => false, 'message' => 'Finalisez d\'abord l\'état des lieux avant d\'envoyer pour signature.']);
        }

        if ($etat->signe_locataire) {
            return response()->json(['status' => false, 'message' => 'Le locataire a déjà signé cet état des lieux.']);
        }

        // Générer token unique
        $token = Str::random(64);
        $etat->update(['signature_token' => $token]);

        $lien = route('etat_des_lieux.signer', $token);
        $nomLocataire = $etat->locataire->nom . ' ' . $etat->locataire->prenom;
        $agence = get_annexe_details_for_invoice($etat->idannexe_ref);

        $envoye = false;
        $canal   = '';

        // Locale de la direction
        $dirId = Auth::user()->iddirection_ref ?? null;
        App::setLocale(get_direction_locale($dirId));

        // Envoi email si disponible
        if (!empty($etat->locataire->email)) {
            try {
                Mail::send('mail.signature_etat_des_lieux', [
                    'lien'        => $lien,
                    'nom'         => $nomLocataire,
                    'typeLabel'   => $etat->type_label,
                    'dateEtat'    => $etat->date_etat->format('d/m/Y'),
                    'agence'      => $agence['designation'] ?? config('app.name'),
                ], function ($m) use ($etat, $nomLocataire, $agence) {
                    $m->to($etat->locataire->email, $nomLocataire)
                      ->subject(__('mail.signature_edl.subtitle') . ' – ' . ($agence['designation'] ?? config('app.name')));
                });
                $envoye = true;
                $canal   = 'email';
            } catch (\Exception $e) {
                // Continuer même si l'email échoue
            }
        }

        // Envoi SMS si disponible (via Africa's Talking)
        if (!$envoye && !empty($etat->locataire->telephone)) {
            try {
                $smsService = app(\App\Services\AfricasTalkingService::class);
                $message = "Bonjour {$nomLocataire}, veuillez signer votre état des lieux via ce lien : {$lien}";
                $smsService->envoyerSMS($etat->locataire->telephone, $message);
                $envoye = true;
                $canal   = 'sms';
            } catch (\Exception $e) {
                // Continuer même si le SMS échoue
            }
        }

        if (!$envoye) {
            return response()->json([
                'status'  => true,
                'message' => 'Lien généré. Le locataire n\'a pas de contact configuré – copiez le lien manuellement.',
                'lien'    => $lien,
            ]);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Lien de signature envoyé par ' . $canal . '.'
        ]);
    }

    // ─── Page publique de signature ──────────────────────────────────────────

    public function signer($token)
    {
        $etat = EtatDesLieux::with([
            'pieces.elements', 'locataire', 'maison', 'chambre', 'etatEntree.pieces.elements',
        ])
        ->where('signature_token', $token)
        ->whereNotNull('signature_token')
        ->firstOrFail();

        if ($etat->signe_locataire) {
            return view('etat_des_lieux.signer', ['etat' => $etat, 'dejaSigné' => true]);
        }

        $entreeMap = [];
        if ($etat->type === 'sortie' && $etat->etatEntree) {
            foreach ($etat->etatEntree->pieces as $pe) {
                foreach ($pe->elements as $el) {
                    $entreeMap[$pe->nom_piece][$el->element] = $el;
                }
            }
        }

        $agence = get_annexe_details_for_invoice($etat->idannexe_ref);

        return view('etat_des_lieux.signer', compact('etat', 'entreeMap', 'agence', 'token'));
    }

    // ─── Confirmation signature ──────────────────────────────────────────────

    public function signerConfirmer(Request $request, $token)
    {
        $etat = EtatDesLieux::where('signature_token', $token)
            ->whereNotNull('signature_token')
            ->firstOrFail();

        if ($etat->signe_locataire) {
            return response()->json(['status' => false, 'message' => 'Déjà signé.']);
        }

        $signatureImage = $request->input('signature_image');
        if (empty($signatureImage) || !preg_match('/^data:image\/png;base64,/', $signatureImage)) {
            return response()->json(['status' => false, 'message' => 'Veuillez dessiner votre signature avant de valider.']);
        }

        try {
            $etat->update([
                'signe_locataire'           => true,
                'signe_locataire_at'        => Carbon::now(),
                'signature_locataire_image' => $signatureImage,
                'statut'                    => 'signe',
            ]);
        } catch (\Exception $e) {
            // Si la colonne n'existe pas encore (migration non lancée), on enregistre sans l'image
            $etat->update([
                'signe_locataire'    => true,
                'signe_locataire_at' => Carbon::now(),
                'statut'             => 'signe',
            ]);
        }

        return response()->json(['status' => true, 'message' => 'Signature enregistrée. Merci !']);
    }

    // ─── Upload photo (AJAX) ─────────────────────────────────────────────────

    public function uploadPhoto(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'etat_des_lieux_id' => 'required|integer',
            'piece_id'          => 'required|integer',
            'photos'            => 'required|array|min:1',
            'photos.*'          => 'image|mimes:jpeg,jpg,png,webp|max:5120',
        ], [
            'photos.*.image'    => 'Le fichier doit être une image.',
            'photos.*.mimes'    => 'Formats acceptés : jpeg, jpg, png, webp.',
            'photos.*.max'      => 'Chaque photo ne doit pas dépasser 5 Mo.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
        }

        $etat = EtatDesLieux::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->findOrFail($request->etat_des_lieux_id);

        if (!Gate::check('Is_admin') && $etat->idannexe_ref !== Auth::user()->idannexe_ref) {
            abort(403);
        }

        // Bloquer l'ajout de photos après finalisation
        if ($etat->statut !== 'brouillon') {
            return response()->json(['status' => false, 'message' => 'L\'ajout de photos n\'est plus possible une fois l\'état des lieux finalisé.']);
        }

        $piece = EtatDesLieuxPiece::where('etat_des_lieux_id', $etat->id)
            ->findOrFail($request->piece_id);

        // Limite : 2 photos par pièce
        $nbExistant = EtatDesLieuxPhoto::where('piece_id', $piece->id)->count();
        $nbAjouter  = count($request->file('photos', []));
        if ($nbExistant + $nbAjouter > 2) {
            $restant = max(0, 2 - $nbExistant);
            return response()->json(['status' => false, 'message' => 'Maximum 2 photos par pièce. Il reste ' . $restant . ' emplacement(s) disponible(s).']);
        }

        $chemin = 'etat_des_lieux/' . $etat->id;
        Storage::makeDirectory('public/' . $chemin);

        $urls = [];
        foreach ($request->file('photos') as $photo) {
            $filename = 'photo_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/' . $chemin, $filename);

            $p = EtatDesLieuxPhoto::create([
                'etat_des_lieux_id' => $etat->id,
                'piece_id'          => $piece->id,
                'chemin_fichier'    => $chemin . '/' . $filename,
                'nom_original'      => $photo->getClientOriginalName(),
            ]);

            $urls[] = ['id' => $p->id, 'url' => Storage::url($chemin . '/' . $filename)];
        }

        return response()->json(['status' => true, 'photos' => $urls, 'message' => count($urls) . ' photo(s) ajoutée(s).']);
    }

    // ─── Suppression photo (AJAX) ────────────────────────────────────────────

    public function deletePhoto(Request $request)
    {
        $photo = EtatDesLieuxPhoto::findOrFail($request->photo_id);

        // Vérification propriété
        $etat = EtatDesLieux::where('id', $photo->etat_des_lieux_id)
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->firstOrFail();

        if (!Gate::check('Is_admin') && $etat->idannexe_ref !== Auth::user()->idannexe_ref) {
            abort(403);
        }

        Storage::delete('public/' . $photo->chemin_fichier);
        $photo->delete();

        return response()->json(['status' => true, 'message' => 'Photo supprimée.']);
    }

    // ─── Signature agent ─────────────────────────────────────────────────────

    public function signerAgent(Request $request)
    {
        $etat = EtatDesLieux::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->findOrFail($request->id);

        if ($etat->signe_agent) {
            return response()->json(['status' => false, 'message' => 'Vous avez déjà signé cet état des lieux.']);
        }

        if ($etat->statut === 'brouillon') {
            return response()->json(['status' => false, 'message' => 'Finalisez d\'abord l\'état des lieux.']);
        }

        $etat->update([
            'signe_agent'    => true,
            'signe_agent_at' => Carbon::now(),
        ]);

        // Si les deux ont signé → statut signé
        if ($etat->signe_locataire) {
            $etat->update(['statut' => 'signe']);
        }

        return response()->json(['status' => true, 'message' => 'Votre signature a été enregistrée.']);
    }

    // ─── Suppression (soft delete) ───────────────────────────────────────────

    public function destroy(Request $request)
    {
        $etat = EtatDesLieux::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->findOrFail($request->id);

        $etat->update(['delete_at' => now()]);

        return response()->json(['status' => true, 'message' => 'État des lieux supprimé.']);
    }
}
