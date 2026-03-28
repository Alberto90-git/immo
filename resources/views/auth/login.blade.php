<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Lokativ | Connexion</title>

    <meta name="description" content="Page de connexion à Immo, application de gestion des agences immobiliers, des maisons, chambres, locataires" />

    @include('css_file')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Helpers -->
    <script src="assetsV2/vendor/js/helpers.js"></script>
    <script src="assetsV2/js/config.js"></script>
    
    <style>
      :root {
        --primary-gradient: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        --secondary-gradient: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        --soft-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        --soft-shadow-hover: 0 30px 60px rgba(0, 0, 0, 0.15);
        --glass-bg: rgba(255, 255, 255, 0.25);
        --glass-border: rgba(255, 255, 255, 0.18);
      }

      body {
        background: var(--primary-gradient);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        overflow-x: hidden;
      }

      /* Animated background elements */
      .bg-animation {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
        pointer-events: none;
      }

      .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
      }

      .shape {
        position: absolute;
        background: var(--secondary-gradient);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
        opacity: 0.1;
      }

      .shape:nth-child(1) {
        width: 300px;
        height: 300px;
        top: 10%;
        left: 10%;
        animation-delay: 0s;
      }

      .shape:nth-child(2) {
        width: 200px;
        height: 200px;
        top: 60%;
        right: 20%;
        animation-delay: 2s;
      }

      .shape:nth-child(3) {
        width: 150px;
        height: 150px;
        bottom: 10%;
        left: 50%;
        animation-delay: 4s;
      }

      @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
      }

      .container-xxl {
        position: relative;
        z-index: 1;
      }

      .authentication-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
      }

      .authentication-inner {
        width: 100%;
        max-width: 480px;
        animation: slideUp 0.8s ease-out;
      }

      @keyframes slideUp {
        from {
          opacity: 0;
          transform: translateY(50px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      .card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        box-shadow: var(--soft-shadow);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
      }

      .card:hover {
        box-shadow: var(--soft-shadow-hover);
        transform: translateY(-5px);
      }

      .card-body {
        padding: 3rem;
      }

      h4 {
        color: #2d3748;
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 1.5rem;
        text-align: center;
      }

      .welcome-emoji {
        display: inline-block;
        animation: wave 2s infinite;
        transform-origin: 70% 70%;
      }

      @keyframes wave {
        0% { transform: rotate(0deg); }
        10% { transform: rotate(14deg); }
        20% { transform: rotate(-8deg); }
        30% { transform: rotate(14deg); }
        40% { transform: rotate(-4deg); }
        50% { transform: rotate(10deg); }
        60% { transform: rotate(0deg); }
        100% { transform: rotate(0deg); }
      }

      .form-label {
        color: #4a5568;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
      }

      .form-control {
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        color: #2d3748;
      }

      .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
      }

      .form-control::placeholder {
        color: #a0aec0;
      }


      .btn-primary {
        background: var(--primary-gradient);
        border: none;
        border-radius: 12px;
        padding: 0.875rem 2rem;
        font-weight: 600;
        font-size: 1rem;
        text-transform: none;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
      }

      .btn-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: all 0.5s;
      }

      .btn-primary:hover::before {
        left: 100%;
      }

      .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
      }

      .btn-primary:active {
        transform: translateY(0);
      }

      .form-check-input {
        border-radius: 6px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.1);
      }

      .form-check-input:checked {
        background-color: #3b82f6;
        border-color: #3b82f6;
      }

      .form-check-label {
        color: #4a5568;
        font-weight: 500;
      }

      .text-center a {
        color:rgb(18, 19, 23);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .text-center a:hover {
        color:rgb(184, 177, 192);
        text-decoration: underline;
      }

      .mb-4 {
        color:rgb(230, 234, 240);
        font-size: 1.1rem;
        text-align: center;
        margin-bottom: 2rem !important;
      }

      /* Logo styling */
      .logo-container {
        text-align: center;
        margin-bottom: 2rem;
      }

      .logo-container img {
        max-height: 60px;
        filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
      }

      /* Alert styling */
      .alert {
        border-radius: 12px;
        border: none;
        backdrop-filter: blur(10px);
        margin-bottom: 1.5rem;
      }

      .alert-success {
        background: rgba(72, 187, 120, 0.1);
        color: #2f855a;
      }

      .alert-danger {
        background: rgba(245, 101, 101, 0.1);
        color: #c53030;
      }

      /* Responsive design */
      @media (max-width: 768px) {
        .card-body {
          padding: 2rem 1.5rem;
        }
        
        .shape {
          opacity: 0.05;
        }
      }

      /* Loading animation */
      .btn-loading {
        position: relative;
        pointer-events: none;
      }

      .btn-loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        margin: auto;
        border: 2px solid transparent;
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 1s linear infinite;
      }

      @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }
    </style>
  </head>

  <body>
    <!-- Animated background -->
    <div class="bg-animation">
      <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
      </div>
    </div>

    <!-- Content -->
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register -->
          <div class="card">
            <div class="card-body">
              <!-- Logo 
              <div class="logo-container">
                @include('logo')
              </div>
              /Logo -->
              
              <h4 class="mb-1">Bienvenue sur <strong>Lokativ</strong> ! <span class="welcome-emoji">👋</span></h4>

              <p class="mb-4">Veuillez vous connecter à votre espace de travail</p>

              <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login_check') }}">
                  @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror"
                    id="email"  
                    name="email" 
                    placeholder="Votre email" 
                    autofocus  
                    required 
                  />
                  @error('email')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                
                <div class="mb-3">
                  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
                    <label class="form-label" for="password" style="margin-bottom:0;">Mot de passe</label>
                    <a href="{{ route('getEmail') }}" style="font-size:0.8rem;">Mot de passe oublié ?</a>
                  </div>
                  <div id="password-group"
                       style="display:flex;align-items:stretch;
                              border:2px solid rgba(255,255,255,0.3);
                              border-radius:12px;overflow:hidden;
                              background:rgba(255,255,255,0.1);
                              backdrop-filter:blur(10px);
                              transition:border-color 0.3s,box-shadow 0.3s;">
                    <input
                      type="password"
                      id="password"
                      name="password"
                      placeholder="••••••••••••"
                      required
                      style="flex:1;border:none;background:transparent;
                             padding:0.75rem 1rem;font-size:1rem;
                             color:#2d3748;outline:none;min-width:0;"
                    />
                    <button type="button" id="togglePassword"
                            aria-label="Afficher/masquer le mot de passe"
                            style="background:transparent;
                                   border:none;
                                   border-left:1px solid rgba(255,255,255,0.25);
                                   padding:0 1rem;
                                   cursor:pointer;
                                   color:#a0aec0;
                                   font-size:1.1rem;
                                   transition:color 0.2s;
                                   flex-shrink:0;">
                      <i class="fas fa-eye-slash" id="toggleIcon"></i>
                    </button>
                  </div>
                </div>
                
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me">Se rappeler de moi</label>
                  </div>
                </div>
                
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit" name="connexion" id="submit">
                    Se connecter
                  </button>
                </div>
              </form>

              <p class="text-center">
                <span class="text-white">Nouveau sur la plateforme ?</span>
                <a href="{{ route('accueil') }}">
                  <span>Créer un compte</span>
                </a>
              </p>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- / Content -->
    <!-- Core JS -->
    @include('js_file')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    
    <script>
      // Messages de session (error / success)
      @if(session('message'))
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          icon: 'success',
          title: 'Succès',
          text: @json(session('message')),
          confirmButtonText: 'OK',
          confirmButtonColor: '#3b82f6',
          customClass: { popup: 'shadow-lg' }
        });
      });
      @elseif(session('error') && !str_contains(session('error'), 'bloquée'))
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          icon: 'error',
          title: 'Erreur',
          text: @json(session('error')),
          confirmButtonText: 'OK',
          confirmButtonColor: '#e53e3e',
          customClass: { popup: 'shadow-lg' }
        });
      });
      @endif

      // Alerte entreprise bloquée
      @if(session('error') && str_contains(session('error'), 'bloquée'))
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          icon: 'error',
          title: '<span style="color:#dc3545;font-size:1.3rem;">Accès bloqué</span>',
          html: `
            <p style="font-size:14px;color:#555;margin-bottom:18px;line-height:1.6;">
              Votre entreprise a été <strong>bloquée</strong>.<br>
              Veuillez nous contacter pour régulariser votre situation.
            </p>
            <a href="https://wa.me/22961082260" target="_blank"
               style="display:inline-flex;align-items:center;gap:8px;
                      background:#25D366;color:#fff;padding:10px 22px;
                      border-radius:8px;text-decoration:none;
                      font-weight:600;font-size:14px;box-shadow:0 4px 12px rgba(37,211,102,0.35);">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zm-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884zm8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
              </svg>
              Contacter via WhatsApp
            </a>
          `,
          confirmButtonText: 'Fermer',
          confirmButtonColor: '#6c757d',
          allowOutsideClick: false,
          allowEscapeKey: false,
          customClass: { popup: 'shadow-lg' }
        });
      });
      @endif

      // Toggle password visibility
      document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon  = document.getElementById('toggleIcon');
        const show  = input.type === 'password';

        input.type = show ? 'text' : 'password';
        icon.classList.toggle('fa-eye-slash', !show);
        icon.classList.toggle('fa-eye',        show);
        this.style.color = show ? '#3b82f6' : '#a0aec0';
      });

      // Focus/blur styling sur le groupe password
      const pwInput = document.getElementById('password');
      const pwGroup = document.getElementById('password-group');
      pwInput.addEventListener('focus', function () {
        pwGroup.style.borderColor = '#3b82f6';
        pwGroup.style.boxShadow   = '0 0 0 3px rgba(59,130,246,0.15)';
      });
      pwInput.addEventListener('blur', function () {
        pwGroup.style.borderColor = 'rgba(255,255,255,0.3)';
        pwGroup.style.boxShadow   = 'none';
      });

      // Hover sur le bouton toggle
      const toggleBtn = document.getElementById('togglePassword');
      toggleBtn.addEventListener('mouseenter', function () { this.style.color = '#3b82f6'; });
      toggleBtn.addEventListener('mouseleave', function () {
        if (document.getElementById('password').type === 'password') this.style.color = '#a0aec0';
      });

      // Form submission with loading state
      document.getElementById('formAuthentication').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submit');
        submitBtn.classList.add('btn-loading');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Connexion en cours...';
      });

      // Focus animations sur les autres champs (email uniquement)
      document.getElementById('email').addEventListener('focus', function () {
        this.style.transform = 'translateY(-2px)';
      });
      document.getElementById('email').addEventListener('blur', function () {
        this.style.transform = 'translateY(0)';
      });
    </script>
  </body>
</html>