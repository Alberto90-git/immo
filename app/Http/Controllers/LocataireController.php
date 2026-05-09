<?php

namespace App\Http\Controllers;

use App\Locataire;
use App\Maison;
use App\Chambre;
use App\Prix;
use App\ContratConfig;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;



class LocataireController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       try {
            // Utiliser l'agence active centralisee
            $idannexe_ref = get_active_annexe_id();

            $allMaison = Maison::whereNull('delete_at')
                                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                                ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                                    $query->where('idannexe_ref', $idannexe_ref);
                                })
                                ->get();

            $allLocataire = Locataire::where('locataires.status', true)
                                      ->whereNull('locataires.delete_at')
                                      ->where('locataires.iddirection_ref', Auth::user()->iddirection_ref)
                                      ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                                        $query->where('locataires.idannexe_ref', $idannexe_ref);
                                      })
                                      ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
                                      ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
                                      ->whereNull('maisons.delete_at')
                                      ->whereNull('chambres.delete_at')
                                      ->select('locataires.iddirection_ref','locataires.idannexe_ref','maisons.nom_maison','chambres.numero_chambre','chambres.type_chambre','locataires.nom','locataires.prenom','locataires.profession','locataires.telephone','locataires.email','locataires.mode_paiement','locataires.nombre_caution','locataires.nombre_avance','locataires.date_entree','locataires.id','locataires.chambre_id','locataires.nombre_avance_consomme','locataires.caution_courant','locataires.caution_eau','locataires.statut_bail')
                                      ->get();

            $contratConfigs = ContratConfig::where('iddirection_ref', Auth::user()->iddirection_ref)
                ->orderBy('is_default', 'desc')
                ->orderBy('nom_modele')
                ->get();

            $prospectPrefill = session()->pull('prospect_prefill');

            return view('locataire.locataire', compact(['allMaison','allLocataire','contratConfigs','prospectPrefill']));

       } catch (QueryException $e) {
            return back()->with('error','Echec, veuillez verifier les donnees');
       }
    }


    public function getNumeroChambreForLocation(Request $request)
    {
            $vide = '';
    
            $vide.="<option disabled selected>Choisir une chambre</option>";
    
            $val = Chambre::where('maison_id',$request->idMaison)
                          //->join('prixes', 'prixes.chambre_id', '=', 'chambres.id')
                          ->where('chambres.iddirection_ref',Auth::user()->iddirection_ref)
                          ->where(function($querry){
                            if (Gate::none(['Is_admin'])) {
                                $querry->where('chambres.idannexe_ref',Auth::user()->idannexe_ref);
                            }
                           })
                          ->whereNull('chambres.delete_at')
                          ->where('chambres.etat',false)
                          ->get();
    
             foreach ($val as  $cont) {
                 $vide.="<option value=".$cont->id.">".$cont->numero_chambre."</option>";
             }
    
            return response()->json([
                                    'list_chambre' => $vide,
                                    ]);
    }

    public function getTypeChambre(Request $request)
    {
            $chambre = Chambre::where('id',$request->numero_chambre_got)
                            ->where('chambres.iddirection_ref',Auth::user()->iddirection_ref)
                            ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('chambres.idannexe_ref',Auth::user()->idannexe_ref);
                                }
                            })
                            ->whereNull('delete_at')
                            ->where('etat',false)
                            ->first();

            return response()->json([
                                    'type_chambres_get' => $chambre?->type_chambre,
                                    ]);
    }

    public function getPrix(Request $request)
    {
            $prix = Prix::where('chambre_id',$request->prixGot)
                            ->where('iddirection_ref',Auth::user()->iddirection_ref)
                            ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                }
                            })
                           ->whereNull('delete_at')
                           ->where('status',true)
                           ->first();

            return response()->json([
                                      'prixApayer' => $prix?->prix,
                                    ]);
    }

    private function get_house_loocation($id)
    {
         return Maison::where('id',$id)
                        ->whereNull('delete_at')
                        ->value('quartier');
    }

    public function store(Request $request)
    {
        try {

            $validator = Validator::make(
                $request->all(),
                [
                    'nom_maison' => ['bail','required'],
                    'numero_chambre' => ['bail','required'],
                    'nom_locataire' => ['bail','required','string'],
                    'prenom_locataire' => ['bail','required','string'],
                    'profession' => ['bail','required','string'],
                    'telephone' => ['bail','required'],
                    'mode_paiement' => ['bail','required','string'],
                    'nombre_caution' => ['bail','required','string'],
                    'nombre_avance' => ['bail','nullable','string'],
                    'date_entre' => ['bail','required'],
                ],
            );


            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()
                ]);
            }

            $occupe = Chambre::where('id',$request->numero_chambre)
                             ->where('chambres.iddirection_ref',Auth::user()->iddirection_ref)
                             ->where('chambres.idannexe_ref',Auth::user()->idannexe_ref)
                             ->whereNull('delete_at')
                             ->where('etat',1)
                             ->count();

            if ($occupe == 1) {

                return response()->json([
                        'status' => false,
                        'message' => "Cette chambre est déjà occupée",
                       ]);

            } else {

                if ($request->caution_eau == 0) {
                    $caution_eau = 0 ;
                }else{
                    $caution_eau = str_replace(" ", "", $request->caution_eau);

                }

                if ($request->caution_courant == 0) {
                    $caution_courant = 0 ;
                }else{
                    $caution_courant = str_replace(" ", "", $request->caution_courant);
                }

                // Utiliser l'annexe active centralisée
                $idannexe_ref = get_active_annexe_id();
                if (!$idannexe_ref) {
                    return response()->json([
                        'status' => false,
                        'message' => "Veuillez sélectionner une agence dans le header"
                    ]);
                }

                $locataires = Locataire::create([
                                    'maison_id' => $request->nom_maison,
                                    'chambre_id' => $request->numero_chambre,
                                    'nom'                   => Str::upper($request->nom_locataire),
                                    'prenom'                => Str::ucfirst($request->prenom_locataire),
                                    'profession' => $request->profession,
                                    'quartier'  => $this->get_house_loocation($request->nom_maison),
                                    'mode_paiement' => $request->mode_paiement,
                                    'telephone' => $request->telephone,
                                    'email' => $request->email ?: null,
                                    'nombre_caution' => $request->nombre_caution,
                                    'nombre_avance' => $request->nombre_avance,
                                    'prix_mois' => $request->prix_mois,
                                    'date_entree' => $request->date_entre,
                                    'nombre_avance_consomme' => 0,
                                    'caution_courant' => $caution_courant,
                                    'caution_eau' => $caution_eau,
                                    'status' => 1,
                                    'iddirection_ref' => Auth::user()->iddirection_ref,
                                    'idannexe_ref' => $idannexe_ref,
                                ]);


                if ($locataires) {

                   try {
                       activity()->performedOn(new Locataire())
                               ->causedBy(Auth::user()->id)
                               ->withProperties(['old' => [], 'new' => [
                                   'nom'            => Str::upper($request->nom_locataire),
                                   'prenom'         => Str::ucfirst($request->prenom_locataire),
                                   'telephone'      => $request->telephone,
                                   'profession'     => $request->profession,
                                   'mode_paiement'  => $request->mode_paiement,
                                   'prix_mois'      => $request->prix_mois,
                                   'date_entree'    => $request->date_entre,
                                   'nombre_caution' => $request->nombre_caution,
                                   'nombre_avance'  => $request->nombre_avance,
                               ]])
                               ->log('Ajout du locataire '.Str::upper($request->nom_locataire).' '.Str::ucfirst($request->prenom_locataire).' par '.Auth::user()->nom.' '.Auth::user()->prenom);
                   } catch (\Exception $e) {}

                   Chambre::where('id',$request->numero_chambre)
                            ->where('iddirection_ref', Auth::user()->iddirection_ref)
                            ->whereNull('delete_at')
                            ->where('etat',0)
                            ->update([
                                'etat' => 1
                            ]);
                 
                    $chambre = Chambre::find($request->numero_chambre);
                    $maison  = Maison::find($request->nom_maison);

                    return response()->json([
                        'status' => true,
                        'message' => "Le locataire ".Str::upper($request->nom_locataire).' '.Str::upper($request->prenom_locataire).' est bien ajouté',
                        'locataire' => [
                            'id'              => $locataires->id,
                            'encoded_id'      => encrypt_id($locataires->id),
                            'nom_maison'      => $maison ? $maison->nom_maison : '',
                            'numero_chambre'  => $chambre ? $chambre->numero_chambre : '',
                            'type_chambre'    => $chambre ? $chambre->type_chambre : '',
                            'nom'             => $locataires->nom,
                            'prenom'          => $locataires->prenom,
                            'telephone'       => $locataires->telephone,
                            'profession'      => $locataires->profession,
                            'email'           => $locataires->email,
                            'date_entree'     => $locataires->date_entree,
                            'nombre_caution'  => $locataires->nombre_caution,
                            'nombre_avance'   => $locataires->nombre_avance,
                            'caution_eau'     => $locataires->caution_eau,
                            'caution_courant' => $locataires->caution_courant,
                            'mode_paiement'   => $locataires->mode_paiement,
                        ],
                    ]);
                }

            }

        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => "Echec,essayez encore",
            ]);

        }
    }

    public function update(Request $request)
    {
        try {

                if ($request->caution_eau == 0) {
                    $caution_eau = 0 ;
                }else{
                    $caution_eau = str_replace(" ", "", $request->caution_eau);

                }

                if ($request->caution_courant == 0) {
                    $caution_courant = 0 ;
                }else{
                    $caution_courant = str_replace(" ", "", $request->caution_courant);
                }

                // Utiliser l'annexe active centralisée
                $idannexe_ref = get_active_annexe_id();
                if (!$idannexe_ref) {
                    return response()->json(['status' => false, 'message' => "Veuillez sélectionner une agence dans le header"]);
                }

                $oldLocataire = Locataire::find($request->locataire_id);

             Locataire::where('id',$request->locataire_id)
                              ->where('iddirection_ref', Auth::user()->iddirection_ref)
                              ->update([
                                    'nom'                   => Str::upper($request->nom_locataire),
                                    'prenom'                => Str::ucfirst($request->prenom_locataire),
                                    'profession' => $request->profession,
                                    'telephone' => $request->telephone,
                                    'email' => $request->email ?: null,
                                    'nombre_caution' => $request->nombre_caution,
                                    'nombre_avance' => $request->nombre_avance,
                                    'date_entree' => $request->date_entre,
                                    'caution_courant' => $caution_courant,
                                    'caution_eau' => $caution_eau,
                                    'idannexe_ref' => $idannexe_ref,
                                    'mode_paiement' => $request->mode_paiement,
                              ]);

              try {
                  activity()->performedOn(new Locataire())
                               ->causedBy(Auth::user()->id)
                               ->withProperties(['old' => $oldLocataire ? [
                                   'nom'            => $oldLocataire->nom,
                                   'prenom'         => $oldLocataire->prenom,
                                   'telephone'      => $oldLocataire->telephone,
                                   'profession'     => $oldLocataire->profession,
                                   'mode_paiement'  => $oldLocataire->mode_paiement,
                                   'nombre_caution' => $oldLocataire->nombre_caution,
                                   'nombre_avance'  => $oldLocataire->nombre_avance,
                               ] : [], 'new' => [
                                   'nom'            => Str::upper($request->nom_locataire),
                                   'prenom'         => Str::ucfirst($request->prenom_locataire),
                                   'telephone'      => $request->telephone,
                                   'profession'     => $request->profession,
                                   'mode_paiement'  => $request->mode_paiement,
                                   'nombre_caution' => $request->nombre_caution,
                                   'nombre_avance'  => $request->nombre_avance,
                               ]])
                               ->log('Mise à jour du locataire '.Str::upper($request->nom_locataire).' '.Str::ucfirst($request->prenom_locataire).' par '.Auth::user()->nom.' '.Auth::user()->prenom);
              } catch (\Exception $e) {}

              return response()->json(['status' => true, 'message' => 'Le locataire est modifié avec succès']);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Echéc, veuillez vérifier les données']);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $locataireId = $request->id ?: $request->locataire_id;
            $deletedValue = Locataire::where('id', $locataireId)
                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                ->first();

            if (!$deletedValue) {
                return response()->json(['status' => false, 'message' => 'Locataire introuvable']);
            }

            $deleted = Locataire::where('id', $locataireId)
                                  ->where('iddirection_ref', Auth::user()->iddirection_ref)
                                  ->update([
                                     'delete_at' => Carbon::now(),
                                     'status' => 0
                                   ]);

            if ($deleted) {

              try {
                  activity()->performedOn(new Locataire())
                               ->causedBy(Auth::user()->id)
                               ->withProperties(['old' => [
                                   'nom'            => $deletedValue->nom,
                                   'prenom'         => $deletedValue->prenom,
                                   'telephone'      => $deletedValue->telephone,
                                   'profession'     => $deletedValue->profession,
                                   'mode_paiement'  => $deletedValue->mode_paiement,
                                   'prix_mois'      => $deletedValue->prix_mois,
                               ], 'new' => []])
                               ->log('Suppression du locataire '.$deletedValue->nom.' '.$deletedValue->prenom.' par '.Auth::user()->nom.' '.Auth::user()->prenom);
              } catch (\Exception $e) {}

               Chambre::where('id', $deletedValue->chambre_id)
                       ->update(['etat' => 0]);

                return response()->json(['status' => true, 'message' => 'Locataire sorti avec succès']);
            }

            return response()->json(['status' => false, 'message' => 'Aucune suppression effectuée']);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Echéc, veuillez vérifier les données']);
        }
    }

}
