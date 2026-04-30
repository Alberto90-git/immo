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
        <span class="text-muted fw-light">{{ __('ui.common.home') }} /</span> {{ __('ui.edl.title') }}
      </h4>
      @can('ajoute-etat-des-lieux')
        <button type="button" class="btn btn-primary" id="btnNouvelEtat">
          <i class="bx bx-plus me-1"></i> {{ __('ui.edl.new') }}
        </button>
      @endcan
    </div>

    {{-- Filtres rapides --}}
    <div class="mb-3 d-flex gap-2 flex-wrap">
      <button class="btn btn-sm btn-outline-secondary filtre-type active" data-type="tous">{{ __('ui.edl.filter_all') }}</button>
      <button class="btn btn-sm btn-outline-primary filtre-type" data-type="entree">{{ __('ui.edl.filter_entries') }}</button>
      <button class="btn btn-sm btn-outline-warning filtre-type" data-type="sortie">{{ __('ui.edl.filter_exits') }}</button>
      <button class="btn btn-sm btn-outline-success filtre-type" data-type="signe">{{ __('ui.edl.filter_signed') }}</button>
      <button class="btn btn-sm btn-outline-danger filtre-type" data-type="degradation">{{ __('ui.edl.filter_damage') }}</button>
    </div>

    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="tableEtats">
            <thead class="table-light">
              <tr>
                <th>{{ __('ui.edl.col_tenant') }}</th>
                <th>{{ __('ui.edl.col_property') }}</th>
                <th>{{ __('ui.edl.col_type') }}</th>
                <th>{{ __('ui.edl.col_date') }}</th>
                <th>{{ __('ui.edl.col_status') }}</th>
                <th>{{ __('ui.edl.col_damages') }}</th>
                <th>{{ __('ui.edl.col_deposit') }}</th>
                <th class="text-center">{{ __('ui.edl.col_actions') }}</th>
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
                      <span class="badge bg-label-primary">{{ __('ui.edl.type_entry') }}</span>
                    @else
                      <span class="badge bg-label-warning">{{ __('ui.edl.type_exit') }}</span>
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
                        <button type="button" class="btn btn-sm btn-icon btn-outline-info btn-voir-etat"
                                data-href="{{ route('etat_des_lieux.show', $etat->id) }}" title="Voir">
                          <i class="bx bx-show"></i>
                        </button>
                      @endcan
                      @can('download-etat-des-lieux')
                        <a href="{{ route('etat_des_lieux.pdf', $etat->id) }}"
                           class="btn btn-sm btn-icon btn-outline-dark" title="PDF">
                          <i class="bx bx-file"></i>
                        </a>
                      @endcan
                      @can('delete-etat-des-lieux')
                        @if($etat->statut !== 'signe')
                          <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-supprimer"
                                  data-id="{{ $etat->id }}"
                                  data-nom="{{ $etat->locataire->nom ?? '' }} ({{ $etat->type_label }})"
                                  title="Supprimer">
                            <i class="bx bx-trash"></i>
                          </button>
                        @endif
                      @endcan
                    </div>
                  </td>
                </tr>
              @empty
                <tr id="ligneVide">
                  <td colspan="8" class="text-center text-muted py-5">
                    <i class="bx bx-clipboard fs-3 d-block mb-2"></i>
                    {{ __('ui.edl.empty') }}
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

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold mb-0">
        <span class="text-muted fw-light">{{ __('ui.edl.title') }} /</span> {{ __('ui.edl.new') }}
      </h4>
      <button type="button" class="btn btn-outline-secondary" id="btnRetourListe">
        <i class="bx bx-arrow-back me-1"></i> {{ __('ui.common.back') }}
      </button>
    </div>

    @include('etat_des_lieux._create_form')

    <div class="d-flex gap-2 justify-content-end mb-5">
      <button type="button" class="btn btn-outline-secondary" id="btnAnnulerCreate">{{ __('ui.common.cancel') }}</button>
      <button type="button" class="btn btn-primary px-4" id="btnSaveEtat">
        <span id="btnSaveText"><i class="bx bx-save me-1"></i> {{ __('ui.common.save') }}</span>
        <span id="btnSaveSpinner" class="d-none">
          <span class="spinner-border spinner-border-sm me-1"></span> {{ __('ui.common.saving') }}
        </span>
      </button>
    </div>

  </div>
  @endcan

  {{-- ══════════════════════════════════════════════════════════════════════════
       VUE DÉTAIL (masquée par défaut, chargée par fetch)
  ══════════════════════════════════════════════════════════════════════════ --}}
  <div id="viewShow" style="display:none;">
    <div id="viewShowContent"></div>
  </div>

</div>{{-- fin .container-xxl --}}

@push('scripts')
<script>
// ── Navigation entre vues ─────────────────────────────────────────────────────
const VIEWS_ETAT = ['viewListe', 'viewCreate', 'viewShow'];

function afficherVueEtat(id) {
    VIEWS_ETAT.forEach(v => {
        const el = document.getElementById(v);
        if (el) el.style.display = (v === id) ? '' : 'none';
    });
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function retourListeEtat() {
    afficherVueEtat('viewListe');
    if (typeof window.__resetCreateForm === 'function') window.__resetCreateForm();
}

document.getElementById('btnNouvelEtat')?.addEventListener('click',    () => afficherVueEtat('viewCreate'));
document.getElementById('btnRetourListe')?.addEventListener('click',   retourListeEtat);
document.getElementById('btnAnnulerCreate')?.addEventListener('click', retourListeEtat);

// ── Chargement page détail sans rechargement ──────────────────────────────────
let _currentEtatUrl = null;

function __refreshShowEtatView() {
    if (_currentEtatUrl) afficherDetailEtat(_currentEtatUrl);
}

async function afficherDetailEtat(url) {
    _currentEtatUrl = url;
    afficherVueEtat('viewShow');

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

        // Remplacer history.back() → retourListeEtat()
        content.querySelectorAll('[onclick]').forEach(el => {
            const oc = el.getAttribute('onclick');
            if (oc && (oc.includes('history.back') || oc.includes('history.go'))) {
                el.setAttribute('onclick', 'retourListeEtat()');
            }
        });

        // Exécuter les scripts inline de la page détail
        const SKIP = ['NProgress', 'display_sweet_alerte2', 'Sepatateur_Milliers', 'display_message(', 'validateAndFormatPhone'];
        doc.querySelectorAll('body script:not([src])').forEach(s => {
            const txt = s.textContent.trim();
            if (!txt || SKIP.some(p => txt.includes(p))) return;
            const ns = document.createElement('script');
            // Encapsuler dans un IIFE pour éviter les conflits de redéclaration const/let
            ns.textContent = '(function(){\n' + txt
                .replace(/location\.reload\(\)/g, '__refreshShowEtatView()')
                .replace(/history\.back\(\)/g, 'retourListeEtat()') + '\n})();';
            document.body.appendChild(ns);
            document.body.removeChild(ns);
        });
    } catch (e) {
        content.innerHTML = `
            <div class="container-p-y">
                <div class="alert alert-warning">Impossible de charger en mode SPA.
                    <a href="${url}" class="alert-link ms-1">Ouvrir en pleine page</a>
                </div>
                <button class="btn btn-outline-secondary mt-2" onclick="retourListeEtat()">
                    <i class="bx bx-arrow-back me-1"></i> Retour à la liste
                </button>
            </div>`;
    }
}

// ── Après création → charger le détail en SPA ─────────────────────────────────
window.__createFormSuccessOverride = function (data) {
    Swal.fire({ icon: 'success', title: '{{ __("ui.common.saved") }}', text: data.message, timer: 1200, showConfirmButton: false })
        .then(() => afficherDetailEtat(data.redirect));
};

// ── Injection d'une nouvelle ligne dans le tableau ────────────────────────────
function injecterLigneEtat(row) {
    const tbody = document.querySelector('#tableEtats tbody');
    const ligneVide = document.getElementById('ligneVide');
    if (ligneVide) ligneVide.remove();

    const typeBadge = row.type === 'entree'
        ? '<span class="badge bg-label-primary">{{ __("ui.edl.type_entry") }}</span>'
        : '<span class="badge bg-label-warning">{{ __("ui.edl.type_exit") }}</span>';

    const showBtn = row.can_show
        ? `<button type="button" class="btn btn-sm btn-icon btn-outline-info btn-voir-etat" data-href="${row.show_url}" title="Voir"><i class="bx bx-show"></i></button>`
        : '';
    const pdfBtn = row.can_pdf
        ? `<a href="${row.pdf_url}" class="btn btn-sm btn-icon btn-outline-dark" title="PDF" target="_blank"><i class="bx bx-file"></i></a>`
        : '';
    const delBtn = row.can_delete && row.statut !== 'signe'
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

// ── Clics sur le tableau (Voir + Supprimer) ───────────────────────────────────
document.getElementById('tableEtats').addEventListener('click', function (e) {
    const btnVoir = e.target.closest('.btn-voir-etat');
    if (btnVoir) {
        e.preventDefault();
        e.stopPropagation();
        afficherDetailEtat(btnVoir.dataset.href);
        return;
    }

    const btn = e.target.closest('.btn-supprimer');
    if (!btn) return;
    Swal.fire({
        title: '{{ __("ui.edl.delete_confirm") }}',
        text: `{{ __("ui.edl.delete_text", ["nom" => ""]) }}`.replace('', btn.dataset.nom),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '{{ __("ui.common.delete") }}',
        cancelButtonText: '{{ __("ui.common.cancel") }}',
    }).then(result => {
        if (!result.isConfirmed) return;
        fetch('{{ route("etat_des_lieux.destroy") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify({ id: btn.dataset.id }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.status) {
                btn.closest('tr').remove();
                Swal.fire({ icon: 'success', title: '{{ __("ui.common.deleted") }}', text: data.message, timer: 1500, showConfirmButton: false });
                if (!document.querySelector('#tableEtats .ligne-etat')) {
                    document.querySelector('#tableEtats tbody').innerHTML =
                        '<tr id="ligneVide"><td colspan="8" class="text-center text-muted py-5">' +
                        '<i class="bx bx-clipboard fs-3 d-block mb-2"></i>{{ __("ui.edl.empty") }}</td></tr>';
                }
            } else {
                Swal.fire('{{ __("ui.common.error") }}', data.message, 'error');
            }
        });
    });
});
</script>
@endpush
@endsection
