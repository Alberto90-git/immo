@extends('layouts.template')

@section('content')
@section('title')
    <title>Gestion Paramétrage</title>
@endsection

<style>
    /* Forcer SweetAlert2 à apparaître au-dessus de tous les modals */
    .swal2-container {
        z-index: 10070 !important;
    }
    
    .swal2-popup {
        z-index: 10071 !important;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Paramétrage</h4>
    
    @include('notification.display_message')

    <div class="col-xl-12">
        <div class="nav-align-top mb-4">
            <ul class="nav nav-pills mb-3 nav-fill" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-pills-justified-home" aria-controls="navs-pills-justified-home"
                        aria-selected="true">
                        <i class="tf-icons bx bx-home"></i> Gestion signature / logo
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                        data-bs-target="#navs-pills-justified-profile" aria-controls="navs-pills-justified-profile"
                        aria-selected="false">
                        <i class="tf-icons bx bx-user"></i> Gestion des annexes
                    </button>
                </li>
            </ul>

            @can('parametrage')
                <div class="tab-content">
                    <div class="tab-pane fade show active table-responsive text-nowrap" id="navs-pills-justified-home"
                        role="tabpanel">
                        <table class="table table-bordered border-primary" style="width:100%">
                            <thead>
                                <tr>
                                    <th scope="col">Image du cash électronique</th>
                                    <th scope="col">Logo de l'agence</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <form method="POST" action="javascript:save_parametre();" id="formulaire" enctype="multipart/form-data">
                                    @csrf
                                    <tr>
                                        <td>
                                            <div class="row mb-3">
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="file" name="cash_electronique" 
                                                        id="cash_electronique" accept="image/*">
                                                    <div class="form-text">
                                                        Formats acceptés: JPEG, PNG, JPG. Taille max: 5MB
                                                    </div>
                                                    <span class="invalid-feedback cash_electronique_err" role="alert"></span>
                                                </div>
                                            </div>

                                            @if(isset($param) && count($param) > 0)
                                                @foreach($param as $item)
                                                    @if($item->cash_electronique_url)
                                                        <div class="mt-3">
                                                            <p><strong>Image actuelle:</strong></p>
                                                            <img src="{{ asset($item->cash_electronique_url) }}" 
                                                                alt="Cash électronique" 
                                                                class="img-thumbnail"
                                                                style="max-width: 200px; max-height: 200px;">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>

                                        <td>
                                            <div class="row mb-3">
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="file" name="logo" 
                                                        id="logo" accept="image/*">
                                                    <div class="form-text">
                                                        Formats acceptés: JPEG, PNG, JPG. Taille max: 5MB
                                                    </div>
                                                    <span class="invalid-feedback logo_err" role="alert"></span>
                                                </div>
                                            </div>

                                            @if(isset($param) && count($param) > 0)
                                                @foreach($param as $item)
                                                    @if($item->logo_url)
                                                        <div class="mt-3">
                                                            <p><strong>Logo actuel:</strong></p>
                                                            <img src="{{ asset($item->logo_url) }}" 
                                                                alt="Logo" 
                                                                class="img-thumbnail"
                                                                style="max-width: 200px; max-height: 200px;">
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @can('modifier-parametre')
                                                <button class="btn btn-primary" id="valider">
                                                    <span class="fa fa-save" id="a"></span>
                                                    <span id="s">Enregistrer</span>
                                                </button>
                                            @endcan
                                        </td>
                                    </tr>
                                </form>
                            </tbody>
                        </table>
                    </div>

                    <!-- Le reste du code pour la gestion des annexes reste inchangé -->
                    <div class="tab-pane fade" id="navs-pills-justified-profile" role="tabpanel">

                        @if (Auth::user()->type_compte != 'Particulier' && Auth::user()->is_admin == 1)

                            <div class="col-md-6">
                                <div class="demo-inline-spacing">
                                    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary"
                                        data-bs-toggle="modal" data-bs-target="#ajouterAnnexe">
                                        <span class="bx bx-plus"></span>
                                    </button>
                                </div>
                            </div><br />

                        @endif

                        <div class="modal fade" id="ajouterAnnexe" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalCenterTitle">Ajouter</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="row g-3" method="post" action="javascript:save_annexe();"
                                            id="formulaireAnnexe">
                                            @csrf


                                            <div class="col-md-6">
                                                <label for="inputNanme4" class="form-label">Designation<span
                                                        style="color: red;">*</span></label>
                                                <input type="text" name="designation" class="form-control"
                                                    id="designation" required="">
                                                <span class="invalid-feedback designation_err" role="alert">
                                                </span>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="inputEmail4" class="form-label">Adresse<span
                                                        style="color: red;">*</span></label>
                                                <input type="text" name="adresse" class="form-control" id="adresse"
                                                    required="">
                                                <span class="invalid-feedback adresse_err" role="alert">
                                                </span>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="inputPassword4" class="form-label">Téléphone<span
                                                        style="color: red;">*</span></label>
                                                <input type="text" name="telephone" class="form-control"
                                                    id="telephone" required="" placeholder="+2290161000000">
                                                <span class="invalid-feedback telephone_err" role="alert">
                                                </span>
                                            </div>


                                            <div class="col-md-6">
                                                <label for="inputAddress" class="form-label">E-mail<span
                                                        style="color: red;">*</span></label>
                                                <input type="email" name="email" class="form-control" id="email"
                                                    required="">
                                                <span class="invalid-feedback email_err" role="alert">
                                                </span>
                                            </div>

                                    
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" onclick="(this);"
                                                    id="close" data-bs-dismiss="modal">Fermer
                                                </button>
                                                <button class="btn btn-primary" id="valider">
                                                    <span class="fa fa-save" id="a"></span>
                                                    <span id="s">Enregistrer</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="col-lg-12">

                                <div class="recent-sales overflow-auto">
                                    <div class="card-body">
                                        <h5 class="card-title text-center">Liste des annexes</h5>

                                        <table class="table datatable border-primary">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Designation</th>
                                                    <th scope="col">Adresse</th>
                                                    <th scope="col">Téléphone</th>
                                                    <th scope="col">E-mail</th>
                                                    <th scope="col">Actions</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @can('Consulter-proprietaire')
                                                    @if (isset($liste_annexe))
                                                        @foreach ($liste_annexe as $item)
                                                            <tr>
                                                                <th scope="row">{{ $item->designation }}</th>
                                                                <td>{{ $item->siege_social }}</td>
                                                                <td>{{ $item->telephone }}</td>
                                                                <td>{{ $item->email }}</td>
                                                                <td>
                                                                    @can('modify-proprietaire')
                                                                        <a class="btn rounded-pill btn-primary small"
                                                                            title="Modifier" href="#"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#modifier{{ $loop->iteration }}">
                                                                            <i class="bx bx-edit-alt me-1"></i>
                                                                        </a>
                                                                    @endcan

                                                                    @can('delete-proprietaire')
                                                                        <a class="btn rounded-pill btn-danger" title="Supprimer"
                                                                            href="#" data-bs-toggle="modal"
                                                                            data-bs-target="#supprimer{{ $loop->iteration }}">
                                                                            <i class="bx bx-trash me-1"></i>
                                                                        </a>
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

                                @if (isset($liste_annexe))
                                    @foreach ($liste_annexe as $items)
                                        <div class="modal fade" id="supprimer{{ $loop->iteration }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-sm" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalCenterTitle">Suppression</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="row g-3" method="post"
                                                            action="{{ route('destroy_annexe') }}">
                                                            Voulez-vous vraiment supprimer cette ligne ?
                                                            @csrf
                                                            <input type="hidden" name="id" class="form-control"
                                                                id="id" value="{{ $items->idannexes }} ">
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Non</button>
                                                                <button type="submit" class="btn btn-danger">Oui</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="modal fade" id="modifier{{ $loop->iteration }}" tabindex="-1"
                                            aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalCenterTitle">Modification</h5>
                                                        <button type="button" class="btn-close"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                        
                                                    <div class="modal-body">
                                                        <form class="row g-3" method="post"  action="{{ route('update_annexe') }}">
                                                            @csrf

                                                            <input type="hidden" name="id" class="form-control" id="id" value="{{ $items->idannexes }}">


                                                            <div class="col-md-6">
                                                                <label for="inputNanme4" class="form-label">Designation<span
                                                                        style="color: red;">*</span></label>
                                                                <input type="text" name="designation" class="form-control"
                                                                    id="designation" value="{{ $items->designation }}" required="">
                                                                <span class="invalid-feedback designation_err" role="alert">
                                                                </span>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="inputEmail4" class="form-label">Adresse<span
                                                                        style="color: red;">*</span></label>
                                                                <input type="text" name="adresse" class="form-control" id="adresse"
                                                                    required=""  value="{{ $items->siege_social }}">
                                                                <span class="invalid-feedback adresse_err" role="alert">
                                                                </span>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="inputPassword4" class="form-label">Téléphone<span
                                                                        style="color: red;">*</span></label>
                                                                        <input type="text" name="telephone" class="form-control"
                                                                        id="telephone{{ $loop->iteration }}"
                                                                        required value="{{ $items->telephone }}">
                                                                <span class="invalid-feedback telephone_err" role="alert">
                                                                </span>
                                                            </div>


                                                            <div class="col-md-6">
                                                                <label for="inputAddress" class="form-label">E-mail<span
                                                                        style="color: red;">*</span></label>
                                                                <input type="email" name="email" class="form-control" id="email"
                                                                    required=""  value="{{ $items->email }}">
                                                                <span class="invalid-feedback email_err" role="alert">
                                                                </span>
                                                            </div>

                                                    
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                                <button class="btn btn-primary">Enregistrer</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.addEventListener("DOMContentLoaded", function () {
                                                const input = document.querySelector("#telephone{{ $loop->iteration }}");
                                                if (input) {
                                                    window.intlTelInput(input, {
                                                    preferredCountries: ["bj", "fr", "ci"],
                                                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                                                    });
                                                }
                                                });
                                        </script>
                                        
                                    @endforeach
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>

<script>
    // Fonction wrapper pour display_sweet_alerte2 avec z-index forcé
    function display_sweet_alert_over_modal(title, text, icon, buttonClass) {
        // Appeler votre fonction existante
        display_sweet_alerte2(title, text, icon, buttonClass);
        
        // Forcer le z-index après un court délai
        setTimeout(function() {
            const swalContainer = document.querySelector('.swal2-container');
            const swalPopup = document.querySelector('.swal2-popup');
            
            if (swalContainer) {
                swalContainer.style.zIndex = '10070';
            }
            if (swalPopup) {
                swalPopup.style.zIndex = '10071';
            }
        }, 10);
    }

    function printErrorMsg(msg) {
        // Réinitialiser tous les messages d'erreur
        $('.invalid-feedback').text('').hide();
        
        // Afficher les nouvelles erreurs
        $.each(msg, function(key, value) {
            const errorElement = $(`.${key}_err`);
            if (errorElement.length) {
                errorElement.text(value).show();
            }
        });
    }
    

    function save_parametre() {
        const formData = new FormData(document.getElementById('formulaire'));
        
        // Validation côté client
        const cashElectronique = document.getElementById('cash_electronique').files[0];
        const logo = document.getElementById('logo').files[0];
        
        if (!cashElectronique && !logo) {
            display_sweet_alert_over_modal("Erreur !!","Veuillez sélectionner au moins une image (cash électronique ou logo)","warning","btn btn-danger");
            return;
        }
        
        // Vérification de la taille des fichiers (5MB max)
        const maxSize = 5 * 1024 * 1024; // 5MB en bytes
        if (cashElectronique && cashElectronique.size > maxSize) {
            display_sweet_alert_over_modal("Erreur !!","L'image du cash électronique dépasse la taille maximale de 5MB","warning","btn btn-danger");
            return;
        }
        
        if (logo && logo.size > maxSize) {
            display_sweet_alert_over_modal("Erreur !!","Le logo dépasse la taille maximale de 5MB","warning","btn btn-danger");
            return;
        }

        $.ajax({
            url: "{{ route('store_param') }}",
            method: "POST",
            processData: false,
            contentType: false,
            data: formData,
            beforeSend: function() {
                $("#valider").prop("disabled", true);
                $("#valider").html('<i class="spinner-border spinner-border-sm"></i> Enregistrement...');
            },
            success: function(data) {
                $("#valider").prop("disabled", false);
                $("#valider").html('<span class="fa fa-save"></span> Enregistrer');

                if (data.error) {
                    printErrorMsg(data.error);
                    display_sweet_alert_over_modal("Erreur de validation",data.message || 'Veuillez corriger les erreurs',"warning","btn btn-danger");
                } else if (data.status) {
                    display_sweet_alert_over_modal("Succès",data.message,"success","btn btn-primary");
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);
                } else {
                    display_sweet_alert_over_modal("Erreur !!",data.message || 'Une erreur est survenue',"warning","btn btn-danger");
                }
            },
            error: function(xhr) {
                $("#valider").prop("disabled", false);
                $("#valider").html('<span class="fa fa-save"></span> Enregistrer');
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    display_sweet_alert_over_modal("Erreur !!",xhr.responseJSON.message,"warning","btn btn-danger");
                } else {
                    display_sweet_alert_over_modal("Erreur !!","Une erreur réseau est survenue","warning","btn btn-danger");
                }
            }
        });
    }

    // Fonction pour valider et soumettre le formulaire annexe
    function save_annexe() {
        const telephoneInput = document.getElementById('telephone');
        const formattedPhone = validateAndFormatPhone('telephone');
        if (!formattedPhone) return;

        const formData = new FormData(document.getElementById('formulaireAnnexe'));
        formData.set('telephone', formattedPhone);

        $.ajax({
            url: "{{ route('store_annexe') }}",
            method: "POST",
            processData: false,
            contentType: false,
            data: formData,
            beforeSend: function() {
                $("#ajouterAnnexe button#close").prop("disabled", true);
                $("#ajouterAnnexe button#valider").prop("disabled", true);
                $("#ajouterAnnexe button#valider").html('<i class="spinner-border spinner-border-sm"></i> Enregistrement...');
            },
            success: function(data) {
                $("#ajouterAnnexe button#close").prop("disabled", false);
                $("#ajouterAnnexe button#valider").prop("disabled", false);
                $("#ajouterAnnexe button#valider").html('Enregistrer');

                if (data.error) {
                    printErrorMsg(data.error);
                    display_sweet_alert_over_modal("Erreur de validation", data.message || 'Veuillez corriger les erreurs',"warning","btn btn-danger");
                } else if (data.status) {
                    display_sweet_alert_over_modal("Succès", data.message ,"success","btn btn-primary");

                    $("#ajouterAnnexe form#formulaireAnnexe")[0].reset();
                    
                    // Réinitialiser le champ téléphone
                    const iti = window.intlTelInputGlobals.getInstance(telephoneInput);
                    if (iti) iti.setNumber('');
                    
                    // Fermer le modal et recharger après 3 secondes
                    // setTimeout(function() {
                    //     $('#ajouterAnnexe').modal('hide');
                    //     window.location.reload();
                    // }, 3000);
                } else {
                    display_sweet_alert_over_modal("Erreur !!", data.message,"warning","btn btn-danger");
                }
            },
            error: function(xhr) {
                $("#ajouterAnnexe button#close").prop("disabled", false);
                $("#ajouterAnnexe button#valider").prop("disabled", false);
                $("#ajouterAnnexe button#valider").html('Enregistrer');
                display_sweet_alert_over_modal("Erreur !!","Une erreur est survenue","warning","btn btn-danger");
            }
        });
    }

    // Fonctions utilitaires pour la validation des téléphones
    function validateAndFormatPhone(inputId) {
        const telephoneInput = document.getElementById(inputId);
        if (!telephoneInput) return null;

        const iti = window.intlTelInputGlobals.getInstance(telephoneInput);
        if (!iti) return null;

        // Vérification globale du numéro
        if (!iti.isValidNumber()) {
            display_sweet_alert_over_modal("Erreur !!","Numéro de téléphone invalide","warning","btn btn-danger");
            return null;
        }

        const formattedPhone = iti.getNumber(); // format E.164
        const selectedCountry = iti.getSelectedCountryData();
        const cleanedPhone = formattedPhone.replace(/\s+/g, "");

        // Règle spécifique au Bénin
        if (selectedCountry.iso2 === "bj") {
            if (cleanedPhone.length !== 14) {
                display_sweet_alert_over_modal("Erreur !!","Le numéro béninois doit contenir exactement 10 chiffres après +229","warning","btn btn-danger");
                return null;
            }
        }

        // Autres pays → accepté si valide
        return formattedPhone;
    }

    // Masquer les messages d'erreur lors de la saisie
    $(document).on('input change', ':input', function() {
        const inputId = $(this).attr('id');
        if (inputId) {
            $(`.${inputId}_err`).hide();
        }
    });
</script>
@endsection