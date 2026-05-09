@extends('layouts.template')

@section('title')
<title>{{ __('pages.agenda_title') }}</title>
<style>
.visit-status-planifiee { background:#fff3cd; color:#856404; }
.visit-status-effectuee { background:#d1fae5; color:#065f46; }
.visit-status-annulee   { background:#fee2e2; color:#991b1b; }
.kpi-card { background:var(--kpi-card-bg,#fff); border-radius:12px; padding:16px 20px; box-shadow:0 2px 8px rgba(0,0,0,.07); }
.kpi-val  { font-size:1.6rem; font-weight:800; }

/* ─── Dark mode ──────────────────────────────────────────── */
html.dark-style .visit-status-planifiee { background:rgba(255,171,0,0.15); color:#ffab00; }
html.dark-style .visit-status-effectuee { background:rgba(113,221,55,0.15); color:#71dd37; }
html.dark-style .visit-status-annulee   { background:rgba(255,62,29,0.15);  color:#ff8a75; }
/* FullCalendar dark overrides */
html.dark-style .fc { color:#d0d4e0; }
html.dark-style .fc .fc-toolbar-title { color:#d0d4e0; }
html.dark-style .fc .fc-button { background:#373852; border-color:#444564; color:#d0d4e0; }
html.dark-style .fc .fc-button:hover { background:#444564; }
html.dark-style .fc .fc-button-primary:not(:disabled).fc-button-active { background:#696cff; border-color:#696cff; }
html.dark-style .fc .fc-daygrid-day,
html.dark-style .fc .fc-timegrid-slot { background:#2f3049; }
html.dark-style .fc .fc-day-today { background:#373852 !important; }
html.dark-style .fc th, html.dark-style .fc td { border-color:#444564; }
html.dark-style .fc .fc-col-header-cell-cushion,
html.dark-style .fc .fc-daygrid-day-number,
html.dark-style .fc .fc-timegrid-axis-cushion { color:#a3a4cc; }
html.dark-style .fc .fc-list-day-cushion { background:#373852; }
html.dark-style .fc .fc-list-event:hover td { background:#444564; }
html.dark-style .fc .fc-list-empty { background:#2f3049; color:#a3a4cc; }
</style>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet'>
@endsection

@section('content')
@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">{{ __('common.home_breadcrumb') }}</span>
      {{ __('pages.agenda_breadcrumb') }}
    </h4>
    <div class="d-flex gap-2">
      <a href="{{ route('prospects.index') }}" class="btn btn-sm btn-outline-primary">
        <i class="bx bx-arrow-back me-1"></i>Pipeline
      </a>
      <a href="{{ route('prospects.calendrier') }}" class="btn btn-sm btn-outline-info">
        <i class="bx bx-grid-alt me-1"></i>{{ __('pages.calendar_breadcrumb') }}
      </a>
    </div>
  </div>

  {{-- KPIs --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="kpi-card text-center">
        <div class="kpi-val text-warning">{{ $visites->where('statut','planifiee')->count() }}</div>
        <div class="small text-muted">{{ __('pages.visite_statut_planifiee') }}</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card text-center">
        <div class="kpi-val text-success">{{ $visites->where('statut','effectuee')->count() }}</div>
        <div class="small text-muted">{{ __('pages.visite_statut_effectuee') }}</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card text-center">
        <div class="kpi-val text-danger">{{ $visites->where('statut','annulee')->count() }}</div>
        <div class="small text-muted">{{ __('pages.visite_statut_annulee') }}</div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="kpi-card text-center">
        <div class="kpi-val text-primary">{{ format_price($totalFrais) }}</div>
        <div class="small text-muted">{{ __('pages.agenda_total_frais') }}</div>
      </div>
    </div>
  </div>

  {{-- Calendrier FullCalendar --}}
  <div class="card mb-4">
    <div class="card-body p-2">
      <div id="fc-agenda"></div>
    </div>
  </div>

  {{-- Tableau --}}
  <div class="card">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
      <strong>{{ __('pages.agenda_title') }}</strong>
      <div class="d-flex gap-2 flex-wrap">
        <select id="filterVisiteStatut" class="form-select form-select-sm" style="width:auto" onchange="filterVisites()">
          <option value="">Tous</option>
          <option value="planifiee">{{ __('pages.visite_statut_planifiee') }}</option>
          <option value="effectuee">{{ __('pages.visite_statut_effectuee') }}</option>
          <option value="annulee">{{ __('pages.visite_statut_annulee') }}</option>
        </select>
        <input type="text" id="filterVisiteQ" class="form-control form-control-sm" style="width:180px" placeholder="Rechercher..." oninput="filterVisites()">
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-hover mb-0" id="agendaTable">
        <thead class="table-light">
          <tr>
            <th>{{ __('pages.agenda_col_prospect') }}</th>
            <th>{{ __('pages.agenda_col_chambre') }}</th>
            <th>{{ __('pages.agenda_col_date') }}</th>
            <th>{{ __('pages.agenda_col_frais') }}</th>
            <th>{{ __('pages.agenda_col_statut') }}</th>
            <th>{{ __('pages.agenda_col_actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @forelse($visites as $v)
          <tr id="visite-row-{{ $v->id }}"
              data-statut="{{ $v->statut }}"
              data-q="{{ strtolower(($v->prospect ? $v->prospect->getNomComplet() : '').' '.($v->chambre ? $v->chambre->numero_chambre : '')) }}">
            <td class="fw-semibold">{{ $v->prospect ? $v->prospect->getNomComplet() : '—' }}</td>
            <td>
              @if($v->chambre)
                Ch. {{ $v->chambre->numero_chambre }}
              @else
                —
              @endif
            </td>
            <td>{{ $v->date_visite->format('d/m/Y H:i') }}</td>
            <td>{{ $v->frais_visite > 0 ? format_price($v->frais_visite) : '—' }}</td>
            <td>
              <span class="badge {{ $v->getStatutBadgeClass() }}">{{ $v->getStatutLabel() }}</span>
            </td>
            <td>
              @can('modify-visite')
              <div class="d-flex gap-1">
                @if($v->statut === 'planifiee')
                <button class="btn btn-xs btn-success" title="{{ __('pages.agenda_mark_done') }}" onclick="updateVisiteStatut({{ $v->id }},'effectuee')">
                  <i class="bx bx-check-circle me-1"></i>{{ __('pages.agenda_mark_done') }}
                </button>
                <button class="btn btn-xs btn-outline-danger" title="{{ __('pages.agenda_mark_cancel') }}" onclick="updateVisiteStatut({{ $v->id }},'annulee')">
                  <i class="bx bx-x-circle me-1"></i>{{ __('pages.agenda_mark_cancel') }}
                </button>
                @endif
              </div>
              @endcan
            </td>
          </tr>
          @empty
          <tr><td colspan="6" class="text-center text-muted py-4">{{ __('pages.agenda_empty') }}</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
var CSRF = '{{ csrf_token() }}';
var UPDATE_VISITE_URL = '{{ route('prospects.visite_update_statut') }}';

document.addEventListener('DOMContentLoaded', function(){
  var events = @json($events);
  var cal = new FullCalendar.Calendar(document.getElementById('fc-agenda'), {
    initialView: 'timeGridWeek',
    locale: '{{ app()->getLocale() }}',
    height: 380,
    headerToolbar: { left:'prev,next today', center:'title', right:'dayGridMonth,timeGridWeek,listWeek' },
    events: events,
    eventClick: function(info) {
      Swal.fire({ title: info.event.title, text: info.event.startStr, icon:'info', timer:3000, showConfirmButton:false });
    }
  });
  cal.render();
});

function updateVisiteStatut(id, statut) {
  var label = statut === 'effectuee' ? '{{ __('pages.agenda_mark_done') }}' : '{{ __('pages.agenda_mark_cancel') }}';
  Swal.fire({
    title: label + ' ?', icon: 'question',
    showCancelButton:true, confirmButtonText:'Confirmer'
  }).then(function(res){
    if(!res.isConfirmed) return;
    $.post(UPDATE_VISITE_URL, {_token:CSRF, id:id, statut:statut})
      .done(function(r){
        if(r.status) {
          Swal.fire({icon:'success', title:r.message, timer:1800, showConfirmButton:false})
            .then(()=>location.reload());
        } else {
          Swal.fire({icon:'error', title:r.message||'Erreur'});
        }
      })
      .fail(function(){ Swal.fire({icon:'error', title:'Erreur réseau'}); });
  });
}

function filterVisites() {
  var q = document.getElementById('filterVisiteQ').value.toLowerCase();
  var s = document.getElementById('filterVisiteStatut').value;
  document.querySelectorAll('#agendaTable tbody tr[data-statut]').forEach(function(row){
    var match = (!q || (row.dataset.q||'').includes(q)) && (!s || row.dataset.statut === s);
    row.style.display = match ? '' : 'none';
  });
}
</script>
@endsection
