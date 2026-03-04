<?php

namespace App\Http\Controllers;

use App\Proprietaire;
use App\Maison;
use App\Chambre;
use App\Prix;
use App\Locataire;
use App\Facture;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ProprietaireController extends Controller
{
    public function guide()
    {
        return view('guide');
    }

    /**
     * Recuperer les proprietaires selon l'agence active
     */
    public function getProprietaire()
    {
        // Utiliser l'agence active centralisee
        $idannexe_ref = get_active_annexe_id();

        return Proprietaire::whereNull('delete_at')
                            ->where('iddirection_ref', Auth::user()->iddirection_ref)
                            ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                                $query->where('idannexe_ref', $idannexe_ref);
                            })
                            ->with('annexe:idannexes,designation')
                            ->get();
    }

    /**
     * Afficher la liste des propriétaires
     */
    public function index()
    {
        $allProprios = $this->getProprietaire();
        return view('proprietaire.index', compact('allProprios'));
    }

    /**
     * Vérifier si l'utilisateur est admin et entreprise
     */
    public function check_is_admin_and_entreprise()
    {
        return Gate::allows('Is_admin') && Auth::user()->type_compte != 'Particulier';
    }

    /**
     * Créer un nouveau propriétaire
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
                return back()->withErrors($validator)->withInput()
                    ->with('error', 'Veuillez bien renseigner les informations');
            }

            // Utiliser l'annexe active centralisée
            $idannexe_ref = get_active_annexe_id();
            if (!$idannexe_ref) {
                return back()->with('error', 'Veuillez sélectionner une agence dans le header');
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

                return redirect()->route('proprietaires.index')
                    ->with('success', Str::upper($request->nom) . ' ' . Str::ucfirst($request->prenom) . ' a été créé avec succès');
            }

            return back()->with('error', 'Échec de la création du propriétaire');

        } catch (QueryException $e) {
            return back()->with('error', 'Échec, veuillez réessayer');
        }
    }

    /**
     * Mettre à jour un propriétaire
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
                return back()->withErrors($validator)->withInput()
                    ->with('error', 'Veuillez bien renseigner les informations');
            }

            // Utiliser l'annexe active centralisée
            $idannexe_ref = get_active_annexe_id();
            if (!$idannexe_ref) {
                return back()->with('error', 'Veuillez sélectionner une agence dans le header');
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
                // Log de l'activité
                activity()->performedOn(new Proprietaire())
                    ->causedBy(Auth::user()->id)
                    ->log('Modification du propriétaire ' . Str::upper($request->nom) . ' ' . Str::ucfirst($request->prenom) . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

                return redirect()->route('proprietaires.index')
                    ->with('success', Str::upper($request->nom) . ' ' . Str::ucfirst($request->prenom) . ' a été mis à jour avec succès');
            }

            return back()->with('error', 'Échec de la modification du propriétaire');

        } catch (QueryException $e) {
            return back()->with('error', 'Échec, veuillez vérifier les données');
        }
    }

    /**
     * Supprimer un propriétaire (soft delete)
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
                return back()->with('error', 'Propriétaire introuvable');
            }

            $now = Carbon::now();

            // Récupérer le propriétaire avant suppression
            $proprio = Proprietaire::find($request->id);

            if (!$proprio) {
                return back()->with('error', 'Propriétaire introuvable');
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

                return redirect()->route('proprietaires.index')
                    ->with('success', Str::upper($proprio->nom) . ' ' . Str::ucfirst($proprio->prenom) . ' a été supprimé avec succès');
            }

            return back()->with('error', 'Aucune suppression effectuée');

        } catch (\Exception $e) {
            return back()->with('error', 'Échec, veuillez vérifier les données');
        }
    }
}
