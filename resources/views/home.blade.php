@extends('layouts.template')

@section('content')

  @section('title')
    <title>{{ __('dashboard.title') }}</title>
  @endsection

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<style>
  .stat-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 12px rgba(0,0,0,.08);
    transition: transform .15s;
  }
  .stat-card:hover { transform: translateY(-3px); }
  .stat-icon {
    width: 52px; height: 52px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem;
  }
  .chart-card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 2px 12px rgba(0,0,0,.08);
  }
  .kpi-label { font-size: .8rem; color: #8592a3; font-weight: 500; text-transform: uppercase; letter-spacing: .05em; }
  .kpi-value { font-size: 1.75rem; font-weight: 700; line-height: 1.2; }
  .kpi-sub   { font-size: .8rem; color: #8592a3; margin-top: 2px; }
  .taux-bar-wrap { height: 6px; background:#e7e9ef; border-radius: 3px; margin-top: 8px; }
  .taux-bar      { height: 6px; border-radius: 3px; background: linear-gradient(90deg,#696cff,#9155fd); }
  /* Dark mode */
  html.dark-style .taux-bar-wrap { background:#444564; }
  html.dark-style .kpi-label,
  html.dark-style .kpi-sub { color:#7f7f9d; }
</style>

<div class="container-xxl flex-grow-1 container-p-y">

  @if(isset($_db_error))
    <div class="alert alert-danger">
      <i class="bx bx-error-circle me-1"></i>
      <strong>Erreur base de données :</strong> {{ $_db_error }}
    </div>
  @endif

  @php $liste = get_annexe_liste(); @endphp

  {{-- ─── Filtre annexe (admins seulement) ─── --}}
  @can('Is_admin')
  <div class="row mb-3">
    <div class="col-md-5 col-lg-4">
      <div class="card stat-card">
        <div class="card-body py-2 px-3">
          <label class="form-label small text-muted mb-1"><i class="bx bx-filter-alt me-1"></i>{{ __('dashboard.filter_agency') }}</label>
          <select class="form-select form-select-sm" id="annexe_id">
            <option value="" disabled selected>{{ __('dashboard.select_agency') }}</option>
            <option value="all">{{ __('dashboard.all_agencies') }}</option>
            @foreach($liste as $list)
              <option value="{{ $list->idannexes }}">{{ $list->designation }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>
  @endcan

  {{-- ─── Vue agence non-admin ─── --}}
  @if(Auth::user()->is_admin != 1)
    <div class="card mb-4">
      <h5 class="card-header text-center">{{ __('dashboard.all_tenants') }}</h5>
      <div class="table-responsive text-nowrap">
        <table class="table table-hover border-primary" style="width:100%">
          <thead><tr>
            <th>{{ __('dashboard.house') }}</th><th>{{ __('dashboard.room_no') }}</th><th>{{ __('dashboard.tenant') }}</th>
            <th>{{ __('dashboard.phone') }}</th><th>{{ __('dashboard.profession') }}</th><th>{{ __('dashboard.entry_date') }}</th>
          </tr></thead>
          <tbody>
            @forelse($data['locataire'] ?? [] as $items)
            <tr>
              <td>{{ $items->nom_maison }}</td>
              <td>{{ $items->numero_chambre }}</td>
              <td>{{ $items->nom }} {{ $items->prenom }}</td>
              <td>{{ $items->telephone }}</td>
              <td>{{ $items->profession }}</td>
              <td>{{ $items->date_entree }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">{{ __('dashboard.no_tenant') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    <div class="card mb-4">
      <h5 class="card-header text-center">{{ __('dashboard.all_owners') }}</h5>
      <div class="table-responsive text-nowrap">
        <table class="table table-hover border-primary" style="width:100%">
          <thead><tr>
            <th>{{ __('dashboard.first_last_name') }}</th><th>{{ __('dashboard.phone') }}</th><th>{{ __('dashboard.address') }}</th><th>{{ __('dashboard.house') }}</th><th>{{ __('dashboard.district') }}</th>
          </tr></thead>
          <tbody>
            @forelse($data['proprioMaison'] ?? [] as $items)
            <tr>
              <td>{{ $items->nom }} {{ $items->prenom }}</td>
              <td>{{ $items->telephone }}</td>
              <td>{{ $items->adresse }}</td>
              <td>{{ $items->nom_maison }}</td>
              <td>{{ $items->quartier }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted">{{ __('dashboard.no_owner') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  @endif

  {{-- ─── Section statistiques admin ─── --}}
  @can('Is_admin')

  {{-- KPI Cards ligne 1 --}}
  <div class="row g-3 mb-3">
    {{-- Propriétaires --}}
    <div class="col-6 col-md-3">
      <div class="card stat-card h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-icon bg-label-primary"><i class="bx bxs-user-check text-primary"></i></div>
          <div>
            <div class="kpi-label">{{ __('dashboard.owners') }}</div>
            <div class="kpi-value" id="nombre_proprio">{{ $data['nombreProprio'] ?? 0 }}</div>
            <div class="kpi-sub">{{ __('dashboard.in_total') }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Maisons --}}
    <div class="col-6 col-md-3">
      <div class="card stat-card h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-icon bg-label-success"><i class="bx bxs-buildings text-success"></i></div>
          <div>
            <div class="kpi-label">{{ __('dashboard.houses') }}</div>
            <div class="kpi-value" id="nombre_maison">{{ $data['nombreMaison'] ?? 0 }}</div>
            <div class="kpi-sub">{{ __('dashboard.in_total') }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Locataires --}}
    <div class="col-6 col-md-3">
      <div class="card stat-card h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-icon bg-label-warning"><i class="bx bxs-group text-warning"></i></div>
          <div>
            <div class="kpi-label">{{ __('dashboard.tenants') }}</div>
            <div class="kpi-value" id="nombre_locataire">{{ $data['nombreLocataire'] ?? 0 }}</div>
            <div class="kpi-sub">{{ __('dashboard.actives') }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Chambres --}}
    <div class="col-6 col-md-3">
      <div class="card stat-card h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-icon bg-label-info"><i class="bx bx-door-open text-info"></i></div>
          <div>
            <div class="kpi-label">{{ __('dashboard.rooms') }}</div>
            <div class="kpi-value" id="nombre_chambre">{{ $data['nombreChambre'] ?? 0 }}</div>
            <div class="kpi-sub">{{ __('dashboard.in_total') }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- KPI Cards ligne 2 --}}
  <div class="row g-3 mb-4">
    {{-- Revenus du mois --}}
    <div class="col-12 col-md-4">
      <div class="card stat-card h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-icon" style="background:rgba(255,165,0,.15)"><i class="bx bx-money text-warning" style="font-size:1.8rem"></i></div>
          <div>
            <div class="kpi-label">{{ __('dashboard.revenue_month') }} — {{ now()->translatedFormat('F Y') }}</div>
            <div class="kpi-value text-success" id="total_revenus_mois">
              {{ format_price($data['totalRevenusMois'] ?? 0) }}
            </div>
            <div class="kpi-sub">{{ __('dashboard.total_collected') }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Taux d'occupation --}}
    <div class="col-12 col-md-4">
      @php
        $nbChambres  = $data['nombreChambre'] ?? 0;
        $nbOccupees  = $data['chambresOccupees'] ?? 0;
        $taux        = $nbChambres > 0 ? round($nbOccupees / $nbChambres * 100) : 0;
      @endphp
      <div class="card stat-card h-100">
        <div class="card-body">
          <div class="d-flex align-items-center gap-3 mb-1">
            <div class="stat-icon bg-label-danger"><i class="bx bx-home-heart text-danger"></i></div>
            <div>
              <div class="kpi-label">{{ __('dashboard.occupancy_rate') }}</div>
              <div class="kpi-value" id="taux_occupation">{{ $taux }}%</div>
              <div class="kpi-sub" id="occ_detail">
                <span id="nb_occupees">{{ $nbOccupees }}</span> / <span id="nb_total_chambres">{{ $nbChambres }}</span> {{ __('dashboard.rooms_occupied') }}
              </div>
            </div>
          </div>
          <div class="taux-bar-wrap">
            <div class="taux-bar" id="taux_bar" style="width:{{ $taux }}%"></div>
          </div>
        </div>
      </div>
    </div>

    {{-- Locataires ce mois --}}
    <div class="col-12 col-md-4">
      @php
        $tmpLoc = $data['locatairesParMois'] ?? [0]; $locMois = end($tmpLoc);
      @endphp
      <div class="card stat-card h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="stat-icon" style="background:rgba(105,108,255,.12)"><i class="bx bx-user-plus" style="color:#696cff;font-size:1.8rem"></i></div>
          <div>
            <div class="kpi-label">{{ __('dashboard.new_entries') }} — {{ now()->translatedFormat('F') }}</div>
            <div class="kpi-value" style="color:#696cff">{{ $locMois }}</div>
            <div class="kpi-sub">{{ __('dashboard.tenants_entered') }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ─── Graphiques ─── --}}
  <div class="row g-3 mb-4">

    {{-- Graphique barres : Paiements mensuels --}}
    <div class="col-12 col-lg-8">
      <div class="card chart-card h-100">
        <div class="card-header d-flex align-items-center justify-content-between pb-0">
          <h6 class="mb-0"><i class="bx bx-bar-chart-alt-2 me-1 text-primary"></i>{{ __('dashboard.monthly_payments') }}</h6>
          <span class="badge bg-label-primary">{{ get_symbole_devise() }}</span>
        </div>
        <div class="card-body">
          <canvas id="chartPaiements" style="max-height:280px"></canvas>
        </div>
      </div>
    </div>

    {{-- Donut : Occupation chambres --}}
    <div class="col-12 col-lg-4">
      <div class="card chart-card h-100">
        <div class="card-header pb-0">
          <h6 class="mb-0"><i class="bx bx-pie-chart-alt me-1 text-danger"></i>{{ __('dashboard.room_occupation') }}</h6>
        </div>
        <div class="card-body d-flex flex-column align-items-center justify-content-center">
          <canvas id="chartOccupation" style="max-height:220px;max-width:220px"></canvas>
          <div class="d-flex gap-4 mt-3 small">
            <span><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#696cff"></span>{{ __('dashboard.occupied') }} (<span id="leg_occ">{{ $nbOccupees }}</span>)</span>
            <span><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#e7e9ef"></span>{{ __('dashboard.free') }} (<span id="leg_lib">{{ $nbChambres - $nbOccupees }}</span>)</span>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- Courbe : Evolution locataires 12 mois --}}
  <div class="row g-3 mb-4">
    <div class="col-12">
      <div class="card chart-card">
        <div class="card-header d-flex align-items-center justify-content-between pb-0">
          <h6 class="mb-0"><i class="bx bx-line-chart me-1 text-success"></i>{{ __('dashboard.tenant_evolution') }}</h6>
          <span class="badge bg-label-success">{{ __('dashboard.per_month') }}</span>
        </div>
        <div class="card-body">
          <canvas id="chartLocataires" style="max-height:220px"></canvas>
        </div>
      </div>
    </div>
  </div>

  {{-- ─── Tableaux ─── --}}
  <div class="row g-3 mb-4">

    {{-- Derniers paiements --}}
    <div class="col-12 col-xl-7">
      <div class="card chart-card">
        <div class="card-header pb-2">
          <h6 class="mb-0"><i class="bx bx-receipt me-1 text-warning"></i>{{ __('dashboard.latest_payments') }}</h6>
        </div>
        <div class="table-responsive">
          <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>{{ __('dashboard.tenant') }}</th><th>{{ __('dashboard.house') }}</th>
                <th>{{ __('dashboard.month_paid') }}</th><th>{{ __('dashboard.amount') }}</th><th>{{ __('dashboard.mode') }}</th><th>{{ __('dashboard.date') }}</th>
              </tr>
            </thead>
            <tbody id="tbody_paiements">
              @forelse($data['recentsPaiements'] ?? [] as $p)
              <tr>
                <td>{{ $p->nom }} {{ $p->prenom }}</td>
                <td>{{ $p->nom_maison }}</td>
                <td>{{ $p->mois }}</td>
                <td class="text-success fw-bold">{{ format_price($p->montant) }}</td>
                <td><span class="badge bg-label-primary">{{ $p->mode_paiement ?: '-' }}</span></td>
                <td>{{ \Carbon\Carbon::parse($p->date_paiement)->format('d/m/Y') }}</td>
              </tr>
              @empty
              <tr><td colspan="6" class="text-center text-muted py-3">{{ __('dashboard.no_payment') }}</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Liste locataires via annexe --}}
    <div class="col-12 col-xl-5">
      <div class="card chart-card">
        <div class="card-header pb-2">
          <h6 class="mb-0"><i class="bx bx-list-ul me-1 text-info"></i>{{ __('dashboard.tenants_agency') }}</h6>
        </div>
        <div class="table-responsive" style="max-height:340px;overflow-y:auto">
          <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
              <tr><th>{{ __('dashboard.house') }}</th><th>{{ __('dashboard.room') }}</th><th>{{ __('dashboard.tenant') }}</th><th>{{ __('dashboard.entry_date') }}</th></tr>
            </thead>
            <tbody id="list_locataire">
              <tr><td colspan="4" class="text-center text-muted py-3">{{ __('dashboard.select_agency_msg') }}</td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  {{-- Liste propriétaires via annexe --}}
  <div class="card chart-card mb-4">
    <div class="card-header pb-2">
      <h6 class="mb-0"><i class="bx bx-buildings me-1 text-success"></i>{{ __('dashboard.owners_agency') }}</h6>
    </div>
    <div class="table-responsive">
      <table class="table table-sm table-hover mb-0">
        <thead class="table-light">
          <tr><th>{{ __('dashboard.first_last_name') }}</th><th>{{ __('dashboard.phone') }}</th><th>{{ __('dashboard.address') }}</th><th>{{ __('dashboard.house') }}</th><th>{{ __('dashboard.district') }}</th></tr>
        </thead>
        <tbody id="list_proprio">
          <tr><td colspan="5" class="text-center text-muted py-3">{{ __('dashboard.select_agency_msg') }}</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  {{-- ══════════════════════════════════════════════════════════════════════
       SECTION ANALYTIQUE
  ══════════════════════════════════════════════════════════════════════ --}}
  <div class="mt-2 mb-2 d-flex align-items-center gap-2">
    <h5 class="fw-bold mb-0"><i class="bx bx-bar-chart-alt text-primary me-1"></i>Tableau de bord analytique</h5>
    <button class="btn btn-sm btn-outline-primary ms-2" id="btnChargerAnalytics">
      <i class="bx bx-refresh me-1"></i>Charger les analyses
    </button>
    <span id="analyticsSpinner" class="d-none">
      <span class="spinner-border spinner-border-sm text-primary"></span>
    </span>
  </div>

  <div id="sectionAnalytics" style="display:none;">

    {{-- ── Ligne alertes ─────────────────────────────────────────────────── --}}
    <div class="row g-3 mb-3">
      <div class="col-6 col-md-3">
        <div class="card stat-card h-100 border-start border-4 border-danger">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-label-danger"><i class="bx bx-error text-danger fs-4"></i></div>
            <div>
              <div class="kpi-label">Impayés ce mois</div>
              <div class="kpi-value text-danger" id="kpi_nb_impayes">–</div>
              <div class="kpi-sub">locataires sans paiement</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card stat-card h-100 border-start border-4 border-warning">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-label-warning"><i class="bx bx-time-five text-warning fs-4"></i></div>
            <div>
              <div class="kpi-label">À renouveler</div>
              <div class="kpi-value text-warning" id="kpi_nb_renouveler">–</div>
              <div class="kpi-sub">contrats ≥ 11 mois</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card stat-card h-100 border-start border-4 border-success">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-label-success"><i class="bx bx-trending-up text-success fs-4"></i></div>
            <div>
              <div class="kpi-label">Revenus <span id="kpi_annee_n" class="text-body">–</span> vs <span id="kpi_annee_n1" class="text-body">–</span></div>
              <div class="kpi-value" id="kpi_delta_rev">–</div>
              <div class="kpi-sub" id="kpi_delta_rev_sub">évolution annuelle</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card stat-card h-100 border-start border-4 border-info">
          <div class="card-body d-flex align-items-center gap-3">
            <div class="stat-icon bg-label-info"><i class="bx bx-wallet text-info fs-4"></i></div>
            <div>
              <div class="kpi-label">Taux de recouvrement</div>
              <div class="kpi-value" id="kpi_taux_recouvrement">–</div>
              <div class="kpi-sub" id="kpi_recouvrement_sub">loyers encaissés / attendus</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- ── Graphiques ligne 1 ────────────────────────────────────────────── --}}
    <div class="row g-3 mb-3">
      <div class="col-12 col-lg-8">
        <div class="card chart-card h-100">
          <div class="card-header d-flex align-items-center justify-content-between pb-0">
            <h6 class="mb-0"><i class="bx bx-line-chart me-1 text-primary"></i>Évolution des revenus N vs N-1</h6>
            <div class="d-flex gap-2 small">
              <span><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#696cff"></span><span id="leg_annee_n">N</span></span>
              <span><span class="d-inline-block rounded-circle me-1" style="width:10px;height:10px;background:#d3d3d3"></span><span id="leg_annee_n1">N-1</span></span>
            </div>
          </div>
          <div class="card-body">
            <canvas id="chartNvsN1" style="max-height:260px"></canvas>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-4">
        <div class="card chart-card h-100">
          <div class="card-header pb-0">
            <h6 class="mb-0"><i class="bx bx-pie-chart-alt-2 me-1 text-info"></i>Taux de recouvrement</h6>
          </div>
          <div class="card-body d-flex flex-column align-items-center justify-content-center">
            <canvas id="chartRecouvrement" style="max-height:200px;max-width:200px"></canvas>
            <div class="mt-2 text-center small text-muted" id="recouvrementDetail">–</div>
          </div>
        </div>
      </div>
    </div>

    {{-- ── Graphiques ligne 2 ────────────────────────────────────────────── --}}
    <div class="row g-3 mb-3">
      <div class="col-12 col-lg-6">
        <div class="card chart-card h-100">
          <div class="card-header pb-0">
            <h6 class="mb-0"><i class="bx bx-building-house me-1 text-success"></i>Occupation par bâtiment</h6>
          </div>
          <div class="card-body">
            <canvas id="chartOccupationMaison" style="max-height:260px"></canvas>
          </div>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="card chart-card h-100">
          <div class="card-header pb-0">
            <h6 class="mb-0"><i class="bx bx-trophy me-1 text-warning"></i>Top propriétaires — 12 mois</h6>
          </div>
          <div class="card-body">
            <canvas id="chartTopProprios" style="max-height:260px"></canvas>
          </div>
        </div>
      </div>
    </div>

    {{-- ── Tableau impayés ──────────────────────────────────────────────── --}}
    <div class="card chart-card mb-4">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <h6 class="mb-0"><i class="bx bx-error-circle me-1 text-danger"></i>Impayés en cours — <span id="mois_courant_label"></span></h6>
        <span class="badge bg-danger" id="badge_nb_impayes">0</span>
      </div>
      <div class="table-responsive">
        <table class="table table-sm table-hover mb-0">
          <thead class="table-light">
            <tr>
              <th>Locataire</th>
              <th>Téléphone</th>
              <th>Logement</th>
              <th class="text-end">Loyer dû</th>
              <th class="text-center">Ancienneté</th>
            </tr>
          </thead>
          <tbody id="tbodyImpayes">
            <tr><td colspan="5" class="text-center text-muted py-3">–</td></tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>{{-- /sectionAnalytics --}}

  @endcan

</div>

<script>
// ── Tableau de bord analytique ────────────────────────────────────────────────
(function() {
  var analyticsLoaded = false;
  var chartNvsN1 = null, chartRecouvrement = null, chartOccupation = null, chartTopProprios = null;

  document.getElementById('btnChargerAnalytics').addEventListener('click', function() {
    if (analyticsLoaded) {
      document.getElementById('sectionAnalytics').style.display = 'block';
      this.innerHTML = '<i class="bx bx-refresh me-1"></i>Actualiser';
      chargerAnalytics();
      return;
    }
    chargerAnalytics();
  });

  function chargerAnalytics() {
    var btn     = document.getElementById('btnChargerAnalytics');
    var spinner = document.getElementById('analyticsSpinner');
    btn.disabled = true;
    spinner.classList.remove('d-none');

    fetch('{{ route("analytics.data") }}')
      .then(r => r.json())
      .then(d => {
        if (d.error) { alert('{{ __("common.swal_error") }}: ' + d.error); return; }
        rendreAnalytics(d);
        document.getElementById('sectionAnalytics').style.display = 'block';
        analyticsLoaded = true;
      })
      .catch(e => alert('{{ __("common.swal_generic_error") }}'))
      .finally(() => { btn.disabled = false; spinner.classList.add('d-none'); btn.innerHTML = '<i class="bx bx-refresh me-1"></i>{{ __("common.btn_refresh") }}'; });
  }

  function fmt(n) { return parseInt(n).toLocaleString('fr-FR'); }

  function rendreAnalytics(d) {
    // ── KPIs ──────────────────────────────────────────────────────────────────
    document.getElementById('kpi_nb_impayes').textContent     = d.nb_impayes;
    document.getElementById('kpi_nb_renouveler').textContent  = d.nb_a_renouveler;
    document.getElementById('kpi_annee_n').textContent        = d.annee_n;
    document.getElementById('kpi_annee_n1').textContent       = d.annee_n1;
    document.getElementById('leg_annee_n').textContent        = d.annee_n;
    document.getElementById('leg_annee_n1').textContent       = d.annee_n1;
    document.getElementById('badge_nb_impayes').textContent   = d.nb_impayes;

    var now = new Date();
    document.getElementById('mois_courant_label').textContent =
      now.toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' });

    // Delta revenus
    var deltaEl = document.getElementById('kpi_delta_rev');
    if (d.delta_rev !== null) {
      var sign = d.delta_rev >= 0 ? '+' : '';
      deltaEl.textContent = sign + d.delta_rev + '%';
      deltaEl.className   = 'kpi-value ' + (d.delta_rev >= 0 ? 'text-success' : 'text-danger');
    } else { deltaEl.textContent = '–'; }
    document.getElementById('kpi_delta_rev_sub').textContent =
      fmt(d.total_n) + ' vs ' + fmt(d.total_n1) + ' {{ get_symbole_devise() }}';

    // Taux recouvrement
    document.getElementById('kpi_taux_recouvrement').textContent = d.taux_recouvrement + '%';
    document.getElementById('kpi_recouvrement_sub').textContent  =
      fmt(d.loyers_encaisses) + ' / ' + fmt(d.loyers_attendus) + ' {{ get_symbole_devise() }}';
    document.getElementById('recouvrementDetail').textContent  =
      fmt(d.loyers_encaisses) + ' encaissés sur ' + fmt(d.loyers_attendus) + ' {{ get_symbole_devise() }} attendus';

    // ── Graphique N vs N-1 ────────────────────────────────────────────────────
    if (chartNvsN1) chartNvsN1.destroy();
    chartNvsN1 = new Chart(document.getElementById('chartNvsN1'), {
      type: 'line',
      data: {
        labels: d.mois_courts,
        datasets: [
          {
            label: d.annee_n,
            data: d.revenus_n,
            borderColor: '#696cff',
            backgroundColor: 'rgba(105,108,255,0.1)',
            tension: 0.4, fill: true, pointRadius: 4,
          },
          {
            label: d.annee_n1,
            data: d.revenus_n1,
            borderColor: '#d3d3d3',
            backgroundColor: 'rgba(0,0,0,0.03)',
            tension: 0.4, fill: false, borderDash: [5,4], pointRadius: 3,
          }
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { callbacks: { label: ctx => ' ' + fmt(ctx.parsed.y) + ' {{ get_symbole_devise() }}' } }
        },
        scales: {
          y: { beginAtZero: true, ticks: { callback: v => fmt(v) } }
        }
      }
    });

    // ── Graphique recouvrement (donut) ────────────────────────────────────────
    var nonRecouvre = Math.max(0, 100 - d.taux_recouvrement);
    if (chartRecouvrement) chartRecouvrement.destroy();
    chartRecouvrement = new Chart(document.getElementById('chartRecouvrement'), {
      type: 'doughnut',
      data: {
        labels: ['Encaissé', 'Non encaissé'],
        datasets: [{ data: [d.taux_recouvrement, nonRecouvre],
          backgroundColor: ['#71dd37', '#e7e9ef'], borderWidth: 0, cutout: '72%' }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { callbacks: { label: ctx => ctx.parsed.toFixed(1) + '%' } }
        }
      },
      plugins: [{
        id: 'centreText',
        afterDraw(chart) {
          var { ctx, chartArea: { width, height, left, top } } = chart;
          ctx.save();
          ctx.font = 'bold 22px sans-serif';
          ctx.fillStyle = d.taux_recouvrement >= 80 ? '#71dd37' : (d.taux_recouvrement >= 50 ? '#ffab00' : '#ff3e1d');
          ctx.textAlign = 'center'; ctx.textBaseline = 'middle';
          ctx.fillText(d.taux_recouvrement + '%', left + width / 2, top + height / 2);
          ctx.restore();
        }
      }]
    });

    // ── Graphique occupation par bâtiment (barres horizontales) ──────────────
    if (chartOccupation) chartOccupation.destroy();
    chartOccupation = new Chart(document.getElementById('chartOccupationMaison'), {
      type: 'bar',
      data: {
        labels: d.occupation_par_maison.map(m => m.nom),
        datasets: [
          { label: 'Occupées', data: d.occupation_par_maison.map(m => m.occupees),
            backgroundColor: '#696cff', borderRadius: 4 },
          { label: 'Libres',   data: d.occupation_par_maison.map(m => m.total - m.occupees),
            backgroundColor: '#e7e9ef', borderRadius: 4 }
        ]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
          legend: { position: 'bottom', labels: { boxWidth: 12 } },
          tooltip: { callbacks: {
            afterBody: (items) => {
              var idx = items[0].dataIndex;
              return 'Taux : ' + d.occupation_par_maison[idx].taux + '%';
            }
          }}
        },
        scales: { x: { stacked: true, beginAtZero: true }, y: { stacked: true } }
      }
    });

    // ── Graphique top propriétaires (barres horizontales) ────────────────────
    if (chartTopProprios) chartTopProprios.destroy();
    chartTopProprios = new Chart(document.getElementById('chartTopProprios'), {
      type: 'bar',
      data: {
        labels: d.top_proprios.map(p => p.nom),
        datasets: [{
          label: 'Revenus {{ get_symbole_devise() }}',
          data: d.top_proprios.map(p => p.total),
          backgroundColor: ['#696cff','#71dd37','#ffab00','#03c3ec','#9155fd','#ff3e1d'],
          borderRadius: 4,
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { callbacks: { label: ctx => ' ' + fmt(ctx.parsed.x) + ' {{ get_symbole_devise() }}' } }
        },
        scales: { x: { beginAtZero: true, ticks: { callback: v => fmt(v) } } }
      }
    });

    // ── Tableau impayés ───────────────────────────────────────────────────────
    var tbody = document.getElementById('tbodyImpayes');
    if (!d.impayes.length) {
      tbody.innerHTML = '<tr><td colspan="5" class="text-center text-success py-3"><i class="bx bx-check-circle me-1"></i>Aucun impayé ce mois. Bravo !</td></tr>';
    } else {
      tbody.innerHTML = d.impayes.map(function(l) {
        var badge = l.anciennete >= 12
          ? '<span class="badge bg-danger">' + l.anciennete + ' mois</span>'
          : l.anciennete >= 6
          ? '<span class="badge bg-warning text-dark">' + l.anciennete + ' mois</span>'
          : '<span class="badge bg-label-secondary">' + l.anciennete + ' mois</span>';
        return '<tr>'
          + '<td><strong>' + l.nom + '</strong></td>'
          + '<td>' + l.telephone + '</td>'
          + '<td><small>' + l.logement + '</small></td>'
          + '<td class="text-end fw-semibold">' + fmt(l.montant) + ' F</td>'
          + '<td class="text-center">' + badge + '</td>'
          + '</tr>';
      }).join('');
    }
  }
})();
</script>

<script>
// ─── Données initiales (JSON Blade) ───────────────────────────────────────────
var moisLabels        = @json($data['moisLabels'] ?? []);
var paiementsParMois  = @json($data['paiementsParMois'] ?? []);
var locatairesParMois = @json($data['locatairesParMois'] ?? []);
var nbOccupees        = {{ $data['chambresOccupees'] ?? 0 }};
var nbTotal           = {{ $data['nombreChambre'] ?? 0 }};

// ─── Chart : Paiements mensuels (barres) ─────────────────────────────────────
var ctxBar = document.getElementById('chartPaiements');
var chartPaiements = new Chart(ctxBar, {
  type: 'bar',
  data: {
    labels: moisLabels,
    datasets: [{
      label: '{{ __('dashboard.chart_amount') }}',
      data: paiementsParMois,
      backgroundColor: 'rgba(105,108,255,0.75)',
      borderRadius: 6,
      borderSkipped: false,
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: function(ctx) {
            return ' ' + ctx.parsed.y.toLocaleString('fr-FR') + ' {{ get_symbole_devise() }}';
          }
        }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: {
          callback: function(v) {
            if (v >= 1000000) return (v/1000000).toFixed(1) + 'M';
            if (v >= 1000)    return (v/1000).toFixed(0) + 'k';
            return v;
          }
        },
        grid: { color: 'rgba(0,0,0,.05)' }
      },
      x: { grid: { display: false } }
    }
  }
});

// ─── Chart : Occupation (donut) ───────────────────────────────────────────────
var ctxDonut = document.getElementById('chartOccupation');
var chartOccupation = new Chart(ctxDonut, {
  type: 'doughnut',
  data: {
    labels: ['{{ __('dashboard.occupied') }}', '{{ __('dashboard.free') }}'],
    datasets: [{
      data: [nbOccupees, Math.max(nbTotal - nbOccupees, 0)],
      backgroundColor: ['#696cff', '#e7e9ef'],
      borderWidth: 0,
      hoverOffset: 4,
    }]
  },
  options: {
    cutout: '72%',
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: { label: function(ctx) { return ' ' + ctx.parsed + ' {{ __('dashboard.rooms') }}'; } }
      }
    }
  }
});

// ─── Chart : Evolution locataires (courbe) ────────────────────────────────────
var ctxLine = document.getElementById('chartLocataires');
var chartLocataires = new Chart(ctxLine, {
  type: 'line',
  data: {
    labels: moisLabels,
    datasets: [{
      label: '{{ __('dashboard.new_entries') }}',
      data: locatairesParMois,
      borderColor: '#71dd37',
      backgroundColor: 'rgba(113,221,55,.12)',
      pointBackgroundColor: '#71dd37',
      pointRadius: 4,
      tension: 0.4,
      fill: true,
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: { label: function(ctx) { return ' ' + ctx.parsed.y + ' {{ __('dashboard.tenants_count') }}'; } }
      }
    },
    scales: {
      y: {
        beginAtZero: true,
        ticks: { stepSize: 1 },
        grid: { color: 'rgba(0,0,0,.05)' }
      },
      x: { grid: { display: false } }
    }
  }
});

// ─── Mise à jour des graphiques ───────────────────────────────────────────────
function updateCharts(data) {
  // Barres paiements
  chartPaiements.data.datasets[0].data = data.paiements_par_mois || [];
  chartPaiements.data.labels = data.mois_labels || moisLabels;
  chartPaiements.update();

  // Donut occupation
  var occ  = data.chambres_occupees || 0;
  var tot  = data.nombre_chambre    || 0;
  var lib  = Math.max(tot - occ, 0);
  chartOccupation.data.datasets[0].data = [occ, lib];
  chartOccupation.update();
  document.getElementById('leg_occ').textContent = occ;
  document.getElementById('leg_lib').textContent = lib;
  document.getElementById('taux_occupation').textContent = tot > 0 ? Math.round(occ/tot*100) + '%' : '0%';
  document.getElementById('taux_bar').style.width = (tot > 0 ? Math.round(occ/tot*100) : 0) + '%';
  document.getElementById('nb_occupees').textContent = occ;
  document.getElementById('nb_total_chambres').textContent = tot;

  // Courbe locataires
  chartLocataires.data.datasets[0].data = data.locataires_par_mois || [];
  chartLocataires.data.labels = data.mois_labels || moisLabels;
  chartLocataires.update();

  // KPI cartes
  document.getElementById('nombre_proprio').textContent   = data.nombre_proprio  || 0;
  document.getElementById('nombre_maison').textContent    = data.nombre_maison   || 0;
  document.getElementById('nombre_locataire').textContent = data.nombre_locataire || 0;
  document.getElementById('nombre_chambre').textContent   = data.nombre_chambre  || 0;

  if (data.total_revenus_mois !== undefined) {
    document.getElementById('total_revenus_mois').textContent =
      parseInt(data.total_revenus_mois).toLocaleString('fr-FR') + ' {{ get_symbole_devise() }}';
  }
}

// ─── Changement agence ─────────────────────────────────────────────────────────
$('#annexe_id').on('change', function() {
  var annexe_id = $(this).val();
  if (!annexe_id) return;

  $.ajax({
    url: '{{ url('listeLocataire') }}',
    data: { annexe_id: annexe_id },
    type: 'GET',
    dataType: 'json',
    success: function(data) {
      // Tables
      $('#list_locataire').html(data.getlist  || '<tr><td colspan="4" class="text-center text-muted">{{ __('dashboard.no_data') }}</td></tr>');
      $('#list_proprio').html(data.getlist2   || '<tr><td colspan="5" class="text-center text-muted">{{ __('dashboard.no_data') }}</td></tr>');

      // KPI simples
      document.getElementById('nombre_proprio').textContent   = data.nombre_proprio;
      document.getElementById('nombre_maison').textContent    = data.nombre_maison;
      document.getElementById('nombre_locataire').textContent = data.nombre_locataire;
      document.getElementById('nombre_chambre').textContent   = data.nombre_chambre;

      // Graphiques si les données existent
      if (data.stats) {
        updateCharts(data.stats);
      }
    },
    error: function() {
      console.error('{{ __('dashboard.load_error') }}');
    }
  });
});

// ─── Message bienvenue ─────────────────────────────────────────────────────────
$(document).ready(function() {
  if (!sessionStorage.getItem('welcome_shown')) {
    Swal.fire({
      title: '{{ __('dashboard.welcome_title') }}',
      text: '{{ Auth::user()->nom }} {{ Auth::user()->prenom }}',
      icon: 'success',
      confirmButtonText: '{{ __('dashboard.welcome_btn') }}',
      timer: 4000,
      showConfirmButton: true,
    });
    sessionStorage.setItem('welcome_shown', '1');
  }
});
</script>

@can('config-paiement')
<script>
// ===== Panneau Prestataire de paiement — Super Admin =====

function toggleFieldVisibility(fieldId) {
    var input = document.getElementById(fieldId);
    input.type = (input.type === 'password') ? 'text' : 'password';
}

function updatePaymentBadge(provider, sandbox) {
    var badge = document.getElementById('payment-status-badge');
    if (provider === 'kkiapay') {
        badge.className = 'badge bg-success fs-6 px-3 py-2';
        badge.textContent = 'KKiaPay — ' + (sandbox ? '{{ __('dashboard.sandbox') }}' : '{{ __('dashboard.production') }}');
    } else if (provider === 'fedapay') {
        badge.className = 'badge bg-warning text-dark fs-6 px-3 py-2';
        badge.textContent = 'FedaPay — ' + (sandbox ? '{{ __('dashboard.sandbox') }}' : '{{ __('dashboard.production') }}');
    } else {
        badge.className = 'badge bg-secondary fs-6 px-3 py-2';
        badge.textContent = '{{ __('dashboard.payment_disabled') }}';
    }
}

function showProviderSection(provider) {
    document.getElementById('section-kkiapay').classList.toggle('d-none', provider !== 'kkiapay');
    document.getElementById('section-fedapay').classList.toggle('d-none', provider !== 'fedapay');
}

$('input[name="active_payment_provider"]').on('change', function() {
    var provider = this.value;
    showProviderSection(provider);
    var sandbox = provider === 'kkiapay'
        ? ($('#kkiapay_sandbox').val() === '1')
        : ($('#fedapay_sandbox').val() === '1');
    updatePaymentBadge(provider, sandbox);
});

$('#payment-config-form').on('submit', function(e) {
    e.preventDefault();

    var btn = document.getElementById('btn-save-payment');
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> {{ __('dashboard.saving') }}';

    var payload = {
        active_payment_provider: $('input[name="active_payment_provider"]:checked').val(),
        kkiapay_public_key:  $('#kkiapay_public_key').val(),
        kkiapay_private_key: $('#kkiapay_private_key').val(),
        kkiapay_secret_key:  $('#kkiapay_secret_key').val(),
        kkiapay_sandbox:     $('#kkiapay_sandbox').val() === '1' ? 1 : 0,
        fedapay_public_key: $('#fedapay_public_key').val(),
        fedapay_secret_key: $('#fedapay_secret_key').val(),
        fedapay_sandbox:    $('#fedapay_sandbox').val() === '1' ? 1 : 0,
    };

    $.ajax({
        url:         '{{ route('platform.kkiapay.update') }}',
        type:        'POST',
        contentType: 'application/json',
        data:        JSON.stringify(payload),
        headers:     { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json' },
        success: function(data) {
            if (data.status) {
                var chosenProvider = payload.active_payment_provider;
                $('input[name="active_payment_provider"]').prop('checked', false);
                $('input[name="active_payment_provider"][value="' + chosenProvider + '"]').prop('checked', true);
                showProviderSection(chosenProvider);
                var isSandbox = chosenProvider === 'kkiapay'
                    ? (payload.kkiapay_sandbox === 1)
                    : (payload.fedapay_sandbox === 1);
                updatePaymentBadge(chosenProvider, isSandbox);
                $('#kkiapay_private_key, #kkiapay_secret_key, #fedapay_secret_key').val('');
                Swal.fire({ title: '{{ __('dashboard.swal_success') }}', text: data.message, icon: 'success', timer: 2500, showConfirmButton: false });
                refreshKeyFields();
            } else {
                Swal.fire('{{ __('dashboard.swal_error') }}', data.message || '{{ __('dashboard.swal_generic_error') }}', 'error');
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '{{ __('dashboard.swal_generic_error') }}';
            Swal.fire('{{ __('dashboard.swal_error') }}', msg, 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-save me-1"></i> {{ __('dashboard.save_config') }}';
        }
    });
});

$(document).ready(function() {
    // État initial rendu par PHP/Blade — rien à charger via AJAX.
});

function refreshKeyFields() {
    $.ajax({
        url: '{{ route('platform.kkiapay.config') }}',
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(data) {
            if (!data.status) return;
            $('#kkiapay_public_key').val(data.kkiapay_public_key || '');
            $('#kkiapay_sandbox').val(data.kkiapay_sandbox ? '1' : '0');
            $('#fedapay_public_key').val(data.fedapay_public_key || '');
            $('#fedapay_sandbox').val(data.fedapay_sandbox ? '1' : '0');
            if (data.has_kkiapay_private_key) $('#kkiapay_private_key').attr('placeholder', '{{ __('dashboard.key_defined') }}');
            if (data.has_kkiapay_secret_key)  $('#kkiapay_secret_key').attr('placeholder', '{{ __('dashboard.key_defined') }}');
            if (data.has_fedapay_secret_key)  $('#fedapay_secret_key').attr('placeholder', '{{ __('dashboard.key_defined') }}');
        }
    });
}
</script>
@endcan

@endsection
