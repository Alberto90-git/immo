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

    /* Multi-select custom */
    .ms-wrapper {
        position: relative;
    }
    .ms-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        padding: 6px 10px;
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
        min-height: 42px;
        cursor: pointer;
        background: #fff;
        align-items: center;
        transition: border-color .15s, box-shadow .15s;
    }
    .ms-tags:focus-within,
    .ms-tags.active {
        border-color: #1e40af;
        box-shadow: 0 0 0 0.2rem rgba(30, 64, 175, 0.15);
    }
    .ms-tags .ms-tag {
        display: inline-flex;
        align-items: center;
        background: #1e40af;
        color: #fff;
        border-radius: 4px;
        padding: 3px 8px;
        font-size: 12px;
        line-height: 1.4;
        white-space: nowrap;
    }
    .ms-tags .ms-tag .ms-tag-remove {
        margin-left: 6px;
        cursor: pointer;
        font-weight: bold;
        opacity: .8;
    }
    .ms-tags .ms-tag .ms-tag-remove:hover {
        opacity: 1;
    }
    .ms-tags .ms-placeholder {
        color: #a1a7b3;
        font-size: 13px;
    }
    .ms-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 10060;
        background: #fff;
        border: 1px solid #d9dee3;
        border-top: none;
        border-radius: 0 0 0.375rem 0.375rem;
        max-height: 220px;
        display: none;
        box-shadow: 0 4px 12px rgba(0,0,0,.1);
    }
    .ms-dropdown.show {
        display: block;
    }
    .ms-dropdown .ms-search {
        padding: 8px 10px;
        border-bottom: 1px solid #eee;
        position: sticky;
        top: 0;
        background: #fff;
    }
    .ms-dropdown .ms-search input {
        width: 100%;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 5px 10px;
        font-size: 13px;
        outline: none;
    }
    .ms-dropdown .ms-search input:focus {
        border-color: #1e40af;
    }
    .ms-dropdown .ms-options {
        overflow-y: auto;
        max-height: 170px;
    }
    .ms-dropdown .ms-option {
        display: flex;
        align-items: center;
        padding: 8px 12px;
        cursor: pointer;
        font-size: 13px;
        transition: background .1s;
    }
    .ms-dropdown .ms-option:hover {
        background: #f0f4ff;
    }
    .ms-dropdown .ms-option.selected {
        background: #eef2ff;
        font-weight: 600;
    }
    .ms-dropdown .ms-option input[type="checkbox"] {
        margin-right: 10px;
        accent-color: #1e40af;
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }
    .ms-dropdown .ms-empty {
        padding: 12px;
        text-align: center;
        color: #999;
        font-size: 13px;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Paramétrage</h4>
    
    @include('notification.display_message')

    <div class="col-xl-12">
        <div class="nav-align-top mb-4">

            {{-- ══ Conteneur unique tablist pour lier les 5 onglets ══ --}}
            <div role="tablist" id="param-tablist">

                {{-- ══ BLOC 1 : Paramètres généraux ══ --}}
                <div class="rounded-3 p-2 mb-1" style="background:rgba(105,108,255,.05);border:1px solid rgba(105,108,255,.18);">
                    <ul class="nav nav-pills nav-fill mb-0">
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
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-pills-justified-pourcentage" aria-controls="navs-pills-justified-pourcentage"
                                aria-selected="false">
                                <i class="tf-icons bx bx-percent"></i> Pourcentage de gestion
                            </button>
                        </li>
                    </ul>
                </div>

                {{-- ══ SÉPARATEUR CATÉGORIEL ══ --}}
                <div class="d-flex align-items-center gap-3 my-2">
                    <hr class="flex-grow-1 my-0" style="border-top:1.5px solid #dee2e6;">
                    <span style="white-space:nowrap;color:#697a8d;font-size:.7rem;font-weight:700;
                                 text-transform:uppercase;letter-spacing:.1em;
                                 background:#fff;padding:3px 14px;
                                 border:1px solid #dee2e6;border-radius:20px;">
                        <i class="bx bx-cog" style="vertical-align:-1px;margin-right:4px;"></i>Configuration avancée
                    </span>
                    <hr class="flex-grow-1 my-0" style="border-top:1.5px solid #dee2e6;">
                </div>

                {{-- ══ BLOC 2 : Configuration avancée ══ --}}
                <div class="rounded-3 p-2 mb-3" style="background:rgba(255,171,0,.04);border:1px dashed rgba(255,171,0,.5);">
                    <ul class="nav nav-pills justify-content-center gap-3 mb-0">
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-pills-justified-contrat" aria-controls="navs-pills-justified-contrat"
                                aria-selected="false" id="tab-btn-contrat">
                                <i class="tf-icons bx bx-file"></i> Modèle de contrat
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-pills-justified-communication" aria-controls="navs-pills-justified-communication"
                                aria-selected="false" id="tab-btn-communication">
                                <i class="tf-icons bx bx-message-rounded-dots"></i> Communication
                            </button>
                        </li>
                    </ul>
                </div>

            </div>{{-- /tablist --}}

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
                                                            <p><strong>Signature actuelle:</strong></p>
                                                            <img src="{{ $item->cash_electronique_url }}" 
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
                                                            <img src="{{ $item->logo_url }}" 
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
                                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-primary">
                                                        <h5 class="modal-title text-white" id="modalCenterTitle">Modification de l'agence</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <form class="row g-3" method="post" action="{{ route('update_annexe') }}" enctype="multipart/form-data">
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
                                                                <label for="inputPassword4" class="form-label">Telephone<span
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

                                                            <hr class="my-3">
                                                            <h6 class="text-primary">Logo et informations de paiement</h6>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Logo de l'agence</label>
                                                                <input class="form-control" type="file" name="logo" accept="image/*">
                                                                <small class="text-muted">Formats: JPEG, PNG. Max: 2MB</small>
                                                                @if($items->logo)
                                                                    <div class="mt-2">
                                                                        <img src="{{ asset('storage/' . $items->logo) }}"
                                                                            alt="Logo actuel" class="img-thumbnail"
                                                                            style="max-width: 100px; max-height: 100px;">
                                                                    </div>
                                                                @endif
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Cash electronique (informations)</label>
                                                                <textarea class="form-control" name="cash_electronique" rows="3"
                                                                    placeholder="Ex: MTN MoMo: 97000000&#10;Moov Money: 96000000">{{ $items->cash_electronique }}</textarea>
                                                                <small class="text-muted">Ces informations apparaitront sur les factures</small>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label class="form-label">Signature du responsable</label>
                                                                <input class="form-control" type="file" name="signature" accept="image/*">
                                                                <small class="text-muted">Formats: JPEG, PNG. Max: 2MB. Cette signature apparaitra sur les factures</small>
                                                                @if($items->signature)
                                                                    <div class="mt-2">
                                                                        <img src="{{ asset('storage/' . $items->signature) }}"
                                                                            alt="Signature actuelle" class="img-thumbnail"
                                                                            style="max-width: 150px; max-height: 80px;">
                                                                    </div>
                                                                @endif
                                                            </div>


                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                                <button class="btn btn-primary"><i class="bx bx-save me-1"></i>Enregistrer</button>
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

                    <!-- TAB 3: POURCENTAGE DE GESTION -->
                    <div class="tab-pane fade" id="navs-pills-justified-pourcentage" role="tabpanel">

                        <!-- SECTION 1 : Pourcentage général -->
                        <div class="card mb-4">
                            <div class="card-header" style="background: linear-gradient(135deg, #1e40af, #3b82f6); color: white;">
                                <h5 class="mb-0 text-white"><i class="bx bx-slider-alt me-2"></i>Pourcentage général</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-4">Ce pourcentage s'applique par défaut à tous les propriétaires qui ne font partie d'aucun groupe actif.</p>

                                <form method="POST" action="javascript:save_pourcentage_general();" id="formPourcentageGeneral">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold">Pourcentage (%)<span style="color:red;">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="pourcentage" id="pourcentage_general_input"
                                                    class="form-control" step="0.01" min="0" max="100"
                                                    value="{{ isset($pourcentageGeneral) ? $pourcentageGeneral->pourcentage : 10 }}" required>
                                                <span class="input-group-text">%</span>
                                            </div>
                                            <span class="invalid-feedback pourcentage_err" role="alert"></span>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold">Statut</label>
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="toggle_general"
                                                    {{ (isset($pourcentageGeneral) && $pourcentageGeneral->is_active) ? 'checked' : '' }}
                                                    onchange="toggle_pourcentage({{ isset($pourcentageGeneral) ? $pourcentageGeneral->id : 0 }})">
                                                <label class="form-check-label" for="toggle_general" id="label_toggle_general">
                                                    <span class="badge {{ (isset($pourcentageGeneral) && $pourcentageGeneral->is_active) ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ (isset($pourcentageGeneral) && $pourcentageGeneral->is_active) ? 'Actif' : 'Inactif' }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            @can('modifier-parametre')
                                                <button class="btn btn-primary" id="btnSavePourcentageGeneral" type="submit">
                                                    <i class="bx bx-save me-1"></i> Enregistrer
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- SECTION 2 : Groupes de pourcentage -->
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #1e40af, #3b82f6); color: white;">
                                <h5 class="mb-0 text-white"><i class="bx bx-group me-2"></i>Groupes de pourcentage</h5>
                                @can('modifier-parametre')
                                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#ajouterGroupe">
                                        <i class="bx bx-plus me-1"></i> Nouveau groupe
                                    </button>
                                @endcan
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-4">Créez des groupes avec un pourcentage spécifique et associez-y des propriétaires. Un propriétaire ne peut appartenir qu'à un seul groupe.</p>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="tableGroupes">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nom du groupe</th>
                                                <th>Pourcentage</th>
                                                <th>Propriétaires</th>
                                                <th>Statut</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($pourcentageGroupes) && $pourcentageGroupes->count() > 0)
                                                @foreach($pourcentageGroupes as $groupe)
                                                    <tr>
                                                        <td><strong>{{ $groupe->nom }}</strong></td>
                                                        <td><span class="badge bg-label-primary">{{ $groupe->pourcentage }} %</span></td>
                                                        <td>
                                                            @foreach($groupe->proprietaires as $p)
                                                                <span class="badge bg-label-info mb-1">{{ $p->nom }} {{ $p->prenom }}</span>
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            <div class="form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" role="switch"
                                                                    {{ $groupe->is_active ? 'checked' : '' }}
                                                                    onchange="toggle_pourcentage({{ $groupe->id }})">
                                                                <span class="badge {{ $groupe->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                                    {{ $groupe->is_active ? 'Actif' : 'Inactif' }}
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @can('modifier-parametre')
                                                                <button class="btn btn-sm btn-outline-primary me-1"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#modifierGroupe{{ $groupe->id }}">
                                                                    <i class="bx bx-edit-alt"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-outline-danger"
                                                                    onclick="delete_groupe({{ $groupe->id }})">
                                                                    <i class="bx bx-trash"></i>
                                                                </button>
                                                            @endcan
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted py-4">
                                                        <i class="bx bx-info-circle me-1"></i> Aucun groupe de pourcentage configuré
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- MODAL : Ajouter un groupe -->
                        <div class="modal fade" id="ajouterGroupe" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title"><i class="bx bx-plus-circle me-2"></i>Nouveau groupe de pourcentage</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="javascript:save_groupe();" id="formGroupe">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Nom du groupe<span style="color:red;">*</span></label>
                                                    <input type="text" name="nom" class="form-control" id="groupe_nom" placeholder="Ex: Premium, Standard..." required>
                                                    <span class="invalid-feedback nom_err" role="alert"></span>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">Pourcentage (%)<span style="color:red;">*</span></label>
                                                    <div class="input-group">
                                                        <input type="number" name="pourcentage" class="form-control" id="groupe_pourcentage"
                                                            step="0.01" min="0" max="100" placeholder="Ex: 15" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <span class="invalid-feedback pourcentage_err" role="alert"></span>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Propriétaires associés<span style="color:red;">*</span></label>
                                                <div class="ms-wrapper" id="ms-add-wrapper">
                                                    <div class="ms-tags" id="ms-add-tags">
                                                        <span class="ms-placeholder">Cliquez pour sélectionner...</span>
                                                    </div>
                                                    <div class="ms-dropdown" id="ms-add-dropdown">
                                                        <div class="ms-search">
                                                            <input type="text" placeholder="Rechercher un propriétaire..." id="ms-add-search">
                                                        </div>
                                                        <div class="ms-options" id="ms-add-options">
                                                            @if(isset($proprietaires_list))
                                                                @foreach($proprietaires_list as $proprio)
                                                                    <label class="ms-option" data-value="{{ $proprio->id }}" data-label="{{ $proprio->nom }} {{ $proprio->prenom }}">
                                                                        <input type="checkbox" name="proprietaires[]" value="{{ $proprio->id }}">
                                                                        {{ $proprio->nom }} {{ $proprio->prenom }}
                                                                    </label>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="invalid-feedback proprietaires_err" role="alert"></span>
                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Annuler</button>
                                                <button type="submit" class="btn btn-primary" id="btnSaveGroupe">
                                                    <i class="bx bx-save me-1"></i> Enregistrer
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- MODALS : Modifier les groupes (générés dynamiquement) -->
                        @if(isset($pourcentageGroupes))
                            @foreach($pourcentageGroupes as $groupe)
                                <div class="modal fade" id="modifierGroupe{{ $groupe->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title"><i class="bx bx-edit me-2"></i>Modifier le groupe "{{ $groupe->nom }}"</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="javascript:update_groupe({{ $groupe->id }});" id="formGroupeEdit{{ $groupe->id }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $groupe->id }}">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-bold">Nom du groupe<span style="color:red;">*</span></label>
                                                            <input type="text" name="nom" class="form-control" value="{{ $groupe->nom }}" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-bold">Pourcentage (%)<span style="color:red;">*</span></label>
                                                            <div class="input-group">
                                                                <input type="number" name="pourcentage" class="form-control"
                                                                    value="{{ $groupe->pourcentage }}" step="0.01" min="0" max="100" required>
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Propriétaires associés<span style="color:red;">*</span></label>
                                                        <div class="ms-wrapper" id="ms-edit-wrapper-{{ $groupe->id }}">
                                                            <div class="ms-tags" id="ms-edit-tags-{{ $groupe->id }}">
                                                                <span class="ms-placeholder">Cliquez pour sélectionner...</span>
                                                            </div>
                                                            <div class="ms-dropdown" id="ms-edit-dropdown-{{ $groupe->id }}">
                                                                <div class="ms-search">
                                                                    <input type="text" placeholder="Rechercher un propriétaire..." id="ms-edit-search-{{ $groupe->id }}">
                                                                </div>
                                                                <div class="ms-options" id="ms-edit-options-{{ $groupe->id }}">
                                                                    @if(isset($proprietaires_list))
                                                                        @foreach($proprietaires_list as $proprio)
                                                                            <label class="ms-option {{ $groupe->proprietaires->contains('id', $proprio->id) ? 'selected' : '' }}" data-value="{{ $proprio->id }}" data-label="{{ $proprio->nom }} {{ $proprio->prenom }}">
                                                                                <input type="checkbox" name="proprietaires[]" value="{{ $proprio->id }}"
                                                                                    {{ $groupe->proprietaires->contains('id', $proprio->id) ? 'checked' : '' }}>
                                                                                {{ $proprio->nom }} {{ $proprio->prenom }}
                                                                            </label>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-primary" id="btnUpdateGroupe{{ $groupe->id }}">
                                                            <i class="bx bx-save me-1"></i> Mettre à jour
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
                    <!-- FIN TAB 3 -->

                    <!-- TAB 4 : Modèle de Contrat -->
                    <div class="tab-pane fade" id="navs-pills-justified-contrat" role="tabpanel">
                        <div class="row p-3">
                            <div class="col-12 mb-3">
                                <h6 class="text-primary fw-bold"><i class="bx bx-file me-1"></i>Personnalisation du modèle de contrat</h6>
                                <p class="text-muted small">Personnalisez le titre, le sous-titre et les articles du contrat de bail généré pour vos locataires. Utilisez les variables ci-dessous pour insérer des données dynamiques.</p>
                            </div>

                            {{-- Variables disponibles --}}
                            <div class="col-12 mb-3">
                                <div class="accordion" id="accordionVariables">
                                    <div class="accordion-item border">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVariables">
                                                <i class="bx bx-code-alt me-2 text-primary"></i> <strong>Variables disponibles</strong> <span class="ms-2 text-muted small">(cliquer pour afficher)</span>
                                            </button>
                                        </h2>
                                        <div id="collapseVariables" class="accordion-collapse collapse">
                                            <div class="accordion-body py-2">
                                                <div class="row g-2">
                                                    @php
                                                    $vars = [
                                                        ['{nom_agence}','Nom de l\'agence'],['{adresse_agence}','Adresse de l\'agence'],['{telephone_agence}','Téléphone de l\'agence'],
                                                        ['{nom_locataire}','Nom complet du locataire'],['{telephone_locataire}','Téléphone du locataire'],['{profession_locataire}','Profession du locataire'],['{adresse_locataire}','Domicile du locataire'],
                                                        ['{nom_maison}','Nom de la maison'],['{quartier_maison}','Quartier / localisation'],['{type_chambre}','Type de chambre'],['{numero_chambre}','Numéro de chambre'],
                                                        ['{montant_loyer}','Loyer mensuel (ex: 50 000 F CFA)'],['{nombre_caution}','Nombre de mois de caution'],['{montant_caution}','Montant total de la caution'],['{caution_courant}','Caution électricité'],['{caution_eau}','Caution eau'],
                                                        ['{nombre_avance}','Nombre de mois d\'avance'],['{montant_avance}','Montant de l\'avance'],['{mode_paiement}','Mode de paiement'],
                                                        ['{date_entree}','Date d\'entrée du locataire'],['{date_contrat}','Date de génération du contrat'],
                                                    ];
                                                    @endphp
                                                    @foreach($vars as $v)
                                                    <div class="col-md-6 col-lg-4">
                                                        <div class="d-flex align-items-start gap-2">
                                                            <code class="text-primary bg-light px-1 rounded small text-nowrap" style="cursor:pointer" onclick="navigator.clipboard.writeText('{{ $v[0] }}');this.classList.add('text-success');setTimeout(()=>this.classList.remove('text-success'),800)">{{ $v[0] }}</code>
                                                            <span class="small text-muted">{{ $v[1] }}</span>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                                <p class="small text-muted mt-2 mb-0"><i class="bx bx-mouse-alt me-1"></i>Cliquez sur une variable pour la copier.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Formulaire principal --}}
                            <div class="col-12">
                                <form method="POST" action="{{ route('store_contrat_config') }}" id="formContrat">
                                    @csrf

                                    <div class="card border-0 shadow-sm mb-3">
                                        <div class="card-body">
                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Titre du contrat <span class="text-danger">*</span></label>
                                                    <input type="text" name="titre_contrat" class="form-control" required
                                                        value="{{ $contratConfig->titre_contrat ?? 'Contrat de Bail d\'Habitation' }}"
                                                        placeholder="Ex: Contrat de Bail d'Habitation">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Sous-titre / Référence légale</label>
                                                    <input type="text" name="sous_titre" class="form-control"
                                                        value="{{ $contratConfig->sous_titre ?? '' }}"
                                                        placeholder="Ex: Conformément à la Loi N°2022-30...">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Liste des articles --}}
                                    <div class="card border-0 shadow-sm mb-3">
                                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                                            <span class="fw-semibold"><i class="bx bx-list-ul me-1 text-primary"></i>Articles du contrat</span>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="contratOpenModal()">
                                                <i class="bx bx-plus me-1"></i>Ajouter un article
                                            </button>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="articles-list" class="list-group list-group-flush">
                                                {{-- Rempli par JS --}}
                                            </div>
                                            <p id="articles-empty" class="text-center text-muted py-4 mb-0 d-none">
                                                <i class="bx bx-info-circle me-1"></i>Aucun article. Cliquez sur "Ajouter un article".
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Inputs hidden pour la soumission --}}
                                    <div id="articles-hidden-inputs"></div>

                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-save me-1"></i>Enregistrer le modèle
                                        </button>
                                    </div>
                                </form>

                                {{-- Bouton reset séparé --}}
                                @if($contratConfig)
                                <form method="POST" action="{{ route('reset_contrat_config') }}" id="formResetContrat" class="d-inline mt-2">
                                    @csrf
                                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="btnResetContrat">
                                        <i class="bx bx-reset me-1"></i>Remettre par défaut
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- FIN TAB 4 -->

                    {{-- ===== ONGLET COMMUNICATION ===== --}}
                    <div class="tab-pane fade" id="navs-pills-justified-communication" role="tabpanel">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-transparent border-bottom">
                                        <h6 class="mb-0"><i class="bx bx-message-rounded-dots me-2 text-primary"></i>Configuration de la communication</h6>
                                        <small class="text-muted">Paramétrez les méthodes d'envoi de documents aux locataires et propriétaires.</small>
                                    </div>
                                    <div class="card-body">
                                        <form id="formCommConfig">
                                            @csrf
                                            <h6 class="mb-3 text-primary"><i class="bx bx-envelope me-1"></i>Email</h6>
                                            <div class="mb-4">
                                                <label class="form-label fw-semibold" for="emailEnvoi">Adresse email d'envoi</label>
                                                <input type="email" class="form-control" id="emailEnvoi" name="email_envoi"
                                                       value="{{ $param->first()?->email_envoi ?? '' }}"
                                                       placeholder="ex: agence@mondomaine.com">
                                                <small class="text-muted">Les documents envoyés par email utiliseront cette adresse comme expéditeur.</small>
                                            </div>

                                            <hr class="my-4">

                                            <h6 class="mb-3 text-success"><i class="bx bxl-whatsapp me-1"></i>WhatsApp</h6>

                                            {{-- Statut de connexion --}}
                                            <div class="d-flex align-items-center gap-3 p-3 rounded mb-3"
                                                 style="background:rgba(37,211,102,.06);border:1px solid rgba(37,211,102,.2);">
                                                <span id="wa-status-icon" class="fs-4">⏳</span>
                                                <div class="flex-grow-1">
                                                    <div class="fw-semibold" id="wa-status-label">Chargement…</div>
                                                    <small class="text-muted" id="wa-status-sub">Vérification du service WhatsApp…</small>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-success" id="btnConnectWA" style="display:none">
                                                    <i class="bx bx-qr-scan me-1"></i>Connecter
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger" id="btnDisconnectWA" style="display:none">
                                                    <i class="bx bx-power-off me-1"></i>Déconnecter
                                                </button>
                                            </div>

                                            <div class="mb-4">
                                                <label class="form-label fw-semibold" for="whatsappNumero">Numéro WhatsApp de l'agence</label>
                                                <input type="text" class="form-control" id="whatsappNumero" name="whatsapp_numero_envoi"
                                                       value="{{ $param->first()?->whatsapp_numero_envoi ?? '' }}"
                                                       placeholder="+22960000000">
                                                <small class="text-muted">Format international recommandé (+229…)</small>
                                            </div>

                                            <div class="d-flex gap-2">
                                                <button type="button" class="btn btn-primary" id="btnSaveCommConfig">
                                                    <i class="bx bx-save me-1"></i>Enregistrer la configuration
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- /tab-content --}}

            @endcan
        </div>{{-- /nav-align-top --}}
    </div>{{-- /col-xl-12 --}}
</div>{{-- /container-xxl --}}

<script>
    // Synchronisation des 5 nav-items entre les deux blocs visuels
    document.querySelectorAll('#param-tablist [data-bs-toggle="tab"]').forEach(function(btn) {
        btn.addEventListener('shown.bs.tab', function() {
            // Désactiver tous les autres boutons du tablist unifié
            document.querySelectorAll('#param-tablist [data-bs-toggle="tab"]').forEach(function(other) {
                if (other !== btn) {
                    other.classList.remove('active');
                    other.setAttribute('aria-selected', 'false');
                }
            });
        });
    });

    // Persister l'onglet actif après rechargement
    (function() {
        var storageKey = 'parametre_active_tab';
        // Restaurer l'onglet au chargement
        var saved = localStorage.getItem(storageKey);
        if (saved) {
            var tabBtn = document.querySelector('[data-bs-target="' + saved + '"]');
            if (tabBtn) {
                // Désactiver l'onglet actif par défaut
                document.querySelectorAll('.nav-pills .nav-link').forEach(function(btn) {
                    btn.classList.remove('active');
                    btn.setAttribute('aria-selected', 'false');
                });
                document.querySelectorAll('.tab-pane').forEach(function(pane) {
                    pane.classList.remove('show', 'active');
                });
                // Activer l'onglet sauvegardé
                tabBtn.classList.add('active');
                tabBtn.setAttribute('aria-selected', 'true');
                var pane = document.querySelector(saved);
                if (pane) {
                    pane.classList.add('show', 'active');
                }
            }
        }
        // Sauvegarder à chaque changement d'onglet
        document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(btn) {
            btn.addEventListener('shown.bs.tab', function() {
                localStorage.setItem(storageKey, btn.getAttribute('data-bs-target'));
            });
        });
    })();

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

    // ==========================================
    // POURCENTAGE DE GESTION
    // ==========================================

    function save_pourcentage_general() {
        const formData = new FormData(document.getElementById('formPourcentageGeneral'));

        $.ajax({
            url: "{{ route('store_pourcentage_general') }}",
            method: "POST",
            processData: false,
            contentType: false,
            data: formData,
            beforeSend: function() {
                $("#btnSavePourcentageGeneral").prop("disabled", true);
                $("#btnSavePourcentageGeneral").html('<i class="spinner-border spinner-border-sm"></i> Enregistrement...');
            },
            success: function(data) {
                $("#btnSavePourcentageGeneral").prop("disabled", false);
                $("#btnSavePourcentageGeneral").html('<i class="bx bx-save me-1"></i> Enregistrer');

                if (data.error) {
                    printErrorMsg(data.error);
                    display_sweet_alert_over_modal("Erreur !!", data.message || 'Veuillez corriger les erreurs', "warning", "btn btn-danger");
                } else if (data.status) {
                    display_sweet_alert_over_modal("Succès", data.message, "success", "btn btn-primary");
                    setTimeout(function() { window.location.reload(); }, 2000);
                } else {
                    display_sweet_alert_over_modal("Erreur !!", data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                $("#btnSavePourcentageGeneral").prop("disabled", false);
                $("#btnSavePourcentageGeneral").html('<i class="bx bx-save me-1"></i> Enregistrer');
                display_sweet_alert_over_modal("Erreur !!", "Une erreur est survenue", "warning", "btn btn-danger");
            }
        });
    }

    function toggle_pourcentage(id) {
        if (id === 0) {
            display_sweet_alert_over_modal("Info", "Veuillez d'abord enregistrer le pourcentage général", "info", "btn btn-primary");
            return;
        }
        $.ajax({
            url: "{{ route('toggle_pourcentage') }}",
            method: "POST",
            data: { _token: '{{ csrf_token() }}', id: id },
            success: function(data) {
                if (data.status) {
                    display_sweet_alert_over_modal("Succès", data.message, "success", "btn btn-primary");
                    setTimeout(function() { window.location.reload(); }, 1500);
                } else {
                    display_sweet_alert_over_modal("Erreur !!", data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                display_sweet_alert_over_modal("Erreur !!", "Une erreur est survenue", "warning", "btn btn-danger");
            }
        });
    }

    function save_groupe() {
        const formData = new FormData(document.getElementById('formGroupe'));

        $.ajax({
            url: "{{ route('store_pourcentage_groupe') }}",
            method: "POST",
            processData: false,
            contentType: false,
            data: formData,
            beforeSend: function() {
                $("#btnSaveGroupe").prop("disabled", true);
                $("#btnSaveGroupe").html('<i class="spinner-border spinner-border-sm"></i> Enregistrement...');
            },
            success: function(data) {
                $("#btnSaveGroupe").prop("disabled", false);
                $("#btnSaveGroupe").html('<i class="bx bx-save me-1"></i> Enregistrer');

                if (data.error) {
                    printErrorMsg(data.error);
                    display_sweet_alert_over_modal("Erreur !!", data.message || 'Veuillez corriger les erreurs', "warning", "btn btn-danger");
                } else if (data.status) {
                    display_sweet_alert_over_modal("Succès", data.message, "success", "btn btn-primary");
                    setTimeout(function() { window.location.reload(); }, 2000);
                } else {
                    display_sweet_alert_over_modal("Erreur !!", data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                $("#btnSaveGroupe").prop("disabled", false);
                $("#btnSaveGroupe").html('<i class="bx bx-save me-1"></i> Enregistrer');
                display_sweet_alert_over_modal("Erreur !!", "Une erreur est survenue", "warning", "btn btn-danger");
            }
        });
    }

    function update_groupe(id) {
        const formData = new FormData(document.getElementById('formGroupeEdit' + id));

        $.ajax({
            url: "{{ route('update_pourcentage_groupe') }}",
            method: "POST",
            processData: false,
            contentType: false,
            data: formData,
            beforeSend: function() {
                $("#btnUpdateGroupe" + id).prop("disabled", true);
                $("#btnUpdateGroupe" + id).html('<i class="spinner-border spinner-border-sm"></i> Mise à jour...');
            },
            success: function(data) {
                $("#btnUpdateGroupe" + id).prop("disabled", false);
                $("#btnUpdateGroupe" + id).html('<i class="bx bx-save me-1"></i> Mettre à jour');

                if (data.error) {
                    printErrorMsg(data.error);
                    display_sweet_alert_over_modal("Erreur !!", data.message || 'Veuillez corriger les erreurs', "warning", "btn btn-danger");
                } else if (data.status) {
                    display_sweet_alert_over_modal("Succès", data.message, "success", "btn btn-primary");
                    setTimeout(function() { window.location.reload(); }, 2000);
                } else {
                    display_sweet_alert_over_modal("Erreur !!", data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                $("#btnUpdateGroupe" + id).prop("disabled", false);
                $("#btnUpdateGroupe" + id).html('<i class="bx bx-save me-1"></i> Mettre à jour');
                display_sweet_alert_over_modal("Erreur !!", "Une erreur est survenue", "warning", "btn btn-danger");
            }
        });
    }

    function delete_groupe(id) {
        Swal.fire({
            title: 'Confirmation',
            text: 'Voulez-vous vraiment supprimer ce groupe ? Les propriétaires seront dissociés.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Oui, supprimer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('destroy_pourcentage_groupe') }}",
                    method: "POST",
                    data: { _token: '{{ csrf_token() }}', id: id },
                    success: function(data) {
                        if (data.status) {
                            display_sweet_alert_over_modal("Succès", data.message, "success", "btn btn-primary");
                            setTimeout(function() { window.location.reload(); }, 1500);
                        } else {
                            display_sweet_alert_over_modal("Erreur !!", data.message, "warning", "btn btn-danger");
                        }
                    },
                    error: function() {
                        display_sweet_alert_over_modal("Erreur !!", "Une erreur est survenue", "warning", "btn btn-danger");
                    }
                });
            }
        });
    }
</script>

<script>
    // ==========================================
    // MULTI-SELECT CUSTOM COMPONENT
    // ==========================================
    (function() {
        function initMultiSelect(wrapperId) {
            var wrapper = document.getElementById(wrapperId);
            if (!wrapper) return;

            var tags = wrapper.querySelector('.ms-tags');
            var dropdown = wrapper.querySelector('.ms-dropdown');
            var searchInput = wrapper.querySelector('.ms-search input');
            var options = wrapper.querySelectorAll('.ms-option');

            function renderTags() {
                // Supprimer les anciens tags
                tags.querySelectorAll('.ms-tag').forEach(function(t) { t.remove(); });
                var placeholder = tags.querySelector('.ms-placeholder');

                var checked = wrapper.querySelectorAll('.ms-option input[type="checkbox"]:checked');
                if (checked.length === 0) {
                    if (!placeholder) {
                        var ph = document.createElement('span');
                        ph.className = 'ms-placeholder';
                        ph.textContent = 'Cliquez pour sélectionner...';
                        tags.appendChild(ph);
                    } else {
                        placeholder.style.display = '';
                    }
                    return;
                }

                if (placeholder) placeholder.style.display = 'none';

                checked.forEach(function(cb) {
                    var opt = cb.closest('.ms-option');
                    var tag = document.createElement('span');
                    tag.className = 'ms-tag';
                    tag.innerHTML = opt.getAttribute('data-label') + ' <span class="ms-tag-remove" data-val="' + cb.value + '">&times;</span>';
                    tags.insertBefore(tag, placeholder);
                });
            }

            // Toggle dropdown
            tags.addEventListener('click', function(e) {
                if (e.target.classList.contains('ms-tag-remove')) {
                    var val = e.target.getAttribute('data-val');
                    var cb = wrapper.querySelector('.ms-option input[value="' + val + '"]');
                    if (cb) {
                        cb.checked = false;
                        cb.closest('.ms-option').classList.remove('selected');
                    }
                    renderTags();
                    e.stopPropagation();
                    return;
                }
                dropdown.classList.toggle('show');
                tags.classList.toggle('active');
                if (dropdown.classList.contains('show') && searchInput) {
                    setTimeout(function() { searchInput.focus(); }, 50);
                }
            });

            // Checkbox change
            options.forEach(function(opt) {
                var cb = opt.querySelector('input[type="checkbox"]');
                cb.addEventListener('change', function() {
                    opt.classList.toggle('selected', cb.checked);
                    renderTags();
                });
            });

            // Recherche
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    var term = this.value.toLowerCase();
                    var found = false;
                    options.forEach(function(opt) {
                        var label = opt.getAttribute('data-label').toLowerCase();
                        var visible = label.indexOf(term) !== -1;
                        opt.style.display = visible ? '' : 'none';
                        if (visible) found = true;
                    });
                    // Message "aucun résultat"
                    var emptyMsg = wrapper.querySelector('.ms-empty');
                    if (!found) {
                        if (!emptyMsg) {
                            emptyMsg = document.createElement('div');
                            emptyMsg.className = 'ms-empty';
                            emptyMsg.textContent = 'Aucun propriétaire trouvé';
                            wrapper.querySelector('.ms-options').appendChild(emptyMsg);
                        }
                        emptyMsg.style.display = '';
                    } else if (emptyMsg) {
                        emptyMsg.style.display = 'none';
                    }
                });

                // Empêcher la fermeture du dropdown quand on clique dans la recherche
                searchInput.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

            // Fermer le dropdown en cliquant ailleurs
            document.addEventListener('click', function(e) {
                if (!wrapper.contains(e.target)) {
                    dropdown.classList.remove('show');
                    tags.classList.remove('active');
                }
            });

            // Rendu initial des tags (pour les modals d'édition avec des valeurs pré-sélectionnées)
            renderTags();
        }

        // Initialiser le multi-select du modal d'ajout à l'ouverture
        var addModal = document.getElementById('ajouterGroupe');
        if (addModal) {
            addModal.addEventListener('shown.bs.modal', function() {
                initMultiSelect('ms-add-wrapper');
            });
        }

        // Initialiser les multi-selects des modals d'édition
        document.querySelectorAll('[id^="modifierGroupe"]').forEach(function(modal) {
            modal.addEventListener('shown.bs.modal', function() {
                var wrapper = modal.querySelector('.ms-wrapper');
                if (wrapper) {
                    initMultiSelect(wrapper.id);
                }
            });
        });
    })();


</script>

<script>
// =====================================================================
// GESTION DES ARTICLES DU CONTRAT
// =====================================================================
(function() {
    var DEFAULT_ARTICLES = [
        { titre: "Les Parties", contenu: "Le Bailleur : {nom_agence}, {adresse_agence}, Tel : {telephone_agence}.\nLe Locataire : {nom_locataire}, Profession : {profession_locataire}, Tel : {telephone_locataire}, Domicile : {adresse_locataire}." },
        { titre: "Objet du Contrat", contenu: "Le Bailleur donne en location au Locataire un logement situe a {nom_maison} ({quartier_maison}), {type_chambre} N\u00b0{numero_chambre}.\nLe logement est destine exclusivement a l'usage d'habitation du Locataire et de sa famille." },
        { titre: "Duree du Bail", contenu: "Le bail est consenti pour une duree indeterminee, prenant effet le {date_entree}. Chaque partie peut y mettre fin avec un preavis de trois (3) mois notifie par ecrit." },
        { titre: "Loyer", contenu: "Le loyer mensuel est fixe a {montant_loyer}, payable au plus tard le cinq (5) de chaque mois.\nMode de paiement : {mode_paiement}." },
        { titre: "Depot de Garantie", contenu: "Le Locataire verse un depot de garantie de {nombre_caution} mois de loyer, soit {montant_caution}.\n- Caution electricite : {caution_courant}\n- Caution eau : {caution_eau}\nCe depot sera restitue dans un delai de trois (3) mois apres la restitution des cles." },
        { titre: "Avance sur Loyer", contenu: "Le Locataire verse une avance de {nombre_avance} mois de loyer, soit {montant_avance}. Cette avance couvre les {nombre_avance} premiers mois de location." },
        { titre: "Obligations du Bailleur", contenu: "Le Bailleur s'engage a :\n- Delivrer le logement en bon etat d'usage et de reparation\n- Assurer la jouissance paisible des lieux\n- Entretenir les gros ouvrages (toiture, murs porteurs, canalisations)\n- Remettre une quittance de loyer a chaque paiement" },
        { titre: "Obligations du Locataire", contenu: "Le Locataire s'engage a :\n- Payer le loyer et les charges aux termes convenus\n- User des locaux en bon pere de famille\n- Ne pas sous-louer sans accord ecrit du Bailleur\n- Signaler sans delai toute degradation necessitant reparation\n- Restituer les locaux en bon etat a la fin du bail" },
        { titre: "Resiliation", contenu: "Chaque partie peut mettre fin au bail avec un preavis de trois (3) mois. Le bail peut etre resilie de plein droit en cas de defaut de paiement apres mise en demeure restee infructueuse pendant un mois." },
        { titre: "Etat des Lieux", contenu: "Un etat des lieux sera etabli contradictoirement lors de la remise et de la restitution des cles. A defaut d'etat des lieux, le Locataire est presume avoir recu les locaux en bon etat." },
        { titre: "Clause Resolutoire", contenu: "A defaut de paiement d'un seul terme de loyer et un mois apres commandement de payer reste sans effet, le bail sera resilie de plein droit." },
        { titre: "Election de Domicile", contenu: "Pour l'execution du present contrat, les parties elisent domicile a leurs adresses respectives. En cas de litige, le tribunal competent du lieu de l'immeuble sera saisi apres tentative de reglement amiable." },
    ];

    var phpData = @json($contratConfig ? $contratConfig->articles : null);
    window.contratArticles = (phpData && phpData.length) ? phpData : JSON.parse(JSON.stringify(DEFAULT_ARTICLES));

    // Le modal est placé après ce script dans le DOM → initialisation après DOMContentLoaded
    var articleModal = null;
    document.addEventListener('DOMContentLoaded', function() {
        var modalEl = document.getElementById('modalArticle');
        if (modalEl) {
            articleModal = new bootstrap.Modal(modalEl, { backdrop: true, keyboard: true });
            modalEl.addEventListener('hidden.bs.modal', function() {
                document.querySelectorAll('.modal-backdrop').forEach(function(b) { b.remove(); });
                document.body.classList.remove('modal-open');
                document.body.style.removeProperty('overflow');
                document.body.style.removeProperty('padding-right');
            });
        }
    });

    function escapeHtml(str) {
        if (!str) return '';
        return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function renderArticlesList() {
        var list = document.getElementById('articles-list');
        var empty = document.getElementById('articles-empty');
        var hiddenContainer = document.getElementById('articles-hidden-inputs');
        if (!list || !hiddenContainer) return;
        list.innerHTML = '';
        hiddenContainer.innerHTML = '';
        if (!window.contratArticles || window.contratArticles.length === 0) {
            if (empty) empty.classList.remove('d-none');
            return;
        }
        if (empty) empty.classList.add('d-none');
        window.contratArticles.forEach(function(article, index) {
            var item = document.createElement('div');
            item.className = 'list-group-item d-flex justify-content-between align-items-start py-2 px-3';
            var isFirst = (index === 0);
            var isLast  = (index === window.contratArticles.length - 1);
            item.innerHTML =
                '<div class="me-auto" style="min-width:0;overflow:hidden">' +
                    '<div class="fw-semibold small text-primary">Article ' + (index+1) + ' \u2014 ' + escapeHtml(article.titre) + '</div>' +
                    '<div class="text-muted" style="font-size:11px;max-height:36px;overflow:hidden;">' + escapeHtml((article.contenu||'').substring(0,100)) + ((article.contenu||'').length>100?'...':'') + '</div>' +
                '</div>' +
                '<div class="d-flex gap-1 ms-2 flex-shrink-0 align-items-center">' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1" title="Monter" onclick="contratMoveArticle('+index+',-1)"'+(isFirst?' disabled':'')+'>&#8679;</button>' +
                    '<button type="button" class="btn btn-sm btn-outline-secondary py-0 px-1" title="Descendre" onclick="contratMoveArticle('+index+',1)"'+(isLast?' disabled':'')+'>&#8681;</button>' +
                    '<button type="button" class="btn btn-sm btn-outline-primary py-0 px-1" title="Modifier" onclick="contratEditArticle('+index+')">&#9998;</button>' +
                    '<button type="button" class="btn btn-sm btn-outline-danger py-0 px-1" title="Supprimer" onclick="contratDeleteArticle('+index+')">&#128465;</button>' +
                '</div>';
            list.appendChild(item);
            ['titre','contenu'].forEach(function(field) {
                var inp = document.createElement('input');
                inp.type = 'hidden';
                inp.name = 'articles['+index+']['+field+']';
                inp.value = article[field] || '';
                hiddenContainer.appendChild(inp);
            });
        });
    }

    window.contratOpenModal = function(index) {
        if (!articleModal) { alert('Veuillez patienter, la page se charge encore.'); return; }
        var isEdit = (typeof index === 'number');
        document.getElementById('modalArticleTitle').textContent = isEdit ? "Modifier l'article" : "Ajouter un article";
        document.getElementById('article-edit-index').value = isEdit ? index : '';
        document.getElementById('article-titre').value  = isEdit ? (window.contratArticles[index].titre||'') : '';
        document.getElementById('article-contenu').value = isEdit ? (window.contratArticles[index].contenu||'') : '';
        document.getElementById('article-titre').classList.remove('is-invalid');
        document.getElementById('article-contenu').classList.remove('is-invalid');
        articleModal.show();
    };

    // ── Sauvegarde AJAX partagée ────────────────────────────────────────
    function saveArticlesToServer(successMsg, btnEl) {
        var formContrat = document.getElementById('formContrat');
        if (!formContrat) return;
        if (btnEl) { btnEl.disabled = true; btnEl.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>'; }
        fetch(formContrat.action, {
            method: 'POST',
            body: new FormData(formContrat),
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status) {
                Swal.fire({ icon: 'success', title: 'Succès', text: successMsg || data.message, timer: 2000, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Erreur', text: data.message || "Échec de l'enregistrement." });
            }
        })
        .catch(function() {
            Swal.fire({ icon: 'error', title: 'Erreur réseau', text: 'Impossible de contacter le serveur.' });
        })
        .finally(function() {
            if (btnEl) { btnEl.disabled = false; btnEl.innerHTML = '<i class="bx bx-save me-1"></i>Enregistrer le modèle'; }
        });
    }

    // ── Ajout / Modification depuis le modal ─────────────────────────
    window.contratSaveArticle = function() {
        var titreEl = document.getElementById('article-titre');
        var contEl  = document.getElementById('article-contenu');
        var idxEl   = document.getElementById('article-edit-index');
        var titre   = titreEl.value.trim();
        var contenu = contEl.value.trim();
        var ok = true;
        if (!titre)   { titreEl.classList.add('is-invalid'); document.getElementById('err-article-titre').textContent = 'Le titre est obligatoire.'; ok = false; }
        else          { titreEl.classList.remove('is-invalid'); }
        if (!contenu) { contEl.classList.add('is-invalid');   document.getElementById('err-article-contenu').textContent = 'Le contenu est obligatoire.'; ok = false; }
        else          { contEl.classList.remove('is-invalid'); }
        if (!ok) return;

        var editIdx = idxEl.value;
        var isEdit  = (editIdx !== '');
        if (isEdit) { window.contratArticles[parseInt(editIdx, 10)] = { titre: titre, contenu: contenu }; }
        else        { window.contratArticles.push({ titre: titre, contenu: contenu }); }

        renderArticlesList();
        if (articleModal) articleModal.hide();
        saveArticlesToServer(isEdit ? 'Article modifié et enregistré.' : 'Article ajouté et enregistré.');
    };

    // ── Suppression avec confirmation SweetAlert + sauvegarde auto ───
    window.contratDeleteArticle = function(index) {
        var article = window.contratArticles[index];
        Swal.fire({
            title: 'Supprimer cet article ?',
            html: article ? '<strong>' + article.titre + '</strong><br><small class="text-muted">Cette suppression sera enregistrée immédiatement.</small>' : '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="bx bx-trash me-1"></i>Supprimer',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
        }).then(function(result) {
            if (result.isConfirmed) {
                window.contratArticles.splice(index, 1);
                renderArticlesList();
                saveArticlesToServer('Article supprimé et enregistré.');
            }
        });
    };

    // ── Réorganisation (sauvegarde différée via bouton principal) ────
    window.contratMoveArticle = function(index, direction) {
        var target = index + direction;
        if (target < 0 || target >= window.contratArticles.length) return;
        var tmp = window.contratArticles[index];
        window.contratArticles[index] = window.contratArticles[target];
        window.contratArticles[target] = tmp;
        renderArticlesList();
    };

    window.contratEditArticle = function(index) { window.contratOpenModal(index); };

    // ── Bouton reset : confirmation SweetAlert avant soumission ──────
    var btnReset = document.getElementById('btnResetContrat');
    if (btnReset) {
        btnReset.addEventListener('click', function() {
            Swal.fire({
                title: 'Remettre par défaut ?',
                html: 'Tous vos articles personnalisés seront <strong>remplacés</strong> par les articles par défaut.<br><small class="text-muted">Cette action est irréversible.</small>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="bx bx-reset me-1"></i>Remettre par défaut',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
            }).then(function(result) {
                if (result.isConfirmed) {
                    document.getElementById('formResetContrat').submit();
                }
            });
        });
    }

    // ── Bouton "Enregistrer le modèle" (titre + sous-titre + ordre) ──
    var formContrat = document.getElementById('formContrat');
    if (formContrat) {
        formContrat.addEventListener('submit', function(e) {
            e.preventDefault();
            if (!window.contratArticles || window.contratArticles.length === 0) {
                Swal.fire({ icon: 'warning', title: 'Attention', text: "Ajoutez au moins un article avant d'enregistrer." });
                return;
            }
            var btn = formContrat.querySelector('[type="submit"]');
            saveArticlesToServer('Modèle de contrat enregistré avec succès.', btn);
        });
    }

    @if(session('active_tab') === 'contrat')
    (function() {
        var tabBtnContrat = document.getElementById('tab-btn-contrat');
        if (tabBtnContrat) {
            document.querySelectorAll('.nav-pills .nav-link').forEach(function(b) { b.classList.remove('active'); b.setAttribute('aria-selected','false'); });
            document.querySelectorAll('.tab-pane').forEach(function(p) { p.classList.remove('show','active'); });
            tabBtnContrat.classList.add('active');
            tabBtnContrat.setAttribute('aria-selected','true');
            var paneContrat = document.getElementById('navs-pills-justified-contrat');
            if (paneContrat) paneContrat.classList.add('show','active');
        }
    })();
    @endif

    renderArticlesList();
})();

// ---- Configuration Communication ----
(function() {
    const btn = document.getElementById('btnSaveCommConfig');
    if (!btn) return;
    btn.addEventListener('click', async function() {
        const form = document.getElementById('formCommConfig');
        const data = new FormData(form);
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Enregistrement…';
        try {
            const res  = await fetch('{{ route("store_comm_config") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: data,
            });
            const json = await res.json();
            if (json.status) {
                Swal.fire({ icon: 'success', title: 'Succès', text: json.message, timer: 2500, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Erreur', text: json.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Erreur réseau', text: err.message });
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-save me-1"></i>Enregistrer la configuration';
        }
    });
})();

// ---- Widget connexion WhatsApp ----
(function() {
    var WA = {
        pollTimer: null,
        qrTimer: null,
        modal: null,

        init: function() {
            document.addEventListener('DOMContentLoaded', function() {
                var modalEl = document.getElementById('modalWhatsAppQR');
                if (modalEl) {
                    WA.modal = new bootstrap.Modal(modalEl, { backdrop: 'static', keyboard: false });
                }
                WA.checkStatus();

                var btnConnect    = document.getElementById('btnConnectWA');
                var btnDisconnect = document.getElementById('btnDisconnectWA');

                if (btnConnect) btnConnect.addEventListener('click', function() { WA.connect(); });
                if (btnDisconnect) btnDisconnect.addEventListener('click', function() { WA.disconnect(); });
            });
        },

        setUI: function(st) {
            var icon    = document.getElementById('wa-status-icon');
            var label   = document.getElementById('wa-status-label');
            var sub     = document.getElementById('wa-status-sub');
            var btnCon  = document.getElementById('btnConnectWA');
            var btnDis  = document.getElementById('btnDisconnectWA');
            if (!icon) return;
            if (st === 'connected') {
                icon.textContent  = '✅';
                label.textContent = 'WhatsApp connecté';
                sub.textContent   = 'Vous pouvez envoyer des documents via WhatsApp.';
                if (btnCon) btnCon.style.display = 'none';
                if (btnDis) btnDis.style.display = '';
            } else if (st === 'waiting_qr') {
                icon.textContent  = '📱';
                label.textContent = 'En attente du scan QR…';
                sub.textContent   = 'Ouvrez WhatsApp sur votre téléphone et scannez le code.';
                if (btnCon) btnCon.style.display = 'none';
                if (btnDis) btnDis.style.display = '';
            } else {
                icon.textContent  = '🔴';
                label.textContent = 'WhatsApp non connecté';
                sub.textContent   = 'Cliquez sur "Connecter" pour démarrer.';
                if (btnCon) btnCon.style.display = '';
                if (btnDis) btnDis.style.display = 'none';
            }
        },

        checkStatus: function() {
            fetch('{{ route("whatsapp.status") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    WA.setUI(data.status || 'disconnected');
                    if (data.status === 'waiting_qr') {
                        WA.openQrModal();
                    }
                })
                .catch(function() { WA.setUI('disconnected'); });
        },

        connect: function() {
            var btnCon = document.getElementById('btnConnectWA');
            if (btnCon) { btnCon.disabled = true; btnCon.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Connexion…'; }

            fetch('{{ route("whatsapp.connect") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
            })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.status === 'starting') {
                    // Service en cours de démarrage : patienter puis relancer connect
                    if (btnCon) { btnCon.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Démarrage…'; }
                    var sub = document.getElementById('wa-status-sub');
                    if (sub) sub.textContent = 'Démarrage du service WhatsApp, veuillez patienter…';
                    WA._waitAndConnect(0);
                } else if (data.status) {
                    WA.setUI('waiting_qr');
                    if (btnCon) { btnCon.disabled = false; btnCon.innerHTML = '<i class="bx bx-qr-scan me-1"></i>Connecter'; }
                    setTimeout(function() { WA.openQrModal(); }, 1000);
                } else {
                    Swal.fire({ icon: 'error', title: 'Erreur', text: data.message });
                    if (btnCon) { btnCon.disabled = false; btnCon.innerHTML = '<i class="bx bx-qr-scan me-1"></i>Connecter'; }
                }
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: 'Erreur', text: 'Impossible de joindre le serveur.' });
                if (btnCon) { btnCon.disabled = false; btnCon.innerHTML = '<i class="bx bx-qr-scan me-1"></i>Connecter'; }
            });
        },

        // Attend que le service soit disponible (max 120s) puis relance connect
        _waitAndConnect: function(attempt) {
            var maxAttempts = 40; // 40 × 3s = 120s
            var btnCon = document.getElementById('btnConnectWA');
            var sub    = document.getElementById('wa-status-sub');

            if (attempt >= maxAttempts) {
                if (btnCon) { btnCon.disabled = false; btnCon.innerHTML = '<i class="bx bx-qr-scan me-1"></i>Connecter'; }
                if (sub) sub.textContent = 'Service non démarré. Cliquez pour réessayer.';
                Swal.fire({
                    icon: 'warning',
                    title: 'Démarrage lent',
                    text: 'Le service WhatsApp prend plus de temps que prévu. Cliquez sur "Connecter" dans quelques secondes pour réessayer.',
                    confirmButtonText: 'OK'
                });
                return;
            }

            var remaining = Math.round((maxAttempts - attempt) * 3);
            if (btnCon) btnCon.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Démarrage (' + remaining + 's)…';
            if (sub) sub.textContent = 'Démarrage du service WhatsApp en cours, veuillez patienter…';

            setTimeout(function() {
                fetch('{{ route("whatsapp.status") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    if (data.status && data.status !== 'disconnected') {
                        // Service prêt → lancer la vraie connexion
                        if (btnCon) { btnCon.disabled = false; btnCon.innerHTML = '<i class="bx bx-qr-scan me-1"></i>Connecter'; }
                        WA.connect();
                    } else {
                        WA._waitAndConnect(attempt + 1);
                    }
                })
                .catch(function() { WA._waitAndConnect(attempt + 1); });
            }, 3000);
        },

        openQrModal: function() {
            if (!WA.modal) return;

            var img     = document.getElementById('wa-qr-img');
            var spinner = document.getElementById('wa-qr-loading');
            if (img)     { img.onload = null; img.onerror = null; img.style.display = 'none'; img.src = ''; }
            if (spinner) { spinner.style.display = ''; }

            WA.modal.show();
            if (WA.qrTimer) clearInterval(WA.qrTimer);

            // Vérifie le statut (léger) puis charge l'image PNG directement
            // via /qr-image (pas de base64 en JSON → pas de surcharge)
            WA.qrTimer = setInterval(function() {
                fetch('{{ route("whatsapp.status") }}', {
                    credentials: 'same-origin',
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    var img     = document.getElementById('wa-qr-img');
                    var spinner = document.getElementById('wa-qr-loading');
                    if (!img) return;

                    if (data.status === 'connected') {
                        clearInterval(WA.qrTimer);
                        WA.modal.hide();
                        WA.setUI('connected');
                        Swal.fire({ icon: 'success', title: 'WhatsApp connecté !', text: 'Vous pouvez maintenant envoyer des documents.', timer: 3000, showConfirmButton: false });

                    } else if (data.status === 'waiting_qr') {
                        // Charge le PNG directement (sans JSON/base64)
                        var url = '{{ route("whatsapp.qr_image") }}?t=' + Date.now();
                        var dbg = document.getElementById('wa-debug');
                        if (dbg) dbg.textContent = '⏳ Chargement image…';
                        img.onload = function() {
                            if (dbg) dbg.textContent = '✅ Image chargée (' + img.naturalWidth + 'x' + img.naturalHeight + ')';
                            img.style.display = '';
                            if (spinner) spinner.style.display = 'none';
                        };
                        img.onerror = function() {
                            if (dbg) dbg.textContent = '❌ Erreur chargement image (503 ou réseau)';
                            img.style.display = 'none';
                            if (spinner) spinner.style.display = '';
                        };
                        img.src = url;
                    } else {
                        var dbg = document.getElementById('wa-debug');
                        if (dbg) dbg.textContent = 'Status reçu: ' + data.status;
                    }
                    // 'disconnected' → spinner reste
                })
                .catch(function() {});
            }, 2000);
        },

        disconnect: function() {
            Swal.fire({
                title: 'Déconnecter WhatsApp ?',
                text: 'Vous devrez rescanner le QR code pour renvoyer des documents.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: '<i class="bx bx-power-off me-1"></i>Déconnecter',
                cancelButtonText: 'Annuler',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
            }).then(function(result) {
                if (!result.isConfirmed) return;
                fetch('{{ route("whatsapp.disconnect") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' },
                })
                .then(function(r) { return r.json(); })
                .then(function(data) {
                    WA.setUI('disconnected');
                    Swal.fire({ icon: 'success', title: 'Déconnecté', text: data.message, timer: 2000, showConfirmButton: false });
                })
                .catch(function() {});
            });
        },
    };

    WA.init();
})();
</script>

{{-- ===== MODAL QR WHATSAPP ===== --}}
<div class="modal fade" id="modalWhatsAppQR" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title"><i class="bx bxl-whatsapp me-2 text-success"></i>Connexion WhatsApp de l'agence</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-3">
                <div class="row align-items-center">
                    <div class="col-md-5 text-center">
                        <p class="text-muted mb-3" style="font-size:.88rem;">
                            Ouvrez <strong>WhatsApp</strong> sur votre téléphone :<br>
                            Menu ⋮ → <em>Appareils associés</em><br>→ <em>Associer un appareil</em>
                        </p>
                        <div class="d-flex flex-column align-items-center gap-2">
                            <span class="badge bg-label-info">Étape 1</span>
                            <small class="text-muted">Attendez l'affichage de l'écran WhatsApp Web</small>
                            <span class="badge bg-label-success mt-2">Étape 2</span>
                            <small class="text-muted">Scannez le QR code visible à droite</small>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="d-flex justify-content-center align-items-center rounded overflow-hidden"
                             style="min-height:260px;background:#f0f4f8;border:1px solid #dee2e6;">
                            <img id="wa-qr-img" src="" alt="Écran WhatsApp Web"
                                 style="max-width:100%;max-height:400px;display:none;border-radius:4px;">
                            <div id="wa-qr-loading" class="text-muted text-center p-4">
                                <div class="spinner-border text-success mb-2" style="width:2.5rem;height:2.5rem;"></div>
                                <br>
                                <small>Démarrage de WhatsApp Web…<br>L'écran apparaîtra dans quelques secondes.</small>
                            </div>
                        </div>
                        <div id="wa-debug" style="font-size:11px;color:#d63384;margin-top:4px;text-align:center;min-height:16px;"></div>
                        <small class="text-muted d-block mt-2 text-center">
                            <i class="bx bx-refresh me-1"></i>L'écran se rafraîchit automatiquement toutes les 2 s.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL ARTICLE (hors tab-content pour éviter tout conflit Bootstrap) ===== --}}
<div class="modal fade" id="modalArticle" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalArticleTitle">Ajouter un article</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="article-edit-index" value="">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Titre de l'article <span class="text-danger">*</span></label>
                    <input type="text" id="article-titre" class="form-control" placeholder="Ex: Durée du bail">
                    <div class="invalid-feedback" id="err-article-titre"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Contenu <span class="text-danger">*</span></label>
                    <textarea id="article-contenu" class="form-control" rows="8"
                        placeholder="Rédigez le contenu de l'article. Utilisez les variables comme {montant_loyer}, {nom_locataire}, etc."></textarea>
                    <div class="invalid-feedback" id="err-article-contenu"></div>
                    <small class="text-muted">Utilisez les retours à la ligne pour la mise en forme.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" onclick="contratSaveArticle()">
                    <i class="bx bx-save me-1"></i>Enregistrer
                </button>
            </div>
        </div>
    </div>
</div>
@endsection