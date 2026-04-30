@extends('layouts.template')

@section('title')
<title>{{ __('pages.tenant_title') }}</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/css/intlTelInput.css">
<style>
    .iti__flag {
        background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/img/flags.png");
    }
    @media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
        .iti__flag {
            background-image: url("https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/img/flags@2x.png");
        }
    }
    .iti { width: 100%; }
    .iti__flag-container { z-index: 1060; }
</style>
@endsection

@section('content')

@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('common.home_breadcrumb') }}</span> {{ __('pages.tenant_breadcrumb') }}</h4>

  @can('ajoute-locataire')
  <div class="col-md-6">
    <div class="demo-inline-spacing">
      <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal"
        data-bs-target="#AjouterLocataire">
        <span class="bx bx-plus"></span>
      </button>
    </div>
  </div><br />
  @endcan

  <!-- Modal Ajouter Locataire -->
  <div class="modal fade" id="AjouterLocataire" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white">{{ __('pages.tenant_add_modal') }}</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('common.btn_close') }}"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3" id="formulaire" onsubmit="save_locataire(event)">
            @csrf

            <div class="col-6">
              <label class="form-label">{{ __('pages.tenant_choose_house') }} <span class="text-danger">*</span></label>
              <select required class="form-select" name="nom_maison" id="nom_maison">
                <option selected disabled value="">{{ __('pages.tenant_choose_house_opt') }}</option>
                @if(isset($allMaison))
                @foreach($allMaison as $terme)
                <option value="{{ $terme->id }}">{{ $terme->nom_maison }}</option>
                @endforeach
                @endif
              </select>
              <span class="invalid-feedback nom_maison_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label class="form-label">{{ __('pages.tenant_lastname') }} <span class="text-danger">*</span></label>
              <input type="text" name="nom_locataire" id="nom_locataire" class="form-control" required>
              <span class="invalid-feedback nom_locataire_err" role="alert"></span>
            </div>

            <div class="col-6">
              <label class="form-label">{{ __('pages.tenant_choose_room') }} <span class="text-danger">*</span></label>
              <select class="form-select" name="numero_chambre" id="numero_chambre">
                <option selected disabled value="">{{ __('pages.tenant_choose_room_opt') }}</option>
              </select>
              <span class="invalid-feedback numero_chambre_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label class="form-label">{{ __('pages.tenant_firstname') }} <span class="text-danger">*</span></label>
              <input type="text" name="prenom_locataire" id="prenom_locataire" class="form-control" required>
              <span class="invalid-feedback prenom_locataire_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label class="form-label">{{ __('pages.tenant_phone') }} <span class="text-danger">*</span></label>
              <input type="tel" name="telephone" id="tel-ajouter-loc" class="form-control" required>
              <span class="invalid-feedback telephone_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label class="form-label">{{ __('pages.tenant_profession') }} <span class="text-danger">*</span></label>
              <input type="text" name="profession" id="profession" class="form-control" required>
              <span class="invalid-feedback profession_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label class="form-label">{{ __('pages.tenant_email_opt') }}</label>
              <input type="email" name="email" id="email_locataire" class="form-control" placeholder="exemple@mail.com">
            </div>

            <div class="col-6">
              <label class="form-label">{{ __('pages.tenant_room_type') }}</label>
              <input type="text" name="type_chambre" class="form-control" id="type_chambre_getData" readonly disabled>
            </div>

            <div class="col-3">
              <label class="form-label">{{ __('pages.tenant_water_deposit') }} <span class="text-danger">*</span></label>
              <input type="text" name="caution_eau" class="form-control" id="caution_eau" required>
              <span class="invalid-feedback caution_eau_err" role="alert"></span>
            </div>

            <div class="col-md-3">
              <label class="form-label">{{ __('pages.tenant_electricity') }} <span class="text-danger">*</span></label>
              <input type="text" name="caution_courant" id="caution_courant" class="form-control" required>
              <span class="invalid-feedback caution_courant_err" role="alert"></span>
            </div>

            <div class="col-6">
              <label class="form-label">{{ __('pages.tenant_price_month') }}</label>
              <input type="text" name="prix_mois" class="form-control" id="prix_mois" readonly>
            </div>

            <div class="col-3">
              <label class="form-label">{{ __('pages.tenant_deposit_for') }} <span class="text-danger">*</span></label>
              <select class="form-select" name="nombre_caution" id="nombre_caution" required>
                <option selected disabled value="">{{ __('pages.tenant_deposit_for_opt') }}</option>
                <option value="2">2 {{ __('pages.tenant_months') }}</option>
                <option value="3">3 {{ __('pages.tenant_months') }}</option>
                <option value="4">4 {{ __('pages.tenant_months') }}</option>
                <option value="5">5 {{ __('pages.tenant_months') }}</option>
                <option value="6">6 {{ __('pages.tenant_months') }}</option>
                <option value="12">12 {{ __('pages.tenant_months') }}</option>
              </select>
            </div>

            <div class="col-3">
              <label class="form-label">{{ __('pages.tenant_entry_date') }} <span class="text-danger">*</span></label>
              <input type="date" name="date_entre" class="form-control" min="<?= date('1970-m-d'); ?>" id="date_entre" required>
              <span class="invalid-feedback date_entre_err" role="alert"></span>
            </div>

            <div class="col-6">
              <label class="form-label">{{ __('pages.tenant_advance_for') }}</label>
              <select class="form-select" name="nombre_avance" id="nombre_avance">
                <option selected disabled value="">{{ __('pages.tenant_advance_for_opt') }}</option>
                <option value="2">2 {{ __('pages.tenant_months') }}</option>
                <option value="3">3 {{ __('pages.tenant_months') }}</option>
                <option value="4">4 {{ __('pages.tenant_months') }}</option>
                <option value="5">5 {{ __('pages.tenant_months') }}</option>
                <option value="6">6 {{ __('pages.tenant_months') }}</option>
                <option value="12">12 {{ __('pages.tenant_months') }}</option>
              </select>
            </div>

            <div class="col-6">
              <label class="form-label">{{ __('pages.tenant_payment_mode') }}</label>
              <select class="form-select" name="mode_paiement" id="mode_paiement">
                <option selected disabled value="">{{ __('pages.tenant_payment_mode_opt') }}</option>
                <option value="Mobile money">{{ __('pages.pay_mobile_money') }}</option>
                <option value="Virement bancaire">{{ __('pages.pay_bank_transfer') }}</option>
                <option value="Chèque">{{ __('pages.pay_cheque') }}</option>
                <option value="Espèces">{{ __('pages.pay_cash') }}</option>
              </select>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
              <button type="submit" class="btn btn-primary" id="btnSaveLocataire" style="min-width:130px;">
                <span class="bx bx-save me-1"></span> {{ __('common.btn_save') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- ── 3 modals partagés (un seul exemplaire chacun) ── -->

  <!-- Modal Détails partagé -->
  <div class="modal fade" id="sharedDetailsLoc" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white"><i class="bx bx-user-circle me-2"></i><span id="d-nom-locataire"></span></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-0">

          <!-- Infos principales -->
          <div class="px-4 pt-3 pb-2">
            <div class="row g-3">
              <div class="col-6">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-label-primary p-2"><i class="bx bx-phone"></i></span>
                  <div>
                    <div class="text-muted small">{{ __('pages.tenant_phone') }}</div>
                    <div class="fw-semibold" id="d-telephone"></div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-label-info p-2"><i class="bx bx-calendar"></i></span>
                  <div>
                    <div class="text-muted small">{{ __('pages.tenant_date_entry') }}</div>
                    <div class="fw-semibold" id="d-date-entree"></div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-label-success p-2"><i class="bx bx-credit-card"></i></span>
                  <div>
                    <div class="text-muted small">{{ __('pages.tenant_payment_mode_d') }}</div>
                    <div class="fw-semibold" id="d-mode-paiement"></div>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-label-warning p-2"><i class="bx bx-briefcase"></i></span>
                  <div>
                    <div class="text-muted small">{{ __('pages.tenant_profession_d') }}</div>
                    <div class="fw-semibold" id="d-profession"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <hr class="my-2">

          <!-- Cautions & Avances -->
          <div class="px-4 pb-3">
            <p class="text-muted small text-uppercase fw-semibold mb-2">{{ __('pages.tenant_details_section') }}</p>
            <div class="row g-2">
              <div class="col-6">
                <div class="card border-0 bg-light rounded-3 p-2 text-center">
                  <div class="text-muted small">{{ __('pages.tenant_caution') }}</div>
                  <div class="fw-bold text-primary fs-6"><span id="d-caution"></span> {{ __('pages.tenant_months') }}</div>
                </div>
              </div>
              <div class="col-6">
                <div class="card border-0 bg-light rounded-3 p-2 text-center">
                  <div class="text-muted small">{{ __('pages.tenant_advance') }}</div>
                  <div class="fw-bold text-info fs-6"><span id="d-avance"></span> {{ __('pages.tenant_months') }}</div>
                </div>
              </div>
              <div class="col-4">
                <div class="card border-0 bg-light rounded-3 p-2 text-center">
                  <div class="text-muted small">{{ __('pages.tenant_consumed') }}</div>
                  <div class="fw-bold text-warning fs-6"><span id="d-avance-consomme"></span> {{ __('pages.tenant_months') }}</div>
                </div>
              </div>
              <div class="col-4">
                <div class="card border-0 bg-light rounded-3 p-2 text-center">
                  <div class="text-muted small">{{ __('pages.tenant_water_caution') }}</div>
                  <div class="fw-bold text-success fs-6"><span id="d-caution-eau"></span> <small>XOF</small></div>
                </div>
              </div>
              <div class="col-4">
                <div class="card border-0 bg-light rounded-3 p-2 text-center">
                  <div class="text-muted small">{{ __('pages.tenant_elec_caution') }}</div>
                  <div class="fw-bold text-danger fs-6"><span id="d-caution-courant"></span> <small>XOF</small></div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Suppression partagé -->
  <div class="modal fade" id="sharedSupprimerLoc" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title text-white">{{ __('pages.tenant_remove_modal') }}</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          {{ __('pages.tenant_remove_confirm') }} <strong id="s-nom-locataire"></strong> ?
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_no') }}</button>
          <button type="button" class="btn btn-danger" id="btnConfirmSupprLoc">{{ __('pages.tenant_remove_btn') }}</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Modification partagé -->
  <div class="modal fade" id="sharedModifierLoc" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white">{{ __('pages.tenant_edit_modal') }}</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3" id="formModifLocShared" onsubmit="updateLocataire(event)">
            @csrf
            <input type="hidden" name="locataire_id" id="m-locataire-id">

            <div class="col-6">
              <label class="form-label">{{ __('common.label_house') }}</label>
              <input type="text" class="form-control" id="m-maison" readonly disabled>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.tenant_name_modal') }} <span class="text-danger">*</span></label>
              <input type="text" name="nom_locataire" id="m-nom" class="form-control" required>
            </div>
            <div class="col-6">
              <label class="form-label">{{ __('pages.tenant_room_no_modal') }}</label>
              <input type="text" class="form-control" id="m-chambre" readonly disabled>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.tenant_firstname') }} <span class="text-danger">*</span></label>
              <input type="text" name="prenom_locataire" id="m-prenom" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.tenant_phone') }} <span class="text-danger">*</span></label>
              <input type="tel" name="telephone" id="m-telephone" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.tenant_email_opt') }}</label>
              <input type="email" name="email" id="m-email" class="form-control" placeholder="exemple@mail.com">
            </div>
            <div class="col-6">
              <label class="form-label">{{ __('pages.tenant_room_type') }}</label>
              <input type="text" name="type_chambre" id="m-type-chambre" class="form-control" readonly disabled>
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.tenant_profession') }} <span class="text-danger">*</span></label>
              <input type="text" name="profession" id="m-profession" class="form-control" required>
            </div>
            <div class="col-6">
              <label class="form-label">{{ __('pages.tenant_water_deposit') }} <span class="text-danger">*</span></label>
              <input type="text" name="caution_eau" id="m-caution-eau" class="form-control" onkeypress="return /[0-9]/i.test(event.key)">
            </div>
            <div class="col-md-6">
              <label class="form-label">{{ __('pages.tenant_electricity') }} <span class="text-danger">*</span></label>
              <input type="text" name="caution_courant" id="m-caution-courant" class="form-control" onkeypress="return /[0-9]/i.test(event.key)">
            </div>
            <div class="col-3">
              <label class="form-label">{{ __('pages.tenant_deposit_for') }} <span class="text-danger">*</span></label>
              <select class="form-select" name="nombre_caution" id="m-nombre-caution">
                <option value="2">2 {{ __('pages.tenant_months') }}</option>
                <option value="3">3 {{ __('pages.tenant_months') }}</option>
                <option value="4">4 {{ __('pages.tenant_months') }}</option>
                <option value="5">5 {{ __('pages.tenant_months') }}</option>
                <option value="6">6 {{ __('pages.tenant_months') }}</option>
                <option value="12">12 {{ __('pages.tenant_months') }}</option>
              </select>
            </div>
            <div class="col-3">
              <label class="form-label">{{ __('pages.tenant_entry_date') }} <span class="text-danger">*</span></label>
              <input type="date" name="date_entre" id="m-date-entree" class="form-control" required min="<?= date('1970-m-d'); ?>">
            </div>
            <div class="col-6">
              <label class="form-label">{{ __('pages.tenant_advance_for') }}</label>
              <select class="form-select" name="nombre_avance" id="m-nombre-avance">
                <option value="">—</option>
                <option value="2">2 {{ __('pages.tenant_months') }}</option>
                <option value="3">3 {{ __('pages.tenant_months') }}</option>
                <option value="4">4 {{ __('pages.tenant_months') }}</option>
                <option value="5">5 {{ __('pages.tenant_months') }}</option>
                <option value="6">6 {{ __('pages.tenant_months') }}</option>
                <option value="12">12 {{ __('pages.tenant_months') }}</option>
              </select>
            </div>
            <div class="col-6">
              <label class="form-label">{{ __('pages.tenant_payment_mode') }}</label>
              <select class="form-select" name="mode_paiement" id="m-mode-paiement">
                <option value="">—</option>
                <option value="Mobile money">{{ __('pages.pay_mobile_money') }}</option>
                <option value="Virement bancaire">{{ __('pages.pay_bank_transfer') }}</option>
                <option value="Chèque">{{ __('pages.pay_cheque') }}</option>
                <option value="Espèces">{{ __('pages.pay_cash') }}</option>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
              <button type="submit" class="btn btn-primary" id="btnSaveModifLoc" style="min-width:130px;">
                <span class="bx bx-save me-1"></span> {{ __('common.btn_save') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- ────────────────────────────────────────────────── -->

  <!-- Tableau des locataires -->
  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-center">
      <h5 class="mb-0 text-white"><i class="bx bx-group me-1"></i> {{ __('pages.tenant_list') }}</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="example" class="table table-striped table-hover align-middle mb-0" style="width:100%">
          <thead class="table-light">
            <tr>
              <th>{{ __('common.th_house') }}</th>
              <th>{{ __('common.th_room_no') }}</th>
              <th>{{ __('common.label_tenant') }}</th>
              <th class="text-center">{{ __('common.th_actions') }}</th>
            </tr>
          </thead>
          <tbody>
            @can('Consulter-locataire')
            @if(isset($allLocataire))
            @foreach($allLocataire as $item)
            <tr id="row-{{ $item->id }}"
                data-id="{{ $item->id }}"
                data-nom="{{ $item->nom }}"
                data-prenom="{{ $item->prenom }}"
                data-maison="{{ $item->nom_maison }}"
                data-chambre="{{ $item->numero_chambre }}"
                data-type-chambre="{{ $item->type_chambre }}"
                data-telephone="{{ $item->telephone }}"
                data-profession="{{ $item->profession }}"
                data-email="{{ $item->email }}"
                data-caution-eau="{{ $item->caution_eau }}"
                data-caution-courant="{{ $item->caution_courant }}"
                data-nombre-caution="{{ $item->nombre_caution }}"
                data-nombre-avance="{{ $item->nombre_avance }}"
                data-nombre-avance-consomme="{{ $item->nombre_avance_consomme }}"
                data-date-entree="{{ $item->date_entree }}"
                data-mode-paiement="{{ $item->mode_paiement }}"
            >
              <th scope="row">{{ $item->nom_maison }}</th>
              <td>{{ $item->numero_chambre }} ({{ $item->type_chambre }})</td>
              <td>{{ $item->nom }} {{ $item->prenom }}</td>
              <td class="text-center">
                @can('modify-locataire')
                <a class="btn btn-sm btn-primary me-1 btn-modif-loc" title="{{ __('common.title_edit') }}" href="#">
                  <i class="bx bx-edit-alt"></i>
                </a>
                @endcan
                @can('delete-locataire')
                <a class="btn btn-sm btn-danger me-1 btn-supp-loc" title="{{ __('pages.tenant_remove_btn') }}" href="#">
                  <i class="bx bx-trash"></i>
                </a>
                @endcan
                @can('download-recu-avance')
                <a class="btn btn-sm btn-success me-1" title="{{ __('common.title_download') }}"
                  href="{{ route('telecharge', ['id' => $item->id]) }}">
                  <i class="bx bx-download"></i>
                </a>
                @endcan
                @can('download-recu-location')
                <a class="btn btn-sm btn-outline-primary me-1" title="Relevé de compte"
                  href="{{ route('releve_locataire', ['id' => $item->id]) }}">
                  <i class="bx bx-file"></i>
                </a>
                <a class="btn btn-sm btn-outline-secondary me-1" title="Attestation de résidence"
                  href="{{ route('attestation_residence', ['id' => $item->id]) }}">
                  <i class="bx bx-home-check"></i>
                </a>
                @endcan
                <button type="button" class="btn btn-sm btn-primary btn-details-loc">
                  <i class="bx bx-zoom-in"></i>
                </button>
              </td>
            </tr>
            @endforeach
            @endif
            @endcan
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/intlTelInput.min.js"></script>
<script>
// ── Supprimer les anciens handlers (navigation SPA) pour éviter les doublons ──
$(document).off('.page');

$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

var canModifyLocataire   = @json(Auth::user()->can('modify-locataire'));
var canDeleteLocataire   = @json(Auth::user()->can('delete-locataire'));
var canDownloadLocataire = @json(Auth::user()->can('download-recu-avance'));
var canDownloadReleve    = @json(Auth::user()->can('download-recu-location'));

var I18N_LOC = {
    titleEdit:    '{{ __('common.title_edit') }}',
    titleRemove:  '{{ __('pages.tenant_remove_btn') }}',
    titleDownload:'{{ __('common.title_download') }}',
    chooseRoom:   '{{ __('pages.tenant_choose_room_opt') }}',
    inProgress:   '{{ __('pages.owner_in_progress') }}',
    btnSave:      '{{ __('common.btn_save') }}',
    errOccurred:  '{{ __('common.swal_generic_error') }}',
};

// ── intl-tel-input : détruire l'instance précédente avant de réinitialiser ───
var ITI_UTILS = 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js';
var ITI_OPTIONS = {
    initialCountry: 'bj',
    preferredCountries: ['bj', 'tg', 'sn', 'ci', 'gh', 'ng', 'fr', 'be'],
    separateDialCode: true,
    utilsScript: ITI_UTILS
};

if (window._itiLocAjouter)  { try { window._itiLocAjouter.destroy();  } catch(e) {} }
if (window._itiLocModifier) { try { window._itiLocModifier.destroy(); } catch(e) {} }
window._itiLocAjouter  = window.intlTelInput(document.getElementById('tel-ajouter-loc'), ITI_OPTIONS);
window._itiLocModifier = window.intlTelInput(document.getElementById('m-telephone'),     ITI_OPTIONS);
var itiLocAjouter  = window._itiLocAjouter;
var itiLocModifier = window._itiLocModifier;

function toastLoc(message, icon) {
    Swal.fire({ toast: true, position: 'top-end', icon: icon, title: message, showConfirmButton: false, timer: 3500, timerProgressBar: true });
}

function closeModalLoc(selector) {
    var $m = $(selector);
    try { bootstrap.Modal.getOrCreateInstance($m[0]).hide(); } catch(e) { $m.modal('hide'); }
    setTimeout(function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css({ overflow: '', paddingRight: '' });
    }, 350);
}

function printErrorMsg(msg) {
    $.each(msg, function(key, value) { $('.' + key + '_err').text(value).show(); });
}

$(document).on('keyup.page', '#caution_eau',    function() { Sepatateur_Milliers('#caution_eau'); });
$(document).on('keyup.page', '#caution_courant',function() { Sepatateur_Milliers('#caution_courant'); });

// ── Ouverture modal Détails ──────────────────────────────────────────────────
$(document).on('click.page', '.btn-details-loc', function() {
    var r = $(this).closest('tr');
    $('#d-nom-locataire').text(r.data('nom') + ' ' + r.data('prenom'));
    $('#d-telephone').text(r.data('telephone') || '—');
    $('#d-date-entree').text(r.data('date-entree') || '—');
    $('#d-mode-paiement').text(r.data('mode-paiement') || '—');
    $('#d-profession').text(r.data('profession') || '—');
    $('#d-caution').text(r.data('nombre-caution') || 0);
    $('#d-avance').text(r.data('nombre-avance') || 0);
    $('#d-avance-consomme').text(r.data('nombre-avance-consomme') || 0);
    $('#d-caution-eau').text(parseInt(r.data('caution-eau') || 0).toLocaleString('fr-FR'));
    $('#d-caution-courant').text(parseInt(r.data('caution-courant') || 0).toLocaleString('fr-FR'));
    $('#sharedDetailsLoc').modal('show');
});

// ── Ouverture modal Suppression ──────────────────────────────────────────────
$(document).on('click.page', '.btn-supp-loc', function() {
    var r = $(this).closest('tr');
    $('#s-nom-locataire').text(r.data('nom') + ' ' + r.data('prenom'));
    $('#sharedSupprimerLoc').data('locataire-id', r.data('id'));
    $('#sharedSupprimerLoc').modal('show');
});

$(document).on('click.page', '#btnConfirmSupprLoc', function() {
    var id = $('#sharedSupprimerLoc').data('locataire-id');
    if (!id) return;
    $.ajax({
        url: "{{ route('destroy_locataire') }}",
        method: "POST",
        data: { _token: $('meta[name="csrf-token"]').attr('content'), id: id },
        success: function(resp) {
            if (resp.status) {
                closeModalLoc('#sharedSupprimerLoc');
                if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.isDataTable('#example')) {
                    $('#example').DataTable().row('#row-' + id).remove().draw(false);
                } else {
                    $('#row-' + id).remove();
                }
                toastLoc(resp.message, 'success');
            } else {
                toastLoc(resp.message, 'warning');
            }
        },
        error: function() { toastLoc(I18N_LOC.errOccurred, 'error'); }
    });
});

// ── Ouverture modal Modification ─────────────────────────────────────────────
$(document).on('click.page', '.btn-modif-loc', function() {
    var r = $(this).closest('tr');
    $('#m-locataire-id').val(r.data('id'));
    $('#m-maison').val(r.data('maison'));
    $('#m-nom').val(r.data('nom'));
    $('#m-chambre').val(r.data('chambre'));
    $('#m-prenom').val(r.data('prenom'));
    $('#m-email').val(r.data('email') || '');
    $('#m-type-chambre').val(r.data('type-chambre'));
    $('#m-profession').val(r.data('profession'));
    $('#m-caution-eau').val(r.data('caution-eau'));
    $('#m-caution-courant').val(r.data('caution-courant'));
    $('#m-nombre-caution').val(r.data('nombre-caution'));
    $('#m-date-entree').val(r.data('date-entree'));
    $('#m-nombre-avance').val(r.data('nombre-avance') || '');
    $('#m-mode-paiement').val(r.data('mode-paiement') || '');
    itiLocModifier.setNumber(r.data('telephone') || '');
    $('#sharedModifierLoc').modal('show');
});

// ── Chargement chambres / type / prix ────────────────────────────────────────
$(document).on('change.page', '#nom_maison', function() {
    var idMaison = $(this).val();
    if (!idMaison) return;
    $.get('{{ url("gerer-locataire/numero_chambre") }}', { idMaison: idMaison }, function(data) {
        $('#numero_chambre').html(data.list_chambre);
        $('#type_chambre_getData').val('');
        $('#prix_mois').val('');
    });
});

$(document).on('change.page', '#numero_chambre', function() {
    var id = $(this).val();
    if (!id) return;
    $.get('{{ url("gerer-locataire/type_chambre") }}', { numero_chambre_got: id }, function(data) {
        $('#type_chambre_getData').val(data.type_chambres_get);
    });
    $.get('{{ url("gerer-locataire/get_prix") }}', { prixGot: id }, function(data) {
        $('#prix_mois').val(data.prixApayer);
    });
});

// ── Ajout locataire ──────────────────────────────────────────────────────────
function save_locataire(e) {
    e.preventDefault();
    var btn = $('#btnSaveLocataire');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> ' + I18N_LOC.inProgress);

    var data = new FormData();
    $('#formulaire').serializeArray().forEach(function(f) { data.append(f.name, f.value); });
    // Écraser le champ telephone avec le numéro international complet
    data.set('telephone', itiLocAjouter.getNumber());

    $.ajax({
        url: "{{ route('store_locataire') }}",
        method: "POST",
        processData: false,
        contentType: false,
        data: data,
        success: function(resp) {
            btn.prop('disabled', false).html('<span class="bx bx-save me-1"></span> ' + I18N_LOC.btnSave);
            if (resp.error) { printErrorMsg(resp.error); return; }
            if (resp.status) {
                var l = resp.locataire;
                var btns = '';
                if (canModifyLocataire)  btns += '<a class="btn btn-sm btn-primary me-1 btn-modif-loc" title="' + I18N_LOC.titleEdit + '" href="#"><i class="bx bx-edit-alt"></i></a>';
                if (canDeleteLocataire)  btns += '<a class="btn btn-sm btn-danger me-1 btn-supp-loc" title="' + I18N_LOC.titleRemove + '" href="#"><i class="bx bx-trash"></i></a>';
                if (canDownloadLocataire) btns += '<a class="btn btn-sm btn-success me-1" href="{{ route('telecharge', ['id' => 'LOCID']) }}'.replace('LOCID', l.id) + '"><i class="bx bx-download"></i></a>';
                if (canDownloadReleve) btns += '<a class="btn btn-sm btn-outline-primary me-1" title="Releve" href="{{ route('releve_locataire', ['id' => 'LOCID']) }}'.replace('LOCID', l.id) + '"><i class="bx bx-file"></i></a>';
                if (canDownloadReleve) btns += '<a class="btn btn-sm btn-outline-secondary me-1" title="Attestation residence" href="{{ route('attestation_residence', ['id' => 'LOCID']) }}'.replace('LOCID', l.id) + '"><i class="bx bx-home-check"></i></a>';
                btns += '<button type="button" class="btn btn-sm btn-primary btn-details-loc"><i class="bx bx-zoom-in"></i></button>';

                var newRow = $('<tr id="row-' + l.id + '"></tr>')
                    .attr('data-id', l.id)
                    .attr('data-nom', l.nom)
                    .attr('data-prenom', l.prenom)
                    .attr('data-maison', l.nom_maison)
                    .attr('data-chambre', l.numero_chambre)
                    .attr('data-type-chambre', l.type_chambre)
                    .attr('data-telephone', l.telephone)
                    .attr('data-profession', l.profession)
                    .attr('data-email', l.email || '')
                    .attr('data-caution-eau', l.caution_eau || 0)
                    .attr('data-caution-courant', l.caution_courant || 0)
                    .attr('data-nombre-caution', l.nombre_caution || 0)
                    .attr('data-nombre-avance', l.nombre_avance || '')
                    .attr('data-nombre-avance-consomme', 0)
                    .attr('data-date-entree', l.date_entree || '')
                    .attr('data-mode-paiement', l.mode_paiement || '')
                    .append('<th scope="row">' + l.nom_maison + '</th>')
                    .append('<td>' + l.numero_chambre + ' (' + l.type_chambre + ')</td>')
                    .append('<td>' + l.nom + ' ' + l.prenom + '</td>')
                    .append('<td class="text-center">' + btns + '</td>');

                if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.isDataTable('#example')) {
                    $('#example').DataTable().row.add(newRow[0]).draw(false);
                } else {
                    $('#example tbody').prepend(newRow);
                }
                $('#formulaire')[0].reset();
                itiLocAjouter.setCountry('bj');
                $('#numero_chambre').html('<option selected disabled value="">' + I18N_LOC.chooseRoom + '</option>');
                $('#type_chambre_getData').val('');
                $('#prix_mois').val('');
                toastLoc(resp.message, 'success');
            } else {
                toastLoc(resp.message, 'warning');
            }
        },
        error: function() {
            btn.prop('disabled', false).html('<span class="bx bx-save me-1"></span> ' + I18N_LOC.btnSave);
            toastLoc(I18N_LOC.errOccurred, 'error');
        }
    });
}

// ── Mise à jour locataire ────────────────────────────────────────────────────
function updateLocataire(e) {
    e.preventDefault();
    var btn = $('#btnSaveModifLoc');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> ' + I18N_LOC.inProgress);
    var id = $('#m-locataire-id').val();
    var data = new FormData();
    $('#formModifLocShared').serializeArray().forEach(function(f) { data.append(f.name, f.value); });
    // Écraser le champ telephone avec le numéro international complet
    data.set('telephone', itiLocModifier.getNumber());

    $.ajax({
        url: "{{ route('update_locataire') }}",
        method: "POST",
        processData: false,
        contentType: false,
        data: data,
        success: function(resp) {
            btn.prop('disabled', false).html('<span class="bx bx-save me-1"></span> ' + I18N_LOC.btnSave);
            if (resp.status) {
                var nom    = $('#m-nom').val().toUpperCase();
                var prenom = $('#m-prenom').val();
                var row    = $('#row-' + id);
                var telFull = itiLocModifier.getNumber();
                // Mettre à jour les data attributes de la ligne
                row.attr('data-nom', nom)
                   .attr('data-prenom', prenom)
                   .attr('data-telephone', telFull)
                   .attr('data-profession', $('#m-profession').val())
                   .attr('data-email', $('#m-email').val())
                   .attr('data-caution-eau', $('#m-caution-eau').val())
                   .attr('data-caution-courant', $('#m-caution-courant').val())
                   .attr('data-nombre-caution', $('#m-nombre-caution').val())
                   .attr('data-date-entree', $('#m-date-entree').val())
                   .attr('data-nombre-avance', $('#m-nombre-avance').val())
                   .attr('data-mode-paiement', $('#m-mode-paiement').val());
                row.find('td:nth-child(3)').text(nom + ' ' + prenom);
                closeModalLoc('#sharedModifierLoc');
                toastLoc(resp.message, 'success');
            } else {
                toastLoc(resp.message, 'warning');
            }
        },
        error: function() {
            btn.prop('disabled', false).html('<span class="bx bx-save me-1"></span> ' + I18N_LOC.btnSave);
            toastLoc(I18N_LOC.errOccurred, 'error');
        }
    });
}

if (typeof Sepatateur_Milliers === 'undefined') {
    window.Sepatateur_Milliers = function(elementId) {
        var input = $(elementId);
        var value = input.val().replace(/\s/g, '').replace(/,/g, '');
        if (!isNaN(value) && value !== '') input.val(parseInt(value).toLocaleString('fr-FR'));
    };
}

// ─── DATATABLES ───────────────────────────────────────────────────────────────
if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.isDataTable('#example')) {
    $('#example').DataTable().destroy();
}
$('#example').DataTable();
</script>

@endsection
