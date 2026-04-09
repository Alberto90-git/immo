<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>{{ __('pages.login_title') }}</title>
    <meta name="description" content="Page de connexion à Lokativ, application de gestion des agences immobilières" />

    @include('css_file')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <script src="assetsV2/vendor/js/helpers.js"></script>
    <script src="assetsV2/js/config.js"></script>

    <style>
      :root {
        --primary-gradient: linear-gradient(135deg, #0c1445 0%, #0f2878 40%, #1d4ed8 100%);
        --secondary-gradient: linear-gradient(135deg, #0a0f35 0%, #1a3a9f 100%);
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
        position: absolute;
        background: var(--secondary-gradient);
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
        opacity: 0.12;
      }

      .shape:nth-child(1) { width: 320px; height: 320px; top: 5%; left: 5%; animation-delay: 0s; }
      .shape:nth-child(2) { width: 220px; height: 220px; top: 55%; right: 8%; animation-delay: 2s; }
      .shape:nth-child(3) { width: 160px; height: 160px; bottom: 8%; left: 45%; animation-delay: 4s; }

      @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-22px) rotate(180deg); }
      }

      .container-xxl { position: relative; z-index: 1; }

      .authentication-wrapper {
        min-height: 100vh;
        display: flex; align-items: center; justify-content: center;
        padding: 1.5rem;
      }

      .authentication-inner {
        width: 100%;
        max-width: 460px;
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
        transition: box-shadow 0.3s;
        overflow: hidden;
      }

      .card:hover { box-shadow: var(--soft-shadow-hover); }

      .card-body { padding: 2.75rem; }

      /* ── Brand block ── */
      .auth-brand {
        display: flex; align-items: center; justify-content: center;
        gap: 10px; margin-bottom: 1.75rem;
      }

      .auth-brand-icon {
        width: 42px; height: 42px;
        background: rgba(255,255,255,0.12);
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 11px;
        display: flex; align-items: center; justify-content: center;
        font-size: 20px;
      }

      .auth-brand-name {
        font-size: 1.45rem; font-weight: 800;
        color: #fff; letter-spacing: -0.02em;
      }

      /* ── Headings ── */
      h4 {
        color: #fff;
        font-weight: 700; font-size: 1.6rem;
        margin-bottom: 0.5rem; text-align: center;
      }

      .welcome-emoji {
        display: inline-block;
        animation: wave 2s infinite;
        transform-origin: 70% 70%;
      }

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

      .auth-subtitle {
        color: rgba(255,255,255,0.6);
        font-size: 0.95rem;
        text-align: center;
        margin-bottom: 2rem;
      }

      /* ── Form ── */
      .form-label {
        color: rgba(255,255,255,0.8);
        font-weight: 600; font-size: 0.875rem;
        margin-bottom: 0.4rem; display: block;
      }

      .form-control {
        border: 1.5px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 0.97rem;
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        color: #fff;
        width: 100%;
      }

      .form-control:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.2);
        background: rgba(255,255,255,0.12);
        outline: none;
      }

      .form-control::placeholder { color: rgba(255,255,255,0.32); }

      /* ── Password group ── */
      #password-group {
        display: flex; align-items: stretch;
        border: 1.5px solid rgba(255,255,255,0.15);
        border-radius: 12px; overflow: hidden;
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(10px);
        transition: border-color 0.3s, box-shadow 0.3s;
      }

      #password-group input {
        flex: 1; border: none; background: transparent;
        padding: 0.75rem 1rem; font-size: 0.97rem;
        color: #fff; outline: none; min-width: 0;
      }

      #password-group input::placeholder { color: rgba(255,255,255,0.32); }

      #togglePassword {
        background: transparent; border: none;
        border-left: 1px solid rgba(255,255,255,0.12);
        padding: 0 1rem; cursor: pointer;
        color: rgba(255,255,255,0.45); font-size: 1rem;
        transition: color 0.2s; flex-shrink: 0;
      }

      /* ── Forgot password link ── */
      .forgot-link {
        font-size: 0.8rem; color: rgba(255,255,255,0.55);
        text-decoration: none; transition: color 0.2s;
      }
      .forgot-link:hover { color: #93c5fd; text-decoration: underline; }

      /* ── Remember me ── */
      .form-check-input {
        border-radius: 6px;
        border: 1.5px solid rgba(255,255,255,0.2);
        background: rgba(255,255,255,0.08);
      }
      .form-check-input:checked { background-color: #3b82f6; border-color: #3b82f6; }
      .form-check-label { color: rgba(255,255,255,0.7); font-weight: 500; font-size: 0.9rem; }

      /* ── Button ── */
      .btn-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        border: none; border-radius: 12px;
        padding: 0.875rem 2rem;
        font-weight: 600; font-size: 1rem;
        color: #fff; width: 100%;
        transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
        position: relative; overflow: hidden;
        cursor: pointer;
      }

      .btn-primary::before {
        content: ''; position: absolute;
        top: 0; left: -100%; width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.18), transparent);
        transition: left 0.5s;
      }

      .btn-primary:hover::before { left: 100%; }
      .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(29,78,216,0.45); }
      .btn-primary:active { transform: translateY(0); }

      /* ── Register link ── */
      .auth-footer { text-align: center; margin-top: 1.5rem; }
      .auth-footer span { color: rgba(255,255,255,0.55); font-size: 0.9rem; }
      .auth-footer a { color: #93c5fd; font-weight: 600; text-decoration: none; transition: color 0.2s; }
      .auth-footer a:hover { color: #fff; text-decoration: underline; }

      /* ── Alerts ── */
      .alert {
        border-radius: 12px; border: none;
        backdrop-filter: blur(10px); margin-bottom: 1.25rem;
        font-size: 0.9rem;
      }

      .alert-success { background: rgba(74,222,128,0.12); color: #86efac; border: 1px solid rgba(74,222,128,0.2); }
      .alert-danger  { background: rgba(248,113,113,0.12); color: #fca5a5; border: 1px solid rgba(248,113,113,0.2); }

      .invalid-feedback { color: #fca5a5; font-size: 0.8rem; margin-top: 0.35rem; }

      /* ── Divider ── */
      .auth-divider {
        border: none;
        border-top: 1px solid rgba(255,255,255,0.1);
        margin: 1.5rem 0;
      }

      /* ── Loading ── */
      .btn-loading { pointer-events: none; }
      .btn-loading::after {
        content: '';
        position: absolute; width: 16px; height: 16px;
        margin: auto; inset: 0;
        border: 2px solid transparent;
        border-top-color: #fff;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
      }

      @keyframes spin {
        0%   { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
      }

      /* ── Responsive ── */
      @media (max-width: 480px) {
        .card-body { padding: 2rem 1.25rem; }
        h4 { font-size: 1.35rem; }
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

              <h4>{{ __('pages.login_welcome') }} <span class="welcome-emoji">👋</span></h4>
              <p class="auth-subtitle">{{ __('pages.login_subtitle') }}</p>

              @if(session('message'))
              <div class="alert alert-success">{{ session('message') }}</div>
              @endif

              <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('login_check') }}">
                @csrf

                <div class="mb-3">
                  <label for="email" class="form-label">{{ __('pages.login_email') }}</label>
                  <input type="email"
                         class="form-control @error('email') is-invalid @enderror"
                         id="email" name="email"
                         placeholder="{{ __('pages.login_email_ph') }}"
                         autofocus required />
                  @error('email')
                  <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                  @enderror
                </div>

                <div class="mb-3">
                  <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.4rem;">
                    <label class="form-label" for="password" style="margin-bottom:0;">{{ __('pages.login_password') }}</label>
                    <a href="{{ route('getEmail') }}" class="forgot-link">{{ __('pages.login_forgot') }}</a>
                  </div>
                  <div id="password-group">
                    <input type="password" id="password" name="password"
                           placeholder="••••••••••••" required />
                    <button type="button" id="togglePassword" aria-label="{{ __('pages.login_toggle_pw') }}">
                      <i class="fas fa-eye-slash" id="toggleIcon"></i>
                    </button>
                  </div>
                </div>

                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me">{{ __('pages.login_remember') }}</label>
                  </div>
                </div>

                <div class="mb-2">
                  <button class="btn btn-primary" type="submit" name="connexion" id="submit">
                    {{ __('pages.login_btn') }}
                  </button>
                </div>
              </form>

              <div class="auth-footer">
                <span>{{ __('pages.login_no_account') }}</span>
                <a href="{{ route('accueil') }}"> {{ __('pages.login_create') }}</a>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    @include('js_file')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <script>
      var LOGIN_I18N = {
          errorTitle:    '{{ __('pages.login_error_title') }}',
          blockedTitle:  '{{ __('pages.login_blocked_title') }}',
          blockedHtml:   '{{ __('pages.login_blocked_html') }}',
          blockedWa:     '{{ __('pages.login_blocked_wa') }}',
          blockedClose:  '{{ __('pages.login_blocked_close') }}',
          btnLoading:    '{{ __('pages.login_btn_loading') }}',
      };

      @if(session('error') && !str_contains(session('error'), 'bloquée'))
      document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
          icon: 'error', title: LOGIN_I18N.errorTitle,
          text: @json(session('error')),
          confirmButtonText: 'OK', confirmButtonColor: '#e53e3e',
        });
      });
      @endif

      @if(session('error') && str_contains(session('error'), 'bloquée'))
      document.addEventListener('DOMContentLoaded', function () {
        @php $waContactBlocage = \App\PlatformConfig::getConfig()->whatsapp_contact_blocage ?? '22961082260'; @endphp
        Swal.fire({
          icon: 'error',
          title: '<span style="color:#dc3545;font-size:1.3rem;">' + LOGIN_I18N.blockedTitle + '</span>',
          html: `
            <p style="font-size:14px;color:#555;margin-bottom:18px;line-height:1.6;">
              ${LOGIN_I18N.blockedHtml}
            </p>
            <a href="https://wa.me/{{ $waContactBlocage }}" target="_blank"
               style="display:inline-flex;align-items:center;gap:8px;
                      background:#25D366;color:#fff;padding:10px 22px;
                      border-radius:8px;text-decoration:none;
                      font-weight:600;font-size:14px;box-shadow:0 4px 12px rgba(37,211,102,0.35);">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="white" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zm-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884zm8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
              </svg>
              ` + LOGIN_I18N.blockedWa + `
            </a>`,
          confirmButtonText: LOGIN_I18N.blockedClose, confirmButtonColor: '#6c757d',
          allowOutsideClick: false, allowEscapeKey: false,
        });
      });
      @endif

      // Toggle password visibility
      document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon  = document.getElementById('toggleIcon');
        const show  = input.type === 'password';
        input.type  = show ? 'text' : 'password';
        icon.classList.toggle('fa-eye-slash', !show);
        icon.classList.toggle('fa-eye', show);
        this.style.color = show ? '#60a5fa' : 'rgba(255,255,255,0.45)';
      });

      // Focus/blur on password group
      const pwInput = document.getElementById('password');
      const pwGroup = document.getElementById('password-group');
      pwInput.addEventListener('focus', function () {
        pwGroup.style.borderColor = '#60a5fa';
        pwGroup.style.boxShadow   = '0 0 0 3px rgba(96,165,250,0.2)';
      });
      pwInput.addEventListener('blur', function () {
        pwGroup.style.borderColor = 'rgba(255,255,255,0.15)';
        pwGroup.style.boxShadow   = 'none';
      });

      // Hover sur toggle button
      const toggleBtn = document.getElementById('togglePassword');
      toggleBtn.addEventListener('mouseenter', function () { this.style.color = '#60a5fa'; });
      toggleBtn.addEventListener('mouseleave', function () {
        if (document.getElementById('password').type === 'password') this.style.color = 'rgba(255,255,255,0.45)';
      });

      // Submit loading state
      document.getElementById('formAuthentication').addEventListener('submit', function () {
        const btn = document.getElementById('submit');
        btn.classList.add('btn-loading');
        btn.disabled = true;
        btn.textContent = LOGIN_I18N.btnLoading;
      });
    </script>
  </body>
</html>
