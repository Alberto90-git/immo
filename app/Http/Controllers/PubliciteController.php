<?php

namespace App\Http\Controllers;

use App\Publicite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PubliciteController extends Controller
{
    public function display_pub()
    {
        $allpub =  Publicite::whereNull('delete_at')
                        ->where('iddirection_ref',Auth::user()->iddirection_ref)
                        ->where(function($querry){
                            if (Gate::none(['Is_admin'])) {
                                $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                            }
                        })
                        ->get();
        return view('pub.publicite', compact('allpub'));
    }



    public function create(Request $request)
    {
        try {
                $validator = Validator::make(
                    $request->all(),
                    [
                        'adresse' => 'bail|required|string',
                        'superficie' => 'bail|required|string',
                        'prix_vente' => 'bail|required|string',
                        'telephone' => 'bail|required|string',
                        'description' => 'bail|required|string',
                        'image' => 'bail|mimes:jpeg,png,jpg,svg,pdf|max:2048',
                        
                    ],
                );

                if ($validator->fails()) {
                    return response()->json([
                        'error' => $validator->errors()
                    ]);
                }

                    
                $path = 'image_pub';

                if (!Storage::exists('public/'.$path)) {
                    Storage::makeDirectory('public/'.$path, 0775, true); //creates directory
                }

                if (Storage::exists('public/'.$path)) {
                    if ($request->hasFile('image')) {
                        $images = $request->file('image');
                        $image_name = 'image'.'_'. time().'.'.$images->getClientOriginalExtension();
                        $images->storeAs('public/'.$path, $image_name);
                        $image_link="$path/$image_name";
                    }
                    
                }
                
                $response = $this->check_is_admin_and_entreprise();

                if ($response) {
                    $idannexe_ref = $request->annexe;
                }else {
                    $idannexe_ref = Auth::user()->idannexe_ref;
                }
        

                $pub = Publicite::create([
                                'iddirection_ref' => Auth::user()->iddirection_ref,
                                'idannexe_ref' => $idannexe_ref,
                                'localisation' => $request->adresse,
                                'Superficie' => $request->superficie,
                                'price' => $request->prix_vente,
                                'description' => $request->description,
                                'telephone' => $request->telephone,
                                'image_url'  => $image_link,
                            ]);

                if ($pub) {

                    activity()->performedOn(new Publicite())
                            ->causedBy(Auth::user()->id)
                            ->log('Ajout de publicité par '.Auth::user()->nom.' '.Auth::user()->prenom);

                    return response()->json([
                        'status' => true,
                        'message' => "Publicité ajoutéé avec succès",
                    ]);
                }
            
            }
            catch (QueryException $e) {
                return response()->json([
                    'status' => false,
                    'message' => "Echec,essayé encore $e",
                ]);
            }
    }




    public function update_pub(Request $request)
    {
        try {

                $this->validate($request, [
                            'id' => 'bail|required',
                            'adresse' => 'bail|required|string',
                            'superficie' => 'bail|required',
                            'prix_vente' => 'bail|required',
                            'telephone' => 'bail|required',
                            'description' => 'bail|required|string',
                            'image_up' => 'bail|required|mimes:jpeg,png,jpg,svg,pdf|max:2048',
                ]);

                $path_to_delete = $request->image_ancien;

                $path = "public/$path_to_delete";

                if (Storage::exists($path)) {
                    Storage::delete($path);
                }
                    
                $path = 'image_pub';

                if (!Storage::exists('public/'.$path)) {
                    Storage::makeDirectory('public/'.$path, 0775, true); //creates directory
                }

                if (Storage::exists('public/'.$path)) {
                    if ($request->hasFile('image_up')) {
                        $images = $request->file('image_up');
                        $image_name = 'image'.'_'. time().'.'.$images->getClientOriginalExtension();
                        $images->storeAs('public/'.$path, $image_name);
                        $image_link="$path/$image_name";
                    }
                }
                
                $response = $this->check_is_admin_and_entreprise();

                if ($response) {
                    $idannexe_ref = $request->annexe;
                }else {
                    $idannexe_ref = Auth::user()->idannexe_ref;
                }
        

                $pub = Publicite::where('id',$request->id)
                                ->update([
                                            'iddirection_ref' => Auth::user()->iddirection_ref,
                                            'idannexe_ref' => $idannexe_ref,
                                            'localisation' => $request->adresse,
                                            'Superficie' => $request->superficie,
                                            'price' => $request->prix_vente,
                                            'description' => $request->description,
                                            'telephone' => $request->telephone,
                                            'image_url'  => $image_link,
                                        ]);

                if ($pub) {

                    activity()->performedOn(new Publicite())
                            ->causedBy(Auth::user()->id)
                            ->log('Ajout de publicité par '.Auth::user()->nom.' '.Auth::user()->prenom);


                    return redirect()->back()->with('message', 'Publicité modifiée avec succès');

                }
            
            }
            catch (QueryException $e) {
                
                return redirect()->back()->with('error', 'Publicité modifiée avec succès');

            }
    }


    public function destroy(Request $request)
    {
        try {

            $this->validate($request, [
                'id' => 'bail|required',
            ]);

            $deleted = Publicite::where('id',$request->id)
                                   ->update([
                                            'delete_at' => Carbon::now()
                                    ]);

            $objetDeleted = Publicite::where('id',$request->id)
                                   ->first();
                
            activity()->performedOn(new Publicite())
                    ->causedBy(Auth::user()->id)
                    ->log('Suppression de la publicité'.$objetDeleted->description.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

        return back()->with('message','Suppression effectuée avec succès');
            
        } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }
}
