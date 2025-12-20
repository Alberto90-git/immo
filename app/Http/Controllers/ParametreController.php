<?php

namespace App\Http\Controllers;

use App\Parametre;
use App\Annexe;
use App\Direction;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Carbon\Carbon;  
use Illuminate\Support\Facades\Storage;
use App\Publicite;
use Illuminate\Support\Facades\Gate;





class ParametreController extends SessionController
{
    public function  welcome_page()
    {
            /*$publicites =  Publicite::whereNull('delete_at')
                                    ->get();*/
                                    
                return view('/welcome');
    }

    public function index()
    {
       try {
            activity()->performedOn(new Parametre())
                       ->causedBy(Auth::user()->id)
                       ->log('Page paramétrage visité par '.Auth::user()->nom.' '.Auth::user()->prenom);

            $param = Parametre::where('iddirection_ref', Auth::user()->iddirection_ref)->get();
            $liste_annexe = Annexe::whereNull('status')
                                    ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                    ->whereNull('blocage_annexe')
                                   ->where('annexes.designation','!=','All Digital Agency')
                                    ->get();
                                    
            return view('parametre',compact('param','liste_annexe'));

       } catch (QueryException $e) {
            return redirect()->back()->with('error','Échec, veuillez vérifier les données');
       }
    }

    private function getDirectionDsignation($iduser)
    {
        return Direction::where('iddirection',$iduser)
                        ->get()
                        ->pluck('designation')[0];
    }

    public function storeAnnexe(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'designation' => 'required|unique:annexes,designation',
                    'adresse' => 'bail|required|string',
                    'telephone' => 'bail|required|string',
                    'email' => 'bail|required|email',
                ],
                [
                    '*.required' => 'Ce champ est obligatoire.',
                    'email.email' => 'Veuillez entrer une adresse email valide.',
                    'designation.unique' => 'Cette désignation existe déjà.',
                ]);

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors(),
                    'message' => 'Veuillez corriger les erreurs de validation'
                ]);
            }

            $annexe = Annexe::create([
                'iddirection_ref'  => Auth::user()->iddirection_ref,
                'designation'      => Str::ucfirst($request->designation),
                'siege_social'     => Str::ucfirst($request->adresse),
                'telephone'        => $request->telephone,          
                'email'            => $request->email,
                'userdata'         => $this->getDirectionDsignation(Auth::user()->iddirection_ref)
            ]);

            if ($annexe) {
                Session::put(['anne_data'=>  SessionController::save_session_annexe()]);

                activity()
                    ->performedOn(new Annexe())
                    ->causedBy(Auth::user()->id)
                    ->log('Ajout d\'une annexe: '.Str::upper($request->designation).' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return response()->json([
                    'status' => true,
                    'message' => Str::upper($request->designation).' a été ajouté avec succès',
                ]);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => "Échec lors de l'ajout de l'annexe. Veuillez réessayer.",
            ]);
        }
    }

    public function create(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'cash_electronique' => 'nullable|mimes:jpeg,png,jpg|max:5120',
                    'logo' => 'nullable|mimes:jpeg,png,jpg|max:5120',
                ],
                [
                    'cash_electronique.mimes' => 'L\'image du cash électronique doit être au format JPEG, PNG ou JPG.',
                    'logo.mimes' => 'Le logo doit être au format JPEG, PNG ou JPG.',
                    'cash_electronique.max' => 'L\'image du cash électronique ne doit pas dépasser 5MB.',
                    'logo.max' => 'Le logo ne doit pas dépasser 5MB.',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors(),
                    'message' => "Veuillez vérifier les fichiers uploadés"
                ]);
            }

            // Vérifier qu'au moins un fichier est présent
            if (!$request->hasFile('cash_electronique') && !$request->hasFile('logo')) {
                return response()->json([
                    'status' => false,
                    'message' => "Veuillez sélectionner au moins une image (cash électronique ou logo)"
                ]);
            }

            $parametre = Parametre::where('iddirection_ref', Auth::user()->iddirection_ref)->first();
            
            // Si un paramètre existe déjà, on le met à jour
            if ($parametre) {
                // Supprimer l'ancien cash électronique si un nouveau est uploadé
                if ($request->hasFile('cash_electronique') && $parametre->cash_electronique_url) {
                    $oldPath = str_replace(asset(''), '', $parametre->cash_electronique_url);
                    if (Storage::exists("public/$oldPath")) {
                        Storage::delete("public/$oldPath");
                    }
                }
                
                // Supprimer l'ancien logo si un nouveau est uploadé
                if ($request->hasFile('logo') && $parametre->logo_url) {
                    $oldPath = str_replace(asset(''), '', $parametre->logo_url);
                    if (Storage::exists("public/$oldPath")) {
                        Storage::delete("public/$oldPath");
                    }
                }
                
                $cashElectroniquePath = $parametre->cash_electronique_url;
                $logoPath = $parametre->logo_url;
            } else {
                $cashElectroniquePath = null;
                $logoPath = null;
                $parametre = new Parametre();
                $parametre->iddirection_ref = Auth::user()->iddirection_ref;
            }

            // Upload de l'image du cash électronique
            if ($request->hasFile('cash_electronique')) {
                $file = $request->file('cash_electronique');
                $path = 'cash_electronique';
                
                if (!Storage::exists("public/$path")) {
                    Storage::makeDirectory("public/$path", 0775, true);
                }
                
                $filename = 'cash_electronique_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->storeAs("public/$path", $filename);
                $cashElectroniquePath = "$path/$filename";
            }

            // Upload du logo
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $path = 'logo';
                
                if (!Storage::exists("public/$path")) {
                    Storage::makeDirectory("public/$path", 0775, true);
                }
                
                $filename = 'logo_' . time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $file->storeAs("public/$path", $filename);
                $logoPath = "$path/$filename";
            }

            // Sauvegarde
            $parametre->cash_electronique_url = $cashElectroniquePath;
            $parametre->logo_url = $logoPath;
            $parametre->save();

            activity()
                ->performedOn(new Parametre())
                ->causedBy(Auth::user()->id)
                ->log('Modification des images (cash électronique/logo) par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

            return response()->json([
                'status' => true,
                'message' => "Images enregistrées avec succès",
            ]);

        } catch (QueryException $e) {
            \Log::error('Erreur lors de l\'enregistrement des paramètres: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => "Échec lors de l'enregistrement. Veuillez réessayer.",
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur générale: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => "Une erreur inattendue est survenue.",
            ]);
        }
    }


    public function updateAnnexe(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                   'designation' => 'bail|required|string',
                    'adresse' => 'bail|required|string',
                    'telephone' => 'bail|required|string',
                    'email' => 'bail|required|string',
                ],
            );



            $annexes = Annexe::where('idannexes',$request->id)
                            ->update([
                                'iddirection_ref'  => Auth::user()->iddirection_ref,
                                'designation'      => Str::ucfirst($request->designation),
                                'siege_social'     => Str::ucfirst($request->adresse),
                                'telephone'        => $request->telephone,          
                                'email'          => $request->email,
                                'userdata'       => $this->getDirectionDsignation(Auth::user()->iddirection_ref)
                            ]);
            if ($annexes) {

                $existeDirection = Direction::where('iddirection',$request->id)->first();

                if ($existeDirection) {
                    $annexes = Direction::where('iddirection',$request->id)
                            ->update([
                                'iddirection'  => Auth::user()->iddirection_ref,
                                'designation'      => Str::ucfirst($request->designation),
                                'siege_social'     => Str::ucfirst($request->adresse),
                                'telephone'        => $request->telephone,          
                                'email'          => $request->email,
                            ]);
                }

                Session::put(['anne_data'=>  SessionController::save_session_annexe()]);

                return back()->with('success', Str::upper($request->designation).' '.' mis à jour avec succès');
            }
        }
        catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->with('error', 'Cette désignation existe déjà.');
            }
        }
    }


    public function destroyAnnexe(Request $request)
    {
        try {

            $deleted = Annexe::where('idannexes',$request->id)
                                   ->update([
                                            'status' => Carbon::now()
                                    ]);

            $objetDeleted = Annexe::where('idannexes',$request->id)
                                   ->first();


            if ($deleted) {
                Session::put(['anne_data'=>  SessionController::save_session_annexe()]);

                 activity()->performedOn(new Annexe())
                           ->causedBy(Auth::user()->id)
                           ->log("Suppression de l'annexe ".Str::upper($objetDeleted->designation).' '.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

                return back()->with('success','Suppression effectuée avec succès');
                
            }
        } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }


    
}
