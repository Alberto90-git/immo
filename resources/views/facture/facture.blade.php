@extends('layouts.template')


@section('content')

    @section('title')
    <title>Gestion des paiements</title>
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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion des paiements</h4>
    
    @can('ajoute-locataire')
        <div class="col-md-6">
        <div class="demo-inline-spacing">
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal"
            data-bs-target="#AjouterLoyer">
            <span class="bx bx-plus"></span>
            </button>
        </div>
        </div><br/>
    @endcan

    <div class="modal fade" id="AjouterLoyer" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalCenterTitle">Paiement du loyer</h5>
            <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form class="row g-3" method="post" action="javascript:save_paiement();" 
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


                <div class="col-12">
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

               <div class="col-4">
                 <label for="inputNanme4" class="form-label">Choisir une chambre<span style="color: red;">*</span></label>
                 <select class="form-select @error('numero_chambre') is-invalid @enderror" name="numero_chambre" id="numero_chambre" aria-label="Default select example">
                     <option selected disabled value="">Choisir une chambre</option>
                 </select>
                   <span class="invalid-feedback numero_chambre_err" role="alert">
                       </span>
               </div>


               <div class="col-4">
                <label for="inputNanme4" class="form-label">à payer<span style="color: red;">*</span></label>
                <input type="number" class="form-control @error('prix') is-invalid @enderror" id="sonPrix"  name="sonPrix" readonly>
                <span class="invalid-feedback prix_err" role="alert">
                </span>
              </div>

              <div class="col-4">
                <label for="inputNanme4" class="form-label">Date<span style="color: red;">*</span></label>
                <input type="datetime-local" name="date_paiement" class="form-control @error('date_paiement') is-invalid @enderror" id="date_paiement"
                min="<?= date('1970-m-d\T00:00:00'); ?>"  required>
                <span class="invalid-feedback date_paiement_err" role="alert">
                </span>
              </div>


               <div class="col-12">
                 <label for="inputNanme4" class="form-label">Type chambre<span style="color: red;">*</span></label>
                 <input type="text" name="type_chambre" class="form-control @error('type_chambre') is-invalid @enderror" id="type_chambre_getData" readonly>
                 <span class="invalid-feedback type_chambre_err" role="alert">
                 </span>
               </div>

               

                <div class="col-6">
                 <label for="inputNanme4" class="form-label">Montant réçu<span style="color: red;">*</span></label>
                 <input type="text" name="montant" class="form-control @error('montant') is-invalid @enderror" id="montant" required>
                 <span class="invalid-feedback montant_err" role="alert">
                 </span>
               </div>

               <div class="col-6">
                 <label for="type_paiement" class="form-label">Type de paiement<span style="color: red;">*</span></label>
                 <select class="form-select @error('type_paiement') is-invalid @enderror" name="type_paiement" id="type_paiement" aria-label="Default select example" required="">
                     <option selected disabled value="">Type de paiement</option>
                     <option value="direct">Direct</option>
                     <option value="avance">Dans son avance</option>
                   <span class="invalid-feedback type_paiement_err" role="alert">
                       </span>
                     </select>
               </div>

               <div class="col-6">
                 <label for="locataire" class="form-label">Locataire<span style="color: red;">*</span></label>
                 <input type="text" class="form-control @error('locataire') is-invalid @enderror" id="myLocataire"  name="locataire" readonly disabled>
                 <span class="invalid-feedback locataire_err" role="alert">
                 </span>
               </div>


               <div class="col-6">
                 <label for="inputNanme4" class="form-label">Mois<span style="color: red;">*</span></label>
                 <select class="form-select @error('mois') is-invalid @enderror" name="mois" id="mois" aria-label="Default select example" required="">
                     <option selected disabled value="">Mois</option>
                     <option value="Janvier">Janvier</option>
                     <option value="Février">Février</option>
                     <option value="Mars">Mars</option>
                     <option value="Avril">Avril</option>
                     <option value="Mai">Mai</option>
                     <option value="Juin">Juin</option>
                     <option value="Juillet">Juillet</option>
                     <option value="Août">Août</option>
                     <option value="Septembre">Septembre</option>
                     <option value="Octobre">Octobre</option>
                     <option value="Novembre">Novembre</option>
                     <option value="Décembre">Décembre</option>
                 </select>
                   <span class="invalid-feedback mois_err" role="alert">
                       </span>
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
      <h5 class="card-header text-center">Liste des loyers pour le mois de {{  nom_mois(date('m') - 1) }}</h5>
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
                @can('Consulter-paiement')

                    @if(isset($allFacture))
                    @foreach($allFacture as $item)
                        <tr>
                            <td>{{ get_annexee_name($item->idannexe_ref) }}</td>
                            <th scope="row">{{ $item->nom_maison }}</th>
                            <td>{{ $item->numero_chambre }}</td>
                            <td>{{ $item->nom }}  {{ $item->prenom }}</td>
                            <td>
                            @can('modify-paiement')
                              <a class="btn rounded-pill btn-primary" 
                              title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                                  <i class="bx bx-edit-alt me-1"></i>
                              </a>
                            @endcan
                            
                            @can('delete-paiement')
                                <a class="btn rounded-pill btn-danger" 
                                    title="Supprimer" href="#" data-bs-toggle="modal" data-bs-target="#supprimer{{$loop->iteration}}">
                                    <i class="bx bx-trash me-1"></i>
                                </a>
                            @endcan
                           
                            @can('download-recu-location')
                                <a class="btn rounded-pill btn-success" 
                                     title="Télcharge réçu" href="{{ route('telecharge2',['id' =>  $item->id ]) }}">
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
                                    <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Montant:   </label>{{ number_format($item->montant ,"0",",",".") }} XOF
                                    </h3>
                                    <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Date paiement:   </label>{{ Carbon\Carbon::parse($item->date_paiement)->format('d/m/Y') }}
                                    </h3>
                                    <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Mois:   </label>{{ $item->mois }} Mois
                                    </h3>
                                    <h3 class="list-group-item"><label class="badge rounded-pill  bg-primary">Type paiement:  </label>
                                      @if($item->type_paiement == 'direct')
                                          Direct
                                      @else
                                          Dans son avance
                                      @endif
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

    @foreach($allFacture as $items)
      <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalCenterTitle">Supprimer un locataire</h5>
                <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
            <div class="modal-body">
              <form class="row g-3" method="post" action="{{ route('destroy_facture') }}">
                      @csrf
                  Voulez-vous vraiment supprimer cette ligne ? 
                  <input type="hidden" name="facture_id_destroy" class="form-control" id="id" value="{{ $items->id }}">
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
                <form class="row g-3" method="post" action="{{ route('update_facture') }}">
                    @csrf
                   
                     <div class="alert-primary bg-primary text-light" id="afficher"></div>
     
                     <input type="hidden" name="facture_id2" class="form-control" id="id" value="{{ $items->id }}">
     
                    <input type="hidden" name="maison_id2" class="form-control" id="id" value="{{ $items->maison_id }}">
     
                    <input type="hidden" name="chambre_id2" class="form-control" id="id" value="{{ $items->chambre_id }}">
                    <input type="hidden" name="locataire_id2" class="form-control" id="id" value="{{ $items->locataire_id }}">
     
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
                       <input type="text" name="numero_chambre" value="{{$items->nom_maison}}" class="form-control @error('numero_chambre') is-invalid @enderror" id="numero_chambre" readonly disabled>
                       <span class="invalid-feedback numero_chambre_err" role="alert">
                       </span>
                     </div>
     
                     <div class="col-5">
                       <label for="inputNanme4" class="form-label">N° de la chambre<span style="color: red;">*</span></label>
                       <input type="text" name="numero_chambre" value="{{ $items->numero_chambre }}" class="form-control @error('numero_chambre') is-invalid @enderror" id="numero_chambre" readonly disabled>
                       <span class="invalid-feedback numero_chambre_err" role="alert">
                       </span>
                     </div>
     
     
                     <div class="col-7">
                       <label for="inputNanme4" class="form-label">Type chambre<span style="color: red;">*</span></label>
                       <input type="text" value="{{ $items->type_chambre }}" name="type_chambre" class="form-control @error('type_chambre') is-invalid @enderror" id="type_chambre_getData" readonly disabled>
                       <span class="invalid-feedback type_chambre_err" role="alert">
                       </span>
                     </div>
     
                     <div class="col-6">
                       <label for="inputNanme4" class="form-label">Montant à payer<span style="color: red;">*</span></label>
                       <input type="text" class="form-control @error('prix') is-invalid @enderror" id="sonPrix"  value="{{ $items->montant }}" name="sonPrix" readonly disabled>
                       <span class="invalid-feedback prix_err" role="alert">
                       </span>
                     </div>
     
                      <div class="col-6">
                       <label for="inputNanme4" class="form-label">Montant réçu<span style="color: red;">*</span></label>
                       <input type="text" value="{{ $items->montant }}" name="montant" class="form-control @error('montant') is-invalid @enderror" onkeypress="return /[0-9]/i.test(event.key)"  id="montant" required>
                       <span class="invalid-feedback montant_err" role="alert">
                       </span>
                     </div>
     
                     <div class="col-6">
                       <label for="type_paiement" class="form-label">Type de paiement<span style="color: red;">*</span></label>
                       <select class="form-select @error('type_paiement') is-invalid @enderror" name="type_paiement" id="type_paiement" aria-label="Default select example" required="">
                           <option selected disabled value="">Type de paiement</option>
                           <option value="direct" {{$items->type_paiement == 'direct' ? 'selected':''}}>Direct</option>
                           <option value="avance" {{$items->type_paiement == 'avance' ? 'selected':''}}>Dans son avance</option>
                         <span class="invalid-feedback type_paiement_err" role="alert">
                             </span>
                           </select>
                     </div>
     
                     <div class="col-6">
                       <label for="locataire" class="form-label">Locataire<span style="color: red;">*</span></label>
                       <input type="text" class="form-control @error('locataire') is-invalid @enderror" id="myLocataire" value="{{ $items->nom }} {{ $items->prenom }}"  name="locataire" readonly disabled>
                       <span class="invalid-feedback locataire_err" role="alert">
                       </span>
                     </div>
     
     
                     <div class="col-6">
                       <label for="inputNanme4" class="form-label">Mois<span style="color: red;">*</span></label>
                       <select class="form-select @error('mois') is-invalid @enderror" name="mois" id="mois" aria-label="Default select example" required="">
                           <option selected disabled value="">Mois</option>
                           <option value="Janvier" {{$items->mois == 'Janvier' ? 'selected':''}}>Janvier</option>
                           <option value="Février" {{$items->mois == 'Février' ? 'selected':''}}>Février</option>
                           <option value="Mars" {{$items->mois == 'Mars' ? 'selected':''}}>Mars</option>
                           <option value="Avril" {{$items->mois == 'Avril' ? 'selected':''}}>Avril</option>
                           <option value="Mai" {{$items->mois == 'Mai' ? 'selected':''}}>Mai</option>
                           <option value="Juin" {{$items->mois == 'Juin' ? 'selected':''}}>Juin</option>
                           <option value="Juillet" {{$items->mois == 'Juillet' ? 'selected':''}}>Juillet</option>
                           <option value="Août" {{$items->mois == 'Août' ? 'selected':''}}>Août</option>
                           <option value="Septembre" {{$items->mois == 'Septembre' ? 'selected':''}}>Septembre</option>
                           <option value="Octobre" {{$items->mois == 'Octobre' ? 'selected':''}}>Octobre</option>
                           <option value="Novembre" {{$items->mois == 'Novembre' ? 'selected':''}}>Novembre</option>
                           <option value="Décembre" {{$items->mois == 'Décembre' ? 'selected':''}}>Décembre</option>
                       </select>
                         <span class="invalid-feedback mois_err" role="alert">
                             </span>
                     </div>
     
                     
                     <div class="col-6">
                       <label for="inputNanme4" class="form-label">Date de paiement<span style="color: red;">*</span></label>
                       <input type="datetime-local" value="{{ $items->date_paiement }}" name="date_paiement" class="form-control @error('date_paiement') is-invalid @enderror" id="date_paiement" required  min="<?= date('1970-m-d\T00:00:00'); ?>">
                       <span class="invalid-feedback date_paiement_err" role="alert">
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
            var max_chars = 12;
            if (element.value.length > max_chars) {
                element.value = element.value.substr(0, max_chars);
            }
      
        }
      
        $(document).on('keyup','#montant',function(){Sepatateur_Milliers('#montant');});
      
         $('#montant').on('change keyup', function() {
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
                  url: '{{ url('gerer-facture/numero_chambre') }}',
                  data: {idMaison:nom_maison2},
                  type: 'GET',
                  cache: false,
                  dataType: 'json',
                  success: function (data) {
                      $('#numero_chambre').html(data.list_chambre);
      
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
                  url: '{{ url('gerer-facture/type_chambre') }}',
                  data: {numero_chambre_got:numero_chambre_go},
                  type: 'GET',
                  cache: false,
                  dataType: 'json',
                  success: function (data) {
                      $('#type_chambre_getData').val(data.type_chambres_get);
                      $('#sonPrix').val(data.sonPrix);
                      $('#myLocataire').val(data.sonLocataire);
      
                  },
                  error:function(data)
                  {
                    
                  },
             }); 
          }
        });
      
      
      
      
      
        function printErrorMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(value);
            });
        }
      
        function save_paiement() {
      
            var data = new FormData();
      
            //Form data
            var form_data = $('#formulaire').serializeArray();
            $.each(form_data, function (key, input) {
                data.append(input.name, input.value);
            });
      
            $.ajax({
                url: "{{ route('store_facture') }}",
                method: "POST",
                processData: false,
                contentType: false,
                data: data,
                beforeSend: function(data) {
                    $("#AjouterLoyer button#close").prop("disabled", true);
                    $("#AjouterLoyer button#valider").prop("disabled", true);
                    $("#AjouterLoyer button#valider").html('<i class="text-center fa fa-spinner fa-pulse fa-1x fa-fw ml-2">En cours...</i>');
                },
                success: function(data) {
      
      
                    $("#AjouterLoyer button#close").prop("disabled", false);
                    $("#AjouterLoyer button#valider").prop("disabled", false);
                    $("#AjouterLoyer button#valider").html('Enregistrer');
      
      
                    if (!$.isEmptyObject(data.error)) {
                        printErrorMsg(data.error);
                    }
                    try {
                        if (data.status) {
                         // rempliretableau();
      
                           // alert(data.message);
                            //$("#AjouterLoyer div#afficher").html(data.message)
                          display_message("Super !!",data.message,"success","btn btn-primary");
                            
      
                            $("#AjouterLoyer form#formulaire")[0].reset();
                        } else {
                           // $("#AjouterLoyer div#afficher").html(data.message)
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
      
      
      
         function Sepatateur_Milliers(param)
          {
      
              var valSaisie=$(param).val().trim().replace(/\s/g,'');
              //alert(valSaisie);
              if($.isNumeric(valSaisie))
                {
                  //.trim();
                  if (valSaisie==0)
                   {
                     $(param).val(valSaisie);
                   }
                   else
                   {
                     var str= valSaisie.toString().replace(/\s/g,'');
                     var n= str.length;
                     if (n < 4)
                      {
                        //alert(n);//return valSaisie;
                      }
                     else
                      {
                        //$('#montant_prime').val().replace(/\s/g,'');
                       $(param).val(((n % 3) ? str.substr(0, n % 3) + ' ' : '') + str.substr(n % 3).match(new RegExp('[0-9]{3}', 'g')).join(' '));
                             //);
                      }
                    }
                }
                else
                {
                    //alert("Ce champ nécessite une valeur entière");
                    //$(param).removeClass('form-control').addClass('form-control has-warning');
                    $(param).val().toString().replace(/\s/g,'');
                    return false;
                }
            }
        
      </script>
@endsection