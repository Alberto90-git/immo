<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;


class ClientController extends Controller
{
    
    public function index()
    {
        $all_customers = Client::whereNull('delete_at')
                                ->where(function ($query) {
                                    $query->whereMonth('status',Carbon::now()->format('m'));
                                    $query->orwhereNull('status');
                                })
                                ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                  ->where(function($querry){
                                    if (Gate::none(['Is_admin'])) {
                                        $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                    }
                                })
                                ->orderBy('status','asc')
                                ->get();

        return view('client.client',['all_customers' => $all_customers]);
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nom' => 'bail|required|string',
                    'prenom' => 'bail|required|string',
                    'telephone' => 'bail|required',
                    'zone' => 'bail|required|string',
                    'budget' => 'bail|required|string',
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

            $terrain = Client::create([
                                    'nom'  => Str::upper($request->nom),
                                    'prenom'  => Str::ucfirst($request->prenom) ,
                                    'telephone' => $request->telephone,
                                    'zone_voulu' => Str::ucfirst($request->zone),
                                    'superficie' => $request->superficie,
                                    'budget' => str_replace(" ", "",$request->budget),
                                    'etat' => 'buy',
                                    'iddirection_ref' => Auth::user()->iddirection_ref,
                                    'idannexe_ref' => $idannexe_ref,
                                ]);


            if ($terrain) {

                activity()->performedOn(new Client())
                           ->causedBy(Auth::user()->id)
                           ->log('Ajout du client '.Str::upper($request->nom).' '.Str::ucfirst($request->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return response()->json([
                    'status' => true,
                    'message' => "Client bien ajouté avec succès",
                ]);

            }
        }
        catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => "Echec,essayez encore",
            ]);
        }
    }


    public function update(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nom' => 'bail|required|string',
                    'prenom' => 'bail|required|string',
                    'telephone' => 'bail|required',
                    'zone' => 'bail|required|string',
                    'budget' => 'bail|required|string',
                ],
            );

            $response = $this->check_is_admin_and_entreprise();

            if ($response) {
                $idannexe_ref = $request->annexe;
            }else {
                $idannexe_ref = Auth::user()->idannexe_ref;
            }


            $terrain = Client::where('id',$request->id)
                                    ->update([
                                        'nom'  => Str::upper($request->nom),
                                        'prenom'  => Str::ucfirst($request->prenom) ,
                                        'telephone' => $request->telephone,
                                        'zone_voulu' => Str::ucfirst($request->zone),
                                        'superficie' => $request->superficie,
                                        'budget' => str_replace(" ", "", $request->budget),
                                        'iddirection_ref' => Auth::user()->iddirection_ref,
                                        'idannexe_ref' => $idannexe_ref,
                                    ]);
            if ($terrain) {

                 activity()->performedOn(new Client())
                           ->causedBy(Auth::user()->id)
                           ->log('Modification du client '.Str::upper($request->nom).' '.Str::ucfirst($request->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return back()->with('message', Str::upper($request->nom).' '.Str::upper($request->prenom).' mise à jour avec succès');
            }
        }
        catch (QueryException $e) {
            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }


    public function delete(Request $request)
    {
        try {

            $deleted = Client::where('id',$request->id)
                                   ->update([
                                            'delete_at' => Carbon::now()
                                    ]);

            $objetDeleted = Client::where('id',$request->id)
                                   ->first();


            if ($deleted) {
                
                 activity()->performedOn(new Client())
                           ->causedBy(Auth::user()->id)
                           ->log('Suppression du client '.Str::upper($objetDeleted->nom).' '.Str::ucfirst($objetDeleted->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return back()->with('message','Suppression effectuée avec succès');
                
            }
        } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }


    public function cloturer(Request $request)
    {
        try {

            $cloture = Client::where('id',$request->id)
                                   ->update([
                                            'status' => Carbon::now()
                                    ]);

            $objetcloture = Client::where('id',$request->id)
                                   ->first();


            if ($cloture) {
                
                 activity()->performedOn(new Client())
                           ->causedBy(Auth::user()->id)
                           ->log('Cloturé le dossier de '.Str::upper($objetcloture->nom).' '.Str::ucfirst($objetcloture->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return back()->with('message','Dossier cloturé avec succès');
                
            }
        } catch (QueryException $e) {
            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }

   
}
