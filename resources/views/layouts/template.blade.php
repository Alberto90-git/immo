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
    @yield('title')


    <meta name="description" content="" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
   <script src="{{ asset('assets/js/vendor/jquery.min.js') }}"></script>

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

          <div class="content-wrapper">
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

    
    <script>

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
          }, 500)
        });


      function display_message(m_title,m_message,m_icone,m_class) {
        swal({
              title: m_title,
              text: m_message,
              icon: m_icone,
              button: {
                  text: "Fermer",
                  className: m_class
              },
              timer: 2000,
              buttonsStyling: true,
              customClass: {
                  popup: 'animated bounceInDown',
              },
              background: '#f0f0f0',
          });
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

  </body>
</html>
