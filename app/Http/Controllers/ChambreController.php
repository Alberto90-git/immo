<?php

namespace App\Http\Controllers;

use App\Chambre;
use App\Maison;
use App\Prix;
use App\Locataire;
use App\Facture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class ChambreController extends Controller
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
                                        $querry->where('maisons.idannexe_ref',Auth::user()->idannexe_ref);
                                    }
                                })
                                ->get();

            $allChambres = Chambre::join('maisons', 'chambres.maison_id', '=', 'maisons.id')
                             ->whereNull('chambres.delete_at')
                             ->whereNull('maisons.delete_at')
                             ->where('chambres.iddirection_ref',Auth::user()->iddirection_ref)
                             ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('chambres.idannexe_ref',Auth::user()->idannexe_ref);
                                }
                            })
                             ->select('chambres.prix_chambre','chambres.iddirection_ref','chambres.idannexe_ref','chambres.maison_id','chambres.id','maisons.nom_maison','chambres.numero_chambre','chambres.type_chambre','chambres.etat')
                             ->get();


            return view('chambre.chambre', compact(['allChambres','allMaison']));

       } catch (QueryException $e) {

            return back()->with('error',"Echéc, veuillez verifier les données");
       }
    }


    private function add_price($nom_maison,$numero_chambre,$annexe,$prix)
    {
        $exist = Prix::where('maison_id',$nom_maison)
                           ->where('chambre_id',$numero_chambre)
                           ->where('status',true)
                           ->whereNull('delete_at')
                           ->count();

        if ($exist == 1) {

            return false;

        }else{

            $response = $this->check_is_admin_and_entreprise();

            if ($response) {
                $idannexe_ref = $annexe;
            }else {
                $idannexe_ref = Auth::user()->idannexe_ref;
            }

            $prix = Prix::create([
                                    'maison_id' => $nom_maison,
                                    'chambre_id' => $numero_chambre,
                                    'prix' => str_replace(" ", "", $prix),
                                    'status' => 1,
                                    'iddirection_ref' => Auth::user()->iddirection_ref,
                                    'idannexe_ref' => $idannexe_ref,
                                ]);

            if ($prix) {

                return true;
            }
        }
    }


    



    public function store(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nom_maison' => 'bail|required',
                    'type_chambre' => 'bail|required|string',
                    'numero_chambre' => 'bail|required|string',
                ],
            );


            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()
                ]);
            }

           // dd(Auth::user()->idannexe_ref);

            $nombreChambre = Maison::where('id',$request->nom_maison)
                                    ->whereNull('delete_at')
                                    ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                    ->where('idannexe_ref',$request->annexe)
                                    ->get()
                                    ->pluck('nombre_chambre')[0];

          // dd($nombreChambre);

            $nombreChambreSaved = Chambre::where('maison_id',$request->nom_maison)
                                         ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                         ->where('idannexe_ref',$request->annexe)
                                         ->where('etat',false)
                                         ->whereNull('delete_at')
                                         ->count();

           //dd($nombreChambreSaved);
          // dd("ici");


            if ($nombreChambreSaved == $nombreChambre) {

                return response()->json([
                                         'status' => false,
                                         'message' => "Le nombre maximal de chambre pour cette maison est ".$nombreChambre,
                                        ]);
            }else {

                //VERIFIER SI LA CHAMBRE EXISTE DANS CETTE DEJA
                $chambreExiste = Chambre::where('numero_chambre', $request->numero_chambre)
                                          ->where('maison_id', $request->nom_maison)
                                          ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                         ->where('idannexe_ref',$request->annexe)
                                          ->whereNull('delete_at')
                                          ->count();

                if ($chambreExiste == 1) {

                  return response()->json([
                            'status' => false,
                            'message' => 'Le n°'.$request->numero_chambre." est déjà attribué à une chambre dans cette maison",
                      ]);

                }else{

                    $response = $this->check_is_admin_and_entreprise();

                    if ($response) {
                        $idannexe_ref = $request->annexe;
                    }else {
                        $idannexe_ref = Auth::user()->idannexe_ref;
                    }

                  $chambreId = Chambre::insertGetId([
                                            'maison_id' => $request->nom_maison,
                                            'numero_chambre' => $request->numero_chambre,
                                            'type_chambre' => $request->type_chambre,
                                            'prix_chambre' => str_replace(" ", "", $request->prix),
                                            'etat' => false,
                                            'iddirection_ref' => Auth::user()->iddirection_ref,
                                            'idannexe_ref' => $idannexe_ref,
                                            ]);

                        $prix = Prix::create([
                                                'maison_id' => $request->nom_maison,
                                                'chambre_id' => $chambreId,
                                                'prix' => str_replace(" ", "", $request->prix),
                                                'status' => 1,
                                                'iddirection_ref' => Auth::user()->iddirection_ref,
                                                'idannexe_ref' => $idannexe_ref,
                                            ]);

                  if ($chambreId) {

                    //$this->add_price($request->nom_maison,$request->numero_chambre,$request->annexe,$request->prix);

                    activity()->performedOn(new Chambre())
                           ->causedBy(Auth::user()->id)
                           ->log('Ajout de la chambre N° '.$request->numero_chambre.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                      return response()->json([
                            'status' => true,
                            'message' => 'Chambre N°'.$request->numero_chambre." est crée avec succès",
                      ]);
                  }

                }
                
            }
        }
        catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => "Echec,essayez encore",
            ]);
        }
    }


    private function update_prix($iddirection_ref,$idannexe_ref,$maison_id,$chambre_id,$prix)
    {
        try {

            $response = $this->check_is_admin_and_entreprise();

            if ($response) {
                $idannexe_ref = $idannexe_ref;
            }else {
                $idannexe_ref = Auth::user()->idannexe_ref;
            }

            
             $prixOff = Prix::where('iddirection_ref',$iddirection_ref)
                            ->where('idannexe_ref',$idannexe_ref)
                            ->where('maison_id',$maison_id)
                            ->where('chambre_id',$chambre_id)
                            ->update([
                            'status' => 0,
                            'idannexe_ref' => $idannexe_ref,
                          ]);
            if ($prixOff) {
                
                 $prix = Prix::create([
                                    'maison_id' => $maison_id,
                                    'chambre_id' => $chambre_id,
                                    'prix' => $prix,
                                    'status' => 1,
                                    'iddirection_ref' => Auth::user()->iddirection_ref,
                                    'idannexe_ref' => $idannexe_ref,
                                ]);

                if ($prix) {

                //   activity()->performedOn(new Prix())
                //            ->causedBy(Auth::user()->id)
                //            ->log('Prix modifié pour la chambre n° '.PrixController::connaitreNumChambre($request->chambre_id2).' / maison: '.PrixController::connaitreMaison($request->maison_id2).' par ' .Auth::user()->nom.' '.Auth::user()->prenom);
                return true;
               }
            }

        } catch (QueryException $e) {
            return false;
            //return back()->with('error','Echéc, veuillez verifier les données');
        }
    }


    
    public function update(Request $request)
    {
        try {

           //VERIFIER SI LA CHAMBRE EXISTE DANS CETTE DEJA

           $response = $this->check_is_admin_and_entreprise();

           if ($response) {
               $idannexe_ref = $request->annexe;
           }else {
               $idannexe_ref = Auth::user()->idannexe_ref;
           }

           $existe_deja = Chambre::where('id',$request->chambre_id)
                                    ->whereNull('delete_at')
                                    ->first();

            $exit_chambre_numero = Chambre::where('id',$request->chambre_id)
                                            ->whereNull('delete_at')
                                            ->where('numero_chambre',$request->numero_chambre)
                                            ->first();


            $house_existe = Chambre::where('id',$request->chambre_id)
                                    ->whereNull('delete_at')
                                    ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                    ->where('idannexe_ref',$idannexe_ref)
                                    ->where('maison_id',$request->nom_maison)
                                    //->where('numero_chambre',$request->numero_chambre)
                                    ->count();

                if ( ($exit_chambre_numero?->numero_chambre == $request->numero_chambre) &&  $house_existe == 1) {

                    $chambre = Chambre::where('id',$request->chambre_id)
                                ->update([
                                    'maison_id' => $request->nom_maison,
                                    'numero_chambre' => $request->numero_chambre,
                                    'type_chambre' => $request->type_chambre,
                                    'prix_chambre' => str_replace(" ", "", $request->prix),
                                    'idannexe_ref' => $idannexe_ref,
                                ]);

                    if ($chambre) {

                        if ($existe_deja->prix_chambre != str_replace(" ", "", $request->prix)) {
                            //TODO LOG FOR FAILLED ON PRICE UPDATE
                            $this->update_prix(Auth::user()->iddirection_ref,$idannexe_ref,$request->nom_maison,$request->chambre_id,str_replace(" ", "", $request->prix));
                        }

                    
                        activity()->performedOn(new Chambre())
                           ->causedBy(Auth::user()->id)
                           ->log('Modification de la chambre N° '.$request->numero_chambre.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                       return back()->with('message','Chambre N°'.$request->numero_chambre." est mise à jour avec succès");
                    }

                }else {
                    if($exit_chambre_numero?->numero_chambre == null){
                        return back()->with('error','Le N°'.$request->numero_chambre." n'est encore attribué à aucune chambre dans cette maison");
                    }else {
                        return back()->with('error','Chambre N°'.$request->numero_chambre." existe déjà dans cette maison");
                    }
                }
            
        }
        catch (QueryException $e) {
            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }

    
    public function destroy(Request $request)
    {
        try {
            
            $valueDeleted = Chambre::where('id',$request->id)->first();
            
            $occupe = Chambre::where('id',$request->id)
                            ->where('etat',1)        
                            ->whereNull('delete_at')        
                            ->count();

            if($occupe == 1){
                return back()->with('error',"Echéc, la chambre est toujours occupé par un locataire, veuillez le faire sortie d'abord");
            }
            
            $deleted = Chambre::where('id',$request->id)
                               ->update([
                                        'delete_at' => Carbon::now()
                                       ]);

            if ($deleted) {

               $tab = Chambre::where('id',$request->id)->select('id')->get();

                for ($i=0; $i < count($tab) ; $i++) {

                    Prix::where('maison_id',$tab[$i]->id)
                           ->update([
                                    'delete_at' => Carbon::now()
                            ]);

                    Locataire::where('maison_id',$tab[$i]->id)
                           ->update([
                                    'delete_at' => Carbon::now()
                            ]);

                    Facture::where('id',$tab[$i]->id)
                           ->update([
                                    'delete_at' => Carbon::now()
                            ]);
                }

              activity()->performedOn(new Chambre())
                           ->causedBy(Auth::user()->id)
                           ->log('Suppression de la chambre N° '.$valueDeleted->numero_chambre.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return back()->with('message','Suppression effectuée avec succès');
            }
            
        } catch (QueryException $e) {
            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }
}
