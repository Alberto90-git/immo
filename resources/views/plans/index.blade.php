@extends('layouts.template')

@section('content')

@section('title')
  <title>Mon Abonnement</title>
@endsection

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Paramètres /</span> Mon Abonnement</h4>

    <!-- Plan actuel -->
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

    <!-- Plans disponibles -->
    <h5 class="mb-3">Changer de plan</h5>

    <div id="alertUpgrade" class="alert d-none mb-3" role="alert"></div>

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
                            @if($isEssai)
                                <li class="mb-1"><i class="bx bx-check text-success me-1"></i> 14 jours gratuits</li>
                            @endif
                        </ul>
                        @if($isCurrent)
                            <button class="btn btn-outline-primary" disabled>Plan actuel</button>
                        @elseif($isEssai)
                            <button class="btn btn-outline-secondary" disabled>Essai uniquement à l'inscription</button>
                        @else
                            <button class="btn btn-primary btn-upgrade" data-plan-id="{{ $plan->idplan }}" data-plan-nom="{{ $plan->nom }}" data-plan-prix="{{ number_format($plan->prix_annuel, 0, ',', '.') }}">
                                Choisir ce plan
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    document.querySelectorAll('.btn-upgrade').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const clickedBtn = this;
            const planId = clickedBtn.getAttribute('data-plan-id');
            const planNom = clickedBtn.getAttribute('data-plan-nom');
            const planPrix = clickedBtn.getAttribute('data-plan-prix');

            Swal.fire({
                title: 'Changer de plan ?',
                html: `Vous allez passer au plan <strong>${planNom}</strong> (${planPrix} XOF/an).<br><br>Votre compte sera temporairement suspendu en attendant la validation de l'administrateur.<br>Une facture vous sera envoyée par email.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1e40af',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Confirmer le changement',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    const alertEl = document.getElementById('alertUpgrade');
                    alertEl.classList.add('d-none');

                    // Désactiver tous les boutons
                    document.querySelectorAll('.btn-upgrade').forEach(b => {
                        b.disabled = true;
                        b.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Traitement...';
                    });

                    $.ajax({
                        url: '{{ route("plans.change") }}',
                        type: 'POST',
                        data: {
                            plan_id: planId,
                            _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.status) {
                                Swal.fire({
                                    title: 'Demande envoyée !',
                                    text: data.message,
                                    icon: 'success',
                                    confirmButtonColor: '#1e40af'
                                }).then(() => {
                                    location.reload();
                                });
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
            });
        });
    });

    function resetButtons() {
        document.querySelectorAll('.btn-upgrade').forEach(b => {
            b.disabled = false;
            b.innerHTML = 'Choisir ce plan';
        });
    }
</script>
@endsection
