<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="ImmoManager - Plateforme complète de gestion immobilière. Automatisez vos tâches, optimisez vos revenus et gérez votre patrimoine en toute simplicité.">
    <meta name="keywords" content="immobilier, gestion locative, propriété, loyers, patrimoine, investissement">
    
    <title>ImmoManager - Votre partenaire de gestion immobilière</title>
    
    <!-- Favicon corrigé -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'><text y='50%' x='50%' dominant-baseline='middle' text-anchor='middle' font-size='400'>🏠</text></svg>">
    
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
      "name": "ImmoManager",
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
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
            background: white;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .header-scrolled .nav-link {
            color: #374151 !important;
        }

        .header-scrolled .nav-link:hover {
            color: #1e40af !important;
        }

        .header-scrolled .logo-text {
            background: linear-gradient(to right, #1e40af, #3b82f6);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
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
                <a href="/" class="flex items-center space-x-2" aria-label="ImmoManager - Retour à l'accueil">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full sm:w-10 sm:h-10 bg-gradient-to-r from-blue-600 to-blue-800">
                        <i class="text-sm text-white fas fa-home sm:text-base" aria-hidden="true"></i>
                    </div>
                    <span class="text-xl font-bold text-white logo-text sm:text-2xl">
                        ImmoManager
                    </span>
                </a>

                <div class="items-center hidden space-x-8 md:flex">
                    <a href="#accueil" class="text-white transition-colors nav-link hover:text-blue-300">Accueil</a>
                    <a href="#fonctionnalites" class="text-white transition-colors nav-link hover:text-blue-300">Fonctionnalités</a>
                    <a href="#portfolio" class="text-white transition-colors nav-link hover:text-blue-300">Portfolio</a>
                    <a href="#tarifs" class="text-white transition-colors nav-link hover:text-blue-300">Tarifs</a>
                    <a href="#contact" class="text-white transition-colors nav-link hover:text-blue-300">Contact</a>

                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-blue-600 transition-colors bg-white rounded-full lg:px-6 hover:bg-blue-50 lg:text-base">
                        Se connecter
                    </a>
                    <a href="#compte" class="px-4 py-2 text-sm font-semibold text-white transition-colors bg-blue-600 rounded-full lg:px-6 hover:bg-blue-700 lg:text-base">
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
                        <button
                            class="px-6 py-3 text-base font-bold text-white transition-all rounded-full glass-effect lg:px-8 lg:py-4 lg:text-lg hover:bg-white hover:text-blue-600">
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
                        <a href="#compte"
                            class="block w-full py-3 mt-4 text-sm font-bold text-center text-white transition-all bg-gradient-to-r from-green-400 to-blue-500 lg:py-4 rounded-xl hover:from-green-500 hover:to-blue-600 lg:text-base">
                            <i class="mr-2 fas fa-play" aria-hidden="true"></i>
                            Essayer la démo
                        </a>
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
                <!-- Fonctionnalités cards (inchangées) -->
                <!-- ... reste du code des fonctionnalités ... -->
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section id="portfolio" class="py-12 sm:py-16 lg:py-20 bg-white">
        <!-- ... reste du code portfolio ... -->
    </section>

    <!-- Témoignages Section -->
    <section class="py-12 sm:py-16 lg:py-20 bg-gray-50">
        <!-- ... reste du code témoignages ... -->
    </section>

    <!-- Tarifs Section -->
    <section id="tarifs" class="py-12 sm:py-16 lg:py-20 bg-white">
        <!-- ... reste du code tarifs ... -->
    </section>

    <!-- Créer un compte Section - Formulaire d'inscription complet -->
    <section id="compte" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="px-4 mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="mb-12 text-center lg:mb-16">
                <h2 class="mb-4 text-2xl font-bold text-gray-800 sm:text-3xl md:text-4xl lg:text-5xl lg:mb-6">
                    Créer votre compte <span class="text-blue-600">ImmoManager</span>
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
                    <div class="step" id="step-4">4</div>
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
                
                <!-- Formulaire étape 3: Plan d'abonnement -->
                <div class="step-form" id="step-form-3">
                    <h3 class="mb-4">Choisissez votre plan</h3>
                    <div id="plans-container">
                        <!-- Les plans seront chargés dynamiquement -->
                    </div>
                    
                    <div class="form-navigation">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(2)">
                            <i class="fas fa-arrow-left me-2" aria-hidden="true"></i> Précédent
                        </button>
                        <button type="button" class="btn btn-primary" onclick="nextStep(4)" id="btn-next-3" disabled>
                            Suivant <i class="fas fa-arrow-right ms-2" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Formulaire étape 4: Sécurité et finalisation -->
                <div class="step-form" id="step-form-4">
                    <h3 class="mb-4">Sécurité du compte</h3>
                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="password-strength">
                            <div class="password-strength-bar" id="password-strength-bar"></div>
                        </div>
                        <div class="form-text">
                            Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.
                        </div>
                        <div class="invalid-feedback" id="password-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                        <div class="invalid-feedback">Les mots de passe ne correspondent pas.</div>
                    </div>
                    
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="conditions" required>
                        <label class="form-check-label" for="conditions">
                            J'accepte les <a href="#" class="text-primary">conditions générales d'utilisation</a> et la <a href="#" class="text-primary">politique de confidentialité</a>.
                        </label>
                        <div class="invalid-feedback">Vous devez accepter les conditions pour continuer.</div>
                    </div>
                    
                    <div class="form-navigation">
                        <button type="button" class="btn btn-outline-secondary" onclick="prevStep(3)">
                            <i class="fas fa-arrow-left me-2" aria-hidden="true"></i> Précédent
                        </button>
                        <button type="button" class="btn btn-success" id="btn-submit" onclick="submitForm()">
                            <i class="fas fa-check me-2" aria-hidden="true"></i> Créer mon compte
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-12 sm:py-16 lg:py-20 bg-gray-50">
        <!-- ... reste du code FAQ ... -->
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-12 sm:py-16 lg:py-20 bg-white">
        <!-- ... reste du code contact ... -->
    </section>

    <!-- Footer -->
    <footer class="py-12 text-white bg-gray-900 lg:py-16" role="contentinfo">
        <!-- ... reste du code footer ... -->
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Données des plans d'abonnement
        const plans = {
            particulier: [
                { id: 'gratuit', nom: 'Gratuit', prix: 0, periode: 'illimité', proprietes: 2, features: ['Tableau de bord basique', 'Gestion des loyers', '2 propriétés maximum'] },
                { id: 'basique', nom: 'Basique', prix: 25000, periode: 'an', proprietes: 3, features: ['Tableau de bord complet', 'Gestion avancée', '3 propriétés maximum', 'Support standard'] },
                { id: 'avance', nom: 'Avancé', prix: 50000, periode: 'an', proprietes: 5, features: ['Toutes les fonctionnalités', '5 propriétés maximum', 'Support prioritaire', 'Statistiques avancées'] }
            ],
            entreprise: [
                { id: 'gratuit', nom: 'Gratuit', prix: 0, periode: 'illimité', proprietes: 2, features: ['Tableau de bord basique', 'Gestion des loyers', '2 propriétés maximum'] },
                { id: 'starter', nom: 'Starter', prix: 50000, periode: 'an', proprietes: 10, features: ['Gestion multi-utilisateurs', '10 propriétés maximum', 'Support standard', 'API basique'] },
                { id: 'premium', nom: 'Premium', prix: 100000, periode: 'an', proprietes: 25, features: ['Fonctionnalités avancées', '25 propriétés maximum', 'Support prioritaire', 'API complète'] },
                { id: 'professionnel', nom: 'Professionnel', prix: 300000, periode: 'an', proprietes: 'Illimitées', features: ['Toutes les fonctionnalités', 'Propriétés illimitées', 'Support dédié 24/7', 'API complète'] }
            ]
        };
        
        let currentStep = 1;
        let selectedPlan = null;
        let accountType = '';
        
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
            
            // Force du mot de passe
            document.getElementById('password').addEventListener('input', checkPasswordStrength);
            
            // Confirmation du mot de passe
            document.getElementById('confirm_password').addEventListener('input', validatePasswordConfirmation);

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
                document.getElementById('progress-bar').style.width = `${(currentStep-1) * 25}%`;
                
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
            document.getElementById('progress-bar').style.width = `${(currentStep-1) * 25}%`;
            
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
                
                planElement.innerHTML = `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">${plan.nom}</h5>
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
                `;
                
                planElement.addEventListener('click', function() {
                    document.querySelectorAll('.plan-card').forEach(c => c.classList.remove('selected'));
                    this.classList.add('selected');
                    selectedPlan = plan;
                    document.getElementById('btn-next-3').disabled = false;
                });
                
                container.appendChild(planElement);
            });
        }
        
        // Vérification de la force du mot de passe
        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('password-strength-bar');
            const feedback = document.getElementById('password-feedback');
            
            // Réinitialiser
            strengthBar.className = 'password-strength-bar';
            feedback.textContent = '';
            document.getElementById('password').classList.remove('is-invalid');
            
            if (password.length === 0) {
                return;
            }
            
            // Critères de force
            let strength = 0;
            let messages = [];
            
            if (password.length >= 8) strength++;
            else messages.push('au moins 8 caractères');
            
            if (/[A-Z]/.test(password)) strength++;
            else messages.push('une lettre majuscule');
            
            if (/[a-z]/.test(password)) strength++;
            else messages.push('une lettre minuscule');
            
            if (/[0-9]/.test(password)) strength++;
            else messages.push('un chiffre');
            
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            else messages.push('un caractère spécial');
            
            // Mettre à jour l'affichage
            if (strength <= 2) {
                strengthBar.className = 'password-strength-bar weak';
                feedback.textContent = 'Faible: ' + messages.join(', ');
                document.getElementById('password').classList.add('is-invalid');
            } else if (strength <= 4) {
                strengthBar.className = 'password-strength-bar medium';
                feedback.textContent = 'Moyen';
            } else {
                strengthBar.className = 'password-strength-bar strong';
                feedback.textContent = 'Fort';
            }
        }
        
        // Validation de la confirmation du mot de passe
        function validatePasswordConfirmation() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password');
            
            if (confirmPassword.value !== password) {
                confirmPassword.classList.add('is-invalid');
                return false;
            } else {
                confirmPassword.classList.remove('is-invalid');
                return true;
            }
        }
        
        // Soumission du formulaire
        async function submitForm() {
            if (!validateStep(4)) {
                return;
            }
            
            if (!validatePasswordConfirmation()) {
                Swal.fire('Erreur', 'Les mots de passe ne correspondent pas.', 'error');
                return;
            }
            
            if (!document.getElementById('conditions').checked) {
                document.getElementById('conditions').classList.add('is-invalid');
                Swal.fire('Erreur', 'Vous devez accepter les conditions générales.', 'error');
                return;
            }
            
            // Récupérer les données du formulaire
            const formData = {
                type_compte: accountType === 'particulier' ? 'Particulier' : 'Entreprise',
                nom: document.getElementById('nom').value,
                prenom: document.getElementById('prenom').value,
                email: document.getElementById('email').value,
                code_pays: document.getElementById('code_pays').value,
                telephone: document.getElementById('telephone').value,
                mot_de_passe: document.getElementById('password').value,
                Confirmer_mot_de_passe: document.getElementById('confirm_password').value,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
            
            if (accountType === 'entreprise') {
                formData.designation = document.getElementById('designation').value;
                formData.adresse = document.getElementById('adresse').value;
                formData.telepone_entreprise = document.getElementById('code_pays').value + document.getElementById('telephone').value;
                formData.email_entreprise = document.getElementById('email_entreprise').value;
            }
            
            // Afficher l'animation de chargement
            const submitBtn = document.getElementById('btn-submit');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Création en cours...';
            
            try {
                // Envoyer la requête AJAX
                const response = await fetch("{{ route('creation_compte') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': formData._token
                    },
                    body: JSON.stringify(formData)
                });
                
                const data = await response.json();
                
                if (data.status === true) {
                    Swal.fire({
                        title: 'Succès!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        // Redirection vers la page de connexion
                        window.location.href = "{{ route('login') }}";
                    });
                } else {
                    Swal.fire('Erreur', data.message, 'error');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check me-2"></i> Créer mon compte';
                    
                    // Afficher les erreurs de validation
                    if (data.error) {
                        Object.keys(data.error).forEach(field => {
                            const input = document.getElementById(field);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = input.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = data.error[field][0];
                                }
                            }
                        });
                    }
                }
            } catch (error) {
                console.error('Erreur:', error);
                Swal.fire('Erreur', 'Une erreur est survenue. Veuillez réessayer.', 'error');
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-check me-2"></i> Créer mon compte';
            }
        }
    </script>

</body>

</html>