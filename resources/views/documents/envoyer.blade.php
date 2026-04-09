@extends('layouts.template')

@section('content')
@section('title')
    <title>{{ __('pages.env_title') }}</title>
@endsection

<style>
    .swal2-container { z-index: 10070 !important; }
    .swal2-popup    { z-index: 10071 !important; }
    .search-input   { max-width: 300px; }
    /* KKiaPay : forcer l'affichage au-dessus des modals Bootstrap */
    iframe[src*="kkiapay"],
    [id*="kkiapay"],
    [class*="kkiapay"],
    div[style*="z-index: 999"] { z-index: 99999 !important; }

    /* ── Nav-tabs destinataires ─────────── */
    #tabDest {
        background: #f1f1f4;
        border-radius: 12px;
        padding: 5px;
        display: inline-flex;
        gap: 4px;
        border: none;
    }
    #tabDest .nav-link {
        border: none;
        border-radius: 9px;
        color: #8592a3;
        font-weight: 500;
        padding: 8px 20px;
        transition: all .22s ease;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    #tabDest .nav-link:hover:not(.active) {
        background: rgba(105,108,255,.1);
        color: #696cff;
    }

    /* Onglet Locataires actif */
    #tab-loc.active {
        background: #696cff;
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(105,108,255,.40);
    }
    #tab-loc.active .badge {
        background: rgba(255,255,255,.25) !important;
        color: #fff !important;
    }

    /* Onglet Propriétaires actif */
    #tab-proprio.active {
        background: #20c997;
        color: #fff !important;
        box-shadow: 0 4px 14px rgba(32,201,151,.40);
    }
    #tab-proprio.active .badge {
        background: rgba(255,255,255,.25) !important;
        color: #fff !important;
    }

    /* Badge inactif */
    #tabDest .nav-link:not(.active) .badge {
        background: #d9dde1 !important;
        color: #697a8d !important;
    }

    /* Liste destinataires dans le modal */
    #listeDestinataires {
        max-height: 280px;
        overflow-y: auto;
    }
    .dest-row {
        border-bottom: 1px solid #e9ecef;
        padding: 8px 0;
    }
    .dest-row:last-child {
        border-bottom: none;
    }

    /* ── Tableaux scrollables ── */
    .table-scroll-wrapper {
        max-height: 420px;
        overflow-y: auto;
        border: 1px solid #e9ecef;
        border-radius: 8px;
    }
    .table-scroll-wrapper table {
        margin-bottom: 0;
    }
    .table-scroll-wrapper thead th {
        position: sticky;
        top: 0;
        z-index: 2;
        background: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        white-space: nowrap;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">{{ __('common.home_breadcrumb') }} /</span> {{ __('pages.env_breadcrumb') }}
    </h4>

    @include('notification.display_message')

    {{-- Alerte si aucune config communication --}}
    @if (!$parametre || !$parametre->email_envoi)
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="bx bx-error-circle me-2 fs-5"></i>
            <div>
                {{ __('pages.env_alert_no_email') }}
                <a href="{{ route('parametrage') }}" class="alert-link">{{ __('pages.env_alert_configure') }}</a>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between pb-0 flex-wrap gap-2">
            <div class="card-title mb-0">
                <h5 class="m-0 me-2"><i class="bx bx-message-dots me-1"></i>{{ __('pages.env_section_send') }}</h5>
                <small class="text-muted">{{ __('pages.env_section_send_sub') }}</small>
            </div>
            {{-- Barre d'action sélection multiple (cachée par défaut) --}}
            <div id="actionBar" class="d-none d-flex align-items-center gap-2">
                <span class="text-muted small" id="selectionLabel">0 {{ __('pages.env_action_selected') }}</span>
                <button class="btn btn-primary btn-sm" id="btnEnvoyerSelection">
                    <i class="bx bx-send me-1"></i>{{ __('pages.env_action_send_selection') }} (<span id="selectionCount">0</span>)
                </button>
            </div>
        </div>

        <div class="card-body">
            {{-- Onglets Locataires / Propriétaires --}}
            <ul class="nav nav-pills mb-4" id="tabDest" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-loc"
                            data-bs-toggle="tab" data-bs-target="#pane-loc"
                            type="button" role="tab">
                        <i class="bx bx-user-check"></i>
                        {{ __('pages.env_tab_tenants') }}
                        <span class="badge ms-1">{{ $locataires->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-proprio"
                            data-bs-toggle="tab" data-bs-target="#pane-proprio"
                            type="button" role="tab">
                        <i class="bx bx-building-house"></i>
                        {{ __('pages.env_tab_owners') }}
                        <span class="badge ms-1">{{ $proprietaires->count() }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                {{-- ===== ONGLET LOCATAIRES ===== --}}
                <div class="tab-pane fade show active" id="pane-loc">
                    <div class="mb-3">
                        <input type="text" id="searchLoc" class="form-control search-input"
                               placeholder="{{ __('pages.env_ph_search_tenant') }}">
                    </div>
                    <div class="table-scroll-wrapper">
                        <table class="table table-hover" id="tableLoc">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;">
                                        <input type="checkbox" id="checkAllLoc" class="form-check-input" title="{{ __('pages.env_select_all') }}">
                                    </th>
                                    <th>{{ __('pages.env_th_name') }}</th>
                                    <th>{{ __('pages.env_th_housing') }}</th>
                                    <th>{{ __('pages.env_th_phone') }}</th>
                                    <th>{{ __('pages.env_th_email') }}</th>
                                    <th>{{ __('pages.env_th_documents') }}</th>
                                    <th>{{ __('pages.env_th_action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($locataires as $loc)
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input check-dest"
                                                   data-type="locataire"
                                                   data-id="{{ $loc->id }}"
                                                   data-nom="{{ $loc->nom }} {{ $loc->prenom }}"
                                                   data-email="{{ $loc->email ?? '' }}"
                                                   data-tel="{{ $loc->telephone ?? '' }}"
                                                   data-factures='@json($facturesParLocataire[$loc->id] ?? [])'>
                                        </td>
                                        <td>{{ $loc->nom }} {{ $loc->prenom }}</td>
                                        <td>{{ $loc->nom_maison }} / {{ $loc->type_chambre }} N°{{ $loc->numero_chambre }}</td>
                                        <td>{{ $loc->telephone }}</td>
                                        <td>{{ $loc->email ?? '—' }}</td>
                                        <td>
                                            <span class="badge bg-label-primary me-1">{{ __('pages.env_badge_contract') }}</span>
                                            <span class="badge bg-label-info me-1">{{ __('pages.env_badge_deposit') }}</span>
                                            @if(isset($facturesParLocataire[$loc->id]) && $facturesParLocataire[$loc->id]->isNotEmpty())
                                                <span class="badge bg-label-success">
                                                    {{ $facturesParLocataire[$loc->id]->count() }} {{ __('pages.env_badge_receipts') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary btn-envoyer"
                                                    data-type="locataire"
                                                    data-id="{{ $loc->id }}"
                                                    data-nom="{{ $loc->nom }} {{ $loc->prenom }}"
                                                    data-tel="{{ $loc->telephone ?? '' }}"
                                                    data-email="{{ $loc->email ?? '' }}"
                                                    data-factures='@json($facturesParLocataire[$loc->id] ?? [])'>
                                                <i class="bx bx-send me-1"></i>{{ __('pages.env_btn_send') }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted">{{ __('pages.env_empty_no_tenants') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ===== ONGLET PROPRIÉTAIRES ===== --}}
                <div class="tab-pane fade" id="pane-proprio">
                    <div class="mb-3">
                        <input type="text" id="searchProprio" class="form-control search-input"
                               placeholder="{{ __('pages.env_ph_search_owner') }}">
                    </div>
                    <div class="table-scroll-wrapper">
                        <table class="table table-hover" id="tableProprio">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;">
                                        <input type="checkbox" id="checkAllProprio" class="form-check-input" title="{{ __('pages.env_select_all') }}">
                                    </th>
                                    <th>{{ __('pages.env_th_name') }}</th>
                                    <th>{{ __('pages.env_th_phone') }}</th>
                                    <th>{{ __('pages.env_th_email') }}</th>
                                    <th>{{ __('pages.env_th_documents') }}</th>
                                    <th>{{ __('pages.env_th_action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($proprietaires as $proprio)
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input check-dest"
                                                   data-type="proprietaire"
                                                   data-id="{{ $proprio->id }}"
                                                   data-nom="{{ $proprio->nom }} {{ $proprio->prenom }}"
                                                   data-email="{{ $proprio->email ?? '' }}"
                                                   data-tel="{{ $proprio->telephone ?? '' }}"
                                                   data-factures='[]'>
                                        </td>
                                        <td>{{ $proprio->nom }} {{ $proprio->prenom }}</td>
                                        <td>{{ $proprio->telephone }}</td>
                                        <td>{{ $proprio->email ?? '—' }}</td>
                                        <td>
                                            <span class="badge bg-label-warning me-1">{{ __('pages.env_badge_owner_stmt') }}</span>
                                            <span class="badge bg-label-secondary">{{ __('pages.env_badge_agency_stmt') }}</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-success btn-envoyer"
                                                    data-type="proprietaire"
                                                    data-id="{{ $proprio->id }}"
                                                    data-nom="{{ $proprio->nom }} {{ $proprio->prenom }}"
                                                    data-tel="{{ $proprio->telephone ?? '' }}"
                                                    data-email="{{ $proprio->email ?? '' }}"
                                                    data-factures='[]'>
                                                <i class="bx bx-send me-1"></i>{{ __('pages.env_btn_send') }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">{{ __('pages.env_empty_no_owners') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== SECTION NOTIFICATIONS LOCATAIRES ===== --}}
    <div class="card mt-4">
        <div class="card-header d-flex align-items-center justify-content-between pb-0 flex-wrap gap-2">
            <div class="card-title mb-0">
                <h5 class="m-0 me-2"><i class="bx bx-bell me-1"></i>{{ __('pages.env_section_notif') }}</h5>
                <small class="text-muted">{{ __('pages.env_section_notif_sub') }}</small>
            </div>
            <div id="actionBarNotif" class="d-none d-flex align-items-center gap-2">
                <span class="text-muted small" id="selectionLabelNotif">0 {{ __('pages.env_action_selected') }}</span>
                <button class="btn btn-warning btn-sm" id="btnEnvoyerRappelSelection">
                    <i class="bx bx-alarm me-1"></i>{{ __('pages.env_notif_action_reminder') }} (<span id="selectionCountNotif">0</span>)
                </button>
                <button class="btn btn-danger btn-sm" id="btnEnvoyerPreavisSelection">
                    <i class="bx bx-calendar-x me-1"></i>{{ __('pages.env_notif_action_notice') }} (<span id="selectionCountNotif2">0</span>)
                </button>
            </div>
        </div>

        <div class="card-body">
            {{-- Onglets Rappel / Préavis --}}
            <ul class="nav nav-pills mb-4" id="tabNotif" role="tablist"
                style="background:#f1f1f4;border-radius:12px;padding:5px;display:inline-flex;gap:4px;border:none;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-rappel"
                            data-bs-toggle="tab" data-bs-target="#pane-rappel"
                            type="button" role="tab"
                            style="border:none;border-radius:9px;font-weight:500;padding:8px 20px;transition:all .22s ease;">
                        <i class="bx bx-alarm me-1"></i>{{ __('pages.env_notif_tab_reminder') }}
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-preavis"
                            data-bs-toggle="tab" data-bs-target="#pane-preavis"
                            type="button" role="tab"
                            style="border:none;border-radius:9px;font-weight:500;padding:8px 20px;transition:all .22s ease;">
                        <i class="bx bx-calendar-x me-1"></i>{{ __('pages.env_notif_tab_notice') }}
                    </button>
                </li>
            </ul>

            <style>
                #tab-rappel {
                    background: rgba(245,158,11,.55);
                    color: #fff !important;
                }
                #tab-rappel.active {
                    background: #f59e0b;
                    color: #fff !important;
                    box-shadow: 0 4px 14px rgba(245,158,11,.45);
                }
                #tab-rappel:hover:not(.active) {
                    background: rgba(245,158,11,.75);
                    color: #fff !important;
                }
                #tab-preavis {
                    background: rgba(239,68,68,.55);
                    color: #fff !important;
                }
                #tab-preavis.active {
                    background: #ef4444;
                    color: #fff !important;
                    box-shadow: 0 4px 14px rgba(239,68,68,.45);
                }
                #tab-preavis:hover:not(.active) {
                    background: rgba(239,68,68,.75);
                    color: #fff !important;
                }
            </style>

            <div class="tab-content">
                {{-- ===== ONGLET RAPPEL DE LOYER ===== --}}
                <div class="tab-pane fade show active" id="pane-rappel">
                    <div class="mb-3">
                        <input type="text" id="searchRappel" class="form-control search-input"
                               placeholder="{{ __('pages.env_ph_search_tenant') }}">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="tableRappel">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;">
                                        <input type="checkbox" id="checkAllRappel" class="form-check-input" title="{{ __('pages.env_select_all') }}">
                                    </th>
                                    <th>{{ __('pages.env_th_name') }}</th>
                                    <th>{{ __('pages.env_th_housing_short') }}</th>
                                    <th>{{ __('pages.env_th_monthly_rent') }}</th>
                                    <th>{{ __('pages.env_th_phone') }}</th>
                                    <th>{{ __('pages.env_th_email') }}</th>
                                    <th>{{ __('pages.env_th_action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($locataires as $loc)
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input check-notif"
                                                   data-id="{{ $loc->id }}"
                                                   data-nom="{{ $loc->nom }} {{ $loc->prenom }}"
                                                   data-email="{{ $loc->email ?? '' }}"
                                                   data-tel="{{ $loc->telephone ?? '' }}"
                                                   data-prix="{{ $loc->prix_mois ?? 0 }}"
                                                   data-logement="{{ $loc->nom_maison }} / {{ $loc->type_chambre }} N°{{ $loc->numero_chambre }}">
                                        </td>
                                        <td>{{ $loc->nom }} {{ $loc->prenom }}</td>
                                        <td>{{ $loc->nom_maison }} / {{ $loc->type_chambre }} N°{{ $loc->numero_chambre }}</td>
                                        <td>
                                            <span class="badge bg-label-warning">
                                                {{ number_format($loc->prix_mois ?? 0, 0, ',', ' ') }} XOF
                                            </span>
                                        </td>
                                        <td>{{ $loc->telephone }}</td>
                                        <td>{{ $loc->email ?? '—' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-warning btn-notif-rappel"
                                                    data-id="{{ $loc->id }}"
                                                    data-nom="{{ $loc->nom }} {{ $loc->prenom }}"
                                                    data-tel="{{ $loc->telephone ?? '' }}"
                                                    data-email="{{ $loc->email ?? '' }}"
                                                    data-prix="{{ $loc->prix_mois ?? 0 }}"
                                                    data-logement="{{ $loc->nom_maison }} / {{ $loc->type_chambre }} N°{{ $loc->numero_chambre }}">
                                                <i class="bx bx-alarm me-1"></i>{{ __('pages.env_btn_reminder') }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted">{{ __('pages.env_empty_no_tenants') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ===== ONGLET PRÉAVIS ===== --}}
                <div class="tab-pane fade" id="pane-preavis">
                    <div class="mb-3">
                        <input type="text" id="searchPreavis" class="form-control search-input"
                               placeholder="{{ __('pages.env_ph_search_tenant') }}">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablePreavis">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;">
                                        <input type="checkbox" id="checkAllPreavis" class="form-check-input" title="{{ __('pages.env_select_all') }}">
                                    </th>
                                    <th>{{ __('pages.env_th_name') }}</th>
                                    <th>{{ __('pages.env_th_housing_short') }}</th>
                                    <th>{{ __('pages.env_th_entry_date') }}</th>
                                    <th>{{ __('pages.env_th_phone') }}</th>
                                    <th>{{ __('pages.env_th_email') }}</th>
                                    <th>{{ __('pages.env_th_action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($locataires as $loc)
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                   class="form-check-input check-preavis"
                                                   data-id="{{ $loc->id }}"
                                                   data-nom="{{ $loc->nom }} {{ $loc->prenom }}"
                                                   data-email="{{ $loc->email ?? '' }}"
                                                   data-tel="{{ $loc->telephone ?? '' }}"
                                                   data-prix="{{ $loc->prix_mois ?? 0 }}"
                                                   data-logement="{{ $loc->nom_maison }} / {{ $loc->type_chambre }} N°{{ $loc->numero_chambre }}">
                                        </td>
                                        <td>{{ $loc->nom }} {{ $loc->prenom }}</td>
                                        <td>{{ $loc->nom_maison }} / {{ $loc->type_chambre }} N°{{ $loc->numero_chambre }}</td>
                                        <td>{{ $loc->date_entree ? \Carbon\Carbon::parse($loc->date_entree)->format('d/m/Y') : '—' }}</td>
                                        <td>{{ $loc->telephone }}</td>
                                        <td>{{ $loc->email ?? '—' }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger btn-notif-preavis"
                                                    data-id="{{ $loc->id }}"
                                                    data-nom="{{ $loc->nom }} {{ $loc->prenom }}"
                                                    data-tel="{{ $loc->telephone ?? '' }}"
                                                    data-email="{{ $loc->email ?? '' }}"
                                                    data-prix="{{ $loc->prix_mois ?? 0 }}"
                                                    data-logement="{{ $loc->nom_maison }} / {{ $loc->type_chambre }} N°{{ $loc->numero_chambre }}">
                                                <i class="bx bx-calendar-x me-1"></i>{{ __('pages.env_btn_notice') }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted">{{ __('pages.env_empty_no_tenants') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Historique --}}
    <div class="card mt-4">
        <div class="card-header">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <h5 class="mb-0"><i class="bx bx-history me-1"></i>{{ __('pages.env_section_history') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small" id="histoInfoCount"></span>
                    <button class="btn btn-sm btn-outline-secondary" id="btnRefreshHistorique" title="{{ __('pages.env_btn_refresh') }}">
                        <i class="bx bx-refresh"></i>
                    </button>
                </div>
            </div>
            {{-- Filtres --}}
            <div class="row g-2">
                <div class="col-12 col-md-4">
                    <input type="text" id="histoFiltreNom" class="form-control form-control-sm"
                           placeholder="{{ __('pages.env_ph_search_history') }}">
                </div>
                <div class="col-6 col-md-2">
                    <select id="histoFiltreDoc" class="form-select form-select-sm">
                        <option value="">{{ __('pages.env_filter_all_docs') }}</option>
                        <option value="contrat">{{ __('pages.env_filter_contract') }}</option>
                        <option value="quittance_mensuelle">{{ __('pages.env_filter_monthly') }}</option>
                        <option value="quittance_caution">{{ __('pages.env_filter_deposit') }}</option>
                        <option value="releve_proprietaire">{{ __('pages.env_filter_owner_stmt') }}</option>
                        <option value="releve_agence">{{ __('pages.env_filter_agency_stmt') }}</option>
                        <option value="rappel_loyer">{{ __('pages.env_filter_reminder') }}</option>
                        <option value="preavis">{{ __('pages.env_filter_notice') }}</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select id="histoFiltreMethode" class="form-select form-select-sm">
                        <option value="">{{ __('pages.env_filter_all_methods') }}</option>
                        <option value="email">{{ __('pages.env_method_email') }}</option>
                        <option value="whatsapp">{{ __('pages.env_method_whatsapp') }}</option>
                        <option value="sms">{{ __('pages.env_method_sms') }}</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select id="histoFiltreStatut" class="form-select form-select-sm">
                        <option value="">{{ __('pages.env_filter_all_statuses') }}</option>
                        <option value="success">{{ __('pages.env_filter_success') }}</option>
                        <option value="error">{{ __('pages.env_filter_failure') }}</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <button class="btn btn-sm btn-outline-secondary w-100" id="histoBtnReset">
                        <i class="bx bx-x me-1"></i>{{ __('pages.env_btn_reset') }}
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0" id="tableHistorique">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('pages.env_th_date') }}</th>
                            <th>{{ __('pages.env_th_recipient') }}</th>
                            <th>{{ __('pages.env_th_document') }}</th>
                            <th>{{ __('pages.env_th_method') }}</th>
                            <th>{{ __('pages.env_th_contact') }}</th>
                            <th>{{ __('pages.env_th_status') }}</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyHistorique">
                        <tr><td colspan="6" class="text-center text-muted py-3">{{ __('pages.env_loading') }}</td></tr>
                    </tbody>
                </table>
            </div>
            {{-- Pagination --}}
            <div class="d-flex align-items-center justify-content-between px-3 py-2 border-top"
                 id="histoPagination" style="display:none!important;">
                <span class="text-muted small" id="histoPaginationInfo"></span>
                <div class="d-flex gap-1 flex-wrap" id="histoPaginationBtns"></div>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL ENVOI ===== --}}
<div class="modal fade" id="modalEnvoiDocument" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-send me-1"></i>{{ __('pages.env_modal_send_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Section 1 — Méthode d'envoi --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">{{ __('pages.env_label_send_method') }}</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="methode_envoi"
                                   id="methodeEmail" value="email"
                                   {{ (!$parametre || !$parametre->email_envoi) ? 'disabled' : '' }} checked>
                            <label class="form-check-label" for="methodeEmail">
                                <i class="bx bx-envelope me-1"></i>{{ __('pages.env_method_email') }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="methode_envoi"
                                   id="methodeWhatsApp" value="whatsapp"
                                   {{ $waConnecte ? '' : 'disabled' }}>
                            <label class="form-check-label" for="methodeWhatsApp">
                                <i class="bx bxl-whatsapp me-1 text-success"></i>{{ __('pages.env_method_whatsapp') }}
                                <span id="wa-envoi-badge" class="badge ms-1 {{ $waConnecte ? 'bg-success' : 'bg-danger' }}" style="font-size:.7rem;">{{ $waConnecte ? __('pages.env_configured') : __('pages.env_not_configured') }}</span>
                            </label>
                        </div>
                    </div>
                    {{-- Info expéditeur --}}
                    <div id="infoExpediteurEmail" class="mt-2" style="font-size:.85rem;">
                        <i class="bx bx-envelope text-primary me-1"></i>
                        {{ __('pages.env_sender_prefix') }} <strong>{{ $parametre?->email_envoi ?: __('pages.env_sender_not_configured') }}</strong>
                    </div>
                    <div id="infoExpediteurWA" class="mt-2 d-none" style="font-size:.85rem;">
                        <i class="bx bxl-whatsapp text-success me-1"></i>
                        {{ __('pages.env_sender_at_prefix') }} <strong>{{ $parametre?->at_sender_id ?: ($parametre?->at_username ?: __('pages.env_sender_not_configured')) }}</strong>
                    </div>

                    {{-- Paiement requis pour WhatsApp (envoi documents) --}}
                    <div id="paymentRequiredDoc" class="mt-3 d-none">
                        <div class="card border-warning">
                            <div class="card-body py-3">
                                <h6 class="fw-bold text-warning mb-3">
                                    <i class="bx bx-credit-card me-1"></i>{{ __('pages.env_payment_title') }}
                                </h6>
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-5">
                                        <label class="form-label small fw-semibold">{{ __('pages.env_payment_country') }}</label>
                                        <select class="form-select form-select-sm" id="paymentCountryDoc">
                                            <option value="BJ">Bénin</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">{{ __('pages.env_payment_recipients') }}</label>
                                        <input type="text" class="form-control form-control-sm" id="paymentRecipientCountDoc" readonly value="0">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-outline-warning btn-sm w-100" id="btnCalculateCostDoc">
                                            <i class="bx bx-calculator me-1"></i>{{ __('pages.env_btn_calculate') }}
                                        </button>
                                    </div>
                                </div>
                                <div id="costResultDoc" class="d-none mt-3">
                                    <div class="alert alert-warning py-2 mb-2">
                                        <strong id="costDisplayDoc"></strong>
                                    </div>
                                    <button type="button" class="btn btn-warning btn-sm" id="btnPayNowDoc">
                                        <i class="bx bx-wallet me-1"></i>{{ __('pages.env_btn_pay') }}
                                    </button>
                                    <span id="paymentSuccessDoc" class="d-none ms-2 text-success fw-bold">
                                        <i class="bx bx-check-circle me-1"></i>{{ __('pages.env_payment_done') }}
                                    </span>
                                </div>
                                <input type="hidden" id="paymentTransactionIdDoc" value="">
                                <input type="hidden" id="paymentCountryCodeDoc" value="BJ">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Section 2 — Documents à envoyer --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">{{ __('pages.env_label_docs_to_send') }}</label>
                    <div id="blocDocsLocataire">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input check-doc" type="checkbox" id="docContrat" value="contrat">
                            <label class="form-check-label" for="docContrat">{{ __('pages.env_doc_contract') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input check-doc" type="checkbox" id="docCaution" value="quittance_caution">
                            <label class="form-check-label" for="docCaution">{{ __('pages.env_doc_deposit') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input check-doc" type="checkbox" id="docMensuelle" value="quittance_mensuelle">
                            <label class="form-check-label" for="docMensuelle">{{ __('pages.env_doc_monthly') }}</label>
                        </div>
                    </div>
                    <div id="blocDocsProprio" class="d-none">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input check-doc" type="checkbox" id="docReleveP" value="releve_proprietaire">
                            <label class="form-check-label" for="docReleveP">{{ __('pages.env_doc_owner_stmt') }}</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input check-doc" type="checkbox" id="docReleveA" value="releve_agence">
                            <label class="form-check-label" for="docReleveA">{{ __('pages.env_doc_agency_stmt') }}</label>
                        </div>
                    </div>

                    {{-- Bloc dates (pour relevés) --}}
                    <div id="blocReleve" class="mt-3 d-none p-3 rounded" style="background:rgba(255,193,7,.07);border:1px solid rgba(255,193,7,.3);">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small" for="dateDebut">{{ __('pages.env_label_start_date') }}</label>
                                <input type="date" class="form-control form-control-sm" id="dateDebut">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small" for="dateFin">{{ __('pages.env_label_end_date') }}</label>
                                <input type="date" class="form-control form-control-sm" id="dateFin">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small" for="pourcentage">{{ __('pages.env_label_pct') }}</label>
                                <input type="number" class="form-control form-control-sm" id="pourcentage"
                                       value="10" min="0" max="100" step="0.5">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Section 3 — Destinataires sélectionnés --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">{{ __('pages.env_label_recipients') }}</label>
                    <div id="listeDestinataires" class="border rounded p-2">
                        <p class="text-muted text-center small mb-0">{{ __('pages.env_no_recipient') }}</p>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Section 4 — Message personnalisé --}}
                <div class="mb-2">
                    <label class="form-label fw-semibold" for="messagePerso">{{ __('pages.env_label_custom_msg') }} <small class="text-muted fw-normal">{{ __('pages.env_optional') }}</small></label>
                    <textarea class="form-control" id="messagePerso" rows="2" maxlength="1000"
                              placeholder="{{ __('pages.env_ph_custom_msg') }}"></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('pages.env_btn_cancel') }}</button>
                <button type="button" class="btn btn-primary" id="btnSubmitEnvoi">
                    <i class="bx bx-send me-1"></i>{{ __('pages.env_btn_submit_send') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== MODAL NOTIFICATION ===== --}}
<div class="modal fade" id="modalNotification" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" id="notifModalHeader">
                <h5 class="modal-title" id="notifModalTitle"><i class="bx bx-bell me-1"></i>{{ __('pages.env_modal_notif_title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Méthode d'envoi --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">{{ __('pages.env_label_send_method') }}</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="notif_methode_envoi"
                                   id="notifMethodeEmail" value="email"
                                   {{ (!$parametre || !$parametre->email_envoi) ? 'disabled' : '' }} checked>
                            <label class="form-check-label" for="notifMethodeEmail">
                                <i class="bx bx-envelope me-1"></i>{{ __('pages.env_method_email') }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="notif_methode_envoi"
                                   id="notifMethodeWhatsApp" value="whatsapp"
                                   {{ $waConnecte ? '' : 'disabled' }}>
                            <label class="form-check-label" for="notifMethodeWhatsApp">
                                <i class="bx bxl-whatsapp me-1 text-success"></i>{{ __('pages.env_method_whatsapp') }}
                                <span id="wa-notif-badge" class="badge ms-1 {{ $waConnecte ? 'bg-success' : 'bg-danger' }}" style="font-size:.7rem;">{{ $waConnecte ? __('pages.env_configured') : __('pages.env_not_configured') }}</span>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="notif_methode_envoi"
                                   id="notifMethodeSMS" value="sms"
                                   {{ $atConnecte ? '' : 'disabled' }}>
                            <label class="form-check-label" for="notifMethodeSMS">
                                <i class="bx bx-message-detail me-1 text-warning"></i>{{ __('pages.env_method_sms') }}
                                <span id="sms-notif-badge" class="badge ms-1 {{ $atConnecte ? 'bg-success' : 'bg-danger' }}" style="font-size:.7rem;">{{ $atConnecte ? __('pages.env_configured') : __('pages.env_not_configured') }}</span>
                            </label>
                        </div>
                    </div>
                    <div id="notifInfoEmail" class="mt-2" style="font-size:.85rem;">
                        <i class="bx bx-envelope text-primary me-1"></i>
                        {{ __('pages.env_sender_prefix') }} <strong>{{ $parametre?->email_envoi ?: __('pages.env_sender_not_configured') }}</strong>
                    </div>
                    <div id="notifInfoWA" class="mt-2 d-none" style="font-size:.85rem;">
                        <i class="bx bxl-whatsapp text-success me-1"></i>
                        {{ __('pages.env_sender_at_prefix') }} <strong>{{ $parametre?->at_sender_id ?: ($parametre?->at_username ?: __('pages.env_sender_not_configured')) }}</strong>
                    </div>
                    <div id="notifInfoSMS" class="mt-2 d-none" style="font-size:.85rem;">
                        <i class="bx bx-message-detail text-warning me-1"></i>
                        {{ __('pages.env_sender_id_prefix') }} <strong>{{ $parametre?->at_sender_id ?: ($parametre?->at_username ?: __('pages.env_sender_not_configured')) }}</strong>
                    </div>

                    {{-- Paiement requis pour SMS/WhatsApp (notifications) --}}
                    <div id="paymentRequiredNotif" class="mt-3 d-none">
                        <div class="card border-warning">
                            <div class="card-body py-3">
                                <h6 class="fw-bold text-warning mb-3">
                                    <i class="bx bx-credit-card me-1"></i>{{ __('pages.env_payment_title') }}
                                </h6>
                                <div class="row g-2 align-items-end">
                                    <div class="col-md-5">
                                        <label class="form-label small fw-semibold">{{ __('pages.env_payment_country') }}</label>
                                        <select class="form-select form-select-sm" id="paymentCountryNotif">
                                            <option value="BJ">Bénin</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">{{ __('pages.env_payment_recipients') }}</label>
                                        <input type="text" class="form-control form-control-sm" id="paymentRecipientCountNotif" readonly value="0">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-outline-warning btn-sm w-100" id="btnCalculateCostNotif">
                                            <i class="bx bx-calculator me-1"></i>{{ __('pages.env_btn_calculate') }}
                                        </button>
                                    </div>
                                </div>
                                <div id="costResultNotif" class="d-none mt-3">
                                    <div class="alert alert-warning py-2 mb-2">
                                        <strong id="costDisplayNotif"></strong>
                                    </div>
                                    <button type="button" class="btn btn-warning btn-sm" id="btnPayNowNotif">
                                        <i class="bx bx-wallet me-1"></i>{{ __('pages.env_btn_pay') }}
                                    </button>
                                    <span id="paymentSuccessNotif" class="d-none ms-2 text-success fw-bold">
                                        <i class="bx bx-check-circle me-1"></i>{{ __('pages.env_payment_done') }}
                                    </span>
                                </div>
                                <input type="hidden" id="paymentTransactionIdNotif" value="">
                                <input type="hidden" id="paymentCountryCodeNotif" value="BJ">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Champ date fin de bail (Préavis uniquement) --}}
                <div id="blocDateFinBail" class="mb-4 d-none">
                    <label class="form-label fw-semibold" for="dateFinBail">
                        <i class="bx bx-calendar-x text-danger me-1"></i>{{ __('pages.env_label_lease_end_date') }} <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control" id="dateFinBail" style="max-width:240px;">
                    <div class="form-text">{{ __('pages.env_hint_lease_end_date') }}</div>
                </div>

                <hr class="my-3 d-none" id="hrDateFin">

                {{-- Destinataires --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">{{ __('pages.env_label_recipients') }}</label>
                    <div id="listeDestinatairesNotif" class="border rounded p-2" style="max-height:280px;overflow-y:auto;">
                        <p class="text-muted text-center small mb-0">{{ __('pages.env_no_recipient') }}</p>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Message personnalisé --}}
                <div class="mb-2">
                    <label class="form-label fw-semibold" for="notifMessagePerso">
                        {{ __('pages.env_label_add_msg') }} <small class="text-muted fw-normal">{{ __('pages.env_optional') }}</small>
                    </label>
                    <textarea class="form-control" id="notifMessagePerso" rows="3" maxlength="2000"
                              placeholder="{{ __('pages.env_ph_add_msg') }}"></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('pages.env_btn_cancel') }}</button>
                <button type="button" class="btn btn-warning" id="btnSubmitNotif">
                    <i class="bx bx-send me-1"></i>{{ __('pages.env_btn_send_notif') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@php $paymentCfgEnvoi = \App\PlatformConfig::getConfig(); @endphp
@if($paymentCfgEnvoi->isKkiapayActive())
<script src="https://cdn.kkiapay.me/k.js"></script>
@elseif($paymentCfgEnvoi->isFedapayActive())
<script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
@endif
<script>
    const CSRF             = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    const ROUTE_ENVOYER    = '{{ route("envoi_document.envoyer") }}';
    const ROUTE_HISTORIQUE = '{{ route("envoi_document.historique") }}';

    var ENV_I18N = {
        selected:         '{{ __('pages.env_action_selected') }}',
        noRecipient:      '{{ __('pages.env_js_no_recipient') }}',
        emailRequired:    '{{ __('pages.env_js_email_required') }}',
        monthsLabel:      '{{ __('pages.env_js_months_label') }}',
        noInvoice:        '{{ __('pages.env_js_no_invoice') }}',
        warning:          '{{ __('pages.env_js_warning') }}',
        selectRecipients: '{{ __('pages.env_js_select_recipients') }}',
        error:            '{{ __('pages.env_js_error') }}',
        btnOpening:       '<span class="spinner-border spinner-border-sm me-1"></span>{{ __('pages.env_js_btn_opening') }}',
        noProvider:       '{{ __('pages.env_js_no_provider') }}',
        btnPay:           '<i class="bx bx-wallet me-1"></i>{{ __('pages.env_js_btn_pay') }}',
        kkiapayError:     '{{ __('pages.env_js_kkiapay_error') }}',
        paymentFailed:    '{{ __('pages.env_js_payment_failed') }}',
        kkiapayFailed:    '{{ __('pages.env_js_kkiapay_failed') }}',
        waDesc:           '{{ __('pages.env_js_wa_desc') }}',
        fedapayFailed:    '{{ __('pages.env_js_fedapay_failed') }}',
        chooseMethod:     '{{ __('pages.env_js_choose_method') }}',
        selectDoc:        '{{ __('pages.env_js_select_doc') }}',
        noRecipientDot:   '{{ __('pages.env_js_no_recipient_dot') }}',
        paymentRequired:  '{{ __('pages.env_js_payment_required') }}',
        paymentReqWa:     '{{ __('pages.env_js_payment_req_wa') }}',
        btnSending:       '<span class="spinner-border spinner-border-sm me-1"></span>{{ __('pages.env_js_btn_sending') }}',
        result:           '{{ __('pages.env_js_result') }}',
        ok:               '{{ __('pages.env_js_ok') }}',
        btnSend:          '<i class="bx bx-send me-1"></i>{{ __('pages.env_btn_submit_send') }}',
        histResults:      '{{ __('pages.env_js_history_results') }}',
        histTotal:        '{{ __('pages.env_js_history_total') }}',
        histNoResults:    '{{ __('pages.env_js_history_no_results') }}',
        histLoadError:    '{{ __('pages.env_js_history_load_error') }}',
        histSuccess:      '{{ __('pages.env_js_success') }}',
        docContract:      '{{ __('pages.env_js_doc_contract') }}',
        docMonthly:       '{{ __('pages.env_js_doc_monthly') }}',
        docDeposit:       '{{ __('pages.env_js_doc_deposit') }}',
        docOwnerStmt:     '{{ __('pages.env_js_doc_owner_stmt') }}',
        docAgencyStmt:    '{{ __('pages.env_js_doc_agency_stmt') }}',
        docReminder:      '{{ __('pages.env_js_doc_reminder') }}',
        docNotice:        '{{ __('pages.env_js_doc_notice') }}',
        notifTitleNotice: '<i class="bx bx-calendar-x me-1"></i>{{ __('pages.env_js_notif_title_notice') }}',
        btnSendNotice:    '<i class="bx bx-send me-1"></i>{{ __('pages.env_js_btn_send_notice') }}',
        notifTitleRemind: '<i class="bx bx-alarm me-1"></i>{{ __('pages.env_js_notif_title_reminder') }}',
        btnSendRemind:    '<i class="bx bx-send me-1"></i>{{ __('pages.env_js_btn_send_reminder') }}',
        specifyLeaseEnd:  '{{ __('pages.env_js_specify_lease_end') }}',
        notifPayReqWa:    '{{ __('pages.env_js_notif_pay_req_wa') }}',
        notifPayReqSms:   '{{ __('pages.env_js_notif_pay_req_sms') }}',
        totalToPay:       '{{ __('pages.env_js_total_to_pay') }}',
        waMessages:       '{{ __('pages.env_js_wa_messages') }}',
        smsMessages:      '{{ __('pages.env_js_sms_messages') }}',
        failure:          '{{ __('pages.env_js_failure') }}',
        contactEmpty:     '{{ __('pages.env_js_contact_empty') }}',
        fieldPhone:       '{{ __('pages.env_js_field_phone') }}',
    };

    // ── État global ─────────────────────────────────────────────
    let selectedDestinataires = [];
    // Dernier type ouvert dans le modal ('locataire' | 'proprietaire')
    let currentModalType = 'locataire';

    // ── Helpers ──────────────────────────────────────────────────
    function updateActionBar() {
        const count = selectedDestinataires.length;
        document.getElementById('selectionCount').textContent = count;
        document.getElementById('selectionLabel').textContent = count + ' ' + ENV_I18N.selected;
        document.getElementById('actionBar').classList.toggle('d-none', count === 0);
    }

    function destFromCheckbox(cb) {
        return {
            type:      cb.dataset.type,
            id:        parseInt(cb.dataset.id),
            nom:       cb.dataset.nom,
            email:     cb.dataset.email || '',
            telephone: cb.dataset.tel   || '',
            factures:  JSON.parse(cb.dataset.factures || '[]'),
        };
    }

    // ── Gestion des checkboxes destinataires ─────────────────────
    document.addEventListener('change', function(e) {
        const cb = e.target;

        // Check-all locataires
        if (cb.id === 'checkAllLoc') {
            document.querySelectorAll('#tableLoc .check-dest').forEach(function(c) {
                if (c.closest('tr').style.display !== 'none') {
                    c.checked = cb.checked;
                }
            });
            rebuildSelectedFromChecked();
            return;
        }

        // Check-all propriétaires
        if (cb.id === 'checkAllProprio') {
            document.querySelectorAll('#tableProprio .check-dest').forEach(function(c) {
                if (c.closest('tr').style.display !== 'none') {
                    c.checked = cb.checked;
                }
            });
            rebuildSelectedFromChecked();
            return;
        }

        // Checkbox individuelle
        if (cb.classList.contains('check-dest')) {
            rebuildSelectedFromChecked();
        }
    });

    function rebuildSelectedFromChecked() {
        selectedDestinataires = [];
        document.querySelectorAll('.check-dest:checked').forEach(function(cb) {
            selectedDestinataires.push(destFromCheckbox(cb));
        });
        updateActionBar();
    }

    // ── Bouton "Envoyer" individuel sur chaque ligne ─────────────
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-envoyer');
        if (!btn) return;

        const dest = {
            type:      btn.dataset.type,
            id:        parseInt(btn.dataset.id),
            nom:       btn.dataset.nom,
            email:     btn.dataset.email || '',
            telephone: btn.dataset.tel   || '',
            factures:  JSON.parse(btn.dataset.factures || '[]'),
        };

        // Sélection unique : juste ce destinataire
        selectedDestinataires = [dest];
        ouvrirModal(dest.type);
    });

    // ── Bouton "Envoyer la sélection" ────────────────────────────
    document.getElementById('btnEnvoyerSelection').addEventListener('click', function() {
        if (selectedDestinataires.length === 0) return;
        // Déterminer le type depuis la sélection (tous du même type ou mixte — on prend le premier pour les docs)
        ouvrirModal(selectedDestinataires[0].type);
    });

    // ── Ouverture du modal ────────────────────────────────────────
    function ouvrirModal(type) {
        currentModalType = type;

        // Afficher le bon bloc de documents
        document.getElementById('blocDocsLocataire').classList.toggle('d-none', type !== 'locataire');
        document.getElementById('blocDocsProprio').classList.toggle('d-none', type !== 'proprietaire');

        // Décocher tous les docs
        document.querySelectorAll('.check-doc').forEach(function(cb) { cb.checked = false; });

        // Cacher blocs conditionnels
        document.getElementById('blocReleve').classList.add('d-none');
        document.getElementById('messagePerso').value = '';

        // Construire la liste des destinataires
        construireListeDestinataires(false);

        // Méthode d'envoi : réinitialiser selon disponibilité
        const emailRadio = document.getElementById('methodeEmail');
        const waRadio    = document.getElementById('methodeWhatsApp');
        if (!emailRadio.disabled) {
            emailRadio.checked = true;
        } else if (!waRadio.disabled) {
            waRadio.checked = true;
        }
        syncInfoExpediteur();
        updatePaymentRequiredDoc();

        new bootstrap.Modal(document.getElementById('modalEnvoiDocument')).show();
    }

    // ── Construction de la liste des destinataires dans le modal ─
    function construireListeDestinataires(avecColonneFacture) {
        const container = document.getElementById('listeDestinataires');

        if (selectedDestinataires.length === 0) {
            container.innerHTML = '<p class="text-muted text-center small mb-0">' + ENV_I18N.noRecipient + '</p>';
            return;
        }

        const methode = document.querySelector('input[name="methode_envoi"]:checked')?.value || 'email';
        let html = '';

        selectedDestinataires.forEach(function(dest, idx) {
            const emailEmpty = !dest.email;
            const emailClass = emailEmpty ? 'border-warning' : '';
            const emailPlaceholder = emailEmpty ? ENV_I18N.emailRequired : '';

            html += `<div class="dest-row" data-idx="${idx}" data-id="${dest.id}" data-type="${dest.type}">`;
            html += `<div class="row g-1 align-items-center">`;

            // Nom
            html += `<div class="col-12 col-md-3">`;
            html += `<span class="fw-semibold small">${escHtml(dest.nom)}</span>`;
            html += `</div>`;

            // Email
            html += `<div class="col-12 col-md-3">`;
            html += `<input type="email" class="dest-email form-control form-control-sm ${emailClass}"
                            value="${escHtml(dest.email)}"
                            placeholder="${emailPlaceholder || 'Email'}"
                            title="Email de ${escHtml(dest.nom)}">`;
            html += `</div>`;

            // Téléphone
            html += `<div class="col-12 col-md-3">`;
            html += `<input type="tel" class="dest-tel form-control form-control-sm"
                            value="${escHtml(dest.telephone)}"
                            placeholder="+229…"
                            title="Téléphone de ${escHtml(dest.nom)}">`;
            html += `</div>`;

            // Colonne factures (checkboxes multi-mois, visible seulement si quittance_mensuelle cochée)
            html += `<div class="col-12 col-facture ${avecColonneFacture ? '' : 'd-none'}">`;
            if (dest.type === 'locataire' && dest.factures.length > 0) {
                html += `<label class="form-label small fw-semibold mb-1">${ENV_I18N.monthsLabel}</label>`;
                html += `<div class="border rounded p-2" style="max-height:100px;overflow-y:auto;background:#f8f9fa;">`;
                dest.factures.forEach(function(f) {
                    html += `<div class="form-check form-check-sm mb-1">
                        <input class="form-check-input dest-facture-cb" type="checkbox"
                               value="${f.id}" id="fact_${idx}_${f.id}">
                        <label class="form-check-label small" for="fact_${idx}_${f.id}">
                            ${escHtml(f.mois)} — ${Number(f.montant).toLocaleString('fr-FR')} XOF
                        </label>
                    </div>`;
                });
                html += `</div>`;
            } else if (dest.type === 'locataire') {
                html += `<small class="text-muted fst-italic">${ENV_I18N.noInvoice}</small>`;
            }
            html += `</div>`;

            html += `</div></div>`;
        });

        container.innerHTML = html;
    }

    function escHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ── Changement de méthode d'envoi ────────────────────────────
    document.querySelectorAll('input[name="methode_envoi"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            syncInfoExpediteur();
            updatePaymentRequiredDoc();
        });
    });

    function syncInfoExpediteur() {
        const methode = document.querySelector('input[name="methode_envoi"]:checked')?.value || 'email';
        document.getElementById('infoExpediteurEmail').classList.toggle('d-none', methode !== 'email');
        document.getElementById('infoExpediteurWA').classList.toggle('d-none', methode !== 'whatsapp');
    }

    // ── Paiement : Envoi Documents ───────────────────────────────
    var docPaymentDone = false;
    var docPaymentTxnId = '';

    function updatePaymentRequiredDoc() {
        var method = document.querySelector('input[name="methode_envoi"]:checked')?.value;
        var needsPayment = (method === 'whatsapp');
        document.getElementById('paymentRequiredDoc').classList.toggle('d-none', !needsPayment);
        if (needsPayment) {
            document.getElementById('paymentRecipientCountDoc').value = selectedDestinataires.length;
        }
        // Reset paiement
        docPaymentDone  = false;
        docPaymentTxnId = '';
        document.getElementById('paymentTransactionIdDoc').value = '';
        document.getElementById('paymentSuccessDoc').classList.add('d-none');
        document.getElementById('btnPayNowDoc').classList.remove('d-none');
        document.getElementById('costResultDoc').classList.add('d-none');
    }

    document.getElementById('btnCalculateCostDoc').addEventListener('click', function() {
        var channel = document.querySelector('input[name="methode_envoi"]:checked')?.value;
        var count   = parseInt(document.getElementById('paymentRecipientCountDoc').value) || selectedDestinataires.length;
        var country = document.getElementById('paymentCountryDoc').value;

        if (count === 0) {
            Swal.fire(ENV_I18N.warning, ENV_I18N.selectRecipients, 'warning');
            return;
        }

        $.get('/messaging/quote', { channel: channel, count: count, country_code: country }, function(data) {
            if (data.status) {
                document.getElementById('paymentCountryCodeDoc').value = country;
                document.getElementById('costDisplayDoc').textContent =
                    ENV_I18N.totalToPay + ' ' + data.total.toLocaleString('fr-FR') + ' ' + data.currency +
                    ' (' + count + ' ' + ENV_I18N.waMessages + ' ' + data.unit_cost + ' ' + data.currency + ')';
                document.getElementById('costResultDoc').classList.remove('d-none');
            } else {
                Swal.fire(ENV_I18N.error, data.message, 'error');
            }
        });
    });

    document.getElementById('btnPayNowDoc').addEventListener('click', function() {
        var btn = this;
        btn.disabled = true;
        btn.innerHTML = ENV_I18N.btnOpening;

        $.get('/platform/kkiapay-public', function(cfg) {
            if (!cfg.payment_enabled) {
                Swal.fire(ENV_I18N.error, ENV_I18N.noProvider, 'error');
                btn.disabled = false;
                btn.innerHTML = ENV_I18N.btnPay;
                return;
            }
            var amount = 0;
            var txt = document.getElementById('costDisplayDoc').textContent;
            var m = txt.match(/[\d\s,]+/);
            if (m) amount = parseInt(m[1].replace(/[\s,]/g, '')) || 0;

            function onDocPaySuccess(txnId) {
                docPaymentDone  = true;
                docPaymentTxnId = txnId;
                document.getElementById('paymentTransactionIdDoc').value = txnId;
                document.getElementById('paymentSuccessDoc').classList.remove('d-none');
                btn.classList.add('d-none');
            }
            function onDocPayFail() {
                btn.disabled = false;
                btn.innerHTML = ENV_I18N.btnPay;
            }

            if (cfg.payment_provider === 'kkiapay') {
                if (typeof openKkiapayWidget !== 'function') {
                    Swal.fire(ENV_I18N.error, ENV_I18N.kkiapayError, 'error');
                    onDocPayFail(); return;
                }
                var kkDone = false, kkSeen = false, kkPoll = null;
                function kkVisible() {
                    var el = document.querySelector('iframe[src*="kkiapay"]') ||
                             document.querySelector('[id*="kkiapay"]') ||
                             document.querySelector('[class*="kkiapay"]');
                    if (!el) return false;
                    var s = window.getComputedStyle(el);
                    return s.display !== 'none' && s.visibility !== 'hidden';
                }
                kkPoll = setInterval(function() {
                    var v = kkVisible();
                    if (v) { kkSeen = true; }
                    else if (kkSeen && !kkDone) { clearInterval(kkPoll); onDocPayFail(); }
                }, 300);
                setTimeout(function() { clearInterval(kkPoll); }, 900000);

                openKkiapayWidget({ amount: amount, key: cfg.payment_public_key, sandbox: cfg.payment_sandbox });

                addSuccessListener(function(response) {
                    if (kkDone) return;
                    kkDone = true; clearInterval(kkPoll);
                    onDocPaySuccess(response.transactionId);
                });
                addFailedListener(function() {
                    if (kkDone) return;
                    kkDone = true; clearInterval(kkPoll);
                    Swal.fire(ENV_I18N.paymentFailed, ENV_I18N.kkiapayFailed, 'error');
                    onDocPayFail();
                });
            } else if (cfg.payment_provider === 'fedapay') {
                FedaPay.init({
                    public_key:  cfg.payment_public_key,
                    transaction: { amount: amount, description: ENV_I18N.waDesc },
                    onComplete: function(resp) {
                        if (resp.reason === FedaPay.DIALOG_DISMISSED) { onDocPayFail(); return; }
                        if (resp.transaction && resp.transaction.status === 'approved') {
                            onDocPaySuccess(resp.transaction.id.toString());
                        } else {
                            Swal.fire(ENV_I18N.paymentFailed, ENV_I18N.fedapayFailed, 'error');
                            onDocPayFail();
                        }
                    }
                }).open();
            }
        }).fail(function() {
            btn.disabled = false;
            btn.innerHTML = ENV_I18N.btnPay;
        });
    });

    // ── Changement des checkboxes documents ──────────────────────
    document.querySelectorAll('.check-doc').forEach(function(cb) {
        cb.addEventListener('change', function() {
            const docsMensuelle = document.getElementById('docMensuelle');
            const docReleveP    = document.getElementById('docReleveP');
            const docReleveA    = document.getElementById('docReleveA');

            // Toggle colonne facture
            const avecFacture = docsMensuelle && docsMensuelle.checked;
            document.querySelectorAll('.col-facture').forEach(function(col) {
                col.classList.toggle('d-none', !avecFacture);
            });

            // Toggle bloc dates relevés
            const avecReleve = (docReleveP && docReleveP.checked) || (docReleveA && docReleveA.checked);
            document.getElementById('blocReleve').classList.toggle('d-none', !avecReleve);

            // Reconstruire la liste pour mettre à jour les colonnes
            construireListeDestinataires(avecFacture);
        });
    });

    // ── Submit envoi ─────────────────────────────────────────────
    document.getElementById('btnSubmitEnvoi').addEventListener('click', async function() {
        const methodeEl = document.querySelector('input[name="methode_envoi"]:checked');
        if (!methodeEl) {
            Swal.fire(ENV_I18N.warning, ENV_I18N.chooseMethod, 'warning');
            return;
        }
        const methode = methodeEl.value;

        // Récupérer les documents cochés
        const typeDocuments = [];
        document.querySelectorAll('.check-doc:checked').forEach(function(cb) {
            typeDocuments.push(cb.value);
        });
        if (typeDocuments.length === 0) {
            Swal.fire(ENV_I18N.warning, ENV_I18N.selectDoc, 'warning');
            return;
        }

        if (selectedDestinataires.length === 0) {
            Swal.fire(ENV_I18N.warning, ENV_I18N.noRecipientDot, 'warning');
            return;
        }

        // Construire la liste des destinataires depuis le DOM du modal
        const rows = document.querySelectorAll('#listeDestinataires .dest-row');
        const destinataires = [];

        let erreurContact = false;
        rows.forEach(function(row) {
            const idx  = parseInt(row.dataset.idx);
            const id   = parseInt(row.dataset.id);
            const type = row.dataset.type;

            const emailInput = row.querySelector('.dest-email');
            const telInput   = row.querySelector('.dest-tel');
            const factureEl  = row.querySelector('.dest-facture');

            const email     = emailInput ? emailInput.value.trim() : '';
            const telephone = telInput   ? telInput.value.trim()   : '';
            const contact   = methode === 'email' ? email : telephone;

            if (!contact) {
                const champ = methode === 'email' ? 'email' : ENV_I18N.fieldPhone;
                const nom   = selectedDestinataires[idx]?.nom || ('#' + idx);
                Swal.fire(ENV_I18N.warning, ENV_I18N.contactEmpty.replace('%s', champ).replace('%s', nom), 'warning');
                erreurContact = true;
                return;
            }

            const entry = { type, id, contact };

            if (typeDocuments.includes('quittance_mensuelle')) {
                const cbs = row.querySelectorAll('.dest-facture-cb:checked');
                entry.facture_ids = Array.from(cbs).map(cb => parseInt(cb.value));
            }

            destinataires.push(entry);
        });

        if (erreurContact) return;

        // Champs relevé
        const dateDebut   = document.getElementById('dateDebut').value   || null;
        const dateFin     = document.getElementById('dateFin').value     || null;
        const pourcentage = document.getElementById('pourcentage').value || null;
        const msgPerso    = document.getElementById('messagePerso').value.trim() || null;

        // Vérifier paiement si WhatsApp
        if (methode === 'whatsapp' && !docPaymentDone) {
            Swal.fire(ENV_I18N.paymentRequired, ENV_I18N.paymentReqWa, 'warning');
            return;
        }

        const payload = {
            methode_envoi:           methode,
            type_documents:          typeDocuments,
            destinataires:           destinataires,
            message_personnalise:    msgPerso,
            date_debut:              dateDebut,
            date_fin:                dateFin,
            pourcentage:             pourcentage ? parseFloat(pourcentage) : null,
            payment_transaction_id:  methode === 'whatsapp' ? docPaymentTxnId : null,
            country_code:            methode === 'whatsapp' ? document.getElementById('paymentCountryCodeDoc').value : null,
        };

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = ENV_I18N.btnSending;

        try {
            const res  = await fetch(ROUTE_ENVOYER, {
                method:  'POST',
                headers: {
                    'X-CSRF-TOKEN':  CSRF,
                    'Content-Type':  'application/json',
                    'Accept':        'application/json',
                },
                body: JSON.stringify(payload),
            });
            const json = await res.json();

            if (json.status) {
                // Résumé des résultats
                let details = '';
                let errors  = [];
                if (json.details && json.details.length > 0) {
                    errors = json.details.filter(d => d.statut === 'error');
                    if (errors.length > 0) {
                        details = '<ul class="text-start mt-2 mb-0" style="font-size:.85rem;">';
                        errors.forEach(function(d) {
                            details += `<li class="text-danger">${escHtml(d.destinataire)} — ${escHtml(d.document)} : ${escHtml(d.erreur || ENV_I18N.failure)}</li>`;
                        });
                        details += '</ul>';
                    }
                }

                Swal.fire({
                    icon: errors.length > 0 ? 'warning' : 'success',
                    title: ENV_I18N.result,
                    html:  json.message + details,
                    confirmButtonText: ENV_I18N.ok,
                });

                // Fermer modal et rafraîchir
                bootstrap.Modal.getInstance(document.getElementById('modalEnvoiDocument')).hide();
                chargerHistorique();

                // Réinitialiser les checkboxes
                document.querySelectorAll('.check-dest').forEach(function(cb) { cb.checked = false; });
                document.getElementById('checkAllLoc').checked    = false;
                document.getElementById('checkAllProprio').checked = false;
                selectedDestinataires = [];
                updateActionBar();

            } else {
                Swal.fire({ icon: 'error', title: ENV_I18N.error, text: json.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: ENV_I18N.error, text: err.message });
        } finally {
            btn.disabled = false;
            btn.innerHTML = ENV_I18N.btnSend;
        }
    });

    // ── Recherche en temps réel ──────────────────────────────────
    function filtreTable(inputId, tableId) {
        document.getElementById(inputId).addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#' + tableId + ' tbody tr').forEach(function(tr) {
                tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }
    filtreTable('searchLoc', 'tableLoc');
    filtreTable('searchProprio', 'tableProprio');

    // ── Historique ───────────────────────────────────────────────
    const labels = {
        contrat:             ENV_I18N.docContract,
        quittance_mensuelle: ENV_I18N.docMonthly,
        quittance_caution:   ENV_I18N.docDeposit,
        releve_proprietaire: ENV_I18N.docOwnerStmt,
        releve_agence:       ENV_I18N.docAgencyStmt,
        rappel_loyer:        ENV_I18N.docReminder,
        preavis:             ENV_I18N.docNotice,
    };

    const HISTO_PAGE_SIZE = 10;
    let allHistoriqueData  = [];
    let histoCurrentPage   = 1;

    // ── Rendu paginé + filtré (sans appel réseau) ─────────────────
    function rendreHistorique() {
        const q       = (document.getElementById('histoFiltreNom')?.value     || '').toLowerCase().trim();
        const doc     = (document.getElementById('histoFiltreDoc')?.value     || '');
        const methode = (document.getElementById('histoFiltreMethode')?.value || '');
        const statut  = (document.getElementById('histoFiltreStatut')?.value  || '');

        const filtered = allHistoriqueData.filter(function(e) {
            if (q && !(
                (e.destinataire_nom     || '').toLowerCase().includes(q) ||
                (e.destinataire_contact || '').toLowerCase().includes(q)
            )) return false;
            if (doc     && e.type_document  !== doc)     return false;
            if (methode && e.methode_envoi  !== methode) return false;
            if (statut  && e.statut         !== statut)  return false;
            return true;
        });

        const total     = filtered.length;
        const totalPages = Math.max(1, Math.ceil(total / HISTO_PAGE_SIZE));
        if (histoCurrentPage > totalPages) histoCurrentPage = 1;

        const debut = (histoCurrentPage - 1) * HISTO_PAGE_SIZE;
        const page  = filtered.slice(debut, debut + HISTO_PAGE_SIZE);

        const tbody = document.getElementById('tbodyHistorique');

        // Compteur global
        const infoEl = document.getElementById('histoInfoCount');
        if (infoEl) {
            const filtreActif = q || doc || methode || statut;
            infoEl.textContent = filtreActif
                ? `${total} ${ENV_I18N.histResults} ${allHistoriqueData.length}`
                : `${allHistoriqueData.length} ${ENV_I18N.histTotal}`;
        }

        if (total === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">' + ENV_I18N.histNoResults + '</td></tr>';
            document.getElementById('histoPagination').style.display = 'none';
            return;
        }

        tbody.innerHTML = page.map(function(e) {
            const dateStr = new Date(e.created_at).toLocaleString('fr-FR', {
                day: '2-digit', month: '2-digit', year: 'numeric',
                hour: '2-digit', minute: '2-digit'
            });
            const docLabel  = labels[e.type_document] || e.type_document;
            const methBadge = e.methode_envoi === 'email'
                ? '<span class="badge bg-primary"><i class="bx bx-envelope me-1"></i>Email</span>'
                : e.methode_envoi === 'sms'
                    ? '<span class="badge bg-warning text-dark"><i class="bx bx-message-detail me-1"></i>SMS</span>'
                    : '<span class="badge bg-success"><i class="bx bxl-whatsapp me-1"></i>WhatsApp</span>';
            const statBadge = e.statut === 'success'
                ? '<span class="badge bg-label-success">' + ENV_I18N.histSuccess + '</span>'
                : `<span class="badge bg-label-danger" title="${escHtml(e.message_erreur || '')}">${ENV_I18N.failure}</span>`;

            return `<tr>
                <td><small class="text-nowrap">${dateStr}</small></td>
                <td><span class="fw-semibold">${escHtml(e.destinataire_nom)}</span></td>
                <td><small>${escHtml(docLabel)}</small></td>
                <td>${methBadge}</td>
                <td><small class="text-muted">${escHtml(e.destinataire_contact || '—')}</small></td>
                <td>${statBadge}</td>
            </tr>`;
        }).join('');

        // Pagination
        const paginEl    = document.getElementById('histoPagination');
        const infoPage   = document.getElementById('histoPaginationInfo');
        const btnsEl     = document.getElementById('histoPaginationBtns');
        const fin        = Math.min(debut + HISTO_PAGE_SIZE, total);
        infoPage.textContent = `${debut + 1}–${fin} sur ${total}`;
        paginEl.style.display = 'flex';

        // Boutons page
        let btnsHtml = '';
        // Précédent
        btnsHtml += `<button class="btn btn-sm ${histoCurrentPage === 1 ? 'btn-outline-secondary disabled' : 'btn-outline-primary'}"
                             data-histo-page="${histoCurrentPage - 1}" ${histoCurrentPage === 1 ? 'disabled' : ''}>
                        <i class="bx bx-chevron-left"></i>
                     </button>`;
        // Pages (max 5 visibles)
        const delta = 2;
        const rangeStart = Math.max(1, histoCurrentPage - delta);
        const rangeEnd   = Math.min(totalPages, histoCurrentPage + delta);
        if (rangeStart > 1) {
            btnsHtml += `<button class="btn btn-sm btn-outline-secondary" data-histo-page="1">1</button>`;
            if (rangeStart > 2) btnsHtml += `<span class="btn btn-sm btn-outline-secondary disabled">…</span>`;
        }
        for (let p = rangeStart; p <= rangeEnd; p++) {
            btnsHtml += `<button class="btn btn-sm ${p === histoCurrentPage ? 'btn-primary' : 'btn-outline-secondary'}"
                                 data-histo-page="${p}">${p}</button>`;
        }
        if (rangeEnd < totalPages) {
            if (rangeEnd < totalPages - 1) btnsHtml += `<span class="btn btn-sm btn-outline-secondary disabled">…</span>`;
            btnsHtml += `<button class="btn btn-sm btn-outline-secondary" data-histo-page="${totalPages}">${totalPages}</button>`;
        }
        // Suivant
        btnsHtml += `<button class="btn btn-sm ${histoCurrentPage === totalPages ? 'btn-outline-secondary disabled' : 'btn-outline-primary'}"
                             data-histo-page="${histoCurrentPage + 1}" ${histoCurrentPage === totalPages ? 'disabled' : ''}>
                        <i class="bx bx-chevron-right"></i>
                     </button>`;
        btnsEl.innerHTML = btnsHtml;
    }

    // Clic sur un bouton de pagination
    document.getElementById('histoPaginationBtns').addEventListener('click', function(e) {
        const btn = e.target.closest('[data-histo-page]');
        if (!btn || btn.disabled || btn.classList.contains('disabled')) return;
        histoCurrentPage = parseInt(btn.dataset.histoPage);
        rendreHistorique();
    });

    // ── Chargement des données (fetch) ────────────────────────────
    async function chargerHistorique() {
        const tbody = document.getElementById('tbodyHistorique');
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-3"><span class="spinner-border spinner-border-sm"></span></td></tr>';
        try {
            const res  = await fetch(ROUTE_HISTORIQUE);
            const json = await res.json();
            allHistoriqueData = json.data || [];
            histoCurrentPage  = 1;
            rendreHistorique();
        } catch (err) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-3">' + ENV_I18N.histLoadError + '</td></tr>';
        }
    }

    // ── Écoute des filtres ────────────────────────────────────────
    ['histoFiltreNom','histoFiltreDoc','histoFiltreMethode','histoFiltreStatut'].forEach(function(id) {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener(el.tagName === 'SELECT' ? 'change' : 'input', function() {
            histoCurrentPage = 1;
            rendreHistorique();
        });
    });

    document.getElementById('histoBtnReset').addEventListener('click', function() {
        document.getElementById('histoFiltreNom').value      = '';
        document.getElementById('histoFiltreDoc').value      = '';
        document.getElementById('histoFiltreMethode').value  = '';
        document.getElementById('histoFiltreStatut').value   = '';
        histoCurrentPage = 1;
        rendreHistorique();
    });

    document.getElementById('btnRefreshHistorique').addEventListener('click', chargerHistorique);
    chargerHistorique();

    // Statut AT : l'état initial (disabled/enabled) est rendu par PHP.
    // Ce bloc JS ne fait rien — gardé pour compatibilité future.

    // ═══════════════════════════════════════════════════════════
    // NOTIFICATIONS LOCATAIRES
    // ═══════════════════════════════════════════════════════════

    const ROUTE_NOTIFICATION = '{{ route("envoi_document.notification") }}';

    let selectedNotifDests = [];
    let currentNotifType   = 'rappel_loyer'; // 'rappel_loyer' | 'preavis'

    // Labels historique enrichis (déjà définis dans l'objet labels via ENV_I18N)

    // ── Helpers notif ────────────────────────────────────────────
    function destFromNotifCheckbox(cb) {
        return {
            id:        parseInt(cb.dataset.id),
            nom:       cb.dataset.nom,
            email:     cb.dataset.email || '',
            telephone: cb.dataset.tel   || '',
            prix:      cb.dataset.prix  || '0',
            logement:  cb.dataset.logement || '',
        };
    }

    function updateActionBarNotif() {
        const count = selectedNotifDests.length;
        document.getElementById('selectionCountNotif').textContent  = count;
        document.getElementById('selectionCountNotif2').textContent = count;
        document.getElementById('selectionLabelNotif').textContent  = count + ' ' + ENV_I18N.selected;
        document.getElementById('actionBarNotif').classList.toggle('d-none', count === 0);
    }

    function rebuildNotifFromChecked() {
        selectedNotifDests = [];
        document.querySelectorAll('.check-notif:checked, .check-preavis:checked').forEach(function(cb) {
            selectedNotifDests.push(destFromNotifCheckbox(cb));
        });
        updateActionBarNotif();
    }

    // Check-all rappel
    document.getElementById('checkAllRappel').addEventListener('change', function() {
        document.querySelectorAll('#tableRappel .check-notif').forEach(function(c) {
            if (c.closest('tr').style.display !== 'none') c.checked = this.checked;
        }, this);
        rebuildNotifFromChecked();
    });

    // Check-all préavis
    document.getElementById('checkAllPreavis').addEventListener('change', function() {
        document.querySelectorAll('#tablePreavis .check-preavis').forEach(function(c) {
            if (c.closest('tr').style.display !== 'none') c.checked = this.checked;
        }, this);
        rebuildNotifFromChecked();
    });

    // Checkboxes individuelles
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('check-notif') || e.target.classList.contains('check-preavis')) {
            rebuildNotifFromChecked();
        }
    });

    // ── Boutons individuels ───────────────────────────────────────
    document.addEventListener('click', function(e) {
        const btnRappel  = e.target.closest('.btn-notif-rappel');
        const btnPreavis = e.target.closest('.btn-notif-preavis');

        if (btnRappel) {
            selectedNotifDests = [destFromNotifCheckbox(btnRappel)];
            updateActionBarNotif();
            ouvrirModalNotif('rappel_loyer');
            return;
        }
        if (btnPreavis) {
            selectedNotifDests = [destFromNotifCheckbox(btnPreavis)];
            updateActionBarNotif();
            ouvrirModalNotif('preavis');
            return;
        }
    });

    // ── Boutons barre de sélection ────────────────────────────────
    document.getElementById('btnEnvoyerRappelSelection').addEventListener('click', function() {
        if (selectedNotifDests.length === 0) return;
        ouvrirModalNotif('rappel_loyer');
    });
    document.getElementById('btnEnvoyerPreavisSelection').addEventListener('click', function() {
        if (selectedNotifDests.length === 0) return;
        ouvrirModalNotif('preavis');
    });

    // ── Ouverture du modal Notification ──────────────────────────
    function ouvrirModalNotif(type) {
        currentNotifType = type;

        const isPreavis  = type === 'preavis';
        const header     = document.getElementById('notifModalHeader');
        const title      = document.getElementById('notifModalTitle');
        const btnSubmit  = document.getElementById('btnSubmitNotif');
        const blocDate   = document.getElementById('blocDateFinBail');
        const hrDate     = document.getElementById('hrDateFin');

        if (isPreavis) {
            header.style.background = '#ef4444';
            title.style.color = '#fff';
            title.innerHTML  = ENV_I18N.notifTitleNotice;
            btnSubmit.className = 'btn btn-danger';
            btnSubmit.innerHTML = ENV_I18N.btnSendNotice;
        } else {
            header.style.background = '#f59e0b';
            title.style.color = '#fff';
            title.innerHTML  = ENV_I18N.notifTitleRemind;
            btnSubmit.className = 'btn btn-warning';
            btnSubmit.innerHTML = ENV_I18N.btnSendRemind;
        }

        blocDate.classList.toggle('d-none', !isPreavis);
        hrDate.classList.toggle('d-none', !isPreavis);

        if (isPreavis) {
            document.getElementById('dateFinBail').value = '';
        }

        document.getElementById('notifMessagePerso').value = '';

        // Méthode d'envoi
        const emailR = document.getElementById('notifMethodeEmail');
        const waR    = document.getElementById('notifMethodeWhatsApp');
        const smsR   = document.getElementById('notifMethodeSMS');
        if (!emailR.disabled) emailR.checked = true;
        else if (!waR.disabled) waR.checked = true;
        else if (smsR && !smsR.disabled) smsR.checked = true;
        syncNotifExpediteur();
        updatePaymentRequiredNotif();

        construireListeDestinatairesNotif();

        new bootstrap.Modal(document.getElementById('modalNotification')).show();
    }

    // ── Sync expéditeur ───────────────────────────────────────────
    document.querySelectorAll('input[name="notif_methode_envoi"]').forEach(function(r) {
        r.addEventListener('change', function() {
            syncNotifExpediteur();
            updatePaymentRequiredNotif();
        });
    });

    function syncNotifExpediteur() {
        const m = document.querySelector('input[name="notif_methode_envoi"]:checked')?.value || 'email';
        document.getElementById('notifInfoEmail').classList.toggle('d-none', m !== 'email');
        document.getElementById('notifInfoWA').classList.toggle('d-none', m !== 'whatsapp');
        document.getElementById('notifInfoSMS').classList.toggle('d-none', m !== 'sms');
    }

    // ── Paiement : Notifications ─────────────────────────────────
    var notifPaymentDone  = false;
    var notifPaymentTxnId = '';

    function updatePaymentRequiredNotif() {
        var method = document.querySelector('input[name="notif_methode_envoi"]:checked')?.value;
        var needsPayment = (method === 'sms' || method === 'whatsapp');
        document.getElementById('paymentRequiredNotif').classList.toggle('d-none', !needsPayment);
        if (needsPayment) {
            document.getElementById('paymentRecipientCountNotif').value = selectedNotifDests.length;
        }
        // Reset
        notifPaymentDone  = false;
        notifPaymentTxnId = '';
        document.getElementById('paymentTransactionIdNotif').value = '';
        document.getElementById('paymentSuccessNotif').classList.add('d-none');
        document.getElementById('btnPayNowNotif').classList.remove('d-none');
        document.getElementById('costResultNotif').classList.add('d-none');
    }

    document.getElementById('btnCalculateCostNotif').addEventListener('click', function() {
        var channel = document.querySelector('input[name="notif_methode_envoi"]:checked')?.value;
        var count   = parseInt(document.getElementById('paymentRecipientCountNotif').value) || selectedNotifDests.length;
        var country = document.getElementById('paymentCountryNotif').value;

        if (count === 0) {
            Swal.fire(ENV_I18N.warning, ENV_I18N.selectRecipients, 'warning');
            return;
        }

        $.get('/messaging/quote', { channel: channel, count: count, country_code: country }, function(data) {
            if (data.status) {
                document.getElementById('paymentCountryCodeNotif').value = country;
                document.getElementById('costDisplayNotif').textContent =
                    ENV_I18N.totalToPay + ' ' + data.total.toLocaleString('fr-FR') + ' ' + data.currency +
                    ' (' + count + ' ' + (channel === 'sms' ? ENV_I18N.smsMessages : ENV_I18N.waMessages) +
                    ' ' + data.unit_cost + ' ' + data.currency + ')';
                document.getElementById('costResultNotif').classList.remove('d-none');
            } else {
                Swal.fire(ENV_I18N.error, data.message, 'error');
            }
        });
    });

    document.getElementById('btnPayNowNotif').addEventListener('click', function() {
        var btn = this;
        btn.disabled = true;
        btn.innerHTML = ENV_I18N.btnOpening;

        $.get('/platform/kkiapay-public', function(cfg) {
            if (!cfg.payment_enabled) {
                Swal.fire(ENV_I18N.error, ENV_I18N.noProvider, 'error');
                btn.disabled = false;
                btn.innerHTML = ENV_I18N.btnPay;
                return;
            }
            var amount = 0;
            var txt = document.getElementById('costDisplayNotif').textContent;
            var m = txt.match(/[\d\s,]+/);
            if (m) amount = parseInt(m[1].replace(/[\s,]/g, '')) || 0;

            var channel = document.querySelector('input[name="notif_methode_envoi"]:checked')?.value || 'sms';

            function onNotifPaySuccess(txnId) {
                notifPaymentDone  = true;
                notifPaymentTxnId = txnId;
                document.getElementById('paymentTransactionIdNotif').value = txnId;
                document.getElementById('paymentSuccessNotif').classList.remove('d-none');
                btn.classList.add('d-none');
            }
            function onNotifPayFail() {
                btn.disabled = false;
                btn.innerHTML = ENV_I18N.btnPay;
            }

            if (cfg.payment_provider === 'kkiapay') {
                if (typeof openKkiapayWidget !== 'function') {
                    Swal.fire(ENV_I18N.error, ENV_I18N.kkiapayError, 'error');
                    onNotifPayFail(); return;
                }
                var kkDone = false, kkSeen = false, kkPoll = null;
                function kkVisible() {
                    var el = document.querySelector('iframe[src*="kkiapay"]') ||
                             document.querySelector('[id*="kkiapay"]') ||
                             document.querySelector('[class*="kkiapay"]');
                    if (!el) return false;
                    var s = window.getComputedStyle(el);
                    return s.display !== 'none' && s.visibility !== 'hidden';
                }
                kkPoll = setInterval(function() {
                    var v = kkVisible();
                    if (v) { kkSeen = true; }
                    else if (kkSeen && !kkDone) { clearInterval(kkPoll); onNotifPayFail(); }
                }, 300);
                setTimeout(function() { clearInterval(kkPoll); }, 900000);

                openKkiapayWidget({ amount: amount, key: cfg.payment_public_key, sandbox: cfg.payment_sandbox });

                addSuccessListener(function(response) {
                    if (kkDone) return;
                    kkDone = true; clearInterval(kkPoll);
                    onNotifPaySuccess(response.transactionId);
                });
                addFailedListener(function() {
                    if (kkDone) return;
                    kkDone = true; clearInterval(kkPoll);
                    Swal.fire(ENV_I18N.paymentFailed, ENV_I18N.kkiapayFailed, 'error');
                    onNotifPayFail();
                });
            } else if (cfg.payment_provider === 'fedapay') {
                FedaPay.init({
                    public_key:  cfg.payment_public_key,
                    transaction: { amount: amount, description: ENV_I18N.sendPrefix + ' ' + channel.toUpperCase() },
                    onComplete: function(resp) {
                        if (resp.reason === FedaPay.DIALOG_DISMISSED) { onNotifPayFail(); return; }
                        if (resp.transaction && resp.transaction.status === 'approved') {
                            onNotifPaySuccess(resp.transaction.id.toString());
                        } else {
                            Swal.fire(ENV_I18N.paymentFailed, ENV_I18N.fedapayFailed, 'error');
                            onNotifPayFail();
                        }
                    }
                }).open();
            }
        }).fail(function() {
            btn.disabled = false;
            btn.innerHTML = ENV_I18N.btnPay;
        });
    });

    // ── Liste des destinataires dans le modal ─────────────────────
    function construireListeDestinatairesNotif() {
        const container = document.getElementById('listeDestinatairesNotif');

        if (selectedNotifDests.length === 0) {
            container.innerHTML = '<p class="text-muted text-center small mb-0">' + ENV_I18N.noRecipient + '</p>';
            return;
        }

        let html = '';
        selectedNotifDests.forEach(function(dest, idx) {
            html += `<div class="dest-row" data-idx="${idx}" data-id="${dest.id}">`;
            html += `<div class="row g-1 align-items-center">`;

            // Nom
            html += `<div class="col-12 col-md-3">`;
            html += `<span class="fw-semibold small">${escHtml(dest.nom)}</span>`;
            if (dest.logement) html += `<div class="text-muted" style="font-size:.75rem;">${escHtml(dest.logement)}</div>`;
            html += `</div>`;

            // Email
            html += `<div class="col-12 col-md-4">`;
            html += `<input type="email" class="notif-dest-email form-control form-control-sm ${!dest.email ? 'border-warning' : ''}"
                            value="${escHtml(dest.email)}"
                            placeholder="Email"
                            title="Email de ${escHtml(dest.nom)}">`;
            html += `</div>`;

            // Téléphone
            html += `<div class="col-12 col-md-4">`;
            html += `<input type="tel" class="notif-dest-tel form-control form-control-sm"
                            value="${escHtml(dest.telephone)}"
                            placeholder="+229…"
                            title="Téléphone de ${escHtml(dest.nom)}">`;
            html += `</div>`;

            html += `</div></div>`;
        });

        container.innerHTML = html;
    }

    // ── Submit Notification ───────────────────────────────────────
    document.getElementById('btnSubmitNotif').addEventListener('click', async function() {
        const methodeEl = document.querySelector('input[name="notif_methode_envoi"]:checked');
        if (!methodeEl) {
            Swal.fire(ENV_I18N.warning, ENV_I18N.chooseMethod, 'warning');
            return;
        }
        const methode = methodeEl.value;

        // Vérifier date fin bail pour préavis
        if (currentNotifType === 'preavis') {
            const dateFinVal = document.getElementById('dateFinBail').value;
            if (!dateFinVal) {
                Swal.fire(ENV_I18N.warning, ENV_I18N.specifyLeaseEnd, 'warning');
                return;
            }
        }

        if (selectedNotifDests.length === 0) {
            Swal.fire(ENV_I18N.warning, ENV_I18N.noRecipientDot, 'warning');
            return;
        }

        // Construire destinataires depuis le DOM du modal
        const rows = document.querySelectorAll('#listeDestinatairesNotif .dest-row');
        const destinataires = [];
        let erreurContact = false;

        rows.forEach(function(row) {
            const idx     = parseInt(row.dataset.idx);
            const id      = parseInt(row.dataset.id);
            const email   = row.querySelector('.notif-dest-email')?.value.trim() || '';
            const tel     = row.querySelector('.notif-dest-tel')?.value.trim()   || '';
            const contact = methode === 'email' ? email : tel;

            if (!contact) {
                const champ = methode === 'email' ? 'email' : ENV_I18N.fieldPhone;
                const nom   = selectedNotifDests[idx]?.nom || ('#' + idx);
                Swal.fire(ENV_I18N.warning, ENV_I18N.contactEmpty.replace('%s', champ).replace('%s', nom), 'warning');
                erreurContact = true;
                return;
            }

            destinataires.push({ id, contact });
        });

        if (erreurContact) return;

        // Vérifier paiement si SMS ou WhatsApp
        if ((methode === 'sms' || methode === 'whatsapp') && !notifPaymentDone) {
            Swal.fire(ENV_I18N.paymentRequired,
                methode === 'whatsapp' ? ENV_I18N.notifPayReqWa : ENV_I18N.notifPayReqSms,
                'warning');
            return;
        }

        const payload = {
            type_notification:       currentNotifType,
            methode_envoi:           methode,
            destinataires:           destinataires,
            message_personnalise:    document.getElementById('notifMessagePerso').value.trim() || null,
            date_fin_bail:           currentNotifType === 'preavis' ? document.getElementById('dateFinBail').value : null,
            payment_transaction_id:  (methode === 'sms' || methode === 'whatsapp') ? notifPaymentTxnId : null,
            country_code:            (methode === 'sms' || methode === 'whatsapp')
                                        ? document.getElementById('paymentCountryCodeNotif').value : null,
        };

        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = ENV_I18N.btnSending;

        try {
            const res  = await fetch(ROUTE_NOTIFICATION, {
                method:  'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Content-Type': 'application/json',
                    'Accept':       'application/json',
                },
                body: JSON.stringify(payload),
            });
            const json = await res.json();

            if (json.status) {
                let details = '';
                const errors = (json.details || []).filter(d => d.statut === 'error');
                if (errors.length > 0) {
                    details = '<ul class="text-start mt-2 mb-0" style="font-size:.85rem;">';
                    errors.forEach(function(d) {
                        details += `<li class="text-danger">${escHtml(d.destinataire)} : ${escHtml(d.erreur || ENV_I18N.failure)}</li>`;
                    });
                    details += '</ul>';
                }

                Swal.fire({
                    icon: errors.length > 0 ? 'warning' : 'success',
                    title: ENV_I18N.result,
                    html:  json.message + details,
                    confirmButtonText: ENV_I18N.ok,
                });

                bootstrap.Modal.getInstance(document.getElementById('modalNotification')).hide();
                chargerHistorique();

                document.querySelectorAll('.check-notif, .check-preavis').forEach(function(cb) { cb.checked = false; });
                document.getElementById('checkAllRappel').checked  = false;
                document.getElementById('checkAllPreavis').checked = false;
                selectedNotifDests = [];
                updateActionBarNotif();

            } else {
                Swal.fire({ icon: 'error', title: ENV_I18N.error, text: json.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: ENV_I18N.error, text: err.message });
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    });

    // ── Recherche en temps réel (sections notifications) ─────────
    filtreTable('searchRappel',  'tableRappel');
    filtreTable('searchPreavis', 'tablePreavis');

    // ── Charger les pays disponibles pour les dropdowns de paiement ──
    (function loadMessagingCountries() {
        // On pré-charge quelques pays communs d'Afrique de l'Ouest + récupère les options via AJAX
        var commonCountries = [
            { code: 'BJ', name: 'Bénin' },
            { code: 'TG', name: 'Togo' },
            { code: 'CI', name: "Côte d'Ivoire" },
            { code: 'SN', name: 'Sénégal' },
            { code: 'ML', name: 'Mali' },
            { code: 'BF', name: 'Burkina Faso' },
            { code: 'NE', name: 'Niger' },
            { code: 'GN', name: 'Guinée' },
            { code: 'NG', name: 'Nigeria' },
            { code: 'GH', name: 'Ghana' },
            { code: 'CM', name: 'Cameroun' },
        ];
        var selectors = ['#paymentCountryDoc', '#paymentCountryNotif'];
        selectors.forEach(function(sel) {
            var el = document.querySelector(sel);
            if (!el) return;
            el.innerHTML = '';
            commonCountries.forEach(function(c) {
                var opt = document.createElement('option');
                opt.value       = c.code;
                opt.textContent = c.name;
                if (c.code === 'BJ') opt.selected = true;
                el.appendChild(opt);
            });
        });
    })();
</script>
@endpush

@endsection
