<div class="modal fade" id="AjouerProprietaire" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <h5 class="modal-title text-white">{{ __('pages.owner_add_modal') }}</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="{{ __('common.btn_close') }}"></button>
          </div>

          <div class="modal-body">
              {{-- ⭐ data-action-url pour le JavaScript --}}
              <form class="row g-3"
                    id="formulaireProprietaire"
                    data-action-url="{{ route('create_propre') }}"
                    onsubmit="event.preventDefault(); save_proprietaire();">
                  @csrf

                  <div id="afficher" class="alert alert-primary d-none"></div>

                  <div class="col-md-6">
                      <label for="nom" class="form-label">{{ __('pages.owner_lastname') }} <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="nom" name="nom" placeholder="{{ __('pages.owner_lastname_ph') }}" required>
                      <span class="invalid-feedback nom_err"></span>
                  </div>

                  <div class="col-md-6">
                      <label for="prenom" class="form-label">{{ __('pages.owner_firstname') }} <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="prenom" name="prenom" placeholder="{{ __('pages.owner_firstname_ph') }}" required>
                      <span class="invalid-feedback prenom_err"></span>
                  </div>

                  <div class="col-md-6">
                      <label for="telephone" class="form-label">
                          {{ __('pages.owner_phone') }} <span class="text-danger">*</span>
                      </label>
                      <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="01 00 00 00" required>
                      <span class="invalid-feedback telephone_err"></span>
                  </div>

                  <div class="col-md-6">
                      <label for="adresse" class="form-label">{{ __('pages.owner_address') }} <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="adresse" name="adresse" placeholder="{{ __('pages.owner_address_ph') }}" required>
                      <span class="invalid-feedback adresse_err"></span>
                  </div>

                  <div class="col-md-6">
                      <label for="email_proprio" class="form-label">{{ __('pages.owner_email_opt') }}</label>
                      <input type="email" class="form-control" id="email_proprio" name="email" placeholder="{{ __('pages.owner_email_ph') }}">
                  </div>

                  <div class="modal-footer mt-3">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">{{ __('common.btn_close') }}</button>
                      <button type="submit" class="btn btn-primary" id="valider">
                          <span class="fa fa-save" id="a"></span>
                          <span id="s">{{ __('common.btn_save') }}</span>
                      </button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>

<style>
  .iti {
      width: 100%;
  }

  .iti input[type="tel"] {
      width: 100%;
  }
</style>

<script>
var PROPRIO_IN_PROGRESS = '{{ __('pages.owner_in_progress') }}';
var PROPRIO_SAVE_LABEL  = '<span class="fa fa-save"></span> {{ __('common.btn_save') }}';
var PROPRIO_SUCCESS_LBL = '{{ __('common.swal_success') }}';
var PROPRIO_ERROR_LBL   = '{{ __('common.swal_error') }}';
var PROPRIO_ERR_MSG     = '{{ __('common.swal_generic_error') }}';

function save_proprietaire() {
    const formattedPhone = validateAndFormatPhone('telephone');
    if (!formattedPhone) return;

    var data = new FormData();
    var form_data = $('#formulaireProprietaire').serializeArray();

    $.each(form_data, function(key, input) {
        if (input.name === "telephone") {
            data.append("telephone", formattedPhone);
        } else {
            data.append(input.name, input.value);
        }
    });

    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    $.ajax({
        url: $('#formulaireProprietaire').data('action-url'),
        method: "POST",
        processData: false,
        contentType: false,
        data: data,
        beforeSend: function() {
            $("#AjouerProprietaire button[data-bs-dismiss='modal']").prop("disabled", true);
            $("#AjouerProprietaire button#valider").prop("disabled", true).html('<i class="fa fa-spinner fa-pulse"></i> ' + PROPRIO_IN_PROGRESS);
        },
        success: function(response) {
            $("#AjouerProprietaire button[data-bs-dismiss='modal']").prop("disabled", false);
            $("#AjouerProprietaire button#valider").prop("disabled", false).html(PROPRIO_SAVE_LABEL);

            if (response.status) {
                addRowToTable(response.data);
                $('#AjouerProprietaire').modal('hide');
                $("#formulaireProprietaire")[0].reset();
                showProprietaireMessage(PROPRIO_SUCCESS_LBL + " !", response.message, "success", "btn btn-primary");
            } else {
                if (response.error) {
                    printErrorMsg(response.error);
                } else {
                    showProprietaireMessage(PROPRIO_ERROR_LBL + " !", response.message, "warning", "btn btn-danger");
                }
            }
        },
        error: function(xhr) {
            $("#AjouerProprietaire button[data-bs-dismiss='modal']").prop("disabled", false);
            $("#AjouerProprietaire button#valider").prop("disabled", false).html(PROPRIO_SAVE_LABEL);

            if (xhr.status === 422) {
                printErrorMsg(xhr.responseJSON.error);
            } else {
                showProprietaireMessage(PROPRIO_ERROR_LBL + " !", PROPRIO_ERR_MSG, "error", "btn btn-danger");
            }
        }
    });
}
</script>
