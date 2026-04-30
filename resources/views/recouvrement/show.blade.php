@extends('layouts.template')

@section('title')
<title>{{ __('ui.recouvrement.dossier_title') }} – {{ $dossier->locataire->prenom }} {{ $dossier->locataire->nom }}</title>
@endsection

@section('content')
@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light"><a href="{{ route('recouvrement.index') }}">{{ __('ui.recouvrement.title') }}</a> /</span>
      {{ $dossier->locataire->prenom }} {{ $dossier->locataire->nom }}
    </h4>
    <div class="d-flex gap-2 flex-wrap">
      @can('download-recouvrement')
      <button class="btn btn-outline-dark btn-sm" id="btnPreviewMED">
        <i class="bx bx-show me-1"></i> {{ __('ui.recouvrement.preview_med_btn') }}
      </button>
      <button class="btn btn-primary btn-sm" id="btnSigner">
        <i class="bx bx-edit-alt me-1"></i> {{ __('ui.recouvrement.sign_btn') }}
      </button>
      @endcan
      @can('delete-recouvrement')
      <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalCloturer">
        <i class="bx bx-archive-in me-1"></i> {{ __('ui.recouvrement.close_btn') }}
      </button>
      @endcan
    </div>
  </div>

  <div class="row g-4">

    {{-- ── Colonne gauche ───────────────────────────────────────────────────── --}}
    <div class="col-lg-4">

      {{-- Fiche locataire --}}
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header">
          <h6 class="mb-0"><i class="bx bx-user me-2 text-primary"></i>{{ __('ui.recouvrement.card_tenant') }}</h6>
        </div>
        <div class="card-body">
          <div class="fw-semibold fs-5">{{ $dossier->locataire->prenom }} {{ $dossier->locataire->nom }}</div>
          <div class="text-muted mb-2">{{ $dossier->locataire->telephone }}</div>
          @if($dossier->locataire->email)
            <div class="small text-muted mb-2"><i class="bx bx-envelope me-1"></i>{{ $dossier->locataire->email }}</div>
          @endif
          <div class="small">
            <i class="bx bx-home me-1 text-muted"></i>
            {{ $dossier->locataire->maison->nom_maison ?? '–' }}
            @if($dossier->locataire->chambre) — {{ __('ui.recouvrement.room_prefix') }} {{ $dossier->locataire->chambre->numero_chambre }} @endif
          </div>
        </div>
      </div>

      {{-- Données du dossier --}}
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="mb-0"><i class="bx bx-folder-open me-2 text-warning"></i>{{ __('ui.recouvrement.card_dossier') }}</h6>
          {!! $dossier->statut_badge !!}
        </div>
        <div class="card-body">
          <table class="table table-sm table-borderless mb-0">
            <tr><td class="text-muted">{{ __('ui.recouvrement.col_due') }}</td><td class="fw-semibold text-danger">{{ format_price($dossier->montant_du) }}</td></tr>
            <tr><td class="text-muted">{{ __('ui.recouvrement.col_recovered') }}</td><td class="fw-semibold text-success">{{ format_price($dossier->montant_recouvre) }}</td></tr>
            <tr><td class="text-muted">{{ __('ui.recouvrement.col_remaining_due') }}</td><td class="fw-bold text-danger">{{ format_price($dossier->montant_reste) }}</td></tr>
            <tr><td class="text-muted">{{ __('ui.recouvrement.modal_nb_months') }}</td><td>{{ $dossier->nb_mois_impayes }}</td></tr>
            @if($dossier->date_dernier_paiement)
            <tr><td class="text-muted">{{ __('ui.recouvrement.col_last_payment') }}</td><td>{{ $dossier->date_dernier_paiement->format('d/m/Y') }}</td></tr>
            @endif
            @if($dossier->date_assignation)
            <tr><td class="text-muted">{{ __('ui.recouvrement.col_assignation') }}</td><td>{{ $dossier->date_assignation->format('d/m/Y') }}</td></tr>
            @endif
          </table>
        </div>
      </div>

      {{-- Score de risque --}}
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body text-center">
          <div class="fs-1 fw-bold text-{{ $dossier->score_color }}">{{ $dossier->score_risque }}<span class="fs-6 fw-normal">/100</span></div>
          <div class="badge bg-{{ $dossier->score_color }} fs-6 mb-1">{{ $dossier->score_label }}</div>
          <div class="text-muted small">{{ __('ui.recouvrement.risk_score_label') }}</div>
          <div class="progress mt-2" style="height:6px;">
            <div class="progress-bar bg-{{ $dossier->score_color }}" style="width:{{ $dossier->score_risque }}%"></div>
          </div>
        </div>
      </div>

      {{-- Actions rapides --}}
      @can('modify-recouvrement')
      <div class="card border-0 shadow-sm">
        <div class="card-header"><h6 class="mb-0"><i class="bx bx-send me-2 text-info"></i>{{ __('ui.recouvrement.relance_title') }}</h6></div>
        <div class="card-body">
          <form id="formRelancer">
            <input type="hidden" name="transaction_id" id="smsTransactionId">
            @php
              $prochain       = $dossier->prochain_type_relance;
              $optionsRelance = $dossier->options_relance;
            @endphp
            <div class="mb-2">
              <label class="form-label small fw-semibold">{{ __('ui.recouvrement.relance_type') }}</label>
              <select name="type" class="form-select form-select-sm" required>
                @foreach($optionsRelance as $value => $label)
                  <option value="{{ $value }}" {{ $value === $prochain ? 'selected' : '' }}>
                    {{ $label }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="mb-2">
              <label class="form-label small fw-semibold">{{ __('ui.recouvrement.relance_channel') }}</label>
              <select name="canal" id="selectCanal" class="form-select form-select-sm" required>
                <option value="email">Email</option>
                <option value="sms">SMS</option>
                <option value="whatsapp">WhatsApp</option>
                <option value="courrier">Courrier (manuel)</option>
              </select>
            </div>

            {{-- Bloc paiement SMS (affiché uniquement si canal = SMS) --}}
            @if($smsCost > 0 && $paymentEnabled)
            <div id="blockPaiementSms" class="d-none mb-3">
              <div class="alert alert-warning small py-2 mb-2">
                <i class="bx bx-credit-card me-1"></i>
                {!! __('ui.recouvrement.sms_cost_alert', ['price' => '<strong>'.number_format($smsCost, 0, ',', ' ').' '.$smsCurrency.'</strong>']) !!}
              </div>
              <div id="smsPaiementStatut" class="d-none alert alert-success small py-2 mb-2">
                <i class="bx bx-check-circle me-1"></i> {{ __('ui.recouvrement.sms_paid_confirm') }}
              </div>
              <button type="button" id="btnPayerSms" class="btn btn-warning btn-sm w-100">
                <i class="bx bx-credit-card me-1"></i> {{ __('ui.recouvrement.sms_pay_label', ['price' => number_format($smsCost, 0, ',', ' ').' '.$smsCurrency]) }}
              </button>
            </div>
            @endif

            <div class="mb-3">
              <label class="form-label small fw-semibold">{{ __('ui.recouvrement.relance_msg_label') }}</label>
              <textarea name="message" class="form-control form-control-sm" rows="3" placeholder="{{ __('ui.recouvrement.relance_msg_ph') }}"></textarea>
            </div>
            <button type="button" id="btnRelancer" class="btn btn-primary btn-sm w-100">
              <i class="bx bx-send me-1"></i> {{ __('ui.recouvrement.relance_send') }}
            </button>
          </form>
        </div>
      </div>
      @endcan

    </div>

    {{-- ── Colonne droite ───────────────────────────────────────────────────── --}}
    <div class="col-lg-8">

      {{-- Timeline des relances --}}
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h6 class="mb-0"><i class="bx bx-history me-2 text-primary"></i>{{ __('ui.recouvrement.tab_history') }}</h6>
          <span class="badge bg-primary rounded-pill" id="relancesCount">{{ $dossier->relances->count() }}</span>
        </div>
        <div class="card-body p-0" id="relancesTimeline"
          style="{{ $dossier->relances->count() > 4 ? 'max-height:320px;overflow-y:auto;' : '' }}">
          @include('recouvrement.partials.relances_timeline', ['dossier' => $dossier])
        </div>
      </div>

      {{-- Plan d'apurement --}}
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
          <h6 class="mb-0"><i class="bx bx-calendar-check me-2 text-success"></i>{{ __('ui.recouvrement.plan_title') }}</h6>
          @can('modify-recouvrement')
          <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#modalPlanApurement">
            <i class="bx bx-plus me-1"></i> {{ __('ui.recouvrement.plan_new') }}
          </button>
          @endcan
        </div>
        @if($dossier->echeances->count())
        <div class="table-responsive" style="{{ $dossier->echeances->count() > 4 ? 'max-height:280px;overflow-y:auto;' : '' }}">
          <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>{{ __('ui.recouvrement.plan_col_due_date') }}</th>
                <th>{{ __('ui.recouvrement.plan_amount') }}</th>
                <th>{{ __('ui.recouvrement.col_status') }}</th>
                <th>{{ __('ui.recouvrement.plan_col_payment') }}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($dossier->echeances as $ech)
              <tr>
                <td class="text-muted">{{ $ech->numero }}</td>
                <td>
                  {{ $ech->date_echeance->format('d/m/Y') }}
                  @if($ech->est_en_retard)
                    <span class="badge bg-danger ms-1">{{ __('ui.recouvrement.plan_late') }}</span>
                  @endif
                </td>
                <td>{{ number_format($ech->montant, 0, ',', ' ') }}</td>
                <td>{!! $ech->statut_badge !!}</td>
                <td class="small text-muted">{{ $ech->date_paiement ? $ech->date_paiement->format('d/m/Y') : '–' }}</td>
                <td>
                  @if($ech->statut !== 'paye')
                  @can('modify-recouvrement')
                  <button type="button" class="btn btn-xs btn-outline-success py-0 px-2 btn-payer-echeance"
                    data-id="{{ $ech->id }}"
                    data-url="{{ route('recouvrement.echeance_payer', $ech->id) }}">
                    <i class="bx bx-check"></i> {{ __('ui.recouvrement.plan_mark_paid') }}
                  </button>
                  @endcan
                  @endif
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        @else
        <div class="card-body text-center text-muted py-3">{{ __('ui.recouvrement.plan_none') }}</div>
        @endif
      </div>

      {{-- Notes juridiques / Contentieux --}}
      <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
          <h6 class="mb-0"><i class="bx bx-notepad me-2 text-dark"></i>{{ __('ui.recouvrement.legal_notes_title') }}</h6>
          @can('modify-recouvrement')
          <button class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#modalContentieux">
            <i class="bx bx-gavel me-1"></i> {{ __('ui.recouvrement.contentieux_btn') }}
          </button>
          @endcan
        </div>
        <div class="card-body">
          @can('modify-recouvrement')
          <form id="formUpdate">
            <div class="mb-3">
              <label class="form-label small fw-semibold">{{ __('ui.recouvrement.demeure_delay_label') }}</label>
              <input type="number" name="delai_paiement" class="form-control form-control-sm"
                value="{{ $dossier->delai_paiement ?? 8 }}" min="1" max="90">
              <div class="form-text">{{ __('ui.recouvrement.demeure_delay_help') }}</div>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">{{ __('ui.recouvrement.legal_notes_label') }}</label>
              <textarea name="notes_juridiques" class="form-control form-control-sm" rows="4">{{ $dossier->notes_juridiques }}</textarea>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-semibold">{{ __('ui.recouvrement.recovered_update_label') }}</label>
              <input type="number" name="montant_recouvre" class="form-control form-control-sm" value="{{ $dossier->montant_recouvre }}" min="0">
            </div>
            <button type="button" id="btnUpdate" class="btn btn-sm btn-primary">
              <i class="bx bx-save me-1"></i> {{ __('ui.common.save') }}
            </button>
          </form>
          @else
          <p class="text-muted">{{ $dossier->notes_juridiques ?: __('ui.recouvrement.no_note') }}</p>
          @endcan
        </div>
      </div>

    </div>
  </div>

</div>{{-- /container --}}

{{-- ── Modal : Plan d'apurement ──────────────────────────────────────────────── --}}
<div class="modal fade" id="modalPlanApurement" tabindex="-1">
  <div class="modal-dialog">
    <form id="formPlanApurement" class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white"><i class="bx bx-calendar-check me-2"></i>{{ __('ui.recouvrement.plan_create_title') }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-info small mb-3 text-dark">
          {{ __('ui.recouvrement.plan_remaining') }} : <strong>{{ format_price($dossier->montant_reste) }}</strong>
        </div>
        <div class="row g-3">
          <div class="col-sm-6">
            <label class="form-label fw-semibold">{{ __('ui.recouvrement.plan_nb_echeances') }}</label>
            <input type="number" name="nb_echeances" class="form-control" min="1" max="36" value="3" required id="nbEcheances">
          </div>
          <div class="col-sm-6">
            <label class="form-label fw-semibold">{{ __('ui.recouvrement.plan_amount_label') }} ({{ get_symbole_devise() }})</label>
            <input type="number" name="montant_echeance" class="form-control" value="{{ round($dossier->montant_reste / 3) }}" min="0" required id="montantEcheance">
          </div>
        </div>
        <div id="alertTotalPlan" class="alert alert-danger small mt-3 mb-0 d-none"></div>
        <div class="mt-3">
          <label class="form-label fw-semibold">{{ __('ui.recouvrement.plan_first_date') }}</label>
          <input type="date" name="date_premiere" class="form-control" value="{{ now()->addMonth()->format('Y-m-d') }}" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('ui.common.cancel') }}</button>
        <button type="button" id="btnPlanApurement" class="btn btn-primary"><i class="bx bx-save me-1"></i>{{ __('ui.recouvrement.plan_create_btn') }}</button>
      </div>
    </form>
  </div>
</div>

{{-- ── Modal : Escalade contentieux ───────────────────────────────────────────── --}}
<div class="modal fade" id="modalContentieux" tabindex="-1">
  <div class="modal-dialog">
    <form id="formContentieux" class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white"><i class="bx bx-gavel me-2"></i>{{ __('ui.recouvrement.contentieux_btn') }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning small text-dark">{!! __('ui.recouvrement.contentieux_alert') !!}</div>
        <div class="mb-3">
          <label class="form-label fw-semibold">{{ __('ui.recouvrement.contentieux_date_label') }}</label>
          <input type="date" name="date_assignation" class="form-control">
        </div>
        <div class="mb-0">
          <label class="form-label fw-semibold">{{ __('ui.recouvrement.contentieux_notes_label') }}</label>
          <textarea name="notes_juridiques" class="form-control" rows="3">{{ $dossier->notes_juridiques }}</textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('ui.common.cancel') }}</button>
        <button type="button" id="btnContentieux" class="btn btn-primary"><i class="bx bx-gavel me-1"></i>{{ __('ui.recouvrement.contentieux_confirm') }}</button>
      </div>
    </form>
  </div>
</div>

{{-- ── Modal : Clôturer ─────────────────────────────────────────────────────── --}}
<div class="modal fade" id="modalCloturer" tabindex="-1">
  <div class="modal-dialog modal-sm">
    <form id="formCloturer" class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white"><i class="bx bx-archive-in me-2"></i>{{ __('ui.recouvrement.close_modal_title') }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label class="form-label fw-semibold">{{ __('ui.recouvrement.close_reason_label') }}</label>
        <select name="statut" class="form-select">
          <option value="resolu">{{ __('ui.recouvrement.close_resolved') }}</option>
          <option value="classe">{{ __('ui.recouvrement.close_dismissed') }}</option>
        </select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('ui.common.cancel') }}</button>
        <button type="button" id="btnCloturer" class="btn btn-primary">{{ __('ui.recouvrement.close_btn') }}</button>
      </div>
    </form>
  </div>
</div>

{{-- ── Modal : Aperçu PDF Mise en demeure ─────────────────────────────────── --}}
<div class="modal fade" id="modalPreviewMED" tabindex="-1">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white"><i class="bx bx-file-blank me-2"></i>{{ __('ui.recouvrement.preview_med_title') }}</h5>
        <div class="ms-auto d-flex gap-2 align-items-center">
          <button type="button" class="btn btn-sm btn-light" id="btnDownloadMED">
            <i class="bx bx-download me-1"></i> {{ __('ui.common.download') }}
          </button>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
      </div>
      <div class="modal-body p-0" style="height:80vh;">
        <iframe id="iframeMED" src="" style="width:100%;height:100%;border:none;"></iframe>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
@php $pc = app(\App\PlatformConfig::class)::getConfig(); @endphp
@if($paymentEnabled && $paymentProvider === 'kkiapay')
<script src="https://cdn.kkiapay.me/k.js"></script>
@elseif($paymentEnabled && $paymentProvider === 'fedapay')
<script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
@endif

<script>
const CSRF             = '{{ csrf_token() }}';
const PAYMENT_ENABLED  = {{ $paymentEnabled ? 'true' : 'false' }};
const PAYMENT_PROVIDER = '{{ $paymentProvider }}';
const PAYMENT_KEY      = '{{ $paymentPublicKey ?? '' }}';
const PAYMENT_SANDBOX  = {{ $paymentSandbox ? 'true' : 'false' }};
const SMS_COST         = {{ $smsCost }};
const SMS_CURRENCY     = '{{ $smsCurrency }}';

// ── Gestion canal SMS : paiement requis ──────────────────────────────────────
let smsPaid = false;

const selectCanal      = document.getElementById('selectCanal');
const blockPaiementSms = document.getElementById('blockPaiementSms');
const btnRelancer      = document.getElementById('btnRelancer');
const btnPayerSms      = document.getElementById('btnPayerSms');
const smsPaiementStatut = document.getElementById('smsPaiementStatut');

function onCanalChange() {
  const isSms = selectCanal?.value === 'sms';
  if (blockPaiementSms) blockPaiementSms.classList.toggle('d-none', !isSms);

  if (isSms && PAYMENT_ENABLED && SMS_COST > 0) {
    btnRelancer.disabled = !smsPaid;
  } else {
    btnRelancer.disabled = false;
  }
}

if (selectCanal) selectCanal.addEventListener('change', onCanalChange);

// Déclencher le paiement SMS
if (btnPayerSms) {
  btnPayerSms.addEventListener('click', function () {
    const origLabel = btnPayerSms.innerHTML;
    btnPayerSms.disabled = true;
    btnPayerSms.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span><small>{{ __("ui.recouvrement.sms_opening_payment") }}</small>';

    // Restaurer le bouton si le widget est fermé sans payer (après 15s max)
    const restoreTimer = setTimeout(() => {
      btnPayerSms.disabled = false;
      btnPayerSms.innerHTML = origLabel;
    }, 15000);

    if (PAYMENT_PROVIDER === 'kkiapay') {
      openKkiapayWidget({
        amount:      SMS_COST,
        key:         PAYMENT_KEY,
        sandbox:     PAYMENT_SANDBOX,
        name:        '{{ $dossier->locataire->prenom }} {{ $dossier->locataire->nom }}',
        callback:    '',
      });
      addSuccessListener(function (response) {
        clearTimeout(restoreTimer);
        document.getElementById('smsTransactionId').value = response.transactionId;
        smsPaid = true;
        btnRelancer.disabled = false;
        if (smsPaiementStatut) smsPaiementStatut.classList.remove('d-none');
        if (btnPayerSms) btnPayerSms.classList.add('d-none');
      });
    } else if (PAYMENT_PROVIDER === 'fedapay') {
      FedaPay.init({
        public_key:  PAYMENT_KEY,
        transaction: { amount: SMS_COST, description: '{{ __("ui.recouvrement.sms_relance_desc") }}' },
        customer:    { firstname: '{{ $dossier->locataire->prenom }}', lastname: '{{ $dossier->locataire->nom }}' },
        onComplete:  function (resp) {
          clearTimeout(restoreTimer);
          btnPayerSms.disabled = false;
          btnPayerSms.innerHTML = origLabel;
          if (resp.reason === FedaPay.DIALOG_DISMISSED) return;
          if (resp.transaction?.status === 'approved') {
            document.getElementById('smsTransactionId').value = resp.transaction.id;
            smsPaid = true;
            btnRelancer.disabled = false;
            if (smsPaiementStatut) smsPaiementStatut.classList.remove('d-none');
            if (btnPayerSms) btnPayerSms.classList.add('d-none');
          }
        },
      }).open();
    }
  });
}

// ── Télécharger PDF sans recharger la page ───────────────────────────────────
document.getElementById('btnDownloadMED').addEventListener('click', function () {
  const btn = this;
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>';

  fetch('{{ route("recouvrement.mise_en_demeure", $dossier->id) }}', {
    headers: { 'X-CSRF-TOKEN': CSRF },
  })
  .then(r => r.blob())
  .then(blob => {
    const url  = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href     = url;
    link.download = 'mise_en_demeure_{{ $dossier->locataire->nom }}_{{ now()->format("Ymd") }}.pdf';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
  })
  .catch(() => Swal.fire({ icon: 'error', title: '{{ __("ui.common.error") }}', text: '{{ __("ui.recouvrement.download_error") }}' }))
  .finally(() => {
    btn.disabled = false;
    btn.innerHTML = '<i class="bx bx-download me-1"></i> {{ __("ui.common.download") }}';
  });
});

// ── Aperçu PDF (sans cachet) ──────────────────────────────────────────────────
const btnPreviewMED = document.getElementById('btnPreviewMED');
if (btnPreviewMED) {
  btnPreviewMED.addEventListener('click', function () {
    document.getElementById('iframeMED').src = '{{ route("recouvrement.mise_en_demeure_preview", $dossier->id) }}';
    new bootstrap.Modal(document.getElementById('modalPreviewMED')).show();
  });
}

// ── Signer : applique le cachet puis affiche le document signé dans le modal ──
const btnSigner = document.getElementById('btnSigner');
if (btnSigner) {
  btnSigner.addEventListener('click', function () {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>{{ __("ui.recouvrement.sign_spinner") }}';

    const fd = new FormData();
    fd.append('_token', CSRF);

    fetch('{{ route("recouvrement.signer_document", $dossier->id) }}', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: fd,
    })
    .then(r => r.json())
    .then(res => {
      if (res.status) {
        Swal.fire({ icon: 'success', title: '{{ __("ui.recouvrement.signed") }}', text: res.message, confirmButtonText: 'OK' });
      } else {
        Swal.fire({ icon: 'error', title: '{{ __("ui.common.error") }}', text: res.message });
      }
    })
    .catch(() => Swal.fire({ icon: 'error', title: '{{ __("ui.common.error") }}', text: '{{ __("ui.common.network_error") }}' }))
    .finally(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="bx bx-edit-alt me-1"></i> {{ __("ui.recouvrement.sign_btn") }}';
    });
  });
}

// Vider l'iframe à la fermeture pour libérer la mémoire
document.getElementById('modalPreviewMED').addEventListener('hidden.bs.modal', function () {
  document.getElementById('iframeMED').src = '';
});

// ── Utilitaire AJAX générique ─────────────────────────────────────────────────
function ajaxPost(url, formData, btn, labelOrig, reload = true) {
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>{{ __("ui.recouvrement.js_creating") }}';

  fetch(url, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
    body: formData,
  })
  .then(r => r.json())
  .then(res => {
    if (res.status) {
      if (res.redirect) {
        Swal.fire({ icon: 'success', title: '{{ __("ui.common.success") }}', text: res.message, confirmButtonText: 'OK' })
          .then(() => { window.location.href = res.redirect; });
      } else if (reload) {
        Swal.fire({ icon: 'success', title: '{{ __("ui.common.success") }}', text: res.message, confirmButtonText: 'OK' })
          .then(() => { window.location.reload(); });
      } else {
        Swal.fire({ icon: 'success', title: '{{ __("ui.common.success") }}', text: res.message, timer: 1800, showConfirmButton: false });
      }
    } else {
      Swal.fire({ icon: 'error', title: '{{ __("ui.common.error") }}', text: res.message });
    }
  })
  .catch(() => Swal.fire({ icon: 'error', title: '{{ __("ui.common.error") }}', text: '{{ __("ui.common.network_error") }}' }))
  .finally(() => { btn.disabled = false; btn.innerHTML = labelOrig; });
}

function formToData(formId) {
  const fd = new FormData(document.getElementById(formId));
  fd.append('_token', CSRF);
  return fd;
}

// ── Envoyer une relance ───────────────────────────────────────────────────────
if (btnRelancer) {
  btnRelancer.addEventListener('click', function () {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>{{ __("ui.recouvrement.js_creating") }}';

    fetch('{{ route("recouvrement.relancer", $dossier->id) }}', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
      body: formToData('formRelancer'),
    })
    .then(r => r.json())
    .then(res => {
      if (res.status) {
        Swal.fire({ icon: 'success', title: '{{ __("ui.common.success") }}', text: res.message, timer: 1800, showConfirmButton: false });

        // Rafraîchir la timeline et le select sans recharger la page
        fetch('{{ route("recouvrement.relances_data", $dossier->id) }}', {
          headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        })
        .then(r => r.json())
        .then(data => {
          // Mettre à jour la timeline
          const timeline = document.getElementById('relancesTimeline');
          timeline.innerHTML = data.relancesHtml;
          // Activer le scroll si > 4 relances
          const nbRelances = timeline.querySelectorAll('.timeline-item').length;
          timeline.style.maxHeight = nbRelances > 4 ? '320px' : '';
          timeline.style.overflowY = nbRelances > 4 ? 'auto' : '';
          // Mettre à jour le compteur
          document.getElementById('relancesCount').textContent = nbRelances;
          // Mettre à jour le select type
          const selectType = document.querySelector('#formRelancer select[name="type"]');
          if (selectType) {
            selectType.innerHTML = '';
            Object.entries(data.options).forEach(([value, label]) => {
              const opt = document.createElement('option');
              opt.value = value;
              opt.textContent = label;
              if (value === data.prochain) opt.selected = true;
              selectType.appendChild(opt);
            });
          }
          // Réinitialiser le textarea message
          const textarea = document.querySelector('#formRelancer textarea[name="message"]');
          if (textarea) textarea.value = '';
        });
      } else {
        Swal.fire({ icon: 'error', title: '{{ __("ui.common.error") }}', text: res.message });
      }
    })
    .catch(() => Swal.fire({ icon: 'error', title: '{{ __("ui.common.error") }}', text: '{{ __("ui.common.network_error") }}' }))
    .finally(() => {
      btn.disabled = false;
      btn.innerHTML = '<i class="bx bx-send me-1"></i> {{ __("ui.recouvrement.relance_send") }}';
    });
  });
}

// ── Plan d'apurement ─────────────────────────────────────────────────────────
const inputNb      = document.getElementById('nbEcheances');
const inputMontant = document.getElementById('montantEcheance');
const montantDu    = {{ $dossier->montant_du }};
const reste        = {{ $dossier->montant_reste }};
const alertPlan    = document.getElementById('alertTotalPlan');
const btnPlan      = document.getElementById('btnPlanApurement');

function validerPlan() {
  const nb      = parseInt(inputNb?.value) || 0;
  const montant = parseFloat(inputMontant?.value) || 0;
  const total   = nb * montant;
  if (total > montantDu) {
    alertPlan.classList.remove('d-none');
    alertPlan.innerHTML = `{{ __("ui.recouvrement.plan_total_part1") }} (<strong>${total.toLocaleString('fr-FR')} {{ get_symbole_devise() }}</strong>) {{ __("ui.recouvrement.plan_total_part2") }} (<strong>${montantDu.toLocaleString('fr-FR')} {{ get_symbole_devise() }}</strong>).`;
    if (btnPlan) btnPlan.disabled = true;
    return false;
  }
  alertPlan.classList.add('d-none');
  if (btnPlan) btnPlan.disabled = false;
  return true;
}

if (inputNb && inputMontant) {
  inputNb.addEventListener('input', function () {
    inputMontant.value = Math.ceil(reste / (parseInt(this.value) || 1));
    validerPlan();
  });
  inputMontant.addEventListener('input', validerPlan);
}

if (btnPlan) {
  btnPlan.addEventListener('click', function () {
    if (!validerPlan()) return;
    bootstrap.Modal.getInstance(document.getElementById('modalPlanApurement'))?.hide();
    ajaxPost(
      '{{ route("recouvrement.plan_apurement", $dossier->id) }}',
      formToData('formPlanApurement'),
      this,
      '<i class="bx bx-save me-1"></i>{{ __("ui.recouvrement.plan_create_btn") }}'
    );
  });
}

// ── Marquer échéance payée ────────────────────────────────────────────────────
document.querySelectorAll('.btn-payer-echeance').forEach(btn => {
  btn.addEventListener('click', function () {
    const url = this.dataset.url;
    const b   = this;
    Swal.fire({
      title: '{{ __("ui.recouvrement.echeance_confirm_title") }}',
      text: '{{ __("ui.common.irreversible") }}',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: '{{ __("ui.recouvrement.echeance_confirm_btn") }}',
      cancelButtonText: '{{ __("ui.common.cancel") }}',
    }).then(result => {
      if (!result.isConfirmed) return;
      const fd = new FormData();
      fd.append('_token', CSRF);
      fd.append('date_paiement', '{{ now()->toDateString() }}');
      ajaxPost(url, fd, b, '<i class="bx bx-check"></i> Payé');
    });
  });
});

// ── Escalade contentieux ──────────────────────────────────────────────────────
const btnContentieux = document.getElementById('btnContentieux');
if (btnContentieux) {
  btnContentieux.addEventListener('click', function () {
    bootstrap.Modal.getInstance(document.getElementById('modalContentieux'))?.hide();
    ajaxPost(
      '{{ route("recouvrement.contentieux", $dossier->id) }}',
      formToData('formContentieux'),
      this,
      '<i class="bx bx-gavel me-1"></i>{{ __("ui.recouvrement.contentieux_confirm") }}'
    );
  });
}

// ── Mise à jour notes / montant (sans rechargement) ──────────────────────────
const btnUpdate = document.getElementById('btnUpdate');
if (btnUpdate) {
  btnUpdate.addEventListener('click', function () {
    ajaxPost(
      '{{ route("recouvrement.update", $dossier->id) }}',
      formToData('formUpdate'),
      this,
      '<i class="bx bx-save me-1"></i> {{ __("ui.common.save") }}',
      false
    );
  });
}

// ── Clôturer dossier ─────────────────────────────────────────────────────────
const btnCloturer = document.getElementById('btnCloturer');
if (btnCloturer) {
  btnCloturer.addEventListener('click', function () {
    bootstrap.Modal.getInstance(document.getElementById('modalCloturer'))?.hide();
    ajaxPost(
      '{{ route("recouvrement.cloturer", $dossier->id) }}',
      formToData('formCloturer'),
      this,
      '{{ __("ui.recouvrement.close_btn") }}'
    );
  });
}
</script>
@endpush
