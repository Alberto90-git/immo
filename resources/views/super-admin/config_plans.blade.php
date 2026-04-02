@extends('layouts.template')

@section('title')
  <title>Configuration des plans d'abonnement</title>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Super Admin /</span> Plans d'abonnement
    </h4>

    {{-- En-tête + bouton nouveau plan --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-0">
                Configurez les limites et tarifs de chaque plan. Laissez un champ de limite vide pour
                <span class="badge bg-label-success">illimité</span>.
                Zéro <span class="badge bg-label-secondary">0</span> signifie interdit.
            </p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNouveauPlan">
            <i class="bx bx-plus me-1"></i> Nouveau plan
        </button>
    </div>

    {{-- Légende des colonnes --}}
    <div class="alert alert-info d-flex align-items-start mb-4" role="alert">
        <i class="bx bx-info-circle me-2 fs-5 mt-1"></i>
        <div>
            <strong>Conventions :</strong>
            <ul class="mb-0 mt-1">
                <li><strong>Vide / —</strong> = illimité (pas de restriction)</li>
                <li><strong>0</strong> = interdit / non autorisé</li>
                <li><strong>N > 0</strong> = maximum autorisé par mois (envois) ou au total (maisons, annexes)</li>
            </ul>
        </div>
    </div>

    {{-- Cartes des plans --}}
    <div class="row g-4" id="plans-container">
        @foreach ($plans as $plan)
        <div class="col-xl-6" id="plan-card-{{ $plan->idplan }}">
            <div class="card border-0 shadow-sm h-100">

                {{-- Header carte --}}
                <div class="card-header d-flex align-items-center justify-content-between py-3"
                     style="background:linear-gradient(135deg,#1e40af,#3b82f6);color:#fff;border-radius:8px 8px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-box fs-4"></i>
                        <div>
                            <h6 class="mb-0 fw-bold" id="plan-title-{{ $plan->idplan }}">{{ $plan->nom }}</h6>
                            <small class="opacity-75">Code : <code class="text-white">{{ $plan->code }}</code></small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($plan->is_active)
                            <span class="badge bg-success" id="badge-active-{{ $plan->idplan }}">Actif</span>
                        @else
                            <span class="badge bg-secondary" id="badge-active-{{ $plan->idplan }}">Inactif</span>
                        @endif
                        <button class="btn btn-sm btn-outline-light"
                                onclick="deletePlan({{ $plan->idplan }}, '{{ $plan->nom }}')"
                                title="Supprimer ce plan">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <form id="form-plan-{{ $plan->idplan }}" onsubmit="return false;">
                        @csrf
                        <input type="hidden" name="idplan" value="{{ $plan->idplan }}">

                        {{-- Nom + Description --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bx bx-rename me-1 text-primary"></i>Nom du plan
                                </label>
                                <input type="text" class="form-control" name="nom"
                                       value="{{ $plan->nom }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bx bx-money me-1 text-success"></i>Prix annuel (XOF)
                                </label>
                                <input type="number" class="form-control" name="prix_annuel"
                                       value="{{ (int) $plan->prix_annuel }}" min="0" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bx bx-text me-1 text-secondary"></i>Description
                                </label>
                                <textarea class="form-control" name="description"
                                          rows="2">{{ $plan->description }}</textarea>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Limites ressources --}}
                        <p class="fw-bold text-muted text-uppercase small mb-2">
                            <i class="bx bx-building me-1"></i>Limites de ressources
                        </p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Maisons max
                                    <span class="text-muted small">(vide = illimité)</span>
                                </label>
                                <input type="number" class="form-control" name="max_maisons"
                                       value="{{ $plan->max_maisons }}" min="0"
                                       placeholder="Illimité">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    Annexes max
                                    <span class="text-muted small">(vide = illimité, 0 = aucune)</span>
                                </label>
                                <input type="number" class="form-control" name="max_annexes"
                                       value="{{ $plan->max_annexes }}" min="0"
                                       placeholder="Illimité">
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Limites envois --}}
                        <p class="fw-bold text-muted text-uppercase small mb-2">
                            <i class="bx bx-send me-1"></i>Limites d'envoi par mois
                        </p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bx bx-envelope me-1 text-primary"></i>
                                    Envois Email / mois
                                    <span class="text-muted small">(vide = illimité)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-label-primary">
                                        <i class="bx bx-envelope"></i>
                                    </span>
                                    <input type="number" class="form-control" name="max_envois_email"
                                           value="{{ $plan->max_envois_email }}" min="0"
                                           placeholder="Illimité">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bx bxl-whatsapp me-1 text-success"></i>
                                    Envois WhatsApp / mois
                                    <span class="text-muted small">(vide = illimité)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-label-success">
                                        <i class="bx bxl-whatsapp"></i>
                                    </span>
                                    <input type="number" class="form-control" name="max_envois_whatsapp"
                                           value="{{ $plan->max_envois_whatsapp }}" min="0"
                                           placeholder="Illimité">
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Notifications locataires --}}
                        <p class="fw-bold text-muted text-uppercase small mb-2">
                            <i class="bx bx-bell me-1"></i>Notifications locataires / mois
                        </p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bx bx-money me-1 text-warning"></i>
                                    Rappels de loyer / mois
                                    <span class="text-muted small">(vide = illimité, 0 = interdit)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-label-warning">
                                        <i class="bx bx-money"></i>
                                    </span>
                                    <input type="number" class="form-control" name="max_rappels_loyer"
                                           value="{{ $plan->max_rappels_loyer }}" min="0"
                                           placeholder="Illimité">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bx bx-exit me-1 text-danger"></i>
                                    Préavis / mois
                                    <span class="text-muted small">(vide = illimité, 0 = interdit)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-label-danger">
                                        <i class="bx bx-exit"></i>
                                    </span>
                                    <input type="number" class="form-control" name="max_preavis"
                                           value="{{ $plan->max_preavis }}" min="0"
                                           placeholder="Illimité">
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Publicités --}}
                        <p class="fw-bold text-muted text-uppercase small mb-2">
                            <i class="bx bx-image me-1"></i>Publicités
                        </p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bx bx-image-alt me-1 text-warning"></i>
                                    Publicités max
                                    <span class="text-muted small">(vide = illimité, 0 = interdit)</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-label-warning">
                                        <i class="bx bx-image-alt"></i>
                                    </span>
                                    <input type="number" class="form-control" name="max_publicites"
                                           value="{{ $plan->max_publicites }}" min="0"
                                           placeholder="Illimité">
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- SMS & WhatsApp pay-per-use --}}
                        <p class="fw-bold text-muted text-uppercase small mb-2">
                            <i class="bx bx-message-dots me-1"></i>Services SMS &amp; WhatsApp (pay-per-use)
                        </p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="sms_enabled"
                                           id="sms_enabled_{{ $plan->idplan }}" value="1"
                                           {{ ($plan->sms_enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sms_enabled_{{ $plan->idplan }}">
                                        <i class="bx bx-message-detail me-1 text-warning"></i>SMS activé (pay-per-use)
                                    </label>
                                </div>
                                <small class="text-muted">Si activé, les utilisateurs peuvent envoyer des SMS en payant à l'usage.</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="whatsapp_enabled"
                                           id="wa_enabled_{{ $plan->idplan }}" value="1"
                                           {{ ($plan->whatsapp_enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="wa_enabled_{{ $plan->idplan }}">
                                        <i class="bx bxl-whatsapp me-1 text-success"></i>WhatsApp activé (pay-per-use)
                                    </label>
                                </div>
                                <small class="text-muted">Si activé, les utilisateurs peuvent envoyer via WhatsApp en payant à l'usage.</small>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Statut + bouton save --}}
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox"
                                       id="is_active_{{ $plan->idplan }}" name="is_active"
                                       value="1" {{ $plan->is_active ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold"
                                       for="is_active_{{ $plan->idplan }}">Plan actif</label>
                            </div>
                            <button type="button" class="btn btn-primary"
                                    onclick="savePlan({{ $plan->idplan }})"
                                    id="btn-save-{{ $plan->idplan }}">
                                <i class="bx bx-save me-1"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Récapitulatif actuel --}}
                <div class="card-footer bg-light border-top-0 py-2">
                    <small class="text-muted">
                        <i class="bx bx-info-circle me-1"></i>
                        Maisons : <strong>{{ $plan->max_maisons ?? '∞' }}</strong> &nbsp;|&nbsp;
                        Annexes : <strong>{{ $plan->max_annexes ?? '∞' }}</strong> &nbsp;|&nbsp;
                        Email/mois : <strong>{{ $plan->max_envois_email ?? '∞' }}</strong> &nbsp;|&nbsp;
                        WA/mois : <strong>{{ $plan->max_envois_whatsapp ?? '∞' }}</strong> &nbsp;|&nbsp;
                        Rappels loyer/mois : <strong>{{ $plan->max_rappels_loyer ?? '∞' }}</strong> &nbsp;|&nbsp;
                        Préavis/mois : <strong>{{ $plan->max_preavis ?? '∞' }}</strong> &nbsp;|&nbsp;
                        Publicités : <strong>{{ $plan->max_publicites ?? '∞' }}</strong> &nbsp;|&nbsp;
                        Prix : <strong>{{ number_format($plan->prix_annuel, 0, ',', ' ') }} XOF/an</strong>
                    </small>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

{{-- Modal : Nouveau plan --}}
<div class="modal fade" id="modalNouveauPlan" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bx bx-plus me-2"></i>Nouveau plan d'abonnement</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form-nouveau-plan">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nom" placeholder="ex: Business" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Code unique <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" placeholder="ex: business" required>
                            <div class="form-text">Minuscules, sans espaces. Utilisé en interne.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description" rows="2"
                                      placeholder="Description du plan..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Prix annuel (XOF) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="prix_annuel" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Statut</label>
                            <select class="form-select" name="is_active">
                                <option value="1">Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>

                        <div class="col-12"><hr class="my-1"><p class="fw-bold text-muted text-uppercase small mb-0">Limites de ressources</p></div>

                        <div class="col-md-6">
                            <label class="form-label">Maisons max <span class="text-muted small">(vide = illimité)</span></label>
                            <input type="number" class="form-control" name="max_maisons" min="0" placeholder="Illimité">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Annexes max <span class="text-muted small">(vide = illimité)</span></label>
                            <input type="number" class="form-control" name="max_annexes" min="0" placeholder="Illimité">
                        </div>

                        <div class="col-12"><hr class="my-1"><p class="fw-bold text-muted text-uppercase small mb-0">Limites d'envoi / mois</p></div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-envelope me-1"></i>Email / mois <span class="text-muted small">(vide = illimité)</span></label>
                            <input type="number" class="form-control" name="max_envois_email" min="0" placeholder="Illimité">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bxl-whatsapp me-1"></i>WhatsApp / mois <span class="text-muted small">(vide = illimité)</span></label>
                            <input type="number" class="form-control" name="max_envois_whatsapp" min="0" placeholder="Illimité">
                        </div>

                        <div class="col-12"><hr class="my-1"><p class="fw-bold text-muted text-uppercase small mb-0">Notifications locataires / mois</p></div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-money me-1"></i>Rappels loyer / mois <span class="text-muted small">(vide = illimité, 0 = interdit)</span></label>
                            <input type="number" class="form-control" name="max_rappels_loyer" min="0" placeholder="Illimité">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-exit me-1"></i>Préavis / mois <span class="text-muted small">(vide = illimité, 0 = interdit)</span></label>
                            <input type="number" class="form-control" name="max_preavis" min="0" placeholder="Illimité">
                        </div>

                        <div class="col-12"><hr class="my-1"><p class="fw-bold text-muted text-uppercase small mb-0">Publicités</p></div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-image-alt me-1"></i>Publicités max <span class="text-muted small">(vide = illimité, 0 = interdit)</span></label>
                            <input type="number" class="form-control" name="max_publicites" min="0" placeholder="Illimité">
                        </div>

                        <div class="col-12"><hr class="my-1"><p class="fw-bold text-muted text-uppercase small mb-0">Services SMS &amp; WhatsApp (pay-per-use)</p></div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="new_sms_enabled" value="1" checked>
                                <label class="form-check-label" for="new_sms_enabled">
                                    <i class="bx bx-message-detail me-1 text-warning"></i>SMS activé (pay-per-use)
                                </label>
                            </div>
                            <small class="text-muted">Si activé, les utilisateurs peuvent envoyer des SMS en payant à l'usage.</small>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="new_whatsapp_enabled" value="1" checked>
                                <label class="form-check-label" for="new_whatsapp_enabled">
                                    <i class="bx bxl-whatsapp me-1 text-success"></i>WhatsApp activé (pay-per-use)
                                </label>
                            </div>
                            <small class="text-muted">Si activé, les utilisateurs peuvent envoyer via WhatsApp en payant à l'usage.</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="btn-create-plan" onclick="createPlan()">
                    <i class="bx bx-save me-1"></i> Créer le plan
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function nullableInt(val) {
    return (val === '' || val === null || val === undefined) ? null : parseInt(val);
}

/* ── Enregistrer un plan existant ── */
function savePlan(idplan) {
    const form   = document.getElementById('form-plan-' + idplan);
    const btn    = document.getElementById('btn-save-' + idplan);
    const fdata  = new FormData(form);

    const payload = {
        idplan:               idplan,
        nom:                  fdata.get('nom'),
        description:          fdata.get('description'),
        prix_annuel:          parseFloat(fdata.get('prix_annuel')) || 0,
        max_maisons:          nullableInt(fdata.get('max_maisons')),
        max_annexes:          nullableInt(fdata.get('max_annexes')),
        max_envois_email:     nullableInt(fdata.get('max_envois_email')),
        max_envois_whatsapp:  nullableInt(fdata.get('max_envois_whatsapp')),
        max_rappels_loyer:    nullableInt(fdata.get('max_rappels_loyer')),
        max_preavis:          nullableInt(fdata.get('max_preavis')),
        max_publicites:       nullableInt(fdata.get('max_publicites')),
        is_active:            form.querySelector('[name="is_active"]').checked ? 1 : 0,
        sms_enabled:          document.getElementById('sms_enabled_' + idplan)?.checked ? 1 : 0,
        whatsapp_enabled:     document.getElementById('wa_enabled_' + idplan)?.checked  ? 1 : 0,
    };

    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Enregistrement...';

    $.ajax({
        url:         '{{ route('super_admin.plans.update') }}',
        type:        'POST',
        contentType: 'application/json',
        data:        JSON.stringify(payload),
        headers:     { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        success: function(data) {
            if (data.status) {
                /* Mise à jour du badge actif/inactif */
                const badge = document.getElementById('badge-active-' + idplan);
                if (payload.is_active) {
                    badge.className = 'badge bg-success';
                    badge.textContent = 'Actif';
                } else {
                    badge.className = 'badge bg-secondary';
                    badge.textContent = 'Inactif';
                }
                /* Mise à jour du titre */
                document.getElementById('plan-title-' + idplan).textContent = payload.nom;

                Swal.fire({ icon: 'success', title: 'Enregistré', text: data.message,
                            timer: 2000, showConfirmButton: false });
            } else {
                Swal.fire('Erreur', data.message || 'Une erreur est survenue.', 'error');
            }
        },
        error: function(xhr) {
            const msg = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message : 'Erreur serveur.';
            Swal.fire('Erreur', msg, 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-save me-1"></i> Enregistrer';
        }
    });
}

/* ── Créer un nouveau plan ── */
function createPlan() {
    const form    = document.getElementById('form-nouveau-plan');
    const btn     = document.getElementById('btn-create-plan');
    const fdata   = new FormData(form);

    const payload = {
        nom:                  fdata.get('nom'),
        code:                 fdata.get('code'),
        description:          fdata.get('description'),
        prix_annuel:          parseFloat(fdata.get('prix_annuel')) || 0,
        max_maisons:          nullableInt(fdata.get('max_maisons')),
        max_annexes:          nullableInt(fdata.get('max_annexes')),
        max_envois_email:     nullableInt(fdata.get('max_envois_email')),
        max_envois_whatsapp:  nullableInt(fdata.get('max_envois_whatsapp')),
        max_rappels_loyer:    nullableInt(fdata.get('max_rappels_loyer')),
        max_preavis:          nullableInt(fdata.get('max_preavis')),
        max_publicites:       nullableInt(fdata.get('max_publicites')),
        is_active:            parseInt(fdata.get('is_active')),
        sms_enabled:          document.getElementById('new_sms_enabled')?.checked      ? 1 : 0,
        whatsapp_enabled:     document.getElementById('new_whatsapp_enabled')?.checked ? 1 : 0,
    };

    if (!payload.nom || !payload.code) {
        Swal.fire('Champs requis', 'Nom et code sont obligatoires.', 'warning');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Création...';

    $.ajax({
        url:         '{{ route('super_admin.plans.store') }}',
        type:        'POST',
        contentType: 'application/json',
        data:        JSON.stringify(payload),
        headers:     { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        success: function(data) {
            if (data.status) {
                Swal.fire({
                    icon: 'success', title: 'Plan créé',
                    text: data.message, timer: 1800, showConfirmButton: false,
                }).then(() => { location.reload(); });
                bootstrap.Modal.getInstance(document.getElementById('modalNouveauPlan')).hide();
                form.reset();
            } else {
                Swal.fire('Erreur', data.message || 'Une erreur est survenue.', 'error');
            }
        },
        error: function(xhr) {
            const msg = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message : 'Erreur serveur.';
            Swal.fire('Erreur', msg, 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-save me-1"></i> Créer le plan';
        }
    });
}

/* ── Supprimer un plan ── */
function deletePlan(idplan, nom) {
    Swal.fire({
        title: 'Supprimer « ' + nom + ' » ?',
        text:  'Les entreprises sur ce plan ne seront pas affectées immédiatement, mais le plan ne sera plus proposable.',
        icon:  'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonText:  'Annuler',
        confirmButtonText: 'Oui, supprimer',
    }).then(function(result) {
        if (!result.isConfirmed) return;

        $.ajax({
            url:         '{{ route('super_admin.plans.destroy') }}',
            type:        'POST',
            contentType: 'application/json',
            data:        JSON.stringify({ idplan: idplan }),
            headers:     { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            success: function(data) {
                if (data.status) {
                    document.getElementById('plan-card-' + idplan).remove();
                    Swal.fire({ icon: 'success', title: 'Supprimé',
                                text: data.message, timer: 1800, showConfirmButton: false });
                } else {
                    Swal.fire('Erreur', data.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Erreur', 'Erreur serveur.', 'error');
            }
        });
    });
}
</script>
@endpush

@endsection
