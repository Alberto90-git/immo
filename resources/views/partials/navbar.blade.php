<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">

    @php
        $agences = get_agences_liste();
    @endphp

    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        {{-- <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..."
                    aria-label="Search..." />
            </div>
        </div> --}}
        <!-- /Search -->

        <!-- Sélecteur d'agence -->
        <div class="nav-item ms-3 position-relative">
            <select class="form-select form-select-sm" id="agence-select" aria-label="Sélection d'agence">
                <option value="" selected disabled>Choisir une agence</option>
                @if (isset($agences))
                    @foreach ($agences as $agence)
                        <option value="{{ $agence->id }}">{{ $agence->designation }}</option>
                    @endforeach
                @endif
            </select>
            <div class="agence-loading" style="display: none; position: absolute; right: 10px; top: 50%; transform: translateY(-50%);">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
           

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
                            <span class="align-middle">Mon Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">Settings</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <span class="d-flex align-items-center align-middle">
                                <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                                <span class="flex-grow-1 align-middle">Billing</span>
                                <span
                                    class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                            </span>
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const agenceSelect = document.getElementById('agence-select');
        const loadingIndicator = document.querySelector('.agence-loading');
        const agenceToast = new bootstrap.Toast(document.getElementById('agence-toast'));
        
        if (agenceSelect) {
            // Récupérer la sélection précédente si elle existe
            const savedAgence = localStorage.getItem('selectedAgence');
            if (savedAgence) {
                agenceSelect.value = savedAgence;
            }
            
            // Écouter les changements de sélection
            agenceSelect.addEventListener('change', function() {
                const selectedAgenceId = this.value;
                if (!selectedAgenceId) return;
                
                localStorage.setItem('selectedAgence', selectedAgenceId);
                
                // Afficher l'indicateur de chargement
                loadingIndicator.style.display = 'block';
                
                // Effectuer la requête AJAX
                fetchAgenceData(selectedAgenceId);
            });
        }
        
        function fetchAgenceData(agenceId) {
            // Configuration de la requête AJAX
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            fetch('/api/agence-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ agence_id: agenceId })
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
                
                // Traiter la réponse
                if (data.success) {
                    showToast('Succès', 'Les données de l\'agence ont été chargées avec succès.', 'success');
                    
                    // Mettre à jour l'interface utilisateur avec les données reçues
                    updateUIWithAgenceData(data.data);
                } else {
                    showToast('Erreur', data.message || 'Une erreur s\'est produite.', 'error');
                }
            })
            .catch(error => {
                // Cacher l'indicateur de chargement
                loadingIndicator.style.display = 'none';
                
                // Afficher un message d'erreur
                showToast('Erreur', 'Impossible de charger les données de l\'agence.', 'error');
                console.error('Erreur:', error);
            });
        }
        
        function updateUIWithAgenceData(data) {
            // Ici, vous pouvez mettre à jour votre interface utilisateur
            // avec les données reçues de l'agence
            
            // Exemple: Mettre à jour le titre de la page
            if (data.nom) {
                document.title = `Immo | ${data.nom}`;
            }
            
            // Exemple: Mettre à jour des statistiques ou d'autres éléments UI
            console.log('Données agence reçues:', data);
            
            // Vous pourriez aussi déclencher un événement personnalisé
            // pour informer d'autres composants que les données ont changé
            window.dispatchEvent(new CustomEvent('agenceChanged', { detail: data }));
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