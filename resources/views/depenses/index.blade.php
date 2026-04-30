@extends('layouts.template')

@section('title')
<title>{{ __('pages.dep_title') }}</title>
@endsection

@section('content')
@include('notification.display_message')

<style>
.dep-tab-btn { border:none; background:none; padding:10px 20px; font-weight:600; color:#6c757d; border-bottom:3px solid transparent; transition:.2s; cursor:pointer; white-space:nowrap; }
.dep-tab-btn.active { color:#696cff; border-bottom-color:#696cff; }
.dep-tab-pane { display:none; }
.dep-tab-pane.active { display:block; }
.kpi-card { background:var(--kpi-card-bg,#fff); border-radius:12px; padding:20px 24px; box-shadow:0 2px 10px rgba(0,0,0,.07); }
.kpi-value { font-size:1.6rem; font-weight:800; }
.kpi-recette { color:#28a745; }
.kpi-depense { color:#dc3545; }
.kpi-resultat-pos { color:#696cff; }
.kpi-resultat-neg { color:#fd7e14; }
.justif-thumb { width:40px; height:40px; object-fit:cover; border-radius:6px; cursor:pointer; }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-2">
    <span class="text-muted fw-light">{{ __('common.home_breadcrumb') }}</span> {{ __('pages.dep_breadcrumb') }}
  </h4>

  <!-- Onglets -->
  <div class="d-flex border-bottom mb-4 overflow-auto">
    <button class="dep-tab-btn active" onclick="depTab(this,'tab-depenses')"><i class="bx bx-receipt me-1"></i>{{ __('pages.dep_tab_expenses') }}</button>
    <button class="dep-tab-btn" onclick="depTab(this,'tab-bilan')"><i class="bx bx-bar-chart-alt-2 me-1"></i>{{ __('pages.dep_tab_report') }}</button>
    <button class="dep-tab-btn" onclick="depTab(this,'tab-categories')"><i class="bx bx-category me-1"></i>{{ __('pages.dep_tab_categories') }}</button>
  </div>

  <!-- ══════════ ONGLET DÉPENSES ══════════ -->
  <div id="tab-depenses" class="dep-tab-pane active">

    <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
      @can('ajoute-depense')
      <button class="btn btn-primary" onclick="openDepModal()">
        <i class="bx bx-plus me-1"></i>{{ __('pages.dep_btn_new') }}
      </button>
      @endcan
      <div class="d-flex gap-2 flex-wrap">
        <select id="filterCat" class="form-select form-select-sm" style="width:auto" onchange="filterDep()">
          <option value="">{{ __('pages.dep_filter_all_cats') }}</option>
          @foreach($categories as $cat)
          <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
          @endforeach
        </select>
        <input type="text" id="filterQ" class="form-control form-control-sm" placeholder="Rechercher..." style="width:180px" oninput="filterDep()">
        @can('gestion-depenses')
        <a href="{{ route('depenses.export_csv') }}" class="btn btn-sm btn-outline-success" id="btnExportCsv">
          <i class="bx bx-download me-1"></i>CSV
        </a>
        @endcan
      </div>
    </div>

    <!-- Tableau -->
    <div class="card">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="depTable">
          <thead class="table-light">
            <tr>
              <th>{{ __('pages.dep_th_date') }}</th>
              <th>{{ __('pages.dep_th_category') }}</th>
              <th>{{ __('pages.dep_th_amount') }}</th>
              <th>{{ __('pages.dep_th_imputation') }}</th>
              <th>{{ __('pages.dep_th_description') }}</th>
              <th>{{ __('pages.dep_th_receipt') }}</th>
              <th>{{ __('common.th_actions') }}</th>
            </tr>
          </thead>
          <tbody id="depTbody">
            @forelse($depenses as $dep)
            <tr id="dep-row-{{ $dep->id }}"
                data-cat="{{ $dep->categorie_id }}"
                data-q="{{ strtolower($dep->description.' '.($dep->categorie->nom??'')) }}">
              <td>{{ $dep->date_depense->format('d/m/Y') }}</td>
              <td>
                <span class="badge bg-label-primary">{{ $dep->categorie->nom ?? __('pages.dep_no_category') }}</span>
              </td>
              <td class="fw-bold">{{ format_price($dep->montant) }}</td>
              <td>
                <span class="badge bg-label-secondary">{{ $dep->imputation_label }}</span>
                @if($dep->type_imputation === 'proprietaire' && $dep->proprietaire)
                  <small class="d-block text-muted">{{ $dep->proprietaire->nom }} {{ $dep->proprietaire->prenom }}</small>
                @elseif($dep->type_imputation === 'maison' && $dep->maison)
                  <small class="d-block text-muted">{{ $dep->maison->nom_maison }}</small>
                @elseif($dep->type_imputation === 'chambre' && $dep->chambre)
                  <small class="d-block text-muted">{{ __('pages.dep_room_prefix') }} {{ $dep->chambre->numero_chambre }}</small>
                @endif
              </td>
              <td class="text-muted" style="max-width:200px">{{ Str::limit($dep->description, 60) }}</td>
              <td>
                @if($dep->getRawOriginal('justificatif_url'))
                  @php $ext = pathinfo($dep->getRawOriginal('justificatif_url'), PATHINFO_EXTENSION); @endphp
                  @if(in_array(strtolower($ext), ['jpg','jpeg','png']))
                    <img src="{{ asset('storage/'.$dep->getRawOriginal('justificatif_url')) }}"
                         class="justif-thumb"
                         onclick="window.open(this.src,'_blank')"
                         title="Voir la pièce justificative">
                  @else
                    <a href="{{ asset('storage/'.$dep->getRawOriginal('justificatif_url')) }}" target="_blank"
                       class="btn btn-sm btn-outline-secondary">
                      <i class="bx bx-file me-1"></i>PDF
                    </a>
                  @endif
                @else
                  <span class="text-muted small">—</span>
                @endif
              </td>
              <td>
                @can('modify-depense')
                <button class="btn btn-sm btn-primary rounded-pill" title="Modifier"
                        onclick="editDep({{ $dep->id }})">
                  <i class="bx bx-edit-alt"></i>
                </button>
                @endcan
                @can('delete-depense')
                <button class="btn btn-sm btn-danger rounded-pill ms-1" title="Supprimer"
                        onclick="deleteDep({{ $dep->id }}, '{{ addslashes(format_price($dep->montant)) }}')">
                  <i class="bx bx-trash"></i>
                </button>
                @endcan
              </td>
            </tr>
            @empty
            <tr id="dep-empty-row">
              <td colspan="7" class="text-center text-muted py-4">{{ __('pages.dep_empty') }}</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
    <div id="depNoResult" class="text-center text-muted py-3 d-none">{{ __('pages.dep_no_result') }}</div>
  </div>

  <!-- ══════════ ONGLET BILAN ══════════ -->
  <div id="tab-bilan" class="dep-tab-pane">

    <!-- Filtres bilan -->
    <div class="card mb-4 p-3">
      <div class="row g-3 align-items-end">
        <div class="col-sm-3">
          <label class="form-label fw-semibold small mb-1">{{ __('pages.dep_year_label') }}</label>
          <select id="bilanAnnee" class="form-select form-select-sm">
            @for($y = date('Y'); $y >= date('Y')-5; $y--)
            <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
          </select>
        </div>
        <div class="col-sm-3">
          <label class="form-label fw-semibold small mb-1">{{ __('pages.dep_month_start') }}</label>
          <select id="bilanMoisDeb" class="form-select form-select-sm">
            @foreach(['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'] as $i=>$m)
            <option value="{{ $i+1 }}">{{ $m }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-sm-3">
          <label class="form-label fw-semibold small mb-1">{{ __('pages.dep_month_end') }}</label>
          <select id="bilanMoisFin" class="form-select form-select-sm">
            @foreach(['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'] as $i=>$m)
            <option value="{{ $i+1 }}" {{ $i==11?'selected':'' }}>{{ $m }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-sm-3">
          <button class="btn btn-primary btn-sm w-100" onclick="loadBilan()">
            <i class="bx bx-refresh me-1"></i>{{ __('pages.dep_refresh_btn') }}
          </button>
        </div>
      </div>
    </div>

    <!-- KPIs -->
    <div class="row g-3 mb-4" id="bilanKpis">
      <div class="col-sm-4">
        <div class="kpi-card text-center">
          <div class="text-muted small mb-1">{{ __('pages.dep_kpi_income') }}</div>
          <div class="kpi-value kpi-recette" id="kpiRecettes">—</div>
          <div class="text-muted small">{{ get_symbole_devise() }}</div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="kpi-card text-center">
          <div class="text-muted small mb-1">{{ __('pages.dep_kpi_expenses') }}</div>
          <div class="kpi-value kpi-depense" id="kpiDepenses">—</div>
          <div class="text-muted small">{{ get_symbole_devise() }}</div>
        </div>
      </div>
      <div class="col-sm-4">
        <div class="kpi-card text-center">
          <div class="text-muted small mb-1">{{ __('pages.dep_kpi_net') }}</div>
          <div class="kpi-value" id="kpiResultat">—</div>
          <div class="text-muted small">{{ get_symbole_devise() }}</div>
        </div>
      </div>
    </div>

    <!-- Graphique mensuel -->
    <div class="card mb-4 p-4">
      <h6 class="fw-bold mb-3">{{ __('pages.dep_chart_monthly') }}</h6>
      <canvas id="bilanChart" height="80"></canvas>
    </div>

    <!-- Détail par catégorie + par propriétaire -->
    <div class="row g-4">
      <div class="col-md-6">
        <div class="card p-4 h-100">
          <h6 class="fw-bold mb-3">{{ __('pages.dep_by_category') }}</h6>
          <div id="bilanParCat">
            <p class="text-muted text-center py-3">{{ __('pages.dep_click_refresh') }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card p-4 h-100">
          <h6 class="fw-bold mb-3">{{ __('pages.dep_by_owner') }}</h6>
          <div id="bilanParProprio">
            <p class="text-muted text-center py-3">{{ __('pages.dep_click_refresh') }}</p>
          </div>
        </div>
      </div>
    </div>

  </div>

  <!-- ══════════ ONGLET CATÉGORIES ══════════ -->
  <div id="tab-categories" class="dep-tab-pane">

    @can('modify-depense')
    <button class="btn btn-primary mb-3" onclick="openCatModal()">
      <i class="bx bx-plus me-1"></i>{{ __('pages.dep_cat_btn_new') }}
    </button>
    @endcan

    <div class="card">
      <div class="table-responsive">
        <table class="table mb-0" id="catTable">
          <thead class="table-light">
            <tr>
              <th>{{ __('pages.dep_cat_th_name') }}</th>
              <th>{{ __('pages.dep_cat_th_type') }}</th>
              <th>{{ __('pages.dep_cat_th_count') }}</th>
              <th>{{ __('common.th_actions') }}</th>
            </tr>
          </thead>
          <tbody id="catTbody">
            @forelse($categories as $cat)
            <tr id="cat-row-{{ $cat->id }}">
              <td class="fw-semibold">{{ $cat->nom }}</td>
              <td><span class="badge bg-label-info">{{ $cat->type_label }}</span></td>
              <td>{{ $cat->depenses()->count() }}</td>
              <td>
                @can('modify-depense')
                <button class="btn btn-sm btn-primary rounded-pill" onclick="editCat({{ $cat->id }}, '{{ addslashes($cat->nom) }}', '{{ $cat->type }}')">
                  <i class="bx bx-edit-alt"></i>
                </button>
                @endcan
                @can('delete-depense')
                <button class="btn btn-sm btn-danger rounded-pill ms-1" onclick="deleteCat({{ $cat->id }}, '{{ addslashes($cat->nom) }}')">
                  <i class="bx bx-trash"></i>
                </button>
                @endcan
              </td>
            </tr>
            @empty
            <tr id="cat-empty-row">
              <td colspan="4" class="text-center text-muted py-4">{{ __('pages.dep_cat_empty') }}</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- ══════ MODAL DÉPENSE ══════ -->
<div class="modal fade" id="depModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="depModalTitle">{{ __('pages.dep_modal_new') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="depForm" enctype="multipart/form-data" class="row g-3">
          @csrf
          <input type="hidden" id="depId" name="id">

          <div class="col-md-4">
            <label class="form-label fw-semibold">{{ __('pages.dep_lbl_category') }} <span class="text-danger">*</span></label>
            <select name="categorie_id" id="depCat" class="form-select" required>
              <option value="">{{ __('pages.dep_choose_option') }}</option>
              @foreach($categories as $cat)
              <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">{{ __('pages.dep_lbl_amount') }} ({{ get_symbole_devise() }}) <span class="text-danger">*</span></label>
            <input type="number" name="montant" id="depMontant" class="form-control" min="0" step="1" required>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">{{ __('pages.dep_lbl_date') }} <span class="text-danger">*</span></label>
            <input type="date" name="date_depense" id="depDate" class="form-control" required>
          </div>

          <div class="col-md-4">
            <label class="form-label fw-semibold">{{ __('pages.dep_lbl_imputation') }} <span class="text-danger">*</span></label>
            <select name="type_imputation" id="depImputation" class="form-select" onchange="toggleImputation(this.value)" required>
              <option value="agence">{{ __('pages.dep_imp_agency') }}</option>
              <option value="proprietaire">{{ __('pages.dep_imp_owner') }}</option>
              <option value="maison">{{ __('pages.dep_imp_house') }}</option>
              <option value="chambre">{{ __('pages.dep_imp_room') }}</option>
            </select>
          </div>
          <div class="col-md-4 imp-field" id="fieldProprietaire" style="display:none">
            <label class="form-label fw-semibold">{{ __('pages.dep_lbl_owner') }}</label>
            <select name="proprietaire_id" id="depProprio" class="form-select">
              <option value="">{{ __('pages.dep_choose_option') }}</option>
              @foreach($proprietaires as $p)
              <option value="{{ $p->id }}">{{ $p->nom }} {{ $p->prenom }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4 imp-field" id="fieldMaison" style="display:none">
            <label class="form-label fw-semibold">{{ __('pages.dep_lbl_house') }}</label>
            <select name="maison_id" id="depMaison" class="form-select">
              <option value="">{{ __('pages.dep_choose_option') }}</option>
              @foreach($maisons as $m)
              <option value="{{ $m->id }}">{{ $m->nom_maison }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4 imp-field" id="fieldChambre" style="display:none">
            <label class="form-label fw-semibold">{{ __('pages.dep_lbl_room') }}</label>
            <select name="chambre_id" id="depChambre" class="form-select">
              <option value="">{{ __('pages.dep_choose_option') }}</option>
              @foreach($chambres as $c)
              <option value="{{ $c->id }}">{{ __('pages.dep_room_prefix') }} {{ $c->numero_chambre }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">{{ __('pages.dep_lbl_description') }}</label>
            <textarea name="description" id="depDesc" class="form-control" rows="2" maxlength="500"></textarea>
          </div>

          <div class="col-12">
            <label class="form-label fw-semibold">{{ __('pages.dep_lbl_receipt') }} <small class="text-muted">{{ __('pages.dep_lbl_receipt_hint') }}</small></label>
            <input type="file" name="justificatif" id="depJustif" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
            <div id="depJustifPreview" class="mt-2"></div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.btn_cancel') }}</button>
        <button type="button" class="btn btn-primary" id="btnSaveDep" onclick="saveDep()">
          <i class="bx bx-save me-1"></i>{{ __('common.btn_save') }}
        </button>
      </div>
    </div>
  </div>
</div>

<!-- ══════ MODAL CATÉGORIE ══════ -->
<div class="modal fade" id="catModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-sm modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="catModalTitle">{{ __('pages.dep_cat_modal_new') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="catForm" class="row g-3">
          @csrf
          <input type="hidden" id="catId" name="id">
          <div class="col-12">
            <label class="form-label fw-semibold">{{ __('pages.dep_lbl_cat_name') }} <span class="text-danger">*</span></label>
            <input type="text" name="nom" id="catNom" class="form-control" maxlength="100" required>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold">{{ __('pages.dep_lbl_cat_type') }} <span class="text-danger">*</span></label>
            <select name="type" id="catType" class="form-select" required>
              @foreach(\App\CategorieDepense::$types as $v => $l)
              <option value="{{ $v }}">{{ $l }}</option>
              @endforeach
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">{{ __('common.btn_cancel') }}</button>
        <button type="button" class="btn btn-primary btn-sm" onclick="saveCat()">{{ __('common.btn_save') }}</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
// ─────────────────────────────────────────────────────────────────
// ONGLETS
// ─────────────────────────────────────────────────────────────────
function depTab(btn, id) {
    document.querySelectorAll('.dep-tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.dep-tab-pane').forEach(p => p.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById(id).classList.add('active');
    if (id === 'tab-bilan') loadBilan();
}

// ─────────────────────────────────────────────────────────────────
// FILTRAGE TABLEAU DÉPENSES (client-side)
// ─────────────────────────────────────────────────────────────────
function filterDep() {
    var cat = document.getElementById('filterCat').value;
    var q   = document.getElementById('filterQ').value.toLowerCase().trim();
    var rows = document.querySelectorAll('#depTbody tr.dep-row');
    var visible = 0;
    rows.forEach(function(r) {
        var matchCat = !cat || r.dataset.cat === cat;
        var matchQ   = !q   || (r.dataset.q || '').indexOf(q) !== -1;
        r.style.display = (matchCat && matchQ) ? '' : 'none';
        if (matchCat && matchQ) visible++;
    });
    document.getElementById('depNoResult').classList.toggle('d-none', visible > 0);
}

// ─────────────────────────────────────────────────────────────────
// MODAL DÉPENSE
// ─────────────────────────────────────────────────────────────────
var depData = @json($depenses->keyBy('id')->map(function($d) {
    return [
        'id'              => $d->id,
        'categorie_id'    => $d->categorie_id,
        'montant'         => $d->montant,
        'date_raw'        => $d->date_depense->format('Y-m-d'),
        'description'     => $d->description ?? '',
        'type_imputation' => $d->type_imputation,
        'proprietaire_id' => $d->proprietaire_id,
        'maison_id'       => $d->maison_id,
        'chambre_id'      => $d->chambre_id,
        'justificatif_raw'=> $d->getRawOriginal('justificatif_url'),
    ];
}));

function openDepModal() {
    document.getElementById('depId').value = '';
    document.getElementById('depForm').reset();
    document.getElementById('depDate').value = '{{ now()->format('Y-m-d') }}';
    document.getElementById('depJustifPreview').innerHTML = '';
    document.getElementById('depModalTitle').textContent = '{{ __('pages.dep_modal_new') }}';
    toggleImputation('agence');
    new bootstrap.Modal(document.getElementById('depModal')).show();
}

function editDep(id) {
    var d = depData[id];
    if (!d) return;
    document.getElementById('depId').value           = d.id;
    document.getElementById('depCat').value          = d.categorie_id;
    document.getElementById('depMontant').value      = d.montant;
    document.getElementById('depDate').value         = d.date_raw;
    document.getElementById('depDesc').value         = d.description;
    document.getElementById('depImputation').value   = d.type_imputation;
    toggleImputation(d.type_imputation);
    document.getElementById('depProprio').value  = d.proprietaire_id || '';
    document.getElementById('depMaison').value   = d.maison_id || '';
    document.getElementById('depChambre').value  = d.chambre_id || '';
    document.getElementById('depModalTitle').textContent = 'Modifier la dépense';
    // Justificatif existant
    var prev = document.getElementById('depJustifPreview');
    prev.innerHTML = d.justificatif_raw
        ? '<a href="{{ asset('storage/') }}/' + d.justificatif_raw + '" target="_blank" class="small text-primary"><i class="bx bx-paperclip me-1"></i>Pièce jointe existante</a>'
        : '';
    new bootstrap.Modal(document.getElementById('depModal')).show();
}

function toggleImputation(val) {
    ['fieldProprietaire','fieldMaison','fieldChambre'].forEach(function(id) {
        document.getElementById(id).style.display = 'none';
    });
    if (val === 'proprietaire') document.getElementById('fieldProprietaire').style.display = '';
    if (val === 'maison')       document.getElementById('fieldMaison').style.display = '';
    if (val === 'chambre')      document.getElementById('fieldChambre').style.display = '';
}

async function saveDep() {
    var btn = document.getElementById('btnSaveDep');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>{{ __('pages.dep_js_saving') }}';

    var fd  = new FormData(document.getElementById('depForm'));
    var id  = document.getElementById('depId').value;
    var url = id ? '{{ route('depenses.update') }}' : '{{ route('depenses.store') }}';

    try {
        var r    = await fetch(url, { method:'POST', body:fd, headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'} });
        var data = await r.json();
        if (data.status) {
            bootstrap.Modal.getInstance(document.getElementById('depModal')).hide();
            Swal.fire({ icon:'success', title:'{{ __('common.swal_success') }}', text:data.message, timer:2000, showConfirmButton:false });
            // Mise à jour DOM
            if (id) {
                depData[data.data.id] = data.data;
                updateDepRow(data.data);
            } else {
                depData[data.data.id] = data.data;
                addDepRow(data.data);
            }
        } else {
            Swal.fire({ icon:'warning', title:'{{ __('common.swal_warning') }}', text:data.message });
        }
    } catch(e) {
        Swal.fire({ icon:'error', title:'{{ __('common.swal_error') }}', text:'{{ __('common.swal_unexpected_error') }}' });
    }
    btn.disabled = false;
    btn.innerHTML = '<i class="bx bx-save me-1"></i>{{ __('pages.dep_js_save_btn') }}';
}

function addDepRow(d) {
    var emptyRow = document.getElementById('dep-empty-row');
    if (emptyRow) emptyRow.remove();
    var tbody = document.getElementById('depTbody');
    var tr = document.createElement('tr');
    tr.id = 'dep-row-' + d.id;
    tr.className = 'dep-row';
    tr.dataset.cat = d.categorie_id;
    tr.dataset.q   = (d.description + ' ' + d.categorie_nom).toLowerCase();
    tr.innerHTML = buildDepRowHtml(d);
    tbody.prepend(tr);
    depData[d.id] = d;
}

function updateDepRow(d) {
    var tr = document.getElementById('dep-row-' + d.id);
    if (tr) {
        tr.dataset.cat = d.categorie_id;
        tr.dataset.q   = (d.description + ' ' + d.categorie_nom).toLowerCase();
        tr.innerHTML = buildDepRowHtml(d);
    }
}

function buildDepRowHtml(d) {
    var justif = d.justificatif_url
        ? '<a href="' + d.justificatif_url + '" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bx bx-file me-1"></i>Voir</a>'
        : '<span class="text-muted small">—</span>';
    return `
      <td>${d.date}</td>
      <td><span class="badge bg-label-primary">${d.categorie_nom}</span></td>
      <td class="fw-bold">${d.montant_fmt}</td>
      <td><span class="badge bg-label-secondary">${d.imputation_label}</span><small class="d-block text-muted">${d.imp_detail}</small></td>
      <td class="text-muted">${(d.description||'').substring(0,60)}</td>
      <td>${justif}</td>
      <td>
        <button class="btn btn-sm btn-primary rounded-pill" onclick="editDep(${d.id})"><i class="bx bx-edit-alt"></i></button>
        <button class="btn btn-sm btn-danger rounded-pill ms-1" onclick="deleteDep(${d.id}, '${d.montant_fmt}')"><i class="bx bx-trash"></i></button>
      </td>`;
}

async function deleteDep(id, label) {
    var res = await Swal.fire({
        title: '{{ __('pages.dep_js_delete_title') }}',
        text: label,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: '{{ __('common.btn_yes_delete') }}',
        cancelButtonText: '{{ __('common.btn_cancel') }}',
        confirmButtonColor: '#dc3545',
    });
    if (!res.isConfirmed) return;
    var fd = new FormData();
    fd.append('_token', '{{ csrf_token() }}');
    fd.append('id', id);
    var r    = await fetch('{{ route('depenses.destroy') }}', { method:'POST', body:fd, headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'} });
    var data = await r.json();
    if (data.status) {
        document.getElementById('dep-row-' + id)?.remove();
        delete depData[id];
        Swal.fire({ icon:'success', title:'{{ __('common.swal_deleted') }}', timer:1500, showConfirmButton:false });
    } else {
        Swal.fire({ icon:'error', title:'{{ __('common.swal_error') }}', text:data.message });
    }
}

// ─────────────────────────────────────────────────────────────────
// BILAN
// ─────────────────────────────────────────────────────────────────
var bilanChartInst = null;

async function loadBilan() {
    var annee   = document.getElementById('bilanAnnee').value;
    var moisDeb = document.getElementById('bilanMoisDeb').value;
    var moisFin = document.getElementById('bilanMoisFin').value;
    var url     = '{{ route('depenses.bilan') }}?annee=' + annee + '&mois_debut=' + moisDeb + '&mois_fin=' + moisFin;

    try {
        var r    = await fetch(url, { headers:{'Accept':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'} });
        var data = await r.json();

        // KPIs
        var fmt = n => parseFloat(n).toLocaleString('fr-FR', {maximumFractionDigits:0});
        document.getElementById('kpiRecettes').textContent = fmt(data.recettes);
        document.getElementById('kpiDepenses').textContent = fmt(data.depenses);
        var resEl = document.getElementById('kpiResultat');
        resEl.textContent = fmt(data.resultat);
        resEl.className = 'kpi-value ' + (data.resultat >= 0 ? 'kpi-resultat-pos' : 'kpi-resultat-neg');

        // Graphique mensuel
        var labels   = data.mensuel.map(m => m.mois);
        var recettes = data.mensuel.map(m => m.recettes);
        var depenses = data.mensuel.map(m => m.depenses);
        var resultats= data.mensuel.map(m => m.resultat);

        if (bilanChartInst) bilanChartInst.destroy();
        bilanChartInst = new Chart(document.getElementById('bilanChart'), {
            type: 'bar',
            data: {
                labels,
                datasets: [
                    { label:'{{ __('pages.dep_js_chart_income') }}', data:recettes, backgroundColor:'rgba(40,167,69,.7)', borderRadius:6 },
                    { label:'{{ __('pages.dep_js_chart_expenses') }}', data:depenses, backgroundColor:'rgba(220,53,69,.7)', borderRadius:6 },
                    { label:'{{ __('pages.dep_js_chart_result') }}', data:resultats, type:'line', borderColor:'#696cff', backgroundColor:'rgba(105,108,255,.1)', tension:.4, fill:true, pointRadius:4 },
                ]
            },
            options: {
                responsive:true,
                plugins:{ legend:{ position:'top' } },
                scales:{ y:{ beginAtZero:true, ticks:{ callback: v => v.toLocaleString('fr-FR') } } }
            }
        });

        // Par catégorie
        var catHtml = data.par_categorie.length
            ? data.par_categorie.map(c => `
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="fw-semibold">${c.nom}</span>
                    <span class="text-danger fw-bold">${c.montant_fmt}</span>
                </div>`).join('')
            : '<p class="text-muted text-center py-3 mb-0">{{ __('pages.dep_js_no_data') }}</p>';
        document.getElementById('bilanParCat').innerHTML = catHtml;

        // Par propriétaire
        var propHtml = data.par_proprietaire.length
            ? data.par_proprietaire.map(p => `
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <span class="fw-semibold">${p.nom}</span>
                    <span class="text-danger fw-bold">${p.montant_fmt}</span>
                </div>`).join('')
            : '<p class="text-muted text-center py-3 mb-0">{{ __('pages.dep_js_no_owner_exp') }}</p>';
        document.getElementById('bilanParProprio').innerHTML = propHtml;

    } catch(e) {
        Swal.fire({ icon:'error', title:'{{ __('common.swal_error') }}', text:'{{ __('pages.dep_js_bilan_error') }}' });
    }
}

// ─────────────────────────────────────────────────────────────────
// CATÉGORIES
// ─────────────────────────────────────────────────────────────────
function openCatModal() {
    document.getElementById('catId').value = '';
    document.getElementById('catNom').value = '';
    document.getElementById('catType').value = 'autre';
    document.getElementById('catModalTitle').textContent = '{{ __('pages.dep_cat_modal_new') }}';
    new bootstrap.Modal(document.getElementById('catModal')).show();
}

function editCat(id, nom, type) {
    document.getElementById('catId').value   = id;
    document.getElementById('catNom').value  = nom;
    document.getElementById('catType').value = type;
    document.getElementById('catModalTitle').textContent = '{{ __('pages.dep_cat_modal_edit') }}';
    new bootstrap.Modal(document.getElementById('catModal')).show();
}

async function saveCat() {
    var fd  = new FormData(document.getElementById('catForm'));
    var id  = document.getElementById('catId').value;
    var url = id ? '{{ route('depenses.categorie_update') }}' : '{{ route('depenses.categorie_store') }}';
    var r    = await fetch(url, { method:'POST', body:fd, headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'} });
    var data = await r.json();
    if (data.status) {
        bootstrap.Modal.getInstance(document.getElementById('catModal')).hide();
        Swal.fire({ icon:'success', title:'{{ __('common.swal_success') }}', text:data.message, timer:1800, showConfirmButton:false });
        if (id) {
            var tr = document.getElementById('cat-row-' + id);
            if (tr) tr.querySelector('td:first-child').textContent = data.data.nom;
        } else {
            // Recharger pour avoir le comptage à jour
            setTimeout(() => location.reload(), 1200);
        }
    } else {
        Swal.fire({ icon:'warning', title:'{{ __('common.swal_warning') }}', text:data.message });
    }
}

async function deleteCat(id, nom) {
    var res = await Swal.fire({
        title:'{{ __('pages.dep_js_cat_del_title') }}'.replace(':nom', nom),
        icon:'warning', showCancelButton:true,
        confirmButtonText:'{{ __('common.btn_delete') }}', cancelButtonText:'{{ __('common.btn_cancel') }}', confirmButtonColor:'#dc3545',
    });
    if (!res.isConfirmed) return;
    var fd = new FormData();
    fd.append('_token', '{{ csrf_token() }}');
    fd.append('id', id);
    var r    = await fetch('{{ route('depenses.categorie_destroy') }}', { method:'POST', body:fd, headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}','Accept':'application/json'} });
    var data = await r.json();
    if (data.status) {
        document.getElementById('cat-row-' + id)?.remove();
        Swal.fire({ icon:'success', title:'{{ __('common.swal_deleted') }}', timer:1500, showConfirmButton:false });
    } else {
        Swal.fire({ icon:'error', title:'{{ __('common.swal_error') }}', text:data.message });
    }
}

// Marquer toutes les lignes dep avec la classe dep-row au chargement
document.querySelectorAll('#depTbody tr').forEach(function(r) { r.classList.add('dep-row'); });
</script>
@endsection
