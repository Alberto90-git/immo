<?php

namespace App\Http\Controllers;

use App\Prix;
use App\Maison;
use App\Chambre;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class PrixController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function getNumeroChambre(Request $request)
    {
            $vide = '';
    
            $vide.="<option disabled selected>Choisir une chambre</option>";
    
            $val = Chambre::where('maison_id',$request->idMaison)
                          ->where('iddirection_ref',Auth::user()->iddirection_ref)
                          ->where(function($querry){
                            if (Gate::none(['Is_admin'])) {
                                $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                            }
                           })
                          ->whereNull('delete_at')
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
                            ->where('iddirection_ref',Auth::user()->iddirection_ref)
                            ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                }
                            })
                            ->get()
                            ->pluck('type_chambre')[0];
    
            return response()->json([
                                    'type_chambres_get' => $vide,
                                    ]);
    }


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

            $allChambres = Chambre::join('maisons', 'chambres.maison_id', '=', 'maisons.id')
                             ->whereNull('chambres.delete_at')
                             ->where('chambres.iddirection_ref',Auth::user()->iddirection_ref)
                             ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('chambres.idannexe_ref',Auth::user()->idannexe_ref);
                                }
                             })
                             ->whereNull('maisons.delete_at')
                             ->select('chambres.maison_id','chambres.id','maisons.nom_maison','chambres.numero_chambre','chambres.type_chambre','chambres.etat')
                             ->get();

            $allPrix = Prix::where('status',true)
                            ->where('prixes.iddirection_ref',Auth::user()->iddirection_ref)
                            ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('prixes.idannexe_ref',Auth::user()->idannexe_ref);
                                }
                            })
                            ->join('maisons', 'prixes.maison_id', '=', 'maisons.id')
                            ->join('chambres', 'prixes.chambre_id', '=', 'chambres.id')
                             ->whereNull('prixes.delete_at')
                            ->whereNull('maisons.delete_at')
                            ->whereNull('chambres.delete_at')
                            ->select('prixes.iddirection_ref','prixes.idannexe_ref','maisons.nom_maison','chambres.numero_chambre','chambres.type_chambre','prixes.prix','prixes.id','prixes.maison_id','prixes.chambre_id')
                            ->get();


            return view('prix.prix', compact(['allChambres','allMaison','allPrix']));

       } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
       }
    }

    public function connaitreMaison($id)
    {
        $dd =  Maison::where('id',$id)
                    ->whereNull('delete_at')
                    ->select('nom_maison')
                    ->first();

        return  $dd->nom_maison;

    }


    public function connaitreNumChambre($id)
    {
        $dd =  Chambre::where('id',$id)
                    ->whereNull('delete_at')
                    ->select('numero_chambre')
                    ->first();
        return $dd->numero_chambre;

    }

    
    public function store(Request $request)
    {
        try {

            $validator = Validator::make(
                $request->all(),
                [
                    'nom_maison' => ['bail','required'],
                    'numero_chambre' => ['bail','required'],
                    'prix' => ['bail','required'],
                ],
            );


            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()
                ]);
            }

            $exist = Prix::where('maison_id',$request->nom_maison)
                           ->where('chambre_id',$request->numero_chambre)
                           ->where('status',true)
                           ->whereNull('delete_at')
                           ->count();

            if ($exist == 1) {

                return response()->json([
                    'status' => false,
                    'message' => "Prix déjà défini pour cette chambre",
                ]);

            }else{

                $response = $this->check_is_admin_and_entreprise();

                if ($response) {
                    $idannexe_ref = $request->annexe;
                }else {
                    $idannexe_ref = Auth::user()->idannexe_ref;
                }

                $prix = Prix::create([
                                        'maison_id' => $request->nom_maison,
                                        'chambre_id' => $request->numero_chambre,
                                        'prix' => str_replace(" ", "", $request->prix),
                                        'status' => 1,
                                        'iddirection_ref' => Auth::user()->iddirection_ref,
                                        'idannexe_ref' => $idannexe_ref,
                                    ]);

                if ($prix) {

                    activity()->performedOn(new Prix())
                           ->causedBy(Auth::user()->id)
                           ->log('Prix défini pour la chambre n° '.PrixController::connaitreNumChambre($request->numero_chambre).' / maison: '.PrixController::connaitreMaison($request->nom_maison).' par ' .Auth::user()->nom.' '.Auth::user()->prenom);

                    return response()->json([
                        'status' => true,
                        'message' => "Prix bien défini pour cette chambre",
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

            $response = $this->check_is_admin_and_entreprise();

            if ($response) {
                $idannexe_ref = $request->annexe;
            }else {
                $idannexe_ref = Auth::user()->idannexe_ref;
            }

             $prixOff = Prix::where('id',$request->prix_id)
                                 ->update([
                                    'status' => 0,
                                    'idannexe_ref' => $idannexe_ref,
                                ]);
            if ($prixOff) {
                
                 $prix = Prix::create([
                                    'maison_id' => $request->maison_id2,
                                    'chambre_id' => $request->chambre_id2,
                                    'prix' => $request->prix,
                                    'status' => 1,
                                    'iddirection_ref' => Auth::user()->iddirection_ref,
                                    'idannexe_ref' => $idannexe_ref,
                                ]);

                if ($prix) {

                  activity()->performedOn(new Prix())
                           ->causedBy(Auth::user()->id)
                           ->log('Prix modifié pour la chambre n° '.PrixController::connaitreNumChambre($request->chambre_id2).' / maison: '.PrixController::connaitreMaison($request->maison_id2).' par ' .Auth::user()->nom.' '.Auth::user()->prenom);


                    return back()->with('message','Le prix est modifié avec succès');
               }
            }

        } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }

    public function destroy(Request $request)
    {
        try {
            $deleted = Prix::where('id',$request->prix_id)->update(['delete_at' => Carbon::now()]);

            if ($deleted) {

              activity()->performedOn(new Prix())
                           ->causedBy(Auth::user()->id)
                           ->log('Suppression du prix pour la chambre n° '.PrixController::connaitreNumChambre($request->chambre_id22).' / maison: '.PrixController::connaitreMaison($request->maison_id22).' par ' .Auth::user()->nom.' '.Auth::user()->prenom);

                return back()->with('message','Suppression effectuée avec succès');
            }
            
        } catch (QueryException $e) {
            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }


}
