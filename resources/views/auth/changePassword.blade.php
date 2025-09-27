<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Immo | Changer mot de passe</title>

    <meta name="description" content="Changer votre mot de passe pour sécuriser votre compte Immo" />
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
            content: '🔑';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.1;
            }

            50% {
                transform: scale(1.2);
                opacity: 0.2;
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
            position: relative;
            text-align: center;
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

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(5deg);
            }

            75% {
                transform: rotate(-5deg);
            }
        }

        .mb-2 {
            color: rgb(229, 233, 239);
            font-size: 1.1rem;
            margin-bottom: 2rem !important;
            text-align: center;
        }

        .form-label {
            color: #4a5568;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            text-align: left;
        }

        /* Form input styling */
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            color: #2d3748;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .input-group {
            position: relative;
        }

        .input-group-merge .form-control {
            border-right: 0;
        }

        .input-group-text {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-left: 0;
            color: #4a5568;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .input-group:focus-within .input-group-text {
            border-color: #667eea;
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
            margin-top: 1rem;
            width: 100%;
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

        .invalid-feedback {
            color: #f56565;
            font-size: 0.8rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }

        /* Password strength indicator */
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 0.5rem;
            background: #e2e8f0;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            width: 0;
            transition: all 0.3s ease;
            border-radius: 3px;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .card-body {
                padding: 2rem 1.5rem;
            }

            .shape {
                opacity: 0.05;
            }

            h4::after {
                display: none;
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
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
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
                <!-- Password Change Card -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="logo-container">
                            @include('logo')
                        </div>

                        @include('display_message')

                        <h4 class="mb-2">Changer votre mot de passe 🔑</h4>

                        <form id="formAuthentication" class="mb-3" action="{{ route('password_submit') }}" method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" placeholder="Entrer votre email" autofocus />
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="Ancien_mot_de_passe">Ancien mot de passe</label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="Ancien_mot_de_passe" class="form-control @error('Ancien_mot_de_passe') is-invalid @enderror" name="Ancien_mot_de_passe"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="Ancien_mot_de_passe" required/>
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    @error('Ancien_mot_de_passe')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 form-password-toggle">
                                <label class="form-label" for="Nouveau_mot_de_passe">Nouveau mot de passe</label>
                                <div class="input-group input-group-merge">
                                    <input
                                        type="password"
                                        id="Nouveau_mot_de_passe"
                                        class="form-control @error('Nouveau_mot_de_passe') is-invalid @enderror"
                                        name="Nouveau_mot_de_passe"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="Nouveau_mot_de_passe"
                                    />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    @error('Nouveau_mot_de_passe')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="password-strength mt-2">
                                    <div class="password-strength-bar" id="password-strength-bar"></div>
                                </div>
                                <small class="form-text text-muted" id="password-strength-text"></small>
                            </div>

                            <button class="btn btn-primary d-grid w-100" id="form_submit1" type="submit" name="Confirmer1">Modifier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- / Content -->
    @include('js_file')

    <script>
        // Password visibility toggle
        document.querySelectorAll('.input-group-text').forEach(icon => {
            icon.addEventListener('click', function() {
                const passwordInput = this.previousElementSibling;
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle icon
                this.querySelector('i').classList.toggle('bx-hide');
                this.querySelector('i').classList.toggle('bx-show');
            });
        });

        // Password strength indicator
        const passwordInput = document.getElementById('Nouveau_mot_de_passe');
        const strengthBar = document.getElementById('password-strength-bar');
        const strengthText = document.getElementById('password-strength-text');

        if (passwordInput && strengthBar && strengthText) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let text = '';

                // Check password strength
                if (password.length > 0) {
                    if (password.length < 6) {
                        strength = 20;
                        text = 'Très faible';
                        strengthBar.style.backgroundColor = '#e53e3e';
                    } else if (password.length < 8) {
                        strength = 40;
                        text = 'Faible';
                        strengthBar.style.backgroundColor = '#ed8936';
                    } else if (password.length < 10) {
                        strength = 60;
                        text = 'Moyen';
                        strengthBar.style.backgroundColor = '#ecc94b';
                    } else if (/[A-Z]/.test(password) && /[0-9]/.test(password) && /[^A-Za-z0-9]/.test(password)) {
                        strength = 100;
                        text = 'Très fort';
                        strengthBar.style.backgroundColor = '#38a169';
                    } else {
                        strength = 80;
                        text = 'Fort';
                        strengthBar.style.backgroundColor = '#48bb78';
                    }
                }

                strengthBar.style.width = strength + '%';
                strengthText.textContent = text;
                strengthText.style.color = strengthBar.style.backgroundColor;
            });
        }

        // Form submission with loading state
        const form = document.getElementById('formAuthentication');
        const submitBtn = document.getElementById('form_submit1');

        if (form && submitBtn) {
            form.addEventListener('submit', function(e) {
                submitBtn.classList.add('btn-loading');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Modification en cours...';
            });
        }
    </script>
</body>

</html>