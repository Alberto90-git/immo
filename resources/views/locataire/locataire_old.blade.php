@extends('layouts.template')

@section('content')

<div class="pagetitle">
  <nav>
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
      <li class="breadcrumb-item active">Gestion des locataires</li>
    </ol>
  </nav>
</div>

<section class="section dashboard">
    @if(session('message'))

    <div class="alert alert-primary bg-primary text-light border-0 alert-dismissible fade show" role="alert">
	    {{ session('message')}}
	    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
	  </div>
	 @endif

	@if(session('error'))
    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show" role="alert">
	    {{ session('error')}}
	    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
	  </div>
	 @endif
             
    <div class="modal fade" id="AjouterLocataire" tabindex="-1" data-bs-backdrop="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #012970;">
          <h5 class="modal-title">Enregistrer un locataire</h5>
        </div>
        <div class="modal-body">

            <form class="row g-3" method="post" action="javascript:save_locataire();" 
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

                

                <div class="col-3">
                  <label for="inputNanme4" class="form-label">Choisir une chambre<span style="color: red;">*</span></label>
                  <select class="form-select @error('numero_chambre') is-invalid @enderror" name="numero_chambre" id="numero_chambre" aria-label="Default select example">
                      <option selected disabled value="">Choisir une chambre</option>
                  </select>
                    <span class="invalid-feedback numero_chambre_err" role="alert">
                        </span>
                </div>


                <div class="col-3">
                  <label for="inputNanme4" class="form-label">Prix / mois<span style="color: red;">*</span></label>
                  <input type="text" name="prix_mois" class="form-control @error('prix_mois') is-invalid @enderror" id="prix_mois" readonly>
                  <span class="invalid-feedback prix_mois_err" role="alert">
                  </span>
                </div>

                <div class="col-md-2">
                  <label for="inputPassword5" class="form-label">Téléphone<span style="color: red;">*</span></label>
                  <input type="text" name="telephone" id="telephone" class="form-control @error('telephone') is-invalid @enderror" required min="1">
                  <span class="invalid-feedback telephone_err" role="alert">
                  </span>
                </div>


                 <div class="col-md-4">
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
                  <label for="inputNanme4" class="form-label">Caution eau (XOF)<span style="color: red;">*</span></label>
                  <input type="text" name="caution_eau" class="form-control @error('caution_eau') is-invalid @enderror" id="caution_eau">
                  <span class="invalid-feedback caution_eau_err" role="alert">
                  </span>
                </div>

                 <div class="col-md-3">
                  <label for="inputEmail5" class="form-label">Caution életricité (XOF)<span style="color: red;">*</span></label>
                  <input type="text" name="caution_courant" id="caution_courant" class="form-control @error('caution_courant') is-invalid @enderror">
                  <span class="invalid-feedback caution_courant_err" role="alert">
                  </span>
                </div>

                <div class="col-md-6">
                  <label for="inputPassword5" class="form-label">Prénom locataire<span style="color: red;">*</span></label>
                  <input type="text" name="prenom_locataire" id="prenom_locataire" class="form-control @error('prenom_locataire') is-invalid @enderror" required>
                  <span class="invalid-feedback prenom_locataire_err" role="alert">
                  </span>
                </div>


                <div class="col-3">
                  <label for="inputNanme4" class="form-label">Caution sur avance<span style="color: red;">*</span></label>
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

	            </div>
    	        <div class="modal-footer">
    	          <button type="button" class="btn btn-secondary" onclick="(this);" id="close" data-bs-dismiss="modal">Fermer</button>
    	          <button  class="btn sbg1" id="valider"><span class="fa fa-save" id="a"></span><span id="s">Enregistrer</span></button>
                
    	        </div>
	         </form>
	      </div>
	    </div>
	  </div>

           

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          @can('ajoute-locataire')
            <button type="button" class="btn sbg1 rounded-pill ri-user-add-fill shadow" 
            data-bs-toggle="modal" data-bs-target="#AjouterLocataire">+</button> <br/><br/>
          @endcan

          <div class="card recent-sales overflow-auto">
            <div class="card-body">
              <h2 class="text-center"><u>Liste des locataires</u></h2>

              <table class="table datatable border-primary">
                <thead>
                  <tr>
                    <th scope="col">Agence</th>
                    <th scope="col">Maison</th>
                    <th scope="col">N° chambre</th>
                    <th scope="col">Locataire</th>
                    <th scope="col">Téléphone</th>
                    <th scope="col">Date entrée</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
              @can('Consulter-locataire')

                @if(isset($allLocataire))
                 @foreach($allLocataire as $item)
                  <tr>
                    <td>{{ get_annexee_name($item->idannexe_ref) }}</td>
                    <th scope="row">{{ $item->nom_maison }}</th>
                    <td>{{ $item->numero_chambre }} ({{ $item->type_chambre }})</td>
                    <td>{{ $item->nom }}  {{ $item->prenom }}</td>
                    <td>{{ $item->telephone }}</td>
                    <td>{{ Carbon\Carbon::parse($item->date_entree)->format('d/m/Y') }}</td>
                    <td>
                    @can('modify-locataire')
                      <a class="btn sbg1 shadow" 
                      title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                          <i class="ri-pencil-fill"></i>
                       </a>
                    @endcan
                    </td>

                    <td>
                    @can('delete-locataire')
                      <a class="btn btn-danger shadow"
                      title="Sortir un locataire" href="#" data-bs-toggle="modal" data-bs-target="#supprimer{{$loop->iteration}}">
                           <i class="ri-run-fill"></i>
                       </a>
                    @endcan
                    </td>
                    <td>
                    @can('download-recu-avance')
                      <a class="btn btn-success shadow"
                      title="Télcharge réçu" href="{{ route('telecharge',['id' =>  $item->id ]) }}">
                           <i class="ri-arrow-down-circle-fill"></i>
                       </a>
                      @endcan
                    </td>
                    <td>
                       <button type="button" class="btn sbg1 shadow" data-bs-toggle="modal" data-bs-target="#disablebackdrop{{ $loop->iteration }}">
                      <i class="ri-zoom-in-line"></i>
                    </button>
                    </td>
                    
                  </tr>

                  <div class="modal fade" id="disablebackdrop{{ $loop->iteration }}" tabindex="-1" data-bs-backdrop="false">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header text-white" style="background-color: #012970;">
                                            <h5 class="modal-title center">Détails des cautions</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <ul class="list-group list-group-flush">
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
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                   @endforeach
                @endcan
                </tbody>
              </table>
              <!-- End Table with stripped rows -->
            </div>
          </div>



        </div>
      </div>
    </section>

     <!--DEBUT MODAL SUPPRESSION -->
    @foreach($allLocataire as $items)
	  <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1">
	    <div class="modal-dialog modal-dialog-centered">
	      <div class="modal-content">
	        <div class="modal-header text-white" style="background-color: #012970;">
	          <h5 class="modal-title">Faire sortir un locataire</h5>
	        </div>
	        <div class="modal-body">

	              <form class="row g-3" method="post" action="{{ route('destroy_locataire') }}">
	              	 Voulez-vous vraiment faire sortir le locataire ?
	               @csrf
                  <input type="hidden" name="locataire_id" class="form-control" id="locataire_id" value="{{ $items->id }}">
	                
                  <input type="hidden" name="chambre_id" class="form-control" id="chambre_id" value="{{ $items->chambre_id }}">

                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                    <button type="submit" class="btn btn-danger" >Oui</button>
                  </div>
		         </form>
		      </div>
		    </div>
		  </div>
		   </div>
	   <!--FIN MODAL SUPPRESSION -->

      

	    <!--DEBUT MODAL MODIFICATION -->
	  <div class="modal fade" id="modifier{{$loop->iteration}}" tabindex="-1">
	    <div class="modal-dialog modal-lg">
	      <div class="modal-content">
	        <div class="modal-header text-white" style="background-color: #012970;">
	          <h5 class="modal-title">Modification d'un locataire</h5>
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

                <div class="col-md-2">
                  <label for="inputPassword5" class="form-label">Téléphone<span style="color: red;">*</span></label>
                  <input type="text" name="telephone"  value="{{ $items->telephone }}" 
                  onkeypress="return /[0-9]/i.test(event.key)"  id="telephone" class="form-control @error('telephone') is-invalid @enderror" required onkeydown="limit(this);" onkeyup="limit(this);">
                  <span class="invalid-feedback telephone_err" role="alert">
                  </span>
                </div>

                <div class="col-md-4">
                  <label for="inputEmail5" class="form-label">Profession<span style="color: red;">*</span></label>
                  <input type="text" name="profession"  value="{{ $items->profession }}" id="profession" class="form-control @error('profession') is-invalid @enderror" required>
                  <span class="invalid-feedback profession_err" role="alert">
                  </span>
                </div>


                <div class="col-6">
                  <label for="inputNanme4" class="form-label">Type chambre<span style="color: red;">*</span></label>
                  <input type="text" name="type_chambre"  value="{{ $items->type_chambre }}" class="form-control @error('type_chambre') is-invalid @enderror" id="type_chambre_getData" readonly disabled>
                  <span class="invalid-feedback type_chambre_err" role="alert">
                  </span>
                </div>


                 <div class="col-3">
                  <label for="inputNanme4" class="form-label">Caution eau (XOF)<span style="color: red;">*</span></label>
                  <input type="text" name="caution_eau" value="{{ $items->caution_eau }}" class="form-control @error('caution_eau') is-invalid @enderror" id="caution_eau" onkeypress="return /[0-9]/i.test(event.key)">
                  <span class="invalid-feedback caution_eau_err" role="alert">
                  </span>
                </div>

                 <div class="col-md-3">
                  <label for="inputEmail5" class="form-label">Caution életricité (XOF)<span style="color: red;">*</span></label>
                  <input type="text" name="caution_courant" value="{{ $items->caution_courant }}" id="caution_courant" class="form-control @error('caution_courant') is-invalid @enderror" onkeypress="return /[0-9]/i.test(event.key)">
                  <span class="invalid-feedback caution_courant_err" role="alert">
                  </span>
                </div>


                


                <div class="col-md-6">
                  <label for="inputEmail5" class="form-label">Nom locataire<span style="color: red;">*</span></label>
                  <input type="text" name="nom_locataire"  value="{{ $items->nom }}" id="nom_locataire" 
                  class="form-control @error('nom_locataire') is-invalid @enderror" required>
                  <span class="invalid-feedback nom_locataire_err" role="alert">
                  </span>
                </div>


                
                <div class="col-3">
                  <label for="inputNanme4" class="form-label">Nombre d'avance<span style="color: red;">*</span></label>
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
                <button type="button" class="btn btn-secondary" id="close" data-bs-dismiss="modal">Fermer</button>
                <button  class="btn sbg1" id="valider"><span class="fa fa-save" id="a"></span><span id="s">Enregistrer</span></button>
                
              </div>
           </form>


		      </div>
		    </div>
		  </div>
		   </div>
	   <!--FIN MODAL MODIFICATION -->
	   @endforeach
     @endif
</section>


<script>
  jQuery("#telephone").inputmask({
    "mask": "99 99 99 99"
  })

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