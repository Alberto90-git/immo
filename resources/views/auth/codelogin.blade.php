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
    <title>Immo | Code de vérification</title>

    <meta name="description" content="Vérification OTP pour accéder à votre compte Immo" />
    @include('css_file')

    <script src="assetsV2/vendor/js/helpers.js"></script>
    <script src="assetsV2/js/config.js"></script>
    
    <style>
      :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        --success-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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
        width: 250px;
        height: 250px;
        top: 15%;
        left: 15%;
        animation-delay: 0s;
      }

      .shape:nth-child(2) {
        width: 180px;
        height: 180px;
        top: 50%;
        right: 25%;
        animation-delay: 3s;
      }

      .shape:nth-child(3) {
        width: 120px;
        height: 120px;
        bottom: 20%;
        left: 60%;
        animation-delay: 1.5s;
      }

      .security-icon {
        position: absolute;
        width: 60px;
        height: 60px;
        background: var(--success-gradient);
        border-radius: 50%;
        top: 30%;
        right: 10%;
        animation: pulse 3s ease-in-out infinite;
        opacity: 0.1;
      }

      .security-icon::before {
        content: '🔒';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 24px;
      }

      @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
      }

      @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 0.1; }
        50% { transform: scale(1.2); opacity: 0.2; }
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
      }

      .card:hover {
        box-shadow: var(--soft-shadow-hover);
        transform: translateY(-5px);
      }

      .card-body {
        padding: 3rem;
        text-align: center;
      }

      h4 {
        color: #2d3748;
        font-weight: 700;
        font-size: 1.75rem;
        margin-bottom: 1rem;
        position: relative;
      }

      h4::after {
        content: '🔐';
        position: absolute;
        right: -40px;
        top: 0;
        font-size: 1.5rem;
        animation: wiggle 2s ease-in-out infinite;
      }

      @keyframes wiggle {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(5deg); }
        75% { transform: rotate(-5deg); }
      }

      .mb-4 {
        color:rgb(229, 233, 239);
        font-size: 1.1rem;
        margin-bottom: 2rem !important;
      }

      .form-label {
        color: #4a5568;
        font-weight: 600;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        text-align: left;
      }

      /* OTP Input Container */
      .otp-container {
        display: flex;
        justify-content: center;
        gap: 0.75rem;
        margin-bottom: 2rem;
      }

      .otp-input {
        width: 50px;
        height: 60px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        font-size: 1.5rem;
        font-weight: 700;
        text-align: center;
        color: #2d3748;
        transition: all 0.3s ease;
        outline: none;
      }

      .otp-input:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px) scale(1.05);
      }

      .otp-input.filled {
        border-color: #48bb78;
        background: rgba(72, 187, 120, 0.1);
        animation: bounceIn 0.3s ease-out;
      }

      @keyframes bounceIn {
        0% { transform: scale(0.8); }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); }
      }

      /* Hidden original input */
      .form-control {
        position: absolute;
        opacity: 0;
        pointer-events: none;
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
        margin-top: 1rem;
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

      .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
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

      /* Resend link */
      .resend-container {
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid rgba(221, 212, 212, 0.1);
      }

      .resend-link {
        color:rgb(13, 16, 26);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .resend-link:hover {
        color: #764ba2;
        text-decoration: underline;
      }

      .resend-link:disabled {
        opacity: 0.5;
        cursor: not-allowed;
      }

      /* Timer display */
      .timer {
        color: #f56565;
        font-weight: 600;
        font-size: 0.9rem;
        margin-top: 1rem;
      }

      /* Responsive design */
      @media (max-width: 768px) {
        .card-body {
          padding: 2rem 1.5rem;
        }
        
        .otp-input {
          width: 45px;
          height: 55px;
          font-size: 1.25rem;
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
        <div class="security-icon"></div>
      </div>
    </div>

    <!-- Content -->
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- OTP Verification -->
          <div class="card">
            <div class="card-body">
              <!-- Logo 
              <div class="logo-container">
                @include('logo')
              </div> -->
              
              @include('display_message')
              
              <h4 class="mb-2">Vérification</h4>
              <p class="mb-4">Entrez le code OTP envoyé à votre email</p>
              
              <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('code_submit') }}">
                @csrf

                <div class="mb-3">
                  <label for="code" class="form-label">Code de vérification</label>
                  
                  <!-- OTP Input Fields -->
                  <div class="otp-container">
                    <input type="text" class="otp-input" maxlength="1" data-index="0">
                    <input type="text" class="otp-input" maxlength="1" data-index="1">
                    <input type="text" class="otp-input" maxlength="1" data-index="2">
                    <input type="text" class="otp-input" maxlength="1" data-index="3">
                    <input type="text" class="otp-input" maxlength="1" data-index="4">
                    <input type="text" class="otp-input" maxlength="1" data-index="5">
                  </div>
                  
                  <!-- Hidden original input for form submission -->
                  <input
                    type="text"
                    class="form-control"
                    id="code"
                    name="code"
                    maxlength="6"
                    required
                    inputmode="numeric"
                    pattern="^\d+$"
                    title="Seuls les chiffres sont autorisés"
                  />
                </div>
                
                <button class="btn btn-primary d-grid w-100" type="submit" id="submitBtn">
                  Valider le code
                </button>
              </form>

              <!-- Resend section -->
              <div class="resend-container">
                <p class="text-muted mb-2">Vous n'avez pas reçu le code ?</p>
                <a href="#" class="resend-link" id="resendLink">Renvoyer le code</a>
                <div class="timer" id="timer" style="display: none;">
                  Vous pourrez renvoyer le code dans <span id="countdown">60</span> secondes
                </div>
              </div>
            </div>
          </div>
          <!-- /OTP Verification -->
        </div>
      </div>
    </div>

    <!-- / Content -->
    @include('js_file')

    <script>
      // OTP Input handling
      const otpInputs = document.querySelectorAll('.otp-input');
      const hiddenInput = document.getElementById('code');
      const submitBtn = document.getElementById('submitBtn');
      const form = document.getElementById('formAuthentication');

      // Focus on first input
      otpInputs[0].focus();

      otpInputs.forEach((input, index) => {
        input.addEventListener('input', function(e) {
          // Only allow numbers
          this.value = this.value.replace(/\D/g, '');
          
          // Add filled class if input has value
          if (this.value) {
            this.classList.add('filled');
          } else {
            this.classList.remove('filled');
          }
          
          // Update hidden input
          updateHiddenInput();
          
          // Move to next input
          if (this.value && index < otpInputs.length - 1) {
            otpInputs[index + 1].focus();
          }
          
          // Submit form if all inputs are filled
          if (index === otpInputs.length - 1 && this.value) {
            setTimeout(() => {
              if (isFormComplete()) {
                submitBtn.focus();
              }
            }, 100);
          }
        });

        input.addEventListener('keydown', function(e) {
          // Handle backspace
          if (e.key === 'Backspace' && !this.value && index > 0) {
            otpInputs[index - 1].focus();
            otpInputs[index - 1].value = '';
            otpInputs[index - 1].classList.remove('filled');
            updateHiddenInput();
          }
          
          // Handle arrow keys
          if (e.key === 'ArrowLeft' && index > 0) {
            otpInputs[index - 1].focus();
          }
          if (e.key === 'ArrowRight' && index < otpInputs.length - 1) {
            otpInputs[index + 1].focus();
          }
        });

        input.addEventListener('paste', function(e) {
          e.preventDefault();
          const paste = e.clipboardData.getData('text');
          const numbers = paste.replace(/\D/g, '').substring(0, 6);
          
          // Fill inputs with pasted numbers
          for (let i = 0; i < numbers.length && i < otpInputs.length; i++) {
            otpInputs[i].value = numbers[i];
            otpInputs[i].classList.add('filled');
          }
          
          updateHiddenInput();
          
          // Focus on next empty input or submit button
          const nextEmptyIndex = numbers.length < otpInputs.length ? numbers.length : -1;
          if (nextEmptyIndex >= 0) {
            otpInputs[nextEmptyIndex].focus();
          } else {
            submitBtn.focus();
          }
        });
      });

      function updateHiddenInput() {
        const code = Array.from(otpInputs).map(input => input.value).join('');
        hiddenInput.value = code;
        
        // Enable/disable submit button
        submitBtn.disabled = code.length !== 6;
      }

      function isFormComplete() {
        return Array.from(otpInputs).every(input => input.value.length === 1);
      }

      // Form submission with loading state
      form.addEventListener('submit', function(e) {
        if (!isFormComplete()) {
          e.preventDefault();
          otpInputs.find(input => !input.value).focus();
          return;
        }
        
        submitBtn.classList.add('btn-loading');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Vérification...';
      });

      // Resend functionality with timer
      let countdownTimer;
      const resendLink = document.getElementById('resendLink');
      const timerDiv = document.getElementById('timer');
      const countdownSpan = document.getElementById('countdown');

      resendLink.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Disable resend link
        this.style.pointerEvents = 'none';
        this.style.opacity = '0.5';
        
        // Show timer
        timerDiv.style.display = 'block';
        
        // Start countdown
        let seconds = 60;
        countdownSpan.textContent = seconds;
        
        countdownTimer = setInterval(() => {
          seconds--;
          countdownSpan.textContent = seconds;
          
          if (seconds <= 0) {
            clearInterval(countdownTimer);
            timerDiv.style.display = 'none';
            resendLink.style.pointerEvents = 'auto';
            resendLink.style.opacity = '1';
          }
        }, 1000);
        
        // Here you would normally make an AJAX call to resend the code
        console.log('Resending OTP code...');
      });

      // Auto-focus on input errors
      window.addEventListener('load', function() {
        if (document.querySelector('.alert-danger')) {
          otpInputs.forEach(input => {
            input.value = '';
            input.classList.remove('filled');
          });
          otpInputs[0].focus();
        }
      });
    </script>
  </body>
</html>