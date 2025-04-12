<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Immo web site officiel</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->

   <link rel="shortcut icon" sizes="32x32" href="{{{ asset('favicon-32x32.png') }}}">
  

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="{{ asset('assets/js/vendor/jquery.min.js') }}"></script>




  <link href="{{ asset('assets2/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('assets2/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets2/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('assets2/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets2/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets2/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
  <link href="{{ asset('assets2/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('assets2/css/style.css') }}" rel="stylesheet">
  <link href="{{ asset('assets2/styleFormulaireMultiple.css') }}" rel="stylesheet">



    <style>
        /* Conteneur de la scène 3D */
        .scene {
            width: 300px;
            height: 300px;
            margin: auto;
            perspective: 1000px; /* Pour donner l'effet 3D */
        }

        /* Cube 3D */
        .cube {
            width: 100%;
            height: 100%;
            position: relative;
            transform-style: preserve-3d;
            animation: rotateCube 10s infinite linear;
        }

        /* Faces du cube */
        .cube-face {
            position: absolute;
            width: 300px;
            height: 300px;
            background-color: #f3f3f3;
            border: 1px solid #ccc;
        }

        .cube-face img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Positionnement des différentes faces */
        .cube-face-front { transform: rotateY(0deg) translateZ(150px); }
        .cube-face-back { transform: rotateY(180deg) translateZ(150px); }
        .cube-face-left { transform: rotateY(-90deg) translateZ(150px); }
        .cube-face-right { transform: rotateY(90deg) translateZ(150px); }
        .cube-face-top { transform: rotateX(90deg) translateZ(150px); }
        .cube-face-bottom { transform: rotateX(-90deg) translateZ(150px); }

        /* Animation de rotation du cube */
        @keyframes rotateCube {
            from {
                transform: rotateX(0deg) rotateY(0deg);
            }
            to {
                transform: rotateX(360deg) rotateY(360deg);
            }
        }


       
    </style>



</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top ">
    <div class="container d-flex align-items-center">

      <h1 class="logo me-auto"><a href="{{ route('accueil') }}">Immo Manager</a></h1>
      <nav id="navbar" class="navbar">
        <ul>
          <li><a class="nav-link scrollto active" href="#hero">Accueil</a></li>
          <!-- <li><a class="nav-link scrollto" href="#marche">Au marche</a></li> -->
          <li><a class="nav-link   scrollto" href="#portfolio">Images du logiciel</a></li> 
          <li><a class="nav-link scrollto" href="#plans">Plan d'abonnement</a></li>
         <!-- <li class="dropdown"><a href="#"><span>Drop Down</span> <i class="bi bi-chevron-down"></i></a>
            <ul>
              <li><a href="#">Drop Down 1</a></li>
              <li class="dropdown"><a href="#"><span>Deep Drop Down</span> <i class="bi bi-chevron-right"></i></a>
                <ul>
                  <li><a href="#">Deep Drop Down 1</a></li>
                  <li><a href="#">Deep Drop Down 2</a></li>
                  <li><a href="#">Deep Drop Down 3</a></li>
                  <li><a href="#">Deep Drop Down 4</a></li>
                  <li><a href="#">Deep Drop Down 5</a></li>
                </ul>
              </li>
              <li><a href="#">Drop Down 2</a></li>
              <li><a href="#">Drop Down 3</a></li>
              <li><a href="#">Drop Down 4</a></li>
            </ul>
          </li> -->
          <li><a class="nav-link scrollto" href="#contactdirect">Contactez-nous</a></li>
          <li><a class="getstarted scrollto" href="#compte">Créer un compte</a></li>
          <li><a class="getstarted scrollto" href="{{ route('login') }}">Se connecter</a></li>
        </ul>
        <i class="bi bi-list mobile-nav-toggle"></i>
      </nav><!-- .navbar -->

    </div>
  </header><!-- End Header -->

  <!-- ======= Hero Section ======= -->
  <section id="hero" class="d-flex align-items-center">

    <div class="container">
      <div class="row">
        <div class="col-lg-6 d-flex flex-column justify-content-center pt-4 pt-lg-0 order-2 order-lg-1" data-aos="fade-up" data-aos-delay="200">
          <h1>Une meilleure plateforme pour bien gérer vos biens immobiliers</h1>
          <h2>Un outil efficace pour une gestion optimale de vos biens immobiliers...</h2>
          <div class="d-flex justify-content-center justify-content-lg-start">
            <a href="#compte" class="btn-get-started scrollto">Créer un compte</a>
            <a href="https://www.youtube.com/watch?v=s3xAis1GB9M" class="glightbox btn-watch-video"><i class="bi bi-play-circle"></i><span>Watch Video</span></a>
          
            {{-- <video width="640" height="360" controls>
              <source rc="{{ asset('assets/video.mp4') }}" type="video/mp4">
              Votre navigateur ne supporte pas la vidéo HTML5.
          </video> --}}
          </div>
        </div>
        <div class="col-lg-6 order-1 order-lg-2 hero-img" data-aos="zoom-in" data-aos-delay="200">
          <img src="assets2/img/hero-img.png" class="img-fluid animated" alt="">
        </div>
      </div>
    </div>

  </section><!-- End Hero -->

  <main id="main">

    <!-- ======= Clients Section ======= -->
    <section id="clients" class="clients section-bg">
      <div class="container">
        <div class="section-title">
          <h2>Nos partenaires</h2>
        </div>

        <div class="row" data-aos="zoom-in">

          <div class="col-lg-2 col-md-4 col-6 d-flex align-items-center justify-content-center">
            <img src="assets2/img/clients/client-1.png" class="img-fluid" alt="">
          </div>

          <div class="col-lg-2 col-md-4 col-6 d-flex align-items-center justify-content-center">
            <img src="assets2/img/clients/client-2.png" class="img-fluid" alt="">
          </div>

          <div class="col-lg-2 col-md-4 col-6 d-flex align-items-center justify-content-center">
            <img src="assets2/img/clients/client-3.png" class="img-fluid" alt="">
          </div>

          <div class="col-lg-2 col-md-4 col-6 d-flex align-items-center justify-content-center">
            <img src="assets2/img/clients/client-4.png" class="img-fluid" alt="">
          </div>

          <div class="col-lg-2 col-md-4 col-6 d-flex align-items-center justify-content-center">
            <img src="assets2/img/clients/client-5.png" class="img-fluid" alt="">
          </div>

          <div class="col-lg-2 col-md-4 col-6 d-flex align-items-center justify-content-center">
            <img src="assets2/img/clients/client-6.png" class="img-fluid" alt="">
          </div>

        </div>

      </div>
    </section>
    <!-- End Cliens Section -->



    <!-- ======= Creer un compte ======= -->
    <section id="compte" class="compte">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
              <h2>Créer un compte</h2>

            </div><div id="afficher">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <!-- DEBUT POUR COMPTE PERSONNEL  <form class="mt-5">-->

            <div class="tab-content d-flex justify-content-center" id="nav-tabContent">
              <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                    <form action="javascript:createCompte();" method="post" id="createcompteformID"  role="form" class="mt-5">
                       @csrf
                      <div class="form-row">
                        <div class="form-group col-md-6">
                          <label for="inputEmail4">Nom <strong style="color:red;">*</strong>
                          </label>
                          <input type="text" class="form-control" name="nom" id="nom" placeholder="Nom" required>
                          <span class="invalid-feedback nom_err" role="alert">
                          </span>
                        </div>

                        <div class="form-group col-md-6">
                          <label for="inputEmail4">Prénom 
                            <strong style="color:red;">*</strong>
                          </label>
                          <input type="text" class="form-control" name="prenom" id="prenom" placeholder="Prénom" required>
                          <span class="invalid-feedback prenom_err" role="alert">
                          </span>
                        </div>

                        <div class="form-group col-md-6">
                          <label for="inputEmail4">E-mail personnel <strong style="color:red;">*</strong>
                          </label>
                          <input type="email" class="form-control" name="email" id="email" placeholder="E-mail" required>
                          <span class="invalid-feedback email_err" role="alert">
                          </span>
                        </div>


                        <div class="form-group col-md-2">
                          <label for="code_pays">Pays <strong style="color:red;">*</strong></label>
                          <select class="form-select" name="code_pays" id="code_pays" required>
                              <option value="" disabled selected>--Pays--</option>
                              <option value="+229" data-mask="(229) 99 99 99 99">Bénin</option>
                              <option value="+228" data-mask="(228) 90 90 90 90">Togo</option>
                              <!-- Vous pouvez ajouter plus de pays ici avec leurs masques -->
                          </select>
                          <span class="invalid-feedback code_pays_err" role="alert"></span>
                        </div>

                        <div class="form-group col-md-4">
                          <label for="inputEmail4">Téléphone personnel <strong style="color:red;">*</strong>
                          </label>
                          <input type="text" class="form-control" name="telephone" id="telephone" placeholder="Téléphone" required>
                          <span class="invalid-feedback telephone_err" role="alert">
                          </span>
                        </div>

                      </div>

                        <div class="form-group col-md-6">
                            <label for="inputPassword4">Choisissez un type de compte <strong style="color:red;">*</strong>
                            </label>
                            <select class="form-select" name="type_compte" id="type_compte" onchange="displayChoix();" required>
                                <option value="" disabled selected>--Type de compte--</option>
                                <option value="Entreprise">Entreprise</option>
                                <option value="Particulier">Particulier</option>
                            </select>
                            <span class="invalid-feedback type_compte_err" role="alert">
                          </span>
                        </div>
                        

                      <section id="contact" class="contact" style="display:none;">
                        <div class="container" data-aos="fade-up">
                          <div class="row">
                              <div class="col-lg-12 mt-5 mt-lg-0 d-flex align-items-stretch">
                                <div  class="php-email-form">
                                  <div class="row">
                                    <div class="form-group col-md-6">
                                      <label for="designation">Désignation de l'entreprise <strong style="color:red;">*</strong></label>
                                      <input type="text" name="designation" class="form-control" id="designation">
                                    </div>
                                    <div class="form-group col-md-6">
                                      <label for="adresse">Adresse de l'entreprise <strong style="color:red;">*</strong></label>
                                      <input type="text" class="form-control" name="adresse" id="adresse">
                                      <span class="invalid-feedback adresse_err" role="alert">
                                      </span>
                                    </div>
                                    <div class="form-group col-md-6">
                                      <label for="email">E-mail de l'entreprise <strong style="color:red;">*</strong></label>
                                      <input type="email" class="form-control" name="email_entreprise" id="email_entreprise">
                                      <span class="invalid-feedback  email_entreprise_err" role="alert">
                                      </span>
                                    </div>
                                    <div class="form-group col-md-6">
                                      <label for="telepone">Téléphone de l'entreprise <strong style="color:red;">*</strong></label>
                                      <input type="telepone" class="form-control" name="telepone_entreprise" id="telepone_entreprise">
                                      <span class="invalid-feedback  telepone_entreprise_err" role="alert">
                                      </span>
                                    </div>
                                  </div>
                                  
                                </div>
                              </div>

                          </div>
                        </div>
                      </section>

                      <div class="text-center sbg1">
                        <button type="submit"  id="valider">
                          <span id="s">Enregister</span>
                        </button>
                      </div>
                    </form>

              </div>
              <!-- FIN POUR COMPTE PERSONNEL -->
              
            </div>
        </div>

      </div>
    </section>
    <!-- End Creer un compte -->

    <!-- ======= Why Us Section ======= -->
    <section id="why-us" class="why-us section-bg">
      <div class="container-fluid" data-aos="fade-up">

        <div class="row">

          <div class="col-lg-7 d-flex flex-column justify-content-center align-items-stretch  order-2 order-lg-1">

            <div class="content">
              <h3>Gérer de façcon automatique toutes vos opérations de votre entreprise grâce à <strong>Immo Manager</strong></h3>
              <p>
              </p>
            </div>

            <div class="accordion-list">
              <ul>
                {{-- <li>
                  <a data-bs-toggle="collapse" class="collapse" data-bs-target="#accordion-list-1"><span>01</span> Non consectetur a erat nam at lectus urna duis? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-1" class="collapse show" data-bs-parent=".accordion-list">
                    <p>
                      Feugiat pretium nibh ipsum consequat. Tempus iaculis urna id volutpat lacus laoreet non curabitur gravida. Venenatis lectus magna fringilla urna porttitor rhoncus dolor purus non.
                    </p>
                  </div>
                </li> --}}

                {{-- <li>
                  <a data-bs-toggle="collapse" data-bs-target="#accordion-list-2" class="collapsed"><span>02</span> Feugiat scelerisque varius morbi enim nunc? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-2" class="collapse" data-bs-parent=".accordion-list">
                    <p>
                      Dolor sit amet consectetur adipiscing elit pellentesque habitant morbi. Id interdum velit laoreet id donec ultrices. Fringilla phasellus faucibus scelerisque eleifend donec pretium. Est pellentesque elit ullamcorper dignissim. Mauris ultrices eros in cursus turpis massa tincidunt dui.
                    </p>
                  </div>
                </li> --}}

                {{-- <li>
                  <a data-bs-toggle="collapse" data-bs-target="#accordion-list-3" class="collapsed"><span>03</span> Dolor sit amet consectetur adipiscing elit? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                  <div id="accordion-list-3" class="collapse" data-bs-parent=".accordion-list">
                    <p>
                      Eleifend mi in nulla posuere sollicitudin aliquam ultrices sagittis orci. Faucibus pulvinar elementum integer enim. Sem nulla pharetra diam sit amet nisl suscipit. Rutrum tellus pellentesque eu tincidunt. Lectus urna duis convallis convallis tellus. Urna molestie at elementum eu facilisis sed odio morbi quis
                    </p>
                  </div>
                </li> --}}

              </ul>
            </div>

          </div>

          <div class="col-lg-5 align-items-stretch order-1 order-lg-2 img" style='background-image: url("assets2/img/why-us.png");' data-aos="zoom-in" data-aos-delay="150">&nbsp;</div>
        </div>

      </div>
    </section>
    <!-- End Why Us Section -->

 

    <!-- ======= Services Section ======= 
      <section id="marche" class="services section-bg">
        <div class="container" data-aos="fade-up">

          <div class="section-title">
            <h2>Terrain et maison à vendre</h2>
            <p>
              Des parcelles et terrains à vendre, choisissez ce qui vous convient...
            </p>
          </div>


          <div class="container text-center my-5">
            <div class="scene">
                <div class="cube">
                    <div class="cube-face cube-face-front">
                        <img src="assets2/img/portfolio/portfolio-1.jpg" alt="Image 1">
                    </div>
                    <div class="cube-face cube-face-back">
                        <img src="assets2/img/portfolio/portfolio-2.jpg" alt="Image 2">
                    </div>
                    <div class="cube-face cube-face-left">
                        <img src="assets2/img/portfolio/portfolio-3.jpg" alt="Image 3">
                    </div>
                    <div class="cube-face cube-face-right">
                        <img src="assets2/img/portfolio/portfolio-4.jpg" alt="Image 4">
                    </div>
                    <div class="cube-face cube-face-top">
                        <img src="assets2/img/portfolio/portfolio-5.jpg" alt="Image 5">
                    </div>
                    <div class="cube-face cube-face-bottom">
                        <img src="assets2/img/portfolio/portfolio-6.jpg" alt="Image 6">
                    </div>
                    
                </div>
            </div>
          </div>


          <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner">

                <div class="carousel-item active card-deck">

                    <div class="row">

                      @if(isset($publicites))
                          @foreach($publicites as $pub)
                        <div class="col-md-4">
                            <div class="card">
                                <img src="{{ asset('http://127.0.0.1/ImmobilierApk/storage/app/public/'.$pub->image_url) }}" class="card-img-top" alt="...">

                                <div class="card-body">
                                  <h5 class="card-title">Lieu: {{ $pub->localisation }} </h5>
                                  <h5 class="card-title">Prix: {{ number_format($pub->price ,"0",",",".") }} XOF</h5>
                                  <h5 class="card-title">Superficie: {{ $pub->Superficie }} m²</h5>
                                  <h5 class="card-title">Contact: {{ $pub->telephone }}</h5>
                                  <p class="card-text">Description: {{ $pub->description }}</p>
                                    <a href = ' ' class="">Détail</a>
                            
                                </div>
                            
                            <div class="card-footer">
                                <small class="text-muted">Publié le {{ $pub->created_at }}</small>
                            </div>
                            </div>
                        
                        </div>


                        @endforeach
                        @endif
                    </div> 
                </div>
            </div>


            <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
        </div>






        </div>
      </section> 
    End Services Section -->


    <!-- ======= Portfolio Section ======= -->
    <section id="portfolio" class="portfolio">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Portfolio</h2>
          <p>Quelques images de notre logiciel</p>
        </div>

        {{-- <ul id="portfolio-flters" class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
          <li data-filter="*" class="filter-active">All</li>
          <li data-filter=".filter-app">App</li>
          <li data-filter=".filter-card">Card</li>
          <li data-filter=".filter-web">Web</li>
        </ul> --}}

        <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">

          <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <div class="portfolio-img"><img src="assets2/img/portfolio/portfolio-1.jpg" class="img-fluid" alt=""></div>
            <div class="portfolio-info">
              <h4>App 1</h4>
              <p>App</p>
              <a href="assets2/img/portfolio/portfolio-1.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox preview-link" title="App 1"><i class="bx bx-plus"></i></a>
              <a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-web">
            <div class="portfolio-img"><img src="assets2/img/portfolio/portfolio-2.jpg" class="img-fluid" alt=""></div>
            <div class="portfolio-info">
              <h4>Web 3</h4>
              <p>Web</p>
              <a href="assets2/img/portfolio/portfolio-2.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox preview-link" title="Web 3"><i class="bx bx-plus"></i></a>
              <a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <div class="portfolio-img"><img src="assets2/img/portfolio/portfolio-3.jpg" class="img-fluid" alt=""></div>
            <div class="portfolio-info">
              <h4>App 2</h4>
              <p>App</p>
              <a href="assets2/img/portfolio/portfolio-3.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox preview-link" title="App 2"><i class="bx bx-plus"></i></a>
              <a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <div class="portfolio-img"><img src="assets2/img/portfolio/portfolio-4.jpg" class="img-fluid" alt=""></div>
            <div class="portfolio-info">
              <h4>Card 2</h4>
              <p>Card</p>
              <a href="assets2/img/portfolio/portfolio-4.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox preview-link" title="Card 2"><i class="bx bx-plus"></i></a>
              <a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-web">
            <div class="portfolio-img"><img src="assets2/img/portfolio/portfolio-5.jpg" class="img-fluid" alt=""></div>
            <div class="portfolio-info">
              <h4>Web 2</h4>
              <p>Web</p>
              <a href="assets2/img/portfolio/portfolio-5.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox preview-link" title="Web 2"><i class="bx bx-plus"></i></a>
              <a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-app">
            <div class="portfolio-img"><img src="assets2/img/portfolio/portfolio-6.jpg" class="img-fluid" alt=""></div>
            <div class="portfolio-info">
              <h4>App 3</h4>
              <p>App</p>
              <a href="assets2/img/portfolio/portfolio-6.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox preview-link" title="App 3"><i class="bx bx-plus"></i></a>
              <a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <div class="portfolio-img"><img src="assets2/img/portfolio/portfolio-7.jpg" class="img-fluid" alt=""></div>
            <div class="portfolio-info">
              <h4>Card 1</h4>
              <p>Card</p>
              <a href="assets2/img/portfolio/portfolio-7.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox preview-link" title="Card 1"><i class="bx bx-plus"></i></a>
              <a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-card">
            <div class="portfolio-img"><img src="assets2/img/portfolio/portfolio-8.jpg" class="img-fluid" alt=""></div>
            <div class="portfolio-info">
              <h4>Card 3</h4>
              <p>Card</p>
              <a href="assets2/img/portfolio/portfolio-8.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox preview-link" title="Card 3"><i class="bx bx-plus"></i></a>
              <a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 portfolio-item filter-web">
            <div class="portfolio-img"><img src="assets2/img/portfolio/portfolio-9.jpg" class="img-fluid" alt=""></div>
            <div class="portfolio-info">
              <h4>Web 3</h4>
              <p>Web</p>
              <a href="assets2/img/portfolio/portfolio-9.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox preview-link" title="Web 3"><i class="bx bx-plus"></i></a>
              <a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
            </div>
          </div>

        </div>

      </div>
    </section><!-- End Portfolio Section -->

    <!-- ======= Pricing Section ======= -->
    <section id="plans" class="pricing">
      <div class="container" data-aos="fade-up">
        <div class="section-title">
          <h2>Nos plans d'abonnement</h2>
          <p>Choisissez le plan qui correspond à vos besoins et commencez à profiter de nos services !</p>
        </div>
    
        <div class="row">
    
          <!-- Plan Gratuit -->
          <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
            <div class="box">
              <h3>Plan Gratuit</h3>
              <h4><sup>xof</sup>0<span>par mois</span></h4>
              <ul>
                <li><i class="bx bx-check"></i> Un compte personnel pourra ajouter au plus 2 maisons</li>
                <li><i class="bx bx-check"></i> Accès limité aux fonctionnalités de base</li>
                <li class="na"><i class="bx bx-x"></i> <span>Pas de support prioritaire</span></li>
                <li class="na"><i class="bx bx-x"></i> <span>Fonctionnalités avancées indisponibles</span></li>
              </ul>
              <a href="#" class="buy-btn">Démarrer</a>
            </div>
          </div>
    
          <!-- Plan Starter -->
          <div class="col-lg-4 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="200">
            <div class="box featured">
              <h3>Plan Starter</h3>
              <h4><sup>xof</sup>50.000<span>par an</span></h4>
              <ul>
                <li><i class="bx bx-check"></i> Un compte personnel pourra ajouter jusqu'à 5 maisons</li>
                <li><i class="bx bx-check"></i> Accès aux fonctionnalités de base</li>
                <li><i class="bx bx-check"></i> Support client standard</li>
                <li class="na"><i class="bx bx-x"></i> <span>Pas de statistiques avancées</span></li>
              </ul>
              <a href="#" class="buy-btn">Démarrer</a>
            </div>
          </div>
    
          <!-- Plan Premium -->
          <div class="col-lg-4 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="300">
            <div class="box">
              <h3>Plan Premium</h3>
              <h4><sup>xof</sup>150.000<span>par an</span></h4>
              <ul>
                <li><i class="bx bx-check"></i> Un compte personnel pourra ajouter jusqu'à 10 maisons</li>
                <li><i class="bx bx-check"></i> Fonctionnalités avancées incluses</li>
                <li><i class="bx bx-check"></i> Support client prioritaire</li>
                <li><i class="bx bx-check"></i> Statistiques avancées</li>
                <li class="na"><i class="bx bx-x"></i> <span>Pas d'intégration d'API</span></li>
              </ul>
              <a href="#" class="buy-btn">Démarrer</a>
            </div>
          </div>
          
    
          <!-- Plan Professionnel -->
          <div class="col-lg-4 mt-4 mt-lg-0" data-aos="fade-up" data-aos-delay="400">
            <div class="box">
              <h3>Plan Professionnel</h3>
              <h4><sup>xof</sup>300.000<span>par an</span></h4>
              <ul>
                <li><i class="bx bx-check"></i> Un compte entreprise pourra ajouter jusqu'à 50 maisons</li>
                <li><i class="bx bx-check"></i> Accès à toutes les fonctionnalités avancées</li>
                <li><i class="bx bx-check"></i> Support client dédié 24/7</li>
                <li><i class="bx bx-check"></i> Statistiques avancées et rapports détaillés</li>
                <li><i class="bx bx-check"></i> Intégration d'API</li>
              </ul>
              <a href="#" class="buy-btn">Démarrer</a>
            </div>
          </div>
    
        </div>
    
      </div>
    </section>
    
   <!-- End Pricing Section -->

    <!-- ======= Frequently Asked Questions Section ======= -->
    <section id="faq" class="faq section-bg">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>FAQ</h2>
          <p>Quelques questions que nos clients nous posent parfois.</p>
        </div>

        <div class="faq-list">
          <ul>
            <li data-aos="fade-up" data-aos-delay="100">
              <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" class="collapse" data-bs-target="#faq-list-1">Comment accèder à  la plateforme Immo Manager ? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
              <div id="faq-list-1" class="collapse show" data-bs-parent=".faq-list">
                <p>
                  Avant de pouvoir utiliser Immo Manager, vous devez créer d'abord un compte selon votre besoin, c'est-à-dire un <strong>compte personnel</strong> ou un <strong>compte entreprise</strong>.
                </p>
              </div>
            </li>

            <li data-aos="fade-up" data-aos-delay="200">
              <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" data-bs-target="#faq-list-2" class="collapsed">Combien coûte l'accès à la plateforme Immo Manager ? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
              <div id="faq-list-2" class="collapse" data-bs-parent=".faq-list">
                <p>
                  Le coût pour accèder à la plateforme Immo Manager est en fonction de votre compte crée et surtout du type d'abonnement choisi lors de votre inscription.
                </p>
              </div>
            </li>

            <li data-aos="fade-up" data-aos-delay="300">
              <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" data-bs-target="#faq-list-3" class="collapsed">Le paiement pour utiliser la plateforme est-elle définitif ? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
              <div id="faq-list-3" class="collapse" data-bs-parent=".faq-list">
                <p>
                  Pour utiliser Immo Manager, il faut effectuer un paiement chaque année. Les services sont rénouvellées à chaque année. Au cas où vous n'aurez pas à respecter votre contrat, vous verrez votre compte bloqué jusqu'à nouvel ordre.
                </p>
              </div>
            </li>

            {{-- <li data-aos="fade-up" data-aos-delay="400">
              <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" data-bs-target="#faq-list-4" class="collapsed">? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
              <div id="faq-list-4" class="collapse" data-bs-parent=".faq-list">
                <p>
                  Molestie a iaculis at erat pellentesque adipiscing commodo. Dignissim suspendisse in est ante in. Nunc vel risus commodo viverra maecenas accumsan. Sit amet nisl suscipit adipiscing bibendum est. Purus gravida quis blandit turpis cursus in.
                </p>
              </div>
            </li>

            <li data-aos="fade-up" data-aos-delay="500">
              <i class="bx bx-help-circle icon-help"></i> <a data-bs-toggle="collapse" data-bs-target="#faq-list-5" class="collapsed">Tortor vitae purus faucibus ornare. Varius vel pharetra vel turpis nunc eget lorem dolor? <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
              <div id="faq-list-5" class="collapse" data-bs-parent=".faq-list">
                <p>
                  Laoreet sit amet cursus sit amet dictum sit amet justo. Mauris vitae ultricies leo integer malesuada nunc vel. Tincidunt eget nullam non nisi est sit amet. Turpis nunc eget lorem dolor sed. Ut venenatis tellus in metus vulputate eu scelerisque.
                </p>
              </div>
            </li> --}}

          </ul>
        </div>

      </div>
    </section><!-- End Frequently Asked Questions Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contactdirect" class="contact">
      <div class="container" data-aos="fade-up">

        <div class="section-title">
          <h2>Contactez-nous</h2>
          <p>Vous pouvez nous écrire ou appeler directement pour avoir plus de renseignement à propos de notre plateforme</p>
        </div>

        <div class="row">

          <div class="col-lg-5 d-flex align-items-stretch">
            <div class="info">
              <div class="address">
                <i class="bi bi-geo-alt"></i>
                <h4>Location:</h4>
                <p>Cotonou, Rép du Bénin</p>
              </div>

              <div class="email">
                <i class="bi bi-envelope"></i>
                <h4>Email:</h4>
                <p>immomanager@gmail.com</p>
              </div>

              <div class="phone">
                <i class="bi bi-phone"></i>
                <h4>Call:</h4>
                <p>+229 610 822 60</p>
              </div>
              <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d2.4397!3d6.3693!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb89d1fe6bc499443!2sCotonou!5e0!3m2!1sen!2sbf!4v1635399326120" frameborder="0" style="border:0; width: 100%; height: 290px;" allowfullscreen></iframe>
            </div>

          </div>

          <div class="col-lg-7 mt-5 mt-lg-0 d-flex align-items-stretch">
          <form action="forms/contact.php" method="post" role="form" class="php-email-form">
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="name">Votre nom & prénom</label>
                  <input type="text" name="name" class="form-control" id="name" required>
                </div>
                <div class="form-group col-md-6">
                  <label for="name">Votre Email</label>
                  <input type="email" class="form-control" name="email"  required>
                </div>
              </div>
              <div class="form-group">
                <label for="name">Objet</label>
                <input type="text" class="form-control" name="subject" id="subject" required>
              </div>
              <div class="form-group">
                <label for="name">Message</label>
                <textarea class="form-control" name="message" rows="10" required></textarea>
              </div>
              <div class="my-3">
                <div class="loading">Loading</div>
                <div class="error-message"></div>
                <div class="sent-message">Your message has been sent. Thank you!</div>
              </div>
              <div class="text-center"><button type="submit">Envoyer</button></div>
            </form>
          </div>

        </div>

      </div>
    </section><!-- End Contact Section -->

   

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">

    {{-- <div class="footer-newsletter">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-6">
            <h4>Join Our Newsletter</h4>
            <p>Tamen quem nulla quae legam multos aute sint culpa legam noster magna</p>
            <form action="" method="post">
              <input type="email" name="email"><input type="submit" value="Subscribe">
            </form>
          </div>
        </div>
      </div>
    </div> --}}

    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-contact">
            <h3>All Digital Agency</h3>
            <p>
              République du Bénin, <br>
              Abomey-calavi<br>
              <strong>Tél:</strong> +229 610 822 60<br>
              <strong>Email:</strong> immomanager@gmail.com<br>
            </p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Liens utiles</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Accueil</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Au marche</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Portfolio</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Contactez-nous</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4></h4>
            {{-- <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Web Design</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Web Development</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Product Management</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Marketing</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="#">Graphic Design</a></li>
            </ul> --}}
          </div>

          <div class="col-lg-3 col-md-6 footer-links">
            <h4>Nos réseaux sociaux</h4>
            {{-- <p>Cras fermentum odio eu feugiat lide par naso tierra videa magna derita valies</p> --}}
            <div class="social-links mt-3">
              <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
              <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
              <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
              <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
              <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container footer-bottom clearfix">
      <div class="copyright">
        &copy; Copyright <strong><span>All Digital Agency</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        Designed by <a href="https://bootstrapmade.com/">All Digital Agency</a>
      </div>
    </div>
  </footer><!-- End Footer -->

  <div id="preloader"></div>
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>



  <!-- Vendor JS Files -->
  <script src="{{ asset('assets2/vendor/aos/aos.js') }}" defer></script>
  <script src="{{ asset('assets2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}" defer></script>
  <script src="{{ asset('assets2/vendor/glightbox/js/glightbox.min.js') }}" defer></script>
  <script src="{{ asset('assets2/vendor/isotope-layout/isotope.pkgd.min.js') }}" defer></script>
  <script src="{{ asset('assets2/vendor/swiper/swiper-bundle.min.js') }}" defer></script>
  <script src="{{ asset('assets2/vendor/waypoints/noframework.waypoints.js') }}" defer></script>
  <script src="{{ asset('assets2/vendor/php-email-form/validate.js') }}" defer></script>
  <script src="{{ asset('assets/js/vendor/jquery-2.2.4.min.js') }}"></script>


  <script src="{{ asset('assets/modules/sweetalert/sweetalert.min.js') }}" defer></script>
  <script src="{{ asset('assets/js/page/modules-sweetalert.js') }}" defer></script>
  
  <script src="{{ asset('assets/tel/isValidPhoneNumber.js') }}" defer></script>
  <script src="{{ asset('assets/tel/sign-up-login.js') }}" defer></script>
  <script src="{{ asset('assets/tel/utils.js') }}" defer></script>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>


  <!-- Template Main JS File -->
  <script src="{{ asset('assets2/js/main.js') }}" defer></script>
  <script src="{{ asset('assets2/javascriptFileFormulaireMultiple.js') }}" defer></script>
  <script type="text/javascript">
      var input = document.querySelector("#phone");
        window.intlTelInput(input, {
            initialCountry: "auto", // Automatically detect user's country
            preferredCountries: ['us', 'gb', 'fr', 'de', 'ng'], // Set preferred countries
            geoIpLookup: function(callback) {
                fetch('https://ipinfo.io?token=YOUR_TOKEN')  // Use your token for accurate IP lookup
                    .then(response => response.json())
                    .then(data => callback(data.country))
                    .catch(() => callback('us')); // Fallback to 'us' in case of failure
            },
            separateDialCode: true, // Separates the dial code from the phone number input
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js", // For formatting
            customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
                return "e.g. " + selectedCountryPlaceholder;
            }
        });


     function displayChoix() {
    
        let val = document.getElementById('type_compte').value;
        if (val == 'Entreprise') {

          document.getElementById('contact').style.display = 'block';

          $("#designation").attr('required', true);
          $("#adresse").attr('required', true);
          $("#email_entreprise").attr('required', true);
          $("#telepone_entreprise").attr('required', true);

        } else {

          document.getElementById('contact').style.display = 'none';
          $("#designation").attr('required', false);
          $("#adresse").attr('required', false);
          $("#email_entreprise").attr('required', false);
          $("#telepone_entreprise").attr('required', false);

        }
      } 



    function printErrorMsg(msg) {
        $.each(msg, function(key, value) {
            $('.' + key + '_err').text(value);
        });
    }


    function createCompte() {

      var data = new FormData();
      var form_data = $('#createcompteformID').serializeArray();

      $.each(form_data, function (key, input) {
          data.append(input.name, input.value);
      });

      $.ajax({
          url: "{{ route('creation_compte') }}",
          method: "POST",
          processData: false,
          contentType: false,
          data: data,
          beforeSend: function(data) {
              $("button#close").prop("disabled", true);
              $("button#valider").prop("disabled", true);
              $("button#valider").html('<i class="text-center fa fa-spinner fa-pulse fa-1x fa-fw ml-2">En cours...</i>');
          },
          success: function(data) {


              $("button#close").prop("disabled", false);
              $("button#valider").prop("disabled", false);
              $("button#valider").html('Enregistrer');


              if (!$.isEmptyObject(data.error)) {
                  printErrorMsg(data.error);
              }
              try {
                  if (data.status) {
                  // rempliretableau();

                    // alert(data.message);
                    // $("#AjouterLocataire div#afficher").html(data.message)
                   
                      swal({
                          title: 'Super !!',
                          text: data.message,
                          icon: 'success',
                          button: {
                              text: "Fermer",
                              className: "btn btn-primary"
                          },
                          timer: 5000,
                          buttonsStyling: true,
                          customClass: {
                              popup: 'animated bounceInDown',
                          },
                          background: '#f0f0f0',
                      });

                      $("#createcompteformID")[0].reset();
                  } else {
                    // $("#AjouterLocataire div#afficher").html(data.message)
                      swal('Erreur !!',data.message, 'warning');

                  }

              } catch (error) {

              }

          },
          error: function(data) {

        }
      });

      }


    function printErrorMsg(msg) {
        const items = [];
        for (const [key, value] of Object.entries(msg)) {
            $('.' + key + '_err').text(value).show();
            var elmnt = $('.' + key + '_err');
            console.log(elmnt.closest('.form-group'));
            items.push(elmnt.closest('.form-group'))
        }

        if (items[0] !== undefined) {
            items[0].get(0).scrollIntoView({
                behavior: "instant",
                block: "end",
                inline: "nearest"
            })
        }
    }


  


  </script>


</body>

</html>