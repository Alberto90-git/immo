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
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use PDF;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;



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

            // Statistiques sur 12 mois glissants — 2 requêtes GROUP BY au lieu de 24
            $paiementsParMois  = [];
            $locatairesParMois = [];
            $moisLabels        = [];
            Carbon::setLocale('fr');
            $dirId     = Auth::user()->iddirection_ref;
            $dateDebut = Carbon::now()->subMonths(11)->startOfMonth()->toDateString();

            $paiementsGroupes = Facture::whereNull('delete_at')
                ->where('iddirection_ref', $dirId)
                ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
                ->where('date_paiement', '>=', $dateDebut)
                ->selectRaw("TO_CHAR(date_paiement, 'YYYY-MM') as mois_cle, SUM(montant) as total")
                ->groupByRaw("TO_CHAR(date_paiement, 'YYYY-MM')")
                ->pluck('total', 'mois_cle');

            $locatairesGroupes = Locataire::whereNull('delete_at')
                ->where('iddirection_ref', $dirId)
                ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
                ->where('date_entree', '>=', $dateDebut)
                ->selectRaw("TO_CHAR(date_entree, 'YYYY-MM') as mois_cle, COUNT(*) as total")
                ->groupByRaw("TO_CHAR(date_entree, 'YYYY-MM')")
                ->pluck('total', 'mois_cle');

            for ($i = 11; $i >= 0; $i--) {
                $mois = Carbon::now()->subMonths($i);
                $moisKey = $mois->format('Y-m');
                $moisLabels[]        = ucfirst($mois->translatedFormat('M Y'));
                $paiementsParMois[]  = (int) ($paiementsGroupes[$moisKey]  ?? 0);
                $locatairesParMois[] = (int) ($locatairesGroupes[$moisKey] ?? 0);
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

        if (!$vide)  $vide  = '<tr><td colspan="4" class="text-center text-muted">' . __('messages.no_data') . '</td></tr>';
        if (!$vide2) $vide2 = '<tr><td colspan="5" class="text-center text-muted">' . __('messages.no_data') . '</td></tr>';

        // Statistiques graphiques pour cette annexe — 2 GROUP BY au lieu de 24 requêtes
        Carbon::setLocale('fr');
        $paiementsParMois  = [];
        $locatairesParMois = [];
        $moisLabels        = [];
        $dateDebutStats    = Carbon::now()->subMonths(11)->startOfMonth()->toDateString();
        $dirIdL            = Auth::user()->iddirection_ref;

        $paiementsGroupesL = Facture::whereNull('delete_at')
            ->where('iddirection_ref', $dirIdL)
            ->when($annexe_id !== 'all', fn($q) => $q->where('idannexe_ref', $annexe_id))
            ->where('date_paiement', '>=', $dateDebutStats)
            ->selectRaw("TO_CHAR(date_paiement, 'YYYY-MM') as mois_cle, SUM(montant) as total")
            ->groupByRaw("TO_CHAR(date_paiement, 'YYYY-MM')")
            ->pluck('total', 'mois_cle');

        $locatairesGroupesL = Locataire::whereNull('delete_at')
            ->where('iddirection_ref', $dirIdL)
            ->when($annexe_id !== 'all', fn($q) => $q->where('idannexe_ref', $annexe_id))
            ->where('date_entree', '>=', $dateDebutStats)
            ->selectRaw("TO_CHAR(date_entree, 'YYYY-MM') as mois_cle, COUNT(*) as total")
            ->groupByRaw("TO_CHAR(date_entree, 'YYYY-MM')")
            ->pluck('total', 'mois_cle');

        for ($i = 11; $i >= 0; $i--) {
            $mois    = Carbon::now()->subMonths($i);
            $moisKey = $mois->format('Y-m');
            $moisLabels[]        = ucfirst($mois->translatedFormat('M Y'));
            $paiementsParMois[]  = (int) ($paiementsGroupesL[$moisKey]  ?? 0);
            $locatairesParMois[] = (int) ($locatairesGroupesL[$moisKey] ?? 0);
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



    // ── Tableau de bord analytique (AJAX) ────────────────────────────────────────
    public function analyticsData(Request $request)
    {
        try {
            $dirId        = Auth::user()->iddirection_ref;
            $idannexe_ref = get_active_annexe_id();
            $now          = Carbon::now();

            // ── Occupation par bâtiment ──────────────────────────────────────────
            $maisons = Maison::whereNull('maisons.delete_at')
                ->where('maisons.iddirection_ref', $dirId)
                ->when($idannexe_ref, fn($q) => $q->where('maisons.idannexe_ref', $idannexe_ref))
                ->leftJoin('chambres as c_all', function($j) {
                    $j->on('c_all.maison_id', '=', 'maisons.id')->whereNull('c_all.delete_at');
                })
                ->leftJoin('chambres as c_occ', function($j) {
                    $j->on('c_occ.maison_id', '=', 'maisons.id')->whereNull('c_occ.delete_at')->where('c_occ.etat', 1);
                })
                ->groupBy('maisons.id', 'maisons.nom_maison')
                ->selectRaw('maisons.id, maisons.nom_maison,
                    COUNT(DISTINCT c_all.id) as total_chambres,
                    COUNT(DISTINCT c_occ.id) as chambres_occupees')
                ->get()
                ->map(fn($m) => [
                    'nom'     => $m->nom_maison,
                    'total'   => (int) $m->total_chambres,
                    'occupees'=> (int) $m->chambres_occupees,
                    'taux'    => $m->total_chambres > 0 ? round(($m->chambres_occupees / $m->total_chambres) * 100) : 0,
                ]);

            // ── Taux de recouvrement du mois ─────────────────────────────────────
            $loyersAttendus = (int) Locataire::whereNull('delete_at')
                ->where('status', true)->where('iddirection_ref', $dirId)
                ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
                ->sum('prix_mois');

            $loyersEncaisses = (int) Facture::whereNull('delete_at')
                ->where('iddirection_ref', $dirId)
                ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
                ->whereYear('date_paiement', $now->year)->whereMonth('date_paiement', $now->month)
                ->sum('montant');

            $tauxRecouvrement = $loyersAttendus > 0 ? round(($loyersEncaisses / $loyersAttendus) * 100, 1) : 0;

            // ── Comparaison N vs N-1 — 1 requête GROUP BY au lieu de 24 ─────────
            $revenusN  = array_fill(0, 12, 0);
            $revenusN1 = array_fill(0, 12, 0);
            $moisCourts = [];

            $revenusParAnMois = Facture::whereNull('delete_at')
                ->where('iddirection_ref', $dirId)
                ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
                ->whereRaw('EXTRACT(YEAR FROM date_paiement) IN (?, ?)', [$now->year, $now->year - 1])
                ->selectRaw('EXTRACT(YEAR FROM date_paiement)::int as annee, EXTRACT(MONTH FROM date_paiement)::int as mois, SUM(montant) as total')
                ->groupByRaw('EXTRACT(YEAR FROM date_paiement), EXTRACT(MONTH FROM date_paiement)')
                ->get()
                ->groupBy('annee');

            $byN  = ($revenusParAnMois[$now->year]       ?? collect())->keyBy('mois');
            $byN1 = ($revenusParAnMois[$now->year - 1]   ?? collect())->keyBy('mois');

            for ($i = 0; $i < 12; $i++) {
                $mois = Carbon::createFromDate($now->year, 1, 1)->addMonths($i);
                $moisCourts[] = ucfirst($mois->locale('fr')->monthName);
                $revenusN[$i]  = (int) ($byN->get($mois->month)?->total  ?? 0);
                $revenusN1[$i] = (int) ($byN1->get($mois->month)?->total ?? 0);
            }

            // ── Impayés du mois courant ──────────────────────────────────────────
            $impayes = Locataire::whereNull('locataires.delete_at')
                ->where('locataires.status', true)->where('locataires.iddirection_ref', $dirId)
                ->when($idannexe_ref, fn($q) => $q->where('locataires.idannexe_ref', $idannexe_ref))
                ->whereNotExists(function($q) use ($now) {
                    $q->from('factures')
                      ->whereColumn('factures.locataire_id', 'locataires.id')
                      ->whereNull('factures.delete_at')
                      ->whereYear('factures.date_paiement', $now->year)
                      ->whereMonth('factures.date_paiement', $now->month);
                })
                ->join('maisons', 'locataires.maison_id', '=', 'maisons.id')
                ->join('chambres', 'locataires.chambre_id', '=', 'chambres.id')
                ->select(
                    'locataires.nom', 'locataires.prenom', 'locataires.telephone',
                    'locataires.prix_mois', 'locataires.date_entree',
                    'maisons.nom_maison', 'chambres.numero_chambre'
                )
                ->get()
                ->map(fn($l) => [
                    'nom'       => $l->prenom . ' ' . $l->nom,
                    'telephone' => $l->telephone ?? '–',
                    'montant'   => $l->prix_mois,
                    'logement'  => $l->nom_maison . ' · ' . $l->numero_chambre,
                    'anciennete'=> Carbon::parse($l->date_entree)->diffInMonths($now),
                ]);

            // ── Top propriétaires rentables (12 mois glissants) ─────────────────
            $topProprios = Proprietaire::whereNull('proprietaires.delete_at')
                ->where('proprietaires.iddirection_ref', $dirId)
                ->when($idannexe_ref, fn($q) => $q->where('proprietaires.idannexe_ref', $idannexe_ref))
                ->join('maisons', 'maisons.proprio_id', '=', 'proprietaires.id')
                ->whereNull('maisons.delete_at')
                ->join('factures', 'factures.maison_id', '=', 'maisons.id')
                ->whereNull('factures.delete_at')
                ->where('factures.date_paiement', '>=', $now->copy()->subMonths(12)->startOfDay())
                ->groupBy('proprietaires.id', 'proprietaires.nom', 'proprietaires.prenom')
                ->selectRaw('proprietaires.id, proprietaires.nom, proprietaires.prenom, SUM(factures.montant) as total_revenus')
                ->orderByDesc('total_revenus')
                ->limit(6)
                ->get()
                ->map(fn($p) => ['nom' => $p->prenom . ' ' . $p->nom, 'total' => (int) $p->total_revenus]);

            // ── Contrats à renouveler (>= 11 mois sans date_fin_bail) ───────────
            $nbARenouveler = Locataire::whereNull('delete_at')
                ->where('status', true)->where('iddirection_ref', $dirId)
                ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
                ->where('date_entree', '<=', $now->copy()->subMonths(11)->toDateString())
                ->count();

            // ── Totaux N et N-1 pour comparaison ────────────────────────────────
            $totalN  = array_sum($revenusN);
            $totalN1 = array_sum($revenusN1);
            $deltaRev = $totalN1 > 0 ? round((($totalN - $totalN1) / $totalN1) * 100, 1) : null;

            $nbLocatairesN  = Locataire::whereNull('delete_at')->where('iddirection_ref', $dirId)
                ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
                ->whereYear('date_entree', $now->year)->count();
            $nbLocatairesN1 = Locataire::whereNull('delete_at')->where('iddirection_ref', $dirId)
                ->when($idannexe_ref, fn($q) => $q->where('idannexe_ref', $idannexe_ref))
                ->whereYear('date_entree', $now->year - 1)->count();
            $deltaLoc = $nbLocatairesN1 > 0 ? round((($nbLocatairesN - $nbLocatairesN1) / $nbLocatairesN1) * 100, 1) : null;

            return response()->json([
                'occupation_par_maison' => $maisons,
                'taux_recouvrement'     => $tauxRecouvrement,
                'loyers_attendus'       => $loyersAttendus,
                'loyers_encaisses'      => $loyersEncaisses,
                'revenus_n'             => $revenusN,
                'revenus_n1'            => $revenusN1,
                'mois_courts'           => $moisCourts,
                'annee_n'               => $now->year,
                'annee_n1'              => $now->year - 1,
                'total_n'               => $totalN,
                'total_n1'              => $totalN1,
                'delta_rev'             => $deltaRev,
                'nb_locataires_n'       => $nbLocatairesN,
                'nb_locataires_n1'      => $nbLocatairesN1,
                'delta_loc'             => $deltaLoc,
                'impayes'               => $impayes,
                'nb_impayes'            => $impayes->count(),
                'nb_a_renouveler'       => $nbARenouveler,
                'top_proprios'          => $topProprios,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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
                  'message' => __('messages.update_success'),
              ]);

            }

        } catch (QueryException $e) {

            return response()->json([
                    'status' => false,
                    'message' => __('messages.update_fail'),
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
                    'message' => __('messages.update_success'),
                ]);

            }

        }
        catch (QueryException $e) {
            return response()->json([
                'status' => false,
                'message' => __('messages.update_fail'),
            ]);
        }
    }


    public function historique(Request $request)
    {
        $authUser = Auth::user();

        // Construire la liste des users visibles selon le rôle
        $isAdmin = ($authUser->is_admin == 1 && $authUser->type_compte != 'Particulier');

        $userQuery = User::whereNull('status')
                         ->where('iddirection_ref', $authUser->iddirection_ref)
                         ->select('id', 'nom', 'prenom', 'idannexe_ref');

        // Non-admin : restreint à sa propre agence
        if (!$isAdmin) {
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
            return redirect()->back()->with('error', __('messages.tenant_not_found'));
        }

        App::setLocale(get_direction_locale(Auth::user()->iddirection_ref));

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

        // Charger la config du contrat (template sélectionné ou défaut)
        $dirId = Auth::user()->iddirection_ref;
        if ($request->filled('contrat_config_id')) {
            $contratConfig = \App\ContratConfig::where('id', $request->contrat_config_id)
                ->where('iddirection_ref', $dirId)
                ->first();
        } else {
            $contratConfig = \App\ContratConfig::where('iddirection_ref', $dirId)
                ->where('is_default', true)
                ->first()
                ?? \App\ContratConfig::where('iddirection_ref', $dirId)->first();
        }

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
            '{montant_loyer}'        => format_price($data->prix_mois ?? 0, $dirId),
            '{nombre_caution}'       => $data->nombre_caution ?? 0,
            '{montant_caution}'      => format_price(($data->nombre_caution ?? 0) * ($data->prix_mois ?? 0), $dirId),
            '{caution_courant}'      => format_price($data->caution_courant ?? 0, $dirId),
            '{caution_eau}'          => format_price($data->caution_eau ?? 0, $dirId),
            '{nombre_avance}'        => $data->nombre_avance ?? 0,
            '{montant_avance}'       => format_price(($data->nombre_avance ?? 0) * ($data->prix_mois ?? 0), $dirId),
            '{mode_paiement}'        => $data->mode_paiement ?? __('pdf.contrat_art4_mode_default'),
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
                'message' => __('messages.fetch_tenants_error')
            ]);
        }
    }
}
