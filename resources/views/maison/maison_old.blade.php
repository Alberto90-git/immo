@extends('layouts.template')

@section('content')

<div class="pagetitle">
  <nav>
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
      <li class="breadcrumb-item active">Gestion maison</li>
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
             
    <div class="modal fade" id="AjouerMaison" tabindex="-1" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">

      <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #012970;">
          <h5 class="modal-title">Ajouter une maison</h5>
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
                        <span class="invalid-feedback annexe_err" role="alert">
                        </span>
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
                        <span class="invalid-feedback nom_proprietaire_err" role="alert">
                        </span>
                </div>


                <div class="col-12">
                  <label for="inputNanme4" class="form-label">Nom de la maison<span style="color: red;">*</span></label>
                  <input type="text" name="nom_maison" class="form-control @error('nom_maison') is-invalid @enderror" id="nom_maison" required>
                  <span class="invalid-feedback nom_maison_err" role="alert">
                        </span>
                </div>
                <div class="col-12">
                  <label for="inputEmail4" class="form-label">Quartier<span style="color: red;">*</span></label>
                  <input type="text" name="quartier" class="form-control @error('quartier') is-invalid @enderror" id="quartier" required>
                  <span class="invalid-feedback quartier_err" role="alert">
                        </span>
                </div>
                <div class="col-12">
                  <label for="inputPassword4" class="form-label">Nombre de chambre<span style="color: red;">*</span></label>
                  <input type="text" name="nombre_chambre" onkeyup="limit(this);" onkeydown="limit(this);" class="form-control @error('nombre_chambre') is-invalid @enderror" id="nombre_chambre" required>
                    <span class="invalid-feedback nombre_chambre_err" role="alert">
                        </span>
                </div>
	        </div>
	        <div class="modal-footer">
	          <button  class="btn btn-secondary" onclick="(this);" id="close" data-bs-dismiss="modal">Fermer</button>
	          <button  class="btn sbg1" id="valider"><span class="fa fa-save" id="a"></span><span id="s">Enregistrer</span></button>
            
	        </div>
	         </form>
	      </div>
	    </div>
	  </div>

           

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          @can('ajoute-maison')
            <button type="button" class="btn sbg1 rounded-pill ri-home-2-line shadow" 
            data-bs-toggle="modal" data-bs-target="#AjouerMaison">+</button> <br/><br/>
          @endcan
          <div class="card recent-sales overflow-auto">
            <div class="card-body">
              <h2 class="text-center"><u>Liste des maisons</u></h2>

              <table class="table datatable border-primary">
                <thead>
                  <tr>
                    <th scope="col">Agence</th>
                    <th scope="col">Propriétaire</th>
                    <th scope="col">Nom maison</th>
                    <th scope="col">Quartier</th>
                    <th scope="col">Nombre de chambre</th>
                    <th scope="col">Actions</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
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
                        <a class="btn sbg1" 
                        title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                          <i class="ri-pencil-fill"></i>
                        </a>
                       @endcan
                    </td>

                    <td>
                      @can('delete-maison')

                         <a class="btn btn-danger shadow"
                         title="Supprimer" href="#" data-bs-toggle="modal" data-bs-target="#supprimer{{$loop->iteration}}">
                           <i class="ri-delete-bin-2-fill"></i>
                          </a>
                       @endcan
                    </td>
                  </tr>
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
    @foreach($allMaison as $items)
	  <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1">
	    <div class="modal-dialog modal-dialog-centered">
	      <div class="modal-content">
	        <div class="modal-header text-white" style="background-color: #012970;">
	          <h5 class="modal-title">Suppression</h5>
	        </div>
	        <div class="modal-body">

	              <form class="row g-3" method="post" action="{{ route('destroy_house') }}">
	              	 Voulez-vous vraiment supprimer cette ligne ? 
	               @csrf
	                <input type="hidden" name="id" class="form-control" id="id" value="{{ $items->id}} ">
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
	    <div class="modal-dialog modal-dialog-centered">
	      <div class="modal-content">
	        <div class="modal-header text-white" style="background-color: #012970;">
	          <h5 class="modal-title">Modification d'une maison</h5>
	        </div>
	        <div class="modal-body">
             <form class="row g-3" method="post" action="{{ route('update_house') }}" 
               id="formulaireModifiedqsdqsr">
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
                        <span class="invalid-feedback nom_proprietaire_err" role="alert">
                        </span>
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

