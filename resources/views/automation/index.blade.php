@extends('layouts.template')

@section('title')
<title>Automatisation & Rappels – Lokativ</title>
@endsection

@section('content')
@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">

  {{-- ══════════════════════════════════════════════════════════════════════
       VUE PRINCIPALE : Onglets (Règles / Historique / Opt-out)
  ══════════════════════════════════════════════════════════════════════ --}}

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="fw-bold mb-0">
      <span class="text-muted fw-light">{{ __('ui.common.home') }} /</span> {{ __('ui.automation.title') }}
    </h4>
    @can('ajoute-automation')
    <button class="btn btn-primary" id="btnNouvelleRegle">
      <i class="bx bx-plus me-1"></i> {{ __('ui.automation.new_rule') }}
    </button>
    @endcan
  </div>

  {{-- Onglets --}}
  <ul class="nav nav-pills mb-4 gap-1 flex-wrap" id="autoTabs">
    <li class="nav-item">
      <a class="nav-link active" href="#" data-tab="tabRegles"><i class="bx bx-list-ul me-1"></i> {{ __('ui.automation.tab_rules') }}</a>
    </li>
    @can('voir-historique-automation')
    <li class="nav-item">
      <a class="nav-link" href="#" data-tab="tabHistorique"><i class="bx bx-history me-1"></i> {{ __('ui.automation.tab_history') }}</a>
    </li>
    @endcan
    @can('voir-locataires-automation')
    <li class="nav-item">
      <a class="nav-link" href="#" data-tab="tabOptOut"><i class="bx bx-user-x me-1"></i> {{ __('ui.automation.tab_optout') }}</a>
    </li>
    @endcan
  </ul>

  {{-- ── TAB : Règles ─────────────────────────────────────────────────────── --}}
  <div id="tabRegles">

    <div class="row g-3" id="listeRegles">
      @forelse($rules as $rule)
        @include('automation._rule_card', ['rule' => $rule])
      @empty
        <div class="col-12" id="carteVide">
          <div class="card shadow-sm">
            <div class="card-body text-center text-muted py-5">
              <i class="bx bx-bot fs-1 d-block mb-2 text-primary opacity-50"></i>
              <p class="mb-0">{!! __('ui.automation.no_rules') !!}</p>
            </div>
          </div>
        </div>
      @endforelse
    </div>

  </div>

  {{-- ── TAB : Historique ────────────────────────────────────────────────── --}}
  <div id="tabHistorique" style="display:none;">

    {{-- Filtres --}}
    <div class="card shadow-sm mb-3">
      <div class="card-body py-3">
        <div class="row g-2 align-items-end">
          <div class="col-6 col-md-3">
            <label class="form-label small mb-1">{{ __('ui.automation.hist_type') }}</label>
            <select class="form-select form-select-sm" id="filtreLogType">
              <option value="">{{ __('ui.automation.hist_type_all') }}</option>
              <option value="rappel_loyer">{{ __('ui.automation.log_rappel') }}</option>
              <option value="escalade_impaye">{{ __('ui.automation.log_escalade') }}</option>
              <option value="preavis_bail">{{ __('ui.automation.log_preavis') }}</option>
              <option value="rapport_mensuel">{{ __('ui.automation.log_rapport') }}</option>
              <option value="renouvellement">{{ __('ui.automation.log_renouvellement') }}</option>
            </select>
          </div>
          <div class="col-6 col-md-2">
            <label class="form-label small mb-1">{{ __('ui.automation.hist_channel') }}</label>
            <select class="form-select form-select-sm" id="filtreLogCanal">
              <option value="">{{ __('ui.automation.hist_canal_all') }}</option>
              <option value="email">Email</option>
              <option value="sms">SMS</option>
              <option value="whatsapp">WhatsApp</option>
            </select>
          </div>
          <div class="col-6 col-md-2">
            <label class="form-label small mb-1">{{ __('ui.automation.hist_status') }}</label>
            <select class="form-select form-select-sm" id="filtreLogStatut">
              <option value="">{{ __('ui.automation.hist_stat_all') }}</option>
              <option value="succes">{{ __('ui.automation.hist_stat_success') }}</option>
              <option value="echec">{{ __('ui.automation.hist_stat_fail') }}</option>
              <option value="ignore">{{ __('ui.automation.hist_stat_ignored') }}</option>
            </select>
          </div>
          <div class="col-6 col-md-3">
            <label class="form-label small mb-1">{{ __('ui.automation.hist_since') }}</label>
            <input type="date" class="form-control form-control-sm" id="filtreLogDepuis">
          </div>
          <div class="col-md-2">
            <button class="btn btn-primary btn-sm w-100" id="btnFiltrerLogs">
              <i class="bx bx-search me-1"></i> {{ __('ui.common.filter') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0" id="tableHistorique">
            <thead class="table-light">
              <tr>
                <th>{{ __('ui.automation.hist_recipient') }}</th>
                <th>{{ __('ui.automation.hist_type') }}</th>
                <th>{{ __('ui.automation.hist_channel') }}</th>
                <th>{{ __('ui.automation.hist_reference') }}</th>
                <th>{{ __('ui.automation.hist_status') }}</th>
                <th>{{ __('ui.automation.hist_message') }}</th>
                <th>{{ __('ui.automation.hist_date') }}</th>
              </tr>
            </thead>
            <tbody id="tbodyHistorique">
              <tr><td colspan="7" class="text-center text-muted py-4">
                <span class="spinner-border spinner-border-sm me-2"></span> {{ __('ui.common.loading') }}
              </td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

  {{-- ── TAB : Locataires non notifiés ─────────────────────────────────────────── --}}
  <div id="tabOptOut" style="display:none;">

    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center py-3">
        <span class="fw-semibold">{{ __('ui.automation.optout_header') }}</span>
        <span class="text-muted small">{!! __('ui.automation.optout_note') !!}</span>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th>{{ __('ui.recouvrement.col_tenant') }}</th>
                <th>{{ __('ui.edl.phone') }}</th>
                <th>Email</th>
                <th class="text-center">{{ __('ui.automation.optout_col') }}</th>
              </tr>
            </thead>
            <tbody id="tbodyOptOut">
              <tr><td colspan="4" class="text-center text-muted py-4">
                <span class="spinner-border spinner-border-sm me-2"></span> {{ __('ui.common.loading') }}
              </td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

</div>{{-- fin .container-xxl --}}

{{-- ══════════════════════════════════════════════════════════════════════════
     MODAL : Créer / éditer une règle
══════════════════════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalRegle" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary">
        <h5 class="modal-title text-white" id="modalRegleTitre"><i class="bx bx-bot me-2"></i>{{ __('ui.automation.modal_title') }}</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="regleId">

        <div class="row g-3">

          {{-- Type de règle --}}
          <div class="col-md-6">
            <label class="form-label">{{ __('ui.automation.rule_type_label') }} <span class="text-danger">*</span></label>
            <select class="form-select" id="regleType" required>
              <option value="">{{ __('ui.automation.rule_type_ph') }}</option>
              <option value="rappel_loyer">{{ __('ui.automation.type_rappel') }}</option>
              <option value="escalade_impaye">{{ __('ui.automation.type_escalade') }}</option>
              <option value="preavis_bail">{{ __('ui.automation.type_preavis') }}</option>
              <option value="rapport_mensuel">{{ __('ui.automation.type_rapport') }}</option>
              <option value="renouvellement">{{ __('ui.automation.type_renouvellement') }}</option>
            </select>
          </div>

          {{-- Canal --}}
          <div class="col-md-6">
            <label class="form-label">{{ __('ui.automation.channel_label') }} <span class="text-danger">*</span></label>
            <select class="form-select" id="regleCanal">
              <option value="email">{{ __('ui.automation.ch_email') }}</option>
              <option value="sms">{{ __('ui.automation.ch_sms') }}</option>
              <option value="whatsapp">{{ __('ui.automation.ch_whatsapp') }}</option>
              <option value="email_sms">{{ __('ui.automation.ch_email_sms') }}</option>
              <option value="email_whatsapp">{{ __('ui.automation.ch_email_wa') }}</option>
              <option value="tous">{{ __('ui.automation.ch_all') }}</option>
            </select>
          </div>

          {{-- Heure d'envoi --}}
          <div class="col-md-4">
            <label class="form-label">{{ __('ui.automation.time_label') }}</label>
            <input type="time" class="form-control" id="regleHeure" value="08:00">
          </div>

          {{-- Actif --}}
          <div class="col-md-4 d-flex align-items-end">
            <div class="form-check form-switch mb-2">
              <input class="form-check-input" type="checkbox" id="regleActif" checked>
              <label class="form-check-label" for="regleActif">{{ __('ui.automation.active_label') }}</label>
            </div>
          </div>

          {{-- Séparateur --}}
          <div class="col-12"><hr class="my-1"></div>

          {{-- Champs contextuels selon le type --}}

          {{-- rappel_loyer : jours --}}
          <div class="col-12 champ-type champ-rappel_loyer" style="display:none;">
            <label class="form-label">{{ __('ui.automation.days_label') }}</label>
            <input type="text" class="form-control" id="regleJours" placeholder="{{ __('ui.automation.days_ph') }}">
            <div class="form-text">{{ __('ui.automation.days_help') }}</div>
          </div>

          {{-- escalade_impaye : délais --}}
          <div class="col-md-6 champ-type champ-escalade_impaye" style="display:none;">
            <label class="form-label">{{ __('ui.automation.delay_sms_label') }}</label>
            <input type="number" class="form-control" id="regleDelaiSms" min="1" value="3" placeholder="3">
          </div>
          <div class="col-md-6 champ-type champ-escalade_impaye" style="display:none;">
            <label class="form-label">{{ __('ui.automation.delay_wa_label') }}</label>
            <input type="number" class="form-control" id="regleDelaiWa" min="1" value="5" placeholder="5">
          </div>

          {{-- preavis_bail + renouvellement : mois avant échéance --}}
          <div class="col-md-6 champ-type champ-preavis_bail champ-renouvellement" style="display:none;">
            <label class="form-label">{{ __('ui.automation.months_bail_label') }}</label>
            <input type="number" class="form-control" id="regleMoisEcheance" min="1" value="2" placeholder="2">
          </div>

          {{-- rapport_mensuel : jour du mois --}}
          <div class="col-md-6 champ-type champ-rapport_mensuel" style="display:none;">
            <label class="form-label">{{ __('ui.automation.day_month_label') }}</label>
            <input type="number" class="form-control" id="regleJourMois" min="1" max="28" value="1" placeholder="1">
            <div class="form-text">{{ __('ui.automation.day_month_help') }}</div>
          </div>

          {{-- ── Section paiement SMS (affichée uniquement si canal inclut SMS) ── --}}
          @php
            $ratesMap = $messagingRates->keyBy('country_code');
            $defaultRate = $messagingRates->firstWhere('is_default', true) ?? $messagingRates->first();
            $paysListe = [
              'BJ' => 'Bénin',    'TG' => 'Togo',         'SN' => 'Sénégal',
              'CI' => 'Côte d\'Ivoire', 'ML' => 'Mali',   'BF' => 'Burkina Faso',
              'NE' => 'Niger',    'GN' => 'Guinée',        'NG' => 'Nigeria',
              'GH' => 'Ghana',    'CM' => 'Cameroun',      'CD' => 'Congo RDC',
            ];
          @endphp
          <div class="col-12" id="sectionPaiementSms" style="display:none;">
            <hr class="my-2">
            <div class="alert alert-warning d-flex gap-2 align-items-center py-2 mb-3">
              <i class="bx bx-credit-card fs-5 flex-shrink-0"></i>
              <span class="small">{!! __('ui.automation.sms_alert') !!}</span>
            </div>

            {{-- Ligne 1 : Pays | Destinataires | Bouton Calculer --}}
            <div class="row g-2 mb-2">
              <div class="col-sm-5">
                <label class="form-label small fw-semibold mb-1">{{ __('ui.automation.sms_country') }}</label>
                <select class="form-select form-select-sm" id="smsPaysSelect">
                  @foreach($paysListe as $code => $nom)
                    @php $rate = $ratesMap->get($code) ?? $defaultRate; @endphp
                    <option value="{{ $code }}"
                            data-unit="{{ $rate ? $rate->sms_unit_cost : 0 }}"
                            data-currency="{{ $rate ? $rate->currency : 'XOF' }}"
                            {{ ($code === 'BJ' || ($rate && $rate->is_default && !$ratesMap->has('BJ'))) ? 'selected' : '' }}>
                      {{ $nom }}
                      @if($ratesMap->has($code))
                        — {{ number_format($ratesMap->get($code)->sms_unit_cost, 0, ',', ' ') }} {{ $ratesMap->get($code)->currency }}/SMS
                      @elseif($defaultRate)
                        — {{ number_format($defaultRate->sms_unit_cost, 0, ',', ' ') }} {{ $defaultRate->currency }}/SMS*
                      @endif
                    </option>
                  @endforeach
                </select>
                @if($messagingRates->count() < count($paysListe))
                  <div class="form-text">{{ __('ui.automation.sms_default_rate') }}</div>
                @endif
              </div>

              <div class="col-sm-4">
                <label class="form-label small fw-semibold mb-1">
                  {{ __('ui.automation.sms_recipients_label') }}
                  <button type="button" class="btn btn-link p-0 ms-1" id="btnReloadCount" title="{{ __('ui.common.reload') }}">
                    <i class="bx bx-refresh small"></i>
                  </button>
                </label>
                <div class="input-group input-group-sm">
                  <span class="input-group-text"><i class="bx bx-group"></i></span>
                  <input type="number" class="form-control form-control-sm" id="smsNbDestinataires"
                         min="1" placeholder="Ex : 12" value="">
                </div>
                <div class="form-text text-muted" id="smsMsgDestinataires">{{ __('ui.automation.sms_loading') }}</div>
              </div>

              <div class="col-sm-3">
                <label class="form-label small fw-semibold mb-1 invisible">Action</label>
                <button type="button" class="btn btn-outline-primary btn-sm w-100" id="btnCalculerCoutSms">
                  <i class="bx bx-calculator me-1"></i> {{ __('ui.automation.sms_calculate') }}
                </button>
              </div>
            </div>

            {{-- Ligne 2 : Résultat + paiement --}}
            <div id="smsDevisResultat" style="display:none;">
              <div class="alert alert-info py-2 px-3 mb-2 d-flex justify-content-between align-items-center">
                <span><i class="bx bx-info-circle me-1"></i><span id="smsDevisTexte" class="fw-semibold"></span></span>
              </div>
              <button type="button" class="btn btn-success btn-sm px-4" id="btnPayerSms">
                <i class="bx bx-wallet me-1"></i> {{ __('ui.automation.sms_pay_btn') }}
              </button>
              <div class="alert alert-success py-2 mt-2 d-none mb-0" id="smsPaiementOk">
                <i class="bx bx-check-circle me-1"></i> {{ __('ui.automation.sms_paid_msg') }}
              </div>
              <input type="hidden" id="smsTransactionId">
              <input type="hidden" id="smsRecipientCount">
            </div>
          </div>

        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('ui.common.cancel') }}</button>
        <button type="button" class="btn btn-primary" id="btnSauvegarderRegle">
          <span id="btnSauvegarderTexte"><i class="bx bx-save me-1"></i> {{ __('ui.common.save') }}</span>
          <span id="btnSauvegarderSpinner" class="d-none">
            <span class="spinner-border spinner-border-sm me-1"></span> {{ __('ui.common.saving') }}
          </span>
        </button>
      </div>
    </div>
  </div>
</div>

{{-- SDK paiement (KKiaPay ou FedaPay) --}}
@if($platformConfig->isKkiapayActive())
  <script src="https://cdn.kkiapay.me/k.js"></script>
@elseif($platformConfig->isFedapayActive())
  <script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
@endif
<style>
  /* KKiaPay : forcer l'affichage au-dessus des modals Bootstrap */
  iframe[src*="kkiapay"],
  [id*="kkiapay"],
  [class*="kkiapay"],
  div[style*="z-index: 999"] { z-index: 99999 !important; }
</style>

@push('scripts')
<script>
// Config paiement injectée côté serveur (évite un fetch asynchrone)
const PAYMENT_CFG = {
    enabled:  {{ $platformConfig->isOperational() ? 'true' : 'false' }},
    provider: '{{ $platformConfig->getActiveProvider() }}',
    pub_key:  '{{ $platformConfig->getActivePublicKey() ?? '' }}',
    sandbox:  {{ $platformConfig->getActiveSandbox() ? 'true' : 'false' }},
};

// Permissions injectées côté serveur
const CAN_TOGGLE_RULE   = {{ auth()->user()->can('toggle-automation')        ? 'true' : 'false' }};
const CAN_DELETE_RULE   = {{ auth()->user()->can('delete-automation')        ? 'true' : 'false' }};
const CAN_TOGGLE_OPTOUT = {{ auth()->user()->can('toggle-optout-automation') ? 'true' : 'false' }};
</script>
<script>
(function () {

// ── Routes ───────────────────────────────────────────────────────────────────
const ROUTES = {
    store:       '{{ route("automation.store") }}',
    toggleActif: '{{ route("automation.toggle_actif") }}',
    destroy:     '{{ route("automation.destroy") }}',
    logs:        '{{ route("automation.logs") }}',
    optOut:      '{{ route("automation.opt_out") }}',
    locataires:  '{{ route("automation.locataires") }}',
    countActifs: '{{ route("automation.count_actifs") }}',
    quoteUrl:    '/messaging/quote',
};
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ── Onglets ──────────────────────────────────────────────────────────────────
const TABS = ['tabRegles', 'tabHistorique', 'tabOptOut'];
let logsCharges = false;
let optOutCharges = false;

document.querySelectorAll('#autoTabs .nav-link').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelectorAll('#autoTabs .nav-link').forEach(l => l.classList.remove('active'));
        this.classList.add('active');
        const tab = this.dataset.tab;
        TABS.forEach(t => { document.getElementById(t).style.display = t === tab ? '' : 'none'; });
        if (tab === 'tabHistorique' && !logsCharges) chargerLogs();
        if (tab === 'tabOptOut' && !optOutCharges) chargerOptOut();
    });
});

// ── Modal règle ──────────────────────────────────────────────────────────────
const modalRegleEl = document.getElementById('modalRegle');
const modalRegle   = bootstrap.Modal.getOrCreateInstance(modalRegleEl);

// État paiement SMS
let smsPaymentDone   = false;
let smsPaymentTxnId  = '';
let smsPaymentAmount = 0;

function resetSmsPaiement() {
    smsPaymentDone   = false;
    smsPaymentTxnId  = '';
    smsPaymentAmount = 0;
    document.getElementById('smsTransactionId').value  = '';
    document.getElementById('smsRecipientCount').value = '';
    document.getElementById('smsDevisResultat').style.display = 'none';
    document.getElementById('smsPaiementOk').classList.add('d-none');
    const btnPay = document.getElementById('btnPayerSms');
    if (btnPay) { btnPay.classList.remove('d-none'); btnPay.disabled = false; btnPay.innerHTML = '<i class="bx bx-credit-card me-1"></i> {{ __("ui.automation.sms_pay_btn") }}'; }
    // Bloquer le bouton Enregistrer si le canal actuel inclut SMS
    const canal = document.getElementById('regleCanal')?.value;
    if (canal && canalInclutSms(canal)) {
        const btnSave = document.getElementById('btnSauvegarderRegle');
        if (btnSave) btnSave.disabled = true;
    }
}

function canalInclutSms(canal) {
    return ['sms', 'email_sms', 'tous'].includes(canal);
}

function mettreAJourSectionSms() {
    const canal   = document.getElementById('regleCanal').value;
    const section = document.getElementById('sectionPaiementSms');
    const btnSave = document.getElementById('btnSauvegarderRegle');
    if (canalInclutSms(canal)) {
        section.style.display = '';
        // Bloquer Enregistrer jusqu'au paiement (sauf si déjà payé)
        if (btnSave) btnSave.disabled = !smsPaymentDone;
        // Charger le count auto si le champ est vide
        const elNb = document.getElementById('smsNbDestinataires');
        if (elNb && !elNb.value) chargerNbLocataires();
    } else {
        section.style.display = 'none';
        resetSmsPaiement();
        if (btnSave) btnSave.disabled = false;
    }
}

function afficherChampsType(type) {
    document.querySelectorAll('.champ-type').forEach(el => el.style.display = 'none');
    if (type) {
        document.querySelectorAll('.champ-' + type).forEach(el => el.style.display = '');
    }
}

document.getElementById('regleType').addEventListener('change', function () {
    afficherChampsType(this.value);
});

document.getElementById('regleCanal').addEventListener('change', mettreAJourSectionSms);

// ── Chargement du nombre de locataires actifs ────────────────────────────────

async function chargerNbLocataires() {
    const elNb  = document.getElementById('smsNbDestinataires');
    const elMsg = document.getElementById('smsMsgDestinataires');
    if (!elNb) return;

    elMsg.textContent = '{{ __("ui.automation.sms_loading") }}';
    elMsg.className   = 'form-text text-muted';

    try {
        const resp = await fetch(ROUTES.countActifs, { credentials: 'same-origin' });
        if (!resp.ok) throw new Error('HTTP ' + resp.status);
        const data = await resp.json();

        if (data.status) {
            elNb.value    = data.count;
            elMsg.textContent = data.count > 0
                ? data.count + '{{ __("ui.automation.locataires_loaded", ["n" => ""]) }}'
                : '{{ __("ui.automation.no_actif_tenant") }}';
        } else {
            elNb.value    = '';
            elMsg.textContent = data.message || '{{ __("ui.automation.server_error") }}';
            elMsg.className   = 'form-text text-danger';
        }
    } catch (e) {
        elNb.value    = '';
        elMsg.textContent = '{{ __("ui.automation.enter_recipients_manually") }}';
        elMsg.className   = 'form-text text-warning';
    }
}

// Bouton reload count
document.getElementById('btnReloadCount')?.addEventListener('click', () => chargerNbLocataires());

// ── Calcul coût SMS ───────────────────────────────────────────────────────────
document.getElementById('btnCalculerCoutSms').addEventListener('click', async () => {
    const pays  = document.getElementById('smsPaysSelect')?.value || 'BJ';
    const count = parseInt(document.getElementById('smsNbDestinataires').value) || 0;

    if (count <= 0) {
        Swal.fire('{{ __("ui.automation.required_field") }}', '{{ __("ui.automation.sms_no_recipients") }}', 'warning');
        document.getElementById('smsNbDestinataires').focus();
        return;
    }

    // Reset résultat précédent
    document.getElementById('smsDevisResultat').style.display = 'none';
    resetSmsPaiement();

    const btn = document.getElementById('btnCalculerCoutSms');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ __("ui.common.loading") }}';

    try {
        const respQuote = await fetch(
            ROUTES.quoteUrl + '?channel=sms&count=' + count + '&country_code=' + encodeURIComponent(pays)
        );
        const dataQuote = await respQuote.json();

        if (!dataQuote.status) {
            Swal.fire('{{ __("ui.common.error") }}', dataQuote.message || '{{ __("ui.automation.sms_rate_not_found") }}', 'error');
            return;
        }

        smsPaymentAmount = Math.round(dataQuote.total);
        document.getElementById('smsRecipientCount').value = count;
        document.getElementById('smsDevisTexte').innerHTML =
            'Total à payer : <strong>' +
            Math.round(dataQuote.total).toLocaleString('fr-FR') + ' ' + dataQuote.currency +
            '</strong> <span class="text-muted fw-normal small">(' + count + ' destinataire(s) × ' +
            Number(dataQuote.unit_cost).toLocaleString('fr-FR') + ' ' + dataQuote.currency + '/SMS)</span>';

        document.getElementById('smsDevisResultat').style.display = '';

    } catch {
        Swal.fire('{{ __("ui.common.error") }}', '{{ __("ui.automation.sms_calc_error") }}', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<i class="bx bx-calculator me-1"></i> {{ __("ui.automation.sms_calculate") }}';
    }
});

// ── Paiement SMS ──────────────────────────────────────────────────────────────
document.getElementById('btnPayerSms').addEventListener('click', function () {
    var btn     = document.getElementById('btnPayerSms');
    var btnSave = document.getElementById('btnSauvegarderRegle');

    btn.disabled  = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> {{ __("ui.common.saving") }}';

    function onSuccess(txnId) {
        smsPaymentDone  = true;
        smsPaymentTxnId = txnId;
        document.getElementById('smsTransactionId').value = txnId;
        document.getElementById('smsPaiementOk').classList.remove('d-none');
        btn.classList.add('d-none');
        btnSave.disabled = false;
        btnSave.removeAttribute('disabled');
    }
    function onFail() {
        btn.disabled  = false;
        btn.innerHTML = '<i class="bx bx-wallet me-1"></i> {{ __("ui.automation.sms_pay_btn") }}';
    }

    if (!PAYMENT_CFG.enabled) {
        btn.disabled  = false;
        btn.innerHTML = '<i class="bx bx-wallet me-1"></i> {{ __("ui.automation.sms_pay_btn") }}';
        Swal.fire({ icon: 'info', title: '{{ __("ui.common.error") }}',
            text: '{{ __("ui.automation.sms_no_config") }}',
            confirmButtonText: '{{ __("ui.automation.sms_continue") }}' })
            .then(function (r) { if (r.isConfirmed) onSuccess('NO_PAYMENT'); else onFail(); });
        return;
    }

    var amount = smsPaymentAmount;
    if (!amount || amount <= 0) {
        btn.disabled  = false;
        btn.innerHTML = '<i class="bx bx-wallet me-1"></i> {{ __("ui.automation.sms_pay_btn") }}';
        Swal.fire('{{ __("ui.common.error") }}', '{{ __("ui.automation.sms_invalid_amount") }}', 'warning');
        return;
    }

    if (PAYMENT_CFG.provider === 'kkiapay') {
        if (typeof openKkiapayWidget !== 'function') {
            btn.disabled  = false;
            btn.innerHTML = '<i class="bx bx-wallet me-1"></i> {{ __("ui.automation.sms_pay_btn") }}';
            Swal.fire('{{ __("ui.common.error") }}', '{{ __("ui.automation.widget_not_loaded_kkiapay") }}', 'error');
            return;
        }

        // Polling pour détecter fermeture du widget (même approche que envoyer.blade.php)
        var kkDone = false, kkSeen = false, kkPoll = null;
        function kkVisible() {
            var el = document.querySelector('iframe[src*="kkiapay"]') ||
                     document.querySelector('[id*="kkiapay"]') ||
                     document.querySelector('[class*="kkiapay"]');
            if (!el) return false;
            var s = window.getComputedStyle(el);
            return s.display !== 'none' && s.visibility !== 'hidden';
        }
        kkPoll = setInterval(function () {
            var v = kkVisible();
            if (v) { kkSeen = true; }
            else if (kkSeen && !kkDone) { clearInterval(kkPoll); onFail(); }
        }, 300);
        setTimeout(function () { clearInterval(kkPoll); }, 900000);

        // Paramètre "key" (pas "api_key") — identique à envoyer.blade.php
        openKkiapayWidget({ amount: amount, key: PAYMENT_CFG.pub_key, sandbox: PAYMENT_CFG.sandbox });

        addSuccessListener(function (response) {
            if (kkDone) return;
            kkDone = true; clearInterval(kkPoll);
            onSuccess(response.transactionId);
        });
        addFailedListener(function () {
            if (kkDone) return;
            kkDone = true; clearInterval(kkPoll);
            Swal.fire('{{ __("ui.automation.pay_failed") }}', '{{ __("ui.automation.pay_failed_kkiapay") }}', 'error');
            onFail();
        });

    } else if (PAYMENT_CFG.provider === 'fedapay') {
        if (typeof FedaPay === 'undefined') {
            btn.disabled  = false;
            btn.innerHTML = '<i class="bx bx-wallet me-1"></i> {{ __("ui.automation.sms_pay_btn") }}';
            Swal.fire('{{ __("ui.common.error") }}', '{{ __("ui.automation.sdk_not_loaded_fedapay") }}', 'error');
            return;
        }
        FedaPay.init({
            public_key:  PAYMENT_CFG.pub_key,
            transaction: { amount: amount, description: '{{ __("ui.automation.fedapay_descr") }}' },
            onComplete: function (resp) {
                if (resp.reason === FedaPay.DIALOG_DISMISSED) { onFail(); return; }
                if (resp.transaction && resp.transaction.status === 'approved') {
                    onSuccess(resp.transaction.id.toString());
                } else {
                    Swal.fire('{{ __("ui.automation.pay_failed") }}', '{{ __("ui.automation.pay_failed_fedapay") }}', 'error');
                    onFail();
                }
            }
        }).open();

    } else {
        btn.disabled  = false;
        btn.innerHTML = '<i class="bx bx-wallet me-1"></i> {{ __("ui.automation.sms_pay_btn") }}';
        Swal.fire('{{ __("ui.common.error") }}', '{{ __("ui.automation.sms_no_provider") }}', 'error');
    }
});

document.getElementById('btnNouvelleRegle').addEventListener('click', () => {
    // Reset formulaire
    document.getElementById('regleId').value    = '';
    document.getElementById('regleType').value   = '';
    document.getElementById('regleCanal').value  = 'email';
    document.getElementById('regleHeure').value  = '08:00';
    document.getElementById('regleActif').checked = true;
    document.getElementById('regleJours').value       = '-5, 0, 3';
    document.getElementById('regleDelaiSms').value    = '3';
    document.getElementById('regleDelaiWa').value     = '5';
    document.getElementById('regleMoisEcheance').value= '2';
    document.getElementById('regleJourMois').value    = '1';
    afficherChampsType('');
    mettreAJourSectionSms();
    resetSmsPaiement();
    document.getElementById('modalRegleTitre').textContent = '{{ __("ui.automation.modal_title") }}';
    modalRegle.show();
});

// Enregistrer règle
document.getElementById('btnSauvegarderRegle').addEventListener('click', async () => {
    const type = document.getElementById('regleType').value;
    if (!type) { Swal.fire('{{ __("ui.automation.required_field") }}', '{{ __("ui.automation.type_required") }}', 'warning'); return; }

    const btn     = document.getElementById('btnSauvegarderRegle');
    const txtEl   = document.getElementById('btnSauvegarderTexte');
    const spinEl  = document.getElementById('btnSauvegarderSpinner');
    btn.disabled = true; txtEl.classList.add('d-none'); spinEl.classList.remove('d-none');

    // Vérifier si paiement SMS requis
    const canal = document.getElementById('regleCanal').value;
    if (canalInclutSms(canal) && !smsPaymentDone) {
        Swal.fire('{{ __("ui.automation.required_field") }}', '{{ __("ui.automation.sms_pay_required") }}', 'warning');
        btn.disabled = false; txtEl.classList.remove('d-none'); spinEl.classList.add('d-none');
        return;
    }

    try {
        const resp = await fetch(ROUTES.store, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({
                type,
                canal,
                heure_envoi:              document.getElementById('regleHeure').value,
                actif:                    document.getElementById('regleActif').checked ? 1 : 0,
                jours_declenchement:      document.getElementById('regleJours').value,
                delai_escalade_sms:       document.getElementById('regleDelaiSms').value,
                delai_escalade_whatsapp:  document.getElementById('regleDelaiWa').value,
                mois_avant_echeance:      document.getElementById('regleMoisEcheance').value,
                jour_du_mois:             document.getElementById('regleJourMois').value,
                transaction_id:           document.getElementById('smsTransactionId').value || null,
                country_code:             document.getElementById('smsPaysSelect')?.value || 'BJ',
                recipient_count:          document.getElementById('smsRecipientCount').value || 0,
            }),
        });
        const data = await resp.json();
        if (!data.status) throw new Error(data.message ?? '{{ __("common.swal_generic_error") }}');

        modalRegle.hide();
        resetSmsPaiement();
        Swal.fire({ icon: 'success', title: '{{ __("ui.common.saved") }}', text: data.message, timer: 1500, showConfirmButton: false });

        // Revenir sur l'onglet Règles et afficher la carte
        document.querySelector('#autoTabs .nav-link[data-tab="tabRegles"]').click();
        if (data.rule && data.rule.id) {
            document.getElementById('carteVide')?.remove();
            injecterOuMajRegleCard(data.rule);
        } else {
            // Fallback : recharger la page pour afficher les règles à jour
            setTimeout(() => window.location.reload(), 1600);
        }

    } catch (e) {
        Swal.fire('{{ __("ui.common.error") }}', e.message, 'error');
    } finally {
        btn.disabled = false; txtEl.classList.remove('d-none'); spinEl.classList.add('d-none');
    }
});

// ── Gestion des cartes règles ─────────────────────────────────────────────────

function injecterOuMajRegleCard(rule) {
    const container = document.getElementById('listeRegles');
    if (!container) return;

    // Vérifie si vide
    const carteVide = document.querySelector('#tabRegles .card .text-muted.py-5');
    if (carteVide) carteVide.closest('.card').remove();

    const existant = document.getElementById('ruleCard-' + rule.id);
    const html = buildRuleCardHtml(rule);
    if (existant) {
        existant.outerHTML = html;
    } else {
        container.insertAdjacentHTML('afterbegin', html);
    }
}

function buildRuleCardHtml(r) {
    const actifBadge   = r.actif
        ? '<span class="badge bg-success">{{ __("ui.automation.rule_active") }}</span>'
        : '<span class="badge bg-secondary">{{ __("ui.automation.rule_inactive") }}</span>';
    const joursStr = r.jours_declenchement ? r.jours_declenchement.map(j => j >= 0 ? 'J+'+j : 'J'+j).join(', ') : '';

    let details = '';
    if (r.type === 'rappel_loyer' && joursStr)
        details = `<div class="text-muted small mt-1"><i class="bx bx-calendar me-1"></i>${joursStr}</div>`;
    if (r.type === 'escalade_impaye')
        details = `<div class="text-muted small mt-1"><i class="bx bx-time me-1"></i>SMS à J+${r.delai_escalade_sms ?? 3} — WA à J+${r.delai_escalade_whatsapp ?? 5}</div>`;
    if (r.type === 'preavis_bail' || r.type === 'renouvellement')
        details = `<div class="text-muted small mt-1"><i class="bx bx-calendar-check me-1"></i>${r.mois_avant_echeance ?? 2}{{ __("ui.automation.months_before", ["n" => ""]) }}</div>`;
    if (r.type === 'rapport_mensuel')
        details = `<div class="text-muted small mt-1"><i class="bx bx-calendar me-1"></i>${r.jour_du_mois ?? 1}{{ __("ui.automation.day_of_month", ["jour" => ""]) }}</div>`;

    return `<div class="col-md-6 col-lg-4" id="ruleCard-${r.id}">
  <div class="card shadow-sm h-100">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <h6 class="fw-bold mb-0">${r.type_label}</h6>
        ${actifBadge}
      </div>
      <div class="mb-2">
        <span class="badge bg-label-info me-1">${r.canal_label}</span>
        <span class="badge bg-label-secondary"><i class="bx bx-time-five me-1"></i>${r.heure_envoi}</span>
      </div>
      ${details}
    </div>
    <div class="card-footer d-flex gap-2 justify-content-end py-2">
      ${CAN_TOGGLE_RULE ? `<button class="btn btn-sm btn-outline-${r.actif ? 'warning' : 'success'} btn-toggle-actif"
              data-id="${r.id}" title="${r.actif ? '{{ __("ui.common.deactivate") }}' : '{{ __("ui.common.activate") }}'}">
        <i class="bx bx-${r.actif ? 'pause' : 'play'}"></i>
      </button>` : ''}
      ${CAN_DELETE_RULE ? `<button class="btn btn-sm btn-outline-danger btn-supprimer-regle" data-id="${r.id}" title="{{ __("ui.common.delete") }}">
        <i class="bx bx-trash"></i>
      </button>` : ''}
    </div>
  </div>
</div>`;
}

// Délégation pour toggle actif + supprimer (sur tabRegles pour couvrir aussi les cartes vides)
document.getElementById('tabRegles').addEventListener('click', async function (e) {
    const btnToggle = e.target.closest('.btn-toggle-actif');
    const btnDel    = e.target.closest('.btn-supprimer-regle');

    if (btnToggle) {
        const id = btnToggle.dataset.id;
        btnToggle.disabled = true;
        try {
            const resp = await fetch(ROUTES.toggleActif, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                body: JSON.stringify({ id }),
            });
            const data = await resp.json();
            if (data.status) {
                Swal.fire({ icon: 'success', title: data.message, timer: 1200, showConfirmButton: false });
                // Met à jour le badge et le bouton directement dans le DOM
                const card = document.getElementById('ruleCard-' + id);
                if (card) {
                    const badge = card.querySelector('.badge.bg-success, .badge.bg-secondary');
                    if (badge) { badge.textContent = data.actif ? '{{ __("ui.automation.rule_active") }}' : '{{ __("ui.automation.rule_inactive") }}'; badge.className = 'badge ' + (data.actif ? 'bg-success' : 'bg-secondary'); }
                    const icon = btnToggle.querySelector('i');
                    if (icon) icon.className = 'bx bx-' + (data.actif ? 'pause' : 'play');
                    btnToggle.className = 'btn btn-sm btn-outline-' + (data.actif ? 'warning' : 'success') + ' btn-toggle-actif';
                    btnToggle.title = data.actif ? '{{ __("ui.common.deactivate") }}' : '{{ __("ui.common.activate") }}';
                }
            }
        } finally {
            btnToggle.disabled = false;
        }
        return;
    }

    if (btnDel) {
        const id = btnDel.dataset.id;
        const res = await Swal.fire({
            title: '{{ __("ui.automation.delete_confirm") }}',
            text: '{{ __("ui.common.irreversible") }}',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: '{{ __("ui.common.delete") }}',
            cancelButtonText: '{{ __("ui.common.cancel") }}',
        });
        if (!res.isConfirmed) return;

        const resp = await fetch(ROUTES.destroy, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ id }),
        });
        const data = await resp.json();
        if (data.status) {
            document.getElementById('ruleCard-' + id)?.remove();
            Swal.fire({ icon: 'success', title: '{{ __("ui.automation.deleted") }}', timer: 1200, showConfirmButton: false });
        }
    }
});

// ── Historique ────────────────────────────────────────────────────────────────

async function chargerLogs() {
    const params = new URLSearchParams({
        type:   document.getElementById('filtreLogType').value,
        canal:  document.getElementById('filtreLogCanal').value,
        statut: document.getElementById('filtreLogStatut').value,
        depuis: document.getElementById('filtreLogDepuis').value,
    });

    const tbody = document.getElementById('tbodyHistorique');
    tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4"><span class="spinner-border spinner-border-sm me-2"></span> {{ __("ui.common.loading") }}</td></tr>';

    try {
        const resp = await fetch(ROUTES.logs + '?' + params.toString());
        const data = await resp.json();

        if (!data.logs.length) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center text-muted py-4"><i class="bx bx-history fs-3 d-block mb-1"></i>{{ __("ui.automation.hist_empty") }}</td></tr>';
            logsCharges = true;
            return;
        }

        const TYPE_LABELS = {
            rappel_loyer: '{{ __("ui.automation.log_rappel") }}', escalade_impaye: '{{ __("ui.automation.log_escalade") }}',
            preavis_bail: '{{ __("ui.automation.log_preavis") }}', rapport_mensuel: '{{ __("ui.automation.log_rapport") }}',
            renouvellement: '{{ __("ui.automation.log_renouvellement") }}',
        };

        tbody.innerHTML = data.logs.map(l => `
            <tr>
              <td>${l.locataire}</td>
              <td><small>${TYPE_LABELS[l.type] ?? l.type}</small></td>
              <td>${l.canal_badge}</td>
              <td><small class="text-muted">${l.reference ?? '–'}</small></td>
              <td>${l.statut_badge}</td>
              <td><small class="text-muted">${l.message ? l.message.substring(0,60) : '–'}</small></td>
              <td><small>${l.envoye_le}</small></td>
            </tr>`).join('');

        logsCharges = true;
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center text-danger py-4">{{ __("ui.automation.hist_error") }}</td></tr>';
    }
}

document.getElementById('btnFiltrerLogs').addEventListener('click', () => {
    logsCharges = false;
    chargerLogs();
});

// ── Locataires non notifiés ────────────────────────────────────────────────────────

async function chargerOptOut() {
    const tbody = document.getElementById('tbodyOptOut');
    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4"><span class="spinner-border spinner-border-sm me-2"></span> {{ __("ui.common.loading") }}</td></tr>';

    try {
        const resp = await fetch(ROUTES.locataires);
        const data = await resp.json();

        if (!data.locataires.length) {
            tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">{{ __("ui.automation.optout_empty") }}</td></tr>';
            optOutCharges = true;
            return;
        }

        tbody.innerHTML = data.locataires.map(l => `
            <tr>
              <td><strong>${l.nom}</strong></td>
              <td>${l.telephone}</td>
              <td>${l.email}</td>
              <td class="text-center">
                ${CAN_TOGGLE_OPTOUT
                    ? `<div class="form-check form-switch d-inline-flex">
                         <input class="form-check-input btn-toggle-optout" type="checkbox" data-id="${l.id}"
                                ${l.opt_out ? 'checked' : ''} title="Exclure des rappels automatiques">
                       </div>`
                    : `<span class="badge ${l.opt_out ? 'bg-label-danger' : 'bg-label-success'}">${l.opt_out ? '{{ __("ui.automation.optout_excluded") }}' : '{{ __("ui.automation.optout_included") }}'}</span>`
                }
              </td>
            </tr>`).join('');

        optOutCharges = true;
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger py-4">{{ __("ui.automation.hist_error") }}</td></tr>';
    }
}

document.getElementById('tbodyOptOut').addEventListener('change', async function (e) {
    const toggle = e.target.closest('.btn-toggle-optout');
    if (!toggle) return;

    const id = toggle.dataset.id;
    toggle.disabled = true;

    try {
        const resp = await fetch(ROUTES.optOut, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
            body: JSON.stringify({ id }),
        });
        const data = await resp.json();
        if (!data.status) { toggle.checked = !toggle.checked; Swal.fire('{{ __("ui.common.error") }}', data.message, 'error'); }
        else {
            Swal.fire({ icon: 'info', title: data.message, timer: 1200, showConfirmButton: false });
        }
    } catch (e) {
        toggle.checked = !toggle.checked;
        Swal.fire('{{ __("ui.common.error") }}', '{{ __("ui.automation.optout_error") }}', 'error');
    } finally {
        toggle.disabled = false;
    }
});

})();
</script>
@endpush
@endsection
