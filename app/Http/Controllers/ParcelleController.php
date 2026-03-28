<?php

namespace App\Http\Controllers;

use App\Parcelle;
use App\Client;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;



class ParcelleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $all_terrain = Parcelle::whereNull('delete_at')
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

        $all_customers = Client::whereNull('delete_at')
                                ->where(function ($query) {
                                    $query->orwhereNull('status');
                                })
                                ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                  ->where(function($querry){
                                    if (Gate::none(['Is_admin'])) {
                                        $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                    }
                                })
                                ->get();




        return view('terrain.parcelle',[
                                         'all_terrain' => $all_terrain,
                                         'all_customers' => $all_customers
                                      ]);
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
                    'quartier' => 'bail|required|string',
                    'prix' => 'bail|required|string',
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

            $terrain = Parcelle::create([
                                    'nom'  => Str::upper($request->nom),
                                    'prenom'  => Str::ucfirst($request->prenom) ,
                                    'telephone' => $request->telephone,
                                    'quartier' => Str::ucfirst($request->quartier),
                                    'superficie' => $request->superficie,
                                    'prix' => str_replace(" ", "", $request->prix),
                                    'etat' => 'to_selle',
                                    'iddirection_ref' => Auth::user()->iddirection_ref,
                                    'idannexe_ref' => $idannexe_ref,
                                ]);

            if ($terrain) {

                activity()->performedOn(new Parcelle())
                           ->causedBy(Auth::user()->id)
                           ->withProperties(['old' => [], 'new' => [
                               'nom'        => Str::upper($request->nom),
                               'prenom'     => Str::ucfirst($request->prenom),
                               'telephone'  => $request->telephone,
                               'quartier'   => Str::ucfirst($request->quartier),
                               'superficie' => $request->superficie,
                               'prix'       => str_replace(" ", "", $request->prix),
                           ]])
                           ->log('Ajout de la parcelle de '.Str::upper($request->nom).' '.Str::ucfirst($request->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return response()->json([
                    'status'   => true,
                    'message'  => "Parcelle bien ajoutée avec succès",
                    'parcelle' => [
                        'id'         => $terrain->id,
                        'nom'        => $terrain->nom,
                        'prenom'     => $terrain->prenom,
                        'telephone'  => $terrain->telephone,
                        'quartier'   => $terrain->quartier,
                        'superficie' => $terrain->superficie,
                        'prix'       => $terrain->prix,
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
                    'quartier' => 'bail|required|string',
                    'prix' => 'bail|required',
                ],
            );

            // Utiliser l'annexe active centralisée
            $idannexe_ref = get_active_annexe_id();
            if (!$idannexe_ref) {
                return response()->json(['status' => false, 'message' => "Veuillez sélectionner une agence dans le header"]);
            }

            $oldParcelle = Parcelle::find($request->id);

            $terrain = Parcelle::where('id',$request->id)
                                    ->update([
                                        'nom'       => Str::upper($request->nom),
                                        'prenom'    => Str::ucfirst($request->prenom),
                                        'telephone' => $request->telephone,
                                        'quartier'  => Str::ucfirst($request->quartier),
                                        'prix'      => str_replace(" ", "", $request->prix),
                                        'iddirection_ref' => Auth::user()->iddirection_ref,
                                        'idannexe_ref' => $idannexe_ref,
                                    ]);
            if ($terrain) {

                 activity()->performedOn(new Parcelle())
                           ->causedBy(Auth::user()->id)
                           ->withProperties(['old' => $oldParcelle ? [
                               'nom'        => $oldParcelle->nom,
                               'prenom'     => $oldParcelle->prenom,
                               'telephone'  => $oldParcelle->telephone,
                               'quartier'   => $oldParcelle->quartier,
                               'prix'       => $oldParcelle->prix,
                           ] : [], 'new' => [
                               'nom'        => Str::upper($request->nom),
                               'prenom'     => Str::ucfirst($request->prenom),
                               'telephone'  => $request->telephone,
                               'quartier'   => Str::ucfirst($request->quartier),
                               'prix'       => str_replace(" ", "", $request->prix),
                           ]])
                           ->log('Modification du parcelle de '.Str::upper($request->nom).' '.Str::ucfirst($request->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return response()->json(['status' => true, 'message' => Str::upper($request->nom).' '.Str::ucfirst($request->prenom).' mis à jour avec succès']);
            }
        }
        catch (QueryException $e) {
            return response()->json(['status' => false, 'message' => 'Echéc, veuillez vérifier les données']);
        }
    }

   
    public function delete(Request $request)
    {
        try {

            $deleted = Parcelle::where('id',$request->id)
                                   ->update([
                                            'delete_at' => Carbon::now()
                                    ]);

            $objetDeleted = Parcelle::where('id',$request->id)
                                   ->first();


            if ($deleted) {

                 activity()->performedOn(new Parcelle())
                           ->causedBy(Auth::user()->id)
                           ->withProperties(['old' => [
                               'nom'        => $objetDeleted->nom,
                               'prenom'     => $objetDeleted->prenom,
                               'telephone'  => $objetDeleted->telephone,
                               'quartier'   => $objetDeleted->quartier,
                               'prix'       => $objetDeleted->prix,
                           ], 'new' => []])
                           ->log('Suppression de la parcelle de '.Str::upper($objetDeleted->nom).' '.Str::ucfirst($objetDeleted->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return response()->json(['status' => true, 'message' => 'Suppression effectuée avec succès']);

            }
        } catch (QueryException $e) {
            return response()->json(['status' => false, 'message' => 'Echéc, veuillez vérifier les données']);
        }
    }


    public function cloturer(Request $request)
    {
        try {

            $cloture = Parcelle::where('id',$request->id)
                                   ->update([
                                            'status' => Carbon::now(),
                                            'client_acheteur' => $request->client_acheteur
                                    ]);

            $objetcloture = Parcelle::where('id',$request->id)
                                   ->first();


            if ($cloture) {

                Client::where('id',$request->client_acheteur)
                                   ->update(['status' => Carbon::now()]);

                 activity()->performedOn(new Parcelle())
                           ->causedBy(Auth::user()->id)
                           ->withProperties(['old' => ['etat' => $objetcloture->etat ?? 'to_selle'], 'new' => ['etat' => 'vendu', 'client_acheteur' => $request->client_acheteur]])
                           ->log('Cloturé la vente de la parcelle de '.Str::upper($objetcloture->nom).' '.Str::ucfirst($objetcloture->prenom).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return response()->json(['status' => true, 'message' => 'Vente cloturée avec succès']);
            }
        } catch (QueryException $e) {
            return response()->json(['status' => false, 'message' => 'Echéc, veuillez vérifier les données']);
        }
    }



}
