@extends('layouts.template')


@section('content')

  @section('title')
  <title>Gestion chambre</title>
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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion chambre</h4>

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
            <h5 class="modal-title" id="modalCenterTitle">Ajouter une chambre</h5>
            <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" method="post" action="javascript:save_chambre();" id="formulaire">
              @csrf

      
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
                      <span class="invalid-feedback annexe_err" role="alert">
                      </span>
                </div>
                @endif
              @endcan


              <div class="col-12">
                <label for="inputNanme4" class="form-label">Choisir une maison<span style="color: red;">*</span></label>
                <select required="" class="form-select @error('nom_maison') is-invalid @enderror" name="nom_maison" id="nom_maison" aria-label="Default select example">
                    <option selected disabled value="">Choisir une maison</option>
                    @if(isset($allMaison))
                      @foreach($allMaison as $terme)
                        <option  value="{{$terme->id}}" {{ old($terme->id) == $terme->id ? 'selected' : '' }}>{{$terme->nom_maison}}</option>
                      @endforeach
                    @endif  
                  </select>
                      <span class="invalid-feedback nom_maison_err" role="alert">
                      </span>
              </div>

              <div class="col-12">
                <label for="inputNanme4" class="form-label">N° de la chambre<span style="color: red;">*</span></label>
                <input type="text" name="numero_chambre" class="form-control @error('numero_chambre') is-invalid @enderror" onkeypress="return /[0-9]/i.test(event.key)" id="numero_chambre" required>
                <span class="invalid-feedback numero_chambre_err" role="alert">
                </span>
              </div>


              <div class="col-12">
                <label for="inputNanme4" class="form-label">Type de chambre<span style="color: red;">*</span></label>
                <select required="" class="form-select @error('type_chambre') is-invalid @enderror" name="type_chambre" id="type_chambre" aria-label="Default select example">
                        <option selected disabled value="">Type de chambre</option>
                        <option  value="Entrée couche ordinaire">Entrée couche ordinaire</option>
                        <option  value="Entrée couche semi-sanitaire">Entrée couche semi-sanitaire</option>
                        <option  value="Entrée couche sanitaire">Entrée couche sanitaire</option>
                        <option  value="Chambre salon ordinaire">Chambre salon ordinaire</option>
                        <option  value="Chambre salon semi-sanitaire">Chambre salon semi-sanitaire</option>
                        <option  value="Chambre salon sanitaire">Chambre salon sanitaire</option>
                        <option  value="2Chambre salon ordinaire">2Chambre salon ordinaire</option>
                        <option  value="2Chambre salon semi-sanitaire">2Chambre salon semi-sanitaire</option>
                        <option  value="2Chambre salon sanitaire">2Chambre salon sanitaire</option>
                         <option  value="3Chambre salon ordinaire">3Chambre salon ordinaire</option>
                        <option  value="3Chambre salon semi-sanitaire">3Chambre salon semi-sanitaire</option>
                        <option  value="3Chambre salon sanitaire">3Chambre salon sanitaire</option>
                        <option  value="Appartement">Appartement</option>
                        <option  value="Boutique">Boutique</option>
                  </select>
                      <span class="invalid-feedback type_chambre_err" role="alert">
                      </span>
              </div>


              <div class="col-12">
                <label for="inputNanme4" class="form-label">Prix / mois<span style="color: red;">*</span></label>
                <input type="text" name="prix" class="form-control @error('prix') is-invalid @enderror" onkeypress="return /[0-9]/i.test(event.key)" id="prix" required>
                <span class="invalid-feedback prix_err" role="alert">
                </span>
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
      <h5 class="card-header text-center">Liste des chambres</h5>
      <div class="table-responsive text-nowrap">
        <table id="example" class="table table-hover border-primary" style="width:100%" >
          <thead>
            <tr>
                <th scope="col">Agence</th>
                <th scope="col">Nom maison</th>
                <th scope="col">N° chambre</th>
                <th scope="col">Type de chambre</th>
                <th scope="col">Prix</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @can('Consulter-chambre')

                @if(isset($allChambres))
                 @foreach($allChambres as $item)
                  <tr>
                    <td>{{ get_annexee_name($item->idannexe_ref) }}</td>
                    <th scope="row">{{ $item->nom_maison }}</th>
                    <td>{{ $item->numero_chambre }}</td>
                    <td>{{ $item->type_chambre }}</td>
                    <td>{{ number_format($item->prix_chambre ,"0",",",".") }} XOF</td>
                    <td>
                      @if($item->etat == true) 
                         <span class="badge rounded-pill bg-danger">Occupé</span>
                      @else
                        <span class="badge rounded-pill bg-success">Libre</span>
                      @endif
                    </td>
                    <td>
                        @can('modify-chambre')
                        <a class="btn rounded-pill btn-primary" title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                            <i class="bx bx-edit-alt me-1"></i>
                        </a>
                        @endcan

                        @can('delete-chambre')
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

    @foreach($allChambres as $items)
      <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Supprimer un propriétaire</h5>
                <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
              <form class="row g-3" method="post" action="{{ route('update_chambre') }}">
                @csrf
                <input type="hidden" name="chambre_id" class="form-control" id="id" value="{{ $items->id}} ">

        
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
                <label for="inputNanme4" class="form-label">Choisir une maison<span style="color: red;">*</span></label>
                <select required="" class="form-select @error('nom_maison') is-invalid @enderror" name="nom_maison" id="nom_maison" aria-label="Default select example">
                    <option selected disabled value="">Choisir une maison</option>
                    @if(isset($allMaison))
                      @foreach($allMaison as $terme)
                        <option  value="{{$terme->id}}"  {{$items->maison_id == $terme->id ? 'selected':''}}>{{$terme->nom_maison}}</option>
                      @endforeach
                    @endif  
                  </select>
                      <span class="invalid-feedback nom_maison_err" role="alert">
                      </span>
                </div>


                <div class="col-12">
                    <label for="inputNanme4" class="form-label">N° de la chambre<span style="color: red;">*</span></label>
                    <input type="text" name="numero_chambre" value="{{ $items->numero_chambre }}" class="form-control @error('numero_chambre') is-invalid @enderror"
                    onkeypress="return /[0-9]/i.test(event.key)" id="numero_chambre" required>
                    <span class="invalid-feedback numero_chambre_err" role="alert">
                    </span>
                  </div>


                  <div class="col-12">
                  <label for="inputNanme4" class="form-label">Type de chambre<span style="color: red;">*</span></label>
                  <select required="" class="form-select @error('type_chambre') is-invalid @enderror" name="type_chambre" id="type_chambre" aria-label="Default select example">
                          <option selected disabled>Type de chambre</option>
                          <option  value="Entrée couche ordinaire"
                          {{$items->type_chambre == 'Entrée couche ordinaire' ? 'selected':''}}
                          >Entrée couche ordinaire
                          </option>
                          <option  value="Entrée couche semi-sanitaire"
                          {{$items->type_chambre == 'Entrée couche semi-sanitaire' ? 'selected':''}}>Entrée couche semi-sanitaire</option>
                          <option  value="Entrée couche sanitaire"
                          {{$items->type_chambre == 'Entrée couche sanitaire' ? 'selected':''}}>Entrée couche sanitaire</option>
                          <option  value="Chambre salon ordinaire"
                          {{$items->type_chambre == 'Chambre salon ordinaire' ? 'selected':''}}>Chambre salon ordinaire</option>
                          <option  value="Chambre salon semi-sanitaire"
                          {{$items->type_chambre == 'Chambre salon semi-sanitaire' ? 'selected':''}}>Chambre salon semi-sanitaire</option>
                          <option  value="Chambre salon sanitaire"
                          {{$items->type_chambre == 'Chambre salon sanitaire' ? 'selected':''}}>Chambre salon sanitaire</option>
                          <option  value="2Chambre salon ordinaire"
                          {{$items->type_chambre == '2Chambre salon ordinaire' ? 'selected':''}}>2Chambre salon ordinaire</option>
                          <option  value="2Chambre salon semi-sanitaire"
                          {{$items->type_chambre == '2Chambre salon semi-sanitaire' ? 'selected':''}}>2Chambre salon semi-sanitaire</option>
                          <option  value="2Chambre salon sanitaire"
                          {{$items->type_chambre == '2Chambre salon sanitaire' ? 'selected':''}}>2Chambre salon sanitaire</option>
                          <option  value="3Chambre salon ordinaire"
                          {{$items->type_chambre == '3Chambre salon ordinaire' ? 'selected':''}}>3Chambre salon ordinaire</option>
                          <option  value="3Chambre salon semi-sanitaire"
                          {{$items->type_chambre == '3Chambre salon semi-sanitaire' ? 'selected':''}}>3Chambre salon semi-sanitaire</option>
                          <option  value="3Chambre salon sanitaire"
                          {{$items->type_chambre == '3Chambre salon sanitaire' ? 'selected':''}}>3Chambre salon sanitaire</option>
                          <option  value="Appartement"
                          {{$items->type_chambre == 'Appartement' ? 'selected':''}}>Appartement</option>
                          <option  value="Boutique"
                          {{$items->type_chambre == 'Boutique' ? 'selected':''}}>Boutique</option>
                    </select>
                        <span class="invalid-feedback type_chambre_err" role="alert">
                        </span>
                </div>

                <div class="col-12">
                  <label for="inputNanme4" class="form-label">Prix / mois<span style="color: red;">*</span></label>
                  <input type="text" name="prix" value="{{ $items->prix_chambre }}" class="form-control @error('prix') is-invalid @enderror" onkeypress="return /[0-9]/i.test(event.key)" id="prix" required>
                  <span class="invalid-feedback prix_err" role="alert">
                  </span>
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
    @endif

        
    <script>

        function printErrorMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(value);
            });
        }
      
        function save_chambre() {
      
            var data = new FormData();
      
            //Form data
            var form_data = $('#formulaire').serializeArray();
            $.each(form_data, function (key, input) {
                data.append(input.name, input.value);
            });
      
      
      
            $.ajax({
                url: "{{ route('store_chambre') }}",
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
                            //swal('Super !!',data.message, 'success');
                           display_message("Super !!",data.message,"success","btn btn-primary");
                            
      
                            $("#AjouerMaison form#formulaire")[0].reset();
                        } else {
                            //$("#AjouerMaison div#afficher").html(data.message)
                            //swal('Erreur !!',data.message, 'warning');
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