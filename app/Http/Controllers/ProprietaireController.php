<?php

namespace App\Http\Controllers;

use App\Proprietaire;
use App\Maison;
use App\Chambre;
use App\Prix;
use App\Locataire;
use App\Facture;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProprietaireController extends Controller
{
    public function guide()
    {
        return view('guide');
    }

    /**
     * Récupérer les propriétaires selon les permissions
     */
    public function getProprietaire()
    {
        return Proprietaire::whereNull('delete_at')
                            ->where('iddirection_ref', Auth::user()->iddirection_ref)
                            ->where(function($query){
                                if (Gate::none(['Is_admin'])) {
                                    $query->where('idannexe_ref', Auth::user()->idannexe_ref);
                                }
                            })
                            ->get();
    }

    /**
     * Afficher la liste des propriétaires
     */
    public function index()
    {
        $allProprios = $this->getProprietaire();
        return view('proprietaire.proprietaire', compact('allProprios'));
    }

    /**
     * Vérifier si l'utilisateur est admin et entreprise
     */
    public function check_is_admin_and_entreprise()
    {
        return Gate::allows('Is_admin') && Auth::user()->type_compte != 'Particulier';
    }

    /**
     * Créer un nouveau propriétaire via AJAX
     */
    public function create(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'telephone' => 'bail|required',
                    'nom' => 'bail|required|string',
                    'prenom' => 'bail|required|string',
                    'adresse' => 'bail|required|string',
                ],
                [
                    'telephone.required' => 'Le téléphone est obligatoire',
                    'nom.required' => 'Le nom est obligatoire',
                    'prenom.required' => 'Le prénom est obligatoire',
                    'adresse.required' => 'L\'adresse est obligatoire',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => "Veuillez bien renseigner les informations"
                ], 422);
            }

            // Déterminer l'annexe
            $response = $this->check_is_admin_and_entreprise();
            if ($response) {
                $idannexe_ref = $request->annexe;
            } else {
                $idannexe_ref = Auth::user()->idannexe_ref;
            }

            // Créer le propriétaire
            $proprio = Proprietaire::create([
                'nom' => Str::upper($request->nom),
                'prenom' => Str::ucfirst($request->prenom),
                'telephone' => $request->telephone,
                'adresse' => Str::ucfirst($request->adresse),
                'iddirection_ref' => Auth::user()->iddirection_ref,
                'idannexe_ref' => $idannexe_ref,
            ]);

            if ($proprio) {
                // Log de l'activité
                activity()->performedOn(new Proprietaire())
                    ->causedBy(Auth::user()->id)
                    ->log('Ajout du propriétaire ' . Str::upper($request->nom) . ' ' . Str::ucfirst($request->prenom) . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

                // ⭐ Retourner l'ID au lieu de l'iteration
                return response()->json([
                    'status' => true,
                    'message' => Str::upper($request->nom) . ' ' . Str::ucfirst($request->prenom) . " est créé avec succès",
                    'data' => [
                        'id' => $proprio->id,
                        'nom' => $proprio->nom,
                        'prenom' => $proprio->prenom,
                        'telephone' => $proprio->telephone,
                        'adresse' => $proprio->adresse,
                        'annexe_name' => get_annexee_name($proprio->idannexe_ref),
                    ]
                ]);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => "Échec, essayez encore",
            ], 500);
        }
    }

    /**
     * Mettre à jour un propriétaire via AJAX
     */
    public function update(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'id' => 'required|exists:proprietaires,id',
                    'telephone' => 'bail|required',
                    'nom' => 'bail|required|string',
                    'prenom' => 'bail|required|string',
                    'adresse' => 'bail|required|string',
                ],
                [
                    'id.required' => 'L\'identifiant est obligatoire',
                    'id.exists' => 'Le propriétaire n\'existe pas',
                    'telephone.required' => 'Le téléphone est obligatoire',
                    'nom.required' => 'Le nom est obligatoire',
                    'prenom.required' => 'Le prénom est obligatoire',
                    'adresse.required' => 'L\'adresse est obligatoire',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                    'message' => "Veuillez bien renseigner les informations"
                ], 422);
            }

            // Déterminer l'annexe
            $response = $this->check_is_admin_and_entreprise();
            if ($response) {
                $idannexe_ref = $request->annexe;
            } else {
                $idannexe_ref = Auth::user()->idannexe_ref;
            }

            // Mettre à jour le propriétaire
            $proprio = Proprietaire::where('id', $request->id)
                ->update([
                    'nom' => Str::upper($request->nom),
                    'prenom' => Str::ucfirst($request->prenom),
                    'telephone' => $request->telephone,
                    'adresse' => Str::ucfirst($request->adresse),
                    'iddirection_ref' => Auth::user()->iddirection_ref,
                    'idannexe_ref' => $idannexe_ref,
                ]);

            if ($proprio) {
                // Récupérer les données mises à jour
                $proprietaire = Proprietaire::find($request->id);

                // Log de l'activité
                activity()->performedOn(new Proprietaire())
                    ->causedBy(Auth::user()->id)
                    ->log('Modification du propriétaire ' . Str::upper($request->nom) . ' ' . Str::ucfirst($request->prenom) . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

                // ⭐ Retourner l'ID au lieu de l'iteration
                return response()->json([
                    'status' => true,
                    'message' => Str::upper($request->nom) . ' ' . Str::ucfirst($request->prenom) . ' mis à jour avec succès',
                    'data' => [
                        'id' => $proprietaire->id, // ⭐ ID utilisé pour les modals
                        'nom' => $proprietaire->nom,
                        'prenom' => $proprietaire->prenom,
                        'telephone' => $proprietaire->telephone,
                        'adresse' => $proprietaire->adresse,
                        'annexe_name' => get_annexee_name($proprietaire->idannexe_ref),
                    ]
                ]);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Échec, veuillez vérifier les données',
            ], 500);
        }
    }

    /**
     * Supprimer un propriétaire via AJAX (soft delete)
     */
    public function destroy(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'id' => 'required|exists:proprietaires,id'
                ],
                [
                    'id.required' => 'L\'identifiant est obligatoire',
                    'id.exists' => 'Le propriétaire n\'existe pas'
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'error' => $validator->errors(),
                ], 422);
            }

            $now = Carbon::now();

            // Récupérer le propriétaire avant suppression
            $proprio = Proprietaire::find($request->id);

            if (!$proprio) {
                return response()->json([
                    'status' => false,
                    'message' => 'Propriétaire introuvable',
                ], 404);
            }

            // Soft delete du propriétaire
            $deleted = Proprietaire::where('id', $request->id)
                ->update(['delete_at' => $now]);

            if ($deleted) {
                // Soft delete des maisons et données associées
                $maisonIds = Maison::where('proprio_id', $request->id)->pluck('id');

                if ($maisonIds->isNotEmpty()) {
                    Maison::whereIn('id', $maisonIds)->update(['delete_at' => $now]);
                    Chambre::whereIn('maison_id', $maisonIds)->update(['delete_at' => $now]);
                    Prix::whereIn('maison_id', $maisonIds)->update(['delete_at' => $now]);
                    Locataire::whereIn('maison_id', $maisonIds)->update(['delete_at' => $now]);
                    Facture::whereIn('maison_id', $maisonIds)->update(['delete_at' => $now]);
                }

                // Log de l'activité
                activity()
                    ->performedOn($proprio)
                    ->causedBy(Auth::user())
                    ->log('Suppression du propriétaire ' . Str::upper($proprio->nom) . ' ' . Str::ucfirst($proprio->prenom) . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

                // Retourner la réponse pour AJAX
                return response()->json([
                    'status' => true,
                    'message' => 'Suppression effectuée avec succès.',
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Aucune suppression effectuée.',
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Échec, veuillez vérifier les données.',
            ], 500);
        }
    }
}