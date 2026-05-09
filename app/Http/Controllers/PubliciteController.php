<?php

namespace App\Http\Controllers;

use App\Publicite;
use App\Direction;
use App\PlatformConfig;
use App\Services\KkiapayService;
use App\Services\FedapayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

                // ── Contrôle limite plan ────────────────────────────────────────
                $direction = Direction::find(Auth::user()->iddirection_ref);
                $plan      = $direction ? $direction->plan : null;
                if ($plan && !is_null($plan->max_publicites)) {
                    if ($plan->max_publicites === 0) {
                        return response()->json([
                            'status'  => false,
                            'message' => "Votre plan « {$plan->nom} » ne permet pas de créer des publicités.",
                        ]);
                    }
                    $nbActuelles = Publicite::whereNull('delete_at')
                        ->where('iddirection_ref', Auth::user()->iddirection_ref)
                        ->count();
                    if ($nbActuelles >= $plan->max_publicites) {
                        return response()->json([
                            'status'  => false,
                            'message' => "Limite atteinte : votre plan « {$plan->nom} » autorise au maximum {$plan->max_publicites} publicité(s). Passez à un plan supérieur pour en ajouter davantage.",
                        ]);
                    }
                }
                // ───────────────────────────────────────────────────────────────

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
                    'ville' => $request->ville ?? null,
                    'quartier' => $request->quartier ?? null,
                    'type_bien' => $request->type_bien ?? null,
                    'video_url' => $request->video_url ?? null,
                    'lat' => $request->lat ?? null,
                    'lng' => $request->lng ?? null,
                    'meta_description' => $request->meta_description ?? null,
                ];

                if ($image_link) $pubData['image_url'] = $image_link;
                if ($image_link2) $pubData['image_url2'] = $image_link2;
                if ($image_link3) $pubData['image_url3'] = $image_link3;
                if ($image_link4) $pubData['image_url4'] = $image_link4;

                $pub = Publicite::create($pubData);

                if ($pub) {
                    // Générer le slug après création
                    $pub->slug = Publicite::generateSlug($request->adresse, $pub->id);
                    $pub->save();

                    activity()->performedOn(new Publicite())
                            ->causedBy(Auth::user()->id)
                            ->log('Ajout de publicité par '.Auth::user()->nom.' '.Auth::user()->prenom);

                    return response()->json([
                        'status' => true,
                        'message' => "Publicité ajoutée avec succès",
                        'data' => [
                            'id'           => $pub->id,
                            'localisation' => $pub->localisation,
                            'Superficie'   => $pub->Superficie,
                            'price'        => $pub->price,
                            'telephone'    => $pub->telephone,
                            'description'  => $pub->description,
                            'image_url'    => $pub->image_url,
                            'image_url2'   => $pub->image_url2,
                            'image_url3'   => $pub->image_url3,
                            'image_url4'   => $pub->image_url4,
                            'image_count'  => $pub->image_count,
                            'annexe_name'  => get_annexee_name($pub->idannexe_ref),
                            'published_at' => $pub->published_at ? $pub->published_at->format('d/m/Y H:i') : null,
                        ],
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
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors(),
                    ]);
                }

                // Utiliser l'annexe active centralisée
                $idannexe_ref = get_active_annexe_id();
                if (!$idannexe_ref) {
                    return response()->json([
                        'status' => false,
                        'message' => "Veuillez sélectionner une agence dans le header",
                    ]);
                }

                $updateData = [
                    'iddirection_ref' => Auth::user()->iddirection_ref,
                    'idannexe_ref' => $idannexe_ref,
                    'localisation' => $request->adresse,
                    'Superficie' => $request->superficie,
                    'price' => $request->prix_vente,
                    'description' => $request->description,
                    'telephone' => $request->telephone,
                    'ville' => $request->ville ?? null,
                    'quartier' => $request->quartier ?? null,
                    'type_bien' => $request->type_bien ?? null,
                    'video_url' => $request->video_url ?? null,
                    'lat' => $request->lat ?? null,
                    'lng' => $request->lng ?? null,
                    'meta_description' => $request->meta_description ?? null,
                    'slug' => Publicite::generateSlug($request->adresse, $request->id),
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

                Publicite::where('id',$request->id)
                    ->where('iddirection_ref', Auth::user()->iddirection_ref)
                    ->update($updateData);
                $updatedPub = Publicite::where('id',$request->id)
                    ->where('iddirection_ref', Auth::user()->iddirection_ref)
                    ->first();

                if ($updatedPub) {

                    activity()->performedOn(new Publicite())
                            ->causedBy(Auth::user()->id)
                            ->log('Modification de publicité par '.Auth::user()->nom.' '.Auth::user()->prenom);

                    return response()->json([
                        'status'  => true,
                        'message' => 'Publicité modifiée avec succès',
                        'data'    => [
                            'id'          => $updatedPub->id,
                            'localisation'=> $updatedPub->localisation,
                            'Superficie'  => $updatedPub->Superficie,
                            'price'       => $updatedPub->price,
                            'telephone'   => $updatedPub->telephone,
                            'description' => $updatedPub->description,
                            'image_url'   => $updatedPub->image_url,
                            'image_url2'  => $updatedPub->image_url2,
                            'image_url3'  => $updatedPub->image_url3,
                            'image_url4'  => $updatedPub->image_url4,
                            'image_count' => $updatedPub->image_count,
                        ],
                    ]);
                }

            }
            catch (QueryException $e) {

                return response()->json([
                    'status'  => false,
                    'message' => 'Erreur lors de la modification',
                ]);

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

            $pub = Publicite::where('id', $request->id)
                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                ->first();
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

            $pub = Publicite::where('id', $request->id)
                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                ->first();
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


    public function sponsoriser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id'             => 'required',
                'duree_jours'    => 'required|integer|min:1|max:365',
                'transaction_id' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => false, 'message' => $validator->errors()->first()]);
            }

            // Vérifier paiement
            $cfg = PlatformConfig::getConfig();
            $verified = false;
            if ($cfg && $cfg->active_payment_provider === 'kkiapay') {
                $svc = new KkiapayService($cfg->kkiapay_public_key, $cfg->kkiapay_private_key, $cfg->kkiapay_secret_key, (bool)$cfg->kkiapay_sandbox);
                $verified = $svc->verifyTransaction($request->transaction_id);
            } elseif ($cfg && $cfg->active_payment_provider === 'fedapay') {
                $svc = new FedapayService($cfg->fedapay_secret_key, (bool)$cfg->fedapay_sandbox);
                $verified = $svc->verifyTransaction($request->transaction_id);
            } else {
                // Pas de paiement configuré — autoriser quand même (admin)
                $verified = true;
            }

            if (!$verified) {
                return response()->json(['status' => false, 'message' => 'Transaction invalide ou non vérifiée.']);
            }

            $pub = Publicite::where('id', $request->id)
                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                ->firstOrFail();

            $pub->is_sponsored         = true;
            $pub->sponsored_until      = now()->addDays((int)$request->duree_jours);
            $pub->transaction_sponsoring = $request->transaction_id;
            $pub->save();

            return response()->json([
                'status'  => true,
                'message' => "Annonce sponsorisée jusqu'au " . $pub->sponsored_until->format('d/m/Y') . '.',
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Erreur lors de la mise en avant.']);
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

        return response()->json([
            'status'  => true,
            'message' => 'Suppression effectuée avec succès',
        ]);

        } catch (QueryException $e) {

            return response()->json([
                'status'  => false,
                'message' => 'Échec, veuillez vérifier les données',
            ]);
        }
    }
}
