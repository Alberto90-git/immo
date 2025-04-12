@extends('layouts.template')


@section('content')
    @section('title')
    <title>Gestion publicité</title>
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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion publicité</h4>

    <div class="col-md-6">
      <div class="demo-inline-spacing">
        <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal"
          data-bs-target="#AjouerPub">
          <span class="bx bx-plus"></span>
        </button>
      </div>
    </div><br/>


    <div class="modal fade" id="AjouerPub" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalCenterTitle">Ajouter une publicité</h5>
            <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" method="post" action="javascript:save_pub();" id="formulaire">
                @csrf
 
 
                 @can('Is_admin')
                   @if(Auth::user()->type_compte != 'Particulier')
                   <div class="col-6">
                     <label for="inputNanme4" class="form-label">Choisir une agence<span style="color: red;">*</span></label>
                     <select required="" class="form-select @error('annexe') is-invalid @enderror" name="annexe" id="annexe" aria-label="Default select example">
                         <option selected disabled value="">Choisir une agence</option>
                         @if(Session::get('anne_data') != null)
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
                   <label for="inputNanme4" class="form-label">Adresse du bien immobilier<span style="color: red;">*</span></label>
                   <input type="text" name="adresse" class="form-control" id="adresse" required="">
                   <span class="invalid-feedback adresse_err" role="alert">
                         </span>
                 </div>
                 <div class="col-6">
                   <label for="inputEmail4" class="form-label">Superficie (m²)<span style="color: red;">*</span></label>
                   <input type="text"  name="superficie" class="form-control" id="superficie" onkeypress="return /[0-9]/i.test(event.key)" required="">
                   <span class="invalid-feedback superficie_err" role="alert"></span>
                 </div>
                 <div class="col-6">
                   <label for="inputPassword4" class="form-label">Prix de vente<span style="color: red;">*</span></label>
                   <input type="text"  name="prix_vente" class="form-control" id="prix_vente" onkeypress="return /[0-9]/i.test(event.key)" required="">
                   <span class="invalid-feedback prix_vente_err" role="alert">
                         </span>
                 </div>
 
                 <div class="col-6">
                   <label for="inputPassword4" class="form-label">Numéro à contacter<span style="color: red;">*</span></label>
                   <input type="tel"  name="telephone" class="form-control" id="telephone" required="">
                   <span class="invalid-feedback telephone_err" role="alert">
                         </span>
                 </div>
                 <div class="col-12">
                   <label for="inputAddress" class="form-label">Description du bien<span style="color: red;">*</span></label>
                     <textarea class="form-control" name="description"  id="description" rows="5" required></textarea>
                   <span class="invalid-feedback description_err" role="alert"></span>
                 </div>
 
                 <div class="col-12">
                     <label for="inputAddress" class="form-label">Image<span style="color: red;">*</span></label>
                       <input type="file" name="image" class="form-control" id="image">
                       <span class="invalid-feedback image_err" role="alert"></span>
                 </div>
               <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" onclick="(this);" id="close" data-bs-dismiss="modal">Fermer</button>
                 <button  class="btn btn-primary" id="valider"><span class="fa fa-save" id="a"></span><span id="s">Enregistrer</span></button>
               </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Hoverable Table rows -->
    <div class="card">
      <h5 class="card-header text-center">Liste des publicités</h5>
      <div class="table-responsive text-nowrap">
        <table id="example" class="table table-hover border-primary" style="width:100%" >
          <thead>
            <tr>
                <th scope="col">Agence</th>
                <th scope="col">Adresse du bien</th>
                <th scope="col">Superficie</th>
                <th scope="col">Contact</th>
                <th scope="col">Prix</th>
                <th scope="col">Actions</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @can('Consulter-proprietaire')
                @if(isset($allpub))
                 @foreach($allpub as $item)
              <tr>
                <td>{{ get_annexee_name($item->idannexe_ref) }}</td>
                <th scope="row">{{ $item->localisation }}</th>
                <td>{{ $item->Superficie }} m²</td>
                <td>{{ $item->telephone }}</td>
                <td>{{ number_format($item->price ,"0",",",".") }} XOF</td>
                <td>
                   @can('modify-proprietaire')
                    @if($item->status == '')
                      <a class="btn rounded-pill btn-primary" 
                      title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                        <i class="bx bx-edit-alt me-1"></i>
                       </a>
                     @endif
                   @endcan
                
                   @can('supprimer-parcelle')
                    @if($item->status == '')
                      <a class="btn rounded-pill btn-danger"
                      title="Supprimer" href="#" data-bs-toggle="modal" data-bs-target="#supprimer{{$loop->iteration}}">
                      <i class="bx bx-trash me-1"></i>
                       </a>
                    @endif
                   @endcan
                
                   <button type="button" class="btn rounded-pill btn-primary" data-bs-toggle="modal" data-bs-target="#disablebackdrop{{ $loop->iteration }}">
                    <i class="bx bx-zoom-in me-1"></i>
                  </button>
                </td>
              </tr>


              <div class="modal fade" id="disablebackdrop{{ $loop->iteration }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg"> <!-- Utilisation de modal-lg pour élargir la modale sur les grands écrans -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center w-100">Description</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Description avec défilement si le texte est long -->
                            <div class="mb-3 text-break" style="max-height: 300px; overflow-y: auto;">
                              {{ $item->description }}
                          </div>
                            <!-- Image responsive -->
                            <div class="text-center">
                                <img src="{{ asset('http://127.0.0.1/ImmobilierApk/storage/app/public/'.$item->image_url) }}" 
                                     alt="Product Image" class="img-fluid rounded" style="max-width: 100%; height: auto;">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        </div>
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

    @foreach($allpub as $items)
      <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Suppression</h5>
                <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
                <form class="row g-3" method="post" action="{{ route('destroy_pub') }}">
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


      <div class="modal fade" id="modifier{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalCenterTitle">Modification</h5>
              <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" action="{{ route('update_pub') }}"   enctype="multipart/form-data">
                    @csrf
    
                    <input type="hidden" name="id" class="form-control" id="id" value="{{ $items->id }}">
    
    
                    <div class="alert-primary bg-primary text-light" id="afficher"></div>
    
                    @can('Is_admin')
                      @if(Auth::user()->type_compte != 'Particulier')
                      <div class="col-6">
                      <label for="inputNanme4" class="form-label">Choisir une agence<span style="color: red;">*</span></label>
                      <select required="" class="form-select @error('annexe') is-invalid @enderror" name="annexe" id="annexe" aria-label="Default select example">
                          <option selected disabled value="">Choisir une agence</option>
                           @if(Session::get('anne_data') != " ")
                            @foreach(Session::get('anne_data') as $terme)
                              <option  value="{{$terme->idannexes}}"    {{$items->idannexe_ref == $terme->idannexes ? 'selected':''}}>{{ $terme->designation }}</option>
                            @endforeach
                           @endif 
                        </select>
                        @error('annexe')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                        @enderror
                       </div>
                       @endif
                    @endcan
                  
                    <div class="col-6">
                      <label for="inputNanme4" class="form-label">Adresse du bien immobilier<span style="color: red;">*</span></label>
                      <input type="text" name="adresse" class="form-control @error('adresse') is-invalid @enderror" id="adresse" value="{{ $items->localisation }}" required="">
                      @error('adresse')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="col-6">
                      <label for="inputEmail4" class="form-label">Superficie (m²)<span style="color: red;">*</span></label>
                      <input type="text"  onkeypress="return /[0-9]/i.test(event.key)"  name="superficie" class="form-control @error('superficie') is-invalid @enderror" id="superficie" value="{{ $items->Superficie }}" required="">
                      @error('superficie')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>

                    <div class="col-6">
                      <label for="inputPassword4" class="form-label">Prix de vente<span style="color: red;">*</span></label>
                      <input type="text"  name="prix_vente" class="form-control @error('prix_vente') is-invalid @enderror" id="prix_vente"  value="{{ $items->price }}"  onkeypress="return /[0-9]/i.test(event.key)" required="">
                      @error('prix_vente')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
    
                    <div class="col-6">
                      <label for="inputPassword4" class="form-label">Numéro à contacter<span style="color: red;">*</span></label>
                      <input type="tel"  name="telephone" class="form-control @error('telephone') is-invalid @enderror" id="telephone" value="{{ $items->telephone }}" required="">
                      @error('telephone')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                    <div class="col-12">
                      <label for="inputAddress" class="form-label">Description du bien<span style="color: red;">*</span></label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description"  id="description" rows="5" required>{{ $items->description }}</textarea>
                        @error('description')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
    
                    <div class="col-12">
                        <label for="inputAddress" class="form-label">Image</label>
                          <input type="file"  name="image_up" class="form-control @error('image_up') is-invalid @enderror" id="image_up" required>
                          @error('image_up')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                          @enderror
                    </div>
                    </div>
    
                    <input type="hidden" name="image_ancien" class="form-control" id="image_ancien"  value="{{ $items->image_url }}">
    
    
                    {{-- <div class="col-12">
                      <img src="{{ asset('http://127.0.0.1/ImmobilierApk/storage/app/public/'.$items->image_url) }}" alt="Product Image" width="150">
                    </div> --}}
    
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" onclick="(this);" id="close" data-bs-dismiss="modal">Fermer</button>
                      <button  type="submit" class="btn btn-primary" ><span class="fa fa-save"></span><span >Enregistrer</span></button>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    @endforeach
    @endif

        
    <script>
  


        jQuery("#telephone").inputmask({
          "mask": "99 99 99 99"
        })
        
      
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
      
        function save_pub() {
      
            var data = new FormData();
      
            //Form data
            var form_data = $('#formulaire').serializeArray();
            $.each(form_data, function (key, input) {
                data.append(input.name, input.value);
            });
      
            //File data
            data.append("image", $('input[name="image"]')[0].files[0]);
      
      
      
            $.ajax({
                url: "{{ route('store_pub') }}",
                method: "POST",
                processData: false,
                contentType: false,
                data: data,
                beforeSend: function(data) {
                    $("#AjouerPub button#close").prop("disabled", true);
                    $("#AjouerPub button#valider").prop("disabled", true);
                    $("#AjouerPub button#valider").html('<i class="text-center fa fa-spinner fa-pulse fa-1x fa-fw ml-2">En cours...</i>');
                },
                success: function(data) {
      
      
                    $("#AjouerPub button#close").prop("disabled", false);
                    $("#AjouerPub button#valider").prop("disabled", false);
                    $("#AjouerPub button#valider").html('Enregistrer');
      
      
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                    }
                    try {
                        if (data.status) {
                         // rempliretableau();
      
                           // alert(data.message);
                           // $("#AjouerPub div#afficher").html(data.message)
                          display_message("Super !!",data.message,"success","btn btn-primary");
      
      
                            $("#AjouerPub form#formulaire")[0].reset();
                        } else {
                            //$("#AjouerPub div#afficher").html(data.message)
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