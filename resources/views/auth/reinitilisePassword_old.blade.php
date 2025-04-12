<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Changer mot de passe - Immo Manager</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link rel="shortcut icon" sizes="32x32" href="{{{ asset('favicon-32x32.png') }}}">

    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>

<body>

    <main>
        <div class="container">

            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            <div class="card mb-3">

                                <div class="card-body">

                                    <div class="pt-4 pb-2" id="screeresult">
                                        <h5 class="card-title text-center pb-0 fs-4">Changer votre mot de passe</h5>
                                    </div>


                                    <form class="row g-3" action="{{ route('changementPwd') }}" method="post">
                                        @csrf

                                       <input type="hidden" name="email" value="{{ $email }}" /> 

                                        <div class="col-12">
                                            <label for="Nouveau_mot_de_passe" class="form-label">Nouveau mot de passe</label>
                                            <div class="input-group has-validation">
                                                <input id="Nouveau_mot_de_passe" type="password" class="form-control @error('Nouveau_mot_de_passe') is-invalid @enderror" name="Nouveau_mot_de_passe" required>
                                                {{-- <span toggle="#Nouveau_mot_de_passe" class="input-group-text ri-eye-fill field-icon toggle-Nouveau_mot_de_passe"></span> --}}
                                                @error('Nouveau_mot_de_passe')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-12">
                                            <label for="confirm_mot_de_passe" class="form-label">Répéter mot de passe</label>
                                            <div class="input-group has-validation">
                                                <input id="confirm_mot_de_passe" type="password" class="form-control @error('confirm_mot_de_passe') is-invalid @enderror" name="confirm_mot_de_passe" required>
                                                {{-- <span toggle="#confirm_mot_de_passe" class="input-group-text ri-eye-fill field-icon toggle-confirm_mot_de_passe"></span> --}}
                                                @error('confirm_mot_de_passe')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <button class="btn shadow w-100 text-white" id="form_submit1" type="submit" name="Confirmer1" style="background-color: #012970;">Modifier</button>
                                        </div>

                                    </form>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </section>

        </div>
    </main><!-- End #main -->


  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/js/vendor/jquery-2.2.4.min.js') }}"></script>

  <script type="text/javascript">

   cacheViewPwd('Nouveau_mot_de_passe');
   cacheViewPwd('confirm_mot_de_passe');


    function cacheViewPwd(element) {

      $(".toggle-" + element).click(function() {

          $(this).toggleClass("ri-eye-off-fill");
          var input = $($(this).attr("toggle"));

          if (input.attr("type") == "password") {
            input.attr("type", "text");
          } else {
            input.attr("type", "password");
          }
      });
    }


  </script>
    

</body>

</html>