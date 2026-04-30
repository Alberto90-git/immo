<script src="{{ asset('assetsV2/vendor/libs/jquery/jquery.js') }}"></script>
<script src="{{ asset('assetsV2/vendor/libs/popper/popper.js') }}"></script>
<script src="{{ asset('assetsV2/vendor/js/bootstrap.js') }}"></script>
<script src="{{ asset('assetsV2/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assetsV2/vendor/js/menu.js') }}"></script>
<script src="{{ asset('assetsV2/js/main.js') }}"></script>

<script src="{{ asset('assetsV2/js/ui-toasts.js') }}"></script>


<!-- Place this tag in your head or just before your close body tag. -->
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="{{ asset('assets/modules/sweetalert/sweetalert.min.js') }}" defer></script>
<script src="{{ asset('assets/js/page/modules-sweetalert.js') }}" defer></script>


<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
<script>
  $(document).ready(function () {
    const telephone = document.querySelector("#telephone");

    const iti = window.intlTelInput(telephone, {
      initialCountry: "auto",
      nationalMode: false, // pour forcer l'affichage du code pays
      separateDialCode: true, // Affiche le code à part
      autoPlaceholder: "polite",
      geoIpLookup: function (callback) {
        $.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
          const countryCode = resp && resp.country ? resp.country : "us";
          callback(countryCode);
        });
      },
      utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
    });
  });


    function display_message(m_title, m_message, m_icone, m_class) {
        swal({
            title: m_title,
            text: m_message,
            icon: m_icone,
            button: { text: "Fermer", className: m_class },
            timer: 2000,
            buttonsStyling: true,
            customClass: { popup: 'animated bounceInDown' },
            background: '#f0f0f0',
        });
    }
</script>