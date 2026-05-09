@extends('layouts.template')

@section('content')

@section('title')
  <title>{{ __('pages.plan_title') }}</title>
@endsection

{{-- SDK de paiement (chargé conditionnellement) --}}
@if(($paymentProvider ?? 'none') === 'kkiapay')
<script src="https://cdn.kkiapay.me/k.js"></script>
@elseif(($paymentProvider ?? 'none') === 'fedapay')
<script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
@endif

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('pages.plan_breadcrumb_parent') }} /</span> {{ __('pages.plan_title') }}</h4>

    <!-- Plan actuel -->
    @can('voir-abonnement')
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('pages.plan_current_card') }}</h5>
                @if($direction && $direction->statut_abonnement)
                    @php
                        $statutColors = [
                            'actif' => 'success',
                            'essai' => 'warning',
                            'expire' => 'danger',
                            'suspendu' => 'secondary',
                        ];
                        $statutLabels = [
                            'actif' => __('pages.plan_status_active'),
                            'essai' => __('pages.plan_status_trial'),
                            'expire' => __('pages.plan_status_expired'),
                            'suspendu' => __('pages.plan_status_suspended'),
                        ];
                        $color = $statutColors[$direction->statut_abonnement] ?? 'secondary';
                        $label = $statutLabels[$direction->statut_abonnement] ?? $direction->statut_abonnement;
                    @endphp
                    <span class="badge bg-{{ $color }}">{{ $label }}</span>
                @endif
            </div>
            <div class="card-body">
                @if($currentPlan && $planInfo)
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="d-flex flex-column">
                                <small class="text-muted">{{ __('pages.plan_label_plan') }}</small>
                                <h5 class="mb-0 text-primary">{{ $currentPlan->nom }}</h5>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="d-flex flex-column">
                                <small class="text-muted">Prix mensuel</small>
                                <h5 class="mb-0">{{ format_price($currentPlan->prix_mensuel) }}/mois</h5>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="d-flex flex-column">
                                <small class="text-muted">{{ __('pages.plan_label_houses') }}</small>
                                <h5 class="mb-0">{{ $planInfo['maisons_utilisees'] }} / {{ $currentPlan->max_maisons == 0 ? __('pages.plan_unlimited_houses') : $currentPlan->max_maisons }}</h5>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="d-flex flex-column">
                                <small class="text-muted">{{ __('pages.plan_label_expiry') }}</small>
                                <h5 class="mb-0">
                                    @if($direction->abonnement_fin)
                                        {{ \Carbon\Carbon::parse($direction->abonnement_fin)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-muted mb-0">{{ __('pages.plan_no_active') }}</p>
                @endif
            </div>
        </div>
    @endcan

    <!-- Plans disponibles -->
    <h5 class="mb-3">{{ __('pages.plan_change_title') }}</h5>

    <div id="alertUpgrade" class="alert d-none mb-3" role="alert"></div>
    
    @can('change-abonnement')
        <div class="row">
            @foreach($plans as $plan)
                @php
                    $isCurrent = $currentPlan && $currentPlan->idplan == $plan->idplan;
                    $isEssai = $plan->code === 'essai';
                @endphp
                <div class="col-md-6 col-lg-3 mb-4">
                    <div class="card h-100 {{ $isCurrent ? 'border-primary' : '' }}">
                        @if($isCurrent)
                            <div class="bg-primary text-white text-center py-1" style="font-size:12px; font-weight:600;">
                                {{ __('pages.plan_current_badge') }}
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary">{{ $plan->nom }}</h5>
                            <h3 class="mb-0">
                                @if(floatval($plan->prix_mensuel) == 0)
                                    {{ __('pages.plan_free') }}
                                @else
                                    {{ format_price($plan->prix_mensuel) }}<small class="text-muted" style="font-size:14px;">/mois</small>
                                @endif
                            </h3>
                            <p class="text-muted small mb-3">{{ $plan->description }}</p>
                            <ul class="list-unstyled mb-3 flex-grow-1">
                                <li class="mb-1"><i class="bx bx-check text-success me-1"></i> {{ $plan->max_maisons == 0 ? __('pages.plan_unlimited_houses') : $plan->max_maisons . ' maison(s)' }}</li>
                                <li class="mb-1"><i class="bx bx-check text-success me-1"></i> {{ $plan->max_annexes == 0 ? __('pages.plan_no_branches') : $plan->max_annexes . ' annexe(s)' }}</li>
                                <li class="mb-1">
                                    <i class="bx bx-image-alt {{ is_null($plan->max_publicites) || $plan->max_publicites > 0 ? 'text-success' : 'text-danger' }} me-1"></i>
                                    @if(is_null($plan->max_publicites))
                                        {{ __('pages.plan_unlimited_ads') }}
                                    @elseif($plan->max_publicites == 0)
                                        {{ __('pages.plan_no_ads') }}
                                    @else
                                        {{ $plan->max_publicites }} publicité(s) max
                                    @endif
                                </li>
                                @if($isEssai)
                                    <li class="mb-1"><i class="bx bx-check text-success me-1"></i> {{ __('pages.plan_trial_days') }}</li>
                                @endif
                            </ul>
                            @can('change-abonnement')
                                @if($isCurrent)
                                    <button class="btn btn-outline-primary" disabled>{{ __('pages.plan_btn_current') }}</button>
                                @elseif($isEssai)
                                    <button class="btn btn-outline-secondary" disabled>{{ __('pages.plan_btn_trial_only') }}</button>
                                @else
                                    @if(floatval($plan->prix_mensuel) > 0)
                                    <div class="mb-2">
                                        <label class="form-label small fw-semibold mb-1" for="nb_mois_{{ $plan->idplan }}">
                                            <i class="bx bx-calendar me-1"></i> Durée (mois)
                                        </label>
                                        <div class="input-group input-group-sm">
                                            <input type="number" class="form-control nb-mois-input"
                                                   id="nb_mois_{{ $plan->idplan }}"
                                                   data-plan-id="{{ $plan->idplan }}"
                                                   data-prix-mensuel="{{ $plan->prix_mensuel }}"
                                                   value="1" min="1" max="24">
                                            <span class="input-group-text text-muted" id="total_display_{{ $plan->idplan }}">
                                                = {{ format_price($plan->prix_mensuel) }}
                                            </span>
                                        </div>
                                    </div>
                                    @endif
                                    <button class="btn btn-primary btn-upgrade"
                                            data-plan-id="{{ $plan->idplan }}"
                                            data-plan-nom="{{ $plan->nom }}"
                                            data-plan-prix-mensuel="{{ $plan->prix_mensuel }}">
                                        @if(($paymentEnabled ?? false) && floatval($plan->prix_mensuel) > 0)
                                            <i class="bx bx-credit-card me-1"></i>{{ __('pages.plan_btn_pay_change') }}
                                        @else
                                            {{ __('pages.plan_btn_choose') }}
                                        @endif
                                    </button>
                                @endif
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endcan
</div>

<script>
    var PLAN_I18N = {
        success:       '{{ __('pages.plan_swal_success') }}',
        unavailable:   '{{ __('pages.plan_swal_unavailable') }}',
        kkiaError:     '{{ __('pages.plan_swal_kkia_error') }}',
        payFailed:     '{{ __('pages.plan_swal_pay_failed') }}',
        kkiaFailed:    '{{ __('pages.plan_swal_kkia_failed') }}',
        fedaFailed:    '{{ __('pages.plan_swal_feda_failed') }}',
        payRequired:   '{{ __('pages.plan_swal_pay_required') }}',
        confirmChange: '{{ __('pages.plan_swal_confirm_change') }}',
        confirmBtn:    '{{ __('pages.plan_swal_confirm_btn') }}',
        genericError:  '{{ __('pages.plan_swal_generic_error') }}',
        processing:    '<span class="spinner-border spinner-border-sm me-1"></span> {{ __('pages.plan_processing') }}',
        cancel:        '{{ __('common.btn_cancel') }}',
        ok:            '{{ __('pages.ent_swal_ok') }}',
        btnPayChange:  '<i class="bx bx-credit-card me-1"></i>{{ __('pages.plan_btn_pay_change') }}',
        btnChoose:     '{{ __('pages.plan_btn_choose') }}',
    };

    const PAYMENT_ENABLED    = @json($paymentEnabled ?? false);
    const PAYMENT_PROVIDER   = @json($paymentProvider ?? 'none');
    const PAYMENT_PUBLIC_KEY = @json($paymentPublicKey ?? '');
    const PAYMENT_SANDBOX    = @json($paymentSandbox ?? true);
    const URL_PRORATION      = '{{ route("plans.proration") }}';
    const URL_CHANGE         = '{{ route("plans.change") }}';
    const CSRF_TOKEN         = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // ===== Mise à jour du total affiché quand on change la durée =====
    document.querySelectorAll('.nb-mois-input').forEach(function(input) {
        input.addEventListener('input', function() {
            const planId      = this.getAttribute('data-plan-id');
            const prixMensuel = parseFloat(this.getAttribute('data-prix-mensuel')) || 0;
            const nb          = Math.max(1, Math.min(24, parseInt(this.value) || 1));
            this.value        = nb;
            const total       = prixMensuel * nb;
            const display     = document.getElementById('total_display_' + planId);
            if (display) {
                display.textContent = '= ' + total.toLocaleString('fr-FR') + ' {{ get_symbole_devise() }}';
            }
        });
    });

    function getNbMois(planId) {
        const input = document.getElementById('nb_mois_' + planId);
        return input ? Math.max(1, Math.min(24, parseInt(input.value) || 1)) : 1;
    }

    function fmt(n) {
        return Math.round(n).toLocaleString('fr-FR');
    }

    // ===== Soumission du changement de plan =====
    function submitPlanChange(planId, transactionId, nbMois, extraDays) {
        const alertEl = document.getElementById('alertUpgrade');
        alertEl.classList.add('d-none');

        document.querySelectorAll('.btn-upgrade').forEach(b => {
            b.disabled = true;
            b.innerHTML = PLAN_I18N.processing;
        });

        $.ajax({
            url: URL_CHANGE,
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                plan_id:        planId,
                transaction_id: transactionId || null,
                nb_mois:        nbMois || 1,
                extra_days:     extraDays || 0,
                _token:         CSRF_TOKEN,
            }),
            headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
            success: function(data) {
                if (data.status) {
                    Swal.fire({
                        title: PLAN_I18N.success,
                        text: data.message,
                        icon: 'success',
                        confirmButtonColor: '#1e40af'
                    }).then(() => { location.reload(); });
                } else {
                    alertEl.classList.remove('d-none');
                    alertEl.classList.add('alert-danger');
                    alertEl.textContent = data.message;
                    resetButtons();
                }
            },
            error: function(xhr) {
                const msg = xhr.responseJSON ? xhr.responseJSON.message : PLAN_I18N.genericError;
                alertEl.classList.remove('d-none');
                alertEl.classList.add('alert-danger');
                alertEl.textContent = msg;
                resetButtons();
            }
        });
    }

    // ===== Lancement du widget de paiement =====
    function initiateUpgradePayment(planId, planNom, montantDu, nbMois, extraDays) {
        if (PAYMENT_PROVIDER === 'kkiapay') {
            if (typeof openKkiapayWidget !== 'function') {
                Swal.fire({ title: PLAN_I18N.unavailable, text: PLAN_I18N.kkiaError, icon: 'error', confirmButtonText: PLAN_I18N.ok });
                resetButtons();
                return;
            }
            openKkiapayWidget({
                amount:  montantDu,
                key:     PAYMENT_PUBLIC_KEY,
                sandbox: PAYMENT_SANDBOX,
                data:    JSON.stringify({ plan_id: planId }),
            });
            addSuccessListener(function(response) {
                submitPlanChange(planId, response.transactionId, nbMois, extraDays);
            });
            addFailedListener(function() {
                Swal.fire(PLAN_I18N.payFailed, PLAN_I18N.kkiaFailed, 'error');
                resetButtons();
            });
            addCloseListener(function() { resetButtons(); });

        } else if (PAYMENT_PROVIDER === 'fedapay') {
            FedaPay.init({
                public_key:  PAYMENT_PUBLIC_KEY,
                transaction: {
                    amount:      montantDu,
                    description: 'Abonnement Lokativ — ' + planNom + ' (' + nbMois + ' mois)',
                },
                onComplete: function(resp) {
                    if (resp.reason === FedaPay.DIALOG_DISMISSED) { resetButtons(); return; }
                    var trans = resp.transaction;
                    if (trans && trans.status === 'approved') {
                        submitPlanChange(planId, String(trans.id), nbMois, extraDays);
                    } else {
                        Swal.fire(PLAN_I18N.payFailed, PLAN_I18N.fedaFailed, 'error');
                        resetButtons();
                    }
                }
            }).open();
        }
    }

    // ===== Construction du HTML de confirmation avec prorata =====
    function buildConfirmHtml(planNom, nbMois, prixMensuel, proration, paymentEnabled) {
        const providerLabel = PAYMENT_PROVIDER === 'fedapay' ? 'FedaPay' : 'KKiaPay';
        const prixTotal     = proration.prix_total;
        const montantDu     = proration.montant_du;
        const credit        = proration.credit;
        const hasProration  = proration.has_proration;
        const joursRestants = proration.jours_restants;
        const extraDays     = proration.extra_days;
        const isPlanPaye    = prixMensuel > 0;

        let html = `<div style="text-align:left;font-size:.92rem;">`;

        // Ligne : nouveau plan
        html += `<div class="mb-2"><strong>Nouveau plan :</strong> ${planNom}</div>`;
        html += `<div class="mb-2"><strong>Durée choisie :</strong> ${nbMois} mois &times; ${fmt(prixMensuel)} {{ get_symbole_devise() }}/mois`;
        html += ` <span class="text-muted">= ${fmt(prixTotal)} {{ get_symbole_devise() }}</span></div>`;

        if (hasProration && isPlanPaye) {
            html += `<hr class="my-2">`;
            html += `<div class="mb-1 text-success"><i class="bx bx-transfer-alt me-1"></i><strong>Calcul au prorata</strong></div>`;
            html += `<div class="mb-1 text-muted small">Plan actuel : <strong>${proration.plan_actuel_nom}</strong> — ${joursRestants} jour(s) restant(s)</div>`;
            html += `<div class="mb-1">Crédit disponible : <strong class="text-success">− ${fmt(credit)} {{ get_symbole_devise() }}</strong></div>`;

            if (montantDu > 0) {
                html += `<hr class="my-2">`;
                html += `<div class="fw-bold fs-6">Montant à payer : <span class="text-primary">${fmt(montantDu)} {{ get_symbole_devise() }}</span></div>`;
                if (paymentEnabled) {
                    html += `<div class="text-muted small mt-1">Paiement via <strong>${providerLabel}</strong> — activation immédiate.</div>`;
                }
            } else {
                html += `<hr class="my-2">`;
                html += `<div class="fw-bold text-success fs-6"><i class="bx bx-check-circle me-1"></i>Aucun paiement requis — crédit suffisant</div>`;
                if (extraDays > 0) {
                    html += `<div class="text-muted small mt-1">Crédit excédentaire → <strong>+${extraDays} jour(s)</strong> ajouté(s) à votre abonnement.</div>`;
                }
            }
        } else if (isPlanPaye) {
            html += `<hr class="my-2">`;
            html += `<div class="fw-bold fs-6">Montant total : <span class="text-primary">${fmt(prixTotal)} {{ get_symbole_devise() }}</span></div>`;
            if (paymentEnabled) {
                html += `<div class="text-muted small mt-1">Paiement via <strong>${providerLabel}</strong> — activation immédiate.</div>`;
            } else {
                html += `<div class="text-muted small mt-1">Activation après validation par l'administrateur.</div>`;
            }
        } else {
            html += `<div class="text-muted small mt-1">Activation après validation par l'administrateur.</div>`;
        }

        // Avertissement annexes inaccessibles après rétrogradation
        if (proration.annexes_warning) {
            html += `<hr class="my-2">`;
            html += `<div class="alert alert-warning p-2 mb-0" style="font-size:.85rem;">
                       <i class="bx bx-info-circle me-1"></i>
                       Vous avez <strong>${proration.annexes_extra} annexe(s) supplémentaire(s)</strong>.
                       Ce plan ne permet pas la création de nouvelles annexes.
                       Vos annexes existantes restent en place mais seront inaccessibles jusqu'à un changement de plan.
                     </div>`;
        }

        html += `</div>`;
        return html;
    }

    // ===== Listener sur les boutons =====
    document.querySelectorAll('.btn-upgrade').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const planId      = this.getAttribute('data-plan-id');
            const planNom     = this.getAttribute('data-plan-nom');
            const prixMensuel = parseFloat(this.getAttribute('data-plan-prix-mensuel')) || 0;
            const nbMois      = getNbMois(planId);
            const isPlanPaye  = prixMensuel > 0;

            // Loader temporaire sur le bouton
            const origHtml = this.innerHTML;
            this.disabled  = true;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>';

            const self = this;

            // Récupération du prorata via l'API
            $.get(URL_PRORATION, { plan_id: planId, nb_mois: nbMois, _token: CSRF_TOKEN })
                .done(function(proration) {
                    self.disabled  = false;
                    self.innerHTML = origHtml;

                    if (!proration.status) {
                        Swal.fire('Erreur', proration.message || PLAN_I18N.genericError, 'error');
                        return;
                    }

                    const montantDu  = proration.montant_du;
                    const extraDays  = proration.extra_days;
                    const needsPay   = isPlanPaye && montantDu > 0 && PAYMENT_ENABLED;
                    const confirmTxt = needsPay
                        ? `<i class="bx bx-credit-card me-1"></i> Payer ${fmt(montantDu)} {{ get_symbole_devise() }}`
                        : (montantDu === 0 && isPlanPaye ? '<i class="bx bx-check me-1"></i> Confirmer (sans paiement)' : PLAN_I18N.confirmBtn);

                    Swal.fire({
                        title: needsPay ? PLAN_I18N.payRequired : PLAN_I18N.confirmChange,
                        html: buildConfirmHtml(planNom, nbMois, prixMensuel, proration, PAYMENT_ENABLED),
                        icon: needsPay ? 'info' : 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#1e40af',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: confirmTxt,
                        cancelButtonText: PLAN_I18N.cancel,
                    }).then(function(result) {
                        if (!result.isConfirmed) return;

                        if (needsPay) {
                            initiateUpgradePayment(planId, planNom, montantDu, nbMois, extraDays);
                        } else {
                            // Pas de paiement : crédit couvre tout, ou pas de prestataire actif
                            submitPlanChange(planId, null, nbMois, extraDays);
                        }
                    });
                })
                .fail(function() {
                    self.disabled  = false;
                    self.innerHTML = origHtml;
                    Swal.fire('Erreur', PLAN_I18N.genericError, 'error');
                });
        });
    });

    function resetButtons() {
        document.querySelectorAll('.btn-upgrade').forEach(function(b) {
            b.disabled = false;
            const prixMensuel = parseFloat(b.getAttribute('data-plan-prix-mensuel')) || 0;
            b.innerHTML = (prixMensuel > 0 && PAYMENT_ENABLED) ? PLAN_I18N.btnPayChange : PLAN_I18N.btnChoose;
        });
    }
</script>
@endsection
