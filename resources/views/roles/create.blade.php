@extends('layouts.template')


@section('content')

@section('title')
<title>Gestion fonction</title>
@endsection


<div class="container-xxl flex-grow-1 container-p-y">

    @include('notification.display_message')

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span>Gestion fonction / Liste des permissions</h4>

    <div class="card">
        <!-- Notifications -->
        @can('liste-role')
            <div class="ms-3 demo-inline-spacing">
                <a href="{{ route('roles.index') }}" class="btn rounded-pill btn-primary">
                    <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Consulter la liste des fonctions
                </a>
            </div>
        @endcan

        @if (count($errors) > 0)
            <div class="col-md-6 p-4">
                <div class="toast-container">
                <div class="bs-toast toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-semibold">Quelques erreurs</div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                </div>
            </div>
        @endif


        <form id="formRole" action="javascript:save_role();">
            @csrf

        <div class="col-md-6 p-3">
            <div class="form-group ms-3">
                <label for="roleName" class="form-label">Nom fonction <span style="color: red;">*</span></label>
                <input type="text" name="name" id="roleName" class="form-control" placeholder="Nom fonction" autocomplete="off">
                <span class="text-danger small name_err"></span>
            </div>
        </div>

        <div class="table-responsive">

            <table class="table table-hover">
            <thead>
              <tr>
                <th class="text-nowrap">Menu</th>
                <th class="text-nowrap text-center">Liste des permissions</th>
             </tr>
            </thead>
            <tbody>
                


                <tr>
                    <td class="text-nowrap">Paramétrage</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllParametrage" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionParametrage as $valueParams)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $valueParams->id, false, ['class' => 'form-check-input permission-parametrage']) }}
                                        {{ $valueParams->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                
                <tr>
                    <td class="text-nowrap">Propriétaire</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllProprietaire" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionProprio as $valueProprio)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $valueProprio->id, false, ['class' => 'form-check-input permission-proprietaire']) }}
                                        {{ $valueProprio->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>


                <tr>
                    <td class="text-nowrap">Maison</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllMaison" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionMaison as $permissionMaison)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionMaison->id, false, ['class' => 'form-check-input permission-maison']) }}
                                        {{ $permissionMaison->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">Chambre</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllChambre" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionChambre as $permissionChambre)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionChambre->id, false, ['class' => 'form-check-input permission-chambre']) }}
                                        {{ $permissionChambre->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">Prix</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllPrix" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionPrix as $permissionPrix)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionPrix->id, false, ['class' => 'form-check-input permission-prix']) }}
                                        {{ $permissionPrix->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">Locataire</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllLocataire" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionLocataire as $permissionLocataire)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionLocataire->id, false, ['class' => 'form-check-input permission-locataire']) }}
                                        {{ $permissionLocataire->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>


                <tr>
                    <td class="text-nowrap">Paiement</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllPaiement" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionPaiement as $permissionPaiement)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionPaiement->id, false, ['class' => 'form-check-input permission-paiement']) }}
                                        {{ $permissionPaiement->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">Statistique</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllSta" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionStatistique as $permissionStatistique)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionStatistique->id, false, ['class' => 'form-check-input permission-sta']) }}
                                        {{ $permissionStatistique->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">Gestion des besoins & annoces</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllDossier" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionDossier as $permissionDossier)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionDossier->id, false, ['class' => 'form-check-input permission-dossier']) }}
                                        {{ $permissionDossier->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">Gestion des abonnements</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllAbonnement" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($abonnement as $abonnement)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $abonnement->id, false, ['class' => 'form-check-input permission-abonnement']) }}
                                        {{ $abonnement->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>


                <tr>
                    <td class="text-nowrap">Gestion publicité</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllPub" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permissionPub as $permissionPub)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permissionPub->id, false, ['class' => 'form-check-input permission-pub']) }}
                                        {{ $permissionPub->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>


                <tr>
                    <td class="text-nowrap">Communication</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllEnvoi" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>

                        <div class="d-flex flex-wrap">
                            @foreach($permissionEnvoi as $pEnvoi)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $pEnvoi->id, false, ['class' => 'form-check-input permission-envoi']) }}
                                        {{ $pEnvoi->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="text-nowrap">Permission fonction & utilisateur</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <label class="form-check-label text-dark">
                                <input type="checkbox" id="selectAllUser" class="form-check-input select-all">
                                Tout sélectionner
                            </label>
                        </div>
                
                        <div class="d-flex flex-wrap">
                            @foreach($permission as $permission)
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        {{ Form::checkbox('permission[]', $permission->id, false, ['class' => 'form-check-input permission-user']) }}
                                        {{ $permission->label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                
            </tbody>
          </table>
        </div>
        <div class="card-body">
            @can('ajouter-role')
                <div class="mt-4">
                    <button type="submit" id="btnSaveRole" class="btn btn-primary me-2">
                        <span id="btnRoleText">Enregistrer</span>
                    </button>
                </div>
            @endcan
        </div>
        </form>
        <!-- /Notifications -->
      </div>


</div>



<script>

    /* ── "Tout sélectionner" ── */
    function setupSelectAll(selectAllId, checkboxClass) {
        var selectAllCheckbox = document.getElementById(selectAllId);
        if (!selectAllCheckbox) return;
        var checkboxes = document.querySelectorAll('.' + checkboxClass);
        selectAllCheckbox.addEventListener('change', function () {
            checkboxes.forEach(function (cb) { cb.checked = selectAllCheckbox.checked; });
        });
    }

    setupSelectAll('selectAllParametrage',  'permission-parametrage');
    setupSelectAll('selectAllProprietaire', 'permission-proprietaire');
    setupSelectAll('selectAllMaison',       'permission-maison');
    setupSelectAll('selectAllChambre',      'permission-chambre');
    setupSelectAll('selectAllPrix',         'permission-prix');
    setupSelectAll('selectAllLocataire',    'permission-locataire');
    setupSelectAll('selectAllPaiement',     'permission-paiement');
    setupSelectAll('selectAllSta',          'permission-sta');
    setupSelectAll('selectAllDossier',      'permission-dossier');
    setupSelectAll('selectAllAbonnement',   'permission-abonnement');
    setupSelectAll('selectAllPub',          'permission-pub');
    setupSelectAll('selectAllEnvoi',        'permission-envoi');
    setupSelectAll('selectAllUser',         'permission-user');

    /* ── Soumission AJAX ── */
    function save_role() {
        // Effacer les erreurs précédentes
        $('.name_err').text('');
        $('#roleName').removeClass('is-invalid');
        $('.permission_err').text('');

        var formData = $('#formRole').serialize();

        $.ajax({
            url: '{{ route('roles.store') }}',
            method: 'POST',
            data: formData,
            beforeSend: function () {
                $('#btnSaveRole').prop('disabled', true);
                $('#btnRoleText').html('<span class="spinner-border spinner-border-sm me-1"></span>En cours...');
            },
            success: function (data) {
                if (data.error && !$.isEmptyObject(data.error)) {
                    if (data.error.name) {
                        $('#roleName').addClass('is-invalid');
                        $('.name_err').text(Array.isArray(data.error.name) ? data.error.name[0] : data.error.name);
                    }
                    if (data.error.permission) {
                        var msg = Array.isArray(data.error.permission) ? data.error.permission[0] : data.error.permission;
                        display_message('Attention', msg, 'warning', 'btn btn-warning');
                    }
                    return;
                }
                display_message('Succès !', data.message, 'success', 'btn btn-primary');
                $('#formRole')[0].reset();
                // Décocher toutes les cases
                $('#formRole input[type="checkbox"]').prop('checked', false);
            },
            error: function (xhr) {
                var msg = 'Une erreur s\'est produite.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                display_message('Erreur', msg, 'error', 'btn btn-danger');
            },
            complete: function () {
                $('#btnSaveRole').prop('disabled', false);
                $('#btnRoleText').text('Enregistrer');
            }
        });
    }

    /* Effacer l'erreur nom dès que l'utilisateur retape */
    $('#roleName').on('input', function () {
        $(this).removeClass('is-invalid');
        $('.name_err').text('');
    });

</script>

@endsection