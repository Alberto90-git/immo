<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Connexion - Immo Manager</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link rel="shortcut icon" sizes="32x32" href="{{{ asset('favicon-32x32.png') }}}">


   <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">


  <!-- Vendor CSS Files -->
  <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

</head>
<!--<style type="text/css">
  body {
    background-image: url('news.jpg');
  }
</style>-->
<body>

  <main >
        <div class="container">
            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                            @if (Session::has("success"))
                              <div class="alert alert-success bg-success text-light border-0 alert-dismissible fade show" )>
                                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  {{ Session::get('success') }}
                              </div>
                              @elseif (Session::has("failed"))
                              <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show">
                                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                  {{ Session::get('failed') }}
                              </div>
                            @endif

                            <div class="d-flex justify-content-center py-4">
                              
                                <span class="d-none d-lg-block"><h4>Bienvenue sur <strong>Immo Manager</strong> </h4></span>
                             
                                
                            </div>

                            <div class="card mb-3">

                                <div class="card-body">
                                    <div class="pt-4 pb-2">
                                      <h5 class="card-title text-center pb-0 fs-4">Connectez-vous à votre espace</h5>
                                    </div>

                                    <form class="row g-3" method="POST" action="{{ route('login_check') }}">
                                        @csrf

                                        <div class="col-12">
                                            <label for="yourUsername" class="form-label">Email<span style="color: red;">*</span></label>
                                            <div class="input-group has-validation">
                                                <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                                required  autofocus>
                                                @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                          

                                        <div class="mb-1">
                                          <label for="yourPassword" class="form-label">Mot de passe<span style="color: red;">*</span></label>
                                            <div class="col-md-6 col-lg-6 input-group">
                                              <input type="password" class="form-control" id="password" name="password" aria-describedby="inputGroupPrepend2">
                                              <span toggle="#password" class="input-group-text  ri-eye-off-fill field-icon toggle-password"></span>
                                              <span class="invalid-feedback Ancien_mot_de_passe_err" role="alert">
                                            </span>
                                            </div>
                                         </div>

                                       
                                        <div class="col-12">
                                            <button class="btn shadow w-100 text-white" type="submit" name="connexion" id="submit" style="background-color: #012970;">Se connecter <i class="ti-arrow-right"></i></button>
                                        </div>
                                        
                                          <div class="col-12">
                                              <p class="small mb-0">Mot de passe oublié ? <a href="{{ route('getEmail') }}"><i class="ri-arrow-right-line"></i> cliquez-ici</a></p>
                                          </div>
                                         
                                    </form>

                                </div>
                            </div>
                           
                            <div class="credits" style="color: black;">
                              Designed by <a href="#" style="color: black;">ALL DIGITAL AGENCY</a>
                              <p class="text-center">(+229) 610 822 60</p>
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

    $(".toggle-password").click(function() {
      //ri-eye-off-fill
      var input = $($(this).attr("toggle"));
      $(this).toggleClass("ri-eye-off-fill");


      if (input.attr("type") == "password") {
        input.attr("type", "text");
       $(this).toggleClass("ri-eye-fill");
      } else {
        input.attr("type", "password");
        $(this).toggleClass("ri-eye-off-fill");

      }
    });

  </script>

</body>

</html>