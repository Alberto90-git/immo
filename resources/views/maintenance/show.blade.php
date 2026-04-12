@extends('layouts.template')

@section('title')<title>Ticket #{{ $ticket->id }} – Maintenance</title>@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  {{-- Fil d'Ariane --}}
  <div class="d-flex align-items-center mb-4 gap-2 flex-wrap">
    <a href="{{ route('maintenance.index') }}" class="btn btn-icon btn-outline-secondary">
      <i class="bx bx-arrow-back"></i>
    </a>
    <h4 class="fw-bold mb-0 me-auto">
      <span class="text-muted fw-light">Maintenance /</span>
      <i class="{{ $ticket->categorie_icon }} ms-1 me-1"></i>
      {{ $ticket->titre }}
    </h4>
    <span id="statutBadge">{!! $ticket->statut_badge !!}</span>
    {!! $ticket->priorite_badge !!}
  </div>

  <div class="row g-4">

    {{-- ── Colonne principale ──────────────────────────────────────────────── --}}
    <div class="col-lg-8">

      {{-- Détails ticket --}}
      <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="mb-0"><i class="bx bx-detail me-2"></i>Détails</h6>
          <span class="badge bg-label-secondary small">Ouvert le {{ $ticket->date_ouverture->format('d/m/Y') }}</span>
        </div>
        <div class="card-body">
          <div class="row g-3 mb-3">
            <div class="col-sm-4">
              <div class="small text-muted mb-1">Catégorie</div>
              <div><i class="{{ $ticket->categorie_icon }} me-1"></i>{{ $ticket->categorie_label }}</div>
            </div>
            <div class="col-sm-4">
              <div class="small text-muted mb-1">Priorité</div>
              <div>{!! $ticket->priorite_badge !!}</div>
            </div>
            <div class="col-sm-4">
              <div class="small text-muted mb-1">Statut</div>
              <div id="statutBadgeDetail">{!! $ticket->statut_badge !!}</div>
            </div>
            <div class="col-sm-4">
              <div class="small text-muted mb-1">Logement</div>
              <div class="fw-semibold">{{ $ticket->maison->nom_maison ?? '–' }}</div>
              @if($ticket->chambre)<div class="small text-muted">Chambre {{ $ticket->chambre->numero_chambre }}</div>@endif
            </div>
            <div class="col-sm-4">
              <div class="small text-muted mb-1">Locataire</div>
              <div>{{ $ticket->locataire ? $ticket->locataire->nom . ' ' . $ticket->locataire->prenom : '–' }}</div>
              @if($ticket->locataire?->telephone)<div class="small text-muted">{{ $ticket->locataire->telephone }}</div>@endif
            </div>
            <div class="col-sm-4">
              <div class="small text-muted mb-1">Créé par</div>
              <div class="small">{{ $ticket->createdBy->name ?? '–' }}</div>
            </div>
          </div>
          @if($ticket->description)
            <hr>
            <div class="small text-muted mb-1">Description</div>
            <p class="mb-0">{{ $ticket->description }}</p>
          @endif
          @if($ticket->cout_intervention)
            <hr>
            <div class="row g-2">
              <div class="col-sm-6">
                <div class="small text-muted mb-1">Coût d'intervention</div>
                <div class="fw-bold text-danger fs-6">{{ number_format($ticket->cout_intervention, 0, ',', ' ') }} FCFA</div>
              </div>
              <div class="col-sm-6">
                <div class="small text-muted mb-1">Imputé à</div>
                <span class="badge {{ $ticket->imputation === 'proprietaire' ? 'bg-label-primary' : 'bg-label-warning' }}">
                  {{ $ticket->imputation === 'proprietaire' ? 'Propriétaire' : 'Agence' }}
                </span>
              </div>
            </div>
          @endif
        </div>
      </div>

      {{-- Timeline historique --}}
      <div class="card shadow-sm">
        <div class="card-header">
          <h6 class="mb-0"><i class="bx bx-history me-2"></i>Historique des interventions</h6>
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
              <li class="text-muted small">Aucun historique.</li>
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
          <h6 class="mb-0 text-white"><i class="bx bx-transfer me-2"></i>Avancer à l'étape suivante</h6>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label small fw-semibold">Commentaire (optionnel)</label>
            <textarea id="commentaireStatut" class="form-control form-control-sm" rows="2" placeholder="Informations sur l'intervention…"></textarea>
          </div>
          @if($ticket->statut === 'resolu')
            <div class="mb-3">
              <label class="form-label small fw-semibold">Coût d'intervention (FCFA)</label>
              <input type="number" id="coutIntervention" class="form-control form-control-sm" min="0" step="500" placeholder="0">
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">Imputer à</label>
              <select id="imputation" class="form-select form-select-sm">
                <option value="agence">Agence</option>
                <option value="proprietaire">Propriétaire</option>
              </select>
            </div>
          @endif
          <button type="button" class="btn btn-primary w-100" id="btnChangerStatut"
                  data-id="{{ $ticket->id }}"
                  data-suivant="{{ $ticket->statutSuivant() }}">
            <i class="bx bx-right-arrow-alt me-1"></i>
            {{ $ticket->labelBoutonStatut() }}
          </button>
        </div>
      </div>
      @else
      <div class="card shadow-sm mb-4">
        <div class="card-body text-center text-success py-4">
          <i class="bx bx-check-circle fs-1 mb-2 d-block"></i>
          <strong>Ticket clôturé</strong>
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
          <h6 class="mb-0"><i class="bx bx-user-check me-2"></i>Prestataire</h6>
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
              <span class="text-muted small">Aucun prestataire affecté</span>
            @endif
          </div>
          <div class="d-flex gap-2">
            <select id="selectPrestataire" class="form-select form-select-sm">
              <option value="">— Choisir —</option>
              @foreach($prestataires as $p)
                <option value="{{ $p->id }}" {{ $ticket->prestataire_id == $p->id ? 'selected' : '' }}>
                  {{ $p->nom }} ({{ $p->specialite_label }})
                </option>
              @endforeach
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
          <h6 class="mb-0"><i class="bx bx-calendar me-2"></i>Dates clés</h6>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between small">
            <span class="text-muted">Ouverture</span>
            <strong>{{ $ticket->date_ouverture->format('d/m/Y') }}</strong>
          </li>
          <li class="list-group-item d-flex justify-content-between small">
            <span class="text-muted">Résolution</span>
            <strong>{{ $ticket->date_resolution ? $ticket->date_resolution->format('d/m/Y') : '–' }}</strong>
          </li>
          <li class="list-group-item d-flex justify-content-between small">
            <span class="text-muted">Clôture</span>
            <strong>{{ $ticket->date_cloture ? $ticket->date_cloture->format('d/m/Y') : '–' }}</strong>
          </li>
          @if($ticket->date_ouverture && !$ticket->date_cloture)
          <li class="list-group-item d-flex justify-content-between small">
            <span class="text-muted">Durée (jours)</span>
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

    Swal.fire({
        title: label + ' ?',
        icon: 'question', showCancelButton: true,
        confirmButtonText: 'Confirmer', cancelButtonText: 'Annuler',
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
                Swal.fire({ icon: 'success', title: 'Mis à jour !', text: data.message, timer: 1500, showConfirmButton: false })
                    .then(() => location.reload());
            } else {
                Swal.fire('Erreur', data.message, 'error');
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
            if (prestataireId) {
                const sel = document.getElementById('selectPrestataire');
                const texte = sel.options[sel.selectedIndex].text;
                div.innerHTML = `<div class="fw-semibold">${data.nom_prestataire}</div>`;
            } else {
                div.innerHTML = '<span class="text-muted small">Aucun prestataire affecté</span>';
            }
            Swal.fire({ icon: 'success', text: data.message, timer: 1200, showConfirmButton: false });
        } else {
            Swal.fire('Erreur', data.message, 'error');
        }
    });
});
</script>
@endpush
@endsection
