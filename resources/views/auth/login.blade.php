<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Immo | Connexion</title>

    <meta name="description" content="Page de connexion à Immo, application de gestion des agences immobiliers, des maisons, chambres, locataires" />

    @include('css_file')

    <!-- Helpers -->
    <script src="assetsV2/vendor/js/helpers.js"></script>
    <script src="assetsV2/js/config.js"></script>
    
    <style>
      :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
      }

      .form-control::placeholder {
        color: #a0aec0;
      }

      .input-group-text {
        background: rgba(255, 255, 255, 0.1);
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-left: none;
        border-radius: 0 12px 12px 0;
        cursor: pointer;
        transition: all 0.3s ease;
      }

      .input-group-text:hover {
        background: rgba(255, 255, 255, 0.2);
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
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
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
        background-color: #667eea;
        border-color: #667eea;
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
              
              <h4 class="mb-1">Bienvenue sur <strong>Immo</strong> ! <span class="welcome-emoji">👋</span></h4>

              @include('display_message')

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
                
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Mot de passe</label>
                    <a href="{{ route('getEmail') }}">
                      <small>Mot de passe oublié ?</small>
                    </a>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      placeholder="••••••••••••"
                      aria-describedby="password" 
                      required
                    />
                    <span class="input-group-text cursor-pointer" id="togglePassword">
                      <i class="bx bx-hide" id="toggleIcon"></i>
                    </span>
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
                <span>Nouveau sur la plateforme ?</span>
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
    
    <script>
      // Toggle password visibility
      document.getElementById('togglePassword').addEventListener('click', function() {
        const passwordInput = document.getElementById('password');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          toggleIcon.classList.remove('bx-hide');
          toggleIcon.classList.add('bx-show');
        } else {
          passwordInput.type = 'password';
          toggleIcon.classList.remove('bx-show');
          toggleIcon.classList.add('bx-hide');
        }
      });

      // Form submission with loading state
      document.getElementById('formAuthentication').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submit');
        submitBtn.classList.add('btn-loading');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Connexion en cours...';
      });

      // Add input focus animations
      const inputs = document.querySelectorAll('.form-control');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
          this.parentElement.style.transform = 'translateY(0)';
        });
      });
    </script>
  </body>
</html>