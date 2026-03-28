@extends('layouts.template')

@section('title')
<title>Gestion maison</title>
@endsection

@section('content')

<style>
  .swal2-container { z-index: 10070 !important; }
  .swal2-popup    { z-index: 10071 !important; }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion maison</h4>

    @include('notification.display_message')

    @can('ajoute-maison')
      <div class="col-md-6 mb-3">
        <button type="button" class="btn rounded-pill btn-icon btn-outline-primary"
                data-bs-toggle="modal" data-bs-target="#AjouerMaison">
          <span class="bx bx-plus"></span>
        </button>
      </div>
    @endcan

    {{-- Modal Ajouter --}}
    <div class="modal fade" id="AjouerMaison" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header bg-primary">
            <h5 class="modal-title text-white">Ajouter une maison</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" id="formAjouterMaison">
              @csrf

              <div class="col-12">
                <label class="form-label">Choisir un propriétaire <span class="text-danger">*</span></label>
                <select required class="form-select" name="nom_proprietaire" id="nom_proprietaire">
                  <option selected disabled value="">Choisir un propriétaire</option>
                  @if(isset($allProprios))
                    @foreach($allProprios as $terme)
                      <option value="{{ $terme->id }}">{{ $terme->nom }} {{ $terme->prenom }}</option>
                    @endforeach
                  @endif
                </select>
              </div>

              <div class="col-12">
                <label class="form-label">Nom de la maison <span class="text-danger">*</span></label>
                <input type="text" name="nom_maison" class="form-control" id="nom_maison" required>
              </div>

              <div class="col-12">
                <label class="form-label">Quartier <span class="text-danger">*</span></label>
                <input type="text" name="quartier" class="form-control" id="quartier" required>
              </div>

              <div class="col-12">
                <label class="form-label">Nombre de chambres <span class="text-danger">*</span></label>
                <input type="number" min="1" name="nombre_chambre" class="form-control" id="nombre_chambre" required>
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

    {{-- Liste des maisons --}}
    <div class="card shadow-sm border-0">
      <div class="card-header bg-primary text-center">
        <h5 class="mb-0 text-white"><i class="bx bx-home me-1"></i> Liste des maisons</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table id="example" class="table table-striped table-hover align-middle mb-0" style="width:100%">
            <thead class="table-light">
              <tr>
                <th>Propriétaire</th>
                <th>Nom maison</th>
                <th>Quartier</th>
                <th>Nombre de chambres</th>
                <th class="text-center">Actions</th>
              </tr>
            </thead>
            <tbody>
              @can('Consulter-maison')
                @if(isset($allMaison))
                  @foreach($allMaison as $item)
                    <tr id="row-maison-{{ $item->id }}">
                      <td><strong>{{ $item->nom }} {{ $item->prenom }}</strong></td>
                      <td>{{ $item->nom_maison }}</td>
                      <td>{{ $item->quartier }}</td>
                      <td>{{ $item->nombre_chambre }}</td>
                      <td class="text-center">
                        @can('modify-maison')
                          <a class="btn btn-sm btn-primary me-1" title="Modifier"
                             data-bs-toggle="modal" data-bs-target="#modifier{{ $item->id }}">
                            <i class="bx bx-edit-alt"></i>
                          </a>
                        @endcan
                        @can('delete-maison')
                          <button type="button"
                                  class="btn btn-sm btn-danger btn-supprimer-maison"
                                  title="Supprimer"
                                  data-id="{{ $item->id }}"
                                  data-nom="{{ $item->nom_maison }}">
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
    @if(isset($allMaison))
      @foreach($allMaison as $items)
        <div class="modal fade" id="modifier{{ $items->id }}" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
              <div class="modal-header bg-primary">
                <h5 class="modal-title text-white">Modifier une maison</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
              </div>
              <div class="modal-body">
                <form class="row g-3 form-modifier-maison" data-id="{{ $items->id }}">
                  @csrf
                  <input type="hidden" name="house_id" value="{{ $items->id }}">

                  <div class="col-12">
                    <label class="form-label">Choisir un propriétaire <span class="text-danger">*</span></label>
                    <select required class="form-select" name="nom_proprietaire2">
                      <option selected disabled value="">Choisir un propriétaire</option>
                      @if(isset($allProprios))
                        @foreach($allProprios as $terme)
                          <option value="{{ $terme->id }}" {{ $items->proprio_id == $terme->id ? 'selected' : '' }}>
                            {{ $terme->nom }} {{ $terme->prenom }}
                          </option>
                        @endforeach
                      @endif
                    </select>
                  </div>

                  <div class="col-12">
                    <label class="form-label">Nom de la maison <span class="text-danger">*</span></label>
                    <input type="text" name="nom_maison" class="form-control" value="{{ $items->nom_maison }}" required>
                  </div>

                  <div class="col-12">
                    <label class="form-label">Quartier <span class="text-danger">*</span></label>
                    <input type="text" name="quartier" class="form-control" value="{{ $items->quartier }}" required>
                  </div>

                  <div class="col-12">
                    <label class="form-label">Nombre de chambres <span class="text-danger">*</span></label>
                    <input type="number" min="1" name="nombre_chambre" class="form-control"
                           value="{{ $items->nombre_chambre }}" required>
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

<script>
$(document).off('.page');
var CSRF_TOKEN      = '{{ csrf_token() }}';
var URL_STORE       = '{{ route("store_house") }}';
var URL_UPDATE      = '{{ route("update_house") }}';
var URL_DESTROY     = '{{ route("destroy_house") }}';
var CAN_MODIFY_MAISON = {{ auth()->user()->can('modify-maison') ? 'true' : 'false' }};
var CAN_DELETE_MAISON = {{ auth()->user()->can('delete-maison') ? 'true' : 'false' }};
var ALL_PROPRIOS = @json($allProprios->map(fn($p) => ['id' => $p->id, 'nom' => $p->nom.' '.$p->prenom]));

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
function creerModalModifierMaison(m) {
    const modalId = 'modifier' + m.id;
    if (document.getElementById(modalId)) return; // déjà présent

    // Construire les options du select propriétaire
    let optionsProprio = '<option selected disabled value="">Choisir un propriétaire</option>';
    ALL_PROPRIOS.forEach(function(p) {
        optionsProprio += `<option value="${p.id}">${p.nom}</option>`;
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
                    <h5 class="modal-title text-white">Modifier une maison</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3 form-modifier-maison" data-id="${m.id}">
                        <input type="hidden" name="_token" value="${CSRF_TOKEN}">
                        <input type="hidden" name="house_id" value="${m.id}">
                        <div class="col-12">
                            <label class="form-label">Choisir un propriétaire <span class="text-danger">*</span></label>
                            <select required class="form-select" name="nom_proprietaire2">
                                ${optionsProprio}
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nom de la maison <span class="text-danger">*</span></label>
                            <input type="text" name="nom_maison" class="form-control" value="${m.nom_maison}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Quartier <span class="text-danger">*</span></label>
                            <input type="text" name="quartier" class="form-control" value="${m.quartier}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Nombre de chambres <span class="text-danger">*</span></label>
                            <input type="number" min="1" name="nombre_chambre" class="form-control" value="${m.nombre_chambre}" required>
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
    // La soumission est gérée par délégation jQuery — pas de bind direct nécessaire
}

// ─── AJOUT ───────────────────────────────────────────────────────────────────
$(document).on('submit.page', '#formAjouterMaison', function(e) {
    e.preventDefault();
    const form = this;
    const data = new FormData(form);

    fetch(URL_STORE, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        body: data
    })
    .then(r => r.json())
    .then(res => {
        if (res.status) {
            const m = res.maison;

            // Créer le modal de modification pour cette nouvelle ligne
            creerModalModifierMaison(m);

            // Boutons d'action
            let actions = '';
            if (CAN_MODIFY_MAISON) {
                actions += `<a class="btn btn-sm btn-primary me-1" title="Modifier" data-bs-toggle="modal" data-bs-target="#modifier${m.id}"><i class="bx bx-edit-alt"></i></a>`;
            }
            if (CAN_DELETE_MAISON) {
                actions += `<button type="button" class="btn btn-sm btn-danger btn-supprimer-maison" title="Supprimer" data-id="${m.id}" data-nom="${m.nom_maison}"><i class="bx bx-trash"></i></button>`;
            }

            // Ajouter la ligne au tableau
            const tr = document.createElement('tr');
            tr.id = 'row-maison-' + m.id;
            tr.innerHTML = `
                <td><strong>${m.proprietaire}</strong></td>
                <td>${m.nom_maison}</td>
                <td>${m.quartier}</td>
                <td>${m.nombre_chambre}</td>
                <td class="text-center">${actions}</td>
            `;
            if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.isDataTable('#example')) {
                $('#example').DataTable().row.add(tr).draw(false);
            } else {
                document.querySelector('#example tbody').prepend(tr);
            }

            // Reset formulaire (modal reste ouvert)
            form.reset();

            Swal.fire({ icon: 'success', title: 'Succès', text: res.message, timer: 2500, showConfirmButton: false });
        } else {
            Swal.fire({ icon: 'warning', title: 'Attention', text: res.message });
        }
    })
    .catch(() => Swal.fire({ icon: 'error', title: 'Erreur', text: 'Une erreur inattendue est survenue.' }));
});

// ─── MODIFICATION (délégation — fonctionne après re-rendu DataTables) ─────────
$(document).on('submit.page', '.form-modifier-maison', function(e) {
    e.preventDefault();
    const id   = this.dataset.id;
    const data = new FormData(this);

    fetch(URL_UPDATE, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        body: data
    })
    .then(r => r.json())
    .then(res => {
        if (res.status) {
            const row = document.getElementById('row-maison-' + id);
            if (row && res.maison) {
                const cells = row.querySelectorAll('td');
                cells[0].innerHTML  = `<strong>${res.maison.proprietaire}</strong>`;
                cells[1].textContent = res.maison.nom_maison;
                cells[2].textContent = res.maison.quartier;
                cells[3].textContent = res.maison.nombre_chambre;
            }
            Swal.fire({ icon: 'success', title: 'Mis à jour', text: res.message, timer: 2500, showConfirmButton: false });
            closeModalClean('modifier' + id);
        } else {
            Swal.fire({ icon: 'error', title: 'Erreur', text: res.message });
        }
    })
    .catch(() => Swal.fire({ icon: 'error', title: 'Erreur', text: 'Une erreur inattendue est survenue.' }));
});

// ─── SUPPRESSION (délégation — fonctionne après re-rendu DataTables) ──────────
$(document).on('click.page', '.btn-supprimer-maison', function() {
    const id  = this.dataset.id;
    const nom = this.dataset.nom;

    Swal.fire({
        title: 'Supprimer cette maison ?',
        html: `<strong class="text-danger">${nom}</strong><br><small class="text-muted">Cette action supprimera également toutes les chambres et locataires associés.</small>`,
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
                    $('#example').DataTable().row('#row-maison-' + id).remove().draw(false);
                } else {
                    const row = document.getElementById('row-maison-' + id);
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
