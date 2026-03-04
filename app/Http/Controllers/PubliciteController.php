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

    private function storeImage($request, $fieldName)
    {
        $path = 'image_pub';

        if (!Storage::exists('public/'.$path)) {
            Storage::makeDirectory('public/'.$path, 0775, true);
        }

        if (Storage::exists('public/'.$path) && $request->hasFile($fieldName)) {
            $file = $request->file($fieldName);
            $image_name = 'image'.'_'. time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
            $file->storeAs('public/'.$path, $image_name);
            return "$path/$image_name";
        }

        return null;
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
                        'image' => 'bail|mimes:jpeg,png,jpg|max:2048',
                        'image2' => 'bail|nullable|mimes:jpeg,png,jpg|max:2048',
                        'image3' => 'bail|nullable|mimes:jpeg,png,jpg|max:2048',
                        'image4' => 'bail|nullable|mimes:jpeg,png,jpg|max:2048',
                    ],
                    [
                        'image.max' => 'L\'image 1 ne doit pas dépasser 2 Mo.',
                        'image2.max' => 'L\'image 2 ne doit pas dépasser 2 Mo.',
                        'image3.max' => 'L\'image 3 ne doit pas dépasser 2 Mo.',
                        'image4.max' => 'L\'image 4 ne doit pas dépasser 2 Mo.',
                        'image.mimes' => 'L\'image 1 doit être au format jpeg, png ou jpg.',
                        'image2.mimes' => 'L\'image 2 doit être au format jpeg, png ou jpg.',
                        'image3.mimes' => 'L\'image 3 doit être au format jpeg, png ou jpg.',
                        'image4.mimes' => 'L\'image 4 doit être au format jpeg, png ou jpg.',
                    ],
                );

                if ($validator->fails()) {
                    return response()->json([
                        'error' => $validator->errors()
                    ]);
                }

                $image_link = $this->storeImage($request, 'image');
                $image_link2 = $this->storeImage($request, 'image2');
                $image_link3 = $this->storeImage($request, 'image3');
                $image_link4 = $this->storeImage($request, 'image4');

                // Utiliser l'annexe active centralisée
                $idannexe_ref = get_active_annexe_id();
                if (!$idannexe_ref) {
                    return response()->json([
                        'status' => false,
                        'message' => "Veuillez sélectionner une agence dans le header"
                    ]);
                }

                $pubData = [
                    'iddirection_ref' => Auth::user()->iddirection_ref,
                    'idannexe_ref' => $idannexe_ref,
                    'localisation' => $request->adresse,
                    'Superficie' => $request->superficie,
                    'price' => $request->prix_vente,
                    'description' => $request->description,
                    'telephone' => $request->telephone,
                    'published_at' => Carbon::now(),
                ];

                if ($image_link) $pubData['image_url'] = $image_link;
                if ($image_link2) $pubData['image_url2'] = $image_link2;
                if ($image_link3) $pubData['image_url3'] = $image_link3;
                if ($image_link4) $pubData['image_url4'] = $image_link4;

                $pub = Publicite::create($pubData);

                if ($pub) {

                    activity()->performedOn(new Publicite())
                            ->causedBy(Auth::user()->id)
                            ->log('Ajout de publicité par '.Auth::user()->nom.' '.Auth::user()->prenom);

                    return response()->json([
                        'status' => true,
                        'message' => "Publicité ajoutée avec succès",
                    ]);
                }

            }
            catch (QueryException $e) {
                return response()->json([
                    'status' => false,
                    'message' => "Echec, essayez encore",
                ]);
            }
    }


    public function update_pub(Request $request)
    {
        try {

                $validator = Validator::make($request->all(), [
                    'id' => 'bail|required',
                    'adresse' => 'bail|required|string',
                    'superficie' => 'bail|required',
                    'prix_vente' => 'bail|required',
                    'telephone' => 'bail|required',
                    'description' => 'bail|required|string',
                    'image_up' => 'bail|nullable|mimes:jpeg,png,jpg|max:2048',
                    'image_up2' => 'bail|nullable|mimes:jpeg,png,jpg|max:2048',
                    'image_up3' => 'bail|nullable|mimes:jpeg,png,jpg|max:2048',
                    'image_up4' => 'bail|nullable|mimes:jpeg,png,jpg|max:2048',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator);
                }

                // Utiliser l'annexe active centralisée
                $idannexe_ref = get_active_annexe_id();
                if (!$idannexe_ref) {
                    return redirect()->back()->with('error', "Veuillez sélectionner une agence dans le header");
                }

                $updateData = [
                    'iddirection_ref' => Auth::user()->iddirection_ref,
                    'idannexe_ref' => $idannexe_ref,
                    'localisation' => $request->adresse,
                    'Superficie' => $request->superficie,
                    'price' => $request->prix_vente,
                    'description' => $request->description,
                    'telephone' => $request->telephone,
                ];

                // Gérer chaque image individuellement
                $imageFields = [
                    'image_up' => ['old' => 'image_ancien', 'col' => 'image_url'],
                    'image_up2' => ['old' => 'image_ancien2', 'col' => 'image_url2'],
                    'image_up3' => ['old' => 'image_ancien3', 'col' => 'image_url3'],
                    'image_up4' => ['old' => 'image_ancien4', 'col' => 'image_url4'],
                ];

                foreach ($imageFields as $inputName => $config) {
                    if ($request->hasFile($inputName)) {
                        // Supprimer l'ancienne image si elle existe
                        $oldPath = $request->input($config['old']);
                        if ($oldPath && Storage::exists('public/'.$oldPath)) {
                            Storage::delete('public/'.$oldPath);
                        }
                        $newLink = $this->storeImage($request, $inputName);
                        if ($newLink) {
                            $updateData[$config['col']] = $newLink;
                        }
                    }
                }

                $pub = Publicite::where('id',$request->id)->update($updateData);

                if ($pub) {

                    activity()->performedOn(new Publicite())
                            ->causedBy(Auth::user()->id)
                            ->log('Modification de publicité par '.Auth::user()->nom.' '.Auth::user()->prenom);

                    return redirect()->back()->with('message', 'Publicité modifiée avec succès');
                }

            }
            catch (QueryException $e) {

                return redirect()->back()->with('error', 'Erreur lors de la modification');

            }
    }


    public function addImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'image' => 'required|mimes:jpeg,png,jpg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Image invalide. Formats acceptés: jpeg, png, jpg (max 2Mo)'
                ]);
            }

            $pub = Publicite::find($request->id);
            if (!$pub) {
                return response()->json([
                    'status' => false,
                    'message' => 'Publicité introuvable'
                ]);
            }

            $freeSlot = $pub->first_free_slot;
            if (!$freeSlot) {
                return response()->json([
                    'status' => false,
                    'message' => 'Maximum de 4 images atteint'
                ]);
            }

            $imageLink = $this->storeImage($request, 'image');
            if ($imageLink) {
                $pub->{$freeSlot} = $imageLink;
                $pub->save();

                return response()->json([
                    'status' => true,
                    'message' => 'Image ajoutée avec succès',
                    'image_count' => $pub->image_count,
                    'image_url' => $imageLink,
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Erreur lors du téléchargement de l\'image'
            ]);

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur, essayez encore'
            ]);
        }
    }


    public function removeImage(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'slot' => 'required|in:image_url,image_url2,image_url3,image_url4',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Données invalides'
                ]);
            }

            $pub = Publicite::find($request->id);
            if (!$pub) {
                return response()->json([
                    'status' => false,
                    'message' => 'Publicité introuvable'
                ]);
            }

            $slot = $request->slot;
            $oldPath = $pub->{$slot};

            if ($oldPath && Storage::exists('public/'.$oldPath)) {
                Storage::delete('public/'.$oldPath);
            }

            $pub->{$slot} = null;
            $pub->save();

            return response()->json([
                'status' => true,
                'message' => 'Image supprimée avec succès',
                'image_count' => $pub->image_count,
            ]);

        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur, essayez encore'
            ]);
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
