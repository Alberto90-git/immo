@extends('layouts.template')

@section('title')<title>Prestataires – Lokativ</title>@endsection

@push('styles')
<style>
  .iti { width: 100%; }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div class="d-flex align-items-center gap-2">
      <a href="{{ route('maintenance.index') }}" class="btn btn-icon btn-outline-secondary">
        <i class="bx bx-arrow-back"></i>
      </a>
      <h4 class="fw-bold mb-0"><span class="text-muted fw-light">Interventions /</span> Prestataires</h4>
    </div>
    <button type="button" class="btn btn-primary" id="btnNouveauPrestataire">
      <i class="bx bx-plus me-1"></i> Nouveau prestataire
    </button>
  </div>

  <div id="alertPrestataireErrors" class="alert alert-danger" style="display:none;"></div>

  <div class="card shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0" id="tablePrestataires">
          <thead class="table-light">
            <tr>
              <th>Nom</th>
              <th>Spécialité</th>
              <th>Téléphone</th>
              <th>Email</th>
              <th>Adresse</th>
              <th>Tickets actifs</th>
              <th>Statut</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody id="tbodyPrestataires">
            @forelse($prestataires as $p)
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
                    <span class="badge bg-label-success">Actif</span>
                  @else
                    <span class="badge bg-label-secondary">Inactif</span>
                  @endif
                </td>
                <td class="text-center">
                  <div class="d-flex gap-1 justify-content-center">
                    <button type="button" class="btn btn-sm btn-icon btn-outline-primary btn-modifier"
                            data-id="{{ $p->id }}" data-nom="{{ $p->nom }}"
                            data-specialite="{{ $p->specialite }}" data-telephone="{{ $p->telephone }}"
                            data-email="{{ $p->email }}" data-adresse="{{ $p->adresse }}"
                            data-actif="{{ $p->actif ? '1' : '0' }}" title="Modifier">
                      <i class="bx bx-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-supprimer"
                            data-id="{{ $p->id }}" data-nom="{{ $p->nom }}" title="Supprimer">
                      <i class="bx bx-trash"></i>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr id="ligneVide">
                <td colspan="8" class="text-center text-muted py-5">
                  <i class="bx bx-group fs-3 d-block mb-2"></i>
                  Aucun prestataire enregistré.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

{{-- Modal création / modification --}}
<div class="modal fade" id="modalPrestataire" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPrestataireTitle">Nouveau prestataire</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="editId">
        <div class="mb-3">
          <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
          <input type="text" id="inputNom" class="form-control" placeholder="Nom du prestataire">
        </div>
        <div class="row g-2 mb-3">
          <div class="col-12 col-sm-6">
            <label class="form-label fw-semibold">Téléphone</label>
            <input type="tel" id="inputTelephone" class="form-control">
          </div>
          <div class="col-12 col-sm-6">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" id="inputEmail" class="form-control" placeholder="exemple@mail.com">
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Spécialité <span class="text-danger">*</span></label>
          <select id="selectSpecialite" class="form-select">
            <option value="">— Choisir —</option>
            @foreach(App\Prestataire::SPECIALITES as $key => $info)
              <option value="{{ $key }}">{{ $info['label'] }}</option>
            @endforeach
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Adresse</label>
          <input type="text" id="inputAdresse" class="form-control" placeholder="Quartier, ville…">
        </div>
        <div id="sectionActif" style="display:none;">
          <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" id="checkActif" checked>
            <label class="form-check-label" for="checkActif">Prestataire actif</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" class="btn btn-primary" id="btnSavePrestataire">
          <i class="bx bx-save me-1"></i> Enregistrer
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const UTILS_SCRIPT_PREST = 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js';

// ── Initialisation intl-tel-input (même pattern que propriétaire) ─────────────
if (window._itiPrestataire) { try { window._itiPrestataire.destroy(); } catch(e) {} }
window._itiPrestataire = window.intlTelInput(document.getElementById('inputTelephone'), {
    initialCountry: 'tg',
    preferredCountries: ['tg', 'bj', 'ci', 'gh', 'sn', 'cm', 'ng', 'ml', 'bf', 'ne'],
    separateDialCode: true,
    utilsScript: UTILS_SCRIPT_PREST,
});
var itiPrestataire = window._itiPrestataire;

function ajaxPost(url, data) {
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify(data),
    }).then(r => r.json());
}

function getPhone() {
    try { return itiPrestataire ? (itiPrestataire.getNumber() || null) : null; } catch(e) { return null; }
}

function setPhone(number) {
    try { itiPrestataire.setNumber(number || ''); } catch(e) {}
}

function ouvrirModal() {
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalPrestataire')).show();
}
function fermerModal() {
    bootstrap.Modal.getOrCreateInstance(document.getElementById('modalPrestataire')).hide();
}

// ── Ouvrir modal création ─────────────────────────────────────────────────────
document.getElementById('btnNouveauPrestataire').addEventListener('click', () => {
    document.getElementById('modalPrestataireTitle').textContent = 'Nouveau prestataire';
    document.getElementById('editId').value            = '';
    document.getElementById('inputNom').value          = '';
    document.getElementById('selectSpecialite').value  = '';
    setPhone('');
    document.getElementById('inputEmail').value        = '';
    document.getElementById('inputAdresse').value      = '';
    document.getElementById('sectionActif').style.display = 'none';
    ouvrirModal();
});

// ── Ouvrir modal modification ─────────────────────────────────────────────────
document.getElementById('tablePrestataires').addEventListener('click', function (e) {
    const btnEdit = e.target.closest('.btn-modifier');
    if (btnEdit) {
        document.getElementById('modalPrestataireTitle').textContent = 'Modifier prestataire';
        document.getElementById('editId').value           = btnEdit.dataset.id;
        document.getElementById('inputNom').value         = btnEdit.dataset.nom;
        document.getElementById('selectSpecialite').value = btnEdit.dataset.specialite;
        const tel = btnEdit.dataset.telephone;
        setPhone(tel && tel !== 'null' ? tel : '');
        document.getElementById('inputEmail').value       = btnEdit.dataset.email !== 'null' ? btnEdit.dataset.email : '';
        document.getElementById('inputAdresse').value     = btnEdit.dataset.adresse !== 'null' ? btnEdit.dataset.adresse : '';
        document.getElementById('checkActif').checked     = btnEdit.dataset.actif === '1';
        document.getElementById('sectionActif').style.display = '';
        ouvrirModal();
        return;
    }

    const btnDel = e.target.closest('.btn-supprimer');
    if (btnDel) {
        Swal.fire({
            title: '{{ __("ui.maintenance.prest_delete_confirm") }}',
            text: '{{ __("ui.maintenance.prest_delete_text") }}'.replace(':nom', btnDel.dataset.nom),
            icon: 'warning', showCancelButton: true,
            confirmButtonColor: '#d33', confirmButtonText: '{{ __("common.btn_delete") }}', cancelButtonText: '{{ __("common.btn_cancel") }}',
        }).then(r => {
            if (!r.isConfirmed) return;
            ajaxPost('{{ route("maintenance.prestataires.destroy") }}', { id: btnDel.dataset.id })
            .then(data => {
                if (data.status) {
                    btnDel.closest('tr').remove();
                    Swal.fire({ icon: 'success', timer: 1200, showConfirmButton: false, title: '{{ __("common.swal_deleted") }}' });
                } else { Swal.fire('{{ __("common.swal_error") }}', data.message, 'error'); }
            });
        });
    }
});

// ── Sauvegarder prestataire ───────────────────────────────────────────────────
document.getElementById('btnSavePrestataire').addEventListener('click', function () {
    const id         = document.getElementById('editId').value;
    const isEdit     = !!id;
    const payload    = {
        id:          id || undefined,
        nom:         document.getElementById('inputNom').value.trim(),
        specialite:  document.getElementById('selectSpecialite').value,
        telephone:   getPhone(),
        email:       document.getElementById('inputEmail').value.trim() || null,
        adresse:     document.getElementById('inputAdresse').value.trim() || null,
        actif:       document.getElementById('checkActif').checked ? 1 : 0,
    };

    if (!payload.nom || !payload.specialite) {
        Swal.fire('{{ __("ui.maintenance.missing_fields") }}', '{{ __("ui.maintenance.missing_fields_text") }}', 'warning');
        return;
    }

    const url = isEdit ? '{{ route("maintenance.prestataires.update") }}' : '{{ route("maintenance.prestataires.store") }}';

    ajaxPost(url, payload).then(data => {
        if (data.status) {
            fermerModal();
            Swal.fire({ icon: 'success', title: isEdit ? '{{ __("ui.maintenance.modified") }}' : '{{ __("ui.maintenance.added") }}', text: data.message, timer: 1500, showConfirmButton: false });

            const tbody = document.getElementById('tbodyPrestataires');
            const ligneVide = document.getElementById('ligneVide');
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
                    // Mettre à jour data-* pour la prochaine édition
                    const btnE = tr.querySelector('.btn-modifier');
                    if (btnE) {
                        btnE.dataset.nom        = p.nom;
                        btnE.dataset.specialite = p.specialite;
                        btnE.dataset.telephone  = p.telephone;
                        btnE.dataset.email      = p.email;
                        btnE.dataset.adresse    = p.adresse;
                        btnE.dataset.actif      = p.actif ? '1' : '0';
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
                    <td><span class="badge bg-label-success">{{ __("ui.maintenance.prest_active") }}</span></td>
                    <td class="text-center">
                        <div class="d-flex gap-1 justify-content-center">
                            <button type="button" class="btn btn-sm btn-icon btn-outline-primary btn-modifier"
                                data-id="${p.id}" data-nom="${p.nom}" data-specialite="${p.specialite}"
                                data-telephone="${p.telephone}" data-email="${p.email}" data-adresse="${p.adresse}" data-actif="1" title="{{ __('common.title_edit') }}">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-icon btn-outline-danger btn-supprimer"
                                data-id="${p.id}" data-nom="${p.nom}" title="{{ __('common.title_delete') }}">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>`;
                tbody.insertBefore(tr, tbody.firstChild);
            }
        } else {
            const msgs = data.errors ? Object.values(data.errors).flat() : [data.message || '{{ __("common.swal_generic_error") }}'];
            Swal.fire('{{ __("common.swal_error") }}', msgs.join('\n'), 'error');
        }
    }).catch(() => {
        Swal.fire('{{ __("common.swal_network_error") }}', '{{ __("common.swal_network_msg") }}', 'error');
    });
});
</script>
@endpush
@endsection
