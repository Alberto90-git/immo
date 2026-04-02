<!DOCTYPE html>
<html lang="fr" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Lokativ | Code de vérification</title>
    <meta name="description" content="Vérification OTP pour accéder à votre compte Lokativ" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            position: absolute; background: var(--secondary-gradient);
            border-radius: 50%; animation: float 6s ease-in-out infinite; opacity: 0.12;
        }

        .shape:nth-child(1) { width: 260px; height: 260px; top: 12%; left: 8%; animation-delay: 0s; }
        .shape:nth-child(2) { width: 190px; height: 190px; top: 52%; right: 6%; animation-delay: 3s; }
        .shape:nth-child(3) { width: 130px; height: 130px; bottom: 12%; left: 52%; animation-delay: 1.5s; }

        /* Security icon */
        .security-orb {
            position: absolute; width: 70px; height: 70px;
            background: radial-gradient(circle, rgba(96,165,250,0.25), rgba(37,99,235,0.1));
            border-radius: 50%; top: 28%; right: 8%;
            animation: pulse-orb 3s ease-in-out infinite; opacity: 0.5;
        }

        @keyframes pulse-orb {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50%       { transform: scale(1.25); opacity: 0.25; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50%       { transform: translateY(-22px) rotate(180deg); }
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

        .card:hover { box-shadow: var(--soft-shadow-hover); }
        .card-body { padding: 2.75rem; text-align: center; }

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
            margin-bottom: 0.75rem;
        }

        .auth-subtitle { color: rgba(255,255,255,0.6); font-size: 0.93rem; margin-bottom: 1.75rem; }

        /* ── OTP status ── */
        #otp-sending {
            display: flex; align-items: center; justify-content: center;
            gap: 8px; color: #93c5fd; font-size: 0.88rem;
        }

        #otp-sent {
            display: none;
            background: rgba(74,222,128,0.1);
            border: 1px solid rgba(74,222,128,0.2);
            border-radius: 10px; padding: 8px 14px;
            color: #86efac; font-size: 0.88rem;
        }

        #otp-error {
            display: none;
            background: rgba(248,113,113,0.1);
            border: 1px solid rgba(248,113,113,0.2);
            border-radius: 10px; padding: 8px 14px;
            color: #fca5a5; font-size: 0.88rem;
        }

        /* ── OTP Inputs ── */
        .otp-container {
            display: flex; justify-content: center;
            gap: 0.65rem; margin-bottom: 2rem;
        }

        .otp-input {
            width: 52px; height: 62px;
            border: 1.5px solid rgba(255,255,255,0.18);
            border-radius: 13px;
            background: rgba(255,255,255,0.08);
            backdrop-filter: blur(10px);
            font-size: 1.5rem; font-weight: 700;
            text-align: center; color: #fff;
            transition: all 0.25s ease; outline: none;
        }

        .otp-input:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96,165,250,0.22);
            background: rgba(255,255,255,0.13);
            transform: translateY(-3px) scale(1.06);
        }

        .otp-input.filled {
            border-color: #4ade80;
            background: rgba(74,222,128,0.1);
            animation: bounceIn 0.3s ease-out;
        }

        @keyframes bounceIn {
            0%   { transform: scale(0.8); }
            55%  { transform: scale(1.12); }
            100% { transform: scale(1); }
        }

        /* Hidden real input */
        .form-control { position: absolute; opacity: 0; pointer-events: none; }

        /* ── Label ── */
        .form-label {
            color: rgba(255,255,255,0.75); font-weight: 600;
            font-size: 0.875rem; margin-bottom: 1rem; display: block;
        }

        /* ── Button ── */
        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border: none; border-radius: 12px;
            padding: 0.875rem 2rem;
            font-weight: 600; font-size: 1rem; color: #fff;
            width: 100%; cursor: pointer; margin-top: 0.5rem;
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

        /* ── Resend ── */
        .resend-container {
            margin-top: 2rem; padding-top: 1.75rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .resend-hint { color: rgba(255,255,255,0.45); font-size: 0.86rem; margin-bottom: 0.4rem; }

        .resend-link {
            color: #93c5fd; text-decoration: none;
            font-weight: 600; font-size: 0.93rem;
            transition: color 0.2s;
        }

        .resend-link:hover { color: #fff; text-decoration: underline; }

        .timer { color: #fca5a5; font-weight: 600; font-size: 0.88rem; margin-top: 0.85rem; }

        /* ── Alerts ── */
        .alert { border-radius: 12px; border: none; backdrop-filter: blur(10px); margin-bottom: 1.25rem; font-size: 0.9rem; }
        .alert-success { background: rgba(74,222,128,0.12); color: #86efac; border: 1px solid rgba(74,222,128,0.2); }
        .alert-danger  { background: rgba(248,113,113,0.12); color: #fca5a5; border: 1px solid rgba(248,113,113,0.2); }

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
            .otp-input { width: 44px; height: 54px; font-size: 1.25rem; gap: 0.45rem; }
        }
    </style>
</head>

<body>
    <div class="bg-animation">
        <div class="floating-shapes">
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="security-orb"></div>
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

                        <h4>Vérification 🔐</h4>
                        <p class="auth-subtitle">Saisissez le code OTP envoyé à votre e-mail</p>

                        @include('display_message')

                        <!-- OTP Status -->
                        <div id="otp-status" style="margin-bottom:1.5rem;">
                            <div id="otp-sending">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="animation:spin 1s linear infinite;flex-shrink:0;">
                                    <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                                </svg>
                                Envoi du code en cours…
                            </div>
                            <div id="otp-sent">
                                <strong>✓</strong> Code envoyé à <span id="otp-email-display">votre e-mail</span>
                            </div>
                            <div id="otp-error">
                                <span id="otp-error-msg">Erreur d'envoi.</span>
                            </div>
                        </div>

                        <form id="formAuthentication" class="mb-3" method="POST" action="{{ route('code_submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="code" class="form-label">Code de vérification</label>

                                <div class="otp-container">
                                    <input type="text" class="otp-input" maxlength="1" data-index="0" inputmode="numeric">
                                    <input type="text" class="otp-input" maxlength="1" data-index="1" inputmode="numeric">
                                    <input type="text" class="otp-input" maxlength="1" data-index="2" inputmode="numeric">
                                    <input type="text" class="otp-input" maxlength="1" data-index="3" inputmode="numeric">
                                    <input type="text" class="otp-input" maxlength="1" data-index="4" inputmode="numeric">
                                    <input type="text" class="otp-input" maxlength="1" data-index="5" inputmode="numeric">
                                </div>

                                <input type="text" class="form-control" id="code" name="code"
                                       maxlength="6" inputmode="numeric" pattern="^\d+$"
                                       title="Seuls les chiffres sont autorisés" />
                            </div>

                            <button class="btn btn-primary" type="submit" id="submitBtn">
                                Valider le code
                            </button>
                        </form>

                        <div class="resend-container">
                            <p class="resend-hint">Vous n'avez pas reçu le code ?</p>
                            <a href="#" class="resend-link" id="resendLink">Renvoyer le code</a>
                            <div class="timer" id="timer" style="display:none;">
                                Renvoi disponible dans <span id="countdown">60</span>s
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('js_file')

    <script>
        const otpInputs     = document.querySelectorAll('.otp-input');
        const hiddenInput   = document.getElementById('code');
        const submitBtn     = document.getElementById('submitBtn');
        const form          = document.getElementById('formAuthentication');
        const resendLink    = document.getElementById('resendLink');
        const timerDiv      = document.getElementById('timer');
        const countdownSpan = document.getElementById('countdown');

        // ── OTP inputs navigation ────────────────────────────────────
        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '');
                this.classList.toggle('filled', !!this.value);
                updateHiddenInput();
                if (this.value && index < otpInputs.length - 1) otpInputs[index + 1].focus();
                if (index === otpInputs.length - 1 && isFormComplete()) submitBtn.focus();
            });

            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    otpInputs[index - 1].value = '';
                    otpInputs[index - 1].classList.remove('filled');
                    otpInputs[index - 1].focus();
                    updateHiddenInput();
                }
                if (e.key === 'ArrowLeft'  && index > 0)                    otpInputs[index - 1].focus();
                if (e.key === 'ArrowRight' && index < otpInputs.length - 1) otpInputs[index + 1].focus();
            });

            input.addEventListener('paste', function (e) {
                e.preventDefault();
                const numbers = e.clipboardData.getData('text').replace(/\D/g, '').substring(0, 6);
                numbers.split('').forEach((n, i) => {
                    if (otpInputs[i]) { otpInputs[i].value = n; otpInputs[i].classList.add('filled'); }
                });
                updateHiddenInput();
                const next = numbers.length < otpInputs.length ? otpInputs[numbers.length] : submitBtn;
                next.focus();
            });
        });

        function updateHiddenInput() {
            const code = Array.from(otpInputs).map(i => i.value).join('');
            hiddenInput.value = code;
            submitBtn.disabled = code.length !== 6;
        }

        function isFormComplete() {
            return Array.from(otpInputs).every(i => i.value.length === 1);
        }

        form.addEventListener('submit', function (e) {
            if (!isFormComplete()) { e.preventDefault(); otpInputs[0].focus(); return; }
            submitBtn.classList.add('btn-loading');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Vérification…';
        });

        // ── Envoi OTP via AJAX ────────────────────────────────────────
        const OTP_URL = "{{ route('send_login_otp') }}";
        const CSRF    = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                      || "{{ csrf_token() }}";

        function showOtpSending() {
            document.getElementById('otp-sending').style.display = 'flex';
            document.getElementById('otp-sent').style.display    = 'none';
            document.getElementById('otp-error').style.display   = 'none';
        }
        function showOtpSent(email) {
            document.getElementById('otp-sending').style.display = 'none';
            document.getElementById('otp-sent').style.display    = 'block';
            document.getElementById('otp-error').style.display   = 'none';
            if (email) document.getElementById('otp-email-display').textContent = email;
        }
        function showOtpError(msg) {
            document.getElementById('otp-sending').style.display = 'none';
            document.getElementById('otp-sent').style.display    = 'none';
            document.getElementById('otp-error').style.display   = 'block';
            document.getElementById('otp-error-msg').textContent = msg;
        }

        async function sendOtp() {
            showOtpSending();
            try {
                const res  = await fetch(OTP_URL, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                });
                const data = await res.json();
                if (data.status) {
                    showOtpSent(data.message.replace('Code envoyé à ', ''));
                    startResendCooldown(60);
                } else {
                    showOtpError(data.message);
                    if (data.remaining) startResendCooldown(data.remaining);
                }
            } catch (e) {
                showOtpError('Erreur réseau. Veuillez réessayer.');
            }
        }

        // ── Compte à rebours "Renvoyer" ───────────────────────────────
        let countdownTimer = null;

        function startResendCooldown(seconds) {
            resendLink.style.pointerEvents = 'none';
            resendLink.style.opacity       = '0.35';
            timerDiv.style.display         = 'block';
            countdownSpan.textContent      = seconds;
            clearInterval(countdownTimer);
            countdownTimer = setInterval(() => {
                seconds--;
                countdownSpan.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(countdownTimer);
                    timerDiv.style.display         = 'none';
                    resendLink.style.pointerEvents = 'auto';
                    resendLink.style.opacity       = '1';
                }
            }, 1000);
        }

        resendLink.addEventListener('click', function (e) {
            e.preventDefault();
            sendOtp();
        });

        // ── Déclenchement automatique au chargement ───────────────────
        window.addEventListener('load', function () {
            if (document.querySelector('.alert-danger')) {
                otpInputs.forEach(i => { i.value = ''; i.classList.remove('filled'); });
            }
            otpInputs[0].focus();
            sendOtp();
        });
    </script>
</body>

</html>
