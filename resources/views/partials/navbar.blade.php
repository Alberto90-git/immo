<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">

    @php
        $agences = get_agences_liste();
        $isAdminEntreprise = Auth::user()->is_admin == 1 && Auth::user()->type_compte != 'Particulier';
        $activeAnnexeId = get_active_annexe_id();
        $activeAnnexeName = get_active_annexe_name();
    @endphp

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">

        @if($isAdminEntreprise)
            <!-- Sélecteur d'agence pour les admins d'entreprise -->
            @if((Auth::user()->type_compte != 'Particulier') && (Auth::user()->is_admin == 1))
                <div class="nav-item ms-3 position-relative">
                    <select class="form-select form-select-sm" id="agence-select" aria-label="Sélection d'agence">
                        <option value="" {{ !$activeAnnexeId ? 'selected' : '' }} disabled>Choisir une agence</option>
                        @if (isset($agences))
                            @foreach ($agences as $agence)
                                <option value="{{ $agence->idannexes }}" {{ $activeAnnexeId == $agence->idannexes ? 'selected' : '' }}>
                                    {{ $agence->designation }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <div class="agence-loading" style="display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            @else
                <div class="nav-item ms-3">
                    <span class="badge bg-primary rounded-pill px-3 py-2">
                        <i class="bx bx-building me-1"></i>
                        {{ $activeAnnexeName }}
                    </span>
                </div>
            @endif
        @else
            <!-- Badge affichant le nom de l'agence pour les non-admins -->
            <div class="nav-item ms-3">
                <span class="badge bg-primary rounded-pill px-3 py-2">
                    <i class="bx bx-building me-1"></i>
                    {{ $activeAnnexeName }}
                </span>
            </div>
        @endif

        <ul class="navbar-nav flex-row align-items-center ms-auto">

            {{-- @if((Auth::user()->type_compte != 'Particulier') && (Auth::user()->is_admin == 1)) --}}
            @can('manager-contrat')
                <!-- Bouton Contrat de bail -->
                <li class="nav-item me-2">
                    <a class="nav-link text-white" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#contratModal" title="Générer un contrat de bail">
                        <span class="d-flex align-items-center justify-content-center" style="width:38px; height:38px; background:#012970; border-radius:50%; color:white;">
                            <i class="bx bx-file" style="font-size:1.2rem;"></i>
                        </span>
                    </a>
                </li>
            @endcan
            {{-- @endif --}}

            <!-- Notifications Bell -->
            <li class="nav-item dropdown me-2" id="notificationDropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                   data-bs-toggle="dropdown" data-bs-auto-close="outside"
                   aria-expanded="false" id="notificationBell">
                    <span class="position-relative d-flex align-items-center justify-content-center"
                          style="width:38px; height:38px; background:#012970; border-radius:50%; color:white;">
                        <i class="bx bx-bell" style="font-size:1.2rem;"></i>
                        <span class="badge rounded-pill bg-danger position-absolute" id="notificationCount"
                              style="top:-2px; right:-2px; font-size:0.6rem; min-width:18px; height:18px; line-height:18px; padding:0 4px; display:none;">
                            0
                        </span>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end py-0"
                    style="width:360px; max-height:420px; border-radius:8px; box-shadow:0 5px 25px rgba(0,0,0,0.15); border:1px solid rgba(0,0,0,0.05);">
                    <li class="border-bottom">
                        <div class="d-flex align-items-center py-3 px-4">
                            <h6 class="mb-0 me-auto fw-semibold">Notifications</h6>
                            <span class="badge bg-label-primary rounded-pill" id="notificationHeaderCount"
                                  style="background-color:rgba(105,108,255,0.16)!important; color:#696cff!important;">0</span>
                            <a href="javascript:void(0);" class="ms-2 text-muted" id="markAllReadBtn" title="Tout marquer comme lu">
                                <i class="bx bx-check-double" style="font-size:1.2rem;"></i>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div id="notificationList" style="max-height:340px; overflow-y:auto;">
                            <div class="text-center py-4 text-muted">
                                <i class="bx bx-bell-off" style="font-size:2rem;"></i>
                                <p class="mb-0 mt-2" style="font-size:0.85rem;">Aucune notification</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </li>
            <!--/ Notifications Bell -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('assetsV2/img/avatars/1.png') }}" alt
                            class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assetsV2/img/avatars/1.png') }}" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->nom }}
                                        {{ Auth::user()->prenom }}</span>
                                    <small class="text-muted">{{ Auth::user()->grade }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profileView') }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">Mon Profil</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profileView') }}#password">
                            <i class="bx bx-lock-alt me-2"></i>
                            <span class="align-middle">Mot de passe</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>

                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Quitter</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>

<!-- Modal Contrat de Bail -->
<div class="modal fade" id="contratModal" tabindex="-1" aria-labelledby="contratModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: #012970; color: white;">
                <h5 class="modal-title text-white" id="contratModalLabel">
                    <i class="bx bx-file me-2"></i>Générer un contrat de bail
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="contratForm" method="POST" action="{{ route('download_contrat') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="contrat-locataire-select" class="form-label fw-bold">Sélectionner un locataire</label>
                        <select class="form-select" id="contrat-locataire-select" name="idlocataire" required>
                            <option value="" selected disabled>-- Choisir un locataire --</option>
                        </select>
                        <div class="mt-2">
                            <input type="text" class="form-control form-control-sm" id="contrat-search" placeholder="Rechercher un locataire..." style="display:none;">
                        </div>
                    </div>

                    <!-- Aperçu du locataire -->
                    <div id="contrat-preview" class="d-none">
                        <div class="card border-0" style="background:#f8f9fa;">
                            <div class="card-body p-3">
                                <h6 class="card-title mb-2" style="color:#012970;">
                                    <i class="bx bx-user me-1"></i>Informations du locataire
                                </h6>
                                <div class="row g-2" style="font-size:0.9rem;">
                                    <div class="col-6">
                                        <strong>Nom :</strong> <span id="preview-nom"></span>
                                    </div>
                                    <div class="col-6">
                                        <strong>Téléphone :</strong> <span id="preview-tel"></span>
                                    </div>
                                    <div class="col-6">
                                        <strong>Maison :</strong> <span id="preview-maison"></span>
                                    </div>
                                    <div class="col-6">
                                        <strong>Chambre :</strong> <span id="preview-chambre"></span>
                                    </div>
                                    <div class="col-12">
                                        <strong>Loyer :</strong> <span id="preview-loyer" class="text-primary fw-bold"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="contrat-generate-btn" disabled onclick="document.getElementById('contratForm').submit();">
                    <i class="bx bx-download me-1"></i>Générer le PDF
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    #contrat-locataire-select {
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        padding: 0.6rem 0.8rem;
        transition: border-color 0.3s;
    }
    #contrat-locataire-select:focus {
        border-color: #012970;
        box-shadow: 0 0 0 3px rgba(1, 41, 112, 0.15);
    }
    #contrat-generate-btn:not(:disabled) {
        background: #012970;
        border-color: #012970;
    }
    #contrat-generate-btn:not(:disabled):hover {
        background: #011d50;
        border-color: #011d50;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var contratModal = document.getElementById('contratModal');
    var locataireSelect = document.getElementById('contrat-locataire-select');
    var searchInput = document.getElementById('contrat-search');
    var previewDiv = document.getElementById('contrat-preview');
    var generateBtn = document.getElementById('contrat-generate-btn');
    var locatairesData = [];

    if (contratModal) {
        contratModal.addEventListener('show.bs.modal', function() {
            loadLocataires();
        });
    }

    function loadLocataires() {
        locataireSelect.innerHTML = '<option value="" selected disabled>Chargement...</option>';

        fetch('{{ route("api.locataires_contrat") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.status && data.data) {
                locatairesData = data.data;
                populateSelect(locatairesData);
                searchInput.style.display = 'block';
            } else {
                locataireSelect.innerHTML = '<option value="" selected disabled>Aucun locataire trouvé</option>';
            }
        })
        .catch(function() {
            locataireSelect.innerHTML = '<option value="" selected disabled>Erreur de chargement</option>';
        });
    }

    function populateSelect(list) {
        locataireSelect.innerHTML = '<option value="" selected disabled>-- Choisir un locataire --</option>';
        list.forEach(function(loc) {
            var opt = document.createElement('option');
            opt.value = loc.id;
            opt.textContent = loc.nom + ' ' + loc.prenom + ' - ' + loc.nom_maison + ' (Ch. ' + (loc.numero_chambre || '') + ')';
            opt.dataset.nom = loc.nom + ' ' + loc.prenom;
            opt.dataset.tel = loc.telephone || '';
            opt.dataset.maison = loc.nom_maison || '';
            opt.dataset.chambre = (loc.type_chambre || '') + ' N°' + (loc.numero_chambre || '');
            opt.dataset.loyer = loc.prix_mois || 0;
            locataireSelect.appendChild(opt);
        });
    }

    // Recherche
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var term = this.value.toLowerCase();
            if (!term) {
                populateSelect(locatairesData);
                return;
            }
            var filtered = locatairesData.filter(function(loc) {
                return (loc.nom + ' ' + loc.prenom + ' ' + loc.nom_maison + ' ' + (loc.telephone || ''))
                    .toLowerCase().indexOf(term) !== -1;
            });
            populateSelect(filtered);
        });
    }

    // Sélection du locataire
    if (locataireSelect) {
        locataireSelect.addEventListener('change', function() {
            var selected = this.options[this.selectedIndex];
            if (selected && selected.value) {
                document.getElementById('preview-nom').textContent = selected.dataset.nom;
                document.getElementById('preview-tel').textContent = selected.dataset.tel;
                document.getElementById('preview-maison').textContent = selected.dataset.maison;
                document.getElementById('preview-chambre').textContent = selected.dataset.chambre;
                var loyer = parseInt(selected.dataset.loyer) || 0;
                document.getElementById('preview-loyer').textContent = loyer.toLocaleString('fr-FR') + ' F CFA / mois';
                previewDiv.classList.remove('d-none');
                generateBtn.disabled = false;
            } else {
                previewDiv.classList.add('d-none');
                generateBtn.disabled = true;
            }
        });
    }
});
</script>

<!-- Toast pour les notifications -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="agence-toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body"></div>
    </div>
</div>

<style>
    /* Style personnalisé pour le sélecteur d'agence */
    #agence-select {
        background-color: #012970;
        color: white;
        border: none;
        border-radius: 50px;
        padding: 0.5rem 1rem;
        cursor: pointer;
        min-width: 200px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 1em;
    }

    #agence-select:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        transform: translateY(-2px);
    }

    #agence-select:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(1, 41, 112, 0.3);
    }

    /* Style des options */
    #agence-select option {
        background-color: white;
        color: #012970;
        padding: 0.5rem;
    }

    /* Style pour l'option sélectionnée */
    #agence-select option:checked {
        background-color: #012970;
        color: white;
    }

    /* Style pour le toast de notification */
    #agence-toast .toast-header {
        background-color: #012970;
        color: white;
    }

    /* Style pour le badge d'agence des non-admins */
    .badge.bg-primary {
        background-color: #012970 !important;
        font-size: 0.85rem;
    }
</style>

@if($isAdminEntreprise)
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const agenceSelect = document.getElementById('agence-select');
        const loadingIndicator = document.querySelector('.agence-loading');
        const agenceToast = new bootstrap.Toast(document.getElementById('agence-toast'));

        if (agenceSelect) {
            // Écouter les changements de sélection
            agenceSelect.addEventListener('change', function() {
                const selectedAgenceId = this.value;
                if (!selectedAgenceId) return;

                // Afficher l'indicateur de chargement
                loadingIndicator.style.display = 'block';

                // Effectuer la requête AJAX pour définir l'agence active
                setActiveAnnexe(selectedAgenceId);
            });
        }

        function setActiveAnnexe(agenceId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route("api.set_active_annexe") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ annexe_id: agenceId })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                // Cacher l'indicateur de chargement
                loadingIndicator.style.display = 'none';

                if (data.status) {
                    showToast('Succès', data.message, 'success');

                    // Mettre à jour le titre de la page
                    if (data.data && data.data.nom) {
                        document.title = `Lokativ | ${data.data.nom}`;
                    }

                    // Déclencher un événement pour informer les autres composants
                    window.dispatchEvent(new CustomEvent('agenceChanged', { detail: data.data }));

                    // Recharger la page après un court délai pour mettre à jour les données
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast('Erreur', data.message || 'Une erreur s\'est produite.', 'error');
                }
            })
            .catch(error => {
                // Cacher l'indicateur de chargement
                loadingIndicator.style.display = 'none';

                // Afficher un message d'erreur
                showToast('Erreur', 'Impossible de changer d\'agence.', 'error');
                console.error('Erreur:', error);
            });
        }

        function showToast(title, message, type = 'info') {
            const toastHeader = document.querySelector('#agence-toast .toast-header');
            const toastBody = document.querySelector('#agence-toast .toast-body');

            // Changer la couleur en fonction du type
            if (type === 'success') {
                toastHeader.style.backgroundColor = '#198754';
            } else if (type === 'error') {
                toastHeader.style.backgroundColor = '#dc3545';
            } else {
                toastHeader.style.backgroundColor = '#012970';
            }

            // Mettre à jour le contenu du toast
            toastBody.textContent = message;

            // Afficher le toast
            agenceToast.show();
        }
    });
</script>
@endif

{{-- Notifications Bell JS --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    var countBadge = document.getElementById('notificationCount');
    var headerBadge = document.getElementById('notificationHeaderCount');
    var listContainer = document.getElementById('notificationList');
    var bellBtn = document.getElementById('notificationBell');
    var markAllBtn = document.getElementById('markAllReadBtn');
    var csrfToken = document.querySelector('meta[name="csrf-token"]');
    var token = csrfToken ? csrfToken.getAttribute('content') : '';

    function updateBadge(count) {
        if (!countBadge) return;
        if (count > 0) {
            countBadge.textContent = count > 99 ? '99+' : count;
            countBadge.style.display = 'block';
        } else {
            countBadge.style.display = 'none';
        }
        if (headerBadge) headerBadge.textContent = count;
    }

    function fetchCount() {
        fetch('{{ route("notifications.unread_count") }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) { updateBadge(data.count); })
        .catch(function() {});
    }

    function fetchNotifications() {
        if (!listContainer) return;
        fetch('{{ route("notifications.recent") }}', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (!data.notifications || data.notifications.length === 0) {
                listContainer.innerHTML =
                    '<div class="text-center py-4 text-muted">' +
                        '<i class="bx bx-bell-off" style="font-size:2rem;"></i>' +
                        '<p class="mb-0 mt-2" style="font-size:0.85rem;">Aucune notification</p>' +
                    '</div>';
                updateBadge(0);
                return;
            }
            var html = '';
            data.notifications.forEach(function(n) {
                var bg = n.read ? '' : 'background-color:#f0f4ff;';
                html +=
                    '<div class="d-flex px-3 py-3 border-bottom notification-item" data-id="' + n.id + '" style="cursor:pointer; ' + bg + '">' +
                        '<div class="flex-shrink-0 me-3">' +
                            '<span style="width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:50%;' +
                            (n.color === 'danger' ? 'background:rgba(255,62,29,0.16);color:#ff3e1d;' : 'background:rgba(255,171,0,0.16);color:#ffab00;') + '">' +
                                '<i class="bx ' + n.icon + '" style="font-size:1.2rem;"></i>' +
                            '</span>' +
                        '</div>' +
                        '<div class="flex-grow-1" style="min-width:0;">' +
                            '<h6 class="mb-1" style="font-size:0.85rem; font-weight:600;">' + n.title + '</h6>' +
                            '<p class="mb-1 text-muted" style="font-size:0.8rem; white-space:normal; word-wrap:break-word;">' + n.message + '</p>' +
                            '<small class="text-muted">' + n.created_at + '</small>' +
                        '</div>' +
                    '</div>';
            });
            listContainer.innerHTML = html;
            updateBadge(data.unread_count);

            document.querySelectorAll('.notification-item').forEach(function(el) {
                el.addEventListener('click', function() {
                    var id = this.getAttribute('data-id');
                    var item = this;
                    fetch('{{ route("notifications.mark_read") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ id: id })
                    })
                    .then(function() {
                        item.style.backgroundColor = '';
                        fetchCount();
                    })
                    .catch(function() {});
                });
            });
        })
        .catch(function() {});
    }

    // Charger le compteur au démarrage
    fetchCount();

    // Polling toutes les 60 secondes
    setInterval(fetchCount, 60000);

    // Charger la liste au clic sur la cloche
    if (bellBtn) {
        bellBtn.addEventListener('click', fetchNotifications);
    }

    // Tout marquer comme lu
    if (markAllBtn) {
        markAllBtn.addEventListener('click', function() {
            fetch('{{ route("notifications.mark_all_read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(function() {
                fetchCount();
                fetchNotifications();
            })
            .catch(function() {});
        });
    }
});
</script>
