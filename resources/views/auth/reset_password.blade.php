<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Lokativ | {{ __('pages.forgot_title') }}</title>
    <meta name="description" content="Réinitialisation de mot de passe Lokativ" />

    @include('css_file')
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
        position: absolute; border-radius: 50%;
        animation: float 8s ease-in-out infinite; opacity: 0.1;
      }

      .shape:nth-child(1) { width: 280px; height: 280px; background: var(--secondary-gradient); top: 12%; left: 8%; animation-delay: 0s; }
      .shape:nth-child(2) { width: 200px; height: 200px; background: var(--secondary-gradient); top: 52%; right: 6%; animation-delay: 3s; }
      .shape:nth-child(3) { width: 140px; height: 140px; background: var(--secondary-gradient); bottom: 15%; left: 55%; animation-delay: 6s; }
      .shape:nth-child(4) { width: 220px; height: 220px; background: var(--secondary-gradient); top: 5%; right: 32%; animation-delay: 1.5s; }

      @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg) scale(1); }
        33%       { transform: translateY(-28px) rotate(120deg) scale(1.08); }
        66%       { transform: translateY(12px) rotate(240deg) scale(0.92); }
      }

      .container-xxl { position: relative; z-index: 1; }

      .authentication-wrapper {
        min-height: 100vh;
        display: flex; align-items: center; justify-content: center;
        padding: 1.5rem;
      }

      .authentication-inner {
        width: 100%; max-width: 440px;
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
        background: linear-gradient(90deg, #3b82f6, #60a5fa, #93c5fd);
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

      /* ── Heading ── */
      h4 {
        color: #fff; font-weight: 700; font-size: 1.55rem;
        margin-bottom: 0.5rem; text-align: center;
        display: flex; align-items: center; justify-content: center; gap: 0.4rem;
      }

      .lock-emoji { display: inline-block; animation: bounce 2s infinite; font-size: 1.4rem; }

      @keyframes bounce {
        0%, 20%, 53%, 80%, 100% { transform: translate3d(0,0,0); }
        40%, 43% { transform: translate3d(0,-14px,0); }
        70% { transform: translate3d(0,-7px,0); }
        90% { transform: translate3d(0,-2px,0); }
      }

      .auth-subtitle { color: rgba(255,255,255,0.6); font-size: 0.92rem; text-align: center; margin-bottom: 1.75rem; line-height: 1.6; }

      /* ── Instruction box ── */
      .instruction-box {
        background: rgba(96,165,250,0.1);
        border: 1px solid rgba(96,165,250,0.2);
        border-radius: 12px;
        padding: 1rem 1.1rem;
        margin-bottom: 1.5rem;
        color: rgba(255,255,255,0.75);
        font-size: 0.88rem;
        line-height: 1.6;
      }

      /* ── Form ── */
      .form-label {
        color: rgba(255,255,255,0.8);
        font-weight: 600; font-size: 0.875rem;
        margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.4rem;
      }

      .form-label::before { content: '📧'; font-size: 0.8rem; }

      .form-control {
        border: 1.5px solid rgba(255,255,255,0.15);
        border-radius: 12px;
        padding: 0.82rem 1rem; font-size: 0.97rem;
        background: rgba(255,255,255,0.08);
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        color: #fff; width: 100%;
      }

      .form-control:focus {
        border-color: #60a5fa;
        box-shadow: 0 0 0 3px rgba(96,165,250,0.2);
        background: rgba(255,255,255,0.12); outline: none;
      }

      .form-control::placeholder { color: rgba(255,255,255,0.32); }

      .form-group { position: relative; margin-bottom: 1.5rem; }

      .form-group::after {
        content: ''; position: absolute; bottom: 0; left: 0;
        height: 2px; width: 0;
        background: linear-gradient(90deg, #3b82f6, #60a5fa);
        transition: width 0.3s ease; border-radius: 1px;
      }

      .form-group:focus-within::after { width: 100%; }

      /* ── Button ── */
      .btn-primary {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
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
      .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 28px rgba(29,78,216,0.45); }
      .btn-primary:active { transform: translateY(0); }

      /* ── Back link ── */
      .back-link {
        display: flex; align-items: center; justify-content: center;
        gap: 6px; margin-top: 1.5rem;
        color: rgba(255,255,255,0.6);
        font-weight: 600; font-size: 0.9rem;
        text-decoration: none;
        padding: 0.7rem 1rem; border-radius: 12px;
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.1);
        transition: all 0.25s;
      }

      .back-link:hover {
        background: rgba(255,255,255,0.1);
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0,0,0,0.2);
      }

      .back-link i { font-size: 1.1rem; transition: transform 0.3s; }
      .back-link:hover i { transform: translateX(-3px); }

      /* ── Alerts ── */
      .alert { border-radius: 12px; border: none; backdrop-filter: blur(10px); margin-bottom: 1.25rem; font-size: 0.9rem; animation: fadeIn 0.4s ease; }
      .alert-success { background: rgba(74,222,128,0.12); color: #86efac; border: 1px solid rgba(74,222,128,0.2); }
      .alert-danger  { background: rgba(248,113,113,0.12); color: #fca5a5; border: 1px solid rgba(248,113,113,0.2); }
      .alert-info    { background: rgba(96,165,250,0.12); color: #93c5fd; border: 1px solid rgba(96,165,250,0.2); }

      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-8px); }
        to   { opacity: 1; transform: translateY(0); }
      }

      .invalid-feedback { color: #fca5a5; font-size: 0.8rem; margin-top: 0.35rem; }

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
      </div>
    </div>

    <div class="container-xxl">
      <div class="authentication-wrapper">
        <div class="authentication-inner py-4">
          <div class="card">
            <div class="card-body">

              <!-- Brand -->
              <div class="auth-brand">
                <div class="auth-brand-icon">🏠</div>
                <span class="auth-brand-name">Lokativ</span>
              </div>

              <h4>{{ __('pages.forgot_heading') }} <span class="lock-emoji">🔒</span></h4>

              @include('display_message')

              <div class="instruction-box">
                {{ __('pages.forgot_instruction') }}
              </div>

              <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('forgot-password') }}">
                @csrf
                <div class="form-group">
                  <label for="email" class="form-label">{{ __('pages.forgot_label') }}</label>
                  <input type="email"
                         class="form-control @error('email') is-invalid @enderror"
                         id="email" name="email"
                         placeholder="{{ __('pages.forgot_ph') }}"
                         autofocus required />
                  @error('email')
                  <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                  @enderror
                </div>

                <div class="mb-3">
                  <button class="btn btn-primary" type="submit" id="submit">
                    {{ __('pages.forgot_btn') }}
                  </button>
                </div>
              </form>

              <a href="{{ route('login') }}" class="back-link">
                <i class="bx bx-chevron-left"></i>
                {{ __('pages.forgot_back') }}
              </a>

            </div>
          </div>
        </div>
      </div>
    </div>

    @include('js_file')

    <script>
      document.getElementById('formAuthentication').addEventListener('submit', function () {
        const btn = document.getElementById('submit');
        btn.classList.add('btn-loading');
        btn.disabled = true;
        btn.textContent = '{{ __('pages.forgot_btn_loading') }}';
      });

      // Subtle parallax on shapes
      document.addEventListener('mousemove', function (e) {
        const shapes = document.querySelectorAll('.shape');
        const x = e.clientX / window.innerWidth;
        const y = e.clientY / window.innerHeight;
        shapes.forEach((shape, i) => {
          const speed = (i + 1) * 0.4;
          shape.style.transform = `translate(${(x - 0.5) * speed * 9}px, ${(y - 0.5) * speed * 9}px)`;
        });
      });
    </script>
  </body>
</html>
