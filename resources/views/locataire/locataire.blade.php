@extends('layouts.template')

@section('content')

@section('title')
<title>Gestion locataire</title>
@endsection

<style>
    /* Forcer SweetAlert2 à apparaître au-dessus de tous les modals */
    .swal2-container {
        z-index: 10070 !important;
    }
    
    .swal2-popup {
        z-index: 10071 !important;
    }
</style>

@include('notification.display_message')


<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion locataire</h4>

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
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCenterTitle">Ajouter un locataire</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3" method="post" action="javascript:save_locataire();" id="formulaire">
            @csrf

            <div class="col-6">
              <label for="inputNanme4" class="form-label">Choisir une maison<span style="color: red;">*</span></label>
              <select required class="form-select @error('nom_maison') is-invalid @enderror" name="nom_maison"
                id="nom_maison" aria-label="Default select example">
                <option selected disabled value="">Choisir une maison</option>
                @if(isset($allMaison))
                @foreach($allMaison as $terme)
                <option value="{{$terme->id}}">{{$terme->nom_maison}}</option>
                @endforeach
                @endif
              </select>
              <span class="invalid-feedback nom_maison_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label for="inputEmail5" class="form-label">Nom locataire<span style="color: red;">*</span></label>
              <input type="text" name="nom_locataire" id="nom_locataire"
                class="form-control @error('nom_locataire') is-invalid @enderror" required>
              <span class="invalid-feedback nom_locataire_err" role="alert"></span>
            </div>

            <div class="col-6">
              <label for="inputNanme4" class="form-label">Choisir une chambre<span style="color: red;">*</span></label>
              <select class="form-select @error('numero_chambre') is-invalid @enderror" name="numero_chambre"
                id="numero_chambre" aria-label="Default select example">
                <option selected disabled value="">Choisir une chambre</option>
              </select>
              <span class="invalid-feedback numero_chambre_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label for="inputPassword5" class="form-label">Prénom locataire<span style="color: red;">*</span></label>
              <input type="text" name="prenom_locataire" id="prenom_locataire"
                class="form-control @error('prenom_locataire') is-invalid @enderror" required>
              <span class="invalid-feedback prenom_locataire_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label for="inputPassword5" class="form-label">Téléphone<span style="color: red;">*</span></label>
              <input type="text" name="telephone" id="telephone"
                class="form-control @error('telephone') is-invalid @enderror" required min="1">
              <span class="invalid-feedback telephone_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label for="inputEmail5" class="form-label">Profession<span style="color: red;">*</span></label>
              <input type="text" name="profession" id="profession"
                class="form-control @error('profession') is-invalid @enderror" required>
              <span class="invalid-feedback profession_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label class="form-label">Email <span class="text-muted fw-normal">(optionnel)</span></label>
              <input type="email" name="email" id="email_locataire"
                class="form-control @error('email') is-invalid @enderror" placeholder="exemple@mail.com">
              <span class="invalid-feedback email_err" role="alert"></span>
            </div>

            <div class="col-6">
              <label for="inputNanme4" class="form-label">Type chambre<span style="color: red;">*</span></label>
              <input type="text" name="type_chambre" class="form-control @error('type_chambre') is-invalid @enderror"
                id="type_chambre_getData" readonly disabled>
              <span class="invalid-feedback type_chambre_err" role="alert"></span>
            </div>

            <div class="col-3">
              <label for="inputNanme4" class="form-label">Caution eau<span style="color: red;">*</span></label>
              <input type="text" name="caution_eau" class="form-control @error('caution_eau') is-invalid @enderror"
                id="caution_eau" required>
              <span class="invalid-feedback caution_eau_err" role="alert"></span>
            </div>

            <div class="col-md-3">
              <label for="inputEmail5" class="form-label"> Électricité<span style="color: red;">*</span></label>
              <input type="text" name="caution_courant" id="caution_courant"
                class="form-control @error('caution_courant') is-invalid @enderror" required>
              <span class="invalid-feedback caution_courant_err" role="alert"></span>
            </div>

            <div class="col-6">
              <label for="inputNanme4" class="form-label">Prix / mois<span style="color: red;">*</span></label>
              <input type="text" name="prix_mois" class="form-control @error('prix_mois') is-invalid @enderror"
                id="prix_mois" readonly>
              <span class="invalid-feedback prix_mois_err" role="alert"></span>
            </div>


            <div class="col-3">
              <label for="inputNanme4" class="form-label">Caution pour<span style="color: red;">*</span></label>
              <select class="form-select @error('nombre_caution') is-invalid @enderror" name="nombre_caution"
                id="nombre_caution" aria-label="Default select example" required>
                <option selected disabled value="">Caution pour</option>
                <option value="2">2 Mois</option>
                <option value="3">3 Mois</option>
                <option value="4">4 Mois</option>
                <option value="5">5 Mois</option>
                <option value="6">6 Mois</option>
                <option value="12">12 Mois</option>
              </select>
              <span class="invalid-feedback nombre_avance_err" role="alert"></span>
            </div>

            <div class="col-3">
              <label for="inputNanme4" class="form-label">Date d'entrée<span style="color: red;">*</span></label>
              <input type="date" name="date_entre" class="form-control @error('date_entre') is-invalid @enderror"
                min="<?= date('1970-m-d'); ?>" id="date_entre" required>
              <span class="invalid-feedback date_entre_err" role="alert"></span>
            </div>

            <div class="col-6">
              <label for="inputNanme4" class="form-label">Avance pour</label>
              <select class="form-select @error('nombre_avance') is-invalid @enderror" name="nombre_avance"
                id="nombre_avance" aria-label="Default select example">
                <option selected disabled value="">Avance pour</option>
                <option value="2">2 Mois</option>
                <option value="3">3 Mois</option>
                <option value="4">4 Mois</option>
                <option value="5">5 Mois</option>
                <option value="6">6 Mois</option>
                <option value="12">12 Mois</option>
              </select>
              <span class="invalid-feedback nombre_avance_err" role="alert"></span>
            </div> 


            <div class="col-6">
              <label for="inputNanme4" class="form-label">Mode de paiement</label>
              <select class="form-select @error('mode_paiement') is-invalid @enderror" name="mode_paiement"
                id="mode_paiement" aria-label="Default select example">
                <option selected disabled value="">Mode de paiement</option>
                <option value="Mobile money">Mobile money</option>
                <option value="Virement bancaire">Virement bancaire</option>
                <option value="Chèque">Chèque</option>
                <option value="Espèces">Espèces</option>
              </select>
              <span class="invalid-feedback mode_paiement_err" role="alert"></span>
            </div> 

            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="close">
                Fermer
              </button>
              <button class="btn btn-primary" id="valider">
                <span class="fa fa-save"></span>
                <span>Enregistrer</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Hoverable Table rows -->
  <div class="card">
    <h5 class="card-header text-center">Liste des locataires</h5>
    <div class="table-responsive text-nowrap">
      <table id="example" class="table table-hover border-primary" style="width:100%">
        <thead>
          <tr>
            <th scope="col">Agence</th>
            <th scope="col">Maison</th>
            <th scope="col">N° chambre</th>
            <th scope="col">Locataire</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @can('Consulter-locataire')
          @if(isset($allLocataire))
          @foreach($allLocataire as $item)
          <tr>
            <td>{{ get_annexee_name($item->idannexe_ref) }}</td>
            <th scope="row">{{ $item->nom_maison }}</th>
            <td>{{ $item->numero_chambre }} ({{ $item->type_chambre }})</td>
            <td>{{ $item->nom }} {{ $item->prenom }}</td>
            <td>
              @can('modify-locataire')
              <a class="btn rounded-pill btn-primary" title="Modifier" href="#" data-bs-toggle="modal"
                data-bs-target="#modifier{{$loop->iteration}}">
                <i class="bx bx-edit-alt me-1"></i>
              </a>
              @endcan

              @can('delete-locataire')
              <a class="btn rounded-pill btn-danger" title="Sortir un locataire" href="#" data-bs-toggle="modal"
                data-bs-target="#supprimer{{$loop->iteration}}">
                <i class="bx bx-trash me-1"></i>
              </a>
              @endcan

              @can('download-recu-avance')
              <a class="btn rounded-pill btn-success" title="Télcharge réçu"
                href="{{ route('telecharge',['id' =>  $item->id ]) }}">
                <i class="bx bx-download me-1"></i>
              </a>
              @endcan

              <button type="button" class="btn rounded-pill btn-primary" data-bs-toggle="modal"
                data-bs-target="#disablebackdrop{{ $loop->iteration }}">
                <i class="bx bx-zoom-in me-1"></i>
              </button>
            </td>
          </tr>

          <div class="modal fade" id="disablebackdrop{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="modalCenterTitle">Détails</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <ul class="list-group list-group-flush">
                    <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Téléphone: </label>{{
                      $item->telephone }}
                    </h3>
                    <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Date d'entrée: </label>{{
                      Carbon\Carbon::parse($item->date_entree)->format('d/m/Y') }}
                    </h3>
                    <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Caution:
                      </label>{{ $item->nombre_caution }} Mois
                    </h3>
                    <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Avance:
                      </label>{{ $item->nombre_avance }} Mois
                    </h3>
                    <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Nombre de mois consommé:
                      </label>{{ $item->nombre_avance_consomme }} Mois
                    </h3>
                    <h3 class="list-group-item"> <label class="badge rounded-pill  bg-primary">Caution eau: </label>
                      {{ number_format($item->caution_eau ,"0",",",".") }} XOF
                    </h3>
                    <h3 class="list-group-item"> <label class="badge rounded-pill  bg-primary">Caution électricité:
                      </label>
                      {{ number_format($item->caution_courant ,"0",",",".") }} XOF
                    </h3>
                  </ul>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
              </div>
            </div>
          </div>
          @endforeach
          @endif
          @endcan
        </tbody>
      </table>
    </div>
  </div>

  @if(isset($allLocataire))
  @foreach($allLocataire as $items)
  <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCenterTitle">Supprimer un locataire</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3" method="post" action="{{ route('destroy_chambre') }}">
            @csrf
            Voulez-vous vraiment supprimer cette ligne ?
            <input type="hidden" name="id" class="form-control" id="id" value="{{ $items->id}} ">
            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                Non
              </button>
              <button type="submit" class="btn btn-outline-danger">Oui</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modifier{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCenterTitle">Modification</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form class="row g-3" method="post" action="{{ route('update_locataire') }}" id="formulaireddsqd">
            @csrf

            <input type="hidden" name="locataire_id" class="form-control" id="id" value="{{ $items->id }}">

            <div class="col-6">
              <label for="inputNanme4" class="form-label">Choisir une maison<span style="color: red;">*</span></label>
              <input type="text" name="numero_chambre" value="{{$items->nom_maison}}"
                class="form-control @error('numero_chambre') is-invalid @enderror" id="numero_chambre" readonly
                disabled>
              <span class="invalid-feedback numero_chambre_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label for="inputEmail5" class="form-label">Nom locataire<span style="color: red;">*</span></label>
              <input type="text" name="nom_locataire" value="{{ $items->nom }}" id="nom_locataire"
                class="form-control @error('nom_locataire') is-invalid @enderror" required>
              <span class="invalid-feedback nom_locataire_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label for="inputPassword5" class="form-label">Prénom locataire<span style="color: red;">*</span></label>
              <input type="text" name="prenom_locataire" value="{{ $items->prenom }}" id="prenom_locataire"
                class="form-control @error('prenom_locataire') is-invalid @enderror" required>
              <span class="invalid-feedback prenom_locataire_err" role="alert"></span>
            </div>

            <div class="col-6">
              <label for="inputNanme4" class="form-label">N° de la chambre<span style="color: red;">*</span></label>
              <input type="text" name="numero_chambre" value="{{ $items->numero_chambre }}"
                class="form-control @error('numero_chambre') is-invalid @enderror" id="numero_chambre" readonly
                disabled>
              <span class="invalid-feedback numero_chambre_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label for="inputPassword5" class="form-label">Téléphone<span style="color: red;">*</span></label>
              <input type="text" name="telephone" value="{{ $items->telephone }}"
                onkeypress="return /[0-9]/i.test(event.key)" id="telephone"
                class="form-control @error('telephone') is-invalid @enderror" required onkeydown="limit(this);"
                onkeyup="limit(this);">
              <span class="invalid-feedback telephone_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label class="form-label">Email <span class="text-muted fw-normal">(optionnel)</span></label>
              <input type="email" name="email" value="{{ $items->email }}"
                class="form-control" placeholder="exemple@mail.com">
            </div>

            <div class="col-6">
              <label for="inputNanme4" class="form-label">Type chambre<span style="color: red;">*</span></label>
              <input type="text" name="type_chambre" value="{{ $items->type_chambre }}"
                class="form-control @error('type_chambre') is-invalid @enderror" id="type_chambre_getData" readonly
                disabled>
              <span class="invalid-feedback type_chambre_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label for="inputEmail5" class="form-label">Profession<span style="color: red;">*</span></label>
              <input type="text" name="profession" value="{{ $items->profession }}" id="profession"
                class="form-control @error('profession') is-invalid @enderror" required>
              <span class="invalid-feedback profession_err" role="alert"></span>
            </div>

            <div class="col-6">
              <label for="inputNanme4" class="form-label">Caution eau<span style="color: red;">*</span></label>
              <input type="text" name="caution_eau" value="{{ $items->caution_eau }}"
                class="form-control @error('caution_eau') is-invalid @enderror" id="caution_eau"
                onkeypress="return /[0-9]/i.test(event.key)">
              <span class="invalid-feedback caution_eau_err" role="alert"></span>
            </div>

            <div class="col-md-6">
              <label for="inputEmail5" class="form-label">Électricité<span style="color: red;">*</span></label>
              <input type="text" name="caution_courant" value="{{ $items->caution_courant }}" id="caution_courant"
                class="form-control @error('caution_courant') is-invalid @enderror"
                onkeypress="return /[0-9]/i.test(event.key)">
              <span class="invalid-feedback caution_courant_err" role="alert"></span>
            </div>

            <div class="col-3">
              <label for="inputNanme4" class="form-label">Caution pour<span style="color: red;">*</span></label>
              <select class="form-select @error('nombre_caution') is-invalid @enderror" name="nombre_caution"
                id="nombre_caution" aria-label="Default select example">
                <option selected disabled>Caution pour</option>
                <option value="2" {{$items->nombre_caution == '2' ? 'selected':''}}>2 Mois</option>
                <option value="3" {{$items->nombre_caution == '3' ? 'selected':''}}>3 Mois</option>
                <option value="4" {{$items->nombre_caution == '4' ? 'selected':''}}>4 Mois</option>
                <option value="5" {{$items->nombre_caution == '5' ? 'selected':''}}>5 Mois</option>
                <option value="6" {{$items->nombre_caution == '6' ? 'selected':''}}>6 Mois</option>
                <option value="12" {{$items->nombre_caution == '12' ? 'selected':''}}>12 Mois</option>
              </select>
              <span class="invalid-feedback nombre_caution_err" role="alert"></span>
            </div>

            <div class="col-3">
              <label for="inputNanme4" class="form-label">Date d'entrée<span style="color: red;">*</span></label>
              <input type="date" name="date_entre" value="{{ $items->date_entree }}"
                class="form-control @error('date_entre') is-invalid @enderror" id="date_entre" required
                min="<?= date('1970-m-d'); ?>">
              <span class="invalid-feedback date_entre_err" role="alert"></span>
            </div>

            <div class="col-6">
              <label for="inputNanme4" class="form-label">Avance pour</label>
              <select class="form-select @error('nombre_avance') is-invalid @enderror" name="nombre_avance"
                id="nombre_avance" aria-label="Default select example">
                <option selected disabled>Avance pour</option>
                <option value="2" {{$items->nombre_avance == '2' ? 'selected':''}}>2 Mois</option>
                <option value="3" {{$items->nombre_avance == '3' ? 'selected':''}}>3 Mois</option>
                <option value="4" {{$items->nombre_avance == '4' ? 'selected':''}}>4 Mois</option>
                <option value="5" {{$items->nombre_avance == '5' ? 'selected':''}}>5 Mois</option>
                <option value="6" {{$items->nombre_avance == '6' ? 'selected':''}}>6 Mois</option>
                <option value="12" {{$items->nombre_avance == '12' ? 'selected':''}}>12 Mois</option>
              </select>
              <span class="invalid-feedback nombre_avance_err" role="alert"></span>
            </div>

            <div class="col-6">
              <label for="inputNanme4" class="form-label">Mode de paiement</label>
              <select class="form-select @error('mode_paiement') is-invalid @enderror" name="mode_paiement"
                id="mode_paiement" aria-label="Default select example">
                <option selected disabled value="">Mode de paiement</option>
                <option value="Mobile money" {{$items->mode_paiement == 'Mobile money' ? 'selected':''}}>Mobile money</option>
                <option value="Virement bancaire" {{$items->mode_paiement == 'Virement bancaire' ? 'selected':''}}>Virement bancaire</option>
                <option value="Chèque" {{$items->mode_paiement == 'Chèque' ? 'selected':''}}>Chèque</option>
                <option value="Espèces" {{$items->mode_paiement == 'Espèces' ? 'selected':''}}>Espèces</option>
              </select>
              <span class="invalid-feedback mode_paiement_err" role="alert"></span>
            </div> 

            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" id="close" data-bs-dismiss="modal">Fermer</button>
              <button class="btn btn-primary" id="valider"><span class="fa fa-save" id="a"></span><span
                  id="s">Enregistrer</span></button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  @endforeach
  @endif

</div>

<!-- Script JavaScript -->
<script>
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

  function printErrorMsg(msg) {
      $.each(msg, function(key, value) {
          $('.' + key + '_err').text(value).show();
      });
  }

  // Fonction pour limiter les caractères
  function limit(element) {
      var max_chars = 8;
      if (element.value.length > max_chars) {
          element.value = element.value.substr(0, max_chars);
      }
  }

  // Fonctions pour formater les nombres
  $(document).on('keyup','#caution_eau',function(){Sepatateur_Milliers('#caution_eau');});

  $('#caution_eau').on('change keyup', function() {
      var sanitized = $(this).val().replace(/[^0-9]/g, '');
      $(this).val(sanitized);
  });

  $(document).on('keyup','#caution_courant',function(){Sepatateur_Milliers('#caution_courant');});

  $('#caution_courant').on('change keyup', function() {
      var sanitized = $(this).val().replace(/[^0-9]/g, '');
      $(this).val(sanitized);
  });

  // Configuration CSRF
  $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
  });

  // Chargement des chambres selon la maison sélectionnée
  $('#nom_maison').on('change',function(e) {
      var nom_maison2 = $(this).val();
      
      if(!nom_maison2) {
          display_sweet_alert_over_modal('Erreur !!', 'Merci de sélectionner une maison', 'warning', 'btn btn-danger');
          return false;
      }
      
      $.ajax({
          url: '{{ url('gerer-locataire/numero_chambre') }}',
          data: {idMaison:nom_maison2},
          type: 'GET',
          cache: false,
          dataType: 'json',
          success: function (data) {
              $('#numero_chambre').html(data.list_chambre);
              // Réinitialiser les champs dépendants
              $('#type_chambre_getData').val('');
              $('#prix_mois').val('');
          },
          error: function(xhr) {
              display_sweet_alert_over_modal('Erreur !!', 'Erreur lors du chargement des chambres', 'warning', 'btn btn-danger');
          }
      });
  });

  // Chargement du type de chambre
  $('#numero_chambre').on('change',function(e) {
      var numero_chambre_go = $(this).val();
      
      if(!numero_chambre_go) {
          display_sweet_alert_over_modal('Erreur !!', 'Merci de sélectionner une chambre', 'warning', 'btn btn-danger');
          return false;
      }
      
      // Charger le type de chambre
      $.ajax({
          url: '{{ url('gerer-locataire/type_chambre') }}',
          data: {numero_chambre_got:numero_chambre_go},
          type: 'GET',
          cache: false,
          dataType: 'json',
          success: function (data) {
              $('#type_chambre_getData').val(data.type_chambres_get);
          },
          error: function(xhr) {
              $('#type_chambre_getData').val('Type non disponible');
          }
      });
      
      // Charger le prix
      $.ajax({
          url: '{{ url('gerer-locataire/get_prix') }}',
          data: {prixGot:numero_chambre_go},
          type: 'GET',
          cache: false,
          dataType: 'json',
          success: function (data) {
              $('#prix_mois').val(data.prixApayer);
          },
          error: function(xhr) {
              $('#prix_mois').val('Prix non défini');
          }
      });
  });

  // Fonction pour soumettre le formulaire AJAX
  function save_locataire() {
      var data = new FormData();

      // Form data
      var form_data = $('#formulaire').serializeArray();
      $.each(form_data, function(key, input) {
          data.append(input.name, input.value);
      });

      $.ajax({
          url: "{{ route('store_locataire') }}",
          method: "POST",
          processData: false,
          contentType: false,
          data: data,
          beforeSend: function() {
              $("#AjouterLocataire button#close").prop("disabled", true);
              $("#AjouterLocataire button#valider").prop("disabled", true);
              $("#AjouterLocataire button#valider").html('<i class="text-center fa fa-spinner fa-pulse fa-1x fa-fw ml-2">En cours...</i>');
          },
          success: function(data) {
              $("#AjouterLocataire button#close").prop("disabled", false);
              $("#AjouterLocataire button#valider").prop("disabled", false);
              $("#AjouterLocataire button#valider").html('<span class="fa fa-save"></span> Enregistrer');

              if (!$.isEmptyObject(data.error)) {
                  printErrorMsg(data.error);
                  return;
              }

              try {
                  if (data.status) {
                      display_sweet_alert_over_modal("Succès !!", data.message, "success", "btn btn-primary");
                      
                      $("#AjouterLocataire form#formulaire")[0].reset();
                  } else {
                      display_sweet_alert_over_modal("Erreur !!", data.message, "warning", "btn btn-danger");
                  }
              } catch (error) {
                  console.error(error);
              }
          },
          error: function(xhr) {
              $("#AjouterLocataire button#close").prop("disabled", false);
              $("#AjouterLocataire button#valider").prop("disabled", false);
              $("#AjouterLocataire button#valider").html('<span class="fa fa-save"></span> Enregistrer');
              
              if (xhr.status === 422) {
                  var errors = xhr.responseJSON.errors || {};
                  var messages = xhr.responseJSON.message || "Erreur de validation";
                  printErrorMsg(errors);
                  display_sweet_alert_over_modal("Erreur !!", messages, "warning", "btn btn-danger");
              } else if (xhr.status === 500) {
                  display_sweet_alert_over_modal("Erreur !!", "Erreur serveur. Veuillez réessayer plus tard.", "warning", "btn btn-danger");
              } else {
                  display_sweet_alert_over_modal("Erreur !!", "Une erreur est survenue", "warning", "btn btn-danger");
              }
          }
      });
  }

  // Masquer les messages d'erreur lors de la saisie
  $(':input').on('input', function() {
      $('.' + $(this).attr("id") + '_err').hide();
  });

  $(':input').on('change', function() {
      $('.' + $(this).attr("id") + '_err').hide();
  });

  $('select').on('change', function() {
      $('.' + $(this).attr("id") + '_err').hide();
  });

  // Si vous avez une fonction Sepatateur_Milliers existante
  if (typeof Sepatateur_Milliers === 'undefined') {
      window.Sepatateur_Milliers = function(elementId) {
          var input = $(elementId);
          var value = input.val().replace(/\s/g, '').replace(/,/g, '');
          if (!isNaN(value) && value !== '') {
              var formatted = parseInt(value).toLocaleString('fr-FR');
              input.val(formatted);
          }
      };
  }
</script>

@endsection