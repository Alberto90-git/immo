@extends('layouts.template')

@section('title')
<title>{{ __('pages.calendar_title') }}</title>
<style>
.avail-grid { display:flex; flex-direction:column; gap:18px; }
.house-block { background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,.07); overflow:hidden; }
.house-header { background:#696cff; color:#fff; padding:10px 18px; font-weight:700; font-size:.95rem; display:flex; align-items:center; gap:8px; }
.rooms-grid { display:flex; flex-wrap:wrap; gap:10px; padding:14px 18px; }
.room-pill {
  display:flex; flex-direction:column; align-items:center; justify-content:center;
  width:110px; min-height:70px; border-radius:10px; padding:10px 8px;
  font-size:.78rem; text-align:center; font-weight:600; cursor:default;
  border:2px solid transparent; transition:.15s;
}
.room-pill.libre      { background:#d1fae5; color:#065f46; border-color:#6ee7b7; }
.room-pill.occupe     { background:#fee2e2; color:#991b1b; border-color:#fca5a5; }
.room-pill.prereservee{ background:#fef3cd; color:#856404; border-color:#fde68a; }
.room-pill .room-num  { font-size:1rem; font-weight:800; }
.room-pill .room-type { font-size:.68rem; opacity:.8; margin-top:1px; }
.room-pill .room-status { font-size:.68rem; margin-top:3px; }
.legend-dot { display:inline-block; width:12px; height:12px; border-radius:50%; margin-right:5px; }
.calendar-wrapper { border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.07); }

/* ─── Dark mode ──────────────────────────────────────────── */
html.dark-style .house-block { background:#2f3049; box-shadow:none; border:1px solid #444564; }
html.dark-style .rooms-grid  { background:#2f3049; }
html.dark-style .room-pill.libre       { background:rgba(113,221,55,0.12);  color:#71dd37; border-color:rgba(113,221,55,0.35); }
html.dark-style .room-pill.occupe      { background:rgba(255,62,29,0.12);   color:#ff8a75; border-color:rgba(255,62,29,0.35); }
html.dark-style .room-pill.prereservee { background:rgba(255,171,0,0.12);   color:#ffab00; border-color:rgba(255,171,0,0.35); }
html.dark-style .calendar-wrapper { box-shadow:none; border:1px solid #444564; }
/* FullCalendar dark overrides */
html.dark-style .fc { color:#d0d4e0; }
html.dark-style .fc .fc-toolbar-title { color:#d0d4e0; }
html.dark-style .fc .fc-button { background:#373852; border-color:#444564; color:#d0d4e0; }
html.dark-style .fc .fc-button:hover { background:#444564; }
html.dark-style .fc .fc-button-primary:not(:disabled).fc-button-active { background:#696cff; border-color:#696cff; }
html.dark-style .fc .fc-daygrid-day { background:#2f3049; }
html.dark-style .fc .fc-day-today { background:#373852 !important; }
html.dark-style .fc th, html.dark-style .fc td { border-color:#444564; }
html.dark-style .fc .fc-col-header-cell-cushion,
html.dark-style .fc .fc-daygrid-day-number { color:#a3a4cc; }
html.dark-style .fc .fc-list-day-cushion { background:#373852; }
html.dark-style .fc .fc-list-event:hover td { background:#444564; }
</style>
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet'>
@endsection

@section('content')
@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">
  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">{{ __('common.home_breadcrumb') }}</span>
      {{ __('pages.calendar_breadcrumb') }}
    </h4>
    <div class="d-flex gap-2">
      <a href="{{ route('prospects.index') }}" class="btn btn-sm btn-outline-primary">
        <i class="bx bx-arrow-back me-1"></i>Pipeline
      </a>
      @can('gestion-visite')
      <a href="{{ route('prospects.agenda') }}" class="btn btn-sm btn-outline-warning">
        <i class="bx bx-calendar me-1"></i>{{ __('pages.agenda_breadcrumb') }}
      </a>
      @endcan
    </div>
  </div>

  {{-- Légende --}}
  <div class="d-flex gap-4 mb-3 flex-wrap">
    <span><span class="legend-dot" style="background:#6ee7b7"></span>{{ __('pages.calendar_available') }}</span>
    <span><span class="legend-dot" style="background:#fca5a5"></span>{{ __('pages.calendar_occupied') }}</span>
    <span><span class="legend-dot" style="background:#fde68a"></span>{{ __('pages.calendar_prereserved') }}</span>
  </div>

  {{-- Grille des disponibilités --}}
  <div class="avail-grid mb-5">
    @forelse($maisons as $maison)
    <div class="house-block">
      <div class="house-header">
        <i class="bx bx-home-alt"></i>
        {{ $maison->nom_maison }}
        @if($maison->quartier)
          <span class="fw-light ms-1 opacity-75">— {{ $maison->quartier }}</span>
        @endif
        <span class="ms-auto badge bg-white text-dark">
          {{ $maison->chambres->where('etat', false)->count() }} libre(s) / {{ $maison->chambres->count() }} chambre(s)
        </span>
      </div>
      <div class="rooms-grid">
        @forelse($maison->chambres as $chambre)
          @php
            if($chambre->etat) {
              $cls = 'occupe';
              $statusLabel = __('pages.calendar_occupied');
            } elseif($chambre->pre_reservee) {
              $cls = 'prereservee';
              $statusLabel = __('pages.calendar_prereserved');
            } else {
              $cls = 'libre';
              $statusLabel = __('pages.calendar_available');
            }
          @endphp
          <div class="room-pill {{ $cls }}" title="{{ $statusLabel }}">
            <span class="room-num">{{ $chambre->numero_chambre }}</span>
            <span class="room-type">{{ $chambre->type_chambre }}</span>
            <span class="room-status">{{ format_price($chambre->prix_chambre) }}/mois</span>
            <span class="room-status">{{ $statusLabel }}</span>
          </div>
        @empty
          <div class="text-muted py-2 ps-1">Aucune chambre configurée.</div>
        @endforelse
      </div>
    </div>
    @empty
    <div class="card p-4 text-center text-muted">Aucune maison trouvée.</div>
    @endforelse
  </div>

  {{-- Calendrier FullCalendar des visites planifiées --}}
  <div class="card mb-4">
    <div class="card-header d-flex align-items-center gap-2">
      <i class="bx bx-calendar text-warning fs-5"></i>
      <strong>Visites planifiées</strong>
    </div>
    <div class="card-body p-2">
      <div id="fc-calendar" class="calendar-wrapper"></div>
    </div>
  </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var calEl = document.getElementById('fc-calendar');
  var events = @json($visites);
  var calendar = new FullCalendar.Calendar(calEl, {
    initialView: 'dayGridMonth',
    locale: '{{ app()->getLocale() }}',
    height: 420,
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,listWeek'
    },
    events: events,
    eventClick: function(info) {
      Swal.fire({ title: info.event.title, icon:'info', timer:3000, showConfirmButton:false });
    }
  });
  calendar.render();
});
</script>
@endsection
