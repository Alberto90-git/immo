<?php

namespace App\Http\Controllers;

use App\Locataire;
use App\Maison;
use App\Chambre;
use App\Prix;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Codedge\Fpdf\Fpdf\Fpdf;
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

            $allMaison = Maison::whereNull('delete_at')
                                ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                ->where(function($querry){
                                    if (Gate::none(['Is_admin'])) {
                                        $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                    }
                                })
                                ->get();

            $allLocataire = Locataire::where('locataires.status',true)
                                      ->whereNull('locataires.delete_at')
                                      ->where('locataires.iddirection_ref',Auth::user()->iddirection_ref)
                                      ->where(function($querry){
                                        if (Gate::none(['Is_admin'])) {
                                            $querry->where('locataires.idannexe_ref',Auth::user()->idannexe_ref);
                                        }
                                      })
                                      ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
                                      ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
                                      ->whereNull('maisons.delete_at')
                                      ->whereNull('chambres.delete_at')
                                      ->select('locataires.iddirection_ref','locataires.idannexe_ref','maisons.nom_maison','chambres.numero_chambre','chambres.type_chambre','locataires.nom','locataires.prenom','locataires.profession','locataires.telephone','locataires.nombre_avance','locataires.date_entree','locataires.id','locataires.chambre_id','locataires.nombre_avance_consomme','locataires.caution_courant','locataires.caution_eau')
                                      ->get();


            return view('locataire.locataire', compact(['allMaison','allLocataire']));

       } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
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

            $vide = Chambre::where('id',$request->numero_chambre_got)
                            ->where('chambres.iddirection_ref',Auth::user()->iddirection_ref)
                            ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('chambres.idannexe_ref',Auth::user()->idannexe_ref);
                                }
                            })
                            ->whereNull('delete_at')
                            ->where('etat',false)
                            ->get()
                            ->pluck('type_chambre')[0];
    
            return response()->json([
                                    'type_chambres_get' => $vide,
                                    ]);
    }

    public function getPrix(Request $request)
    {

            $vide = Prix::where('chambre_id',$request->prixGot)
                            ->where('iddirection_ref',Auth::user()->iddirection_ref)
                            ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                }
                            })
                           ->whereNull('delete_at')
                           ->where('status',true)
                            ->get()
                            ->pluck('prix')[0];
          
            return response()->json([
                                      'prixApayer' => $vide,
                                    ]);
            
    }

    private function get_house_loocation($id)
    {
         return Maison::where('id',$id)
                        ->whereNull('delete_at')
                        ->get()
                        ->pluck('quartier')[0];
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
                    'nombre_avance' => ['bail','required','string'],
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


                $response = $this->check_is_admin_and_entreprise();

                if ($response) {
                    $idannexe_ref = $request->annexe;
                }else {
                    $idannexe_ref = Auth::user()->idannexe_ref;
                }
                
                $locataires = Locataire::create([
                                    'maison_id' => $request->nom_maison,
                                    'chambre_id' => $request->numero_chambre,
                                    'nom'                   => Str::upper($request->nom_locataire),
                                    'prenom'                => Str::ucfirst($request->prenom_locataire),
                                    'profession' => $request->profession,
                                    'quartier'  => $this->get_house_loocation($request->nom_maison),
                                    'telephone' => $request->telephone,
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

                   activity()->performedOn(new Locataire())
                           ->causedBy(Auth::user()->id)
                           ->log('Ajout du locataire '.Str::upper($request->nom_locataire).' '.Str::ucfirst($request->prenom_locataire).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                   Chambre::where('id',$request->numero_chambre)
                            ->whereNull('delete_at')
                            ->where('etat',0)
                            ->update([
                                'etat' => 1
                            ]);
                 
                    return response()->json([
                        'status' => true,
                        'message' => "Le locataire ".Str::upper($request->nom_locataire).' '.Str::upper($request->prenom_locataire).' est bien ajouté',
                    ]);
                }

            }

        } catch (QueryException $e) {

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

                $response = $this->check_is_admin_and_entreprise();

                if ($response) {
                    $idannexe_ref = $request->annexe;
                }else {
                    $idannexe_ref = Auth::user()->idannexe_ref;
                }
                
             $locataire = Locataire::where('id',$request->locataire_id)
                              ->update([
                                    'nom'                   => Str::upper($request->nom_locataire),
                                    'prenom'                => Str::ucfirst($request->prenom_locataire),
                                    'profession' => $request->profession,
                                    'telephone' => $request->telephone,
                                    'nombre_avance' => $request->nombre_avance,
                                    'date_entree' => $request->date_entre,
                                    //'nombre_avance_consomme' => 0,
                                     'caution_courant' => $caution_courant,
                                    'caution_eau' => $caution_eau,
                                    'idannexe_ref' => $idannexe_ref,
                              ]);

            if ($locataire) {

              activity()->performedOn(new Locataire())
                           ->causedBy(Auth::user()->id)
                           ->log('Mise à jour du locataire '.Str::upper($request->nom_locataire).' '.Str::ucfirst($request->prenom_locataire).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

              return back()->with('message','Le locataire est modifié avec succès');
            }

        } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }

    public function destroy(Request $request)
    {
        try {
            $deletedValue = Locataire::where('id',$request->locataire_id)->first();
            
            $deleted = Locataire::where('id',$request->locataire_id)
                                  ->update([
                                     'delete_at' => Carbon::now(),
                                     'status' => 0
                                   ]);

            if ($deleted) {

              activity()->performedOn(new Locataire())
                           ->causedBy(Auth::user()->id)
                           ->log('Suppression du locataire '.$deletedValue->nom.' '.$deletedValue->prenom.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

               Chambre::where('id',$request->chambre_id)
                       ->update([
                                 'etat' => 0 
                       ]);

                return back()->with('message','Suppression effectuée avec succès');
            }
            
        } catch (QueryException $e) {
            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }

}
