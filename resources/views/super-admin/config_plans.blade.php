@extends('layouts.template')

@section('title')
  <title>{{ __('pages.cp_title') }}</title>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Super Admin /</span> {{ __('pages.cp_breadcrumb') }}
    </h4>

    {{-- En-tête + bouton nouveau plan --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <p class="text-muted mb-0">
                {{ __('pages.cp_intro_part1') }}
                <span class="badge bg-label-success">{{ __('pages.cp_unlimited') }}</span>.
                {{ __('pages.cp_intro_part2') }} <span class="badge bg-label-secondary">0</span> {{ __('pages.cp_intro_zero_means') }}
            </p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNouveauPlan">
            <i class="bx bx-plus me-1"></i> {{ __('pages.cp_btn_new') }}
        </button>
    </div>

    {{-- Légende des colonnes --}}
    <div class="alert alert-info d-flex align-items-start mb-4" role="alert">
        <i class="bx bx-info-circle me-2 fs-5 mt-1"></i>
        <div>
            <strong>{{ __('pages.cp_legend_title') }}</strong>
            <ul class="mb-0 mt-1">
                <li><strong>Vide / —</strong> {{ __('pages.cp_legend_empty') }}</li>
                <li><strong>0</strong> {{ __('pages.cp_legend_zero') }}</li>
                <li><strong>N > 0</strong> {{ __('pages.cp_legend_n') }}</li>
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
                            <small class="opacity-75">{{ __('pages.cp_plan_code') }} <code class="text-white">{{ $plan->code }}</code></small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($plan->is_active)
                            <span class="badge bg-success" id="badge-active-{{ $plan->idplan }}">{{ __('pages.cp_badge_active') }}</span>
                        @else
                            <span class="badge bg-secondary" id="badge-active-{{ $plan->idplan }}">{{ __('pages.cp_badge_inactive') }}</span>
                        @endif
                        <button class="btn btn-sm btn-outline-light"
                                onclick="deletePlan({{ $plan->idplan }}, '{{ $plan->nom }}')"
                                title="{{ __('pages.cp_btn_delete_plan') }}">
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
                                    <i class="bx bx-rename me-1 text-primary"></i>{{ __('pages.cp_label_plan_name') }}
                                </label>
                                <input type="text" class="form-control" name="nom"
                                       value="{{ $plan->nom }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bx bx-money me-1 text-success"></i>Prix mensuel (XOF)
                                </label>
                                <input type="number" class="form-control" name="prix_mensuel"
                                       value="{{ (int) $plan->prix_mensuel }}" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="bx bx-money me-1 text-secondary"></i>{{ __('pages.cp_label_annual_price') }}
                                </label>
                                <input type="number" class="form-control" name="prix_annuel"
                                       value="{{ (int) $plan->prix_annuel }}" min="0" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">
                                    <i class="bx bx-text me-1 text-secondary"></i>{{ __('pages.cp_label_description') }}
                                </label>
                                <textarea class="form-control" name="description"
                                          rows="2">{{ $plan->description }}</textarea>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Limites ressources --}}
                        <p class="fw-bold text-muted text-uppercase small mb-2">
                            <i class="bx bx-building me-1"></i>{{ __('pages.cp_section_resources') }}
                        </p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    {{ __('pages.cp_label_max_houses') }}
                                    <span class="text-muted small">{{ __('pages.cp_hint_empty') }}</span>
                                </label>
                                <input type="number" class="form-control" name="max_maisons"
                                       value="{{ $plan->max_maisons }}" min="0"
                                       placeholder="{{ __('pages.cp_unlimited') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    {{ __('pages.cp_label_max_branches') }}
                                    <span class="text-muted small">{{ __('pages.cp_hint_empty_zero') }}</span>
                                </label>
                                <input type="number" class="form-control" name="max_annexes"
                                       value="{{ $plan->max_annexes }}" min="0"
                                       placeholder="{{ __('pages.cp_unlimited') }}">
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Limites envois --}}
                        <p class="fw-bold text-muted text-uppercase small mb-2">
                            <i class="bx bx-send me-1"></i>{{ __('pages.cp_section_send') }}
                        </p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bx bx-envelope me-1 text-primary"></i>
                                    {{ __('pages.cp_label_email_month') }}
                                    <span class="text-muted small">{{ __('pages.cp_hint_empty') }}</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-label-primary">
                                        <i class="bx bx-envelope"></i>
                                    </span>
                                    <input type="number" class="form-control" name="max_envois_email"
                                           value="{{ $plan->max_envois_email }}" min="0"
                                           placeholder="{{ __('pages.cp_unlimited') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bx bxl-whatsapp me-1 text-success"></i>
                                    {{ __('pages.cp_label_wa_month') }}
                                    <span class="text-muted small">{{ __('pages.cp_hint_empty') }}</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-label-success">
                                        <i class="bx bxl-whatsapp"></i>
                                    </span>
                                    <input type="number" class="form-control" name="max_envois_whatsapp"
                                           value="{{ $plan->max_envois_whatsapp }}" min="0"
                                           placeholder="{{ __('pages.cp_unlimited') }}">
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Notifications locataires --}}
                        <p class="fw-bold text-muted text-uppercase small mb-2">
                            <i class="bx bx-bell me-1"></i>{{ __('pages.cp_section_notif') }}
                        </p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bx bx-money me-1 text-warning"></i>
                                    {{ __('pages.cp_label_rent_remind') }}
                                    <span class="text-muted small">{{ __('pages.cp_hint_forbidden') }}</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-label-warning">
                                        <i class="bx bx-money"></i>
                                    </span>
                                    <input type="number" class="form-control" name="max_rappels_loyer"
                                           value="{{ $plan->max_rappels_loyer }}" min="0"
                                           placeholder="{{ __('pages.cp_unlimited') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bx bx-exit me-1 text-danger"></i>
                                    {{ __('pages.cp_label_notice') }}
                                    <span class="text-muted small">{{ __('pages.cp_hint_forbidden') }}</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-label-danger">
                                        <i class="bx bx-exit"></i>
                                    </span>
                                    <input type="number" class="form-control" name="max_preavis"
                                           value="{{ $plan->max_preavis }}" min="0"
                                           placeholder="{{ __('pages.cp_unlimited') }}">
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- Publicités --}}
                        <p class="fw-bold text-muted text-uppercase small mb-2">
                            <i class="bx bx-image me-1"></i>{{ __('pages.cp_section_ads') }}
                        </p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    <i class="bx bx-image-alt me-1 text-warning"></i>
                                    {{ __('pages.cp_label_max_ads') }}
                                    <span class="text-muted small">{{ __('pages.cp_hint_forbidden') }}</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-label-warning">
                                        <i class="bx bx-image-alt"></i>
                                    </span>
                                    <input type="number" class="form-control" name="max_publicites"
                                           value="{{ $plan->max_publicites }}" min="0"
                                           placeholder="{{ __('pages.cp_unlimited') }}">
                                </div>
                            </div>
                        </div>

                        <hr class="my-3">

                        {{-- SMS & WhatsApp pay-per-use --}}
                        <p class="fw-bold text-muted text-uppercase small mb-2">
                            <i class="bx bx-message-dots me-1"></i>{{ __('pages.cp_section_sms_wa') }}
                        </p>
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="sms_enabled"
                                           id="sms_enabled_{{ $plan->idplan }}" value="1"
                                           {{ ($plan->sms_enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="sms_enabled_{{ $plan->idplan }}">
                                        <i class="bx bx-message-detail me-1 text-warning"></i>{{ __('pages.cp_label_sms_ppu') }}
                                    </label>
                                </div>
                                <small class="text-muted">{{ __('pages.cp_hint_sms_ppu') }}</small>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="whatsapp_enabled"
                                           id="wa_enabled_{{ $plan->idplan }}" value="1"
                                           {{ ($plan->whatsapp_enabled ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="wa_enabled_{{ $plan->idplan }}">
                                        <i class="bx bxl-whatsapp me-1 text-success"></i>{{ __('pages.cp_label_wa_ppu') }}
                                    </label>
                                </div>
                                <small class="text-muted">{{ __('pages.cp_hint_wa_ppu') }}</small>
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
                                       for="is_active_{{ $plan->idplan }}">{{ __('pages.cp_label_plan_active') }}</label>
                            </div>
                            <button type="button" class="btn btn-primary"
                                    onclick="savePlan({{ $plan->idplan }})"
                                    id="btn-save-{{ $plan->idplan }}">
                                <i class="bx bx-save me-1"></i> {{ __('common.btn_save') }}
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Récapitulatif actuel --}}
                <div class="card-footer bg-light border-top-0 py-2">
                    <small class="text-muted">
                        <i class="bx bx-info-circle me-1"></i>
                        {{ __('pages.cp_footer_houses') }} <strong>{{ $plan->max_maisons ?? '∞' }}</strong> &nbsp;|&nbsp;
                        {{ __('pages.cp_footer_branches') }} <strong>{{ $plan->max_annexes ?? '∞' }}</strong> &nbsp;|&nbsp;
                        {{ __('pages.cp_footer_email') }} <strong>{{ $plan->max_envois_email ?? '∞' }}</strong> &nbsp;|&nbsp;
                        {{ __('pages.cp_footer_wa') }} <strong>{{ $plan->max_envois_whatsapp ?? '∞' }}</strong> &nbsp;|&nbsp;
                        {{ __('pages.cp_footer_reminders') }} <strong>{{ $plan->max_rappels_loyer ?? '∞' }}</strong> &nbsp;|&nbsp;
                        {{ __('pages.cp_footer_notice') }} <strong>{{ $plan->max_preavis ?? '∞' }}</strong> &nbsp;|&nbsp;
                        {{ __('pages.cp_footer_ads') }} <strong>{{ $plan->max_publicites ?? '∞' }}</strong> &nbsp;|&nbsp;
                        Prix mensuel : <strong>{{ number_format($plan->prix_mensuel, 0, ',', ' ') }} XOF/mois</strong> &nbsp;|&nbsp;
                        {{ __('pages.cp_footer_price') }} <strong>{{ number_format($plan->prix_annuel, 0, ',', ' ') }} XOF/an</strong>
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
                <h5 class="modal-title"><i class="bx bx-plus me-2"></i>{{ __('pages.cp_modal_new_title') }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="form-nouveau-plan">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ __('pages.cp_label_name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nom" placeholder="ex: Business" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ __('pages.cp_label_code') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" placeholder="ex: business" required>
                            <div class="form-text">{{ __('pages.cp_hint_code') }}</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">{{ __('pages.cp_label_description') }}</label>
                            <textarea class="form-control" name="description" rows="2"
                                      placeholder="Description du plan..."></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Prix mensuel (XOF) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="prix_mensuel" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ __('pages.cp_label_annual_price') }} <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="prix_annuel" min="0" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">{{ __('pages.cp_label_status') }}</label>
                            <select class="form-select" name="is_active">
                                <option value="1">{{ __('pages.cp_opt_active') }}</option>
                                <option value="0">{{ __('pages.cp_opt_inactive') }}</option>
                            </select>
                        </div>

                        <div class="col-12"><hr class="my-1"><p class="fw-bold text-muted text-uppercase small mb-0">{{ __('pages.cp_section_resources') }}</p></div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('pages.cp_label_max_houses') }} <span class="text-muted small">{{ __('pages.cp_hint_empty') }}</span></label>
                            <input type="number" class="form-control" name="max_maisons" min="0" placeholder="{{ __('pages.cp_unlimited') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('pages.cp_label_max_branches') }} <span class="text-muted small">{{ __('pages.cp_hint_empty') }}</span></label>
                            <input type="number" class="form-control" name="max_annexes" min="0" placeholder="{{ __('pages.cp_unlimited') }}">
                        </div>

                        <div class="col-12"><hr class="my-1"><p class="fw-bold text-muted text-uppercase small mb-0">{{ __('pages.cp_section_send_month') }}</p></div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-envelope me-1"></i>{{ __('pages.cp_label_email_month') }} <span class="text-muted small">{{ __('pages.cp_hint_empty') }}</span></label>
                            <input type="number" class="form-control" name="max_envois_email" min="0" placeholder="{{ __('pages.cp_unlimited') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bxl-whatsapp me-1"></i>{{ __('pages.cp_label_wa_month') }} <span class="text-muted small">{{ __('pages.cp_hint_empty') }}</span></label>
                            <input type="number" class="form-control" name="max_envois_whatsapp" min="0" placeholder="{{ __('pages.cp_unlimited') }}">
                        </div>

                        <div class="col-12"><hr class="my-1"><p class="fw-bold text-muted text-uppercase small mb-0">{{ __('pages.cp_section_notif_month') }}</p></div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-money me-1"></i>{{ __('pages.cp_label_rent_remind') }} <span class="text-muted small">{{ __('pages.cp_hint_forbidden') }}</span></label>
                            <input type="number" class="form-control" name="max_rappels_loyer" min="0" placeholder="{{ __('pages.cp_unlimited') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-exit me-1"></i>{{ __('pages.cp_label_notice') }} <span class="text-muted small">{{ __('pages.cp_hint_forbidden') }}</span></label>
                            <input type="number" class="form-control" name="max_preavis" min="0" placeholder="{{ __('pages.cp_unlimited') }}">
                        </div>

                        <div class="col-12"><hr class="my-1"><p class="fw-bold text-muted text-uppercase small mb-0">{{ __('pages.cp_section_ads') }}</p></div>

                        <div class="col-md-6">
                            <label class="form-label"><i class="bx bx-image-alt me-1"></i>{{ __('pages.cp_label_max_ads') }} <span class="text-muted small">{{ __('pages.cp_hint_forbidden') }}</span></label>
                            <input type="number" class="form-control" name="max_publicites" min="0" placeholder="{{ __('pages.cp_unlimited') }}">
                        </div>

                        <div class="col-12"><hr class="my-1"><p class="fw-bold text-muted text-uppercase small mb-0">{{ __('pages.cp_section_sms_wa') }}</p></div>

                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="new_sms_enabled" value="1" checked>
                                <label class="form-check-label" for="new_sms_enabled">
                                    <i class="bx bx-message-detail me-1 text-warning"></i>{{ __('pages.cp_label_sms_ppu') }}
                                </label>
                            </div>
                            <small class="text-muted">{{ __('pages.cp_hint_sms_ppu') }}</small>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="new_whatsapp_enabled" value="1" checked>
                                <label class="form-check-label" for="new_whatsapp_enabled">
                                    <i class="bx bxl-whatsapp me-1 text-success"></i>{{ __('pages.cp_label_wa_ppu') }}
                                </label>
                            </div>
                            <small class="text-muted">{{ __('pages.cp_hint_wa_ppu') }}</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_cancel') }}</button>
                <button type="button" class="btn btn-primary" id="btn-create-plan" onclick="createPlan()">
                    <i class="bx bx-save me-1"></i> {{ __('pages.cp_btn_create_plan') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

var CP_I18N = {
    required:       '{{ __('pages.cp_swal_required') }}',
    nameCode:       '{{ __('pages.cp_swal_name_code') }}',
    saving:         '<i class="bx bx-loader bx-spin me-1"></i> {{ __('pages.cp_saving') }}',
    creating:       '<i class="bx bx-loader bx-spin me-1"></i> {{ __('pages.cp_creating') }}',
    saved:          '{{ __('pages.cp_swal_saved') }}',
    planCreated:    '{{ __('pages.cp_swal_plan_created') }}',
    error:          '{{ __('pages.cp_swal_error') }}',
    genericError:   '{{ __('pages.cp_swal_generic_error') }}',
    serverError:    '{{ __('pages.cp_swal_server_error') }}',
    badgeActive:    '{{ __('pages.cp_badge_active') }}',
    badgeInactive:  '{{ __('pages.cp_badge_inactive') }}',
    deleteTitle:    '{{ __('pages.cp_delete_title') }}',
    deleteText:     '{{ __('pages.cp_delete_text') }}',
    deleteYes:      '{{ __('pages.cp_delete_yes') }}',
    deleted:        '{{ __('pages.cp_swal_deleted') }}',
    cancel:         '{{ __('common.btn_cancel') }}',
    btnSave:        '<i class="bx bx-save me-1"></i> {{ __('common.btn_save') }}',
    btnCreate:      '<i class="bx bx-save me-1"></i> {{ __('pages.cp_btn_create_plan') }}',
};

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
        prix_mensuel:         parseFloat(fdata.get('prix_mensuel')) || 0,
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
    btn.innerHTML = CP_I18N.saving;

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
                    badge.textContent = CP_I18N.badgeActive;
                } else {
                    badge.className = 'badge bg-secondary';
                    badge.textContent = CP_I18N.badgeInactive;
                }
                /* Mise à jour du titre */
                document.getElementById('plan-title-' + idplan).textContent = payload.nom;

                Swal.fire({ icon: 'success', title: CP_I18N.saved, text: data.message,
                            timer: 2000, showConfirmButton: false });
            } else {
                Swal.fire(CP_I18N.error, data.message || CP_I18N.genericError, 'error');
            }
        },
        error: function(xhr) {
            const msg = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message : CP_I18N.serverError;
            Swal.fire(CP_I18N.error, msg, 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = CP_I18N.btnSave;
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
        prix_mensuel:         parseFloat(fdata.get('prix_mensuel')) || 0,
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
        Swal.fire(CP_I18N.required, CP_I18N.nameCode, 'warning');
        return;
    }

    btn.disabled = true;
    btn.innerHTML = CP_I18N.creating;

    $.ajax({
        url:         '{{ route('super_admin.plans.store') }}',
        type:        'POST',
        contentType: 'application/json',
        data:        JSON.stringify(payload),
        headers:     { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        success: function(data) {
            if (data.status) {
                Swal.fire({
                    icon: 'success', title: CP_I18N.planCreated,
                    text: data.message, timer: 1800, showConfirmButton: false,
                }).then(() => { location.reload(); });
                bootstrap.Modal.getInstance(document.getElementById('modalNouveauPlan')).hide();
                form.reset();
            } else {
                Swal.fire(CP_I18N.error, data.message || CP_I18N.genericError, 'error');
            }
        },
        error: function(xhr) {
            const msg = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message : CP_I18N.serverError;
            Swal.fire(CP_I18N.error, msg, 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = CP_I18N.btnCreate;
        }
    });
}

/* ── Supprimer un plan ── */
function deletePlan(idplan, nom) {
    Swal.fire({
        title: CP_I18N.deleteTitle + ' « ' + nom + ' » ?',
        text:  CP_I18N.deleteText,
        icon:  'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonText:  CP_I18N.cancel,
        confirmButtonText: CP_I18N.deleteYes,
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
                    Swal.fire({ icon: 'success', title: CP_I18N.deleted,
                                text: data.message, timer: 1800, showConfirmButton: false });
                } else {
                    Swal.fire(CP_I18N.error, data.message, 'error');
                }
            },
            error: function() {
                Swal.fire(CP_I18N.error, CP_I18N.serverError, 'error');
            }
        });
    });
}
</script>
@endpush

@endsection
