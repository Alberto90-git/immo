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
                    <p class="text-gray-600">Laissez ImmoManager gérer les factures et rappels</p>
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

    <!-- Portfolio Section -->
    <section id="portfolio" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-12 text-center lg:mb-16">
                <h2 class="mb-4 text-2xl font-bold text-gray-800 sm:text-3xl md:text-4xl lg:text-5xl lg:mb-6">
                    Aperçu de la <span class="text-blue-600">plateforme</span>
                </h2>
                <p class="max-w-3xl mx-auto text-base text-gray-600 sm:text-lg lg:text-xl">
                    Découvrez les interfaces intuitives et puissantes d'ImmoManager
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
                    <p class="mb-6 italic text-gray-600">"ImmoManager a révolutionné ma façon de gérer mes 5 appartements. Je gagne un temps précieux chaque mois sur la facturation et le suivi des paiements."</p>
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
                    <p class="mb-6 italic text-gray-600">"En tant qu'agence immobilière, nous gérons plus de 50 biens. ImmoManager nous permet de tout centraliser et d'offrir un meilleur service à nos clients."</p>
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

            <div class="grid gap-8 md:grid-cols-3">
                <!-- Plan Starter -->
                <div class="p-6 bg-white border border-gray-200 pricing-card rounded-2xl lg:p-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Starter</h3>
                        <span class="px-3 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded-full">Débutant</span>
                    </div>
                    <p class="mb-6 text-gray-500">Idéal pour démarrer</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-gray-800">54 000</span>
                        <span class="text-gray-500">XOF/an</span>
                    </div>
                    <ul class="mb-8 space-y-3">
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Jusqu'à <strong class="mx-1">5 maisons</strong>
                        </li>
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
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Export PDF
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="mr-3 fas fa-times"></i>
                            Création d'annexes
                        </li>
                    </ul>
                    <a href="#compte" class="block w-full py-3 text-center text-blue-600 transition-colors border-2 border-blue-600 rounded-full hover:bg-blue-600 hover:text-white">
                        Commencer
                    </a>
                </div>

                <!-- Plan Standard (Featured) -->
                <div class="p-6 bg-white border-2 border-blue-600 pricing-card featured rounded-2xl lg:p-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Standard</h3>
                        <span class="px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded-full">Populaire</span>
                    </div>
                    <p class="mb-6 text-gray-500">Pour les propriétaires actifs</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-blue-600">120 000</span>
                        <span class="text-gray-500">XOF/an</span>
                    </div>
                    <ul class="mb-8 space-y-3">
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Jusqu'à <strong class="mx-1">20 maisons</strong>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Tableau de bord avancé
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Gestion des locataires
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Facturation automatique
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Statistiques détaillées
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Export PDF/Excel
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="mr-3 fas fa-times"></i>
                            Création d'annexes
                        </li>
                    </ul>
                    <a href="#compte" class="block w-full py-3 text-center text-white transition-colors bg-blue-600 rounded-full hover:bg-blue-700">
                        Choisir ce plan
                    </a>
                </div>

                <!-- Plan Premium -->
                <div class="p-6 bg-white border border-gray-200 pricing-card rounded-2xl lg:p-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Premium</h3>
                        <span class="px-3 py-1 text-xs font-semibold text-purple-600 bg-purple-100 rounded-full">Pro</span>
                    </div>
                    <p class="mb-6 text-gray-500">Pour les agences immobilières</p>
                    <div class="mb-6">
                        <span class="text-4xl font-bold text-gray-800">180 000</span>
                        <span class="text-gray-500">XOF/an</span>
                    </div>
                    <ul class="mb-8 space-y-3">
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            <strong class="mx-1">Maisons illimitées</strong>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Jusqu'à <strong class="mx-1">2 annexes</strong>
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Toutes les fonctionnalités
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Multi-utilisateurs
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Statistiques avancées
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Support prioritaire
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="mr-3 text-green-500 fas fa-check"></i>
                            Formation incluse
                        </li>
                    </ul>
                    <a href="#compte" class="block w-full py-3 text-center text-blue-600 transition-colors border-2 border-blue-600 rounded-full hover:bg-blue-600 hover:text-white">
                        Nous contacter
                    </a>
                </div>
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
                        <h3 class="text-lg font-semibold text-gray-800">Comment puis-je commencer à utiliser ImmoManager ?</h3>
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
                        <p class="text-gray-600 pb-6">Une fois vos locataires et les montants de loyer configurés, ImmoManager génère automatiquement les factures à chaque échéance. Vous pouvez personnaliser les modèles de factures et configurer des rappels automatiques en cas de retard de paiement.</p>
                    </div>
                </div>

                <!-- FAQ 5 -->
                <div class="faq-item bg-white rounded-xl shadow-sm">
                    <div class="faq-question flex items-center justify-between p-6 cursor-pointer">
                        <h3 class="text-lg font-semibold text-gray-800">Y a-t-il une application mobile ?</h3>
                        <i class="fas fa-chevron-down text-blue-600 faq-icon transition-transform duration-300"></i>
                    </div>
                    <div class="faq-answer px-6">
                        <p class="text-gray-600 pb-6">ImmoManager est entièrement responsive et fonctionne parfaitement sur tous les appareils mobiles via votre navigateur. Une application native iOS et Android est en cours de développement et sera bientôt disponible.</p>
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
                                <p class="text-gray-600">contact@immomanager.com</p>
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
                Rejoignez des centaines de propriétaires qui font confiance à ImmoManager
            </p>
            <div class="flex flex-col justify-center gap-4 sm:flex-row">
                <a href="#compte" class="px-8 py-4 text-lg font-bold text-blue-600 transition-all bg-white rounded-full hover:bg-blue-50 hover-lift">
                    <i class="mr-2 fas fa-rocket"></i>
                    Créer un compte gratuit
                </a>
                <a href="#contact" class="px-8 py-4 text-lg font-bold text-white transition-all border-2 border-white rounded-full hover:bg-white hover:text-blue-600">
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
                        <span class="text-2xl font-bold text-white">ImmoManager</span>
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
                        <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Conditions d'utilisation</a></li>
                        <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Politique de confidentialité</a></li>
                        <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Mentions légales</a></li>
                        <li><a href="#" class="text-gray-400 transition-colors hover:text-white">Cookies</a></li>
                    </ul>
                </div>
            </div>

            <!-- Barre de séparation -->
            <div class="pt-8 border-t border-gray-800">
                <div class="flex flex-col items-center justify-between md:flex-row">
                    <p class="mb-4 text-gray-400 md:mb-0">
                        &copy; {{ date('Y') }} ImmoManager. Tous droits réservés.
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
    
    <script>
        // Données des plans d'abonnement
        const plans = {
            particulier: [
                { id: 'starter', nom: 'Starter', prix: 54000, periode: 'an', proprietes: 5, annexes: 0, features: ['Jusqu\'à 5 maisons', 'Tableau de bord complet', 'Gestion des locataires', 'Facturation automatique', 'Export PDF'] },
                { id: 'standard', nom: 'Standard', prix: 120000, periode: 'an', proprietes: 20, annexes: 0, features: ['Jusqu\'à 20 maisons', 'Tableau de bord avancé', 'Statistiques détaillées', 'Export PDF/Excel', 'Support prioritaire'] },
                { id: 'premium', nom: 'Premium', prix: 180000, periode: 'an', proprietes: 'Illimitées', annexes: 2, features: ['Maisons illimitées', 'Jusqu\'à 2 annexes', 'Toutes les fonctionnalités', 'Multi-utilisateurs', 'Formation incluse'] }
            ],
            entreprise: [
                { id: 'starter', nom: 'Starter', prix: 54000, periode: 'an', proprietes: 5, annexes: 0, features: ['Jusqu\'à 5 maisons', 'Tableau de bord complet', 'Gestion des locataires', 'Facturation automatique', 'Export PDF'] },
                { id: 'standard', nom: 'Standard', prix: 120000, periode: 'an', proprietes: 20, annexes: 0, features: ['Jusqu\'à 20 maisons', 'Tableau de bord avancé', 'Statistiques détaillées', 'Export PDF/Excel', 'Support prioritaire'], featured: true },
                { id: 'premium', nom: 'Premium', prix: 180000, periode: 'an', proprietes: 'Illimitées', annexes: 2, features: ['Maisons illimitées', 'Jusqu\'à 2 annexes', 'Toutes les fonctionnalités', 'Multi-utilisateurs', 'Formation incluse'] }
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