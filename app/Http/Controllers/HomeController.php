<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Locataire;
use App\PlatformConfig;
use App\Maison;
use App\Proprietaire;
use App\User;
use App\Chambre;
use App\Facture;
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

            $element['chambresOccupees'] = Chambre::whereNull('delete_at')
                                                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                                                ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                                                    $query->where('idannexe_ref', $idannexe_ref);
                                                })
                                                ->where('etat', 1)
                                                ->count();

            // Revenus du mois courant
            $element['totalRevenusMois'] = Facture::whereNull('delete_at')
                                                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                                                ->when($idannexe_ref, function($query) use ($idannexe_ref) {
                                                    $query->where('idannexe_ref', $idannexe_ref);
                                                })
                                                ->whereMonth('date_paiement', Carbon::now()->month)
                                                ->whereYear('date_paiement', Carbon::now()->year)
                                                ->sum('montant');

            // Statistiques sur 12 mois glissants
            $paiementsParMois  = [];
            $locatairesParMois = [];
            $moisLabels        = [];
            Carbon::setLocale('fr');

            for ($i = 11; $i >= 0; $i--) {
                $mois = Carbon::now()->subMonths($i);
                $moisLabels[] = ucfirst($mois->translatedFormat('M Y'));

                $paiementsParMois[] = (int) Facture::whereNull('delete_at')
                    ->where('iddirection_ref', Auth::user()->iddirection_ref)
                    ->when($idannexe_ref, function($q) use ($idannexe_ref) {
                        $q->where('idannexe_ref', $idannexe_ref);
                    })
                    ->whereMonth('date_paiement', $mois->month)
                    ->whereYear('date_paiement', $mois->year)
                    ->sum('montant');

                $locatairesParMois[] = (int) Locataire::whereNull('delete_at')
                    ->where('iddirection_ref', Auth::user()->iddirection_ref)
                    ->when($idannexe_ref, function($q) use ($idannexe_ref) {
                        $q->where('idannexe_ref', $idannexe_ref);
                    })
                    ->whereMonth('date_entree', $mois->month)
                    ->whereYear('date_entree', $mois->year)
                    ->count();
            }

            $element['moisLabels']        = $moisLabels;
            $element['paiementsParMois']  = $paiementsParMois;
            $element['locatairesParMois'] = $locatairesParMois;

            // Derniers paiements
            $element['recentsPaiements'] = Facture::whereNull('factures.delete_at')
                ->where('factures.iddirection_ref', Auth::user()->iddirection_ref)
                ->when($idannexe_ref, function($q) use ($idannexe_ref) {
                    $q->where('factures.idannexe_ref', $idannexe_ref);
                })
                ->join('locataires', 'factures.locataire_id', '=', 'locataires.id')
                ->join('maisons', 'factures.maison_id', '=', 'maisons.id')
                ->select(
                    'factures.montant', 'factures.date_paiement',
                    'factures.mois', 'factures.mode_paiement',
                    'locataires.nom', 'locataires.prenom', 'maisons.nom_maison'
                )
                ->orderByDesc('factures.created_at')
                ->limit(8)
                ->get();

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
            \Illuminate\Support\Facades\Log::error('HomeController::index QueryException: ' . $e->getMessage());

            return view('home', [
                'data'                  => [],
                'activePaymentProvider' => 'none',
                'activePaymentSandbox'  => true,
                'paymentCfgData'        => [],
                '_db_error'             => $e->getMessage(),
            ]);
        }
    }

    //LISTE DES LOCATAIRES
    public function getLocataire(Request $request)
    {
        $vide  = '';
        $vide2 = '';
        $annexe_id = $request->annexe_id;

        $element['locataire'] = Locataire::whereNull('locataires.delete_at')
                                        ->when($annexe_id !== 'all', function($q) use ($annexe_id) {
                                            $q->where('locataires.idannexe_ref', $annexe_id);
                                        })
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
                                                ->when($annexe_id !== 'all', function($q) use ($annexe_id) {
                                                    $q->where('proprietaires.idannexe_ref', $annexe_id);
                                                })
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
                                                ->when($annexe_id !== 'all', function($q) use ($annexe_id) {
                                                    $q->where('idannexe_ref', $annexe_id);
                                                })
                                                ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                                ->where(function($querry){
                                                    if (Gate::none(['Is_admin'])) {
                                                        $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                                    }
                                                })
                                                ->count();

        $element['nombreMaison'] = Maison::whereNull('delete_at')
                                        ->when($annexe_id !== 'all', function($q) use ($annexe_id) {
                                            $q->where('idannexe_ref', $annexe_id);
                                        })
                                        ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                        ->where(function($querry){
                                            if (Gate::none(['Is_admin'])) {
                                                $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                            }
                                        })
                                        ->count();


        $element['nombreLocataire'] = Locataire::whereNull('delete_at')
                                                ->where('status',true)
                                                ->when($annexe_id !== 'all', function($q) use ($annexe_id) {
                                                    $q->where('idannexe_ref', $annexe_id);
                                                })
                                                ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                                ->where(function($querry){
                                                    if (Gate::none(['Is_admin'])) {
                                                        $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                                    }
                                                })
                                                ->count();


        $element['nombreChambre'] = Chambre::whereNull('delete_at')
                                            ->when($annexe_id !== 'all', function($q) use ($annexe_id) {
                                                $q->where('idannexe_ref', $annexe_id);
                                            })
                                            ->where('iddirection_ref',Auth::user()->iddirection_ref)
                                            ->where(function($querry){
                                                if (Gate::none(['Is_admin'])) {
                                                    $querry->where('idannexe_ref',Auth::user()->idannexe_ref);
                                                }
                                            })
                                            ->count();



        // Génération HTML tables
        foreach ($element['locataire'] as $value) {
            $vide .= '<tr>'
                . '<td>' . $value->nom_maison . '</td>'
                . '<td>' . $value->numero_chambre . '</td>'
                . '<td>' . $value->nom . ' ' . $value->prenom . '</td>'
                . '<td>' . Carbon::parse($value->date_entree)->format('d/m/Y') . '</td>'
                . '</tr>';
        }

        foreach ($element['proprioMaison'] as $value2) {
            $vide2 .= '<tr>'
                . '<td>' . $value2->nom . ' ' . $value2->prenom . '</td>'
                . '<td>' . $value2->telephone . '</td>'
                . '<td>' . $value2->adresse . '</td>'
                . '<td>' . $value2->nom_maison . '</td>'
                . '<td>' . $value2->quartier . '</td>'
                . '</tr>';
        }

        if (!$vide)  $vide  = '<tr><td colspan="4" class="text-center text-muted">Aucune donnée</td></tr>';
        if (!$vide2) $vide2 = '<tr><td colspan="5" class="text-center text-muted">Aucune donnée</td></tr>';

        // Statistiques graphiques pour cette annexe
        Carbon::setLocale('fr');
        $paiementsParMois  = [];
        $locatairesParMois = [];
        $moisLabels        = [];

        for ($i = 11; $i >= 0; $i--) {
            $mois = Carbon::now()->subMonths($i);
            $moisLabels[] = ucfirst($mois->translatedFormat('M Y'));

            $paiementsParMois[] = (int) Facture::whereNull('delete_at')
                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                ->when($annexe_id !== 'all', function($q) use ($annexe_id) {
                    $q->where('idannexe_ref', $annexe_id);
                })
                ->whereMonth('date_paiement', $mois->month)
                ->whereYear('date_paiement', $mois->year)
                ->sum('montant');

            $locatairesParMois[] = (int) Locataire::whereNull('delete_at')
                ->where('iddirection_ref', Auth::user()->iddirection_ref)
                ->when($annexe_id !== 'all', function($q) use ($annexe_id) {
                    $q->where('idannexe_ref', $annexe_id);
                })
                ->whereMonth('date_entree', $mois->month)
                ->whereYear('date_entree', $mois->year)
                ->count();
        }

        $chambresOccupees = Chambre::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->when($annexe_id !== 'all', function($q) use ($annexe_id) {
                $q->where('idannexe_ref', $annexe_id);
            })
            ->where('etat', 1)
            ->count();

        $totalRevenusMois = (int) Facture::whereNull('delete_at')
            ->where('iddirection_ref', Auth::user()->iddirection_ref)
            ->when($annexe_id !== 'all', function($q) use ($annexe_id) {
                $q->where('idannexe_ref', $annexe_id);
            })
            ->whereMonth('date_paiement', Carbon::now()->month)
            ->whereYear('date_paiement', Carbon::now()->year)
            ->sum('montant');

        return response()->json([
            'getlist'          => $vide,
            'getlist2'         => $vide2,
            'nombre_proprio'   => $element['nombreProprio'],
            'nombre_maison'    => $element['nombreMaison'],
            'nombre_locataire' => $element['nombreLocataire'],
            'nombre_chambre'   => $element['nombreChambre'],
            'stats' => [
                'mois_labels'         => $moisLabels,
                'paiements_par_mois'  => $paiementsParMois,
                'locataires_par_mois' => $locatairesParMois,
                'chambres_occupees'   => $chambresOccupees,
                'nombre_chambre'      => $element['nombreChambre'],
                'total_revenus_mois'  => $totalRevenusMois,
            ],
        ]);
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
        $authUser = Auth::user();

        // Construire la liste des users visibles selon le rôle
        $userQuery = User::whereNull('status')
                         ->where('iddirection_ref', $authUser->iddirection_ref)
                         ->select('id', 'nom', 'prenom', 'idannexe_ref');

        // Non-admin : restreint à sa propre agence
        if (Gate::none(['Is_admin'])) {
            $userQuery->where('idannexe_ref', $authUser->idannexe_ref);
        }

        $users        = $userQuery->get();
        $allowedIds   = $users->pluck('id')->toArray();

        // Map id → nom pour affichage dans la vue
        $usersMap = $users->keyBy('id');

        $today   = now()->format('Y-m-d');
        $choix   = $request->choix;
        $dateDebut = $request->date_debut ?? $today;
        $dateFin   = $request->date_fin   ?? $today;
        $userName  = $request->user_name;

        if ($choix == 'by_user') {
            // Vérifier que l'user demandé est bien dans les autorisés
            if (!in_array($userName, $allowedIds)) {
                $all = collect();
            } else {
                $all = Activity::where('causer_id', $userName)
                                ->orderByDesc('created_at')
                                ->take(200)
                                ->get();
            }
        } elseif ($choix == 'by_date') {
            $all = Activity::whereIn('causer_id', $allowedIds)
                            ->whereBetween('created_at', [$dateDebut.' 00:00:00', $dateFin.' 23:59:59'])
                            ->orderByDesc('created_at')
                            ->take(500)
                            ->get();
        } else {
            // Par défaut : aujourd'hui
            $choix = 'by_date';
            $all = Activity::whereIn('causer_id', $allowedIds)
                            ->whereBetween('created_at', [$today.' 00:00:00', $today.' 23:59:59'])
                            ->orderByDesc('created_at')
                            ->get();
        }

        return view('historique', [
            'all'       => $all,
            'users'     => $users,
            'usersMap'  => $usersMap,
            'choix'     => $choix,
            'dateDebut' => $dateDebut,
            'dateFin'   => $dateFin,
            'userName'  => $userName,
        ]);
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
