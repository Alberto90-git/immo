@extends('layouts.template')


@section('content')
  @section('title')
  <title>Gestion client</title>
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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion des dossiers client</h4>

    <div class="col-md-6">
      <div class="demo-inline-spacing">
        <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal"
          data-bs-target="#AjouerClient">
          <span class="bx bx-plus"></span>
        </button>
      </div>
    </div><br/>


    <div class="modal fade" id="AjouerClient" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalCenterTitle">Ajouter un dossier client</h5>
            <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" method="post" action="javascript:save_client();" 
               id="formulaire">
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
                
                <div class="col-6">
                  <label for="inputNanme4" class="form-label">Nom client<span style="color: red;">*</span></label>
                  <input type="text" name="nom" class="form-control" id="nom" required="">
                  <span class="invalid-feedback nom_err" role="alert">
                  </span>
                </div>

                <div class="col-6">
                  <label for="inputEmail4" class="form-label">Prénom client<span style="color: red;">*</span></label>
                  <input type="text" name="prenom" class="form-control" id="prenom" required="">
                  <span class="invalid-feedback prenom_err" role="alert"></span>
                </div>

                <div class="col-6">
                  <label for="inputPassword4" class="form-label">Téléphone<span style="color: red;">*</span></label>
                  <input type="text" name="telephone" class="form-control"
                  id="telephone" required="">
                  <span class="invalid-feedback telephone_err" role="alert"></span>
                </div>

                <div class="col-6">
                  <label for="inputAddress" class="form-label">Zone voulue<span style="color: red;">*</span></label>
                  <input type="text" name="zone" class="form-control"
                   id="zone" required="">
                  <span class="invalid-feedback zone_err" role="alert"></span>
                </div>

                <div class="col-6">
                  <label for="inputAddress" class="form-label">Superficie (m²)<span style="color: red;">*</span></label>
                  <input type="text" name="superficie" class="form-control"
                   id="superficie" required="" onkeypress="return /[0-9]/i.test(event.key)">
                  <span class="invalid-feedback superficie_err" role="alert"></span>
                </div>


                <div class="col-6">
                  <label for="inputAddress" class="form-label">Budget<span style="color: red;">*</span></label>
                  <input type="text" name="budget" class="form-control"
                   id="budget" required="">
                  <span class="invalid-feedback budget_err" role="alert"></span>
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-outline-secondary" onclick="(this);" id="close" data-bs-dismiss="modal">Fermer</button>
                  <button  class="btn btn-primary" id="valider"><span class="fa fa-save" id="a"></span><span id="s">Enregistrer</span></button>
                </div>
	         </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Hoverable Table rows -->
    <div class="card">
      <h5 class="card-header text-center">Liste des dossiers client</h5>
      <div class="table-responsive text-nowrap">
        <table id="example" class="table table-hover border-primary" style="width:100%" >
          <thead>
            <tr>
                <th scope="col">Agence</th>
                <th scope="col">Nom & Prénom</th>
                <th scope="col">Téléphone</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
              </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @can('consulter-client')

                @if(isset($all_customers))
                 @foreach($all_customers as $item)
                  <tr>
                    <td>{{ get_annexee_name($item->idannexe_ref) }}</td>
                    <th scope="row">{{ $item->nom }}  {{ $item->prenom }}</th>
                    <td>{{ $item->telephone }}</td>
                    <td>
                      @if($item->status == '')
                      <span class="badge rounded-pill bg-success">En attente</span>
                      @else
                      <span class="badge rounded-pill bg-danger">Dossier cloturé</span>
                      @endif
                    </td>
                    <td>
                       @can('modifier-client')
                        @if($item->status == '')
                          <a class="btn rounded-pill btn-primary" 
                          title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                            <i class="bx bx-edit-alt me-1"></i>
                           </a>
                         @endif
                       @endcan
                    
                       @can('supprimer-client')
                        @if($item->status == '')
                          <a class="btn rounded-pill btn-danger"
                            title="Supprimer" href="#" data-bs-toggle="modal" data-bs-target="#supprimer{{$loop->iteration}}">
                                <i class="bx bx-trash me-1"></i>
                           </a>
                        @endif
                       @endcan
                    
                       @can('cloturer-client')
                        @if($item->status == '')
                          <a class="btn rounded-pill btn-success" 
                          title="Cloturer" href="#" data-bs-toggle="modal" data-bs-target="#cloturer{{$loop->iteration}}">
                            <i class="bx bx-check-circle me-1"></i>
                           </a>
                        @endif
                       @endcan

                       <button type="button" class="btn rounded-pill btn-primary" data-bs-toggle="modal" data-bs-target="#disablebackdrop{{ $loop->iteration }}">
                        <i class="bx bx-zoom-in me-1"></i>
                      </button>
                    </td>
                  </tr>


                  <div class="modal fade" id="disablebackdrop{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="modalCenterTitle">Détails</h5>
                          <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <ul class="list-group list-group-flush">
                              <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Zone voulue:   </label>{{ $item->zone_voulu }}
                              </h3>
                              <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Superficie:   </label>{{ $item->superficie }} m²
                              </h3>
                              <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Budget:   </label>{{ number_format($item->budget ,"0",",",".") }} XOF
                              </h3>
                              
                          </ul>

                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
                      </div>
                    </div>
                  </div>

                @endforeach
              @endcan
          </tbody>
        </table>
      </div>
    </div>
    <!--/ Hoverable Table rows -->

    @foreach($all_customers as $items)
      <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Supprimer un propriétaire</h5>
                <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
                <form class="row g-3" method="post" action="{{ route('delete_client') }}">
                    Voulez-vous vraiment supprimer cette ligne ? 
                    @csrf
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


      <div class="modal fade" id="cloturer{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Cloturer un dossier</h5>
                <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
                <form class="row g-3" method="post" action="{{ route('cloture_client') }}">
                    Voulez-vous vraiment cloturer ce dossier ? 
                    @csrf
                   <input type="hidden" name="id" class="form-control" id="id" value="{{ $items->id}} ">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Non</button>
                        <button type="submit" class="btn btn-danger shadow" >Oui</button>
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
                <form class="row g-3" method="post" action="{{ route('update_client') }}">
                    @csrf
                     <input type="hidden" name="id" class="form-control" id="id" value="{{ $items->id}} ">
 
 
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
 
                     <div class="col-6">
                       <label for="inputNanme4" class="form-label">Nom<span style="color: red;">*</span></label>
                       <input type="text" name="nom" value="{{ $items->nom }}" class="form-control" id="nom">
                     </div>
                     <div class="col-6">
                       <label for="inputEmail4" class="form-label">Prénom<span style="color: red;">*</span></label>
                       <input type="text" name="prenom" value="{{ $items->prenom }}" class="form-control" id="prenom">
                     </div>
                     <div class="col-6">
                       <label for="inputPassword4" class="form-label">Téléphone<span style="color: red;">*</span></label>
                       <input type="text" name="telephone" value="{{ $items->telephone }}" class="form-control"  id="telephone_{{ $items->id}}" onload="setTelephoneMask('telephone_{{ $items->id}}');"  onkeypress="return /[0-9]/i.test(event.key)">
                     </div>
                     <div class="col-6">
                       <label for="inputAddress"  class="form-label">Zone voulue<span style="color: red;">*</span></label>
                       <input type="text" name="zone" value="{{ $items->zone_voulu }}" class="form-control" id="zone">
                     </div>
                   <div class="col-6">
                     <label for="inputAddress"  class="form-label">Superficie (m²)<span style="color: red;">*</span></label>
                     <input type="text" name="superficie" value="{{ $items->superficie }}" class="form-control" id="superficie" onkeypress="return /[0-9]/i.test(event.key)">
                   </div>
                   <div class="col-6">
                     <label for="inputAddress"  class="form-label">Budget<span style="color: red;">*</span></label>
                     <input type="text" name="budget" value="{{ $items->budget }}" class="form-control" id="budget" onkeypress="return /[0-9]/i.test(event.key)">
                   </div>
                 </div>
                 <div class="modal-footer">
                   <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
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

        $(document).on('keyup','#budget',function(){Sepatateur_Milliers('#budget');});
      
         $('#budget').on('change keyup', function() {
          // Remove invalid characters
          var sanitized = $(this).val().replace(/[^0-9]/g, '');
          $(this).val(sanitized);
        });
      
      
        $(document).ready(function() {
            setTelephoneMask('ids');
        });
      
         function setTelephoneMask(telephones) {
      
          let ttt = document.getElementById(telephones);
      
            jQuery('#tt').inputmask({
            "mask": "99 99 99 99"
            })
      
          }
      
         
      
          
      
      
        jQuery("#telephone").inputmask({
          "mask": "99 99 99 99"
        })
        
      
        function printErrorMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(value);
            });
        }
      
      
        function save_client() {
      
            var data = new FormData();
      
            //Form data
            var form_data = $('#formulaire').serializeArray();
            $.each(form_data, function (key, input) {
                data.append(input.name, input.value);
            });
      
      
      
            $.ajax({
                url: "{{ route('store_client') }}",
                method: "POST",
                processData: false,
                contentType: false,
                data: data,
                beforeSend: function(data) {
                    $("#AjouerClient button#close").prop("disabled", true);
                    $("#AjouerClient button#valider").prop("disabled", true);
                     $("#AjouerClient button#valider").html('<i class="text-center fa fa-spinner fa-pulse fa-1x fa-fw ml-2">En cours...</i>');
                    
                },
                success: function(data) {
      
      
                    $("#AjouerClient button#close").prop("disabled", false);
                    $("#AjouerClient button#valider").prop("disabled", false);
                    $("#AjouerClient button#valider").html('Enregistrer');
      
      
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                    }
                    try {
                        if (data.status) {
                         // rempliretableau();
      
                           // alert(data.message);
                           // $("#AjouerClient div#afficher").html(data.message)
                          display_message("Super !!",data.message,"success","btn btn-primary");
      
                            $("#AjouerClient form#formulaire")[0].reset();
                        } else {
                            //$("#AjouerClient div#afficher").html(data.message)
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