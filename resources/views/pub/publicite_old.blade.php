@extends('layouts.template')

@section('content')

<div class="pagetitle">
  <nav>
    <ol class="breadcrumb">
       <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
      <li class="breadcrumb-item active">Gestion publicité</li>
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
             
    <div class="modal fade" id="AjouerPub" tabindex="-1" data-bs-backdrop="false">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header text-white" style="background-color: #012970;">
          <h5 class="modal-title">Ajouter une publicité</h5>
        </div>
        <div>
          
        </div>
        <div class="modal-body">

            <form class="row g-3" method="post" action="javascript:save_pub();" id="formulaire">
               @csrf

               <div class="alert-primary bg-primary text-light" id="afficher"></div>

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
                  <input type="number" min="1" name="superficie" class="form-control" id="superficie" required="">
                  <span class="invalid-feedback superficie_err" role="alert"></span>
                </div>
                <div class="col-6">
                  <label for="inputPassword4" class="form-label">Prix de vente<span style="color: red;">*</span></label>
                  <input type="number" min="1" name="prix_vente" class="form-control" id="prix_vente" required="">
                  <span class="invalid-feedback prix_vente_err" role="alert">
                        </span>
                </div>

                <div class="col-12">
                  <label for="inputPassword4" class="form-label">Numéro à contacter<span style="color: red;">*</span></label>
                  <input type="tel" min="1" name="telephone" class="form-control" id="telephone" required="">
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
            data-bs-toggle="modal" data-bs-target="#AjouerPub">+</button> <br/><br/>
          @endcan
          <div class="card recent-sales overflow-auto">
            <div class="card-body">
              <h5 class="card-title">Liste des pubs</h5>

              <table class="table datatable border-primary">
                <thead>
                  <tr>
                    <th scope="col">Agence</th>
                    <th scope="col">Adresse du bien</th>
                    <th scope="col">Superficie</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Actions</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
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

                       <button type="button" class="btn sbg1 shadow" data-bs-toggle="modal" data-bs-target="#disablebackdrop{{ $loop->iteration }}">
                        <i class="ri-zoom-in-line"></i>
                      </button>
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

    @foreach($allpub as $items)

    <div class="modal fade" id="disablebackdrop{{ $loop->iteration }}" tabindex="-1" data-bs-backdrop="false">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header text-white" style="background-color: #012970;">
                  <h5 class="modal-title center">Description</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  {{ $items->description }}
              </div>

              <div>
                <img src="{{ asset('http://127.0.0.1/ImmobilierApk/storage/app/public/'.$items->image_url) }}" alt="Product Image" width="150">
              </div>

              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
              </div>
          </div>
      </div>
    </div>

     <!--DEBUT MODAL SUPPRESSION -->
	  <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1">
	    <div class="modal-dialog modal-dialog-centered">
	      <div class="modal-content">
	        <div class="modal-header text-white" style="background-color: #012970;">
	          <h5 class="modal-title">Suppression</h5>
	        </div>
	        <div class="modal-body">

	              <form class="row g-3" method="post" action="{{ route('destroy_pub') }}">
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
                        <span class="invalid-feedback annexe_err" role="alert">
                        </span>
                   </div>
                   @endif
                @endcan
              
                <div class="col-6">
                  <label for="inputNanme4" class="form-label">Adresse du bien immobilier<span style="color: red;">*</span></label>
                  <input type="text" name="adresse" class="form-control" id="adresse" value="{{ $items->localisation }}" required="">
                  <span class="invalid-feedback adresse_err" role="alert">
                        </span>
                </div>
                <div class="col-6">
                  <label for="inputEmail4" class="form-label">Superficie (m²)<span style="color: red;">*</span></label>
                  <input type="number" min="1" name="superficie" class="form-control" id="superficie" value="{{ $items->Superficie }}" required="">
                  <span class="invalid-feedback superficie_err" role="alert"></span>
                </div>
                <div class="col-6">
                  <label for="inputPassword4" class="form-label">Prix de vente<span style="color: red;">*</span></label>
                  <input type="number" min="1" name="prix_vente" class="form-control" id="prix_vente"  value="{{ $items->price }}" required="">
                  <span class="invalid-feedback prix_vente_err" role="alert">
                        </span>
                </div>

                <div class="col-12">
                  <label for="inputPassword4" class="form-label">Numéro à contacter<span style="color: red;">*</span></label>
                  <input type="tel" min="1" name="telephone" class="form-control" id="telephone" value="{{ $items->telephone }}" required="">
                  <span class="invalid-feedback telephone_err" role="alert">
                        </span>
                </div>
                <div class="col-12">
                  <label for="inputAddress" class="form-label">Description du bien<span style="color: red;">*</span></label>
                    <textarea class="form-control" name="description"  id="description" rows="5" required>{{ $items->description }}</textarea>
                  <span class="invalid-feedback description_err" role="alert"></span>
                </div>

                <div class="col-12">
                    <label for="inputAddress" class="form-label">Image</label>
                      <input type="file" name="image_up" class="form-control" id="image_up" required>
                      <span class="invalid-feedback image_up_err" role="alert"></span>
                </div>
                </div>

                <input type="hidden" name="image_ancien" class="form-control" id="image_ancien"  value="{{ $items->image_url }}">


                <div>
                  <img src="{{ asset('http://127.0.0.1/ImmobilierApk/storage/app/public/'.$items->image_url) }}" alt="Product Image" width="150">
                </div>

                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" onclick="(this);" id="close" data-bs-dismiss="modal">Fermer</button>
                  <button  type="submit" class="btn sbg1" ><span class="fa fa-save"></span><span >Enregistrer</span></button>
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