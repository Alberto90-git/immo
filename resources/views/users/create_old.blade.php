@extends('layouts.template')
@section('css')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">

@endsection()


@section('content')

<div class="pagetitle">
    <h1>Gestion utilisateur</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item">Gestion utilisateur</li>
            <li class="breadcrumb-item active">Ajouter utilisateur</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div id="afficher">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <!-- <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div> -->
            @php
               $liste = get_annexe_liste();
            @endphp


            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h4 class="center"></h4>
                            </div>
                            <div class="pull-right">
                                <a class="btn sbg1 shadow" href="{{ route('getUserView') }}">Retour</a>
                            </div>
                        </div>
                    </div><br>
                    <form action="javascript:save_user();" method="post" id="save_user">
                        @csrf

                        <div class="row mb-3">
                            <label for="nom" class="col-sm-2 col-form-label">Choisir une agence<span style="color: red;">*</span></label>
                            <div class="col-sm-6">
                            <select required="" class="form-select @error('annexe') is-invalid @enderror" name= "annexe" id="annexe" aria-label="Default select example">
                                <option selected disabled value="">Choisir une agence</option>
                                @if(isset($liste))
                                    @foreach($liste as $terme)
                                    <option  value="{{$terme->idannexes}}">{{$terme->designation}}</option>
                                    @endforeach
                                @endif  
                                </select>
                                <span class="text-danger error-text annexe_err small mb-2"></span>
                            </div>
                        </div>
                       
                        <div class="row mb-3">
                            <label for="nom" class="col-sm-2 col-form-label">Nom<span style="color: red;">*</span></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control  @error('nom') is-invalid @enderror" id="nom" name="nom" required>
                                <span class="text-danger error-text nom_err small mb-2"></span>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="prenom" class="col-sm-2 col-form-label">Prénom<span style="color: red;">*</span></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control  @error('prenom') is-invalid @enderror" id="prenom" name="prenom" required>
                                <span class="text-danger error-text prenom_err small mb-2"></span>
                            </div>
                        </div>
                        

                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">Email<span style="color: red;">*</span></label>
                            <div class="col-sm-6">
                                <input type="email" class="form-control  @error('email') is-invalid @enderror" id="email" name="email" required>
                                <span class="text-danger error-text email_err small mb-2"></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="grade" class="col-sm-2 col-form-label">Grade<span style="color: red;">*</span></label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control  @error('grade') is-invalid @enderror" id="grade" name="grade" required>
                                <span class="text-danger error-text grade_err small mb-2"></span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputEmail3" class="col-sm-2 col-form-label">Rôles<span style="color: red;">*</span></label>
                            <div class="col-sm-6">
                                {!! Form::select('roles[]', $roles, [], ['class' => 'form-control', 'required']) !!}
                            </div>
                        </div>


                        <div class="mt-2 text-center">
                            <button class="btn sbg1 shadow" id="valider" type="submit">
                                <span id="s">Enregister</span>
                            </button>
                        </div>


                    </form>
                </div>
            </div>

        </div>
    </div>
</section>



<script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>

<script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>




<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>


<script type="text/javascript">


    $(document).ready(function() {

    $('.js-example-basic-multiple').select2();

    });


    function printErrorMsg(msg) {
        $.each(msg, function(key, value) {
            $('.' + key + '_err').text(value);
        });
    }

    function save_user() {
        // var frm = $('form#formulaire');
        var donnees = $("form#save_user").serialize();
        $.ajax({
            url: "{{ route('saveUser') }}",
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

                        $("#afficher").removeClass("alert alert-danger bg-danger text-light border-0 alert-dismissible fade show")

                        $("#afficher").addClass("alert alert-success bg-success text-light border-0 alert-dismissible fade show").html(data.message).show();

                        $("#save_user")[0].reset();
                    } else {
                        $("#afficher").addClass("alert alert-danger bg-danger text-light border-0 alert-dismissible fade show").html(data.message).show();
                    }
                    $("#s").html("Enregister");
                    $("#a").removeClass("spinner-border spinner-border-sm");
                    $("#a").addClass("mdi mdi-content-save");
                    $("#valider").attr("disabled", false);
                } catch (error) {
                    $("#afficher").addClass("alert alert-danger bg-danger text-light border-0 alert-dismissible fade show").html('Error').show();

                    //$("#afficher").addClass("alert-danger").html(data.message).show();
                    $("#s").html("Enregister");

                    $("#a").removeClass("spinner-border spinner-border-sm");
                    $("#a").addClass("mdi mdi-content-save");
                    $("#valider").attr("disabled", false);
                }


            },
            error: function(data) {
                $("#s").html("Enregister");
                $("#a").removeClass("spinner-border spinner-border-sm");
                $("#a").addClass("mdi mdi-content-save");
                $("#valider").attr("disabled", false);
                $("#afficher").addClass("alert alert-danger bg-danger text-light border-0 alert-dismissible fade show").html("Il y a un problème").show();

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