@extends('layouts.template')

@section('content')
@section('title')
<title>{{ __('pages.ent_title') }}</title>
@endsection

<div class="container-xxl flex-grow-1 container-p-y">

  {{-- ===== Cartes de statistiques ===== --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card text-center border-0 shadow-sm h-100">
        <div class="card-body py-3">
          <div class="fs-1 fw-bold text-primary">{{ $stats['total'] }}</div>
          <div class="text-muted small">{{ __('pages.ent_stat_total') }}</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card text-center border-0 shadow-sm h-100">
        <div class="card-body py-3">
          <div class="fs-1 fw-bold text-success">{{ $stats['actifs'] }}</div>
          <div class="text-muted small">{{ __('pages.ent_stat_active') }}</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card text-center border-0 shadow-sm h-100">
        <div class="card-body py-3">
          <div class="fs-1 fw-bold text-warning">{{ $stats['en_attente'] }}</div>
          <div class="text-muted small">{{ __('pages.ent_stat_waiting') }}</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card text-center border-0 shadow-sm h-100">
        <div class="card-body py-3">
          <div class="fs-1 fw-bold text-danger">{{ $stats['expires'] }}</div>
          <div class="text-muted small">{{ __('pages.ent_stat_expired') }}</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== Tableau principal entreprises ===== --}}
  <div class="card shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
      <h5 class="mb-0"><i class="bx bx-buildings me-1"></i> {{ __('pages.ent_list_title') }}</h5>
      <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-sm btn-outline-secondary filter-btn active" data-filter="all">{{ __('pages.ent_filter_all') }}</button>
        <button class="btn btn-sm btn-outline-success filter-btn" data-filter="actif">{{ __('pages.ent_filter_active') }}</button>
        <button class="btn btn-sm btn-outline-warning filter-btn" data-filter="attente">{{ __('pages.ent_filter_waiting') }}</button>
        <button class="btn btn-sm btn-outline-danger filter-btn" data-filter="expire">{{ __('pages.ent_filter_expired') }}</button>
      </div>
    </div>

    <div class="table-responsive">
      <table id="tableEntreprises" class="table table-hover align-middle" style="width:100%">
        <thead class="table-light">
          <tr>
            <th>{{ __('pages.ent_th_company') }}</th>
            <th>{{ __('pages.ent_th_admin') }}</th>
            <th>{{ __('pages.ent_th_plan') }}</th>
            <th>{{ __('pages.ent_th_houses') }}</th>
            <th>{{ __('pages.ent_th_agencies') }}</th>
            <th>{{ __('pages.ent_th_sub_end') }}</th>
            <th>{{ __('pages.ent_th_days_left') }}</th>
            <th>{{ __('pages.ent_th_status') }}</th>
            <th class="text-center">{{ __('common.th_actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data as $item)
            @php
              $expired   = $item->abonnement_fin && \Carbon\Carbon::parse($item->abonnement_fin)->isPast();
              $blocked   = !empty($item->blocage_entreprise);
              $jours     = $item->jours_restants;
              $filterTag = $blocked ? 'attente' : ($expired ? 'expire' : 'actif');
            @endphp
            <tr data-filter="{{ $filterTag }}">
              {{-- Entreprise --}}
              <td>
                <div class="fw-semibold">{{ $item->designation }}</div>
                @if($item->direction_email)
                  <small class="text-muted">{{ $item->direction_email }}</small>
                @endif
              </td>

              {{-- Admin --}}
              <td>
                <div>{{ $item->nom }} {{ $item->prenom }}</div>
                <small class="text-muted">{{ $item->email }}</small>
              </td>

              {{-- Plan --}}
              <td>
                <span class="badge bg-info">{{ $item->plan_nom ?? __('pages.ent_badge_none') }}</span>
                @if($item->plan_prix)
                  <div class="small text-muted">{{ number_format($item->plan_prix, 0, ',', '.') }} {{ get_symbole_devise('XOF') }}/an</div>
                @endif
              </td>

              {{-- Maisons --}}
              <td class="text-center">
                <span class="badge bg-secondary">
                  {{ $item->nb_maisons }}
                  @if($item->max_maisons)
                    / {{ $item->max_maisons }}
                  @endif
                </span>
              </td>

              {{-- Agences --}}
              <td class="text-center">
                <span class="badge bg-secondary">
                  {{ $item->nb_agences }}
                  @if($item->max_annexes)
                    / {{ $item->max_annexes }}
                  @endif
                </span>
              </td>

              {{-- Fin abonnement --}}
              <td>
                @if($item->abonnement_fin)
                  {{ \Carbon\Carbon::parse($item->abonnement_fin)->format('d/m/Y') }}
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>

              {{-- Jours restants --}}
              <td class="text-center">
                @if(is_null($jours))
                  <span class="text-muted">-</span>
                @elseif($jours < 0)
                  <span class="badge bg-danger">{{ __('pages.ent_badge_expired') }}</span>
                @elseif($jours <= 7)
                  <span class="badge bg-warning text-dark">{{ $jours }}j</span>
                @elseif($jours <= 30)
                  <span class="badge bg-info">{{ $jours }}j</span>
                @else
                  <span class="badge bg-success">{{ $jours }}j</span>
                @endif
              </td>

              {{-- Statut --}}
              <td>
                @if($blocked)
                  <span class="badge rounded-pill bg-danger">{{ __('pages.ent_badge_waiting') }}</span>
                @elseif($expired)
                  <span class="badge rounded-pill bg-warning text-dark">{{ __('pages.ent_badge_expired') }}</span>
                @else
                  <span class="badge rounded-pill bg-success">{{ __('pages.ent_badge_active') }}</span>
                @endif
              </td>

              {{-- Actions --}}
              <td class="text-center">
                <div class="dropdown">
                  <button class="btn btn-sm btn-outline-secondary dt-action-toggle" type="button"
                          aria-expanded="false">
                    <i class="bx bx-cog"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end">

                    {{-- Bloquer / Valider --}}
                    @if($blocked)
                      <li>
                        <button class="dropdown-item text-success btn-toggle-block"
                                data-id="{{ encrypt_id($item->iddirection) }}"
                                data-action="debloquer"
                                data-nom="{{ $item->designation }}">
                          <i class="bx bx-check-circle me-1"></i> {{ __('pages.ent_menu_validate') }}
                        </button>
                      </li>
                    @else
                      <li>
                        <button class="dropdown-item text-danger btn-toggle-block"
                                data-id="{{ encrypt_id($item->iddirection) }}"
                                data-action="bloquer"
                                data-nom="{{ $item->designation }}">
                          <i class="bx bx-lock me-1"></i> {{ __('pages.ent_menu_block') }}
                        </button>
                      </li>
                    @endif

                    <li><hr class="dropdown-divider"></li>

                    {{-- Changer de plan --}}
                    <li>
                      <button class="dropdown-item btn-change-plan"
                              data-id="{{ encrypt_id($item->iddirection) }}"
                              data-nom="{{ $item->designation }}"
                              data-plan="{{ $item->idplan ?? '' }}">
                        <i class="bx bx-transfer me-1"></i> {{ __('pages.ent_menu_change_plan') }}
                      </button>
                    </li>

                    {{-- Renouveler --}}
                    <li>
                      <button class="dropdown-item btn-renouveler"
                              data-id="{{ encrypt_id($item->iddirection) }}"
                              data-nom="{{ $item->designation }}"
                              data-fin="{{ $item->abonnement_fin ?? '' }}">
                        <i class="bx bx-refresh me-1"></i> {{ __('pages.ent_menu_renew') }}
                      </button>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    {{-- Détails --}}
                    <li>
                      <button class="dropdown-item btn-details"
                              data-id="{{ $item->iddirection }}"
                              data-nom="{{ $item->designation }}"
                              data-admin="{{ $item->nom }} {{ $item->prenom }}"
                              data-email-admin="{{ $item->email }}"
                              data-email-dir="{{ $item->direction_email ?? '-' }}"
                              data-tel="{{ $item->telephone ?? '-' }}"
                              data-siege="{{ $item->siege_social ?? '-' }}"
                              data-plan="{{ $item->plan_nom ?? 'Aucun' }}"
                              data-prix="{{ $item->plan_prix ? number_format($item->plan_prix, 0, ',', '.') . ' ' . get_symbole_devise('XOF') : 'Gratuit' }}"
                              data-maisons="{{ $item->nb_maisons }}"
                              data-agences="{{ $item->nb_agences }}"
                              data-debut="{{ $item->abonnement_debut ? \Carbon\Carbon::parse($item->abonnement_debut)->format('d/m/Y') : '-' }}"
                              data-fin="{{ $item->abonnement_fin ? \Carbon\Carbon::parse($item->abonnement_fin)->format('d/m/Y') : '-' }}"
                              data-statut="{{ $item->statut_abonnement ?? '-' }}">
                        <i class="bx bx-info-circle me-1"></i> {{ __('pages.ent_menu_details') }}
                      </button>
                    </li>

                  </ul>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <br>

  {{-- ===== Tableau annexes ===== --}}
  <div class="card shadow-sm">
    <div class="card-header">
      <h5 class="mb-0"><i class="bx bx-sitemap me-1"></i> {{ __('pages.ent_agencies_title') }}</h5>
    </div>
    <div class="table-responsive">
      <table id="tableAnnexes" class="table table-hover align-middle" style="width:100%">
        <thead class="table-light">
          <tr>
            <th>{{ __('pages.ent_th_company_agency') }}</th>
            <th>{{ __('pages.ent_th_status') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach($dataannexe as $item)
            <tr>
              <td>{{ get_status_entreprise($item->iddirection_ref, $item->idannexes) }}</td>
              <td>
                @if(empty($item->blocage_annexe))
                  <span class="badge rounded-pill bg-success">{{ __('pages.ent_badge_active') }}</span>
                @else
                  <span class="badge rounded-pill bg-danger">{{ __('pages.ent_badge_disabled') }}</span>
                @endif
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

</div>

{{-- ===== Modal Changer de plan ===== --}}
<div class="modal fade" id="modalChangePlan" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bx bx-transfer me-1"></i> {{ __('pages.ent_modal_change_plan') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-3">{{ __('pages.ent_cp_company') }} <strong id="cpNomEntreprise"></strong></p>
        <input type="hidden" id="cpIdDirection">
        <div class="mb-3">
          <label class="form-label">{{ __('pages.ent_cp_new_plan') }}</label>
          <select class="form-select" id="cpIdPlan">
            @foreach($plans as $plan)
              <option value="{{ $plan->idplan }}">{{ $plan->nom }} — {{ number_format($plan->prix_annuel, 0, ',', '.') }} {{ get_symbole_devise('XOF') }}/an</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">{{ __('pages.ent_cp_duration') }}</label>
          <div class="input-group">
            <input type="number" class="form-control" id="cpDuree" value="12" min="1" max="60">
            <span class="input-group-text">{{ __('pages.recu_th_month') }}</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.btn_cancel') }}</button>
        <button type="button" class="btn btn-primary" id="btnSaveChangePlan">
          <i class="bx bx-save me-1"></i> {{ __('common.btn_save') }}
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ===== Modal Renouveler ===== --}}
<div class="modal fade" id="modalRenouveler" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bx bx-refresh me-1"></i> {{ __('pages.ent_modal_renew') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-1">{{ __('pages.ent_rn_company') }} <strong id="rnNomEntreprise"></strong></p>
        <p class="text-muted mb-3">{{ __('pages.ent_rn_current_end') }} <strong id="rnFinActuelle"></strong></p>
        <input type="hidden" id="rnIdDirection">
        <div class="mb-3">
          <label class="form-label">{{ __('pages.ent_rn_extra_duration') }}</label>
          <div class="input-group">
            <input type="number" class="form-control" id="rnDuree" value="12" min="1" max="60">
            <span class="input-group-text">{{ __('pages.recu_th_month') }}</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.btn_cancel') }}</button>
        <button type="button" class="btn btn-success" id="btnSaveRenouveler">
          <i class="bx bx-refresh me-1"></i> {{ __('pages.ent_rn_btn') }}
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ===== Modal Détails ===== --}}
<div class="modal fade" id="modalDetails" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="bx bx-info-circle me-1"></i> {{ __('pages.ent_modal_details') }} <span id="dtNom"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="card border-0 bg-light h-100">
              <div class="card-body">
                <h6 class="text-muted text-uppercase small mb-3">{{ __('pages.ent_dt_info') }}</h6>
                <table class="table table-sm table-borderless mb-0">
                  <tr><td class="text-muted">{{ __('pages.ent_dt_admin') }}</td><td><strong id="dtAdmin"></strong></td></tr>
                  <tr><td class="text-muted">{{ __('pages.ent_dt_email_admin') }}</td><td id="dtEmailAdmin"></td></tr>
                  <tr><td class="text-muted">{{ __('pages.ent_dt_email_dir') }}</td><td id="dtEmailDir"></td></tr>
                  <tr><td class="text-muted">{{ __('pages.ent_dt_phone') }}</td><td id="dtTel"></td></tr>
                  <tr><td class="text-muted">{{ __('pages.ent_dt_head_office') }}</td><td id="dtSiege"></td></tr>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card border-0 bg-light h-100">
              <div class="card-body">
                <h6 class="text-muted text-uppercase small mb-3">{{ __('pages.ent_dt_subscription') }}</h6>
                <table class="table table-sm table-borderless mb-0">
                  <tr><td class="text-muted">{{ __('pages.ent_dt_plan') }}</td><td><strong id="dtPlan"></strong></td></tr>
                  <tr><td class="text-muted">{{ __('pages.ent_dt_price') }}</td><td id="dtPrix"></td></tr>
                  <tr><td class="text-muted">{{ __('pages.ent_dt_start') }}</td><td id="dtDebut"></td></tr>
                  <tr><td class="text-muted">{{ __('pages.ent_dt_end') }}</td><td id="dtFin"></td></tr>
                  <tr><td class="text-muted">{{ __('pages.ent_dt_status') }}</td><td id="dtStatut"></td></tr>
                </table>
                <hr>
                <h6 class="text-muted text-uppercase small mb-2">{{ __('pages.ent_dt_usage') }}</h6>
                <table class="table table-sm table-borderless mb-0">
                  <tr><td class="text-muted">{{ __('pages.ent_dt_houses') }}</td><td><strong id="dtMaisons"></strong></td></tr>
                  <tr><td class="text-muted">{{ __('pages.ent_dt_agencies') }}</td><td><strong id="dtAgences"></strong></td></tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
      </div>
    </div>
  </div>
</div>

<script>
var ENT_I18N = {
    blockTitle:    '{{ __('pages.ent_swal_block_title') }}',
    blockBtn:      '{{ __('pages.ent_swal_block_btn') }}',
    validateTitle: '{{ __('pages.ent_swal_validate_title') }}',
    validateBtn:   '{{ __('pages.ent_swal_validate_btn') }}',
    cancel:        '{{ __('common.btn_cancel') }}',
    success:       '{{ __('common.swal_success') }}',
    error:         '{{ __('common.swal_error') }}',
    ok:            '{{ __('pages.ent_swal_ok') }}',
    networkError:  '{{ __('pages.ent_swal_network_error') }}',
    inProgress:    '<span class="spinner-border spinner-border-sm me-1"></span>{{ __('pages.ent_in_progress') }}',
    btnSave:       '<i class="bx bx-save me-1"></i> {{ __('common.btn_save') }}',
    btnRenew:      '<i class="bx bx-refresh me-1"></i> {{ __('pages.ent_rn_btn') }}',
    none:          '{{ __('pages.ent_none') }}',
};
// ── Scripts inline (pas via push/stack) pour que le SPA puisse les ré-exécuter
// ── Événements nommés (.ent) + .off() avant .on() → pas de double-binding

function closeDtMenus() {
  $('.dt-menu-open').each(function () {
    $(this).removeClass('dt-menu-open show')
           .css({ display: '', position: '', top: '', left: '', zIndex: '' });
  });
  $('.dt-action-toggle').attr('aria-expanded', 'false');
}

// Clic sur le bouton engrenage
$(document).off('click.ent', '.dt-action-toggle')
           .on('click.ent', '.dt-action-toggle', function (e) {
  e.stopPropagation();
  var $btn   = $(this);
  var $menu  = $btn.parent('.dropdown').children('.dropdown-menu');
  var isOpen = $menu.hasClass('dt-menu-open');

  closeDtMenus();

  if (!isOpen) {
    $btn.attr('aria-expanded', 'true');
    $menu.addClass('dt-menu-open show').css({ display: 'block' });

    var r     = $btn[0].getBoundingClientRect();
    var menuW = $menu.outerWidth() || 160;
    $menu.css({
      position : 'fixed',
      zIndex   : 9999,
      top      : (r.bottom + 2) + 'px',
      left     : Math.max(4, r.right - menuW) + 'px'
    });
  }
});

// Fermer en cliquant ailleurs
$(document).off('click.entmenu').on('click.entmenu', function (e) {
  if (!$(e.target).closest('.dt-action-toggle, .dt-menu-open').length) {
    closeDtMenus();
  }
});

// Fermer sur scroll / resize
$(window).off('scroll.dtmenu resize.dtmenu').on('scroll.dtmenu resize.dtmenu', closeDtMenus);

// ── Bloquer / Débloquer avec SweetAlert2 ────────────────────
$(document).off('click.ent', '.btn-toggle-block')
           .on('click.ent', '.btn-toggle-block', function (e) {
  e.stopPropagation();
  closeDtMenus();

  var action = this.getAttribute('data-action');
  var nom    = this.getAttribute('data-nom');
  var id     = this.getAttribute('data-id');
  var url    = "{{ route('blocage', ['id' => '__ID__']) }}".replace('__ID__', id);

  if (action === 'bloquer') {
    Swal.fire({
      title: ENT_I18N.blockTitle,
      html: '{{ __('pages.ent_swal_block_html', ['%s' => '']) }}'.replace('%s', nom),
      icon: 'warning',
      iconColor: '#dc3545',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: ENT_I18N.blockBtn,
      cancelButtonText: ENT_I18N.cancel,
      reverseButtons: true,
      focusCancel: true
    }).then(function (result) {
      if (result.isConfirmed) window.location.href = url;
    });
  } else {
    Swal.fire({
      title: ENT_I18N.validateTitle,
      html: '{{ __('pages.ent_swal_validate_html', ['%s' => '']) }}'.replace('%s', nom),
      icon: 'question',
      iconColor: '#198754',
      showCancelButton: true,
      confirmButtonColor: '#198754',
      cancelButtonColor: '#6c757d',
      confirmButtonText: ENT_I18N.validateBtn,
      cancelButtonText: ENT_I18N.cancel,
      reverseButtons: true,
      focusCancel: true
    }).then(function (result) {
      if (result.isConfirmed) window.location.href = url;
    });
  }
});

// ── Modal Changer de plan ───────────────────────────────────
$(document).off('click.ent', '.btn-change-plan')
           .on('click.ent', '.btn-change-plan', function () {
  closeDtMenus();
  var btn = $(this);
  $('#cpIdDirection').val(btn.data('id'));
  $('#cpNomEntreprise').text(btn.data('nom'));
  var planActuel = btn.data('plan');
  if (planActuel) $('#cpIdPlan').val(planActuel);
  new bootstrap.Modal('#modalChangePlan').show();
});

$(document).off('click.ent', '#btnSaveChangePlan')
           .on('click.ent', '#btnSaveChangePlan', function () {
  var btn = $(this);
  btn.prop('disabled', true).html(ENT_I18N.inProgress);
  $.post("{{ route('entreprise.change-plan') }}", {
    _token: '{{ csrf_token() }}',
    iddirection: $('#cpIdDirection').val(),
    idplan: $('#cpIdPlan').val(),
    duree: $('#cpDuree').val()
  })
  .done(function (res) {
    bootstrap.Modal.getInstance('#modalChangePlan').hide();
    Swal.fire({ icon: res.status ? 'success' : 'error', title: res.status ? ENT_I18N.success : ENT_I18N.error, text: res.message, confirmButtonText: ENT_I18N.ok })
        .then(function () { if (res.status) location.reload(); });
  })
  .fail(function () { Swal.fire(ENT_I18N.error, ENT_I18N.networkError, 'error'); })
  .always(function () { btn.prop('disabled', false).html(ENT_I18N.btnSave); });
});

// ── Modal Renouveler ────────────────────────────────────────
$(document).off('click.ent', '.btn-renouveler')
           .on('click.ent', '.btn-renouveler', function () {
  closeDtMenus();
  var btn = $(this);
  $('#rnIdDirection').val(btn.data('id'));
  $('#rnNomEntreprise').text(btn.data('nom'));
  $('#rnFinActuelle').text(btn.data('fin') || ENT_I18N.none);
  new bootstrap.Modal('#modalRenouveler').show();
});

$(document).off('click.ent', '#btnSaveRenouveler')
           .on('click.ent', '#btnSaveRenouveler', function () {
  var btn = $(this);
  btn.prop('disabled', true).html(ENT_I18N.inProgress);
  $.post("{{ route('entreprise.renouveler') }}", {
    _token: '{{ csrf_token() }}',
    iddirection: $('#rnIdDirection').val(),
    duree: $('#rnDuree').val()
  })
  .done(function (res) {
    bootstrap.Modal.getInstance('#modalRenouveler').hide();
    Swal.fire({ icon: res.status ? 'success' : 'error', title: res.status ? ENT_I18N.success : ENT_I18N.error, text: res.message, confirmButtonText: ENT_I18N.ok })
        .then(function () { if (res.status) location.reload(); });
  })
  .fail(function () { Swal.fire(ENT_I18N.error, ENT_I18N.networkError, 'error'); })
  .always(function () { btn.prop('disabled', false).html(ENT_I18N.btnRenew); });
});

// ── Modal Détails ───────────────────────────────────────────
$(document).off('click.ent', '.btn-details')
           .on('click.ent', '.btn-details', function () {
  closeDtMenus();
  var b = $(this);
  $('#dtNom').text(b.data('nom'));       $('#dtAdmin').text(b.data('admin'));
  $('#dtEmailAdmin').text(b.data('email-admin')); $('#dtEmailDir').text(b.data('email-dir'));
  $('#dtTel').text(b.data('tel'));       $('#dtSiege').text(b.data('siege'));
  $('#dtPlan').text(b.data('plan'));     $('#dtPrix').text(b.data('prix'));
  $('#dtDebut').text(b.data('debut'));   $('#dtFin').text(b.data('fin'));
  $('#dtStatut').text(b.data('statut'));
  $('#dtMaisons').text(b.data('maisons')); $('#dtAgences').text(b.data('agences'));
  new bootstrap.Modal('#modalDetails').show();
});

// ── DataTables (en dernier — son erreur éventuelle n'affecte plus rien)
var DT_FR = {
  processing:   "Traitement...",
  search:       "Rechercher :",
  lengthMenu:   "Afficher _MENU_ éléments",
  info:         "Affichage de _START_ à _END_ sur _TOTAL_ éléments",
  infoEmpty:    "Affichage de 0 à 0 sur 0 élément",
  infoFiltered: "(filtré depuis _MAX_ éléments au total)",
  infoPostFix:  "",
  loadingRecords:"Chargement...",
  zeroRecords:  "Aucun élément correspondant trouvé",
  emptyTable:   "Aucune donnée disponible",
  paginate: { first:"Premier", previous:"Précédent", next:"Suivant", last:"Dernier" },
  aria: { sortAscending:" : activer pour trier la colonne par ordre croissant", sortDescending:" : activer pour trier la colonne par ordre décroissant" }
};

$(document).ready(function () {
  if (typeof $.fn.DataTable === 'undefined') return;

  var tableE = $('#tableEntreprises').DataTable({
    pageLength: 25,
    language: DT_FR,
    columnDefs: [{ orderable: false, targets: -1 }],
    drawCallback: function () { closeDtMenus(); }
  });

  $('#tableAnnexes').DataTable({
    pageLength: 10,
    language: DT_FR
  });

  var currentFilter = 'all';
  $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
    if (settings.nTable.id !== 'tableEntreprises') return true;
    if (currentFilter === 'all') return true;
    var nTr = settings.aoData[dataIndex] && settings.aoData[dataIndex].nTr;
    return nTr && $(nTr).data('filter') === currentFilter;
  });

  $('.filter-btn').on('click', function () {
    $('.filter-btn').removeClass('active');
    $(this).addClass('active');
    currentFilter = $(this).data('filter');
    tableE.draw();
  });
});
</script>

@endsection
