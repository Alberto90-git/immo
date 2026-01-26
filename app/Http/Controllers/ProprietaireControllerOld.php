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
                    'error' => $validator->errors(),
                    "message" => "Veuillez bien renseigner les informations"
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

                return back()->with('success', Str::upper($request->nom).' '.Str::upper($request->prenom).' mis à jour avec succès');
            }
        }
        catch (QueryException $e) {
            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }

   
    public function destroy(Request $request)
    {
        try {
            $now = Carbon::now();

            $deleted = Proprietaire::where('id', $request->id)
                                    ->update(['delete_at' => $now]);

            $proprio = Proprietaire::find($request->id);

            if ($deleted && $proprio) {

                $maisonIds = Maison::where('proprio_id', $request->id)->pluck('id');

                if ($maisonIds->isNotEmpty()) {
                    Maison::whereIn('id', $maisonIds)->update(['delete_at' => $now]);
                    Chambre::whereIn('maison_id', $maisonIds)->update(['delete_at' => $now]);
                    Prix::whereIn('maison_id', $maisonIds)->update(['delete_at' => $now]);
                    Locataire::whereIn('maison_id', $maisonIds)->update(['delete_at' => $now]);
                    Facture::whereIn('maison_id', $maisonIds)->update(['delete_at' => $now]);
                }

                activity()
                    ->performedOn($proprio)
                    ->causedBy(Auth::user())
                    ->log('Suppression du propriétaire ' . Str::upper($proprio->nom) . ' ' . Str::ucfirst($proprio->prenom) . ' par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

                return back()->with('success', 'Suppression effectuée avec succès.');
            }

            return back()->with('error', 'Aucune suppression effectuée.');

        } catch (\Exception $e) {
            return back()->with('error', 'Échec, veuillez vérifier les données.');
        }
    }

}
