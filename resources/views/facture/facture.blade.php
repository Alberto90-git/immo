@extends('layouts.template')

@section('title')
<title>{{ __('pages.invoice_title') }}</title>
@endsection

@section('content')

@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('common.home_breadcrumb') }}</span> {{ __('pages.invoice_breadcrumb') }}</h4>

  @can('ajoute-locataire')
  <div class="col-md-6 mb-3">
    <button type="button" class="btn rounded-pill btn-icon btn-outline-primary"
      data-bs-toggle="modal" data-bs-target="#AjouterLoyer">
      <span class="bx bx-plus"></span>
    </button>
  </div>
  @endcan

  {{-- ── Modal Ajouter ── --}}
  <div class="modal fade" id="AjouterLoyer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white">{{ __('pages.invoice_pay_modal') }}</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3" id="formulaire" onsubmit="save_paiement(event)">
            @csrf

            <div class="col-12">
              <label class="form-label">{{ __('pages.invoice_choose_house') }} <span class="text-danger">*</span></label>
              <select required class="form-select" name="nom_maison" id="nom_maison">
                <option selected disabled value="">{{ __('pages.invoice_choose_house') }}</option>
                @if(isset($allMaison))
                  @foreach($allMaison as $terme)
                    <option value="{{ $terme->id }}">{{ $terme->nom_maison }}</option>
                  @endforeach
                @endif
              </select>
              <span class="invalid-feedback nom_maison_err" role="alert"></span>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">{{ __('pages.invoice_choose_room') }} <span class="text-danger">*</span></label>
              <select class="form-select" name="numero_chambre" id="numero_chambre">
                <option selected disabled value="">{{ __('pages.invoice_choose_room') }}</option>
              </select>
              <span class="invalid-feedback numero_chambre_err" role="alert"></span>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">{{ __('pages.invoice_to_pay') }} <span class="text-danger">*</span></label>
              <input type="number" class="form-control" id="sonPrix" name="sonPrix" readonly>
            </div>

            <div class="col-12 col-md-4">
              <label class="form-label">{{ __('pages.invoice_date') }} <span class="text-danger">*</span></label>
              <input type="datetime-local" name="date_paiement" class="form-control"
                id="date_paiement" min="<?= date('1970-m-d\T00:00:00'); ?>" required>
            </div>

            <div class="col-12">
              <label class="form-label">{{ __('pages.invoice_room_type') }}</label>
              <input type="text" name="type_chambre" class="form-control" id="type_chambre_getData" readonly>
            </div>

            <div class="col-12 col-sm-6">
              <label class="form-label">{{ __('pages.invoice_received') }} <span class="text-danger">*</span></label>
              <input type="text" name="montant" class="form-control" id="montant" required>
              <span class="invalid-feedback montant_err" role="alert"></span>
            </div>

            <div class="col-12 col-sm-6">
              <label class="form-label">{{ __('pages.invoice_pay_type') }} <span class="text-danger">*</span></label>
              <select class="form-select" name="type_paiement" id="type_paiement" required>
                <option selected disabled value="">{{ __('pages.invoice_pay_type_opt') }}</option>
                <option value="direct">{{ __('pages.pay_direct') }}</option>
                <option value="avance">{{ __('pages.pay_advance') }}</option>
              </select>
            </div>

            <div class="col-12 col-sm-6">
              <label class="form-label">{{ __('pages.invoice_tenant') }}</label>
              <input type="text" class="form-control" id="myLocataire" name="locataire" readonly disabled>
            </div>

            <div class="col-12 col-sm-6">
              <label class="form-label">{{ __('pages.invoice_month') }} <span class="text-danger">*</span></label>
              <select class="form-select" name="mois" id="mois" required>
                <option selected disabled value="">{{ __('pages.invoice_month') }}</option>
                <option value="Janvier">{{ __('pages.month_jan') }}</option>
                <option value="Février">{{ __('pages.month_feb') }}</option>
                <option value="Mars">{{ __('pages.month_mar') }}</option>
                <option value="Avril">{{ __('pages.month_apr') }}</option>
                <option value="Mai">{{ __('pages.month_may') }}</option>
                <option value="Juin">{{ __('pages.month_jun') }}</option>
                <option value="Juillet">{{ __('pages.month_jul') }}</option>
                <option value="Août">{{ __('pages.month_aug') }}</option>
                <option value="Septembre">{{ __('pages.month_sep') }}</option>
                <option value="Octobre">{{ __('pages.month_oct') }}</option>
                <option value="Novembre">{{ __('pages.month_nov') }}</option>
                <option value="Décembre">{{ __('pages.month_dec') }}</option>
              </select>
            </div>

            <div class="col-12 col-sm-6">
              <label class="form-label">{{ __('pages.invoice_pay_mode') }}</label>
              <select class="form-select" name="mode_paiement" id="mode_paiement">
                <option selected disabled value="">{{ __('pages.invoice_pay_mode_opt') }}</option>
                <option value="Mobile money">{{ __('pages.pay_mobile_money') }}</option>
                <option value="Virement bancaire">{{ __('pages.pay_bank_transfer') }}</option>
                <option value="Chèque">{{ __('pages.pay_cheque') }}</option>
                <option value="Espèces">{{ __('pages.pay_cash') }}</option>
              </select>
            </div>

            <div class="modal-footer mt-2">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
              <button type="submit" class="btn btn-primary" id="btnSaveLoyer" style="min-width:130px;">
                <span class="bx bx-save me-1"></span> {{ __('common.btn_save') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- ── Modal Détails partagé ── --}}
  <div class="modal fade" id="sharedDetailsLoyer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white"><i class="bx bx-receipt me-2"></i><span id="dl-locataire"></span></h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-0">
          <div class="px-4 pt-3 pb-2">
            <div class="row g-3">
              <div class="col-12 col-sm-6">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-label-primary p-2"><i class="bx bx-home"></i></span>
                  <div>
                    <div class="text-muted small">{{ __('pages.invoice_detail_house') }}</div>
                    <div class="fw-semibold"><span id="dl-maison"></span> — <span id="dl-chambre"></span></div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-label-success p-2"><i class="bx bx-calendar"></i></span>
                  <div>
                    <div class="text-muted small">{{ __('pages.invoice_detail_month') }}</div>
                    <div class="fw-semibold" id="dl-mois"></div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-label-warning p-2"><i class="bx bx-money"></i></span>
                  <div>
                    <div class="text-muted small">{{ __('pages.invoice_detail_amount') }}</div>
                    <div class="fw-semibold" id="dl-montant"></div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-label-info p-2"><i class="bx bx-credit-card"></i></span>
                  <div>
                    <div class="text-muted small">{{ __('pages.invoice_detail_mode') }}</div>
                    <div class="fw-semibold" id="dl-mode-paiement"></div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-label-secondary p-2"><i class="bx bx-time"></i></span>
                  <div>
                    <div class="text-muted small">{{ __('pages.invoice_detail_date') }}</div>
                    <div class="fw-semibold" id="dl-date-paiement"></div>
                  </div>
                </div>
              </div>
              <div class="col-12 col-sm-6">
                <div class="d-flex align-items-center gap-2">
                  <span class="badge bg-label-danger p-2"><i class="bx bx-transfer"></i></span>
                  <div>
                    <div class="text-muted small">{{ __('pages.invoice_detail_type') }}</div>
                    <div class="fw-semibold" id="dl-type-paiement"></div>
                  </div>
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

  {{-- ── Modal Suppression partagé ── --}}
  <div class="modal fade" id="sharedSupprimerLoyer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-danger">
          <h5 class="modal-title text-white">{{ __('common.swal_confirm') }}</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <i class="bx bx-error-circle text-danger" style="font-size:4rem;"></i>
          <p class="mt-3">{{ __('pages.invoice_delete_msg') }}</p>
        </div>
        <div class="modal-footer justify-content-center">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('pages.invoice_delete_no') }}</button>
          <button type="button" class="btn btn-danger" id="btnConfirmSuppLoyer">{{ __('common.btn_yes_delete') }}</button>
        </div>
      </div>
    </div>
  </div>

  {{-- ── Modal Modification partagé ── --}}
  <div class="modal fade" id="sharedModifierLoyer" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white">{{ __('pages.invoice_edit_modal') }}</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3" id="formModifLoyerShared" onsubmit="updateFacture(event)">
            @csrf
            <input type="hidden" name="facture_id2"   id="ml-facture-id">
            <input type="hidden" name="maison_id2"    id="ml-maison-id">
            <input type="hidden" name="chambre_id2"   id="ml-chambre-id">
            <input type="hidden" name="locataire_id2" id="ml-locataire-id">

            <div class="col-12">
              <label class="form-label">{{ __('pages.invoice_th_house') }}</label>
              <input type="text" class="form-control" id="ml-maison" readonly disabled>
            </div>
            <div class="col-12 col-sm-5">
              <label class="form-label">{{ __('pages.invoice_th_room') }}</label>
              <input type="text" class="form-control" id="ml-chambre" readonly disabled>
            </div>
            <div class="col-12 col-sm-7">
              <label class="form-label">{{ __('pages.invoice_room_type') }}</label>
              <input type="text" class="form-control" id="ml-type-chambre" readonly disabled>
            </div>
            <div class="col-12 col-sm-6">
              <label class="form-label">{{ __('pages.invoice_to_pay_label') }}</label>
              <input type="text" class="form-control" id="ml-montant-payer" name="sonPrix" readonly disabled>
            </div>
            <div class="col-12 col-sm-6">
              <label class="form-label">{{ __('pages.invoice_received') }} <span class="text-danger">*</span></label>
              <input type="text" name="montant" id="ml-montant" class="form-control"
                onkeypress="return /[0-9]/i.test(event.key)" required>
            </div>
            <div class="col-12 col-sm-6">
              <label class="form-label">{{ __('pages.invoice_pay_type') }} <span class="text-danger">*</span></label>
              <select class="form-select" name="type_paiement" id="ml-type-paiement" required>
                <option value="direct">{{ __('pages.pay_direct') }}</option>
                <option value="avance">{{ __('pages.pay_advance') }}</option>
              </select>
            </div>
            <div class="col-12 col-sm-6">
              <label class="form-label">{{ __('pages.invoice_tenant') }}</label>
              <input type="text" class="form-control" id="ml-locataire" name="locataire" readonly disabled>
            </div>
            <div class="col-12 col-sm-6">
              <label class="form-label">{{ __('pages.invoice_month') }} <span class="text-danger">*</span></label>
              <select class="form-select" name="mois" id="ml-mois" required>
                <option value="Janvier">{{ __('pages.month_jan') }}</option>
                <option value="Février">{{ __('pages.month_feb') }}</option>
                <option value="Mars">{{ __('pages.month_mar') }}</option>
                <option value="Avril">{{ __('pages.month_apr') }}</option>
                <option value="Mai">{{ __('pages.month_may') }}</option>
                <option value="Juin">{{ __('pages.month_jun') }}</option>
                <option value="Juillet">{{ __('pages.month_jul') }}</option>
                <option value="Août">{{ __('pages.month_aug') }}</option>
                <option value="Septembre">{{ __('pages.month_sep') }}</option>
                <option value="Octobre">{{ __('pages.month_oct') }}</option>
                <option value="Novembre">{{ __('pages.month_nov') }}</option>
                <option value="Décembre">{{ __('pages.month_dec') }}</option>
              </select>
            </div>
            <div class="col-12 col-sm-6">
              <label class="form-label">{{ __('pages.invoice_pay_date') }} <span class="text-danger">*</span></label>
              <input type="datetime-local" name="date_paiement" id="ml-date-paiement" class="form-control"
                required min="<?= date('1970-m-d\T00:00:00'); ?>">
            </div>
            <div class="col-12 col-sm-6">
              <label class="form-label">{{ __('pages.invoice_pay_mode') }}</label>
              <select class="form-select" name="mode_paiement" id="ml-mode-paiement">
                <option value="">—</option>
                <option value="Mobile money">{{ __('pages.pay_mobile_money') }}</option>
                <option value="Virement bancaire">{{ __('pages.pay_bank_transfer') }}</option>
                <option value="Chèque">{{ __('pages.pay_cheque') }}</option>
                <option value="Espèces">{{ __('pages.pay_cash') }}</option>
              </select>
            </div>
            <div class="modal-footer mt-2">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
              <button type="submit" class="btn btn-primary" id="btnSaveModifLoyer" style="min-width:130px;">
                <span class="bx bx-save me-1"></span> {{ __('common.btn_save') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- ── Tableau ── --}}
  <div class="card shadow-sm border-0">
    <div class="card-header bg-primary text-center">
      <h5 class="mb-0 text-white"><i class="bx bx-receipt me-1"></i> {{ __('pages.invoice_list') }} — {{ nom_mois(date('m') - 1) }}</h5>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table id="example" class="table table-striped table-hover align-middle mb-0" style="width:100%">
          <thead class="table-light">
            <tr>
              <th>{{ __('pages.invoice_th_house') }}</th>
              <th>{{ __('pages.invoice_th_room') }}</th>
              <th>{{ __('pages.invoice_tenant') }}</th>
              <th class="text-center">{{ __('common.th_actions') }}</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @can('Consulter-paiement')
              @if(isset($allFacture))
                @foreach($allFacture as $item)
                  <tr id="row-{{ $item->id }}"
                    data-id="{{ $item->id }}"
                    data-nom-maison="{{ $item->nom_maison }}"
                    data-numero-chambre="{{ $item->numero_chambre }}"
                    data-type-chambre="{{ $item->type_chambre }}"
                    data-locataire="{{ $item->nom }} {{ $item->prenom }}"
                    data-montant="{{ $item->montant }}"
                    data-date-paiement="{{ $item->date_paiement }}"
                    data-mode-paiement="{{ $item->mode_paiement }}"
                    data-mois="{{ $item->mois }}"
                    data-type-paiement="{{ $item->type_paiement }}"
                    data-maison-id="{{ $item->maison_id }}"
                    data-chambre-id="{{ $item->chambre_id }}"
                    data-locataire-id="{{ $item->locataire_id }}"
                  >
                    <td>{{ $item->nom_maison }}</td>
                    <td>{{ $item->numero_chambre }}</td>
                    <td>{{ $item->nom }} {{ $item->prenom }}</td>
                    <td class="text-center">
                      @can('modify-paiement')
                        <a class="btn btn-sm rounded-pill btn-outline-primary btn-modif-loyer me-1" title="{{ __('common.title_edit') }}" href="#">
                          <i class="bx bx-edit-alt"></i>
                        </a>
                      @endcan
                      @can('delete-paiement')
                        <a class="btn btn-sm rounded-pill btn-outline-danger btn-supp-loyer me-1" title="{{ __('common.title_delete') }}" href="#">
                          <i class="bx bx-trash"></i>
                        </a>
                      @endcan
                      @can('download-recu-location')
                        <a class="btn btn-sm rounded-pill btn-outline-success me-1" title="{{ __('common.title_download') }}"
                          href="{{ route('telecharge2', ['id' => encrypt_id($item->id)]) }}">
                          <i class="bx bx-download"></i>
                        </a>
                        <a class="btn btn-sm rounded-pill btn-outline-info me-1" title="Attestation de paiement"
                          href="{{ route('attestation_paiement', ['id' => encrypt_id($item->id)]) }}">
                          <i class="bx bx-badge-check"></i>
                        </a>
                      @endcan
                      <a class="btn btn-sm rounded-pill btn-outline-secondary btn-details-loyer" title="{{ __('common.title_details') }}" href="#">
                        <i class="bx bx-zoom-in"></i>
                      </a>
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

<script>
$.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

var INVOICE_I18N = {
    payAdvance:    '{{ __('pages.pay_advance') }}',
    payDirect:     '{{ __('pages.pay_direct') }}',
    inProgress:    '{{ __('pages.owner_in_progress') }}',
    btnSave:       '<span class="bx bx-save me-1"></span> {{ __('common.btn_save') }}',
    genericError:  '{{ __('common.swal_generic_error') }}',
    titleEdit:     '{{ __('common.title_edit') }}',
    titleDelete:   '{{ __('common.title_delete') }}',
    titleDownload: '{{ __('common.title_download') }}',
    titleDetails:  '{{ __('common.title_details') }}',
    chooseRoom:    '{{ __('pages.invoice_choose_room') }}',
};

var canModifyLoyer    = @json(Auth::user()->can('modify-paiement'));
var canDeleteLoyer    = @json(Auth::user()->can('delete-paiement'));
var canDownloadLoyer       = @json(Auth::user()->can('download-recu-location'));
var telecharge2Base        = "{{ route('telecharge2', ['id' => 'ENCID']) }}".replace('ENCID', '');
var attestationPaiementBase = "{{ route('attestation_paiement', ['id' => 'ENCID']) }}".replace('ENCID', '');

function toastLoyer(message, icon) {
    Swal.fire({ toast: true, position: 'top-end', icon: icon, title: message,
        showConfirmButton: false, timer: 3500, timerProgressBar: true });
}

function closeModalLoyer(selector) {
    $(selector).modal('hide');
    setTimeout(function() {
        $('.modal-backdrop').remove();
        $('body').removeClass('modal-open').css({ overflow: '', paddingRight: '' });
    }, 350);
}

function printErrorMsg(msg) {
    $.each(msg, function(key, value) { $('.' + key + '_err').text(value).show(); });
}

// ── Chargement chambres / type / prix ────────────────────────────────────────
$('#nom_maison').on('change', function() {
    var idMaison = $(this).val();
    if (!idMaison) return;
    $.get('{{ url("gerer-facture/numero_chambre") }}', { idMaison: idMaison }, function(data) {
        $('#numero_chambre').html(data.list_chambre);
        $('#type_chambre_getData').val('');
        $('#sonPrix').val('');
        $('#myLocataire').val('');
    });
});

$('#numero_chambre').on('change', function() {
    var id = $(this).val();
    if (!id) return;
    $.get('{{ url("gerer-facture/type_chambre") }}', { numero_chambre_got: id }, function(data) {
        $('#type_chambre_getData').val(data.type_chambres_get);
        $('#sonPrix').val(data.sonPrix);
        $('#myLocataire').val(data.sonLocataire);
    });
});

$(document).on('keyup', '#montant', function() { Sepatateur_Milliers('#montant'); });

// ── Détails ──────────────────────────────────────────────────────────────────
$(document).on('click', '.btn-details-loyer', function() {
    var r = $(this).closest('tr');
    $('#dl-locataire').text(r.data('locataire'));
    $('#dl-maison').text(r.data('nom-maison'));
    $('#dl-chambre').text(r.data('numero-chambre'));
    $('#dl-mois').text(r.data('mois') || '—');
    $('#dl-montant').text(parseInt(r.data('montant') || 0).toLocaleString('fr-FR') + ' {{ get_symbole_devise() }}');
    $('#dl-mode-paiement').text(r.data('mode-paiement') || '—');
    var dp = r.data('date-paiement') || '';
    $('#dl-date-paiement').text(dp ? dp.toString().replace('T', ' ').substring(0, 16) : '—');
    var tp = r.data('type-paiement');
    $('#dl-type-paiement').text(tp === 'avance' ? INVOICE_I18N.payAdvance : INVOICE_I18N.payDirect);
    $('#sharedDetailsLoyer').modal('show');
});

// ── Suppression ───────────────────────────────────────────────────────────────
$(document).on('click', '.btn-supp-loyer', function() {
    $('#btnConfirmSuppLoyer').data('id', $(this).closest('tr').data('id'));
    $('#sharedSupprimerLoyer').modal('show');
});

$('#btnConfirmSuppLoyer').on('click', function() {
    var id = $(this).data('id');
    $.ajax({
        url: "{{ route('destroy_facture') }}",
        method: "POST",
        data: { _token: $('meta[name="csrf-token"]').attr('content'), facture_id_destroy: id },
        success: function(resp) {
            if (resp.status) {
                $('#row-' + id).fadeOut(400, function() { $(this).remove(); });
                closeModalLoyer('#sharedSupprimerLoyer');
                toastLoyer(resp.message, 'success');
            } else {
                toastLoyer(resp.message, 'warning');
            }
        },
        error: function() { toastLoyer(INVOICE_I18N.genericError, 'error'); }
    });
});

// ── Modification ──────────────────────────────────────────────────────────────
$(document).on('click', '.btn-modif-loyer', function() {
    var r = $(this).closest('tr');
    $('#ml-facture-id').val(r.data('id'));
    $('#ml-maison-id').val(r.data('maison-id'));
    $('#ml-chambre-id').val(r.data('chambre-id'));
    $('#ml-locataire-id').val(r.data('locataire-id'));
    $('#ml-maison').val(r.data('nom-maison'));
    $('#ml-chambre').val(r.data('numero-chambre'));
    $('#ml-type-chambre').val(r.data('type-chambre'));
    $('#ml-montant-payer').val(parseInt(r.data('montant') || 0).toLocaleString('fr-FR'));
    $('#ml-montant').val(r.data('montant'));
    $('#ml-type-paiement').val(r.data('type-paiement'));
    $('#ml-locataire').val(r.data('locataire'));
    $('#ml-mois').val(r.data('mois'));
    $('#ml-date-paiement').val(r.data('date-paiement') || '');
    $('#ml-mode-paiement').val(r.data('mode-paiement') || '');
    $('#sharedModifierLoyer').modal('show');
});

function updateFacture(e) {
    e.preventDefault();
    var btn = $('#btnSaveModifLoyer');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> ' + INVOICE_I18N.inProgress);
    var id = $('#ml-facture-id').val();
    var data = new FormData();
    $('#formModifLoyerShared').serializeArray().forEach(function(f) { data.append(f.name, f.value); });
    $.ajax({
        url: "{{ route('update_facture') }}",
        method: "POST",
        processData: false,
        contentType: false,
        data: data,
        success: function(resp) {
            btn.prop('disabled', false).html(INVOICE_I18N.btnSave);
            if (resp.status) {
                var row = $('#row-' + id);
                var montant   = $('#ml-montant').val();
                var mois      = $('#ml-mois').val();
                var modePaie  = $('#ml-mode-paiement').val();
                var typePaie  = $('#ml-type-paiement').val();
                var datePaie  = $('#ml-date-paiement').val();
                row.attr('data-montant', montant.replace(/\s/g, ''))
                   .attr('data-mois', mois)
                   .attr('data-mode-paiement', modePaie)
                   .attr('data-type-paiement', typePaie)
                   .attr('data-date-paiement', datePaie);
                closeModalLoyer('#sharedModifierLoyer');
                toastLoyer(resp.message, 'success');
            } else {
                toastLoyer(resp.message, 'warning');
            }
        },
        error: function() {
            btn.prop('disabled', false).html(INVOICE_I18N.btnSave);
            toastLoyer(INVOICE_I18N.genericError, 'error');
        }
    });
}

// ── Ajout loyer ───────────────────────────────────────────────────────────────
function save_paiement(e) {
    e.preventDefault();
    var btn = $('#btnSaveLoyer');
    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> ' + INVOICE_I18N.inProgress);
    var data = new FormData();
    $('#formulaire').serializeArray().forEach(function(f) { data.append(f.name, f.value); });
    $.ajax({
        url: "{{ route('store_facture') }}",
        method: "POST",
        processData: false,
        contentType: false,
        data: data,
        success: function(resp) {
            btn.prop('disabled', false).html(INVOICE_I18N.btnSave);
            if (resp.error) { printErrorMsg(resp.error); return; }
            if (resp.status) {
                var f = resp.facture;
                var btns = '';
                if (canModifyLoyer)   btns += '<a class="btn btn-sm rounded-pill btn-outline-primary btn-modif-loyer me-1" title="' + INVOICE_I18N.titleEdit + '" href="#"><i class="bx bx-edit-alt"></i></a>';
                if (canDeleteLoyer)   btns += '<a class="btn btn-sm rounded-pill btn-outline-danger btn-supp-loyer me-1" title="' + INVOICE_I18N.titleDelete + '" href="#"><i class="bx bx-trash"></i></a>';
                if (canDownloadLoyer) btns += '<a class="btn btn-sm rounded-pill btn-outline-success me-1" title="' + INVOICE_I18N.titleDownload + '" href="' + telecharge2Base + f.encoded_id + '"><i class="bx bx-download"></i></a>';
                if (canDownloadLoyer) btns += '<a class="btn btn-sm rounded-pill btn-outline-info me-1" title="Attestation de paiement" href="' + attestationPaiementBase + f.encoded_id + '"><i class="bx bx-badge-check"></i></a>';
                btns += '<a class="btn btn-sm rounded-pill btn-outline-secondary btn-details-loyer" title="' + INVOICE_I18N.titleDetails + '" href="#"><i class="bx bx-zoom-in"></i></a>';

                var newRow = $('<tr id="row-' + f.id + '"></tr>')
                    .attr('data-id', f.id)
                    .attr('data-nom-maison', f.nom_maison)
                    .attr('data-numero-chambre', f.numero_chambre)
                    .attr('data-type-chambre', f.type_chambre)
                    .attr('data-locataire', f.nom + ' ' + f.prenom)
                    .attr('data-montant', f.montant)
                    .attr('data-date-paiement', f.date_paiement || '')
                    .attr('data-mode-paiement', f.mode_paiement || '')
                    .attr('data-mois', f.mois || '')
                    .attr('data-type-paiement', f.type_paiement || '')
                    .attr('data-maison-id', f.maison_id)
                    .attr('data-chambre-id', f.chambre_id)
                    .attr('data-locataire-id', f.locataire_id)
                    .append('<td>' + f.nom_maison + '</td>')
                    .append('<td>' + f.numero_chambre + '</td>')
                    .append('<td>' + f.nom + ' ' + f.prenom + '</td>')
                    .append('<td class="text-center">' + btns + '</td>');

                $('#example tbody').prepend(newRow);
                $('#formulaire')[0].reset();
                $('#numero_chambre').html('<option selected disabled value="">' + INVOICE_I18N.chooseRoom + '</option>');
                $('#type_chambre_getData').val('');
                $('#sonPrix').val('');
                $('#myLocataire').val('');
                toastLoyer(resp.message, 'success');
            } else {
                toastLoyer(resp.message, 'warning');
            }
        },
        error: function() {
            btn.prop('disabled', false).html(INVOICE_I18N.btnSave);
            toastLoyer(INVOICE_I18N.genericError, 'error');
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
</script>

@endsection
