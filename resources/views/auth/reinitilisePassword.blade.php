<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Lokativ | {{ __('pages.reinit_title') }}</title>
    <meta name="description" content="Réinitialisation de mot de passe Lokativ" />

    @include('css_file')
    <script src="assetsV2/vendor/js/helpers.js"></script>
    <script src="assetsV2/js/config.js"></script>

    <style>
      :root {
        --primary-gradient: linear-gradient(135deg, #0c1445 0%, #0f2878 40%, #1d4ed8 100%);
        --secondary-gradient: linear-gradient(135deg, #0a0f35 0%, #1a3a9f 100%);
        --success-gradient: linear-gradient(135deg, #059669 0%, #10b981 100%);
        --accent-gradient: linear-gradient(135deg, #1d4ed8 0%, #60a5fa 100%);
        --soft-shadow: 0 25px 60px rgba(0,0,0,0.45);
        --soft-shadow-hover: 0 35px 70px rgba(0,0,0,0.55);
        --glass-bg: rgba(255,255,255,0.07);
        --glass-border: rgba(255,255,255,0.15);
      }

      *, *::before, *::after { box-sizing: border-box; }

      body {
        background: var(--primary-gradient);
        min-height: 100vh;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        overflow-x: hidden;
      }

      .bg-animation {
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        overflow: hidden; z-index: 0; pointer-events: none;
      }

      .floating-shapes { position: absolute; width: 100%; height: 100%; }

      .shape {
        position: absolute; border-radius: 50%;
        animation: float 7s ease-in-out infinite; opacity: 0.1;
      }

      .shape:nth-child(1) { width: 240px; height: 240px; background: var(--success-gradient); top: 12%; left: 8%; animation-delay: 0s; }
      .shape:nth-child(2) { width: 170px; height: 170px; background: var(--accent-gradient); top: 48%; right: 7%; animation-delay: 2s; }
      .shape:nth-child(3) { width: 145px; height: 145px; background: var(--secondary-gradient); bottom: 22%; left: 52%; animation-delay: 4s; }
      .shape:nth-child(4) { width: 200px; height: 200px; background: var(--success-gradient); top: 4%; right: 32%; animation-delay: 1s; }
      .shape:nth-child(5) { width: 100px; height: 100px; background: var(--accent-gradient); bottom: 8%; left: 18%; animation-delay: 3s; }

      @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
        25%       { transform: translateY(-24px) rotate(90deg) scale(1.05); }
        50%       { transform: translateY(10px) rotate(180deg) scale(0.95); }
        75%       { transform: translateY(-14px) rotate(270deg) scale(1.08); }
      }

      .container-xxl { position: relative; z-index: 1; }

      .authentication-wrapper {
        min-height: 100vh;
        display: flex; align-items: center; justify-content: center;
        padding: 1.5rem;
      }

      .authentication-inner {
        width: 100%; max-width: 480px;
        animation: slideUp 0.7s ease-out;
      }

      @keyframes slideUp {
        from { opacity: 0; transform: translateY(40px); }
        to   { opacity: 1; transform: translateY(0); }
      }

      .card {
        background: var(--glass-bg);
        backdrop-filter: blur(24px);
        -webkit-backdrop-filter: blur(24px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        box-shadow: var(--soft-shadow);
        overflow: hidden; position: relative;
        transition: box-shadow 0.3s;
      }

      .card::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0;
        height: 3px;
        background: linear-gradient(90deg, #10b981, #34d399, #6ee7b7);
        border-radius: 24px 24px 0 0;
      }

      .card:hover { box-shadow: var(--soft-shadow-hover); }
      .card-body { padding: 2.75rem; }

      /* ── Brand ── */
      .auth-brand {
        display: flex; align-items: center; justify-content: center;
        gap: 10px; margin-bottom: 1.75rem;
      }

      .auth-brand-icon {
        width: 42px; height: 42px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 11px;
        display: flex; align-items: center; justify-content: center; font-size: 20px;
      }

      .auth-brand-name { font-size: 1.45rem; font-weight: 800; color: #fff; letter-spacing: -0.02em; }

      /* ── Headings ── */
      h4 {
        color: #fff; font-weight: 700; font-size: 1.55rem;
        margin-bottom: 0.5rem; text-align: center;
      }

      .welcome-emoji { display: inline-block; animation: wave 2s infinite; transform-origin: 70% 70%; }

      @keyframes wave {
        0%   { transform: rotate(0deg); }
        10%  { transform: rotate(14deg); }
        20%  { transform: rotate(-8deg); }
        30%  { transform: rotate(14deg); }
        40%  { transform: rotate(-4deg); }
        50%  { transform: rotate(10deg); }
        60%  { transform: rotate(0deg); }
        100% { transform: rotate(0deg); }
      }

      .auth-info-box {
        background: rgba(16,185,129,0.1);
        border: 1px solid rgba(16,185,129,0.2);
        border-radius: 12px;
        padding: 0.9rem 1.1rem;
        margin-bottom: 1.75rem;
        color: rgba(255,255,255,0.75);
        font-size: 0.88rem; line-height: 1.6; text-align: center;
      }

      /* ── Form ── */
      .form-label {
        color: rgba(255,255,255,0.8);
        font-weight: 600; font-size: 0.875rem;
        margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;
      }

      .form-control {
        border: 1.5px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        padding: 0.75rem 1rem; font-size: 0.97rem;
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        color: #fff; width: 100%;
      }

      .form-control:focus {
        border-color: #34d399;
        box-shadow: 0 0 0 3px rgba(52,211,153,0.2);
        background: rgba(255,255,255,0.12); outline: none;
      }

      .form-control::placeholder { color: rgba(255,255,255,0.32); }

      /* ── Input group ── */
      .input-group { display: flex; }

      .input-group-merge .form-control {
        border-right: none;
        border-radius: 12px 0 0 12px;
      }

      .input-group-merge:focus-within .form-control {
        border-color: #34d399;
        box-shadow: 0 0 0 3px rgba(52,211,153,0.2);
        background: rgba(255,255,255,0.12);
      }

      .input-group-text {
        background: rgba(255,255,255,0.08);
        border: 1.5px solid rgba(255,255,255,0.15);
        border-left: none;
        border-radius: 0 12px 12px 0;
        color: rgba(255,255,255,0.5);
        transition: all 0.3s ease; cursor: pointer;
        padding: 0 0.9rem;
        display: flex; align-items: center;
      }

      .input-group-merge:focus-within .input-group-text {
        border-color: #34d399;
        background: rgba(255,255,255,0.1);
      }

      .input-group-text:hover { color: #6ee7b7; }
      .input-group-text i { font-size: 1.1rem; transition: all 0.3s; }

      /* ── Form group animated underline ── */
      .form-group { position: relative; margin-bottom: 1.5rem; }

      .form-group::after {
        content: ''; position: absolute; bottom: -3px; left: 0;
        height: 2px; width: 0;
        background: linear-gradient(90deg, #10b981, #34d399);
        transition: width 0.3s ease; border-radius: 1px;
      }

      .form-group:focus-within::after { width: 100%; }

      /* ── Password strength ── */
      .password-strength {
        margin-top: 0.5rem; height: 4px;
        background: rgba(255,255,255,0.1);
        border-radius: 2px; overflow: hidden;
      }

      .password-strength-bar { height: 100%; width: 0; transition: all 0.3s ease; border-radius: 2px; }
      .password-strength-weak   { background: #f87171; width: 33%; }
      .password-strength-medium { background: #fb923c; width: 66%; }
      .password-strength-strong { background: #4ade80; width: 100%; }

      /* ── Password requirements ── */
      .password-requirements {
        margin-top: 1rem; padding: 1rem;
        background: rgba(255,255,255,0.06);
        border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        transition: max-height 0.5s ease, opacity 0.4s ease, padding 0.4s ease, margin 0.4s ease;
        overflow: hidden;
      }

      .password-requirements h6 {
        color: rgba(255,255,255,0.65); font-weight: 600;
        margin-bottom: 0.5rem; font-size: 0.82rem;
      }

      .requirement {
        display: flex; align-items: center; gap: 0.5rem;
        color: rgba(255,255,255,0.45); font-size: 0.8rem;
        margin-bottom: 0.25rem; max-height: 2rem; opacity: 1;
        overflow: hidden;
        transition: max-height 0.4s ease, opacity 0.3s ease, margin 0.4s ease;
      }

      .requirement.valid { max-height: 0; opacity: 0; margin-bottom: 0; }
      .requirement i { font-size: 0.75rem; }

      .password-requirements.all-valid {
        max-height: 0 !important; opacity: 0;
        padding: 0; margin: 0; border: none;
      }

      /* ── Match indicators ── */
      .passwords-match {
        color: #4ade80; font-size: 0.8rem; margin-top: 0.35rem;
        opacity: 0; transition: opacity 0.3s ease;
        display: flex; align-items: center; gap: 4px;
      }

      .passwords-no-match {
        color: #f87171; font-size: 0.8rem; margin-top: 0.35rem;
        opacity: 0; transition: opacity 0.3s ease;
        display: flex; align-items: center; gap: 4px;
      }

      .passwords-match.show, .passwords-no-match.show { opacity: 1; }

      /* ── Button ── */
      .btn-primary {
        background: var(--success-gradient);
        border: none; border-radius: 12px;
        padding: 0.875rem 2rem;
        font-weight: 600; font-size: 1rem; color: #fff;
        width: 100%; cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        position: relative; overflow: hidden;
      }

      .btn-primary::before {
        content: ''; position: absolute;
        top: 0; left: -100%; width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent);
        transition: left 0.5s;
      }

      .btn-primary:hover::before { left: 100%; }
      .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(16,185,129,0.4); }
      .btn-primary:active { transform: translateY(0); }

      /* ── Alerts ── */
      .alert { border-radius: 12px; border: none; backdrop-filter: blur(10px); margin-bottom: 1.25rem; font-size: 0.9rem; animation: fadeIn 0.4s ease; }
      .alert-success { background: rgba(74,222,128,0.12); color: #86efac; border: 1px solid rgba(74,222,128,0.2); }
      .alert-danger  { background: rgba(248,113,113,0.12); color: #fca5a5; border: 1px solid rgba(248,113,113,0.2); }

      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
      }

      /* ── Loading ── */
      .btn-loading { pointer-events: none; }
      .btn-loading::after {
        content: ''; position: absolute; width: 16px; height: 16px;
        margin: auto; inset: 0;
        border: 2px solid transparent; border-top-color: #fff;
        border-radius: 50%; animation: spin 0.8s linear infinite;
      }

      @keyframes spin {
        0%   { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }

      /* ── Responsive ── */
      @media (max-width: 480px) {
        .card-body { padding: 2rem 1.25rem; }
        h4 { font-size: 1.3rem; }
        .authentication-inner { max-width: 100%; }
      }
    </style>
  </head>

  <body>
    <div class="bg-animation">
      <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
      </div>
    </div>

    <div class="container-xxl">
      <div class="authentication-wrapper">
        <div class="authentication-inner">
          <div class="card">
            <div class="card-body">

              <!-- Brand -->
              <div class="auth-brand">
                <div class="auth-brand-icon">🏠</div>
                <span class="auth-brand-name">Lokativ</span>
              </div>

              <h4>{{ __('pages.reinit_heading') }} <span class="welcome-emoji">🔑</span></h4>

              @include('display_message')

              <div class="auth-info-box">
                {{ __('pages.reinit_info_box') }}
              </div>

              <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('changementPwd') }}">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}" />

                <div class="form-group">
                  <label for="Nouveau_mot_de_passe" class="form-label">
                    {{ __('pages.reinit_label_new') }}
                  </label>
                  <div class="input-group input-group-merge">
                    <input type="password" id="Nouveau_mot_de_passe"
                           class="form-control" name="Nouveau_mot_de_passe"
                           placeholder="{{ __('pages.reinit_ph_new') }}"
                           aria-describedby="Nouveau_mot_de_passe" required />
                    <span class="input-group-text cursor-pointer" id="togglePassword1">
                      <i class="bx bx-hide" id="toggleIcon1"></i>
                    </span>
                  </div>
                  <div class="password-strength">
                    <div class="password-strength-bar" id="strengthBar"></div>
                  </div>
                </div>

                <div class="form-group">
                  <label for="confirm_mot_de_passe" class="form-label">
                    {{ __('pages.reinit_label_confirm') }}
                  </label>
                  <div class="input-group input-group-merge">
                    <input type="password" id="confirm_mot_de_passe"
                           class="form-control" name="confirm_mot_de_passe"
                           placeholder="{{ __('pages.reinit_ph_confirm') }}"
                           aria-describedby="confirm_mot_de_passe" required />
                    <span class="input-group-text cursor-pointer" id="togglePassword2">
                      <i class="bx bx-hide" id="toggleIcon2"></i>
                    </span>
                  </div>
                  <div class="passwords-match" id="passwordsMatch">
                    <i class="bx bx-check"></i> {{ __('pages.reinit_match') }}
                  </div>
                  <div class="passwords-no-match" id="passwordsNoMatch">
                    <i class="bx bx-x"></i> {{ __('pages.reinit_no_match') }}
                  </div>
                </div>

                <div class="password-requirements" id="passwordRequirementsBlock">
                  <h6>{{ __('pages.auth_criteria_title') }}</h6>
                  <div class="requirement" id="minLength"><i class="bx bx-x"></i> {{ __('pages.auth_req_length') }}</div>
                  <div class="requirement" id="hasUpper"><i class="bx bx-x"></i> {{ __('pages.auth_req_upper') }}</div>
                  <div class="requirement" id="hasLower"><i class="bx bx-x"></i> {{ __('pages.auth_req_lower') }}</div>
                  <div class="requirement" id="hasNumber"><i class="bx bx-x"></i> {{ __('pages.auth_req_number') }}</div>
                  <div class="requirement" id="hasSpecial"><i class="bx bx-x"></i> {{ __('pages.auth_req_special') }}</div>
                </div>

                <div class="mb-3" style="margin-top:1.5rem;">
                  <button class="btn btn-primary" type="submit" name="connexion" id="submit">
                    {{ __('pages.reinit_btn') }}
                  </button>
                </div>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>

    @include('js_file')

    <script>
      var REINIT_I18N = {
          error:    '{{ __('pages.reinit_js_error') }}',
          noMatch:  '{{ __('pages.reinit_js_no_match') }}',
          loading:  '{{ __('pages.reinit_js_loading') }}',
      };

      // Password visibility toggles
      document.getElementById('togglePassword1').addEventListener('click', function () {
        const input = document.getElementById('Nouveau_mot_de_passe');
        const icon  = document.getElementById('toggleIcon1');
        input.type  = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('bx-hide');
        icon.classList.toggle('bx-show');
      });

      document.getElementById('togglePassword2').addEventListener('click', function () {
        const input = document.getElementById('confirm_mot_de_passe');
        const icon  = document.getElementById('toggleIcon2');
        input.type  = input.type === 'password' ? 'text' : 'password';
        icon.classList.toggle('bx-hide');
        icon.classList.toggle('bx-show');
      });

      // Password strength checker
      function checkPasswordStrength(password) {
        const strengthBar = document.getElementById('strengthBar');
        const requirements = {
          minLength : password.length >= 8,
          hasUpper  : /[A-Z]/.test(password),
          hasLower  : /[a-z]/.test(password),
          hasNumber : /\d/.test(password),
          hasSpecial: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };

        Object.keys(requirements).forEach(req => {
          const el   = document.getElementById(req);
          const icon = el.querySelector('i');
          if (requirements[req]) {
            el.classList.add('valid');
            icon.classList.remove('bx-x'); icon.classList.add('bx-check');
          } else {
            el.classList.remove('valid');
            icon.classList.remove('bx-check'); icon.classList.add('bx-x');
          }
        });

        const allValid = Object.values(requirements).every(Boolean);
        document.getElementById('passwordRequirementsBlock').classList.toggle('all-valid', allValid);

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
        const pw1 = document.getElementById('Nouveau_mot_de_passe').value;
        const pw2 = document.getElementById('confirm_mot_de_passe').value;
        const matchEl   = document.getElementById('passwordsMatch');
        const noMatchEl = document.getElementById('passwordsNoMatch');

        if (pw2.length > 0) {
          if (pw1 === pw2) {
            matchEl.classList.add('show');
            noMatchEl.classList.remove('show');
          } else {
            matchEl.classList.remove('show');
            noMatchEl.classList.add('show');
          }
        } else {
          matchEl.classList.remove('show');
          noMatchEl.classList.remove('show');
        }
      }

      document.getElementById('Nouveau_mot_de_passe').addEventListener('input', function () {
        checkPasswordStrength(this.value);
        checkPasswordMatch();
      });

      document.getElementById('confirm_mot_de_passe').addEventListener('input', checkPasswordMatch);

      // Form submission with loading state
      document.getElementById('formAuthentication').addEventListener('submit', function (e) {
        const pw1 = document.getElementById('Nouveau_mot_de_passe').value;
        const pw2 = document.getElementById('confirm_mot_de_passe').value;
        if (pw1 !== pw2) {
          e.preventDefault();
          Swal.fire({
            icon: 'warning', title: REINIT_I18N.error,
            text: REINIT_I18N.noMatch,
            confirmButtonClass: 'btn btn-danger', buttonsStyling: false
          });
          return;
        }
        const btn = document.getElementById('submit');
        btn.classList.add('btn-loading');
        btn.disabled = true;
        btn.textContent = REINIT_I18N.loading;
      });

      // Subtle parallax on shapes
      document.addEventListener('mousemove', function (e) {
        const shapes = document.querySelectorAll('.shape');
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;
        shapes.forEach((shape, i) => {
          const speed = (i + 1) * 0.3;
          shape.style.transform = `translate(${(x - 0.5) * speed * 8}px, ${(y - 0.5) * speed * 8}px)`;
        });
      });
    </script>
  </body>
</html>
