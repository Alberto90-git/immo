@extends('layouts.template')

@section('content')
@section('title')
    <title>Communications</title>
@endsection

<style>
    .swal2-container { z-index: 10070 !important; }
    .swal2-popup    { z-index: 10071 !important; }
    .search-input   { max-width: 300px; }

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
        <span class="text-muted fw-light">Accueil /</span> Communications
    </h4>

    @include('notification.display_message')

    {{-- Alerte si aucune config communication --}}
    @if (!$parametre || !$parametre->email_envoi)
        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
            <i class="bx bx-error-circle me-2 fs-5"></i>
            <div>
                L'adresse email d'envoi n'est pas configurée.
                <a href="{{ route('parametrage') }}" class="alert-link">Configurer dans Paramétrage → Communication</a>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between pb-0 flex-wrap gap-2">
            <div class="card-title mb-0">
                <h5 class="m-0 me-2"><i class="bx bx-message-dots me-1"></i>Envoi de Documents</h5>
                <small class="text-muted">Envoyez des documents aux locataires et propriétaires via Email ou WhatsApp</small>
            </div>
            {{-- Barre d'action sélection multiple (cachée par défaut) --}}
            <div id="actionBar" class="d-none d-flex align-items-center gap-2">
                <span class="text-muted small" id="selectionLabel">0 sélectionné(s)</span>
                <button class="btn btn-primary btn-sm" id="btnEnvoyerSelection">
                    <i class="bx bx-send me-1"></i>Envoyer la sélection (<span id="selectionCount">0</span>)
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
                        Locataires
                        <span class="badge ms-1">{{ $locataires->count() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-proprio"
                            data-bs-toggle="tab" data-bs-target="#pane-proprio"
                            type="button" role="tab">
                        <i class="bx bx-building-house"></i>
                        Propriétaires
                        <span class="badge ms-1">{{ $proprietaires->count() }}</span>
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                {{-- ===== ONGLET LOCATAIRES ===== --}}
                <div class="tab-pane fade show active" id="pane-loc">
                    <div class="mb-3">
                        <input type="text" id="searchLoc" class="form-control search-input"
                               placeholder="Rechercher un locataire…">
                    </div>
                    <div class="table-scroll-wrapper">
                        <table class="table table-hover" id="tableLoc">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;">
                                        <input type="checkbox" id="checkAllLoc" class="form-check-input" title="Tout sélectionner">
                                    </th>
                                    <th>Nom</th>
                                    <th>Maison / Chambre</th>
                                    <th>Téléphone</th>
                                    <th>Email</th>
                                    <th>Documents disponibles</th>
                                    <th>Action</th>
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
                                            <span class="badge bg-label-primary me-1">Contrat</span>
                                            <span class="badge bg-label-info me-1">Caution</span>
                                            @if(isset($facturesParLocataire[$loc->id]) && $facturesParLocataire[$loc->id]->isNotEmpty())
                                                <span class="badge bg-label-success">
                                                    {{ $facturesParLocataire[$loc->id]->count() }} quittance(s)
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
                                                <i class="bx bx-send me-1"></i>Envoyer
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted">Aucun locataire actif trouvé.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ===== ONGLET PROPRIÉTAIRES ===== --}}
                <div class="tab-pane fade" id="pane-proprio">
                    <div class="mb-3">
                        <input type="text" id="searchProprio" class="form-control search-input"
                               placeholder="Rechercher un propriétaire…">
                    </div>
                    <div class="table-scroll-wrapper">
                        <table class="table table-hover" id="tableProprio">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;">
                                        <input type="checkbox" id="checkAllProprio" class="form-check-input" title="Tout sélectionner">
                                    </th>
                                    <th>Nom</th>
                                    <th>Téléphone</th>
                                    <th>Email</th>
                                    <th>Documents disponibles</th>
                                    <th>Action</th>
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
                                            <span class="badge bg-label-warning me-1">Relevé propriétaire</span>
                                            <span class="badge bg-label-secondary">Relevé agence</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-success btn-envoyer"
                                                    data-type="proprietaire"
                                                    data-id="{{ $proprio->id }}"
                                                    data-nom="{{ $proprio->nom }} {{ $proprio->prenom }}"
                                                    data-tel="{{ $proprio->telephone ?? '' }}"
                                                    data-email="{{ $proprio->email ?? '' }}"
                                                    data-factures='[]'>
                                                <i class="bx bx-send me-1"></i>Envoyer
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">Aucun propriétaire trouvé.</td></tr>
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
                <h5 class="m-0 me-2"><i class="bx bx-bell me-1"></i>Notifications Locataires</h5>
                <small class="text-muted">Envoyez des rappels de loyer et des préavis de fin de bail</small>
            </div>
            <div id="actionBarNotif" class="d-none d-flex align-items-center gap-2">
                <span class="text-muted small" id="selectionLabelNotif">0 sélectionné(s)</span>
                <button class="btn btn-warning btn-sm" id="btnEnvoyerRappelSelection">
                    <i class="bx bx-alarm me-1"></i>Rappel loyer (<span id="selectionCountNotif">0</span>)
                </button>
                <button class="btn btn-danger btn-sm" id="btnEnvoyerPreavisSelection">
                    <i class="bx bx-calendar-x me-1"></i>Préavis (<span id="selectionCountNotif2">0</span>)
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
                        <i class="bx bx-alarm me-1"></i>Rappel de loyer
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-preavis"
                            data-bs-toggle="tab" data-bs-target="#pane-preavis"
                            type="button" role="tab"
                            style="border:none;border-radius:9px;font-weight:500;padding:8px 20px;transition:all .22s ease;">
                        <i class="bx bx-calendar-x me-1"></i>Préavis de fin de bail
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
                               placeholder="Rechercher un locataire…">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="tableRappel">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;">
                                        <input type="checkbox" id="checkAllRappel" class="form-check-input" title="Tout sélectionner">
                                    </th>
                                    <th>Nom</th>
                                    <th>Logement</th>
                                    <th>Loyer/mois</th>
                                    <th>Téléphone</th>
                                    <th>Email</th>
                                    <th>Action</th>
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
                                                <i class="bx bx-alarm me-1"></i>Rappel
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted">Aucun locataire actif trouvé.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- ===== ONGLET PRÉAVIS ===== --}}
                <div class="tab-pane fade" id="pane-preavis">
                    <div class="mb-3">
                        <input type="text" id="searchPreavis" class="form-control search-input"
                               placeholder="Rechercher un locataire…">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover" id="tablePreavis">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:40px;">
                                        <input type="checkbox" id="checkAllPreavis" class="form-check-input" title="Tout sélectionner">
                                    </th>
                                    <th>Nom</th>
                                    <th>Logement</th>
                                    <th>Entrée le</th>
                                    <th>Téléphone</th>
                                    <th>Email</th>
                                    <th>Action</th>
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
                                                <i class="bx bx-calendar-x me-1"></i>Préavis
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted">Aucun locataire actif trouvé.</td></tr>
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
                <h5 class="mb-0"><i class="bx bx-history me-1"></i>Historique des envois récents</h5>
                <div class="d-flex align-items-center gap-2">
                    <span class="text-muted small" id="histoInfoCount"></span>
                    <button class="btn btn-sm btn-outline-secondary" id="btnRefreshHistorique" title="Actualiser">
                        <i class="bx bx-refresh"></i>
                    </button>
                </div>
            </div>
            {{-- Filtres --}}
            <div class="row g-2">
                <div class="col-12 col-md-4">
                    <input type="text" id="histoFiltreNom" class="form-control form-control-sm"
                           placeholder="Rechercher par nom ou contact…">
                </div>
                <div class="col-6 col-md-2">
                    <select id="histoFiltreDoc" class="form-select form-select-sm">
                        <option value="">Tous documents</option>
                        <option value="contrat">Contrat</option>
                        <option value="quittance_mensuelle">Quittance mensuelle</option>
                        <option value="quittance_caution">Quittance caution</option>
                        <option value="releve_proprietaire">Relevé propriétaire</option>
                        <option value="releve_agence">Relevé agence</option>
                        <option value="rappel_loyer">Rappel de loyer</option>
                        <option value="preavis">Préavis</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select id="histoFiltreMethode" class="form-select form-select-sm">
                        <option value="">Toutes méthodes</option>
                        <option value="email">Email</option>
                        <option value="whatsapp">WhatsApp</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <select id="histoFiltreStatut" class="form-select form-select-sm">
                        <option value="">Tous statuts</option>
                        <option value="success">Succès</option>
                        <option value="error">Échec</option>
                    </select>
                </div>
                <div class="col-6 col-md-2">
                    <button class="btn btn-sm btn-outline-secondary w-100" id="histoBtnReset">
                        <i class="bx bx-x me-1"></i>Réinitialiser
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0" id="tableHistorique">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th>Destinataire</th>
                            <th>Document</th>
                            <th>Méthode</th>
                            <th>Contact</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyHistorique">
                        <tr><td colspan="6" class="text-center text-muted py-3">Chargement…</td></tr>
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
                <h5 class="modal-title"><i class="bx bx-send me-1"></i>Envoyer des documents</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Section 1 — Méthode d'envoi --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Méthode d'envoi</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="methode_envoi"
                                   id="methodeEmail" value="email"
                                   {{ (!$parametre || !$parametre->email_envoi) ? 'disabled' : '' }} checked>
                            <label class="form-check-label" for="methodeEmail">
                                <i class="bx bx-envelope me-1"></i>Email
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="methode_envoi"
                                   id="methodeWhatsApp" value="whatsapp" disabled>
                            <label class="form-check-label" for="methodeWhatsApp">
                                <i class="bx bxl-whatsapp me-1 text-success"></i>WhatsApp
                                <span id="wa-envoi-badge" class="badge bg-secondary ms-1" style="font-size:.7rem;">Vérification…</span>
                            </label>
                        </div>
                    </div>
                    {{-- Info expéditeur --}}
                    <div id="infoExpediteurEmail" class="mt-2" style="font-size:.85rem;">
                        <i class="bx bx-envelope text-primary me-1"></i>
                        Expéditeur : <strong>{{ $parametre?->email_envoi ?: 'Non configuré (voir Paramétrage)' }}</strong>
                    </div>
                    <div id="infoExpediteurWA" class="mt-2 d-none" style="font-size:.85rem;">
                        <i class="bx bxl-whatsapp text-success me-1"></i>
                        Expéditeur : <strong>{{ $parametre?->whatsapp_numero_envoi ?: 'Non configuré (voir Paramétrage)' }}</strong>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Section 2 — Documents à envoyer --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Documents à envoyer</label>
                    <div id="blocDocsLocataire">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input check-doc" type="checkbox" id="docContrat" value="contrat">
                            <label class="form-check-label" for="docContrat">Contrat de location</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input check-doc" type="checkbox" id="docCaution" value="quittance_caution">
                            <label class="form-check-label" for="docCaution">Quittance de caution</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input check-doc" type="checkbox" id="docMensuelle" value="quittance_mensuelle">
                            <label class="form-check-label" for="docMensuelle">Quittance mensuelle</label>
                        </div>
                    </div>
                    <div id="blocDocsProprio" class="d-none">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input check-doc" type="checkbox" id="docReleveP" value="releve_proprietaire">
                            <label class="form-check-label" for="docReleveP">Relevé propriétaire</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input check-doc" type="checkbox" id="docReleveA" value="releve_agence">
                            <label class="form-check-label" for="docReleveA">Relevé agence</label>
                        </div>
                    </div>

                    {{-- Bloc dates (pour relevés) --}}
                    <div id="blocReleve" class="mt-3 d-none p-3 rounded" style="background:rgba(255,193,7,.07);border:1px solid rgba(255,193,7,.3);">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small" for="dateDebut">Date début</label>
                                <input type="date" class="form-control form-control-sm" id="dateDebut">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small" for="dateFin">Date fin</label>
                                <input type="date" class="form-control form-control-sm" id="dateFin">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small" for="pourcentage">Pourcentage (%)</label>
                                <input type="number" class="form-control form-control-sm" id="pourcentage"
                                       value="10" min="0" max="100" step="0.5">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Section 3 — Destinataires sélectionnés --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Destinataires sélectionnés</label>
                    <div id="listeDestinataires" class="border rounded p-2">
                        <p class="text-muted text-center small mb-0">Aucun destinataire sélectionné</p>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Section 4 — Message personnalisé --}}
                <div class="mb-2">
                    <label class="form-label fw-semibold" for="messagePerso">Message personnalisé <small class="text-muted fw-normal">(optionnel)</small></label>
                    <textarea class="form-control" id="messagePerso" rows="2" maxlength="1000"
                              placeholder="Ajoutez un message personnalisé…"></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="btnSubmitEnvoi">
                    <i class="bx bx-send me-1"></i>Envoyer
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
                <h5 class="modal-title" id="notifModalTitle"><i class="bx bx-bell me-1"></i>Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Méthode d'envoi --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Méthode d'envoi</label>
                    <div class="d-flex gap-3">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="notif_methode_envoi"
                                   id="notifMethodeEmail" value="email"
                                   {{ (!$parametre || !$parametre->email_envoi) ? 'disabled' : '' }} checked>
                            <label class="form-check-label" for="notifMethodeEmail">
                                <i class="bx bx-envelope me-1"></i>Email
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="notif_methode_envoi"
                                   id="notifMethodeWhatsApp" value="whatsapp" disabled>
                            <label class="form-check-label" for="notifMethodeWhatsApp">
                                <i class="bx bxl-whatsapp me-1 text-success"></i>WhatsApp
                                <span id="wa-notif-badge" class="badge bg-secondary ms-1" style="font-size:.7rem;">Vérification…</span>
                            </label>
                        </div>
                    </div>
                    <div id="notifInfoEmail" class="mt-2" style="font-size:.85rem;">
                        <i class="bx bx-envelope text-primary me-1"></i>
                        Expéditeur : <strong>{{ $parametre?->email_envoi ?: 'Non configuré (voir Paramétrage)' }}</strong>
                    </div>
                    <div id="notifInfoWA" class="mt-2 d-none" style="font-size:.85rem;">
                        <i class="bx bxl-whatsapp text-success me-1"></i>
                        Expéditeur : <strong>{{ $parametre?->whatsapp_numero_envoi ?: 'Non configuré (voir Paramétrage)' }}</strong>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Champ date fin de bail (Préavis uniquement) --}}
                <div id="blocDateFinBail" class="mb-4 d-none">
                    <label class="form-label fw-semibold" for="dateFinBail">
                        <i class="bx bx-calendar-x text-danger me-1"></i>Date de fin de bail <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control" id="dateFinBail" style="max-width:240px;">
                    <div class="form-text">Date à laquelle le locataire devra libérer le logement.</div>
                </div>

                <hr class="my-3 d-none" id="hrDateFin">

                {{-- Destinataires --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold">Destinataires sélectionnés</label>
                    <div id="listeDestinatairesNotif" class="border rounded p-2" style="max-height:280px;overflow-y:auto;">
                        <p class="text-muted text-center small mb-0">Aucun destinataire sélectionné</p>
                    </div>
                </div>

                <hr class="my-3">

                {{-- Message personnalisé --}}
                <div class="mb-2">
                    <label class="form-label fw-semibold" for="notifMessagePerso">
                        Message complémentaire <small class="text-muted fw-normal">(optionnel)</small>
                    </label>
                    <textarea class="form-control" id="notifMessagePerso" rows="3" maxlength="2000"
                              placeholder="Ajoutez des informations supplémentaires…"></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-warning" id="btnSubmitNotif">
                    <i class="bx bx-send me-1"></i>Envoyer la notification
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const CSRF             = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    const ROUTE_ENVOYER    = '{{ route("envoi_document.envoyer") }}';
    const ROUTE_HISTORIQUE = '{{ route("envoi_document.historique") }}';

    // ── État global ─────────────────────────────────────────────
    let selectedDestinataires = [];
    // Dernier type ouvert dans le modal ('locataire' | 'proprietaire')
    let currentModalType = 'locataire';

    // ── Helpers ──────────────────────────────────────────────────
    function updateActionBar() {
        const count = selectedDestinataires.length;
        document.getElementById('selectionCount').textContent = count;
        document.getElementById('selectionLabel').textContent = count + ' sélectionné(s)';
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

        new bootstrap.Modal(document.getElementById('modalEnvoiDocument')).show();
    }

    // ── Construction de la liste des destinataires dans le modal ─
    function construireListeDestinataires(avecColonneFacture) {
        const container = document.getElementById('listeDestinataires');

        if (selectedDestinataires.length === 0) {
            container.innerHTML = '<p class="text-muted text-center small mb-0">Aucun destinataire sélectionné</p>';
            return;
        }

        const methode = document.querySelector('input[name="methode_envoi"]:checked')?.value || 'email';
        let html = '';

        selectedDestinataires.forEach(function(dest, idx) {
            const emailEmpty = !dest.email;
            const emailClass = emailEmpty ? 'border-warning' : '';
            const emailPlaceholder = emailEmpty ? 'Email requis' : '';

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
                html += `<label class="form-label small fw-semibold mb-1">Mois à envoyer :</label>`;
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
                html += `<small class="text-muted fst-italic">Aucune facture disponible</small>`;
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
        });
    });

    function syncInfoExpediteur() {
        const methode = document.querySelector('input[name="methode_envoi"]:checked')?.value || 'email';
        document.getElementById('infoExpediteurEmail').classList.toggle('d-none', methode !== 'email');
        document.getElementById('infoExpediteurWA').classList.toggle('d-none', methode !== 'whatsapp');
    }

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
            Swal.fire('Attention', 'Veuillez choisir une méthode d\'envoi.', 'warning');
            return;
        }
        const methode = methodeEl.value;

        // Récupérer les documents cochés
        const typeDocuments = [];
        document.querySelectorAll('.check-doc:checked').forEach(function(cb) {
            typeDocuments.push(cb.value);
        });
        if (typeDocuments.length === 0) {
            Swal.fire('Attention', 'Veuillez sélectionner au moins un document à envoyer.', 'warning');
            return;
        }

        if (selectedDestinataires.length === 0) {
            Swal.fire('Attention', 'Aucun destinataire sélectionné.', 'warning');
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
                const champ = methode === 'email' ? 'email' : 'téléphone';
                Swal.fire('Attention', `Le ${champ} de "${selectedDestinataires[idx]?.nom || 'destinataire #' + idx}" est vide.`, 'warning');
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

        const payload = {
            methode_envoi:        methode,
            type_documents:       typeDocuments,
            destinataires:        destinataires,
            message_personnalise: msgPerso,
            date_debut:           dateDebut,
            date_fin:             dateFin,
            pourcentage:          pourcentage ? parseFloat(pourcentage) : null,
        };

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Envoi en cours…';

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
                            details += `<li class="text-danger">${escHtml(d.destinataire)} — ${escHtml(d.document)} : ${escHtml(d.erreur || 'Échec')}</li>`;
                        });
                        details += '</ul>';
                    }
                }

                Swal.fire({
                    icon: errors.length > 0 ? 'warning' : 'success',
                    title: 'Résultat',
                    html:  json.message + details,
                    confirmButtonText: 'OK',
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
                Swal.fire({ icon: 'error', title: 'Erreur', text: json.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Erreur réseau', text: err.message });
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-send me-1"></i>Envoyer';
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
        contrat:             'Contrat',
        quittance_mensuelle: 'Quittance mensuelle',
        quittance_caution:   'Quittance caution',
        releve_proprietaire: 'Relevé propriétaire',
        releve_agence:       'Relevé agence',
        rappel_loyer:        'Rappel de loyer',
        preavis:             'Préavis',
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
                ? `${total} résultat(s) sur ${allHistoriqueData.length}`
                : `${allHistoriqueData.length} envoi(s) au total`;
        }

        if (total === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">Aucun résultat.</td></tr>';
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
                : '<span class="badge bg-success"><i class="bx bxl-whatsapp me-1"></i>WhatsApp</span>';
            const statBadge = e.statut === 'success'
                ? '<span class="badge bg-label-success">Succès</span>'
                : `<span class="badge bg-label-danger" title="${escHtml(e.message_erreur || '')}">Échec</span>`;

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
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-3">Erreur de chargement.</td></tr>';
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

    // ── Statut WhatsApp ──────────────────────────────────────────
    (function checkWaStatus() {
        fetch('{{ route("whatsapp.status") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var radio      = document.getElementById('methodeWhatsApp');
                var badge      = document.getElementById('wa-envoi-badge');
                var radioNotif = document.getElementById('notifMethodeWhatsApp');
                var badgeNotif = document.getElementById('wa-notif-badge');
                if (data.status === 'connected') {
                    if (radio)      radio.disabled = false;
                    if (radioNotif) radioNotif.disabled = false;
                    if (badge)      { badge.textContent = 'Connecté'; badge.className = 'badge bg-success ms-1'; badge.style.fontSize = '.7rem'; }
                    if (badgeNotif) { badgeNotif.textContent = 'Connecté'; badgeNotif.className = 'badge bg-success ms-1'; badgeNotif.style.fontSize = '.7rem'; }
                } else {
                    if (radio)      radio.disabled = true;
                    if (radioNotif) radioNotif.disabled = true;
                    if (badge)      { badge.textContent = 'Non connecté'; badge.className = 'badge bg-danger ms-1'; badge.style.fontSize = '.7rem'; }
                    if (badgeNotif) { badgeNotif.textContent = 'Non connecté'; badgeNotif.className = 'badge bg-danger ms-1'; badgeNotif.style.fontSize = '.7rem'; }
                }
            })
            .catch(function() {
                var badge      = document.getElementById('wa-envoi-badge');
                var badgeNotif = document.getElementById('wa-notif-badge');
                if (badge)      { badge.textContent = 'Service absent'; badge.className = 'badge bg-secondary ms-1'; badge.style.fontSize = '.7rem'; }
                if (badgeNotif) { badgeNotif.textContent = 'Service absent'; badgeNotif.className = 'badge bg-secondary ms-1'; badgeNotif.style.fontSize = '.7rem'; }
            });
    })();

    // ═══════════════════════════════════════════════════════════
    // NOTIFICATIONS LOCATAIRES
    // ═══════════════════════════════════════════════════════════

    const ROUTE_NOTIFICATION = '{{ route("envoi_document.notification") }}';

    let selectedNotifDests = [];
    let currentNotifType   = 'rappel_loyer'; // 'rappel_loyer' | 'preavis'

    // Labels historique enrichis
    labels['rappel_loyer'] = 'Rappel de loyer';
    labels['preavis']      = 'Préavis';

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
        document.getElementById('selectionLabelNotif').textContent  = count + ' sélectionné(s)';
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
            title.innerHTML  = '<i class="bx bx-calendar-x me-1"></i>Préavis de fin de bail';
            btnSubmit.className = 'btn btn-danger';
            btnSubmit.innerHTML = '<i class="bx bx-send me-1"></i>Envoyer le préavis';
        } else {
            header.style.background = '#f59e0b';
            title.style.color = '#fff';
            title.innerHTML  = '<i class="bx bx-alarm me-1"></i>Rappel de paiement de loyer';
            btnSubmit.className = 'btn btn-warning';
            btnSubmit.innerHTML = '<i class="bx bx-send me-1"></i>Envoyer le rappel';
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
        if (!emailR.disabled) emailR.checked = true;
        else if (!waR.disabled) waR.checked = true;
        syncNotifExpediteur();

        construireListeDestinatairesNotif();

        new bootstrap.Modal(document.getElementById('modalNotification')).show();
    }

    // ── Sync expéditeur ───────────────────────────────────────────
    document.querySelectorAll('input[name="notif_methode_envoi"]').forEach(function(r) {
        r.addEventListener('change', syncNotifExpediteur);
    });

    function syncNotifExpediteur() {
        const m = document.querySelector('input[name="notif_methode_envoi"]:checked')?.value || 'email';
        document.getElementById('notifInfoEmail').classList.toggle('d-none', m !== 'email');
        document.getElementById('notifInfoWA').classList.toggle('d-none', m !== 'whatsapp');
    }

    // ── Liste des destinataires dans le modal ─────────────────────
    function construireListeDestinatairesNotif() {
        const container = document.getElementById('listeDestinatairesNotif');

        if (selectedNotifDests.length === 0) {
            container.innerHTML = '<p class="text-muted text-center small mb-0">Aucun destinataire sélectionné</p>';
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
            Swal.fire('Attention', 'Veuillez choisir une méthode d\'envoi.', 'warning');
            return;
        }
        const methode = methodeEl.value;

        // Vérifier date fin bail pour préavis
        if (currentNotifType === 'preavis') {
            const dateFinVal = document.getElementById('dateFinBail').value;
            if (!dateFinVal) {
                Swal.fire('Attention', 'Veuillez indiquer la date de fin de bail.', 'warning');
                return;
            }
        }

        if (selectedNotifDests.length === 0) {
            Swal.fire('Attention', 'Aucun destinataire sélectionné.', 'warning');
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
                const champ = methode === 'email' ? 'email' : 'téléphone';
                Swal.fire('Attention', `Le ${champ} de "${selectedNotifDests[idx]?.nom || '#' + idx}" est vide.`, 'warning');
                erreurContact = true;
                return;
            }

            destinataires.push({ id, contact });
        });

        if (erreurContact) return;

        const payload = {
            type_notification:    currentNotifType,
            methode_envoi:        methode,
            destinataires:        destinataires,
            message_personnalise: document.getElementById('notifMessagePerso').value.trim() || null,
            date_fin_bail:        currentNotifType === 'preavis' ? document.getElementById('dateFinBail').value : null,
        };

        const btn = this;
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Envoi en cours…';

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
                        details += `<li class="text-danger">${escHtml(d.destinataire)} : ${escHtml(d.erreur || 'Échec')}</li>`;
                    });
                    details += '</ul>';
                }

                Swal.fire({
                    icon: errors.length > 0 ? 'warning' : 'success',
                    title: 'Résultat',
                    html:  json.message + details,
                    confirmButtonText: 'OK',
                });

                bootstrap.Modal.getInstance(document.getElementById('modalNotification')).hide();
                chargerHistorique();

                document.querySelectorAll('.check-notif, .check-preavis').forEach(function(cb) { cb.checked = false; });
                document.getElementById('checkAllRappel').checked  = false;
                document.getElementById('checkAllPreavis').checked = false;
                selectedNotifDests = [];
                updateActionBarNotif();

            } else {
                Swal.fire({ icon: 'error', title: 'Erreur', text: json.message });
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Erreur réseau', text: err.message });
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
        }
    });

    // ── Recherche en temps réel (sections notifications) ─────────
    filtreTable('searchRappel',  'tableRappel');
    filtreTable('searchPreavis', 'tablePreavis');
</script>
@endpush

@endsection
