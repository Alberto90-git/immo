@extends('layouts.template')

@section('title')
<title>États des lieux – Lokativ</title>
@endsection

@section('content')
@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">

  {{-- ══════════════════════════════════════════════════════════════════════════
       VUE LISTE
  ══════════════════════════════════════════════════════════════════════════ --}}
  <div id="viewListe">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold mb-0">
        <span class="text-muted fw-light">Accueil /</span> États des lieux
      </h4>
      @can('ajoute-etat-des-lieux')
        <button type="button" class="btn btn-primary" id="btnNouvelEtat">
          <i class="bx bx-plus me-1"></i> Nouvel état des lieux
        </button>
      @endcan
    </div>

    {{-- Filtres rapides --}}
    <div class="mb-3 d-flex gap-2 flex-wrap">
      <button class="btn btn-sm btn-outline-secondary filtre-type active" data-type="tous">Tous</button>
      <button class="btn btn-sm btn-outline-primary filtre-type" data-type="entree">Entrées</button>
      <button class="btn btn-sm btn-outline-warning filtre-type" data-type="sortie">Sorties</button>
      <button class="btn btn-sm btn-outline-success filtre-type" data-type="signe">Signés</button>
      <button class="btn btn-sm btn-outline-danger filtre-type" data-type="degradation">Avec dégradations</button>
    </div>

    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="tableEtats">
            <thead class="table-light">
              <tr>
                <th>Locataire</th>
                <th>Logement</th>
                <th>Type</th>
                <th>Date</th>
                <th>Statut</th>
                <th>Dégradations</th>
                <th>Retenue</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @forelse($etats as $etat)
                @php
                  $nbDegradations = 0;
                  foreach ($etat->pieces as $piece) {
                      $nbDegradations += $piece->elements->where('degradation_detectee', true)->count();
                  }
                @endphp
                <tr class="ligne-etat"
                    data-type="{{ $etat->type }}"
                    data-statut="{{ $etat->statut }}"
                    data-degradation="{{ $nbDegradations > 0 ? '1' : '0' }}">
                  <td><strong>{{ $etat->locataire->nom ?? '–' }} {{ $etat->locataire->prenom ?? '' }}</strong></td>
                  <td class="text-muted small">
                    {{ $etat->maison->nom_maison ?? '–' }} – {{ $etat->chambre->numero_chambre ?? '–' }}
                  </td>
                  <td>
                    @if($etat->type === 'entree')
                      <span class="badge bg-label-primary">Entrée</span>
                    @else
                      <span class="badge bg-label-warning">Sortie</span>
                    @endif
                  </td>
                  <td>{{ $etat->date_etat->format('d/m/Y') }}</td>
                  <td>{!! $etat->statut_badge !!}</td>
                  <td>
                    @if($nbDegradations > 0)
                      <span class="badge bg-danger">{{ $nbDegradations }} dégradation(s)</span>
                    @else
                      <span class="text-muted small">–</span>
                    @endif
                  </td>
                  <td>
                    @if($etat->retenue_caution > 0)
                      <span class="fw-semibold text-danger">{{ number_format($etat->retenue_caution, 0, ',', ' ') }} F</span>
                    @else
                      <span class="text-muted small">–</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="d-flex gap-1 justify-content-center">
                      @can('Consulter-etat-des-lieux')
                        <a href="{{ route('etat_des_lieux.show', $etat->id) }}"
                           class="btn btn-sm btn-icon btn-outline-info" title="Voir">
                          <i class="bx bx-show"></i>
                        </a>
                      @endcan
                      @can('download-etat-des-lieux')
                        <a href="{{ route('etat_des_lieux.pdf', $etat->id) }}"
                           class="btn btn-sm btn-icon btn-outline-dark" title="PDF">
                          <i class="bx bx-file"></i>
                        </a>
                      @endcan
                      @can('delete-etat-des-lieux')
                        <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-supprimer"
                                data-id="{{ $etat->id }}"
                                data-nom="{{ $etat->locataire->nom ?? '' }} ({{ $etat->type_label }})"
                                title="Supprimer">
                          <i class="bx bx-trash"></i>
                        </button>
                      @endcan
                    </div>
                  </td>
                </tr>
              @empty
                <tr id="ligneVide">
                  <td colspan="8" class="text-center text-muted py-5">
                    <i class="bx bx-clipboard fs-3 d-block mb-2"></i>
                    Aucun état des lieux enregistré.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>{{-- fin #viewListe --}}


  {{-- ══════════════════════════════════════════════════════════════════════════
       VUE FORMULAIRE (masquée par défaut)
  ══════════════════════════════════════════════════════════════════════════ --}}
  @can('ajoute-etat-des-lieux')
  <div id="viewCreate" style="display:none;">

    <div class="d-flex align-items-center mb-4 gap-2">
      <button type="button" class="btn btn-icon btn-outline-secondary" id="btnRetourListe" title="Retour à la liste">
        <i class="bx bx-arrow-back"></i>
      </button>
      <h4 class="fw-bold mb-0">
        <span class="text-muted fw-light">États des lieux /</span> Nouveau
      </h4>
    </div>

    @include('etat_des_lieux._create_form')

    <div class="d-flex gap-2 justify-content-end mb-5">
      <button type="button" class="btn btn-outline-secondary" id="btnAnnulerCreate">Annuler</button>
      <button type="button" class="btn btn-primary px-4" id="btnSaveEtat">
        <span id="btnSaveText"><i class="bx bx-save me-1"></i> Enregistrer</span>
        <span id="btnSaveSpinner" class="d-none">
          <span class="spinner-border spinner-border-sm me-1"></span> En cours...
        </span>
      </button>
    </div>

  </div>
  @endcan

</div>{{-- fin .container-xxl --}}

@push('scripts')
<script>
// ── Basculer liste ↔ formulaire ───────────────────────────────────────────────
function afficherFormulaire() {
    document.getElementById('viewListe').style.display  = 'none';
    document.getElementById('viewCreate').style.display = '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function afficherListe() {
    document.getElementById('viewCreate').style.display = 'none';
    document.getElementById('viewListe').style.display  = '';
    window.scrollTo({ top: 0, behavior: 'smooth' });
    if (typeof window.__resetCreateForm === 'function') window.__resetCreateForm();
}

document.getElementById('btnNouvelEtat')?.addEventListener('click', afficherFormulaire);
document.getElementById('btnRetourListe')?.addEventListener('click', afficherListe);
document.getElementById('btnAnnulerCreate')?.addEventListener('click', afficherListe);

// ── Callback succès création → redirection vers la page détail ───────────────
window.__createFormSuccessOverride = function (data) {
    Swal.fire({ icon: 'success', title: 'Enregistré !', text: data.message, timer: 1200, showConfirmButton: false })
        .then(() => { window.location.href = data.redirect; });
};

// ── Injection d'une nouvelle ligne dans le tableau ────────────────────────────
function injecterLigneEtat(row) {
    const tbody = document.querySelector('#tableEtats tbody');

    const ligneVide = document.getElementById('ligneVide');
    if (ligneVide) ligneVide.remove();

    const typeBadge = row.type === 'entree'
        ? '<span class="badge bg-label-primary">Entrée</span>'
        : '<span class="badge bg-label-warning">Sortie</span>';

    const showBtn = row.can_show
        ? `<a href="${row.show_url}" class="btn btn-sm btn-icon btn-outline-info" title="Voir"><i class="bx bx-show"></i></a>`
        : '';
    const pdfBtn = row.can_pdf
        ? `<a href="${row.pdf_url}" class="btn btn-sm btn-icon btn-outline-dark" title="PDF" target="_blank"><i class="bx bx-file-pdf"></i></a>`
        : '';
    const delBtn = row.can_delete
        ? `<button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-supprimer" data-id="${row.id}" data-nom="${row.locataire} (${row.type_label})" title="Supprimer"><i class="bx bx-trash"></i></button>`
        : '';

    const tr = document.createElement('tr');
    tr.className = 'ligne-etat';
    tr.dataset.type        = row.type;
    tr.dataset.statut      = 'brouillon';
    tr.dataset.degradation = '0';
    tr.innerHTML = `
        <td><strong>${row.locataire}</strong></td>
        <td class="text-muted small">${row.maison}</td>
        <td>${typeBadge}</td>
        <td>${row.date_etat}</td>
        <td>${row.statut_badge}</td>
        <td><span class="text-muted small">–</span></td>
        <td><span class="text-muted small">–</span></td>
        <td class="text-center">
            <div class="d-flex gap-1 justify-content-center">${showBtn}${pdfBtn}${delBtn}</div>
        </td>`;
    tbody.insertBefore(tr, tbody.firstChild);
}

// ── Filtres par type/statut ───────────────────────────────────────────────────
document.querySelectorAll('.filtre-type').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.filtre-type').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const type = this.dataset.type;
        document.querySelectorAll('.ligne-etat').forEach(row => {
            let visible = false;
            if (type === 'tous')                              visible = true;
            else if (type === 'entree' || type === 'sortie') visible = row.dataset.type === type;
            else if (type === 'signe')                        visible = row.dataset.statut === 'signe';
            else if (type === 'degradation')                  visible = row.dataset.degradation === '1';
            row.style.display = visible ? '' : 'none';
        });
    });
});

// ── Suppression (délégation → fonctionne aussi sur les lignes injectées) ───────
document.getElementById('tableEtats').addEventListener('click', function (e) {
    const btn = e.target.closest('.btn-supprimer');
    if (!btn) return;
    const id  = btn.dataset.id;
    const nom = btn.dataset.nom;
    Swal.fire({
        title: 'Supprimer ?',
        text: `Voulez-vous supprimer l'état des lieux de : ${nom} ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Supprimer',
        cancelButtonText: 'Annuler',
    }).then(result => {
        if (!result.isConfirmed) return;
        fetch('{{ route("etat_des_lieux.destroy") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ id }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                btn.closest('tr').remove();
                Swal.fire({ icon: 'success', title: 'Supprimé !', text: data.message, timer: 1500, showConfirmButton: false });
                if (!document.querySelector('#tableEtats .ligne-etat')) {
                    document.querySelector('#tableEtats tbody').innerHTML =
                        '<tr id="ligneVide"><td colspan="8" class="text-center text-muted py-5">' +
                        '<i class="bx bx-clipboard fs-3 d-block mb-2"></i>Aucun état des lieux enregistré.</td></tr>';
                }
            } else {
                Swal.fire('Erreur', data.message, 'error');
            }
        });
    });
});
</script>
@endpush
@endsection
