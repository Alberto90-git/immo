@extends('layouts.template')

@section('title')
<title>{{ __('pages.role_title') }}</title>
@endsection

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">

    @include('notification.display_message')

    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('common.home_breadcrumb') }}</span> {{ __('pages.role_edit_breadcrumb') }}</h4>

    <div class="card">

        @can('liste-role')
            <div class="ms-3 demo-inline-spacing">
                <a href="{{ route('roles.index') }}" class="btn rounded-pill btn-primary">
                    <span class="tf-icons bx bx-arrow-back"></span>&nbsp; {{ __('pages.role_btn_list_fn') }}
                </a>
            </div>
        @endcan

        <form id="formEditRole" action="javascript:update_role();">
            @csrf
            <input type="hidden" name="_method" value="PATCH">
            <input type="hidden" name="role_id" value="{{ encrypt_id($role->id) }}">

            <div class="col-md-6 p-3">
                <div class="form-group ms-3">
                    <label for="roleName" class="form-label">{{ __('pages.role_label_name_fn') }} <span style="color: red;">*</span></label>
                    <input type="text" name="name" id="roleName" class="form-control"
                           placeholder="{{ __('pages.role_ph_name_fn') }}" autocomplete="off" value="{{ $role->name }}">
                    <span class="text-danger small name_err"></span>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-borderless border-bottom">
                    <thead>
                        <tr>
                            <th class="text-nowrap">{{ __('pages.role_th_menu') }}</th>
                            <th class="text-nowrap text-center">{{ __('pages.role_th_perm') }}</th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td class="text-nowrap">{{ __('pages.role_menu_config') }}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllParametrage" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
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
                            <td class="text-nowrap">{{ __('pages.role_menu_owner') }}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllProprietaire" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
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
                            <td class="text-nowrap">{{ __('pages.role_menu_house') }}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllMaison" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
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
                            <td class="text-nowrap">{{ __('pages.role_menu_room') }}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllChambre" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
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
                            <td class="text-nowrap">{{ __('pages.role_menu_price') }}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllPrix" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
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
                            <td class="text-nowrap">{{ __('pages.role_menu_tenant') }}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllLocataire" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
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
                            <td class="text-nowrap">{{ __('pages.role_menu_payment') }}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllPaiement" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
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
                            <td class="text-nowrap">{{ __('pages.role_menu_stats') }}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllSta" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
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
                            <td class="text-nowrap">{!! __('pages.role_menu_listings') !!}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllDossier" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
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
                            <td class="text-nowrap">{{ __('pages.role_menu_ads') }}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllPub" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
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
                            <td class="text-nowrap">{{ __('pages.role_menu_subs') }}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllAbonnement" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
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
                            <td class="text-nowrap">États des lieux</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllEtatDesLieux" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissionEtatDesLieux as $p)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $p->id }}"
                                                    class="form-check-input permission-etat-des-lieux"
                                                    {{ in_array($p->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $p->label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-nowrap">Maintenance</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllMaintenance" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
                                    </label>
                                </div>
                                <div class="d-flex flex-wrap">
                                    @foreach($permissionMaintenance as $pM)
                                        <div class="form-check form-check-inline">
                                            <label class="form-check-label text-dark">
                                                <input type="checkbox" name="permission[]" value="{{ $pM->id }}"
                                                    class="form-check-input permission-maintenance"
                                                    {{ in_array($pM->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $pM->label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-nowrap">{{ __('pages.role_menu_comm') }}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllEnvoi" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
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
                            <td class="text-nowrap">{!! __('pages.role_menu_perms') !!}</td>
                            <td>
                                <div class="form-check form-check-inline">
                                    <label class="form-check-label text-dark">
                                        <input type="checkbox" id="selectAllUser" class="form-check-input select-all"> {{ __('pages.role_perm_all') }}
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
                            <span id="btnUpdateText">{{ __('common.btn_save') }}</span>
                        </button>
                    </div>
                @endcan
            </div>

        </form>

    </div>

</div>

<script>
    var ROLE_I18N = {
        inProgress: '<span class="spinner-border spinner-border-sm me-1"></span>{{ __('pages.owner_in_progress') }}',
        btnSave:    '{{ __('common.btn_save') }}',
        warning:    '{{ __('common.swal_warning') }}',
        success:    '{{ __('common.swal_success') }} !',
        error:      '{{ __('common.swal_error') }}',
        updateErr:  '{{ __('pages.role_update_error') }}',
    };

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
    setupSelectAll('selectAllEtatDesLieux',  'permission-etat-des-lieux');
    setupSelectAll('selectAllMaintenance',  'permission-maintenance');
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
                $('#btnUpdateText').html(ROLE_I18N.inProgress);
            },
            success: function (data) {
                if (data.error && !$.isEmptyObject(data.error)) {
                    if (data.error.name) {
                        $('#roleName').addClass('is-invalid');
                        $('.name_err').text(Array.isArray(data.error.name) ? data.error.name[0] : data.error.name);
                    }
                    if (data.error.permission) {
                        var msg = Array.isArray(data.error.permission) ? data.error.permission[0] : data.error.permission;
                        display_message(ROLE_I18N.warning, msg, 'warning', 'btn btn-warning');
                    }
                    return;
                }
                display_message(ROLE_I18N.success, data.message, 'success', 'btn btn-primary');
            },
            error: function (xhr) {
                var msg = ROLE_I18N.updateErr;
                if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                display_message(ROLE_I18N.error, msg, 'error', 'btn btn-danger');
            },
            complete: function () {
                $('#btnUpdateRole').prop('disabled', false);
                $('#btnUpdateText').text(ROLE_I18N.btnSave);
            }
        });
    }

    $('#roleName').on('input', function () {
        $(this).removeClass('is-invalid');
        $('.name_err').text('');
    });

</script>

@endsection
