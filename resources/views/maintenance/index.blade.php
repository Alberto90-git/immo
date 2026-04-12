@extends('layouts.template')

@section('title')<title>Maintenance – Lokativ</title>@endsection

@section('content')
@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">

  {{-- ══ VUE LISTE ══════════════════════════════════════════════════════════ --}}
  <div id="viewListe">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Accueil /</span> Maintenance</h4>
      <div class="d-flex gap-2">
        @can('gestion-prestataire')
          <a href="{{ route('maintenance.prestataires') }}" class="btn btn-outline-secondary">
            <i class="bx bx-group me-1"></i> Prestataires
          </a>
        @endcan
        @can('ajoute-maintenance')
          <button type="button" class="btn btn-primary" id="btnNouveauTicket">
            <i class="bx bx-plus me-1"></i> Nouveau ticket
          </button>
        @endcan
      </div>
    </div>

    {{-- Stats --}}
    <div class="row g-3 mb-4">
      <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center py-3">
          <div class="fs-4 fw-bold text-body stat-total">{{ $stats['total'] }}</div>
          <div class="small text-muted">Total</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center py-3">
          <div class="fs-4 fw-bold text-secondary stat-nouveau">{{ $stats['nouveau'] }}</div>
          <div class="small text-muted">Nouveaux</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center py-3">
          <div class="fs-4 fw-bold text-warning">{{ $stats['en_cours'] }}</div>
          <div class="small text-muted">En cours</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="card shadow-sm text-center py-3">
          <div class="fs-4 fw-bold text-danger">{{ $stats['urgents'] }}</div>
          <div class="small text-muted">Urgents actifs</div>
        </div>
      </div>
    </div>

    {{-- Filtres --}}
    <div class="mb-3 d-flex gap-2 flex-wrap align-items-center">
      <div class="d-flex gap-1">
        <button class="btn btn-sm btn-outline-secondary filtre-statut active" data-val="tous">Tous</button>
        <button class="btn btn-sm btn-outline-secondary filtre-statut" data-val="nouveau">Nouveaux</button>
        <button class="btn btn-sm btn-outline-warning filtre-statut" data-val="en_cours">En cours</button>
        <button class="btn btn-sm btn-outline-info filtre-statut" data-val="resolu">Résolus</button>
        <button class="btn btn-sm btn-outline-success filtre-statut" data-val="cloture">Clôturés</button>
      </div>
      <div class="ms-auto d-flex gap-1">
        <button class="btn btn-sm btn-outline-secondary filtre-priorite active" data-val="tous">Toutes priorités</button>
        <button class="btn btn-sm btn-outline-danger filtre-priorite" data-val="urgente">Urgents</button>
        <button class="btn btn-sm btn-outline-warning filtre-priorite" data-val="haute">Haute</button>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="tableTickets">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th>Logement</th>
                <th>Priorité</th>
                <th>Statut</th>
                <th>Prestataire</th>
                <th>Ouvert le</th>
                <th class="text-center">Actions</th>
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
                        <a href="{{ route('maintenance.show', $ticket->id) }}"
                           class="btn btn-sm btn-icon btn-outline-info" title="Voir">
                          <i class="bx bx-show"></i>
                        </a>
                      @endcan
                      @can('delete-maintenance')
                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-supprimer"
                                data-id="{{ $ticket->id }}" data-titre="{{ $ticket->titre }}" title="Supprimer">
                          <i class="bx bx-trash"></i>
                        </button>
                      @endcan
                    </div>
                  </td>
                </tr>
              @empty
                <tr id="ligneVide">
                  <td colspan="9" class="text-center text-muted py-5">
                    <i class="bx bx-wrench fs-3 d-block mb-2"></i>
                    Aucun ticket de maintenance.
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

    <div class="d-flex align-items-center mb-4 gap-2">
      <button type="button" class="btn btn-icon btn-outline-secondary" id="btnRetourListe">
        <i class="bx bx-arrow-back"></i>
      </button>
      <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Maintenance /</span> Nouveau ticket</h4>
    </div>

    <div id="alertTicketErrors" class="alert alert-danger" style="display:none;"></div>

    <form id="formTicket">
      @csrf

      <div class="row g-4">
        {{-- Colonne principale --}}
        <div class="col-lg-8">
          <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary">
              <h6 class="mb-0 text-white"><i class="bx bx-info-circle me-2"></i>Informations du ticket</h6>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label fw-semibold">Titre <span class="text-danger">*</span></label>
                <input type="text" name="titre" class="form-control" placeholder="Ex : Fuite d'eau dans la cuisine" required>
              </div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Catégorie <span class="text-danger">*</span></label>
                  <select name="categorie" class="form-select" required>
                    <option value="">— Choisir —</option>
                    @foreach(App\Prestataire::SPECIALITES as $key => $info)
                      <option value="{{ $key }}">{{ $info['label'] }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label fw-semibold">Priorité <span class="text-danger">*</span></label>
                  <select name="priorite" class="form-select" required>
                    <option value="basse">Basse</option>
                    <option value="normale" selected>Normale</option>
                    <option value="haute">Haute</option>
                    <option value="urgente">Urgente</option>
                  </select>
                </div>
              </div>
              <div class="mt-3">
                <label class="form-label fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Décrivez le problème en détail…"></textarea>
              </div>
            </div>
          </div>
        </div>

        {{-- Colonne latérale --}}
        <div class="col-lg-4">
          <div class="card shadow-sm mb-4">
            <div class="card-header">
              <h6 class="mb-0"><i class="bx bx-home me-2"></i>Logement concerné</h6>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label fw-semibold">Maison <span class="text-danger">*</span></label>
                <select name="maison_id" id="selectMaison" class="form-select" required>
                  <option value="">— Choisir —</option>
                  @foreach($maisons as $maison)
                    <option value="{{ $maison->id }}">{{ $maison->nom_maison }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Chambre</label>
                <select name="chambre_id" id="selectChambre" class="form-select">
                  <option value="">— Sélectionnez d'abord une maison —</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-semibold">Locataire concerné</label>
                <input type="hidden" name="locataire_id" id="inputLocataireId">
                <div id="locataireInfo" class="form-control bg-light text-muted small" style="min-height:38px; cursor:default;">
                  Sélectionnez une chambre
                </div>
              </div>
              <div>
                <label class="form-label fw-semibold">Date d'ouverture <span class="text-danger">*</span></label>
                <input type="date" name="date_ouverture" class="form-control" value="{{ date('Y-m-d') }}" required>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="d-flex gap-2 justify-content-end mb-5">
        <button type="button" class="btn btn-outline-secondary" id="btnAnnulerCreate">Annuler</button>
        <button type="button" class="btn btn-primary px-4" id="btnSaveTicket">
          <span id="btnSaveText"><i class="bx bx-save me-1"></i> Créer le ticket</span>
          <span id="btnSaveSpinner" class="d-none">
            <span class="spinner-border spinner-border-sm me-1"></span> En cours…
          </span>
        </button>
      </div>

    </form>
  </div>
  @endcan

</div>

@push('scripts')
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
    if (_canShow)   actions += `<a href="${t.show_url}" class="btn btn-sm btn-icon btn-outline-info" title="Voir"><i class="bx bx-show"></i></a>`;
    if (_canDelete) actions += `<button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-supprimer" data-id="${t.id}" data-titre="${t.titre}" title="Supprimer"><i class="bx bx-trash"></i></button>`;
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

// ── Basculer liste / formulaire ───────────────────────────────────────────────
document.getElementById('btnNouveauTicket')?.addEventListener('click', () => {
    document.getElementById('viewListe').style.display  = 'none';
    document.getElementById('viewCreate').style.display = '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
});

function retourListe() {
    document.getElementById('viewCreate').style.display = 'none';
    document.getElementById('viewListe').style.display  = '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
document.getElementById('btnRetourListe')?.addEventListener('click', retourListe);
document.getElementById('btnAnnulerCreate')?.addEventListener('click', retourListe);

// ── Chargement chambres selon maison ─────────────────────────────────────────
document.getElementById('selectMaison')?.addEventListener('change', function () {
    const maisonId = this.value;
    const selChambre  = document.getElementById('selectChambre');
    selChambre.innerHTML = '<option value="">Chargement…</option>';
    document.getElementById('inputLocataireId').value = '';
    document.getElementById('locataireInfo').textContent = 'Sélectionnez une chambre';

    if (!maisonId) { selChambre.innerHTML = '<option value="">— Sélectionnez d\'abord une maison —</option>'; return; }

    fetch('{{ route("maintenance.chambres") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ maison_id: maisonId }),
    })
    .then(r => r.json())
    .then(data => {
        selChambre.innerHTML = '<option value="">— Toute la maison —</option>';
        (data.chambres || []).forEach(c => {
            selChambre.innerHTML += `<option value="${c.id}">Chambre ${c.numero_chambre}</option>`;
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
            info.textContent = 'Aucun locataire dans cette chambre';
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
            retourListe();
            injecterLigneTicket(data.ticket);
            document.getElementById('formTicket').reset();
            document.getElementById('selectChambre').innerHTML = '<option value="">— Sélectionnez d\'abord une maison —</option>';
            document.getElementById('locataireInfo').textContent = 'Sélectionnez une chambre';
            document.getElementById('inputLocataireId').value = '';
            Swal.fire({ icon: 'success', title: 'Ticket créé !', text: data.message, timer: 1400, showConfirmButton: false });
        } else {
            const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || 'Erreur.'];
            errDiv.innerHTML = '<ul class="mb-0">' + msgs.map(m => `<li>${m}</li>`).join('') + '</ul>';
            errDiv.style.display = '';
            errDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    })
    .catch(() => {
        this.disabled = false; btnText.classList.remove('d-none'); btnSpin.classList.add('d-none');
        Swal.fire('Erreur', 'Une erreur réseau est survenue.', 'error');
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

// ── Suppression ───────────────────────────────────────────────────────────────
document.getElementById('tableTickets').addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-supprimer');
    if (!btn) return;
    Swal.fire({
        title: 'Supprimer ?', text: `Supprimer le ticket : "${btn.dataset.titre}" ?`,
        icon: 'warning', showCancelButton: true,
        confirmButtonColor: '#d33', cancelButtonColor: '#6c757d',
        confirmButtonText: 'Supprimer', cancelButtonText: 'Annuler',
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
                Swal.fire({ icon: 'success', title: 'Supprimé !', timer: 1200, showConfirmButton: false });
            } else {
                Swal.fire('Erreur', data.message, 'error');
            }
        });
    });
});
</script>
@endpush
@endsection
