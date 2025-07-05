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

            $param = Parametre::get();
            $liste_annexe = Annexe::whereNull('status')
                                    ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                    ->whereNull('blocage_annexe')
                                   ->where('annexes.designation','!=','All Digital Agency')
                                    ->get();
                                    
            return view('parametre',compact('param','liste_annexe'));

       } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
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
                    //'designation' => 'bail|required|string',
                    'designation' => 'required|unique:annexes,designation',
                    //'designation' => ['bail','required','string','designation','max:255',Rule::unique(Annexe::class),],
                    'adresse' => 'bail|required|string',
                    'telephone' => 'bail|required|string',
                    'email' => 'bail|required|string',
                ],
                [
                    '*.required' => 'Ce champ est obligatoire.',
                ]);


            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors(),
                    'message' => 'Veuillez bien remplir les champs'
                ]);
            }

            $annexe = Annexe::create([
                'iddirection_ref'  => Auth::user()->iddirection_ref,
                'designation'      => Str::ucfirst($request->designation),
                'siege_social'     => Str::ucfirst($request->adresse),
                'telephone'        => $request->telephone,          
                'email'          => $request->email,
                'userdata'       => $this->getDirectionDsignation(Auth::user()->iddirection_ref)
            ]);

            if ($annexe) {

                Session::put(['anne_data'=>  SessionController::save_session_annexe()]);

                return response()->json([
                    'status' => true,
                    'message' => Str::upper($request->designation).' '." est ajoutée avec succès",
                ]);

            }
        }
        catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => "Echec,essayez à nouveau en chargeant peut-être la designation",
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

                return back()->with('message', Str::upper($request->designation).' '.' mis à jour avec succès');
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

                return back()->with('message','Suppression effectuée avec succès');
                
            }
        } catch (QueryException $e) {

            return back()->with('error','Echéc, veuillez verifier les données');
        }
    }


    public function create(Request $request)
{
    try {
        $validator = Validator::make(
            $request->all(),
            [
                'format_choisi' => 'bail|required|string',
                'logo' => 'bail|required|mimes:jpeg,png,jpg|max:2048',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors(),
                'message' => "Veuillez renseigner toutes les informations"
            ]);
        }

        $exist = Parametre::count();
        $image_link = null;

        // Supprimer l'ancien logo si existant
        if ($exist == 1) {
            $pathdelete = get_logo(Auth::user()->iddirection_ref)?->logo_url;
            if ($pathdelete && Storage::exists("public/$pathdelete")) {
                Storage::delete("public/$pathdelete");
            }
            Parametre::truncate(); // tu n'as qu'un seul paramètre actif
        }

        // Traitement image
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = 'logo';

            if (!Storage::exists("public/$path")) {
                Storage::makeDirectory("public/$path", 0775, true);
            }

            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs("public/$path", $filename);
            $image_link = "$path/$filename"; // logos/logo_xxx.jpg
        }

        // Création du paramètre
        $param = Parametre::create([
            'iddirection_ref' => Auth::user()->iddirection_ref,
            'format_choisi'   => $request->format_choisi,
            'logo_url'        => $image_link,
        ]);

        if ($param) {
            activity()
                ->performedOn(new Parametre())
                ->causedBy(Auth::user()->id)
                ->log('Modification du paramétrage par ' . Auth::user()->nom . ' ' . Auth::user()->prenom);

            return response()->json([
                'status' => true,
                'message' => "Paramétrage effectué avec succès",
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => "Erreur inconnue lors de l'enregistrement.",
        ]);

    } catch (QueryException $e) {
        return response()->json([
            'status' => false,
            'message' => "Échec, essayez encore : " . $e->getMessage(),
        ]);
    }
}


    
}
