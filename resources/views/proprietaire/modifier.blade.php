@foreach ($allProprios as $items)
    @include('proprietaire.delete')

    <div class="modal fade" id="modifier{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
          <div class="modal-content">
            <div class="modal-header bg-primary">
              <h5 class="modal-title text-white">Modifier un propriétaire</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
      
            <div class="modal-body">
              <form class="row g-3" method="post" action="{{ route('update_proprio') }}" id="formulaireModifierProprietaire">
                @csrf
                <input type="hidden" name="id" value="{{ $items->id }}">
      
                <div id="afficher" class="alert alert-warning d-none"></div>
      
                @can('Is_admin')
                  @if(Auth::user()->type_compte != 'Particulier')
                    <div class="col-md-12">
                      <label class="form-label">Agence <span class="text-danger">*</span></label>
                      <select class="form-select" id="annexe" name="annexe" required>
                        <option value="" disabled>Choisir une agence</option>
                        @if(Session::get('anne_data') != ' ')
                          @foreach(Session::get('anne_data') as $terme)
                            <option value="{{ $terme->idannexes }}"
                              {{ $items->idannexe_ref == $terme->idannexes ? 'selected' : '' }}>
                              {{ $terme->designation }}
                            </option>
                          @endforeach
                        @endif
                      </select>
                      <span class="invalid-feedback annexe_err"></span>
                    </div>
                  @endif
                @endcan
      
                <div class="col-md-6">
                  <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="nom" name="nom" value="{{ $items->nom }}" required>
                  <span class="invalid-feedback nom_err"></span>
                </div>
      
                <div class="col-md-6">
                  <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="prenom" name="prenom" value="{{ $items->prenom }}" required>
                  <span class="invalid-feedback prenom_err"></span>
                </div>
      
                <style>
                  .iti {
                    width: 100%;
                  }
      
                  .iti input[type="tel"] {
                    width: 100%;
                  }
                </style>
                <div class="col-md-6">
                    <label for="inputPassword4" class="form-label">Téléphone<span
                            style="color: red;">*</span></label>
                            <input type="text" name="telephone" class="form-control"
                            id="telephone{{ $loop->iteration }}"
                            required value="{{ $items->telephone }}">
                    <span class="invalid-feedback telephone_err" role="alert">
                    </span>
                </div>

                <!--<div class="col-md-6">
                  <label for="telephone" class="form-label">
                    Téléphone (sans indicatif) <span class="text-danger">*</span>
                  </label>
                  <input type="tel" class="form-control" id="telephone" name="telephone" value="{{ $items->telephone }}" required>
                  <span class="invalid-feedback telephone_err"></span>
                </div> -->
      
                <div class="col-md-6">
                  <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                  <input type="text" class="form-control" id="adresse" name="adresse" value="{{ $items->adresse }}" required>
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


      <script>
        document.addEventListener("DOMContentLoaded", function () {
            const input = document.querySelector("#telephone{{ $loop->iteration }}");
            if (input) {
                window.intlTelInput(input, {
                preferredCountries: ["bj", "fr", "ci"],
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                });
            }
            });
    </script>
      
@endforeach
