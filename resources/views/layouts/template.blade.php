 <!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed"
  dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />
    
  <!-- Dans la section head -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @yield('title')


    <meta name="description" content="" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
   <script src="{{ asset('assets/js/vendor/jquery.min.js') }}"></script>

   <style>
    /* Forcer SweetAlert2 à apparaître au-dessus de tous les modals */
    .swal2-container {
        z-index: 10070 !important;
    }
    .swal2-popup {
        z-index: 10071 !important;
    }

    /* Navbar fixe au scroll */
    #layout-navbar {
        position: sticky;
        top: 0;
        z-index: 1020;
    }
  </style>

    @include('css_file')


    
    <script src="{{ asset('assetsV2/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assetsV2/js/config.js') }}"></script>
  </head>

  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">

        @include('partials.sidebar')
       
        <div class="layout-page">

          @include('partials.navbar')

          <div class="content-wrapper" id="main-content-wrapper">
            <!-- Alerte admin sans agence sélectionnée -->
            @if(is_admin_without_annexe_selected())
              <div class="alert alert-warning alert-dismissible fade show m-3" role="alert">
                <i class="bx bx-error-circle me-2"></i>
                <strong>Attention !</strong> Veuillez sélectionner une agence dans le header pour pouvoir effectuer des opérations.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <!-- Content -->

            @yield('content')
          

            <div class="content-backdrop fade"></div>
          </div>
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    @include('js_file')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>

<!-- Avant la fermeture du body -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
  <script>

    function display_sweet_alerte2(title, message, type, buttonClass) {
        // Vérifier si SweetAlert2 est disponible
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                html: message,
                icon: type,
                confirmButtonText: 'Fermer',
                confirmButtonClass: buttonClass,
                customClass: {
                    popup: 'animated bounceIn'
                }
            });
        }
        // Vérifier si Toastr est disponible
        else if (typeof toastr !== 'undefined') {
            switch(type) {
                case 'success':
                    toastr.success(message, title);
                    break;
                case 'warning':
                    toastr.warning(message, title);
                    break;
                case 'error':
                case 'danger':
                    toastr.error(message, title);
                    break;
                default:
                    toastr.info(message, title);
            }
        }
        // Fallback avec alert simple
        else {
            alert(title + ":\n" + message);
        }
    }

    function Sepatateur_Milliers(param)
    {

        var valSaisie=$(param).val().trim().replace(/\s/g,'');
        //alert(valSaisie);
        if($.isNumeric(valSaisie))
          {
            //.trim();
            if (valSaisie==0)
             {
               $(param).val(valSaisie);
             }
             else
             {
               var str= valSaisie.toString().replace(/\s/g,'');
               var n= str.length;
               if (n < 4)
                {
                  //alert(n);//return valSaisie;
                }
               else
                {
                  //$('#montant_prime').val().replace(/\s/g,'');
                 $(param).val(((n % 3) ? str.substr(0, n % 3) + ' ' : '') + str.substr(n % 3).match(new RegExp('[0-9]{3}', 'g')).join(' '));
                       //);
                }
              }
          }
          else
          {
              //alert("Ce champ nécessite une valeur entière");
              //$(param).removeClass('form-control').addClass('form-control has-warning');
              $(param).val().toString().replace(/\s/g,'');
              return false;
          }
    }

    
    $('#close').on('click', function(){
      setInterval(function(){
          window.location.reload()
      }, 1000)
    });


    // Dans votre layout ou dans un fichier JS global
    function display_message(title, message, type, buttonClass) {
        // Vérifier si SweetAlert2 est disponible
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: title,
                html: message,
                icon: type,
                confirmButtonText: 'OK',
                confirmButtonClass: buttonClass,
                customClass: {
                    popup: 'animated bounceIn'
                }
            });
        }
        // Vérifier si Toastr est disponible
        else if (typeof toastr !== 'undefined') {
            switch(type) {
                case 'success':
                    toastr.success(message, title);
                    break;
                case 'warning':
                    toastr.warning(message, title);
                    break;
                case 'error':
                case 'danger':
                    toastr.error(message, title);
                    break;
                default:
                    toastr.info(message, title);
            }
        }
        // Fallback avec alert simple
        else {
            alert(title + ":\n" + message);
        }
    }


     


        
    function validateAndFormatPhone(inputId) {
        const telephoneInput = document.querySelector(`#${inputId}`);

        if (!telephoneInput) {
            console.error(`L'élément avec l'ID "${inputId}" est introuvable.`);
            return null;
        }

        const iti = window.intlTelInputGlobals.getInstance(telephoneInput);

        if (!iti) {
            console.error(`L'instance intlTelInput n'est pas initialisée pour l'élément "${inputId}".`);
            return null;
        }

        const formattedPhone = iti.getNumber();
        const selectedCountry = iti.getSelectedCountryData();
        const cleanedPhone = formattedPhone.replace(/\s+/g, "");

        if (selectedCountry.iso2 === "bj" && cleanedPhone.length !== 14) {
            display_message("Erreur !!", "Le numéro béninois doit contenir exactement 10 chiffres après +229.",
                "warning", "btn btn-danger");
            return null;
        }

        if (selectedCountry.iso2 !== "bj") {
            display_message("Erreur !!", "Numéro de téléphone invalide ou trop court.",
                "warning", "btn btn-danger");
            return null;
        }

        return formattedPhone;
    }

    </script>

    @stack('scripts')

  {{-- ── Barre de progression NProgress (navigation entre pages) ──────── --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.css">
  <script src="https://cdn.jsdelivr.net/npm/nprogress@0.2.0/nprogress.js"></script>
  <style>
    /* Couleur de la barre NProgress */
    #nprogress .bar { background: #696cff; height: 3px; }
    #nprogress .peg  { box-shadow: 0 0 10px #696cff, 0 0 5px #696cff; }
    #nprogress .spinner-icon { border-top-color: #696cff; border-left-color: #696cff; }
  </style>

  <script>
  (function () {
    // ── NProgress : configuration ─────────────────────────────────────
    if (typeof NProgress !== 'undefined') {
      NProgress.configure({ showSpinner: false, speed: 250, minimum: 0.15 });
    }

    // ── Démarrer la barre dès qu'un lien navigue vers une autre page ──
    document.addEventListener('click', function (e) {
      var el = e.target.closest('a[href]');
      if (!el) return;
      var href = el.getAttribute('href');
      if (!href || href === '#' || href.startsWith('javascript:') ||
          href.startsWith('mailto:') || href.startsWith('tel:') ||
          el.getAttribute('target') === '_blank' ||
          el.getAttribute('download') != null) return;
      try {
        var url = new URL(href, window.location.origin);
        if (url.origin !== window.location.origin) return;
        // Même page (ancre uniquement) → pas de barre
        if (url.pathname === window.location.pathname && url.search === window.location.search) return;
      } catch (err) { return; }
      if (typeof NProgress !== 'undefined') NProgress.start();
    }, true);

    // ── Arrêter la barre quand la page est chargée ────────────────────
    window.addEventListener('load', function () {
      if (typeof NProgress !== 'undefined') NProgress.done();
    });

    // ── Arrêter la barre si le formulaire soumet (navigation form) ───
    document.addEventListener('submit', function () {
      if (typeof NProgress !== 'undefined') NProgress.start();
    }, true);

    // ── Auto-reload après retour d'onglet ≥ 30s ───────────────────────
    // Recharge la page réelle pour avoir des données fraîches quand
    // l'utilisateur revient après une longue absence.
    var _hiddenAt = 0;
    document.addEventListener('visibilitychange', function () {
      if (document.hidden) {
        _hiddenAt = Date.now();
      } else if (_hiddenAt > 0 && (Date.now() - _hiddenAt) >= 30000) {
        _hiddenAt = 0;
        // Ne pas recharger si un modal ou une alerte est ouvert
        if (!document.querySelector('.modal.show, .swal2-container.swal2-backdrop-show')) {
          window.location.reload();
        }
      } else {
        _hiddenAt = 0;
      }
    });

  }());
  </script>

  </body>
</html>
