<?php

namespace App\Http\Controllers;

use App\Parametre;
use App\Annexe;
use App\Direction;
use App\PourcentageGestion;
use App\Proprietaire;
use App\Publicite;
use App\ContratConfig;
use App\PlatformConfig;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;  
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;





class ParametreController extends SessionController
{
    public function  welcome_page()
    {
        $publicites = Publicite::notDeleted()
                        ->notExpired()
                        ->whereNotNull('image_url')
                        ->orderBy('published_at', 'desc')
                        ->get();

        $paymentConfig    = PlatformConfig::getConfig();
        $paymentEnabled   = $paymentConfig->isOperational();
        $paymentProvider  = $paymentConfig->getActiveProvider();
        $paymentPublicKey = $paymentEnabled ? $paymentConfig->getActivePublicKey() : null;
        $paymentSandbox   = $paymentConfig->getActiveSandbox();

        return view('/welcome', compact('publicites', 'paymentEnabled', 'paymentProvider', 'paymentPublicKey', 'paymentSandbox'));
    }

    public function index()
    {
       try {
            activity()->performedOn(new Parametre())
                       ->causedBy(Auth::user()->id)
                       ->log('Page paramétrage visité par '.Auth::user()->nom.' '.Auth::user()->prenom);

            $param = Parametre::where('iddirection_ref', Auth::user()->iddirection_ref)->get();
            $liste_annexe = Annexe::whereNull('status')
                                    ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                    ->whereNull('blocage_annexe')
                                   ->where('annexes.designation','!=','All Digital Agency')
                                    ->get();
                                    
            $pourcentageGeneral = PourcentageGestion::where('iddirection_ref', Auth::user()->iddirection_ref)
                ->where('type', 'general')
                ->whereNull('delete_at')
                ->first();

            $pourcentageGroupes = PourcentageGestion::where('iddirection_ref', Auth::user()->iddirection_ref)
                ->where('type', 'groupe')
                ->whereNull('delete_at')
                ->with('proprietaires')
                ->get();

            $proprietaires_list = Proprietaire::where('iddirection_ref', Auth::user()->iddirection_ref)
                ->whereNull('delete_at')
                ->get();

            $contratConfig = ContratConfig::where('iddirection_ref', Auth::user()->iddirection_ref)->first();

            return view('parametre', compact('param', 'liste_annexe', 'pourcentageGeneral', 'pourcentageGroupes', 'proprietaires_list', 'contratConfig'));

       } catch (QueryException $e) {
            return redirect()->back()->with('error','Échec, veuillez vérifier les données');
       }
    }

    private function getDirectionDsignation($iduser)
    {
        return Direction::where('iddirection',$iduser)
                        ->get()
                        ->pluck('designation')[0];
    }

    public function storeAnnexe(Request $request)
    {
        try {
            // Vérifier les limites du plan d'abonnement pour les annexes
            $direction = Direction::find(Auth::user()->iddirection_ref);

            if ($direction) {
                $canCreate = $direction->canCreateAnnexe();

                if (!$canCreate['allowed']) {
                    return response()->json([
                        'status' => false,
                        'message' => $canCreate['message'],
                        'plan_limit' => true
                    ]);
                }
            }

            $validator = Validator::make(
                $request->all(),
                [
                    'designation' => 'required|unique:annexes,designation',
                    'adresse' => 'bail|required|string',
                    'telephone' => 'bail|required|string',
                    'email' => 'bail|required|email',
                ],
                [
                    '*.required' => 'Ce champ est obligatoire.',
                    'email.email' => 'Veuillez entrer une adresse email valide.',
                    'designation.unique' => 'Cette désignation existe déjà.',
                ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors(),
                    'message' => 'Veuillez corriger les erreurs de validation'
                ]);
            }

            $annexe = Annexe::create([
                'iddirection_ref'  => Auth::user()->iddirection_ref,
                'designation'      => Str::ucfirst($request->designation),
                'siege_social'     => Str::ucfirst($request->adresse),
                'telephone'        => $request->telephone,
                'email'            => $request->email,
                'userdata'         => $this->getDirectionDsignation(Auth::user()->iddirection_ref)
            ]);

            if ($annexe) {
                Session::put(['anne_data'=>  SessionController::save_session_annexe()]);

                activity()
                    ->performedOn(new Annexe())
                    ->causedBy(Auth::user()->id)
                    ->log('Ajout d\'une annexe: '.Str::upper($request->designation).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                // Récupérer les informations du plan pour afficher le nombre restant
                $planInfo = $direction ? $direction->getPlanInfo() : null;
                $messageComplet = Str::upper($request->designation).' a été ajouté avec succès.';

                if ($planInfo && $planInfo['annexes_restantes'] > 0) {
                    $messageComplet .= " (Il vous reste {$planInfo['annexes_restantes']} annexe(s) disponible(s))";
                }

                return response()->json([
                    'status' => true,
                    'message' => $messageComplet,
                    'plan_info' => $planInfo
                ]);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => "Échec lors de l'ajout de l'annexe. Veuillez réessayer.",
            ]);
        }
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'cash_electronique' => 'nullable|mimes:jpeg,png,jpg|max:5120',
                    'logo' => 'nullable|mimes:jpeg,png,jpg|max:5120',
                ],
                [
                    'cash_electronique.mimes' => 'L\'image du cash électronique doit être au format JPEG, PNG ou JPG.',
                    'logo.mimes' => 'Le logo doit être au format JPEG, PNG ou JPG.',
                    'cash_electronique.max' => 'L\'image du cash électronique ne doit pas dépasser 5MB.',
                    'logo.max' => 'Le logo ne doit pas dépasser 5MB.',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors(),
                    'message' => "Veuillez vérifier les fichiers uploadés"
                ]);
            }

            // Vérifier qu'au moins un fichier est présent
            if (!$request->hasFile('cash_electronique') && !$request->hasFile('logo')) {
                return response()->json([
                    'status' => false,
                    'message' => "Veuillez sélectionner au moins une image (cash électronique ou logo)"
                ]);
            }

            $parametre = Parametre::where('iddirection_ref', Auth::user()->iddirection_ref)->first();
            
            // Si un paramètre existe déjà, on le met à jour
            if ($parametre) {
                // Supprimer l'ancien cash électronique si un nouveau est uploadé
                if ($request->hasFile('cash_electronique') && $parametre->cash_electronique_url) {
                    $oldPath = str_replace(asset(''), '', $parametre->cash_electronique_url);
                    if (Storage::exists("public/$oldPath")) {
                        Storage::delete("public/$oldPath");
                    }
                }
                
                // Supprimer l'ancien logo si un nouveau est uploadé
                if ($request->hasFile('logo') && $parametre->logo_url) {
                    $oldPath = str_replace(asset(''), '', $parametre->logo_url);
                    if (Storage::exists("public/$oldPath")) {
                        Storage::delete("public/$oldPath");
                    }
                }
                
                $cashElectroniquePath = $parametre->getRawOriginal('cash_electronique_url');
                $logoPath = $parametre->getRawOriginal('logo_url');
            } else {
                $cashElectroniquePath = null;
                $logoPath = null;
                $parametre = new Parametre();
                $parametre->iddirection_ref = Auth::user()->iddirection_ref;
                $parametre->format_choisi = 'default';
            }

            // Upload de l'image du cash électronique
            if ($request->hasFile('cash_electronique')) {
                $file = $request->file('cash_electronique');
                $path = 'cash_electronique';
                
                if (!Storage::exists("public/$path")) {
                    Storage::makeDirectory("public/$path", 0775, true);
                }
                
                $filename = 'cash_electronique_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->storeAs("public/$path", $filename);
                $cashElectroniquePath = "$path/$filename";
            }

            // Upload du logo
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $path = 'logo';
                
                if (!Storage::exists("public/$path")) {
                    Storage::makeDirectory("public/$path", 0775, true);
                }
                
                $filename = 'logo_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->storeAs("public/$path", $filename);
                $logoPath = "$path/$filename";
            }

            // Sauvegarde
            $parametre->cash_electronique_url = $cashElectroniquePath;
            $parametre->logo_url = $logoPath;
            $parametre->save();

            activity()
                ->performedOn(new Parametre())
                ->causedBy(Auth::user()->id)
                ->log('Modification des images (cash électronique/logo) par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

            return response()->json([
                'status' => true,
                'message' => "Images enregistrées avec succès",
            ]);

        } catch (QueryException $e) {

            return response()->json([
                'status' => false,
                'message' => "Échec lors de l'enregistrement. Veuillez réessayer.  ddddd",
            ]);
        } catch (\Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => "Une erreur inattendue est survenue.",
            ]);
        }
    }


    public function updateAnnexe(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                   'designation' => 'bail|required|string',
                    'adresse' => 'bail|required|string',
                    'telephone' => 'bail|required|string',
                    'email' => 'bail|required|string',
                    'logo' => 'nullable|mimes:jpeg,png,jpg|max:2048',
                    'signature' => 'nullable|mimes:jpeg,png,jpg|max:2048',
                ],
                [
                    'logo.mimes' => 'Le logo doit être au format JPEG, PNG ou JPG.',
                    'logo.max' => 'Le logo ne doit pas dépasser 2MB.',
                    'signature.mimes' => 'La signature doit être au format JPEG, PNG ou JPG.',
                    'signature.max' => 'La signature ne doit pas dépasser 2MB.',
                ]
            );

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            // Récupérer l'annexe existante
            $annexe = Annexe::where('idannexes', $request->id)->first();

            if (!$annexe) {
                return back()->with('error', 'Annexe non trouvée.');
            }

            // Préparer les données à mettre à jour
            $updateData = [
                'iddirection_ref'  => Auth::user()->iddirection_ref,
                'designation'      => Str::ucfirst($request->designation),
                'siege_social'     => Str::ucfirst($request->adresse),
                'telephone'        => $request->telephone,
                'email'            => $request->email,
                'userdata'         => $this->getDirectionDsignation(Auth::user()->iddirection_ref),
                'cash_electronique' => $request->cash_electronique,
            ];

            // Upload du logo si présent
            if ($request->hasFile('logo')) {
                // Supprimer l'ancien logo
                if ($annexe->logo && Storage::exists("public/{$annexe->logo}")) {
                    Storage::delete("public/{$annexe->logo}");
                }

                $file = $request->file('logo');
                $path = 'annexes/logos';

                if (!Storage::exists("public/$path")) {
                    Storage::makeDirectory("public/$path", 0775, true);
                }

                $filename = 'logo_annexe_' . $request->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs("public/$path", $filename);
                $updateData['logo'] = "$path/$filename";
            }

            // Upload de la signature si présente
            if ($request->hasFile('signature')) {
                // Supprimer l'ancienne signature
                if ($annexe->signature && Storage::exists("public/{$annexe->signature}")) {
                    Storage::delete("public/{$annexe->signature}");
                }

                $file = $request->file('signature');
                $path = 'annexes/signatures';

                if (!Storage::exists("public/$path")) {
                    Storage::makeDirectory("public/$path", 0775, true);
                }

                $filename = 'signature_annexe_' . $request->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs("public/$path", $filename);
                $updateData['signature'] = "$path/$filename";
            }

            $annexes = Annexe::where('idannexes', $request->id)->update($updateData);

            if ($annexes) {

                $existeDirection = Direction::where('iddirection',$request->id)->first();

                if ($existeDirection) {
                    $annexes = Direction::where('iddirection',$request->id)
                            ->update([
                                'iddirection'  => Auth::user()->iddirection_ref,
                                'designation'      => Str::ucfirst($request->designation),
                                'siege_social'     => Str::ucfirst($request->adresse),
                                'telephone'        => $request->telephone,
                                'email'          => $request->email,
                            ]);
                }

                Cache::forget("annexe_details_{$request->id}");

                Session::put(['anne_data'=>  SessionController::save_session_annexe()]);

                activity()->performedOn(new Annexe())
                          ->causedBy(Auth::user()->id)
                          ->log('Modification de l\'annexe ' . Str::upper($request->designation) . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

                return back()->with('success', Str::upper($request->designation).' mis à jour avec succès');
            }
        }
        catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->with('error', 'Cette désignation existe déjà.');
            }
            return back()->with('error', 'Erreur lors de la mise à jour.');
        }
    }


    public function destroyAnnexe(Request $request)
    {
        try {

            $deleted = Annexe::where('idannexes',$request->id)
                                   ->update([
                                            'status' => Carbon::now()
                                    ]);

            $objetDeleted = Annexe::where('idannexes',$request->id)
                                   ->first();


            if ($deleted) {
                Session::put(['anne_data'=>  SessionController::save_session_annexe()]);

                 activity()->performedOn(new Annexe())
                           ->causedBy(Auth::user()->id)
                           ->log("Suppression de l'annexe ".Str::upper($objetDeleted->designation).' '.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return back()->with('success','Suppression effectuée avec succès');

            }
        } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }

    /**
     * Définir l'annexe active en session (pour les admins)
     */
    public function setActiveAnnexe(Request $request)
    {
        try {
            // Vérifier que l'utilisateur est admin et type entreprise
            if (Auth::user()->is_admin != 1 || Auth::user()->type_compte == 'Particulier') {
                return response()->json([
                    'status' => false,
                    'message' => 'Vous n\'êtes pas autorisé à effectuer cette action.'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'annexe_id' => 'required|integer'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'ID d\'annexe invalide',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Vérifier que l'annexe appartient à la direction de l'utilisateur
            $annexe = Annexe::where('idannexes', $request->annexe_id)
                           ->where('iddirection_ref', Auth::user()->iddirection_ref)
                           ->whereNull('status')
                           ->whereNull('blocage_annexe')
                           ->first();

            if (!$annexe) {
                return response()->json([
                    'status' => false,
                    'message' => 'Annexe non trouvée ou non autorisée.'
                ], 404);
            }

            // Stocker l'annexe active en session
            Session::put('active_annexe_id', $request->annexe_id);

            activity()->performedOn($annexe)
                      ->causedBy(Auth::user()->id)
                      ->log('Changement d\'agence active vers ' . $annexe->designation . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

            return response()->json([
                'status' => true,
                'message' => 'Agence "' . $annexe->designation . '" sélectionnée avec succès.',
                'data' => [
                    'id' => $annexe->idannexes,
                    'nom' => $annexe->designation,
                    'adresse' => $annexe->siege_social,
                    'telephone' => $annexe->telephone,
                    'email' => $annexe->email
                ]
            ]);

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors du changement d\'agence.'
            ], 500);
        }
    }

    /**
     * Récupérer l'annexe active actuelle
     */
    public function getActiveAnnexe()
    {
        try {
            $annexeId = get_active_annexe_id();

            if (!$annexeId) {
                return response()->json([
                    'status' => false,
                    'message' => 'Aucune agence active.',
                    'data' => null
                ]);
            }

            $annexe = Annexe::where('idannexes', $annexeId)
                           ->whereNull('status')
                           ->first();

            if (!$annexe) {
                return response()->json([
                    'status' => false,
                    'message' => 'Agence non trouvée.',
                    'data' => null
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Agence active récupérée.',
                'data' => [
                    'id' => $annexe->idannexes,
                    'nom' => $annexe->designation,
                    'adresse' => $annexe->siege_social,
                    'telephone' => $annexe->telephone,
                    'email' => $annexe->email
                ]
            ]);

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la récupération de l\'agence.'
            ], 500);
        }
    }

    // ==========================================
    // POURCENTAGE DE GESTION
    // ==========================================

    public function storePourcentageGeneral(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pourcentage' => 'required|numeric|min:0|max:100',
            ], [
                'pourcentage.required' => 'Le pourcentage est obligatoire.',
                'pourcentage.numeric' => 'Le pourcentage doit être un nombre.',
                'pourcentage.min' => 'Le pourcentage minimum est 0.',
                'pourcentage.max' => 'Le pourcentage maximum est 100.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => 'Veuillez corriger les erreurs de validation'
                ]);
            }

            $existing = PourcentageGestion::where('iddirection_ref', Auth::user()->iddirection_ref)
                ->where('type', 'general')
                ->whereNull('delete_at')
                ->first();

            if ($existing) {
                $existing->pourcentage = $request->pourcentage;
                $existing->save();
            } else {
                PourcentageGestion::create([
                    'iddirection_ref' => Auth::user()->iddirection_ref,
                    'type' => 'general',
                    'nom' => null,
                    'pourcentage' => $request->pourcentage,
                    'is_active' => false,
                ]);
            }

            activity()->performedOn(new PourcentageGestion())
                ->causedBy(Auth::user()->id)
                ->log('Modification du pourcentage général par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

            return response()->json([
                'status' => true,
                'message' => 'Pourcentage général enregistré avec succès',
            ]);

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Échec lors de l\'enregistrement.',
            ]);
        }
    }

    public function togglePourcentage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => 'ID invalide']);
            }

            $pourcentage = PourcentageGestion::where('id', $request->id)
                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                ->whereNull('delete_at')
                ->first();

            if (!$pourcentage) {
                return response()->json(['status' => false, 'message' => 'Élément non trouvé.']);
            }

            $pourcentage->is_active = !$pourcentage->is_active;
            $pourcentage->save();

            $statut = $pourcentage->is_active ? 'activé' : 'désactivé';

            activity()->performedOn($pourcentage)
                ->causedBy(Auth::user()->id)
                ->log("Pourcentage {$pourcentage->type} {$statut} par " . Auth::user()->nom . ' ' . Auth::user()->prenom);

            return response()->json([
                'status' => true,
                'message' => "Pourcentage {$statut} avec succès",
                'is_active' => $pourcentage->is_active,
            ]);

        } catch (QueryException $e) {
            return response()->json(['status' => false, 'message' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function storeGroupePourcentage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nom' => 'required|string|max:255',
                'pourcentage' => 'required|numeric|min:0|max:100',
                'proprietaires' => 'required|array|min:1',
                'proprietaires.*' => 'integer|exists:proprietaires,id',
            ], [
                'nom.required' => 'Le nom du groupe est obligatoire.',
                'pourcentage.required' => 'Le pourcentage est obligatoire.',
                'proprietaires.required' => 'Veuillez sélectionner au moins un propriétaire.',
                'proprietaires.min' => 'Veuillez sélectionner au moins un propriétaire.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => 'Veuillez corriger les erreurs de validation'
                ]);
            }

            // Vérifier qu'aucun propriétaire sélectionné n'appartient déjà à un autre groupe
            $existingAssignments = DB::table('pourcentage_gestion_proprietaire')
                ->join('pourcentage_gestions', 'pourcentage_gestions.id', '=', 'pourcentage_gestion_proprietaire.pourcentage_gestion_id')
                ->whereIn('pourcentage_gestion_proprietaire.proprietaire_id', $request->proprietaires)
                ->where('pourcentage_gestions.iddirection_ref', Auth::user()->iddirection_ref)
                ->whereNull('pourcentage_gestions.delete_at')
                ->count();

            if ($existingAssignments > 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Un ou plusieurs propriétaires appartiennent déjà à un groupe. Retirez-les d\'abord de leur groupe actuel.',
                ]);
            }

            $groupe = PourcentageGestion::create([
                'iddirection_ref' => Auth::user()->iddirection_ref,
                'type' => 'groupe',
                'nom' => $request->nom,
                'pourcentage' => $request->pourcentage,
                'is_active' => false,
            ]);

            $groupe->proprietaires()->sync($request->proprietaires);

            activity()->performedOn($groupe)
                ->causedBy(Auth::user()->id)
                ->log('Création du groupe de pourcentage "' . $request->nom . '" par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

            return response()->json([
                'status' => true,
                'message' => 'Groupe "' . $request->nom . '" créé avec succès',
            ]);

        } catch (QueryException $e) {
            return response()->json(['status' => false, 'message' => 'Échec lors de la création du groupe.']);
        }
    }

    public function updateGroupePourcentage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required|integer',
                'nom' => 'required|string|max:255',
                'pourcentage' => 'required|numeric|min:0|max:100',
                'proprietaires' => 'required|array|min:1',
                'proprietaires.*' => 'integer|exists:proprietaires,id',
            ], [
                'nom.required' => 'Le nom du groupe est obligatoire.',
                'pourcentage.required' => 'Le pourcentage est obligatoire.',
                'proprietaires.required' => 'Veuillez sélectionner au moins un propriétaire.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => 'Veuillez corriger les erreurs de validation'
                ]);
            }

            $groupe = PourcentageGestion::where('id', $request->id)
                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                ->where('type', 'groupe')
                ->whereNull('delete_at')
                ->first();

            if (!$groupe) {
                return response()->json(['status' => false, 'message' => 'Groupe non trouvé.']);
            }

            // Vérifier les conflits (en excluant le groupe courant)
            $existingAssignments = DB::table('pourcentage_gestion_proprietaire')
                ->join('pourcentage_gestions', 'pourcentage_gestions.id', '=', 'pourcentage_gestion_proprietaire.pourcentage_gestion_id')
                ->whereIn('pourcentage_gestion_proprietaire.proprietaire_id', $request->proprietaires)
                ->where('pourcentage_gestion_proprietaire.pourcentage_gestion_id', '!=', $request->id)
                ->where('pourcentage_gestions.iddirection_ref', Auth::user()->iddirection_ref)
                ->whereNull('pourcentage_gestions.delete_at')
                ->count();

            if ($existingAssignments > 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Un ou plusieurs propriétaires appartiennent déjà à un autre groupe.',
                ]);
            }

            $groupe->update([
                'nom' => $request->nom,
                'pourcentage' => $request->pourcentage,
            ]);

            $groupe->proprietaires()->sync($request->proprietaires);

            activity()->performedOn($groupe)
                ->causedBy(Auth::user()->id)
                ->log('Modification du groupe "' . $request->nom . '" par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

            return response()->json([
                'status' => true,
                'message' => 'Groupe mis à jour avec succès',
            ]);

        } catch (QueryException $e) {
            return response()->json(['status' => false, 'message' => 'Échec lors de la mise à jour.']);
        }
    }

    public function destroyGroupePourcentage(Request $request)
    {
        try {
            $groupe = PourcentageGestion::where('id', $request->id)
                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                ->where('type', 'groupe')
                ->whereNull('delete_at')
                ->first();

            if (!$groupe) {
                return response()->json(['status' => false, 'message' => 'Groupe non trouvé.']);
            }

            $groupe->proprietaires()->detach();
            $groupe->update(['delete_at' => Carbon::now()]);

            activity()->performedOn($groupe)
                ->causedBy(Auth::user()->id)
                ->log('Suppression du groupe "' . $groupe->nom . '" par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

            return response()->json([
                'status' => true,
                'message' => 'Groupe supprimé avec succès',
            ]);

        } catch (QueryException $e) {
            return response()->json(['status' => false, 'message' => 'Échec lors de la suppression.']);
        }
    }

    public function getPourcentageForProprietaire(Request $request)
    {
        try {
            $pourcentage = get_pourcentage_gestion($request->proprietaire_id);
            return response()->json([
                'status' => true,
                'pourcentage' => $pourcentage,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'pourcentage' => 10,
            ]);
        }
    }

    public function storeContratConfig(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'titre_contrat' => 'required|string|max:255',
                'sous_titre'    => 'nullable|string|max:500',
                'articles'      => 'required|array|min:1',
                'articles.*.titre'   => 'required|string|max:255',
                'articles.*.contenu' => 'required|string',
            ], [
                'titre_contrat.required' => 'Le titre du contrat est obligatoire.',
                'articles.required'      => 'Ajoutez au moins un article.',
                'articles.min'           => 'Ajoutez au moins un article.',
                'articles.*.titre.required'   => 'Chaque article doit avoir un titre.',
                'articles.*.contenu.required' => 'Chaque article doit avoir un contenu.',
            ]);

            if ($validator->fails()) {
                if ($request->expectsJson()) {
                    return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
                }
                return back()->withErrors($validator)->with('active_tab', 'contrat');
            }

            ContratConfig::updateOrCreate(
                ['iddirection_ref' => Auth::user()->iddirection_ref],
                [
                    'titre_contrat' => $request->titre_contrat,
                    'sous_titre'    => $request->sous_titre,
                    'articles'      => $request->articles,
                ]
            );

            activity()->performedOn(new ContratConfig())
                ->causedBy(Auth::user())
                ->log('Modification du modèle de contrat par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

            if ($request->expectsJson()) {
                return response()->json(['status' => true, 'message' => 'Modèle de contrat enregistré avec succès.']);
            }
            return back()->with('success', 'Modèle de contrat enregistré avec succès.')->with('active_tab', 'contrat');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['status' => false, 'message' => 'Échec, veuillez réessayer.']);
            }
            return back()->with('error', 'Échec, veuillez réessayer.')->with('active_tab', 'contrat');
        }
    }

    public function resetContratConfig(Request $request)
    {
        try {
            ContratConfig::where('iddirection_ref', Auth::user()->iddirection_ref)->delete();

            activity()->performedOn(new ContratConfig())
                ->causedBy(Auth::user())
                ->log('Réinitialisation du modèle de contrat par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

            return back()->with('success', 'Modèle de contrat remis aux valeurs par défaut.')->with('active_tab', 'contrat');

        } catch (\Exception $e) {
            return back()->with('error', 'Échec de la réinitialisation.')->with('active_tab', 'contrat');
        }
    }

    public function storeCommConfig(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email_envoi'           => 'nullable|email|max:255',
                'whatsapp_numero_envoi' => 'nullable|string|max:50',
            ], [
                'email_envoi.email' => 'L\'adresse email d\'envoi est invalide.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => implode(' ', $validator->errors()->all()),
                ]);
            }

            Parametre::updateOrCreate(
                ['iddirection_ref' => Auth::user()->iddirection_ref],
                [
                    'email_envoi'           => $request->email_envoi,
                    'whatsapp_numero_envoi' => $request->whatsapp_numero_envoi,
                ]
            );

            activity()->performedOn(new Parametre())
                ->causedBy(Auth::user())
                ->log('Modification de la configuration communication par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

            return response()->json([
                'status'  => true,
                'message' => 'Configuration communication enregistrée avec succès.',
            ]);

        } catch (QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Échec lors de l\'enregistrement. Veuillez réessayer.',
            ]);
        }
    }
}
