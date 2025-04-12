@extends('layouts.template')


@section('content')

    @section('title')
    <title>Gestion locataire</title>
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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion locataire</h4>
    
    @can('ajoute-locataire')
        <div class="col-md-6">
        <div class="demo-inline-spacing">
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal"
            data-bs-target="#AjouterLocataire">
            <span class="bx bx-plus"></span>
            </button>
        </div>
        </div><br/>
    @endcan

    <div class="modal fade" id="AjouterLocataire" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalCenterTitle">Ajouter un locataire</h5>
            <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" method="post" action="javascript:save_locataire();" id="formulaire">
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
                  <label for="inputNanme4" class="form-label">Choisir une maison<span style="color: red;">*</span></label>
                  <select required="" class="form-select @error('nom_maison') is-invalid @enderror" name= "nom_maison" id="nom_maison" aria-label="Default select example">
                      <option selected disabled value="">Choisir une maison</option>
                      @if(isset($allMaison))
                        @foreach($allMaison as $terme)
                          <option  value="{{$terme->id}}">{{$terme->nom_maison}}</option>
                        @endforeach
                      @endif  
                    </select>
                    <span class="invalid-feedback nom_maison_err" role="alert"></span>
                </div>

              

                <div class="col-md-6">
                    <label for="inputEmail5" class="form-label">Nom locataire<span style="color: red;">*</span></label>
                    <input type="text" name="nom_locataire" id="nom_locataire" 
                    class="form-control @error('nom_locataire') is-invalid @enderror" required>
                    <span class="invalid-feedback nom_locataire_err" role="alert">
                    </span>
                </div>

              

              <div class="col-6">
                <label for="inputNanme4" class="form-label">Choisir une chambre<span style="color: red;">*</span></label>
                <select class="form-select @error('numero_chambre') is-invalid @enderror" name="numero_chambre" id="numero_chambre" aria-label="Default select example">
                    <option selected disabled value="">Choisir une chambre</option>
                </select>
                  <span class="invalid-feedback numero_chambre_err" role="alert">
                      </span>
              </div>
              

              <div class="col-md-6">
                <label for="inputPassword5" class="form-label">Prénom locataire<span style="color: red;">*</span></label>
                <input type="text" name="prenom_locataire" id="prenom_locataire" class="form-control @error('prenom_locataire') is-invalid @enderror" required>
                <span class="invalid-feedback prenom_locataire_err" role="alert">
                </span>
              </div>

              <div class="col-md-6">
                <label for="inputPassword5" class="form-label">Téléphone<span style="color: red;">*</span></label>
                <input type="text" name="telephone" id="telephone" class="form-control @error('telephone') is-invalid @enderror" required min="1">
                <span class="invalid-feedback telephone_err" role="alert">
                </span>
              </div>


               <div class="col-md-6">
                <label for="inputEmail5" class="form-label">Profession<span style="color: red;">*</span></label>
                <input type="text" name="profession" id="profession" class="form-control @error('profession') is-invalid @enderror" required>
                <span class="invalid-feedback profession_err" role="alert">
                </span>
              </div>

              <div class="col-6">
                <label for="inputNanme4" class="form-label">Type chambre<span style="color: red;">*</span></label>
                <input type="text" name="type_chambre" class="form-control @error('type_chambre') is-invalid @enderror" id="type_chambre_getData" readonly disabled>
                <span class="invalid-feedback type_chambre_err" role="alert">
                </span>
              </div>


              <div class="col-3">
                <label for="inputNanme4" class="form-label">Caution eau<span style="color: red;">*</span></label>
                <input type="text" name="caution_eau" class="form-control @error('caution_eau') is-invalid @enderror" id="caution_eau" required>
                <span class="invalid-feedback caution_eau_err" role="alert">
                </span>
              </div>

               <div class="col-md-3">
                <label for="inputEmail5" class="form-label"> életricité<span style="color: red;">*</span></label>
                <input type="text" name="caution_courant" id="caution_courant" class="form-control @error('caution_courant') is-invalid @enderror" required>
                <span class="invalid-feedback caution_courant_err" role="alert">
                </span>
              </div>

              <div class="col-6">
                <label for="inputNanme4" class="form-label">Prix / mois<span style="color: red;">*</span></label>
                <input type="text" name="prix_mois" class="form-control @error('prix_mois') is-invalid @enderror" id="prix_mois" readonly>
                <span class="invalid-feedback prix_mois_err" role="alert">
                </span>
              </div>


              <div class="col-3">
                <label for="inputNanme4" class="form-label">Caution pour<span style="color: red;">*</span></label>
                <select class="form-select @error('nombre_avance') is-invalid @enderror" name="nombre_avance" id="nombre_avance" aria-label="Default select example" required="">
                    <option selected disabled value="">Caution sur avance</option>
                    <option value="2">2 Mois</option>
                    <option value="3">3 Mois</option>
                    <option value="4">4 Mois</option>
                    <option value="5">5 Mois</option>
                    <option value="6">6 Mois</option>
                    <option value="12">12 Mois</option>
                </select>
                  <span class="invalid-feedback nombre_avance_err" role="alert">
                      </span>
              </div>

              <div class="col-3">
                <label for="inputNanme4" class="form-label">Date d'entrée<span style="color: red;">*</span></label>
                <input type="date" name="date_entre" class="form-control @error('date_entre') is-invalid @enderror"
                  min="<?= date('1970-m-d'); ?>"  id="date_entre" required>
                <span class="invalid-feedback date_entre_err" role="alert">
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
      <h5 class="card-header text-center">Liste des locataires</h5>
      <div class="table-responsive text-nowrap">
        <table id="example" class="table table-hover border-primary" style="width:100%" >
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
                    <td>{{ $item->nom }}  {{ $item->prenom }}</td>
                    <td>
                    @can('modify-locataire')
                      <a class="btn rounded-pill btn-primary" 
                      title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                          <i class="bx bx-edit-alt me-1"></i>
                       </a>
                    @endcan
                   
                    @can('delete-locataire')
                      <a class="btn rounded-pill btn-danger" 
                      title="Sortir un locataire" href="#" data-bs-toggle="modal" data-bs-target="#supprimer{{$loop->iteration}}">
                           <i class="bx bx-trash me-1"></i>
                       </a>
                    @endcan
                   
                    @can('download-recu-avance')
                      <a class="btn rounded-pill btn-success" 
                      title="Télcharge réçu" href="{{ route('telecharge',['id' =>  $item->id ]) }}">
                           <i class="bx bx-download me-1"></i>
                       </a>
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
                                  <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Téléphone:   </label>{{ $item->telephone }}
                                  </h3>
                                  <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Date d'entrée:   </label>{{ Carbon\Carbon::parse($item->date_entree)->format('d/m/Y') }}
                                  </h3>
                                  <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Caution sur avance:   </label>{{ $item->nombre_avance }} Mois
                                  </h3>
                                  <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Caution avance consommée:  </label>{{ $item->nombre_avance_consomme }} Mois
                                  </h3>
                                  <h3 class="list-group-item"> <label class="badge rounded-pill  bg-primary">Caution eau: </label>
                                    {{ number_format($item->caution_eau ,"0",",",".") }} XOF
                                  </h3>
                                  <h3 class="list-group-item"> <label class="badge rounded-pill  bg-primary">Caution électricité: </label>
                                    {{ number_format($item->caution_courant ,"0",",",".") }} XOF
                                  </h3>
                              </ul><!-- End Clean list group -->

                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fermer</button>
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

    @foreach($allLocataire as $items)
      <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Supprimer un locataire</h5>
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
              <form class="row g-3" method="post" action="{{ route('update_locataire') }}" 
                id="formulaireddsqd">
                @csrf
                
                <input type="hidden" name="locataire_id" class="form-control" id="id" value="{{ $items->id }}">

                
                  <div class="alert-primary bg-primary text-light" id="afficher"></div>

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
                    <label for="inputNanme4" class="form-label">Choisir une maison<span style="color: red;">*</span></label>
                    <input type="text" name="numero_chambre" value="{{$items->nom_maison}}" class="form-control @error('numero_chambre') is-invalid @enderror" id="numero_chambre" readonly disabled>
                    <span class="invalid-feedback numero_chambre_err" role="alert">
                    </span>
                  </div>


                  <div class="col-md-6">
                    <label for="inputEmail5" class="form-label">Nom locataire<span style="color: red;">*</span></label>
                    <input type="text" name="nom_locataire"  value="{{ $items->nom }}" id="nom_locataire" 
                    class="form-control @error('nom_locataire') is-invalid @enderror" required>
                    <span class="invalid-feedback nom_locataire_err" role="alert">
                    </span>
                  </div>


                  <div class="col-md-6">
                    <label for="inputPassword5" class="form-label">Prénom locataire<span style="color: red;">*</span></label>
                    <input type="text" name="prenom_locataire"  value="{{ $items->prenom }}" id="prenom_locataire" class="form-control @error('prenom_locataire') is-invalid @enderror" required>
                    <span class="invalid-feedback prenom_locataire_err" role="alert">
                    </span>
                  </div>

                  <div class="col-6">
                    <label for="inputNanme4" class="form-label">N° de la chambre<span style="color: red;">*</span></label>
                    <input type="text" name="numero_chambre" value="{{ $items->numero_chambre }}" class="form-control @error('numero_chambre') is-invalid @enderror" id="numero_chambre" readonly disabled>
                    <span class="invalid-feedback numero_chambre_err" role="alert">
                    </span>
                  </div>

                  <div class="col-md-6">
                    <label for="inputPassword5" class="form-label">Téléphone<span style="color: red;">*</span></label>
                    <input type="text" name="telephone"  value="{{ $items->telephone }}" 
                    onkeypress="return /[0-9]/i.test(event.key)"  id="telephone" class="form-control @error('telephone') is-invalid @enderror" required onkeydown="limit(this);" onkeyup="limit(this);">
                    <span class="invalid-feedback telephone_err" role="alert">
                    </span>
                  </div>


                  <div class="col-6">
                    <label for="inputNanme4" class="form-label">Type chambre<span style="color: red;">*</span></label>
                    <input type="text" name="type_chambre"  value="{{ $items->type_chambre }}" class="form-control @error('type_chambre') is-invalid @enderror" id="type_chambre_getData" readonly disabled>
                    <span class="invalid-feedback type_chambre_err" role="alert">
                    </span>
                  </div>

                  <div class="col-md-6">
                    <label for="inputEmail5" class="form-label">Profession<span style="color: red;">*</span></label>
                    <input type="text" name="profession"  value="{{ $items->profession }}" id="profession" class="form-control @error('profession') is-invalid @enderror" required>
                    <span class="invalid-feedback profession_err" role="alert">
                    </span>
                  </div>


                  


                  <div class="col-6">
                    <label for="inputNanme4" class="form-label">Caution eau<span style="color: red;">*</span></label>
                    <input type="text" name="caution_eau" value="{{ $items->caution_eau }}" class="form-control @error('caution_eau') is-invalid @enderror" id="caution_eau" onkeypress="return /[0-9]/i.test(event.key)">
                    <span class="invalid-feedback caution_eau_err" role="alert">
                    </span>
                  </div>

                  <div class="col-md-6">
                    <label for="inputEmail5" class="form-label">életricité<span style="color: red;">*</span></label>
                    <input type="text" name="caution_courant" value="{{ $items->caution_courant }}" id="caution_courant" class="form-control @error('caution_courant') is-invalid @enderror" onkeypress="return /[0-9]/i.test(event.key)">
                    <span class="invalid-feedback caution_courant_err" role="alert">
                    </span>
                  </div>



                  
                  <div class="col-3">
                    <label for="inputNanme4" class="form-label">Avance pour<span style="color: red;">*</span></label>
                    <select class="form-select @error('nombre_avance') is-invalid @enderror" name="nombre_avance" id="nombre_avance" aria-label="Default select example">
                        <option selected disabled>Nombre d'avance</option>
                        <option value="2" {{$items->nombre_avance == '2' ? 'selected':''}}>2 Mois</option>
                        <option value="3" {{$items->nombre_avance == '3' ? 'selected':''}}>3 Mois</option>
                        <option value="4" {{$items->nombre_avance == '4' ? 'selected':''}}>4 Mois</option>
                        <option value="5" {{$items->nombre_avance == '5' ? 'selected':''}}>5 Mois</option>
                        <option value="6" {{$items->nombre_avance == '6' ? 'selected':''}}>6 Mois</option>
                        <option value="12" {{$items->nombre_avance == '12' ? 'selected':''}}>12 Mois</option>
                    </select>
                      <span class="invalid-feedback nombre_avance_err" role="alert">
                          </span>
                  </div>

                  <div class="col-3">
                    <label for="inputNanme4" class="form-label">Date d'entrée<span style="color: red;">*</span></label>
                    <input type="date" name="date_entre"  value="{{ $items->date_entree }}"
                    class="form-control @error('date_entre') is-invalid @enderror" id="date_entre" required  min="<?= date('1970-m-d'); ?>">
                    <span class="invalid-feedback date_entre_err" role="alert">
                    </span>
                  </div>

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
            var max_chars = 8;
            if (element.value.length > max_chars) {
                element.value = element.value.substr(0, max_chars);
            }
        }
      
      
       $(document).on('keyup','#caution_eau',function(){Sepatateur_Milliers('#caution_eau');});
      
         $('#caution_eau').on('change keyup', function() {
          // Remove invalid characters
          var sanitized = $(this).val().replace(/[^0-9]/g, '');
          $(this).val(sanitized);
        });
      
      
         $(document).on('keyup','#caution_courant',function(){Sepatateur_Milliers('#caution_courant');});
      
         $('#caution_courant').on('change keyup', function() {
          // Remove invalid characters
          var sanitized = $(this).val().replace(/[^0-9]/g, '');
          $(this).val(sanitized);
        });
      
         $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
         }); 
      
      
        $('#nom_maison').on('change',function(e)
        {
          var nom_maison2 = $(this).val(); 
          //var idrefDemande = $('#idrefDemande').val(); 
          if(nom_maison2 === null )
          {
              alert('Merci de sélectionner une maison');
              return false;
          }
          else
          {
              return $.ajax
              ({
                  url: '{{ url('gerer-locataire/numero_chambre') }}',
                  data: {idMaison:nom_maison2},
                  type: 'GET',
                  cache: false,
                  dataType: 'json',
                  success: function (data) {
                      $('#numero_chambre').html(data.list_chambre);
      
                      //$('#date_valeur').val(data.vraiDate);
                      //$('#numero_compte_client').prop('disabled',data.valeur);
                      //$('#code_banque_client').prop('disabled',data.valeur_banque);
                  },
                  error:function(data)
                  {
                    
                  },
             }); 
          }
        });
      
      
        $('#numero_chambre').on('change',function(e)
        {
          var numero_chambre_go = $(this).val(); 
      
          if(numero_chambre_go === null )
          {
              alert('Merci de sélectionner un type de chambre');
              return false;
          }
          else
          {
              return $.ajax
              ({
                  url: '{{ url('gerer-locataire/type_chambre') }}',
                  data: {numero_chambre_got:numero_chambre_go},
                  type: 'GET',
                  cache: false,
                  dataType: 'json',
                  success: function (data) {
                      $('#type_chambre_getData').val(data.type_chambres_get);
      
                  },
                  error:function(data)
                  {
                    
                  },
             }); 
          }
        });
      
         $('#numero_chambre').on('change',function(e)
        {
          var numero_chambre_prix = $(this).val(); 
      
          if(numero_chambre_prix === null )
          {
              alert('Merci de sélectionner un type de chambre');
              return false;
          }
          else
          {
              return $.ajax
              ({
                  url: '{{ url('gerer-locataire/get_prix') }}',
                  data: {prixGot:numero_chambre_prix},
                  type: 'GET',
                  cache: false,
                  dataType: 'json',
                  success: function (data) {
                    $('#prix_mois').val(data.prixApayer);
                  },
                  error:function(data)
                  {
                    $('#prix_mois').val('Prix non défini');
                    
                  },
             }); 
          }
        });
      
      
        function printErrorMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(value);
            });
        }
      
        function save_locataire() {
      
            var data = new FormData();
      
            //Form data
            var form_data = $('#formulaire').serializeArray();
            $.each(form_data, function (key, input) {
                data.append(input.name, input.value);
            });
      
            $.ajax({
                url: "{{ route('store_locataire') }}",
                method: "POST",
                processData: false,
                contentType: false,
                data: data,
                beforeSend: function(data) {
                    $("#AjouterLocataire button#close").prop("disabled", true);
                    $("#AjouterLocataire button#valider").prop("disabled", true);
                    $("#AjouterLocataire button#valider").html('<i class="text-center fa fa-spinner fa-pulse fa-1x fa-fw ml-2">En cours...</i>');
                },
                success: function(data) {
      
      
                    $("#AjouterLocataire button#close").prop("disabled", false);
                    $("#AjouterLocataire button#valider").prop("disabled", false);
                    $("#AjouterLocataire button#valider").html('Enregistrer');
      
      
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                    }
                    try {
                        if (data.status) {
                         // rempliretableau();
      
                           // alert(data.message);
                           // $("#AjouterLocataire div#afficher").html(data.message)
                          display_message("Super !!",data.message,"success","btn btn-primary");
      
      
                            $("#AjouterLocataire form#formulaire")[0].reset();
                        } else {
                           // $("#AjouterLocataire div#afficher").html(data.message)
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