@extends('layouts.template')

@section('content')

@section('title')
  <title>Mon Abonnement</title>
@endsection

{{-- SDK de paiement (chargé conditionnellement) --}}
@if(($paymentProvider ?? 'none') === 'kkiapay')
<script src="https://cdn.kkiapay.me/k.js"></script>
@elseif(($paymentProvider ?? 'none') === 'fedapay')
<script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
@endif

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Paramètres /</span> Mon Abonnement</h4>

    <!-- Plan actuel -->
    @can('voir-abonnement')
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Mon plan actuel</h5>
                @if($direction && $direction->statut_abonnement)
                    @php
                        $statutColors = [
                            'actif' => 'success',
                            'essai' => 'warning',
                            'expire' => 'danger',
                            'suspendu' => 'secondary',
                        ];
                        $statutLabels = [
                            'actif' => 'Actif',
                            'essai' => 'En attente de validation',
                            'expire' => 'Expiré',
                            'suspendu' => 'Suspendu',
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
                                <small class="text-muted">Plan</small>
                                <h5 class="mb-0 text-primary">{{ $currentPlan->nom }}</h5>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="d-flex flex-column">
                                <small class="text-muted">Prix annuel</small>
                                <h5 class="mb-0">{{ number_format($currentPlan->prix_annuel, 0, ',', '.') }} XOF</h5>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="d-flex flex-column">
                                <small class="text-muted">Maisons</small>
                                <h5 class="mb-0">{{ $planInfo['maisons_utilisees'] }} / {{ $currentPlan->max_maisons == 0 ? 'Illimité' : $currentPlan->max_maisons }}</h5>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="d-flex flex-column">
                                <small class="text-muted">Expiration</small>
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
                    <p class="text-muted mb-0">Aucun plan actif.</p>
                @endif
            </div>
        </div>
    @endcan

    <!-- Plans disponibles -->
    <h5 class="mb-3">Changer de plan</h5>

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
                                Plan actuel
                            </div>
                        @endif
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-primary">{{ $plan->nom }}</h5>
                            <h3 class="mb-1">
                                @if(floatval($plan->prix_annuel) == 0)
                                    Gratuit
                                @else
                                    {{ number_format($plan->prix_annuel, 0, ',', '.') }} <small class="text-muted" style="font-size:14px;">XOF/an</small>
                                @endif
                            </h3>
                            <p class="text-muted small mb-3">{{ $plan->description }}</p>
                            <ul class="list-unstyled mb-3 flex-grow-1">
                                <li class="mb-1"><i class="bx bx-check text-success me-1"></i> {{ $plan->max_maisons == 0 ? 'Maisons illimitées' : $plan->max_maisons . ' maison(s)' }}</li>
                                <li class="mb-1"><i class="bx bx-check text-success me-1"></i> {{ $plan->max_annexes == 0 ? 'Pas d\'annexes' : $plan->max_annexes . ' annexe(s)' }}</li>
                                <li class="mb-1">
                                    <i class="bx bx-image-alt {{ is_null($plan->max_publicites) || $plan->max_publicites > 0 ? 'text-success' : 'text-danger' }} me-1"></i>
                                    @if(is_null($plan->max_publicites))
                                        Publicités illimitées
                                    @elseif($plan->max_publicites == 0)
                                        Aucune publicité
                                    @else
                                        {{ $plan->max_publicites }} publicité(s) max
                                    @endif
                                </li>
                                @if($isEssai)
                                    <li class="mb-1"><i class="bx bx-check text-success me-1"></i> 14 jours gratuits</li>
                                @endif
                            </ul>
                            @can('change-abonnement')
                                @if($isCurrent)
                                    <button class="btn btn-outline-primary" disabled>Plan actuel</button>
                                @elseif($isEssai)
                                    <button class="btn btn-outline-secondary" disabled>Essai uniquement à l'inscription</button>
                                @else
                                    <button class="btn btn-primary btn-upgrade"
                                            data-plan-id="{{ $plan->idplan }}"
                                            data-plan-nom="{{ $plan->nom }}"
                                            data-plan-prix="{{ number_format($plan->prix_annuel, 0, ',', '.') }}"
                                            data-plan-prix-raw="{{ $plan->prix_annuel }}">
                                        @if(($paymentEnabled ?? false) && floatval($plan->prix_annuel) > 0)
                                            <i class="bx bx-credit-card me-1"></i>Payer et changer de plan
                                        @else
                                            Choisir ce plan
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
    // ===== Config paiement injectée depuis le contrôleur =====
    const PAYMENT_ENABLED    = @json($paymentEnabled ?? false);
    const PAYMENT_PROVIDER   = @json($paymentProvider ?? 'none');
    const PAYMENT_PUBLIC_KEY = @json($paymentPublicKey ?? '');
    const PAYMENT_SANDBOX    = @json($paymentSandbox ?? true);

    // ===== Soumission du changement de plan (avec ou sans paiement) =====
    function submitPlanChange(planId, transactionId) {
        const alertEl = document.getElementById('alertUpgrade');
        alertEl.classList.add('d-none');

        document.querySelectorAll('.btn-upgrade').forEach(b => {
            b.disabled = true;
            b.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Traitement...';
        });

        $.ajax({
            url: '{{ route("plans.change") }}',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                plan_id:        planId,
                transaction_id: transactionId || null,
                _token:         document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            success: function(data) {
                if (data.status) {
                    Swal.fire({
                        title: 'Succès !',
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
                const msg = xhr.responseJSON ? xhr.responseJSON.message : 'Une erreur est survenue.';
                alertEl.classList.remove('d-none');
                alertEl.classList.add('alert-danger');
                alertEl.textContent = msg;
                resetButtons();
            }
        });
    }

    // ===== Paiement puis changement de plan =====
    function initiateUpgradePayment(planId, planNom, planPrix, planPrixRaw) {
        if (PAYMENT_PROVIDER === 'kkiapay') {
            if (typeof openKkiapayWidget !== 'function') {
                Swal.fire({
                    title: 'Service indisponible',
                    text: 'Le widget de paiement KKiaPay n\'a pas pu être chargé. Vérifiez votre connexion internet et réessayez.',
                    icon: 'error', confirmButtonText: 'OK',
                });
                resetButtons();
                return;
            }
            openKkiapayWidget({
                amount:  planPrixRaw,
                key:     PAYMENT_PUBLIC_KEY,
                sandbox: PAYMENT_SANDBOX,
                data:    JSON.stringify({ plan_id: planId }),
            });

            addSuccessListener(function(response) {
                submitPlanChange(planId, response.transactionId);
            });

            addFailedListener(function() {
                Swal.fire('Paiement échoué', 'Le paiement KKiaPay n\'a pas abouti. Veuillez réessayer.', 'error');
                resetButtons();
            });

            addCloseListener(function() { resetButtons(); });

        } else if (PAYMENT_PROVIDER === 'fedapay') {
            FedaPay.init({
                public_key:  PAYMENT_PUBLIC_KEY,
                transaction: {
                    amount:      planPrixRaw,
                    description: 'Changement de plan Lokativ — ' + planNom,
                },
                onComplete: function(resp) {
                    if (resp.reason === FedaPay.DIALOG_DISMISSED) {
                        resetButtons();
                        return;
                    }
                    var trans = resp.transaction;
                    if (trans && trans.status === 'approved') {
                        submitPlanChange(planId, String(trans.id));
                    } else {
                        Swal.fire('Paiement échoué', 'Le paiement FedaPay n\'a pas été approuvé. Veuillez réessayer.', 'error');
                        resetButtons();
                    }
                }
            }).open();
        }
    }

    // ===== Listener sur les boutons =====
    document.querySelectorAll('.btn-upgrade').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const planId     = this.getAttribute('data-plan-id');
            const planNom    = this.getAttribute('data-plan-nom');
            const planPrix   = this.getAttribute('data-plan-prix');
            const planPrixRaw = parseFloat(this.getAttribute('data-plan-prix-raw')) || 0;
            const isPlanPaye  = planPrixRaw > 0;

            if (isPlanPaye && PAYMENT_ENABLED) {
                // Plan payant + prestataire actif → confirmer puis payer
                const providerLabel = PAYMENT_PROVIDER === 'fedapay' ? 'FedaPay' : 'KKiaPay';
                Swal.fire({
                    title: 'Paiement requis',
                    html: `Vous allez passer au plan <strong>${planNom}</strong>.<br>
                           Montant : <strong>${planPrix} XOF/an</strong>.<br><br>
                           Le paiement sera traité via <strong>${providerLabel}</strong>.
                           Votre plan sera activé immédiatement après confirmation du paiement.`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#1e40af',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `<i class="bx bx-credit-card me-1"></i> Payer ${planPrix} XOF`,
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        initiateUpgradePayment(planId, planNom, planPrix, planPrixRaw);
                    }
                });
            } else {
                // Plan gratuit ou pas de prestataire → confirmation simple
                Swal.fire({
                    title: 'Changer de plan ?',
                    html: `Vous allez passer au plan <strong>${planNom}</strong>${planPrixRaw > 0 ? ' (' + planPrix + ' XOF/an)' : ''}.<br><br>
                           Votre compte sera temporairement suspendu en attendant la validation de l'administrateur.<br>
                           Une facture vous sera envoyée par email.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#1e40af',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmer le changement',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitPlanChange(planId, null);
                    }
                });
            }
        });
    });

    function resetButtons() {
        document.querySelectorAll('.btn-upgrade').forEach(function(b) {
            b.disabled = false;
            const prixRaw = parseFloat(b.getAttribute('data-plan-prix-raw')) || 0;
            if (prixRaw > 0 && PAYMENT_ENABLED) {
                b.innerHTML = '<i class="bx bx-credit-card me-1"></i>Payer et changer de plan';
            } else {
                b.innerHTML = 'Choisir ce plan';
            }
        });
    }
</script>
@endsection
