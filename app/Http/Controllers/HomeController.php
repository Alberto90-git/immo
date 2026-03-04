<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Locataire;
use App\PlatformConfig;
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

            // Utiliser l'agence active centralisee
            $idannexe_ref = get_active_annexe_id();

            $element['nombreProprio'] = Proprietaire::whereNull('delete_at')
                                                    ->where('iddirection_ref', Auth::user()->iddirection_ref)
                                                    ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                                                        $query->where('idannexe_ref', $idannexe_ref);
                                                    })
                                                    ->count();

            $element['nombreMaison'] = Maison::whereNull('delete_at')
                                            ->where('iddirection_ref', Auth::user()->iddirection_ref)
                                            ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                                                $query->where('idannexe_ref', $idannexe_ref);
                                            })
                                            ->count();

            $element['nombreLocataire'] = Locataire::whereNull('delete_at')
                                                    ->where('status', true)
                                                    ->where('iddirection_ref', Auth::user()->iddirection_ref)
                                                    ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                                                        $query->where('idannexe_ref', $idannexe_ref);
                                                    })
                                                    ->count();

            $element['nombreChambre'] = Chambre::whereNull('delete_at')
                                                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                                                ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                                                    $query->where('idannexe_ref', $idannexe_ref);
                                                })
                                                ->count();

            //TOUS LES LOCATAIRES
            $element['locataire'] = Locataire::whereNull('locataires.delete_at')
                                            ->where('locataires.status', true)
                                            ->where('locataires.iddirection_ref', Auth::user()->iddirection_ref)
                                            ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                                                $query->where('locataires.idannexe_ref', $idannexe_ref);
                                            })
                                            ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
                                            ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
                                            ->select(
                                                'locataires.id', 'locataires.nom', 'locataires.prenom',
                                                'locataires.telephone', 'locataires.profession', 'locataires.date_entree',
                                                'locataires.prix_mois', 'locataires.idannexe_ref',
                                                'maisons.nom_maison', 'chambres.numero_chambre', 'chambres.type_chambre'
                                            )
                                            ->get();

            $element['proprioMaison'] = Proprietaire::whereNull('proprietaires.delete_at')
                                            ->join('maisons', 'maisons.proprio_id', '=', 'proprietaires.id')
                                            ->where('proprietaires.iddirection_ref', Auth::user()->iddirection_ref)
                                            ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                                                $query->where('proprietaires.idannexe_ref', $idannexe_ref);
                                            })
                                            ->whereNull('maisons.delete_at')
                                            ->select(
                                                'proprietaires.id', 'proprietaires.nom', 'proprietaires.prenom',
                                                'proprietaires.telephone', 'proprietaires.adresse',
                                                'maisons.nom_maison', 'maisons.quartier', 'maisons.id as maison_id'
                                            )
                                            ->get();


            $paymentConfig         = PlatformConfig::getConfig();
            $activePaymentProvider = $paymentConfig->getActiveProvider();
            $activePaymentSandbox  = $paymentConfig->getActiveSandbox();
            $paymentCfgData        = [
                'kkiapay_public_key'  => $paymentConfig->kkiapay_public_key ?? '',
                'kkiapay_sandbox'     => (bool) $paymentConfig->kkiapay_sandbox,
                'has_kkiapay_private' => !empty($paymentConfig->kkiapay_private_key),
                'has_kkiapay_secret'  => !empty($paymentConfig->kkiapay_secret_key),
                'fedapay_public_key'  => $paymentConfig->fedapay_public_key ?? '',
                'fedapay_sandbox'     => (bool) ($paymentConfig->fedapay_sandbox ?? true),
                'has_fedapay_secret'  => !empty($paymentConfig->fedapay_secret_key),
            ];

            return view('home', [
                'data'                 => $element,
                'activePaymentProvider' => $activePaymentProvider,
                'activePaymentSandbox'  => $activePaymentSandbox,
                'paymentCfgData'        => $paymentCfgData,
            ]);

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
                                        ->select(
                                            'locataires.id', 'locataires.nom', 'locataires.prenom',
                                            'locataires.telephone', 'locataires.profession', 'locataires.date_entree',
                                            'maisons.nom_maison', 'chambres.numero_chambre'
                                        )
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
                                                ->select(
                                                    'proprietaires.id', 'proprietaires.nom', 'proprietaires.prenom',
                                                    'proprietaires.telephone', 'proprietaires.adresse',
                                                    'maisons.nom_maison', 'maisons.quartier'
                                                )
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
      $user = User::whereNull('status')->select('id', 'nom', 'prenom')->get();

      if ($request->choix == 'by_user') {
        $all = Activity::where('causer_id', $request->user_name)
                        ->orderByDesc('created_at')
                        ->take(200)
                        ->get();

        return view('historique',['all' => $all, 'users' => $user]);
      } else if ($request->choix == 'by_date') {

        $all = Activity::whereBetween('created_at',[$request->date_debut.' 01:00:00', $request->date_fin.' 23:59:59'])
                        ->orderByDesc('created_at')
                        ->take(500)
                        ->get();

        return view('historique',['all' => $all, 'users' => $user]);

      } else {
        $all = Activity::take(5)->orderByDesc('created_at')->get();

        return view('historique',['all' => $all, 'users' => $user]);
      }

    }


    public function getcontratpdf(Request $request)
    {
        $data = Locataire::where('locataires.id', $request->idlocataire)
                    ->whereNull('locataires.delete_at')
                    ->where('locataires.status', true)
                    ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
                    ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
                    ->select(
                        'locataires.*',
                        'maisons.nom_maison',
                        'maisons.quartier as quartier_maison',
                        'chambres.type_chambre',
                        'chambres.numero_chambre',
                        'chambres.prix_chambre'
                    )
                    ->first();

        if (!$data) {
            return redirect()->back()->with('error', 'Locataire introuvable');
        }

        // Récupérer les infos de l'agence
        $idannexe_ref = get_active_annexe_id() ?? $data->idannexe_ref;
        $agence = get_annexe_details_for_invoice($idannexe_ref);

        if (!$agence) {
            $agence = [
                'designation'    => 'Agence Immobilière',
                'telephone'      => '',
                'email'          => '',
                'siege_social'   => '',
                'logo_path'      => null,
                'logo_base64'    => null,
                'signature_path' => null,
                'signature_base64' => null,
            ];
        }

        // Charger la config personnalisée du contrat pour cette direction
        $contratConfig = \App\ContratConfig::where('iddirection_ref', Auth::user()->iddirection_ref)->first();

        // Table de remplacement des variables dynamiques
        $replacements = [
            '{nom_agence}'           => $agence['designation'] ?? '',
            '{adresse_agence}'       => $agence['siege_social'] ?? '',
            '{telephone_agence}'     => $agence['telephone'] ?? '',
            '{nom_locataire}'        => trim(($data->nom ?? '') . ' ' . ($data->prenom ?? '')),
            '{telephone_locataire}'  => $data->telephone ?? '',
            '{profession_locataire}' => $data->profession ?? '',
            '{adresse_locataire}'    => $data->quartier ?? '',
            '{nom_maison}'           => $data->nom_maison ?? '',
            '{quartier_maison}'      => $data->quartier_maison ?? '',
            '{type_chambre}'         => $data->type_chambre ?? '',
            '{numero_chambre}'       => $data->numero_chambre ?? '',
            '{montant_loyer}'        => number_format($data->prix_mois ?? 0, 0, ',', '.') . ' F CFA',
            '{nombre_caution}'       => $data->nombre_caution ?? 0,
            '{montant_caution}'      => number_format(($data->nombre_caution ?? 0) * ($data->prix_mois ?? 0), 0, ',', '.') . ' F CFA',
            '{caution_courant}'      => number_format($data->caution_courant ?? 0, 0, ',', '.') . ' F CFA',
            '{caution_eau}'          => number_format($data->caution_eau ?? 0, 0, ',', '.') . ' F CFA',
            '{nombre_avance}'        => $data->nombre_avance ?? 0,
            '{montant_avance}'       => number_format(($data->nombre_avance ?? 0) * ($data->prix_mois ?? 0), 0, ',', '.') . ' F CFA',
            '{mode_paiement}'        => $data->mode_paiement ?? 'tout moyen convenu entre les parties',
            '{date_entree}'          => isset($data->date_entree) ? Carbon::parse($data->date_entree)->translatedFormat('d F Y') : 'N/A',
            '{date_contrat}'         => Carbon::now()->translatedFormat('d F Y'),
        ];

        // Appliquer les replacements sur les articles personnalisés
        $articlesCustom = null;
        if ($contratConfig && !empty($contratConfig->articles)) {
            $articlesCustom = array_map(function ($article) use ($replacements) {
                return [
                    'titre'   => str_replace(array_keys($replacements), array_values($replacements), $article['titre']),
                    'contenu' => str_replace(array_keys($replacements), array_values($replacements), $article['contenu']),
                ];
            }, $contratConfig->articles);
        }

        $pdf = PDF::loadView('pdf.contrat', compact('data', 'agence', 'contratConfig', 'articlesCustom'))
                    ->setPaper('a4', 'portrait')
                    ->setOptions(['defaultFont' => 'DejaVu Sans', 'isRemoteEnabled' => true]);

        $pdf->output();

        activity()->performedOn(new Locataire())
                    ->causedBy(Auth::user()->id)
                    ->log("Téléchargement du contrat du locataire " . ($data->nom ?? '') . " " . ($data->prenom ?? '') . " par " . Auth::user()->nom . " " . Auth::user()->prenom);

        $filename = 'Contrat_' . ($data->nom ?? '') . '_' . ($data->prenom ?? '') . '_' . Carbon::now()->format('d-m-Y') . '.pdf';
        return $pdf->download($filename);
    }


    public function getLocatairesForContrat(Request $request)
    {
        try {
            $idannexe_ref = get_active_annexe_id();

            $locataires = Locataire::whereNull('locataires.delete_at')
                            ->where('locataires.status', true)
                            ->where('locataires.iddirection_ref', Auth::user()->iddirection_ref)
                            ->when($idannexe_ref, function ($query) use ($idannexe_ref) {
                                $query->where('locataires.idannexe_ref', $idannexe_ref);
                            })
                            ->when(!Gate::allows('Is_admin'), function ($query) {
                                $query->where('locataires.idannexe_ref', Auth::user()->idannexe_ref);
                            })
                            ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
                            ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
                            ->select(
                                'locataires.id',
                                'locataires.nom',
                                'locataires.prenom',
                                'locataires.telephone',
                                'locataires.prix_mois',
                                'maisons.nom_maison',
                                'chambres.type_chambre',
                                'chambres.numero_chambre'
                            )
                            ->orderBy('locataires.nom')
                            ->get();

            return response()->json([
                'status' => true,
                'data' => $locataires
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Erreur lors de la récupération des locataires'
            ]);
        }
    }
}
