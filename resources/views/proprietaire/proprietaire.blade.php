@extends('layouts.template')


@section('content')

  @section('title')
    <title>Gestion propriétaire</title>
  @endsection

    @if (Session::has("message"))
      <div class="col-md-6 p-4">
        <div class="toast-container">
        <div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-semibold">SUCCES</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
              {{ Session::get('message') }}
            </div>
        </div>
        </div>
      </div>
    @elseif (Session::has("error"))
        <div class="col-md-6 p-4">
            <div class="toast-container">
            <div class="bs-toast toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-semibold">ERREUR</div>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                  {{ Session::get('error') }}
                </div>
            </div>
            </div>
        </div>
    @endif
    
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion propriétaire</h4>

    <div class="col-md-6">
      <div class="demo-inline-spacing">
        <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal"
          data-bs-target="#AjouerProprietaire">
          <span class="bx bx-plus"></span>
        </button>
      </div>
    </div><br/>


    <div class="modal fade" id="AjouerProprietaire" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalCenterTitle">Ajouter propiétaire</h5>
            <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" method="post" action="javascript:save_proprietaire();" id="formulaire">
              @csrf

              <div class="alert-primary bg-primary text-light" id="afficher"></div>
      
                @can('Is_admin')
                @if(Auth::user()->type_compte != 'Particulier')
                  <div class="row mb-3">
                  <label class="col-sm-2 col-form-label" for="basic-default-company">Agence<span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                      <select class="form-select" id="annexe" name="annexe" aria-label="Default select example">
                        <option value="" selected disabled>Choisir une agence</option>
                          @if(Session::get('anne_data') != " ")
                            @foreach(Session::get('anne_data') as $terme)
                              <option  value="{{$terme->idannexes}}">{{ $terme->designation }}</option>
                            @endforeach
                          @endif 
                      </select>
                        <span class="invalid-feedback annexe_err" role="alert">
                        </span>
                    </div>
                  </div>
                @endif
                @endcan


                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label" for="basic-default-name">Nom<span style="color: red;">*</span></label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="nom" name="nom" />
                    <span class="invalid-feedback nom_err" role="alert">
                    </span>
                  </div>
                </div>

                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label" for="prenom">Prénom<span style="color: red;">*</span></label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="prenom" name="prenom" />
                    <span class="invalid-feedback prenom_err" role="alert"></span>
                  </div>
                </div>
                <div class="row mb-3">
                  <label class="col-sm-2 col-form-label" for="telephone">Téléphone<span style="color: red;">*</span></label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="telephone" name="telephone" />
                    <span class="invalid-feedback telephone_err" role="alert"></span>
                  </div>
                </div>
                
                <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-default-name">Adresse<span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="adresse" name="adresse" />
                      <span class="invalid-feedback adresse_err" role="alert">
                    </span>
                    </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                  Fermer
                </button>
                <button  class="btn btn-primary" id="valider"><span class="fa fa-save" id="a"></span><span id="s">Enregistrer</span></button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

      <!-- Hoverable Table rows -->
    <div class="card">
      <h5 class="card-header text-center">Liste des propriétaires</h5>
      <div class="table-responsive text-nowrap">
        <table id="example" class="table table-hover border-primary" style="width:100%" >
          <thead>
            <tr>
              <th scope="col">Agence</th>
              <th>Nom & prénom</th>
              <th>Téléphone</th>
              <th>Adresse</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @can('Consulter-proprietaire')
              @if(isset($allProprios))
                @foreach($allProprios as $item)  
                  <tr>
                    <td><i class="fab fa-angular fa-lg text-danger me-3"></i> <strong>{{ get_annexee_name($item->idannexe_ref) }}</strong></td>
                    <th scope="row">{{ $item->nom }}  {{ $item->prenom }}</th>
                    <td>{{ $item->telephone }}</td>
                    <td>{{ $item->adresse }}</td>
                    <td>
                      @can('modify-proprietaire')
                      <a class="btn rounded-pill btn-primary" 
                      title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                          <i class="bx bx-edit-alt me-1"></i>
                        </a>
                      @endcan
                  
                      @can('delete-proprietaire')
                      <a class="btn rounded-pill btn-danger"
                      title="Supprimer" href="#" data-bs-toggle="modal" data-bs-target="#supprimer{{$loop->iteration}}">
                            <i class="bx bx-trash me-1"></i>
                        </a>
                      @endcan
                  </td>
                  
                  </tr>
                @endforeach
              @endif
            @endcan
          </tbody>
        </table>
      </div>
    </div>
    <!--/ Hoverable Table rows -->

    @foreach($allProprios as $items)
      <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Supprimer un propriétaire</h5>
                <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
              <form class="row g-3" method="post" action="{{ route('destroy_proprio') }}">
                      @csrf
                  Voulez-vous vraiment supprimer cette ligne ? 
                <input type="hidden" name="id" class="form-control" id="id" value="{{ $items->id}} ">
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Non
                  </button>
                  <button  type="submit" class="btn btn-outline-danger">Oui</button>
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
              <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form class="row g-3" method="post" action="{{ route('update_proprio') }}">
                @csrf
                <input type="hidden" name="id" class="form-control" id="id" value="{{ $items->id}} ">

                <div class="alert-primary bg-primary text-light" id="afficher"></div>
        
                  @can('Is_admin')
                  @if(Auth::user()->type_compte != 'Particulier')
                    <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-default-company">Agence<span style="color: red;">*</span></label>
                      <div class="col-sm-10">
                        <select class="form-select" id="annexe" name="annexe" aria-label="Default select example">
                          <option value="" selected disabled>Choisir une agence</option>
                            @if(Session::get('anne_data') != " ")
                              @foreach(Session::get('anne_data') as $terme)
                                <option  value="{{$terme->idannexes}}"    {{$items->idannexe_ref == $terme->idannexes ? 'selected':''}}>{{ $terme->designation }}</option>
                              @endforeach
                            @endif 
                        </select>
                        <span class="invalid-feedback annexe_err" role="alert"></span>
                      </div>
                    </div>
                  @endif
                  @endcan


                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-default-name">Nom<span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" value="{{ $items->nom }}" id="nom" name="nom"  required/>
                      <span class="invalid-feedback nom_err" role="alert">
                      </span>
                    </div>
                  </div>

                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-default-company">Prénom<span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" value="{{ $items->prenom }}" id="prenom" name="prenom" required/>
                      <span class="invalid-feedback prenom_err" role="alert">
                      </span>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <label class="col-sm-2 col-form-label" for="basic-default-company">Téléphone<span style="color: red;">*</span></label>
                    <div class="col-sm-10">
                      <input type="text" class="form-control" value="{{ $items->telephone }}" id="telephone" name="telephone" required/>
                      <span class="invalid-feedback telephone_err" role="alert">
                      </span>
                    </div>
                  </div>
                  <div class="row mb-3">
                      <label class="col-sm-2 col-form-label" for="basic-default-name">Adresse<span style="color: red;">*</span></label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" value="{{ $items->adresse }}" id="adresse" name="adresse" required />
                        <span class="invalid-feedback adresse_err" role="alert">
                      </span>
                      </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Fermer
                  </button>
                  <button  class="btn btn-primary">Enregistrer</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    @endforeach

        
<script>
   $(document).ready(function() {
      $('#example').DataTable({
          responsive: true,
          lengthChange: false,
          autoWidth: false,
          buttons: [
              {
                  extend: 'copy',
                  exportOptions: {
                      columns: ':not(:last-child)'
                  }
              },
              {
                  extend: 'csv',
                  exportOptions: {
                      columns: ':not(:last-child)'
                  }
              },
              {
                  extend: 'excel',
                  exportOptions: {
                      columns: ':not(:last-child)'
                  }
              },
              {
                  extend: 'pdf',
                  exportOptions: {
                      columns: ':not(:last-child)'
                  }
              },
              {
                  extend: 'print',
                  exportOptions: {
                      columns: ':not(:last-child)'
                  }
              },
              'colvis'
          ]
      }).buttons().container().appendTo('#example .col-md-6:eq(0)');
  });
  

  function printErrorMsg(msg) {
      $.each(msg, function(key, value) {
          $('.' + key + '_err').text(value);
      });
  }
      
      
  function limit(element) {
      var max_chars = 8;
      if (element.value.length > max_chars) {
          element.value = element.value.substr(0, max_chars);
      }
  }
      
  function save_proprietaire() {

      var data = new FormData();

      //Form data
      var form_data = $('#formulaire').serializeArray();
      $.each(form_data, function (key, input) {
          data.append(input.name, input.value);
      });

      $.ajax({
          url: "{{ route('store_propre') }}",
          method: "POST",
          processData: false,
          contentType: false,
          data: data,
          beforeSend: function(data) {
              $("#AjouerProprietaire button#close").prop("disabled", true);
              $("#AjouerProprietaire button#valider").prop("disabled", true);
              $("#AjouerProprietaire button#valider").html('<i class="text-center fa fa-spinner fa-pulse fa-1x fa-fw ml-2">En cours...</i>');
          },
          success: function(data) {


              $("#AjouerProprietaire button#close").prop("disabled", false);
              $("#AjouerProprietaire button#valider").prop("disabled", false);
              $("#AjouerProprietaire button#valider").html('Enregistrer');


              if (!$.isEmptyObject(data.error)) {
                  printErrorMsg(data.error);
              }
              try {
                  if (data.status) {
                    // rempliretableau();

                      // alert(data.message);
                      // $("#AjouerProprietaire div#afficher").html(data.message)
                    display_message("Super !!",data.message,"success","btn btn-primary");

                      $("#AjouerProprietaire form#formulaire")[0].reset();
                  } else {
                      //$("#AjouerProprietaire div#afficher").html(data.message)
                    display_message("Erreur !!",data.message,"warning","btn btn-danger");

                  }

              } catch (error) {

              }

          },
          error: function(data) {

          }
      });

  }
      
      
      
  function printErrorMsg(msg) {
    const items = [];
    for (const [key, value] of Object.entries(msg)) {
        $('.' + key + '_err').text(value).show();
        var elmnt = $('.' + key + '_err');
        items.push(elmnt.closest('.form-group'))
    }
  }

  $(':input').on('input', function() {
      $('.' + $(this).attr("id") + '_err').hide();
  });


  $(':input').on('change', function() {
      $('.' + $(this).attr("id") + '_err').hide();
  });
      
      
  $('select').on('change', function() {
      $('.' + $(this).attr("id") + '_err').hide();
  });
        
</script>
@endsection