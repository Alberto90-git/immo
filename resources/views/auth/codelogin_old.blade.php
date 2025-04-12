<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Code -Login - Immo Manager</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link rel="shortcut icon" sizes="32x32" href="{{{ asset('favicon-32x32.png') }}}">
    

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
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
                                        <h5 class="card-title text-center pb-0 fs-4">
                                            Entrez le code envoyé à votre email
                                        </h5>

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
                                    </div>

                                    <form class="row g-3" method="POST" action="{{ route('code_submit') }}">
                                        @csrf

                                        <div class="col-12">
                                            <label for="code" class="form-label">Code</label>
                                            <input name="code" id="code" type="text" 
                                            class="form-control @error('code') is-invalid @enderror" 
                                            required autocomplete="off" >
                                            @error('code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>


                                        <div class="col-12">
                                            <button class="btn shadow w-100 text-white" id="form_submit" type="submit" name="Confirmer" style="background-color: #012970;">Confirmation <i class="ti-arrow-right"></i></button>
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

</body>

</html>