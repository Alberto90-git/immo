@extends('layouts.template')


@section('content')

    @section('title')
    <title>Gestion maison</title>
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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion maison</h4>

    <div class="col-md-6">
      <div class="demo-inline-spacing">
        <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal"
          data-bs-target="#AjouerMaison">
          <span class="bx bx-plus"></span>
        </button>
      </div>
    </div><br/>


    <div class="modal fade" id="AjouerMaison" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalCenterTitle">Ajouter une maison</h5>
            <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" method="post" action="javascript:save_house();" 
               id="formulaire">
               @csrf
              
                <div class="alert-primary bg-primary text-light" id="afficher"></div>

                    @can('Is_admin')
                        @if(Auth::user()->type_compte != 'Particulier')
                            <div class="col-12">
                                <label for="inputNanme4" class="form-label">Choisir une annexe<span style="color: red;">*</span></label>
                                <select required="" class="form-select @error('annexe') is-invalid @enderror" name="annexe" id="annexe" aria-label="Default select example">
                                    <option selected disabled value="">Choisir une annexe</option>
                                    @if(!empty(Session::get('anne_data')))
                                        @foreach(Session::get('anne_data') as $terme)
                                        <option  value="{{$terme->idannexes}}">{{ $terme->designation }}</option>
                                        @endforeach
                                    @endif 
                                    </select>
                                    <span class="invalid-feedback annexe_err" role="alert"></span>
                            </div>
                        @endif
                    @endcan


                    <div class="col-12">
                        <label for="inputNanme4" class="form-label">Choisir un propiétaire<span style="color: red;">*</span></label>
                        <select required="" class="form-select @error('nom_proprietaire') is-invalid @enderror" name="nom_proprietaire" id="nom_proprietaire" aria-label="Default select example">
                            <option selected disabled value="">Choisir un propiétaire</option>
                            @if(isset($allProprios))
                                @foreach($allProprios as $terme)
                                <option  value="{{$terme->id}}">{{$terme->nom}}  {{$terme->prenom}}</option>
                                @endforeach
                            @endif  
                        </select>
                        <span class="invalid-feedback nom_proprietaire_err" role="alert"></span>
                    </div>


                    <div class="col-12">
                        <label for="inputNanme4" class="form-label">Nom de la maison<span style="color: red;">*</span></label>
                        <input type="text" name="nom_maison" class="form-control @error('nom_maison') is-invalid @enderror" id="nom_maison" required>
                        <span class="invalid-feedback nom_maison_err" role="alert"></span>
                    </div>

                    <div class="col-12">
                        <label for="inputEmail4" class="form-label">Quartier<span style="color: red;">*</span></label>
                        <input type="text" name="quartier" class="form-control @error('quartier') is-invalid @enderror" id="quartier" required>
                        <span class="invalid-feedback quartier_err" role="alert"> </span>
                    </div>

                    <div class="col-12">
                        <label for="inputPassword4" class="form-label">Nombre de chambre<span style="color: red;">*</span></label>
                        <input type="text" name="nombre_chambre" onkeyup="limit(this);" onkeydown="limit(this);" class="form-control @error('nombre_chambre') is-invalid @enderror" id="nombre_chambre" required>
                        <span class="invalid-feedback nombre_chambre_err" role="alert"></span>
                    </div>
                    <div class="modal-footer">
                    <button  class="btn btn-outline-secondary" onclick="(this);" id="close" data-bs-dismiss="modal">Fermer</button>
                    <button  class="btn btn-primary" id="valider"><span class="fa fa-save" id="a"></span><span id="s">Enregistrer</span></button>
                    </div>
	         </form>
	        </div>
          </div>
        </div>
      </div>

      <!-- Hoverable Table rows -->
    <div class="card">
      <h5 class="card-header text-center">Liste des maisons</h5>
      <div class="table-responsive text-nowrap">
        <table id="example" class="table table-hover border-primary" style="width:100%" >
          <thead>
            <tr>
                <th scope="col">Agence</th>
                <th scope="col">Propriétaire</th>
                <th scope="col">Nom maison</th>
                <th scope="col">Quartier</th>
                <th scope="col">Nombre de chambre</th>
                <th scope="col">Actions</th>
              </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @can('Consulter-maison')
                @if(isset($allMaison))
                 @foreach($allMaison as $item)
                  <tr>
                    <td>{{ get_annexee_name($item->idannexe_ref) }}</td>
                    <th scope="row">{{ $item->nom }}  {{ $item->prenom }}</th>
                    <td>{{ $item->nom_maison }}</td>
                    <td>{{ $item->quartier }}</td>
                    <td>{{ $item->nombre_chambre }}</td>
                    <td>
                      @can('modify-maison')
                        <a class="btn rounded-pill btn-primary" 
                        title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                            <i class="bx bx-edit-alt me-1"></i>
                        </a>
                       @endcan

                        @can('delete-maison')
                            <a class="btn rounded-pill btn-danger" title="Supprimer" href="#" data-bs-toggle="modal" data-bs-target="#supprimer{{$loop->iteration}}">
                                <i class="bx bx-trash me-1"></i>
                            </a>
                        @endcan
                    </td>
                  </tr>
                @endforeach
                @endcan
          </tbody>
        </table>
      </div>
    </div>
    <!--/ Hoverable Table rows -->

    @foreach($allMaison as $items)
      <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Supprimer un propriétaire</h5>
                <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
              <form class="row g-3" method="post" action="{{ route('destroy_house') }}">
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
                <form class="row g-3" method="post" action="{{ route('update_house') }}" id="formulaireModifiedqsdqsr">
                        @csrf
               
                    <input type="hidden" name="house_id" class="form-control" id="id" value="{{ $items->id}} ">
                    <div class="alert-primary bg-primary text-light" id="afficher2"></div>
    
                    @can('Is_admin')
                    @if(Auth::user()->type_compte != 'Particulier')
                        <div class="col-12">
                            <label for="inputNanme4" class="form-label">Choisir une annexe<span style="color: red;">*</span></label>
                            <select required="" class="form-select @error('annexe') is-invalid @enderror" name="annexe" id="annexe" aria-label="Default select example">
                                <option selected disabled value="">Choisir une annexe</option>
                                @if(!empty(Session::get('anne_data')))
                                @foreach(Session::get('anne_data') as $terme)
                                    <option  value="{{$terme->idannexes}}"    {{$items->idannexe_ref == $terme->idannexes ? 'selected':''}}>{{ $terme->designation }}</option>
                                @endforeach
                                @endif 
                            </select>
                            <span class="invalid-feedback annexe_err" role="alert">
                                </span>
                        </div>
                    @endif
                    @endcan
 
 
                    <div class="col-12">
                        <label for="inputNanme4" class="form-label">Choisir un propiétaire<span style="color: red;">*</span></label>
                        <select required="" class="form-select @error('nom_proprietaire') is-invalid @enderror" name="nom_proprietaire2" id="nom_proprietaire" aria-label="Default select example">
                            <option selected disabled value="">Choisir un propiétaire</option>
                            @if(isset($allProprios))
                            @foreach($allProprios as $terme)
                                 <option  value="{{$terme->id}}"  {{$items->proprio_id == $terme->id ? 'selected':''}}>{{$terme->nom}}  {{$terme->prenom}}</option>
                            @endforeach
                            @endif 
                        </select>
                        <span class="invalid-feedback nom_proprietaire_err" role="alert"></span>
                    </div>
 
 
                    <div class="col-12">
                        <label for="inputNanme4" class="form-label">Nom de la maison<span style="color: red;">*</span></label>
                        <input type="text" name="nom_maison" class="form-control @error('nom_maison') is-invalid @enderror" id="nom_maison" value="{{ $items->nom_maison }}" required>
                        <span class="invalid-feedback nom_maison_err" role="alert">
                            </span>
                    </div>

                    <div class="col-12">
                        <label for="inputEmail4" class="form-label">Quartier<span style="color: red;">*</span></label>
                        <input type="text" name="quartier" class="form-control @error('quartier') is-invalid @enderror" id="quartier" value="{{ $items->quartier }}"required>
                        <span class="invalid-feedback quartier_err" role="alert">
                            </span>
                    </div>

                    <div class="col-12">
                        <label for="inputPassword4" class="form-label">Nombre de chambre<span style="color: red;">*</span></label>
                        <input type="number" min="1" name="nombre_chambre" class="form-control @error('nombre_chambre') is-invalid @enderror" 
                        id="nombre_chambre" value="{{ $items->nombre_chambre }}" required>
                            <span class="invalid-feedback nombre_chambre_err" role="alert">
                            </span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" id="close" data-bs-dismiss="modal">Fermer</button>
                        <button  class="btn btn-primary" id="valider"><span class="fa fa-save" id="a"></span><span id="s">Enregistrer</span></button>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>

      @endforeach
     @endif

        
    <script>

        function limit(element) {
            var max_chars = 2;
            if (element.value.length > max_chars) {
                element.value = element.value.substr(0, max_chars);
            }
      
        }
      
      
        $('#nombre_chambre').on('change keyup', function() {
          // Remove invalid characters
          var sanitized = $(this).val().replace(/[^0-9]/g, '');
          $(this).val(sanitized);
        });
      
        function printErrorMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(value);
            });
        }
      
        function save_house() {
      
            var data = new FormData();
      
            //Form data
            var form_data = $('#formulaire').serializeArray();
            $.each(form_data, function (key, input) {
                data.append(input.name, input.value);
            });
      
      
      
            $.ajax({
                url: "{{ route('store_house') }}",
                method: "POST",
                processData: false,
                contentType: false,
                data: data,
                beforeSend: function(data) {
                    $("#AjouerMaison button#close").prop("disabled", true);
                    $("#AjouerMaison button#valider").prop("disabled", true);
                    $("#AjouerMaison button#valider").html('<i class="text-center fa fa-spinner fa-pulse fa-1x fa-fw ml-2">En cours...</i>');
                },
                success: function(data) {
      
      
                    $("#AjouerMaison button#close").prop("disabled", false);
                    $("#AjouerMaison button#valider").prop("disabled", false);
                    $("#AjouerMaison button#valider").html('Enregistrer');
      
      
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                    }
                    try {
                        if (data.status) {
                         // rempliretableau();
      
                           // alert(data.message);
                           // $("#AjouerMaison div#afficher").html(data.message)
                          display_message("Super !!",data.message,"success","btn btn-primary");
      
      
                            $("#AjouerMaison form#formulaire")[0].reset();
                        } else {
                           // $("#AjouerMaison div#afficher").html(data.message)
                          display_message("Erreur !!",data.message,"warning","btn btn-danger");
      
                        }
      
                    } catch (error) {
      
                    }
      
                },
                error: function(data) {
      
               }
            });
      
        }
      
      
         function modification() {
      
            var data = new FormData();
      
            //Form data
            var form_data = $('#formulaireModifier').serializeArray();
            $.each(form_data, function (key, input) {
                data.append(input.name, input.value);
            });
      
      
      
            $.ajax({
                url: "{{ route('update_house') }}",
                method: "POST",
                processData: false,
                contentType: false,
                data: data,
                beforeSend: function(data) {
                    $("#AjouerMaison button#close").prop("disabled", true);
                    $("#AjouerMaison button#valider").prop("disabled", true);
                    $("#AjouerMaison button#valider").html('<i class="text-center fa fa-spinner fa-pulse fa-1x fa-fw ml-2">En cours...</i>');
                },
                success: function(data) {
      
      
                    $("#AjouerMaison button#close").prop("disabled", false);
                    $("#AjouerMaison button#valider").prop("disabled", false);
                    $("#AjouerMaison button#valider").html('Enregistrer');
      
      
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                    }
                    try {
                        if (data.status) {
                         // rempliretableau();
      
                           // alert(data.message);
                            $("#AjouerMaison div#afficher2").html(data.message)
                            $("#AjouerMaison form#formulaireModifier")[0].reset();
                        } else {
                            $("#AjouerMaison div#afficher2").html(data.message)
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