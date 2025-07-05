
<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Immo | Changer mot de passe</title>

    <meta name="description" content="" />
    
    @include('css_file')

    <script src="{{ asset('assetsV2/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assetsV2/js/config.js') }}"></script>

  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register Card -->
          <div class="card">
            <div class="card-body">
              @include('logo')
              
              <h4 class="mb-2">Changer votre mot de passe 🚀</h4>

              <form id="formAuthentication" class="mb-3" action="{{ route('password_submit') }}" method="post">
                    @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror"
                    id="email" name="email" placeholder="Entrer votre email" autofocus />
                    @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                </div>
                
                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="Ancien_mot_de_passe">Ancien mot de passe</label>
                  <div class="input-group input-group-merge">
                    <input type="password" id="Ancien_mot_de_passe" class="form-control @error('Ancien_mot_de_passe') is-invalid @enderror" name="Ancien_mot_de_passe"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="Ancien_mot_de_passe" required/>
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                    @error('Ancien_mot_de_passe')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>



                <div class="mb-3 form-password-toggle">
                    <label class="form-label" for="Nouveau_mot_de_passe">Nouveau mot de passe</label>
                    <div class="input-group input-group-merge">
                      <input
                        type="password"
                        id="Nouveau_mot_de_passe"
                        class="form-control @error('Nouveau_mot_de_passe') is-invalid @enderror"
                        name="Nouveau_mot_de_passe"
                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                        aria-describedby="Nouveau_mot_de_passe"
                      />
                      <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                      @error('Nouveau_mot_de_passe')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                    </div>
                  </div>

                
                <button class="btn btn-primary d-grid w-100" id="form_submit1" type="submit" name="Confirmer1">Modifier</button>
              </form>
              
            </div>
          </div>
          <!-- Register Card -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    @include('js_file')

   
  </body>
</html>
