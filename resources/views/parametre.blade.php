@extends('layouts.template')

@section('content')
    @section('title')
      <title>Gestion Paramétrage</title>
    @endsection


    <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Paramétrage</h4>
      
        <div class="col-xl-12">
            <div class="nav-align-top mb-4">
                <ul class="nav nav-pills mb-3 nav-fill" role="tablist">

                    <li class="nav-item">
                    <button
                        type="button"
                        class="nav-link active"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-pills-justified-home"
                        aria-controls="navs-pills-justified-home"
                        aria-selected="true">
                        <i class="tf-icons bx bx-home"></i> Gestion direction
                        {{-- <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger">3</span> --}}
                    </button>
                    </li>

                    <li class="nav-item">
                    <button
                        type="button"
                        class="nav-link"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-pills-justified-profile"
                        aria-controls="navs-pills-justified-profile"
                        aria-selected="false"
                    >
                        <i class="tf-icons bx bx-user"></i> Gestion des annexes
                    </button>
                    </li>
                    
                </ul>

                <div class="tab-content">

                    <div class="tab-pane fade show active  table-responsive text-nowrap" id="navs-pills-justified-home" role="tabpanel">
                        <table class="table table-bordered border-primary" style="width:100%" >

                            <thead>
                            <tr>
                                <th scope="col">Format des factures</th>
                                <th scope="col">Logo de l'agence</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>
                            <form method="POST" action="javascript:save_parametre();" id="formulaire">
                                @csrf
                        
                                <div class="alert-primary bg-primary text-light" id="afficher"></div>
                                <tr>
                                <td>
                                <div class="row mb-3">
                                    <div class="col-sm-10">
                                    <select class="form-select" name="format_choisi" id="format_choisi" aria-label="Default select example" required>
                                        <option selected disabled>Selectionner un format</option>
                                        <option value="A4">A4</option>
                                        <option value="A3">A3</option>
                                    </select>
                                    <span class="invalid-feedback format_choisi_err" role="alert">
                                        </span>
                                    </div>
                                </div>

                                @if(isset($param))
                                    @foreach($param as $item)
                                    <p><strong>Option choisie:</strong>  {{ $item->format_choisi ?? null}} </p>
                                @endforeach
                                @endif
                                </td>

                                <td>
                                <div class="row mb-3">
                                <div class="row mb-3">
                                    <div class="col-sm-10">
                                    <input class="form-control" type="file" name="logo" id="logo">
                                    </div>
                                    <span class="invalid-feedback logo_err" role="alert">
                                    </span>
                                </div>
                                
                                </div>


                                <div>
                                
                                    <img src="{{ asset('http://127.0.0.1/ImmobilierApk/storage/app/public/'.get_logo(Auth::user()->iddirection_ref)?->logo_url) }}"  width="100">
                                </div>

                                </td>
                                <td>
                                
                                @can('modifier-parametre')
                                <button  class="btn btn-primary" id="valider"><span class="fa fa-save" id="a"></span><span id="s">Enregistrer</span></button>
                                @endcan
                                </td>
                            </tr>
                            </form>
                            </tbody>
                        </table>
                    </div>


                    <div class="tab-pane fade" id="navs-pills-justified-profile" role="tabpanel">

                        {{-- @if((Auth::user()->type_compte != 'Particulier') && (Auth::user()->is_admin == 1)) --}}

                        <div class="col-md-6">
                            <div class="demo-inline-spacing">
                            <button type="button" class="btn rounded-pill btn-icon btn-outline-primary" data-bs-toggle="modal"
                                data-bs-target="#ajouterAnnexe">
                                <span class="bx bx-plus"></span>
                            </button>
                            </div>
                        </div><br/>

                        {{-- @endif --}}
                    
                        <div class="modal fade" id="ajouterAnnexe" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h5 class="modal-title" id="modalCenterTitle">Modification</h5>
                                    <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
            
                                    <form class="row g-3" method="post" action="javascript:save_annexe();" id="formulaireAnnexe">
                                            @csrf
                                            
                                        <div class="alert-primary bg-primary text-light" id="afficher"></div>
                                        <div class="col-12">
                                        <label for="inputNanme4" class="form-label">Designation<span style="color: red;">*</span></label>
                                        <input type="text" name="designation" class="form-control" id="designation" required="">
                                        <span class="invalid-feedback designation_err" role="alert">
                                                </span>
                                        </div>
                                        <div class="col-12">
                                        <label for="inputEmail4" class="form-label">Adresse<span style="color: red;">*</span></label>
                                        <input type="text" name="adresse" class="form-control" id="adresse" required="">
                                        <span class="invalid-feedback adresse_err" role="alert">
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
                                        <label for="inputAddress" class="form-label">E-mail<span style="color: red;">*</span></label>
                                        <input type="email" name="email" class="form-control"
                                        id="email" required="">
                                        <span class="invalid-feedback email_err" role="alert">
                                                </span>
                                        </div>
                                        </div>
                                        <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" onclick="(this);" id="close" data-bs-dismiss="modal">Fermer</button>
                                        <button  class="btn btn-primary" id="valider"><span class="fa fa-save" id="a"></span><span id="s">Enregistrer</span></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
            
            
                
                            <div class="row">
                                <div class="col-lg-12">
                                
                                    <div class="recent-sales overflow-auto">
                                        <div class="card-body">
                                            <h5 class="card-title text-center">Liste des annexes</h5>
                
                                            <table class="table datatable border-primary">
                                                <thead>
                                                    <tr>
                                                    <th scope="col">Designation</th>
                                                    <th scope="col">Adresse</th>
                                                    <th scope="col">Téléphone</th>
                                                    <th scope="col">E-mail</th>
                                                    <th scope="col">Actions</th>
                                                    <th scope="col"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @can('Consulter-proprietaire')
                                                    @if(isset($liste_annexe))
                                                        @foreach($liste_annexe as $item)
                                                            <tr>
                                                                <th scope="row">{{ $item->designation }}</th>
                                                                <td>{{ $item->siege_social }}</td>
                                                                <td>{{ $item->telephone }}</td>
                                                                <td>{{ $item->email }}</td>
                                                                <td>
                                                                    @can('modify-proprietaire')
                                                                        <a class="btn rounded-pill btn-primary small"
                                                                        title="Modifier" href="#" data-bs-toggle="modal" data-bs-target="#modifier{{$loop->iteration}}">
                                                                            <i class="bx bx-edit-alt me-1"></i>
                                                                        </a>
                                                                    @endcan

                                                                    @can('delete-proprietaire')
                                                                        <a class="btn rounded-pill btn-danger"
                                                                        title="Supprimer" href="#" data-bs-toggle="modal" data-bs-target="#supprimer{{$loop->iteration}}">
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
            
                                    @if(isset($liste_annexe))
                                        @foreach($liste_annexe as $items)
                                            <div class="modal fade" id="supprimer{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-sm" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                        <h5 class="modal-title" id="modalCenterTitle">Suppression</h5>
                                                        <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="row g-3" method="post" action="{{ route('destroy_annexe') }}">
                                                            Voulez-vous vraiment supprimer cette ligne ? 
                                                        @csrf
                                                            <input type="hidden" name="id" class="form-control" id="id" value="{{ $items->idannexes}} ">
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Non</button>
                                                                <button type="submit" class="btn btn-danger" >Oui</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            
                                            <div class="modal fade" id="modifier{{$loop->iteration}}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalCenterTitle">Modification</h5>
                                                            <button type="button"  class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form class="row g-3" method="post" action="{{ route('update_annexe') }}">
                                                                @csrf
                                                            <input type="hidden" name="id" class="form-control" id="id" value="{{ $items->idannexes}} ">
                    
                                                            <div class="col-12">
                                                                <label for="inputNanme4" class="form-label">Designation<span style="color: red;">*</span></label>
                                                                <input type="text" name="designation" value="{{ $items->designation }}" class="form-control" id="designation">
                                                            </div>

                                                            <div class="col-12">
                                                                <label for="inputEmail4" class="form-label">Adresse<span style="color: red;">*</span></label>
                                                                <input type="text" name="adresse" value="{{ $items->siege_social }}" class="form-control" id="adresse">
                                                            </div>

                                                            <div class="col-12">
                                                                <label for="inputPassword4" class="form-label">Téléphone<span style="color: red;">*</span></label>
                                                                <input type="tel" name="telephone" value="{{ $items->telephone }}" class="form-control"  id="telephone_{{ $items->id}}" onload="telephoneMask('telephone_{{ $items->id}}');" onkeydown="limit(this);" onkeyup="limit(this);" onkeypress="return /[0-9]/i.test(event.key)">
                                                            </div>

                                                            <div class="col-12">
                                                                <label for="inputAddress"  class="form-label">E-mal<span style="color: red;">*</span></label>
                                                                <input type="email" name="email" value="{{ $items->email }}" class="form-control" id="email">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                                                <button  class="btn sbg1">Enregistrer</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
            
                    </div>
                </div>
                        

                </div>
            </div>

        </div>
    
    </div>



    
    <script>

        function printErrorMsg(msg) {
            $.each(msg, function(key, value) {
                $('.' + key + '_err').text(value);
            });
        }
    
    
        function save_annexe() {
    
        var data = new FormData();
    
        //Form data
        var form_data = $('#formulaireAnnexe').serializeArray();
        $.each(form_data, function (key, input) {
            data.append(input.name, input.value);
        });
    
    
        $.ajax({
            url: "{{ route('store_annexe') }}",
            method: "POST",
            processData: false,
            contentType: false,
            data: data,
            beforeSend: function(data) {
                $("#ajouterAnnexe button#close").prop("disabled", true);
                $("#ajouterAnnexe button#valider").prop("disabled", true);
                $("#ajouterAnnexe button#valider").html('<i class="text-center fa fa-spinner fa-pulse fa-1x fa-fw ml-2">En cours...</i>');
            },
            success: function(data) {
    
    
                $("#ajouterAnnexe button#close").prop("disabled", false);
                $("#ajouterAnnexe button#valider").prop("disabled", false);
                $("#ajouterAnnexe button#valider").html('Enregistrer');
    
    
                if (!$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error);
                }
                try {
                    if (data.status) {
                    // rempliretableau();
    
                        // alert(data.message);
                        // $("#AjouerProprietaire div#afficher").html(data.message)
                        display_message("Super !!",data.message,"success","btn btn-primary");
    
    
                        $("#ajouterAnnexe form#formulaireAnnexe")[0].reset();
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
    
    
    
    
        function save_parametre() {
            var data = new FormData();
            //Form data
            var form_data = $('#formulaire').serializeArray();
            $.each(form_data, function (key, input) {
                data.append(input.name, input.value);
            });
    
            //File data
            data.append("logo", $('input[name="logo"]')[0].files[0]);
    
            $.ajax({
                url: "{{ route('store_param') }}",
                method: "POST",
                processData: false,
                contentType: false,
                data: data,
                beforeSend: function(data) {
                    $("button#close").prop("disabled", true);
                    $("button#valider").prop("disabled", true);
                    $("button#valider").html('<i class="spinner-border text-light"></i>');
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
    
                            $("form#formulaire")[0].reset();
    
                            setInterval(function(){
                                window.location.reload()
                            }, 2000)
                            
                            display_message("Super !!",data.message,"success","btn btn-primary");
    
    
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