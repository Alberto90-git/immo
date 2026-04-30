@extends('layouts.template')

@section('content')
@section('title')
    <title>{{ __('pages.param_title') }}</title>
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

    /* ── Dark mode overrides (parametre) ── */
    html.dark-style .ms-tags {
        background: #373852 !important;
        border-color: #444564 !important;
        color: #a3a4cc !important;
    }
    html.dark-style .ms-dropdown {
        background: #2f3049 !important;
        border-color: #444564 !important;
    }
    html.dark-style .ms-dropdown .ms-search {
        background: #373852 !important;
        border-color: #444564 !important;
    }
    html.dark-style .ms-dropdown .ms-search input {
        background: #2b2c40 !important;
        border-color: #444564 !important;
        color: #a3a4cc !important;
    }
    html.dark-style .ms-dropdown .ms-option {
        color: #a3a4cc !important;
    }
    html.dark-style .ms-dropdown .ms-option:hover { background: rgba(105,108,255,0.1) !important; }
    html.dark-style .ms-dropdown .ms-option.selected { background: rgba(105,108,255,0.15) !important; }
    html.dark-style .ms-placeholder { color: #6e7191 !important; }
    html.dark-style .ms-dropdown .ms-empty { color: #7f7f9d !important; }

    /* Séparateur d'onglets */
    .param-tab-sep-badge {
        color: #697a8d;
        background: #fff;
        border: 1px solid #dee2e6;
    }
    html.dark-style .param-tab-sep-badge {
        color: #a3a4cc !important;
        background: #373852 !important;
        border-color: #444564 !important;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('pages.home') }} /</span> {{ __('pages.param_breadcrumb') }}</h4>
    
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
                                <i class="tf-icons bx bx-home"></i> {{ __('pages.param_tab_logo') }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-pills-justified-profile" aria-controls="navs-pills-justified-profile"
                                aria-selected="false">
                                <i class="tf-icons bx bx-user"></i> {{ __('pages.param_tab_annexes') }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-pills-justified-pourcentage" aria-controls="navs-pills-justified-pourcentage"
                                aria-selected="false">
                                <i class="tf-icons bx bx-percent"></i> {{ __('pages.param_tab_pct') }}
                            </button>
                        </li>
                    </ul>
                </div>

                {{-- ══ SÉPARATEUR CATÉGORIEL ══ --}}
                <div class="d-flex align-items-center gap-3 my-2">
                    <hr class="flex-grow-1 my-0" style="border-top:1.5px solid #dee2e6;">
                    <span class="param-tab-sep-badge" style="white-space:nowrap;font-size:.7rem;font-weight:700;
                                 text-transform:uppercase;letter-spacing:.1em;
                                 padding:3px 14px;border-radius:20px;">
                        <i class="bx bx-cog" style="vertical-align:-1px;margin-right:4px;"></i>{{ __('pages.param_tab_sep') }}
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
                                <i class="tf-icons bx bx-file"></i> {{ __('pages.param_tab_contrat') }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-pills-justified-communication" aria-controls="navs-pills-justified-communication"
                                aria-selected="false" id="tab-btn-communication">
                                <i class="tf-icons bx bx-message-rounded-dots"></i> {{ __('pages.param_tab_comm') }}
                            </button>
                        </li>
                        <li class="nav-item">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-pills-justified-devise" aria-controls="navs-pills-justified-devise"
                                aria-selected="false" id="tab-btn-devise">
                                <i class="tf-icons bx bx-world"></i> Devise &amp; Région
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
                                    <th scope="col">{{ __('pages.param_th_cash') }}</th>
                                    <th scope="col">{{ __('pages.param_th_logo') }}</th>
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
                                                        {{ __('pages.param_img_formats') }}
                                                    </div>
                                                    <span class="invalid-feedback cash_electronique_err" role="alert"></span>
                                                </div>
                                            </div>

                                            @php
                                                $currentCachet = collect($param ?? [])->first(fn($i) => $i->cash_electronique_url);
                                            @endphp
                                            @if($currentCachet)
                                                <div class="mt-3 text-center">
                                                    <p class="mb-1 text-muted small fw-semibold">
                                                        <i class="bx bx-image-alt me-1"></i>{{ __('pages.param_cachet_current') }}
                                                    </p>
                                                    <img src="{{ $currentCachet->cash_electronique_url }}"
                                                        alt="{{ __('pages.param_cachet_current') }}"
                                                        id="preview-cachet"
                                                        class="img-thumbnail rounded"
                                                        style="max-width: 180px; max-height: 180px; object-fit: contain; background: #fff;">
                                                </div>
                                            @else
                                                <div class="mt-3 text-center text-muted small">
                                                    <i class="bx bx-image bx-sm"></i>
                                                    <p class="mb-0">{{ __('pages.param_cachet_none') }}</p>
                                                </div>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="row mb-3">
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="file" name="logo" 
                                                        id="logo" accept="image/*">
                                                    <div class="form-text">
                                                        {{ __('pages.param_img_formats') }}
                                                    </div>
                                                    <span class="invalid-feedback logo_err" role="alert"></span>
                                                </div>
                                            </div>

                                            @php
                                                $currentLogo = collect($param ?? [])->first(fn($i) => $i->logo_url);
                                            @endphp
                                            @if($currentLogo)
                                                <div class="mt-3 text-center">
                                                    <p class="mb-1 text-muted small fw-semibold">
                                                        <i class="bx bx-image-alt me-1"></i>{{ __('pages.param_logo_current') }}
                                                    </p>
                                                    <img src="{{ $currentLogo->logo_url }}"
                                                        alt="{{ __('pages.param_logo_current') }}"
                                                        id="preview-logo"
                                                        class="img-thumbnail rounded"
                                                        style="max-width: 180px; max-height: 180px; object-fit: contain; background: #fff;">
                                                </div>
                                            @else
                                                <div class="mt-3 text-center text-muted small">
                                                    <i class="bx bx-image bx-sm"></i>
                                                    <p class="mb-0">{{ __('pages.param_logo_none') }}</p>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @can('modifier-parametre')
                                                <button class="btn btn-primary" id="valider">
                                                    <span class="fa fa-save" id="a"></span>
                                                    <span id="s">{{ __('pages.param_btn_save') }}</span>
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
                                        <h5 class="modal-title" id="modalCenterTitle">{{ __('pages.param_annexe_add_title') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form class="row g-3" method="post" action="javascript:save_annexe();"
                                            id="formulaireAnnexe">
                                            @csrf


                                            <div class="col-md-6">
                                                <label for="inputNanme4" class="form-label">{{ __('pages.param_lbl_designation') }}<span
                                                        style="color: red;">*</span></label>
                                                <input type="text" name="designation" class="form-control"
                                                    id="designation" required="">
                                                <span class="invalid-feedback designation_err" role="alert">
                                                </span>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="inputEmail4" class="form-label">{{ __('pages.param_lbl_address') }}<span
                                                        style="color: red;">*</span></label>
                                                <input type="text" name="adresse" class="form-control" id="adresse"
                                                    required="">
                                                <span class="invalid-feedback adresse_err" role="alert">
                                                </span>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="inputPassword4" class="form-label">{{ __('pages.param_lbl_phone') }}<span
                                                        style="color: red;">*</span></label>
                                                <input type="text" name="telephone" class="form-control"
                                                    id="telephone" required="" placeholder="+2290161000000">
                                                <span class="invalid-feedback telephone_err" role="alert">
                                                </span>
                                            </div>


                                            <div class="col-md-6">
                                                <label for="inputAddress" class="form-label">{{ __('pages.param_th_email') }}<span
                                                        style="color: red;">*</span></label>
                                                <input type="email" name="email" class="form-control" id="email"
                                                    required="">
                                                <span class="invalid-feedback email_err" role="alert">
                                                </span>
                                            </div>


                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" onclick="(this);"
                                                    id="close" data-bs-dismiss="modal">{{ __('pages.param_annexe_close') }}
                                                </button>
                                                <button class="btn btn-primary" id="valider">
                                                    <span class="fa fa-save" id="a"></span>
                                                    <span id="s">{{ __('pages.param_btn_save') }}</span>
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
                                        <h5 class="card-title text-center">{{ __('pages.param_annexe_list_title') }}</h5>

                                        <table class="table datatable border-primary">
                                            <thead>
                                                <tr>
                                                    <th scope="col">{{ __('pages.param_th_designation') }}</th>
                                                    <th scope="col">{{ __('pages.param_th_address') }}</th>
                                                    <th scope="col">{{ __('pages.param_th_phone') }}</th>
                                                    <th scope="col">{{ __('pages.param_th_email') }}</th>
                                                    <th scope="col">{{ __('pages.param_th_actions') }}</th>
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
                                                        <h5 class="modal-title" id="modalCenterTitle">{{ __('pages.param_delete_title') }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="row g-3" method="post"
                                                            action="{{ route('destroy_annexe') }}">
                                                            {{ __('pages.param_delete_confirm') }}
                                                            @csrf
                                                            <input type="hidden" name="id" class="form-control"
                                                                id="id" value="{{ $items->idannexes }} ">
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">{{ __('pages.param_delete_no') }}</button>
                                                                <button type="submit" class="btn btn-danger">{{ __('pages.param_delete_yes') }}</button>
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
                                                        <h5 class="modal-title text-white" id="modalCenterTitle">{{ __('pages.param_annexe_edit_title') }}</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <form class="row g-3" method="post" action="{{ route('update_annexe') }}" enctype="multipart/form-data">
                                                            @csrf

                                                            <input type="hidden" name="id" class="form-control" id="id" value="{{ $items->idannexes }}">

                                                            <div class="col-md-6">
                                                                <label for="inputNanme4" class="form-label">{{ __('pages.param_lbl_designation') }}<span
                                                                        style="color: red;">*</span></label>
                                                                <input type="text" name="designation" class="form-control"
                                                                    id="designation" value="{{ $items->designation }}" required="">
                                                                <span class="invalid-feedback designation_err" role="alert">
                                                                </span>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="inputEmail4" class="form-label">{{ __('pages.param_lbl_address') }}<span
                                                                        style="color: red;">*</span></label>
                                                                <input type="text" name="adresse" class="form-control" id="adresse"
                                                                    required=""  value="{{ $items->siege_social }}">
                                                                <span class="invalid-feedback adresse_err" role="alert">
                                                                </span>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <label for="telephone{{ $loop->iteration }}" class="form-label d-block">{{ __('pages.param_lbl_phone') }}<span style="color:red;">*</span></label>
                                                                <div style="width:100%;display:block;">
                                                                    <input type="text" name="telephone" class="form-control"
                                                                        id="telephone{{ $loop->iteration }}"
                                                                        required value="{{ $items->telephone }}"
                                                                        style="width:100%;">
                                                                </div>
                                                                <style>
                                                                    #telephone{{ $loop->iteration }}~.iti,
                                                                    .iti:has(#telephone{{ $loop->iteration }}) { width: 100% !important; }
                                                                </style>
                                                                <span class="invalid-feedback telephone_err" role="alert"></span>
                                                            </div>


                                                            <div class="col-md-6">
                                                                <label for="inputAddress" class="form-label">{{ __('pages.param_th_email') }}<span
                                                                        style="color: red;">*</span></label>
                                                                <input type="email" name="email" class="form-control" id="email"
                                                                    required=""  value="{{ $items->email }}">
                                                                <span class="invalid-feedback email_err" role="alert">
                                                                </span>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('pages.param_annexe_close') }}</button>
                                                                <button class="btn btn-primary"><i class="bx bx-save me-1"></i>{{ __('pages.param_btn_save') }}</button>
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
                                                        containerClass: "w-100",
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
                                <h5 class="mb-0 text-white"><i class="bx bx-slider-alt me-2"></i>{{ __('pages.param_pct_gen_title') }}</h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-4">{{ __('pages.param_pct_gen_desc') }}</p>

                                <form method="POST" action="javascript:save_pourcentage_general();" id="formPourcentageGeneral">
                                    @csrf
                                    <div class="row align-items-end">
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold">{{ __('pages.param_pct_lbl') }}<span style="color:red;">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="pourcentage" id="pourcentage_general_input"
                                                    class="form-control" step="0.01" min="0" max="100"
                                                    value="{{ isset($pourcentageGeneral) ? $pourcentageGeneral->pourcentage : 10 }}" required>
                                                <span class="input-group-text">%</span>
                                            </div>
                                            <span class="invalid-feedback pourcentage_err" role="alert"></span>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            <label class="form-label fw-bold">{{ __('pages.param_pct_status') }}</label>
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="toggle_general"
                                                    {{ (isset($pourcentageGeneral) && $pourcentageGeneral->is_active) ? 'checked' : '' }}
                                                    onchange="toggle_pourcentage({{ isset($pourcentageGeneral) ? $pourcentageGeneral->id : 0 }})">
                                                <label class="form-check-label" for="toggle_general" id="label_toggle_general">
                                                    <span class="badge {{ (isset($pourcentageGeneral) && $pourcentageGeneral->is_active) ? 'bg-success' : 'bg-secondary' }}">
                                                        {{ (isset($pourcentageGeneral) && $pourcentageGeneral->is_active) ? __('pages.param_pct_active') : __('pages.param_pct_inactive') }}
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mb-3">
                                            @can('modifier-parametre')
                                                <button class="btn btn-primary" id="btnSavePourcentageGeneral" type="submit">
                                                    <i class="bx bx-save me-1"></i> {{ __('pages.param_btn_save') }}
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
                                <h5 class="mb-0 text-white"><i class="bx bx-group me-2"></i>{{ __('pages.param_grp_title') }}</h5>
                                @can('modifier-parametre')
                                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#ajouterGroupe">
                                        <i class="bx bx-plus me-1"></i> {{ __('pages.param_grp_new') }}
                                    </button>
                                @endcan
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-4">{{ __('pages.param_grp_desc') }}</p>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="tableGroupes">
                                        <thead class="table-light">
                                            <tr>
                                                <th>{{ __('pages.param_th_grp_name') }}</th>
                                                <th>{{ __('pages.param_th_pct') }}</th>
                                                <th>{{ __('pages.param_th_owners') }}</th>
                                                <th>{{ __('pages.param_pct_status') }}</th>
                                                <th>{{ __('pages.param_th_actions') }}</th>
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
                                                                    {{ $groupe->is_active ? __('pages.param_pct_active') : __('pages.param_pct_inactive') }}
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
                                                        <i class="bx bx-info-circle me-1"></i> {{ __('pages.param_grp_none') }}
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
                                        <h5 class="modal-title"><i class="bx bx-plus-circle me-2"></i>{{ __('pages.param_grp_add_title') }}</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="POST" action="javascript:save_groupe();" id="formGroupe">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">{{ __('pages.param_lbl_grp_name') }}<span style="color:red;">*</span></label>
                                                    <input type="text" name="nom" class="form-control" id="groupe_nom" placeholder="Ex: Premium, Standard..." required>
                                                    <span class="invalid-feedback nom_err" role="alert"></span>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label class="form-label fw-bold">{{ __('pages.param_pct_lbl') }}<span style="color:red;">*</span></label>
                                                    <div class="input-group">
                                                        <input type="number" name="pourcentage" class="form-control" id="groupe_pourcentage"
                                                            step="0.01" min="0" max="100" placeholder="Ex: 15" required>
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                    <span class="invalid-feedback pourcentage_err" role="alert"></span>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">{{ __('pages.param_lbl_owners') }}<span style="color:red;">*</span></label>
                                                <div class="ms-wrapper" id="ms-add-wrapper">
                                                    <div class="ms-tags" id="ms-add-tags">
                                                        <span class="ms-placeholder">{{ __('pages.param_ms_placeholder') }}</span>
                                                    </div>
                                                    <div class="ms-dropdown" id="ms-add-dropdown">
                                                        <div class="ms-search">
                                                            <input type="text" placeholder="{{ __('pages.param_ms_search') }}" id="ms-add-search">
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
                                                <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">{{ __('pages.param_btn_cancel') }}</button>
                                                <button type="submit" class="btn btn-primary" id="btnSaveGroupe">
                                                    <i class="bx bx-save me-1"></i> {{ __('pages.param_btn_save') }}
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
                                                <h5 class="modal-title"><i class="bx bx-edit me-2"></i>{{ __('pages.param_grp_edit_prefix') }} "{{ $groupe->nom }}"</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="javascript:update_groupe({{ $groupe->id }});" id="formGroupeEdit{{ $groupe->id }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $groupe->id }}">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-bold">{{ __('pages.param_lbl_grp_name') }}<span style="color:red;">*</span></label>
                                                            <input type="text" name="nom" class="form-control" value="{{ $groupe->nom }}" required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label fw-bold">{{ __('pages.param_pct_lbl') }}<span style="color:red;">*</span></label>
                                                            <div class="input-group">
                                                                <input type="number" name="pourcentage" class="form-control"
                                                                    value="{{ $groupe->pourcentage }}" step="0.01" min="0" max="100" required>
                                                                <span class="input-group-text">%</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">{{ __('pages.param_lbl_owners') }}<span style="color:red;">*</span></label>
                                                        <div class="ms-wrapper" id="ms-edit-wrapper-{{ $groupe->id }}">
                                                            <div class="ms-tags" id="ms-edit-tags-{{ $groupe->id }}">
                                                                <span class="ms-placeholder">{{ __('pages.param_ms_placeholder') }}</span>
                                                            </div>
                                                            <div class="ms-dropdown" id="ms-edit-dropdown-{{ $groupe->id }}">
                                                                <div class="ms-search">
                                                                    <input type="text" placeholder="{{ __('pages.param_ms_search') }}" id="ms-edit-search-{{ $groupe->id }}">
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
                                                        <button type="button" class="btn btn-outline-secondary me-2" data-bs-dismiss="modal">{{ __('pages.param_btn_cancel') }}</button>
                                                        <button type="submit" class="btn btn-primary" id="btnUpdateGroupe{{ $groupe->id }}">
                                                            <i class="bx bx-save me-1"></i> {{ __('pages.param_btn_update') }}
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
                                <h6 class="text-primary fw-bold"><i class="bx bx-file me-1"></i>{{ __('pages.param_contrat_heading') }}</h6>
                                <p class="text-muted small">{{ __('pages.param_contrat_desc') }}</p>
                            </div>

                            {{-- Variables disponibles --}}
                            <div class="col-12 mb-3">
                                <div class="accordion" id="accordionVariables">
                                    <div class="accordion-item border">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVariables">
                                                <i class="bx bx-code-alt me-2 text-primary"></i> <strong>{{ __('pages.param_vars_accordion') }}</strong> <span class="ms-2 text-muted small">{{ __('pages.param_vars_click') }}</span>
                                            </button>
                                        </h2>
                                        <div id="collapseVariables" class="accordion-collapse collapse">
                                            <div class="accordion-body py-2">
                                                <div class="row g-2">
                                                    @php
                                                    $vars = [
                                                        ['{nom_agence}',__('pages.param_var_agency_name')],['{adresse_agence}',__('pages.param_var_agency_address')],['{telephone_agence}',__('pages.param_var_agency_phone')],
                                                        ['{nom_locataire}',__('pages.param_var_tenant_name')],['{telephone_locataire}',__('pages.param_var_tenant_phone')],['{profession_locataire}',__('pages.param_var_tenant_job')],['{adresse_locataire}',__('pages.param_var_tenant_address')],
                                                        ['{nom_maison}',__('pages.param_var_house_name')],['{quartier_maison}',__('pages.param_var_house_district')],['{type_chambre}',__('pages.param_var_room_type')],['{numero_chambre}',__('pages.param_var_room_number')],
                                                        ['{montant_loyer}',__('pages.param_var_rent')],['{nombre_caution}',__('pages.param_var_caution_months')],['{montant_caution}',__('pages.param_var_caution_amount')],['{caution_courant}',__('pages.param_var_caution_elec')],['{caution_eau}',__('pages.param_var_caution_water')],
                                                        ['{nombre_avance}',__('pages.param_var_advance_months')],['{montant_avance}',__('pages.param_var_advance_amount')],['{mode_paiement}',__('pages.param_var_payment_mode')],
                                                        ['{date_entree}',__('pages.param_var_entry_date')],['{date_contrat}',__('pages.param_var_contract_date')],
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
                                                <p class="small text-muted mt-2 mb-0"><i class="bx bx-mouse-alt me-1"></i>{{ __('pages.param_vars_copy_hint') }}</p>
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
                                                    <label class="form-label fw-semibold">{{ __('pages.param_contrat_title_lbl') }} <span class="text-danger">*</span></label>
                                                    <input type="text" name="titre_contrat" class="form-control" required
                                                        value="{{ $contratConfig->titre_contrat ?? 'Contrat de Bail d\'Habitation' }}"
                                                        placeholder="{{ __('pages.param_contrat_title_ph') }}">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">{{ __('pages.param_contrat_subtitle_lbl') }}</label>
                                                    <input type="text" name="sous_titre" class="form-control"
                                                        value="{{ $contratConfig->sous_titre ?? '' }}"
                                                        placeholder="{{ __('pages.param_contrat_sub_ph') }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Liste des articles --}}
                                    <div class="card border-0 shadow-sm mb-3">
                                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                                            <span class="fw-semibold"><i class="bx bx-list-ul me-1 text-primary"></i>{{ __('pages.param_articles_title') }}</span>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="contratOpenModal()">
                                                <i class="bx bx-plus me-1"></i>{{ __('pages.param_article_add_btn') }}
                                            </button>
                                        </div>
                                        <div class="card-body p-0">
                                            <div id="articles-list" class="list-group list-group-flush">
                                                {{-- Rempli par JS --}}
                                            </div>
                                            <p id="articles-empty" class="text-center text-muted py-4 mb-0 d-none">
                                                <i class="bx bx-info-circle me-1"></i>{{ __('pages.param_article_empty') }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Inputs hidden pour la soumission --}}
                                    <div id="articles-hidden-inputs"></div>

                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-save me-1"></i>{{ __('pages.param_btn_save_template') }}
                                        </button>
                                    </div>
                                </form>

                                {{-- Bouton reset séparé --}}
                                @if($contratConfig)
                                <form method="POST" action="{{ route('reset_contrat_config') }}" id="formResetContrat" class="d-inline mt-2">
                                    @csrf
                                    <button type="button" class="btn btn-outline-secondary btn-sm mt-2" id="btnResetContrat">
                                        <i class="bx bx-reset me-1"></i>{{ __('pages.param_btn_reset') }}
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
                                        <h6 class="mb-0"><i class="bx bx-message-rounded-dots me-2 text-primary"></i>{{ __('pages.param_comm_title') }}</h6>
                                        <small class="text-muted">{{ __('pages.param_comm_subtitle') }}</small>
                                    </div>
                                    <div class="card-body">
                                        <form id="formCommConfig">
                                            @csrf
                                            <h6 class="mb-3 text-primary"><i class="bx bx-envelope me-1"></i>{{ __('pages.param_comm_email_sect') }}</h6>
                                            <div class="mb-4">
                                                <label class="form-label fw-semibold" for="emailEnvoi">{{ __('pages.param_comm_email_lbl') }}</label>
                                                <input type="email" class="form-control" id="emailEnvoi" name="email_envoi"
                                                       value="{{ $param->first()?->email_envoi ?? '' }}"
                                                       placeholder="{{ __('pages.param_comm_email_ph') }}">
                                                <small class="text-muted">{{ __('pages.param_comm_email_hint') }}</small>
                                            </div>

                                            <div class="alert alert-secondary py-2 px-3 mb-0" style="font-size:.85rem;">
                                                <i class="bx bx-info-circle me-1"></i>
                                                {!! __('pages.param_comm_at_alert') !!}
                                            </div>

                                            <hr class="my-4">

                                            {{-- ── Fuseau horaire ── --}}
                                            <h6 class="mb-3 text-primary"><i class="bx bx-time me-1"></i>Fuseau horaire</h6>
                                            <div class="mb-3">
                                                <label class="form-label fw-semibold" for="timezoneSelect">Fuseau horaire de l'agence</label>
                                                <select class="form-select" id="timezoneSelect" name="timezone">
                                                    @php
                                                        $currentTz = $param->first()?->timezone ?? 'Africa/Porto-Novo';
                                                        $timezones = [
                                                            'Africa/Porto-Novo'   => '🇧🇯 Bénin — Africa/Porto-Novo (UTC+1)',
                                                            'Africa/Lagos'        => '🇳🇬 Nigeria — Africa/Lagos (UTC+1)',
                                                            'Africa/Niamey'       => '🇳🇪 Niger — Africa/Niamey (UTC+1)',
                                                            'Africa/Ndjamena'     => '🇹🇩 Tchad — Africa/Ndjamena (UTC+1)',
                                                            'Africa/Douala'       => '🇨🇲 Cameroun — Africa/Douala (UTC+1)',
                                                            'Africa/Libreville'   => '🇬🇦 Gabon — Africa/Libreville (UTC+1)',
                                                            'Africa/Brazzaville'  => '🇨🇬 Congo — Africa/Brazzaville (UTC+1)',
                                                            'Africa/Kinshasa'     => '🇨🇩 RDC — Africa/Kinshasa (UTC+1)',
                                                            'Africa/Abidjan'      => '🇨🇮 Côte d\'Ivoire — Africa/Abidjan (UTC+0)',
                                                            'Africa/Dakar'        => '🇸🇳 Sénégal — Africa/Dakar (UTC+0)',
                                                            'Africa/Bamako'       => '🇲🇱 Mali — Africa/Bamako (UTC+0)',
                                                            'Africa/Ouagadougou'  => '🇧🇫 Burkina Faso — Africa/Ouagadougou (UTC+0)',
                                                            'Africa/Lome'         => '🇹🇬 Togo — Africa/Lome (UTC+0)',
                                                            'Africa/Accra'        => '🇬🇭 Ghana — Africa/Accra (UTC+0)',
                                                            'Africa/Conakry'      => '🇬🇳 Guinée — Africa/Conakry (UTC+0)',
                                                            'Africa/Bissau'       => '🇬🇼 Guinée-Bissau — Africa/Bissau (UTC+0)',
                                                            'Africa/Freetown'     => '🇸🇱 Sierra Leone — Africa/Freetown (UTC+0)',
                                                            'Africa/Monrovia'     => '🇱🇷 Liberia — Africa/Monrovia (UTC+0)',
                                                            'Africa/Nairobi'      => '🇰🇪 Kenya — Africa/Nairobi (UTC+3)',
                                                            'Africa/Johannesburg' => '🇿🇦 Afrique du Sud — Africa/Johannesburg (UTC+2)',
                                                            'Europe/Paris'        => '🇫🇷 France — Europe/Paris (UTC+1/+2)',
                                                            'UTC'                 => '🌍 UTC (UTC+0)',
                                                        ];
                                                    @endphp
                                                    @foreach($timezones as $tz => $label)
                                                        <option value="{{ $tz }}" {{ $currentTz === $tz ? 'selected' : '' }}>
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted">Ce fuseau horaire est utilisé pour les envois automatiques de rappels.</small>
                                            </div>

                                            <div class="d-flex gap-2 mt-3">
                                                <button type="button" class="btn btn-primary" id="btnSaveCommConfig">
                                                    <i class="bx bx-save me-1"></i>{{ __('pages.param_btn_save_comm') }}
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ═══════════════════════════════════════════════════════════
                         ONGLET 6 — DEVISE & RÉGION
                    ═══════════════════════════════════════════════════════════ --}}
                    <div class="tab-pane fade" id="navs-pills-justified-devise" role="tabpanel">
                        <div class="px-2 py-3">

                            @php
                                $dp        = $deviseParamtre;
                                $paysActuel   = $dp->pays    ?? 'BJ';
                                $deviseActuel = $dp->devise  ?? 'XOF';
                                $indicatif    = $dp->indicatif_tel ?? '';
                                $formatDate   = $dp->format_date   ?? 'd/m/Y';
                                $tauxChange   = $dp->taux_change   ?? [];
                                $taxes        = $dp->taxes         ?? [];
                                $paysJs       = json_encode($paysList);
                            @endphp

                            <form id="formDeviseConfig">
                                @csrf

                                {{-- Section : Localisation --}}
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom">
                                        <h6 class="mb-0 fw-bold"><i class="bx bx-map me-2 text-primary"></i>Localisation</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            {{-- Pays --}}
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Pays <span class="text-danger">*</span></label>
                                                <select name="pays" id="devise-pays" class="form-select" onchange="onPaysChange(this.value)">
                                                    @foreach($paysList as $code => $p)
                                                    <option value="{{ $code }}" {{ $paysActuel === $code ? 'selected' : '' }}>
                                                        {{ $p['drapeau'] }} {{ $p['nom'] }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-text">Détermine les valeurs par défaut (devise, indicatif, format date).</div>
                                            </div>

                                            {{-- Devise --}}
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Devise <span class="text-danger">*</span></label>
                                                <select name="devise" id="devise-devise" class="form-select" onchange="onDeviseChange(this.value)">
                                                    @foreach($devisesList as $d)
                                                    <option value="{{ $d['code'] }}" {{ $deviseActuel === $d['code'] ? 'selected' : '' }}>
                                                        {{ $d['code'] }} — {{ $d['label'] }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            {{-- Indicatif téléphonique --}}
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Indicatif téléphonique</label>
                                                <input type="text" name="indicatif_tel" id="devise-indicatif"
                                                       class="form-control" value="{{ $indicatif }}" placeholder="+229" maxlength="6">
                                            </div>

                                            {{-- Format date --}}
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Format de date</label>
                                                <select name="format_date" id="devise-format-date" class="form-select">
                                                    <option value="d/m/Y" {{ $formatDate==='d/m/Y' ?'selected':'' }}>JJ/MM/AAAA (ex: 19/04/2026)</option>
                                                    <option value="m/d/Y" {{ $formatDate==='m/d/Y' ?'selected':'' }}>MM/JJ/AAAA (ex: 04/19/2026)</option>
                                                    <option value="Y-m-d" {{ $formatDate==='Y-m-d' ?'selected':'' }}>AAAA-MM-JJ (ex: 2026-04-19)</option>
                                                </select>
                                            </div>

                                            {{-- Aperçu --}}
                                            <div class="col-md-4 d-flex align-items-end">
                                                <div class="bg-light rounded p-3 w-100 text-center">
                                                    <small class="text-muted d-block mb-1">Aperçu format montant</small>
                                                    <strong id="devise-apercu" class="fs-5 text-primary">—</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Section : Taux de change --}}
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                                        <h6 class="mb-0 fw-bold"><i class="bx bx-transfer me-2 text-success"></i>Taux de change</h6>
                                        <small class="text-muted">1 unité de devise étrangère = X {{ $deviseActuel }}</small>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted small mb-3">
                                            Configurez combien d'unités de votre devise (<strong id="devise-base-label">{{ $deviseActuel }}</strong>)
                                            correspondent à 1 unité de chaque devise étrangère.
                                            Ces taux permettent d'afficher des conversions dans les rapports.
                                        </p>
                                        <div class="row g-3" id="taux-change-container">
                                            @foreach($devisesList as $d)
                                                @if($d['code'] !== $deviseActuel)
                                                <div class="col-md-4 taux-row" data-devise="{{ $d['code'] }}">
                                                    <label class="form-label fw-semibold small">
                                                        1 {{ $d['code'] }} =
                                                        <span class="text-primary">X {{ $deviseActuel }}</span>
                                                    </label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.0001" min="0"
                                                               name="taux_change[{{ $d['code'] }}]"
                                                               class="form-control"
                                                               placeholder="ex: 655.957"
                                                               value="{{ $tauxChange[$d['code']] ?? '' }}">
                                                        <span class="input-group-text">{{ $deviseActuel }}</span>
                                                    </div>
                                                    <div class="form-text">{{ $d['label'] }}</div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <div class="mt-3">
                                            <div class="alert alert-info py-2 small mb-0">
                                                <i class="bx bx-info-circle me-1"></i>
                                                <strong>Taux de référence indicatifs (avril 2026) :</strong>
                                                XOF/EUR ≈ 655,96 | XOF/USD ≈ 600 | XOF/GHS ≈ 45 | XOF/NGN ≈ 0,40 | XOF/XAF = 1
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Section : Taxes --}}
                                <div class="card mb-4 border-0 shadow-sm">
                                    <div class="card-header bg-white border-bottom">
                                        <h6 class="mb-0 fw-bold"><i class="bx bx-receipt me-2 text-warning"></i>Taxes locales</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Nom de la taxe</label>
                                                <input type="text" name="nom_taxe" class="form-control"
                                                       value="{{ $taxes['nom_taxe'] ?? 'TVA' }}" placeholder="TVA, TPS...">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label fw-semibold">Taux (%)</label>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" min="0" max="100"
                                                           name="tva" class="form-control"
                                                           value="{{ $taxes['tva'] ?? 0 }}" placeholder="18">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <div class="col-md-5 d-flex align-items-end">
                                                <div class="form-check form-switch mt-2">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                           name="tva_applicable" id="tva-applicable" value="1"
                                                           {{ !empty($taxes['tva_applicable']) ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold" for="tva-applicable">
                                                        Appliquer la taxe sur les loyers/factures
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Bouton sauvegarde --}}
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary px-5" id="btnSaveDevise" onclick="saveDeviseConfig()">
                                        <i class="bx bx-save me-2"></i>Enregistrer la configuration
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>{{-- /tab-pane devise --}}

                </div>{{-- /tab-content --}}

            @endcan
        </div>{{-- /nav-align-top --}}
    </div>{{-- /col-xl-12 --}}
</div>{{-- /container-xxl --}}

<script>
    var PARAM_I18N = {
        error:          '{{ __('pages.param_js_error') }}',
        success:        '{{ __('pages.param_js_success') }}',
        info:           '{{ __('pages.param_js_info') }}',
        warning:        '{{ __('pages.param_js_warning') }}',
        valError:       '{{ __('pages.param_js_val_error') }}',
        fixErrors:      '{{ __('pages.param_js_fix_errors') }}',
        netError:       '{{ __('pages.param_js_net_error') }}',
        genError:       '{{ __('pages.param_js_gen_error') }}',
        selectImg:      '{{ __('pages.param_js_select_img') }}',
        cashTooBig:     '{{ __('pages.param_js_cash_too_big') }}',
        logoTooBig:     '{{ __('pages.param_js_logo_too_big') }}',
        saving:         '{{ __('pages.param_js_saving') }}',
        saveBtn:        '{!! __('pages.param_js_save_btn') !!}',
        save2:          '{{ __('pages.param_js_save2') }}',
        invalidPhone:   '{{ __('pages.param_js_invalid_phone') }}',
        beninPhone:     '{{ __('pages.param_js_benin_phone') }}',
        savePct:        '{!! __('pages.param_js_save_pct') !!}',
        pctFirst:       '{{ __('pages.param_js_pct_first') }}',
        updating:       '{{ __('pages.param_js_updating') }}',
        saveUpdate:     '{!! __('pages.param_js_save_update') !!}',
        delGrpTitle:    '{{ __('pages.param_js_del_grp_title') }}',
        delGrpText:     '{{ __('pages.param_js_del_grp_text') }}',
        delYes:         '{{ __('pages.param_js_del_yes') }}',
        cancel:         '{{ __('pages.param_btn_cancel') }}',
        msPlaceholder:  '{{ __('pages.param_js_ms_placeholder') }}',
        msNoResults:    '{{ __('pages.param_js_ms_no_results') }}',
        waitPage:       '{{ __('pages.param_js_wait_page') }}',
        articleEdit:    '{{ __('pages.param_js_article_edit') }}',
        articleAdd:     '{{ __('pages.param_js_article_add') }}',
        titleReq:       '{{ __('pages.param_js_title_req') }}',
        contentReq:     '{{ __('pages.param_js_content_req') }}',
        saveFail:       '{{ __('pages.param_js_save_fail') }}',
        serverError:    '{{ __('pages.param_js_server_error') }}',
        btnSaveTpl:     '{!! __('pages.param_js_btn_save_tpl') !!}',
        articleEdited:  '{{ __('pages.param_js_article_edited') }}',
        articleAdded:   '{{ __('pages.param_js_article_added') }}',
        delArtTitle:    '{{ __('pages.param_js_del_art_title') }}',
        delArtSaved:    '{{ __('pages.param_js_del_art_saved') }}',
        delBtn:         '{!! __('pages.param_js_del_btn') !!}',
        articleDel:     '{{ __('pages.param_js_article_del') }}',
        resetTitle:     '{{ __('pages.param_js_reset_title') }}',
        resetHtml:      '{!! __('pages.param_js_reset_html') !!}',
        resetBtn:       '{!! __('pages.param_js_reset_btn') !!}',
        noArticles:     '{{ __('pages.param_js_no_articles') }}',
        tplSaved:       '{{ __('pages.param_js_tpl_saved') }}',
        commSaving:     '{{ __('pages.param_js_comm_saving') }}',
        btnSaveComm:    '{!! __('pages.param_js_btn_save_comm') !!}',
        netErrTitle:    '{{ __('pages.param_js_net_err_title') }}',
    };

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
    

    // Aperçu en temps réel du cachet sélectionné
    document.getElementById('cash_electronique').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const preview = document.getElementById('preview-cachet');
        if (preview) {
            preview.src = URL.createObjectURL(file);
        }
    });

    // Aperçu en temps réel du logo sélectionné
    document.getElementById('logo').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;
        const preview = document.getElementById('preview-logo');
        if (preview) {
            preview.src = URL.createObjectURL(file);
        }
    });

    function save_parametre() {
        const formData = new FormData(document.getElementById('formulaire'));

        // Validation côté client
        const cashElectronique = document.getElementById('cash_electronique').files[0];
        const logo = document.getElementById('logo').files[0];
        
        if (!cashElectronique && !logo) {
            display_sweet_alert_over_modal(PARAM_I18N.error, PARAM_I18N.selectImg, "warning", "btn btn-danger");
            return;
        }

        // Vérification de la taille des fichiers (5MB max)
        const maxSize = 5 * 1024 * 1024; // 5MB en bytes
        if (cashElectronique && cashElectronique.size > maxSize) {
            display_sweet_alert_over_modal(PARAM_I18N.error, PARAM_I18N.cashTooBig, "warning", "btn btn-danger");
            return;
        }

        if (logo && logo.size > maxSize) {
            display_sweet_alert_over_modal(PARAM_I18N.error, PARAM_I18N.logoTooBig, "warning", "btn btn-danger");
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
                $("#valider").html('<i class="spinner-border spinner-border-sm"></i> ' + PARAM_I18N.saving);
            },
            success: function(data) {
                $("#valider").prop("disabled", false);
                $("#valider").html(PARAM_I18N.saveBtn);

                if (data.error) {
                    printErrorMsg(data.error);
                    display_sweet_alert_over_modal(PARAM_I18N.valError, data.message || PARAM_I18N.fixErrors, "warning", "btn btn-danger");
                } else if (data.status) {
                    display_sweet_alert_over_modal(PARAM_I18N.success, data.message, "success", "btn btn-primary");
                    setTimeout(function() {
                        window.location.reload();
                    }, 3000);
                } else {
                    display_sweet_alert_over_modal(PARAM_I18N.error, data.message || PARAM_I18N.genError, "warning", "btn btn-danger");
                }
            },
            error: function(xhr) {
                $("#valider").prop("disabled", false);
                $("#valider").html(PARAM_I18N.saveBtn);

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    display_sweet_alert_over_modal(PARAM_I18N.error, xhr.responseJSON.message, "warning", "btn btn-danger");
                } else {
                    display_sweet_alert_over_modal(PARAM_I18N.error, PARAM_I18N.netError, "warning", "btn btn-danger");
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
                $("#ajouterAnnexe button#valider").html('<i class="spinner-border spinner-border-sm"></i> ' + PARAM_I18N.saving);
            },
            success: function(data) {
                $("#ajouterAnnexe button#close").prop("disabled", false);
                $("#ajouterAnnexe button#valider").prop("disabled", false);
                $("#ajouterAnnexe button#valider").html(PARAM_I18N.save2);

                if (data.error) {
                    printErrorMsg(data.error);
                    display_sweet_alert_over_modal(PARAM_I18N.valError, data.message || PARAM_I18N.fixErrors, "warning", "btn btn-danger");
                } else if (data.status) {
                    display_sweet_alert_over_modal(PARAM_I18N.success, data.message, "success", "btn btn-primary");

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
                    display_sweet_alert_over_modal(PARAM_I18N.error, data.message, "warning", "btn btn-danger");
                }
            },
            error: function(xhr) {
                $("#ajouterAnnexe button#close").prop("disabled", false);
                $("#ajouterAnnexe button#valider").prop("disabled", false);
                $("#ajouterAnnexe button#valider").html(PARAM_I18N.save2);
                display_sweet_alert_over_modal(PARAM_I18N.error, PARAM_I18N.genError, "warning", "btn btn-danger");
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
            display_sweet_alert_over_modal(PARAM_I18N.error, PARAM_I18N.invalidPhone, "warning", "btn btn-danger");
            return null;
        }

        const formattedPhone = iti.getNumber(); // format E.164
        const selectedCountry = iti.getSelectedCountryData();
        const cleanedPhone = formattedPhone.replace(/\s+/g, "");

        // Règle spécifique au Bénin
        if (selectedCountry.iso2 === "bj") {
            if (cleanedPhone.length !== 14) {
                display_sweet_alert_over_modal(PARAM_I18N.error, PARAM_I18N.beninPhone, "warning", "btn btn-danger");
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
                $("#btnSavePourcentageGeneral").html('<i class="spinner-border spinner-border-sm"></i> ' + PARAM_I18N.saving);
            },
            success: function(data) {
                $("#btnSavePourcentageGeneral").prop("disabled", false);
                $("#btnSavePourcentageGeneral").html(PARAM_I18N.savePct);

                if (data.error) {
                    printErrorMsg(data.error);
                    display_sweet_alert_over_modal(PARAM_I18N.error, data.message || PARAM_I18N.fixErrors, "warning", "btn btn-danger");
                } else if (data.status) {
                    display_sweet_alert_over_modal(PARAM_I18N.success, data.message, "success", "btn btn-primary");
                    setTimeout(function() { window.location.reload(); }, 2000);
                } else {
                    display_sweet_alert_over_modal(PARAM_I18N.error, data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                $("#btnSavePourcentageGeneral").prop("disabled", false);
                $("#btnSavePourcentageGeneral").html(PARAM_I18N.savePct);
                display_sweet_alert_over_modal(PARAM_I18N.error, PARAM_I18N.genError, "warning", "btn btn-danger");
            }
        });
    }

    function toggle_pourcentage(id) {
        if (id === 0) {
            display_sweet_alert_over_modal(PARAM_I18N.info, PARAM_I18N.pctFirst, "info", "btn btn-primary");
            return;
        }
        $.ajax({
            url: "{{ route('toggle_pourcentage') }}",
            method: "POST",
            data: { _token: '{{ csrf_token() }}', id: id },
            success: function(data) {
                if (data.status) {
                    display_sweet_alert_over_modal(PARAM_I18N.success, data.message, "success", "btn btn-primary");
                    setTimeout(function() { window.location.reload(); }, 1500);
                } else {
                    display_sweet_alert_over_modal(PARAM_I18N.error, data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                display_sweet_alert_over_modal(PARAM_I18N.error, PARAM_I18N.genError, "warning", "btn btn-danger");
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
                $("#btnSaveGroupe").html('<i class="spinner-border spinner-border-sm"></i> ' + PARAM_I18N.saving);
            },
            success: function(data) {
                $("#btnSaveGroupe").prop("disabled", false);
                $("#btnSaveGroupe").html(PARAM_I18N.savePct);

                if (data.error) {
                    printErrorMsg(data.error);
                    display_sweet_alert_over_modal(PARAM_I18N.error, data.message || PARAM_I18N.fixErrors, "warning", "btn btn-danger");
                } else if (data.status) {
                    display_sweet_alert_over_modal(PARAM_I18N.success, data.message, "success", "btn btn-primary");
                    setTimeout(function() { window.location.reload(); }, 2000);
                } else {
                    display_sweet_alert_over_modal(PARAM_I18N.error, data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                $("#btnSaveGroupe").prop("disabled", false);
                $("#btnSaveGroupe").html(PARAM_I18N.savePct);
                display_sweet_alert_over_modal(PARAM_I18N.error, PARAM_I18N.genError, "warning", "btn btn-danger");
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
                $("#btnUpdateGroupe" + id).html('<i class="spinner-border spinner-border-sm"></i> ' + PARAM_I18N.updating);
            },
            success: function(data) {
                $("#btnUpdateGroupe" + id).prop("disabled", false);
                $("#btnUpdateGroupe" + id).html(PARAM_I18N.saveUpdate);

                if (data.error) {
                    printErrorMsg(data.error);
                    display_sweet_alert_over_modal(PARAM_I18N.error, data.message || PARAM_I18N.fixErrors, "warning", "btn btn-danger");
                } else if (data.status) {
                    display_sweet_alert_over_modal(PARAM_I18N.success, data.message, "success", "btn btn-primary");
                    setTimeout(function() { window.location.reload(); }, 2000);
                } else {
                    display_sweet_alert_over_modal(PARAM_I18N.error, data.message, "warning", "btn btn-danger");
                }
            },
            error: function() {
                $("#btnUpdateGroupe" + id).prop("disabled", false);
                $("#btnUpdateGroupe" + id).html(PARAM_I18N.saveUpdate);
                display_sweet_alert_over_modal(PARAM_I18N.error, PARAM_I18N.genError, "warning", "btn btn-danger");
            }
        });
    }

    function delete_groupe(id) {
        Swal.fire({
            title: PARAM_I18N.delGrpTitle,
            text: PARAM_I18N.delGrpText,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: PARAM_I18N.delYes,
            cancelButtonText: PARAM_I18N.cancel
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('destroy_pourcentage_groupe') }}",
                    method: "POST",
                    data: { _token: '{{ csrf_token() }}', id: id },
                    success: function(data) {
                        if (data.status) {
                            display_sweet_alert_over_modal(PARAM_I18N.success, data.message, "success", "btn btn-primary");
                            setTimeout(function() { window.location.reload(); }, 1500);
                        } else {
                            display_sweet_alert_over_modal(PARAM_I18N.error, data.message, "warning", "btn btn-danger");
                        }
                    },
                    error: function() {
                        display_sweet_alert_over_modal(PARAM_I18N.error, PARAM_I18N.genError, "warning", "btn btn-danger");
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
                        ph.textContent = PARAM_I18N.msPlaceholder;
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
                            emptyMsg.textContent = PARAM_I18N.msNoResults;
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
        if (!articleModal) { alert(PARAM_I18N.waitPage); return; }
        var isEdit = (typeof index === 'number');
        document.getElementById('modalArticleTitle').textContent = isEdit ? PARAM_I18N.articleEdit : PARAM_I18N.articleAdd;
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
                Swal.fire({ icon: 'success', title: PARAM_I18N.success, text: successMsg || data.message, timer: 2000, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: PARAM_I18N.error, text: data.message || PARAM_I18N.saveFail });
            }
        })
        .catch(function() {
            Swal.fire({ icon: 'error', title: PARAM_I18N.netErrTitle, text: PARAM_I18N.serverError });
        })
        .finally(function() {
            if (btnEl) { btnEl.disabled = false; btnEl.innerHTML = PARAM_I18N.btnSaveTpl; }
        });
    }

    // ── Insertion de variable dans le textarea du modal ──────────────
    window.insertVariableInArticle = function(variable) {
        var ta = document.getElementById('article-contenu');
        if (!ta) return;
        var start = ta.selectionStart;
        var end   = ta.selectionEnd;
        var val   = ta.value;
        ta.value  = val.substring(0, start) + variable + val.substring(end);
        ta.selectionStart = ta.selectionEnd = start + variable.length;
        ta.focus();
    };

    // ── Ajout / Modification depuis le modal ─────────────────────────
    window.contratSaveArticle = function() {
        var titreEl = document.getElementById('article-titre');
        var contEl  = document.getElementById('article-contenu');
        var idxEl   = document.getElementById('article-edit-index');
        var titre   = titreEl.value.trim();
        var contenu = contEl.value.trim();
        var ok = true;
        if (!titre)   { titreEl.classList.add('is-invalid'); document.getElementById('err-article-titre').textContent = PARAM_I18N.titleReq; ok = false; }
        else          { titreEl.classList.remove('is-invalid'); }
        if (!contenu) { contEl.classList.add('is-invalid');   document.getElementById('err-article-contenu').textContent = PARAM_I18N.contentReq; ok = false; }
        else          { contEl.classList.remove('is-invalid'); }
        if (!ok) return;

        var editIdx = idxEl.value;
        var isEdit  = (editIdx !== '');
        if (isEdit) { window.contratArticles[parseInt(editIdx, 10)] = { titre: titre, contenu: contenu }; }
        else        { window.contratArticles.push({ titre: titre, contenu: contenu }); }

        renderArticlesList();
        if (articleModal) articleModal.hide();
        saveArticlesToServer(isEdit ? PARAM_I18N.articleEdited : PARAM_I18N.articleAdded);
    };

    // ── Suppression avec confirmation SweetAlert + sauvegarde auto ───
    window.contratDeleteArticle = function(index) {
        var article = window.contratArticles[index];
        Swal.fire({
            title: PARAM_I18N.delArtTitle,
            html: article ? '<strong>' + article.titre + '</strong><br><small class="text-muted">' + PARAM_I18N.delArtSaved + '</small>' : '',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: PARAM_I18N.delBtn,
            cancelButtonText: PARAM_I18N.cancel,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
        }).then(function(result) {
            if (result.isConfirmed) {
                window.contratArticles.splice(index, 1);
                renderArticlesList();
                saveArticlesToServer(PARAM_I18N.articleDel);
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
                title: PARAM_I18N.resetTitle,
                html: PARAM_I18N.resetHtml,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: PARAM_I18N.resetBtn,
                cancelButtonText: PARAM_I18N.cancel,
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
                Swal.fire({ icon: 'warning', title: PARAM_I18N.warning, text: PARAM_I18N.noArticles });
                return;
            }
            var btn = formContrat.querySelector('[type="submit"]');
            saveArticlesToServer(PARAM_I18N.tplSaved, btn);
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
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>' + PARAM_I18N.commSaving;
        try {
            const res  = await fetch('{{ route("store_comm_config") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: data,
            });
            const json = await res.json();
            if (json.status) {
                Swal.fire({ icon: 'success', title: PARAM_I18N.success, text: json.message, timer: 2500, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: PARAM_I18N.error, text: json.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: PARAM_I18N.netErrTitle, text: err.message });
        } finally {
            btn.disabled = false;
            btn.innerHTML = PARAM_I18N.btnSaveComm;
        }
    });
})();

// ---- Widget connexion WhatsApp (désactivé - AT API ne nécessite pas de QR) ----
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
                    if (sub) sub.textContent = '{{ __("pages.param_wa_starting") }}';
                    WA._waitAndConnect(0);
                } else if (data.status) {
                    WA.setUI('waiting_qr');
                    if (btnCon) { btnCon.disabled = false; btnCon.innerHTML = '<i class="bx bx-qr-scan me-1"></i>Connecter'; }
                    setTimeout(function() { WA.openQrModal(); }, 1000);
                } else {
                    Swal.fire({ icon: 'error', title: '{{ __("common.swal_error") }}', text: data.message });
                    if (btnCon) { btnCon.disabled = false; btnCon.innerHTML = '{{ __("pages.param_wa_btn_connect") }}'; }
                }
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: '{{ __("common.swal_error") }}', text: '{{ __("common.swal_cannot_reach") }}' });
                if (btnCon) { btnCon.disabled = false; btnCon.innerHTML = '{{ __("pages.param_wa_btn_connect") }}'; }
            });
        },

        // Attend que le service soit disponible (max 120s) puis relance connect
        _waitAndConnect: function(attempt) {
            var maxAttempts = 40; // 40 × 3s = 120s
            var btnCon = document.getElementById('btnConnectWA');
            var sub    = document.getElementById('wa-status-sub');

            if (attempt >= maxAttempts) {
                if (btnCon) { btnCon.disabled = false; btnCon.innerHTML = '{{ __("pages.param_wa_btn_connect") }}'; }
                if (sub) sub.textContent = '{{ __("pages.param_wa_not_started") }}';
                Swal.fire({
                    icon: 'warning',
                    title: '{{ __("pages.param_wa_slow_title") }}',
                    text: '{{ __("pages.param_wa_slow_text") }}',
                    confirmButtonText: '{{ __("common.swal_ok") }}'
                });
                return;
            }

            var remaining = Math.round((maxAttempts - attempt) * 3);
            if (btnCon) btnCon.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Démarrage (' + remaining + 's)…';
            if (sub) sub.textContent = '{{ __("pages.param_wa_starting_wait") }}';

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

<script>
// ══════════════════════════════════════════════════════
// ONGLET DEVISE & RÉGION
// ══════════════════════════════════════════════════════
(function() {
    var URL_DEVISE = '{{ route("store_devise_config") }}';
    var CSRF       = '{{ csrf_token() }}';

    var PAYS_DATA   = @json($paysList);
    var DEVISE_DATA = @json($devisesList);

    var DEVISE_CONFIGS = {
        'XOF': { decimales: 0, sep_decimal: ',', sep_milliers: ' ', symbole: 'FCFA', avant: false },
        'XAF': { decimales: 0, sep_decimal: ',', sep_milliers: ' ', symbole: 'FCFA', avant: false },
        'GHS': { decimales: 2, sep_decimal: '.', sep_milliers: ',', symbole: 'GH₵',  avant: true  },
        'NGN': { decimales: 2, sep_decimal: '.', sep_milliers: ',', symbole: '₦',    avant: true  },
        'EUR': { decimales: 2, sep_decimal: ',', sep_milliers: ' ', symbole: '€',    avant: false },
        'USD': { decimales: 2, sep_decimal: '.', sep_milliers: ',', symbole: '$',    avant: true  },
    };

    function formatSample(code) {
        var cfg = DEVISE_CONFIGS[code];
        if (!cfg) return '—';
        var n = 150000;
        var parts = n.toFixed(cfg.decimales).split('.');
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, cfg.sep_milliers);
        var formatted = parts.join(cfg.sep_decimal);
        return cfg.avant ? cfg.symbole + ' ' + formatted : formatted + ' ' + cfg.symbole;
    }

    function updateApercu(code) {
        var el = document.getElementById('devise-apercu');
        if (el) el.textContent = formatSample(code || document.getElementById('devise-devise').value);
    }

    window.onPaysChange = function(code) {
        var pays = PAYS_DATA[code];
        if (!pays) return;
        document.getElementById('devise-devise').value      = pays.devise;
        document.getElementById('devise-indicatif').value   = pays.indicatif;
        document.getElementById('devise-format-date').value = pays.format_date;
        updateApercu(pays.devise);
        updateTauxLabels(pays.devise);
    };

    window.onDeviseChange = function(code) {
        updateApercu(code);
        updateTauxLabels(code);
    };

    function updateTauxLabels(deviseBase) {
        document.querySelectorAll('#taux-change-container .taux-row').forEach(function(row) {
            var codeEtr = row.dataset.devise;
            if (codeEtr === deviseBase) {
                row.style.display = 'none';
            } else {
                row.style.display = '';
                var lbl = row.querySelector('label');
                if (lbl) lbl.innerHTML = '1 ' + codeEtr + ' = <span class="text-primary">X ' + deviseBase + '</span>';
                var span = row.querySelector('.input-group-text');
                if (span) span.textContent = deviseBase;
            }
        });
        var baseLabel = document.getElementById('devise-base-label');
        if (baseLabel) baseLabel.textContent = deviseBase;
    }

    window.saveDeviseConfig = function() {
        var btn = document.getElementById('btnSaveDevise');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Enregistrement...';

        var fd = new FormData(document.getElementById('formDeviseConfig'));
        // Checkbox
        var tvaCheck = document.getElementById('tva-applicable');
        if (tvaCheck && !tvaCheck.checked) {
            fd.set('tva_applicable', '0');
        }

        fetch(URL_DEVISE, {
            method: 'POST',
            body: fd,
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            btn.disabled = false;
            btn.innerHTML = '{{ __("pages.param_js_btn_save_comm") }}';
            if (data.status) {
                Swal.fire({ icon: 'success', title: '{{ __("common.swal_success") }}', text: data.message, timer: 2500, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: '{{ __("common.swal_error") }}', text: data.message });
            }
        })
        .catch(function() {
            btn.disabled = false;
            btn.innerHTML = '{{ __("pages.param_js_btn_save_comm") }}';
            Swal.fire({ icon: 'error', title: '{{ __("common.swal_error") }}', text: '{{ __("common.swal_unexpected_error") }}' });
        });
    };

    // Init aperçu au chargement
    document.addEventListener('DOMContentLoaded', function() {
        var deviseEl = document.getElementById('devise-devise');
        if (deviseEl) updateApercu(deviseEl.value);
    });
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
                <h5 class="modal-title" id="modalArticleTitle">{{ __('pages.param_article_add_btn') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="article-edit-index" value="">
                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('pages.param_art_modal_title_lbl') }} <span class="text-danger">*</span></label>
                    <input type="text" id="article-titre" class="form-control" placeholder="{{ __('pages.param_art_modal_title_ph') }}">
                    <div class="invalid-feedback" id="err-article-titre"></div>
                </div>

                {{-- Variables disponibles dans le modal --}}
                <div class="mb-3">
                    <div class="accordion accordion-flush" id="accordionVariablesModal">
                        <div class="accordion-item border rounded">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed py-2 rounded" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVariablesModal">
                                    <i class="bx bx-code-alt me-2 text-primary"></i>
                                    <strong class="small">{{ __('pages.param_art_modal_vars') }}</strong>
                                    <span class="ms-2 text-muted" style="font-size:12px;">{{ __('pages.param_art_modal_vars_click') }}</span>
                                </button>
                            </h2>
                            <div id="collapseVariablesModal" class="accordion-collapse collapse">
                                <div class="accordion-body py-2 px-2">
                                    @php
                                    $varsModal = [
                                        ['{nom_agence}',__('pages.param_var_agency_name')],['{adresse_agence}',__('pages.param_var_agency_address')],['{telephone_agence}',__('pages.param_var_agency_phone')],
                                        ['{nom_locataire}',__('pages.param_var_tenant_name')],['{telephone_locataire}',__('pages.param_var_tenant_phone')],['{profession_locataire}',__('pages.param_var_tenant_job')],['{adresse_locataire}',__('pages.param_var_tenant_address')],
                                        ['{nom_maison}',__('pages.param_var_house_name')],['{quartier_maison}',__('pages.param_var_house_district')],['{type_chambre}',__('pages.param_var_room_type')],['{numero_chambre}',__('pages.param_var_room_number')],
                                        ['{montant_loyer}',__('pages.param_var_rent')],['{nombre_caution}',__('pages.param_var_caution_months')],['{montant_caution}',__('pages.param_var_caution_amount')],['{caution_courant}',__('pages.param_var_caution_elec')],['{caution_eau}',__('pages.param_var_caution_water')],
                                        ['{nombre_avance}',__('pages.param_var_advance_months')],['{montant_avance}',__('pages.param_var_advance_amount')],['{mode_paiement}',__('pages.param_var_payment_mode')],
                                        ['{date_entree}',__('pages.param_var_entry_date')],['{date_contrat}',__('pages.param_var_contract_date')],
                                    ];
                                    @endphp
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($varsModal as $v)
                                        <button type="button"
                                            class="btn btn-sm btn-outline-primary py-0 px-2"
                                            style="font-size:11px;font-family:monospace;"
                                            title="{{ $v[1] }}"
                                            onclick="insertVariableInArticle('{{ $v[0] }}')">{{ $v[0] }}</button>
                                        @endforeach
                                    </div>
                                    <p class="small text-muted mt-2 mb-0"><i class="bx bx-mouse-alt me-1"></i>{{ __('pages.param_var_insert_hint') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">{{ __('pages.param_art_modal_content') }} <span class="text-danger">*</span></label>
                    <textarea id="article-contenu" class="form-control" rows="8"
                        placeholder="{{ __('pages.param_art_modal_content_ph') }}"></textarea>
                    <div class="invalid-feedback" id="err-article-contenu"></div>
                    <small class="text-muted">{{ __('pages.param_art_modal_hint') }}</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('pages.param_btn_cancel') }}</button>
                <button type="button" class="btn btn-primary" onclick="contratSaveArticle()">
                    <i class="bx bx-save me-1"></i>{{ __('pages.param_btn_save') }}
                </button>
            </div>
        </div>
    </div>
</div>

@endsection