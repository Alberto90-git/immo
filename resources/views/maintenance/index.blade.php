@extends('layouts.template')

@section('title')<title>Interventions – Lokativ</title>@endsection

@push('styles')
<style>.iti { width: 100%; }</style>
@endpush

@section('content')
@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">

  {{-- ══ VUE LISTE ══════════════════════════════════════════════════════════ --}}
  <div id="viewListe">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold mb-0"><span class="text-muted fw-light">{{ __('ui.common.home') }} /</span> {{ __('ui.maintenance.title') }}</h4>
      <div class="d-flex gap-2">
        @can('gestion-prestataire')
          <button type="button" class="btn btn-outline-secondary" id="btnPrestataires">
            <i class="bx bx-group me-1"></i> {{ __('ui.maintenance.prestataires_btn') }}
          </button>
        @endcan
        @can('ajoute-maintenance')
          <button type="button" class="btn btn-primary" id="btnNouveauTicket">
            <i class="bx bx-plus me-1"></i> {{ __('ui.maintenance.new_ticket') }}
          </button>
        @endcan
      </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center py-3">
          <div class="fs-4 fw-bold text-body stat-total">{{ $stats['total'] }}</div>
          <div class="small text-muted">{{ __('ui.maintenance.stat_total') }}</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center py-3">
          <div class="fs-4 fw-bold text-secondary stat-nouveau">{{ $stats['nouveau'] }}</div>
          <div class="small text-muted">{{ __('ui.maintenance.stat_new') }}</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center py-3">
          <div class="fs-4 fw-bold text-warning">{{ $stats['en_cours'] }}</div>
          <div class="small text-muted">{{ __('ui.maintenance.stat_in_progress') }}</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center py-3">
          <div class="fs-4 fw-bold text-danger">{{ $stats['urgents'] }}</div>
          <div class="small text-muted">{{ __('ui.maintenance.stat_urgent') }}</div>
        </div>
      </div>
    </div>

    {{-- Filtres --}}
    <div class="mb-3 d-flex gap-2 flex-wrap align-items-center">
      <div class="d-flex gap-1">
        <button class="btn btn-sm btn-outline-secondary filtre-statut active" data-val="tous">{{ __('ui.maintenance.filter_all') }}</button>
        <button class="btn btn-sm btn-outline-secondary filtre-statut" data-val="nouveau">{{ __('ui.maintenance.filter_new') }}</button>
        <button class="btn btn-sm btn-outline-warning filtre-statut" data-val="en_cours">{{ __('ui.maintenance.filter_in_progress') }}</button>
        <button class="btn btn-sm btn-outline-info filtre-statut" data-val="resolu">{{ __('ui.maintenance.filter_resolved') }}</button>
        <button class="btn btn-sm btn-outline-success filtre-statut" data-val="cloture">{{ __('ui.maintenance.filter_closed') }}</button>
      </div>
      <div class="ms-auto d-flex gap-1">
        <button class="btn btn-sm btn-outline-secondary filtre-priorite active" data-val="tous">{{ __('ui.maintenance.filter_all_prio') }}</button>
        <button class="btn btn-sm btn-outline-danger filtre-priorite" data-val="urgente">{{ __('ui.maintenance.filter_urgent') }}</button>
        <button class="btn btn-sm btn-outline-warning filtre-priorite" data-val="haute">{{ __('ui.maintenance.filter_high') }}</button>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="tableTickets">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>{{ __('ui.maintenance.form_title') }}</th>
                <th>{{ __('ui.maintenance.col_category') }}</th>
                <th>{{ __('ui.maintenance.col_property') }}</th>
                <th>{{ __('ui.maintenance.col_priority') }}</th>
                <th>{{ __('ui.maintenance.col_status') }}</th>
                <th>{{ __('ui.maintenance.col_provider') }}</th>
                <th>{{ __('ui.maintenance.col_date') }}</th>
                <th class="text-center">{{ __('ui.maintenance.col_actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($tickets as $ticket)
                <tr class="ligne-ticket"
                    data-statut="{{ $ticket->statut }}"
                    data-priorite="{{ $ticket->priorite }}">
                  <td class="text-muted small">#{{ $ticket->id }}</td>
                  <td>
                    <div class="fw-semibold">{{ $ticket->titre }}</div>
                    @if($ticket->description)
                      <div class="small text-muted text-truncate" style="max-width:200px">{{ $ticket->description }}</div>
                    @endif
                  </td>
                  <td>
                    <i class="{{ $ticket->categorie_icon }} me-1 text-muted"></i>
                    <span class="small">{{ $ticket->categorie_label }}</span>
                  </td>
                  <td class="small text-muted">
                    {{ $ticket->maison->nom_maison ?? '–' }}
                    @if($ticket->chambre) <br><span class="text-body">Ch. {{ $ticket->chambre->numero_chambre }}</span>@endif
                  </td>
                  <td>{!! $ticket->priorite_badge !!}</td>
                  <td>{!! $ticket->statut_badge !!}</td>
                  <td class="small">{{ $ticket->prestataire->nom ?? '–' }}</td>
                  <td class="small text-muted">{{ $ticket->date_ouverture->format('d/m/Y') }}</td>
                  <td class="text-center">
                    <div class="d-flex gap-1 justify-content-center">
                      @can('Consulter-maintenance')
                        <button type="button" class="btn btn-sm btn-icon btn-outline-info btn-voir-ticket"
                                data-href="{{ route('maintenance.show', $ticket->id) }}" title="Voir">
                          <i class="bx bx-show"></i>
                        </button>
                      @endcan
                      @can('delete-maintenance')
                        @if($ticket->statut !== 'cloture')
                          <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-supprimer"
                                  data-id="{{ $ticket->id }}" data-titre="{{ $ticket->titre }}" title="Supprimer">
                            <i class="bx bx-trash"></i>
                          </button>
                        @endif
                      @endcan
                    </div>
                  </td>
                </tr>
              @empty
                <tr id="ligneVide">
                  <td colspan="9" class="text-center text-muted py-5">
                    <i class="bx bx-wrench fs-3 d-block mb-2"></i>
                    {{ __('ui.maintenance.empty') }}
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>{{-- fin #viewListe --}}


  {{-- ══ VUE FORMULAIRE ════════════════════════════════════════════════════ --}}
  @can('ajoute-maintenance')
  <div id="viewCreate" style="display:none;">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold mb-0"><span class="text-muted fw-light">{{ __('ui.maintenance.title') }} /</span> {{ __('ui.maintenance.new_ticket') }}</h4>
      <button type="button" class="btn btn-outline-secondary" id="btnRetourListe">
        <i class="bx bx-arrow-back me-1"></i> {{ __('ui.common.back') }}
      </button>
    </div>

    <div id="alertTicketErrors" class="alert alert-danger" style="display:none;"></div>

    <form id="formTicket">
      @csrf

      <div class="row g-4">
        {{-- Colonne principale --}}
        <div class="col-lg-8">
          <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary">
              <h6 class="mb-0 text-white"><i class="bx bx-info-circle me-2"></i>{{ __('ui.maintenance.info_title') }}</h6>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label fw-semibold">{{ __('ui.maintenance.form_title') }} <span class="text-danger">*</span></label>
                <input type="text" name="titre" class="form-control" placeholder="{{ __('ui.maintenance.form_title_ph') }}" required>
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">{{ __('ui.maintenance.form_category') }} <span class="text-danger">*</span></label>
                  <select name="categorie" class="form-select" required>
                    <option value="">{{ __('ui.common.choose') }}</option>
                    @foreach(App\Prestataire::SPECIALITES as $key => $info)
                      <option value="{{ $key }}">{{ $info['label'] }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">{{ __('ui.maintenance.form_priority') }} <span class="text-danger">*</span></label>
                  <select name="priorite" class="form-select" required>
                    <option value="basse">{{ __('ui.maintenance.prio_low') }}</option>
                    <option value="normale" selected>{{ __('ui.maintenance.prio_normal') }}</option>
                    <option value="haute">{{ __('ui.maintenance.prio_high') }}</option>
                    <option value="urgente">{{ __('ui.maintenance.prio_urgent') }}</option>
                  </select>
                </div>
              </div>
              <div class="mt-3">
                <label class="form-label fw-semibold">{{ __('ui.maintenance.form_description') }}</label>
                <textarea name="description" class="form-control" rows="3" placeholder="{{ __('ui.maintenance.form_desc_ph') }}"></textarea>
              </div>
            </div>
          </div>
        </div>

        {{-- Colonne latérale --}}
        <div class="col-lg-4">
          <div class="card shadow-sm mb-4">
            <div class="card-header">
              <h6 class="mb-0"><i class="bx bx-home me-2"></i>{{ __('ui.maintenance.form_housing') }}</h6>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label fw-semibold">{{ __('ui.maintenance.form_property') }} <span class="text-danger">*</span></label>
                <select name="maison_id" id="selectMaison" class="form-select" required>
                  <option value="">{{ __('ui.common.choose') }}</option>
                  @foreach($maisons as $maison)
                    <option value="{{ $maison->id }}">{{ $maison->nom_maison }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">{{ __('ui.maintenance.form_room') }}</label>
                <select name="chambre_id" id="selectChambre" class="form-select">
                  <option value="">{{ __('ui.maintenance.form_room_select') }}</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">{{ __('ui.maintenance.form_tenant') }}</label>
                <input type="hidden" name="locataire_id" id="inputLocataireId">
                <div id="locataireInfo" class="form-control bg-light text-muted small" style="min-height:38px; cursor:default;">
                  {{ __('ui.maintenance.form_tenant_sel') }}
                </div>
              </div>
              <div>
                <label class="form-label fw-semibold">{{ __('ui.maintenance.form_date') }} <span class="text-danger">*</span></label>
                <input type="date" name="date_ouverture" class="form-control" value="{{ date('Y-m-d') }}" required>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2 justify-content-end mb-5">
        <button type="button" class="btn btn-outline-secondary" id="btnAnnulerCreate">{{ __('ui.common.cancel') }}</button>
        <button type="button" class="btn btn-primary px-4" id="btnSaveTicket">
          <span id="btnSaveText"><i class="bx bx-save me-1"></i> {{ __('ui.maintenance.new_ticket') }}</span>
          <span id="btnSaveSpinner" class="d-none">
            <span class="spinner-border spinner-border-sm me-1"></span> {{ __('ui.common.saving') }}
          </span>
        </button>
      </div>

    </form>
  </div>
  @endcan

  {{-- ══ VUE PRESTATAIRES ══════════════════════════════════════════════════ --}}
  @can('gestion-prestataire')
  <div id="viewPrestataires" style="display:none;">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold mb-0"><span class="text-muted fw-light">{{ __('ui.maintenance.title') }} /</span> {{ __('ui.maintenance.prest_title') }}</h4>
      <div class="d-flex gap-2">
        <button type="button" class="btn btn-outline-secondary" id="btnRetourListePrest">
          <i class="bx bx-arrow-back me-1"></i> {{ __('ui.common.back') }}
        </button>
        <button type="button" class="btn btn-primary" id="btnNouveauPrestataire">
          <i class="bx bx-plus me-1"></i> {{ __('ui.maintenance.prest_new') }}
        </button>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="tablePrestataires">
            <thead class="table-light">
              <tr>
                <th>{{ __('ui.maintenance.prest_name') }}</th>
                <th>{{ __('ui.maintenance.prest_specialty') }}</th>
                <th>{{ __('ui.maintenance.prest_phone') }}</th>
                <th>Email</th>
                <th>{{ __('ui.edl.property') }}</th>
                <th>{{ __('ui.maintenance.prest_tickets') }}</th>
                <th>{{ __('ui.maintenance.prest_status') }}</th>
                <th class="text-center">{{ __('ui.maintenance.col_actions') }}</th>
              </tr>
            </thead>
            <tbody id="tbodyPrestataires">
              @forelse($prestatairesList as $p)
                <tr data-id="{{ $p->id }}">
                  <td class="fw-semibold">
                    <i class="{{ $p->specialite_icon }} me-1 text-muted"></i>{{ $p->nom }}
                  </td>
                  <td>{!! $p->specialite_badge !!}</td>
                  <td class="small">{{ $p->telephone ?? '–' }}</td>
                  <td class="small">{{ $p->email ?? '–' }}</td>
                  <td class="small text-muted">{{ $p->adresse ?? '–' }}</td>
                  <td class="text-center">
                    @if($p->tickets_actifs > 0)
                      <span class="badge bg-label-warning">{{ $p->tickets_actifs }}</span>
                    @else
                      <span class="text-muted small">0</span>
                    @endif
                  </td>
                  <td>
                    @if($p->actif)
                      <span class="badge bg-label-success">{{ __('ui.maintenance.prest_active') }}</span>
                    @else
                      <span class="badge bg-label-secondary">{{ __('ui.maintenance.prest_inactive') }}</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="d-flex gap-1 justify-content-center">
                      <button type="button" class="btn btn-sm btn-icon btn-outline-primary btn-modifier-prest"
                              data-id="{{ $p->id }}" data-nom="{{ $p->nom }}"
                              data-specialite="{{ $p->specialite }}" data-telephone="{{ $p->telephone }}"
                              data-email="{{ $p->email }}" data-adresse="{{ $p->adresse }}"
                              data-actif="{{ $p->actif ? '1' : '0' }}" title="Modifier">
                        <i class="bx bx-edit"></i>
                      </button>
                      <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-supprimer-prest"
                              data-id="{{ $p->id }}" data-nom="{{ $p->nom }}" title="Supprimer">
                        <i class="bx bx-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr id="ligneVidePrest">
                  <td colspan="8" class="text-center text-muted py-5">
                    <i class="bx bx-group fs-3 d-block mb-2"></i>
                    {{ __('ui.maintenance.prest_empty') }}
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>{{-- fin #viewPrestataires --}}

  {{-- Modal prestataire --}}
  <div class="modal fade" id="modalPrestataire" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title text-white" id="modalPrestataireTitle">{{ __('ui.maintenance.prest_new') }}</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="editPrestId">
          <div class="mb-3">
            <label class="form-label fw-semibold">{{ __('ui.maintenance.prest_name') }} <span class="text-danger">*</span></label>
            <input type="text" id="inputNom" class="form-control" placeholder="{{ __('ui.maintenance.prest_name') }}">
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label fw-semibold">{{ __('ui.maintenance.prest_phone') }}</label>
              <input type="tel" id="inputTelephone" class="form-control">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold">Email</label>
              <input type="email" id="inputEmail" class="form-control" placeholder="exemple@mail.com">
            </div>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">{{ __('ui.maintenance.prest_specialty') }} <span class="text-danger">*</span></label>
            <select id="selectSpecialite" class="form-select">
              <option value="">{{ __('ui.common.choose') }}</option>
              @foreach(App\Prestataire::SPECIALITES as $key => $info)
                <option value="{{ $key }}">{{ $info['label'] }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">{{ __('ui.maintenance.prest_address') }}</label>
            <input type="text" id="inputAdresse" class="form-control" placeholder="{{ __('ui.maintenance.prest_address') }}">
          </div>
          <div id="sectionActif" style="display:none;">
            <div class="form-check form-switch">
              <input class="form-check-input" type="checkbox" id="checkActif" checked>
              <label class="form-check-label" for="checkActif">{{ __('ui.maintenance.prest_actif_label') }}</label>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('ui.common.cancel') }}</button>
          <button type="button" class="btn btn-primary" id="btnSavePrestataire">
            <i class="bx bx-save me-1"></i> {{ __('ui.common.save') }}
          </button>
        </div>
      </div>
    </div>
  </div>
  @endcan

  {{-- ══ VUE DÉTAIL TICKET ═════════════════════════════════════════════════ --}}
  <div id="viewShow" style="display:none;">
    <div id="viewShowContent"></div>
  </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script>
// ── intl-tel-input prestataire (même pattern que propriétaire) ────────────────
if (window._itiPrestataire) { try { window._itiPrestataire.destroy(); } catch(e) {} }
window._itiPrestataire = window.intlTelInput(document.getElementById('inputTelephone'), {
    initialCountry: 'tg',
    preferredCountries: ['tg', 'bj', 'ci', 'gh', 'sn', 'cm', 'ng', 'ml', 'bf', 'ne'],
    separateDialCode: true,
    utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js',
});
var itiPrestataire = window._itiPrestataire;

function getPhone() { try { return itiPrestataire ? (itiPrestataire.getNumber() || null) : null; } catch(e) { return null; } }
function setPhone(n) { try { itiPrestataire.setNumber(n || ''); } catch(e) {} }

function ajaxPrest(url, data) {
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(data),
    }).then(r => r.json());
}
function ouvrirModalPrest() { bootstrap.Modal.getOrCreateInstance(document.getElementById('modalPrestataire')).show(); }
function fermerModalPrest() { bootstrap.Modal.getOrCreateInstance(document.getElementById('modalPrestataire')).hide(); }
</script>
<script>
const _canShow   = {{ auth()->user()->can('Consulter-maintenance') ? 'true' : 'false' }};
const _canDelete = {{ auth()->user()->can('delete-maintenance') ? 'true' : 'false' }};

// ── Injection d'une ligne ticket dans le tableau ──────────────────────────────
function injecterLigneTicket(t) {
    const tbody = document.querySelector('#tableTickets tbody');
    const ligneVide = document.getElementById('ligneVide');
    if (ligneVide) ligneVide.remove();

    const descHtml    = t.description ? `<div class="small text-muted text-truncate" style="max-width:200px">${t.description}</div>` : '';
    const chambreHtml = t.chambre_numero ? `<br><span class="text-body">Ch. ${t.chambre_numero}</span>` : '';

    let actions = '<div class="d-flex gap-1 justify-content-center">';
    if (_canShow)   actions += `<button type="button" class="btn btn-sm btn-icon btn-outline-info btn-voir-ticket" data-href="${t.show_url}" title="Voir"><i class="bx bx-show"></i></button>`;
    if (_canDelete && t.statut !== 'cloture') actions += `<button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-supprimer" data-id="${t.id}" data-titre="${t.titre}" title="Supprimer"><i class="bx bx-trash"></i></button>`;
    actions += '</div>';

    const tr = document.createElement('tr');
    tr.className = 'ligne-ticket';
    tr.dataset.statut   = t.statut;
    tr.dataset.priorite = t.priorite;
    tr.innerHTML = `
        <td class="text-muted small">#${t.id}</td>
        <td><div class="fw-semibold">${t.titre}</div>${descHtml}</td>
        <td><i class="${t.categorie_icon} me-1 text-muted"></i><span class="small">${t.categorie_label}</span></td>
        <td class="small text-muted">${t.maison_nom}${chambreHtml}</td>
        <td>${t.priorite_badge}</td>
        <td>${t.statut_badge}</td>
        <td class="small">–</td>
        <td class="small text-muted">${t.date_ouverture}</td>
        <td class="text-center">${actions}</td>`;
    tbody.insertBefore(tr, tbody.firstChild);

    // Mettre à jour les compteurs stats
    ['stat-total', 'stat-nouveau'].forEach(cls => {
        const el = document.querySelector('.' + cls);
        if (el) el.textContent = parseInt(el.textContent || '0') + 1;
    });
}

// ── Navigation entre vues ─────────────────────────────────────────────────────
function afficherVue(id) {
    ['viewListe', 'viewCreate', 'viewPrestataires', 'viewShow'].forEach(v => {
        const el = document.getElementById(v);
        if (el) el.style.display = (v === id) ? '' : 'none';
    });
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function retourListe() { afficherVue('viewListe'); }

// ── Chargement de la page détail sans rechargement ───────────────────────────
let _currentShowUrl = null;

// Appelée depuis les scripts injectés à la place de location.reload()
function __refreshShowView() {
    if (_currentShowUrl) afficherDetailTicket(_currentShowUrl);
}

async function afficherDetailTicket(url) {
    _currentShowUrl = url;
    afficherVue('viewShow');

    const content = document.getElementById('viewShowContent');
    content.innerHTML = '<div class="d-flex justify-content-center py-5"><div class="spinner-border text-primary"></div></div>';

    try {
        const resp = await fetch(url, { credentials: 'same-origin' });
        if (!resp.ok) throw new Error('HTTP ' + resp.status);
        const html = await resp.text();
        const doc  = new DOMParser().parseFromString(html, 'text/html');
        const main = doc.getElementById('main-content-wrapper');
        if (!main) throw new Error('Contenu non trouvé');

        main.querySelector('.content-backdrop')?.remove();
        content.innerHTML = main.innerHTML;

        // Remplacer history.back() → retourListe() dans les boutons injectés
        content.querySelectorAll('[onclick]').forEach(el => {
            const oc = el.getAttribute('onclick');
            if (oc && (oc.includes('history.back') || oc.includes('history.go'))) {
                el.setAttribute('onclick', 'retourListe()');
            }
        });

        // Exécuter les scripts inline de toute la page (y compris les scripts poussés hors main-content-wrapper)
        // On exclut les scripts externes (src=) et les blocs du layout déjà chargés
        const SKIP_PATTERNS = ['NProgress', 'display_sweet_alerte2', 'Sepatateur_Milliers', 'display_message(', 'validateAndFormatPhone'];
        doc.querySelectorAll('body script:not([src])').forEach(s => {
            const txt = s.textContent.trim();
            if (!txt) return;
            if (SKIP_PATTERNS.some(p => txt.includes(p))) return;
            const ns = document.createElement('script');
            ns.textContent = txt
                .replace(/location\.reload\(\)/g, '__refreshShowView()')
                .replace(/history\.back\(\)/g, 'retourListe()');
            document.body.appendChild(ns);
            document.body.removeChild(ns);
        });
    } catch (e) {
        content.innerHTML = `
            <div class="container-p-y">
                <div class="alert alert-warning">{{ __("ui.maintenance.spa_load_error") }}
                    <a href="${url}" class="alert-link ms-1">{{ __("ui.maintenance.spa_open_full") }}</a>
                </div>
                <button class="btn btn-outline-secondary" onclick="retourListe()">
                    <i class="bx bx-arrow-back me-1"></i> {{ __("ui.maintenance.spa_back") }}
                </button>
            </div>`;
    }
}

document.getElementById('btnNouveauTicket')?.addEventListener('click', () => afficherVue('viewCreate'));
document.getElementById('btnRetourListe')?.addEventListener('click', retourListe);
document.getElementById('btnAnnulerCreate')?.addEventListener('click', retourListe);
document.getElementById('btnPrestataires')?.addEventListener('click', () => afficherVue('viewPrestataires'));
document.getElementById('btnRetourListePrest')?.addEventListener('click', retourListe);

// ── CRUD Prestataires ─────────────────────────────────────────────────────────
document.getElementById('btnNouveauPrestataire')?.addEventListener('click', () => {
    document.getElementById('modalPrestataireTitle').textContent = '{{ __("ui.maintenance.prest_new") }}';
    document.getElementById('editPrestId').value         = '';
    document.getElementById('inputNom').value            = '';
    document.getElementById('selectSpecialite').value    = '';
    setPhone('');
    document.getElementById('inputEmail').value          = '';
    document.getElementById('inputAdresse').value        = '';
    document.getElementById('sectionActif').style.display = 'none';
    ouvrirModalPrest();
});

document.getElementById('tablePrestataires')?.addEventListener('click', function (e) {
    const btnEdit = e.target.closest('.btn-modifier-prest');
    if (btnEdit) {
        document.getElementById('modalPrestataireTitle').textContent = '{{ __("ui.maintenance.prest_edit") }}';
        document.getElementById('editPrestId').value         = btnEdit.dataset.id;
        document.getElementById('inputNom').value            = btnEdit.dataset.nom;
        document.getElementById('selectSpecialite').value    = btnEdit.dataset.specialite;
        const tel = btnEdit.dataset.telephone;
        setPhone(tel && tel !== 'null' ? tel : '');
        document.getElementById('inputEmail').value          = btnEdit.dataset.email !== 'null' ? btnEdit.dataset.email : '';
        document.getElementById('inputAdresse').value        = btnEdit.dataset.adresse !== 'null' ? btnEdit.dataset.adresse : '';
        document.getElementById('checkActif').checked        = btnEdit.dataset.actif === '1';
        document.getElementById('sectionActif').style.display = '';
        ouvrirModalPrest();
        return;
    }

    const btnDel = e.target.closest('.btn-supprimer-prest');
    if (btnDel) {
        Swal.fire({
            title: '{{ __("ui.maintenance.prest_delete_confirm") }}',
            text: '{{ __("ui.maintenance.prest_delete_text", ["nom" => ""]) }}'.replace('', btnDel.dataset.nom),
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', confirmButtonText: '{{ __("ui.common.delete") }}', cancelButtonText: '{{ __("ui.common.cancel") }}',
        }).then(r => {
            if (!r.isConfirmed) return;
            ajaxPrest('{{ route("maintenance.prestataires.destroy") }}', { id: btnDel.dataset.id })
            .then(data => {
                if (data.status) {
                    btnDel.closest('tr').remove();
                    Swal.fire({ icon: 'success', timer: 1200, showConfirmButton: false, title: '{{ __("ui.common.deleted") }}' });
                } else { Swal.fire('{{ __("ui.common.error") }}', data.message, 'error'); }
            });
        });
    }
});

document.getElementById('btnSavePrestataire')?.addEventListener('click', function () {
    const id     = document.getElementById('editPrestId').value;
    const isEdit = !!id;
    const payload = {
        id:         id || undefined,
        nom:        document.getElementById('inputNom').value.trim(),
        specialite: document.getElementById('selectSpecialite').value,
        telephone:  getPhone(),
        email:      document.getElementById('inputEmail').value.trim() || null,
        adresse:    document.getElementById('inputAdresse').value.trim() || null,
        actif:      document.getElementById('checkActif').checked ? 1 : 0,
    };

    if (!payload.nom || !payload.specialite) {
        Swal.fire('{{ __("ui.maintenance.missing_fields") }}', '{{ __("ui.maintenance.missing_fields_text") }}', 'warning');
        return;
    }

    const url = isEdit ? '{{ route("maintenance.prestataires.update") }}' : '{{ route("maintenance.prestataires.store") }}';

    ajaxPrest(url, payload).then(data => {
        if (data.status) {
            fermerModalPrest();
            Swal.fire({ icon: 'success', title: isEdit ? '{{ __("ui.maintenance.modified") }}' : '{{ __("ui.maintenance.added") }}', text: data.message, timer: 1400, showConfirmButton: false });

            const tbody = document.getElementById('tbodyPrestataires');
            const ligneVide = document.getElementById('ligneVidePrest');
            if (ligneVide) ligneVide.remove();

            const p = data.prestataire;
            if (isEdit) {
                const tr = tbody.querySelector(`tr[data-id="${p.id}"]`);
                if (tr) {
                    tr.querySelector('td:nth-child(1)').innerHTML = `<i class="${p.specialite_icon} me-1 text-muted"></i>${p.nom}`;
                    tr.querySelector('td:nth-child(2)').innerHTML = p.specialite_badge;
                    tr.querySelector('td:nth-child(3)').textContent = p.telephone;
                    tr.querySelector('td:nth-child(4)').textContent = p.email;
                    tr.querySelector('td:nth-child(5)').textContent = p.adresse;
                    tr.querySelector('td:nth-child(7)').innerHTML = p.actif
                        ? '<span class="badge bg-label-success">{{ __("ui.maintenance.prest_active") }}</span>'
                        : '<span class="badge bg-label-secondary">{{ __("ui.maintenance.prest_inactive") }}</span>';
                    const btnE = tr.querySelector('.btn-modifier-prest');
                    if (btnE) {
                        btnE.dataset.nom = p.nom; btnE.dataset.specialite = p.specialite;
                        btnE.dataset.telephone = p.telephone; btnE.dataset.email = p.email;
                        btnE.dataset.adresse = p.adresse; btnE.dataset.actif = p.actif ? '1' : '0';
                    }
                }
            } else {
                const tr = document.createElement('tr');
                tr.dataset.id = p.id;
                tr.innerHTML = `
                    <td class="fw-semibold"><i class="${p.specialite_icon} me-1 text-muted"></i>${p.nom}</td>
                    <td>${p.specialite_badge}</td>
                    <td class="small">${p.telephone}</td>
                    <td class="small">${p.email}</td>
                    <td class="small text-muted">${p.adresse}</td>
                    <td class="text-center"><span class="text-muted small">0</span></td>
                    <td><span class="badge bg-label-success">{{ __('ui.maintenance.prest_active') }}</span></td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-sm btn-icon btn-outline-primary btn-modifier-prest"
                                data-id="${p.id}" data-nom="${p.nom}" data-specialite="${p.specialite}"
                                data-telephone="${p.telephone}" data-email="${p.email}" data-adresse="${p.adresse}" data-actif="1" title="Modifier">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-supprimer-prest"
                                data-id="${p.id}" data-nom="${p.nom}" title="Supprimer">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>`;
                tbody.insertBefore(tr, tbody.firstChild);
            }
        } else {
            const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || '{{ __("ui.common.error") }}.'];
            Swal.fire('{{ __("ui.common.error") }}', msgs.join('\n'), 'error');
        }
    }).catch(() => {
        Swal.fire('{{ __("ui.common.error") }}', '{{ __("ui.common.network_error") }}', 'error');
    });
});

// ── Chargement chambres selon maison ─────────────────────────────────────────
document.getElementById('selectMaison')?.addEventListener('change', function () {
    const maisonId = this.value;
    const selChambre  = document.getElementById('selectChambre');
    selChambre.innerHTML = '<option value="">{{ __("ui.common.loading") }}</option>';
    document.getElementById('inputLocataireId').value = '';
    document.getElementById('locataireInfo').textContent = '{{ __("ui.maintenance.form_tenant_sel") }}';

    if (!maisonId) { selChambre.innerHTML = '<option value="">{{ __("ui.maintenance.form_room_select") }}</option>'; return; }

    fetch('{{ route("maintenance.chambres") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ maison_id: maisonId }),
    })
    .then(r => r.json())
    .then(data => {
        selChambre.innerHTML = '<option value="">{{ __("ui.maintenance.form_whole_prop") }}</option>';
        (data.chambres || []).forEach(c => {
            selChambre.innerHTML += `<option value="${c.id}">{{ __("ui.maintenance.form_room_prefix") }} ${c.numero_chambre}</option>`;
        });
    });
});

// ── Chargement locataire selon chambre ────────────────────────────────────────
document.getElementById('selectChambre')?.addEventListener('change', function () {
    const chambreId = this.value;
    const info = document.getElementById('locataireInfo');
    document.getElementById('inputLocataireId').value = '';
    info.textContent = '–';
    if (!chambreId) return;

    fetch('{{ route("maintenance.locataire") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ chambre_id: chambreId }),
    })
    .then(r => r.json())
    .then(data => {
        if (data.status && data.locataire) {
            document.getElementById('inputLocataireId').value = data.locataire.id;
            info.innerHTML = `<strong>${data.locataire.nom} ${data.locataire.prenom ?? ''}</strong>`
                + (data.locataire.telephone ? `<br><small class="text-muted">${data.locataire.telephone}</small>` : '');
        } else {
            info.textContent = '{{ __("ui.maintenance.no_tenant_room") }}';
        }
    });
});

// ── Soumission AJAX ───────────────────────────────────────────────────────────
document.getElementById('btnSaveTicket')?.addEventListener('click', function () {
    const errDiv = document.getElementById('alertTicketErrors');
    const btnText  = document.getElementById('btnSaveText');
    const btnSpin  = document.getElementById('btnSaveSpinner');
    errDiv.style.display = 'none';
    this.disabled = true; btnText.classList.add('d-none'); btnSpin.classList.remove('d-none');

    fetch('{{ route("maintenance.store") }}', {
        method: 'POST',
        body: new FormData(document.getElementById('formTicket')),
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
    })
    .then(r => r.json())
    .then(data => {
        this.disabled = false; btnText.classList.remove('d-none'); btnSpin.classList.add('d-none');
        if (data.status) {
            Swal.fire({ icon: 'success', title: '{{ __("ui.maintenance.ticket_created") }}', text: data.message, timer: 1200, showConfirmButton: false })
                .then(() => { window.location.href = data.ticket.show_url; });
        } else {
            const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || '{{ __("ui.common.error") }}.'];
            errDiv.innerHTML = '<ul class="mb-0">' + msgs.map(m => `<li>${m}</li>`).join('') + '</ul>';
            errDiv.style.display = '';
            errDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    })
    .catch(() => {
        this.disabled = false; btnText.classList.remove('d-none'); btnSpin.classList.add('d-none');
        Swal.fire('{{ __("ui.common.error") }}', '{{ __("ui.common.network_error") }}', 'error');
    });
});

// ── Filtres statut / priorité ─────────────────────────────────────────────────
function appliquerFiltres() {
    const statut   = document.querySelector('.filtre-statut.active')?.dataset.val || 'tous';
    const priorite = document.querySelector('.filtre-priorite.active')?.dataset.val || 'tous';
    document.querySelectorAll('.ligne-ticket').forEach(row => {
        const okStatut   = statut   === 'tous' || row.dataset.statut   === statut;
        const okPriorite = priorite === 'tous' || row.dataset.priorite === priorite;
        row.style.display = (okStatut && okPriorite) ? '' : 'none';
    });
}

document.querySelectorAll('.filtre-statut').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.filtre-statut').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        appliquerFiltres();
    });
});
document.querySelectorAll('.filtre-priorite').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.filtre-priorite').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        appliquerFiltres();
    });
});

// ── Clics sur le tableau (Voir + Supprimer) ───────────────────────────────────
document.getElementById('tableTickets').addEventListener('click', function (e) {
    // Voir détail → charger sans rechargement
    const btnVoir = e.target.closest('.btn-voir-ticket');
    if (btnVoir) {
        e.preventDefault();
        e.stopPropagation();
        afficherDetailTicket(btnVoir.dataset.href);
        return;
    }

    const btn = e.target.closest('.btn-supprimer');
    if (!btn) return;
    Swal.fire({
        title: '{{ __("ui.maintenance.delete_confirm") }}',
        text: '{{ __("ui.maintenance.ticket_delete_text", ["titre" => ""]) }}'.replace('', btn.dataset.titre),
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("ui.common.delete") }}', cancelButtonText: '{{ __("ui.common.cancel") }}',
    }).then(r => {
        if (!r.isConfirmed) return;
        fetch('{{ route("maintenance.destroy") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ id: btn.dataset.id }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                btn.closest('tr').remove();
                Swal.fire({ icon: 'success', title: '{{ __("ui.common.deleted") }}', timer: 1200, showConfirmButton: false });
            } else {
                Swal.fire('{{ __("ui.common.error") }}', data.message, 'error');
            }
        });
    });
});
</script>
@endpush
@endsection
