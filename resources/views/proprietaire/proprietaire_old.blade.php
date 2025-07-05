@extends('layouts.template')

@section('content')

<div class="pagetitle">
  <nav>
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
      <li class="breadcrumb-item active">Gestion propriétaire</li>
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
             
    <div class="modal fade" id="AjouerProprietaire" tabindex="-1" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #012970;">
          <h5 class="modal-title">Ajouter un propriétaire</h5>
        </div>
        <div class="modal-body">

              <form class="row g-3" method="post" action="javascript:save_proprietaire();" 
               id="formulaire">
               @csrf

               <div class="alert-primary bg-primary text-light" id="afficher"></div>

              @can('Is_admin')
                 @if(Auth::user()->type_compte != 'Particulier')
                 <div class="col-12">
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
              
                <div class="col-12">
                  <label for="inputNanme4" class="form-label">Nom<span style="color: red;">*</span></label>
                  <input type="text" name="nom" class="form-control" id="nom" required="">
                  <span class="invalid-feedback nom_err" role="alert">
                        </span>
                </div>
                <div class="col-12">
                  <label for="inputEmail4" class="form-label">Prénom<span style="color: red;">*</span></label>
                  <input type="text" name="prenom" class="form-control" id="prenom" required="">
                  <span class="invalid-feedback prenom_err" role="alert">
                        </span>
                </div>
                <div class="col-12">
                  <label for="inputPassword4" class="form-label">Téléphone<span style="color: red;">*</span></label>
                  <input type="text" name="telephone" class="form-control"
                  id="telephone" required="">
                  <span class="invalid-feedback telephone_err" role="alert">
                        </span>
                </div>
                <div class="col-12">
                  <label for="inputAddress" class="form-label">Adresse<span style="color: red;">*</span></label>
                  <input type="text" name="adresse" class="form-control"
                   id="adresse" required="">
                  <span class="invalid-feedback adresse_err" role="alert">
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


  
      <div class="row">
        <div class="col-lg-12">
          @can('ajoute-proprietaire')
            <button type="button" class="btn sbg1 rounded-pill ri-user-add-line shadow" 
            data-bs-toggle="modal" data-bs-target="#AjouerProprietaire">+</button> <br/><br/>
          @endcan
          <div class="card recent-sales overflow-auto">
            <div class="card-body">
              <h5 class="card-title">Liste des propriétaires</h5>

              <table class="table datatable border-primary">
                <thead>
                  <tr>
                    <th scope="col">Agence</th>
                    <th scope="col">Nom & Prénom</th>
                    <th scope="col">Téléphone</th>
                    <th scope="col">Adresse</th>
                    <th scope="col">Actions</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
              @can('Consulter-proprietaire')

                @if(isset($allProprios))
                 @foreach($allProprios as $item)  
                  <tr>
                    <td>{{ get_annexee_name($item->idannexe_ref) }}</td>
                    <th scope="row">{{ $item->nom }}  {{ $item->prenom }}</th>
                    <td>{{ $item->telephone }}</td>
                    <td>{{ $item->adresse }}</td>
                    <td>
                       @can('modify-proprietaire')
                        <a class="btn sbg1" 
                        title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                            <i class="ri-pencil-fill"></i>
                         </a>
                       @endcan
                    </td>

                    <td>
                       @can('delete-proprietaire')
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
    @foreach($allProprios as $items)
	  <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1">
	    <div class="modal-dialog modal-dialog-centered">
	      <div class="modal-content">
	        <div class="modal-header text-white" style="background-color: #012970;">
	          <h5 class="modal-title">Suppression</h5>
	        </div>
	        <div class="modal-body">

	              <form class="row g-3" method="post" action="{{ route('destroy_proprio') }}">
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
	          <h5 class="modal-title">Modification</h5>
	        </div>
	        <div class="modal-body">

	              <form class="row g-3" method="post" action="{{ route('update_proprio') }}">
	               @csrf
	                <input type="hidden" name="id" class="form-control" id="id" value="{{ $items->id}} ">

                @can('Is_admin')
                  @if(Auth::user()->type_compte != 'Particulier')
                  <div class="col-12">
                  <label for="inputNanme4" class="form-label">Choisir une agence<span style="color: red;">*</span></label>
                  <select required="" class="form-select @error('annexe') is-invalid @enderror" name="annexe" id="annexe" aria-label="Default select example">
                      <option selected disabled value="">Choisir une agence</option>
                       @if(Session::get('anne_data') != " ")
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
	                  <label for="inputNanme4" class="form-label">Nom<span style="color: red;">*</span></label>
	                  <input type="text" name="nom" value="{{ $items->nom }}" class="form-control" id="nom">
	                </div>
	                <div class="col-12">
	                  <label for="inputEmail4" class="form-label">Prénom<span style="color: red;">*</span></label>
	                  <input type="text" name="prenom" value="{{ $items->prenom }}" class="form-control" id="prenom">
	                </div>
	                <div class="col-12">
	                  <label for="inputPassword4" class="form-label">Téléphone<span style="color: red;">*</span></label>
	                  <input type="text" name="telephone" value="{{ $items->telephone }}" class="form-control"  id="telephone_{{ $items->id}}" onload="telephoneMask('telephone_{{ $items->id}}');" onkeydown="limit(this);" onkeyup="limit(this);" onkeypress="return /[0-9]/i.test(event.key)">
	                </div>
	                <div class="col-12">
	                  <label for="inputAddress"  class="form-label">Adresse<span style="color: red;">*</span></label>
	                  <input type="text" name="adresse" value="{{ $items->adresse }}" class="form-control" id="adresse">
	                </div>
		        </div>
		        <div class="modal-footer">
		          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
		          <button  class="btn sbg1">Enregistrer</button>
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

