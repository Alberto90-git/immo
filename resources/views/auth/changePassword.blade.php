<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Lokativ | {{ __('pages.chpwd_title') }}</title>
    <meta name="description" content="Changer votre mot de passe pour sécuriser votre compte Lokativ" />
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
            animation: float 6s ease-in-out infinite; opacity: 0.12;
        }

        .shape:nth-child(1) { width: 260px; height: 260px; background: var(--secondary-gradient); top: 12%; left: 8%; animation-delay: 0s; }
        .shape:nth-child(2) { width: 180px; height: 180px; background: var(--secondary-gradient); top: 50%; right: 6%; animation-delay: 3s; }
        .shape:nth-child(3) { width: 130px; height: 130px; background: var(--secondary-gradient); bottom: 15%; left: 52%; animation-delay: 1.5s; }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50%       { transform: translateY(-20px) rotate(180deg); }
        }

        .container-xxl { position: relative; z-index: 1; }

        .authentication-wrapper {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
        }

        .authentication-inner {
            width: 100%; max-width: 460px;
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

        .auth-subtitle { color: rgba(255,255,255,0.6); font-size: 0.92rem; text-align: center; margin-bottom: 2rem; }

        /* ── Form ── */
        .form-label {
            color: rgba(255,255,255,0.8);
            font-weight: 600; font-size: 0.875rem;
            margin-bottom: 0.4rem; display: block;
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
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96,165,250,0.2);
            background: rgba(255,255,255,0.12); outline: none;
        }

        .form-control::placeholder { color: rgba(255,255,255,0.32); }

        /* ── Input group (password toggle) ── */
        .input-group { position: relative; display: flex; }

        .input-group-merge .form-control {
            border-right: none;
            border-radius: 12px 0 0 12px;
        }

        .input-group-merge:focus-within .form-control {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96,165,250,0.2);
            background: rgba(255,255,255,0.12);
        }

        .input-group-text {
            background: rgba(255,255,255,0.08);
            border: 1.5px solid rgba(255,255,255,0.15);
            border-left: none;
            border-radius: 0 12px 12px 0;
            color: rgba(255,255,255,0.5);
            transition: all 0.3s ease;
            cursor: pointer; padding: 0 0.9rem;
            display: flex; align-items: center;
        }

        .input-group-merge:focus-within .input-group-text {
            border-color: #60a5fa;
            background: rgba(255,255,255,0.1);
        }

        .input-group-text:hover { color: #93c5fd; }
        .input-group-text i { font-size: 1.1rem; }

        /* ── Password strength ── */
        .password-strength {
            height: 5px; border-radius: 3px; margin-top: 0.6rem;
            background: rgba(255,255,255,0.1); overflow: hidden;
        }

        .password-strength-bar { height: 100%; width: 0; transition: all 0.3s ease; border-radius: 3px; }

        /* ── Password requirements ── */
        .password-requirements {
            margin-top: 0.75rem; padding: 0.875rem 1rem;
            background: rgba(255,255,255,0.06);
            border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            transition: max-height 0.5s ease, opacity 0.4s ease, padding 0.4s ease, margin 0.4s ease;
            overflow: hidden;
        }

        .password-requirements h6 {
            color: rgba(255,255,255,0.7); font-weight: 600;
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

        /* ── Button ── */
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none; border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600; font-size: 1rem; color: #fff;
            width: 100%; cursor: pointer; margin-top: 1rem;
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
        .btn-primary:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        /* ── Alerts ── */
        .alert { border-radius: 12px; border: none; backdrop-filter: blur(10px); margin-bottom: 1.25rem; font-size: 0.9rem; }
        .alert-success { background: rgba(74,222,128,0.12); color: #86efac; border: 1px solid rgba(74,222,128,0.2); }
        .alert-danger  { background: rgba(248,113,113,0.12); color: #fca5a5; border: 1px solid rgba(248,113,113,0.2); }

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

                        <h4>{{ __('pages.chpwd_heading') }}</h4>
                        <p class="auth-subtitle">{{ __('pages.chpwd_subtitle') }}</p>

                        @include('display_message')

                        <form id="formAuthentication" class="mb-3" action="{{ route('password_submit') }}" method="post">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('pages.login_email') }}</label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email" name="email"
                                       placeholder="votre@email.com" autofocus />
                                @error('email')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="Ancien_mot_de_passe">{{ __('pages.chpwd_label_old') }}</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="Ancien_mot_de_passe"
                                           class="form-control @error('Ancien_mot_de_passe') is-invalid @enderror"
                                           name="Ancien_mot_de_passe"
                                           placeholder="••••••••••••"
                                           aria-describedby="Ancien_mot_de_passe" required />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    @error('Ancien_mot_de_passe')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="Nouveau_mot_de_passe">{{ __('pages.chpwd_label_new') }}</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="Nouveau_mot_de_passe"
                                           class="form-control @error('Nouveau_mot_de_passe') is-invalid @enderror"
                                           name="Nouveau_mot_de_passe"
                                           placeholder="••••••••••••"
                                           aria-describedby="Nouveau_mot_de_passe" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    @error('Nouveau_mot_de_passe')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>
                                <div class="password-strength mt-2">
                                    <div class="password-strength-bar" id="password-strength-bar"></div>
                                </div>
                                <small style="font-size:0.8rem;color:rgba(255,255,255,0.5);" id="password-strength-text"></small>
                                <div class="password-requirements" id="passwordRequirementsBlock">
                                    <h6>{{ __('pages.auth_criteria_title') }}</h6>
                                    <div class="requirement" id="req-minLength"><i class="bx bx-x"></i> {{ __('pages.auth_req_length') }}</div>
                                    <div class="requirement" id="req-hasUpper"><i class="bx bx-x"></i> {{ __('pages.auth_req_upper') }}</div>
                                    <div class="requirement" id="req-hasLower"><i class="bx bx-x"></i> {{ __('pages.auth_req_lower') }}</div>
                                    <div class="requirement" id="req-hasNumber"><i class="bx bx-x"></i> {{ __('pages.auth_req_number') }}</div>
                                    <div class="requirement" id="req-hasSpecial"><i class="bx bx-x"></i> {{ __('pages.auth_req_special') }}</div>
                                </div>
                            </div>

                            <button class="btn btn-primary" id="form_submit1" type="submit" name="Confirmer1">
                                {{ __('pages.chpwd_btn') }}
                            </button>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('js_file')

    <script>
        var CHPWD_I18N = {
            veryWeak:   '{{ __('pages.auth_strength_very_weak') }}',
            weak:       '{{ __('pages.auth_strength_weak') }}',
            medium:     '{{ __('pages.auth_strength_medium') }}',
            strong:     '{{ __('pages.auth_strength_strong') }}',
            veryStrong: '{{ __('pages.auth_strength_very_strong') }}',
            loading:    '{{ __('pages.chpwd_js_loading') }}',
        };

        // Password visibility toggle
        document.querySelectorAll('.input-group-text').forEach(icon => {
            icon.addEventListener('click', function () {
                const passwordInput = this.previousElementSibling;
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('bx-hide');
                this.querySelector('i').classList.toggle('bx-show');
            });
        });

        // Password strength + criteria checker
        const passwordInput = document.getElementById('Nouveau_mot_de_passe');
        const strengthBar   = document.getElementById('password-strength-bar');
        const strengthText  = document.getElementById('password-strength-text');

        const criteria = {
            'req-minLength' : pwd => pwd.length >= 8,
            'req-hasUpper'  : pwd => /[A-Z]/.test(pwd),
            'req-hasLower'  : pwd => /[a-z]/.test(pwd),
            'req-hasNumber' : pwd => /\d/.test(pwd),
            'req-hasSpecial': pwd => /[@$!%*#?&]/.test(pwd),
        };

        if (passwordInput) {
            passwordInput.addEventListener('input', function () {
                const password = this.value;
                let validCount = 0;

                Object.entries(criteria).forEach(([id, test]) => {
                    const el   = document.getElementById(id);
                    const icon = el.querySelector('i');
                    const ok   = test(password);
                    if (ok) {
                        el.classList.add('valid');
                        icon.classList.replace('bx-x', 'bx-check');
                        validCount++;
                    } else {
                        el.classList.remove('valid');
                        icon.classList.replace('bx-check', 'bx-x');
                    }
                });

                const block = document.getElementById('passwordRequirementsBlock');
                block.classList.toggle('all-valid', validCount === Object.keys(criteria).length);

                if (strengthBar && strengthText) {
                    let strength = 0, text = '', color = '';
                    if (password.length === 0) { strength = 0; text = ''; color = ''; }
                    else if (validCount <= 1)   { strength = 20; text = CHPWD_I18N.veryWeak; color = '#f87171'; }
                    else if (validCount === 2)  { strength = 40; text = CHPWD_I18N.weak; color = '#fb923c'; }
                    else if (validCount === 3)  { strength = 60; text = CHPWD_I18N.medium; color = '#facc15'; }
                    else if (validCount === 4)  { strength = 80; text = CHPWD_I18N.strong; color = '#4ade80'; }
                    else                       { strength = 100; text = CHPWD_I18N.veryStrong; color = '#22c55e'; }
                    strengthBar.style.width           = strength + '%';
                    strengthBar.style.backgroundColor = color;
                    strengthText.textContent          = text;
                    strengthText.style.color          = color;
                }
            });
        }

        // Submit loading state
        const form      = document.getElementById('formAuthentication');
        const submitBtn = document.getElementById('form_submit1');

        if (form && submitBtn) {
            form.addEventListener('submit', function () {
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                submitBtn.textContent = CHPWD_I18N.loading;
            });
        }
    </script>
</body>

</html>
