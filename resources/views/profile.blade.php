@extends('layouts.template')

@section('content')

@section('title')
  <title>{{ __('pages.profile_title') }}</title>
@endsection

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">{{ __('pages.profile_breadcrumb') }}</span> {{ __('pages.profile_title') }}</h4>

    <div class="row">
      <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-md-row mb-3" id="profileTabs">
          <li class="nav-item">
            <a class="nav-link active" id="tab-account" href="javascript:void(0);" onclick="showTab('account')">
              <i class="bx bx-user me-1"></i> {{ __('pages.profile_tab_info') }}
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="tab-password" href="javascript:void(0);" onclick="showTab('password')">
              <i class="bx bx-lock-alt me-1"></i> {{ __('pages.profile_tab_password') }}
            </a>
          </li>
        </ul>

        <!-- Section Informations personnelles -->
        <div id="section-account">
          <div class="card mb-4">
            <h5 class="card-header">{{ __('pages.profile_card_info') }}</h5>
            <div class="card-body">
              <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
                <div class="avatar avatar-xl">
                  <span class="avatar-initial rounded-circle bg-primary" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; font-size: 2rem;">
                    {{ strtoupper(substr(Auth::user()->nom, 0, 1)) }}{{ strtoupper(substr(Auth::user()->prenom, 0, 1)) }}
                  </span>
                </div>
                <div>
                  <h5 class="mb-1">{{ Auth::user()->nom }} {{ Auth::user()->prenom }}</h5>
                  <p class="text-muted mb-0">{{ Auth::user()->grade }}</p>
                </div>
              </div>
              <hr class="my-0 mb-4" />

              <div id="profileAlertSuccess" class="alert alert-success d-none" role="alert"></div>
              <div id="profileAlertError" class="alert alert-danger d-none" role="alert"></div>

              <form id="formProfileUpdate" onsubmit="return false">
                @csrf
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                <div class="row">
                  <div class="mb-3 col-md-6">
                    <label for="nom" class="form-label">{{ __('pages.profile_lastname') }}</label>
                    <input
                      class="form-control"
                      type="text"
                      id="nom"
                      name="nom"
                      value="{{ Auth::user()->nom }}"
                      required
                    />
                    <div class="invalid-feedback" id="error-nom"></div>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="prenom" class="form-label">{{ __('pages.profile_firstname') }}</label>
                    <input
                      class="form-control"
                      type="text"
                      id="prenom"
                      name="prenom"
                      value="{{ Auth::user()->prenom }}"
                      required
                    />
                    <div class="invalid-feedback" id="error-prenom"></div>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="email" class="form-label">{{ __('pages.profile_email') }}</label>
                    <input
                      class="form-control"
                      type="email"
                      id="email"
                      name="email"
                      value="{{ Auth::user()->email }}"
                      readonly
                      disabled
                      style="background-color: #f5f5f9;"
                    />
                    <small class="text-muted">{{ __('pages.profile_email_readonly') }}</small>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="grade" class="form-label">{{ __('pages.profile_grade') }}</label>
                    <input
                      class="form-control"
                      type="text"
                      id="grade"
                      name="grade"
                      value="{{ Auth::user()->grade }}"
                      required
                    />
                    <div class="invalid-feedback" id="error-grade"></div>
                  </div>
                </div>
                <div class="mt-2">
                  <button type="submit" class="btn btn-primary me-2" id="btnSaveProfile">
                    <span class="spinner-border spinner-border-sm d-none" id="spinnerProfile" role="status"></span>
                    {{ __('common.btn_save') }}
                  </button>
                  <button type="reset" class="btn btn-outline-secondary">{{ __('common.btn_cancel') }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- Section Mot de passe -->
        <div id="section-password" style="display: none;">
          <div class="card mb-4">
            <h5 class="card-header">{{ __('pages.profile_card_password') }}</h5>
            <div class="card-body">
              <div class="alert alert-info mb-4">
                <i class="bx bx-info-circle me-1"></i>
                {{ __('pages.profile_pwd_rules') }}
              </div>

              <div id="passwordAlertSuccess" class="alert alert-success d-none" role="alert"></div>
              <div id="passwordAlertError" class="alert alert-danger d-none" role="alert"></div>

              <form id="formPasswordChange" onsubmit="return false">
                @csrf
                <div class="row">
                  <div class="mb-3 col-md-6">
                    <label for="Ancien_mot_de_passe" class="form-label">{{ __('pages.profile_old_pwd') }}</label>
                    <div class="input-group">
                      <input
                        class="form-control"
                        type="password"
                        id="Ancien_mot_de_passe"
                        name="Ancien_mot_de_passe"
                        required
                      />
                      <button class="btn btn-outline-secondary toggle-password" type="button" data-target="Ancien_mot_de_passe">
                        <i class="bx bx-hide"></i>
                      </button>
                    </div>
                    <div class="invalid-feedback" id="error-Ancien_mot_de_passe"></div>
                  </div>
                </div>
                <div class="row">
                  <div class="mb-3 col-md-6">
                    <label for="Nouveau_mot_de_passe" class="form-label">{{ __('pages.profile_new_pwd') }}</label>
                    <div class="input-group">
                      <input
                        class="form-control"
                        type="password"
                        id="Nouveau_mot_de_passe"
                        name="Nouveau_mot_de_passe"
                        required
                      />
                      <button class="btn btn-outline-secondary toggle-password" type="button" data-target="Nouveau_mot_de_passe">
                        <i class="bx bx-hide"></i>
                      </button>
                    </div>
                    <div class="invalid-feedback" id="error-Nouveau_mot_de_passe"></div>
                  </div>
                  <div class="mb-3 col-md-6">
                    <label for="Confirmer_mot_de_passe" class="form-label">{{ __('pages.profile_confirm_pwd') }}</label>
                    <div class="input-group">
                      <input
                        class="form-control"
                        type="password"
                        id="Confirmer_mot_de_passe"
                        name="Confirmer_mot_de_passe"
                        required
                      />
                      <button class="btn btn-outline-secondary toggle-password" type="button" data-target="Confirmer_mot_de_passe">
                        <i class="bx bx-hide"></i>
                      </button>
                    </div>
                    <div class="invalid-feedback" id="error-Confirmer_mot_de_passe"></div>
                  </div>
                </div>
                <div class="mt-2">
                  <button type="submit" class="btn btn-primary me-2" id="btnChangePassword">
                    <span class="spinner-border spinner-border-sm d-none" id="spinnerPassword" role="status"></span>
                    {{ __('pages.profile_btn_pwd') }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

      </div>
    </div>
</div>

<script>
  var PROFILE_FIX_ERRORS   = '{{ __('pages.profile_fix_errors') }}';
  var PROFILE_GENERIC_ERR  = '{{ __('pages.profile_generic_error') }}';
  var PROFILE_RETRY_ERR    = '{{ __('pages.profile_retry_error') }}';

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  // Navigation entre les onglets
  function showTab(tab) {
    document.getElementById('section-account').style.display = tab === 'account' ? 'block' : 'none';
    document.getElementById('section-password').style.display = tab === 'password' ? 'block' : 'none';
    document.getElementById('tab-account').classList.toggle('active', tab === 'account');
    document.getElementById('tab-password').classList.toggle('active', tab === 'password');
  }

  // Ouvrir l'onglet mot de passe si le hash est #password
  if (window.location.hash === '#password') {
    showTab('password');
  }

  // Toggle visibilité mot de passe
  document.querySelectorAll('.toggle-password').forEach(function(btn) {
    btn.addEventListener('click', function() {
      var targetId = this.getAttribute('data-target');
      var input = document.getElementById(targetId);
      var icon = this.querySelector('i');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('bx-hide');
        icon.classList.add('bx-show');
      } else {
        input.type = 'password';
        icon.classList.remove('bx-show');
        icon.classList.add('bx-hide');
      }
    });
  });

  // Mise à jour du profil
  $('#formProfileUpdate').on('submit', function(e) {
    e.preventDefault();

    // Reset erreurs
    $('.form-control').removeClass('is-invalid');
    $('#profileAlertSuccess').addClass('d-none');
    $('#profileAlertError').addClass('d-none');
    $('#spinnerProfile').removeClass('d-none');
    $('#btnSaveProfile').prop('disabled', true);

    $.ajax({
      url: '{{ route("modifier_profile") }}',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(data) {
        $('#spinnerProfile').addClass('d-none');
        $('#btnSaveProfile').prop('disabled', false);

        if (data.error) {
          var errors = data.error;
          for (var field in errors) {
            $('#' + field).addClass('is-invalid');
            $('#error-' + field).text(errors[field][0]);
          }
          $('#profileAlertError').removeClass('d-none').text(PROFILE_FIX_ERRORS);
        } else if (data.status) {
          $('#profileAlertSuccess').removeClass('d-none').text(data.message);
          setTimeout(function() { location.reload(); }, 1500);
        } else {
          $('#profileAlertError').removeClass('d-none').text(data.message || PROFILE_GENERIC_ERR);
        }
      },
      error: function() {
        $('#spinnerProfile').addClass('d-none');
        $('#btnSaveProfile').prop('disabled', false);
        $('#profileAlertError').removeClass('d-none').text(PROFILE_RETRY_ERR);
      }
    });
  });

  // Changement de mot de passe
  $('#formPasswordChange').on('submit', function(e) {
    e.preventDefault();

    // Reset erreurs
    $('.form-control').removeClass('is-invalid');
    $('#passwordAlertSuccess').addClass('d-none');
    $('#passwordAlertError').addClass('d-none');
    $('#spinnerPassword').removeClass('d-none');
    $('#btnChangePassword').prop('disabled', true);

    $.ajax({
      url: '{{ route("modifierPassword") }}',
      type: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function(data) {
        $('#spinnerPassword').addClass('d-none');
        $('#btnChangePassword').prop('disabled', false);

        if (data.error) {
          var errors = data.error;
          for (var field in errors) {
            $('#' + field).addClass('is-invalid');
            $('#error-' + field).text(errors[field][0]);
          }
          $('#passwordAlertError').removeClass('d-none').text(PROFILE_FIX_ERRORS);
        } else if (data.status) {
          $('#passwordAlertSuccess').removeClass('d-none').text(data.message);
          document.getElementById('formPasswordChange').reset();
        } else {
          $('#passwordAlertError').removeClass('d-none').text(data.message || PROFILE_GENERIC_ERR);
        }
      },
      error: function(xhr) {
        $('#spinnerPassword').addClass('d-none');
        $('#btnChangePassword').prop('disabled', false);

        if (xhr.status === 422) {
          var errors = xhr.responseJSON.errors;
          for (var field in errors) {
            $('#' + field).addClass('is-invalid');
            $('#error-' + field).text(errors[field][0]);
          }
          $('#passwordAlertError').removeClass('d-none').text(PROFILE_FIX_ERRORS);
        } else {
          $('#passwordAlertError').removeClass('d-none').text(PROFILE_RETRY_ERR);
        }
      }
    });
  });
</script>
@endsection
