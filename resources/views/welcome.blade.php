<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Lokativ - Plateforme complète de gestion immobilière. Automatisez vos tâches, optimisez vos revenus et gérez votre patrimoine en toute simplicité.">
    <meta name="keywords" content="immobilier, gestion locative, propriété, loyers, patrimoine, investissement">
    
    <title>Lokativ - Votre partenaire de gestion immobilière</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e40af">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Lokativ">
    <link rel="apple-touch-icon" href="/logo/LOGO.jpg">
    
    <!-- Chargement optimisé des ressources -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- Schema.org structured data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "Lokativ",
      "applicationCategory": "BusinessApplication",
      "operatingSystem": "Web Browser",
      "description": "Plateforme complète de gestion immobilière",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "EUR"
      }
    }
    </script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                        secondary: '#1e3a8a',
                        accent: '#3b82f6',
                        success: '#10b981',
                        warning: '#f59e0b'
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'glow': 'glow 2s ease-in-out infinite alternate',
                        'slide-up': 'slideUp 0.8s ease-out forwards',
                        'fade-in': 'fadeIn 1s ease-out forwards',
                        'slide-down': 'slideDown 0.3s ease-out forwards'
                    }
                }
            }
        }
    </script>
    
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        @keyframes glow {
            from { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
            to { box-shadow: 0 0 30px rgba(59, 130, 246, 0.8); }
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from { transform: translateY(-100%); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .gradient-bg {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }

        .glass-effect {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        @media (max-width: 767px) {
            .glass-effect {
                background: rgba(255, 255, 255, 0.25);
                border: 2px solid rgba(255, 255, 255, 0.5);
            }
        }

        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        #hero-canvas {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
            width: 100%;
            height: 100%;
        }

        /* Header avec changement de couleur */
        .header-transparent {
            background: transparent;
            transition: all 0.3s ease;
        }

        .header-scrolled {
            background: white !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header-scrolled .nav-link {
            color: #374151 !important;
        }

        .header-scrolled .nav-link:hover {
            color: #1e40af !important;
        }

        .header-scrolled .logo-text {
            background: linear-gradient(to right, #1e40af, #3b82f6) !important;
            -webkit-background-clip: text !important;
            background-clip: text !important;
            color: transparent !important;
            -webkit-text-fill-color: transparent;
        }

        .header-scrolled .hamburger span {
            background-color: #1e40af !important;
        }

        .header-scrolled #mobile-menu-btn {
            color: #1e40af !important;
        }

        /* Boutons header scrollé */
        .header-scrolled .header-btn-login {
            color: #1e40af !important;
            background: #eff6ff !important;
        }

        .header-scrolled .header-btn-register {
            color: #ffffff !important;
            background: #1e40af !important;
        }

        /* Mobile menu responsive */
        .mobile-menu-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 60;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .mobile-menu-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .mobile-menu-panel {
            position: fixed;
            top: 0;
            right: -100%;
            width: 280px;
            height: 100vh;
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            z-index: 70;
            padding: 2rem 1.5rem;
            transition: right 0.3s ease;
            overflow-y: auto;
        }

        .mobile-menu-panel.active {
            right: 0;
        }

        .mobile-menu-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 1.5rem;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .mobile-menu-close:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .mobile-menu-content {
            margin-top: 4rem;
        }

        .mobile-menu-content a {
            color: white;
            display: block;
            padding: 1rem 0;
            text-decoration: none;
            font-size: 1.1rem;
            font-weight: 500;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .mobile-menu-content a:hover {
            color: #fbbf24;
            padding-left: 0.5rem;
        }

        .mobile-menu-cta {
            margin-top: 2rem;
        }

        .mobile-menu-cta a {
            border-bottom: none !important;
            padding: 0.75rem 1.5rem !important;
            font-weight: 700 !important;
            font-size: 1rem !important;
        }

        .mobile-menu-cta a:first-child {
            color: #1e40af !important;
            background: white !important;
        }

        .mobile-menu-cta a:first-child:hover {
            color: #1e40af !important;
            background: #eff6ff !important;
        }

        .mobile-menu-cta a:last-child {
            color: white !important;
            background: #2563eb !important;
        }

        .mobile-menu-cta a:last-child:hover {
            background: #1d4ed8 !important;
        }

        /* Hamburger menu animation */
        .hamburger {
            display: flex;
            flex-direction: column;
            width: 24px;
            height: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .hamburger span {
            display: block;
            height: 3px;
            width: 100%;
            background-color: white;
            margin-bottom: 3px;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .hamburger span:last-child {
            margin-bottom: 0;
        }

        .hamburger.active span:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }

        /* Portfolio grid */
        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .portfolio-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            height: 250px;
        }

        .portfolio-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .portfolio-item:hover img {
            transform: scale(1.1);
        }

        .portfolio-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(30, 64, 175, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .portfolio-item:hover .portfolio-overlay {
            opacity: 1;
        }

        /* Pricing cards */
        .pricing-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            border-color: #3b82f6;
        }

        .pricing-card.featured {
            border-color: #3b82f6;
            position: relative;
        }

        .pricing-card.featured::before {
            content: "Populaire";
            position: absolute;
            top: -10px;
            right: 20px;
            background: #3b82f6;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        /* FAQ accordion */
        .faq-item {
            border-bottom: 1px solid #e5e7eb;
        }

        .faq-question {
            padding: 1.5rem 0;
            cursor: pointer;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
            padding-bottom: 1.5rem;
        }

        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Styles pour le formulaire d'inscription */
        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        
        .step {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 10px;
            font-weight: bold;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .step.active {
            background: #1e40af;
            color: white;
        }
        
        .step.completed {
            background: #10b981;
            color: white;
        }
        
        .step::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 100%;
            width: 20px;
            height: 2px;
            background: #e9ecef;
            z-index: -1;
        }
        
        .step:last-child::after {
            display: none;
        }
        
        .step-form {
            display: none;
            animation: fadeIn 0.5s ease;
        }
        
        .step-form.active {
            display: block;
        }
        
        .form-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }
        
        .plan-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .plan-card:hover {
            border-color: #3b82f6;
            transform: translateY(-5px);
        }
        
        .plan-card.selected {
            border-color: #1e40af;
            background: rgba(30, 64, 175, 0.05);
        }
        
        .plan-card.featured {
            border-color: #3b82f6;
            position: relative;
        }
        
        .plan-card.featured::before {
            content: "Populaire";
            position: absolute;
            top: -10px;
            right: 20px;
            background: #3b82f6;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .progress-bar {
            height: 5px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        
        .progress {
            height: 100%;
            background: #1e40af;
            width: 0%;
            transition: width 0.5s ease;
        }
        
        .password-strength {
            height: 5px;
            background: #e9ecef;
            border-radius: 10px;
            margin-top: 5px;
            overflow: hidden;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
        }
        
        .weak { background: #ef4444; width: 33%; }
        .medium { background: #f59e0b; width: 66%; }
        .strong { background: #10b981; width: 100%; }
        
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
        }
        
        .summary-item:last-child {
            border-bottom: none;
        }

        /* Améliorations d'accessibilité */
        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border: 0;
        }
        
        /* Mode sombre */
        @media (prefers-color-scheme: dark) {
            .dark-mode-auto {
                background-color: #1a202c;
                color: #e2e8f0;
            }
        }
        
        /* Optimisations pour les performances */
        .will-change-transform {
            will-change: transform;
        }

        /* Amélioration de la lisibilité mobile */
        @media (max-width: 767px) {
            .hero-content h1,
            .hero-content p {
                text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            }

            .hero-content .glass-effect {
                background: rgba(255, 255, 255, 0.25);
                border: 2px solid rgba(255, 255, 255, 0.5);
            }

            /* Meilleur contraste pour les boutons CTA sur mobile */
            .gradient-bg a,
            .gradient-bg button {
                text-shadow: none;
            }

            /* Boutons outline plus lisibles sur mobile */
            .pricing-card a {
                font-weight: 700;
                padding-top: 0.875rem;
                padding-bottom: 0.875rem;
            }
        }

        /* Styles pour les témoignages */
        .testimonial-card {
            transition: all 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="overflow-x-hidden font-sans text-gray-800">

    <!-- Navigation -->
    <nav id="main-header" class="fixed z-50 w-full header-transparent" aria-label="Navigation principale">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <a href="/" class="flex items-center space-x-2" aria-label="Lokativ - Retour à l'accueil">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full sm:w-10 sm:h-10 bg-gradient-to-r from-blue-600 to-blue-800">
                        <i class="text-sm text-white fas fa-home sm:text-base" aria-hidden="true"></i>
                    </div>
                    <span class="text-xl font-bold text-white logo-text sm:text-2xl">
                        Lokativ
                    </span>
                </a>

                <div class="items-center hidden space-x-8 md:flex">
                    <a href="#accueil" class="text-white transition-colors nav-link hover:text-blue-300">Accueil</a>
                    <a href="#fonctionnalites" class="text-white transition-colors nav-link hover:text-blue-300">Fonctionnalités</a>
                    <a href="#portfolio" class="text-white transition-colors nav-link hover:text-blue-300">Portfolio</a>
                    <a href="#tarifs" class="text-white transition-colors nav-link hover:text-blue-300">Tarifs</a>
                    <a href="#contact" class="text-white transition-colors nav-link hover:text-blue-300">Contact</a>

                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-blue-600 transition-colors bg-white rounded-full header-btn-login lg:px-6 hover:bg-blue-50 lg:text-base">
                        Se connecter
                    </a>
                    <a href="#compte" class="px-4 py-2 text-sm font-semibold text-white transition-colors bg-blue-600 rounded-full header-btn-register lg:px-6 hover:bg-blue-700 lg:text-base">
                        Créer un compte
                    </a>
                </div>

                <div class="relative md:hidden">
                    <button class="text-white" id="mobile-menu-btn" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="mobile-menu-panel">
                        <div class="hamburger" id="hamburger">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Overlay -->
    <div class="mobile-menu-overlay" id="mobile-menu-overlay"></div>

    <!-- Mobile Menu Panel -->
    <div class="mobile-menu-panel" id="mobile-menu-panel">
        <button class="mobile-menu-close" id="mobile-menu-close" aria-label="Fermer le menu">
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>

        <div class="mobile-menu-content">
            <a href="#accueil" class="mobile-menu-link">Accueil</a>
            <a href="#fonctionnalites" class="mobile-menu-link">Fonctionnalités</a>
            <a href="#portfolio" class="mobile-menu-link">Portfolio</a>
            <a href="#tarifs" class="mobile-menu-link">Tarifs</a>
            <a href="#contact" class="mobile-menu-link">Contact</a>

            <div class="mobile-menu-cta">
                <a href="{{ route('login') }}" class="block w-full px-6 py-3 mb-3 font-bold text-center text-blue-600 transition-colors bg-white rounded-full hover:bg-blue-50">
                    Se connecter
                </a>
                <a href="#compte" class="block w-full px-6 py-3 font-bold text-center text-white transition-colors bg-blue-600 rounded-full hover:bg-blue-700">
                    Créer un compte
                </a>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <section id="accueil" class="relative flex items-center min-h-screen overflow-hidden gradient-bg">
        <canvas id="hero-canvas" aria-hidden="true"></canvas>

        <div class="relative z-10 px-4 py-20 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid items-center gap-8 lg:grid-cols-2 lg:gap-12 hero-grid">
                <div class="text-white animate-slide-up hero-content">
                    <h1 class="mb-4 text-3xl font-bold leading-tight sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl lg:mb-6">
                        Gérez vos biens immobiliers
                        <span class="block text-transparent bg-gradient-to-r from-green-400 to-blue-500 bg-clip-text sm:inline">
                            en toute simplicité
                        </span>
                    </h1>
                    <p class="mb-6 text-base leading-relaxed text-blue-100 sm:text-lg md:text-xl lg:text-2xl lg:mb-8">
                        Une plateforme complète pour gérer l'ensemble de votre patrimoine immobilier. Automatisez vos tâches, optimisez vos revenus et prenez les bonnes décisions.
                    </p>

                    <div class="flex flex-col gap-4 mb-8 sm:flex-row lg:mb-12">
                        <a href="#compte"
                            class="px-6 py-3 text-base font-bold text-center text-white transition-all bg-green-500 rounded-full lg:px-8 lg:py-4 lg:text-lg hover:bg-green-600 hover-lift animate-glow">
                            <i class="mr-2 fas fa-rocket" aria-hidden="true"></i>
                            Démarrer gratuitement
                        </a>
                        <button id="btn-voir-demo"
                            class="px-6 py-3 text-base font-bold text-white transition-all border-2 border-white rounded-full lg:px-8 lg:py-4 lg:text-lg hover:bg-white hover:text-blue-600" style="background: rgba(255,255,255,0.2);">
                            <i class="mr-2 fas fa-play-circle" aria-hidden="true"></i>
                            Voir la démo
                        </button>
                    </div>

                    <div class="flex flex-col space-y-2 text-sm text-blue-100 sm:flex-row sm:items-center sm:space-x-4 lg:space-x-8 sm:space-y-0 sm:text-base">
                        <div class="flex items-center">
                            <i class="mr-2 text-green-400 fas fa-shield-alt" aria-hidden="true"></i>
                            <span>Données sécurisées</span>
                        </div>
                        <div class="flex items-center">
                            <i class="mr-2 text-yellow-400 fas fa-clock" aria-hidden="true"></i>
                            <span>Configuration en 5min</span>
                        </div>
                        <div class="flex items-center">
                            <i class="mr-2 text-orange-400 fas fa-star" aria-hidden="true"></i>
                            <span>97% de satisfaction</span>
                        </div>
                    </div>
                </div>

                <div class="relative animate-float hero-form">
                    <div class="max-w-md p-4 mx-auto glass-effect rounded-2xl lg:rounded-3xl sm:p-6 lg:p-8">
                        <h3 class="mb-4 text-xl font-bold text-white lg:text-2xl lg:mb-6">Démo interactive</h3>
                        <div class="space-y-4">
                            <div class="flex items-center p-3 bg-white/10 rounded-xl">
                                <i class="mr-3 text-2xl text-green-400 fas fa-chart-line" aria-hidden="true"></i>
                                <div>
                                    <h4 class="font-semibold text-white">Tableau de bord complet</h4>
                                    <p class="text-sm text-blue-100">Vue d'ensemble de votre patrimoine</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-white/10 rounded-xl">
                                <i class="mr-3 text-2xl text-blue-400 fas fa-file-invoice-dollar" aria-hidden="true"></i>
                                <div>
                                    <h4 class="font-semibold text-white">Gestion des loyers</h4>
                                    <p class="text-sm text-blue-100">Automatisation des paiements</p>
                                </div>
                            </div>
                            <div class="flex items-center p-3 bg-white/10 rounded-xl">
                                <i class="mr-3 text-2xl text-purple-400 fas fa-tools" aria-hidden="true"></i>
                                <div>
                                    <h4 class="font-semibold text-white">Suivi des travaux</h4>
                                    <p class="text-sm text-blue-100">Planning et budget intégrés</p>
                                </div>
                            </div>
                        </div>
                        <button id="btn-essayer-demo"
                            class="block w-full py-3 mt-4 text-sm font-bold text-center text-white transition-all bg-gradient-to-r from-green-400 to-blue-500 lg:py-4 rounded-xl hover:from-green-500 hover:to-blue-600 lg:text-base">
                            <i class="mr-2 fas fa-play" aria-hidden="true"></i>
                            Voir la démo
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fonctionnalités Section -->
    <section id="fonctionnalites" class="py-12 sm:py-16 lg:py-20 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-12 text-center lg:mb-16">
                <h2 class="mb-4 text-2xl font-bold text-gray-800 sm:text-3xl md:text-4xl lg:text-5xl lg:mb-6">
                    Des fonctionnalités <span class="text-blue-600">puissantes</span>
                </h2>
                <p class="max-w-3xl mx-auto text-base text-gray-600 sm:text-lg lg:text-xl">
                    Découvrez tous les outils dont vous avez besoin pour gérer efficacement votre patrimoine immobilier
                </p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 lg:gap-8">
                <!-- Gestion des propriétés -->
                <div class="p-6 bg-white border border-gray-100 shadow-lg hover-lift rounded-2xl lg:p-8">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-xl bg-blue-50">
                        <i class="text-3xl text-blue-600 fas fa-building"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-800">Gestion des propriétés</h3>
                    <p class="text-gray-600">Centralisez toutes vos propriétés en un seul endroit. Suivez les détails, les documents et l'historique de chaque bien.</p>
                </div>

                <!-- Gestion des locataires -->
                <div class="p-6 bg-white border border-gray-100 shadow-lg hover-lift rounded-2xl lg:p-8">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-xl bg-green-50">
                        <i class="text-3xl text-green-600 fas fa-users"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-800">Gestion des locataires</h3>
                    <p class="text-gray-600">Gérez vos locataires efficacement : contrats, paiements, communications et historique complet.</p>
                </div>

                <!-- Facturation automatique -->
                <div class="p-6 bg-white border border-gray-100 shadow-lg hover-lift rounded-2xl lg:p-8">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-xl bg-purple-50">
                        <i class="text-3xl text-purple-600 fas fa-file-invoice-dollar"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-800">Facturation automatique</h3>
                    <p class="text-gray-600">Générez automatiquement vos factures de loyer et suivez les paiements en temps réel.</p>
                </div>

                <!-- Tableau de bord -->
                <div class="p-6 bg-white border border-gray-100 shadow-lg hover-lift rounded-2xl lg:p-8">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-xl bg-orange-50">
                        <i class="text-3xl text-orange-600 fas fa-chart-pie"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-800">Tableau de bord analytique</h3>
                    <p class="text-gray-600">Visualisez vos performances avec des graphiques clairs : revenus, taux d'occupation, rentabilité.</p>
                </div>

                <!-- Rappels et notifications -->
                <div class="p-6 bg-white border border-gray-100 shadow-lg hover-lift rounded-2xl lg:p-8">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-xl bg-red-50">
                        <i class="text-3xl text-red-600 fas fa-bell"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-800">Rappels intelligents</h3>
                    <p class="text-gray-600">Ne manquez plus aucune échéance grâce aux notifications automatiques pour loyers et contrats.</p>
                </div>

                <!-- Documents et contrats -->
                <div class="p-6 bg-white border border-gray-100 shadow-lg hover-lift rounded-2xl lg:p-8">
                    <div class="flex items-center justify-center w-16 h-16 mb-6 rounded-xl bg-teal-50">
                        <i class="text-3xl text-teal-600 fas fa-file-contract"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-800">Documents & Contrats</h3>
                    <p class="text-gray-600">Générez des contrats de bail personnalisés et stockez tous vos documents en toute sécurité.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Comment ça marche -->
    <section class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-12 text-center lg:mb-16">
                <h2 class="mb-4 text-2xl font-bold text-gray-800 sm:text-3xl md:text-4xl lg:text-5xl lg:mb-6">
                    Comment ça <span class="text-blue-600">marche ?</span>
                </h2>
                <p class="max-w-3xl mx-auto text-base text-gray-600 sm:text-lg lg:text-xl">
                    Démarrez en quelques étapes simples et gérez votre patrimoine immobilier comme un pro
                </p>
            </div>

            <div class="grid gap-8 md:grid-cols-4">
                <!-- Étape 1 -->
                <div class="relative text-center">
                    <div class="flex items-center justify-center w-20 h-20 mx-auto mb-6 text-3xl font-bold text-white rounded-full bg-gradient-to-r from-blue-600 to-blue-800">
                        1
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-800">Créez votre compte</h3>
                    <p class="text-gray-600">Inscrivez-vous gratuitement en moins de 2 minutes</p>
                    <div class="hidden md:block absolute top-10 left-[60%] w-[80%] h-0.5 bg-blue-200"></div>
                </div>

                <!-- Étape 2 -->
                <div class="relative text-center">
                    <div class="flex items-center justify-center w-20 h-20 mx-auto mb-6 text-3xl font-bold text-white rounded-full bg-gradient-to-r from-blue-600 to-blue-800">
                        2
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-800">Ajoutez vos biens</h3>
                    <p class="text-gray-600">Renseignez vos propriétés et leurs caractéristiques</p>
                    <div class="hidden md:block absolute top-10 left-[60%] w-[80%] h-0.5 bg-blue-200"></div>
                </div>

                <!-- Étape 3 -->
                <div class="relative text-center">
                    <div class="flex items-center justify-center w-20 h-20 mx-auto mb-6 text-3xl font-bold text-white rounded-full bg-gradient-to-r from-blue-600 to-blue-800">
                        3
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-800">Gérez vos locataires</h3>
                    <p class="text-gray-600">Ajoutez vos locataires et créez leurs contrats</p>
                    <div class="hidden md:block absolute top-10 left-[60%] w-[80%] h-0.5 bg-blue-200"></div>
                </div>

                <!-- Étape 4 -->
                <div class="text-center">
                    <div class="flex items-center justify-center w-20 h-20 mx-auto mb-6 text-3xl font-bold text-white rounded-full bg-gradient-to-r from-green-500 to-green-600">
                        <i class="fas fa-check"></i>
                    </div>
                    <h3 class="mb-3 text-xl font-bold text-gray-800">Automatisez tout</h3>
                    <p class="text-gray-600">Laissez Lokativ gérer les factures et rappels</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Statistiques -->
    <section class="py-12 sm:py-16 lg:py-20 gradient-bg">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid gap-8 text-center text-white sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <div class="mb-2 text-4xl font-bold lg:text-5xl">500+</div>
                    <p class="text-blue-100">Utilisateurs actifs</p>
                </div>
                <div>
                    <div class="mb-2 text-4xl font-bold lg:text-5xl">2,500+</div>
                    <p class="text-blue-100">Propriétés gérées</p>
                </div>
                <div>
                    <div class="mb-2 text-4xl font-bold lg:text-5xl">15M+</div>
                    <p class="text-blue-100">XOF de loyers traités</p>
                </div>
                <div>
                    <div class="mb-2 text-4xl font-bold lg:text-5xl">97%</div>
                    <p class="text-blue-100">Taux de satisfaction</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Partenaires / Confiance -->
    <section class="py-12 bg-white sm:py-16">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-8 text-center">
                <p class="text-lg text-gray-500">Ils nous font confiance</p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-8 grayscale opacity-60 lg:gap-16">
                <div class="flex items-center justify-center w-32 h-16">
                    <div class="flex items-center space-x-2">
                        <i class="text-3xl text-gray-400 fas fa-building"></i>
                        <span class="text-xl font-bold text-gray-400">Agence A</span>
                    </div>
                </div>
                <div class="flex items-center justify-center w-32 h-16">
                    <div class="flex items-center space-x-2">
                        <i class="text-3xl text-gray-400 fas fa-home"></i>
                        <span class="text-xl font-bold text-gray-400">Immo B</span>
                    </div>
                </div>
                <div class="flex items-center justify-center w-32 h-16">
                    <div class="flex items-center space-x-2">
                        <i class="text-3xl text-gray-400 fas fa-city"></i>
                        <span class="text-xl font-bold text-gray-400">Urban C</span>
                    </div>
                </div>
                <div class="flex items-center justify-center w-32 h-16">
                    <div class="flex items-center space-x-2">
                        <i class="text-3xl text-gray-400 fas fa-landmark"></i>
                        <span class="text-xl font-bold text-gray-400">Groupe D</span>
                    </div>
                </div>
                <div class="flex items-center justify-center w-32 h-16">
                    <div class="flex items-center space-x-2">
                        <i class="text-3xl text-gray-400 fas fa-hotel"></i>
                        <span class="text-xl font-bold text-gray-400">Estate E</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Biens Immobiliers / Publicités -->
    @if(isset($publicites) && $publicites->count() > 0)
    <section id="biens" class="py-12 sm:py-16 lg:py-20 bg-gradient-to-b from-blue-50 to-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-12 text-center lg:mb-16">
                <span class="inline-block px-4 py-1 mb-4 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full">
                    Nos offres du moment
                </span>
                <h2 class="mb-4 text-2xl font-bold text-gray-800 sm:text-3xl md:text-4xl lg:text-5xl">
                    Biens <span class="text-blue-600">Immobiliers</span> Disponibles
                </h2>
                <p class="max-w-3xl mx-auto text-base text-gray-600 sm:text-lg">
                    Découvrez nos dernières offres immobilières disponibles
                </p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($publicites as $pub)
                <div class="overflow-hidden transition-all duration-300 bg-white shadow-lg group rounded-2xl hover:shadow-2xl hover:-translate-y-1">
                    <!-- Image / Carrousel -->
                    <div class="relative overflow-hidden" style="height: 220px;">
                        @php $pubImages = $pub->images; @endphp
                        @if(count($pubImages) > 1)
                            <!-- Carrousel -->
                            <div class="pub-carousel" data-pub-id="{{ $pub->id }}" style="height:100%; position:relative;">
                                @foreach($pubImages as $idx => $img)
                                <div class="pub-slide" style="position:absolute; inset:0; transition:opacity 0.5s; opacity:{{ $idx === 0 ? '1' : '0' }};">
                                    <img src="{{ asset('storage/'.$img) }}" alt="{{ $pub->localisation }}"
                                         class="object-cover w-full h-full">
                                </div>
                                @endforeach
                                <!-- Indicateurs -->
                                <div class="absolute flex space-x-1 transform -translate-x-1/2 bottom-3 left-1/2">
                                    @foreach($pubImages as $idx => $img)
                                    <button class="pub-dot w-2 h-2 rounded-full transition-all {{ $idx === 0 ? 'bg-white scale-125' : 'bg-white/50' }}"
                                            onclick="goToSlide({{ $pub->id }}, {{ $idx }})"></button>
                                    @endforeach
                                </div>
                                <!-- Flèches -->
                                <button class="absolute p-1 text-white transform -translate-y-1/2 rounded-full left-2 top-1/2 bg-black/30 hover:bg-black/50" onclick="prevSlide({{ $pub->id }})">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button class="absolute p-1 text-white transform -translate-y-1/2 rounded-full right-2 top-1/2 bg-black/30 hover:bg-black/50" onclick="nextSlide({{ $pub->id }})">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        @elseif(count($pubImages) === 1)
                            <img src="{{ asset('storage/'.$pubImages[0]) }}" alt="{{ $pub->localisation }}"
                                 class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110">
                        @endif

                        <!-- Badge Nouveau -->
                        @if($pub->published_at && $pub->published_at->diffInDays(now()) < 3)
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 text-xs font-bold text-white bg-green-500 rounded-full shadow-lg">
                                Nouveau
                            </span>
                        </div>
                        @endif

                        <!-- Badge Prix -->
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 text-sm font-bold text-white rounded-full shadow-lg bg-blue-600/90">
                                {{ number_format($pub->price, 0, ',', '.') }} XOF
                            </span>
                        </div>
                    </div>

                    <!-- Contenu -->
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-lg font-bold text-gray-800 line-clamp-1">{{ $pub->localisation }}</h3>
                        </div>

                        <p class="mb-4 text-sm text-gray-500 line-clamp-2">{{ $pub->description }}</p>

                        <div class="flex items-center gap-4 mb-4 text-sm text-gray-600">
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                <span>{{ $pub->Superficie }} m²</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span>{{ $pub->telephone }}</span>
                            </div>
                        </div>

                        <a href="tel:{{ preg_replace('/\s+/', '', $pub->telephone) }}" class="flex items-center justify-center w-full gap-2 px-4 py-2.5 text-sm font-semibold text-white transition-all bg-blue-600 rounded-xl hover:bg-blue-700 hover:shadow-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            Contacter
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <script>
    // Carrousel simple pour les publicités
    (function() {
        var carousels = {};

        document.querySelectorAll('.pub-carousel').forEach(function(el) {
            var pubId = el.dataset.pubId;
            var slides = el.querySelectorAll('.pub-slide');
            var dots = el.querySelectorAll('.pub-dot');
            carousels[pubId] = { slides: slides, dots: dots, current: 0, total: slides.length };

            // Auto-slide
            setInterval(function() {
                nextSlide(parseInt(pubId));
            }, 4000);
        });

        window.goToSlide = function(pubId, index) {
            var c = carousels[pubId];
            if (!c) return;
            c.slides.forEach(function(s, i) { s.style.opacity = i === index ? '1' : '0'; });
            c.dots.forEach(function(d, i) {
                d.className = d.className.replace(/bg-white\/50|bg-white scale-125/g, '').trim();
                d.classList.add(i === index ? 'bg-white' : 'bg-white/50');
                if (i === index) d.classList.add('scale-125');
            });
            c.current = index;
        };

        window.nextSlide = function(pubId) {
            var c = carousels[pubId];
            if (!c) return;
            goToSlide(pubId, (c.current + 1) % c.total);
        };

        window.prevSlide = function(pubId) {
            var c = carousels[pubId];
            if (!c) return;
            goToSlide(pubId, (c.current - 1 + c.total) % c.total);
        };
    })();
    </script>
    @endif

    <!-- Portfolio Section -->
    <section id="portfolio" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-12 text-center lg:mb-16">
                <h2 class="mb-4 text-2xl font-bold text-gray-800 sm:text-3xl md:text-4xl lg:text-5xl lg:mb-6">
                    Aperçu de la <span class="text-blue-600">plateforme</span>
                </h2>
                <p class="max-w-3xl mx-auto text-base text-gray-600 sm:text-lg lg:text-xl">
                    Découvrez les interfaces intuitives et puissantes d'Lokativ
                </p>
            </div>

            <div class="portfolio-grid">
                <!-- Dashboard -->
                <div class="portfolio-item">
                    <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-blue-500 to-blue-700">
                        <div class="p-8 text-center text-white">
                            <i class="mb-4 text-5xl fas fa-tachometer-alt"></i>
                            <h4 class="text-xl font-bold">Tableau de bord</h4>
                            <p class="text-blue-100">Vue d'ensemble complète</p>
                        </div>
                    </div>
                    <div class="portfolio-overlay">
                        <div class="text-center text-white">
                            <i class="mb-2 text-3xl fas fa-eye"></i>
                            <p class="font-semibold">Voir le détail</p>
                        </div>
                    </div>
                </div>

                <!-- Gestion propriétés -->
                <div class="portfolio-item">
                    <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-green-500 to-green-700">
                        <div class="p-8 text-center text-white">
                            <i class="mb-4 text-5xl fas fa-home"></i>
                            <h4 class="text-xl font-bold">Gestion des biens</h4>
                            <p class="text-green-100">Maisons et chambres</p>
                        </div>
                    </div>
                    <div class="portfolio-overlay">
                        <div class="text-center text-white">
                            <i class="mb-2 text-3xl fas fa-eye"></i>
                            <p class="font-semibold">Voir le détail</p>
                        </div>
                    </div>
                </div>

                <!-- Facturation -->
                <div class="portfolio-item">
                    <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-purple-500 to-purple-700">
                        <div class="p-8 text-center text-white">
                            <i class="mb-4 text-5xl fas fa-receipt"></i>
                            <h4 class="text-xl font-bold">Facturation</h4>
                            <p class="text-purple-100">Factures automatiques</p>
                        </div>
                    </div>
                    <div class="portfolio-overlay">
                        <div class="text-center text-white">
                            <i class="mb-2 text-3xl fas fa-eye"></i>
                            <p class="font-semibold">Voir le détail</p>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="portfolio-item">
                    <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-orange-500 to-orange-700">
                        <div class="p-8 text-center text-white">
                            <i class="mb-4 text-5xl fas fa-chart-bar"></i>
                            <h4 class="text-xl font-bold">Statistiques</h4>
                            <p class="text-orange-100">Analyses détaillées</p>
                        </div>
                    </div>
                    <div class="portfolio-overlay">
                        <div class="text-center text-white">
                            <i class="mb-2 text-3xl fas fa-eye"></i>
                            <p class="font-semibold">Voir le détail</p>
                        </div>
                    </div>
                </div>

                <!-- Locataires -->
                <div class="portfolio-item">
                    <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-teal-500 to-teal-700">
                        <div class="p-8 text-center text-white">
                            <i class="mb-4 text-5xl fas fa-user-friends"></i>
                            <h4 class="text-xl font-bold">Locataires</h4>
                            <p class="text-teal-100">Gestion centralisée</p>
                        </div>
                    </div>
                    <div class="portfolio-overlay">
                        <div class="text-center text-white">
                            <i class="mb-2 text-3xl fas fa-eye"></i>
                            <p class="font-semibold">Voir le détail</p>
                        </div>
                    </div>
                </div>

                <!-- Rapports PDF -->
                <div class="portfolio-item">
                    <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-red-500 to-red-700">
                        <div class="p-8 text-center text-white">
                            <i class="mb-4 text-5xl fas fa-file-pdf"></i>
                            <h4 class="text-xl font-bold">Rapports PDF</h4>
                            <p class="text-red-100">Export automatique</p>
                        </div>
                    </div>
                    <div class="portfolio-overlay">
                        <div class="text-center text-white">
                            <i class="mb-2 text-3xl fas fa-eye"></i>
                            <p class="font-semibold">Voir le détail</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Témoignages Section -->
    <section class="py-12 sm:py-16 lg:py-20 bg-gray-50">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-12 text-center lg:mb-16">
                <h2 class="mb-4 text-2xl font-bold text-gray-800 sm:text-3xl md:text-4xl lg:text-5xl lg:mb-6">
                    Ce que disent nos <span class="text-blue-600">clients</span>
                </h2>
                <p class="max-w-3xl mx-auto text-base text-gray-600 sm:text-lg lg:text-xl">
                    Découvrez les retours de nos utilisateurs satisfaits
                </p>
            </div>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                <!-- Témoignage 1 -->
                <div class="p-6 bg-white shadow-lg testimonial-card rounded-2xl lg:p-8">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="mb-6 italic text-gray-600">"Lokativ a révolutionné ma façon de gérer mes 5 appartements. Je gagne un temps précieux chaque mois sur la facturation et le suivi des paiements."</p>
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 mr-4 font-bold text-white bg-blue-600 rounded-full">
                            KA
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Kokou Amavi</h4>
                            <p class="text-sm text-gray-500">Propriétaire à Cotonou</p>
                        </div>
                    </div>
                </div>

                <!-- Témoignage 2 -->
                <div class="p-6 bg-white shadow-lg testimonial-card rounded-2xl lg:p-8">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="mb-6 italic text-gray-600">"En tant qu'agence immobilière, nous gérons plus de 50 biens. Lokativ nous permet de tout centraliser et d'offrir un meilleur service à nos clients."</p>
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 mr-4 font-bold text-white bg-green-600 rounded-full">
                            AS
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Agence Sunrise</h4>
                            <p class="text-sm text-gray-500">Agence immobilière, Porto-Novo</p>
                        </div>
                    </div>
                </div>

                <!-- Témoignage 3 -->
                <div class="p-6 bg-white shadow-lg testimonial-card rounded-2xl lg:p-8">
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                    <p class="mb-6 italic text-gray-600">"Les rappels automatiques m'ont permis de réduire les retards de paiement de 80%. Le tableau de bord est très intuitif et facile à utiliser."</p>
                    <div class="flex items-center">
                        <div class="flex items-center justify-center w-12 h-12 mr-4 font-bold text-white bg-purple-600 rounded-full">
                            MD
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800">Marie Dossou</h4>
                            <p class="text-sm text-gray-500">Investisseur immobilier</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Tarifs Section -->
    <section id="tarifs" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-12 text-center lg:mb-16">
                <h2 class="mb-4 text-2xl font-bold text-gray-800 sm:text-3xl md:text-4xl lg:text-5xl lg:mb-6">
                    Des tarifs <span class="text-blue-600">adaptés</span> à vos besoins
                </h2>
                <p class="max-w-3xl mx-auto text-base text-gray-600 sm:text-lg lg:text-xl">
                    Choisissez le plan qui correspond à la taille de votre patrimoine
                </p>
            </div>

            @php
                $colClass = count($plansActifs) <= 2 ? 'md:grid-cols-2' :
                            (count($plansActifs) === 3 ? 'md:grid-cols-3' : 'md:grid-cols-2 lg:grid-cols-4');
            @endphp
            <div class="grid gap-8 {{ $colClass }}">
                @foreach ($plansActifs as $plan)
                @php
                    $isFree     = floatval($plan->prix_annuel) == 0;
                    $isFeatured = $plan->code === 'standard';
                    $isFirst    = $loop->first;
                    $isLast     = $loop->last;

                    // Couleurs selon position / type
                    if ($isFree) {
                        $borderClass  = 'border-2 border-green-500';
                        $priceColor   = 'text-green-600';
                        $badgeCls     = 'text-green-600 bg-green-100';
                        $badgeLabel   = '14 jours';
                        $btnClass     = 'bg-green-500 text-white hover:bg-green-600';
                        $btnLabel     = 'Essayer gratuitement';
                    } elseif ($isFeatured) {
                        $borderClass  = 'border-2 border-blue-600 pricing-card featured';
                        $priceColor   = 'text-blue-600';
                        $badgeCls     = 'text-white bg-blue-600';
                        $badgeLabel   = 'Populaire';
                        $btnClass     = 'bg-blue-600 text-white hover:bg-blue-700';
                        $btnLabel     = 'Choisir ce plan';
                    } elseif ($isLast && !$isFree) {
                        $borderClass  = 'border border-purple-200';
                        $priceColor   = 'text-purple-700';
                        $badgeCls     = 'text-purple-600 bg-purple-100';
                        $badgeLabel   = 'Pro';
                        $btnClass     = 'border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white';
                        $btnLabel     = 'Nous contacter';
                    } else {
                        $borderClass  = 'border border-gray-200';
                        $priceColor   = 'text-gray-800';
                        $badgeCls     = 'text-blue-600 bg-blue-100';
                        $badgeLabel   = 'Débutant';
                        $btnClass     = 'border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white';
                        $btnLabel     = 'Commencer';
                    }

                    // Libellé maisons
                    if ($plan->max_maisons === null || $plan->max_maisons === 0) {
                        $maisonLabel = '<strong class="mx-1">Maisons illimitées</strong>';
                    } else {
                        $maisonLabel = 'Jusqu\'à <strong class="mx-1">' . $plan->max_maisons . ' maisons</strong>';
                    }

                    // Libellé annexes
                    if ($plan->max_annexes === null) {
                        $annexeLabel = 'Annexes illimitées';
                        $annexeOk    = true;
                    } elseif ($plan->max_annexes > 0) {
                        $annexeLabel = 'Jusqu\'à <strong class="mx-1">' . $plan->max_annexes . ' annexe(s)</strong>';
                        $annexeOk    = true;
                    } else {
                        $annexeLabel = 'Création d\'annexes';
                        $annexeOk    = false;
                    }
                @endphp

                <div class="p-6 bg-white {{ $borderClass }} pricing-card rounded-2xl lg:p-8"
                     style="position:relative;">

                    {{-- Badge gratuit --}}
                    @if($isFree)
                        <span style="position:absolute;top:-10px;left:50%;transform:translateX(-50%);background:#22c55e;color:#fff;padding:2px 14px;border-radius:20px;font-size:12px;font-weight:600;">Gratuit</span>
                    @endif

                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800">{{ $plan->nom }}</h3>
                        <span class="px-3 py-1 text-xs font-semibold {{ $badgeCls }} rounded-full">{{ $badgeLabel }}</span>
                    </div>

                    @if($plan->description)
                        <p class="mb-6 text-gray-500">{{ $plan->description }}</p>
                    @endif

                    {{-- Prix --}}
                    <div class="mb-6">
                        @if($isFree)
                            <span class="text-4xl font-bold {{ $priceColor }}">Gratuit</span>
                        @else
                            <span class="text-4xl font-bold {{ $priceColor }}">{{ number_format($plan->prix_annuel, 0, ',', ' ') }}</span>
                            <span class="text-gray-500"> XOF/an</span>
                        @endif
                    </div>

                    {{-- Fonctionnalités --}}
                    <ul class="mb-8 space-y-3">
                        {{-- Maisons --}}
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            {!! $maisonLabel !!}
                        </li>

                        {{-- Annexes --}}
                        <li class="flex items-center {{ $annexeOk ? 'text-gray-600' : 'text-gray-400' }}">
                            <i class="mr-3 {{ $annexeOk ? 'text-green-500' : '' }} fas fa-{{ $annexeOk ? 'check' : 'times' }}"></i>
                            {!! $annexeLabel !!}
                        </li>

                        {{-- Fonctionnalités fixes --}}
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Tableau de bord complet
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Gestion des locataires
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Facturation automatique
                        </li>

                        {{-- Envoi email --}}
                        @if($plan->max_envois_email === null)
                            <li class="flex items-center text-gray-600">
                                <i class="mr-3 text-green-500 fas fa-check"></i>
                                <i class="fas fa-envelope mr-1 text-blue-400"></i>
                                <strong class="mx-1">Emails illimités</strong> / mois
                            </li>
                        @elseif($plan->max_envois_email > 0)
                            <li class="flex items-center text-gray-600">
                                <i class="mr-3 text-green-500 fas fa-check"></i>
                                <i class="fas fa-envelope mr-1 text-blue-400"></i>
                                <strong class="mx-1">{{ $plan->max_envois_email }}</strong> email(s) / mois
                            </li>
                        @else
                            <li class="flex items-center text-gray-400">
                                <i class="mr-3 fas fa-times"></i>
                                <i class="fas fa-envelope mr-1"></i> Envoi par email
                            </li>
                        @endif

                        {{-- Envoi WhatsApp --}}
                        @if($plan->max_envois_whatsapp === null)
                            <li class="flex items-center text-gray-600">
                                <i class="mr-3 text-green-500 fas fa-check"></i>
                                <i class="fab fa-whatsapp mr-1 text-green-500"></i>
                                <strong class="mx-1">WhatsApp illimités</strong> / mois
                            </li>
                        @elseif($plan->max_envois_whatsapp > 0)
                            <li class="flex items-center text-gray-600">
                                <i class="mr-3 text-green-500 fas fa-check"></i>
                                <i class="fab fa-whatsapp mr-1 text-green-500"></i>
                                <strong class="mx-1">{{ $plan->max_envois_whatsapp }}</strong> WhatsApp / mois
                            </li>
                        @else
                            <li class="flex items-center text-gray-400">
                                <i class="mr-3 fas fa-times"></i>
                                <i class="fab fa-whatsapp mr-1"></i> Envoi WhatsApp
                            </li>
                        @endif

                        {{-- Publicités --}}
                        @if($plan->max_publicites === null)
                            <li class="flex items-center text-gray-600">
                                <i class="mr-3 text-green-500 fas fa-check"></i>
                                <i class="fas fa-bullhorn mr-1 text-yellow-500"></i>
                                <strong class="mx-1">Publicités illimitées</strong>
                            </li>
                        @elseif($plan->max_publicites > 0)
                            <li class="flex items-center text-gray-600">
                                <i class="mr-3 text-green-500 fas fa-check"></i>
                                <i class="fas fa-bullhorn mr-1 text-yellow-500"></i>
                                <strong class="mx-1">{{ $plan->max_publicites }}</strong> publicité(s) max
                            </li>
                        @else
                            <li class="flex items-center text-gray-400">
                                <i class="mr-3 fas fa-times"></i>
                                <i class="fas fa-bullhorn mr-1"></i> Publicités
                            </li>
                        @endif
                    </ul>

                    <a href="#compte"
                       class="block w-full py-3 text-center transition-colors rounded-full {{ $btnClass }}">
                        {{ $btnLabel }}
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Note informative -->
            <div class="p-6 mt-12 text-center bg-blue-50 rounded-2xl">
                <p class="text-gray-600">
                    <i class="mr-2 text-blue-600 fas fa-info-circle"></i>
                    Tous les plans incluent une <strong>période d'essai gratuite</strong>. Pas de frais cachés.
                </p>
            </div>
        </div>
    </section>

    <!-- Créer un compte Section - Formulaire d'inscription complet -->
    <section id="compte" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="px-4 mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="mb-12 text-center lg:mb-16">
                <h2 class="mb-4 text-2xl font-bold text-gray-800 sm:text-3xl md:text-4xl lg:text-5xl lg:mb-6">
                    Créer votre compte <span class="text-blue-600">Lokativ</span>
                </h2>
                <p class="max-w-3xl mx-auto text-base text-gray-600 sm:text-lg lg:text-xl">
                    Rejoignez notre plateforme en quelques étapes simples. Commencez à gérer votre patrimoine immobilier en quelques minutes.
                </p>
            </div>

            <div class="p-6 bg-white border border-gray-200 shadow-lg rounded-2xl lg:p-8">
                <!-- Indicateur de progression -->
                <div class="progress-bar">
                    <div class="progress" id="progress-bar"></div>
                </div>
                
                <!-- Étapes -->
                <div class="step-indicator">
                    <div class="step active" id="step-1">1</div>
                    <div class="step" id="step-2">2</div>
                    <div class="step" id="step-3">3</div>
                </div>
                
                <!-- Formulaire étape 1: Type de compte -->
                <div class="step-form active" id="step-form-1">
                    <h3 class="mb-4">Type de compte</h3>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <div class="plan-card" data-type="particulier">
                                <div class="text-center">
                                    <i class="fas fa-user fa-3x text-primary mb-3" aria-hidden="true"></i>
                                    <h4>Particulier</h4>
                                    <p class="text-muted">Idéal pour la gestion de votre patrimoine personnel</p>
                                    <ul class="text-start">
                                        <li>Jusqu'à 10 propriétés</li>
                                        <li>Tableau de bord personnalisé</li>
                                        <li>Support par email</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="plan-card" data-type="entreprise">
                                <div class="text-center">
                                    <i class="fas fa-building fa-3x text-primary mb-3" aria-hidden="true"></i>
                                    <h4>Entreprise</h4>
                                    <p class="text-muted">Parfait pour les professionnels de l'immobilier</p>
                                    <ul class="text-start">
                                        <li>Propriétés illimitées</li>
                                        <li>Comptes multiples</li>
                                        <li>Support prioritaire</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" id="type_compte" name="type_compte">
                    <div class="form-navigation">
                        <div></div>
                        <button type="button" class="btn btn-primary" onclick="nextStep(2)" disabled id="btn-next-1">
                            Suivant <i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Formulaire étape 2: Informations personnelles -->
                <div class="step-form" id="step-form-2">
                    <h3 class="mb-4">Informations personnelles</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                                <div class="invalid-feedback">Veuillez saisir votre nom.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="prenom" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                                <div class="invalid-feedback">Veuillez saisir votre prénom.</div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback">Veuillez saisir une adresse email valide.</div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="code_pays" class="form-label">Pays <span class="text-danger">*</span></label>
                                <select class="form-select" id="code_pays" name="code_pays" required>
                                    <option value="">Sélectionnez...</option>
                                    <option value="+229">Bénin (+229)</option>
                                    <option value="+33">France (+33)</option>
                                    <option value="+1">États-Unis (+1)</option>
                                </select>
                                <div class="invalid-feedback">Veuillez sélectionner un pays.</div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" required>
                                <div class="invalid-feedback">Veuillez saisir un numéro de téléphone valide.</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Section entreprise -->
                    <div id="entreprise-section" style="display: none;">
                        <h5 class="mt-4 mb-3">Informations de l'entreprise</h5>
                        <div class="mb-3">
                            <label for="designation" class="form-label">Nom de l'entreprise <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="designation" name="designation">
                            <div class="invalid-feedback">Veuillez saisir le nom de l'entreprise.</div>
                        </div>
                        <div class="mb-3">
                            <label for="adresse" class="form-label">Adresse <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="adresse" name="adresse">
                            <div class="invalid-feedback">Veuillez saisir l'adresse de l'entreprise.</div>
                        </div>
                        <div class="mb-3">
                            <label for="email_entreprise" class="form-label">Email de l'entreprise <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email_entreprise" name="email_entreprise">
                            <div class="invalid-feedback">Veuillez saisir l'email de l'entreprise.</div>
                        </div>
                    </div>
                    
                    <div class="form-navigation">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(1)">
                            <i class="fas fa-arrow-left me-2" aria-hidden="true"></i> Précédent
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(3)" id="btn-next-2">
                            Suivant <i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Formulaire étape 3: Plan d'abonnement et finalisation -->
                <div class="step-form" id="step-form-3">
                    <h3 class="mb-4">Choisissez votre plan</h3>
                    <div id="plans-container">
                        <!-- Les plans seront chargés dynamiquement -->
                    </div>

                    <div class="mb-3 mt-4 form-check">
                        <input type="checkbox" class="form-check-input" id="conditions" required>
                        <label class="form-check-label" for="conditions">
                            J'accepte les <a href="{{ route('legal.cgu') }}" target="_blank" class="text-primary">conditions générales d'utilisation</a> et la <a href="{{ route('legal.confidentialite') }}" target="_blank" class="text-primary">politique de confidentialité</a>.
                        </label>
                        <div class="invalid-feedback">Vous devez accepter les conditions pour continuer.</div>
                    </div>

                    <div class="form-navigation">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                            <i class="fas fa-arrow-left me-2" aria-hidden="true"></i> Précédent
                        </button>
                        <button type="button" class="btn btn-success" id="btn-submit" onclick="submitForm()" disabled>
                            <i class="fas fa-check me-2" aria-hidden="true"></i>
                            <span id="btn-submit-label">Créer mon compte</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-12 sm:py-16 lg:py-20 bg-gray-50">
        <div class="px-4 mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="mb-12 text-center lg:mb-16">
                <h2 class="mb-4 text-2xl font-bold text-gray-800 sm:text-3xl md:text-4xl lg:text-5xl lg:mb-6">
                    Questions <span class="text-blue-600">fréquentes</span>
                </h2>
                <p class="max-w-3xl mx-auto text-base text-gray-600 sm:text-lg lg:text-xl">
                    Trouvez rapidement les réponses à vos questions
                </p>
            </div>

            <div class="space-y-4">
                <!-- FAQ 1 -->
                <div class="faq-item active bg-white rounded-xl shadow-sm">
                    <div class="faq-question flex items-center justify-between p-6 cursor-pointer">
                        <h3 class="text-lg font-semibold text-gray-800">Comment puis-je commencer à utiliser Lokativ ?</h3>
                        <i class="fas fa-chevron-down text-blue-600 faq-icon transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer px-6">
                        <p class="text-gray-600 pb-6">C'est très simple ! Créez un compte gratuit en cliquant sur "Créer un compte", renseignez vos informations et vous pourrez immédiatement ajouter vos propriétés et commencer à gérer votre patrimoine. Aucune carte bancaire n'est requise pour l'offre gratuite.</p>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="faq-item bg-white rounded-xl shadow-sm">
                    <div class="faq-question flex items-center justify-between p-6 cursor-pointer">
                        <h3 class="text-lg font-semibold text-gray-800">Mes données sont-elles sécurisées ?</h3>
                        <i class="fas fa-chevron-down text-blue-600 faq-icon transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer px-6">
                        <p class="text-gray-600 pb-6">Absolument. Nous utilisons un chiffrement SSL de bout en bout et nos serveurs sont hébergés dans des centres de données sécurisés. Vos données sont sauvegardées quotidiennement et ne sont jamais partagées avec des tiers.</p>
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="faq-item bg-white rounded-xl shadow-sm">
                    <div class="faq-question flex items-center justify-between p-6 cursor-pointer">
                        <h3 class="text-lg font-semibold text-gray-800">Puis-je changer de plan à tout moment ?</h3>
                        <i class="fas fa-chevron-down text-blue-600 faq-icon transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer px-6">
                        <p class="text-gray-600 pb-6">Oui, vous pouvez passer à un plan supérieur à tout moment. La différence de prix sera calculée au prorata. Vous pouvez également rétrograder à la fin de votre période d'abonnement.</p>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="faq-item bg-white rounded-xl shadow-sm">
                    <div class="faq-question flex items-center justify-between p-6 cursor-pointer">
                        <h3 class="text-lg font-semibold text-gray-800">Comment fonctionne la facturation automatique ?</h3>
                        <i class="fas fa-chevron-down text-blue-600 faq-icon transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer px-6">
                        <p class="text-gray-600 pb-6">Une fois vos locataires et les montants de loyer configurés, Lokativ génère automatiquement les factures à chaque échéance. Vous pouvez personnaliser les modèles de factures et configurer des rappels automatiques en cas de retard de paiement.</p>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="faq-item bg-white rounded-xl shadow-sm">
                    <div class="faq-question flex items-center justify-between p-6 cursor-pointer">
                        <h3 class="text-lg font-semibold text-gray-800">Y a-t-il une application mobile ?</h3>
                        <i class="fas fa-chevron-down text-blue-600 faq-icon transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer px-6">
                        <p class="text-gray-600 pb-6">Lokativ est entièrement responsive et fonctionne parfaitement sur tous les appareils mobiles via votre navigateur. Une application native iOS et Android est en cours de développement et sera bientôt disponible.</p>
                    </div>
                </div>

                <!-- FAQ 6 -->
                <div class="faq-item bg-white rounded-xl shadow-sm">
                    <div class="faq-question flex items-center justify-between p-6 cursor-pointer">
                        <h3 class="text-lg font-semibold text-gray-800">Proposez-vous une assistance technique ?</h3>
                        <i class="fas fa-chevron-down text-blue-600 faq-icon transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer px-6">
                        <p class="text-gray-600 pb-6">Oui, notre équipe de support est disponible pour vous aider. Les utilisateurs gratuits bénéficient d'un support par email, tandis que les plans payants incluent un support prioritaire avec des temps de réponse garantis.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-12 text-center lg:mb-16">
                <h2 class="mb-4 text-2xl font-bold text-gray-800 sm:text-3xl md:text-4xl lg:text-5xl lg:mb-6">
                    Contactez-<span class="text-blue-600">nous</span>
                </h2>
                <p class="max-w-3xl mx-auto text-base text-gray-600 sm:text-lg lg:text-xl">
                    Une question ? Notre équipe est là pour vous aider
                </p>
            </div>

            <div class="grid gap-12 lg:grid-cols-2">
                <!-- Informations de contact -->
                <div>
                    <h3 class="mb-6 text-2xl font-bold text-gray-800">Nos coordonnées</h3>

                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 rounded-xl bg-blue-50">
                                <i class="text-xl text-blue-600 fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Adresse</h4>
                                <p class="text-gray-600">Cotonou, Bénin<br>Quartier Akpakpa</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 rounded-xl bg-green-50">
                                <i class="text-xl text-green-600 fas fa-phone"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Téléphone</h4>
                                <p class="text-gray-600">+229 XX XX XX XX</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 rounded-xl bg-purple-50">
                                <i class="text-xl text-purple-600 fas fa-envelope"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Email</h4>
                                <p class="text-gray-600">contact@lokativ.com</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex items-center justify-center w-12 h-12 mr-4 rounded-xl bg-orange-50">
                                <i class="text-xl text-orange-600 fas fa-clock"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">Horaires</h4>
                                <p class="text-gray-600">Lun - Ven : 8h00 - 18h00<br>Sam : 9h00 - 13h00</p>
                            </div>
                        </div>
                    </div>

                    <!-- Réseaux sociaux -->
                    <div class="mt-8">
                        <h4 class="mb-4 font-semibold text-gray-800">Suivez-nous</h4>
                        <div class="flex space-x-4">
                            <a href="#" class="flex items-center justify-center w-10 h-10 text-blue-600 transition-colors bg-blue-100 rounded-full hover:bg-blue-600 hover:text-white">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="flex items-center justify-center w-10 h-10 text-blue-400 transition-colors bg-blue-100 rounded-full hover:bg-blue-400 hover:text-white">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="flex items-center justify-center w-10 h-10 text-pink-600 transition-colors bg-pink-100 rounded-full hover:bg-pink-600 hover:text-white">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="flex items-center justify-center w-10 h-10 text-blue-700 transition-colors bg-blue-100 rounded-full hover:bg-blue-700 hover:text-white">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Formulaire de contact -->
                <div class="p-6 bg-gray-50 rounded-2xl lg:p-8">
                    <h3 class="mb-6 text-2xl font-bold text-gray-800">Envoyez-nous un message</h3>

                    <form id="contact-form">
                        <div class="grid gap-4 mb-4 sm:grid-cols-2">
                            <div>
                                <label for="contact_nom" class="block mb-2 text-sm font-medium text-gray-700">Nom</label>
                                <input type="text" id="contact_nom" name="contact_nom" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Votre nom" required>
                            </div>
                            <div>
                                <label for="contact_prenom" class="block mb-2 text-sm font-medium text-gray-700">Prénom</label>
                                <input type="text" id="contact_prenom" name="contact_prenom" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Votre prénom" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="contact_email" class="block mb-2 text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="contact_email" name="contact_email" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="votre@email.com" required>
                        </div>

                        <div class="mb-4">
                            <label for="contact_sujet" class="block mb-2 text-sm font-medium text-gray-700">Sujet</label>
                            <select id="contact_sujet" name="contact_sujet" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="">Sélectionnez un sujet</option>
                                <option value="information">Demande d'information</option>
                                <option value="demo">Demande de démonstration</option>
                                <option value="support">Support technique</option>
                                <option value="partenariat">Partenariat</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label for="contact_message" class="block mb-2 text-sm font-medium text-gray-700">Message</label>
                            <textarea id="contact_message" name="contact_message" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Votre message..." required></textarea>
                        </div>

                        <button type="submit" class="w-full px-6 py-4 font-bold text-white transition-colors bg-blue-600 rounded-xl hover:bg-blue-700">
                            <i class="mr-2 fas fa-paper-plane"></i>
                            Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Section CTA finale -->
    <section class="py-12 sm:py-16 lg:py-20 gradient-bg">
        <div class="px-4 mx-auto text-center max-w-4xl sm:px-6 lg:px-8">
            <h2 class="mb-6 text-2xl font-bold text-white sm:text-3xl md:text-4xl lg:text-5xl">
                Prêt à simplifier votre gestion immobilière ?
            </h2>
            <p class="mb-8 text-lg text-blue-100 lg:text-xl">
                Rejoignez des centaines de propriétaires qui font confiance à Lokativ
            </p>
            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                <a href="#compte" class="px-8 py-4 text-lg font-bold text-blue-600 transition-all bg-white rounded-full hover:bg-blue-50 hover-lift">
                    <i class="mr-2 fas fa-rocket"></i>
                    Créer un compte gratuit
                </a>
                <a href="#contact" class="px-8 py-4 text-lg font-bold text-white transition-all border-2 border-white rounded-full hover:bg-white hover:text-blue-600" style="background: rgba(255,255,255,0.15);">
                    <i class="mr-2 fas fa-phone"></i>
                    Nous contacter
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 text-white bg-gray-900 lg:py-16" role="contentinfo">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid gap-8 mb-12 md:grid-cols-2 lg:grid-cols-4">
                <!-- Logo et description -->
                <div class="lg:col-span-1">
                    <a href="/" class="flex items-center mb-4 space-x-2">
                        <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-blue-600 to-blue-800">
                            <i class="text-white fas fa-home"></i>
                        </div>
                        <span class="text-2xl font-bold text-white">Lokativ</span>
                    </a>
                    <p class="mb-6 text-gray-400">
                        La solution complète pour gérer votre patrimoine immobilier en toute simplicité.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 transition-colors hover:text-white">
                            <i class="text-xl fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 transition-colors hover:text-white">
                            <i class="text-xl fab fa-twitter"></i>
                        </a>
                        <a href="#" class="text-gray-400 transition-colors hover:text-white">
                            <i class="text-xl fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 transition-colors hover:text-white">
                            <i class="text-xl fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>

                <!-- Liens rapides -->
                <div>
                    <h4 class="mb-4 text-lg font-bold text-white">Liens rapides</h4>
                    <ul class="space-y-3">
                        <li><a href="#accueil" class="text-gray-400 transition-colors hover:text-white">Accueil</a></li>
                        <li><a href="#fonctionnalites" class="text-gray-400 transition-colors hover:text-white">Fonctionnalités</a></li>
                        <li><a href="#tarifs" class="text-gray-400 transition-colors hover:text-white">Tarifs</a></li>
                        <li><a href="#contact" class="text-gray-400 transition-colors hover:text-white">Contact</a></li>
                        <li><a href="{{ route('login') }}" class="text-gray-400 transition-colors hover:text-white">Connexion</a></li>
                    </ul>
                </div>

                <!-- Services -->
                <div>
                    <h4 class="mb-4 text-lg font-bold text-white">Services</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Gestion locative</a></li>
                        <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Facturation</a></li>
                        <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Statistiques</a></li>
                        <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Contrats</a></li>
                        <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Support</a></li>
                    </ul>
                </div>

                <!-- Légal -->
                <div>
                    <h4 class="mb-4 text-lg font-bold text-white">Légal</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ route('legal.cgu') }}" class="text-gray-400 transition-colors hover:text-white">Conditions d'utilisation</a></li>
                        <li><a href="{{ route('legal.confidentialite') }}" class="text-gray-400 transition-colors hover:text-white">Politique de confidentialité</a></li>
                        <li><a href="{{ route('legal.mentions') }}" class="text-gray-400 transition-colors hover:text-white">Mentions légales</a></li>
                        <li><a href="{{ route('legal.cookies') }}" class="text-gray-400 transition-colors hover:text-white">Cookies</a></li>
                    </ul>
                </div>
            </div>

            <!-- Barre de séparation -->
            <div class="pt-8 border-t border-gray-800">
                <div class="flex flex-col items-center justify-between md:flex-row">
                    <p class="mb-4 text-gray-400 md:mb-0">
                        &copy; {{ date('Y') }} Lokativ. Tous droits réservés.
                    </p>
                    <div class="flex items-center space-x-4">
                        <span class="text-gray-400">Paiements sécurisés</span>
                        <div class="flex space-x-2">
                            <i class="text-2xl text-gray-400 fab fa-cc-visa"></i>
                            <i class="text-2xl text-gray-400 fab fa-cc-mastercard"></i>
                            <i class="text-2xl text-gray-400 fas fa-mobile-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bouton retour en haut -->
    <button id="back-to-top" class="fixed z-50 hidden p-3 text-white transition-all bg-blue-600 rounded-full shadow-lg bottom-8 right-8 hover:bg-blue-700" aria-label="Retour en haut">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(($paymentProvider ?? 'none') === 'kkiapay')
    <!-- KKiaPay SDK -->
    <script src="https://cdn.kkiapay.me/k.js"></script>
    @elseif(($paymentProvider ?? 'none') === 'fedapay')
    <!-- FedaPay SDK -->
    <script src="https://cdn.fedapay.com/checkout.js?v=1.1.7"></script>
    @endif
    
    <script>
        // ===== Configuration prestataire de paiement (injectée depuis le serveur) =====
        const PAYMENT_ENABLED    = @json($paymentEnabled ?? false);
        const PAYMENT_PROVIDER   = @json($paymentProvider ?? 'none');
        const PAYMENT_PUBLIC_KEY = @json($paymentPublicKey ?? '');
        const PAYMENT_SANDBOX    = @json($paymentSandbox ?? true);

        // ===== Données des plans d'abonnement (depuis la base de données) =====
        const _plansData = @json($plansJs);
        const plans = {
            particulier: _plansData,
            entreprise:  _plansData,
        };
        
        let currentStep = 1;
        let selectedPlan = null;
        let accountType = '';
        let paymentAuthToken = null; // Token émis par preValidate(), requis avant tout paiement
        let createAccountLock = false; // Empêche les appels doubles à createAccount (listeners accumulés)
        
        // Initialisation
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion de la sélection du type de compte
            document.querySelectorAll('.plan-card[data-type]').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    
                    accountType = this.getAttribute('data-type');
                    document.getElementById('type_compte').value = accountType;
                    document.getElementById('btn-next-1').disabled = false;
                    
                    // Afficher/masquer la section entreprise
                    if (accountType === 'entreprise') {
                        document.getElementById('entreprise-section').style.display = 'block';
                        document.getElementById('designation').setAttribute('required', 'required');
                        document.getElementById('adresse').setAttribute('required', 'required');
                        document.getElementById('email_entreprise').setAttribute('required', 'required');
                    } else {
                        document.getElementById('entreprise-section').style.display = 'none';
                        document.getElementById('designation').removeAttribute('required');
                        document.getElementById('adresse').removeAttribute('required');
                        document.getElementById('email_entreprise').removeAttribute('required');
                    }
                });
            });
            
            // Validation en temps réel de l'email
            document.getElementById('email').addEventListener('blur', validateEmail);

            // Gestion du menu mobile
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
            const mobileMenuPanel = document.getElementById('mobile-menu-panel');
            const mobileMenuClose = document.getElementById('mobile-menu-close');
            const hamburger = document.getElementById('hamburger');

            function updateMenuAria(expanded) {
                mobileMenuBtn.setAttribute('aria-expanded', expanded);
                if (expanded === 'true') {
                    mobileMenuBtn.setAttribute('aria-label', 'Fermer le menu');
                } else {
                    mobileMenuBtn.setAttribute('aria-label', 'Ouvrir le menu');
                }
            }

            function openMobileMenu() {
                mobileMenuOverlay.classList.add('active');
                mobileMenuPanel.classList.add('active');
                hamburger.classList.add('active');
                document.body.style.overflow = 'hidden';
                updateMenuAria('true');
                
                // Focus trap pour l'accessibilité
                const firstLink = mobileMenuPanel.querySelector('a');
                if (firstLink) firstLink.focus();
            }

            function closeMobileMenu() {
                mobileMenuOverlay.classList.remove('active');
                mobileMenuPanel.classList.remove('active');
                hamburger.classList.remove('active');
                document.body.style.overflow = 'auto';
                updateMenuAria('false');
                mobileMenuBtn.focus();
            }

            if (mobileMenuBtn) {
                mobileMenuBtn.addEventListener('click', openMobileMenu);
            }

            if (mobileMenuClose) {
                mobileMenuClose.addEventListener('click', closeMobileMenu);
            }

            if (mobileMenuOverlay) {
                mobileMenuOverlay.addEventListener('click', closeMobileMenu);
            }

            // Fermer le menu avec la touche Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileMenu();
                }
            });

            // Gestion des FAQ
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                
                question.addEventListener('click', () => {
                    // Fermer toutes les autres FAQ
                    faqItems.forEach(otherItem => {
                        if (otherItem !== item) {
                            otherItem.classList.remove('active');
                        }
                    });
                    
                    // Ouvrir/fermer la FAQ cliquée
                    item.classList.toggle('active');
                });
            });

            // Animation Three.js pour le hero
            function initHeroAnimation() {
                const canvas = document.getElementById('hero-canvas');
                if (!canvas) return;
                
                const scene = new THREE.Scene();
                const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
                const renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true });
                
                renderer.setSize(window.innerWidth, window.innerHeight);
                
                // Créer des formes géométriques flottantes (maisons)
                const geometries = [
                    new THREE.BoxGeometry(1, 1, 1),
                    new THREE.ConeGeometry(0.8, 1.5, 4),
                    new THREE.CylinderGeometry(0.6, 0.6, 1, 6)
                ];
                
                const material = new THREE.MeshBasicMaterial({ 
                    color: 0x4F46E5, 
                    transparent: true, 
                    opacity: 0.6 
                });
                
                const objects = [];
                for (let i = 0; i < 30; i++) {
                    const geometry = geometries[Math.floor(Math.random() * geometries.length)];
                    const object = new THREE.Mesh(geometry, material);
                    object.position.set(
                        (Math.random() - 0.5) * 100,
                        (Math.random() - 0.5) * 100,
                        (Math.random() - 0.5) * 100
                    );
                    object.rotation.set(
                        Math.random() * Math.PI,
                        Math.random() * Math.PI,
                        Math.random() * Math.PI
                    );
                    object.scale.setScalar(Math.random() * 0.5 + 0.3);
                    scene.add(object);
                    objects.push(object);
                }
                
                camera.position.z = 30;
                
                function animate() {
                    requestAnimationFrame(animate);
                    
                    objects.forEach((object, index) => {
                        object.rotation.x += 0.01;
                        object.rotation.y += 0.02;
                        object.position.y += Math.sin(Date.now() * 0.001 + index) * 0.1;
                    });
                    
                    renderer.render(scene, camera);
                }
                
                animate();
                
                window.addEventListener('resize', () => {
                    camera.aspect = window.innerWidth / window.innerHeight;
                    camera.updateProjectionMatrix();
                    renderer.setSize(window.innerWidth, window.innerHeight);
                });
            }
            
            initHeroAnimation();

            // Smooth scrolling pour les liens d'ancrage
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Gestion du changement de couleur du header
            function handleHeaderScroll() {
                const header = document.getElementById('main-header');
                const scrollPosition = window.scrollY;
                const heroSection = document.getElementById('accueil');
                const heroHeight = heroSection.offsetHeight;
                
                if (scrollPosition > heroHeight - 100) {
                    header.classList.add('header-scrolled');
                    header.classList.remove('header-transparent');
                } else {
                    header.classList.remove('header-scrolled');
                    header.classList.add('header-transparent');
                }
            }
            
            // Écouter l'événement de scroll
            window.addEventListener('scroll', handleHeaderScroll);
            // Appeler une fois au chargement pour initialiser l'état
            handleHeaderScroll();

            // Bouton retour en haut
            const backToTopBtn = document.getElementById('back-to-top');

            window.addEventListener('scroll', function() {
                if (window.scrollY > 500) {
                    backToTopBtn.classList.remove('hidden');
                } else {
                    backToTopBtn.classList.add('hidden');
                }
            });

            if (backToTopBtn) {
                backToTopBtn.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }

            // Formulaire de contact
            const contactForm = document.getElementById('contact-form');
            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Simuler l'envoi du formulaire
                    Swal.fire({
                        title: 'Message envoyé !',
                        text: 'Nous vous répondrons dans les plus brefs délais.',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#1e40af'
                    });

                    // Réinitialiser le formulaire
                    contactForm.reset();
                });
            }
        });
        
        // Navigation entre les étapes
        function nextStep(step) {
            if (validateStep(currentStep)) {
                document.getElementById(`step-form-${currentStep}`).classList.remove('active');
                document.getElementById(`step-${currentStep}`).classList.remove('active');
                
                currentStep = step;
                
                document.getElementById(`step-form-${currentStep}`).classList.add('active');
                document.getElementById(`step-${currentStep}`).classList.add('active');
                
                // Mettre à jour la barre de progression
                document.getElementById('progress-bar').style.width = `${(currentStep-1) * 50}%`;

                // Charger les plans si on arrive à l'étape 3
                if (currentStep === 3) {
                    loadPlans();
                }

                // Empêcher le scroll vers le haut
                return false;
            }
            return false;
        }

        function prevStep(step) {
            document.getElementById(`step-form-${currentStep}`).classList.remove('active');
            document.getElementById(`step-${currentStep}`).classList.remove('active');

            currentStep = step;

            document.getElementById(`step-form-${currentStep}`).classList.add('active');
            document.getElementById(`step-${currentStep}`).classList.add('active');

            // Mettre à jour la barre de progression
            document.getElementById('progress-bar').style.width = `${(currentStep-1) * 50}%`;
            
            // Empêcher le scroll vers le haut
            return false;
        }
        
        // Validation des étapes
        function validateStep(step) {
            let isValid = true;
            
            switch(step) {
                case 1:
                    if (!accountType) {
                        Swal.fire('Erreur', 'Veuillez sélectionner un type de compte.', 'error');
                        isValid = false;
                    }
                    break;
                    
                case 2:
                    const requiredFields = ['nom', 'prenom', 'email', 'code_pays', 'telephone'];
                    if (accountType === 'entreprise') {
                        requiredFields.push('designation', 'adresse', 'email_entreprise');
                    }
                    
                    requiredFields.forEach(field => {
                        const element = document.getElementById(field);
                        if (!element.value.trim()) {
                            element.classList.add('is-invalid');
                            isValid = false;
                        } else {
                            element.classList.remove('is-invalid');
                        }
                    });
                    
                    if (!validateEmail()) {
                        isValid = false;
                    }
                    break;
                    
                case 3:
                    if (!selectedPlan) {
                        Swal.fire('Erreur', 'Veuillez sélectionner un plan d\'abonnement.', 'error');
                        isValid = false;
                    }
                    break;
            }
            
            return isValid;
        }
        
        // Validation de l'email
        function validateEmail() {
            const email = document.getElementById('email');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            if (!emailRegex.test(email.value)) {
                email.classList.add('is-invalid');
                return false;
            } else {
                email.classList.remove('is-invalid');
                return true;
            }
        }
        
        // Chargement des plans d'abonnement
        function loadPlans() {
            const container = document.getElementById('plans-container');
            const plansList = plans[accountType];
            
            container.innerHTML = '';
            
            plansList.forEach(plan => {
                const planElement = document.createElement('div');
                planElement.className = 'plan-card';
                planElement.setAttribute('data-plan', plan.id);
                
                const isPaidPlan    = plan.prix > 0;
                const providerLabel = PAYMENT_PROVIDER === 'fedapay' ? 'FedaPay' : 'KKiaPay';
                const paymentBadge = (isPaidPlan && PAYMENT_ENABLED)
                    ? `<span class="badge" style="background:#10b981;font-size:11px;">
                           <i class="fas fa-credit-card me-1"></i>Paiement ${providerLabel}
                       </span>`
                    : '';

                planElement.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1">${plan.nom} ${paymentBadge}</h5>
                            <p class="mb-1">Jusqu'à ${plan.proprietes} propriétés</p>
                        </div>
                        <div class="text-end">
                            <h4 class="text-primary mb-1">${plan.prix === 0 ? 'Gratuit' : plan.prix.toLocaleString() + ' XOF'}</h4>
                            <small class="text-muted">${plan.prix === 0 ? plan.periode : 'par ' + plan.periode}</small>
                        </div>
                    </div>
                    <ul class="mt-3 mb-0">
                        ${plan.features.map(feature => `<li>${feature}</li>`).join('')}
                    </ul>
                    ${isPaidPlan && PAYMENT_ENABLED
                        ? `<div class="mt-2 pt-2 border-top" style="font-size:12px;color:#065f46;">
                               <i class="fas fa-shield-alt me-1"></i>
                               Paiement sécurisé via ${providerLabel} — Mobile Money &amp; carte bancaire
                           </div>`
                        : ''}
                `;
                
                planElement.addEventListener('click', function() {
                    document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedPlan = plan;
                    document.getElementById('btn-submit').disabled = false;

                    // Mettre à jour le label du bouton selon le mode de paiement
                    const isPaid = plan.prix > 0;
                    const label  = document.getElementById('btn-submit-label');
                    if (isPaid && PAYMENT_ENABLED) {
                        label.textContent = 'Payer et créer mon compte';
                        document.getElementById('btn-submit').className = 'btn btn-primary';
                    } else {
                        label.textContent = 'Créer mon compte';
                        document.getElementById('btn-submit').className = 'btn btn-success';
                    }
                });
                
                container.appendChild(planElement);
            });
        }
        
        // ===== Soumission du formulaire =====
        // ===== Construction du formData partagé =====
        function buildFormData(transactionId = null) {
            const data = {
                type_compte: accountType === 'particulier' ? 'Particulier' : 'Entreprise',
                nom:         document.getElementById('nom').value,
                prenom:      document.getElementById('prenom').value,
                email:       document.getElementById('email').value,
                code_pays:   document.getElementById('code_pays').value,
                telephone:   document.getElementById('telephone').value,
                plan_code:   selectedPlan.id,
                _token:      document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            };
            if (transactionId) {
                data.transaction_id = transactionId;
            }
            // Inclure le token d'autorisation si disponible (plans payants)
            if (paymentAuthToken) {
                data.payment_auth_token = paymentAuthToken;
            }
            if (accountType === 'entreprise') {
                data.designation         = document.getElementById('designation').value;
                data.adresse             = document.getElementById('adresse').value;
                data.telepone_entreprise = document.getElementById('code_pays').value + document.getElementById('telephone').value;
                data.email_entreprise    = document.getElementById('email_entreprise').value;
            }
            return data;
        }

        async function submitForm() {
            if (!selectedPlan) {
                Swal.fire('Erreur', 'Veuillez sélectionner un plan d\'abonnement.', 'error');
                return;
            }
            if (!document.getElementById('conditions').checked) {
                document.getElementById('conditions').classList.add('is-invalid');
                Swal.fire('Erreur', 'Vous devez accepter les conditions générales.', 'error');
                return;
            }

            const submitBtn = document.getElementById('btn-submit');
            submitBtn.disabled = true;
            paymentAuthToken   = null;
            createAccountLock  = false;

            // ── ÉTAPE 1 : Validation serveur — bloque TOUT si échoue ────────────
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Vérification...';

            let validationPassed = false; // démarre à false, ne peut passer à true que par réponse serveur explicite
            let serverJson       = null;

            try {
                const fd   = buildFormData();
                const resp = await fetch("{{ route('pre_validate_inscription') }}", {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept':       'application/json',
                        'X-CSRF-TOKEN': fd._token,
                    },
                    body: JSON.stringify(fd),
                });

                try { serverJson = await resp.json(); } catch (_) { serverJson = null; }

            } catch (networkErr) {
                Swal.fire('Erreur réseau', 'Impossible de joindre le serveur. Vérifiez votre connexion.', 'error');
                resetSubmitBtn(submitBtn);
                return; // STOP
            }

            // Seul cas où on autorise la suite : réponse JSON avec status === true
            if (serverJson !== null && serverJson.status === true) {
                validationPassed = true;
                paymentAuthToken = serverJson.auth_token || null;
            }

            // Si validation échouée → afficher erreurs et STOPPER
            if (!validationPassed) {
                let firstErrorStep = null;
                if (serverJson && serverJson.errors) {
                    Object.keys(serverJson.errors).forEach(field => {
                        const input = document.getElementById(field);
                        if (input) {
                            input.classList.add('is-invalid');
                            const fb = input.nextElementSibling;
                            if (fb && fb.classList.contains('invalid-feedback')) {
                                const msgs = serverJson.errors[field];
                                fb.textContent = Array.isArray(msgs) ? msgs[0] : msgs;
                            }
                            if (!firstErrorStep) {
                                const stepEl = input.closest('.step-form');
                                if (stepEl) firstErrorStep = parseInt(stepEl.id.replace('step-form-', ''));
                            }
                        }
                    });
                }
                const errMsg = (serverJson && serverJson.message) || 'Veuillez vérifier les informations saisies.';
                Swal.fire({ title: 'Erreur', text: errMsg, icon: 'error', confirmButtonText: 'Corriger' }).then(() => {
                    if (firstErrorStep && firstErrorStep !== currentStep) {
                        document.getElementById(`step-form-${currentStep}`).classList.remove('active');
                        document.getElementById(`step-${currentStep}`).classList.remove('active');
                        currentStep = firstErrorStep;
                        document.getElementById(`step-form-${currentStep}`).classList.add('active');
                        document.getElementById(`step-${currentStep}`).classList.add('active');
                        document.getElementById('progress-bar').style.width = `${(currentStep - 1) * 50}%`;
                    }
                });
                resetSubmitBtn(submitBtn);
                return; // STOP — aucun paiement, aucun compte
            }
            // ────────────────────────────────────────────────────────────────────

            // ── ÉTAPE 2 : Paiement ou création directe (uniquement si validationPassed) ──
            const isPlanGratuit = selectedPlan.prix === 0;

            if (!isPlanGratuit && PAYMENT_ENABLED) {
                initiatePayment(submitBtn);
            } else {
                await createAccount(null, submitBtn);
            }
        }

        // ===== Ouverture du widget de paiement (KKiaPay ou FedaPay) =====
        function initiatePayment(submitBtn) {
            // Garde : le token doit avoir été émis par preValidate()
            if (!paymentAuthToken) {
                Swal.fire('Erreur', 'Session de validation expirée ou manquante. Veuillez recommencer.', 'error');
                resetSubmitBtn(submitBtn);
                return;
            }

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Ouverture du paiement...';

            if (PAYMENT_PROVIDER === 'kkiapay') {
                if (typeof openKkiapayWidget !== 'function') {
                    Swal.fire({
                        title: 'Service indisponible',
                        text: 'Le widget de paiement KKiaPay n\'a pas pu être chargé. Vérifiez votre connexion internet et réessayez.',
                        icon: 'error', confirmButtonText: 'OK', confirmButtonColor: '#1e40af',
                    });
                    resetSubmitBtn(submitBtn);
                    return;
                }

                // Flag : paiement finalisé (succès ou échec confirmé)
                let kkPaymentDone = false;
                let kkWidgetSeen  = false;
                let kkPollInterval = null;

                function kkCleanup() {
                    if (kkPollInterval) { clearInterval(kkPollInterval); kkPollInterval = null; }
                }

                function kkWidgetVisible() {
                    // Cherche le widget KKiaPay dans le DOM (iframe ou conteneur)
                    const el = document.querySelector('iframe[src*="kkiapay"]') ||
                               document.querySelector('[id*="kkiapay"]') ||
                               document.querySelector('[class*="kkiapay"]');
                    if (!el) return false;
                    // Vérifier qu'il est visible (pas caché)
                    const style = window.getComputedStyle(el);
                    return style.display !== 'none' && style.visibility !== 'hidden';
                }

                // Polling toutes les 300ms : détecte disparition du widget
                kkPollInterval = setInterval(function() {
                    const visible = kkWidgetVisible();
                    if (visible) {
                        kkWidgetSeen = true;
                    } else if (kkWidgetSeen && !kkPaymentDone) {
                        // Widget était ouvert, maintenant disparu sans paiement complété
                        kkCleanup();
                        resetSubmitBtn(submitBtn);
                    }
                }, 300);

                // Sécurité : arrêt du polling après 15 min
                setTimeout(kkCleanup, 900000);

                openKkiapayWidget({
                    amount:  selectedPlan.prix,
                    key:     PAYMENT_PUBLIC_KEY,
                    sandbox: PAYMENT_SANDBOX,
                    email:   document.getElementById('email').value,
                    name:    document.getElementById('prenom').value + ' ' + document.getElementById('nom').value,
                    phone:   document.getElementById('telephone').value,
                    data:    JSON.stringify({ plan_code: selectedPlan.id }),
                });

                addSuccessListener(async function(response) {
                    if (createAccountLock) return; // listener dupliqué — ignorer
                    createAccountLock = true;
                    kkPaymentDone = true;
                    kkCleanup();
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Création du compte...';
                    await createAccount(response.transactionId, submitBtn);
                });

                addFailedListener(function() {
                    kkPaymentDone = true;
                    kkCleanup();
                    Swal.fire({
                        title: 'Paiement échoué',
                        text: 'Le paiement KKiaPay n\'a pas abouti. Veuillez réessayer.',
                        icon: 'error', confirmButtonText: 'Réessayer', confirmButtonColor: '#1e40af',
                    });
                    resetSubmitBtn(submitBtn);
                });

                addCloseListener(function() {
                    kkCleanup();
                    if (!kkPaymentDone) {
                        resetSubmitBtn(submitBtn);
                    }
                });

            } else if (PAYMENT_PROVIDER === 'fedapay') {
                FedaPay.init({
                    public_key:  PAYMENT_PUBLIC_KEY,
                    transaction: {
                        amount:      selectedPlan.prix,
                        description: 'Abonnement Lokativ — ' + selectedPlan.nom,
                    },
                    customer: {
                        email:     document.getElementById('email').value,
                        firstname: document.getElementById('prenom').value,
                        lastname:  document.getElementById('nom').value,
                    },
                    onComplete: async function(resp) {
                        if (resp.reason === FedaPay.DIALOG_DISMISSED) {
                            resetSubmitBtn(submitBtn);
                            return;
                        }
                        var trans = resp.transaction;
                        if (trans && trans.status === 'approved') {
                            if (createAccountLock) return;
                            createAccountLock = true;
                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Création du compte...';
                            await createAccount(String(trans.id), submitBtn);
                        } else {
                            Swal.fire({
                                title: 'Paiement échoué',
                                text: 'Le paiement FedaPay n\'a pas été approuvé. Veuillez réessayer.',
                                icon: 'error', confirmButtonText: 'Réessayer', confirmButtonColor: '#1e40af',
                            });
                            resetSubmitBtn(submitBtn);
                        }
                    }
                }).open();
            }
        }

        function resetSubmitBtn(submitBtn) {
            createAccountLock = false;
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-check me-2"></i> Créer mon compte';
        }

        // ===== Création du compte (après paiement ou directement) =====
        async function createAccount(transactionId, submitBtn) {
            const formData = buildFormData(transactionId);

            try {
                const response = await fetch("{{ route('creation_compte') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept':       'application/json',
                        'X-CSRF-TOKEN': formData._token,
                    },
                    body: JSON.stringify(formData),
                });

                const data = await response.json();

                if (data.status === true) {
                    Swal.fire({
                        title:             'Compte créé !',
                        text:              data.message,
                        icon:              'success',
                        confirmButtonText: 'Se connecter',
                        confirmButtonColor: '#1e40af',
                    }).then(() => {
                        window.location.href = "{{ route('login') }}";
                    });
                } else {
                    if (submitBtn) resetSubmitBtn(submitBtn);

                    // Cas spécial : paiement reçu mais création de compte échouée
                    if (data.payment_pending) {
                        Swal.fire({
                            title:             'Paiement reçu — Compte non créé',
                            html:              '<p>' + data.message + '</p>' +
                                               '<p class="mt-2 text-muted" style="font-size:13px;">Conservez cette référence et contactez notre support pour régulariser votre compte.</p>',
                            icon:              'warning',
                            confirmButtonText: 'Compris',
                            confirmButtonColor: '#d97706',
                            allowOutsideClick: false,
                        });
                        return; // Ne pas tenter de corriger le formulaire
                    }

                    let firstErrorStep = null;
                    const errorLines   = [];

                    if (data.error) {
                        Object.keys(data.error).forEach(field => {
                            const msg   = data.error[field][0];
                            errorLines.push(msg);
                            const input = document.getElementById(field);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = msg;
                                }
                                if (!firstErrorStep) {
                                    const stepEl = input.closest('.step-form');
                                    if (stepEl) {
                                        firstErrorStep = parseInt(stepEl.id.replace('step-form-', ''));
                                    }
                                }
                            }
                        });
                    }

                    const errorHtml = errorLines.length
                        ? '<ul class="text-start ps-3 mb-0">' + errorLines.map(m => `<li>${m}</li>`).join('') + '</ul>'
                        : data.message;

                    Swal.fire({
                        title:             'Erreur',
                        html:              errorHtml,
                        icon:              'error',
                        confirmButtonText: 'Corriger',
                    }).then(() => {
                        if (firstErrorStep && firstErrorStep !== currentStep) {
                            document.getElementById(`step-form-${currentStep}`).classList.remove('active');
                            document.getElementById(`step-${currentStep}`).classList.remove('active');
                            currentStep = firstErrorStep;
                            document.getElementById(`step-form-${currentStep}`).classList.add('active');
                            document.getElementById(`step-${currentStep}`).classList.add('active');
                            document.getElementById('progress-bar').style.width = `${(currentStep - 1) * 50}%`;
                        }
                    });
                }
            } catch (error) {
                console.error('Erreur:', error);
                Swal.fire('Erreur', 'Une erreur est survenue. Veuillez réessayer.', 'error');
                if (submitBtn) resetSubmitBtn(submitBtn);
            }
        }
    </script>

    <!-- PWA Install Banner -->
    <div id="pwa-install-banner" style="display:none; position:fixed; bottom:0; left:0; right:0; z-index:9999; background:linear-gradient(135deg,#1e40af 0%,#3b82f6 100%); color:#fff; padding:16px 20px; box-shadow:0 -4px 20px rgba(0,0,0,0.15); animation:slideUp 0.4s ease-out;">
        <div style="max-width:600px; margin:0 auto; display:flex; align-items:center; justify-content:space-between; gap:12px;">
            <div style="display:flex; align-items:center; gap:12px; flex:1;">
                <img src="/logo/LOGO.jpg" alt="Lokativ" style="width:44px; height:44px; border-radius:10px; border:2px solid rgba(255,255,255,0.3);">
                <div>
                    <div style="font-weight:700; font-size:15px;">Installer Lokativ</div>
                    <div style="font-size:12px; opacity:0.85;">Accédez rapidement depuis votre écran d'accueil</div>
                </div>
            </div>
            <div style="display:flex; gap:8px; flex-shrink:0;">
                <button id="pwa-install-dismiss" style="background:transparent; border:1px solid rgba(255,255,255,0.4); color:#fff; padding:8px 14px; border-radius:8px; cursor:pointer; font-size:13px;">Plus tard</button>
                <button id="pwa-install-btn" style="background:#fff; color:#1e40af; border:none; padding:8px 18px; border-radius:8px; cursor:pointer; font-weight:700; font-size:13px;">Installer</button>
            </div>
        </div>
    </div>
    <style>
        @keyframes slideUp { from { transform: translateY(100%); } to { transform: translateY(0); } }
    </style>

    <!-- PWA Service Worker Registration & Install Prompt -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then((registration) => {
                        console.log('SW registered: ', registration.scope);
                    })
                    .catch((error) => {
                        console.log('SW registration failed: ', error);
                    });
            });
        }

        let deferredPrompt = null;
        const isStandalone = window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;

        if (!isStandalone) {
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                const dismissed = localStorage.getItem('pwa-install-dismissed');
                if (!dismissed) {
                    document.getElementById('pwa-install-banner').style.display = 'block';
                }
            });

            document.getElementById('pwa-install-btn').addEventListener('click', async () => {
                if (deferredPrompt) {
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    if (outcome === 'accepted') {
                        document.getElementById('pwa-install-banner').style.display = 'none';
                    }
                    deferredPrompt = null;
                }
            });

            document.getElementById('pwa-install-dismiss').addEventListener('click', () => {
                document.getElementById('pwa-install-banner').style.display = 'none';
                localStorage.setItem('pwa-install-dismissed', Date.now());
            });
        }
    </script>

    {{-- ===== MODAL VIDÉO DÉMO ===== --}}
    <div id="modal-demo" role="dialog" aria-modal="true" aria-label="Vidéo de démonstration"
         style="display:none;position:fixed;inset:0;z-index:10500;background:rgba(0,0,0,0.85);
                align-items:center;justify-content:center;padding:16px;">

        {{-- Overlay cliquable pour fermer --}}
        <div onclick="closeDemoModal()" style="position:absolute;inset:0;cursor:pointer;"></div>

        {{-- Contenu du modal --}}
        <div style="position:relative;width:100%;max-width:900px;z-index:1;">

            {{-- Header --}}
            <div style="display:flex;align-items:center;justify-content:space-between;
                        margin-bottom:12px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:36px;height:36px;border-radius:50%;
                                background:linear-gradient(135deg,#1e40af,#3b82f6);
                                display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-home" style="color:#fff;font-size:14px;"></i>
                    </div>
                    <div>
                        <p style="margin:0;font-weight:700;color:#fff;font-size:15px;">Lokativ</p>
                        <p style="margin:0;color:#93c5fd;font-size:12px;">Démonstration de la plateforme</p>
                    </div>
                </div>
                <button onclick="closeDemoModal()" aria-label="Fermer"
                        style="background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);
                               color:#fff;width:36px;height:36px;border-radius:50%;font-size:16px;
                               cursor:pointer;display:flex;align-items:center;justify-content:center;
                               transition:background 0.2s;"
                        onmouseover="this.style.background='rgba(255,255,255,0.25)'"
                        onmouseout="this.style.background='rgba(255,255,255,0.1)'">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Conteneur iframe 16/9 --}}
            <div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;
                        border-radius:16px;box-shadow:0 25px 60px rgba(0,0,0,0.5);
                        background:#000;">
                <iframe id="demo-iframe"
                        src=""
                        title="Démonstration Lokativ"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen
                        style="position:absolute;top:0;left:0;width:100%;height:100%;border-radius:16px;">
                </iframe>

                {{-- Placeholder affiché avant que la vidéo se charge --}}
                <div id="demo-placeholder" style="position:absolute;inset:0;display:flex;flex-direction:column;
                            align-items:center;justify-content:center;background:#111827;border-radius:16px;">
                    <div style="width:72px;height:72px;border-radius:50%;
                                background:linear-gradient(135deg,#1e40af,#3b82f6);
                                display:flex;align-items:center;justify-content:center;margin-bottom:16px;
                                animation:pulse 2s ease-in-out infinite;">
                        <i class="fas fa-play" style="color:#fff;font-size:28px;margin-left:4px;"></i>
                    </div>
                    <p style="color:#fff;font-weight:700;font-size:18px;margin:0 0 6px;">
                        Chargement de la démo...
                    </p>
                    <p style="color:#9ca3af;font-size:13px;margin:0;">Veuillez patienter</p>
                </div>
            </div>

            {{-- Footer CTA --}}
            <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;
                        gap:12px;margin-top:14px;">
                <p style="margin:0;color:#d1d5db;font-size:13px;">
                    <i class="fas fa-star" style="color:#f59e0b;margin-right:4px;"></i>
                    Prêt à démarrer ? Créez votre compte gratuitement en 2 minutes.
                </p>
                <a href="#compte" onclick="closeDemoModal()"
                   style="padding:9px 22px;background:linear-gradient(135deg,#10b981,#3b82f6);
                          color:#fff;border-radius:50px;font-weight:700;font-size:13px;
                          text-decoration:none;white-space:nowrap;transition:opacity 0.2s;"
                   onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                    <i class="fas fa-rocket" style="margin-right:6px;"></i>Démarrer gratuitement
                </a>
            </div>
        </div>
    </div>

    {{-- ===== BANNIÈRE CONSENTEMENT COOKIES ===== --}}
    <div id="cookie-banner" role="dialog" aria-label="Consentement aux cookies"
         style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:9999;
                background:rgba(17,24,39,0.97);backdrop-filter:blur(8px);
                border-top:3px solid #3b82f6;padding:20px 24px;">
        <div style="max-width:1200px;margin:0 auto;">
            <div style="display:flex;flex-wrap:wrap;align-items:center;gap:16px;">
                <div style="flex:1;min-width:280px;">
                    <p style="margin:0 0 6px;font-size:15px;font-weight:700;color:#fff;">
                        <i class="fas fa-cookie-bite" style="color:#f59e0b;margin-right:8px;"></i>
                        Ce site utilise des cookies
                    </p>
                    <p style="margin:0;font-size:13px;color:#9ca3af;line-height:1.5;">
                        Nous utilisons des cookies essentiels au fonctionnement du site et, avec votre consentement,
                        des cookies d'analyse et de préférences pour améliorer votre expérience.
                        <a href="{{ route('legal.cookies') }}" style="color:#60a5fa;text-decoration:underline;" target="_blank">En savoir plus</a>
                    </p>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;">
                    <button id="cookie-reject" onclick="lokativCookies('essential')"
                            style="padding:9px 20px;background:transparent;border:1px solid #4b5563;color:#9ca3af;
                                   border-radius:50px;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;"
                            onmouseover="this.style.borderColor='#9ca3af';this.style.color='#fff';"
                            onmouseout="this.style.borderColor='#4b5563';this.style.color='#9ca3af';">
                        Refuser
                    </button>
                    <button id="cookie-customize" onclick="lokativCookiePanel()"
                            style="padding:9px 20px;background:transparent;border:1px solid #3b82f6;color:#60a5fa;
                                   border-radius:50px;font-size:13px;font-weight:600;cursor:pointer;transition:all 0.2s;"
                            onmouseover="this.style.background='rgba(59,130,246,0.1)';"
                            onmouseout="this.style.background='transparent';">
                        Personnaliser
                    </button>
                    <button id="cookie-accept" onclick="lokativCookies('all')"
                            style="padding:9px 24px;background:#3b82f6;border:none;color:#fff;
                                   border-radius:50px;font-size:13px;font-weight:700;cursor:pointer;transition:all 0.2s;"
                            onmouseover="this.style.background='#2563eb';"
                            onmouseout="this.style.background='#3b82f6';">
                        Tout accepter
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Panneau de personnalisation cookies --}}
    <div id="cookie-panel" role="dialog" aria-label="Personnaliser les cookies" aria-modal="true"
         style="display:none;position:fixed;inset:0;z-index:10000;align-items:center;justify-content:center;background:rgba(0,0,0,0.6);">
        <div style="background:#fff;border-radius:16px;max-width:480px;width:90%;padding:28px;box-shadow:0 20px 60px rgba(0,0,0,0.3);">
            <div style="display:flex;align-items:center;justify-content:between;margin-bottom:20px;">
                <h3 style="margin:0;font-size:18px;font-weight:700;color:#111827;flex:1;">
                    <i class="fas fa-sliders-h" style="color:#3b82f6;margin-right:8px;"></i>
                    Personnaliser les cookies
                </h3>
                <button onclick="document.getElementById('cookie-panel').style.display='none';"
                        style="background:none;border:none;font-size:20px;color:#6b7280;cursor:pointer;padding:4px;float:right;">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Nécessaires --}}
            <div style="border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:12px;">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <p style="margin:0 0 4px;font-weight:700;font-size:14px;color:#111827;">
                            <i class="fas fa-lock" style="color:#10b981;margin-right:6px;"></i>Essentiels
                        </p>
                        <p style="margin:0;font-size:12px;color:#6b7280;">Indispensables au fonctionnement</p>
                    </div>
                    <span style="background:#d1fae5;color:#065f46;font-size:11px;font-weight:700;padding:3px 10px;border-radius:50px;">Toujours actifs</span>
                </div>
            </div>

            {{-- Analytiques --}}
            <div style="border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:12px;">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <p style="margin:0 0 4px;font-weight:700;font-size:14px;color:#111827;">
                            <i class="fas fa-chart-bar" style="color:#f59e0b;margin-right:6px;"></i>Analytiques
                        </p>
                        <p style="margin:0;font-size:12px;color:#6b7280;">Amélioration de l'expérience</p>
                    </div>
                    <label style="position:relative;display:inline-block;width:44px;height:24px;cursor:pointer;">
                        <input type="checkbox" id="toggle-analytics" style="opacity:0;width:0;height:0;">
                        <span id="toggle-analytics-span" onclick="toggleCookieSwitch('analytics')"
                              style="position:absolute;inset:0;background:#d1d5db;border-radius:50px;transition:0.3s;cursor:pointer;">
                            <span id="toggle-analytics-dot"
                                  style="position:absolute;left:2px;top:2px;width:20px;height:20px;background:#fff;border-radius:50%;transition:0.3s;"></span>
                        </span>
                    </label>
                </div>
            </div>

            {{-- Préférences --}}
            <div style="border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:20px;">
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <p style="margin:0 0 4px;font-weight:700;font-size:14px;color:#111827;">
                            <i class="fas fa-heart" style="color:#8b5cf6;margin-right:6px;"></i>Préférences
                        </p>
                        <p style="margin:0;font-size:12px;color:#6b7280;">Mémorisation de vos paramètres</p>
                    </div>
                    <label style="position:relative;display:inline-block;width:44px;height:24px;cursor:pointer;">
                        <input type="checkbox" id="toggle-prefs" style="opacity:0;width:0;height:0;">
                        <span id="toggle-prefs-span" onclick="toggleCookieSwitch('prefs')"
                              style="position:absolute;inset:0;background:#d1d5db;border-radius:50px;transition:0.3s;cursor:pointer;">
                            <span id="toggle-prefs-dot"
                                  style="position:absolute;left:2px;top:2px;width:20px;height:20px;background:#fff;border-radius:50%;transition:0.3s;"></span>
                        </span>
                    </label>
                </div>
            </div>

            <div style="display:flex;gap:10px;">
                <button onclick="lokativCookiesSave()"
                        style="flex:1;padding:10px;background:#3b82f6;border:none;color:#fff;border-radius:50px;font-weight:700;font-size:14px;cursor:pointer;">
                    Enregistrer mes choix
                </button>
                <button onclick="lokativCookies('all')"
                        style="flex:1;padding:10px;background:#f1f5f9;border:1px solid #e2e8f0;color:#374151;border-radius:50px;font-weight:600;font-size:14px;cursor:pointer;">
                    Tout accepter
                </button>
            </div>
            <p style="margin-top:12px;text-align:center;font-size:11px;color:#9ca3af;">
                <a href="{{ route('legal.cookies') }}" style="color:#60a5fa;">Politique de cookies</a> &nbsp;·&nbsp;
                <a href="{{ route('legal.confidentialite') }}" style="color:#60a5fa;">Confidentialité</a>
            </p>
        </div>
    </div>

    <script>
    (function() {
        var cookieStates = { analytics: false, prefs: false };

        function getCookie(name) {
            var m = document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)');
            return m ? m.pop() : null;
        }
        function setCookie(name, value, days) {
            var d = new Date(); d.setTime(d.getTime() + days * 864e5);
            document.cookie = name + '=' + value + ';expires=' + d.toUTCString() + ';path=/;SameSite=Lax';
        }

        // Afficher la bannière si pas encore de consentement
        if (!getCookie('lokativ_consent')) {
            document.getElementById('cookie-banner').style.display = 'block';
        }

        window.lokativCookies = function(choice) {
            setCookie('lokativ_consent', choice, 365);
            document.getElementById('cookie-banner').style.display = 'none';
            document.getElementById('cookie-panel').style.display = 'none';
            if (choice === 'all') {
                setCookie('lokativ_analytics', '1', 365);
                setCookie('lokativ_prefs', '1', 365);
            } else {
                setCookie('lokativ_analytics', '0', 365);
                setCookie('lokativ_prefs', '0', 365);
            }
        };

        window.lokativCookiePanel = function() {
            document.getElementById('cookie-panel').style.removeProperty('display');
            document.getElementById('cookie-panel').style.display = 'flex';
        };

        window.toggleCookieSwitch = function(type) {
            cookieStates[type] = !cookieStates[type];
            var span = document.getElementById('toggle-' + type + '-span');
            var dot = document.getElementById('toggle-' + type + '-dot');
            if (cookieStates[type]) {
                span.style.background = '#3b82f6';
                dot.style.left = '22px';
            } else {
                span.style.background = '#d1d5db';
                dot.style.left = '2px';
            }
        };

        window.lokativCookiesSave = function() {
            setCookie('lokativ_consent', 'custom', 365);
            setCookie('lokativ_analytics', cookieStates.analytics ? '1' : '0', 365);
            setCookie('lokativ_prefs', cookieStates.prefs ? '1' : '0', 365);
            document.getElementById('cookie-banner').style.display = 'none';
            document.getElementById('cookie-panel').style.display = 'none';
        };
    })();
    </script>

    {{-- ===== JS MODAL VIDÉO DÉMO ===== --}}
    <script>
    (function () {
        // ⚠️ Remplacez VIDEO_ID par l'identifiant de votre vidéo YouTube
        // Ex : pour https://www.youtube.com/watch?v=dQw4w9WgXcQ → VIDEO_ID = dQw4w9WgXcQ
        var YOUTUBE_VIDEO_ID = 'Qf8eSKxztQk';
        var BASE_URL = 'https://www.youtube.com/embed/' + YOUTUBE_VIDEO_ID
                     + '?autoplay=1&rel=0&modestbranding=1&color=white';

        var modal      = document.getElementById('modal-demo');
        var iframe     = document.getElementById('demo-iframe');
        var placeholder = document.getElementById('demo-placeholder');

        function openDemoModal() {
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            // Charger la vidéo avec autoplay
            placeholder.style.display = 'flex';
            iframe.src = BASE_URL;
            iframe.onload = function () {
                placeholder.style.display = 'none';
            };
        }

        window.closeDemoModal = function () {
            modal.style.display = 'none';
            document.body.style.overflow = '';
            // Stopper la vidéo en vidant le src
            iframe.src = '';
            placeholder.style.display = 'flex';
        };

        // Fermer avec Échap
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && modal.style.display === 'flex') {
                window.closeDemoModal();
            }
        });

        // Brancher les boutons
        ['btn-voir-demo', 'btn-essayer-demo'].forEach(function (id) {
            var btn = document.getElementById(id);
            if (btn) btn.addEventListener('click', openDemoModal);
        });
    })();
    </script>

</body>

</html>