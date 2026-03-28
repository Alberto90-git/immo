@extends('layouts.template')

@section('title')
<title>Gestion fonction</title>
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    @include('notification.display_message')

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Accueil /</span> Gestion fonction / Modification</h4>

    <div class="card">

        @can('liste-role')
            <div class="ms-3 demo-inline-spacing">
                <a href="{{ route('roles.index') }}" class="btn rounded-pill btn-primary">
                    <span class="tf-icons bx bx-arrow-back"></span>&nbsp; Consulter la liste des fonctions
                </a>
            </div>
        @endcan

        <form id="formEditRole" action="javascript:update_role();">
            @csrf
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="role_id" value="{{ encrypt_id($role->id) }}">

            <div class="col-md-6 p-3">
                <div class="form-group ms-3">
                    <label for="roleName" class="form-label">Nom fonction <span style="color: red;">*</span></label>
                    <input type="text" name="name" id="roleName" class="form-control"
                           placeholder="Nom fonction" autocomplete="off" value="{{ $role->name }}">
                    <span class="text-danger small name_err"></span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-borderless border-bottom">
                    <thead>
                        <tr>
                            <th class="text-nowrap">MENU</th>
                            <th class="text-nowrap text-center">Liste des permissions</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td class="text-nowrap">Paramétrage</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllParametrage" class="form-check-input select-all"> Tout sélectionner
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissionParametrage as $p)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                                                    class="form-check-input permission-parametrage"
                                                    {{ in_array($p->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $p->label }}
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
                                        <input type="checkbox" id="selectAllProprietaire" class="form-check-input select-all"> Tout sélectionner
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissionProprio as $p)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                                                    class="form-check-input permission-proprietaire"
                                                    {{ in_array($p->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $p->label }}
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
                                        <input type="checkbox" id="selectAllMaison" class="form-check-input select-all"> Tout sélectionner
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissionMaison as $p)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                                                    class="form-check-input permission-maison"
                                                    {{ in_array($p->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $p->label }}
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
                                        <input type="checkbox" id="selectAllChambre" class="form-check-input select-all"> Tout sélectionner
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissionChambre as $p)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                                                    class="form-check-input permission-chambre"
                                                    {{ in_array($p->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $p->label }}
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
                                        <input type="checkbox" id="selectAllPrix" class="form-check-input select-all"> Tout sélectionner
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissionPrix as $p)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                                                    class="form-check-input permission-prix"
                                                    {{ in_array($p->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $p->label }}
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
                                        <input type="checkbox" id="selectAllLocataire" class="form-check-input select-all"> Tout sélectionner
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissionLocataire as $p)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                                                    class="form-check-input permission-locataire"
                                                    {{ in_array($p->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $p->label }}
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
                                        <input type="checkbox" id="selectAllPaiement" class="form-check-input select-all"> Tout sélectionner
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissionPaiement as $p)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                                                    class="form-check-input permission-paiement"
                                                    {{ in_array($p->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $p->label }}
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
                                        <input type="checkbox" id="selectAllSta" class="form-check-input select-all"> Tout sélectionner
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissionStatistique as $p)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                                                    class="form-check-input permission-sta"
                                                    {{ in_array($p->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $p->label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-nowrap">Gestion des besoins &amp; annonces</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllDossier" class="form-check-input select-all"> Tout sélectionner
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissionDossier as $p)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                                                    class="form-check-input permission-dossier"
                                                    {{ in_array($p->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $p->label }}
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
                                        <input type="checkbox" id="selectAllPub" class="form-check-input select-all"> Tout sélectionner
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissionPub as $p)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                                                    class="form-check-input permission-pub"
                                                    {{ in_array($p->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $p->label }}
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
                                        <input type="checkbox" id="selectAllAbonnement" class="form-check-input select-all"> Tout sélectionner
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($abonnement as $p)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                                                    class="form-check-input permission-abonnement"
                                                    {{ in_array($p->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $p->label }}
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
                                        <input type="checkbox" id="selectAllEnvoi" class="form-check-input select-all"> Tout sélectionner
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissionEnvoi as $pEnvoi)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                {{ Form::checkbox('permission[]', $pEnvoi->id, in_array($pEnvoi->id, $rolePermissions), ['class' => 'form-check-input permission-envoi']) }}
                                                {{ $pEnvoi->label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-nowrap">Permission fonction &amp; utilisateur</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllUser" class="form-check-input select-all"> Tout sélectionner
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permission as $p)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                                                    class="form-check-input permission-user"
                                                    {{ in_array($p->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $p->label }}
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
                @can('modifier-role')
                    <div class="mt-4">
                        <button type="submit" id="btnUpdateRole" class="btn btn-primary me-2">
                            <span id="btnUpdateText">Enregistrer</span>
                        </button>
                    </div>
                @endcan
            </div>

        </form>

    </div>

</div>

<script>

    /* ── "Tout sélectionner" ── */
    function setupSelectAll(selectAllId, checkboxClass) {
        var sa = document.getElementById(selectAllId);
        if (!sa) return;
        var boxes = document.querySelectorAll('.' + checkboxClass);

        function syncSelectAll() {
            sa.checked = boxes.length > 0 && Array.from(boxes).every(function (cb) { return cb.checked; });
        }

        sa.addEventListener('change', function () {
            boxes.forEach(function (cb) { cb.checked = sa.checked; });
        });
        boxes.forEach(function (cb) { cb.addEventListener('change', syncSelectAll); });
        syncSelectAll();
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
    setupSelectAll('selectAllPub',          'permission-pub');
    setupSelectAll('selectAllAbonnement',   'permission-abonnement');
    setupSelectAll('selectAllEnvoi',        'permission-envoi');
    setupSelectAll('selectAllUser',         'permission-user');

    /* ── Soumission AJAX ── */
    function update_role() {
        $('.name_err').text('');
        $('#roleName').removeClass('is-invalid');

        var roleId   = $('input[name="role_id"]').val();
        var formData = $('#formEditRole').serialize();

        $.ajax({
            url: '{{ url('roles') }}/' + roleId,
            method: 'POST',
            data: formData,
            beforeSend: function () {
                $('#btnUpdateRole').prop('disabled', true);
                $('#btnUpdateText').html('<span class="spinner-border spinner-border-sm me-1"></span>En cours...');
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
            },
            error: function (xhr) {
                var msg = 'Une erreur s\'est produite.';
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                display_message('Erreur', msg, 'error', 'btn btn-danger');
            },
            complete: function () {
                $('#btnUpdateRole').prop('disabled', false);
                $('#btnUpdateText').text('Enregistrer');
            }
        });
    }

    $('#roleName').on('input', function () {
        $(this).removeClass('is-invalid');
        $('.name_err').text('');
    });

</script>

@endsection
