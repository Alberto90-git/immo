@extends('layouts.template')

@section('content')
@section('title')
<title>Gestion entreprise</title>
@endsection

<div class="container-xxl flex-grow-1 container-p-y">

  {{-- ===== Cartes de statistiques ===== --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="card text-center border-0 shadow-sm h-100">
        <div class="card-body py-3">
          <div class="fs-1 fw-bold text-primary">{{ $stats['total'] }}</div>
          <div class="text-muted small">Total entreprises</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card text-center border-0 shadow-sm h-100">
        <div class="card-body py-3">
          <div class="fs-1 fw-bold text-success">{{ $stats['actifs'] }}</div>
          <div class="text-muted small">Actives</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card text-center border-0 shadow-sm h-100">
        <div class="card-body py-3">
          <div class="fs-1 fw-bold text-warning">{{ $stats['en_attente'] }}</div>
          <div class="text-muted small">En attente</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="card text-center border-0 shadow-sm h-100">
        <div class="card-body py-3">
          <div class="fs-1 fw-bold text-danger">{{ $stats['expires'] }}</div>
          <div class="text-muted small">Abonnements expirés</div>
        </div>
      </div>
    </div>
  </div>

  {{-- ===== Tableau principal entreprises ===== --}}
  <div class="card shadow-sm">
    <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
      <h5 class="mb-0"><i class="bx bx-buildings me-1"></i> Liste des entreprises</h5>
      <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-sm btn-outline-secondary filter-btn active" data-filter="all">Tous</button>
        <button class="btn btn-sm btn-outline-success filter-btn" data-filter="actif">Actifs</button>
        <button class="btn btn-sm btn-outline-warning filter-btn" data-filter="attente">En attente</button>
        <button class="btn btn-sm btn-outline-danger filter-btn" data-filter="expire">Expirés</button>
      </div>
    </div>

    <div class="table-responsive">
      <table id="tableEntreprises" class="table table-hover align-middle" style="width:100%">
        <thead class="table-light">
          <tr>
            <th>Entreprise</th>
            <th>Admin</th>
            <th>Plan</th>
            <th>Maisons</th>
            <th>Agences</th>
            <th>Fin abonnement</th>
            <th>Jours restants</th>
            <th>Statut</th>
            <th class="text-center">Actions</th>
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
                <span class="badge bg-info">{{ $item->plan_nom ?? 'Aucun' }}</span>
                @if($item->plan_prix)
                  <div class="small text-muted">{{ number_format($item->plan_prix, 0, ',', '.') }} XOF/an</div>
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
                  <span class="badge bg-danger">Expiré</span>
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
                  <span class="badge rounded-pill bg-danger">En attente</span>
                @elseif($expired)
                  <span class="badge rounded-pill bg-warning text-dark">Expiré</span>
                @else
                  <span class="badge rounded-pill bg-success">Actif</span>
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
                                data-id="{{ $item->iddirection }}"
                                data-action="debloquer"
                                data-nom="{{ $item->designation }}">
                          <i class="bx bx-check-circle me-1"></i> Valider
                        </button>
                      </li>
                    @else
                      <li>
                        <button class="dropdown-item text-danger btn-toggle-block"
                                data-id="{{ $item->iddirection }}"
                                data-action="bloquer"
                                data-nom="{{ $item->designation }}">
                          <i class="bx bx-lock me-1"></i> Bloquer
                        </button>
                      </li>
                    @endif

                    <li><hr class="dropdown-divider"></li>

                    {{-- Changer de plan --}}
                    <li>
                      <button class="dropdown-item btn-change-plan"
                              data-id="{{ $item->iddirection }}"
                              data-nom="{{ $item->designation }}"
                              data-plan="{{ $item->idplan ?? '' }}">
                        <i class="bx bx-transfer me-1"></i> Changer de plan
                      </button>
                    </li>

                    {{-- Renouveler --}}
                    <li>
                      <button class="dropdown-item btn-renouveler"
                              data-id="{{ $item->iddirection }}"
                              data-nom="{{ $item->designation }}"
                              data-fin="{{ $item->abonnement_fin ?? '' }}">
                        <i class="bx bx-refresh me-1"></i> Renouveler
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
                              data-prix="{{ $item->plan_prix ? number_format($item->plan_prix, 0, ',', '.') . ' XOF' : 'Gratuit' }}"
                              data-maisons="{{ $item->nb_maisons }}"
                              data-agences="{{ $item->nb_agences }}"
                              data-debut="{{ $item->abonnement_debut ? \Carbon\Carbon::parse($item->abonnement_debut)->format('d/m/Y') : '-' }}"
                              data-fin="{{ $item->abonnement_fin ? \Carbon\Carbon::parse($item->abonnement_fin)->format('d/m/Y') : '-' }}"
                              data-statut="{{ $item->statut_abonnement ?? '-' }}">
                        <i class="bx bx-info-circle me-1"></i> Détails
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
      <h5 class="mb-0"><i class="bx bx-sitemap me-1"></i> Liste des agences</h5>
    </div>
    <div class="table-responsive">
      <table id="tableAnnexes" class="table table-hover align-middle" style="width:100%">
        <thead class="table-light">
          <tr>
            <th>Entreprise / Agence</th>
            <th>Statut</th>
          </tr>
        </thead>
        <tbody>
          @foreach($dataannexe as $item)
            <tr>
              <td>{{ get_status_entreprise($item->iddirection_ref, $item->idannexes) }}</td>
              <td>
                @if(empty($item->blocage_annexe))
                  <span class="badge rounded-pill bg-success">Actif</span>
                @else
                  <span class="badge rounded-pill bg-danger">Désactivé</span>
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
        <h5 class="modal-title"><i class="bx bx-transfer me-1"></i> Changer de plan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-3">Entreprise : <strong id="cpNomEntreprise"></strong></p>
        <input type="hidden" id="cpIdDirection">
        <div class="mb-3">
          <label class="form-label">Nouveau plan</label>
          <select class="form-select" id="cpIdPlan">
            @foreach($plans as $plan)
              <option value="{{ $plan->idplan }}">{{ $plan->nom }} — {{ number_format($plan->prix_annuel, 0, ',', '.') }} XOF/an</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Durée (mois)</label>
          <div class="input-group">
            <input type="number" class="form-control" id="cpDuree" value="12" min="1" max="60">
            <span class="input-group-text">mois</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="btnSaveChangePlan">
          <i class="bx bx-save me-1"></i> Enregistrer
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
        <h5 class="modal-title"><i class="bx bx-refresh me-1"></i> Renouveler l'abonnement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="text-muted mb-1">Entreprise : <strong id="rnNomEntreprise"></strong></p>
        <p class="text-muted mb-3">Fin actuelle : <strong id="rnFinActuelle"></strong></p>
        <input type="hidden" id="rnIdDirection">
        <div class="mb-3">
          <label class="form-label">Durée supplémentaire (mois)</label>
          <div class="input-group">
            <input type="number" class="form-control" id="rnDuree" value="12" min="1" max="60">
            <span class="input-group-text">mois</span>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-success" id="btnSaveRenouveler">
          <i class="bx bx-refresh me-1"></i> Renouveler
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
        <h5 class="modal-title"><i class="bx bx-info-circle me-1"></i> Détails — <span id="dtNom"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <div class="card border-0 bg-light h-100">
              <div class="card-body">
                <h6 class="text-muted text-uppercase small mb-3">Informations</h6>
                <table class="table table-sm table-borderless mb-0">
                  <tr><td class="text-muted">Admin</td><td><strong id="dtAdmin"></strong></td></tr>
                  <tr><td class="text-muted">Email admin</td><td id="dtEmailAdmin"></td></tr>
                  <tr><td class="text-muted">Email direction</td><td id="dtEmailDir"></td></tr>
                  <tr><td class="text-muted">Téléphone</td><td id="dtTel"></td></tr>
                  <tr><td class="text-muted">Siège social</td><td id="dtSiege"></td></tr>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card border-0 bg-light h-100">
              <div class="card-body">
                <h6 class="text-muted text-uppercase small mb-3">Abonnement</h6>
                <table class="table table-sm table-borderless mb-0">
                  <tr><td class="text-muted">Plan</td><td><strong id="dtPlan"></strong></td></tr>
                  <tr><td class="text-muted">Prix</td><td id="dtPrix"></td></tr>
                  <tr><td class="text-muted">Début</td><td id="dtDebut"></td></tr>
                  <tr><td class="text-muted">Fin</td><td id="dtFin"></td></tr>
                  <tr><td class="text-muted">Statut</td><td id="dtStatut"></td></tr>
                </table>
                <hr>
                <h6 class="text-muted text-uppercase small mb-2">Utilisation</h6>
                <table class="table table-sm table-borderless mb-0">
                  <tr><td class="text-muted">Maisons</td><td><strong id="dtMaisons"></strong></td></tr>
                  <tr><td class="text-muted">Agences</td><td><strong id="dtAgences"></strong></td></tr>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<script>
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
      title: 'Bloquer cette entreprise ?',
      html: 'L\'entreprise <strong>' + nom + '</strong> sera suspendue.<br><small class="text-muted">Ses utilisateurs ne pourront plus se connecter.</small>',
      icon: 'warning',
      iconColor: '#dc3545',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Oui, bloquer',
      cancelButtonText: 'Annuler',
      reverseButtons: true,
      focusCancel: true
    }).then(function (result) {
      if (result.isConfirmed) window.location.href = url;
    });
  } else {
    Swal.fire({
      title: 'Valider cette entreprise ?',
      html: 'L\'entreprise <strong>' + nom + '</strong> sera réactivée.<br><small class="text-muted">Ses utilisateurs pourront à nouveau se connecter.</small>',
      icon: 'question',
      iconColor: '#198754',
      showCancelButton: true,
      confirmButtonColor: '#198754',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Oui, valider',
      cancelButtonText: 'Annuler',
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
  btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>En cours…');
  $.post("{{ route('entreprise.change-plan') }}", {
    _token: '{{ csrf_token() }}',
    iddirection: $('#cpIdDirection').val(),
    idplan: $('#cpIdPlan').val(),
    duree: $('#cpDuree').val()
  })
  .done(function (res) {
    bootstrap.Modal.getInstance('#modalChangePlan').hide();
    Swal.fire({ icon: res.status ? 'success' : 'error', title: res.status ? 'Succès' : 'Erreur', text: res.message, confirmButtonText: 'OK' })
        .then(function () { if (res.status) location.reload(); });
  })
  .fail(function () { Swal.fire('Erreur', 'Une erreur réseau est survenue.', 'error'); })
  .always(function () { btn.prop('disabled', false).html('<i class="bx bx-save me-1"></i> Enregistrer'); });
});

// ── Modal Renouveler ────────────────────────────────────────
$(document).off('click.ent', '.btn-renouveler')
           .on('click.ent', '.btn-renouveler', function () {
  closeDtMenus();
  var btn = $(this);
  $('#rnIdDirection').val(btn.data('id'));
  $('#rnNomEntreprise').text(btn.data('nom'));
  $('#rnFinActuelle').text(btn.data('fin') || 'Aucune');
  new bootstrap.Modal('#modalRenouveler').show();
});

$(document).off('click.ent', '#btnSaveRenouveler')
           .on('click.ent', '#btnSaveRenouveler', function () {
  var btn = $(this);
  btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>En cours…');
  $.post("{{ route('entreprise.renouveler') }}", {
    _token: '{{ csrf_token() }}',
    iddirection: $('#rnIdDirection').val(),
    duree: $('#rnDuree').val()
  })
  .done(function (res) {
    bootstrap.Modal.getInstance('#modalRenouveler').hide();
    Swal.fire({ icon: res.status ? 'success' : 'error', title: res.status ? 'Succès' : 'Erreur', text: res.message, confirmButtonText: 'OK' })
        .then(function () { if (res.status) location.reload(); });
  })
  .fail(function () { Swal.fire('Erreur', 'Une erreur réseau est survenue.', 'error'); })
  .always(function () { btn.prop('disabled', false).html('<i class="bx bx-refresh me-1"></i> Renouveler'); });
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
$(document).ready(function () {
  if (typeof $.fn.DataTable === 'undefined') return;

  var tableE = $('#tableEntreprises').DataTable({
    pageLength: 25,
    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' },
    columnDefs: [{ orderable: false, targets: -1 }],
    drawCallback: function () { closeDtMenus(); }
  });

  $('#tableAnnexes').DataTable({
    pageLength: 10,
    language: { url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/fr-FR.json' }
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
