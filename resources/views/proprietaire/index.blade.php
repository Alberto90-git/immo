@extends('layouts.template')

@section('title')
    <title>Gestion propriétaire</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
    <style>
        .iti { width: 100%; }
        .iti__flag-container { z-index: 1060; }
        .iti__flag {
            background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/img/flags.png");
        }
        @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
            .iti__flag {
                background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/img/flags@2x.png");
            }
        }
    </style>
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    @include('notification.display_message')

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion propriétaire</h4>

    @can('ajoute-proprietaire')
        <div class="col-md-6 mb-3">
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary"
                    data-bs-toggle="modal" data-bs-target="#AjouterProprietaire">
                <span class="bx bx-plus"></span>
            </button>
        </div>
    @endcan

    {{-- Modal Ajouter --}}
    <div class="modal fade" id="AjouterProprietaire" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Ajouter un propriétaire</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form id="formAjouter" class="row g-3">
                        @csrf

                        <div class="col-md-6">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nom" placeholder="Nom du propriétaire" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="prenom" placeholder="Prénom du propriétaire" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="tel" id="tel-ajouter" class="form-control" name="telephone" placeholder="XX XX XX XX" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Adresse <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="adresse" placeholder="Adresse complète" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" placeholder="exemple@email.com">
                        </div>

                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="bx bx-save me-1"></span> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Liste des propriétaires --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-center">
            <h5 class="mb-0 text-white"><i class="bx bx-user me-1"></i> Liste des propriétaires</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>Nom & prénoms</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th>Email</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @can('Consulter-proprietaire')
                            @if (isset($allProprios) && count($allProprios) > 0)
                                @foreach ($allProprios as $item)
                                    <tr id="row-proprio-{{ $item->id }}">
                                        <td><strong>{{ $item->nom }} {{ $item->prenom }}</strong></td>
                                        <td><i class="bx bx-phone-call text-success me-1"></i> {{ $item->telephone }}</td>
                                        <td><i class="bx bx-map text-secondary me-1"></i> {{ $item->adresse }}</td>
                                        <td>{{ $item->email ?? '—' }}</td>
                                        <td class="text-center">
                                            @can('modify-proprietaire')
                                                <a class="btn btn-sm btn-primary me-1"
                                                   title="Modifier"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#modifier{{ $item->id }}">
                                                    <i class="bx bx-edit-alt"></i>
                                                </a>
                                            @endcan

                                            @can('delete-proprietaire')
                                                <button type="button"
                                                        class="btn btn-sm btn-danger btn-supprimer"
                                                        title="Supprimer"
                                                        data-id="{{ $item->id }}"
                                                        data-nom="{{ $item->nom }} {{ $item->prenom }}">
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
    @if (isset($allProprios) && count($allProprios) > 0)
        @foreach ($allProprios as $item)
            <div class="modal fade" id="modifier{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary">
                            <h5 class="modal-title text-white">Modifier un propriétaire</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <form class="row g-3 form-modifier" data-id="{{ $item->id }}">
                                @csrf
                                <input type="hidden" name="id" value="{{ $item->id }}">

                                <div class="col-md-6">
                                    <label class="form-label">Nom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nom" value="{{ $item->nom }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Prénom <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="prenom" value="{{ $item->prenom }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                                    <input type="tel" id="tel-modifier-{{ $item->id }}" class="form-control tel-modifier" name="telephone" value="{{ $item->telephone }}" data-id="{{ $item->id }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Adresse <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="adresse" value="{{ $item->adresse }}" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="{{ $item->email }}" placeholder="exemple@email.com">
                                </div>

                                <div class="modal-footer mt-3">
                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                                    <button type="submit" class="btn btn-primary">
                                        <span class="bx bx-save me-1"></span> Enregistrer
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

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script>
$(document).off('.page');
var CSRF_TOKEN  = '{{ csrf_token() }}';
var URL_CREATE  = '{{ route("create_propre") }}';
var URL_UPDATE  = '{{ route("update_proprio") }}';
var URL_DESTROY = '{{ route("destroy_proprio") }}';
var CAN_MODIFY  = {{ auth()->user()->can('modify-proprietaire') ? 'true' : 'false' }};
var CAN_DELETE  = {{ auth()->user()->can('delete-proprietaire') ? 'true' : 'false' }};

// ─── INTL-TEL-INPUT ───────────────────────────────────────────────────────────
var UTILS_SCRIPT = 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js';

if (window._itiProprioAjouter) { try { window._itiProprioAjouter.destroy(); } catch(e) {} }
window._itiProprioAjouter = window.intlTelInput(document.getElementById('tel-ajouter'), {
    initialCountry: 'bj',
    preferredCountries: ['bj', 'tg', 'sn', 'ci', 'gh', 'ng', 'fr', 'be'],
    separateDialCode: true,
    utilsScript: UTILS_SCRIPT
});
var itiAjouter = window._itiProprioAjouter;

// Stocker les instances pour les modals modifier
// Détruire les anciennes instances itiModifiers
if (window._itiProprioModifiers) {
    Object.values(window._itiProprioModifiers).forEach(function(iti) { try { iti.destroy(); } catch(e) {} });
}
window._itiProprioModifiers = {};
document.querySelectorAll('.tel-modifier').forEach(function (el) {
    var id = el.dataset.id;
    window._itiProprioModifiers[id] = window.intlTelInput(el, {
        initialCountry: 'bj',
        preferredCountries: ['bj', 'tg', 'sn', 'ci', 'gh', 'ng', 'fr', 'be'],
        separateDialCode: true,
        utilsScript: UTILS_SCRIPT
    });
});
var itiModifiers = window._itiProprioModifiers;

// ─── CRÉATION DYNAMIQUE DU MODAL MODIFIER ────────────────────────────────────
function creerModalModifier(p) {
    const modalEl = document.createElement('div');
    modalEl.className = 'modal fade';
    modalEl.id = 'modifier' + p.id;
    modalEl.setAttribute('tabindex', '-1');
    modalEl.setAttribute('aria-hidden', 'true');
    modalEl.innerHTML = `
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Modifier un propriétaire</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 form-modifier" data-id="${p.id}">
                        <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                        <input type="hidden" name="id" value="${p.id}">
                        <div class="col-md-6">
                            <label class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nom" value="${p.nom}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prénom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="prenom" value="${p.prenom}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="tel" id="tel-modifier-${p.id}" class="form-control tel-modifier" name="telephone" value="${p.telephone}" data-id="${p.id}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Adresse <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="adresse" value="${p.adresse}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="${p.email || ''}" placeholder="exemple@email.com">
                        </div>
                        <div class="modal-footer mt-3">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                            <button type="submit" class="btn btn-primary">
                                <span class="bx bx-save me-1"></span> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
    document.body.appendChild(modalEl);

    // Initialiser intl-tel-input pour ce nouveau modal
    const telInput = modalEl.querySelector('#tel-modifier-' + p.id);
    window._itiProprioModifiers[p.id] = window.intlTelInput(telInput, {
        initialCountry: 'bj',
        preferredCountries: ['bj', 'tg', 'sn', 'ci', 'gh', 'ng', 'fr', 'be'],
        separateDialCode: true,
        utilsScript: UTILS_SCRIPT
    });
    itiModifiers = window._itiProprioModifiers;

    // La soumission est gérée par délégation jQuery — pas de bind direct nécessaire
}

// ─── AJOUT ───────────────────────────────────────────────────────────────────
$(document).on('submit.page', '#formAjouter', function(e) {
    e.preventDefault();
    var form = this;
    const data = new FormData(form);
    data.set('telephone', itiAjouter.getNumber());

    fetch(URL_CREATE, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        body: data
    })
    .then(r => r.json())
    .then(res => {
        if (res.status) {
            const p = res.proprio;

            // Créer le modal de modification pour cette nouvelle ligne
            creerModalModifier(p);

            // Boutons d'action
            let actions = '';
            if (CAN_MODIFY) {
                actions += `<a class="btn btn-sm btn-primary me-1" title="Modifier" data-bs-toggle="modal" data-bs-target="#modifier${p.id}"><i class="bx bx-edit-alt"></i></a>`;
            }
            if (CAN_DELETE) {
                actions += `<button type="button" class="btn btn-sm btn-danger btn-supprimer" title="Supprimer" data-id="${p.id}" data-nom="${p.nom} ${p.prenom}"><i class="bx bx-trash"></i></button>`;
            }

            // Ajouter la ligne au tableau
            const tr = document.createElement('tr');
            tr.id = 'row-proprio-' + p.id;
            tr.innerHTML = `
                <td><strong>${p.nom} ${p.prenom}</strong></td>
                <td><i class="bx bx-phone-call text-success me-1"></i> ${p.telephone}</td>
                <td><i class="bx bx-map text-secondary me-1"></i> ${p.adresse}</td>
                <td>${p.email || '—'}</td>
                <td class="text-center">${actions}</td>
            `;
            if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.isDataTable('#example')) {
                $('#example').DataTable().row.add(tr).draw(false);
            } else {
                document.querySelector('#example tbody').prepend(tr);
            }

            // Reset formulaire
            form.reset();
            itiAjouter.setCountry('bj');

            Swal.fire({ icon: 'success', title: 'Succès', text: res.message, timer: 2000, showConfirmButton: false });
        } else {
            Swal.fire({ icon: 'error', title: 'Erreur', text: res.message });
        }
    })
    .catch(() => Swal.fire({ icon: 'error', title: 'Erreur', text: 'Une erreur inattendue est survenue.' }));
});

// ─── MODIFICATION (délégation — fonctionne après re-rendu DataTables) ─────────
$(document).on('submit.page', '.form-modifier', function(e) {
    e.preventDefault();
    const data = new FormData(this);
    const id   = this.dataset.id;
    if (itiModifiers[id]) data.set('telephone', itiModifiers[id].getNumber());

    fetch(URL_UPDATE, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        body: data
    })
    .then(r => r.json())
    .then(res => {
        if (res.status) {
            const row = document.getElementById('row-proprio-' + id);
            if (row && res.proprio) {
                const cells = row.querySelectorAll('td');
                cells[0].innerHTML  = `<strong>${res.proprio.nom} ${res.proprio.prenom}</strong>`;
                cells[1].innerHTML  = `<i class="bx bx-phone-call text-success me-1"></i> ${res.proprio.telephone}`;
                cells[2].innerHTML  = `<i class="bx bx-map text-secondary me-1"></i> ${res.proprio.adresse}`;
                cells[3].innerHTML  = res.proprio.email || '—';
            }

            Swal.fire({ icon: 'success', title: 'Mis à jour', text: res.message, timer: 2500, showConfirmButton: false });
            const modalEl = document.getElementById('modifier' + id);
            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.hide();
            modalEl.addEventListener('hidden.bs.modal', function handler() {
                document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');
                modalEl.removeEventListener('hidden.bs.modal', handler);
            });
        } else {
            Swal.fire({ icon: 'error', title: 'Erreur', text: res.message });
        }
    })
    .catch(() => Swal.fire({ icon: 'error', title: 'Erreur', text: 'Une erreur inattendue est survenue.' }));
});

// ─── SUPPRESSION (délégation — fonctionne après re-rendu DataTables) ──────────
$(document).on('click.page', '.btn-supprimer', function() {
    const id  = this.dataset.id;
    const nom = this.dataset.nom;

    Swal.fire({
        title: 'Supprimer ce propriétaire ?',
        html: `<strong class="text-danger">${nom}</strong><br><small class="text-muted">Cette action supprimera également toutes les maisons, chambres et locataires associés.</small>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Oui, supprimer',
        cancelButtonText: 'Annuler'
    }).then(result => {
        if (!result.isConfirmed) return;

        const data = new FormData();
        data.append('_token', CSRF_TOKEN);
        data.append('id', id);

        fetch(URL_DESTROY, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
            body: data
        })
        .then(r => r.json())
        .then(res => {
            if (res.status) {
                Swal.fire({ icon: 'success', title: 'Supprimé', text: res.message, timer: 2500, showConfirmButton: false });
                if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.isDataTable('#example')) {
                    $('#example').DataTable().row('#row-proprio-' + id).remove().draw(false);
                } else {
                    const row = document.getElementById('row-proprio-' + id);
                    if (row) row.remove();
                }
            } else {
                Swal.fire({ icon: 'error', title: 'Erreur', text: res.message });
            }
        })
        .catch(() => Swal.fire({ icon: 'error', title: 'Erreur', text: 'Une erreur inattendue est survenue.' }));
    });
});

// ─── DATATABLES ───────────────────────────────────────────────────────────────
if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.isDataTable('#example')) {
    $('#example').DataTable().destroy();
}
$('#example').DataTable();
</script>

@endsection
