@extends('layouts.template')

@section('title')
<title>{{ __('pages.prospect_title') }}</title>
<style>
/* ─── Kanban ─────────────────────────────────────────────── */
.pipeline-board { display:flex; gap:14px; overflow-x:auto; padding-bottom:12px; }
.pipeline-col { min-width:230px; max-width:260px; flex-shrink:0; }
.pipeline-col-header { border-radius:8px 8px 0 0; padding:8px 12px; font-weight:700; font-size:.82rem; letter-spacing:.04em; display:flex; align-items:center; justify-content:space-between; }
.pipeline-col-body { background:#f4f5fb; border-radius:0 0 8px 8px; padding:8px; min-height:120px; }
.prospect-card { background:#fff; border-radius:8px; padding:10px 12px; margin-bottom:8px; box-shadow:0 1px 4px rgba(0,0,0,.07); font-size:.83rem; cursor:pointer; transition:box-shadow .15s; }
.prospect-card:hover { box-shadow:0 3px 12px rgba(105,108,255,.15); }
.prospect-card .name { font-weight:700; color:#333; margin-bottom:2px; }
.prospect-card .meta { color:#888; font-size:.75rem; }
.prospect-card .prereserv-badge { font-size:.68rem; background:#fef3cd; color:#856404; border-radius:4px; padding:1px 5px; }
/* ─── Stat cards ─────────────────────────────────────────── */
.stat-card { background:var(--kpi-card-bg,#fff); border-radius:12px; padding:16px 20px; box-shadow:0 2px 8px rgba(0,0,0,.07); }
.stat-value { font-size:1.5rem; font-weight:800; }
/* ─── View tabs ──────────────────────────────────────────── */
.view-tab { border:none; background:none; padding:8px 16px; font-weight:600; color:#6c757d; border-bottom:3px solid transparent; transition:.2s; cursor:pointer; }
.view-tab.active { color:#696cff; border-bottom-color:#696cff; }

/* ─── Dark mode ──────────────────────────────────────────── */
html.dark-style .pipeline-col-body { background:#373852; }
html.dark-style .prospect-card { background:#2f3049; border:1px solid #444564; box-shadow:none; }
html.dark-style .prospect-card:hover { box-shadow:0 3px 12px rgba(140,142,255,.12); border-color:#696cff; }
html.dark-style .prospect-card .name { color:#d0d4e0; }
html.dark-style .prospect-card .meta { color:#a3a4cc; }
html.dark-style .prospect-card .prereserv-badge { background:rgba(255,171,0,0.15); color:#ffab00; }
html.dark-style .view-tab { color:#a3a4cc; }
html.dark-style .view-tab.active { color:#8c8eff; border-bottom-color:#8c8eff; }
html.dark-style .view-tab:hover { color:#d0d4e0; }
</style>
@endsection

@section('content')
@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">{{ __('common.home_breadcrumb') }}</span>
      {{ __('pages.prospect_breadcrumb') }}
    </h4>
    <div class="d-flex gap-2 flex-wrap">
      @can('gestion-visite')
      <a href="{{ route('prospects.agenda') }}" class="btn btn-sm btn-outline-warning">
        <i class="bx bx-calendar me-1"></i>{{ __('pages.agenda_breadcrumb') }}
      </a>
      @endcan
      <a href="{{ route('prospects.calendrier') }}" class="btn btn-sm btn-outline-info">
        <i class="bx bx-grid-alt me-1"></i>{{ __('pages.calendar_breadcrumb') }}
      </a>
      @can('ajoute-prospect')
      <button class="btn btn-primary btn-sm" onclick="openAddModal()">
        <i class="bx bx-plus me-1"></i>{{ __('pages.prospect_add_btn') }}
      </button>
      @endcan
    </div>
  </div>

  {{-- Stats bar --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-2">
      <div class="stat-card text-center">
        <div class="stat-value text-info">{{ $pipeline->get('demande',collect())->count() }}</div>
        <div class="small text-muted">{{ __('pages.prospect_statut_demande') }}</div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="stat-card text-center">
        <div class="stat-value text-warning">{{ ($pipeline->get('visite_planifiee',collect())->count() + $pipeline->get('visite_effectuee',collect())->count()) }}</div>
        <div class="small text-muted">Visites</div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="stat-card text-center">
        <div class="stat-value text-secondary">{{ $pipeline->get('dossier',collect())->count() }}</div>
        <div class="small text-muted">{{ __('pages.prospect_statut_dossier') }}</div>
      </div>
    </div>
    <div class="col-6 col-md-2">
      <div class="stat-card text-center">
        <div class="stat-value text-success">{{ $pipeline->get('converti',collect())->count() }}</div>
        <div class="small text-muted">{{ __('pages.prospect_statut_converti') }}</div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="stat-card text-center">
        <div class="stat-value text-danger">{{ format_price($totalFraisVisites) }}</div>
        <div class="small text-muted">{{ __('pages.agenda_total_frais') }}</div>
      </div>
    </div>
  </div>

  {{-- View toggle --}}
  <div class="d-flex border-bottom mb-3 gap-2">
    <button class="view-tab active" onclick="switchView(this,'view-kanban')"><i class="bx bx-columns me-1"></i>Pipeline</button>
    <button class="view-tab" onclick="switchView(this,'view-list')"><i class="bx bx-list-ul me-1"></i>{{ __('pages.prospect_list_title') }}</button>
  </div>

  {{-- ══ KANBAN ══ --}}
  <div id="view-kanban">
    @php
      $cols = [
        'demande'          => ['label' => __('pages.prospect_statut_demande'),          'color' => '#0dcaf0'],
        'visite_planifiee' => ['label' => __('pages.prospect_statut_visite_planifiee'), 'color' => '#ffc107'],
        'visite_effectuee' => ['label' => __('pages.prospect_statut_visite_effectuee'), 'color' => '#696cff'],
        'dossier'          => ['label' => __('pages.prospect_statut_dossier'),          'color' => '#6c757d'],
        'converti'         => ['label' => __('pages.prospect_statut_converti'),         'color' => '#28a745'],
        'annule'           => ['label' => __('pages.prospect_statut_annule'),           'color' => '#dc3545'],
      ];
    @endphp
    <div class="pipeline-board">
      @foreach($cols as $statut => $cfg)
      <div class="pipeline-col">
        <div class="pipeline-col-header text-white" style="background:{{ $cfg['color'] }}">
          <span>{{ $cfg['label'] }}</span>
          <span class="badge bg-white" style="color:{{ $cfg['color'] }}">{{ $pipeline->get($statut, collect())->count() }}</span>
        </div>
        <div class="pipeline-col-body" id="col-{{ $statut }}">
          @forelse($pipeline->get($statut, collect()) as $p)
          <div class="prospect-card" onclick="openDetailModal({{ $p->id }})">
            <div class="name">{{ $p->getNomComplet() }}</div>
            <div class="meta"><i class="bx bx-phone-call"></i> {{ $p->telephone }}</div>
            @if($p->maison)
            <div class="meta"><i class="bx bx-home-alt"></i> {{ $p->maison->nom_maison }}</div>
            @endif
            @if($p->chambre)
            <div class="meta"><i class="bx bx-door-open"></i> Ch. {{ $p->chambre->numero_chambre }}</div>
            @endif
            @if($p->preReservationActive)
            <span class="prereserv-badge mt-1 d-inline-block"><i class="bx bx-lock-alt"></i> Pré-réservée</span>
            @endif
            <div class="meta mt-1 text-end">{{ $p->source === 'public' ? '🌐' : '👤' }} {{ $p->created_at->diffForHumans() }}</div>
          </div>
          @empty
          <div class="text-center text-muted py-3" style="font-size:.75rem;">—</div>
          @endforelse
        </div>
      </div>
      @endforeach
    </div>
  </div>

  {{-- ══ LIST ══ --}}
  <div id="view-list" style="display:none">
    <div class="d-flex gap-2 mb-3 flex-wrap align-items-center">
      <input type="text" id="searchQ" class="form-control form-control-sm" style="max-width:220px" placeholder="Rechercher..." oninput="filterList()">
      <select id="filterStatut" class="form-select form-select-sm" style="width:auto" onchange="filterList()">
        <option value="">Tous les statuts</option>
        @foreach(['demande','visite_planifiee','visite_effectuee','dossier','converti','annule'] as $s)
        <option value="{{ $s }}">{{ __('pages.prospect_statut_'.$s) }}</option>
        @endforeach
      </select>
    </div>
    <div class="card">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="prospectTable">
          <thead class="table-light">
            <tr>
              <th>{{ __('pages.prospect_col_name') }}</th>
              <th>{{ __('pages.prospect_col_phone') }}</th>
              <th>{{ __('pages.prospect_col_house') }}</th>
              <th>{{ __('pages.prospect_col_room') }}</th>
              <th>{{ __('pages.prospect_col_statut') }}</th>
              <th>{{ __('pages.prospect_col_source') }}</th>
              <th>{{ __('pages.prospect_col_date') }}</th>
              <th>{{ __('pages.prospect_col_actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @forelse($prospects as $p)
            <tr data-statut="{{ $p->statut }}" data-q="{{ strtolower($p->getNomComplet().' '.$p->telephone) }}">
              <td class="fw-semibold">{{ $p->getNomComplet() }}</td>
              <td>{{ $p->telephone }}</td>
              <td>{{ $p->maison->nom_maison ?? '—' }}</td>
              <td>{{ $p->chambre ? 'Ch. '.$p->chambre->numero_chambre : '—' }}</td>
              <td><span class="badge {{ $p->getStatutBadgeClass() }}">{{ $p->getStatutLabel() }}</span></td>
              <td>
                @if($p->source === 'public')
                <span class="badge bg-label-info">🌐 {{ __('pages.prospect_source_public') }}</span>
                @else
                <span class="badge bg-label-secondary">👤 {{ __('pages.prospect_source_agent') }}</span>
                @endif
              </td>
              <td>{{ $p->created_at->format('d/m/Y') }}</td>
              <td>
                <div class="d-flex gap-1">
                  <button class="btn btn-xs btn-icon btn-outline-primary" title="Détail" onclick="openDetailModal({{ $p->id }})">
                    <i class="bx bx-show"></i>
                  </button>
                  @can('ajoute-visite')
                  <button class="btn btn-xs btn-icon btn-outline-warning" title="{{ __('pages.visite_add_modal') }}" onclick="openVisiteModal({{ $p->id }}, '{{ addslashes($p->getNomComplet()) }}')">
                    <i class="bx bx-calendar-plus"></i>
                  </button>
                  @endcan
                  @can('convertir-prospect')
                  @if(!in_array($p->statut, ['converti','annule']))
                  <a href="{{ route('prospects.convertir', encrypt_id($p->id)) }}" class="btn btn-xs btn-icon btn-outline-success" title="{{ __('pages.prospect_convert_btn') }}"
                     onclick="return confirm('{{ __('pages.prospect_confirm_convert') }} ({{ $p->getNomComplet() }})')">
                    <i class="bx bx-transfer"></i>
                  </a>
                  @endif
                  @endcan
                  @can('delete-prospect')
                  <button class="btn btn-xs btn-icon btn-outline-danger" title="Supprimer" onclick="deleteProspect({{ $p->id }}, '{{ addslashes($p->getNomComplet()) }}')">
                    <i class="bx bx-trash"></i>
                  </button>
                  @endcan
                </div>
              </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted py-4">Aucun prospect.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════ MODAL AJOUTER PROSPECT ═══════════════ --}}
<div class="modal fade" id="modalAddProspect" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white">{{ __('pages.prospect_add_modal') }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formAddProspect" onsubmit="saveProspect(event)">
          @csrf
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.prospect_lbl_nom') }} <span class="text-danger">*</span></label>
              <input type="text" name="nom" id="add_nom" class="form-control" required maxlength="100">
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.prospect_lbl_prenom') }}</label>
              <input type="text" name="prenom" id="add_prenom" class="form-control" maxlength="100">
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.prospect_lbl_telephone') }} <span class="text-danger">*</span></label>
              <input type="text" name="telephone" id="add_telephone" class="form-control" required maxlength="20">
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.prospect_lbl_email') }}</label>
              <input type="email" name="email" id="add_email" class="form-control" maxlength="150">
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.prospect_lbl_maison') }}</label>
              <select name="maison_id" id="add_maison" class="form-select" onchange="loadChambresProspect(this.value,'add_chambre')">
                <option value="">{{ __('pages.prospect_choose_house') }}</option>
                @foreach($maisons as $m)
                <option value="{{ $m->id }}">{{ $m->nom_maison }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.prospect_lbl_chambre') }}</label>
              <select name="chambre_id" id="add_chambre" class="form-select">
                <option value="">{{ __('pages.prospect_choose_room') }}</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">{{ __('pages.prospect_lbl_note') }}</label>
              <textarea name="note" id="add_note" class="form-control" rows="2"></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
        <button type="button" class="btn btn-primary" onclick="$('#formAddProspect').submit()">
          <i class="bx bx-save me-1"></i>Enregistrer
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════ MODAL DÉTAIL / ÉDITION ═══════════════ --}}
<div class="modal fade" id="modalDetailProspect" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-secondary">
        <h5 class="modal-title text-white" id="detailModalTitle">Prospect</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="detailModalBody">
        <div class="text-center py-4"><div class="spinner-border text-primary"></div></div>
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════ MODAL PLANIFIER VISITE ═══════════════ --}}
<div class="modal fade" id="modalVisite" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">{{ __('pages.visite_add_modal') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formVisite" onsubmit="saveVisite(event)">
          @csrf
          <input type="hidden" name="prospect_id" id="visite_prospect_id">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label fw-semibold" id="visite_prospect_name"></label>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.visite_lbl_date') }} <span class="text-danger">*</span></label>
              <input type="datetime-local" name="date_visite" id="visite_date" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.visite_lbl_frais') }}</label>
              <input type="number" name="frais_visite" id="visite_frais" class="form-control" min="0" step="0.01" placeholder="0">
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.visite_lbl_chambre') }}</label>
              <select name="chambre_id" id="visite_chambre" class="form-select">
                <option value="">—</option>
                @foreach($chambresLibres as $c)
                <option value="{{ $c->id }}">{{ $c->maison->nom_maison ?? '' }} – Ch. {{ $c->numero_chambre }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.visite_lbl_agent') }}</label>
              <select name="agent_id" id="visite_agent" class="form-select">
                <option value="">—</option>
                @foreach($agents as $a)
                <option value="{{ $a->id }}">{{ $a->nom ?? $a->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-12">
              <label class="form-label">{{ __('pages.visite_lbl_note') }}</label>
              <textarea name="note" id="visite_note" class="form-control" rows="2"></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
        <button type="button" class="btn btn-warning" onclick="$('#formVisite').submit()">
          <i class="bx bx-calendar-check me-1"></i>Planifier
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════ MODAL PRÉ-RÉSERVATION ═══════════════ --}}
<div class="modal fade" id="modalPreReserv" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">{{ __('pages.prereserv_add_modal') }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="formPreReserv" onsubmit="savePreReserv(event)">
          @csrf
          <input type="hidden" name="prospect_id" id="prereserv_prospect_id">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">{{ __('pages.prereserv_lbl_chambre') }} <span class="text-danger">*</span></label>
              <select name="chambre_id" id="prereserv_chambre" class="form-select" required>
                <option value="">{{ __('pages.prospect_choose_room') }}</option>
                @foreach($chambresLibres as $c)
                <option value="{{ $c->id }}">{{ $c->maison->nom_maison ?? '' }} – Ch. {{ $c->numero_chambre }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.prereserv_lbl_date_debut') }} <span class="text-danger">*</span></label>
              <input type="date" name="date_debut" id="prereserv_debut" class="form-control" required value="{{ date('Y-m-d') }}">
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.prereserv_lbl_date_fin') }} <span class="text-danger">*</span></label>
              <input type="date" name="date_fin" id="prereserv_fin" class="form-control" required value="{{ date('Y-m-d', strtotime('+15 days')) }}">
            </div>
            <div class="col-12">
              <label class="form-label">{{ __('pages.prereserv_lbl_note') }}</label>
              <textarea name="note" id="prereserv_note" class="form-control" rows="2"></textarea>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
        <button type="button" class="btn btn-info text-white" onclick="$('#formPreReserv').submit()">
          <i class="bx bx-lock-alt me-1"></i>Bloquer
        </button>
      </div>
    </div>
  </div>
</div>

<script>
var CSRF = '{{ csrf_token() }}';
var ROUTES = {
  store:        '{{ route('prospects.store') }}',
  update:       '{{ route('prospects.update') }}',
  updateStatut: '{{ route('prospects.update_statut') }}',
  destroy:      '{{ route('prospects.destroy') }}',
  visiteStore:  '{{ route('prospects.visite_store') }}',
  prereservStore: '{{ route('prospects.prereserv_store') }}',
  prereservAnnuler: '{{ route('prospects.prereserv_annuler') }}',
  chambres:     '{{ route('prospects.chambres_maison') }}',
};

// Prospects data (for detail modal)
var PROSPECTS = @json($prospects->keyBy('id'));

function switchView(btn, target) {
  document.querySelectorAll('.view-tab').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  ['view-kanban','view-list'].forEach(id => {
    document.getElementById(id).style.display = (id === target) ? '' : 'none';
  });
}

function openAddModal() {
  document.getElementById('formAddProspect').reset();
  document.getElementById('add_chambre').innerHTML = '<option value="">{{ __('pages.prospect_choose_room') }}</option>';
  new bootstrap.Modal(document.getElementById('modalAddProspect')).show();
}

function saveProspect(e) {
  e.preventDefault();
  var form = document.getElementById('formAddProspect');
  var btn  = form.closest('.modal').querySelector('.btn-primary');
  btn.disabled = true;
  $.post(ROUTES.store, $(form).serialize())
    .done(function(r) {
      if(r.status) { Swal.fire({icon:'success',title:r.message,timer:2000,showConfirmButton:false}).then(()=>location.reload()); }
      else Swal.fire({icon:'error',title:r.message});
    })
    .fail(function(){ Swal.fire({icon:'error',title:'Erreur réseau'}); })
    .always(function(){ btn.disabled = false; });
}

function openDetailModal(id) {
  var p = PROSPECTS[id];
  if(!p) return;
  document.getElementById('detailModalTitle').textContent = (p.nom||'') + ' ' + (p.prenom||'');
  var html = buildDetailHtml(p);
  document.getElementById('detailModalBody').innerHTML = html;
  new bootstrap.Modal(document.getElementById('modalDetailProspect')).show();
}

function buildDetailHtml(p) {
  var statuts = {
    demande:'{{ __('pages.prospect_statut_demande') }}',
    visite_planifiee:'{{ __('pages.prospect_statut_visite_planifiee') }}',
    visite_effectuee:'{{ __('pages.prospect_statut_visite_effectuee') }}',
    dossier:'{{ __('pages.prospect_statut_dossier') }}',
    converti:'{{ __('pages.prospect_statut_converti') }}',
    annule:'{{ __('pages.prospect_statut_annule') }}'
  };
  var badges = {
    demande:'bg-label-info',visite_planifiee:'bg-label-warning',
    visite_effectuee:'bg-label-primary',dossier:'bg-label-secondary',
    converti:'bg-label-success',annule:'bg-label-danger'
  };
  var html = '<div class="row g-3">';
  html += '<div class="col-md-6"><strong>Téléphone :</strong> ' + (p.telephone||'—') + '</div>';
  html += '<div class="col-md-6"><strong>Email :</strong> ' + (p.email||'—') + '</div>';
  if(p.maison) html += '<div class="col-md-6"><strong>Maison :</strong> ' + p.maison.nom_maison + '</div>';
  if(p.chambre) html += '<div class="col-md-6"><strong>Chambre :</strong> Ch. ' + p.chambre.numero_chambre + '</div>';
  if(p.note) html += '<div class="col-12"><strong>Note :</strong> ' + p.note + '</div>';
  if(p.message) html += '<div class="col-12"><strong>Message :</strong> ' + p.message + '</div>';
  html += '<div class="col-md-6"><strong>Statut :</strong> <span class="badge ' + (badges[p.statut]||'bg-secondary') + '">' + (statuts[p.statut]||p.statut) + '</span></div>';
  html += '<div class="col-md-6"><strong>Source :</strong> ' + (p.source === 'public' ? '🌐 {{ __('pages.prospect_source_public') }}' : '👤 {{ __('pages.prospect_source_agent') }}') + '</div>';
  // Actions
  html += '<div class="col-12 d-flex gap-2 flex-wrap pt-2">';
  @can('ajoute-visite')
  html += '<button class="btn btn-sm btn-warning" onclick="openVisiteModal('+p.id+', \''+p.nom+' '+(p.prenom||'')+'\')"><i class="bx bx-calendar-plus me-1"></i>{{ __('pages.visite_add_modal') }}</button>';
  @endcan
  @can('ajoute-prospect')
  html += '<button class="btn btn-sm btn-info text-white" onclick="openPreReservModal('+p.id+')"><i class="bx bx-lock-alt me-1"></i>{{ __('pages.prereserv_add_modal') }}</button>';
  @endcan
  @can('convertir-prospect')
  if(!['converti','annule'].includes(p.statut)) {
    html += '<a href="{{ url('prospects') }}/'+p.id+'/convertir" class="btn btn-sm btn-success" onclick="return confirm(\'{{ __('pages.prospect_confirm_convert') }}\')"><i class="bx bx-transfer me-1"></i>{{ __('pages.prospect_convert_btn') }}</a>';
  }
  @endcan
  @can('modify-prospect')
  html += '<select class="form-select form-select-sm" style="width:auto" onchange="updateStatut('+p.id+',this.value)">';
  var statOpts = ['demande','visite_planifiee','visite_effectuee','dossier','converti','annule'];
  statOpts.forEach(function(s){ html += '<option value="'+s+'"'+(p.statut===s?' selected':'')+'>'+( statuts[s]||s)+'</option>'; });
  html += '</select>';
  @endcan
  @can('delete-prospect')
  html += '<button class="btn btn-sm btn-outline-danger ms-auto" onclick="deleteProspect('+p.id+', \''+p.nom+' '+(p.prenom||'')+'\')"><i class="bx bx-trash"></i></button>';
  @endcan
  html += '</div></div>';
  return html;
}

function openVisiteModal(prospectId, prospectName) {
  document.getElementById('visite_prospect_id').value = prospectId;
  document.getElementById('visite_prospect_name').textContent = prospectName;
  document.getElementById('formVisite').reset();
  document.getElementById('visite_prospect_id').value = prospectId;
  // Set default date to now+1h
  var d = new Date(); d.setHours(d.getHours()+1,0,0,0);
  document.getElementById('visite_date').value = d.toISOString().slice(0,16);
  new bootstrap.Modal(document.getElementById('modalVisite')).show();
}

function saveVisite(e) {
  e.preventDefault();
  var btn = document.querySelector('#modalVisite .btn-warning');
  btn.disabled = true;
  $.post(ROUTES.visiteStore, $('#formVisite').serialize())
    .done(function(r){
      if(r.status) { Swal.fire({icon:'success',title:r.message,timer:2000,showConfirmButton:false}).then(()=>location.reload()); }
      else Swal.fire({icon:'error',title:r.message});
    })
    .fail(function(){ Swal.fire({icon:'error',title:'Erreur réseau'}); })
    .always(function(){ btn.disabled=false; });
}

function openPreReservModal(prospectId) {
  document.getElementById('prereserv_prospect_id').value = prospectId;
  document.getElementById('formPreReserv').reset();
  document.getElementById('prereserv_prospect_id').value = prospectId;
  new bootstrap.Modal(document.getElementById('modalPreReserv')).show();
}

function savePreReserv(e) {
  e.preventDefault();
  var btn = document.querySelector('#modalPreReserv .btn-info');
  btn.disabled = true;
  $.post(ROUTES.prereservStore, $('#formPreReserv').serialize())
    .done(function(r){
      if(r.status) { Swal.fire({icon:'success',title:r.message,timer:2000,showConfirmButton:false}).then(()=>location.reload()); }
      else Swal.fire({icon:'error',title:r.message});
    })
    .fail(function(){ Swal.fire({icon:'error',title:'Erreur réseau'}); })
    .always(function(){ btn.disabled=false; });
}

function updateStatut(id, statut) {
  $.post(ROUTES.updateStatut, {_token:CSRF, id:id, statut:statut})
    .done(function(r){
      if(r.status) { Swal.fire({icon:'success',title:r.message,timer:1500,showConfirmButton:false}).then(()=>location.reload()); }
      else Swal.fire({icon:'error',title:r.message});
    });
}

function deleteProspect(id, name) {
  Swal.fire({
    title: '{{ __('pages.prospect_confirm_delete') }}',
    html: '<strong>'+name+'</strong>',
    icon:'warning', showCancelButton:true,
    confirmButtonColor:'#d33', confirmButtonText:'Supprimer'
  }).then(function(res){
    if(res.isConfirmed) {
      $.post(ROUTES.destroy, {_token:CSRF, id:id})
        .done(function(r){
          if(r.status) { Swal.fire({icon:'success',title:r.message,timer:1800,showConfirmButton:false}).then(()=>location.reload()); }
          else Swal.fire({icon:'error',title:r.message});
        });
    }
  });
}

function loadChambresProspect(maisonId, targetId) {
  var sel = document.getElementById(targetId);
  if(!maisonId) { sel.innerHTML='<option value="">{{ __('pages.prospect_choose_room') }}</option>'; return; }
  $.get(ROUTES.chambres, {maison_id:maisonId}, function(r){
    sel.innerHTML='<option value="">{{ __('pages.prospect_choose_room') }}</option>';
    if(r.status && r.data.length) {
      r.data.forEach(function(c){ sel.innerHTML+='<option value="'+c.id+'">Ch. '+c.numero_chambre+' ('+c.type_chambre+')</option>'; });
    } else {
      sel.innerHTML='<option value="" disabled>{{ __('pages.prospect_no_room') }}</option>';
    }
  });
}

function filterList() {
  var q = document.getElementById('searchQ').value.toLowerCase();
  var s = document.getElementById('filterStatut').value;
  document.querySelectorAll('#prospectTable tbody tr').forEach(function(row){
    var rowQ = (row.dataset.q||'').toLowerCase();
    var rowS = row.dataset.statut||'';
    var show = (!q || rowQ.includes(q)) && (!s || rowS === s);
    row.style.display = show ? '' : 'none';
  });
}
</script>
@endsection
