<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Locataire;
use App\Maison;
use App\Proprietaire;
use App\User;
use App\Chambre;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\Gate;



class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    

    public function __construct()
    {
        $this->middleware('auth');
    }

    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try { 
            $element = array();

            $element['nombreProprio'] = Proprietaire::whereNull('delete_at')
                                                    ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                                    ->where(function($querry){
                                                        if (Gate::none(['Is_admin'])) {
                                                            $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                                        }
                                                    })
                                                    ->count();

            $element['nombreMaison'] = Maison::whereNull('delete_at')->count();
        
            $element['nombreLocataire'] = Locataire::whereNull('delete_at')->where('status',true)->count();
            $element['nombreChambre'] = Chambre::whereNull('delete_at')->count();

            //TOUS LES LOCATAIRES
            $element['locataire'] = Locataire::whereNull('locataires.delete_at')
                                            ->where('locataires.status',true)
                                            ->where('locataires.iddirection_ref',Auth::user()->iddirection_ref)
                                            ->where(function($querry){
                                                if (Gate::none(['Is_admin'])) {
                                                    $querry->where('locataires.idannexe_ref',Auth::user()->idannexe_ref);
                                                }
                                            })
                                            ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
                                            ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
                                            ->get();

            $element['proprioMaison'] = Proprietaire::whereNull('proprietaires.delete_at')
                                            ->join('maisons', 'maisons.proprio_id', '=', 'proprietaires.id')
                                            ->where('proprietaires.iddirection_ref',Auth::user()->iddirection_ref)
                                            ->where(function($querry){
                                                if (Gate::none(['Is_admin'])) {
                                                    $querry->where('proprietaires.idannexe_ref',Auth::user()->idannexe_ref);
                                                }
                                            })
                                            ->whereNull('maisons.delete_at')
                                            ->get();


            return view('home',['data' => $element]);
            
        } catch (QueryException $e) {
            //throw $th;
        }
       
    }

    //LISTE DES LOCATAIRES
    public function getLocataire(Request $request)
    {
       
        $vide='';
        $vide2='';
        $vide3 = '';
        $nombre_proprio ='';

        $element['locataire'] = Locataire::whereNull('locataires.delete_at')
                                        ->where('locataires.idannexe_ref',$request->annexe_id)
                                        ->where('locataires.status',true)
                                        ->where('locataires.iddirection_ref',Auth::user()->iddirection_ref)
                                        ->where(function($querry){
                                            if (Gate::none(['Is_admin'])) {
                                                $querry->where('locataires.idannexe_ref',Auth::user()->idannexe_ref);
                                            }
                                        })
                                        ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
                                        ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
                                        ->get();


        $element['proprioMaison'] = Proprietaire::whereNull('proprietaires.delete_at')
                                                ->where('proprietaires.idannexe_ref',$request->annexe_id)
                                                ->where('proprietaires.iddirection_ref',Auth::user()->iddirection_ref)
                                                ->where(function($querry){
                                                    if (Gate::none(['Is_admin'])) {
                                                        $querry->where('proprietaires.idannexe_ref',Auth::user()->idannexe_ref);
                                                    }
                                                })
                                                ->join('maisons', 'maisons.proprio_id', '=', 'proprietaires.id')
                                                ->whereNull('maisons.delete_at')
                                                ->get();


        $element['nombreProprio'] = Proprietaire::whereNull('delete_at')
                                                ->where('idannexe_ref',$request->annexe_id)
                                                ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                                ->where(function($querry){
                                                    if (Gate::none(['Is_admin'])) {
                                                        $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                                    }
                                                })
                                                ->count();

        $element['nombreMaison'] = Maison::whereNull('delete_at')
                                        ->where('idannexe_ref',$request->annexe_id)
                                        ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                        ->where(function($querry){
                                            if (Gate::none(['Is_admin'])) {
                                                $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                            }
                                        })
                                        ->count();

        
        $element['nombreLocataire'] = Locataire::whereNull('delete_at')
                                                ->where('status',true)
                                                ->where('idannexe_ref',$request->annexe_id)
                                                ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                                ->where(function($querry){
                                                    if (Gate::none(['Is_admin'])) {
                                                        $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                                    }
                                                })
                                                ->count();


        $element['nombreChambre'] = Chambre::whereNull('delete_at')
                                            ->where('idannexe_ref',$request->annexe_id)
                                            ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                            ->where(function($querry){
                                                if (Gate::none(['Is_admin'])) {
                                                    $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                                }
                                            })
                                            ->count();



        
            if(count($element['locataire']) === 0 || count($element['proprioMaison']) === 0 || $element['nombreProprio'] ===0 ||  $element['nombreMaison'] === 0 || $element['nombreLocataire'] === 0 || $element['nombreChambre'] ===0)
            {
                $vide.='<tr> <td colspan="6">Aucune donné trouvée</td><tr>';
                $vide2.='<tr> <td colspan="6">Aucune donné trouvée</td><tr>';
                return response()->json([
                    'getlist'=> $vide,
                    'getlist2'=> $vide2,
                    'nombre_proprio' => $element['nombreProprio'],
                    'nombre_maison' => $element['nombreMaison'],
                    'nombre_locataire' => $element['nombreLocataire'],
                    'nombre_chambre' => $element['nombreChambre'],
                    'list' => json_encode(['Cotonou' => 10, 'Parakou' => 5,"Lokossa" => 40,"Dogbo" => 20,"Azove" => 30,"Come" => 15,"Porto-novo" => 40,"Abomey" => 45,"Abomey1" => 45,"Abomey2" => 45,"Abomey3" => 45,"Abomey4" => 45,"Abomey5" => 45,"Abomey6" => 45,"Abomey7" => 45,"Abomey8" => 45,"Abomey9" => 45,"Abomey10" => 45,"Abomey11" => 45,"Abomey12" => 45,"Abomey13" => 45,"Abomey14" => 45,"Abomey15" => 45])

                ]);

            }
            else
            {
                foreach ($element['locataire'] as $value) {
                    $vide.='<tr>';
                    $vide.='<td>'.$value->nom_maison.'</td>';
                    $vide.='<td>'.$value->numero_chambre.'</td><td>'.$value->nom.' '.$value->prenom.'</td>';
                    $vide.='<td>'.$value->telephone.'</td>';
                    $vide.='<td>'.$value->profession.'</td>';
                    $vide.='<td>'.Carbon::parse($value->date_entree)->format('d/m/Y').'</td>';
                    $vide.='</tr>';
                }


                foreach ($element['proprioMaison'] as $value2) {
                    $vide2.='<tr>';
                    $vide2.='<td>'.$value2->nom.' '.$value2->prenom.'</td>';
                    $vide2.='<td>'.$value2->telephone.'</td><td>'.$value2->adresse.'</td>';
                    $vide2.='<td>'.$value2->nom_maison.'</td>';
                    $vide2.='<td>'.$value2->quartier.'</td>';
                    $vide2.='</tr>';
                }

                $ville = ['Cotonou' => 10,'Parakou' => 5];


                foreach ($ville as $value2) {
                    $vide3.='value:'.$value2[1];
                    $vide3.='name:'.$value2[0];
                }


               
                  
                

                return response()->json([
                    'getlist'=> $vide,
                    'getlist2'=> $vide2,
                    'nombre_proprio' => $element['nombreProprio'],
                    'nombre_maison' => $element['nombreMaison'],
                    'nombre_locataire' => $element['nombreLocataire'],
                    'nombre_chambre' => $element['nombreChambre'],
                    'list' => json_encode(['Cotonou' => 10, 'Parakou' => 5,"Lokossa" => 40,"Dogbo" => 20,"Azove" => 30,"Come" => 15,"Porto-novo" => 40,"Abomey" => 45,"Abomey1" => 45,"Abomey2" => 45,"Abomey3" => 45,"Abomey4" => 45,"Abomey5" => 45,"Abomey6" => 45,"Abomey7" => 45,"Abomey8" => 45,"Abomey9" => 45,"Abomey10" => 45,"Abomey11" => 45,"Abomey12" => 45,"Abomey13" => 45,"Abomey14" => 45,"Abomey15" => 45])
                ]); 
            }
    }



    public function profile()
    {
        return view('profile');
    }


    public function updatePassword(Request $request)
    {

        try {
           $validator = Validator::make(
                $request->all(),
                [
                  //'Ancien_mot_de_passe' => ['required'],
                  'Ancien_mot_de_passe' => ['required', new MatchOldPassword],
                  'Nouveau_mot_de_passe' => 'required|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*#?&]/',
                  'Confirmer_mot_de_passe' => ['same:Nouveau_mot_de_passe'],
                ],
            );


            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()
                ]);
            }

            $succes = User::find(auth()->user()->id)->update(['password'=> Hash::make($request->Nouveau_mot_de_passe)]);

            if ($succes) {

              activity()->performedOn(new User())
                           ->causedBy(Auth::user()->id)
                           ->log('Mise à jour du mot de passe '.' par '.Auth::user()->nom.' '.Auth::user()->prenom);

              return response()->json([
                  'status' => true,
                  'message' => "Mise à jour effectuée avec succès",
              ]);

            }

        } catch (QueryException $e) {

            return response()->json([
                    'status' => false,
                    'message' => "Echec,essayez encore",
            ]);
        }

    }

    

    public function updateProfile(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'nom' => 'bail|required|string',
                    'prenom' => 'bail|required|string',
                    'grade' => 'bail|required|string',
                ],
            );


            if ($validator->fails()) {
                return response()->json([
                    'error' => $validator->errors()
                ]);
            }

            $user = User::where('id',$request->user_id)->update([
                            'nom' => Str::upper($request->nom),
                            'prenom' => Str::ucfirst($request->prenom),
                            'grade' => $request->grade,
                        ]);

            if ($user) {

            activity()->performedOn(new User())
                    ->causedBy(Auth::user()->id)
                    ->log('Mise à jour du profile '.' par '.Auth::user()->nom.' '.Auth::user()->prenom);


                return response()->json([
                    'status' => true,
                    'message' => "Mise à jour effectuée avec succès",
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


    public function historique(Request $request)
    {
      $user = User::whereNull('status')->get();

       // $choix = $request->choix ;

      if ($request->choix == 'by_user') {
        $all = Activity::where('causer_id',$request->user_name)->get();
        
        return view('historique',['all' => $all, 'users' => $user]);
      }else if($request->choix == 'by_date') {

        $all = Activity::whereBetween('created_at',[$request->date_debut.' 01:00:00', $request->date_fin.' 23:59:59'])
                        ->get();

        return view('historique',['all' => $all, 'users' => $user]);

      }else{
        $all = Activity::take(5)->orderByDesc('created_at')->get();
        
        return view('historique',['all' => $all, 'users' => $user]);
      }

    }


    public function getcontratpdf(Request $request)
    {
        $data =   Locataire::where('locataires.id',$request->idlocataire)
                            ->whereNull('locataires.delete_at')
                            ->where('locataires.status',true)
                            ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
                            ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
                            ->first();


    	$pdf = PDF::loadView('pdf.contrat',compact('data'))->setOptions(['defaultFont' => 'sans-serif']);

    	$pdf->output();

        activity()->performedOn(new Locataire())
                           ->causedBy(Auth::user()->id)
                           ->log("Téléchargement du contrat d'un client "." par ".Auth::user()->nom." ".Auth::user()->prenom);

    	return $pdf->download('Contrat du '.Carbon::now().'.pdf');
    }
}
