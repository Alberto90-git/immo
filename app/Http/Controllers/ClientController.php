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

            // Utiliser l'annexe active centralisée
            $idannexe_ref = get_active_annexe_id();
            if (!$idannexe_ref) {
                return response()->json([
                    'status' => false,
                    'message' => "Veuillez sélectionner une agence dans le header"
                ]);
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
                           ->withProperties(['old' => [], 'new' => [
                               'nom'        => Str::upper($request->nom),
                               'prenom'     => Str::ucfirst($request->prenom),
                               'telephone'  => $request->telephone,
                               'zone_voulu' => Str::ucfirst($request->zone),
                               'superficie' => $request->superficie,
                               'budget'     => str_replace(" ", "", $request->budget),
                           ]])
                           ->log('Ajout du client '.Str::upper($request->nom).' '.Str::ucfirst($request->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return response()->json([
                    'status'  => true,
                    'message' => "Client bien ajouté avec succès",
                    'client'  => [
                        'id'         => $terrain->id,
                        'nom'        => $terrain->nom,
                        'prenom'     => $terrain->prenom,
                        'telephone'  => $terrain->telephone,
                        'zone'       => $terrain->zone_voulu,
                        'superficie' => $terrain->superficie,
                        'budget'     => $terrain->budget,
                    ],
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

            // Utiliser l'annexe active centralisée
            $idannexe_ref = get_active_annexe_id();
            if (!$idannexe_ref) {
                return response()->json(['status' => false, 'message' => "Veuillez sélectionner une agence dans le header"]);
            }

            $dirId = Auth::user()->iddirection_ref;
            $oldClient = Client::where('id', $request->id)
                ->where('iddirection_ref', $dirId)
                ->first();

            if (!$oldClient) {
                return response()->json(['status' => false, 'message' => 'Client introuvable']);
            }

            $terrain = Client::where('id',$request->id)
                                    ->where('iddirection_ref', $dirId)
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
                           ->withProperties(['old' => $oldClient ? [
                               'nom'        => $oldClient->nom,
                               'prenom'     => $oldClient->prenom,
                               'telephone'  => $oldClient->telephone,
                               'zone_voulu' => $oldClient->zone_voulu,
                               'budget'     => $oldClient->budget,
                           ] : [], 'new' => [
                               'nom'        => Str::upper($request->nom),
                               'prenom'     => Str::ucfirst($request->prenom),
                               'telephone'  => $request->telephone,
                               'zone_voulu' => Str::ucfirst($request->zone),
                               'budget'     => str_replace(" ", "", $request->budget),
                           ]])
                           ->log('Modification du client '.Str::upper($request->nom).' '.Str::ucfirst($request->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return response()->json(['status' => true, 'message' => Str::upper($request->nom).' '.Str::upper($request->prenom).' mis à jour avec succès']);
            }
        }
        catch (QueryException $e) {
            return response()->json(['status' => false, 'message' => 'Echéc, veuillez vérifier les données']);
        }
    }


    public function delete(Request $request)
    {
        try {

            $dirId = Auth::user()->iddirection_ref;
            $objetDeleted = Client::where('id', $request->id)
                ->where('iddirection_ref', $dirId)
                ->first();

            if (!$objetDeleted) {
                return response()->json(['status' => false, 'message' => 'Client introuvable']);
            }

            $deleted = Client::where('id',$request->id)
                                   ->where('iddirection_ref', $dirId)
                                   ->update([
                                            'delete_at' => Carbon::now()
                                    ]);


            if ($deleted) {
                
                 activity()->performedOn(new Client())
                           ->causedBy(Auth::user()->id)
                           ->withProperties(['old' => [
                               'nom'        => $objetDeleted->nom,
                               'prenom'     => $objetDeleted->prenom,
                               'telephone'  => $objetDeleted->telephone,
                               'zone_voulu' => $objetDeleted->zone_voulu,
                               'budget'     => $objetDeleted->budget,
                           ], 'new' => []])
                           ->log('Suppression du client '.Str::upper($objetDeleted->nom).' '.Str::ucfirst($objetDeleted->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return response()->json(['status' => true, 'message' => 'Suppression effectuée avec succès']);
            }
        } catch (QueryException $e) {
            return response()->json(['status' => false, 'message' => 'Echéc, veuillez vérifier les données']);
        }
    }


    public function cloturer(Request $request)
    {
        try {

            $dirId = Auth::user()->iddirection_ref;
            $objetcloture = Client::where('id', $request->id)->where('iddirection_ref', $dirId)->first();

            if (!$objetcloture) {
                return response()->json(['status' => false, 'message' => 'Client introuvable']);
            }

            $cloture = Client::where('id',$request->id)
                                   ->where('iddirection_ref', $dirId)
                                   ->update([
                                            'status' => Carbon::now()
                                    ]);


            if ($cloture) {
                
                 activity()->performedOn(new Client())
                           ->causedBy(Auth::user()->id)
                           ->withProperties(['old' => ['etat' => 'buy'], 'new' => ['etat' => 'clôturé']])
                           ->log('Cloturé le dossier de '.Str::upper($objetcloture->nom).' '.Str::ucfirst($objetcloture->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return response()->json(['status' => true, 'message' => 'Dossier cloturé avec succès']);
            }
        } catch (QueryException $e) {
            return response()->json(['status' => false, 'message' => 'Echéc, veuillez vérifier les données']);
        }
    }

   
}
