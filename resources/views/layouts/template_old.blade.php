<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Immo Manager</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  <meta name="_token" content="{{ csrf_token() }}">

  <!-- Favicons -->
  <link rel="shortcut icon" sizes="32x32" href="{{{ asset('favicon-32x32.png') }}}">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">



  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

 <link href="https://raw.githack.com/ttskch/select2-bootstrap4-theme/master/dist/select2-bootstrap4.css" rel="stylesheet">





  <!-- Vendor CSS Files -->
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="{{ asset('assets/js/vendor/jquery.min.js') }}"></script>

 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.6/jquery.inputmask.min.js"></script>


   <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/vendor/quill/quill.snow.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
   <link href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">



  <!-- Template Main CSS File -->
   <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">

</head>

<body>

  <!-- ======= Header ======= -->
  @include('partials.navbar')
  <!-- End Header -->

  <!-- ======= Sidebar ======= -->
  @include('partials.sidebar')

  <main id="main" class="main">

    @yield('content')
    

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Vendor JS Files -->
   
  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/quill/quill.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
  <script src="{{ asset('assets/vendor/tinymce/tinymce.min.js') }}"></script>
  <script src="{{ asset('assets/vendor/php-email-form/validate.js') }}"></script>
   <script src="{{ asset('assets/js/vendor/jquery-2.2.4.min.js') }}"></script>

 <!--  <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
  <script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}" defer></script> -->

  <script src="{{ asset('assets/modules/sweetalert/sweetalert.min.js') }}" defer></script>
  <script src="{{ asset('assets/js/page/modules-sweetalert.js') }}" defer></script>

 
  <!-- Template Main JS File -->
  <script src="{{ asset('assets/js/main.js') }}"></script>
  <script src="{{ asset('jasny-bootstrap.min.js') }}"></script>

 <script type="text/javascript">
   $('#close').on('click', function(){
    setInterval(function(){
        window.location.reload()
    }, 500)
  });


   function limit(element) {
      var max_chars = 8;
      if (element.value.length > max_chars) {
          element.value = element.value.substr(0, max_chars);
      }
  }

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
 </script>
</body>
</html>
