@extends('layouts.template')


@section('content')

    @section('title')
    <title>Gestion utilisateur</title>
    @endsection

    @if (Session::has("success"))
      <div class="col-md-6 p-4">
        <div class="toast-container">
        <div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-semibold">SUCCES</div>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
              {{ Session::get('success') }}
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
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil/Gestion utilisateur/</span>Modification</h4>

        <div class="ms-3 demo-inline-spacing">
            <a href="{{ route('getUserView') }}" class="btn rounded-pill btn-primary">
                <span class="tf-icons bx bx-arrow-back"></span>&nbsp;
            </a>
        </div> <br>

        @php
         $liste = get_annexe_liste();
        @endphp


        <div class="card">
            <div class="card-body">
                <form action="javascript:update_user();" method="post" id="update_user">

                    @csrf
                    <input type="text" value="{{ $user->id }}" name="user" hidden>

                    <div class="row mb-3">
                        <label for="nom" class="col-sm-2 col-form-label">Choisir une agence<span style="color: red;">*</span></label>
                        <div class="col-sm-6">
                        <select required="" class="form-select @error('annexe') is-invalid @enderror" name= "annexe" id="annexe" aria-label="Default select example">
                            <option selected disabled value="">Choisir une agence</option>
                            @if(isset($annexes))
                            @foreach($annexes as $terme)
                                <option  value="{{$terme->idannexes}}"  {{$user->idannexe_ref == $terme->idannexes ? 'selected':''}}>{{$terme->designation}}</option>
                            @endforeach
                            @endif 
                            </select>
                            <span class="text-danger error-text annexe_err small mb-2"></span>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label for="nom" class="col-sm-2 col-form-label">Nom<span style="color: red;">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" value="{{ $user->nom }}"class="form-control  @error('nom') is-invalid @enderror" id="nom" name="nom" required>
                            <span class="text-danger error-text nom_err small mb-2"></span>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label for="prenom" class="col-sm-2 col-form-label">Prénom<span style="color: red;">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" value="{{ $user->prenom }}"  class="form-control  @error('prenom') is-invalid @enderror" id="prenom" name="prenom" required>
                            <span class="text-danger error-text prenom_err small mb-2"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Email<span style="color: red;">*</span></label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control  @error('email') is-invalid @enderror" value="{{ $user->email }}" id="email" name="email" required>
                            <span class="text-danger error-text email_err small mb-2"></span>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label for="grade" class="col-sm-2 col-form-label">Grade<span style="color: red;">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" value="{{ $user->grade }}" class="form-control  @error('grade') is-invalid @enderror" id="grade" name="grade" required>
                            <span class="text-danger error-text grade_err small mb-2"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Rules<span style="color: red;">*</span></label>
                        <div class="col-sm-6">
                            {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control')) !!}

                        </div>
                    </div>

                   

                    <div class="mt-2 text-center">
                        <button class="btn btn-primary" id="valider" type="submit">
                            <span class="fa fa-save" id="a"></span>
                            <span id="s">Enregistrer</span>
                        </button>
                    </div>


                </form>
            </div>
        </div>


</div>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>



<script>
    $(document).ready(function() {

            $('.select2-multiple').select2({
                placeholder: "Select your r",
                allowClear: true
            });

    });

    function printErrorMsg(msg) {
        $.each(msg, function(key, value) {
            $('.' + key + '_err').text(value);
        });
    }

    function update_user() {
        // var frm = $('form#formulaire');
        var donnees = $("form#update_user").serialize();
        $.ajax({
            url: "{{ route('modifieUser') }}",
            method: 'POST',
            data: donnees,
            beforeSend: function(data) {
                $("#s").html("<i class='spinner-border spinner-border-sm'></i> En cours...");

                $("#a").removeClass("fa fa-save");
                $("#a").addClass("spinner-border text-primary");
                $("#valider").attr("disabled", true);

            },
            success: function(data) {
                if (!$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error);

                }
                try {
                    if (data.status) {

                        display_message("Super !!",data.message,"success","btn btn-primary");

                        $("#afficher").removeClass("alert alert-danger bg-danger text-light border-0 alert-dismissible fade show");

                        $("#afficher").addClass("alert alert-success bg-success text-light border-0 alert-dismissible fade show").html(data.message).show();

                        $("#update_user")[0].reset();
                    } else {
                    display_message("Erreur !!",data.message,"warning","btn btn-danger");

                        $("#afficher").addClass("alert alert-danger bg-danger text-light border-0 alert-dismissible fade show").html(data.message).show();
                    }
                    $("#s").html("Enregistrer");
                    $("#a").removeClass("spinner-border spinner-border-sm");
                    $("#a").addClass("mdi mdi-content-save");
                    $("#valider").attr("disabled", false);
                } catch (error) {
                    display_message("Erreur !!",data.message,"warning","btn btn-danger");

                    $("#afficher").addClass("alert alert-danger bg-danger text-light border-0 alert-dismissible fade show").html(data.message).show();
                    $("#s").html("Enregistrer");

                    $("#a").removeClass("spinner-border spinner-border-sm");
                    $("#a").addClass("mdi mdi-content-save");
                    $("#valider").attr("disabled", false);
                }


            },
            error: function(data) {
                $("#s").html("Enregistrer");
                $("#a").removeClass("spinner-border spinner-border-sm");
                $("#a").addClass("mdi mdi-content-save");
                $("#valider").attr("disabled", false);
                $("#afficher").addClass("alert alert-danger bg-danger text-light border-0 alert-dismissible fade show").html("vous n'êtes pas autorisé pour effectuer cette action").show();
            }
        });
    }


    function printErrorMsg(msg) {
        const items = [];
        for (const [key, value] of Object.entries(msg)) {
            $('.' + key + '_err').text(value).show();
            var elmnt = $('.' + key + '_err');
            console.log(elmnt.closest('.form-group'));
            items.push(elmnt.closest('.form-group'))
        }

        if (items[0] !== undefined) {
            items[0].get(0).scrollIntoView({
                behavior: "instant",
                block: "end",
                inline: "nearest"
            })
        }
    }
</script>
@endsection