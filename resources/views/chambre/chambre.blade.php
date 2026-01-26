@extends('layouts.template')

@section('content')

@section('title')
<title>Gestion chambre</title>
@endsection

<style>
    /* Forcer SweetAlert2 à apparaître au-dessus de tous les modals */
    .swal2-container {
        z-index: 10070 !important;
    }
    
    .swal2-popup {
        z-index: 10071 !important;
    }
</style>

@include('notification.display_message')


<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion chambre</h4>

    <div class="col-md-6">
        <div class="demo-inline-spacing">
            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal"
                data-bs-target="#AjouerChambre">
                <span class="bx bx-plus"></span>
            </button>
        </div>
    </div><br/>

    <!-- Modal Ajout -->
    <div class="modal fade" id="AjouerChambre" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Ajouter une chambre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" method="post" action="javascript:save_chambre();" id="formulaire">
                        @csrf

                        <div class="col-12">
                            <label for="inputNanme4" class="form-label">Choisir une maison<span style="color: red;">*</span></label>
                            <select required class="form-select @error('nom_maison') is-invalid @enderror" name="nom_maison" id="nom_maison" aria-label="Default select example">
                                <option selected disabled value="">Choisir une maison</option>
                                @if(isset($allMaison))
                                @foreach($allMaison as $terme)
                                <option value="{{$terme->id}}" {{ old($terme->id) == $terme->id ? 'selected' : '' }}>{{$terme->nom_maison}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span class="invalid-feedback nom_maison_err" role="alert"></span>
                        </div>

                        <div class="col-12">
                            <label for="inputNanme4" class="form-label">N° de la chambre<span style="color: red;">*</span></label>
                            <input type="text" name="numero_chambre" class="form-control @error('numero_chambre') is-invalid @enderror" onkeypress="return /[0-9]/i.test(event.key)" id="numero_chambre" required>
                            <span class="invalid-feedback numero_chambre_err" role="alert"></span>
                        </div>

                        <div class="col-12">
                            <label for="inputNanme4" class="form-label">Type de chambre<span style="color: red;">*</span></label>
                            <select required class="form-select @error('type_chambre') is-invalid @enderror" name="type_chambre" id="type_chambre" aria-label="Default select example">
                                <option selected disabled value="">Type de chambre</option>
                                <option value="Entrée couche ordinaire">Entrée couche ordinaire</option>
                                <option value="Entrée couche semi-sanitaire">Entrée couche semi-sanitaire</option>
                                <option value="Entrée couche sanitaire">Entrée couche sanitaire</option>
                                <option value="Chambre salon ordinaire">Chambre salon ordinaire</option>
                                <option value="Chambre salon semi-sanitaire">Chambre salon semi-sanitaire</option>
                                <option value="Chambre salon sanitaire">Chambre salon sanitaire</option>
                                <option value="2Chambre salon ordinaire">2Chambre salon ordinaire</option>
                                <option value="2Chambre salon semi-sanitaire">2Chambre salon semi-sanitaire</option>
                                <option value="2Chambre salon sanitaire">2Chambre salon sanitaire</option>
                                <option value="3Chambre salon ordinaire">3Chambre salon ordinaire</option>
                                <option value="3Chambre salon semi-sanitaire">3Chambre salon semi-sanitaire</option>
                                <option value="3Chambre salon sanitaire">3Chambre salon sanitaire</option>
                                <option value="Appartement">Appartement</option>
                                <option value="Boutique">Boutique</option>
                            </select>
                            <span class="invalid-feedback type_chambre_err" role="alert"></span>
                        </div>

                        <div class="col-12">
                            <label for="inputNanme4" class="form-label">Prix / mois<span style="color: red;">*</span></label>
                            <input type="text" name="prix" class="form-control @error('prix') is-invalid @enderror" onkeypress="return /[0-9]/i.test(event.key)" id="prix" required>
                            <span class="invalid-feedback prix_err" role="alert"></span>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" id="close">
                                Fermer
                            </button>
                            <button class="btn btn-primary" id="valider">
                                <span class="fa fa-save"></span>
                                <span>Enregistrer</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des chambres -->
    <div class="card">
        <h5 class="card-header text-center">Liste des chambres</h5>
        <div class="table-responsive text-nowrap">
            <table id="example" class="table table-hover border-primary" style="width:100%">
                <thead>
                    <tr>
                        <th scope="col">Agence</th>
                        <th scope="col">Nom maison</th>
                        <th scope="col">N° chambre</th>
                        <th scope="col">Type de chambre</th>
                        <th scope="col">Prix</th>
                        <th scope="col">Status</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-0">
                    @can('Consulter-chambre')
                    @if(isset($allChambres))
                    @foreach($allChambres as $item)
                    <tr>
                        <td>{{ get_annexee_name($item->idannexe_ref) }}</td>
                        <th scope="row">{{ $item->nom_maison }}</th>
                        <td>{{ $item->numero_chambre }}</td>
                        <td>{{ $item->type_chambre }}</td>
                        <td>{{ number_format($item->prix_chambre ,"0",",",".") }} XOF</td>
                        <td>
                            @if($item->etat == true)
                            <span class="badge rounded-pill bg-danger">Occupé</span>
                            @else
                            <span class="badge rounded-pill bg-success">Libre</span>
                            @endif
                        </td>
                        <td>
                            @can('modify-chambre')
                            <a class="btn rounded-pill btn-primary" title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                                <i class="bx bx-edit-alt me-1"></i>
                            </a>
                            @endcan

                            @can('delete-chambre')
                            <a class="btn rounded-pill btn-danger" title="Supprimer" href="#" data-bs-toggle="modal" data-bs-target="#supprimer{{$loop->iteration}}">
                                <i class="bx bx-trash me-1"></i>
                            </a>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    @endcan
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modals de suppression et modification -->
    @if(isset($allChambres))
    @foreach($allChambres as $items)
    <!-- Modal Suppression -->
    <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Supprimer une chambre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" method="post" action="{{ route('destroy_chambre') }}">
                        @csrf
                        Voulez-vous vraiment supprimer cette ligne ?
                        <input type="hidden" name="id" class="form-control" id="id" value="{{ $items->id}}">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Non
                            </button>
                            <button type="submit" class="btn btn-outline-danger">Oui</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modification -->
    <div class="modal fade" id="modifier{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCenterTitle">Modification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row g-3" method="post" action="{{ route('update_chambre') }}">
                        @csrf
                        <input type="hidden" name="chambre_id" class="form-control" id="id" value="{{ $items->id}}">

                        <div class="col-12">
                            <label for="inputNanme4" class="form-label">Choisir une maison<span style="color: red;">*</span></label>
                            <select required class="form-select @error('nom_maison') is-invalid @enderror" name="nom_maison" id="nom_maison" aria-label="Default select example">
                                <option selected disabled value="">Choisir une maison</option>
                                @if(isset($allMaison))
                                @foreach($allMaison as $terme)
                                <option value="{{$terme->id}}" {{$items->maison_id == $terme->id ? 'selected':''}}>{{$terme->nom_maison}}</option>
                                @endforeach
                                @endif
                            </select>
                            <span class="invalid-feedback nom_maison_err" role="alert"></span>
                        </div>

                        <div class="col-12">
                            <label for="inputNanme4" class="form-label">N° de la chambre<span style="color: red;">*</span></label>
                            <input type="text" name="numero_chambre" value="{{ $items->numero_chambre }}" class="form-control @error('numero_chambre') is-invalid @enderror" onkeypress="return /[0-9]/i.test(event.key)" id="numero_chambre" required>
                            <span class="invalid-feedback numero_chambre_err" role="alert"></span>
                        </div>

                        <div class="col-12">
                            <label for="inputNanme4" class="form-label">Type de chambre<span style="color: red;">*</span></label>
                            <select required class="form-select @error('type_chambre') is-invalid @enderror" name="type_chambre" id="type_chambre" aria-label="Default select example">
                                <option selected disabled>Type de chambre</option>
                                <option value="Entrée couche ordinaire" {{$items->type_chambre == 'Entrée couche ordinaire' ? 'selected':''}}>Entrée couche ordinaire</option>
                                <option value="Entrée couche semi-sanitaire" {{$items->type_chambre == 'Entrée couche semi-sanitaire' ? 'selected':''}}>Entrée couche semi-sanitaire</option>
                                <option value="Entrée couche sanitaire" {{$items->type_chambre == 'Entrée couche sanitaire' ? 'selected':''}}>Entrée couche sanitaire</option>
                                <option value="Chambre salon ordinaire" {{$items->type_chambre == 'Chambre salon ordinaire' ? 'selected':''}}>Chambre salon ordinaire</option>
                                <option value="Chambre salon semi-sanitaire" {{$items->type_chambre == 'Chambre salon semi-sanitaire' ? 'selected':''}}>Chambre salon semi-sanitaire</option>
                                <option value="Chambre salon sanitaire" {{$items->type_chambre == 'Chambre salon sanitaire' ? 'selected':''}}>Chambre salon sanitaire</option>
                                <option value="2Chambre salon ordinaire" {{$items->type_chambre == '2Chambre salon ordinaire' ? 'selected':''}}>2Chambre salon ordinaire</option>
                                <option value="2Chambre salon semi-sanitaire" {{$items->type_chambre == '2Chambre salon semi-sanitaire' ? 'selected':''}}>2Chambre salon semi-sanitaire</option>
                                <option value="2Chambre salon sanitaire" {{$items->type_chambre == '2Chambre salon sanitaire' ? 'selected':''}}>2Chambre salon sanitaire</option>
                                <option value="3Chambre salon ordinaire" {{$items->type_chambre == '3Chambre salon ordinaire' ? 'selected':''}}>3Chambre salon ordinaire</option>
                                <option value="3Chambre salon semi-sanitaire" {{$items->type_chambre == '3Chambre salon semi-sanitaire' ? 'selected':''}}>3Chambre salon semi-sanitaire</option>
                                <option value="3Chambre salon sanitaire" {{$items->type_chambre == '3Chambre salon sanitaire' ? 'selected':''}}>3Chambre salon sanitaire</option>
                                <option value="Appartement" {{$items->type_chambre == 'Appartement' ? 'selected':''}}>Appartement</option>
                                <option value="Boutique" {{$items->type_chambre == 'Boutique' ? 'selected':''}}>Boutique</option>
                            </select>
                            <span class="invalid-feedback type_chambre_err" role="alert"></span>
                        </div>

                        <div class="col-12">
                            <label for="inputNanme4" class="form-label">Prix / mois<span style="color: red;">*</span></label>
                            <input type="text" name="prix" value="{{ $items->prix_chambre }}" class="form-control @error('prix') is-invalid @enderror" onkeypress="return /[0-9]/i.test(event.key)" id="prix" required>
                            <span class="invalid-feedback prix_err" role="alert"></span>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Fermer
                            </button>
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>

<!-- Script JavaScript -->
<script>

function display_sweet_alert_over_modal(title, text, icon, buttonClass) {
    // Appeler votre fonction existante
    display_sweet_alerte2(title, text, icon, buttonClass);
    
    // Forcer le z-index après un court délai
    setTimeout(function() {
        const swalContainer = document.querySelector('.swal2-container');
        const swalPopup = document.querySelector('.swal2-popup');
        
        if (swalContainer) {
            swalContainer.style.zIndex = '10070';
        }
        if (swalPopup) {
            swalPopup.style.zIndex = '10071';
        }
    }, 10);
}

function printErrorMsg(msg) {
    $.each(msg, function(key, value) {
        $('.' + key + '_err').text(value).show();
    });
}

// Fonction pour soumettre le formulaire AJAX
function save_chambre() {
    var data = new FormData();

    // Form data
    var form_data = $('#formulaire').serializeArray();
    $.each(form_data, function(key, input) {
        data.append(input.name, input.value);
    });

    $.ajax({
        url: "{{ route('store_chambre') }}",
        method: "POST",
        processData: false,
        contentType: false,
        data: data,
        beforeSend: function() {
            $("#AjouerChambre button#close").prop("disabled", true);
            $("#AjouerChambre button#valider").prop("disabled", true);
            $("#AjouerChambre button#valider").html('<i class="text-center fa fa-spinner fa-pulse fa-1x fa-fw ml-2">En cours...</i>');
        },
        success: function(data) {
            $("#AjouerChambre button#close").prop("disabled", false);
            $("#AjouerChambre button#valider").prop("disabled", false);
            $("#AjouerChambre button#valider").html('<span class="fa fa-save"></span> Enregistrer');

            if (!$.isEmptyObject(data.error)) {
                printErrorMsg(data.error);
                return;
            }

            try {
                if (data.status) {
                    display_sweet_alert_over_modal("Succès !!", data.message, "success", "btn btn-primary");
                    
                    $("#AjouerChambre form#formulaire")[0].reset();
                } else {
                    display_sweet_alert_over_modal("Erreur !!", data.message, "warning", "btn btn-danger");
                }
            } catch (error) {
                console.error(error);
            }
        },
        error: function(xhr) {
            $("#AjouerChambre button#close").prop("disabled", false);
            $("#AjouerChambre button#valider").prop("disabled", false);
            $("#AjouerChambre button#valider").html('<span class="fa fa-save"></span> Enregistrer');
            
            if (xhr.status === 422) {
                var errors = xhr.responseJSON.errors || {};
                var messages = xhr.responseJSON.message || "Erreur de validation";
                printErrorMsg(errors);
                display_sweet_alert_over_modal("Erreur !!", messages, "warning", "btn btn-danger");
            } else if (xhr.status === 500) {
                display_sweet_alert_over_modal("Erreur !!", messages, "warning", "btn btn-danger");
            } else {
                display_sweet_alert_over_modal("Erreur !!", "Une erreur est survenue", "warning", "btn btn-danger");
            }
        }
    });
}

// Masquer les messages d'erreur lors de la saisie
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