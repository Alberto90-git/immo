@extends('layouts.template')

@section('content')
@section('title')
    <title>Envoi de Documents</title>
@endsection

<style>
    .swal2-container { z-index: 10070 !important; }
    .swal2-popup { z-index: 10071 !important; }
    .search-input { max-width: 300px; }
    .badge-methode { font-size: 11px; }
    .tab-section { display: none; }
    .tab-section.active { display: block; }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Accueil /</span> Envoi de Documents
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
        <div class="card-header d-flex align-items-center justify-content-between pb-0">
            <div class="card-title mb-0">
                <h5 class="m-0 me-2"><i class="bx bx-send me-1"></i>Envoi de Documents</h5>
                <small class="text-muted">Envoyez des documents aux locataires et propriétaires via Email ou WhatsApp</small>
            </div>
        </div>

        <div class="card-body">
            {{-- Onglets Locataires / Propriétaires --}}
            <ul class="nav nav-tabs mb-3" id="tabDest" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="tab-loc" data-bs-toggle="tab" data-bs-target="#pane-loc" type="button">
                        <i class="bx bx-user-check me-1"></i>Locataires
                        <span class="badge bg-primary ms-1">{{ $locataires->count() }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="tab-proprio" data-bs-toggle="tab" data-bs-target="#pane-proprio" type="button">
                        <i class="bx bx-user-pin me-1"></i>Propriétaires
                        <span class="badge bg-success ms-1">{{ $proprietaires->count() }}</span>
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
                    <div class="table-responsive">
                        <table class="table table-hover" id="tableLoc">
                            <thead class="table-light">
                                <tr>
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
                                                    data-tel="{{ $loc->telephone }}"
                                                    data-email="{{ $loc->email }}"
                                                    data-factures='@json($facturesParLocataire[$loc->id] ?? [])'>
                                                <i class="bx bx-send me-1"></i>Envoyer
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6" class="text-center text-muted">Aucun locataire actif trouvé.</td></tr>
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
                    <div class="table-responsive">
                        <table class="table table-hover" id="tableProprio">
                            <thead class="table-light">
                                <tr>
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
                                                    data-tel="{{ $proprio->telephone }}"
                                                    data-email="{{ $proprio->email }}"
                                                    data-factures='[]'>
                                                <i class="bx bx-send me-1"></i>Envoyer
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">Aucun propriétaire trouvé.</td></tr>
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
        <div class="card-header d-flex align-items-center justify-content-between">
            <h5 class="mb-0"><i class="bx bx-history me-1"></i>Historique des envois récents</h5>
            <button class="btn btn-sm btn-outline-secondary" id="btnRefreshHistorique">
                <i class="bx bx-refresh"></i>
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm" id="tableHistorique">
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
        </div>
    </div>
</div>

{{-- ===== MODAL ENVOI ===== --}}
<div class="modal fade" id="modalEnvoiDocument" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bx bx-send me-1"></i>Envoyer un document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formEnvoi">
                    @csrf
                    <input type="hidden" id="envoiType" name="destinataire_type">
                    <input type="hidden" id="envoiId" name="destinataire_id">

                    {{-- Destinataire --}}
                    <div class="d-flex align-items-center gap-2 mb-3 p-2 rounded bg-light">
                        <i class="bx bx-user-circle fs-5 text-primary"></i>
                        <div>
                            <div class="fw-semibold" id="envoiNom"></div>
                            <small class="text-muted" id="envoiInfoContact"></small>
                        </div>
                    </div>

                    {{-- Méthode d'envoi --}}
                    <div class="mb-3">
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
                    </div>

                    {{-- Bloc WhatsApp : expéditeur + numéro destinataire modifiable --}}
                    <div id="blocWhatsApp" class="d-none mb-3 p-3 rounded" style="background:rgba(37,211,102,.07);border:1px solid rgba(37,211,102,.25);">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <i class="bx bxl-whatsapp text-success fs-5"></i>
                            <small class="text-muted">
                                Expéditeur :
                                <strong class="text-dark">{{ $parametre?->whatsapp_numero_envoi ?: 'Non configuré (voir Paramétrage)' }}</strong>
                            </small>
                        </div>
                        <label class="form-label fw-semibold mb-1" for="telephoneWA">
                            Numéro WhatsApp du destinataire
                            <small class="text-muted fw-normal">(vérifiez ou corrigez)</small>
                        </label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text"><i class="bx bx-phone"></i></span>
                            <input type="tel" class="form-control" id="telephoneWA" name="telephone_override"
                                   placeholder="+22960000000">
                        </div>
                        <small class="text-muted">Format international recommandé : +229…</small>
                    </div>

                    {{-- Type de document --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="typeDocument">Type de document</label>
                        <select class="form-select" id="typeDocument" name="type_document" required>
                            <option value="">— Choisir un document —</option>
                        </select>
                    </div>

                    {{-- Bloc quittance mensuelle : sélection facture --}}
                    <div class="mb-3 d-none" id="blocFacture">
                        <label class="form-label fw-semibold" for="factureId">Facture (mois)</label>
                        <select class="form-select" id="factureId" name="facture_id">
                            <option value="">— Choisir une facture —</option>
                        </select>
                    </div>

                    {{-- Bloc relevé : dates + pourcentage --}}
                    <div class="mb-3 d-none" id="blocReleve">
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label fw-semibold" for="dateDebut">Date début</label>
                                <input type="date" class="form-control" id="dateDebut" name="date_debut">
                            </div>
                            <div class="col-6">
                                <label class="form-label fw-semibold" for="dateFin">Date fin</label>
                                <input type="date" class="form-control" id="dateFin" name="date_fin">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold" for="pourcentage">Pourcentage de gestion (%)</label>
                                <input type="number" class="form-control" id="pourcentage" name="pourcentage"
                                       value="10" min="0" max="100" step="0.5">
                            </div>
                        </div>
                    </div>

                    {{-- Message personnalisé --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="messagePerso">Message personnalisé (optionnel)</label>
                        <textarea class="form-control" id="messagePerso" name="message_personnalise"
                                  rows="2" maxlength="1000"
                                  placeholder="Ajoutez un message personnalisé…"></textarea>
                    </div>
                </form>
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

@push('scripts')
<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    const ROUTE_ENVOYER   = '{{ route("envoi_document.envoyer") }}';
    const ROUTE_HISTORIQUE = '{{ route("envoi_document.historique") }}';

    const docTypesLocataire = [
        { value: 'contrat',            label: 'Contrat de location' },
        { value: 'quittance_caution',  label: 'Quittance de caution' },
        { value: 'quittance_mensuelle', label: 'Quittance mensuelle' },
    ];

    const docTypesProprietaire = [
        { value: 'releve_proprietaire', label: 'Relevé propriétaire' },
        { value: 'releve_agence',       label: 'Relevé agence' },
    ];

    let currentFactures = [];

    // ---- Afficher/cacher le bloc WhatsApp selon méthode sélectionnée ----
    document.querySelectorAll('input[name="methode_envoi"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const blocWA = document.getElementById('blocWhatsApp');
            blocWA.classList.toggle('d-none', this.value !== 'whatsapp');
        });
    });

    // ---- Boutons Envoyer ----
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-envoyer');
        if (!btn) return;

        const type     = btn.dataset.type;
        const id       = btn.dataset.id;
        const nom      = btn.dataset.nom;
        const email    = btn.dataset.email || '';
        const tel      = btn.dataset.tel   || '';
        currentFactures = JSON.parse(btn.dataset.factures || '[]');

        document.getElementById('envoiType').value = type;
        document.getElementById('envoiId').value   = id;
        document.getElementById('envoiNom').textContent = nom;

        // Info contact sous le nom
        const infoEl = document.getElementById('envoiInfoContact');
        const parts = [];
        if (tel)   parts.push('📞 ' + tel);
        if (email) parts.push('✉ ' + email);
        infoEl.textContent = parts.join('  |  ') || 'Aucun contact renseigné';

        // Pré-remplir le numéro WhatsApp avec le tel du destinataire
        document.getElementById('telephoneWA').value = tel;

        // Remplir le select type document
        const sel = document.getElementById('typeDocument');
        sel.innerHTML = '<option value="">— Choisir un document —</option>';
        const docs = type === 'locataire' ? docTypesLocataire : docTypesProprietaire;
        docs.forEach(d => {
            const opt = document.createElement('option');
            opt.value = d.value;
            opt.textContent = d.label;
            sel.appendChild(opt);
        });

        // Reset blocs conditionnels
        document.getElementById('blocFacture').classList.add('d-none');
        document.getElementById('blocReleve').classList.add('d-none');
        document.getElementById('blocWhatsApp').classList.add('d-none');
        document.getElementById('messagePerso').value = '';

        // Pré-sélectionner la méthode disponible
        const emailRadio = document.getElementById('methodeEmail');
        const waRadio    = document.getElementById('methodeWhatsApp');
        if (!emailRadio.disabled) {
            emailRadio.checked = true;
        } else if (!waRadio.disabled) {
            waRadio.checked = true;
            document.getElementById('blocWhatsApp').classList.remove('d-none');
        }

        new bootstrap.Modal(document.getElementById('modalEnvoiDocument')).show();
    });

    // ---- Select type document → blocs conditionnels ----
    document.getElementById('typeDocument').addEventListener('change', function() {
        const val = this.value;
        const blocFact  = document.getElementById('blocFacture');
        const blocRel   = document.getElementById('blocReleve');
        const factSel   = document.getElementById('factureId');

        blocFact.classList.add('d-none');
        blocRel.classList.add('d-none');

        if (val === 'quittance_mensuelle') {
            blocFact.classList.remove('d-none');
            factSel.innerHTML = '<option value="">— Choisir une facture —</option>';
            currentFactures.forEach(f => {
                const opt = document.createElement('option');
                opt.value = f.id;
                opt.textContent = f.mois + ' — ' + Number(f.montant).toLocaleString('fr-FR') + ' XOF';
                factSel.appendChild(opt);
            });
        } else if (val === 'releve_proprietaire' || val === 'releve_agence') {
            blocRel.classList.remove('d-none');
        }
    });

    // ---- Soumettre envoi ----
    document.getElementById('btnSubmitEnvoi').addEventListener('click', async function() {
        const form = document.getElementById('formEnvoi');
        const data = new FormData(form);

        // Vérifications rapides
        const typeDoc = data.get('type_document');
        if (!typeDoc) {
            Swal.fire('Attention', 'Veuillez choisir un type de document.', 'warning');
            return;
        }
        const methode = document.querySelector('input[name="methode_envoi"]:checked');
        if (!methode) {
            Swal.fire('Attention', 'Veuillez choisir une méthode d\'envoi.', 'warning');
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Envoi…';

        try {
            const res = await fetch(ROUTE_ENVOYER, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF },
                body: data,
            });
            const json = await res.json();

            if (json.status) {
                Swal.fire({ icon: 'success', title: 'Envoyé !', text: json.message, timer: 3000, showConfirmButton: false });
                bootstrap.Modal.getInstance(document.getElementById('modalEnvoiDocument')).hide();
                chargerHistorique();
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

    // ---- Recherche en temps réel ----
    function filtreTable(inputId, tableId) {
        document.getElementById(inputId).addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#' + tableId + ' tbody tr').forEach(tr => {
                tr.style.display = tr.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    }
    filtreTable('searchLoc', 'tableLoc');
    filtreTable('searchProprio', 'tableProprio');

    // ---- Historique ----
    const labels = {
        contrat: 'Contrat',
        quittance_mensuelle: 'Quittance mensuelle',
        quittance_caution: 'Quittance caution',
        releve_proprietaire: 'Relevé propriétaire',
        releve_agence: 'Relevé agence',
    };

    async function chargerHistorique() {
        const tbody = document.getElementById('tbodyHistorique');
        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-3"><span class="spinner-border spinner-border-sm"></span></td></tr>';
        try {
            const res  = await fetch(ROUTE_HISTORIQUE);
            const json = await res.json();
            if (!json.data || json.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">Aucun envoi enregistré.</td></tr>';
                return;
            }
            tbody.innerHTML = json.data.map(e => `
                <tr>
                    <td><small>${new Date(e.created_at).toLocaleString('fr-FR')}</small></td>
                    <td>${e.destinataire_nom}</td>
                    <td>${labels[e.type_document] || e.type_document}</td>
                    <td>
                        <span class="badge ${e.methode_envoi === 'email' ? 'bg-primary' : 'bg-success'}">
                            ${e.methode_envoi === 'email' ? '📧 Email' : '💬 WhatsApp'}
                        </span>
                    </td>
                    <td><small>${e.destinataire_contact || '—'}</small></td>
                    <td>
                        <span class="badge ${e.statut === 'success' ? 'bg-label-success' : 'bg-label-danger'}">
                            ${e.statut === 'success' ? 'Succès' : 'Échec'}
                        </span>
                    </td>
                </tr>
            `).join('');
        } catch (err) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger py-3">Erreur de chargement.</td></tr>';
        }
    }

    document.getElementById('btnRefreshHistorique').addEventListener('click', chargerHistorique);
    chargerHistorique();

    // --- Statut WhatsApp (enable/disable radio) ---
    (function checkWaStatus() {
        fetch('{{ route("whatsapp.status") }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var radio = document.getElementById('methodeWhatsApp');
                var badge = document.getElementById('wa-envoi-badge');
                if (data.status === 'connected') {
                    if (radio) radio.disabled = false;
                    if (badge) { badge.textContent = 'Connecté'; badge.className = 'badge bg-success ms-1'; badge.style.fontSize = '.7rem'; }
                } else {
                    if (radio) radio.disabled = true;
                    if (badge) { badge.textContent = 'Non connecté'; badge.className = 'badge bg-danger ms-1'; badge.style.fontSize = '.7rem'; }
                }
            })
            .catch(function() {
                var badge = document.getElementById('wa-envoi-badge');
                if (badge) { badge.textContent = 'Service absent'; badge.className = 'badge bg-secondary ms-1'; badge.style.fontSize = '.7rem'; }
            });
    })();
</script>
@endpush

@endsection
