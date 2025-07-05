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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
 public function getProprietaire()
 {
     return Proprietaire::whereNull('delete_at')
                        ->where('iddirection_ref',Auth::user()->iddirection_ref)
                        ->where(function($querry){
                            if (Gate::none(['Is_admin'])) {
                                $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                            }
                        })
                        ->get();
 }

    public function index()
    {
        $allProprios = $this->getProprietaire();
        return view('proprietaire.proprietaire', compact('allProprios'));
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
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

            $proprio = Proprietaire::create([
                'nom'                   => Str::upper($request->nom),
                'prenom'                => Str::ucfirst($request->prenom),
                'telephone' => $request->telephone,
                'adresse' => Str::ucfirst($request->adresse),
                'iddirection_ref' => Auth::user()->iddirection_ref,
                'idannexe_ref' => $idannexe_ref,
            ]);

            if ($proprio) {

                activity()->performedOn(new Proprietaire())
                           ->causedBy(Auth::user()->id)
                           ->log('Ajout du propriétaire'.Str::upper($request->nom).' '.Str::ucfirst($request->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return response()->json([
                    'status' => true,
                    'message' => Str::upper($request->nom).' '.Str::ucfirst($request->prenom)." est crée avec succès",
                ]);

            }
        }
        catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => "Echec,essayé encore",
            ]);
        }
    }

   
    public function update(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'telephone' => 'bail|required|numeric|digits:8',
                    'nom' => 'bail|required|string',
                    'prenom' => 'bail|required|string',
                    'adresse' => 'bail|required|string',
                ],
            );


            //if ($validator->fails()) {
              //   return back()->withErrors('message','Proprietaire ajouté avec succès')->withInput();
            //}

            $response = $this->check_is_admin_and_entreprise();

            if ($response) {
                $idannexe_ref = $request->annexe;
            }else {
                $idannexe_ref = Auth::user()->idannexe_ref;
            }

            $proprio = Proprietaire::where('id',$request->id)
                                    ->update([
                                        'nom'                   => Str::upper($request->nom),
                                        'prenom'                => Str::ucfirst($request->prenom),
                                        'telephone' => $request->telephone,
                                        'adresse' => Str::ucfirst($request->adresse),
                                        'iddirection_ref' => Auth::user()->iddirection_ref,
                                        'idannexe_ref' => $idannexe_ref,
                                    ]);
            if ($proprio) {

                 activity()->performedOn(new Proprietaire())
                           ->causedBy(Auth::user()->id)
                           ->log('Modification du propriétaire'.Str::upper($request->nom).' '.Str::ucfirst($request->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return back()->with('message', Str::upper($request->nom).' '.Str::upper($request->prenom).' mis à jour avec succès');
            }
        }
        catch (QueryException $e) {
            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }

   
    public function destroy(Request $request)
    {
        try {

            $deleted = Proprietaire::where('id',$request->id)
                                   ->update([
                                            'delete_at' => Carbon::now()
                                    ]);

            $objetDeleted = Proprietaire::where('id',$request->id)
                                   ->first();


            if ($deleted) {

                $tab = Maison::where('proprio_id',$request->id)->select('id')->get();


                for ($i=0; $i < count($tab) ; $i++) {

                    Maison::where('id',$tab[$i]->id)
                           ->update([
                                    'delete_at' => Carbon::now()
                            ]);

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

                    Facture::where('maison_id',$tab[$i]->id)
                           ->update([
                                    'delete_at' => Carbon::now()
                            ]);
                }

                
                 activity()->performedOn(new Proprietaire())
                           ->causedBy(Auth::user()->id)
                           ->log('Suppression du propriétaire'.Str::upper($objetDeleted->nom).' '.Str::ucfirst($objetDeleted->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return back()->with('message','Suppression effectuée avec succès');
                
            }
        } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }
}
