<div class="modal fade" id="AjouerProprietaire" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <h5 class="modal-title text-white">Ajouter un propriétaire</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>

          <div class="modal-body">
              {{-- ⭐ data-action-url pour le JavaScript --}}
              <form class="row g-3" 
                    id="formulaireProprietaire" 
                    data-action-url="{{ route('create_propre') }}"
                    onsubmit="event.preventDefault(); save_proprietaire();">
                  @csrf

                  <div id="afficher" class="alert alert-primary d-none"></div>

                  @can('Is_admin')
                      @if(Auth::user()->type_compte != 'Particulier')
                          <div class="col-md-12">
                              <label class="form-label">Agence <span class="text-danger">*</span></label>
                              <select class="form-select" id="annexe" name="annexe" required>
                                  <option value="" disabled selected>Choisir une agence</option>
                                  @if(Session::get('anne_data') != " ")
                                      @foreach(Session::get('anne_data') as $terme)
                                          <option value="{{ $terme->idannexes }}">{{ $terme->designation }}</option>
                                      @endforeach
                                  @endif 
                              </select>
                              <span class="invalid-feedback annexe_err"></span>
                          </div>
                      @endif
                  @endcan

                  <div class="col-md-6">
                      <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom du propriétaire" required>
                      <span class="invalid-feedback nom_err"></span>
                  </div>

                  <div class="col-md-6">
                      <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom du propriétaire" required>
                      <span class="invalid-feedback prenom_err"></span>
                  </div>

                  <div class="col-md-6">
                      <label for="telephone" class="form-label">
                          Téléphone <span class="text-danger">*</span>
                      </label>
                      <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="01 00 00 00" required>
                      <span class="invalid-feedback telephone_err"></span>
                  </div>

                  <div class="col-md-6">
                      <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Adresse complète" required>
                      <span class="invalid-feedback adresse_err"></span>
                  </div>

                  <div class="modal-footer mt-3">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                      <button type="submit" class="btn btn-primary" id="valider">
                          <span class="fa fa-save" id="a"></span>
                          <span id="s">Enregistrer</span>
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


// Fonction wrapper pour display_sweet_alerte2 avec z-index forcé
function display_sweet_alert_over_modal(title, text, icon, buttonClass) {
    // Appeler votre fonction existante
    display_sweet_alerte2(title, text, icon, buttonClass);
    
    // Forcer le z-index après un court délai
    setTimeout(function() {
        const swalContainer = document.querySelector('.swal2-container');
        const swalPopup = document.querySelector('.swal2-popup');
        
        if (swalContainer) {
            swalContainer.style.zIndex = '10070';
        }
        if (swalPopup) {
            swalPopup.style.zIndex = '10071';
        }
    }, 10);
}



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
            $("#AjouerProprietaire button#valider").prop("disabled", true).html('<i class="fa fa-spinner fa-pulse"></i> En cours...');
        },
        success: function(response) {
            $("#AjouerProprietaire button[data-bs-dismiss='modal']").prop("disabled", false);
            $("#AjouerProprietaire button#valider").prop("disabled", false).html('<span class="fa fa-save"></span> Enregistrer');

            if (response.status) {
                addRowToTable(response.data);
                display_sweet_alert_over_modal("Succès !", response.message, "success", "btn btn-primary");
                $('#AjouerProprietaire').modal('hide');
                $("#formulaireProprietaire")[0].reset();
            } else {
                if (response.error) {
                    printErrorMsg(response.error);
                } else {
                    display_sweet_alert_over_modal("Erreur !", response.message, "warning", "btn btn-danger");
                }
            }
        },
        error: function(xhr) {
            $("#AjouerProprietaire button[data-bs-dismiss='modal']").prop("disabled", false);
            $("#AjouerProprietaire button#valider").prop("disabled", false).html('<span class="fa fa-save"></span> Enregistrer');
            
            if (xhr.status === 422) {
                printErrorMsg(xhr.responseJSON.error);
            } else {
                display_sweet_alert_over_modal("Erreur !", "Une erreur est survenue", "error", "btn btn-danger");
            }
        }
    });
}
</script>
