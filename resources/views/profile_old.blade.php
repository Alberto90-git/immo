@extends('layouts.template')

@section('content')

   <div class="pagetitle">
      <h1>Profile</h1>
      <nav>
        <ol class="breadcrumb">
           <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
          <li class="breadcrumb-item active">Profile</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

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



    

    <section class="section profile">
      <div class="row">

        <div class="col-xl-12">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Mes informations</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Modifier mon profile</button>
                </li>

                

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change mot de passe</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                  <h5 class="card-title">Details du profile</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Nom & prénom</div>
                    <div class="col-lg-9 col-md-8">{{ Auth::user()->nom }}   {{ Auth::user()->prenom }}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">E-mail</div>
                    <div class="col-lg-9 col-md-8">{{ Auth::user()->email }}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Grade</div>
                    <div class="col-lg-9 col-md-8">{{ Auth::user()->grade }}</div>
                  </div>
                </div>



                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">

                  <!-- Profile Edit Form -->
                  <form method="POST" action="javascript:modifier_profile();" id="formulaire">
                   @csrf
        

                    <input type="hidden" name="user_id"  id="user_id" value="{{ Auth::user()->id }}">
                    <div class="row mb-3">
                      <label for="nom" class="col-md-4 col-lg-3 col-form-label">Nom</label>
                      <div class="col-md-8 col-lg-6">
                        <input name="nom" type="text" class="form-control" id="nom" value="{{ Auth::user()->nom }}">
                        <span class="invalid-feedback nom_err" role="alert">
                        </span>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="prenom" class="col-md-4 col-lg-3 col-form-label">Prénom</label>
                      <div class="col-md-8 col-lg-6">
                        <input name="prenom" type="text" class="form-control" id="prenom" value="{{ Auth::user()->prenom }}">
                        <span class="invalid-feedback prenom_err" role="alert">
                        </span>
                      </div>
                    </div>

                    <div class="row mb-3">
                      <label for="email" class="col-md-4 col-lg-3 col-form-label">E-mail</label>
                      <div class="col-md-8 col-lg-6">
                        <input name="email" type="email" class="form-control" id="email" value="{{ Auth::user()->email }}" readonly>
                        <span class="invalid-feedback email_err" role="alert">
                        </span>
                      </div>
                    </div>

                   

                    <div class="row mb-3">
                      <label for="grade" class="col-md-4 col-lg-3 col-form-label">Grade</label>
                      <div class="col-md-8 col-lg-6">
                        <input name="grade" type="text" class="form-control" id="grade" value="{{ Auth::user()->grade }}"  readonly>
                        <span class="invalid-feedback grade_err" role="alert">
                        </span>
                      </div>
                    </div>

                    <div class="text-center">
                       <button  class="btn sbg1" id="valider"><span class="fa fa-save" id="a"></span><span id="s">Enregistrer</span></button>
                    </div>
                  </form>
                </div>
                  <!-- End Profile Edit Form -->

                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                   <form method="POST" action="javascript:modifier_password();" id="formulaire2">
                   @csrf

                     <div class="mb-1">
                      <label for="Ancien_mot_de_passe" class="col-md-6 col-form-label">Ancien mot de passe</label>
                        <div class="col-md-6 col-lg-6 input-group">
                          <input type="password" class="form-control" id="Ancien_mot_de_passe" aria-describedby="inputGroupPrepend2">
                          {{-- <span toggle="#Ancien_mot_de_passe" class="input-group-text ri-eye-fill field-icon toggle-password"></span> --}}
                          <span class="invalid-feedback Ancien_mot_de_passe_err" role="alert">
                        </span>
                        </div>
                     </div>

                    <div class="mb-1">
                      <label for="Nouveau_mot_de_passe" class="col-md-4 col-lg-3 col-form-label">Nouveau mot de passe</label>
                      <div class="col-md-8 col-lg-6 input-group">
                        <input name="Nouveau_mot_de_passe" type="password" class="form-control" id="Nouveau_mot_de_passe">
                        {{-- <span toggle="#Nouveau_mot_de_passe" class="input-group-text ri-eye-fill field-icon toggle-password"></span> --}}
                        <span class="invalid-feedback Nouveau_mot_de_passe_err" role="alert">
                        </span>
                      </div>
                    </div>

                    <div class="mb-1">
                      <label for="Confirmer_mot_de_passe" class="col-md-4 col-lg-3 col-form-label">Confirmer mot de passe</label>
                      <div class="col-md-8 col-lg-6 input-group">
                        <input name="Confirmer_mot_de_passe" type="password" class="form-control" id="Confirmer_mot_de_passe">
                        {{-- <span toggle="#Confirmer_mot_de_passe" class="input-group-text ri-eye-fill field-icon toggle-password"></span> --}}
                        <span class="invalid-feedback Confirmer_mot_de_passe_err" role="alert">
                        </span>
                      </div>
                    </div>
                    <br>

                    <div>
                      <button  class="btn sbg1" id="valider"><span class="fa fa-save" id="a"></span><span id="s">Enregistrer</span></button>
                    </div>
                  </form><!-- End Change Password Form -->

                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>



            
<script>

  $(".toggle-password").click(function() {

    $(this).toggleClass("ri-eye-off-fill");
    var input = $($(this).attr("toggle"));

    if (input.attr("type") == "password") {
      input.attr("type", "text");
    } else {
      input.attr("type", "password");
    }
  });

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

  function modifier_password() {

      var data = new FormData();

      //Form data
      var form_data = $('#formulaire2').serializeArray();
      $.each(form_data, function (key, input) {
          data.append(input.name, input.value);
      });



      $.ajax({
          url: "{{ route('modifierPassword') }}",
          method: "POST",
          processData: false,
          contentType: false,
          data: data,
          beforeSend: function(data) {
              $("button#close").prop("disabled", true);
              $("button#valider").prop("disabled", true);
              $("button#valider").html("<i class='spinner-border spinner-border-sm'></i> En cours...");
          },
          success: function(data) {
              $("button#close").prop("disabled", false);
              $("button#valider").prop("disabled", false);
              $("button#valider").html('Enregistrer');

              if (!$.isEmptyObject(data.error)) {
                  printErrorMsg(data.error);
              }
              try {
                  if (data.status) {
                   // rempliretableau();
                     // alert(data.message);
                      swal('Super !!',data.message, 'success');

                      $("form#formulaire2")[0].reset();
                      top.location.href = '/home';
                      
                  } else {
                      swal('Erreur !!',data.message, 'warning');
                  }
              } catch (error) {
                
              }
          },
          error: function(data) {

         }
      });

  }


  function modifier_profile() {

      var data = new FormData();

      //Form data
      var form_data = $('#formulaire').serializeArray();
      $.each(form_data, function (key, input) {
          data.append(input.name, input.value);
      });

      $.ajax({
          url: "{{ route('modifier_profile') }}",
          method: "POST",
          processData: false,
          contentType: false,
          data: data,
          beforeSend: function(data) {
              $("button#close").prop("disabled", true);
              $("button#valider").prop("disabled", true);
              $("button#valider").html("<i class='spinner-border spinner-border-sm'></i> En cours...");
          },
          success: function(data) {

              $("button#close").prop("disabled", false);
              $("button#valider").prop("disabled", false);
              $("button#valider").html('Enregistrer');

              if (!$.isEmptyObject(data.error)) {
                  printErrorMsg(data.error);
              }
              try {
                  if (data.status) {
                   // rempliretableau();

                     // alert(data.message);
                      //$("div#afficher").html(data.message)
                    display_message("Super !!",data.message,"success","btn btn-primary");


                      $("form#formulaire")[0].reset();

                      setInterval(function(){
                          window.location.reload()
                      }, 1000)
                  } else {
                     // $("div#afficher").html(data.message)
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

