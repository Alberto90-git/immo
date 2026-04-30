@extends('layouts.template')

@section('title')<title>Ticket #{{ $ticket->id }} – Interventions</title>@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  {{-- Fil d'Ariane --}}
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">{{ __('ui.maintenance.title') }} /</span>
      <i class="{{ $ticket->categorie_icon }} ms-1 me-1"></i>
      {{ $ticket->titre }}
    </h4>
    <div class="d-flex align-items-center gap-2">
      <span id="statutBadge">{!! $ticket->statut_badge !!}</span>
      {!! $ticket->priorite_badge !!}
      <button type="button" class="btn btn-outline-secondary" onclick="history.back()">
        <i class="bx bx-arrow-back me-1"></i> {{ __('ui.common.back') }}
      </button>
    </div>
  </div>

  <div class="row g-4">

    {{-- ── Colonne principale ──────────────────────────────────────────────── --}}
    <div class="col-lg-8">

      {{-- Détails ticket --}}
      <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="mb-0"><i class="bx bx-detail me-2"></i>{{ __('ui.maintenance.show_details') }}</h6>
          <span class="badge bg-label-secondary small">{{ __('ui.maintenance.opened_on') }} {{ $ticket->date_ouverture->format('d/m/Y') }}</span>
        </div>
        <div class="card-body">
          <div class="row g-3 mb-3">
            <div class="col-sm-4">
              <div class="small text-muted mb-1">{{ __('ui.maintenance.category') }}</div>
              <div><i class="{{ $ticket->categorie_icon }} me-1"></i>{{ $ticket->categorie_label }}</div>
            </div>
            <div class="col-sm-4">
              <div class="small text-muted mb-1">{{ __('ui.maintenance.priority') }}</div>
              <div>{!! $ticket->priorite_badge !!}</div>
            </div>
            <div class="col-sm-4">
              <div class="small text-muted mb-1">{{ __('ui.maintenance.col_status') }}</div>
              <div id="statutBadgeDetail">{!! $ticket->statut_badge !!}</div>
            </div>
            <div class="col-sm-4">
              <div class="small text-muted mb-1">{{ __('ui.maintenance.property') }}</div>
              <div class="fw-semibold">{{ $ticket->maison->nom_maison ?? '–' }}</div>
              @if($ticket->chambre)<div class="small text-muted">{{ __('ui.maintenance.room') }} {{ $ticket->chambre->numero_chambre }}</div>@endif
            </div>
            <div class="col-sm-4">
              <div class="small text-muted mb-1">{{ __('ui.maintenance.tenant') }}</div>
              <div>{{ $ticket->locataire ? $ticket->locataire->nom . ' ' . $ticket->locataire->prenom : '–' }}</div>
              @if($ticket->locataire?->telephone)<div class="small text-muted">{{ $ticket->locataire->telephone }}</div>@endif
            </div>
            <div class="col-sm-4">
              <div class="small text-muted mb-1">{{ __('ui.maintenance.created_by') }}</div>
              <div class="small">{{ $ticket->createdBy->name ?? '–' }}</div>
            </div>
          </div>
          @if($ticket->description)
            <hr>
            <div class="small text-muted mb-1">{{ __('ui.maintenance.form_description') }}</div>
            <p class="mb-0">{{ $ticket->description }}</p>
          @endif
          @if($ticket->cout_intervention)
            <hr>
            <div class="row g-2">
              <div class="col-sm-6">
                <div class="small text-muted mb-1">{{ __('ui.maintenance.cost_label') }}</div>
                <div class="fw-bold text-danger fs-6">{{ format_price($ticket->cout_intervention) }}</div>
              </div>
              <div class="col-sm-6">
                <div class="small text-muted mb-1">{{ __('ui.maintenance.imputed_to') }}</div>
                <span class="badge {{ $ticket->imputation === 'proprietaire' ? 'bg-label-primary' : 'bg-label-warning' }}">
                  {{ $ticket->imputation === 'proprietaire' ? __('ui.maintenance.owner_label') : __('ui.maintenance.agency_label') }}
                </span>
              </div>
            </div>
          @endif
        </div>
      </div>

      {{-- Timeline historique --}}
      <div class="card shadow-sm">
        <div class="card-header">
          <h6 class="mb-0"><i class="bx bx-history me-2"></i>{{ __('ui.maintenance.history_title') }}</h6>
        </div>
        <div class="card-body">
          <ul class="timeline" id="listeHistorique">
            @forelse($ticket->historiques as $h)
              <li class="timeline-item pb-3">
                <span class="timeline-indicator">
                  <i class="bx {{ $h->statut_apres === 'cloture' ? 'bx-check-circle text-success' : ($h->statut_apres === 'resolu' ? 'bx-check text-info' : ($h->statut_apres === 'en_cours' ? 'bx-time text-warning' : 'bx-circle text-secondary')) }}"></i>
                </span>
                <div class="timeline-event">
                  <div class="d-flex justify-content-between align-items-start">
                    <div>{!! $h->statut_apres_badge !!} <span class="small text-muted ms-1">par {{ $h->user->name ?? 'Système' }}</span></div>
                    <small class="text-muted">{{ $h->created_at->format('d/m/Y à H:i') }}</small>
                  </div>
                  @if($h->commentaire)
                    <p class="mb-0 mt-1 small text-muted">{{ $h->commentaire }}</p>
                  @endif
                </div>
              </li>
            @empty
              <li class="text-muted small">{{ __('ui.maintenance.no_history') }}</li>
            @endforelse
          </ul>
        </div>
      </div>

    </div>

    {{-- ── Colonne actions ─────────────────────────────────────────────────── --}}
    <div class="col-lg-4">

      {{-- Changer statut --}}
      @can('modify-maintenance')
      @if($ticket->statutSuivant())
      <div class="card shadow-sm mb-4 border-primary">
        <div class="card-header bg-primary text-white py-2">
          <h6 class="mb-0 text-white"><i class="bx bx-transfer me-2"></i>{{ __('ui.maintenance.advance_status') }}</h6>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label small fw-semibold">{{ __('ui.maintenance.comment_label') }}</label>
            <textarea id="commentaireStatut" class="form-control form-control-sm" rows="2" placeholder="{{ __('ui.maintenance.comment_interv_ph') }}"></textarea>
          </div>
          @if($ticket->statut === 'resolu')
            <div class="mb-3">
              <label class="form-label small fw-semibold">{{ __('ui.maintenance.cost_label') }} ({{ get_symbole_devise() }})</label>
              <input type="number" id="coutIntervention" class="form-control form-control-sm" min="0" step="500" placeholder="0">
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">{{ __('ui.maintenance.imputed_to') }}</label>
              <select id="imputation" class="form-select form-select-sm">
                <option value="agence">{{ __('ui.maintenance.agency_label') }}</option>
                <option value="proprietaire">{{ __('ui.maintenance.owner_label') }}</option>
              </select>
            </div>
          @endif
          <button type="button" class="btn btn-primary w-100" id="btnChangerStatut"
                  data-id="{{ $ticket->id }}"
                  data-suivant="{{ $ticket->statutSuivant() }}"
                  data-prestataire-id="{{ $ticket->prestataire_id ?? '' }}">
            <i class="bx bx-right-arrow-alt me-1"></i>
            {{ $ticket->labelBoutonStatut() }}
          </button>
        </div>
      </div>
      @else
      <div class="card shadow-sm mb-4">
        <div class="card-body text-center text-success py-4">
          <i class="bx bx-check-circle fs-1 mb-2 d-block"></i>
          <strong>{{ __('ui.maintenance.ticket_closed') }}</strong>
          @if($ticket->date_cloture)
            <div class="small text-muted mt-1">Le {{ $ticket->date_cloture->format('d/m/Y') }}</div>
          @endif
        </div>
      </div>
      @endif
      @endcan

      {{-- Affectation prestataire --}}
      @can('modify-maintenance')
      <div class="card shadow-sm mb-4">
        <div class="card-header py-2">
          <h6 class="mb-0"><i class="bx bx-user-check me-2"></i>{{ __('ui.maintenance.provider_label') }}</h6>
        </div>
        <div class="card-body">
          <div id="nomPrestataire" class="mb-3">
            @if($ticket->prestataire)
              <div class="d-flex align-items-center gap-2">
                <i class="{{ $ticket->prestataire->specialite_icon }} text-primary fs-5"></i>
                <div>
                  <div class="fw-semibold">{{ $ticket->prestataire->nom }}</div>
                  <div class="small text-muted">{{ $ticket->prestataire->telephone ?? '' }}</div>
                </div>
              </div>
            @else
              <span class="text-muted small">{{ __('ui.maintenance.no_provider_assigned') }}</span>
            @endif
          </div>
          @php
            $prestatairesFiltres = $prestataires->where('specialite', $ticket->categorie);
            $autresPrestataires  = $prestataires->where('specialite', '!=', $ticket->categorie);
          @endphp
          <div class="d-flex gap-2">
            <select id="selectPrestataire" class="form-select form-select-sm">
              <option value="">{{ __('ui.common.choose') }}</option>
              @if($prestatairesFiltres->isNotEmpty())
                <optgroup label="{{ $ticket->categorie_label }}">
                  @foreach($prestatairesFiltres as $p)
                    <option value="{{ $p->id }}" {{ $ticket->prestataire_id == $p->id ? 'selected' : '' }}>
                      {{ $p->nom }}
                    </option>
                  @endforeach
                </optgroup>
              @endif
              @if($autresPrestataires->isNotEmpty())
                <optgroup label="{{ __('ui.maintenance.other_providers') }}">
                  @foreach($autresPrestataires as $p)
                    <option value="{{ $p->id }}" {{ $ticket->prestataire_id == $p->id ? 'selected' : '' }}>
                      {{ $p->nom }} ({{ $p->specialite_label }})
                    </option>
                  @endforeach
                </optgroup>
              @endif
            </select>
            <button type="button" class="btn btn-sm btn-primary" id="btnAffecter" data-id="{{ $ticket->id }}">
              <i class="bx bx-check"></i>
            </button>
          </div>
        </div>
      </div>
      @endcan

      {{-- Résumé dates --}}
      <div class="card shadow-sm">
        <div class="card-header py-2">
          <h6 class="mb-0"><i class="bx bx-calendar me-2"></i>{{ __('ui.maintenance.key_dates') }}</h6>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between small">
            <span class="text-muted">{{ __('ui.maintenance.date_open') }}</span>
            <strong>{{ $ticket->date_ouverture->format('d/m/Y') }}</strong>
          </li>
          <li class="list-group-item d-flex justify-content-between small">
            <span class="text-muted">{{ __('ui.maintenance.date_resolve') }}</span>
            <strong>{{ $ticket->date_resolution ? $ticket->date_resolution->format('d/m/Y') : '–' }}</strong>
          </li>
          <li class="list-group-item d-flex justify-content-between small">
            <span class="text-muted">{{ __('ui.maintenance.date_close') }}</span>
            <strong>{{ $ticket->date_cloture ? $ticket->date_cloture->format('d/m/Y') : '–' }}</strong>
          </li>
          @if($ticket->date_ouverture && !$ticket->date_cloture)
          <li class="list-group-item d-flex justify-content-between small">
            <span class="text-muted">{{ __('ui.maintenance.duration_days') }}</span>
            <strong>{{ $ticket->date_ouverture->diffInDays(now()) }} j</strong>
          </li>
          @endif
        </ul>
      </div>

    </div>
  </div>
</div>

<style>
.timeline { list-style: none; padding: 0; position: relative; }
.timeline-item { display: flex; gap: 12px; }
.timeline-indicator { flex-shrink: 0; width: 28px; height: 28px; border-radius: 50%; background: #f5f5f5;
  display: flex; align-items: center; justify-content: center; font-size: 14px; border: 2px solid #e0e0e0; }
html.dark-style .timeline-indicator { background: #373852 !important; border-color: #444564 !important; }
.timeline-event { flex: 1; padding-bottom: 4px; }
</style>

@push('scripts')
<script>
function ajaxPost(url, data) {
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify(data),
    }).then(r => r.json());
}

// ── Changer statut ────────────────────────────────────────────────────────────
document.getElementById('btnChangerStatut')?.addEventListener('click', function () {
    const suivant = this.dataset.suivant;
    const label   = this.textContent.trim();

    // Vérifier qu'un prestataire est affecté avant tout
    if (!this.dataset.prestataireId) {
        Swal.fire({
            icon: 'warning',
            title: '{{ __("ui.maintenance.provider_required") }}',
            html: '{!! __("ui.maintenance.provider_required_html") !!}',
            confirmButtonText: '{{ __("ui.maintenance.understood") }}',
            confirmButtonColor: '#696cff',
        });
        return;
    }

    Swal.fire({
        title: label + ' ?',
        icon: 'question', showCancelButton: true,
        confirmButtonText: '{{ __("ui.common.confirm") }}', cancelButtonText: '{{ __("ui.common.cancel") }}',
        confirmButtonColor: '#696cff',
    }).then(r => {
        if (!r.isConfirmed) return;

        const payload = {
            id: this.dataset.id,
            commentaire: document.getElementById('commentaireStatut')?.value || null,
        };
        if (suivant === 'cloture') {
            payload.cout_intervention = document.getElementById('coutIntervention')?.value || null;
            payload.imputation        = document.getElementById('imputation')?.value || 'agence';
        }

        ajaxPost('{{ route("maintenance.changer_statut") }}', payload)
        .then(data => {
            if (data.status) {
                Swal.fire({ icon: 'success', title: '{{ __("ui.maintenance.status_updated") }}', text: data.message, timer: 1500, showConfirmButton: false })
                    .then(() => location.reload());
            } else {
                Swal.fire('{{ __("ui.common.error") }}', data.message, 'error');
            }
        });
    });
});

// ── Affecter prestataire ──────────────────────────────────────────────────────
document.getElementById('btnAffecter')?.addEventListener('click', function () {
    const prestataireId = document.getElementById('selectPrestataire').value;
    ajaxPost('{{ route("maintenance.affecter") }}', { id: this.dataset.id, prestataire_id: prestataireId || null })
    .then(data => {
        if (data.status) {
            const div = document.getElementById('nomPrestataire');
            const btn = document.getElementById('btnChangerStatut');
            if (prestataireId) {
                div.innerHTML = `<div class="fw-semibold">${data.nom_prestataire}</div>`;
                if (btn) btn.dataset.prestataireId = prestataireId;
            } else {
                div.innerHTML = '<span class="text-muted small">{{ __("ui.maintenance.no_provider_assigned") }}</span>';
                if (btn) btn.dataset.prestataireId = '';
            }
            Swal.fire({ icon: 'success', text: data.message, timer: 1200, showConfirmButton: false });
        } else {
            Swal.fire('{{ __("ui.common.error") }}', data.message, 'error');
        }
    });
});
</script>
@endpush
@endsection
