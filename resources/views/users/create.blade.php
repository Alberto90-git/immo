@extends('layouts.template')


@section('content')
    @section('title')
    <title>{{ __('pages.user_create_title') }}</title>
    @endsection

    @include('notification.display_message')


<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('pages.user_create_breadcrumb') }}</span></h4>

        <div class="ms-3 demo-inline-spacing">
            <a href="{{ route('getUserView') }}" class="btn rounded-pill btn-primary">
                <span class="tf-icons bx bx-arrow-back"></span>&nbsp; {{ __('common.btn_back') }}
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
                        <label for="nom" class="col-sm-2 col-form-label">{{ __('pages.user_label_name') }}<span style="color: red;">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control  @error('nom') is-invalid @enderror" id="nom" name="nom" required>
                            <span class="text-danger error-text nom_err small mb-2"></span>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label for="prenom" class="col-sm-2 col-form-label">{{ __('pages.user_label_firstname') }}<span style="color: red;">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control  @error('prenom') is-invalid @enderror" id="prenom" name="prenom" required>
                            <span class="text-danger error-text prenom_err small mb-2"></span>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">{{ __('pages.user_label_email') }}<span style="color: red;">*</span></label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control  @error('email') is-invalid @enderror" id="email" name="email" required>
                            <span class="text-danger error-text email_err small mb-2"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="grade" class="col-sm-2 col-form-label">{{ __('pages.user_label_grade') }}<span style="color: red;">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control  @error('grade') is-invalid @enderror" id="grade" name="grade" required>
                            <span class="text-danger error-text grade_err small mb-2"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">{{ __('pages.user_label_roles') }}<span style="color: red;">*</span></label>
                        <div class="col-sm-6">
                            {!! Form::select('roles[]', $roles, [], ['class' => 'form-control', 'required']) !!}
                        </div>
                    </div>


                    <div class="mt-2 text-center">
                        <button class="btn btn-primary" id="valider" type="submit">
                            <span id="s">{{ __('common.btn_save') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>


</div>




<script type="text/javascript">
var USER_SAVE_LBL     = '{{ __('common.btn_save') }}';
var USER_IN_PROGRESS  = '{{ __('pages.owner_in_progress') }}';
var USER_SUCCESS_LBL  = '{{ __('common.swal_success') }}';
var USER_ERROR_LBL    = '{{ __('common.swal_error') }}';
var USER_ERR_MSG      = '{{ __('common.swal_generic_error') }}';

    function printErrorMsg(msg) {
        var items = [];
        for (var key in msg) {
            if (!msg.hasOwnProperty(key)) continue;
            var value = Array.isArray(msg[key]) ? msg[key][0] : msg[key];
            $('.' + key + '_err').text(value).show();
            var elmnt = $('.' + key + '_err');
            if (elmnt.length) items.push(elmnt);
        }
        if (items.length > 0 && items[0].get(0)) {
            items[0].get(0).scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function save_user() {
        var donnees = $("form#save_user").serialize();

        $.ajax({
            url: "{{ route('saveUser') }}",
            method: 'POST',
            data: donnees,
            beforeSend: function () {
                $("#valider").attr("disabled", true);
                $("#s").html("<i class='spinner-border spinner-border-sm'></i> " + USER_IN_PROGRESS);
            },
            success: function (data) {
                if (data.error && !$.isEmptyObject(data.error)) {
                    printErrorMsg(data.error);
                    return;
                }
                if (data.status) {
                    display_message(USER_SUCCESS_LBL + " !", data.message, "success", "btn btn-success");
                    $("#save_user")[0].reset();
                } else {
                    display_message(USER_ERROR_LBL + " !", data.message, "warning", "btn btn-danger");
                }
            },
            error: function (xhr) {
                var msg = USER_ERR_MSG;
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                display_message(USER_ERROR_LBL + " !", msg, "error", "btn btn-danger");
            },
            complete: function () {
                $("#valider").attr("disabled", false);
                $("#s").html(USER_SAVE_LBL);
            }
        });
    }
</script>
@endsection
