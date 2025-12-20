@extends('layouts.template')


@section('content')
    @section('title')
    <title>Gestion utilisateur</title>
    @endsection

    @include('notification.display_message')

    
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil/Gestion utilisateur/</span>Ajouter utilisateur </h4>

        <div class="ms-3 demo-inline-spacing">
            <a href="{{ route('getUserView') }}" class="btn rounded-pill btn-primary">
                <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Retour
            </a>
        </div> <br>

        @php
         $liste = get_annexe_liste();
        @endphp


        <div class="card">
            <div class="card-body">
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
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Fonctions<span style="color: red;">*</span></label>
                        <div class="col-sm-6">
                            {!! Form::select('roles[]', $roles, [], ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>


                    <div class="mt-2 text-center">
                        <button class="btn btn-primary" id="valider" type="submit">
                            <span id="s">Enregistrer</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>


</div>




<script type="text/javascript">


    function printErrorMsg(msg) {
        $.each(msg, function(key, value) {
            $('.' + key + '_err').text(value);
        });
    }

    function save_user() {
        var donnees = $("form#save_user").serialize();
        
        $.ajax({
            url: "{{ route('saveUser') }}",
            method: 'POST',
            data: donnees,
            beforeSend: function(data) {
                // Désactiver le bouton et afficher le spinner
                $("#s").html("<i class='spinner-border spinner-border-sm'></i> En cours...");
                $("#a").removeClass("fa fa-save mdi mdi-content-save");
                $("#a").addClass("spinner-border text-primary");
                $("#valider").attr("disabled", true);
            },
            success: function(data) {
                // Fonction pour réactiver le bouton
                function reactivateButton() {
                    $("#valider").attr("disabled", false);
                    $("#s").html("Enregistrer");
                    $("#a").removeClass("spinner-border text-primary");
                    $("#a").addClass("mdi mdi-content-save");
                }
                
                // Gestion des erreurs de validation
                if (!$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error);
                    reactivateButton();
                    return;
                }
                
                // Gestion des autres réponses
                try {
                    if (data.status) {
                        // Succès
                        display_message("Succès !", data.message, "success", "btn btn-success");
                        $("#save_user")[0].reset();
                    } else {
                        // Erreur métier
                        display_message("Erreur !", data.message, "warning", "btn btn-danger");
                    }
                    reactivateButton();
                } catch (error) {
                    // Erreur JavaScript
                    console.error('Erreur lors du traitement de la réponse:', error);
                    display_message("Erreur !", "Une erreur inattendue s'est produite", "warning", "btn btn-danger");
                    reactivateButton();
                }
            },
            error: function(xhr, status, error) {
                // Fonction pour réactiver le bouton
                function reactivateButton() {
                    $("#valider").attr("disabled", false);
                    $("#s").html("Enregistrer");
                    $("#a").removeClass("spinner-border text-primary");
                    $("#a").addClass("mdi mdi-content-save");
                }
                
                // Gestion des erreurs HTTP
                let errorMessage = "Une erreur s'est produite";
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.message) {
                            errorMessage = response.message;
                        }
                    } catch (e) {
                        // Si ce n'est pas du JSON, utiliser le message par défaut
                        errorMessage = "Erreur de connexion au serveur";
                    }
                }
                
                display_message("Erreur !", errorMessage, "error", "btn btn-danger");
                reactivateButton();
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