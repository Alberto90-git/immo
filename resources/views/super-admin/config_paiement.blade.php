@extends('layouts.template')

@section('title')
  <title>Configuration Prestataire de paiement</title>
@endsection

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold py-3 mb-4">
        <span class="text-muted fw-light">Super Admin /</span> Prestataire de paiement
    </h4>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex align-items-center justify-content-between py-3"
                     style="background:linear-gradient(135deg,#1e40af,#3b82f6);color:#fff;border-radius:8px 8px 0 0;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-credit-card fs-4"></i>
                        <div>
                            <h6 class="mb-0 fw-bold">Prestataire de paiement</h6>
                            <small class="opacity-75">Choisissez et configurez la passerelle de paiement pour les abonnements</small>
                        </div>
                    </div>
                    @php
                        $apInit  = $activePaymentProvider ?? 'none';
                        $sbInit  = $activePaymentSandbox ?? true;
                        $badgeClass = $apInit === 'kkiapay' ? 'badge bg-success fs-6 px-3 py-2'
                                    : ($apInit === 'fedapay' ? 'badge bg-warning text-dark fs-6 px-3 py-2'
                                    : 'badge bg-secondary fs-6 px-3 py-2');
                        $badgeText  = $apInit === 'kkiapay' ? 'KKiaPay — ' . ($sbInit ? 'Sandbox' : 'Production')
                                    : ($apInit === 'fedapay' ? 'FedaPay — ' . ($sbInit ? 'Sandbox' : 'Production')
                                    : 'Paiement désactivé');
                    @endphp
                    <span id="payment-status-badge" class="{{ $badgeClass }}">
                        {{ $badgeText }}
                    </span>
                </div>
                <div class="card-body pt-4">
                    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                        <i class="bx bx-info-circle me-2 fs-5"></i>
                        <div>
                            Activez un prestataire pour que les plans payants requièrent un paiement lors de
                            <strong>l'inscription</strong> et du <strong>changement de plan</strong>.
                            Par défaut, aucun prestataire n'est actif.
                        </div>
                    </div>

                    <form id="payment-config-form">
                        @csrf

                        {{-- Choix du prestataire actif --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold fs-6">
                                <i class="bx bx-list-check me-1 text-primary"></i>Prestataire actif
                            </label>
                            @php $ap = $activePaymentProvider ?? 'none'; @endphp
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check border rounded p-3 pe-4 {{ $ap === 'none' ? 'border-primary' : '' }}" style="cursor:pointer;">
                                    <input class="form-check-input" type="radio" name="active_payment_provider"
                                           id="provider_none" value="none" {{ $ap === 'none' ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="provider_none" style="cursor:pointer;">
                                        <i class="bx bx-block me-1 text-secondary"></i> Aucun paiement
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
                            <h6 class="fw-bold mb-3 text-primary"><i class="bx bx-credit-card me-1"></i>Configuration KKiaPay</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-key me-1 text-warning"></i>Clé publique (Public Key) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="kkiapay_public_key"
                                           name="kkiapay_public_key" placeholder="Votre clé publique KKiaPay"
                                           value="{{ $paymentCfgData['kkiapay_public_key'] ?? '' }}">
                                    <div class="form-text">Utilisée côté client pour ouvrir le widget de paiement.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-lock me-1 text-danger"></i>Clé privée (Private Key) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="kkiapay_private_key"
                                               name="kkiapay_private_key"
                                               placeholder="{{ ($paymentCfgData['has_kkiapay_private'] ?? false) ? '••••••••••••••••' : 'Entrer la clé privée' }}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleFieldVisibility('kkiapay_private_key')">
                                            <i class="bx bx-show"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-danger fw-semibold"><i class="bx bx-error-circle me-1"></i>Confidentielle.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-shield me-1 text-danger"></i>Clé secrète (Secret Key) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="kkiapay_secret_key"
                                               name="kkiapay_secret_key"
                                               placeholder="{{ ($paymentCfgData['has_kkiapay_secret'] ?? false) ? '••••••••••••••••' : 'Entrer la clé secrète' }}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleFieldVisibility('kkiapay_secret_key')">
                                            <i class="bx bx-show"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-danger fw-semibold"><i class="bx bx-error-circle me-1"></i>Vérification côté serveur.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-test-tube me-1 text-info"></i>Environnement KKiaPay
                                    </label>
                                    <select class="form-select" id="kkiapay_sandbox" name="kkiapay_sandbox">
                                        <option value="1" {{ ($paymentCfgData['kkiapay_sandbox'] ?? true) ? 'selected' : '' }}>Sandbox (Mode test)</option>
                                        <option value="0" {{ !($paymentCfgData['kkiapay_sandbox'] ?? true) ? 'selected' : '' }}>Production (Paiements réels)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Section FedaPay --}}
                        <div id="section-fedapay" class="{{ ($activePaymentProvider ?? 'none') === 'fedapay' ? '' : 'd-none' }}">
                            <hr class="my-3">
                            <h6 class="fw-bold mb-3 text-warning"><i class="bx bx-credit-card-alt me-1"></i>Configuration FedaPay</h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-key me-1 text-warning"></i>Clé publique (Public Key) <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" id="fedapay_public_key"
                                           name="fedapay_public_key" placeholder="pk_sandbox_... ou pk_live_..."
                                           value="{{ $paymentCfgData['fedapay_public_key'] ?? '' }}">
                                    <div class="form-text">Utilisée côté client pour initialiser le widget FedaPay.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-shield me-1 text-danger"></i>Clé secrète (Secret Key) <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="fedapay_secret_key"
                                               name="fedapay_secret_key"
                                               placeholder="{{ ($paymentCfgData['has_fedapay_secret'] ?? false) ? '••••••••••••••••' : 'Entrer la clé secrète' }}">
                                        <button type="button" class="btn btn-outline-secondary" onclick="toggleFieldVisibility('fedapay_secret_key')">
                                            <i class="bx bx-show"></i>
                                        </button>
                                    </div>
                                    <div class="form-text text-danger fw-semibold"><i class="bx bx-error-circle me-1"></i>Vérification côté serveur.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="bx bx-test-tube me-1 text-info"></i>Environnement FedaPay
                                    </label>
                                    <select class="form-select" id="fedapay_sandbox" name="fedapay_sandbox">
                                        <option value="1" {{ ($paymentCfgData['fedapay_sandbox'] ?? true) ? 'selected' : '' }}>Sandbox (Mode test)</option>
                                        <option value="0" {{ !($paymentCfgData['fedapay_sandbox'] ?? true) ? 'selected' : '' }}>Production (Paiements réels)</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="submit" class="btn btn-primary px-4" id="btn-save-payment">
                                <i class="bx bx-save me-1"></i> Enregistrer la configuration
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="loadPaymentConfig()">
                                <i class="bx bx-refresh me-1"></i> Recharger
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function toggleFieldVisibility(fieldId) {
    var input = document.getElementById(fieldId);
    input.type = (input.type === 'password') ? 'text' : 'password';
}

function updatePaymentBadge(provider, sandbox) {
    var badge = document.getElementById('payment-status-badge');
    if (provider === 'kkiapay') {
        badge.className = 'badge bg-success fs-6 px-3 py-2';
        badge.textContent = 'KKiaPay — ' + (sandbox ? 'Sandbox' : 'Production');
    } else if (provider === 'fedapay') {
        badge.className = 'badge bg-warning text-dark fs-6 px-3 py-2';
        badge.textContent = 'FedaPay — ' + (sandbox ? 'Sandbox' : 'Production');
    } else {
        badge.className = 'badge bg-secondary fs-6 px-3 py-2';
        badge.textContent = 'Paiement désactivé';
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
                $('#kkiapay_private_key').attr('placeholder', '••••••••• (définie — laisser vide pour conserver)');
            }
            if (data.has_kkiapay_secret_key) {
                $('#kkiapay_secret_key').attr('placeholder', '••••••••• (définie — laisser vide pour conserver)');
            }

            $('#fedapay_public_key').val(data.fedapay_public_key || '');
            $('#fedapay_sandbox').val(data.fedapay_sandbox ? '1' : '0');
            if (data.has_fedapay_secret_key) {
                $('#fedapay_secret_key').attr('placeholder', '••••••••• (définie — laisser vide pour conserver)');
            }

            var isSandbox = provider === 'kkiapay' ? data.kkiapay_sandbox : data.fedapay_sandbox;
            updatePaymentBadge(provider, isSandbox);
        },
        error: function() {
            document.getElementById('payment-status-badge').textContent = 'Erreur de chargement';
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
    btn.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i> Enregistrement...';

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
                    title: 'Succès',
                    text:  data.message,
                    icon:  'success',
                    timer: 2500,
                    showConfirmButton: false,
                });

                refreshKeyFields();
            } else {
                Swal.fire('Erreur', data.message || 'Une erreur est survenue.', 'error');
            }
        },
        error: function(xhr) {
            var msg = xhr.responseJSON && xhr.responseJSON.message
                ? xhr.responseJSON.message
                : 'Une erreur est survenue.';
            Swal.fire('Erreur', msg, 'error');
        },
        complete: function() {
            btn.disabled = false;
            btn.innerHTML = '<i class="bx bx-save me-1"></i> Enregistrer la configuration';
        }
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
                $('#kkiapay_private_key').attr('placeholder', '••••••••• (définie — laisser vide pour conserver)');
            }
            if (data.has_kkiapay_secret_key) {
                $('#kkiapay_secret_key').attr('placeholder', '••••••••• (définie — laisser vide pour conserver)');
            }
            if (data.has_fedapay_secret_key) {
                $('#fedapay_secret_key').attr('placeholder', '••••••••• (définie — laisser vide pour conserver)');
            }
        }
    });
}
</script>
@endpush

@endsection
