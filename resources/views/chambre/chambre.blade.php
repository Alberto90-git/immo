@extends('layouts.template')

@section('title')
<title>{{ __('pages.room_title') }}</title>
@endsection

@section('content')

<style>
    .swal2-container { z-index: 10070 !important; }
    .swal2-popup    { z-index: 10071 !important; }
</style>

@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('common.home_breadcrumb') }}</span> {{ __('pages.room_breadcrumb') }}</h4>

    @can('ajoute-chambre')
        <div class="col-md-6 mb-3">
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary"
                    data-bs-toggle="modal" data-bs-target="#AjouerChambre">
                <span class="bx bx-plus"></span>
            </button>
        </div>
    @endcan

    {{-- Modal Ajout --}}
    <div class="modal fade" id="AjouerChambre" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">{{ __('pages.room_add_modal') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('common.btn_close') }}"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" id="formAjouterChambre">
                        @csrf
                        <div class="col-12">
                            <label class="form-label">{{ __('pages.room_choose_house') }} <span class="text-danger">*</span></label>
                            <select required class="form-select" name="nom_maison" id="nom_maison">
                                <option selected disabled value="">{{ __('pages.room_choose_house_opt') }}</option>
                                @if(isset($allMaison))
                                    @foreach($allMaison as $terme)
                                        <option value="{{ $terme->id }}">{{ $terme->nom_maison }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('pages.room_number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="numero_chambre" class="form-control"
                                   onkeypress="return /[0-9]/i.test(event.key)" id="numero_chambre" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('pages.room_type') }} <span class="text-danger">*</span></label>
                            <select required class="form-select" name="type_chambre" id="type_chambre">
                                <option selected disabled value="">{{ __('pages.room_type_opt') }}</option>
                                @foreach(['Entrée couche ordinaire','Entrée couche semi-sanitaire','Entrée couche sanitaire','Chambre salon ordinaire','Chambre salon semi-sanitaire','Chambre salon sanitaire','2Chambre salon ordinaire','2Chambre salon semi-sanitaire','2Chambre salon sanitaire','3Chambre salon ordinaire','3Chambre salon semi-sanitaire','3Chambre salon sanitaire','Appartement','Boutique'] as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('pages.room_price_month') }} <span class="text-danger">*</span></label>
                            <input type="text" name="prix" class="form-control"
                                   onkeypress="return /[0-9]/i.test(event.key)" id="prix" required>
                        </div>

                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="bx bx-save me-1"></span> {{ __('common.btn_save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-center">
            <h5 class="mb-0 text-white"><i class="bx bx-door-open me-1"></i> {{ __('pages.room_list') }}</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('common.th_house') }}</th>
                            <th>{{ __('common.th_room_no') }}</th>
                            <th>{{ __('common.th_room_type') }}</th>
                            <th>{{ __('common.th_price') }}</th>
                            <th>{{ __('common.th_status') }}</th>
                            <th class="text-center">{{ __('common.th_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @can('Consulter-chambre')
                        @if(isset($allChambres))
                        @foreach($allChambres as $item)
                        <tr id="row-chambre-{{ $item->id }}">
                            <td>{{ $item->nom_maison }}</td>
                            <td>{{ $item->numero_chambre }}</td>
                            <td>{{ $item->type_chambre }}</td>
                            <td>{{ format_price($item->prix_chambre) }}</td>
                            <td>
                                @if($item->etat == true)
                                    <span class="badge rounded-pill bg-danger">{{ __('common.badge_occupied') }}</span>
                                @else
                                    <span class="badge rounded-pill bg-success">{{ __('common.badge_free') }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @can('modify-chambre')
                                    <a class="btn btn-sm btn-primary me-1" title="{{ __('common.title_edit') }}"
                                       data-bs-toggle="modal" data-bs-target="#modifier{{ $item->id }}">
                                        <i class="bx bx-edit-alt"></i>
                                    </a>
                                @endcan
                                @can('delete-chambre')
                                    <button type="button"
                                            class="btn btn-sm btn-danger btn-supprimer-chambre"
                                            title="{{ __('common.title_delete') }}"
                                            data-id="{{ $item->id }}"
                                            data-num="{{ $item->numero_chambre }}"
                                            data-etat="{{ $item->etat }}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                        @endif
                        @endcan
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modals de modification --}}
    @if(isset($allChambres))
    @foreach($allChambres as $items)
    <div class="modal fade" id="modifier{{ $items->id }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">{{ __('pages.room_edit_modal') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('common.btn_close') }}"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 form-modifier-chambre" data-id="{{ $items->id }}">
                        @csrf
                        <input type="hidden" name="chambre_id" value="{{ $items->id }}">

                        <div class="col-12">
                            <label class="form-label">{{ __('pages.room_choose_house') }} <span class="text-danger">*</span></label>
                            <select required class="form-select" name="nom_maison">
                                <option selected disabled value="">{{ __('pages.room_choose_house_opt') }}</option>
                                @if(isset($allMaison))
                                    @foreach($allMaison as $terme)
                                        <option value="{{ $terme->id }}" {{ $items->maison_id == $terme->id ? 'selected' : '' }}>
                                            {{ $terme->nom_maison }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('pages.room_number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="numero_chambre" value="{{ $items->numero_chambre }}"
                                   class="form-control" onkeypress="return /[0-9]/i.test(event.key)" required>
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('pages.room_type') }} <span class="text-danger">*</span></label>
                            <select required class="form-select" name="type_chambre">
                                <option selected disabled>{{ __('pages.room_type_opt') }}</option>
                                @foreach(['Entrée couche ordinaire','Entrée couche semi-sanitaire','Entrée couche sanitaire','Chambre salon ordinaire','Chambre salon semi-sanitaire','Chambre salon sanitaire','2Chambre salon ordinaire','2Chambre salon semi-sanitaire','2Chambre salon sanitaire','3Chambre salon ordinaire','3Chambre salon semi-sanitaire','3Chambre salon sanitaire','Appartement','Boutique'] as $type)
                                    <option value="{{ $type }}" {{ $items->type_chambre == $type ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label">{{ __('pages.room_price_month') }} <span class="text-danger">*</span></label>
                            <input type="text" name="prix" value="{{ $items->prix_chambre }}"
                                   class="form-control" onkeypress="return /[0-9]/i.test(event.key)" required>
                        </div>

                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="bx bx-save me-1"></span> {{ __('common.btn_save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif

</div>

<script>
$(document).off('.page');
var CSRF_TOKEN         = '{{ csrf_token() }}';
var URL_STORE_CH       = '{{ route("store_chambre") }}';
var URL_UPDATE_CH      = '{{ route("update_chambre") }}';
var URL_DESTROY_CH     = '{{ route("destroy_chambre") }}';
var CAN_MODIFY_CHAMBRE = {{ auth()->user()->can('modify-chambre') ? 'true' : 'false' }};
var CAN_DELETE_CHAMBRE = {{ auth()->user()->can('delete-chambre') ? 'true' : 'false' }};
var ALL_MAISONS = @json($allMaison->map(fn($m) => ['id' => $m->id, 'nom' => $m->nom_maison]));
var TYPES_CHAMBRE = ['Entrée couche ordinaire','Entrée couche semi-sanitaire','Entrée couche sanitaire','Chambre salon ordinaire','Chambre salon semi-sanitaire','Chambre salon sanitaire','2Chambre salon ordinaire','2Chambre salon semi-sanitaire','2Chambre salon sanitaire','3Chambre salon ordinaire','3Chambre salon semi-sanitaire','3Chambre salon sanitaire','Appartement','Boutique'];

// i18n strings
var I18N = {
    editModal:       '{{ __('pages.room_edit_modal') }}',
    chooseHouse:     '{{ __('pages.room_choose_house_opt') }}',
    roomType:        '{{ __('pages.room_type_opt') }}',
    btnClose:        '{{ __('common.btn_close') }}',
    btnSave:         '{{ __('common.btn_save') }}',
    badgeFree:       '{{ __('common.badge_free') }}',
    titleEdit:       '{{ __('common.title_edit') }}',
    titleDelete:     '{{ __('common.title_delete') }}',
    swalSuccess:     '{{ __('common.swal_success') }}',
    swalWarning:     '{{ __('common.swal_warning') }}',
    swalError:       '{{ __('common.swal_error') }}',
    swalDeleted:     '{{ __('common.swal_deleted') }}',
    swalUpdated:     '{{ __('pages.room_updated') }}',
    swalUnexpected:  '{{ __('common.swal_unexpected_error') }}',
    deleteTitle:     '{{ __('pages.room_delete_title') }}',
    deleteHtml:      '{{ __('pages.room_delete_html') }}',
    btnYesDelete:    '{{ __('common.btn_yes_delete') }}',
    btnCancel:       '{{ __('common.btn_cancel') }}',
};

// ─── Utilitaire fermeture modal ───────────────────────────────────────────────
function closeModalClean(id) {
    const modalEl = document.getElementById(id);
    if (!modalEl) return;
    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.hide();
    modalEl.addEventListener('hidden.bs.modal', function handler() {
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        document.body.style.removeProperty('overflow');
        document.body.style.removeProperty('padding-right');
        modalEl.removeEventListener('hidden.bs.modal', handler);
    });
}

// ─── CRÉATION DYNAMIQUE DU MODAL MODIFIER ────────────────────────────────────
function creerModalModifierChambre(c) {
    const modalId = 'modifier' + c.id;
    if (document.getElementById(modalId)) return; // déjà présent

    // Construire les options du select maison
    let optionsMaison = `<option selected disabled value="">${I18N.chooseHouse}</option>`;
    ALL_MAISONS.forEach(function(m) {
        const selected = m.id == c.maison_id ? 'selected' : '';
        optionsMaison += `<option value="${m.id}" ${selected}>${m.nom}</option>`;
    });

    // Construire les options du select type chambre
    let optionsType = `<option selected disabled value="">${I18N.roomType}</option>`;
    TYPES_CHAMBRE.forEach(function(t) {
        const selected = t === c.type_chambre ? 'selected' : '';
        optionsType += `<option value="${t}" ${selected}>${t}</option>`;
    });

    const modalEl = document.createElement('div');
    modalEl.className = 'modal fade';
    modalEl.id = modalId;
    modalEl.setAttribute('tabindex', '-1');
    modalEl.setAttribute('aria-hidden', 'true');
    modalEl.innerHTML = `
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">${I18N.editModal}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="${I18N.btnClose}"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 form-modifier-chambre" data-id="${c.id}">
                        <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                        <input type="hidden" name="chambre_id" value="${c.id}">
                        <div class="col-12">
                            <label class="form-label">${I18N.chooseHouse} <span class="text-danger">*</span></label>
                            <select required class="form-select" name="nom_maison">
                                ${optionsMaison}
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('pages.room_number') }} <span class="text-danger">*</span></label>
                            <input type="text" name="numero_chambre" value="${c.numero_chambre}"
                                   class="form-control" onkeypress="return /[0-9]/i.test(event.key)" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">${I18N.roomType} <span class="text-danger">*</span></label>
                            <select required class="form-select" name="type_chambre">
                                ${optionsType}
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('pages.room_price_month') }} <span class="text-danger">*</span></label>
                            <input type="text" name="prix" value="${c.prix_chambre}"
                                   class="form-control" onkeypress="return /[0-9]/i.test(event.key)" required>
                        </div>
                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">${I18N.btnClose}</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="bx bx-save me-1"></span> ${I18N.btnSave}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modalEl);
    // La soumission est gérée par délégation jQuery — pas de bind direct nécessaire
}

// ─── AJOUT ───────────────────────────────────────────────────────────────────
$(document).on('submit.page', '#formAjouterChambre', function(e) {
    e.preventDefault();
    const form = this;
    const data = new FormData(form);

    fetch(URL_STORE_CH, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        body: data
    })
    .then(r => r.json())
    .then(res => {
        if (res.status) {
            const c = res.chambre;

            // Créer le modal de modification pour cette nouvelle ligne
            creerModalModifierChambre(c);

            // Boutons d'action
            let actions = '';
            if (CAN_MODIFY_CHAMBRE) {
                actions += `<a class="btn btn-sm btn-primary me-1" title="${I18N.titleEdit}" data-bs-toggle="modal" data-bs-target="#modifier${c.id}"><i class="bx bx-edit-alt"></i></a>`;
            }
            if (CAN_DELETE_CHAMBRE) {
                actions += `<button type="button" class="btn btn-sm btn-danger btn-supprimer-chambre" title="${I18N.titleDelete}" data-id="${c.id}" data-num="${c.numero_chambre}" data-etat="0"><i class="bx bx-trash"></i></button>`;
            }

            // Ajouter la ligne au tableau
            const tr = document.createElement('tr');
            tr.id = 'row-chambre-' + c.id;
            tr.innerHTML = `
                <td>${c.nom_maison}</td>
                <td>${c.numero_chambre}</td>
                <td>${c.type_chambre}</td>
                <td>${parseInt(c.prix_chambre).toLocaleString('fr-FR')} {{ get_symbole_devise() }}</td>
                <td><span class="badge rounded-pill bg-success">${I18N.badgeFree}</span></td>
                <td class="text-center">${actions}</td>
            `;
            if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.isDataTable('#example')) {
                $('#example').DataTable().row.add(tr).draw(false);
            } else {
                document.querySelector('#example tbody').prepend(tr);
            }

            // Reset formulaire (modal reste ouvert)
            form.reset();

            Swal.fire({ icon: 'success', title: I18N.swalSuccess, text: res.message, timer: 2500, showConfirmButton: false });
        } else {
            Swal.fire({ icon: 'warning', title: I18N.swalWarning, text: res.message });
        }
    })
    .catch(() => Swal.fire({ icon: 'error', title: I18N.swalError, text: I18N.swalUnexpected }));
});

// ─── MODIFICATION (délégation — fonctionne après re-rendu DataTables) ─────────
$(document).on('submit.page', '.form-modifier-chambre', function(e) {
    e.preventDefault();
    const id   = this.dataset.id;
    const data = new FormData(this);

    fetch(URL_UPDATE_CH, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        body: data
    })
    .then(r => r.json())
    .then(res => {
        if (res.status) {
            const row = document.getElementById('row-chambre-' + id);
            if (row && res.chambre) {
                const cells = row.querySelectorAll('td');
                cells[0].textContent = res.chambre.nom_maison;
                cells[1].textContent = res.chambre.numero_chambre;
                cells[2].textContent = res.chambre.type_chambre;
                cells[3].textContent = res.chambre.prix_chambre + ' {{ get_symbole_devise() }}';
            }
            Swal.fire({ icon: 'success', title: I18N.swalUpdated, text: res.message, timer: 2500, showConfirmButton: false });
            closeModalClean('modifier' + id);
        } else {
            Swal.fire({ icon: 'error', title: I18N.swalError, text: res.message });
        }
    })
    .catch(() => Swal.fire({ icon: 'error', title: I18N.swalError, text: I18N.swalUnexpected }));
});

// ─── SUPPRESSION (délégation — fonctionne après re-rendu DataTables) ──────────
$(document).on('click.page', '.btn-supprimer-chambre', function() {
    const id  = this.dataset.id;
    const num = this.dataset.num;

    Swal.fire({
        title: I18N.deleteTitle,
        html: `<strong class="text-danger">${I18N.deleteHtml}${num}</strong>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: I18N.btnYesDelete,
        cancelButtonText: I18N.btnCancel
    }).then(result => {
        if (!result.isConfirmed) return;

        const data = new FormData();
        data.append('_token', CSRF_TOKEN);
        data.append('id', id);

        fetch(URL_DESTROY_CH, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
            body: data
        })
        .then(r => r.json())
        .then(res => {
            if (res.status) {
                Swal.fire({ icon: 'success', title: I18N.swalDeleted, text: res.message, timer: 2500, showConfirmButton: false });
                if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.isDataTable('#example')) {
                    $('#example').DataTable().row('#row-chambre-' + id).remove().draw(false);
                } else {
                    const row = document.getElementById('row-chambre-' + id);
                    if (row) row.remove();
                }
            } else {
                Swal.fire({ icon: 'error', title: I18N.swalError, text: res.message });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', title: I18N.swalError, text: I18N.swalUnexpected }));
    });
});

// ─── DATATABLES ───────────────────────────────────────────────────────────────
if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.isDataTable('#example')) {
    $('#example').DataTable().destroy();
}
$('#example').DataTable();
</script>

@endsection
