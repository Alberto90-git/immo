<div class="modal fade" id="AjouerProprietaire" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title text-white">Ajouter un propriétaire</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fermer"></button>
        </div>
  
        <div class="modal-body">
          <form class="row g-3" method="post" action="javascript:save_proprietaire();" id="formulaireProprietaire">
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
  
            <style>
              .iti {
                width: 100%;
              }
            
              .iti input[type="tel"] {
                width: 100%;
              }
            </style>
            <div class="col-md-6">
              <label for="telephone" class="form-label">
                Téléphone (sans indicatif) <span class="text-danger">*</span>
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