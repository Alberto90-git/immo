@extends('layouts.template')

@section('title')
<title>{{ __('ui.recouvrement.title') }} – Lokativ</title>
@endsection

@section('content')
@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">{{ __('ui.common.home') }} /</span> {{ __('ui.recouvrement.title') }}
    </h4>
  </div>

  {{-- ── KPI cards ──────────────────────────────────────────────────────────── --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-folder-open fs-4"></i></span>
          </div>
          <div>
            <div class="fs-5 fw-bold">{{ $stats['total_dossiers'] }}</div>
            <div class="text-muted small">{{ __('ui.recouvrement.kpi_active') }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-money fs-4"></i></span>
          </div>
          <div>
            <div class="fs-5 fw-bold">{{ format_price($stats['montant_total']) }}</div>
            <div class="text-muted small">{{ __('ui.recouvrement.kpi_due') }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-check-circle fs-4"></i></span>
          </div>
          <div>
            <div class="fs-5 fw-bold">{{ format_price($stats['montant_recouvre']) }}</div>
            <div class="text-muted small">{{ __('ui.recouvrement.kpi_recovered') }}</div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center gap-3">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-dark"><i class="bx bx-gavel fs-4"></i></span>
          </div>
          <div>
            <div class="fs-5 fw-bold">{{ $stats['en_contentieux'] }}</div>
            <div class="text-muted small">{{ __('ui.recouvrement.kpi_legal') }}</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- ── Onglets ─────────────────────────────────────────────────────────────── --}}
  <ul class="nav nav-tabs mb-3" id="recouvrementTabs" role="tablist">
    <li class="nav-item">
      <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-dossiers">
        <i class="bx bx-folder me-1"></i> {{ __('ui.recouvrement.tab_dossiers') }} <span class="badge bg-danger ms-1">{{ $dossiers->count() }}</span>
      </button>
    </li>
    <li class="nav-item">
      <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-impayes">
        <i class="bx bx-error-circle me-1"></i> {{ __('ui.recouvrement.tab_impayes') }} <span class="badge bg-warning text-dark ms-1">{{ $impayes->count() }}</span>
      </button>
    </li>
  </ul>

  <div class="tab-content">

    {{-- ── Onglet : Dossiers ──────────────────────────────────────────────────── --}}
    <div class="tab-pane fade show active" id="tab-dossiers">
      <div class="card border-0 shadow-sm">
        <div class="card-header d-flex align-items-center gap-2 flex-wrap">
          <input type="text" id="searchDossiers" class="form-control form-control-sm w-auto" placeholder="{{ __('ui.recouvrement.filter_search_ph') }}">
          <select id="filterStatut" class="form-select form-select-sm w-auto">
            <option value="">{{ __('ui.recouvrement.filter_all_status') }}</option>
            <option value="actif">{{ __('ui.recouvrement.filter_actif') }}</option>
            <option value="apurement">{{ __('ui.recouvrement.filter_apurement') }}</option>
            <option value="contentieux">{{ __('ui.recouvrement.filter_contentieux') }}</option>
          </select>
        </div>
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="tableDossiers">
            <thead class="table-light">
              <tr>
                <th>{{ __('ui.recouvrement.col_tenant') }}</th>
                <th>{{ __('ui.recouvrement.col_property') }}</th>
                <th>{{ __('ui.recouvrement.col_due') }}</th>
                <th>{{ __('ui.recouvrement.col_recovered') }}</th>
                <th>{{ __('ui.recouvrement.col_status') }}</th>
                <th>{{ __('ui.recouvrement.col_risk_score') }}</th>
                <th>{{ __('ui.recouvrement.col_last_relance') }}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @forelse($dossiers as $d)
              <tr data-statut="{{ $d->statut }}" data-nom="{{ strtolower($d->locataire->nom ?? '') }} {{ strtolower($d->locataire->prenom ?? '') }}">
                <td>
                  <div class="fw-semibold">{{ $d->locataire->prenom }} {{ $d->locataire->nom }}</div>
                  <div class="text-muted small">{{ $d->locataire->telephone }}</div>
                </td>
                <td class="small text-muted">
                  {{ $d->locataire->maison->nom_maison ?? '–' }}<br>
                  @if($d->locataire->chambre) {{ __('ui.recouvrement.room_prefix') }} {{ $d->locataire->chambre->numero_chambre }} @endif
                </td>
                <td class="fw-semibold text-danger">{{ format_price($d->montant_du) }}</td>
                <td class="text-success">{{ format_price($d->montant_recouvre) }}</td>
                <td>{!! $d->statut_badge !!}</td>
                <td>
                  <span class="badge bg-{{ $d->score_color }}">{{ $d->score_risque }}/100</span>
                  <div class="text-muted" style="font-size:.7rem;">{{ $d->score_label }}</div>
                </td>
                <td class="small text-muted">
                  @if($d->derniere_relance)
                    {!! $d->derniere_relance->type_badge !!}<br>
                    {{ $d->derniere_relance->envoye_le->format('d/m/Y') }}
                  @else
                    <span class="text-muted">{{ __('ui.recouvrement.no_relance') }}</span>
                  @endif
                </td>
                <td>
                  <a href="{{ route('recouvrement.show', encrypt_id($d->id)) }}" class="btn btn-sm btn-outline-primary">
                    <i class="bx bx-show"></i>
                  </a>
                </td>
              </tr>
              @empty
              <tr><td colspan="8" class="text-center text-muted py-4">{{ __('ui.recouvrement.no_dossier') }}</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- ── Onglet : Impayés sans dossier ─────────────────────────────────────── --}}
    <div class="tab-pane fade" id="tab-impayes">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>{{ __('ui.recouvrement.col_tenant') }}</th>
                  <th>{{ __('ui.recouvrement.col_property') }}</th>
                  <th>{{ __('ui.recouvrement.col_monthly_rent') }}</th>
                  <th>{{ __('ui.recouvrement.col_last_payment') }}</th>
                  <th>{{ __('ui.recouvrement.col_unpaid_months') }}</th>
                  <th>{{ __('ui.recouvrement.col_estimated') }}</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                @forelse($impayes as $loc)
                @php
                  $montantEstime = ($loc->nbMoisImpayes ?? 1) * $loc->prix_mois;
                @endphp
                <tr>
                  <td>
                    <div class="fw-semibold">{{ $loc->prenom }} {{ $loc->nom }}</div>
                    <div class="text-muted small">{{ $loc->telephone }}</div>
                  </td>
                  <td class="small text-muted">
                    {{ $loc->maison->nom_maison ?? '–' }}<br>
                    {{ $loc->chambre->numero_chambre ?? '' }}
                  </td>
                  <td>{{ format_price($loc->prix_mois) }}</td>
                  <td class="small">
                    @if($loc->dernierPaiement)
                      {{ \Carbon\Carbon::parse($loc->dernierPaiement)->format('d/m/Y') }}
                    @else
                      <span class="text-danger">{{ __('ui.recouvrement.never') }}</span>
                    @endif
                  </td>
                  <td>
                    @if($loc->nbMoisImpayes)
                      <span class="badge {{ $loc->nbMoisImpayes >= 3 ? 'bg-danger' : 'bg-warning text-dark' }}">
                        {{ $loc->nbMoisImpayes }} {{ __('ui.recouvrement.months_suffix') }}
                      </span>
                    @else
                      <span class="badge bg-secondary">–</span>
                    @endif
                  </td>
                  <td class="fw-semibold text-danger">{{ format_price($montantEstime) }}</td>
                  <td>
                    @can('ajoute-recouvrement')
                    <button class="btn btn-sm btn-danger btn-ouvrir-dossier"
                      data-id="{{ $loc->id }}"
                      data-nom="{{ $loc->prenom }} {{ $loc->nom }}"
                      data-montant="{{ $montantEstime }}"
                      data-mois="{{ $loc->nbMoisImpayes ?? 1 }}"
                      data-dernier="{{ $loc->dernierPaiement }}">
                      <i class="bx bx-folder-plus me-1"></i> {{ __('ui.recouvrement.open_dossier') }}
                    </button>
                    @endcan
                  </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-muted py-4">{{ __('ui.recouvrement.no_impaye') }}</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>{{-- /tab-content --}}

</div>{{-- /container --}}

{{-- ── Modal : Ouvrir un dossier ──────────────────────────────────────────────── --}}
<div class="modal fade" id="modalOuvrirDossier" tabindex="-1">
  <div class="modal-dialog">
    <form id="formOuvrirDossier" class="modal-content">
      @csrf
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white"><i class="bx bx-folder-plus me-2"></i>{{ __('ui.recouvrement.modal_open_title') }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="locataire_id" id="inputLocataireId">
        <div class="mb-3">
          <label class="form-label fw-semibold">{{ __('ui.recouvrement.select_tenant') }}</label>
          <input type="text" id="inputLocataireNom" class="form-control" readonly>
        </div>
        <div class="row g-3">
          <div class="col-sm-6">
            <label class="form-label fw-semibold">{{ __('ui.recouvrement.amount_due') }} ({{ get_symbole_devise() }})</label>
            <input type="number" name="montant_du" id="inputMontantDu" class="form-control" min="0" required>
          </div>
          <div class="col-sm-6">
            <label class="form-label fw-semibold">{{ __('ui.recouvrement.modal_nb_months') }}</label>
            <input type="number" name="nb_mois_impayes" id="inputNbMois" class="form-control" min="1" required>
          </div>
        </div>
        <div class="mb-3 mt-3">
          <label class="form-label fw-semibold">{{ __('ui.recouvrement.modal_last_payment') }}</label>
          <input type="date" name="date_dernier_paiement" id="inputDernierPaiement" class="form-control">
        </div>
        <div class="mb-0">
          <label class="form-label fw-semibold">{{ __('ui.recouvrement.modal_notes') }}</label>
          <textarea name="notes_juridiques" class="form-control" rows="2" placeholder="{{ __('ui.recouvrement.modal_notes_ph') }}"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('ui.common.cancel') }}</button>
        <button type="button" id="btnCreerDossier" class="btn btn-primary"><i class="bx bx-save me-1"></i>{{ __('ui.recouvrement.modal_create') }}</button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
// ── Filtres tableau dossiers ──────────────────────────────────────────────────
document.getElementById('searchDossiers').addEventListener('input', filterDossiers);
document.getElementById('filterStatut').addEventListener('change', filterDossiers);

function filterDossiers() {
  const search = document.getElementById('searchDossiers').value.toLowerCase();
  const statut = document.getElementById('filterStatut').value;
  document.querySelectorAll('#tableDossiers tbody tr[data-nom]').forEach(tr => {
    const nom    = tr.dataset.nom || '';
    const st     = tr.dataset.statut || '';
    const showNom   = !search || nom.includes(search);
    const showSt    = !statut || st === statut;
    tr.style.display = showNom && showSt ? '' : 'none';
  });
}

// ── Modal ouvrir dossier ──────────────────────────────────────────────────────
document.querySelectorAll('.btn-ouvrir-dossier').forEach(btn => {
  btn.addEventListener('click', function () {
    document.getElementById('inputLocataireId').value     = this.dataset.id;
    document.getElementById('inputLocataireNom').value    = this.dataset.nom;
    document.getElementById('inputMontantDu').value       = this.dataset.montant;
    document.getElementById('inputNbMois').value          = this.dataset.mois;
    document.getElementById('inputDernierPaiement').value = this.dataset.dernier || '';
    new bootstrap.Modal(document.getElementById('modalOuvrirDossier')).show();
  });
});

// ── Soumission AJAX : créer dossier ──────────────────────────────────────────
document.getElementById('btnCreerDossier').addEventListener('click', function () {
  const btn  = this;
  const form = document.getElementById('formOuvrirDossier');
  const data = new FormData(form);
  data.append('_token', '{{ csrf_token() }}');

  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>{{ __("ui.recouvrement.js_creating") }}';

  fetch('{{ route("recouvrement.creer_dossier") }}', {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
    body: data,
  })
  .then(r => r.json())
  .then(res => {
    bootstrap.Modal.getInstance(document.getElementById('modalOuvrirDossier')).hide();
    if (res.status) {
      Swal.fire({ icon: 'success', title: '{{ __("ui.common.success") }}', text: res.message, confirmButtonText: 'OK' })
        .then(() => { if (res.redirect) window.location.href = res.redirect; else window.location.reload(); });
    } else {
      Swal.fire({ icon: 'error', title: '{{ __("ui.common.error") }}', text: res.message });
    }
  })
  .catch(() => Swal.fire({ icon: 'error', title: '{{ __("ui.common.error") }}', text: '{{ __("ui.common.network_error") }}' }))
  .finally(() => {
    btn.disabled = false;
    btn.innerHTML = '<i class="bx bx-save me-1"></i>{{ __("ui.recouvrement.modal_create") }}';
  });
});
</script>
@endpush
