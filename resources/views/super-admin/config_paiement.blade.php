@extends('layouts.template')

@section('title')
  <title>{{ __('pages.cfg_title') }}</title>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Super Admin /</span> {{ __('pages.cfg_breadcrumb') }}
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between py-3"
                     style="background:linear-gradient(135deg,#1e40af,#3b82f6);color:#fff;border-radius:8px 8px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-credit-card fs-4"></i>
                        <div>
                            <h6 class="mb-0 fw-bold">{{ __('pages.cfg_payment_header') }}</h6>
                            <small class="opacity-75">{{ __('pages.cfg_payment_subtitle') }}</small>
                        </div>
                    </div>
                    @php
                        $apInit  = $activePaymentProvider ?? 'none';
                        $sbInit  = $activePaymentSandbox ?? true;
                        $badgeClass = $apInit === 'kkiapay' ? 'badge bg-success fs-6 px-3 py-2'
                                    : ($apInit === 'fedapay' ? 'badge bg-warning text-dark fs-6 px-3 py-2'
                                    : 'badge bg-secondary fs-6 px-3 py-2');
                        $sandboxLabel = __('pages.cfg_sandbox');
                        $badgeText  = $apInit === 'kkiapay' ? 'KKiaPay — ' . ($sbInit ? $sandboxLabel : __('pages.cfg_production'))
                                    : ($apInit === 'fedapay' ? 'FedaPay — ' . ($sbInit ? $sandboxLabel : __('pages.cfg_production'))
                                    : __('pages.cfg_payment_disabled'));
                    @endphp
                    <span id="payment-status-badge" class="{{ $badgeClass }}">
                        {{ $badgeText }}
                    </span>
                </div>
                <div class="card-body pt-4">
                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                        <i class="bx bx-info-circle me-2 fs-5"></i>
                        <div>
                            {!! __('pages.cfg_payment_info') !!}
                        </div>
                    </div>

                    <form id="payment-config-form">
                        @csrf

                        {{-- Choix du prestataire actif --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-6">
                                <i class="bx bx-list-check me-1 text-primary"></i>{{ __('pages.cfg_provider_active') }}
                            </label>
                            @php $ap = $activePaymentProvider ?? 'none'; @endphp
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check border rounded p-3 pe-4 {{ $ap === 'none' ? 'border-primary' : '' }}" style="cursor:pointer;">
                                    <input class="form-check-input" type="radio" name="active_payment_provider"
                                           id="provider_none" value="none" {{ $ap === 'none' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="provider_none" style="cursor:pointer;">
                                        <i class="bx bx-block me-1 text-secondary"></i> {{ __('pages.cfg_provider_none') }}
                                    </label>
                                </div>
                                <div class="form-check border rounded p-3 pe-4 {{ $ap === 'kkiapay' ? 'border-primary' : '' }}" style="cursor:pointer;">
                                    <input class="form-check-input" type="radio" name="active_payment_provider"
                                           id="provider_kkiapay" value="kkiapay" {{ $ap === 'kkiapay' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="provider_kkiapay" style="cursor:pointer;">
                                        <i class="bx bx-credit-card me-1 text-primary"></i> KKiaPay
                                    </label>
                                </div>
                                <div class="form-check border rounded p-3 pe-4 {{ $ap === 'fedapay' ? 'border-warning' : '' }}" style="cursor:pointer;">
                                    <input class="form-check-input" type="radio" name="active_payment_provider"
                                           id="provider_fedapay" value="fedapay" {{ $ap === 'fedapay' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="provider_fedapay" style="cursor:pointer;">
                                        <i class="bx bx-credit-card-alt me-1 text-warning"></i> FedaPay
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Section KKiaPay --}}
                        <div id="section-kkiapay" class="{{ ($activePaymentProvider ?? 'none') === 'kkiapay' ? '' : 'd-none' }}">
                            <hr class="my-3">
                            <h6 class="fw-bold mb-3 text-primary"><i class="bx bx-credit-card me-1"></i>{{ __('pages.cfg_kkia_config') }}</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-key me-1 text-warning"></i>{{ __('pages.cfg_kkia_public_key') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="kkiapay_public_key"
                                           name="kkiapay_public_key" placeholder="Votre clé publique KKiaPay"
                                           value="{{ $paymentCfgData['kkiapay_public_key'] ?? '' }}">
                                    <div class="form-text">{{ __('pages.cfg_kkia_public_help') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-lock me-1 text-danger"></i>{{ __('pages.cfg_kkia_private_key') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="kkiapay_private_key"
                                               name="kkiapay_private_key"
                                               placeholder="{{ ($paymentCfgData['has_kkiapay_private'] ?? false) ? '••••••••••••••••' : 'Entrer la clé privée' }}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleFieldVisibility('kkiapay_private_key')">
                                            <i class="bx bx-show"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-danger fw-semibold"><i class="bx bx-error-circle me-1"></i>{{ __('pages.cfg_key_confidential') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-shield me-1 text-danger"></i>{{ __('pages.cfg_kkia_secret_key') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="kkiapay_secret_key"
                                               name="kkiapay_secret_key"
                                               placeholder="{{ ($paymentCfgData['has_kkiapay_secret'] ?? false) ? '••••••••••••••••' : 'Entrer la clé secrète' }}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleFieldVisibility('kkiapay_secret_key')">
                                            <i class="bx bx-show"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-danger fw-semibold"><i class="bx bx-error-circle me-1"></i>{{ __('pages.cfg_server_verify') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-test-tube me-1 text-info"></i>{{ __('pages.cfg_kkia_env') }}
                                    </label>
                                    <select class="form-select" id="kkiapay_sandbox" name="kkiapay_sandbox">
                                        <option value="1" {{ ($paymentCfgData['kkiapay_sandbox'] ?? true) ? 'selected' : '' }}>{{ __('pages.cfg_sandbox') }}</option>
                                        <option value="0" {{ !($paymentCfgData['kkiapay_sandbox'] ?? true) ? 'selected' : '' }}>{{ __('pages.cfg_production') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section FedaPay --}}
                        <div id="section-fedapay" class="{{ ($activePaymentProvider ?? 'none') === 'fedapay' ? '' : 'd-none' }}">
                            <hr class="my-3">
                            <h6 class="fw-bold mb-3 text-warning"><i class="bx bx-credit-card-alt me-1"></i>{{ __('pages.cfg_feda_config') }}</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-key me-1 text-warning"></i>{{ __('pages.cfg_kkia_public_key') }} <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="fedapay_public_key"
                                           name="fedapay_public_key" placeholder="pk_sandbox_... ou pk_live_..."
                                           value="{{ $paymentCfgData['fedapay_public_key'] ?? '' }}">
                                    <div class="form-text">{{ __('pages.cfg_feda_public_help') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-shield me-1 text-danger"></i>{{ __('pages.cfg_kkia_secret_key') }} <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="fedapay_secret_key"
                                               name="fedapay_secret_key"
                                               placeholder="{{ ($paymentCfgData['has_fedapay_secret'] ?? false) ? '••••••••••••••••' : 'Entrer la clé secrète' }}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleFieldVisibility('fedapay_secret_key')">
                                            <i class="bx bx-show"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-danger fw-semibold"><i class="bx bx-error-circle me-1"></i>{{ __('pages.cfg_server_verify') }}</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-test-tube me-1 text-info"></i>{{ __('pages.cfg_feda_env') }}
                                    </label>
                                    <select class="form-select" id="fedapay_sandbox" name="fedapay_sandbox">
                                        <option value="1" {{ ($paymentCfgData['fedapay_sandbox'] ?? true) ? 'selected' : '' }}>{{ __('pages.cfg_sandbox') }}</option>
                                        <option value="0" {{ !($paymentCfgData['fedapay_sandbox'] ?? true) ? 'selected' : '' }}>{{ __('pages.cfg_production') }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary px-4" id="btn-save-payment">
                                <i class="bx bx-save me-1"></i> {{ __('pages.cfg_btn_save_payment') }}
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="loadPaymentConfig()">
                                <i class="bx bx-refresh me-1"></i> {{ __('pages.cfg_btn_reload') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== CARD Africa's Talking ===== --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between py-3"
                     style="background:linear-gradient(135deg,#15803d,#22c55e);color:#fff;border-radius:8px 8px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-message-dots fs-4"></i>
                        <div>
                            <h6 class="mb-0 fw-bold text-white">{{ __('pages.cfg_at_header') }}</h6>
                            <small class="opacity-75">{{ __('pages.cfg_at_subtitle') }}</small>
                        </div>
                    </div>
                    @php
                        $atOk  = !empty($atCfg['at_username']) && !empty($atCfg['at_api_key']);
                        $waOk  = $atOk && !empty($atCfg['at_whatsapp_product_id']);
                    @endphp
                    <span class="badge {{ $atOk ? 'bg-light text-success' : 'bg-secondary' }} fs-6 px-3 py-2">
                        {{ $atOk ? __('pages.cfg_at_active') . ($waOk ? ' ' . __('pages.cfg_at_wa_active') : '') : __('pages.cfg_at_inactive') }}
                    </span>
                </div>
                <div class="card-body pt-4">
                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                        <i class="bx bx-info-circle me-2 fs-5"></i>
                        <div>
                            {!! __('pages.cfg_at_info') !!}
                        </div>
                    </div>

                    <form id="at-config-form">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="at_username" name="at_username"
                                       value="{{ $atCfg['at_username'] ?? '' }}"
                                       placeholder="ex: sandbox ou votre username AT" autocomplete="off">
                                <div class="form-text">{!! __('pages.cfg_at_username_help') !!}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">API Key <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="at_api_key" name="at_api_key"
                                           placeholder="{{ !empty($atCfg['at_api_key']) ? '••••••••• (définie — laisser vide pour conserver)' : 'Entrer la clé API' }}"
                                           autocomplete="off">
                                    <button type="button" class="btn btn-outline-secondary" onclick="toggleFieldVisibility('at_api_key')">
                                        <i class="bx bx-show"></i>
                                    </button>
                                </div>
                                <div class="form-text text-danger fw-semibold"><i class="bx bx-error-circle me-1"></i>{{ __('pages.cfg_key_confidential') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('pages.cfg_at_sender_label') }} <small class="text-muted fw-normal">(optionnel)</small>
                                </label>
                                <input type="text" class="form-control" id="at_sender_id" name="at_sender_id"
                                       value="{{ $atCfg['at_sender_id'] ?? '' }}"
                                       placeholder="{{ __('pages.cfg_at_sender_ph') }}">
                                <div class="form-text">{{ __('pages.cfg_at_sender_help') }}</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    {{ __('pages.cfg_at_wa_product_label') }} <small class="text-muted fw-normal">(optionnel — requis pour WhatsApp)</small>
                                </label>
                                <input type="text" class="form-control" id="at_whatsapp_product_id" name="at_whatsapp_product_id"
                                       value="{{ $atCfg['at_whatsapp_product_id'] ?? '' }}"
                                       placeholder="{{ __('pages.cfg_at_wa_product_ph') }}" autocomplete="off">
                                <div class="form-text">{{ __('pages.cfg_at_wa_product_help') }}</div>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-success px-4" id="btn-save-at">
                                <i class="bx bx-save me-1"></i> {{ __('pages.cfg_at_btn_save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── Contact WhatsApp (compte bloqué) ──────────────────────────────────── --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header py-3 d-flex align-items-center gap-2"
                 style="background:linear-gradient(135deg,#128c7e,#25d366);color:#fff;border-radius:8px 8px 0 0;">
                <i class="bx bxl-whatsapp fs-4"></i>
                <div>
                    <h6 class="mb-0 fw-bold">{{ __('pages.cfg_wa_blocage_header') }}</h6>
                    <small class="opacity-75">{{ __('pages.cfg_wa_blocage_subtitle') }}</small>
                </div>
            </div>
            <div class="card-body pt-4">
                <form id="form-contact-blocage">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="bx bxl-whatsapp me-1 text-success"></i>{{ __('pages.cfg_wa_blocage_label') }}
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bx bxl-whatsapp text-success"></i></span>
                                <input type="text" class="form-control" id="whatsapp_contact_blocage"
                                       name="whatsapp_contact_blocage"
                                       value="{{ $whatsappContactBlocage ?? '' }}"
                                       placeholder="{{ __('pages.cfg_wa_blocage_ph') }}"
                                       maxlength="30">
                            </div>
                            <div class="form-text">
                                {!! __('pages.cfg_wa_blocage_help') !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn btn-success w-100" id="btn-save-contact-blocage">
                                <i class="bx bx-save me-1"></i> {{ __('common.btn_save') }}
                            </button>
                        </div>
                        @if(!empty($whatsappContactBlocage))
                        <div class="col-md-3">
                            <a href="https://wa.me/{{ $whatsappContactBlocage }}" target="_blank"
                               class="btn btn-outline-success w-100">
                                <i class="bx bxl-whatsapp me-1"></i> {{ __('pages.cfg_wa_test_link') }}
                            </a>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
var CFG_I18N = {
    sandbox:         '{{ __('pages.cfg_sandbox') }}',
    production:      '{{ __('pages.cfg_production') }}',
    paymentDisabled: '{{ __('pages.cfg_payment_disabled') }}',
    saving:          '{{ __('pages.cfg_saving') }}',
    btnSavePayment:  '<i class="bx bx-save me-1"></i> {{ __('pages.cfg_btn_save_payment') }}',
    btnSaveAt:       '<i class="bx bx-save me-1"></i> {{ __('pages.cfg_at_btn_save') }}',
    btnSave:         '<i class="bx bx-save me-1"></i> {{ __('common.btn_save') }}',
    swalSuccess:     '{{ __('common.swal_success') }}',
    swalError:       '{{ __('common.swal_error') }}',
    keySetPh:        '{{ __('pages.cfg_key_set_placeholder') }}',
    loadError:       '{{ __('pages.cfg_load_error') }}',
    inProgress:      '{{ __('pages.cfg_in_progress') }}',
    unexpectedError: '{{ __('pages.cfg_unexpected_error') }}',
};

function toggleFieldVisibility(fieldId) {
    var input = document.getElementById(fieldId);
    input.type = (input.type === 'password') ? 'text' : 'password';
}

function updatePaymentBadge(provider, sandbox) {
    var badge = document.getElementById('payment-status-badge');
    if (provider === 'kkiapay') {
        badge.className = 'badge bg-success fs-6 px-3 py-2';
        badge.textContent = 'KKiaPay — ' + (sandbox ? CFG_I18N.sandbox : CFG_I18N.production);
    } else if (provider === 'fedapay') {
        badge.className = 'badge bg-warning text-dark fs-6 px-3 py-2';
        badge.textContent = 'FedaPay — ' + (sandbox ? CFG_I18N.sandbox : CFG_I18N.production);
    } else {
        badge.className = 'badge bg-secondary fs-6 px-3 py-2';
        badge.textContent = CFG_I18N.paymentDisabled;
    }
}

function showProviderSection(provider) {
    document.getElementById('section-kkiapay').classList.toggle('d-none', provider !== 'kkiapay');
    document.getElementById('section-fedapay').classList.toggle('d-none', provider !== 'fedapay');
}

function loadPaymentConfig() {
    $.ajax({
        url: '{{ route('platform.kkiapay.config') }}',
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(data) {
            if (!data.status) return;

            var provider = data.active_payment_provider || 'none';
            $('input[name="active_payment_provider"][value="' + provider + '"]').prop('checked', true);
            showProviderSection(provider);

            $('#kkiapay_public_key').val(data.kkiapay_public_key || '');
            $('#kkiapay_sandbox').val(data.kkiapay_sandbox ? '1' : '0');
            if (data.has_kkiapay_private_key) {
                $('#kkiapay_private_key').attr('placeholder', CFG_I18N.keySetPh);
            }
            if (data.has_kkiapay_secret_key) {
                $('#kkiapay_secret_key').attr('placeholder', CFG_I18N.keySetPh);
            }

            $('#fedapay_public_key').val(data.fedapay_public_key || '');
            $('#fedapay_sandbox').val(data.fedapay_sandbox ? '1' : '0');
            if (data.has_fedapay_secret_key) {
                $('#fedapay_secret_key').attr('placeholder', CFG_I18N.keySetPh);
            }

            var isSandbox = provider === 'kkiapay' ? data.kkiapay_sandbox : data.fedapay_sandbox;
            updatePaymentBadge(provider, isSandbox);
        },
        error: function() {
            document.getElementById('payment-status-badge').textContent = CFG_I18N.loadError;
        }
    });
}

$('input[name="active_payment_provider"]').on('change', function() {
    var provider = this.value;
    showProviderSection(provider);
    var sandbox = provider === 'kkiapay'
        ? ($('#kkiapay_sandbox').val() === '1')
        : ($('#fedapay_sandbox').val() === '1');
    updatePaymentBadge(provider, sandbox);
});

$('#payment-config-form').on('submit', function(e) {
    e.preventDefault();

    var btn = document.getElementById('btn-save-payment');
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> ' + CFG_I18N.saving;

    var payload = {
        active_payment_provider: $('input[name="active_payment_provider"]:checked').val(),
        kkiapay_public_key:  $('#kkiapay_public_key').val(),
        kkiapay_private_key: $('#kkiapay_private_key').val(),
        kkiapay_secret_key:  $('#kkiapay_secret_key').val(),
        kkiapay_sandbox:     $('#kkiapay_sandbox').val() === '1' ? 1 : 0,
        fedapay_public_key: $('#fedapay_public_key').val(),
        fedapay_secret_key: $('#fedapay_secret_key').val(),
        fedapay_sandbox:    $('#fedapay_sandbox').val() === '1' ? 1 : 0,
    };

    $.ajax({
        url:         '{{ route('platform.kkiapay.update') }}',
        type:        'POST',
        contentType: 'application/json',
        data:        JSON.stringify(payload),
        headers:     { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json' },
        success: function(data) {
            if (data.status) {
                var chosenProvider = payload.active_payment_provider;
                $('input[name="active_payment_provider"]').prop('checked', false);
                $('input[name="active_payment_provider"][value="' + chosenProvider + '"]').prop('checked', true);
                showProviderSection(chosenProvider);
                var isSandbox = chosenProvider === 'kkiapay'
                    ? (payload.kkiapay_sandbox === 1)
                    : (payload.fedapay_sandbox === 1);
                updatePaymentBadge(chosenProvider, isSandbox);

                $('#kkiapay_private_key, #kkiapay_secret_key, #fedapay_secret_key').val('');

                Swal.fire({
                    title: CFG_I18N.swalSuccess,
                    text:  data.message,
                    icon:  'success',
                    timer: 2500,
                    showConfirmButton: false,
                });

                refreshKeyFields();
            } else {
                Swal.fire(CFG_I18N.swalError, data.message || CFG_I18N.unexpectedError, 'error');
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : CFG_I18N.unexpectedError;
            Swal.fire(CFG_I18N.swalError, msg, 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = CFG_I18N.btnSavePayment;
        }
    });
});

// ---- Africa's Talking config ----
$('#at-config-form').on('submit', function(e) {
    e.preventDefault();
    var btn = document.getElementById('btn-save-at');
    btn.disabled = true;
    btn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> ' + CFG_I18N.saving;

    var payload = {
        at_username:            $('#at_username').val(),
        at_api_key:             $('#at_api_key').val(),
        at_sender_id:           $('#at_sender_id').val(),
        at_whatsapp_product_id: $('#at_whatsapp_product_id').val(),
    };

    $.ajax({
        url:         '{{ route('platform.at.update') }}',
        type:        'POST',
        contentType: 'application/json',
        data:        JSON.stringify(payload),
        headers:     { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'), 'Accept': 'application/json' },
        success: function(data) {
            if (data.status) {
                $('#at_api_key').val('').attr('placeholder', CFG_I18N.keySetPh);
                Swal.fire({ title: CFG_I18N.swalSuccess, text: data.message, icon: 'success', timer: 2500, showConfirmButton: false });
            } else {
                Swal.fire(CFG_I18N.swalError, data.message || CFG_I18N.unexpectedError, 'error');
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : CFG_I18N.unexpectedError;
            Swal.fire(CFG_I18N.swalError, msg, 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = CFG_I18N.btnSaveAt;
        }
    });
});

// ---- Contact WhatsApp blocage ----
document.getElementById('btn-save-contact-blocage').addEventListener('click', function () {
    const btn = this;
    const numero = document.getElementById('whatsapp_contact_blocage').value.trim();
    btn.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-pulse me-1"></i> ' + CFG_I18N.inProgress;

    fetch('{{ route("platform.contact_blocage.update") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ whatsapp_contact_blocage: numero }),
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = CFG_I18N.btnSave;
        if (res.status) {
            Swal.fire({ icon: 'success', title: CFG_I18N.swalSuccess, text: res.message, timer: 2500, showConfirmButton: false });
        } else {
            Swal.fire({ icon: 'error', title: CFG_I18N.swalError, text: res.message });
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = CFG_I18N.btnSave;
        Swal.fire({ icon: 'error', title: CFG_I18N.swalError, text: CFG_I18N.unexpectedError });
    });
});

function refreshKeyFields() {
    $.ajax({
        url: '{{ route('platform.kkiapay.config') }}',
        type: 'GET',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(data) {
            if (!data.status) return;
            $('#kkiapay_public_key').val(data.kkiapay_public_key || '');
            $('#kkiapay_sandbox').val(data.kkiapay_sandbox ? '1' : '0');
            $('#fedapay_public_key').val(data.fedapay_public_key || '');
            $('#fedapay_sandbox').val(data.fedapay_sandbox ? '1' : '0');
            if (data.has_kkiapay_private_key) {
                $('#kkiapay_private_key').attr('placeholder', CFG_I18N.keySetPh);
            }
            if (data.has_kkiapay_secret_key) {
                $('#kkiapay_secret_key').attr('placeholder', CFG_I18N.keySetPh);
            }
            if (data.has_fedapay_secret_key) {
                $('#fedapay_secret_key').attr('placeholder', CFG_I18N.keySetPh);
            }
        }
    });
}
</script>
@endpush

@endsection
