<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>Immo | Changement mot de passe</title>

    <meta name="description" content="Page de changement de mot de passe pour Immo, application de gestion des agences immobiliers" />

    @include('css_file')

    <!-- Helpers -->
    <script src="assetsV2/vendor/js/helpers.js"></script>
    <script src="assetsV2/js/config.js"></script>
    
    <style>
      :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --accent-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --success-gradient: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
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
        animation: float 7s ease-in-out infinite;
        opacity: 0.12;
      }

      .shape:nth-child(1) {
        width: 220px;
        height: 220px;
        background: var(--success-gradient);
        top: 15%;
        left: 10%;
        animation-delay: 0s;
      }

      .shape:nth-child(2) {
        width: 160px;
        height: 160px;
        background: var(--accent-gradient);
        top: 45%;
        right: 15%;
        animation-delay: 2s;
      }

      .shape:nth-child(3) {
        width: 140px;
        height: 140px;
        background: var(--secondary-gradient);
        bottom: 25%;
        left: 55%;
        animation-delay: 4s;
      }

      .shape:nth-child(4) {
        width: 190px;
        height: 190px;
        background: var(--success-gradient);
        top: 5%;
        right: 35%;
        animation-delay: 1s;
      }

      .shape:nth-child(5) {
        width: 100px;
        height: 100px;
        background: var(--accent-gradient);
        bottom: 10%;
        left: 20%;
        animation-delay: 3s;
      }

      @keyframes float {
        0%, 100% { 
          transform: translateY(0px) rotate(0deg) scale(1); 
        }
        25% { 
          transform: translateY(-25px) rotate(90deg) scale(1.05); 
        }
        50% { 
          transform: translateY(10px) rotate(180deg) scale(0.95); 
        }
        75% { 
          transform: translateY(-15px) rotate(270deg) scale(1.1); 
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
        position: relative;
      }

      .card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--success-gradient);
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
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

      .input-group-text i {
        color: #4a5568;
        transition: all 0.3s ease;
      }

      .input-group-text:hover i {
        color: #667eea;
        transform: scale(1.1);
      }

      .btn-primary {
        background: var(--success-gradient);
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
        box-shadow: 0 10px 25px rgba(72, 187, 120, 0.3);
      }

      .btn-primary:active {
        transform: translateY(0);
      }

      .mb-4 {
        color: #4a5568;
        font-size: 1.1rem;
        text-align: center;
        margin-bottom: 2rem !important;
        background: rgba(255, 255, 255, 0.1);
        padding: 1rem;
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
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

      /* Password strength indicator */
      .password-strength {
        margin-top: 0.5rem;
        height: 4px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 2px;
        overflow: hidden;
        transition: all 0.3s ease;
      }

      .password-strength-bar {
        height: 100%;
        width: 0%;
        transition: all 0.3s ease;
        border-radius: 2px;
      }

      .password-strength-weak {
        background: #f56565;
        width: 33%;
      }

      .password-strength-medium {
        background: #ed8936;
        width: 66%;
      }

      .password-strength-strong {
        background: #48bb78;
        width: 100%;
      }

      .password-requirements {
        margin-top: 1rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
      }

      .password-requirements h6 {
        color:rgb(14, 14, 15);
        font-weight: 600;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
      }

      .requirement {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: #718096;
        font-size: 0.8rem;
        margin-bottom: 0.25rem;
        max-height: 2rem;
        opacity: 1;
        overflow: hidden;
        transition: max-height 0.4s ease, opacity 0.3s ease, margin 0.4s ease;
      }

      .requirement.valid {
        max-height: 0;
        opacity: 0;
        margin-bottom: 0;
      }

      .requirement i {
        font-size: 0.7rem;
      }

      .password-requirements {
        transition: max-height 0.5s ease, opacity 0.4s ease, padding 0.4s ease, margin 0.4s ease;
        overflow: hidden;
      }

      .password-requirements.all-valid {
        max-height: 0 !important;
        opacity: 0;
        padding: 0;
        margin: 0;
        border: none;
      }

      /* Form groups with enhanced styling */
      .form-group {
        position: relative;
        margin-bottom: 1.5rem;
      }

      .form-group::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        height: 2px;
        width: 0;
        background: var(--success-gradient);
        transition: width 0.3s ease;
        border-radius: 1px;
      }

      .form-group:focus-within::after {
        width: 100%;
      }

      .form-group label {
        position: relative;
        z-index: 2;
      }

      .form-group label::before {
        content: '🔐';
        margin-right: 0.5rem;
        font-size: 0.9rem;
      }

      .form-group:nth-child(2) label::before {
        content: '🔒';
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

      /* Match validation styling */
      .passwords-match {
        color:rgb(7, 7, 7);
        font-size: 0.8rem;
        margin-top: 0.25rem;
        opacity: 0;
        transition: opacity 0.3s ease;
      }

      .passwords-match.show {
        opacity: 1;
      }

      .passwords-no-match {
        color:rgb(20, 19, 19);
        font-size: 0.8rem;
        margin-top: 0.25rem;
        opacity: 0;
        transition: opacity 0.3s ease;
      }

      .passwords-no-match.show {
        opacity: 1;
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
              
              <h4 class="mb-2">Bienvenue sur <strong>Immo</strong> ! <span class="welcome-emoji">👋</span></h4>
              
              @include('display_message')

              <p class="mb-4">Nous vous invitons à créer un nouveau mot de passe sécurisé</p>

              <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('changementPwd') }}">
                @csrf

                <input type="hidden" name="email" value="{{ $email }}" /> 
               
                <div class="form-group">
                  <label for="Nouveau_mot_de_passe" class="form-label">Nouveau mot de passe</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="Nouveau_mot_de_passe"
                      class="form-control"
                      name="Nouveau_mot_de_passe"
                      placeholder="Entrez votre nouveau mot de passe"
                      aria-describedby="Nouveau_mot_de_passe" 
                      required
                    />
                    <span class="input-group-text cursor-pointer" id="togglePassword1">
                      <i class="bx bx-hide" id="toggleIcon1"></i>
                    </span>
                  </div>
                  <div class="password-strength">
                    <div class="password-strength-bar" id="strengthBar"></div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="confirm_mot_de_passe" class="form-label">Confirmer le mot de passe</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="confirm_mot_de_passe"
                      class="form-control"
                      name="confirm_mot_de_passe"
                      placeholder="Confirmez votre nouveau mot de passe"
                      aria-describedby="confirm_mot_de_passe" 
                      required
                    />
                    <span class="input-group-text cursor-pointer" id="togglePassword2">
                      <i class="bx bx-hide" id="toggleIcon2"></i>
                    </span>
                  </div>
                  <div class="passwords-match" id="passwordsMatch">
                    <i class="bx bx-check"></i> Les mots de passe correspondent
                  </div>
                  <div class="passwords-no-match" id="passwordsNoMatch">
                    <i class="bx bx-x"></i> Les mots de passe ne correspondent pas
                  </div>
                </div>

                <div class="password-requirements">
                  <h6>Critères de sécurité :</h6>
                  <div class="requirement" id="minLength">
                    <i class="bx bx-x"></i> Au moins 8 caractères
                  </div>
                  <div class="requirement" id="hasUpper">
                    <i class="bx bx-x"></i> Une lettre majuscule
                  </div>
                  <div class="requirement" id="hasLower">
                    <i class="bx bx-x"></i> Une lettre minuscule
                  </div>
                  <div class="requirement" id="hasNumber">
                    <i class="bx bx-x"></i> Un chiffre
                  </div>
                  <div class="requirement" id="hasSpecial">
                    <i class="bx bx-x"></i> Un caractère spécial
                  </div>
                </div>
                <br />

                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit" name="connexion" id="submit">
                    Valider
                  </button>
                </div>
              </form>

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
      // Password visibility toggles
      document.getElementById('togglePassword1').addEventListener('click', function() {
        const passwordInput = document.getElementById('Nouveau_mot_de_passe');
        const toggleIcon = document.getElementById('toggleIcon1');
        
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

      document.getElementById('togglePassword2').addEventListener('click', function() {
        const passwordInput = document.getElementById('confirm_mot_de_passe');
        const toggleIcon = document.getElementById('toggleIcon2');
        
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

      // Password strength checker
      function checkPasswordStrength(password) {
        const strengthBar = document.getElementById('strengthBar');
        const requirements = {
          minLength: password.length >= 8,
          hasUpper: /[A-Z]/.test(password),
          hasLower: /[a-z]/.test(password),
          hasNumber: /\d/.test(password),
          hasSpecial: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };

        // Update requirement indicators
        Object.keys(requirements).forEach(req => {
          const element = document.getElementById(req);
          const icon = element.querySelector('i');

          if (requirements[req]) {
            element.classList.add('valid');
            icon.classList.remove('bx-x');
            icon.classList.add('bx-check');
          } else {
            element.classList.remove('valid');
            icon.classList.remove('bx-check');
            icon.classList.add('bx-x');
          }
        });

        // Masquer le bloc entier si tous les critères sont remplis
        const allValid = Object.values(requirements).every(Boolean);
        const requirementsBlock = document.querySelector('.password-requirements');
        if (allValid) {
          requirementsBlock.classList.add('all-valid');
        } else {
          requirementsBlock.classList.remove('all-valid');
        }

        // Calculate strength
        const validCount = Object.values(requirements).filter(Boolean).length;
        
        if (validCount <= 2) {
          strengthBar.className = 'password-strength-bar password-strength-weak';
        } else if (validCount <= 4) {
          strengthBar.className = 'password-strength-bar password-strength-medium';
        } else {
          strengthBar.className = 'password-strength-bar password-strength-strong';
        }
      }

      // Password matching checker
      function checkPasswordMatch() {
        const password1 = document.getElementById('Nouveau_mot_de_passe').value;
        const password2 = document.getElementById('confirm_mot_de_passe').value;
        const matchElement = document.getElementById('passwordsMatch');
        const noMatchElement = document.getElementById('passwordsNoMatch');

        if (password2.length > 0) {
          if (password1 === password2) {
            matchElement.classList.add('show');
            noMatchElement.classList.remove('show');
          } else {
            matchElement.classList.remove('show');
            noMatchElement.classList.add('show');
          }
        } else {
          matchElement.classList.remove('show');
          noMatchElement.classList.remove('show');
        }
      }

      // Event listeners
      document.getElementById('Nouveau_mot_de_passe').addEventListener('input', function() {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
      });

      document.getElementById('confirm_mot_de_passe').addEventListener('input', function() {
        checkPasswordMatch();
      });

      // Form submission with loading state
      document.getElementById('formAuthentication').addEventListener('submit', function(e) {
        const password1 = document.getElementById('Nouveau_mot_de_passe').value;
        const password2 = document.getElementById('confirm_mot_de_passe').value;
        
        if (password1 !== password2) {
          e.preventDefault();
          Swal.fire({
              icon: 'warning',
              title: 'ERREUR',
              text: "Les mots de passe ne correspondent pas !",
              confirmButtonClass: 'btn btn-danger',
              buttonsStyling: false
          });
          return;
        }

        const submitBtn = document.getElementById('submit');
        submitBtn.classList.add('btn-loading');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Validation en cours...';
      });

      // Add input focus animations
      const inputs = document.querySelectorAll('.form-control');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.parentElement.style.transform = 'translateY(-2px)';
        });
        
        input.addEventListener('blur', function() {
          this.parentElement.parentElement.style.transform = 'translateY(0)';
        });
      });

      // Add subtle parallax effect to shapes
      document.addEventListener('mousemove', function(e) {
        const shapes = document.querySelectorAll('.shape');
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;
        
        shapes.forEach((shape, index) => {
          const speed = (index + 1) * 0.3;
          const xOffset = (x - 0.5) * speed * 8;
          const yOffset = (y - 0.5) * speed * 8;
          
          shape.style.transform = `translate(${xOffset}px, ${yOffset}px)`;
        });
      });
    </script>
  </body>
</html>