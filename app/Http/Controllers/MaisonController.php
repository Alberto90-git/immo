<?php

namespace App\Http\Controllers;

use App\Maison;
use App\Proprietaire;
use App\Chambre;
use App\Prix;
use App\Locataire;
use App\Facture;
use Illuminate\Http\Request; 
use App\Http\Requests\maisonReqest;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;



class MaisonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       try {
            $allProprios = Proprietaire::whereNull('delete_at')
                                        ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                        ->where(function($querry){
                                            if (Gate::none(['Is_admin'])) {
                                                $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                            }
                                        })
                                        ->get();

            $allMaison = Maison::join('proprietaires', 'maisons.proprio_id', '=', 'proprietaires.id')
                             ->whereNull('maisons.delete_at')
                             ->where('maisons.iddirection_ref',Auth::user()->iddirection_ref)
                             ->where(function($querry){
                                if (Gate::none(['Is_admin'])) {
                                    $querry->where('maisons.idannexe_ref',Auth::user()->idannexe_ref);
                                }
                            })
                             ->whereNull('proprietaires.delete_at')
                             ->select('maisons.iddirection_ref','maisons.idannexe_ref','maisons.proprio_id','maisons.id','proprietaires.nom','proprietaires.prenom','maisons.nom_maison','maisons.quartier','maisons.nombre_chambre')
                             ->get();

            return view('maison.maison', compact(['allProprios','allMaison']));

       } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
       }
    }




    public function store(Request $request)
    {
        try {

            $validator = Validator::make(
                $request->all(),
                [
                    'nom_proprietaire' => ['bail','required'],
                    'nom_maison' => ['bail','required', 'string', 'max:255'],
                    'quartier' => ['bail','required', 'string', 'max:255'],
                    'nombre_chambre' => ['bail','required', 'max:255'],
                ],
            );


            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()
                ]);
            }

            $response = $this->check_is_admin_and_entreprise();

            if ($response) {
                $idannexe_ref = $request->annexe;
            }else {
                $idannexe_ref = Auth::user()->idannexe_ref;
            }

             $maison = Maison::create([
                'proprio_id' => $request->nom_proprietaire,
                'nom_maison' => Str::ucfirst($request->nom_maison),
                'quartier' => Str::ucfirst($request->quartier),
                'nombre_chambre' => $request->nombre_chambre,
                'iddirection_ref' => Auth::user()->iddirection_ref,
                'idannexe_ref' => $idannexe_ref,
            ]);

            if ($maison) {

                activity()->performedOn(new Maison())
                           ->causedBy(Auth::user()->id)
                           ->log('Ajout de la maison '.$request->nom_maison.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return response()->json([
                    'status' => true,
                    'message' => "La maison ".$request->nom_maison." est crée avec succès",
                ]);
            }

        } catch (QueryException $e) {

            return response()->json([
                'status' => false,
                'message' => "Echec,essayé encore $e",
            ]);

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
  

   
   
    public function update(Request $request)
    {
        try {

            $response = $this->check_is_admin_and_entreprise();

            if ($response) {
                $idannexe_ref = $request->annexe;
            }else {
                $idannexe_ref = Auth::user()->idannexe_ref;
            }

             $maison = Maison::where('id',$request->house_id)
                                 ->update([
                                    'proprio_id' => $request->nom_proprietaire2,
                                    'nom_maison' =>Str::ucfirst($request->nom_maison),
                                    'quartier' => Str::ucfirst($request->quartier),
                                    'nombre_chambre' => $request->nombre_chambre,
                                    'idannexe_ref' => $idannexe_ref,
                                ]);  

            if ($maison) {

                activity()->performedOn(new Maison())
                           ->causedBy(Auth::user()->id)
                           ->log('Modification de la maison '.$request->nom_maison.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return back()->with('message','La maison '.$request->nom_maison.' est mise avec succès');
            }

        } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }

    public function destroy(Request $request)
    {
        try {
            $valueDeleted = Maison::where('id',$request->id)->first();
           
            $deleted = Maison::where('id',$request->id)->update(['delete_at' => Carbon::now()]);

            if ($deleted) {


                $tab = Maison::where('id',$request->id)->select('id')->get();

                for ($i=0; $i < count($tab) ; $i++) {

                   Chambre::where('maison_id',$tab[$i]->id)
                           ->update([
                                    'delete_at' => Carbon::now()
                            ]);

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

                activity()->performedOn(new Maison())
                           ->causedBy(Auth::user()->id)
                           ->log('Suppression de la maison '.$valueDeleted->nom_maison.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return back()->with('message','Suppression effectuée avec succès');
            }
            
        } catch (QueryException $e) {
            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }


}
