{{-- Utiliser $items->id comme identifiant unique au lieu de $loop->iteration --}}
<div class="modal fade" id="modifier{{ $items->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
          <div class="modal-header bg-primary">
              <h5 class="modal-title text-white">Modifier un propriétaire</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
          </div>

          <div class="modal-body">
              <form class="row g-3" 
                    id="formulaireModifierProprietaire{{ $items->id }}" 
                    data-action-url="{{ route('update_proprio') }}"
                    onsubmit="event.preventDefault(); update_proprietaire(this, {{ $items->id }});">
                  @csrf
                  <input type="hidden" name="id" value="{{ $items->id }}">

                  <div id="afficher" class="alert alert-warning d-none"></div>

                  <div class="col-md-6">
                      <label for="nom{{ $items->id }}" class="form-label">Nom <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="nom{{ $items->id }}" name="nom" value="{{ $items->nom }}" required>
                      <span class="invalid-feedback nom_err"></span>
                  </div>

                  <div class="col-md-6">
                      <label for="prenom{{ $items->id }}" class="form-label">Prénom <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="prenom{{ $items->id }}" name="prenom" value="{{ $items->prenom }}" required>
                      <span class="invalid-feedback prenom_err"></span>
                  </div>

                  <div class="col-md-6">
                      <label for="telephone{{ $items->id }}" class="form-label">
                          Téléphone <span class="text-danger">*</span>
                      </label>
                      <input type="tel" 
                             name="telephone" 
                             class="form-control"
                             id="telephone{{ $items->id }}"
                             required 
                             value="{{ $items->telephone }}">
                      <span class="invalid-feedback telephone_err"></span>
                  </div>

                  <div class="col-md-6">
                      <label for="adresse{{ $items->id }}" class="form-label">Adresse <span class="text-danger">*</span></label>
                      <input type="text" class="form-control" id="adresse{{ $items->id }}" name="adresse" value="{{ $items->adresse }}" required>
                      <span class="invalid-feedback adresse_err"></span>
                  </div>

                  <div class="modal-footer mt-3">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                      <button type="submit" class="btn btn-primary" id="valider{{ $items->id }}">
                          <span class="fa fa-save"></span>
                          <span>Enregistrer</span>
                      </button>
                  </div>
              </form>
          </div>
      </div>
  </div>
</div>

<script>
  document.addEventListener("DOMContentLoaded", function () {
      const input = document.querySelector("#telephone{{ $items->id }}");
      if (input && !input.classList.contains('iti__tel-input')) {
          window.intlTelInput(input, {
              preferredCountries: ["bj", "fr", "ci"],
              utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
          });
      }
  });
</script>

<style>
  .iti {
      width: 100%;
  }

  .iti input[type="tel"] {
      width: 100%;
  }
</style>