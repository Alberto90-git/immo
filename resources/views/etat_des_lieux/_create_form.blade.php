{{--
  Partial : formulaire création état des lieux
  Variables attendues : $locataires, $pieces_defaut, $elements_defaut
  Callback succès    : window.__createFormSuccessOverride(data) — si absent → redirect
--}}

{{-- Tom Select (recherche dans les selects) --}}
@once
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<style>
  .ts-wrapper.form-select { padding: 0; }
  .ts-control { border: none !important; box-shadow: none !important; min-height: 38px; }
</style>
@endonce

<div id="alertEtatErrors" class="alert alert-danger" style="display:none;"></div>

<form id="formEtat">
@csrf

{{-- ── Informations générales ──────────────────────────────────────────────── --}}
<div class="card mb-4 shadow-sm">
  <div class="card-header bg-primary">
    <h6 class="mb-0 text-white"><i class="bx bx-info-circle me-2"></i>{{ __('ui.edl.form_info') }}</h6>
  </div>
  <div class="card-body">
    <div class="row g-3">
      <div class="col-md-5">
        <label class="form-label fw-semibold">{{ __('ui.edl.form_tenant') }} <span class="text-danger">*</span></label>
        <select name="locataire_id" id="selectLocataire" class="form-select" required>
          <option value="">{{ __('ui.common.choose') }}</option>
          @foreach($locataires as $loc)
            <option value="{{ $loc->id }}">{{ $loc->nom }} {{ $loc->prenom }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">{{ __('ui.edl.form_type') }} <span class="text-danger">*</span></label>
        <div class="d-flex gap-3 mt-2">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="type" id="typeEntree" value="entree" checked required>
            <label class="form-check-label" for="typeEntree"><span class="badge bg-primary">{{ __('ui.edl.type_entry') }}</span></label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="type" id="typeSortie" value="sortie">
            <label class="form-check-label" for="typeSortie"><span class="badge bg-warning text-dark">{{ __('ui.edl.type_exit') }}</span></label>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold">{{ __('ui.edl.form_date') }} <span class="text-danger">*</span></label>
        <input type="date" name="date_etat" class="form-control" value="{{ date('Y-m-d') }}" required>
      </div>
    </div>

    {{-- Alerte état d'entrée --}}
    <div id="alerteEntree" class="mt-3" style="display:none;">
      <div id="entreeOk" class="alert alert-success d-none mb-0 py-2">
        <i class="bx bx-check-circle me-1"></i>
        {!! __('ui.edl.entry_loaded', ['date' => '<strong id="dateEntreeLabel"></strong>']) !!}
        <input type="hidden" name="etat_entree_id" id="etatEntreeId">
      </div>
      <div id="entreeKo" class="alert alert-warning d-none mb-0 py-2">
        <i class="bx bx-error-circle me-1"></i>
        {{ __('ui.edl.entry_not_found') }}
      </div>
      <div id="entreeLoading" class="text-muted d-none py-2">
        <div class="spinner-border spinner-border-sm me-1"></div> {{ __('ui.edl.entry_loading') }}
      </div>
    </div>
  </div>
</div>

{{-- ── Pièces & observations ────────────────────────────────────────────────── --}}
<div class="card mb-4 shadow-sm">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h6 class="mb-0"><i class="bx bx-home-circle me-2"></i>{{ __('ui.edl.form_pieces') }}</h6>
    <button type="button" class="btn btn-sm btn-outline-primary" id="btnAjouterPiece">
      <i class="bx bx-plus me-1"></i> {{ __('ui.edl.form_add_piece') }}
    </button>
  </div>
  <div class="card-body p-0">
    <div class="accordion accordion-flush" id="accordionPieces">

      @foreach($pieces_defaut as $idx => $nomPiece)
      <div class="accordion-item piece-block" data-index="{{ $idx }}">
        <h2 class="accordion-header">
          {{-- Pas de data-bs-toggle : toggle géré manuellement en JS --}}
          <button class="accordion-button {{ $idx > 0 ? 'collapsed' : '' }} py-3" type="button"
                  data-piece-target="#piece{{ $idx }}">
            <div class="form-check me-3 d-flex align-items-center" onclick="event.stopPropagation()">
              <input class="form-check-input piece-check me-2" type="checkbox"
                     name="pieces[{{ $idx }}][inclus]" value="1" id="check{{ $idx }}" checked>
              <input type="hidden" name="pieces[{{ $idx }}][nom_piece]" value="{{ $nomPiece }}">
            </div>
            <span class="fw-semibold">{{ $nomPiece }}</span>
            <span class="badge bg-label-danger ms-2 degra-badge-{{ $idx }}" style="display:none;"></span>
          </button>
        </h2>
        <div id="piece{{ $idx }}" class="accordion-collapse collapse {{ $idx === 0 ? 'show' : '' }}">
          <div class="accordion-body p-0">
            <div class="table-responsive">
              <table class="table table-bordered mb-0 align-middle">
                <thead class="table-light text-center small">
                  <tr>
                    <th style="width:18%">{{ __('ui.edl.element') }}</th>
                    <th id="colEntree{{ $idx }}" style="display:none; width:14%">{{ __('ui.edl.entry_col') }}</th>
                    <th style="width:16%">{{ __('ui.edl.state_col') }} <span class="text-danger">*</span></th>
                    <th>{{ __('ui.edl.col_obs') }}</th>
                    <th id="colRetenue{{ $idx }}" style="display:none; width:14%">{{ __('ui.edl.retenue_col') }} ({{ get_symbole_devise() }})</th>
                    <th id="colDegra{{ $idx }}" style="display:none; width:12%">{{ __('ui.edl.degra_col') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($elements_defaut as $element)
                  <tr>
                    <td class="fw-semibold small ps-3">{{ $element }}</td>
                    <td id="entree{{ $idx }}_{{ $loop->index }}" class="text-center col-entree-{{ $idx }}" style="display:none;">
                      <span class="badge bg-secondary etat-entree-badge">–</span>
                      <input type="hidden" class="val-entree" data-element="{{ $element }}" value="">
                    </td>
                    <td>
                      <select name="pieces[{{ $idx }}][elements][{{ $element }}][etat]"
                              class="form-select form-select-sm select-etat"
                              data-piece-idx="{{ $idx }}" data-element="{{ $element }}">
                        <option value="tres_bon">{{ __('ui.edl.state_tres_bon') }}</option>
                        <option value="bon" selected>{{ __('ui.edl.state_bon') }}</option>
                        <option value="moyen">{{ __('ui.edl.state_moyen') }}</option>
                        <option value="mauvais">{{ __('ui.edl.state_mauvais') }}</option>
                        <option value="hors_service">{{ __('ui.edl.state_hors_service') }}</option>
                      </select>
                    </td>
                    <td>
                      <input type="text" name="pieces[{{ $idx }}][elements][{{ $element }}][description]"
                             class="form-control form-control-sm" placeholder="{{ __('ui.common.details_ph') }}">
                    </td>
                    <td class="col-retenue-{{ $idx }}" style="display:none;">
                      <input type="number" name="pieces[{{ $idx }}][elements][{{ $element }}][montant_retenue]"
                             class="form-control form-control-sm input-retenue" min="0" value="0" step="100">
                    </td>
                    <td class="text-center col-degra-{{ $idx }}" style="display:none;">
                      <span class="badge degra-flag bg-secondary">–</span>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      @endforeach

    </div>
  </div>
</div>

{{-- ── Notes générales ──────────────────────────────────────────────────────── --}}
<div class="card mb-4 shadow-sm">
  <div class="card-header">
    <h6 class="mb-0"><i class="bx bx-note me-2"></i>{{ __('ui.edl.form_notes') }}</h6>
  </div>
  <div class="card-body">
    <textarea name="notes_generales" class="form-control" rows="3"
              placeholder="{{ __('ui.edl.form_notes_ph') }}"></textarea>
  </div>
</div>

</form>

{{-- Modal ajout pièce personnalisée --}}
<div class="modal fade" id="modalNouveauPiece" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">{{ __('ui.edl.modal_new_piece') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label class="form-label">{{ __('ui.edl.piece_name') }}</label>
        <input type="text" id="inputNouveauPiece" class="form-control" placeholder="{{ __('ui.edl.piece_name_ph') }}">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('ui.common.cancel') }}</button>
        <button type="button" class="btn btn-primary" id="btnConfirmNouveauPiece">{{ __('ui.edl.form_add') }}</button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
(function () { // IIFE — évite les conflits de variables si inclus 2×

const QUALITE     = { tres_bon: 5, bon: 4, moyen: 3, mauvais: 2, hors_service: 1 };
const ETAT_LABELS = { tres_bon: '{{ __("ui.edl.state_tres_bon") }}', bon: '{{ __("ui.edl.state_bon") }}', moyen: '{{ __("ui.edl.state_moyen") }}', mauvais: '{{ __("ui.edl.state_mauvais") }}', hors_service: '{{ __("ui.edl.state_hors_service") }}' };
const ETAT_CLASSES= { tres_bon: 'bg-success', bon: 'bg-info', moyen: 'bg-warning text-dark', mauvais: 'bg-danger', hors_service: 'bg-dark' };

let entreeData = null;
let pieceIndex = {{ count($pieces_defaut) }};
let isSortie   = false;

// ── Tom Select : recherche locataire ─────────────────────────────────────────
const tsLocataire = new TomSelect('#selectLocataire', {
    placeholder: '{{ __("ui.edl.form_tenant_ph") }}',
    allowEmptyOption: true,
    sortField: { field: 'text', direction: 'asc' },
    onChange: function () {
        if (isSortie) chargerEntree();
    },
});

// ── Toggle accordéon (manuel — bidirectionnel) ────────────────────────────────
document.getElementById('accordionPieces').addEventListener('click', function (e) {
    const btn = e.target.closest('.accordion-button[data-piece-target]');
    if (!btn || e.target.closest('.form-check')) return;

    const targetSel = btn.getAttribute('data-piece-target');
    const collapseEl = document.querySelector(targetSel);
    if (!collapseEl) return;

    const bsCollapse = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
    if (collapseEl.classList.contains('show')) {
        bsCollapse.hide();
        btn.classList.add('collapsed');
        btn.setAttribute('aria-expanded', 'false');
    } else {
        bsCollapse.show();
        btn.classList.remove('collapsed');
        btn.setAttribute('aria-expanded', 'true');
    }
});

// ── Détection type ────────────────────────────────────────────────────────────
document.querySelectorAll('input[name="type"]').forEach(radio => {
    radio.addEventListener('change', function () {
        isSortie = this.value === 'sortie';
        toggleSortieColumns();
        if (isSortie) chargerEntree();
    });
});

function chargerEntree() {
    const locataireId = tsLocataire.getValue();
    const alerteDiv   = document.getElementById('alerteEntree');
    alerteDiv.style.display = '';
    document.getElementById('entreeOk').classList.add('d-none');
    document.getElementById('entreeKo').classList.add('d-none');
    document.getElementById('entreeLoading').classList.remove('d-none');
    if (!locataireId) { alerteDiv.style.display = 'none'; return; }

    fetch('{{ route("etat_des_lieux.entree_data") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
        body: JSON.stringify({ locataire_id: locataireId }),
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('entreeLoading').classList.add('d-none');
        if (data.status) {
            document.getElementById('entreeOk').classList.remove('d-none');
            document.getElementById('dateEntreeLabel').textContent = data.date_entree;
            document.getElementById('etatEntreeId').value = data.etat_entree_id;
            entreeData = {};
            data.pieces.forEach(p => { entreeData[p.nom_piece] = p.elements; });
            remplirComparaison();
        } else {
            document.getElementById('entreeKo').classList.remove('d-none');
            document.getElementById('etatEntreeId').value = '';
            entreeData = null;
        }
    });
}

function toggleSortieColumns() {
    document.querySelectorAll('.piece-block').forEach(block => {
        const idx  = block.dataset.index;
        ['colEntree','colRetenue','colDegra'].forEach(col => {
            const th = document.getElementById(col + idx);
            if (th) th.style.display = isSortie ? '' : 'none';
        });
        block.querySelectorAll(`.col-entree-${idx},.col-retenue-${idx},.col-degra-${idx}`)
             .forEach(td => { td.style.display = isSortie ? '' : 'none'; });
    });
    if (!isSortie) { document.getElementById('alerteEntree').style.display = 'none'; entreeData = null; }
}

function remplirComparaison() {
    if (!entreeData) return;
    document.querySelectorAll('.piece-block').forEach(block => {
        const idx      = block.dataset.index;
        const nomPiece = block.querySelector(`input[name="pieces[${idx}][nom_piece]"]`)?.value;
        const pieceE   = entreeData[nomPiece];
        block.querySelectorAll('.select-etat').forEach(select => {
            const element = select.dataset.element;
            const row     = select.closest('tr');
            const badge   = row.querySelector('.etat-entree-badge');
            const valInput= row.querySelector('.val-entree');
            if (pieceE && pieceE[element]) {
                const etatE = pieceE[element].etat;
                if (badge) { badge.textContent = ETAT_LABELS[etatE] || etatE; badge.className = 'badge etat-entree-badge ' + (ETAT_CLASSES[etatE] || 'bg-secondary'); }
                if (valInput) valInput.value = etatE;
            }
            select.addEventListener('change', () => calculerDegradation(select, row, nomPiece, idx));
            calculerDegradation(select, row, nomPiece, idx);
        });
    });
}

function calculerDegradation(select, row, nomPiece, pieceIdx) {
    if (!isSortie || !entreeData) return;
    const element   = select.dataset.element;
    const pieceE    = entreeData[nomPiece];
    const degraSpan = row.querySelector('.degra-flag');
    const retenue   = row.querySelector('.input-retenue');
    if (!pieceE || !pieceE[element]) { if (degraSpan) degraSpan.textContent = '–'; return; }
    const degra = (QUALITE[select.value] || 4) < (QUALITE[pieceE[element].etat] || 4);
    if (degraSpan) { degraSpan.textContent = degra ? '{{ __("ui.edl.damage_yes") }}' : '{{ __("ui.edl.damage_no") }}'; degraSpan.className = 'badge degra-flag ' + (degra ? 'bg-danger' : 'bg-success'); }
    if (retenue) retenue.readOnly = !degra;
    const pieceBadge = document.querySelector(`.degra-badge-${pieceIdx}`);
    if (pieceBadge) {
        const n = document.querySelectorAll(`.piece-block[data-index="${pieceIdx}"] .degra-flag.bg-danger`).length;
        pieceBadge.textContent = n > 0 ? `${n}{{ __("ui.edl.damage_badge", ["n" => ""]) }}` : '';
        pieceBadge.style.display = n > 0 ? '' : 'none';
    }
}

// ── Soumission AJAX ───────────────────────────────────────────────────────────
document.getElementById('btnSaveEtat').addEventListener('click', function () {
    const form    = document.getElementById('formEtat');
    const errDiv  = document.getElementById('alertEtatErrors');
    const btnText = document.getElementById('btnSaveText');
    const btnSpin = document.getElementById('btnSaveSpinner');

    errDiv.style.display = 'none';
    errDiv.innerHTML = '';
    this.disabled = true;
    btnText.classList.add('d-none');
    btnSpin.classList.remove('d-none');

    fetch('{{ route("etat_des_lieux.store") }}', {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
    })
    .then(r => r.json())
    .then(data => {
        this.disabled = false;
        btnText.classList.remove('d-none');
        btnSpin.classList.add('d-none');

        if (data.status) {
            if (typeof window.__createFormSuccessOverride === 'function') {
                window.__createFormSuccessOverride(data);
            } else {
                Swal.fire({ icon: 'success', title: '{{ __("ui.common.saved") }}', text: data.message, timer: 1500, showConfirmButton: false })
                    .then(() => { window.location.href = data.redirect; });
            }
        } else {
            if (data.errors) {
                const msgs = Object.values(data.errors).flat();
                errDiv.innerHTML = '<ul class="mb-0">' + msgs.map(m => `<li>${m}</li>`).join('') + '</ul>';
            } else {
                errDiv.innerHTML = data.message || '{{ __("ui.common.error") }}';
            }
            errDiv.style.display = '';
            errDiv.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    })
    .catch(() => {
        this.disabled = false;
        btnText.classList.remove('d-none');
        btnSpin.classList.add('d-none');
        Swal.fire('{{ __("ui.common.error") }}', '{{ __("ui.common.network_error") }}', 'error');
    });
});

// ── Ajout pièce personnalisée ─────────────────────────────────────────────────
document.getElementById('btnAjouterPiece').addEventListener('click', () => {
    new bootstrap.Modal(document.getElementById('modalNouveauPiece')).show();
});
document.getElementById('btnConfirmNouveauPiece').addEventListener('click', () => {
    const nom = document.getElementById('inputNouveauPiece').value.trim();
    if (!nom) return;
    ajouterPieceAccordion(nom);
    document.getElementById('inputNouveauPiece').value = '';
    bootstrap.Modal.getInstance(document.getElementById('modalNouveauPiece')).hide();
});

function ajouterPieceAccordion(nomPiece) {
    const idx      = pieceIndex++;
    const elements = @json($elements_defaut);
    const rowsHtml = elements.map(el => `
      <tr>
        <td class="fw-semibold small ps-3">${el}</td>
        ${isSortie ? `<td class="text-center col-entree-${idx}"><span class="badge bg-secondary etat-entree-badge">–</span><input type="hidden" class="val-entree" data-element="${el}" value=""></td>` : ''}
        <td><select name="pieces[${idx}][elements][${el}][etat]" class="form-select form-select-sm select-etat" data-piece-idx="${idx}" data-element="${el}">
          <option value="tres_bon">{{ __("ui.edl.state_tres_bon") }}</option><option value="bon" selected>{{ __("ui.edl.state_bon") }}</option>
          <option value="moyen">{{ __("ui.edl.state_moyen") }}</option><option value="mauvais">{{ __("ui.edl.state_mauvais") }}</option>
          <option value="hors_service">{{ __("ui.edl.state_hors_service") }}</option>
        </select></td>
        <td><input type="text" name="pieces[${idx}][elements][${el}][description]" class="form-control form-control-sm" placeholder="{{ __("ui.common.details_ph") }}"></td>
        ${isSortie ? `<td class="col-retenue-${idx}"><input type="number" name="pieces[${idx}][elements][${el}][montant_retenue]" class="form-control form-control-sm input-retenue" min="0" value="0" step="100"></td>` : ''}
        ${isSortie ? `<td class="text-center col-degra-${idx}"><span class="badge degra-flag bg-secondary">–</span></td>` : ''}
      </tr>`).join('');

    const html = `
    <div class="accordion-item piece-block" data-index="${idx}">
      <h2 class="accordion-header">
        <button class="accordion-button py-3" type="button" data-piece-target="#piece${idx}">
          <div class="form-check me-3 d-flex align-items-center" onclick="event.stopPropagation()">
            <input class="form-check-input piece-check me-2" type="checkbox" name="pieces[${idx}][inclus]" value="1" id="check${idx}" checked>
            <input type="hidden" name="pieces[${idx}][nom_piece]" value="${nomPiece}">
          </div>
          <span class="fw-semibold">${nomPiece}</span>
          <span class="badge bg-label-danger ms-2 degra-badge-${idx}" style="display:none;"></span>
        </button>
      </h2>
      <div id="piece${idx}" class="accordion-collapse collapse show">
        <div class="accordion-body p-0">
          <div class="table-responsive">
            <table class="table table-bordered mb-0 align-middle">
              <thead class="table-light text-center small">
                <tr>
                  <th style="width:18%">{{ __("ui.edl.element") }}</th>
                  ${isSortie ? `<th id="colEntree${idx}" style="width:14%">{{ __("ui.edl.entry_col") }}</th>` : ''}
                  <th style="width:16%">{{ __("ui.edl.state_col") }} *</th><th>{{ __("ui.edl.col_obs") }}</th>
                  ${isSortie ? `<th id="colRetenue${idx}" style="width:14%">{{ __("ui.edl.retenue_col") }} ({{ get_symbole_devise() }})</th><th id="colDegra${idx}" style="width:12%">{{ __("ui.edl.degra_col") }}</th>` : ''}
                </tr>
              </thead>
              <tbody>${rowsHtml}</tbody>
            </table>
          </div>
        </div>
      </div>
    </div>`;

    document.getElementById('accordionPieces').insertAdjacentHTML('beforeend', html);
    if (isSortie) remplirComparaison();
}

// ── Réinitialisation du formulaire (appelée depuis l'extérieur) ───────────────
window.__resetCreateForm = function () {
    document.getElementById('formEtat').reset();
    document.getElementById('alertEtatErrors').style.display = 'none';
    document.getElementById('alerteEntree').style.display = 'none';

    // Supprimer les pièces ajoutées dynamiquement (au-delà des pièces par défaut)
    const defaultCount = {{ count($pieces_defaut) }};
    document.getElementById('accordionPieces').querySelectorAll('.piece-block').forEach(block => {
        if (parseInt(block.dataset.index) >= defaultCount) block.remove();
    });

    // Réinitialiser l'accordéon : ouvrir la 1ère pièce, fermer les autres
    document.querySelectorAll('#accordionPieces .piece-block').forEach((block, i) => {
        const idx       = block.dataset.index;
        const collapseEl = document.getElementById('piece' + idx);
        if (!collapseEl) return;
        const bsCol = bootstrap.Collapse.getOrCreateInstance(collapseEl, { toggle: false });
        const btn   = block.querySelector('.accordion-button');
        if (i === 0) {
            bsCol.show();
            if (btn) { btn.classList.remove('collapsed'); btn.setAttribute('aria-expanded', 'true'); }
        } else {
            bsCol.hide();
            if (btn) { btn.classList.add('collapsed'); btn.setAttribute('aria-expanded', 'false'); }
        }
    });

    // Reset Tom Select locataire
    tsLocataire.clear(true);

    // Reset état interne
    isSortie   = false;
    entreeData = null;
    pieceIndex = {{ count($pieces_defaut) }};
    toggleSortieColumns();
};

})(); // fin IIFE
</script>
@endpush
