<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Immo | Réinitialiser mot de passe</title>

    <meta name="description" content="Page de réinitialisation de mot de passe pour Immo, application de gestion des agences immobiliers" />

    @include('css_file')

    <!-- Helpers -->
    <script src="assetsV2/vendor/js/helpers.js"></script>
    <script src="assetsV2/js/config.js"></script>
    
    <style>
      :root {
        --primary-gradient: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        --secondary-gradient: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        --accent-gradient: linear-gradient(135deg, #60a5fa 0%, #93c5fd 100%);
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
        border-radius: 50%;
        animation: float 8s ease-in-out infinite;
        opacity: 0.1;
      }

      .shape:nth-child(1) {
        width: 250px;
        height: 250px;
        background: var(--secondary-gradient);
        top: 20%;
        left: 15%;
        animation-delay: 0s;
      }

      .shape:nth-child(2) {
        width: 180px;
        height: 180px;
        background: var(--accent-gradient);
        top: 50%;
        right: 10%;
        animation-delay: 3s;
      }

      .shape:nth-child(3) {
        width: 120px;
        height: 120px;
        background: var(--secondary-gradient);
        bottom: 20%;
        left: 60%;
        animation-delay: 6s;
      }

      .shape:nth-child(4) {
        width: 200px;
        height: 200px;
        background: var(--accent-gradient);
        top: 10%;
        right: 40%;
        animation-delay: 1.5s;
      }

      @keyframes float {
        0%, 100% { 
          transform: translateY(0px) rotate(0deg) scale(1); 
        }
        33% { 
          transform: translateY(-30px) rotate(120deg) scale(1.1); 
        }
        66% { 
          transform: translateY(15px) rotate(240deg) scale(0.9); 
        }
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
        max-width: 450px;
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
        position: relative;
      }

      .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--primary-gradient);
        border-radius: 24px 24px 0 0;
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
        margin-bottom: 1rem;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
      }

      .lock-emoji {
        display: inline-block;
        animation: bounce 2s infinite;
        font-size: 1.5rem;
      }

      @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
          transform: translate3d(0, 0, 0);
        }
        40%, 43% {
          transform: translate3d(0, -15px, 0);
        }
        70% {
          transform: translate3d(0, -7px, 0);
        }
        90% {
          transform: translate3d(0, -2px, 0);
        }
      }

      .form-label {
        color: #4a5568;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
      }

      .form-label::before {
        content: '📧';
        font-size: 0.8rem;
      }

      .form-control {
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        color: #2d3748;
        position: relative;
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

      .text-center a {
        color: #2d3748;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem;
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
      }

      .text-center a:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      }

      .text-center a i {
        font-size: 1.2rem;
        transition: transform 0.3s ease;
      }

      .text-center a:hover i {
        transform: translateX(-3px);
      }

      .mb-4 {
        color: #4a5568;
        font-size: 1rem;
        text-align: center;
        margin-bottom: 2rem !important;
        line-height: 1.6;
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
        animation: fadeIn 0.5s ease-out;
      }

      .alert-success {
        background: rgba(72, 187, 120, 0.1);
        color: #2f855a;
        border: 1px solid rgba(72, 187, 120, 0.2);
      }

      .alert-danger {
        background: rgba(245, 101, 101, 0.1);
        color: #c53030;
        border: 1px solid rgba(245, 101, 101, 0.2);
      }

      .alert-info {
        background: rgba(66, 153, 225, 0.1);
        color: #2c5282;
        border: 1px solid rgba(66, 153, 225, 0.2);
      }

      @keyframes fadeIn {
        from {
          opacity: 0;
          transform: translateY(-10px);
        }
        to {
          opacity: 1;
          transform: translateY(0);
        }
      }

      /* Responsive design */
      @media (max-width: 768px) {
        .card-body {
          padding: 2rem 1.5rem;
        }
        
        .shape {
          opacity: 0.05;
        }

        h4 {
          font-size: 1.5rem;
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

      /* Additional enhancements */
      .form-group {
        position: relative;
        margin-bottom: 1.5rem;
      }

      .form-group::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        height: 2px;
        width: 0;
        background: var(--primary-gradient);
        transition: width 0.3s ease;
        border-radius: 1px;
      }

      .form-group:focus-within::after {
        width: 100%;
      }

      .instruction-text {
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1.5rem;
        backdrop-filter: blur(10px);
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
        <div class="shape"></div>
      </div>
    </div>

    <!-- Content -->
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Forgot Password -->
          <div class="card">
            <div class="card-body">
              <!-- Logo 
              <div class="logo-container">
                @include('logo')
              </div>
              /Logo -->
              
              <h4 class="mb-2">Mot de passe oublié ? <span class="lock-emoji">🔒</span></h4>
              
              @include('display_message')

              <div class="instruction-text">
                <p class="mb-4">Entrez votre adresse email et nous vous enverrons des instructions pour réinitialiser votre mot de passe.</p>
              </div>

              <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('forgot-password') }}">
                @csrf
                <div class="form-group">
                  <label for="email" class="form-label">Email</label>
                  <input 
                    type="email" 
                    class="form-control @error('email') is-invalid @enderror"
                    id="email" 
                    name="email" 
                    placeholder="Entrez votre adresse email" 
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
                  <button class="btn btn-primary d-grid w-100" type="submit" id="submit">
                    Envoyer les instructions
                  </button>
                </div>
              </form>
              
              <div class="text-center">
                <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center">
                  <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i>
                  Retour à la connexion
                </a>
              </div>
            </div>
          </div>
          <!-- /Forgot Password -->
        </div>
      </div>
    </div>

    <!-- / Content -->
    @include('js_file')
    
    <script>
      // Form submission with loading state
      document.getElementById('formAuthentication').addEventListener('submit', function() {
        const submitBtn = document.getElementById('submit');
        submitBtn.classList.add('btn-loading');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Envoi en cours...';
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

      // Add subtle parallax effect to shapes
      document.addEventListener('mousemove', function(e) {
        const shapes = document.querySelectorAll('.shape');
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;
        
        shapes.forEach((shape, index) => {
          const speed = (index + 1) * 0.5;
          const xOffset = (x - 0.5) * speed * 10;
          const yOffset = (y - 0.5) * speed * 10;
          
          shape.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
        });
      });
    </script>
  </body>
</html>