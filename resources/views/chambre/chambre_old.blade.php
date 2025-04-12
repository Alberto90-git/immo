@extends('layouts.template')

@section('content')

<div class="pagetitle">
  <nav>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
      <li class="breadcrumb-item active">Gestion chambre</li>
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
             
    <div class="modal fade" id="AjouerMaison" tabindex="-1"  data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #012970;">
          <h5 class="modal-title">Ajouter une chambre</h5>
        </div>
        <div class="modal-body">

            <form class="row g-3" method="post" action="javascript:save_chambre();" 
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
        @can('ajoute-chambre')
            <button type="button" class="btn sbg1 rounded-pill ri-home-2-line shadow" 
           data-bs-toggle="modal" data-bs-target="#AjouerMaison">+</button> <br/><br/>
        @endcan
          <div class="card recent-sales overflow-auto">
            <div class="card-body">
              <h2 class="text-center"><u>Liste des chambres</u></h2>

              <table class="table datatable border-primary">
                <thead>
                  <tr>
                    <th scope="col">Agence</th>
                    <th scope="col">Nom maison</th>
                    <th scope="col">N° chambre</th>
                    <th scope="col">Type de chambre</th>
                    <th scope="col">Status</th>
                    <th scope="col">Actions</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
              @can('Consulter-chambre')

                @if(isset($allChambres))
                 @foreach($allChambres as $item)
                  <tr>
                    <td>{{ get_annexee_name($item->idannexe_ref) }}</td>
                    <th scope="row">{{ $item->nom_maison }}</th>
                    <td>{{ $item->numero_chambre }}</td>
                    <td>{{ $item->type_chambre }}</td>
                    <td>
                      @if($item->etat == true) 
                         <span class="badge bg-danger">Occupé</span>
                      @else
                        <span class="badge bg-success">Libre</span>
                      @endif
                  </td>
                    <td>
                    @can('modify-chambre')
                      <a class="btn sbg1" 
                      title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                          <i class="ri-pencil-fill"></i>
                       </a>
                    @endcan
                    </td>

                    <td>
                    @can('delete-chambre')
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
    @foreach($allChambres as $items)
	  <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1">
	    <div class="modal-dialog modal-dialog-centered">
	      <div class="modal-content">
	        <div class="modal-header text-white" style="background-color: #012970;">
	          <h5 class="modal-title">Suppression</h5>
	        </div>
	        <div class="modal-body">

	              <form class="row g-3" method="post" action="{{ route('destroy_chambre') }}">
	              	 Voulez-vous vraiment supprimer cette ligne ? 
	               @csrf
	                <input type="hidden" name="chambre_id" class="form-control" id="chambre_id" value="{{ $items->id}} ">
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
	          <h5 class="modal-title">Modification d'une chambre</h5>
	        </div>
	        <div class="modal-body">

            <form class="row g-3" method="post" action="{{ route('update_chambre') }}" 
               id="formulairererree">
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

