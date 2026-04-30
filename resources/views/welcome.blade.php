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
    <meta name="theme-color" content="#1e40af" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1e3a8a" media="(prefers-color-scheme: dark)">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Lokativ">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/img/apple-touch-icon.png">
    <link rel="apple-touch-icon" href="/assets/img/apple-touch-icon.png">
    <!-- MS Tiles -->
    <meta name="msapplication-TileColor" content="#1e40af">
    <meta name="msapplication-TileImage" content="/logo/LOGO-VARIANTE-16267d.png">
    <meta name="application-name" content="Lokativ">
    
    <!-- Chargement optimisé des ressources -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@18/build/css/intlTelInput.css">
    <style>
        /* Intégration intl-tel-input avec Bootstrap */
        .iti { width: 100%; }
        .iti__flag-container { z-index: 100; }
        .iti__selected-flag { border-radius: 0.375rem 0 0 0.375rem; }
        #telephone.is-invalid ~ .invalid-feedback { display: block; }
    </style>
    
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

        /* ── NAV ─────────────────────────────────── */
        .nav-glass {
            background: rgba(15,23,42,0.35);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border-bottom: 1px solid rgba(255,255,255,0.09);
            transition: all 0.35s cubic-bezier(.4,0,.2,1);
        }
        .nav-scrolled {
            background: rgba(255,255,255,0.97) !important;
            backdrop-filter: blur(24px) !important;
            border-bottom: 1px solid #e2e8f0 !important;
            box-shadow: 0 4px 28px rgba(30,64,175,0.09) !important;
        }
        .nav-scrolled .nav-link { color: #374151 !important; }
        .nav-scrolled .nav-link:hover { color: #2563eb !important; }
        .nav-scrolled .nav-link::after { background: #2563eb !important; }
        .nav-scrolled .logo-text { color: #1e40af !important; background: none !important; -webkit-text-fill-color: #1e40af !important; }
        .nav-scrolled .logo-icon { background: linear-gradient(135deg,#1e40af,#3b82f6) !important; }
        .nav-scrolled .hamburger span { background-color: #1e3a8a !important; }
        .nav-scrolled .header-btn-login { color: #2563eb !important; background: #eff6ff !important; border-color: #bfdbfe !important; }
        .nav-scrolled .header-btn-register { background: linear-gradient(135deg,#1d4ed8,#2563eb) !important; color: #fff !important; box-shadow: 0 4px 14px rgba(37,99,235,0.3) !important; }

        .nav-link {
            position: relative; font-size: 0.875rem; font-weight: 600;
            padding: 6px 0; letter-spacing: 0.01em; text-decoration: none;
            transition: color 0.2s;
        }
        .nav-link::after {
            content: ''; position: absolute; left: 0; bottom: -2px;
            width: 0; height: 2px; border-radius: 2px;
            background: rgba(255,255,255,0.8); transition: width 0.25s;
        }
        .nav-link:hover::after { width: 100%; }

        .header-btn-login {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 18px; border-radius: 50px;
            font-size: 0.82rem; font-weight: 700; text-decoration: none;
            color: rgba(255,255,255,0.92); background: rgba(255,255,255,0.12);
            border: 1.5px solid rgba(255,255,255,0.28);
            transition: all 0.22s;
        }
        .header-btn-login:hover { background: rgba(255,255,255,0.22); color: #fff; }

        .header-btn-register {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 20px; border-radius: 50px;
            font-size: 0.82rem; font-weight: 700; text-decoration: none;
            color: #1e3a8a; background: #fff;
            border: 1.5px solid transparent;
            transition: all 0.22s;
            box-shadow: 0 2px 14px rgba(0,0,0,0.14);
        }
        .header-btn-register:hover { background: #eff6ff; color: #1d4ed8; transform: translateY(-1px); }

        /* Mobile menu */
        .mobile-menu-overlay {
            position: fixed; inset: 0;
            background: rgba(15,23,42,0.65);
            backdrop-filter: blur(4px);
            z-index: 60; opacity: 0; visibility: hidden;
            transition: all 0.3s ease;
        }
        .mobile-menu-overlay.active { opacity: 1; visibility: visible; }

        .mobile-menu-panel {
            position: fixed; top: 0; right: -100%;
            width: min(320px, 90vw); height: 100dvh;
            background: #0f172a;
            z-index: 70; padding: 0;
            transition: right 0.35s cubic-bezier(.4,0,.2,1);
            overflow-y: auto; display: flex; flex-direction: column;
        }
        .mobile-menu-panel.active { right: 0; }

        .mob-panel-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .mobile-menu-close {
            width: 36px; height: 36px; border-radius: 50%;
            background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.12);
            color: white; font-size: 0.9rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: background 0.2s; flex-shrink: 0;
        }
        .mobile-menu-close:hover { background: rgba(255,255,255,0.18); }

        .mob-nav-link {
            display: flex; align-items: center; gap: 12px;
            color: rgba(255,255,255,0.75); text-decoration: none;
            padding: 13px 1.5rem; font-size: 0.95rem; font-weight: 600;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            transition: all 0.2s;
        }
        .mob-nav-link:hover { color: #fff; background: rgba(255,255,255,0.05); padding-left: 2rem; }
        .mob-nav-link i { width: 18px; text-align: center; opacity: 0.7; font-size: 0.85rem; }

        /* Hamburger */
        .hamburger {
            display: flex; flex-direction: column; gap: 5px;
            width: 22px; cursor: pointer; padding: 2px 0;
        }
        .hamburger span {
            display: block; height: 2px; border-radius: 2px;
            background-color: white; transition: all 0.3s ease;
        }
        .hamburger span:nth-child(2) { width: 70%; }
        .hamburger.active span:nth-child(1) { transform: rotate(45deg) translate(5px, 7px); }
        .hamburger.active span:nth-child(2) { opacity: 0; transform: translateX(-6px); }
        .hamburger.active span:nth-child(3) { transform: rotate(-45deg) translate(5px, -7px); width: 100%; }

        /* ── HERO ─────────────────────────────────── */
        .hero-section {
            background: linear-gradient(135deg, #0c1445 0%, #0f2878 40%, #1d4ed8 100%);
            position: relative; overflow: hidden;
        }
        .hero-section::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse 80% 60% at 70% 50%, rgba(59,130,246,0.18) 0%, transparent 70%),
                        radial-gradient(ellipse 40% 40% at 10% 80%, rgba(99,102,241,0.15) 0%, transparent 60%);
            pointer-events: none;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2);
            backdrop-filter: blur(8px); border-radius: 50px;
            padding: 7px 18px; font-size: 0.78rem; font-weight: 700; color: #fff;
            margin-bottom: 1.5rem;
        }
        .hero-badge-dot {
            width: 7px; height: 7px; border-radius: 50%; background: #4ade80;
            box-shadow: 0 0 8px #4ade80;
            animation: pulse-dot 2s ease-in-out infinite;
        }
        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.75); }
        }
        .hero-stat-row { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 2rem; }
        .hero-stat {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,0.09); border: 1px solid rgba(255,255,255,0.15);
            border-radius: 50px; padding: 6px 14px;
            font-size: 0.78rem; font-weight: 600; color: rgba(255,255,255,0.9);
        }
        .hero-stat i { font-size: 0.72rem; }
        .hero-card-wrap {
            background: rgba(255,255,255,0.07);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 24px; overflow: hidden;
        }
        .hero-card-header {
            background: rgba(255,255,255,0.06); border-bottom: 1px solid rgba(255,255,255,0.1);
            padding: 12px 16px; display: flex; align-items: center; gap: 10px;
        }
        .hero-dots { display: flex; gap: 5px; }
        .hero-dots span { width: 8px; height: 8px; border-radius: 50%; }
        .hero-mini-row {
            display: flex; align-items: center; gap: 10px;
            padding: 11px 14px; border-radius: 12px;
            background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1);
            transition: background 0.2s;
        }
        .hero-mini-icon {
            width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 14px;
        }
        .hero-mini-stat {
            background: rgba(255,255,255,0.08); border-radius: 12px; padding: 10px 14px; text-align: center;
        }

        /* ── Phone mockup — mode clair (défaut) ── */
        .phone-frame {
            background: linear-gradient(160deg,#dbeafe 0%,#eff6ff 100%);
            box-shadow: 0 36px 90px rgba(30,58,138,0.14), 0 0 0 1.5px rgba(37,99,235,0.18), inset 0 1px 0 rgba(255,255,255,0.8);
        }
        .phone-island       { background: #bfdbfe; }
        .phone-island-inner { background: #93c5fd; }
        .phone-statusbar    { color: rgba(15,23,42,0.6); }
        .phone-screen-bg    { background: #f1f5f9; }
        .phone-appbar       { border-bottom: 1px solid rgba(0,0,0,0.07); }
        .phone-brand-label  { color: rgba(15,23,42,0.38); }
        .phone-screen-title { color: #1e293b; }
        .phone-icon-btn     { background: rgba(37,99,235,0.1); border: 1px solid rgba(37,99,235,0.25); }
        .phone-card         { background: #fff; border: 1px solid rgba(0,0,0,0.07); box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .phone-name         { color: #1e293b; }
        .phone-sublabel     { color: rgba(15,23,42,0.45); }
        .phone-stat-label   { color: rgba(15,23,42,0.5); }
        .phone-chart-wrap   { background: rgba(37,99,235,0.06); }
        .phone-chart-label  { color: rgba(15,23,42,0.5); }
        .phone-inv-amount   { color: #1e293b; }
        .phone-inv-date     { color: rgba(15,23,42,0.4); }
        .phone-inv-row      { background: rgba(0,0,0,0.02); border: 1px solid rgba(0,0,0,0.06); }
        .phone-icon-sm      { color: rgba(15,23,42,0.35); }
        .phone-billing-label{ color: rgba(15,23,42,0.5); }
        .phone-dot-inactive { background: rgba(15,23,42,0.18); }

        /* ── Phone mockup — mode sombre ── */
        html[data-theme="dark"] .phone-frame {
            background: linear-gradient(160deg,#0d1526 0%,#1a2540 100%);
            box-shadow: 0 36px 90px rgba(0,0,0,0.6), 0 0 0 1.5px rgba(255,255,255,0.09), inset 0 1px 0 rgba(255,255,255,0.06);
        }
        html[data-theme="dark"] .phone-island       { background: #060d18; }
        html[data-theme="dark"] .phone-island-inner { background: #1e2d45; }
        html[data-theme="dark"] .phone-statusbar    { color: rgba(255,255,255,0.65); }
        html[data-theme="dark"] .phone-screen-bg    { background: #0b1120; }
        html[data-theme="dark"] .phone-appbar       { border-bottom-color: rgba(255,255,255,0.06); }
        html[data-theme="dark"] .phone-brand-label  { color: rgba(255,255,255,0.35); }
        html[data-theme="dark"] .phone-screen-title { color: #fff; }
        html[data-theme="dark"] .phone-icon-btn     { background: rgba(59,130,246,0.15); border-color: rgba(59,130,246,0.3); }
        html[data-theme="dark"] .phone-card         { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.07); box-shadow: none; }
        html[data-theme="dark"] .phone-name         { color: #fff; }
        html[data-theme="dark"] .phone-sublabel     { color: rgba(255,255,255,0.38); }
        html[data-theme="dark"] .phone-stat-label   { color: rgba(255,255,255,0.45); }
        html[data-theme="dark"] .phone-chart-wrap   { background: rgba(255,255,255,0.04); }
        html[data-theme="dark"] .phone-chart-label  { color: rgba(255,255,255,0.4); }
        html[data-theme="dark"] .phone-inv-amount   { color: #f1f5f9; }
        html[data-theme="dark"] .phone-inv-date     { color: rgba(255,255,255,0.32); }
        html[data-theme="dark"] .phone-inv-row      { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); }
        html[data-theme="dark"] .phone-icon-sm      { color: rgba(255,255,255,0.35); }
        html[data-theme="dark"] .phone-billing-label{ color: rgba(255,255,255,0.4); }
        html[data-theme="dark"] .phone-dot-inactive { background: rgba(255,255,255,0.22); }

        @media (max-width: 1023px) {
            .hero-grid-inner { grid-template-columns: 1fr !important; }
            .hero-card-outer { display: none !important; }
        }
        @media (max-width: 640px) {
            .hero-stat-row { gap: 7px; }
            .hero-stat { font-size: 0.72rem; padding: 5px 10px; }
        }

        /* Aperçu plateforme — tabs */
        .ap-tabs { display:flex; gap:8px; flex-wrap:wrap; justify-content:center; margin-bottom:32px; }
        .ap-tab {
            display:inline-flex; align-items:center; gap:7px;
            padding:9px 18px; border-radius:50px; font-size:0.82rem; font-weight:600;
            cursor:pointer; border:1.5px solid #e2e8f0; background:#fff; color:#64748b;
            transition:all 0.22s;
        }
        .ap-tab:hover { border-color:#93c5fd; color:#2563eb; background:#eff6ff; }
        .ap-tab.active { background:#2563eb; color:#fff; border-color:#2563eb; box-shadow:0 4px 14px rgba(37,99,235,0.25); }
        .ap-tab i { font-size:0.9rem; }

        .ap-screen-wrap {
            background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:20px;
            overflow:hidden; box-shadow:0 20px 60px rgba(30,64,175,0.08);
        }
        .ap-browser-bar {
            background:#f1f5f9; border-bottom:1px solid #e2e8f0;
            padding:10px 16px; display:flex; align-items:center; gap:12px;
        }
        .ap-browser-dots { display:flex; gap:5px; }
        .ap-browser-dots span { width:10px; height:10px; border-radius:50%; }
        .ap-browser-url {
            flex:1; background:#fff; border:1px solid #e2e8f0; border-radius:20px;
            padding:4px 14px; font-size:0.72rem; color:#94a3b8; max-width:340px;
        }
        .ap-screen { display:none; }
        .ap-screen.active { display:block; }
        .ap-screen-inner { padding:24px 20px; min-height:320px; }

        /* Mock UI elements */
        .mock-topbar { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; }
        .mock-title { font-size:1rem; font-weight:700; color:#1e293b; }
        .mock-badge { font-size:0.7rem; font-weight:700; padding:3px 10px; border-radius:20px; }
        .mock-cards-row { display:grid; grid-template-columns:repeat(4,1fr); gap:10px; margin-bottom:16px; }
        .mock-card {
            padding:12px 14px; border-radius:12px; background:#fff;
            border:1px solid #e2e8f0; box-shadow:0 2px 8px rgba(0,0,0,0.04);
        }
        .mock-card .mc-label { font-size:0.65rem; color:#94a3b8; font-weight:600; text-transform:uppercase; letter-spacing:0.04em; margin-bottom:4px; }
        .mock-card .mc-value { font-size:1.15rem; font-weight:800; color:#1e293b; }
        .mock-card .mc-sub { font-size:0.65rem; color:#22c55e; font-weight:600; margin-top:2px; }
        .mock-icon-circle { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:14px; margin-bottom:8px; }
        .mock-chart-bar { height:80px; display:flex; align-items:flex-end; gap:4px; padding:0 4px; }
        .mock-bar { flex:1; border-radius:4px 4px 0 0; opacity:0.85; }
        .mock-list { display:flex; flex-direction:column; gap:7px; }
        .mock-list-item { display:flex; align-items:center; gap:10px; padding:9px 12px; background:#fff; border-radius:10px; border:1px solid #f1f5f9; }
        .mock-avatar { width:28px; height:28px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:0.65rem; font-weight:800; flex-shrink:0; }
        .mock-list-text .t1 { font-size:0.72rem; font-weight:700; color:#1e293b; }
        .mock-list-text .t2 { font-size:0.65rem; color:#94a3b8; }
        .mock-pill { font-size:0.6rem; font-weight:700; padding:2px 8px; border-radius:20px; margin-left:auto; }
        .mock-progress { height:6px; border-radius:3px; background:#f1f5f9; overflow:hidden; margin-top:8px; }
        .mock-progress-fill { height:100%; border-radius:3px; }

        /* Témoignages modernes */
        .testi-card {
            background:#fff; border-radius:18px; padding:28px 26px 24px;
            border:1.5px solid #e8edf5; position:relative; overflow:hidden;
            transition:box-shadow 0.25s, transform 0.25s, border-color 0.25s;
            display:flex; flex-direction:column;
        }
        .testi-card::before {
            content:''; position:absolute; top:0; left:0; right:0; height:3px;
            border-radius:18px 18px 0 0;
        }
        .testi-card:hover { transform:translateY(-5px); box-shadow:0 16px 40px rgba(30,64,175,0.1); border-color:#bfdbfe; }
        .testi-quote-mark { font-size:4rem; line-height:1; color:#dbeafe; font-family:Georgia,serif; position:absolute; top:14px; right:20px; pointer-events:none; }
        .testi-stars { display:flex; gap:3px; margin-bottom:14px; }
        .testi-stars i { font-size:13px; color:#f59e0b; }
        .testi-text { font-size:0.88rem; color:#475569; line-height:1.75; flex:1; margin-bottom:20px; font-style:italic; }
        .testi-footer { display:flex; align-items:center; gap:12px; padding-top:16px; border-top:1px solid #f1f5f9; }
        .testi-avatar { width:42px; height:42px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:0.82rem; color:#fff; flex-shrink:0; }
        .testi-name { font-size:0.88rem; font-weight:700; color:#1e293b; }
        .testi-role { font-size:0.75rem; color:#94a3b8; }
        .testi-verified { display:inline-flex; align-items:center; gap:4px; font-size:0.65rem; font-weight:700; color:#2563eb; background:#eff6ff; padding:2px 8px; border-radius:20px; margin-top:4px; }

        /* ── Modal témoignage ───────────────────── */
        .testi-modal-overlay {
            position:fixed; inset:0; z-index:9999;
            background:rgba(10,15,50,0.72);
            backdrop-filter:blur(6px);
            display:flex; align-items:center; justify-content:center;
            padding:1rem;
            opacity:0; visibility:hidden;
            transition:opacity 0.25s, visibility 0.25s;
        }
        .testi-modal-overlay.open { opacity:1; visibility:visible; }

        .testi-modal {
            background:#fff; border-radius:22px;
            padding:2rem 2rem 1.75rem;
            width:100%; max-width:520px;
            box-shadow:0 30px 80px rgba(10,20,80,0.28);
            transform:translateY(30px) scale(0.97);
            transition:transform 0.3s cubic-bezier(0.34,1.56,0.64,1);
            position:relative;
        }
        .testi-modal-overlay.open .testi-modal { transform:translateY(0) scale(1); }

        .testi-modal-close {
            position:absolute; top:14px; right:16px;
            background:none; border:none; font-size:1.4rem;
            color:#94a3b8; cursor:pointer; line-height:1;
            transition:color 0.2s, transform 0.2s;
        }
        .testi-modal-close:hover { color:#1e293b; transform:scale(1.15); }

        .testi-modal h3 {
            font-size:1.2rem; font-weight:800; color:#1e293b;
            margin-bottom:0.25rem;
        }
        .testi-modal .modal-sub { font-size:0.85rem; color:#64748b; margin-bottom:1.5rem; }

        .testi-form-label { font-size:0.82rem; font-weight:600; color:#374151; margin-bottom:0.3rem; display:block; }

        .testi-form-input, .testi-form-textarea {
            width:100%; border:1.5px solid #e2e8f0; border-radius:10px;
            padding:0.65rem 0.9rem; font-size:0.93rem; color:#1e293b;
            background:#f8fafc; transition:border-color 0.2s, box-shadow 0.2s;
            outline:none; font-family:inherit;
        }
        .testi-form-input:focus, .testi-form-textarea:focus {
            border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.1);
            background:#fff;
        }
        .testi-form-textarea { resize:vertical; min-height:100px; }
        .testi-char-count { font-size:0.72rem; color:#94a3b8; text-align:right; margin-top:3px; }

        /* Star rating interactive */
        .testi-star-row { display:flex; gap:6px; margin-bottom:1.25rem; }
        .testi-star-btn {
            background:none; border:none; cursor:pointer;
            font-size:1.6rem; line-height:1; padding:0;
            color:#e2e8f0; transition:color 0.15s, transform 0.15s;
        }
        .testi-star-btn.active, .testi-star-btn.hover { color:#f59e0b; }
        .testi-star-btn:hover { transform:scale(1.2); }

        .testi-submit-btn {
            width:100%; background:linear-gradient(135deg,#1d4ed8,#2563eb);
            color:#fff; border:none; border-radius:12px;
            padding:0.85rem; font-weight:700; font-size:0.97rem;
            cursor:pointer; margin-top:1rem;
            transition:all 0.25s; position:relative; overflow:hidden;
        }
        .testi-submit-btn:hover { transform:translateY(-2px); box-shadow:0 10px 24px rgba(29,78,216,0.35); }
        .testi-submit-btn:disabled { opacity:0.6; cursor:not-allowed; transform:none; }

        /* Badge "Nouveau" sur les cards DB */
        .testi-new-badge {
            position:absolute; top:14px; left:16px;
            font-size:0.62rem; font-weight:800; letter-spacing:0.5px;
            background:linear-gradient(135deg,#1d4ed8,#2563eb);
            color:#fff; padding:2px 8px; border-radius:20px;
        }

        /* Animation entrée nouvelle card */
        @keyframes testi-pop-in {
            0%   { opacity:0; transform:scale(0.88) translateY(20px); }
            70%  { transform:scale(1.03) translateY(-4px); }
            100% { opacity:1; transform:scale(1) translateY(0); }
        }
        .testi-card-new { animation:testi-pop-in 0.45s cubic-bezier(0.34,1.56,0.64,1) forwards; }

        /* Pricing cards */
        .pricing-card {
            transition: all 0.35s cubic-bezier(.4,0,.2,1);
            border: 1.5px solid #e5e7eb;
            background: #fff;
            border-radius: 1.25rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .pricing-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(59,130,246,0.13);
            border-color: #93c5fd;
        }

        .pricing-card.featured {
            border-color: #3b82f6;
            box-shadow: 0 16px 48px rgba(59,130,246,0.18);
            transform: translateY(-4px);
        }

        .pricing-card.featured:hover {
            transform: translateY(-12px);
            box-shadow: 0 28px 56px rgba(59,130,246,0.24);
        }

        .pricing-card.plan-free     { border-top: 4px solid #22c55e; }
        .pricing-card.plan-standard { border-top: 4px solid #3b82f6; }
        .pricing-card.plan-pro      { border-top: 4px solid #a855f7; }
        .pricing-card.plan-default  { border-top: 4px solid #64748b; }

        .plan-header {
            padding: 1.5rem 1.75rem 1rem;
        }

        .plan-price-block {
            padding: 0 1.75rem 1.25rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .plan-features {
            padding: 1.25rem 1.75rem;
            flex: 1;
        }

        .plan-feature-item {
            display: flex;
            align-items: flex-start;
            gap: 0.6rem;
            padding: 0.4rem 0;
            font-size: 0.88rem;
            color: #4b5563;
            line-height: 1.4;
        }

        .plan-feature-item.disabled {
            color: #9ca3af;
        }

        .plan-feature-icon {
            flex-shrink: 0;
            width: 16px;
            margin-top: 2px;
        }

        .plan-section-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            margin: 0.9rem 0 0.4rem;
        }

        .badge-ppu {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            background: linear-gradient(135deg, #fef9c3, #fde68a);
            color: #92400e;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 20px;
            white-space: nowrap;
            border: 1px solid #fcd34d;
        }

        .plan-badge-popular {
            position: absolute;
            top: -1px;
            right: 22px;
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 0 0 10px 10px;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            box-shadow: 0 4px 12px rgba(59,130,246,0.3);
        }

        .plan-btn {
            display: block;
            width: calc(100% - 3.5rem);
            margin: 0 1.75rem 1.75rem;
            padding: 0.7rem 1rem;
            text-align: center;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
            text-decoration: none;
        }

        /* FAQ accordion */
        .faq-item {
            background: #fff;
            border: 1.5px solid #e8edf5;
            border-radius: 14px;
            overflow: hidden;
            transition: box-shadow 0.25s ease, border-color 0.25s ease;
        }

        .faq-item:hover {
            border-color: #bfdbfe;
            box-shadow: 0 4px 20px rgba(59,130,246,0.08);
        }

        .faq-item.active {
            border-color: #93c5fd;
            box-shadow: 0 6px 24px rgba(59,130,246,0.12);
        }

        .faq-question {
            padding: 1.2rem 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 1rem;
            user-select: none;
        }

        .faq-icon-wrap {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #eff6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .faq-item.active .faq-icon-wrap {
            background: #2563eb;
        }

        .faq-item.active .faq-icon-wrap i {
            color: #fff !important;
        }

        .faq-question h3 {
            flex: 1;
            font-size: 0.97rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
            line-height: 1.4;
        }

        .faq-chevron {
            flex-shrink: 0;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease, background 0.2s;
        }

        .faq-item.active .faq-chevron {
            transform: rotate(180deg);
            background: #dbeafe;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.35s cubic-bezier(.4,0,.2,1);
        }

        .faq-item.active .faq-answer {
            max-height: 600px;
        }

        .faq-answer-inner {
            padding: 0 1.5rem 1.25rem 4.35rem;
            font-size: 0.9rem;
            color: #4b5563;
            line-height: 1.7;
            border-top: 1px dashed #e2e8f0;
            padding-top: 1rem;
        }

        .faq-category-label {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            margin-bottom: 1.25rem;
            margin-top: 0.5rem;
        }

        .faq-category-label::before,
        .faq-category-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* ══════════════════════════════════════════════
           Formulaire d'inscription — Design modernisé
        ══════════════════════════════════════════════ */

        /* Barre de progression */
        .reg-progress-track {
            height: 4px;
            background: #e2e8f0;
            border-radius: 99px;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .progress-bar { /* alias Bootstrap */
            height: 4px;
            background: #e2e8f0;
            border-radius: 99px;
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .progress {
            height: 100%;
            background: linear-gradient(90deg, #2563eb, #38bdf8);
            width: 0%;
            transition: width 0.5s cubic-bezier(.4,0,.2,1);
            border-radius: 99px;
        }

        /* Indicateur d'étapes */
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 2rem;
            gap: 0;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .step {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #94a3b8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.95rem;
            position: relative;
            transition: all 0.3s cubic-bezier(.4,0,.2,1);
            border: 2px solid #e2e8f0;
            z-index: 1;
        }

        .step.active {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.15);
        }

        .step.completed {
            background: #10b981;
            color: #fff;
            border-color: #10b981;
        }

        .step-label {
            font-size: 0.7rem;
            font-weight: 600;
            color: #94a3b8;
            white-space: nowrap;
        }

        .step-item.active .step-label  { color: #2563eb; }
        .step-item.completed .step-label { color: #10b981; }

        .step-connector {
            height: 2px;
            width: 60px;
            background: #e2e8f0;
            transition: background 0.4s ease;
            margin-bottom: 20px;
            flex-shrink: 0;
        }

        .step-connector.done { background: #10b981; }

        /* Étapes du formulaire */
        .step-form {
            display: none;
            animation: fadeInUp 0.4s ease;
        }
        .step-form.active { display: block; }

        @keyframes fadeInUp {
            from { opacity:0; transform:translateY(12px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* Titre d'étape */
        .step-form h3 {
            font-size: 1.1rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .step-form h3 .step-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #eff6ff;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 14px;
            flex-shrink: 0;
        }

        /* Cards type de compte */
        .account-type-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 480px) {
            .account-type-grid { grid-template-columns: 1fr; }
            .step-connector { width: 32px; }
        }

        .plan-card {
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 1.4rem 1.2rem;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(.4,0,.2,1);
            position: relative;
            background: #fff;
            text-align: center;
        }

        .plan-card:hover {
            border-color: #93c5fd;
            box-shadow: 0 4px 20px rgba(37,99,235,0.1);
            transform: translateY(-3px);
        }

        .plan-card.selected {
            border-color: #2563eb;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            box-shadow: 0 6px 24px rgba(37,99,235,0.15);
        }

        .plan-card .card-icon-wrap {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            font-size: 22px;
            transition: transform 0.2s;
        }

        .plan-card:hover .card-icon-wrap { transform: scale(1.08); }

        .plan-card h4 {
            font-size: 0.98rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .plan-card p {
            font-size: 0.78rem;
            color: #64748b;
            margin-bottom: 12px;
            line-height: 1.4;
        }

        .plan-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
            text-align: left;
        }

        .plan-card ul li {
            font-size: 0.78rem;
            color: #475569;
            padding: 3px 0;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .plan-card ul li::before {
            content: '✓';
            color: #10b981;
            font-weight: 800;
            font-size: 11px;
        }

        .plan-card.selected h4 { color: #1d4ed8; }

        .selected-check {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #2563eb;
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 11px;
        }

        .plan-card.selected .selected-check { display: flex; }

        /* Champs du formulaire */
        .reg-field {
            margin-bottom: 1rem;
        }

        .reg-field label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: #374151;
            margin-bottom: 5px;
        }

        .reg-field .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .reg-field .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }

        /* Section entreprise */
        .entreprise-section-card {
            background: #f8faff;
            border: 1.5px solid #dbeafe;
            border-radius: 14px;
            padding: 1.25rem;
            margin-top: 1rem;
        }

        .entreprise-section-card h5 {
            font-size: 0.85rem;
            font-weight: 800;
            color: #1e40af;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        /* Navigation du formulaire */
        .form-navigation {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1.75rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f1f5f9;
        }

        .btn-reg-prev {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 20px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            color: #64748b;
            font-weight: 600;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-reg-prev:hover { border-color: #94a3b8; color: #374151; }

        .btn-reg-next {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 10px 24px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(37,99,235,0.3);
        }

        .btn-reg-next:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(37,99,235,0.4); }
        .btn-reg-next:disabled { opacity: 0.5; cursor: not-allowed; box-shadow: none; }

        .btn-reg-submit {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 11px 28px;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff;
            font-weight: 700;
            font-size: 0.92rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(16,185,129,0.3);
        }

        .btn-reg-submit:hover:not(:disabled) { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(16,185,129,0.4); }
        .btn-reg-submit:disabled { opacity: 0.5; cursor: not-allowed; box-shadow: none; }

        /* Plan cards dans étape 3 */
        .plan-card.featured {
            border-color: #2563eb;
            position: relative;
        }

        .plan-card.featured::before {
            content: "Populaire";
            position: absolute;
            top: -11px;
            right: 16px;
            background: linear-gradient(135deg, #1d4ed8, #3b82f6);
            color: white;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.04em;
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
        
        /* ═══════════════════════════════════════════════════════
           DARK MODE — html[data-theme="dark"]
        ═══════════════════════════════════════════════════════ */

        /* Transition douce globale */
        *, *::before, *::after {
            transition: background-color 0.28s ease, color 0.18s ease,
                        border-color 0.28s ease, box-shadow 0.28s ease;
        }

        /* Bouton toggle thème */
        .theme-toggle-btn {
            display: inline-flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 50%;
            background: rgba(255,255,255,0.12); border: 1.5px solid rgba(255,255,255,0.22);
            color: #fff; cursor: pointer; font-size: 15px;
            transition: background 0.2s, transform 0.2s;
            flex-shrink: 0;
        }
        .theme-toggle-btn:hover { background: rgba(255,255,255,0.22); transform: rotate(20deg); }
        .nav-scrolled .theme-toggle-btn { background: #eff6ff; border-color: #bfdbfe; color: #2563eb; }

        /* Sélecteur de langue */
        .lang-switcher {
            display: inline-flex; align-items: center; gap: 0;
            background: rgba(255,255,255,0.1); border: 1.5px solid rgba(255,255,255,0.22);
            border-radius: 20px; overflow: hidden; flex-shrink: 0;
        }
        .lang-btn {
            padding: 5px 10px; font-size: 0.72rem; font-weight: 800;
            letter-spacing: 0.04em; cursor: pointer; border: none; background: transparent;
            color: rgba(255,255,255,0.55); transition: all 0.2s;
        }
        .lang-btn.active { background: rgba(255,255,255,0.22); color: #fff; }
        .nav-scrolled .lang-switcher { background: #eff6ff; border-color: #bfdbfe; }
        .nav-scrolled .lang-btn { color: #94a3b8; }
        .nav-scrolled .lang-btn.active { background: #2563eb; color: #fff; border-radius: 18px; }
        /* Mobile lang */
        .mob-lang-switcher {
            display: inline-flex; align-items: center; gap: 0;
            background: rgba(255,255,255,0.1); border: 1.5px solid rgba(255,255,255,0.2);
            border-radius: 20px; overflow: hidden;
        }
        .mob-lang-btn {
            padding: 5px 12px; font-size: 0.75rem; font-weight: 800;
            cursor: pointer; border: none; background: transparent;
            color: rgba(255,255,255,0.5); transition: all 0.2s;
        }
        .mob-lang-btn.active { background: rgba(255,255,255,0.2); color: #fff; }

        /* ─────────────────────────────────────────────────────
           DARK MODE
        ───────────────────────────────────────────────────── */
        html[data-theme="dark"] body { background: #0f172a; color: #e2e8f0; }

        /* Sections claires → fond sombre */
        html[data-theme="dark"] #fonctionnalites,
        html[data-theme="dark"] #sec-comment,
        html[data-theme="dark"] #sec-partenaires,
        html[data-theme="dark"] #biens,
        html[data-theme="dark"] #portfolio,
        html[data-theme="dark"] #temoignages,
        html[data-theme="dark"] #tarifs,
        html[data-theme="dark"] #sec-faq { background: #0f172a !important; }

        /* Textes Tailwind */
        html[data-theme="dark"] .text-gray-900,
        html[data-theme="dark"] .text-gray-800,
        html[data-theme="dark"] .text-gray-700 { color: #f1f5f9 !important; }
        html[data-theme="dark"] .text-gray-600,
        html[data-theme="dark"] .text-gray-500 { color: #94a3b8 !important; }
        html[data-theme="dark"] .bg-blue-100  { background: rgba(37,99,235,0.25) !important; }
        html[data-theme="dark"] .text-blue-700 { color: #93c5fd !important; }
        html[data-theme="dark"] .bg-white { background-color: #1e293b !important; }
        html[data-theme="dark"] .shadow-lg,
        html[data-theme="dark"] .shadow-xl,
        html[data-theme="dark"] .shadow-2xl { box-shadow: 0 4px 24px rgba(0,0,0,0.45) !important; }

        /* ── Bento fonctionnalités ── */
        html[data-theme="dark"] .bento-grid > div:not(.bento-card-large) {
            background: #1e293b !important;
            border-color: rgba(255,255,255,0.09) !important;
        }
        html[data-theme="dark"] .bento-grid > div > div[style*="border-radius:16px"] {
            background: #273549 !important;
            border-color: rgba(255,255,255,0.07) !important;
        }
        html[data-theme="dark"] .bento-grid h3,
        html[data-theme="dark"] .bento-grid p[style*="color:#1e293b"],
        html[data-theme="dark"] .bento-grid p[style*="color:#1e293b"] { color: #f1f5f9 !important; }
        html[data-theme="dark"] .bento-grid p[style*="color:#64748b"] { color: #94a3b8 !important; }
        html[data-theme="dark"] .bento-grid div[style*="background:#eff6ff"],
        html[data-theme="dark"] .bento-grid div[style*="background:#dcfce7"],
        html[data-theme="dark"] .bento-grid div[style*="background:#f3e8ff"],
        html[data-theme="dark"] .bento-grid div[style*="background:#ffedd5"],
        html[data-theme="dark"] .bento-grid div[style*="background:#ffe4e6"],
        html[data-theme="dark"] .bento-grid div[style*="background:#ccfbf1"] {
            background: rgba(255,255,255,0.1) !important;
        }

        /* ── Section "Comment ça marche" ── */
        html[data-theme="dark"] #sec-comment [style*="background:#fff"],
        html[data-theme="dark"] #sec-comment [style*="background: #fff"],
        html[data-theme="dark"] #sec-comment [style*="background:#f8faff"] {
            background: #1e293b !important;
            border-color: rgba(255,255,255,0.08) !important;
        }
        html[data-theme="dark"] #sec-comment [style*="color:#1e293b"],
        html[data-theme="dark"] #sec-comment h3 { color: #f1f5f9 !important; }
        html[data-theme="dark"] #sec-comment [style*="color:#64748b"],
        html[data-theme="dark"] #sec-comment [style*="color:#475569"] { color: #94a3b8 !important; }

        /* ── Cards portfolio & biens ── */
        html[data-theme="dark"] #portfolio .group.rounded-2xl,
        html[data-theme="dark"] #biens .group.rounded-2xl {
            background: #1e293b !important;
        }
        html[data-theme="dark"] #portfolio .text-gray-800,
        html[data-theme="dark"] #biens .text-gray-800 { color: #f1f5f9 !important; }
        html[data-theme="dark"] #portfolio .text-gray-500,
        html[data-theme="dark"] #portfolio .text-gray-600,
        html[data-theme="dark"] #biens .text-gray-500,
        html[data-theme="dark"] #biens .text-gray-600 { color: #94a3b8 !important; }
        html[data-theme="dark"] #biens .text-gray-800 { color: #f1f5f9 !important; }

        /* ── Biens — filtres (inputs/selects) ── */
        html[data-theme="dark"] #biens input[type="text"],
        html[data-theme="dark"] #biens input[type="number"],
        html[data-theme="dark"] #biens select {
            background: #0f172a !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        html[data-theme="dark"] #biens input::placeholder { color: #475569 !important; }
        html[data-theme="dark"] #biens input:focus,
        html[data-theme="dark"] #biens select:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 2px rgba(59,130,246,0.2) !important;
        }

        /* ── Témoignages cards ── */
        html[data-theme="dark"] .testi-card {
            background: #1e293b !important;
            border-color: rgba(255,255,255,0.08) !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.35) !important;
        }
        html[data-theme="dark"] .testi-name   { color: #f1f5f9 !important; }
        html[data-theme="dark"] .testi-role   { color: #94a3b8 !important; }
        html[data-theme="dark"] .testi-text   { color: #cbd5e1 !important; }
        html[data-theme="dark"] .testi-quote-mark { color: rgba(255,255,255,0.05) !important; }

        /* ── Plans tarifaires ── */
        html[data-theme="dark"] .plan-card {
            background: #1e293b !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        html[data-theme="dark"] .plan-card h4,
        html[data-theme="dark"] .plan-card .plan-name { color: #f1f5f9 !important; }
        html[data-theme="dark"] .plan-card p,
        html[data-theme="dark"] .plan-card ul li { color: #94a3b8 !important; }
        html[data-theme="dark"] .plan-card.selected {
            background: linear-gradient(135deg,#1e3a6e,#1e40af) !important;
            border-color: #3b82f6 !important;
        }
        html[data-theme="dark"] .plan-card.selected h4 { color: #93c5fd !important; }

        /* ── Pricing cards (#tarifs) ── */
        html[data-theme="dark"] .pricing-card {
            background: #1e293b !important;
            border-color: rgba(255,255,255,0.09) !important;
        }
        html[data-theme="dark"] .pricing-card:hover { border-color: rgba(96,165,250,0.4) !important; }
        html[data-theme="dark"] .pricing-card.featured { border-color: #3b82f6 !important; }
        html[data-theme="dark"] .pricing-card .plan-header {
            filter: brightness(0.55) saturate(0.6);
        }
        html[data-theme="dark"] .pricing-card h3[style*="color:#1e293b"] { color: #f1f5f9 !important; }
        html[data-theme="dark"] .pricing-card p[style*="color:#64748b"]  { color: #94a3b8 !important; }
        html[data-theme="dark"] .pricing-card [style*="color:#1e293b"]   { color: #f1f5f9 !important; }
        html[data-theme="dark"] .pricing-card [style*="color:#64748b"],
        html[data-theme="dark"] .pricing-card [style*="color:#475569"]   { color: #94a3b8 !important; }
        html[data-theme="dark"] .pricing-card [style*="color:#334155"]   { color: #94a3b8 !important; }
        html[data-theme="dark"] .pricing-card [style*="background:#f8fafc"],
        html[data-theme="dark"] .pricing-card [style*="background:#f1f5f9"],
        html[data-theme="dark"] .pricing-card [style*="background:#fff"] { background: #273549 !important; }
        html[data-theme="dark"] .pricing-card .plan-feature-item { color: #94a3b8 !important; }
        html[data-theme="dark"] .pricing-card .plan-feature-item.disabled { color: #475569 !important; }
        html[data-theme="dark"] .pricing-card [style*="border:1px solid #e5e7eb"],
        html[data-theme="dark"] .pricing-card [style*="border-color:#e5e7eb"] {
            border-color: rgba(255,255,255,0.08) !important;
        }
        /* Fix: texte bloc prix en mode sombre */
        html[data-theme="dark"] .pricing-card .plan-price-block { border-bottom-color: rgba(255,255,255,0.08) !important; }
        html[data-theme="dark"] .pricing-card .plan-price-block p { color: #94a3b8 !important; }
        html[data-theme="dark"] .pricing-card .plan-price-block p strong { color: #e2e8f0 !important; }
        html[data-theme="dark"] .pricing-card .plan-price-block p span { color: #94a3b8 !important; }
        html[data-theme="dark"] .pricing-card .plan-price-block > div > span[style*="color:#94a3b8"] { color: #94a3b8 !important; }
        html[data-theme="dark"] #plans-container .plan-card small { color: #94a3b8 !important; }

        /* ── FAQ — COMPLET ── */
        html[data-theme="dark"] .faq-item {
            background: #1e293b !important;
            border-color: rgba(255,255,255,0.08) !important;
        }
        html[data-theme="dark"] .faq-item:hover { border-color: rgba(96,165,250,0.4) !important; }
        html[data-theme="dark"] .faq-item.active { border-color: rgba(96,165,250,0.6) !important; }
        html[data-theme="dark"] .faq-question h3 { color: #f1f5f9 !important; }
        html[data-theme="dark"] .faq-icon-wrap   { background: rgba(37,99,235,0.2) !important; }
        html[data-theme="dark"] .faq-chevron     { background: rgba(255,255,255,0.08) !important; }
        html[data-theme="dark"] .faq-item.active .faq-chevron { background: rgba(37,99,235,0.3) !important; }
        html[data-theme="dark"] .faq-answer-inner {
            color: #94a3b8 !important;
            border-top-color: rgba(255,255,255,0.08) !important;
        }
        html[data-theme="dark"] .faq-answer-inner strong { color: #cbd5e1 !important; }
        /* Séparateurs de catégorie FAQ */
        html[data-theme="dark"] #sec-faq [style*="background:#e2e8f0"] { background: #334155 !important; }
        html[data-theme="dark"] #sec-faq [style*="color:#94a3b8"] { color: #64748b !important; }

        /* ── Partenaires ── */
        html[data-theme="dark"] #sec-partenaires [style*="background:#fff"],
        html[data-theme="dark"] #sec-partenaires [style*="background: #fff"] {
            background: #1e293b !important;
            border-color: rgba(255,255,255,0.08) !important;
        }

        /* ── Formulaire de création de compte (#compte) ── */
        /* Carte blanche du formulaire */
        html[data-theme="dark"] #compte [style*="background:#fff"] {
            background: #1e293b !important;
            box-shadow: 0 25px 60px rgba(0,0,0,0.6) !important;
        }

        /* Barre de progression */
        html[data-theme="dark"] .progress-bar { background: #334155 !important; }

        /* Pastilles d'étapes */
        html[data-theme="dark"] .step {
            background: #334155 !important;
            border-color: #475569 !important;
            color: #94a3b8 !important;
        }
        html[data-theme="dark"] .step.active {
            background: #2563eb !important;
            border-color: #2563eb !important;
            color: #fff !important;
        }
        html[data-theme="dark"] .step.completed {
            background: #10b981 !important;
            border-color: #10b981 !important;
            color: #fff !important;
        }
        html[data-theme="dark"] .step-label   { color: #64748b !important; }
        html[data-theme="dark"] .step-item.active .step-label   { color: #60a5fa !important; }
        html[data-theme="dark"] .step-item.completed .step-label { color: #34d399 !important; }
        html[data-theme="dark"] .step-connector { background: #334155 !important; }
        html[data-theme="dark"] .step-connector.done { background: #10b981 !important; }

        /* Titres d'étapes */
        html[data-theme="dark"] .step-form h3 { color: #f1f5f9 !important; }
        html[data-theme="dark"] .step-form h3 .step-icon {
            background: rgba(37,99,235,0.2) !important;
            color: #60a5fa !important;
        }

        /* Cards type de compte (plan-card dans #compte) */
        html[data-theme="dark"] #compte .plan-card {
            background: #273549 !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        html[data-theme="dark"] #compte .plan-card h4 { color: #f1f5f9 !important; }
        html[data-theme="dark"] #compte .plan-card p  { color: #94a3b8 !important; }
        html[data-theme="dark"] #compte .plan-card ul li { color: #94a3b8 !important; }
        html[data-theme="dark"] #compte .plan-card.selected {
            background: linear-gradient(135deg,#1e3a6e,#1e40af) !important;
            border-color: #3b82f6 !important;
        }
        html[data-theme="dark"] #compte .plan-card .card-icon-wrap {
            filter: brightness(0.75) saturate(0.7);
        }

        /* Champs de formulaire */
        html[data-theme="dark"] .reg-field label { color: #cbd5e1 !important; }
        html[data-theme="dark"] .reg-field .form-control {
            background: #0f172a !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }

        /* Sélecteur de durée d'abonnement */
        html[data-theme="dark"] #nb-mois-container {
            background: #0f172a !important;
            border-color: #1e40af !important;
        }
        html[data-theme="dark"] #nb-mois-container label { color: #93c5fd !important; }
        html[data-theme="dark"] #nb-mois-container span  { color: #94a3b8 !important; }
        html[data-theme="dark"] #nb-mois-container #nb_mois_reg {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        html[data-theme="dark"] #nb-mois-container #total-preview-reg { color: #93c5fd !important; }
        html[data-theme="dark"] #nb-mois-container p { color: #94a3b8 !important; }
        html[data-theme="dark"] #nb-mois-container strong { color: #f1f5f9 !important; }
        html[data-theme="dark"] .reg-field .form-control::placeholder { color: #475569 !important; }
        html[data-theme="dark"] .reg-field .form-control:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2) !important;
        }

        /* Section entreprise */
        html[data-theme="dark"] .entreprise-section-card {
            background: #162032 !important;
            border-color: rgba(59,130,246,0.25) !important;
        }
        html[data-theme="dark"] .entreprise-section-card h5 { color: #60a5fa !important; }

        /* Bloc conditions */
        html[data-theme="dark"] #compte [style*="background:#f8faff"] {
            background: #162032 !important;
            border-color: rgba(59,130,246,0.25) !important;
        }
        html[data-theme="dark"] #compte .form-check-label { color: #94a3b8 !important; }

        /* Séparateur form-navigation */
        html[data-theme="dark"] .form-navigation { border-top-color: #334155 !important; }

        /* Boutons de navigation */
        html[data-theme="dark"] .btn-reg-prev {
            background: #1e293b !important;
            border-color: #475569 !important;
            color: #94a3b8 !important;
        }
        html[data-theme="dark"] .btn-reg-prev:hover {
            border-color: #64748b !important;
            color: #f1f5f9 !important;
        }

        /* Select & intl-tel-input dans #compte */
        html[data-theme="dark"] #compte select.form-control,
        html[data-theme="dark"] #compte select,
        html[data-theme="dark"] #compte .iti__flag-container,
        html[data-theme="dark"] #compte .iti__selected-flag {
            background: #0f172a !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        html[data-theme="dark"] #compte .iti__country-list {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        html[data-theme="dark"] #compte .iti__country:hover,
        html[data-theme="dark"] #compte .iti__country.iti__highlight {
            background: #273549 !important;
        }
        /* Plans dynamiques chargés en JS (étape 3) */
        html[data-theme="dark"] #plans-container .plan-card {
            background: #273549 !important;
            border-color: rgba(255,255,255,0.1) !important;
        }
        html[data-theme="dark"] #plans-container .plan-card h4 { color: #f1f5f9 !important; }
        html[data-theme="dark"] #plans-container .plan-card p  { color: #94a3b8 !important; }
        html[data-theme="dark"] #plans-container .plan-card ul li { color: #94a3b8 !important; }

        /* ════════════════════════════════════════════════════
           FOOTER — thème clair / sombre
        ════════════════════════════════════════════════════ */

        /* ── MODE SOMBRE : fond très sombre, textes clairs ── */
        html[data-theme="dark"] .footer-main {
            background: #060c18 !important;
        }
        /* Textes inline en gris foncé → rendus visibles */
        html[data-theme="dark"] .footer-main [style*="color:#475569"] { color: #94a3b8 !important; }
        html[data-theme="dark"] .footer-main [style*="color:#334155"] { color: #64748b  !important; }
        html[data-theme="dark"] .footer-main [style*="color:#64748b"] { color: #94a3b8  !important; }
        /* Logo "Lokativ" : reste blanc ✓ */
        /* Liens */
        html[data-theme="dark"] .footer-link       { color: #64748b !important; }
        html[data-theme="dark"] .footer-link:hover { color: #93c5fd !important; }
        /* Badge sécurité : rgba(255,255,255,x) déjà ok sur fond sombre ✓ */
        /* Newsletter input : déjà ok sur fond sombre ✓ */
        /* Séparateur grille */
        html[data-theme="dark"] .footer-grid { border-bottom-color: rgba(255,255,255,0.06) !important; }
        /* Copyright */
        html[data-theme="dark"] .footer-copyright p { color: #64748b !important; }
        html[data-theme="dark"] .footer-copyright strong { color: #94a3b8 !important; }
        /* Icônes paiement */
        html[data-theme="dark"] .footer-copyright i.fab { color: #64748b !important; }
        /* Badge "Mobile Money" */
        html[data-theme="dark"] .footer-copyright [style*="background:rgba(255,255,255,0.06)"] {
            background: rgba(255,255,255,0.06) !important;
            color: #64748b !important;
        }

        /* ── MODE CLAIR : fond clair, textes lisibles ── */
        html[data-theme="light"] .footer-main {
            background: #f1f5f9 !important;
        }
        /* Logo "Lokativ" et titres blancs → deveint sombres */
        html[data-theme="light"] .footer-main span[style*="color:#fff"],
        html[data-theme="light"] .footer-main p[style*="color:#fff"],
        html[data-theme="light"] .footer-main h4[style*="color:#fff"] {
            color: #1e293b !important;
        }
        /* Textes gris : déjà lisibles sur fond clair ✓ */
        /* Liens */
        html[data-theme="light"] .footer-link       { color: #64748b !important; }
        html[data-theme="light"] .footer-link:hover { color: #2563eb !important; }
        /* Badge sécurité : rgba(white) → fond light */
        html[data-theme="light"] .footer-main [style*="background:rgba(255,255,255,0.04)"] {
            background: #e2e8f0 !important;
            border-color: #cbd5e1 !important;
        }
        /* Séparateur grille */
        html[data-theme="light"] .footer-grid { border-bottom-color: #e2e8f0 !important; }
        /* Newsletter input */
        html[data-theme="light"] .footer-newsletter input {
            background: #fff !important;
            border-color: #e2e8f0 !important;
            color: #1e293b !important;
        }
        html[data-theme="light"] .footer-newsletter input::placeholder { color: #94a3b8 !important; }
        /* Icônes sociales */
        html[data-theme="light"] .footer-social { filter: brightness(0.8) saturate(1.2); }
        /* Copyright */
        html[data-theme="light"] .footer-copyright [style*="color:#334155"] { color: #475569 !important; }
        html[data-theme="light"] .footer-copyright i.fab { color: #94a3b8 !important; }
        /* Badge "Mobile Money" */
        html[data-theme="light"] .footer-copyright [style*="background:rgba(255,255,255,0.06)"] {
            background: #e2e8f0 !important;
            color: #475569 !important;
        }

        /* ── Header nav-scrolled en mode sombre ── */
        html[data-theme="dark"] .nav-scrolled {
            background: rgba(15,23,42,0.97) !important;
            box-shadow: 0 1px 0 rgba(255,255,255,0.07), 0 8px 30px rgba(0,0,0,0.45) !important;
        }
        html[data-theme="dark"] .nav-scrolled .nav-link { color: #cbd5e1 !important; }
        html[data-theme="dark"] .nav-scrolled .nav-link:hover { color: #93c5fd !important; }
        html[data-theme="dark"] .nav-scrolled .nav-link::after { background: #60a5fa !important; }
        html[data-theme="dark"] .nav-scrolled .logo-text { color: #93c5fd !important; -webkit-text-fill-color: #93c5fd !important; }
        html[data-theme="dark"] .nav-scrolled .logo-icon { background: linear-gradient(135deg,#1e40af,#3b82f6) !important; }
        html[data-theme="dark"] .nav-scrolled .hamburger span { background-color: #93c5fd !important; }
        html[data-theme="dark"] .nav-scrolled .header-btn-login { color: #93c5fd !important; background: rgba(37,99,235,0.15) !important; border-color: rgba(96,165,250,0.35) !important; }
        html[data-theme="dark"] .nav-scrolled .header-btn-register { background: linear-gradient(135deg,#1d4ed8,#2563eb) !important; color: #fff !important; box-shadow: 0 4px 14px rgba(37,99,235,0.4) !important; }
        html[data-theme="dark"] .nav-scrolled .theme-toggle-btn { background: rgba(37,99,235,0.2) !important; border-color: rgba(96,165,250,0.35) !important; color: #93c5fd !important; }
        html[data-theme="dark"] .nav-scrolled .lang-switcher { background: rgba(37,99,235,0.15) !important; border-color: rgba(96,165,250,0.3) !important; }
        html[data-theme="dark"] .nav-scrolled .lang-btn { color: #64748b !important; }
        html[data-theme="dark"] .nav-scrolled .lang-btn.active { background: #2563eb !important; color: #fff !important; }

        /* ── Aperçu plateforme — tabs ── */
        html[data-theme="dark"] .ap-tab {
            background: #1e293b !important;
            border-color: rgba(255,255,255,0.1) !important;
            color: #94a3b8 !important;
        }
        html[data-theme="dark"] .ap-tab:hover { border-color: #60a5fa !important; color: #93c5fd !important; background: rgba(37,99,235,0.15) !important; }
        html[data-theme="dark"] .ap-tab.active { background: #2563eb !important; color: #fff !important; border-color: #2563eb !important; }

        /* ── Aperçu plateforme — mockup wrapper ── */
        html[data-theme="dark"] .ap-screen-wrap {
            background: #1e293b !important;
            border-color: rgba(255,255,255,0.09) !important;
            box-shadow: 0 20px 60px rgba(0,0,0,0.5) !important;
        }
        html[data-theme="dark"] .ap-browser-bar {
            background: #162032 !important;
            border-bottom-color: rgba(255,255,255,0.07) !important;
        }
        html[data-theme="dark"] .ap-browser-url {
            background: #0f172a !important;
            border-color: rgba(255,255,255,0.1) !important;
            color: #64748b !important;
        }
        html[data-theme="dark"] .ap-screen-inner { background: #1e293b !important; }

        /* ── Aperçu plateforme — mock UI elements ── */
        html[data-theme="dark"] .mock-card {
            background: #273549 !important;
            border-color: rgba(255,255,255,0.08) !important;
        }
        html[data-theme="dark"] .mock-card .mc-value { color: #f1f5f9 !important; }
        html[data-theme="dark"] .mock-title { color: #f1f5f9 !important; }
        html[data-theme="dark"] .mock-list-item {
            background: #273549 !important;
            border-color: rgba(255,255,255,0.06) !important;
        }
        html[data-theme="dark"] .mock-list-text .t1 { color: #e2e8f0 !important; }
        html[data-theme="dark"] .mock-progress { background: #334155 !important; }

        /* ── Contact form — inputs ── */
        html[data-theme="dark"] .contact-field {
            background: #1e293b !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        html[data-theme="dark"] .contact-field:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2) !important;
        }
        html[data-theme="dark"] .contact-field::placeholder { color: #475569 !important; }
        html[data-theme="dark"] .contact-label { color: #cbd5e1 !important; }

        /* ── Select option (global) ── */
        html[data-theme="dark"] select option {
            background: #1e293b;
            color: #f1f5f9;
        }

        /* ── Bouton "Partager votre avis" ── */
        html[data-theme="dark"] #openTestiModal {
            background: #1e293b !important;
            border-color: #3b82f6 !important;
            color: #60a5fa !important;
        }
        html[data-theme="dark"] #openTestiModal:hover {
            background: rgba(37,99,235,0.2) !important;
            color: #93c5fd !important;
        }

        /* ── Modal témoignage ── */
        html[data-theme="dark"] .testi-modal {
            background: #1e293b !important;
            color: #e2e8f0 !important;
        }
        html[data-theme="dark"] .testi-modal h3 { color: #f1f5f9 !important; }
        html[data-theme="dark"] .testi-modal .modal-sub { color: #94a3b8 !important; }
        html[data-theme="dark"] .testi-modal-close { color: #64748b !important; }
        html[data-theme="dark"] .testi-modal-close:hover { color: #f1f5f9 !important; }
        html[data-theme="dark"] .testi-form-label { color: #cbd5e1 !important; }
        html[data-theme="dark"] .testi-form-input,
        html[data-theme="dark"] .testi-form-textarea {
            background: #0f172a !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        html[data-theme="dark"] .testi-form-input:focus,
        html[data-theme="dark"] .testi-form-textarea:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.2) !important;
            background: #162032 !important;
        }
        html[data-theme="dark"] .testi-form-input::placeholder,
        html[data-theme="dark"] .testi-form-textarea::placeholder { color: #475569 !important; }
        html[data-theme="dark"] .testi-star-btn { color: #334155 !important; }
        html[data-theme="dark"] .testi-star-btn.active,
        html[data-theme="dark"] .testi-star-btn.hover { color: #f59e0b !important; }
        html[data-theme="dark"] .testi-char-count { color: #64748b !important; }

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
        .testimonial-card { transition: all 0.3s ease; }
        .testimonial-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }

        /* ── CONTACT ───────────────────────────── */
        .contact-info-card {
            display: flex; align-items: flex-start; gap: 14px;
            padding: 16px 18px; border-radius: 14px;
            background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1);
            transition: background 0.2s;
        }
        .contact-info-card:hover { background: rgba(255,255,255,0.12); }
        .contact-icon-wrap {
            width: 42px; height: 42px; border-radius: 11px; flex-shrink: 0;
            display: flex; align-items: center; justify-content: center; font-size: 16px;
        }
        .contact-social-btn {
            width: 38px; height: 38px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; text-decoration: none;
            transition: all 0.22s; border: 1px solid rgba(255,255,255,0.15);
            background: rgba(255,255,255,0.08); color: rgba(255,255,255,0.7);
        }
        .contact-social-btn:hover { transform: translateY(-3px); color: #fff; background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.35); }

        .contact-field {
            width: 100%; padding: 11px 14px; border-radius: 10px; font-size: 0.875rem;
            border: 1.5px solid #e2e8f0; background: #fff; color: #1e293b;
            outline: none; transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
        }
        .contact-field:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .contact-field::placeholder { color: #94a3b8; }
        .contact-label { display: block; font-size: 0.78rem; font-weight: 700; color: #475569; margin-bottom: 5px; }

        /* ── CTA FINALE ─────────────────────────── */
        .cta-section {
            position: relative; overflow: hidden;
            background: linear-gradient(135deg, #0c1445 0%, #0f2878 45%, #1d4ed8 100%);
        }
        .cta-section::before {
            content: ''; position: absolute; inset: 0;
            background: radial-gradient(ellipse 70% 60% at 80% 50%, rgba(99,102,241,0.2) 0%, transparent 65%),
                        radial-gradient(ellipse 50% 50% at 15% 70%, rgba(59,130,246,0.15) 0%, transparent 55%);
            pointer-events: none;
        }
        .cta-orb {
            position: absolute; border-radius: 50%; filter: blur(60px); pointer-events: none;
        }
        .cta-btn-primary {
            display: inline-flex; align-items: center; gap: 9px;
            padding: 14px 32px; border-radius: 50px; font-weight: 800; font-size: 0.95rem;
            color: #1e3a8a; background: #fff; text-decoration: none;
            box-shadow: 0 6px 28px rgba(0,0,0,0.22); transition: all 0.25s;
        }
        .cta-btn-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 36px rgba(0,0,0,0.28); color: #1d4ed8; }
        .cta-btn-secondary {
            display: inline-flex; align-items: center; gap: 9px;
            padding: 14px 30px; border-radius: 50px; font-weight: 700; font-size: 0.95rem;
            color: #fff; background: rgba(255,255,255,0.12);
            border: 1.5px solid rgba(255,255,255,0.28); text-decoration: none;
            transition: all 0.25s;
        }
        .cta-btn-secondary:hover { background: rgba(255,255,255,0.22); transform: translateY(-2px); }

        /* ── FOOTER ──────────────────────────────── */
        .footer-main { background: #080f1e; }
        .footer-link {
            display: flex; align-items: center; gap: 7px;
            color: #64748b; text-decoration: none; font-size: 0.83rem; font-weight: 500;
            padding: 3px 0; transition: color 0.2s, padding-left 0.2s;
        }
        .footer-link:hover { color: #cbd5e1; padding-left: 4px; }
        .footer-link i { font-size: 0.65rem; opacity: 0; transition: opacity 0.2s; }
        .footer-link:hover i { opacity: 1; }
        .footer-social {
            width: 36px; height: 36px; border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; text-decoration: none; transition: all 0.2s;
            border: 1px solid rgba(255,255,255,0.08);
        }
        .footer-social:hover { transform: translateY(-3px); }

        /* ═══════════════════════════════════════════
           RESPONSIVE — BREAKPOINTS GLOBAUX
        ═══════════════════════════════════════════ */

        /* ── Tablet large (≤ 1024px) ── */
        @media (max-width: 1023px) {
            .footer-grid { grid-template-columns: 1fr 1fr !important; gap: 32px !important; }
            .contact-grid { grid-template-columns: 1fr !important; }
            .bento-grid  { grid-template-columns: 1fr 1fr !important; }
            .bento-card-large { grid-column: 1 / -1 !important; grid-row: auto !important; min-height: 220px !important; }
            .bento-card-bottom { grid-column: auto !important; grid-row: auto !important; }
            .stats-grid  { grid-template-columns: repeat(2, 1fr) !important; }
            .how-grid    { grid-template-columns: repeat(2, 1fr) !important; }
        }

        /* ── Tablet (≤ 768px) ── */
        @media (max-width: 768px) {
            /* Nav */
            .nav-glass, .nav-scrolled { padding-left: 0; padding-right: 0; }

            /* Hero */
            .hero-grid-inner { grid-template-columns: 1fr !important; gap: 32px !important; text-align: center; }
            .hero-card-outer { display: none !important; }
            .hero-stat-row   { justify-content: center; }
            .hero-badge      { font-size: 0.72rem; }

            /* Bento fonctionnalités */
            .bento-grid { grid-template-columns: 1fr !important; }
            .bento-card-large { grid-column: 1 !important; grid-row: auto !important; }

            /* Comment ça marche */
            .how-grid    { grid-template-columns: 1fr !important; }
            .how-connector { display: none !important; }

            /* Stats */
            .stats-grid  { grid-template-columns: repeat(2, 1fr) !important; }

            /* Aperçu plateforme */
            .ap-tabs { gap: 6px; }
            .ap-tab  { padding: 7px 12px; font-size: 0.75rem; }
            .mock-cards-row { grid-template-columns: repeat(2, 1fr) !important; }

            /* Contact */
            .contact-grid { grid-template-columns: 1fr !important; }
            .contact-form-row { grid-template-columns: 1fr !important; }

            /* CTA finale */
            .cta-section { padding: 4rem 0 !important; }

            /* Footer */
            .footer-grid { grid-template-columns: 1fr 1fr !important; gap: 28px !important; }
        }

        /* ── Mobile (≤ 640px) ── */
        @media (max-width: 640px) {
            /* Hero */
            .hero-stat { font-size: 0.7rem; padding: 5px 9px; }

            /* Stats */
            .stats-grid { grid-template-columns: 1fr 1fr !important; }

            /* Aperçu */
            .mock-cards-row { grid-template-columns: 1fr 1fr !important; }
            .ap-screen-inner { padding: 16px 12px; }

            /* Témoignages */
            .testi-card { padding: 22px 18px 18px; }

            /* CTA proofs row */
            .cta-proofs { gap: 12px !important; }

            /* Footer */
            .footer-grid { grid-template-columns: 1fr !important; gap: 28px !important; }
            .footer-copyright { flex-direction: column; align-items: flex-start !important; gap: 10px !important; }
        }

        /* ── Petit mobile (≤ 480px) ── */
        @media (max-width: 480px) {
            /* Hero headline */
            .hero-grid-inner h1 { font-size: 1.75rem !important; }

            /* Section headings globaux */
            section h2 { font-size: 1.6rem !important; }

            /* Bento */
            .bento-grid { gap: 10px !important; }

            /* Stats: 2 colonnes reste, texte plus petit */
            .stats-grid { grid-template-columns: 1fr 1fr !important; }

            /* Tabs aperçu: wrap compact */
            .ap-tabs { gap: 5px; }
            .ap-tab  { padding: 6px 10px; font-size: 0.7rem; }

            /* Mock cards 2 colonnes sur 480 */
            .mock-cards-row { grid-template-columns: 1fr 1fr !important; }

            /* Contact form Nom/Prénom */
            .contact-form-row { grid-template-columns: 1fr !important; }

            /* CTA buttons pleine largeur */
            .cta-btn-primary, .cta-btn-secondary { width: 100%; justify-content: center; }

            /* Footer newsletter */
            .footer-newsletter { flex-direction: column !important; }
            .footer-newsletter input { width: 100% !important; }

            /* How steps: 1 colonne */
            .how-grid { grid-template-columns: 1fr !important; gap: 24px !important; }
        }

        /* ── Responsive utilitaires globaux ── */
        @media (max-width: 768px) {
            /* Paddings de sections réduits sur mobile */
            section { padding-top: 3.5rem !important; padding-bottom: 3.5rem !important; }

            /* Formulaire : champs pleine hauteur */
            .contact-field { padding: 10px 12px; }

            /* "Comment ça marche" : colonne avec texte centré */
            .how-grid { gap: 28px !important; }
            .how-grid > div { padding: 0 0.5rem !important; }

            /* Aperçu mock : réduire min-height */
            .ap-screen-inner { min-height: 260px !important; }

            /* Partenaires : réduire vitesse défilement */
            .marquee-track { animation-duration: 25s !important; }
        }
    </style>
</head>

<body class="overflow-x-hidden font-sans text-gray-800">

    <!-- Navigation -->
    <nav id="main-header" class="fixed z-50 w-full nav-glass" aria-label="Navigation principale">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between" style="height:64px;">

                <!-- Logo -->
                <a href="/" class="flex items-center gap-2.5" aria-label="Lokativ - Retour à l'accueil" style="text-decoration:none;">
                    <div class="logo-icon flex items-center justify-center rounded-xl" style="width:36px;height:36px;background:linear-gradient(135deg,rgba(255,255,255,0.25),rgba(255,255,255,0.1));border:1px solid rgba(255,255,255,0.25);flex-shrink:0;">
                        <i class="fas fa-home" style="font-size:15px;color:#fff;"></i>
                    </div>
                    <span class="logo-text font-extrabold text-white" style="font-size:1.3rem;letter-spacing:-0.02em;">Lokativ</span>
                </a>

                <!-- Desktop links -->
                <div class="items-center hidden gap-7 md:flex">
                    <a href="#accueil" class="text-white nav-link" style="color:rgba(255,255,255,0.88);" data-i18n="nav.home">Accueil</a>
                    <a href="#fonctionnalites" class="nav-link" style="color:rgba(255,255,255,0.88);" data-i18n="nav.features">Fonctionnalités</a>
                    <a href="#biens" class="nav-link" style="color:rgba(255,255,255,0.88);">Annonces</a>
                    <a href="#portfolio" class="nav-link" style="color:rgba(255,255,255,0.88);" data-i18n="nav.preview">Aperçu</a>
                    <a href="#tarifs" class="nav-link" style="color:rgba(255,255,255,0.88);" data-i18n="nav.pricing">Tarifs</a>
                    <a href="#contact" class="nav-link" style="color:rgba(255,255,255,0.88);" data-i18n="nav.contact">Contact</a>
                </div>

                <!-- CTA buttons + toggle thème + langue -->
                <div class="items-center hidden gap-3 md:flex">
                    <div class="lang-switcher" role="group" aria-label="Langue">
                        <button class="lang-btn active" data-lang="fr" onclick="setLang('fr')">FR</button>
                        <button class="lang-btn" data-lang="en" onclick="setLang('en')">EN</button>
                    </div>
                    <button id="theme-toggle-desktop" class="theme-toggle-btn" aria-label="Changer le thème" title="Thème clair / sombre">
                        <i class="fas fa-moon" id="theme-icon-desktop"></i>
                    </button>
                    <a href="{{ route('login') }}" class="header-btn-login">
                        <i class="fas fa-sign-in-alt" style="font-size:11px;"></i> <span data-i18n="nav.login">Se connecter</span>
                    </a>
                    <a href="#compte" class="header-btn-register">
                        <i class="fas fa-rocket" style="font-size:11px;"></i> <span data-i18n="nav.start">Démarrer</span>
                    </a>
                </div>

                <!-- Hamburger mobile -->
                <button id="mobile-menu-btn" class="md:hidden" aria-label="Ouvrir le menu" style="background:none;border:none;padding:4px;cursor:pointer;">
                    <div class="hamburger" id="hamburger">
                        <span></span><span></span><span></span>
                    </div>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Overlay -->
    <div class="mobile-menu-overlay" id="mobile-menu-overlay"></div>

    <!-- Mobile Panel -->
    <div class="mobile-menu-panel" id="mobile-menu-panel">
        <!-- Header panel -->
        <div class="mob-panel-header">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;border-radius:9px;background:linear-gradient(135deg,#1d4ed8,#3b82f6);display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-home" style="font-size:13px;color:#fff;"></i>
                </div>
                <span style="font-weight:800;color:#fff;font-size:1.1rem;letter-spacing:-0.02em;">Lokativ</span>
            </div>
            <button class="mobile-menu-close" id="mobile-menu-close" aria-label="Fermer le menu">
                <i class="fas fa-times" style="font-size:12px;"></i>
            </button>
        </div>

        <!-- Links -->
        <div style="flex:1;padding:8px 0;">
            <a href="#accueil" class="mob-nav-link"><i class="fas fa-home"></i> <span data-i18n="nav.home">Accueil</span></a>
            <a href="#fonctionnalites" class="mob-nav-link"><i class="fas fa-bolt"></i> <span data-i18n="nav.features">Fonctionnalités</span></a>
            <a href="#biens" class="mob-nav-link"><i class="fas fa-building"></i> <span>Annonces</span></a>
            <a href="#portfolio" class="mob-nav-link"><i class="fas fa-desktop"></i> <span data-i18n="nav.preview">Aperçu</span></a>
            <a href="#tarifs" class="mob-nav-link"><i class="fas fa-tags"></i> <span data-i18n="nav.pricing">Tarifs</span></a>
            <a href="#contact" class="mob-nav-link"><i class="fas fa-envelope"></i> <span data-i18n="nav.contact">Contact</span></a>
        </div>

        <!-- Toggle langue mobile -->
        <div style="padding:0.75rem 1.5rem;border-top:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.85rem;color:rgba(255,255,255,0.75);font-weight:600;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-globe" style="font-size:13px;color:#93c5fd;"></i> <span data-i18n="mob.language">Langue</span>
            </span>
            <div class="mob-lang-switcher">
                <button class="mob-lang-btn active" data-lang="fr" onclick="setLang('fr')">FR</button>
                <button class="mob-lang-btn" data-lang="en" onclick="setLang('en')">EN</button>
            </div>
        </div>

        <!-- Toggle thème -->
        <div class="mob-panel-theme-row" style="padding:0.75rem 1.5rem;border-top:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.85rem;color:rgba(255,255,255,0.75);font-weight:600;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-palette" style="font-size:13px;color:#93c5fd;"></i> Préférence d'affichage
            </span>
            <div style="display:flex;align-items:center;gap:8px;">
                <span id="theme-label-mob" style="font-size:0.75rem;color:rgba(255,255,255,0.55);">Clair</span>
                <button id="theme-toggle-mobile"
                        style="width:44px;height:24px;border-radius:12px;border:none;cursor:pointer;position:relative;
                               background:rgba(255,255,255,0.15);transition:background 0.3s;flex-shrink:0;"
                        aria-label="Changer le thème">
                    <span id="theme-toggle-knob"
                          style="position:absolute;top:3px;left:3px;width:18px;height:18px;border-radius:50%;
                                 background:#fff;transition:transform 0.3s;display:flex;align-items:center;justify-content:center;font-size:10px;">
                        ☀️
                    </span>
                </button>
            </div>
        </div>

        <!-- CTA -->
        <div style="padding:1.25rem 1.5rem;border-top:1px solid rgba(255,255,255,0.07);display:flex;flex-direction:column;gap:10px;">
            <a href="{{ route('login') }}" style="display:block;text-align:center;padding:12px;border-radius:50px;font-weight:700;font-size:0.9rem;color:#1e40af;background:#fff;text-decoration:none;">
                <i class="fas fa-sign-in-alt" style="margin-right:6px;font-size:12px;"></i>Se connecter
            </a>
            <a href="#compte" style="display:block;text-align:center;padding:12px;border-radius:50px;font-weight:700;font-size:0.9rem;color:#fff;background:linear-gradient(135deg,#1d4ed8,#2563eb);text-decoration:none;box-shadow:0 4px 14px rgba(37,99,235,0.35);">
                <i class="fas fa-rocket" style="margin-right:6px;font-size:12px;"></i>Démarrer gratuitement
            </a>
        </div>
    </div>

    <!-- Hero Section -->
    <section id="accueil" class="hero-section" style="min-height:100dvh;display:flex;align-items:center;">
        <canvas id="hero-canvas" aria-hidden="true"></canvas>

        <div class="relative z-10 w-full px-4 mx-auto max-w-7xl sm:px-6 lg:px-8" style="padding-top:96px;padding-bottom:80px;">
            <div class="hero-grid-inner" style="display:grid;grid-template-columns:1fr 1fr;gap:56px;align-items:center;">

                <!-- LEFT — texte -->
                <div style="color:#fff;">
                    <!-- Badge -->
                    <div class="hero-badge">
                        <span class="hero-badge-dot"></span>
                        <span data-i18n="hero.badge">Plateforme de gestion immobilière</span>
                        <span style="background:rgba(74,222,128,0.2);color:#4ade80;border-radius:20px;padding:1px 8px;font-size:0.7rem;" data-i18n="hero.new">Nouveau</span>
                    </div>

                    <!-- Headline -->
                    <h1 style="font-size:clamp(2rem,4vw,3.6rem);font-weight:900;line-height:1.1;letter-spacing:-0.03em;margin-bottom:1.25rem;">
                        <span data-i18n="hero.h1a">Gérez votre patrimoine</span>
                        <span style="display:block;background:linear-gradient(135deg,#60a5fa,#a78bfa,#34d399);-webkit-background-clip:text;background-clip:text;color:transparent;-webkit-text-fill-color:transparent;" data-i18n="hero.h1b">
                            immobilier en ligne
                        </span>
                    </h1>

                    <!-- Subtext -->
                    <p style="font-size:1.05rem;line-height:1.75;color:rgba(255,255,255,0.72);max-width:480px;margin-bottom:2rem;" data-i18n="hero.sub">
                        Centralisez vos biens, automatisez la facturation, suivez les paiements et envoyez des documents via WhatsApp — tout en un seul endroit.
                    </p>

                    <!-- CTAs -->
                    <div style="display:flex;flex-wrap:wrap;gap:12px;margin-bottom:2rem;">
                        <a href="#compte" style="display:inline-flex;align-items:center;gap:8px;padding:13px 28px;border-radius:50px;font-weight:800;font-size:0.92rem;color:#1e3a8a;background:#fff;text-decoration:none;box-shadow:0 6px 24px rgba(0,0,0,0.2);transition:all 0.22s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 10px 32px rgba(0,0,0,0.25)'" onmouseout="this.style.transform='';this.style.boxShadow='0 6px 24px rgba(0,0,0,0.2)'">
                            <i class="fas fa-rocket" style="font-size:13px;color:#2563eb;"></i>
                            <span data-i18n="hero.cta1">Démarrer gratuitement</span>
                        </a>
                        <button id="btn-voir-demo" style="display:inline-flex;align-items:center;gap:8px;padding:13px 26px;border-radius:50px;font-weight:700;font-size:0.92rem;color:#fff;background:rgba(255,255,255,0.12);border:1.5px solid rgba(255,255,255,0.25);cursor:pointer;transition:all 0.22s;" onmouseover="this.style.background='rgba(255,255,255,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.12)'">
                            <i class="fas fa-play-circle" style="font-size:13px;"></i>
                            <span data-i18n="hero.cta2">Voir la démo</span>
                        </button>
                    </div>

                    <!-- Stats row -->
                    <div class="hero-stat-row">
                        <span class="hero-stat"><i class="fas fa-users" style="color:#4ade80;"></i> <span data-i18n="hero.stat1">500+ gestionnaires</span></span>
                        <span class="hero-stat"><i class="fas fa-home" style="color:#60a5fa;"></i> <span data-i18n="hero.stat2">10 000+ biens gérés</span></span>
                        <span class="hero-stat"><i class="fas fa-star" style="color:#fbbf24;"></i> <span data-i18n="hero.stat3">4.9 / 5 satisfaction</span></span>
                    </div>
                </div>

                <!-- RIGHT — phone mockup with 4 sliding screens -->
                <div class="animate-float" style="max-width:240px;margin-left:auto;position:relative;">

                    <!-- ── Phone frame ── -->
                    <div class="phone-frame" style="border-radius:38px;padding:14px 10px 18px;position:relative;">

                        <!-- Dynamic island -->
                        <div class="phone-island" style="width:68px;height:18px;border-radius:0 0 12px 12px;margin:0 auto 7px;display:flex;align-items:center;justify-content:center;gap:6px;">
                            <div class="phone-island-inner" style="width:5px;height:5px;border-radius:50%;"></div>
                            <div class="phone-island-inner" style="width:26px;height:5px;border-radius:3px;"></div>
                        </div>

                        <!-- Status bar -->
                        <div class="phone-statusbar" style="display:flex;justify-content:space-between;align-items:center;padding:0 10px 7px;font-size:0.5rem;font-weight:700;letter-spacing:0.03em;">
                            <span>9:41</span>
                            <div style="display:flex;gap:4px;align-items:center;">
                                <i class="fas fa-signal" style="font-size:6px;"></i>
                                <i class="fas fa-wifi" style="font-size:6px;"></i>
                                <i class="fas fa-battery-three-quarters" style="font-size:6px;"></i>
                            </div>
                        </div>

                        <!-- Screen slider -->
                        <div class="phone-screen-bg" style="overflow:hidden;border-radius:18px;height:332px;">
                            <div id="phone-track" style="display:flex;height:100%;width:400%;transition:transform 0.48s cubic-bezier(0.4,0,0.2,1);will-change:transform;">

                                {{-- ═══ ÉCRAN 1 : Tableau de bord ═══ --}}
                                <div class="phone-screen-bg" style="width:25%;flex-shrink:0;height:100%;display:flex;flex-direction:column;overflow:hidden;">
                                    <!-- App bar -->
                                    <div class="phone-appbar" style="padding:10px 12px 8px;display:flex;justify-content:space-between;align-items:center;">
                                        <div>
                                            <div class="phone-brand-label" style="font-size:0.56rem;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;">Lokativ</div>
                                            <div class="phone-screen-title" style="font-size:0.7rem;font-weight:800;" data-i18n="phone.s1.title">Tableau de bord</div>
                                        </div>
                                        <div style="width:24px;height:24px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:0.5rem;font-weight:800;color:#fff;">K</div>
                                    </div>
                                    <!-- 2×2 stat grid -->
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:5px;padding:8px 10px 5px;">
                                        <div style="background:rgba(59,130,246,0.13);border:1px solid rgba(59,130,246,0.22);border-radius:10px;padding:7px 8px;">
                                            <div class="phone-name" style="font-size:1rem;font-weight:900;line-height:1.1;">24</div>
                                            <div class="phone-stat-label" style="font-size:0.48rem;margin-top:1px;" data-i18n="hero.mini.props">Biens</div>
                                            <div style="font-size:0.46rem;color:#4ade80;font-weight:700;margin-top:2px;">↑ +2</div>
                                        </div>
                                        <div style="background:rgba(16,185,129,0.13);border:1px solid rgba(16,185,129,0.22);border-radius:10px;padding:7px 8px;">
                                            <div class="phone-name" style="font-size:1rem;font-weight:900;line-height:1.1;">38</div>
                                            <div class="phone-stat-label" style="font-size:0.48rem;margin-top:1px;" data-i18n="hero.mini.tenants">Locataires</div>
                                            <div style="font-size:0.46rem;color:#4ade80;font-weight:700;margin-top:2px;">↑ +4</div>
                                        </div>
                                        <div style="background:rgba(245,158,11,0.13);border:1px solid rgba(245,158,11,0.22);border-radius:10px;padding:7px 8px;">
                                            <div class="phone-name" style="font-size:0.75rem;font-weight:900;line-height:1.1;">845k F</div>
                                            <div class="phone-stat-label" style="font-size:0.48rem;margin-top:1px;" data-i18n="hero.mini.revenue">Revenus</div>
                                            <div style="font-size:0.46rem;color:#4ade80;font-weight:700;margin-top:2px;">↑ +12%</div>
                                        </div>
                                        <div style="background:rgba(239,68,68,0.13);border:1px solid rgba(239,68,68,0.22);border-radius:10px;padding:7px 8px;">
                                            <div class="phone-name" style="font-size:1rem;font-weight:900;line-height:1.1;">2</div>
                                            <div class="phone-stat-label" style="font-size:0.48rem;margin-top:1px;" data-i18n="phone.unpaid">Impayés</div>
                                            <div style="font-size:0.46rem;color:#f87171;font-weight:700;margin-top:2px;">↑ +1</div>
                                        </div>
                                    </div>
                                    <!-- Mini chart -->
                                    <div class="phone-chart-wrap" style="margin:0 10px 6px;border-radius:10px;padding:8px 10px;">
                                        <div class="phone-chart-label" style="font-size:0.46rem;font-weight:600;margin-bottom:6px;" data-i18n="hero.mini.chart">Encaissements — 6 mois</div>
                                        <div style="display:flex;align-items:flex-end;gap:3px;height:32px;">
                                            @foreach([40,58,50,72,84,95] as $hh)
                                            <div style="flex:1;border-radius:2px 2px 0 0;background:linear-gradient(180deg,#60a5fa,#2563eb);height:{{ $hh }}%;opacity:0.85;"></div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Recent activity -->
                                    <div style="padding:0 10px;display:flex;flex-direction:column;gap:5px;">
                                        @foreach([
                                            ['Kokou A.','Loyer payé · 85 000 F','#4ade80','KA','#052e16','hero.act.1'],
                                            ['Fatima O.','Contrat signé · Studio','#60a5fa','FO','#0c1a35','hero.act.2'],
                                            ['Jean P.','Rappel WhatsApp','#fbbf24','JP','#2d1a00','hero.act.3'],
                                        ] as $ai)
                                        <div style="display:flex;align-items:center;gap:7px;">
                                            <div style="width:22px;height:22px;border-radius:50%;background:{{ $ai[4] }};border:1.5px solid {{ $ai[2] }};display:flex;align-items:center;justify-content:center;font-size:0.42rem;font-weight:800;color:{{ $ai[2] }};flex-shrink:0;">{{ $ai[3] }}</div>
                                            <div style="flex:1;min-width:0;">
                                                <div class="phone-name" style="font-size:0.52rem;font-weight:700;">{{ $ai[0] }}</div>
                                                <div class="phone-sublabel" style="font-size:0.44rem;" data-i18n="{{ $ai[5] }}">{{ $ai[1] }}</div>
                                            </div>
                                            <div style="width:5px;height:5px;border-radius:50%;background:{{ $ai[2] }};box-shadow:0 0 4px {{ $ai[2] }};"></div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- ═══ ÉCRAN 2 : Mes biens ═══ --}}
                                <div class="phone-screen-bg" style="width:25%;flex-shrink:0;height:100%;display:flex;flex-direction:column;overflow:hidden;">
                                    <div class="phone-appbar" style="padding:10px 12px 8px;display:flex;justify-content:space-between;align-items:center;">
                                        <div class="phone-screen-title" style="font-size:0.7rem;font-weight:800;" data-i18n="phone.s2.title">Mes biens</div>
                                        <div class="phone-icon-btn" style="display:flex;align-items:center;gap:4px;border-radius:8px;padding:3px 8px;">
                                            <i class="fas fa-plus" style="color:#60a5fa;font-size:6px;"></i>
                                            <span style="font-size:0.46rem;color:#60a5fa;font-weight:700;" data-i18n="phone.s2.add">Ajouter</span>
                                        </div>
                                    </div>
                                    <div style="padding:8px 10px;display:flex;flex-direction:column;gap:6px;overflow:hidden;">
                                        @foreach([
                                            ['Villa Cocotiers','Cotonou · 4 pièces','#4ade80','Loué','phone.status.rented','fa-home'],
                                            ['Appt Studio B12','Abomey-Calavi','#60a5fa','Disponible','phone.status.avail','fa-building'],
                                            ['Maison Tokpa','Cotonou · 3 pièces','#fbbf24','Intervention','phone.status.maint','fa-tools'],
                                            ['Chambre 5, Lot 8','Porto-Novo','#a78bfa','Loué','phone.status.rented','fa-door-open'],
                                        ] as $bp)
                                        <div class="phone-card" style="border-radius:10px;padding:7px 9px;display:flex;align-items:center;gap:7px;">
                                            <div style="width:26px;height:26px;border-radius:8px;background:{{ $bp[2] }}18;border:1px solid {{ $bp[2] }}33;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                                <i class="fas {{ $bp[5] }}" style="color:{{ $bp[2] }};font-size:10px;"></i>
                                            </div>
                                            <div style="flex:1;min-width:0;">
                                                <div class="phone-name" style="font-size:0.54rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $bp[0] }}</div>
                                                <div class="phone-sublabel" style="font-size:0.44rem;">{{ $bp[1] }}</div>
                                            </div>
                                            <span style="font-size:0.41rem;font-weight:700;color:{{ $bp[2] }};background:{{ $bp[2] }}20;border-radius:6px;padding:2px 5px;white-space:nowrap;" data-i18n="{{ $bp[4] }}">{{ $bp[3] }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- ═══ ÉCRAN 3 : Locataires ═══ --}}
                                <div class="phone-screen-bg" style="width:25%;flex-shrink:0;height:100%;display:flex;flex-direction:column;overflow:hidden;">
                                    <div class="phone-appbar" style="padding:10px 12px 8px;display:flex;justify-content:space-between;align-items:center;">
                                        <div class="phone-screen-title" style="font-size:0.7rem;font-weight:800;" data-i18n="phone.s3.title">Locataires</div>
                                        <i class="fas fa-search phone-icon-sm" style="font-size:11px;"></i>
                                    </div>
                                    <div style="padding:8px 10px;display:flex;flex-direction:column;gap:6px;overflow:hidden;">
                                        @foreach([
                                            ['Kokou Agbodjan','85 000 F/mois','#4ade80','KA','#052e16','À jour','phone.pay.ok'],
                                            ['Fatima Ouédraogo','45 000 F/mois','#ef4444','FO','#1f0707','Impayé','phone.pay.late'],
                                            ['Jean Pierre T.','60 000 F/mois','#fbbf24','JP','#2d1a00','En attente','phone.pay.wait'],
                                            ['Aïssatou Bah','75 000 F/mois','#4ade80','AB','#052e16','À jour','phone.pay.ok'],
                                        ] as $lt)
                                        <div class="phone-card" style="border-radius:10px;padding:7px 9px;display:flex;align-items:center;gap:7px;">
                                            <div style="width:26px;height:26px;border-radius:50%;background:{{ $lt[4] }};border:1.5px solid {{ $lt[2] }};display:flex;align-items:center;justify-content:center;font-size:0.45rem;font-weight:800;color:{{ $lt[2] }};flex-shrink:0;">{{ $lt[3] }}</div>
                                            <div style="flex:1;min-width:0;">
                                                <div class="phone-name" style="font-size:0.54rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $lt[0] }}</div>
                                                <div class="phone-sublabel" style="font-size:0.44rem;">{{ $lt[1] }}</div>
                                            </div>
                                            <span style="font-size:0.41rem;font-weight:700;color:{{ $lt[2] }};background:{{ $lt[2] }}20;border-radius:6px;padding:2px 5px;white-space:nowrap;" data-i18n="{{ $lt[6] }}">{{ $lt[5] }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- ═══ ÉCRAN 4 : Facturation ═══ --}}
                                <div class="phone-screen-bg" style="width:25%;flex-shrink:0;height:100%;display:flex;flex-direction:column;overflow:hidden;">
                                    <div class="phone-appbar" style="padding:10px 12px 8px;display:flex;justify-content:space-between;align-items:center;">
                                        <div class="phone-screen-title" style="font-size:0.7rem;font-weight:800;" data-i18n="phone.s4.title">Facturation</div>
                                        <i class="fas fa-chart-bar phone-icon-sm" style="font-size:11px;"></i>
                                    </div>
                                    <!-- Summary -->
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:5px;padding:8px 10px 5px;">
                                        <div style="background:rgba(16,185,129,0.13);border:1px solid rgba(16,185,129,0.25);border-radius:10px;padding:7px 8px;">
                                            <div class="phone-billing-label" style="font-size:0.44rem;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;" data-i18n="phone.billing.collected">Encaissé</div>
                                            <div style="font-size:0.68rem;font-weight:900;color:#4ade80;margin-top:3px;">845 000 F</div>
                                        </div>
                                        <div style="background:rgba(245,158,11,0.13);border:1px solid rgba(245,158,11,0.25);border-radius:10px;padding:7px 8px;">
                                            <div class="phone-billing-label" style="font-size:0.44rem;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;" data-i18n="phone.billing.pending">En attente</div>
                                            <div style="font-size:0.68rem;font-weight:900;color:#fbbf24;margin-top:3px;">105 000 F</div>
                                        </div>
                                    </div>
                                    <!-- Invoice rows -->
                                    <div style="padding:4px 10px;display:flex;flex-direction:column;gap:5px;">
                                        @foreach([
                                            ['Kokou A.','85 000 F','Jan 2025','#4ade80','Payé','phone.pay.paid'],
                                            ['Fatima O.','45 000 F','Jan 2025','#ef4444','Impayé','phone.pay.late'],
                                            ['Jean P.','60 000 F','Jan 2025','#fbbf24','En attente','phone.pay.wait'],
                                            ['Aïssatou B.','75 000 F','Déc 2024','#4ade80','Payé','phone.pay.paid'],
                                        ] as $inv)
                                        <div class="phone-inv-row" style="display:flex;align-items:center;gap:7px;padding:6px 8px;border-radius:8px;">
                                            <div style="flex:1;min-width:0;">
                                                <div class="phone-name" style="font-size:0.52rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $inv[0] }}</div>
                                                <div class="phone-inv-date" style="font-size:0.42rem;">{{ $inv[2] }}</div>
                                            </div>
                                            <div class="phone-inv-amount" style="font-size:0.52rem;font-weight:800;">{{ $inv[1] }}</div>
                                            <span style="font-size:0.4rem;font-weight:700;color:{{ $inv[3] }};background:{{ $inv[3] }}20;border-radius:5px;padding:2px 4px;white-space:nowrap;" data-i18n="{{ $inv[5] }}">{{ $inv[4] }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- /Screen slider -->

                        <!-- Navigation dots -->
                        <div style="display:flex;justify-content:center;gap:5px;margin-top:11px;">
                            <span class="phone-nav-dot" style="width:18px;height:4px;border-radius:2px;background:#3b82f6;cursor:pointer;transition:all 0.3s;display:inline-block;"></span>
                            <span class="phone-nav-dot phone-dot-inactive" style="width:6px;height:4px;border-radius:2px;cursor:pointer;transition:all 0.3s;display:inline-block;"></span>
                            <span class="phone-nav-dot phone-dot-inactive" style="width:6px;height:4px;border-radius:2px;cursor:pointer;transition:all 0.3s;display:inline-block;"></span>
                            <span class="phone-nav-dot phone-dot-inactive" style="width:6px;height:4px;border-radius:2px;cursor:pointer;transition:all 0.3s;display:inline-block;"></span>
                        </div>
                    </div>
                    <!-- /Phone frame -->

                    <!-- Trust badge -->
                    <div style="margin-top:16px;display:flex;align-items:center;justify-content:center;gap:8px;font-size:0.72rem;color:rgba(255,255,255,0.48);">
                        <i class="fas fa-shield-alt" style="color:#4ade80;font-size:11px;"></i>
                        <span data-i18n="hero.trust">Données hébergées en sécurité · SSL · RGPD</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Scroll indicator -->
        <div style="position:absolute;bottom:28px;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:6px;opacity:0.45;">
            <div style="font-size:0.65rem;color:#fff;letter-spacing:0.08em;text-transform:uppercase;">Découvrir</div>
            <div style="width:1px;height:32px;background:linear-gradient(180deg,#fff,transparent);"></div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
         FONCTIONNALITÉS PUISSANTES
    ═══════════════════════════════════════════ -->
    <section id="fonctionnalites" style="padding:5rem 0;background:#fff;">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="text-center mb-14">
                <span class="inline-block px-4 py-1 mb-4 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full">
                    <i class="fas fa-bolt me-1"></i> <span data-i18n="sec.features.badge">Tout-en-un</span>
                </span>
                <h2 class="mb-3 text-3xl font-extrabold text-gray-900 sm:text-4xl lg:text-5xl" data-i18n="sec.features.h2">
                    Des fonctionnalités puissantes
                </h2>
                <p class="max-w-2xl mx-auto text-gray-500 text-base sm:text-lg" data-i18n="sec.features.sub">
                    Tous les outils dont vous avez besoin pour gérer votre patrimoine immobilier au quotidien
                </p>
            </div>

            <!-- Bento grid -->
            <div class="bento-grid" style="display:grid;grid-template-columns:repeat(3,1fr);grid-template-rows:auto auto;gap:1.25rem;">

                <!-- Grande carte à gauche -->
                <div class="bento-card-large" style="grid-column:1/2;grid-row:1/3;border-radius:20px;background:linear-gradient(135deg,#1e3a8a,#2563eb);color:#fff;padding:2.5rem 2rem;display:flex;flex-direction:column;justify-content:flex-end;min-height:320px;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-30px;right:-30px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,0.07);"></div>
                    <div style="position:absolute;top:40px;right:30px;width:90px;height:90px;border-radius:50%;background:rgba(255,255,255,0.05);"></div>
                    <div style="width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;margin-bottom:1.5rem;">
                        <i class="fas fa-building" style="font-size:24px;color:#fff;"></i>
                    </div>
                    <h3 style="font-size:1.35rem;font-weight:800;margin-bottom:0.75rem;" data-i18n="feat.prop.title">Gestion des propriétés</h3>
                    <p style="font-size:0.88rem;opacity:0.82;line-height:1.6;margin:0;" data-i18n="feat.prop.desc">Centralisez toutes vos propriétés en un seul endroit. Maisons, appartements, chambres — suivez chaque bien, ses documents et son historique.</p>
                    <div style="margin-top:1.5rem;display:flex;gap:8px;flex-wrap:wrap;">
                        <span style="background:rgba(255,255,255,0.15);border-radius:20px;padding:4px 12px;font-size:0.72rem;font-weight:600;" data-i18n="feat.prop.tag1">Multi-sites</span>
                        <span style="background:rgba(255,255,255,0.15);border-radius:20px;padding:4px 12px;font-size:0.72rem;font-weight:600;" data-i18n="feat.prop.tag2">Documents</span>
                        <span style="background:rgba(255,255,255,0.15);border-radius:20px;padding:4px 12px;font-size:0.72rem;font-weight:600;" data-i18n="feat.prop.tag3">Historique</span>
                    </div>
                </div>

                <!-- Carte haut-centre -->
                <div style="grid-column:2/3;grid-row:1/2;border-radius:20px;background:#f0fdf4;border:1.5px solid #bbf7d0;padding:1.75rem;display:flex;flex-direction:column;gap:1rem;">
                    <div style="width:48px;height:48px;border-radius:14px;background:#dcfce7;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-users" style="font-size:20px;color:#16a34a;"></i>
                    </div>
                    <div>
                        <h3 style="font-size:1.05rem;font-weight:800;color:#1e293b;margin-bottom:6px;" data-i18n="feat.tenant.title">Gestion des locataires</h3>
                        <p style="font-size:0.83rem;color:#64748b;margin:0;line-height:1.5;" data-i18n="feat.tenant.desc">Contrats, paiements, communications et historique complet pour chaque locataire.</p>
                    </div>
                </div>

                <!-- Carte haut-droite -->
                <div style="grid-column:3/4;grid-row:1/2;border-radius:20px;background:#fdf4ff;border:1.5px solid #e9d5ff;padding:1.75rem;display:flex;flex-direction:column;gap:1rem;">
                    <div style="width:48px;height:48px;border-radius:14px;background:#f3e8ff;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-file-invoice-dollar" style="font-size:20px;color:#7c3aed;"></i>
                    </div>
                    <div>
                        <h3 style="font-size:1.05rem;font-weight:800;color:#1e293b;margin-bottom:6px;" data-i18n="feat.billing.title">Facturation automatique</h3>
                        <p style="font-size:0.83rem;color:#64748b;margin:0;line-height:1.5;" data-i18n="feat.billing.desc">Générez vos quittances de loyer et suivez les paiements en temps réel.</p>
                    </div>
                </div>

                <!-- Carte bas-centre -->
                <div style="grid-column:2/3;grid-row:2/3;border-radius:20px;background:#fff7ed;border:1.5px solid #fed7aa;padding:1.75rem;display:flex;flex-direction:column;gap:1rem;">
                    <div style="width:48px;height:48px;border-radius:14px;background:#ffedd5;display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-chart-pie" style="font-size:20px;color:#ea580c;"></i>
                    </div>
                    <div>
                        <h3 style="font-size:1.05rem;font-weight:800;color:#1e293b;margin-bottom:6px;" data-i18n="feat.dash.title">Tableau de bord analytique</h3>
                        <p style="font-size:0.83rem;color:#64748b;margin:0;line-height:1.5;" data-i18n="feat.dash.desc">Revenus, taux d'occupation, rentabilité — tout en un coup d'œil.</p>
                    </div>
                </div>

                <!-- Carte bas-droite : deux mini cartes -->
                <div style="grid-column:3/4;grid-row:2/3;display:flex;flex-direction:column;gap:1rem;">
                    <div style="border-radius:16px;background:#fff1f2;border:1.5px solid #fecdd3;padding:1.25rem;display:flex;align-items:center;gap:14px;flex:1;">
                        <div style="width:40px;height:40px;border-radius:12px;background:#ffe4e6;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-bell" style="font-size:16px;color:#e11d48;"></i>
                        </div>
                        <div>
                            <p style="font-size:0.9rem;font-weight:800;color:#1e293b;margin:0 0 3px;" data-i18n="feat.alerts.title">Rappels intelligents</p>
                            <p style="font-size:0.77rem;color:#64748b;margin:0;" data-i18n="feat.alerts.desc">Notifications automatiques pour loyers et contrats</p>
                        </div>
                    </div>
                    <div style="border-radius:16px;background:#f0fdfa;border:1.5px solid #99f6e4;padding:1.25rem;display:flex;align-items:center;gap:14px;flex:1;">
                        <div style="width:40px;height:40px;border-radius:12px;background:#ccfbf1;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-file-contract" style="font-size:16px;color:#0d9488;"></i>
                        </div>
                        <div>
                            <p style="font-size:0.9rem;font-weight:800;color:#1e293b;margin:0 0 3px;" data-i18n="feat.docs.title">Documents & Contrats</p>
                            <p style="font-size:0.77rem;color:#64748b;margin:0;" data-i18n="feat.docs.desc">Génération de baux et stockage sécurisé</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <style>
            @media (max-width: 768px) {
                #fonctionnalites > div > div:last-child {
                    grid-template-columns: 1fr !important;
                    grid-template-rows: auto !important;
                }
                #fonctionnalites > div > div:last-child > * {
                    grid-column: auto !important;
                    grid-row: auto !important;
                    min-height: 180px !important;
                }
            }
        </style>
    </section>

    <!-- ═══════════════════════════════════════════
         COMMENT ÇA MARCHE
    ═══════════════════════════════════════════ -->
    <section id="sec-comment" style="padding:5rem 0;background:linear-gradient(180deg,#f8faff 0%,#fff 100%);">
        <div class="px-4 mx-auto max-w-6xl sm:px-6 lg:px-8">

            <div class="text-center mb-14">
                <span class="inline-block px-4 py-1 mb-4 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full">
                    <i class="fas fa-map-signs me-1"></i> <span data-i18n="sec.how.badge">En 4 étapes</span>
                </span>
                <h2 class="mb-3 text-3xl font-extrabold text-gray-900 sm:text-4xl lg:text-5xl" data-i18n="sec.how.h2">
                    Comment ça marche ?
                </h2>
                <p class="max-w-xl mx-auto text-gray-500 text-base" data-i18n="sec.how.sub">
                    Démarrez en quelques minutes et gérez votre patrimoine comme un pro
                </p>
            </div>

            <!-- Timeline horizontale -->
            <div class="how-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:0;position:relative;">

                <!-- Ligne de connexion -->
                <div class="how-connector" style="position:absolute;top:36px;left:12.5%;right:12.5%;height:2px;background:linear-gradient(90deg,#bfdbfe,#2563eb,#bfdbfe);z-index:0;"></div>

                @php
                $howSteps = [
                    ['num'=>1,'icon'=>'fa-user-plus','color'=>'#2563eb','bg'=>'#eff6ff','i18n'=>'step.1','title'=>'Créez votre compte','desc'=>'Inscrivez-vous gratuitement en moins de 2 minutes, sans carte bancaire.'],
                    ['num'=>2,'icon'=>'fa-home','color'=>'#7c3aed','bg'=>'#f3e8ff','i18n'=>'step.2','title'=>'Ajoutez vos biens','desc'=>'Renseignez vos propriétés, chambres et leurs caractéristiques.'],
                    ['num'=>3,'icon'=>'fa-users','color'=>'#059669','bg'=>'#ecfdf5','i18n'=>'step.3','title'=>'Gérez vos locataires','desc'=>'Ajoutez vos locataires, créez leurs contrats et suivez les paiements.'],
                    ['num'=>4,'icon'=>'fa-magic','color'=>'#ea580c','bg'=>'#fff7ed','i18n'=>'step.4','title'=>'Automatisez tout','desc'=>'Laissez Lokativ générer les factures, rappels et documents automatiquement.'],
                ];
                @endphp

                @foreach($howSteps as $s)
                <div style="display:flex;flex-direction:column;align-items:center;text-align:center;padding:0 1rem;position:relative;z-index:1;">
                    <!-- Numéro + icône -->
                    <div style="position:relative;margin-bottom:1.5rem;">
                        <div style="width:72px;height:72px;border-radius:50%;background:{{ $s['bg'] }};border:3px solid {{ $s['color'] }}33;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 20px {{ $s['color'] }}22;">
                            <i class="fas {{ $s['icon'] }}" style="font-size:24px;color:{{ $s['color'] }};"></i>
                        </div>
                        <div style="position:absolute;top:-6px;right:-6px;width:24px;height:24px;border-radius:50%;background:{{ $s['color'] }};color:#fff;font-size:0.7rem;font-weight:800;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px {{ $s['color'] }}55;">{{ $s['num'] }}</div>
                    </div>
                    <h3 style="font-size:0.98rem;font-weight:800;color:#1e293b;margin-bottom:8px;" data-i18n="{{ $s['i18n'] }}.title">{{ $s['title'] }}</h3>
                    <p style="font-size:0.82rem;color:#64748b;line-height:1.55;margin:0;" data-i18n="{{ $s['i18n'] }}.desc">{{ $s['desc'] }}</p>
                </div>
                @endforeach
            </div>

            <!-- CTA bas -->
            <div style="text-align:center;margin-top:3rem;">
                <a href="#compte" style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#1d4ed8,#2563eb);color:#fff;font-weight:700;font-size:0.95rem;padding:13px 32px;border-radius:50px;text-decoration:none;box-shadow:0 4px 20px rgba(37,99,235,0.35);transition:transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                    <span data-i18n="step.cta">Commencer maintenant</span> <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
         STATISTIQUES
    ═══════════════════════════════════════════ -->
    <section style="padding:4rem 0;background:linear-gradient(135deg,#1e3a8a 0%,#1e40af 60%,#2563eb 100%);">
        <div class="px-4 mx-auto max-w-6xl sm:px-6 lg:px-8">
            <div class="stats-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;text-align:center;color:#fff;">
                @php
                $stats = [
                    ['val'=>'500+','label'=>'Utilisateurs actifs','icon'=>'fa-users','i18n'=>'stat.users'],
                    ['val'=>'2 500+','label'=>'Propriétés gérées','icon'=>'fa-home','i18n'=>'stat.props'],
                    ['val'=>'15M+','label'=>'XOF de loyers traités','icon'=>'fa-coins','i18n'=>'stat.revenue'],
                    ['val'=>'97%','label'=>'Taux de satisfaction','icon'=>'fa-heart','i18n'=>'stat.satisfaction'],
                ];
                @endphp
                @foreach($stats as $stat)
                <div style="padding:1.5rem;border-radius:16px;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);">
                    <div style="width:44px;height:44px;border-radius:12px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;">
                        <i class="fas {{ $stat['icon'] }}" style="font-size:18px;color:#93c5fd;"></i>
                    </div>
                    <div style="font-size:2.2rem;font-weight:900;letter-spacing:-0.02em;line-height:1;">{{ $stat['val'] }}</div>
                    <p style="font-size:0.82rem;color:#bfdbfe;margin-top:6px;" data-i18n="{{ $stat['i18n'] }}">{{ $stat['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ═══════════════════════════════════════════
         ILS NOUS FONT CONFIANCE
    ═══════════════════════════════════════════ -->
    <section id="sec-partenaires" style="padding:3.5rem 0;background:#f8faff;overflow:hidden;">
        <div class="px-4 mx-auto max-w-5xl sm:px-6 lg:px-8">
            <div style="text-align:center;margin-bottom:2rem;">
                <p style="font-size:0.78rem;font-weight:700;text-transform:uppercase;letter-spacing:0.12em;color:#94a3b8;" data-i18n="partners.label">Ils nous font confiance</p>
            </div>

            <!-- Défilement infini -->
            <div style="position:relative;overflow:hidden;-webkit-mask:linear-gradient(90deg,transparent,#000 10%,#000 90%,transparent);mask:linear-gradient(90deg,transparent,#000 10%,#000 90%,transparent);">
                <div style="display:flex;gap:3rem;animation:marquee 22s linear infinite;width:max-content;">
                    @php
                    $partners = [
                        ['icon'=>'fa-building','name'=>'Agence Soleil','color'=>'#2563eb'],
                        ['icon'=>'fa-home','name'=>'Immo Bénin','color'=>'#7c3aed'],
                        ['icon'=>'fa-city','name'=>'Urban Invest','color'=>'#059669'],
                        ['icon'=>'fa-landmark','name'=>'Groupe Horizon','color'=>'#ea580c'],
                        ['icon'=>'fa-hotel','name'=>'Estate Pro','color'=>'#e11d48'],
                        ['icon'=>'fa-store','name'=>'LokaImmo','color'=>'#0284c7'],
                    ];
                    @endphp
                    @foreach(array_merge($partners,$partners) as $p)
                    <div style="display:flex;align-items:center;gap:10px;padding:12px 24px;border-radius:12px;background:#fff;border:1.5px solid #e2e8f0;white-space:nowrap;flex-shrink:0;">
                        <i class="fas {{ $p['icon'] }}" style="font-size:18px;color:{{ $p['color'] }};"></i>
                        <span style="font-weight:700;font-size:0.9rem;color:#334155;">{{ $p['name'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <style>
            @keyframes marquee { from { transform:translateX(0); } to { transform:translateX(-50%); } }
        </style>
    </section>

    <!-- ══════════════════════════════════════════════════════════════════
         Section Marketplace — Recherche + Grille + Carte
    ══════════════════════════════════════════════════════════════════ -->
    <section id="biens" class="py-12 sm:py-16 lg:py-20" style="background:linear-gradient(180deg,#eff6ff 0%,#fff 100%)">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- En-tête -->
            <div class="mb-10 text-center">
                <span class="inline-block px-4 py-1 mb-4 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full">
                    Marketplace
                </span>
                <h2 class="mb-3 text-2xl font-bold text-gray-800 sm:text-3xl md:text-4xl">
                    Biens <span class="text-blue-600">Immobiliers</span> Disponibles
                </h2>
                <p class="max-w-2xl mx-auto text-base text-gray-500">
                    Trouvez appartements, maisons, terrains et locaux — recherchez et filtrez en temps réel
                </p>
            </div>

            <!-- ── Filtres JS (pas de soumission) ── -->
            <div class="p-4 mb-8 bg-white shadow-md rounded-2xl sm:p-6" style="border:1px solid #e2e8f0">
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-5">
                    <!-- Recherche libre -->
                    <div class="col-span-2 sm:col-span-4 lg:col-span-2">
                        <label class="block mb-1 text-xs font-semibold text-gray-500 uppercase tracking-wide">Recherche</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                            </span>
                            <input type="text" id="mkpQ" placeholder="Quartier, description..."
                                   class="w-full pl-9 pr-9 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <button type="button" id="mkpClearQ"
                                    class="absolute inset-y-0 right-3 flex items-center text-gray-300 hover:text-gray-500"
                                    style="display:none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                    <!-- Type -->
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-500 uppercase tracking-wide">Type</label>
                        <select id="mkpType" class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                            <option value="">Tous</option>
                            @foreach(['appartement'=>'Appartement','maison'=>'Maison','villa'=>'Villa','terrain'=>'Terrain','bureau'=>'Bureau','commerce'=>'Local commercial','chambre'=>'Chambre','studio'=>'Studio'] as $v=>$l)
                            <option value="{{ $v }}">{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Ville -->
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-500 uppercase tracking-wide">Ville</label>
                        <input type="text" id="mkpVille" list="mkpVillesList" placeholder="Cotonou..."
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                        <datalist id="mkpVillesList">
                            @foreach($villesMarketplace ?? [] as $v)<option value="{{ $v }}">@endforeach
                        </datalist>
                    </div>
                    <!-- Budget max -->
                    <div>
                        <label class="block mb-1 text-xs font-semibold text-gray-500 uppercase tracking-wide">Budget max (XOF)</label>
                        <input type="number" id="mkpBudget" placeholder="500 000"
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>
                </div>
            </div>

            @if(isset($publicites) && $publicites->count() > 0)

            <!-- Compteur résultats -->
            <div class="flex items-center justify-between mb-6 flex-wrap gap-2">
                <p class="text-sm text-gray-500">
                    <span class="font-bold text-gray-800" id="mkpCount">{{ $publicites->count() }}</span>
                    <span id="mkpCountLabel">annonce{{ $publicites->count() > 1 ? 's' : '' }} trouvée{{ $publicites->count() > 1 ? 's' : '' }}</span>
                </p>
                <button type="button" id="mkpToggleMap"
                        class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                    <span id="mkpMapLabel">Afficher la carte</span>
                </button>
            </div>

            <!-- Carte Leaflet (masquée par défaut) -->
            <div id="mkpMapContainer" style="display:none;margin-bottom:2rem">
                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
                <div id="mkpMap" style="height:360px;border-radius:16px;border:1px solid #e2e8f0;overflow:hidden"></div>
            </div>

            <!-- Message aucun résultat JS -->
            <div id="mkpEmpty" style="display:none" class="py-12 text-center">
                <svg class="w-14 h-14 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                <p class="text-gray-500 font-medium">Aucune annonce ne correspond à votre recherche.</p>
                <button type="button" onclick="mkpReset()" class="mt-3 text-sm text-blue-600 hover:underline">Effacer les filtres</button>
            </div>

            <!-- Grille des annonces -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3" id="mkpGrid">
                @foreach($publicites as $pub)
                @php $pubImages = $pub->images; @endphp
                <div class="mkp-card overflow-hidden bg-white shadow-md rounded-2xl transition-all duration-300 hover:shadow-xl hover:-translate-y-1 flex flex-col"
                     data-q="{{ strtolower($pub->localisation.' '.$pub->description.' '.($pub->ville ?? '').' '.($pub->quartier ?? '')) }}"
                     data-type="{{ $pub->type_bien ?? '' }}"
                     data-ville="{{ strtolower($pub->ville ?? '') }}"
                     data-price="{{ (float) $pub->price }}">

                    <!-- Image principale -->
                    <div class="relative overflow-hidden" style="height:210px;background:#e2e8f0;flex-shrink:0">
                        @if(count($pubImages))
                        <img src="{{ asset('storage/'.$pubImages[0]) }}" alt="{{ $pub->localisation }}"
                             class="object-cover w-full h-full transition-transform duration-500 group-hover:scale-110"
                             loading="lazy">
                        @else
                        <div class="flex items-center justify-center h-full text-gray-400 flex-col gap-2">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-sm">Pas de photo</span>
                        </div>
                        @endif

                        <!-- Badges overlay -->
                        <div class="absolute top-0 left-0 p-3 flex flex-wrap gap-1">
                            @if($pub->is_sponsored_active)
                            <span class="px-2 py-0.5 text-xs font-bold text-white rounded-full shadow"
                                  style="background:linear-gradient(90deg,#f7b731,#f0932b)">
                                ★ Sponsorisée
                            </span>
                            @endif
                            @if($pub->type_bien)
                            <span class="px-2 py-0.5 text-xs font-semibold rounded-full"
                                  style="background:rgba(255,255,255,.9);color:#2563eb">
                                {{ $pub->type_label }}
                            </span>
                            @endif
                            @if($pub->published_at && $pub->published_at->diffInDays(now()) < 3)
                            <span class="px-2 py-0.5 text-xs font-bold text-white bg-green-500 rounded-full shadow">
                                Nouveau
                            </span>
                            @endif
                        </div>

                        <!-- Prix -->
                        <div class="absolute top-3 right-3">
                            <span class="px-3 py-1 text-sm font-bold text-white rounded-full shadow-lg"
                                  style="background:rgba(37,99,235,.9)">
                                {{ number_format((float)$pub->price, 0, ',', ' ') }} XOF
                            </span>
                        </div>
                    </div>

                    <!-- Corps -->
                    <div class="p-5 flex flex-col flex-1">
                        <h3 class="mb-1 text-base font-bold text-gray-800 line-clamp-1">{{ $pub->localisation }}</h3>
                        @if($pub->ville || $pub->quartier)
                        <p class="text-xs text-gray-400 mb-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ implode(', ', array_filter([$pub->quartier, $pub->ville])) }}
                        </p>
                        @endif
                        <p class="mb-4 text-sm text-gray-500 line-clamp-2 flex-1">{{ $pub->description }}</p>

                        <!-- Caractéristiques -->
                        <div class="flex items-center gap-4 mb-4 text-sm text-gray-500">
                            @if($pub->Superficie)
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                                <span>{{ $pub->Superficie }} m²</span>
                            </div>
                            @endif
                            <div class="flex items-center gap-1">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span>{{ $pub->telephone }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 mt-auto">
                            @if($pub->slug)
                            <a href="{{ route('marketplace.show', $pub->slug) }}"
                               class="flex-1 flex items-center justify-center gap-1 px-3 py-2 text-sm font-semibold text-blue-600 border border-blue-200 rounded-xl hover:bg-blue-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Voir détail
                            </a>
                            @endif
                            <a href="tel:{{ preg_replace('/\s+/', '', $pub->telephone) }}"
                               class="flex-1 flex items-center justify-center gap-1 px-3 py-2 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                Appeler
                            </a>
                            @php $shareUrl = urlencode($pub->slug ? route('marketplace.show', $pub->slug) : url('/')); @endphp
                            <a href="https://wa.me/?text={{ urlencode($pub->localisation.' — '.$pub->price.' XOF ') }}{{ $shareUrl }}"
                               target="_blank"
                               class="flex items-center justify-center px-3 py-2 rounded-xl transition-colors"
                               style="background:#25d366;color:#fff"
                               title="Partager sur WhatsApp">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.126.555 4.126 1.526 5.87L0 24l6.337-1.656A11.953 11.953 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.818 9.818 0 01-5.006-1.369l-.36-.213-3.76.983 1.002-3.659-.234-.376A9.817 9.817 0 012.182 12C2.182 6.573 6.574 2.182 12 2.182c5.427 0 9.818 4.391 9.818 9.818 0 5.427-4.391 9.818-9.818 9.818z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- CTA vers le Marketplace complet -->
            <div class="mt-10 text-center">
                <a href="{{ route('marketplace.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 text-sm font-semibold text-white bg-blue-600 rounded-2xl shadow-md hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Voir toutes les annonces sur le Marketplace
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>

            @else
            <div class="py-16 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <h3 class="mb-2 text-lg font-semibold text-gray-500">Aucune annonce trouvée</h3>
                <p class="text-sm text-gray-400">Modifiez vos critères ou revenez plus tard.</p>
                <a href="{{ url('/') }}#biens" class="inline-block mt-4 text-sm text-blue-600 hover:underline">Voir toutes les annonces</a>
            </div>
            @endif

        </div>
    </section>

    @php
    $mkpMapData = ($publicites ?? collect())->filter(function($p) {
        return $p->lat && $p->lng;
    })->map(function($p) {
        return [
            'lat'          => (float) $p->lat,
            'lng'          => (float) $p->lng,
            'localisation' => $p->localisation,
            'price'        => $p->price,
            'slug'         => $p->slug,
            'image'        => $p->image_url ? asset('storage/'.$p->image_url) : null,
        ];
    })->values();
    @endphp
    <script>
    (function() {
        // ── Filtrage JS temps réel ────────────────────────────────────────────
        var fQ      = document.getElementById('mkpQ');
        var fType   = document.getElementById('mkpType');
        var fVille  = document.getElementById('mkpVille');
        var fBudget = document.getElementById('mkpBudget');
        var clearQ  = document.getElementById('mkpClearQ');
        var grid    = document.getElementById('mkpGrid');
        var empty   = document.getElementById('mkpEmpty');
        var countEl = document.getElementById('mkpCount');
        var labelEl = document.getElementById('mkpCountLabel');

        function runFilter() {
            if (!grid) return;
            var q      = fQ     ? fQ.value.toLowerCase().trim()     : '';
            var type   = fType  ? fType.value                        : '';
            var ville  = fVille ? fVille.value.toLowerCase().trim()  : '';
            var budget = fBudget && fBudget.value ? parseFloat(fBudget.value) : null;

            // Bouton effacer recherche
            if (clearQ) clearQ.style.display = q ? '' : 'none';

            var cards   = grid.querySelectorAll('.mkp-card');
            var visible = 0;
            cards.forEach(function(card) {
                var dq    = (card.dataset.q    || '').toLowerCase();
                var dtype = (card.dataset.type || '');
                var dv    = (card.dataset.ville|| '').toLowerCase();
                var dp    = parseFloat(card.dataset.price || '0');

                var ok = true;
                if (q     && dq.indexOf(q)        === -1) ok = false;
                if (type  && dtype !== type)               ok = false;
                if (ville && dv.indexOf(ville)    === -1) ok = false;
                if (budget !== null && dp > budget)        ok = false;

                card.style.display = ok ? '' : 'none';
                if (ok) visible++;
            });

            // Compteur
            if (countEl) countEl.textContent = visible;
            if (labelEl) labelEl.textContent = 'annonce' + (visible > 1 ? 's' : '') + ' trouvée' + (visible > 1 ? 's' : '');

            // Message vide
            if (empty) empty.style.display = (visible === 0) ? '' : 'none';
            if (grid)  grid.style.display  = (visible === 0) ? 'none' : '';
        }

        window.mkpReset = function() {
            if (fQ)      fQ.value     = '';
            if (fType)   fType.value  = '';
            if (fVille)  fVille.value = '';
            if (fBudget) fBudget.value= '';
            runFilter();
        };

        [fQ, fVille, fBudget].forEach(function(el) {
            if (el) el.addEventListener('input', runFilter);
        });
        if (fType) fType.addEventListener('change', runFilter);
        if (clearQ) clearQ.addEventListener('click', function() {
            if (fQ) fQ.value = '';
            runFilter();
            if (fQ) fQ.focus();
        });

        // ── Carte (lazy) ──────────────────────────────────────────────────────
        var mapLoaded = false;
        var mapData   = @json($mkpMapData);

        var toggleMap = document.getElementById('mkpToggleMap');
        if (toggleMap) {
            toggleMap.addEventListener('click', function() {
                var container = document.getElementById('mkpMapContainer');
                var label     = document.getElementById('mkpMapLabel');
                var visible   = container.style.display !== 'none';

                container.style.display = visible ? 'none' : '';
                label.textContent = visible ? 'Afficher la carte' : 'Masquer la carte';

                if (!visible && !mapLoaded) {
                    mapLoaded = true;
                    var s = document.createElement('script');
                    s.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                    s.onload = function() { initMap(); };
                    document.head.appendChild(s);
                } else if (!visible && mapLoaded) {
                    setTimeout(function() { window._mkpMap && window._mkpMap.invalidateSize(); }, 100);
                }
            });
        }

        function initMap() {
            var m = L.map('mkpMap').setView([6.3703, 2.3912], 7);
            window._mkpMap = m;
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(m);
            var icon = L.divIcon({
                html: '<div style="background:#2563eb;color:#fff;border-radius:50%;width:30px;height:30px;display:flex;align-items:center;justify-content:center;font-size:13px;box-shadow:0 2px 6px rgba(0,0,0,.3);border:2px solid #fff">&#128205;</div>',
                iconSize: [30, 30], iconAnchor: [15, 30], className: ''
            });
            var bounds = [];
            mapData.forEach(function(a) {
                var popup = '<div style="min-width:160px">'
                    + (a.image ? '<img src="'+a.image+'" style="width:100%;height:80px;object-fit:cover;border-radius:6px;margin-bottom:6px">' : '')
                    + '<strong style="font-size:.82rem;display:block;margin-bottom:2px">'+a.localisation+'</strong>'
                    + '<span style="color:#2563eb;font-weight:700">'+parseFloat(a.price).toLocaleString('fr-FR')+' XOF</span>'
                    + (a.slug ? '<br><a href="/marketplace/annonce/'+a.slug+'" style="font-size:.75rem;color:#2563eb;margin-top:4px;display:inline-block">Voir l\'annonce \u2192</a>' : '')
                    + '</div>';
                L.marker([a.lat, a.lng], {icon: icon}).addTo(m).bindPopup(popup);
                bounds.push([a.lat, a.lng]);
            });
            if (bounds.length > 0) {
                try { m.fitBounds(bounds, {padding:[40,40]}); } catch(e){}
            }
        }
    })();
    </script>

    <!-- Portfolio Section -->
    <section id="portfolio" class="py-12 sm:py-16 lg:py-20 bg-white">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-10 text-center">
                <span class="inline-block px-4 py-1 mb-4 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full" data-i18n="portfolio.badge">Interface moderne</span>
                <h2 class="mb-4 text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl lg:text-5xl">
                    <span data-i18n="portfolio.h2a">Aperçu de la</span> <span class="text-blue-600" data-i18n="portfolio.h2b">plateforme</span>
                </h2>
                <p class="max-w-2xl mx-auto text-base text-gray-500 sm:text-lg" data-i18n="portfolio.sub">
                    Explorez les écrans intuitifs conçus pour simplifier votre gestion immobilière
                </p>
            </div>

            <!-- Tabs -->
            <div class="ap-tabs">
                <button class="ap-tab active" onclick="switchApScreen(this,'ap-dashboard')"><i class="fas fa-tachometer-alt"></i> <span data-i18n="portfolio.tab1">Dashboard</span></button>
                <button class="ap-tab" onclick="switchApScreen(this,'ap-biens')"><i class="fas fa-home"></i> <span data-i18n="portfolio.tab2">Biens</span></button>
                <button class="ap-tab" onclick="switchApScreen(this,'ap-locataires')"><i class="fas fa-user-friends"></i> <span data-i18n="portfolio.tab3">Locataires</span></button>
                <button class="ap-tab" onclick="switchApScreen(this,'ap-facturation')"><i class="fas fa-receipt"></i> <span data-i18n="portfolio.tab4">Facturation</span></button>
                <button class="ap-tab" onclick="switchApScreen(this,'ap-stats')"><i class="fas fa-chart-bar"></i> <span data-i18n="portfolio.tab5">Statistiques</span></button>
                <button class="ap-tab" onclick="switchApScreen(this,'ap-rapports')"><i class="fas fa-file-pdf"></i> <span data-i18n="portfolio.tab6">Rapports</span></button>
            </div>

            <!-- Screen mockup -->
            <div class="ap-screen-wrap">
                <!-- Browser bar -->
                <div class="ap-browser-bar">
                    <div class="ap-browser-dots">
                        <span style="background:#ef4444;"></span>
                        <span style="background:#f59e0b;"></span>
                        <span style="background:#22c55e;"></span>
                    </div>
                    <div class="ap-browser-url">app.lokativ.com/home</div>
                    <i class="fas fa-redo-alt" style="font-size:11px;color:#94a3b8;"></i>
                </div>

                <!-- Dashboard -->
                <div id="ap-dashboard" class="ap-screen active">
                    <div class="ap-screen-inner">
                        <div class="mock-topbar">
                            <div class="mock-title">Tableau de bord</div>
                            <span class="mock-badge" style="background:#eff6ff;color:#2563eb;">Avril 2026</span>
                        </div>
                        <div class="mock-cards-row">
                            <div class="mock-card">
                                <div class="mock-icon-circle" style="background:#eff6ff;color:#2563eb;"><i class="fas fa-home" style="font-size:13px;"></i></div>
                                <div class="mc-label">Biens</div><div class="mc-value">24</div><div class="mc-sub">↑ 2 ce mois</div>
                            </div>
                            <div class="mock-card">
                                <div class="mock-icon-circle" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-user-friends" style="font-size:13px;"></i></div>
                                <div class="mc-label">Locataires</div><div class="mc-value">38</div><div class="mc-sub">↑ 4 actifs</div>
                            </div>
                            <div class="mock-card">
                                <div class="mock-icon-circle" style="background:#fef9c3;color:#92400e;"><i class="fas fa-money-bill-wave" style="font-size:13px;"></i></div>
                                <div class="mc-label">Revenus</div><div class="mc-value" style="font-size:0.9rem;">845k</div><div class="mc-sub">↑ +12%</div>
                            </div>
                            <div class="mock-card">
                                <div class="mock-icon-circle" style="background:#fef2f2;color:#dc2626;"><i class="fas fa-exclamation-triangle" style="font-size:13px;"></i></div>
                                <div class="mc-label">Impayés</div><div class="mc-value">3</div><div class="mc-sub" style="color:#ef4444;">↓ -1</div>
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div style="background:#fff;border:1px solid #f1f5f9;border-radius:12px;padding:14px;">
                                <div style="font-size:0.72rem;font-weight:700;color:#64748b;margin-bottom:10px;">Encaissements 6 derniers mois</div>
                                <div class="mock-chart-bar">
                                    <div class="mock-bar" style="height:45%;background:#bfdbfe;"></div>
                                    <div class="mock-bar" style="height:62%;background:#93c5fd;"></div>
                                    <div class="mock-bar" style="height:78%;background:#60a5fa;"></div>
                                    <div class="mock-bar" style="height:55%;background:#3b82f6;"></div>
                                    <div class="mock-bar" style="height:88%;background:#2563eb;"></div>
                                    <div class="mock-bar" style="height:95%;background:#1d4ed8;"></div>
                                </div>
                            </div>
                            <div style="background:#fff;border:1px solid #f1f5f9;border-radius:12px;padding:14px;">
                                <div style="font-size:0.72rem;font-weight:700;color:#64748b;margin-bottom:10px;">Taux d'occupation</div>
                                <div style="display:flex;align-items:center;gap:10px;margin-top:8px;">
                                    <div style="width:60px;height:60px;border-radius:50%;background:conic-gradient(#2563eb 0% 87%,#e2e8f0 87% 100%);flex-shrink:0;"></div>
                                    <div><div style="font-size:1.4rem;font-weight:800;color:#1e293b;">87%</div><div style="font-size:0.65rem;color:#94a3b8;">21/24 biens loués</div></div>
                                </div>
                                <div class="mock-progress" style="margin-top:14px;"><div class="mock-progress-fill" style="width:87%;background:linear-gradient(90deg,#2563eb,#60a5fa);"></div></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Biens -->
                <div id="ap-biens" class="ap-screen">
                    <div class="ap-screen-inner">
                        <div class="mock-topbar">
                            <div class="mock-title">Gestion des biens</div>
                            <span class="mock-badge" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-plus" style="font-size:9px;"></i> Ajouter un bien</span>
                        </div>
                        <div class="mock-list">
                            @foreach([
                                ['Villa Cocody','Abidjan — 4 chambres','#eff6ff','#2563eb','VC','Loué','#dcfce7','#16a34a',90],
                                ['Appartement Plateau','Cotonou — F3','#f0fdf4','#16a34a','AP','Disponible','#fef9c3','#92400e',0],
                                ['Studio Cadjehoun','Cotonou — Studio','#fef9c3','#92400e','SC','Loué','#dcfce7','#16a34a',100],
                                ['Maison Fidjrossè','Cotonou — 3 chambres','#faf5ff','#7c3aed','MF','Loué','#dcfce7','#16a34a',75],
                                ['Duplex Agla','Cotonou — 5 pièces','#fef2f2','#dc2626','DA','Intervention','#fee2e2','#dc2626',0],
                            ] as $bien)
                            <div class="mock-list-item">
                                <div class="mock-avatar" style="background:{{ $bien[2] }};color:{{ $bien[3] }};">{{ $bien[4] }}</div>
                                <div class="mock-list-text"><div class="t1">{{ $bien[0] }}</div><div class="t2">{{ $bien[1] }}</div></div>
                                @if($bien[8] > 0)<div style="width:50px;font-size:0.6rem;color:#64748b;text-align:right;flex-shrink:0;"><div class="mock-progress" style="margin-top:0;width:100%;"><div class="mock-progress-fill" style="width:{{ $bien[8] }}%;background:#2563eb;"></div></div><div style="margin-top:2px;">{{ $bien[8] }}%</div></div>@endif
                                <span class="mock-pill" style="background:{{ $bien[6] }};color:{{ $bien[7] }};">{{ $bien[5] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Locataires -->
                <div id="ap-locataires" class="ap-screen">
                    <div class="ap-screen-inner">
                        <div class="mock-topbar">
                            <div class="mock-title">Locataires</div>
                            <span class="mock-badge" style="background:#eff6ff;color:#2563eb;">38 au total</span>
                        </div>
                        <div class="mock-list">
                            @foreach([
                                ['Kokou Amavi','Villa Cocody · Contrat actif','#eff6ff','#2563eb','KA','À jour','#dcfce7','#16a34a'],
                                ['Fatima Ouédraogo','Studio Cadjehoun · 14 mois','#f0fdf4','#16a34a','FO','À jour','#dcfce7','#16a34a'],
                                ['Jean-Pierre Dossou','Appartement Plateau · Bientôt','#fef9c3','#92400e','JD','Impayé','#fee2e2','#dc2626'],
                                ['Awa Koné','Duplex Agla · 6 mois','#faf5ff','#7c3aed','AK','À jour','#dcfce7','#16a34a'],
                                ['Serge Mensah','Maison Fidjrossè · 2 ans','#fef2f2','#dc2626','SM','En attente','#fef9c3','#92400e'],
                            ] as $loc)
                            <div class="mock-list-item">
                                <div class="mock-avatar" style="background:{{ $loc[2] }};color:{{ $loc[3] }};">{{ $loc[4] }}</div>
                                <div class="mock-list-text"><div class="t1">{{ $loc[0] }}</div><div class="t2">{{ $loc[1] }}</div></div>
                                <span class="mock-pill" style="background:{{ $loc[6] }};color:{{ $loc[7] }};">{{ $loc[5] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Facturation -->
                <div id="ap-facturation" class="ap-screen">
                    <div class="ap-screen-inner">
                        <div class="mock-topbar">
                            <div class="mock-title">Facturation</div>
                            <span class="mock-badge" style="background:#eff6ff;color:#2563eb;">Avril 2026</span>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-bottom:14px;">
                            <div class="mock-card" style="border-color:#dcfce7;"><div class="mc-label">Payées</div><div class="mc-value" style="color:#16a34a;">29</div></div>
                            <div class="mock-card" style="border-color:#fee2e2;"><div class="mc-label">En retard</div><div class="mc-value" style="color:#dc2626;">3</div></div>
                            <div class="mock-card" style="border-color:#fef9c3;"><div class="mc-label">En attente</div><div class="mc-value" style="color:#92400e;">6</div></div>
                        </div>
                        <div class="mock-list">
                            @foreach([
                                ['FAC-2026-041','Kokou Amavi','85 000 F','Payée','#dcfce7','#16a34a'],
                                ['FAC-2026-042','Fatima Ouédraogo','65 000 F','Payée','#dcfce7','#16a34a'],
                                ['FAC-2026-043','Jean-Pierre Dossou','120 000 F','En retard','#fee2e2','#dc2626'],
                                ['FAC-2026-044','Awa Koné','95 000 F','Envoyée','#fef9c3','#92400e'],
                            ] as $fac)
                            <div class="mock-list-item">
                                <div style="font-size:0.65rem;font-weight:700;color:#64748b;font-family:monospace;flex-shrink:0;">{{ $fac[0] }}</div>
                                <div class="mock-list-text"><div class="t1">{{ $fac[1] }}</div><div class="t2">{{ $fac[2] }}</div></div>
                                <span class="mock-pill" style="background:{{ $fac[4] }};color:{{ $fac[5] }};">{{ $fac[3] }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div id="ap-stats" class="ap-screen">
                    <div class="ap-screen-inner">
                        <div class="mock-topbar">
                            <div class="mock-title">Statistiques & Analyses</div>
                            <span class="mock-badge" style="background:#eff6ff;color:#2563eb;">12 mois</span>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div style="background:#fff;border:1px solid #f1f5f9;border-radius:12px;padding:14px;">
                                <div style="font-size:0.72rem;font-weight:700;color:#64748b;margin-bottom:10px;">Revenus mensuels (FCFA)</div>
                                <div class="mock-chart-bar" style="height:100px;">
                                    <div class="mock-bar" style="height:50%;background:#bfdbfe;"></div>
                                    <div class="mock-bar" style="height:65%;background:#93c5fd;"></div>
                                    <div class="mock-bar" style="height:55%;background:#60a5fa;"></div>
                                    <div class="mock-bar" style="height:80%;background:#3b82f6;"></div>
                                    <div class="mock-bar" style="height:70%;background:#2563eb;"></div>
                                    <div class="mock-bar" style="height:90%;background:#1d4ed8;"></div>
                                    <div class="mock-bar" style="height:75%;background:#1e40af;"></div>
                                    <div class="mock-bar" style="height:95%;background:#1e3a8a;"></div>
                                </div>
                            </div>
                            <div style="background:#fff;border:1px solid #f1f5f9;border-radius:12px;padding:14px;">
                                <div style="font-size:0.72rem;font-weight:700;color:#64748b;margin-bottom:10px;">Répartition par type</div>
                                <div style="display:flex;flex-direction:column;gap:8px;margin-top:10px;">
                                    @foreach([['Appartements','#2563eb',60],['Villas','#16a34a',25],['Studios','#7c3aed',15]] as $cat)
                                    <div>
                                        <div style="display:flex;justify-content:space-between;font-size:0.65rem;color:#64748b;margin-bottom:3px;"><span>{{ $cat[0] }}</span><span>{{ $cat[2] }}%</span></div>
                                        <div class="mock-progress" style="margin-top:0;"><div class="mock-progress-fill" style="width:{{ $cat[2] }}%;background:{{ $cat[1] }};"></div></div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:12px;">
                            <div class="mock-card"><div class="mc-label">Taux occupation</div><div class="mc-value">87%</div><div class="mc-sub">↑ +5%</div></div>
                            <div class="mock-card"><div class="mc-label">Revenu moyen</div><div class="mc-value" style="font-size:0.85rem;">82k F</div><div class="mc-sub">↑ +8%</div></div>
                            <div class="mock-card"><div class="mc-label">Taux recouvrement</div><div class="mc-value">94%</div><div class="mc-sub">↑ +2%</div></div>
                        </div>
                    </div>
                </div>

                <!-- Rapports -->
                <div id="ap-rapports" class="ap-screen">
                    <div class="ap-screen-inner">
                        <div class="mock-topbar">
                            <div class="mock-title">Rapports & Documents</div>
                            <span class="mock-badge" style="background:#fef2f2;color:#dc2626;"><i class="fas fa-file-pdf" style="font-size:9px;"></i> PDF</span>
                        </div>
                        <div class="mock-list">
                            @foreach([
                                ['Relevé de compte — Mars 2026','Généré le 01/04/2026','fa-file-alt','#eff6ff','#2563eb'],
                                ['Quittances de loyer — Q1 2026','38 quittances · PDF groupé','fa-file-invoice','#f0fdf4','#16a34a'],
                                ['Rapport financier annuel 2025','Revenus, charges, bénéfice net','fa-chart-line','#faf5ff','#7c3aed'],
                                ['Contrats locataires actifs','24 contrats · Format PDF','fa-file-contract','#fef9c3','#92400e'],
                                ['Rapport d\'impayés — Avril 2026','3 locataires concernés','fa-exclamation-circle','#fef2f2','#dc2626'],
                            ] as $rap)
                            <div class="mock-list-item" style="cursor:pointer;">
                                <div class="mock-icon-circle" style="background:{{ $rap[3] }};color:{{ $rap[4] }};width:32px;height:32px;flex-shrink:0;"><i class="fas {{ $rap[2] }}" style="font-size:13px;"></i></div>
                                <div class="mock-list-text"><div class="t1">{{ $rap[0] }}</div><div class="t2">{{ $rap[1] }}</div></div>
                                <i class="fas fa-download" style="font-size:11px;color:#94a3b8;margin-left:auto;flex-shrink:0;"></i>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    function switchApScreen(btn, screenId) {
        document.querySelectorAll('.ap-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.ap-screen').forEach(s => s.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(screenId).classList.add('active');
    }
    </script>

    <!-- Témoignages Section -->
    <section id="temoignages" class="py-12 sm:py-16 lg:py-20" style="background:linear-gradient(135deg,#f8faff 0%,#eef2ff 100%);">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-12 text-center">
                <span class="inline-block px-4 py-1 mb-4 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full" data-i18n="sec.testi.badge">Témoignages</span>
                <h2 class="mb-4 text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl lg:text-5xl">
                    <span data-i18n="sec.testi.h2a">Ce que disent</span> nos <span class="text-blue-600" data-i18n="sec.testi.h2b">clients</span>
                </h2>
                <p class="max-w-2xl mx-auto text-base text-gray-500 sm:text-lg" data-i18n="sec.testi.sub">
                    Des milliers de gestionnaires font confiance à Lokativ pour simplifier leur quotidien.
                </p>
            </div>

            @php
            $temoignages = [
                [
                    'initials' => 'KA',
                    'name'     => 'Kokou Amavi',
                    'role'     => 'Propriétaire · Cotonou',
                    'text'     => 'Lokativ a révolutionné ma façon de gérer mes 5 appartements. Je gagne un temps précieux chaque mois sur la facturation et le suivi des paiements.',
                    'stars'    => 5,
                    'accent'   => '#2563eb',
                    'bg'       => '#eff6ff',
                    'bar'      => '#2563eb',
                    'tag'      => 'Propriétaire particulier',
                    'biens'    => '5 biens',
                ],
                [
                    'initials' => 'AS',
                    'name'     => 'Agence Sunrise',
                    'role'     => 'Directeur · Porto-Novo',
                    'text'     => 'En tant qu\'agence immobilière, nous gérons plus de 50 biens. Lokativ nous permet de tout centraliser et d\'offrir un meilleur service à nos clients propriétaires.',
                    'stars'    => 5,
                    'accent'   => '#16a34a',
                    'bg'       => '#f0fdf4',
                    'bar'      => '#16a34a',
                    'tag'      => 'Agence immobilière',
                    'biens'    => '50+ biens',
                ],
                [
                    'initials' => 'MD',
                    'name'     => 'Marie Dossou',
                    'role'     => 'Investisseur · Abidjan',
                    'text'     => 'Les rappels automatiques m\'ont permis de réduire les retards de paiement de 80 %. Le tableau de bord est très intuitif et les rapports PDF me sauvent chaque mois.',
                    'stars'    => 4,
                    'accent'   => '#7c3aed',
                    'bg'       => '#faf5ff',
                    'bar'      => '#7c3aed',
                    'tag'      => 'Investisseur immobilier',
                    'biens'    => '12 biens',
                ],
                [
                    'initials' => 'BS',
                    'name'     => 'Brice Sossou',
                    'role'     => 'Promoteur · Lomé',
                    'text'     => 'La gestion multi-agences est exactement ce dont nous avions besoin. Chaque annexe a ses propres données et nous voyons tout depuis un seul compte admin.',
                    'stars'    => 5,
                    'accent'   => '#0891b2',
                    'bg'       => '#ecfeff',
                    'bar'      => '#0891b2',
                    'tag'      => 'Promoteur immobilier',
                    'biens'    => '3 agences',
                ],
                [
                    'initials' => 'FO',
                    'name'     => 'Fatima Ouédraogo',
                    'role'     => 'Gestionnaire · Ouagadougou',
                    'text'     => 'L\'envoi automatique des quittances par WhatsApp a changé la vie de mes locataires et la mienne. Plus besoin de courir après les paiements !',
                    'stars'    => 5,
                    'accent'   => '#d97706',
                    'bg'       => '#fffbeb',
                    'bar'      => '#d97706',
                    'tag'      => 'Gestionnaire de biens',
                    'biens'    => '8 biens',
                ],
                [
                    'initials' => 'JD',
                    'name'     => 'Jean-Paul Dossou',
                    'role'     => 'Propriétaire · Dakar',
                    'text'     => 'Support client réactif, interface claire et toutes les fonctionnalités qu\'on peut espérer pour une gestion locative professionnelle. Je recommande !',
                    'stars'    => 5,
                    'accent'   => '#dc2626',
                    'bg'       => '#fef2f2',
                    'bar'      => '#dc2626',
                    'tag'      => 'Propriétaire particulier',
                    'biens'    => '7 biens',
                ],
            ];
            @endphp

            @php
            $testiPalette = [
                ['accent'=>'#2563eb','bg'=>'#eff6ff','bar'=>'#2563eb'],
                ['accent'=>'#16a34a','bg'=>'#f0fdf4','bar'=>'#16a34a'],
                ['accent'=>'#7c3aed','bg'=>'#faf5ff','bar'=>'#7c3aed'],
                ['accent'=>'#0891b2','bg'=>'#ecfeff','bar'=>'#0891b2'],
                ['accent'=>'#d97706','bg'=>'#fffbeb','bar'=>'#d97706'],
                ['accent'=>'#dc2626','bg'=>'#fef2f2','bar'=>'#dc2626'],
                ['accent'=>'#0d9488','bg'=>'#f0fdfa','bar'=>'#0d9488'],
                ['accent'=>'#9333ea','bg'=>'#fdf4ff','bar'=>'#9333ea'],
            ];
            @endphp

            <!-- Défilement infini des témoignages -->
            <div style="position:relative;overflow:hidden;
                        -webkit-mask:linear-gradient(90deg,transparent,#000 7%,#000 93%,transparent);
                        mask:linear-gradient(90deg,transparent,#000 7%,#000 93%,transparent);">
                <div id="testi-track" style="display:flex;gap:1.5rem;width:max-content;will-change:transform;">

                    {{-- Témoignages par défaut --}}
                    @foreach($temoignages as $t)
                    <div class="testi-card" style="min-width:310px;max-width:310px;flex-shrink:0;border-top-color:{{ $t['bar'] }};border-top-width:3px;border-top-style:solid;">
                        <div class="testi-quote-mark">"</div>
                        <div class="testi-stars">
                            @for($s = 1; $s <= 5; $s++)
                                <i class="{{ $s <= $t['stars'] ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                            <span style="font-size:0.72rem;color:#94a3b8;margin-left:6px;font-weight:600;">{{ $t['stars'] }}.0</span>
                        </div>
                        <p class="testi-text">"{{ $t['text'] }}"</p>
                        <div class="testi-footer">
                            <div class="testi-avatar" style="background:{{ $t['accent'] }};">{{ $t['initials'] }}</div>
                            <div style="flex:1;min-width:0;">
                                <div class="testi-name">{{ $t['name'] }}</div>
                                <div class="testi-role">{{ $t['role'] }}</div>
                                <div style="display:flex;align-items:center;gap:6px;margin-top:5px;flex-wrap:wrap;">
                                    <span style="font-size:0.65rem;font-weight:700;padding:2px 8px;border-radius:20px;background:{{ $t['bg'] }};color:{{ $t['accent'] }};">{{ $t['tag'] }}</span>
                                    <span style="font-size:0.65rem;font-weight:700;padding:2px 8px;border-radius:20px;background:#f1f5f9;color:#475569;"><i class="fas fa-home" style="font-size:8px;margin-right:3px;"></i>{{ $t['biens'] }}</span>
                                </div>
                            </div>
                            <i class="fas fa-check-circle" style="font-size:16px;color:{{ $t['accent'] }};opacity:0.7;flex-shrink:0;"></i>
                        </div>
                    </div>
                    @endforeach

                    {{-- Témoignages soumis par les utilisateurs --}}
                    @foreach($temoignagesDb as $t)
                    @php
                        $colors   = $testiPalette[$t->id % count($testiPalette)];
                        $words    = preg_split('/\s+/', trim($t->nom));
                        $initials = collect($words)->take(2)->map(fn($w) => mb_strtoupper(mb_substr($w,0,1)))->implode('');
                    @endphp
                    <div class="testi-card" style="min-width:310px;max-width:310px;flex-shrink:0;border-top-color:{{ $colors['bar'] }};border-top-width:3px;border-top-style:solid;">
                        <span class="testi-new-badge">✦ Utilisateur</span>
                        <div class="testi-quote-mark">"</div>
                        <div class="testi-stars" style="margin-top:18px;">
                            @for($s = 1; $s <= 5; $s++)
                                <i class="{{ $s <= $t->etoiles ? 'fas' : 'far' }} fa-star"></i>
                            @endfor
                            <span style="font-size:0.72rem;color:#94a3b8;margin-left:6px;font-weight:600;">{{ $t->etoiles }}.0</span>
                        </div>
                        <p class="testi-text">"{{ $t->texte }}"</p>
                        <div class="testi-footer">
                            <div class="testi-avatar" style="background:{{ $colors['accent'] }};">{{ $initials }}</div>
                            <div style="flex:1;min-width:0;">
                                <div class="testi-name">{{ $t->nom }}</div>
                                <div class="testi-role">{{ $t->role ?: 'Utilisateur Lokativ' }}</div>
                                <div style="margin-top:5px;">
                                    <span style="font-size:0.65rem;font-weight:700;padding:2px 8px;border-radius:20px;background:{{ $colors['bg'] }};color:{{ $colors['accent'] }};">Avis vérifié</span>
                                </div>
                            </div>
                            <i class="fas fa-check-circle" style="font-size:16px;color:{{ $colors['accent'] }};opacity:0.7;flex-shrink:0;"></i>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
            <style>
                @keyframes marquee-testi {
                    from { transform: translateX(0); }
                    to   { transform: translateX(-50%); }
                }
                #testi-track { animation: marquee-testi 55s linear infinite; }
                #testi-track:hover { animation-play-state: paused; }
                @media (max-width: 768px) {
                    #testi-track { animation-duration: 35s !important; }
                }
            </style>

            <!-- CTA sous témoignages -->
            <div class="mt-12 text-center">
                <p style="font-size:0.9rem;color:#64748b;margin-bottom:20px;">Rejoignez <strong style="color:#1e293b;">+500 gestionnaires</strong> qui font confiance à Lokativ</p>
                <div style="display:flex;flex-wrap:wrap;gap:14px;justify-content:center;">
                    <a href="#compte" class="inline-flex items-center gap-2 px-8 py-3 font-bold text-white rounded-full" style="background:linear-gradient(135deg,#1d4ed8,#2563eb);box-shadow:0 4px 20px rgba(37,99,235,0.3);text-decoration:none;font-size:0.92rem;">
                        <span data-i18n="sec.testi.cta1">Démarrer gratuitement</span> <i class="fas fa-arrow-right"></i>
                    </a>
                    <button id="openTestiModal" style="display:inline-flex;align-items:center;gap:8px;padding:0.75rem 1.75rem;border-radius:50px;border:2px solid #2563eb;background:#fff;color:#2563eb;font-weight:700;font-size:0.92rem;cursor:pointer;transition:all 0.25s;">
                        <span data-i18n="sec.testi.cta2">⭐ Partager votre avis</span>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Tarifs Section -->
    <section id="tarifs" class="py-12 sm:py-16 lg:py-20" style="background: linear-gradient(135deg, #f8faff 0%, #eef2ff 100%);">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-14 text-center">
                <span class="inline-block px-4 py-1 mb-4 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full" data-i18n="sec.pricing.badge">Tarification transparente</span>
                <h2 class="mb-4 text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl lg:text-5xl">
                    <span data-i18n="sec.pricing.h2a">Des plans adaptés à</span>
                    <span class="text-blue-600" data-i18n="sec.pricing.h2b">chaque besoin</span>
                </h2>
                <p class="max-w-2xl mx-auto text-base text-gray-500 sm:text-lg" data-i18n="sec.pricing.sub">
                    Démarrez gratuitement. Évoluez selon votre croissance.
                </p>
            </div>

            @php
                $colClass = count($plansActifs) <= 2 ? 'md:grid-cols-2' :
                            (count($plansActifs) === 3 ? 'md:grid-cols-3' : 'md:grid-cols-2 lg:grid-cols-4');
            @endphp

            <div class="grid gap-6 items-start {{ $colClass }}">
                @foreach ($plansActifs as $plan)
                @php
                    $isFree     = floatval($plan->prix_annuel) == 0;
                    $isFeatured = $plan->code === 'standard';
                    $isLast     = $loop->last;

                    if ($isFree) {
                        $accentClass  = 'plan-free';
                        $accentColor  = '#22c55e';
                        $priceColor   = '#16a34a';
                        $badgeBg      = '#dcfce7';
                        $badgeColor   = '#15803d';
                        $badgeLabel   = 'Essai gratuit';
                        $badgeI18nKey = 'plan.badge.free';
                        $badgeIcon    = 'fa-gift';
                        $btnStyle     = 'background:#22c55e;color:#fff;';
                        $btnHoverCls  = '';
                        $btnLabel     = 'Essayer gratuitement';
                        $btnI18nKey   = 'plan.btn.free';
                        $headerBg     = 'linear-gradient(135deg,#dcfce7,#bbf7d0)';
                        $iconColor    = '#16a34a';
                        $planIcon     = 'fa-seedling';
                    } elseif ($isFeatured) {
                        $accentClass  = 'plan-standard featured';
                        $accentColor  = '#3b82f6';
                        $priceColor   = '#1d4ed8';
                        $badgeBg      = '#eff6ff';
                        $badgeColor   = '#1d4ed8';
                        $badgeLabel   = 'Plus populaire';
                        $badgeI18nKey = 'plan.badge.featured';
                        $badgeIcon    = 'fa-star';
                        $btnStyle     = 'background:linear-gradient(135deg,#2563eb,#3b82f6);color:#fff;box-shadow:0 4px 14px rgba(59,130,246,0.4);';
                        $btnHoverCls  = '';
                        $btnLabel     = 'Choisir ce plan';
                        $btnI18nKey   = 'plan.btn.featured';
                        $headerBg     = 'linear-gradient(135deg,#eff6ff,#dbeafe)';
                        $iconColor    = '#2563eb';
                        $planIcon     = 'fa-rocket';
                    } elseif ($isLast && !$isFree) {
                        $accentClass  = 'plan-pro';
                        $accentColor  = '#a855f7';
                        $priceColor   = '#7e22ce';
                        $badgeBg      = '#faf5ff';
                        $badgeColor   = '#7e22ce';
                        $badgeLabel   = 'Entreprise';
                        $badgeI18nKey = 'plan.badge.pro';
                        $badgeIcon    = 'fa-building';
                        $btnStyle     = 'background:#fff;color:#7e22ce;border:2px solid #a855f7;';
                        $btnHoverCls  = '';
                        $btnLabel     = 'Nous contacter';
                        $btnI18nKey   = 'plan.btn.pro';
                        $headerBg     = 'linear-gradient(135deg,#faf5ff,#f3e8ff)';
                        $iconColor    = '#a855f7';
                        $planIcon     = 'fa-crown';
                    } else {
                        $accentClass  = 'plan-default';
                        $accentColor  = '#64748b';
                        $priceColor   = '#334155';
                        $badgeBg      = '#f1f5f9';
                        $badgeColor   = '#475569';
                        $badgeLabel   = 'Débutant';
                        $badgeI18nKey = 'plan.badge.default';
                        $badgeIcon    = 'fa-bolt';
                        $btnStyle     = 'background:#fff;color:#3b82f6;border:2px solid #3b82f6;';
                        $btnHoverCls  = '';
                        $btnLabel     = 'Commencer';
                        $btnI18nKey   = 'plan.btn.default';
                        $headerBg     = 'linear-gradient(135deg,#f8fafc,#f1f5f9)';
                        $iconColor    = '#64748b';
                        $planIcon     = 'fa-chart-line';
                    }

                    // Libellé maisons
                    if ($plan->max_maisons === null || $plan->max_maisons === 0) {
                        $maisonLabel = '<span data-i18n-html="plan.maisons.unlimited">Maisons <strong>illimitées</strong></span>';
                    } else {
                        $maisonLabel = '<span data-i18n="plan.maisons.upto">Jusqu\'à</span> <strong>' . $plan->max_maisons . ' <span data-i18n="plan.maisons.unit">maisons</span></strong>';
                    }

                    // Libellé annexes
                    if ($plan->max_annexes === null) {
                        $annexeLabel = '<span data-i18n-html="plan.annexes.unlimited">Agences <strong>illimitées</strong></span>';
                        $annexeOk    = true;
                    } elseif ($plan->max_annexes > 0) {
                        $annexeLabel = '<span data-i18n="plan.annexes.upto">Jusqu\'à</span> <strong>' . $plan->max_annexes . ' <span data-i18n="plan.annexes.unit">agence(s)</span></strong>';
                        $annexeOk    = true;
                    } else {
                        $annexeLabel = '<span data-i18n="plan.annexes.none">Multi-agences</span>';
                        $annexeOk    = false;
                    }
                @endphp

                <div class="pricing-card {{ $accentClass }}" style="position:relative;">

                    {{-- Badge Populaire --}}
                    @if($isFeatured)
                        <div class="plan-badge-popular">
                            <i class="fas fa-star me-1" style="font-size:9px;"></i> <span data-i18n="plan.popular">Populaire</span>
                        </div>
                    @endif

                    {{-- En-tête --}}
                    <div class="plan-header" style="background:{{ $headerBg }};">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
                            <div style="width:42px;height:42px;border-radius:12px;background:{{ $accentColor }}22;display:flex;align-items:center;justify-content:center;">
                                <i class="fas {{ $planIcon }}" style="color:{{ $iconColor }};font-size:18px;"></i>
                            </div>
                            <span style="display:inline-flex;align-items:center;gap:4px;background:{{ $badgeBg }};color:{{ $badgeColor }};font-size:0.7rem;font-weight:700;padding:3px 10px;border-radius:20px;">
                                <i class="fas {{ $badgeIcon }}" style="font-size:9px;"></i>
                                <span data-i18n="{{ $badgeI18nKey }}">{{ $badgeLabel }}</span>
                            </span>
                        </div>
                        <h3 style="font-size:1.2rem;font-weight:800;color:#1e293b;margin:0 0 0.25rem;">{{ $plan->nom }}</h3>
                        @if($plan->description)
                            <p style="font-size:0.82rem;color:#64748b;margin:0;">{{ $plan->description }}</p>
                        @endif
                    </div>

                    {{-- Prix --}}
                    <div class="plan-price-block">
                        @if($isFree)
                            <div style="display:flex;align-items:baseline;gap:4px;padding:0.75rem 0 0.5rem;">
                                <span style="font-size:2.4rem;font-weight:900;color:{{ $priceColor }};line-height:1;">0</span>
                                <span style="font-size:1rem;color:#94a3b8;font-weight:500;">XOF</span>
                            </div>
                            <p style="font-size:0.78rem;color:#64748b;margin:0;" data-i18n-html="plan.trial.text">Pendant <strong>14 jours</strong> · Sans CB requise</p>
                        @else
                            <div style="display:flex;align-items:baseline;gap:4px;padding:0.75rem 0 0.25rem;">
                                <span style="font-size:2.1rem;font-weight:900;color:{{ $priceColor }};line-height:1;">{{ number_format($plan->prix_annuel, 0, ',', ' ') }}</span>
                                <span style="font-size:0.9rem;color:#94a3b8;font-weight:500;">XOF / an</span>
                            </div>
                            <p style="font-size:0.78rem;color:#64748b;margin:0;"><span data-i18n="plan.price.soit">soit</span> {{ number_format($plan->prix_annuel / 12, 0, ',', ' ') }} XOF <span data-i18n="plan.price.permonth">/ mois</span></p>
                        @endif
                    </div>

                    {{-- Fonctionnalités --}}
                    <div class="plan-features">

                        {{-- Gestion --}}
                        <p class="plan-section-label" data-i18n="plan.cat.gestion">Gestion immobilière</p>

                        <div class="plan-feature-item">
                            <i class="fas fa-home plan-feature-icon" style="color:{{ $accentColor }};"></i>
                            <span>{!! $maisonLabel !!}</span>
                        </div>
                        <div class="plan-feature-item {{ $annexeOk ? '' : 'disabled' }}">
                            <i class="fas fa-{{ $annexeOk ? 'building' : 'times-circle' }} plan-feature-icon" style="color:{{ $annexeOk ? $accentColor : '#d1d5db' }};"></i>
                            <span>{!! $annexeLabel !!}</span>
                        </div>
                        <div class="plan-feature-item">
                            <i class="fas fa-check-circle plan-feature-icon" style="color:{{ $accentColor }};"></i>
                            <span data-i18n="plan.feat.dashboard">Tableau de bord complet</span>
                        </div>
                        <div class="plan-feature-item">
                            <i class="fas fa-check-circle plan-feature-icon" style="color:{{ $accentColor }};"></i>
                            <span data-i18n="plan.feat.tenants">Gestion des locataires</span>
                        </div>
                        <div class="plan-feature-item">
                            <i class="fas fa-check-circle plan-feature-icon" style="color:{{ $accentColor }};"></i>
                            <span data-i18n="plan.feat.billing">Facturation automatique</span>
                        </div>

                        {{-- Communication --}}
                        <p class="plan-section-label" data-i18n="plan.cat.comm">Communication</p>

                        {{-- Email --}}
                        @if($plan->max_envois_email === null)
                            <div class="plan-feature-item">
                                <i class="fas fa-envelope plan-feature-icon" style="color:#3b82f6;"></i>
                                <span data-i18n-html="plan.emails.unlimited"><strong>Emails illimités</strong> / mois</span>
                            </div>
                        @elseif($plan->max_envois_email > 0)
                            <div class="plan-feature-item">
                                <i class="fas fa-envelope plan-feature-icon" style="color:#3b82f6;"></i>
                                <span><strong>{{ $plan->max_envois_email }}</strong> <span data-i18n="plan.emails.suffix">email(s) / mois</span></span>
                            </div>
                        @else
                            <div class="plan-feature-item disabled">
                                <i class="fas fa-times-circle plan-feature-icon" style="color:#d1d5db;"></i>
                                <span data-i18n="plan.emails.none">Envoi par email</span>
                            </div>
                        @endif

                        {{-- Rappels loyer --}}
                        @if($plan->max_rappels_loyer === null)
                            <div class="plan-feature-item">
                                <i class="fas fa-bell plan-feature-icon" style="color:#f59e0b;"></i>
                                <span data-i18n-html="plan.rappels.unlimited"><strong>Rappels loyer illimités</strong> (email)</span>
                            </div>
                        @elseif(isset($plan->max_rappels_loyer) && $plan->max_rappels_loyer > 0)
                            <div class="plan-feature-item">
                                <i class="fas fa-bell plan-feature-icon" style="color:#f59e0b;"></i>
                                <span><strong>{{ $plan->max_rappels_loyer }}</strong> <span data-i18n="plan.rappels.suffix">rappel(s) loyer / mois (email)</span></span>
                            </div>
                        @else
                            <div class="plan-feature-item disabled">
                                <i class="fas fa-times-circle plan-feature-icon" style="color:#d1d5db;"></i>
                                <span data-i18n="plan.rappels.none">Rappels de loyer</span>
                            </div>
                        @endif

                        {{-- Préavis --}}
                        @if($plan->max_preavis === null)
                            <div class="plan-feature-item">
                                <i class="fas fa-door-open plan-feature-icon" style="color:#ef4444;"></i>
                                <span data-i18n-html="plan.preavis.unlimited"><strong>Préavis illimités</strong> / mois (email)</span>
                            </div>
                        @elseif(isset($plan->max_preavis) && $plan->max_preavis > 0)
                            <div class="plan-feature-item">
                                <i class="fas fa-door-open plan-feature-icon" style="color:#ef4444;"></i>
                                <span><strong>{{ $plan->max_preavis }}</strong> <span data-i18n="plan.preavis.suffix">préavis / mois (email)</span></span>
                            </div>
                        @else
                            <div class="plan-feature-item disabled">
                                <i class="fas fa-times-circle plan-feature-icon" style="color:#d1d5db;"></i>
                                <span data-i18n="plan.preavis.none">Envoi de préavis</span>
                            </div>
                        @endif

                        {{-- SMS / WA pay-per-use --}}
                        @if(($plan->sms_enabled ?? true) || ($plan->whatsapp_enabled ?? true))
                            <div class="plan-feature-item" style="flex-wrap:wrap;gap:4px;">
                                <i class="fas fa-comments plan-feature-icon" style="color:#10b981;"></i>
                                <span style="margin-right:4px;" data-i18n="plan.sms_wa">SMS &amp; WhatsApp</span>
                                <span class="badge-ppu"><i class="fas fa-coins" style="font-size:9px;"></i> <span data-i18n="plan.ppu">Pay-per-use</span></span>
                            </div>
                        @endif

                        {{-- Docs WhatsApp --}}
                        @if($plan->whatsapp_enabled ?? true)
                            <div class="plan-feature-item" style="flex-wrap:wrap;gap:4px;">
                                <i class="fab fa-whatsapp plan-feature-icon" style="color:#25d366;"></i>
                                <span style="margin-right:4px;" data-i18n="plan.docs_wa">Envoi docs WhatsApp</span>
                                <span class="badge-ppu"><i class="fas fa-coins" style="font-size:9px;"></i> <span data-i18n="plan.ppu">Pay-per-use</span></span>
                            </div>
                        @else
                            <div class="plan-feature-item disabled">
                                <i class="fas fa-times-circle plan-feature-icon" style="color:#d1d5db;"></i>
                                <span data-i18n="plan.docs_wa">Envoi docs WhatsApp</span>
                            </div>
                        @endif

                        {{-- Publicités --}}
                        @if($plan->max_publicites === null || $plan->max_publicites > 0)
                            <p class="plan-section-label" data-i18n="plan.cat.visib">Visibilité</p>
                            <div class="plan-feature-item">
                                <i class="fas fa-bullhorn plan-feature-icon" style="color:#f59e0b;"></i>
                                @if($plan->max_publicites === null)
                                    <span data-i18n-html="plan.ads.unlimited"><strong>Publicités illimitées</strong></span>
                                @else
                                    <span><strong>{{ $plan->max_publicites }}</strong> <span data-i18n="plan.ads.suffix">publicité(s) max</span></span>
                                @endif
                            </div>
                        @endif

                    </div>

                    {{-- CTA --}}
                    <a href="#compte" class="plan-btn" style="{{ $btnStyle }}">
                        <span data-i18n="plan.btn.select">Choisir ce plan</span>
                        <i class="fas fa-arrow-right ms-2" style="font-size:12px;"></i>
                    </a>
                </div>
                @endforeach
            </div>

            <!-- Note informative -->
            <div class="mt-12 text-center">
                <p class="text-sm text-gray-500">
                    <i class="mr-1 text-blue-500 fas fa-shield-alt"></i>
                    <span data-i18n-html="plan.note">Tous les plans incluent une <strong>période d'essai</strong> · Annulation facile · Support inclus</span>
                </p>
            </div>
        </div>
    </section>

    <!-- Créer un compte Section -->
    <section id="compte" style="background:linear-gradient(135deg,#0f172a 0%,#1e3a8a 50%,#1e40af 100%); padding:5rem 0;">
        <div class="px-4 mx-auto sm:px-6 lg:px-8" style="max-width:1100px;">

            <!-- ── Branding pleine largeur ── -->
            <div style="text-align:center;color:#fff;margin-bottom:2.5rem;">
                <span style="display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);color:#93c5fd;font-size:0.75rem;font-weight:700;padding:5px 14px;border-radius:20px;margin-bottom:1.25rem;letter-spacing:0.06em;text-transform:uppercase;">
                    <i class="fas fa-rocket" style="font-size:10px;"></i> <span data-i18n="reg.badge">Démarrez gratuitement</span>
                </span>
                <h2 style="font-size:2.4rem;font-weight:900;line-height:1.2;margin-bottom:0.75rem;letter-spacing:-0.02em;">
                    <span data-i18n="reg.h2">Créer votre compte</span><br>
                    <span style="background:linear-gradient(135deg,#60a5fa,#38bdf8);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">Lokativ</span>
                </h2>
                <p style="color:#94a3b8;font-size:0.95rem;line-height:1.7;max-width:520px;margin:0 auto 0;" data-i18n="reg.sub">
                    Rejoignez des centaines de gestionnaires immobiliers. Configuré en moins de 3 minutes.
                </p>

                <!-- Avantages — 3 colonnes -->
                <div class="compte-avantages" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:14px;max-width:780px;margin:1.75rem auto 1.5rem;">
                    <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.1);border-radius:14px;padding:16px 14px;display:flex;flex-direction:column;align-items:center;gap:8px;">
                        <div style="width:40px;height:40px;border-radius:11px;background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-shield-alt" style="color:#34d399;font-size:15px;"></i>
                        </div>
                        <p style="font-weight:700;font-size:0.88rem;color:#f1f5f9;margin:0;" data-i18n="reg.feat1.title">Données sécurisées</p>
                        <p style="font-size:0.76rem;color:#64748b;margin:0;line-height:1.4;" data-i18n="reg.feat1.sub">Chiffrement SSL · Sauvegarde quotidienne</p>
                    </div>
                    <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.1);border-radius:14px;padding:16px 14px;display:flex;flex-direction:column;align-items:center;gap:8px;">
                        <div style="width:40px;height:40px;border-radius:11px;background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-credit-card" style="color:#60a5fa;font-size:15px;"></i>
                        </div>
                        <p style="font-weight:700;font-size:0.88rem;color:#f1f5f9;margin:0;" data-i18n="reg.feat2.title">Sans CB pour l'essai</p>
                        <p style="font-size:0.76rem;color:#64748b;margin:0;line-height:1.4;" data-i18n="reg.feat2.sub">14 jours gratuits · Annulation facile</p>
                    </div>
                    <div style="background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.1);border-radius:14px;padding:16px 14px;display:flex;flex-direction:column;align-items:center;gap:8px;">
                        <div style="width:40px;height:40px;border-radius:11px;background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.3);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-headset" style="color:#c084fc;font-size:15px;"></i>
                        </div>
                        <p style="font-weight:700;font-size:0.88rem;color:#f1f5f9;margin:0;" data-i18n="reg.feat3.title">Support inclus</p>
                        <p style="font-size:0.76rem;color:#64748b;margin:0;line-height:1.4;" data-i18n="reg.feat3.sub">Assistance email &amp; WhatsApp</p>
                    </div>
                </div>

                <!-- Témoignage compact horizontal -->
                <div style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:14px;padding:14px 20px;max-width:580px;margin:0 auto;display:flex;align-items:center;gap:14px;text-align:left;">
                    <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#7c3aed);display:flex;align-items:center;justify-content:center;font-weight:800;color:#fff;font-size:14px;flex-shrink:0;">K</div>
                    <div>
                        <p style="font-size:0.82rem;color:#cbd5e1;font-style:italic;margin:0 0 4px;line-height:1.5;" data-i18n="reg.quote">« Lokativ a transformé ma façon de gérer mes 12 propriétés. Je gagne des heures chaque mois. »</p>
                        <p style="font-size:0.75rem;margin:0;"><strong style="color:#f1f5f9;">Kofi A.</strong> <span style="color:#64748b;" data-i18n="reg.quote.role">— Promoteur immobilier · Cotonou</span></p>
                    </div>
                </div>
            </div>

            <!-- ── Formulaire centré ── -->
            <div style="max-width:700px;margin:0 auto;">
                <div style="background:#fff;border-radius:24px;padding:2rem;box-shadow:0 25px 60px rgba(0,0,0,0.3);">

                    <!-- Barre de progression -->
                    <div class="progress-bar">
                        <div class="progress" id="progress-bar"></div>
                    </div>

                    <!-- Indicateur d'étapes -->
                    <div class="step-indicator">
                        <div class="step-item active" id="step-item-1">
                            <div class="step active" id="step-1">1</div>
                            <span class="step-label" data-i18n="reg.step1.label">Profil</span>
                        </div>
                        <div class="step-connector" id="connector-1"></div>
                        <div class="step-item" id="step-item-2">
                            <div class="step" id="step-2">2</div>
                            <span class="step-label" data-i18n="reg.step2.label">Infos</span>
                        </div>
                        <div class="step-connector" id="connector-2"></div>
                        <div class="step-item" id="step-item-3">
                            <div class="step" id="step-3">3</div>
                            <span class="step-label" data-i18n="reg.step3.label">Plan</span>
                        </div>
                    </div>

                    <!-- ── Étape 1 : Type de compte ── -->
                    <div class="step-form active" id="step-form-1">
                        <h3>
                            <span class="step-icon"><i class="fas fa-user-tag"></i></span>
                            <span data-i18n="reg.step1.title">Quel est votre profil ?</span>
                        </h3>

                        <div class="account-type-grid">
                            <div class="plan-card" data-type="particulier">
                                <div class="selected-check"><i class="fas fa-check" style="font-size:10px;"></i></div>
                                <div class="card-icon-wrap" style="background:linear-gradient(135deg,#dbeafe,#eff6ff);">
                                    <i class="fas fa-user" style="color:#2563eb;"></i>
                                </div>
                                <h4 data-i18n="reg.type.perso">Particulier</h4>
                                <p data-i18n="reg.type.perso.desc">Gérez votre patrimoine personnel en toute simplicité</p>
                                <ul>
                                    <li data-i18n="reg.type.perso.li1">Jusqu'à 10 propriétés</li>
                                    <li data-i18n="reg.type.perso.li2">Tableau de bord personnalisé</li>
                                    <li data-i18n="reg.type.perso.li3">Support par email</li>
                                </ul>
                            </div>
                            <div class="plan-card" data-type="entreprise">
                                <div class="selected-check"><i class="fas fa-check" style="font-size:10px;"></i></div>
                                <div class="card-icon-wrap" style="background:linear-gradient(135deg,#f3e8ff,#faf5ff);">
                                    <i class="fas fa-building" style="color:#7c3aed;"></i>
                                </div>
                                <h4 data-i18n="reg.type.biz">Entreprise</h4>
                                <p data-i18n="reg.type.biz.desc">Solution complète pour les professionnels de l'immobilier</p>
                                <ul>
                                    <li data-i18n="reg.type.biz.li1">Propriétés illimitées</li>
                                    <li data-i18n="reg.type.biz.li2">Multi-agences</li>
                                    <li data-i18n="reg.type.biz.li3">Support prioritaire</li>
                                </ul>
                            </div>
                        </div>

                        <input type="hidden" id="type_compte" name="type_compte">

                        <div class="form-navigation">
                            <div></div>
                            <button type="button" class="btn-reg-next" onclick="nextStep(2)" disabled id="btn-next-1">
                                <span data-i18n="reg.next">Continuer</span> <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- ── Étape 2 : Informations personnelles ── -->
                    <div class="step-form" id="step-form-2">
                        <h3>
                            <span class="step-icon"><i class="fas fa-id-card"></i></span>
                            <span data-i18n="reg.step2.title">Vos informations</span>
                        </h3>

                        <div class="row g-3">
                            <div class="col-6">
                                <div class="reg-field">
                                    <label for="nom" data-i18n="reg.label.nom">Nom</label>
                                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Dupont" required>
                                    <div class="invalid-feedback">Veuillez saisir votre nom.</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="reg-field">
                                    <label for="prenom" data-i18n="reg.label.prenom">Prénom</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Jean" required>
                                    <div class="invalid-feedback">Veuillez saisir votre prénom.</div>
                                </div>
                            </div>
                        </div>

                        <div class="reg-field">
                            <label for="email" data-i18n="reg.label.email">Adresse email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="vous@exemple.com" required>
                            <div class="invalid-feedback">Veuillez saisir une adresse email valide.</div>
                        </div>

                        <div class="reg-field">
                            <label for="telephone" data-i18n="reg.label.phone">Téléphone</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone">
                            <div class="invalid-feedback" id="telephone-error">Veuillez saisir un numéro de téléphone valide.</div>
                        </div>

                        <!-- Section entreprise -->
                        <div id="entreprise-section" style="display:none;">
                            <div class="entreprise-section-card">
                                <h5>
                                    <i class="fas fa-building"></i>
                                    <span data-i18n="reg.company.title">Informations de l'entreprise</span>
                                </h5>
                                <div class="reg-field">
                                    <label for="designation" data-i18n="reg.label.company">Nom de l'entreprise</label>
                                    <input type="text" class="form-control" id="designation" name="designation" placeholder="Immobilière XYZ">
                                    <div class="invalid-feedback">Veuillez saisir le nom de l'entreprise.</div>
                                </div>
                                <div class="reg-field">
                                    <label for="adresse" data-i18n="reg.label.address">Adresse</label>
                                    <input type="text" class="form-control" id="adresse" name="adresse" placeholder="123 rue de l'Immobilier">
                                    <div class="invalid-feedback">Veuillez saisir l'adresse.</div>
                                </div>
                                <div class="reg-field">
                                    <label for="email_entreprise" data-i18n="reg.label.company.email">Email de l'entreprise</label>
                                    <input type="email" class="form-control" id="email_entreprise" name="email_entreprise" placeholder="contact@entreprise.com">
                                    <div class="invalid-feedback">Veuillez saisir l'email de l'entreprise.</div>
                                </div>
                            </div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="btn-reg-prev" onclick="prevStep(1)">
                                <i class="fas fa-arrow-left"></i> <span data-i18n="reg.prev">Retour</span>
                            </button>
                            <button type="button" class="btn-reg-next" onclick="nextStep(3)" id="btn-next-2">
                                <span data-i18n="reg.next">Continuer</span> <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>

                    <!-- ── Étape 3 : Plan ── -->
                    <div class="step-form" id="step-form-3">
                        <h3>
                            <span class="step-icon"><i class="fas fa-layer-group"></i></span>
                            <span data-i18n="reg.step3.title">Choisissez votre plan</span>
                        </h3>

                        <div id="plans-container" style="display:grid;grid-template-columns:repeat(2,1fr);gap:0.75rem;">
                            <!-- Plans chargés dynamiquement -->
                        </div>

                        <!-- Sélecteur de durée — visible uniquement pour les plans payants -->
                        <div id="nb-mois-container" style="display:none;margin-top:0.75rem;padding:14px 16px;background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:12px;">
                            <label class="fw-semibold" style="font-size:0.88rem;color:#0369a1;display:block;margin-bottom:8px;">
                                <i class="fas fa-calendar-alt me-1"></i> Durée de l'abonnement
                            </label>
                            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                                <input type="number" id="nb_mois_reg" value="1" min="1" max="24"
                                       style="width:72px;padding:7px 10px;border:1.5px solid #bae6fd;border-radius:8px;font-size:1rem;text-align:center;font-weight:700;">
                                <span style="color:#475569;font-size:0.88rem;">mois</span>
                                <span id="total-preview-reg" style="margin-left:auto;font-weight:700;color:#1e40af;font-size:1rem;"></span>
                            </div>
                            <p style="margin-top:6px;margin-bottom:0;font-size:0.78rem;color:#64748b;">
                                <i class="fas fa-calendar-check me-1"></i> Fin d'abonnement estimée : <strong id="fin-date-preview"></strong>
                            </p>
                        </div>

                        <div style="margin-top:1.25rem;padding:14px 16px;background:#f8faff;border:1.5px solid #dbeafe;border-radius:12px;">
                            <div class="form-check" style="margin:0;">
                                <input type="checkbox" class="form-check-input" id="conditions" required>
                                <label class="form-check-label" for="conditions" style="font-size:0.82rem;color:#475569;line-height:1.5;">
                                    <span data-i18n="reg.cgu.prefix">J'accepte les</span> <a href="{{ route('legal.cgu') }}" target="_blank" class="text-primary fw-semibold" data-i18n="reg.cgu.link1">conditions générales d'utilisation</a> <span data-i18n="reg.cgu.and">et la</span> <a href="{{ route('legal.confidentialite') }}" target="_blank" class="text-primary fw-semibold" data-i18n="reg.cgu.link2">politique de confidentialité</a><span data-i18n="reg.cgu.dot">.</span>
                                </label>
                                <div class="invalid-feedback">Vous devez accepter les conditions pour continuer.</div>
                            </div>
                        </div>

                        <div class="form-navigation">
                            <button type="button" class="btn-reg-prev" onclick="prevStep(2)">
                                <i class="fas fa-arrow-left"></i> <span data-i18n="reg.prev">Retour</span>
                            </button>
                            <button type="button" class="btn-reg-submit" id="btn-submit" onclick="submitForm()" disabled>
                                <i class="fas fa-check-circle"></i>
                                <span id="btn-submit-label" data-i18n="reg.submit">Créer mon compte</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <!-- fin formulaire -->

        </div>
    </section>

    <style>
        @media (max-width: 600px) {
            #compte .compte-avantages { grid-template-columns: 1fr !important; }
            #plans-container { grid-template-columns: 1fr !important; }
        }
        @media (max-width: 480px) {
            #plans-container { grid-template-columns: 1fr !important; }
        }
        #plans-container .plan-card { text-align: left; }
    </style>

    <!-- FAQ Section -->
    <section id="sec-faq" class="py-12 sm:py-16 lg:py-20" style="background:linear-gradient(180deg,#f8faff 0%,#fff 100%);">
        <div class="px-4 mx-auto max-w-3xl sm:px-6 lg:px-8">

            <!-- En-tête -->
            <div class="mb-12 text-center">
                <span class="inline-block px-4 py-1 mb-4 text-sm font-semibold text-blue-700 bg-blue-100 rounded-full">
                    <i class="fas fa-circle-question me-1"></i> <span data-i18n="sec.faq.badge">Aide & Réponses</span>
                </span>
                <h2 class="mb-3 text-2xl font-bold text-gray-900 sm:text-3xl md:text-4xl">
                    <span data-i18n="sec.faq.h2a">Questions</span> <span class="text-blue-600" data-i18n="sec.faq.h2b">fréquentes</span>
                </h2>
                <p class="text-gray-500 text-base">
                    <span data-i18n="sec.faq.sub">Tout ce que vous devez savoir avant de démarrer.</span>
                    <a href="#contact" class="text-blue-600 font-medium hover:underline">Contactez-nous</a>.
                </p>
            </div>

            <!-- Groupe : Démarrage -->
            <div class="faq-category-label" style="display:flex;gap:10px;align-items:center;margin-bottom:1rem;">
                <span style="flex:1;height:1px;background:#e2e8f0;"></span>
                <span style="display:inline-flex;align-items:center;gap:6px;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">
                    <i class="fas fa-play-circle" style="color:#3b82f6;"></i> <span data-i18n="faq.cat.start">Démarrage</span>
                </span>
                <span style="flex:1;height:1px;background:#e2e8f0;"></span>
            </div>

            <div class="space-y-3 mb-6">
                <!-- FAQ 1 -->
                <div class="faq-item active">
                    <div class="faq-question">
                        <div class="faq-icon-wrap">
                            <i class="fas fa-rocket" style="color:#3b82f6;font-size:14px;"></i>
                        </div>
                        <h3 data-i18n="faq.q1">Comment puis-je commencer à utiliser Lokativ ?</h3>
                        <div class="faq-chevron">
                            <i class="fas fa-chevron-down" style="color:#64748b;font-size:11px;"></i>
                        </div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner" data-i18n-html="faq.a1">
                            C'est très simple ! Créez un compte gratuit en cliquant sur <strong>"Créer un compte"</strong>, renseignez vos informations et vous pourrez immédiatement ajouter vos propriétés. Aucune carte bancaire n'est requise pour l'offre d'essai.
                        </div>
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="faq-item">
                    <div class="faq-question">
                        <div class="faq-icon-wrap">
                            <i class="fas fa-shield-alt" style="color:#3b82f6;font-size:14px;"></i>
                        </div>
                        <h3 data-i18n="faq.q2">Mes données sont-elles sécurisées ?</h3>
                        <div class="faq-chevron">
                            <i class="fas fa-chevron-down" style="color:#64748b;font-size:11px;"></i>
                        </div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner" data-i18n-html="faq.a2">
                            Absolument. Nous utilisons un chiffrement <strong>SSL de bout en bout</strong> et nos serveurs sont hébergés dans des centres de données sécurisés. Vos données sont sauvegardées quotidiennement et ne sont jamais partagées avec des tiers.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Groupe : Abonnement & Paiement -->
            <div style="display:flex;gap:10px;align-items:center;margin-bottom:1rem;margin-top:1.5rem;">
                <span style="flex:1;height:1px;background:#e2e8f0;"></span>
                <span style="display:inline-flex;align-items:center;gap:6px;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">
                    <i class="fas fa-credit-card" style="color:#3b82f6;"></i> <span data-i18n="faq.cat.billing">Abonnement & Paiement</span>
                </span>
                <span style="flex:1;height:1px;background:#e2e8f0;"></span>
            </div>

            <div class="space-y-3 mb-6">
                <!-- FAQ 3 -->
                <div class="faq-item">
                    <div class="faq-question">
                        <div class="faq-icon-wrap">
                            <i class="fas fa-sync-alt" style="color:#3b82f6;font-size:14px;"></i>
                        </div>
                        <h3 data-i18n="faq.q3">Puis-je changer de plan à tout moment ?</h3>
                        <div class="faq-chevron">
                            <i class="fas fa-chevron-down" style="color:#64748b;font-size:11px;"></i>
                        </div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner" data-i18n-html="faq.a3">
                            Oui, vous pouvez passer à un plan supérieur à tout moment depuis votre espace <strong>"Mon abonnement"</strong>. Le paiement se fait via KKiaPay ou FedaPay. Vous pouvez également rétrograder à la fin de votre période d'abonnement.
                        </div>
                    </div>
                </div>

                <!-- FAQ 4 -->
                <div class="faq-item">
                    <div class="faq-question">
                        <div class="faq-icon-wrap">
                            <i class="fas fa-comments-dollar" style="color:#3b82f6;font-size:14px;"></i>
                        </div>
                        <h3 data-i18n="faq.q4">Comment fonctionne la facturation des SMS et WhatsApp ?</h3>
                        <div class="faq-chevron">
                            <i class="fas fa-chevron-down" style="color:#64748b;font-size:11px;"></i>
                        </div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner" data-i18n-html="faq.a4">
                            Les envois SMS et WhatsApp sont <strong>pay-per-use</strong> : vous payez uniquement ce que vous utilisez. Avant chaque envoi, le coût total est calculé selon votre pays et le nombre de destinataires. Le paiement s'effectue via l'agrégateur (KKiaPay ou FedaPay) et est vérifié avant l'envoi.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Groupe : Fonctionnalités -->
            <div style="display:flex;gap:10px;align-items:center;margin-bottom:1rem;margin-top:1.5rem;">
                <span style="flex:1;height:1px;background:#e2e8f0;"></span>
                <span style="display:inline-flex;align-items:center;gap:6px;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;">
                    <i class="fas fa-cogs" style="color:#3b82f6;"></i> <span data-i18n="faq.cat.features">Fonctionnalités</span>
                </span>
                <span style="flex:1;height:1px;background:#e2e8f0;"></span>
            </div>

            <div class="space-y-3">
                <!-- FAQ 5 -->
                <div class="faq-item">
                    <div class="faq-question">
                        <div class="faq-icon-wrap">
                            <i class="fas fa-file-invoice" style="color:#3b82f6;font-size:14px;"></i>
                        </div>
                        <h3 data-i18n="faq.q5">Comment fonctionne la facturation automatique ?</h3>
                        <div class="faq-chevron">
                            <i class="fas fa-chevron-down" style="color:#64748b;font-size:11px;"></i>
                        </div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner" data-i18n-html="faq.a5">
                            Une fois vos locataires et les montants de loyer configurés, Lokativ <strong>génère automatiquement</strong> les quittances à chaque échéance. Vous pouvez envoyer des rappels par email, SMS ou WhatsApp et consulter l'historique complet des paiements.
                        </div>
                    </div>
                </div>

                <!-- FAQ 6 -->
                <div class="faq-item">
                    <div class="faq-question">
                        <div class="faq-icon-wrap">
                            <i class="fas fa-mobile-alt" style="color:#3b82f6;font-size:14px;"></i>
                        </div>
                        <h3 data-i18n="faq.q6">Y a-t-il une application mobile ?</h3>
                        <div class="faq-chevron">
                            <i class="fas fa-chevron-down" style="color:#64748b;font-size:11px;"></i>
                        </div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner" data-i18n-html="faq.a6">
                            Lokativ est <strong>entièrement responsive</strong> et fonctionne parfaitement sur tous les appareils mobiles via votre navigateur. Vous pouvez également l'installer comme PWA (Progressive Web App) directement depuis votre téléphone.
                        </div>
                    </div>
                </div>

                <!-- FAQ 7 -->
                <div class="faq-item">
                    <div class="faq-question">
                        <div class="faq-icon-wrap">
                            <i class="fas fa-headset" style="color:#3b82f6;font-size:14px;"></i>
                        </div>
                        <h3 data-i18n="faq.q7">Proposez-vous une assistance technique ?</h3>
                        <div class="faq-chevron">
                            <i class="fas fa-chevron-down" style="color:#64748b;font-size:11px;"></i>
                        </div>
                    </div>
                    <div class="faq-answer">
                        <div class="faq-answer-inner" data-i18n-html="faq.a7">
                            Oui ! Les utilisateurs en essai bénéficient d'un <strong>support par email</strong>. Les plans payants incluent un support prioritaire via WhatsApp et email avec des temps de réponse garantis sous 24h.
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA bas de FAQ -->
            <div class="mt-10 text-center p-6 rounded-2xl" style="background:linear-gradient(135deg,#eff6ff,#dbeafe);">
                <p class="text-gray-700 font-medium mb-3" data-i18n="faq.cta.text">Vous n'avez pas trouvé votre réponse ?</p>
                <a href="#contact" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-semibold text-white" style="background:linear-gradient(135deg,#2563eb,#3b82f6);box-shadow:0 4px 14px rgba(59,130,246,0.3);">
                    <i class="fas fa-paper-plane"></i>
                    <span data-i18n="faq.cta.btn">Envoyer un message</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" style="padding:5rem 0;background:linear-gradient(135deg,#0c1445 0%,#0f2878 45%,#1a3a9f 100%);position:relative;overflow:hidden;">
        <!-- Orbs déco -->
        <div style="position:absolute;top:-80px;right:-80px;width:320px;height:320px;border-radius:50%;background:rgba(99,102,241,0.12);filter:blur(60px);pointer-events:none;"></div>
        <div style="position:absolute;bottom:-60px;left:-60px;width:260px;height:260px;border-radius:50%;background:rgba(59,130,246,0.1);filter:blur(50px);pointer-events:none;"></div>

        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8" style="position:relative;z-index:1;">

            <!-- Heading -->
            <div style="text-align:center;margin-bottom:3.5rem;">
                <span style="display:inline-block;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.18);border-radius:50px;padding:6px 18px;font-size:0.78rem;font-weight:700;color:rgba(255,255,255,0.85);margin-bottom:1rem;letter-spacing:0.04em;">
                    <i class="fas fa-headset" style="margin-right:6px;color:#60a5fa;"></i><span data-i18n="contact.badge">Support & Contact</span>
                </span>
                <h2 style="font-size:clamp(1.8rem,3.5vw,3rem);font-weight:900;color:#fff;margin-bottom:0.75rem;letter-spacing:-0.02em;" data-i18n="contact.h2">
                    Contactez-nous
                </h2>
                <p style="color:rgba(255,255,255,0.6);font-size:1rem;max-width:480px;margin:0 auto;" data-i18n="contact.sub">
                    Notre équipe répond en moins de 2h. Posez vos questions, demandez une démo ou signalez un problème.
                </p>
            </div>

            <div class="contact-grid" style="display:grid;grid-template-columns:1fr 1.4fr;gap:40px;align-items:start;">

                <!-- LEFT — Infos -->
                <div>
                    <!-- Cards info -->
                    <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:2rem;">
                        @foreach([
                            ['fa-map-marker-alt','#60a5fa','rgba(96,165,250,0.15)','Adresse','Cotonou, Bénin — Quartier Akpakpa','contact.info.address'],
                            ['fa-phone-alt','#4ade80','rgba(74,222,128,0.15)','Téléphone','+229 XX XX XX XX','contact.info.phone'],
                            ['fa-envelope','#a78bfa','rgba(167,139,250,0.15)','Email','contact@lokativ.com','contact.info.email'],
                            ['fa-clock','#fbbf24','rgba(251,191,36,0.15)','Horaires','Lun–Ven : 8h–18h · Sam : 9h–13h','contact.info.hours'],
                        ] as $info)
                        <div class="contact-info-card">
                            <div class="contact-icon-wrap" style="background:{{ $info[2] }};color:{{ $info[1] }};">
                                <i class="fas {{ $info[0] }}"></i>
                            </div>
                            <div>
                                <div style="font-size:0.72rem;font-weight:700;color:rgba(255,255,255,0.45);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:3px;" data-i18n="{{ $info[5] }}">{{ $info[3] }}</div>
                                <div style="font-size:0.88rem;font-weight:600;color:rgba(255,255,255,0.9);">{{ $info[4] }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Réseaux sociaux -->
                    <div>
                        <div style="font-size:0.72rem;font-weight:700;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:12px;" data-i18n="contact.social">Suivez-nous</div>
                        <div style="display:flex;gap:10px;">
                            <a href="#" class="contact-social-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="contact-social-btn" title="Twitter/X"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="contact-social-btn" title="Instagram"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="contact-social-btn" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <a href="#" class="contact-social-btn" title="WhatsApp" style="color:#4ade80;"><i class="fab fa-whatsapp"></i></a>
                        </div>
                    </div>

                    <!-- Badge réponse rapide -->
                    <div style="margin-top:2rem;padding:16px 18px;border-radius:14px;background:rgba(74,222,128,0.08);border:1px solid rgba(74,222,128,0.2);display:flex;align-items:center;gap:12px;">
                        <div style="width:36px;height:36px;border-radius:50%;background:rgba(74,222,128,0.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-bolt" style="color:#4ade80;font-size:14px;"></i>
                        </div>
                        <div>
                            <div style="font-size:0.8rem;font-weight:700;color:#4ade80;" data-i18n="contact.fast">Réponse rapide garantie</div>
                            <div style="font-size:0.72rem;color:rgba(255,255,255,0.45);margin-top:2px;" data-i18n="contact.fast.sub">Temps de réponse moyen : 1h30 en heures ouvrées</div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT — Formulaire -->
                <div style="background:rgba(255,255,255,0.04);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.1);border-radius:22px;padding:32px 28px;">
                    <div style="margin-bottom:22px;">
                        <h3 style="font-size:1.15rem;font-weight:800;color:#fff;margin-bottom:4px;" data-i18n="contact.form.title">Envoyez-nous un message</h3>
                        <p style="font-size:0.8rem;color:rgba(255,255,255,0.45);" data-i18n="contact.form.sub">Remplissez le formulaire, nous vous répondons rapidement.</p>
                    </div>

                    <form id="contact-form">
                        @csrf
                        <div class="contact-form-row" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
                            <div>
                                <label for="contact_nom" class="contact-label text-white" data-i18n="contact.label.nom">Nom</label>
                                <input type="text" id="contact_nom" name="contact_nom" class="contact-field" placeholder="Votre nom" data-i18n-ph="contact.ph.nom" required>
                            </div>
                            <div>
                                <label for="contact_prenom" class="contact-label text-white" data-i18n="contact.label.prenom">Prénom</label>
                                <input type="text" id="contact_prenom" name="contact_prenom" class="contact-field" placeholder="Votre prénom" data-i18n-ph="contact.ph.prenom" required>
                            </div>
                        </div>

                        <div style="margin-bottom:12px;">
                            <label for="contact_email" class="contact-label text-white" data-i18n="contact.label.email">Adresse email</label>
                            <input type="email" id="contact_email" name="contact_email" class="contact-field" placeholder="votre@email.com" required>
                        </div>

                        <div style="margin-bottom:12px;">
                            <label for="contact_sujet" class="contact-label text-white" data-i18n="contact.label.sujet">Sujet</label>
                            <select id="contact_sujet" name="contact_sujet" class="contact-field" required>
                                <option value="" data-i18n="contact.opt.select">Sélectionnez un sujet</option>
                                <option value="information" data-i18n="contact.opt.info">Demande d'information</option>
                                <option value="demo" data-i18n="contact.opt.demo">Demande de démonstration</option>
                                <option value="support" data-i18n="contact.opt.support">Support technique</option>
                                <option value="partenariat" data-i18n="contact.opt.partner">Partenariat</option>
                                <option value="autre" data-i18n="contact.opt.other">Autre</option>
                            </select>
                        </div>

                        <div style="margin-bottom:18px;">
                            <label for="contact_message" class="contact-label text-white" data-i18n="contact.label.msg">Message</label>
                            <textarea id="contact_message" name="contact_message" rows="4" class="contact-field" placeholder="Décrivez votre demande..." data-i18n-ph="contact.ph.msg" required style="resize:vertical;min-height:100px;"></textarea>
                        </div>

                        <div id="contact-alert" style="display:none;" class="mb-4 p-3 rounded-xl text-sm font-medium"></div>

                        <button type="submit" id="contact-btn" style="width:100%;padding:13px;border-radius:12px;border:none;font-weight:800;font-size:0.9rem;color:#fff;background:linear-gradient(135deg,#1d4ed8,#2563eb);cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 18px rgba(37,99,235,0.35);transition:all 0.22s;" onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 26px rgba(37,99,235,0.45)'" onmouseout="this.style.transform='';this.style.boxShadow='0 4px 18px rgba(37,99,235,0.35)'">
                            <i class="fas fa-paper-plane" style="font-size:13px;"></i>
                            <span data-i18n="contact.btn">Envoyer le message</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Section CTA finale -->
    <section class="cta-section" style="padding:6rem 0;">
        <!-- Orbs décoratifs -->
        <div class="cta-orb" style="width:400px;height:400px;background:rgba(99,102,241,0.15);top:-120px;right:-100px;"></div>
        <div class="cta-orb" style="width:300px;height:300px;background:rgba(59,130,246,0.12);bottom:-80px;left:-60px;"></div>

        <div class="px-4 mx-auto max-w-5xl sm:px-6 lg:px-8" style="position:relative;z-index:1;text-align:center;">

            <!-- Badge -->
            <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.2);border-radius:50px;padding:7px 18px;font-size:0.78rem;font-weight:700;color:rgba(255,255,255,0.9);margin-bottom:1.75rem;">
                <span style="width:7px;height:7px;border-radius:50%;background:#4ade80;box-shadow:0 0 8px #4ade80;display:inline-block;"></span>
                <span data-i18n="sec.cta.badge">500+ gestionnaires nous font déjà confiance</span>
            </div>

            <!-- Titre -->
            <h2 style="font-size:clamp(2rem,4.5vw,3.5rem);font-weight:900;color:#fff;letter-spacing:-0.03em;line-height:1.1;margin-bottom:1.25rem;">
                <span data-i18n="sec.cta.h2a">Prêt à simplifier votre</span><br>
                <span style="background:linear-gradient(135deg,#60a5fa,#a78bfa,#34d399);-webkit-background-clip:text;background-clip:text;color:transparent;-webkit-text-fill-color:transparent;" data-i18n="sec.cta.h2b">
                    gestion immobilière ?
                </span>
            </h2>

            <!-- Sous-titre -->
            <p style="font-size:1.05rem;color:rgba(255,255,255,0.65);max-width:560px;margin:0 auto 2.5rem;line-height:1.7;" data-i18n="sec.cta.sub">
                Démarrez gratuitement en 2 minutes. Pas de carte bancaire requise, pas d'engagement. Évoluez à votre rythme.
            </p>

            <!-- CTAs -->
            <div style="display:flex;flex-wrap:wrap;gap:14px;justify-content:center;margin-bottom:2.5rem;">
                <a href="#compte" class="cta-btn-primary">
                    <i class="fas fa-rocket" style="color:#2563eb;font-size:14px;"></i>
                    <span data-i18n="sec.cta.btn1">Créer un compte gratuit</span>
                </a>
                <a href="#contact" class="cta-btn-secondary">
                    <i class="fas fa-comments" style="font-size:14px;"></i>
                    <span data-i18n="sec.cta.btn2">Parler à un expert</span>
                </a>
            </div>

            <!-- Mini preuves sociales -->
            <div class="cta-proofs" style="display:flex;flex-wrap:wrap;justify-content:center;gap:24px;">
                @foreach([
                    ['fa-shield-alt','#4ade80','Sans engagement'],
                    ['fa-credit-card','#60a5fa','Aucune CB requise'],
                    ['fa-clock','#fbbf24','Config en 2 minutes'],
                    ['fa-headset','#a78bfa','Support inclus'],
                ] as $proof)
                <div style="display:flex;align-items:center;gap:7px;font-size:0.78rem;color:rgba(255,255,255,0.55);font-weight:600;">
                    <i class="fas {{ $proof[0] }}" style="color:{{ $proof[1] }};font-size:11px;"></i>
                    {{ $proof[2] }}
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-main" style="padding:4rem 0 0;" role="contentinfo">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Grille principale -->
            <div class="footer-grid" style="display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:48px;padding-bottom:3rem;border-bottom:1px solid rgba(255,255,255,0.06);">

                <!-- Colonne 1 — Brand -->
                <div>
                    <a href="/" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;margin-bottom:1.25rem;">
                        <div style="width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,#1d4ed8,#3b82f6);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-home" style="color:#fff;font-size:15px;"></i>
                        </div>
                        <span style="font-size:1.35rem;font-weight:900;color:#fff;letter-spacing:-0.02em;">Lokativ</span>
                    </a>

                    <p style="font-size:0.83rem;color:#475569;line-height:1.75;margin-bottom:1.5rem;max-width:260px;" data-i18n="footer.desc">
                        La plateforme de gestion immobilière conçue pour les propriétaires et agences d'Afrique de l'Ouest.
                    </p>

                    <!-- Réseaux sociaux -->
                    <div style="display:flex;gap:8px;margin-bottom:2rem;">
                        <a href="#" class="footer-social" style="background:rgba(59,89,152,0.2);color:#7b9ed9;" title="Facebook"><i class="fab fa-facebook-f" style="font-size:12px;"></i></a>
                        <a href="#" class="footer-social" style="background:rgba(29,161,242,0.15);color:#5aabee;" title="Twitter"><i class="fab fa-twitter" style="font-size:12px;"></i></a>
                        <a href="#" class="footer-social" style="background:rgba(225,48,108,0.15);color:#e1306c;" title="Instagram"><i class="fab fa-instagram" style="font-size:12px;"></i></a>
                        <a href="#" class="footer-social" style="background:rgba(10,102,194,0.15);color:#5d9dd5;" title="LinkedIn"><i class="fab fa-linkedin-in" style="font-size:12px;"></i></a>
                        <a href="#" class="footer-social" style="background:rgba(37,211,102,0.12);color:#25d366;" title="WhatsApp"><i class="fab fa-whatsapp" style="font-size:13px;"></i></a>
                    </div>

                    <!-- Badge sécurité -->
                    <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:8px 12px;">
                        <i class="fas fa-lock" style="color:#4ade80;font-size:11px;"></i>
                        <span style="font-size:0.72rem;color:#475569;font-weight:600;">SSL · Données sécurisées · RGPD</span>
                    </div>
                </div>

                <!-- Colonne 2 — Navigation -->
                <div>
                    <h4 style="font-size:0.72rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:1.1rem;" data-i18n="footer.nav">Navigation</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:2px;">
                        @foreach([
                            ['#accueil','Accueil'],
                            ['#fonctionnalites','Fonctionnalités'],
                            ['#portfolio','Aperçu'],
                            ['#tarifs','Tarifs'],
                            ['#contact','Contact'],
                            [route('login'),'Se connecter'],
                        ] as $lnk)
                        <li><a href="{{ $lnk[0] }}" class="footer-link"><i class="fas fa-chevron-right"></i>{{ $lnk[1] }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Colonne 3 — Fonctionnalités -->
                <div>
                    <h4 style="font-size:0.72rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:1.1rem;" data-i18n="footer.features">Fonctionnalités</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:2px;">
                        @foreach([
                            'Gestion des biens',
                            'Locataires & contrats',
                            'Facturation automatique',
                            'Rapports PDF',
                            'Notifications WhatsApp',
                            'Multi-agences',
                        ] as $feat)
                        <li><a href="#fonctionnalites" class="footer-link"><i class="fas fa-chevron-right"></i>{{ $feat }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <!-- Colonne 4 — Légal -->
                <div>
                    <h4 style="font-size:0.72rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:1.1rem;" data-i18n="footer.legal">Légal</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:2px;">
                        <li><a href="{{ route('legal.cgu') }}" class="footer-link"><i class="fas fa-chevron-right"></i>Conditions d'utilisation</a></li>
                        <li><a href="{{ route('legal.confidentialite') }}" class="footer-link"><i class="fas fa-chevron-right"></i>Confidentialité</a></li>
                        <li><a href="{{ route('legal.mentions') }}" class="footer-link"><i class="fas fa-chevron-right"></i>Mentions légales</a></li>
                        <li><a href="{{ route('legal.cookies') }}" class="footer-link"><i class="fas fa-chevron-right"></i>Cookies</a></li>
                    </ul>

                    <!-- Newsletter mini -->
                    <div style="margin-top:1.75rem;">
                        <h4 style="font-size:0.72rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:10px;" data-i18n="footer.newsletter">Restez informé</h4>
                        <div class="footer-newsletter" style="display:flex;gap:6px;">
                            <input type="email" placeholder="votre@email.com" style="flex:1;min-width:0;padding:9px 12px;border-radius:9px;border:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.05);color:#cbd5e1;font-size:0.78rem;outline:none;" onfocus="this.style.borderColor='#2563eb'" onblur="this.style.borderColor='rgba(255,255,255,0.08)'">
                            <button style="padding:9px 12px;border-radius:9px;border:none;background:#2563eb;color:#fff;font-size:0.78rem;font-weight:700;cursor:pointer;flex-shrink:0;" title="S'abonner">
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barre de copyright -->
            <div class="footer-copyright" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;padding:1.5rem 0;">
                <p style="font-size:0.78rem;color:#334155;margin:0;">
                    &copy; {{ date('Y') }} <strong style="color:#475569;">Lokativ</strong> — Tous droits réservés.
                    Fabriqué avec <i class="fas fa-heart" style="color:#ef4444;font-size:10px;"></i> en Afrique de l'Ouest.
                </p>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:0.72rem;color:#334155;font-weight:600;" data-i18n="footer.payment">Paiements acceptés :</span>
                    <i class="fab fa-cc-visa" style="font-size:1.4rem;color:#334155;"></i>
                    <i class="fab fa-cc-mastercard" style="font-size:1.4rem;color:#334155;"></i>
                    <span style="font-size:0.72rem;color:#334155;font-weight:700;background:rgba(255,255,255,0.06);padding:3px 8px;border-radius:6px;">Mobile Money</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bouton retour en haut -->
    <button id="back-to-top" aria-label="Retour en haut" style="display:none;position:fixed;bottom:28px;right:28px;z-index:999;width:44px;height:44px;border-radius:50%;border:none;background:linear-gradient(135deg,#1d4ed8,#2563eb);color:#fff;font-size:15px;cursor:pointer;box-shadow:0 4px 18px rgba(37,99,235,0.4);transition:all 0.22s;align-items:center;justify-content:center;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(37,99,235,0.5)'" onmouseout="this.style.transform='';this.style.boxShadow='0 4px 18px rgba(37,99,235,0.4)'">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- intl-tel-input -->
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@18/build/js/intlTelInput.min.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', function () {
            var telInput = document.getElementById('telephone');
            if (!telInput) return;
            window._iti = window.intlTelInput(telInput, {
                initialCountry:     'bj',
                preferredCountries: ['bj', 'ci', 'sn', 'tg', 'ng', 'gh', 'ml', 'bf', 'fr'],
                separateDialCode:   true,
                autoPlaceholder:    'polite',
                utilsScript:        'https://cdn.jsdelivr.net/npm/intl-tel-input@18/build/js/utils.js',
            });
            // Effacer l'erreur dès que l'utilisateur commence à taper
            telInput.addEventListener('input', function () {
                if (window._iti.isValidNumber()) {
                    telInput.classList.remove('is-invalid');
                }
            });
        });
    </script>
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
        let selectedNbMois = 1;
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
                    const isActive = item.classList.contains('active');

                    // Fermer toutes les autres
                    faqItems.forEach(other => other.classList.remove('active'));

                    // Ouvrir si elle n'était pas déjà ouverte
                    if (!isActive) {
                        item.classList.add('active');
                        // Scroll doux si hors vue
                        const rect = item.getBoundingClientRect();
                        if (rect.top < 80) {
                            item.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }
                    }
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
                    header.classList.add('nav-scrolled');
                } else {
                    header.classList.remove('nav-scrolled');
                }
            }
            
            // Écouter l'événement de scroll
            window.addEventListener('scroll', handleHeaderScroll);
            // Appeler une fois au chargement pour initialiser l'état
            handleHeaderScroll();

            // Bouton retour en haut
            const backToTopBtn = document.getElementById('back-to-top');

            window.addEventListener('scroll', function() {
                if (backToTopBtn) {
                    backToTopBtn.style.display = window.scrollY > 500 ? 'flex' : 'none';
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

                    const btn   = document.getElementById('contact-btn');
                    const alert = document.getElementById('contact-alert');
                    const data  = new FormData(contactForm);

                    btn.disabled   = true;
                    btn.innerHTML  = '<i class="mr-2 fas fa-spinner fa-spin"></i> Envoi en cours...';
                    alert.style.display = 'none';

                    fetch('{{ route("contact.store") }}', {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value },
                        body: data,
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.status) {
                            contactForm.reset();
                            Swal.fire({
                                title: 'Message envoyé !',
                                text: res.message,
                                icon: 'success',
                                confirmButtonText: 'OK',
                                confirmButtonColor: '#1e40af'
                            });
                        } else {
                            alert.style.display = 'block';
                            alert.className     = 'mb-4 p-3 rounded-xl text-sm font-medium bg-red-100 text-red-700';
                            alert.textContent   = res.message || '{{ __("common.swal_generic_error") }}';
                        }
                    })
                    .catch(() => {
                        alert.style.display = 'block';
                        alert.className     = 'mb-4 p-3 rounded-xl text-sm font-medium bg-red-100 text-red-700';
                        alert.textContent   = 'Impossible d\'envoyer le message. Veuillez réessayer.';
                    })
                    .finally(() => {
                        btn.disabled  = false;
                        btn.innerHTML = '<i class="mr-2 fas fa-paper-plane"></i> Envoyer le message';
                    });
                });
            }
        });
        
        // Navigation entre les étapes
        function updateStepUI(newStep, prevStepNum) {
            // Barre de progression
            document.getElementById('progress-bar').style.width = `${(newStep - 1) * 50}%`;

            // Étapes : active / completed
            [1, 2, 3].forEach(n => {
                const stepEl    = document.getElementById(`step-${n}`);
                const stepItem  = document.getElementById(`step-item-${n}`);
                if (!stepEl) return;
                stepEl.classList.remove('active', 'completed');
                stepItem && stepItem.classList.remove('active', 'completed');

                if (n < newStep) {
                    stepEl.classList.add('completed');
                    stepItem && stepItem.classList.add('completed');
                    stepEl.innerHTML = '<i class="fas fa-check" style="font-size:12px;"></i>';
                } else if (n === newStep) {
                    stepEl.classList.add('active');
                    stepItem && stepItem.classList.add('active');
                    stepEl.innerHTML = n;
                } else {
                    stepEl.innerHTML = n;
                }
            });

            // Connectors
            [1, 2].forEach(n => {
                const c = document.getElementById(`connector-${n}`);
                if (!c) return;
                n < newStep ? c.classList.add('done') : c.classList.remove('done');
            });
        }

        function nextStep(step) {
            if (validateStep(currentStep)) {
                document.getElementById(`step-form-${currentStep}`).classList.remove('active');
                currentStep = step;
                document.getElementById(`step-form-${currentStep}`).classList.add('active');
                updateStepUI(currentStep);
                if (currentStep === 3) loadPlans();
                return false;
            }
            return false;
        }

        function prevStep(step) {
            document.getElementById(`step-form-${currentStep}`).classList.remove('active');
            currentStep = step;
            document.getElementById(`step-form-${currentStep}`).classList.add('active');
            updateStepUI(currentStep);
            return false;
        }
        
        // Validation des étapes
        function validateStep(step) {
            let isValid = true;
            
            switch(step) {
                case 1:
                    if (!accountType) {
                        Swal.fire('{{ __("common.swal_error") }}', '{{ __("pages.reg_js_select_account_type") }}', 'error');
                        isValid = false;
                    }
                    break;
                    
                case 2:
                    const requiredFields = ['nom', 'prenom', 'email'];
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

                    // Validation du téléphone via intl-tel-input
                    const telEl = document.getElementById('telephone');
                    const telError = document.getElementById('telephone-error');
                    if (!window._iti) {
                        if (!telEl.value.trim()) {
                            telEl.classList.add('is-invalid');
                            telError.textContent = 'Veuillez saisir un numéro de téléphone.';
                            isValid = false;
                        }
                    } else if (!window._iti.isValidNumber()) {
                        telEl.classList.add('is-invalid');
                        telError.textContent = telEl.value.trim()
                            ? 'Numéro de téléphone invalide pour ce pays.'
                            : 'Veuillez saisir un numéro de téléphone.';
                        isValid = false;
                    } else {
                        telEl.classList.remove('is-invalid');
                    }
                    break;
                    
                case 3:
                    if (!selectedPlan) {
                        Swal.fire('{{ __("common.swal_error") }}', '{{ __("pages.reg_js_select_plan") }}', 'error');
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
                
                const isPaidPlan    = plan.prix_mensuel > 0;
                const providerLabel = PAYMENT_PROVIDER === 'fedapay' ? 'FedaPay' : 'KKiaPay';
                const paymentBadge = (isPaidPlan && PAYMENT_ENABLED)
                    ? `<span class="badge" style="background:#10b981;font-size:11px;">
                           <i class="fas fa-credit-card me-1"></i>Paiement ${providerLabel}
                       </span>`
                    : '';

                const prixAffiche = plan.prix_mensuel === 0
                    ? 'Gratuit'
                    : plan.prix_mensuel.toLocaleString('fr-FR') + ' XOF/mois';

                planElement.innerHTML = `
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1">${plan.nom}</h5>
                            <p class="mb-1">Jusqu'à ${plan.proprietes} propriétés</p>
                        </div>
                        <div class="text-end">
                            <h4 class="text-primary mb-1">${prixAffiche}</h4>
                            <small class="text-muted">${plan.prix_mensuel === 0 ? plan.periode : 'par mois'}</small>
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
                    document.getElementById('btn-submit').disabled = false;

                    // Afficher/masquer le sélecteur de durée
                    const nbMoisContainer = document.getElementById('nb-mois-container');
                    if (plan.prix_mensuel > 0) {
                        nbMoisContainer.style.display = 'block';
                        selectedNbMois = parseInt(document.getElementById('nb_mois_reg').value) || 1;
                        updateNbMoisPreview();
                    } else {
                        nbMoisContainer.style.display = 'none';
                        selectedNbMois = 1;
                    }

                    // Mettre à jour le label du bouton selon le mode de paiement
                    const isPaid = plan.prix_mensuel > 0;
                    const label  = document.getElementById('btn-submit-label');
                    if (isPaid && PAYMENT_ENABLED) {
                        label.textContent = typeof _t === 'function' ? _t('reg.submit_pay') : 'Payer et créer mon compte';
                        document.getElementById('btn-submit').className = 'btn btn-primary';
                    } else {
                        label.textContent = typeof _t === 'function' ? _t('reg.submit') : 'Créer mon compte';
                        document.getElementById('btn-submit').className = 'btn btn-success';
                    }
                });
                
                container.appendChild(planElement);
            });
        }
        
        // ===== Mise à jour du prévisualisateur de durée =====
        function updateNbMoisPreview() {
            if (!selectedPlan || selectedPlan.prix_mensuel === 0) return;
            const nb    = selectedNbMois;
            const total = selectedPlan.prix_mensuel * nb;
            document.getElementById('total-preview-reg').textContent =
                'Total : ' + total.toLocaleString('fr-FR') + ' XOF';
            // Calcul de la date de fin estimée
            const fin = new Date();
            fin.setMonth(fin.getMonth() + nb);
            document.getElementById('fin-date-preview').textContent =
                fin.toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const nbInput = document.getElementById('nb_mois_reg');
            if (nbInput) {
                nbInput.addEventListener('input', function() {
                    let val = Math.max(1, Math.min(24, parseInt(this.value) || 1));
                    this.value  = val;
                    selectedNbMois = val;
                    updateNbMoisPreview();
                });
            }
        });

        // ===== Soumission du formulaire =====
        // ===== Construction du formData partagé =====
        function buildFormData(transactionId = null) {
            const telephone = window._iti ? window._iti.getNumber() : document.getElementById('telephone').value;
            const data = {
                type_compte: accountType === 'particulier' ? 'Particulier' : 'Entreprise',
                nom:         document.getElementById('nom').value,
                prenom:      document.getElementById('prenom').value,
                email:       document.getElementById('email').value,
                telephone:   telephone,
                plan_code:   selectedPlan.id,
                nb_mois:     selectedNbMois || 1,
                _token:      document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            };
            if (transactionId) {
                data.transaction_id = transactionId;
            }
            if (paymentAuthToken) {
                data.payment_auth_token = paymentAuthToken;
            }
            if (accountType === 'entreprise') {
                data.designation         = document.getElementById('designation').value;
                data.adresse             = document.getElementById('adresse').value;
                data.telepone_entreprise = telephone;
                data.email_entreprise    = document.getElementById('email_entreprise').value;
            }
            return data;
        }

        async function submitForm() {
            if (!selectedPlan) {
                Swal.fire('{{ __("common.swal_error") }}', '{{ __("pages.reg_js_select_plan") }}', 'error');
                return;
            }
            if (!document.getElementById('conditions').checked) {
                document.getElementById('conditions').classList.add('is-invalid');
                Swal.fire('{{ __("common.swal_error") }}', '{{ __("pages.reg_js_accept_terms") }}', 'error');
                return;
            }

            const submitBtn = document.getElementById('btn-submit');
            submitBtn.disabled = true;
            paymentAuthToken   = null;
            createAccountLock  = false;

            // ── ÉTAPE 0 : Vérification email disponible — bloque le paiement ─────
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ' + _t('reg.checking');
            try {
                const fd0       = buildFormData();
                const emailResp = await fetch("{{ route('check_email_available') }}", {
                    method:  'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept':       'application/json',
                        'X-CSRF-TOKEN': fd0._token,
                    },
                    body: JSON.stringify({
                        email:            fd0.email,
                        email_entreprise: fd0.email_entreprise || null,
                        type_compte:      fd0.type_compte,
                    }),
                });
                const emailJson = await emailResp.json().catch(() => null);
                if (!emailJson || emailJson.available === false) {
                    Swal.fire('{{ __("common.swal_error") }}', '{{ __("pages.reg_js_email_taken") }}', 'error');
                    resetSubmitBtn(submitBtn);
                    return; // STOP — email pris
                }
            } catch (_) {
                Swal.fire('{{ __("common.swal_network_error") }}', '{{ __("pages.reg_js_email_check_error") }}', 'error');
                resetSubmitBtn(submitBtn);
                return; // STOP — erreur réseau
            }
            // ────────────────────────────────────────────────────────────────────

            // ── ÉTAPE 1 : Validation serveur — bloque TOUT si échoue ────────────
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ' + _t('reg.checking');

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
                Swal.fire('{{ __("common.swal_network_error") }}', '{{ __("common.swal_cannot_reach") }}', 'error');
                resetSubmitBtn(submitBtn);
                return; // STOP
            }

            // Seul cas où on autorise la suite : réponse JSON avec status === true SANS email_taken
            if (serverJson !== null && serverJson.status === true && !serverJson.email_taken) {
                validationPassed = true;
                paymentAuthToken = serverJson.auth_token || null;
            }

            // Si email déjà utilisé — blocage immédiat sans afficher les champs en erreur
            if (serverJson && serverJson.email_taken) {
                Swal.fire('{{ __("common.swal_error") }}', serverJson.message || '{{ __("pages.reg_js_email_taken") }}', 'error');
                resetSubmitBtn(submitBtn);
                return; // STOP DÉFINITIF — email pris
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
                const errMsg = (serverJson && serverJson.message) || '{{ __("pages.reg_js_check_info") }}';
                Swal.fire({ title: '{{ __("common.swal_error") }}', text: errMsg, icon: 'error', confirmButtonText: '{{ __("pages.reg_js_fix_btn") }}' }).then(() => {
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
            const isPlanGratuit = selectedPlan.prix_mensuel === 0;

            if (!isPlanGratuit && PAYMENT_ENABLED) {
                await initiatePayment(submitBtn);
            } else {
                await createAccount(null, submitBtn);
            }
        }

        // ===== Ouverture du widget de paiement (KKiaPay ou FedaPay) =====
        async function initiatePayment(submitBtn) {
            // Garde : le token doit avoir été émis par preValidate()
            if (!paymentAuthToken) {
                Swal.fire('{{ __("common.swal_error") }}', '{{ __("pages.reg_js_session_expired") }}', 'error');
                resetSubmitBtn(submitBtn);
                return;
            }

            // ── Vérification directe de l'email en DB avant d'ouvrir le widget ──
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ' + _t('reg.checking');
            try {
                const fd        = buildFormData();
                const emailResp = await fetch("{{ route('check_email_available') }}", {
                    method:  'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': fd._token },
                    body: JSON.stringify({
                        email:            fd.email,
                        email_entreprise: fd.email_entreprise || null,
                        type_compte:      fd.type_compte,
                    }),
                });
                const emailJson = await emailResp.json().catch(() => null);
                if (!emailJson || emailJson.available === false) {
                    Swal.fire('{{ __("common.swal_error") }}', '{{ __("pages.reg_js_email_taken") }}', 'error');
                    resetSubmitBtn(submitBtn);
                    return;
                }
            } catch (_) {
                Swal.fire('{{ __("common.swal_network_error") }}', '{{ __("pages.reg_js_email_check_error") }}', 'error');
                resetSubmitBtn(submitBtn);
                return;
            }
            // ─────────────────────────────────────────────────────────────────

            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ' + _t('reg.opening_payment');

            if (PAYMENT_PROVIDER === 'kkiapay') {
                if (typeof openKkiapayWidget !== 'function') {
                    Swal.fire({
                        title: _t('payment.unavailable_title'),
                        text: _t('payment.kkiapay_unavailable'),
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

                const kkPaymentAmount = selectedPlan.prix_mensuel * selectedNbMois;
                openKkiapayWidget({
                    amount:  kkPaymentAmount,
                    key:     PAYMENT_PUBLIC_KEY,
                    sandbox: PAYMENT_SANDBOX,
                    email:   document.getElementById('email').value,
                    name:    document.getElementById('prenom').value + ' ' + document.getElementById('nom').value,
                    phone:   window._iti ? window._iti.getNumber() : document.getElementById('telephone').value,
                    data:    JSON.stringify({ plan_code: selectedPlan.id, nb_mois: selectedNbMois }),
                });

                addSuccessListener(async function(response) {
                    if (createAccountLock) return; // listener dupliqué — ignorer
                    createAccountLock = true;
                    kkPaymentDone = true;
                    kkCleanup();
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ' + _t('reg.creating_account');
                    await createAccount(response.transactionId, submitBtn);
                });

                addFailedListener(function() {
                    kkPaymentDone = true;
                    kkCleanup();
                    Swal.fire({
                        title: _t('payment.failed_title'),
                        text: _t('payment.kkiapay_failed'),
                        icon: 'error', confirmButtonText: _t('payment.retry'), confirmButtonColor: '#1e40af',
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
                const fdPaymentAmount = selectedPlan.prix_mensuel * selectedNbMois;
                FedaPay.init({
                    public_key:  PAYMENT_PUBLIC_KEY,
                    transaction: {
                        amount:      fdPaymentAmount,
                        description: 'Abonnement Lokativ — ' + selectedPlan.nom + ' (' + selectedNbMois + ' mois)',
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
                            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> ' + _t('reg.creating_account');
                            await createAccount(String(trans.id), submitBtn);
                        } else {
                            Swal.fire({
                                title: _t('payment.failed_title'),
                                text: _t('payment.fedapay_failed'),
                                icon: 'error', confirmButtonText: _t('payment.retry'), confirmButtonColor: '#1e40af',
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
            submitBtn.innerHTML = '<i class="fas fa-check me-2"></i> ' + _t('reg.submit');
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
                        title:             '{{ __("pages.reg_js_account_created_title") }}',
                        text:              data.message,
                        icon:              'success',
                        confirmButtonText: '{{ __("pages.reg_js_login_btn") }}',
                        confirmButtonColor: '#1e40af',
                    }).then(() => {
                        window.location.href = "{{ route('login') }}";
                    });
                } else {
                    if (submitBtn) resetSubmitBtn(submitBtn);

                    // Cas spécial : paiement reçu mais création de compte échouée
                    if (data.payment_pending) {
                        Swal.fire({
                            title:             data.alert_title || '{{ __("pages.reg_js_payment_pending_title") }}',
                            html:              '<p>' + (data.message || '') + '</p>' +
                                               '<p class="mt-2 text-muted" style="font-size:13px;">' + (data.alert_note || '{{ __("pages.reg_js_payment_pending_note") }}') + '</p>',
                            icon:              'warning',
                            confirmButtonText: data.alert_btn || '{{ __("pages.reg_js_payment_pending_btn") }}',
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
                        title:             '{{ __("common.swal_error") }}',
                        html:              errorHtml,
                        icon:              'error',
                        confirmButtonText: '{{ __("pages.reg_js_fix_btn") }}',
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
                console.error('Error:', error);
                Swal.fire('{{ __("common.swal_error") }}', '{{ __("common.swal_generic_error") }}', 'error');
                if (submitBtn) resetSubmitBtn(submitBtn);
            }
        }
    </script>

    <!-- ═══════════════════════════════════════════════════ PWA UI ══ -->
    <style>
        @keyframes slideUp   { from { transform:translateY(110%); opacity:0; } to { transform:translateY(0); opacity:1; } }
        @keyframes fadeInBg  { from { opacity:0; } to { opacity:1; } }
        @keyframes slideDown { from { transform:translateY(-60px); opacity:0; } to { transform:translateY(0); opacity:1; } }
        @keyframes spin2     { from { transform:rotate(0deg); } to { transform:rotate(360deg); } }

        /* ── Bannière install Android/Chrome ── */
        #pwa-install-banner {
            display: none;
            position: fixed;
            bottom: 16px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 32px);
            max-width: 480px;
            z-index: 99990;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            color: #fff;
            border-radius: 18px;
            padding: 16px 18px;
            box-shadow: 0 12px 40px rgba(30,58,138,0.45);
            animation: slideUp 0.45s cubic-bezier(.4,0,.2,1);
        }
        #pwa-install-banner .pwa-inner { display:flex; align-items:center; gap:14px; }
        #pwa-install-banner img { width:52px; height:52px; border-radius:13px; border:2px solid rgba(255,255,255,0.35); flex-shrink:0; object-fit:cover; }
        #pwa-install-banner .pwa-text { flex:1; }
        #pwa-install-banner .pwa-text strong { display:block; font-size:0.92rem; font-weight:800; }
        #pwa-install-banner .pwa-text span  { font-size:0.78rem; opacity:0.82; }
        #pwa-install-banner .pwa-actions { display:flex; flex-direction:column; gap:7px; flex-shrink:0; }
        .pwa-btn-install { background:#fff; color:#1e40af; border:none; padding:8px 18px; border-radius:50px; cursor:pointer; font-weight:800; font-size:0.82rem; white-space:nowrap; transition:transform 0.15s; }
        .pwa-btn-install:hover { transform:scale(1.03); }
        .pwa-btn-dismiss { background:transparent; border:none; color:rgba(255,255,255,0.65); font-size:0.75rem; cursor:pointer; text-align:center; padding:2px 0; }
        .pwa-btn-dismiss:hover { color:#fff; }

        /* ── Modal iOS ── */
        #pwa-ios-modal { display:none; position:fixed; inset:0; z-index:99990; background:rgba(15,23,42,0.7); backdrop-filter:blur(6px); align-items:flex-end; justify-content:center; padding:16px; animation:fadeInBg 0.3s ease; }
        #pwa-ios-modal.open { display:flex; }
        #pwa-ios-modal .modal-card { background:#fff; border-radius:24px; padding:28px 24px; max-width:400px; width:100%; animation:slideUp 0.4s cubic-bezier(.4,0,.2,1); }
        #pwa-ios-modal .ios-header { display:flex; align-items:center; gap:14px; margin-bottom:20px; }
        #pwa-ios-modal .ios-header img { width:56px; height:56px; border-radius:14px; border:2px solid #e2e8f0; object-fit:cover; }
        #pwa-ios-modal h3 { font-size:1rem; font-weight:800; color:#1e293b; margin:0 0 3px; }
        #pwa-ios-modal .ios-header p { font-size:0.78rem; color:#64748b; margin:0; }
        .ios-step { display:flex; align-items:flex-start; gap:14px; padding:12px 0; border-bottom:1px solid #f1f5f9; }
        .ios-step:last-of-type { border-bottom:none; }
        .ios-step-num { width:28px; height:28px; border-radius:50%; background:#eff6ff; color:#2563eb; font-weight:800; font-size:0.8rem; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
        .ios-step-text strong { display:block; font-size:0.88rem; color:#1e293b; font-weight:700; }
        .ios-step-text span   { font-size:0.8rem; color:#64748b; }
        .ios-icon-inline { display:inline-flex; align-items:center; justify-content:center; width:22px; height:22px; border-radius:6px; background:#f1f5f9; vertical-align:middle; font-size:13px; color:#2563eb; }
        #pwa-ios-modal .ios-close { display:block; width:100%; margin-top:18px; padding:11px; border-radius:12px; background:#f1f5f9; border:none; font-weight:700; font-size:0.88rem; color:#475569; cursor:pointer; }
        #pwa-ios-modal .ios-close:hover { background:#e2e8f0; }

        /* ── Toast mise à jour ── */
        #pwa-update-toast { display:none; position:fixed; top:16px; left:50%; transform:translateX(-50%); z-index:99990; background:#1e293b; color:#fff; border-radius:14px; padding:14px 20px; font-size:0.88rem; box-shadow:0 8px 30px rgba(0,0,0,0.25); align-items:center; gap:14px; white-space:nowrap; animation:slideDown 0.4s cubic-bezier(.4,0,.2,1); max-width:calc(100vw - 32px); }
        #pwa-update-toast.show { display:flex; }
        .pwa-update-btn { background:#3b82f6; color:#fff; border:none; padding:7px 16px; border-radius:50px; font-weight:700; font-size:0.8rem; cursor:pointer; flex-shrink:0; }

        /* ── Barre hors-ligne ── */
        #pwa-offline-bar { display:none; position:fixed; top:0; left:0; right:0; z-index:99989; background:#ef4444; color:#fff; text-align:center; font-size:0.82rem; font-weight:600; padding:8px 16px; animation:slideDown 0.3s ease; }
        #pwa-offline-bar.online-bar { background:#22c55e; }
    </style>

    <!-- Barre hors-ligne -->
    <div id="pwa-offline-bar">
        <i class="fas fa-wifi me-2" style="text-decoration:line-through;"></i>
        <span id="pwa-offline-msg">Vous êtes hors ligne — Vérifiez votre connexion.</span>
    </div>

    <!-- Bannière install Chrome/Android -->
    <div id="pwa-install-banner">
        <div class="pwa-inner">
            <img src="/logo/LOGO.jpg" alt="Lokativ" onerror="this.src='/assets/img/logo.png'">
            <div class="pwa-text">
                <strong>Installer Lokativ</strong>
                <span>Accès rapide depuis votre écran d'accueil</span>
            </div>
            <div class="pwa-actions">
                <button class="pwa-btn-install" id="pwa-install-btn">
                    <i class="fas fa-download me-1"></i> Installer
                </button>
                <button class="pwa-btn-dismiss" id="pwa-install-dismiss">Plus tard</button>
            </div>
        </div>
    </div>

    <!-- Modal iOS (Safari) -->
    <div id="pwa-ios-modal" role="dialog" aria-label="Installer Lokativ sur iOS">
        <div class="modal-card">
            <div class="ios-header">
                <img src="/logo/LOGO.jpg" alt="Lokativ" onerror="this.src='/assets/img/logo.png'">
                <div>
                    <h3>Installer Lokativ</h3>
                    <p>Ajoutez l'app à votre écran d'accueil</p>
                </div>
            </div>
            <div class="ios-step">
                <div class="ios-step-num">1</div>
                <div class="ios-step-text">
                    <strong>Appuyez sur <span class="ios-icon-inline">⬆</span> Partager</strong>
                    <span>Icône en bas de votre navigateur Safari</span>
                </div>
            </div>
            <div class="ios-step">
                <div class="ios-step-num">2</div>
                <div class="ios-step-text">
                    <strong>Faites défiler et choisissez</strong>
                    <span>« Sur l'écran d'accueil » <span class="ios-icon-inline">＋</span></span>
                </div>
            </div>
            <div class="ios-step">
                <div class="ios-step-num">3</div>
                <div class="ios-step-text">
                    <strong>Confirmez en appuyant sur « Ajouter »</strong>
                    <span>Lokativ apparaîtra comme une vraie application</span>
                </div>
            </div>
            <button class="ios-close" id="pwa-ios-close">Compris</button>
        </div>
    </div>

    <!-- Toast mise à jour disponible -->
    <div id="pwa-update-toast">
        <i class="fas fa-rotate me-1"></i>
        <span>Nouvelle version disponible</span>
        <button class="pwa-update-btn" id="pwa-update-btn">Actualiser</button>
    </div>

    <!-- ── PWA : Service Worker + Logique ── -->
    <script>
    (function() {
        'use strict';

        const isStandalone = window.matchMedia('(display-mode: standalone)').matches
                          || window.navigator.standalone === true;

        // ── Indicateur connexion ──────────────────────────────────────────
        const offlineBar = document.getElementById('pwa-offline-bar');
        const offlineMsg = document.getElementById('pwa-offline-msg');

        function showOfflineBar() {
            offlineBar.classList.remove('online-bar');
            offlineMsg.textContent = 'Vous êtes hors ligne — Vérifiez votre connexion.';
            offlineBar.style.display = 'block';
        }
        function showOnlineBar() {
            offlineBar.classList.add('online-bar');
            offlineMsg.textContent = '✓ Connexion rétablie';
            offlineBar.style.display = 'block';
            setTimeout(() => { offlineBar.style.display = 'none'; offlineBar.classList.remove('online-bar'); }, 3000);
        }

        if (!navigator.onLine) showOfflineBar();
        window.addEventListener('offline', showOfflineBar);
        window.addEventListener('online',  showOnlineBar);

        // ── Service Worker ────────────────────────────────────────────────
        if (!('serviceWorker' in navigator)) return;

        let swRegistration = null;

        navigator.serviceWorker.register('/sw.js').then((reg) => {
            swRegistration = reg;
            setInterval(() => reg.update(), 30 * 60 * 1000);

            reg.addEventListener('updatefound', () => {
                const newWorker = reg.installing;
                newWorker.addEventListener('statechange', () => {
                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                        const toast = document.getElementById('pwa-update-toast');
                        toast.classList.add('show');
                        toast.style.display = 'flex';
                        document.getElementById('pwa-update-btn').addEventListener('click', () => {
                            newWorker.postMessage({ type: 'SKIP_WAITING' });
                            window.location.reload();
                        });
                    }
                });
            });
        }).catch(() => {});

        navigator.serviceWorker.addEventListener('controllerchange', () => {
            window.location.reload();
        });

        // ── Install prompt (Chrome / Android) ────────────────────────────
        let deferredPrompt = null;
        const banner     = document.getElementById('pwa-install-banner');
        const installBtn = document.getElementById('pwa-install-btn');
        const dismissBtn = document.getElementById('pwa-install-dismiss');

        if (!isStandalone) {
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                const dismissedAt = parseInt(localStorage.getItem('pwa-dismissed-at') || '0');
                if ((Date.now() - dismissedAt) > 7 * 24 * 60 * 60 * 1000) {
                    setTimeout(() => { banner.style.display = 'block'; }, 3000);
                }
            });

            installBtn.addEventListener('click', async () => {
                if (!deferredPrompt) return;
                installBtn.disabled = true;
                installBtn.innerHTML = '<span style="display:inline-block;animation:spin2 0.8s linear infinite">↻</span> Installation...';
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                if (outcome === 'accepted') {
                    banner.style.display = 'none';
                } else {
                    installBtn.disabled = false;
                    installBtn.innerHTML = '<i class="fas fa-download me-1"></i> Installer';
                }
                deferredPrompt = null;
            });

            dismissBtn.addEventListener('click', () => {
                banner.style.display = 'none';
                localStorage.setItem('pwa-dismissed-at', Date.now());
            });

            // ── iOS / Safari ──────────────────────────────────────────────
            const isIos    = /iphone|ipad|ipod/i.test(navigator.userAgent);
            const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
            if (isIos && isSafari && !localStorage.getItem('pwa-ios-shown')) {
                setTimeout(() => {
                    document.getElementById('pwa-ios-modal').classList.add('open');
                }, 4500);
            }

            document.getElementById('pwa-ios-close').addEventListener('click', () => {
                document.getElementById('pwa-ios-modal').classList.remove('open');
                localStorage.setItem('pwa-ios-shown', '1');
            });
            document.getElementById('pwa-ios-modal').addEventListener('click', (e) => {
                if (e.target === e.currentTarget) {
                    e.currentTarget.classList.remove('open');
                    localStorage.setItem('pwa-ios-shown', '1');
                }
            });
        }

        window.addEventListener('appinstalled', () => {
            banner.style.display = 'none';
            deferredPrompt = null;
            localStorage.setItem('pwa-dismissed-at', Date.now());
        });
    })();
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
        var YOUTUBE_VIDEO_ID = '4Yoo6LJfkeA';
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

    <!-- ═══════════════════════════════════════════════════════════
         MODAL — Partager votre avis
    ══════════════════════════════════════════════════════════════ -->
    <div id="testiModalOverlay" class="testi-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="testiModalTitle">
        <div class="testi-modal">
            <button class="testi-modal-close" id="closeTestiModal" aria-label="Fermer">✕</button>

            <h3 id="testiModalTitle" data-i18n="testi.modal.title">⭐ Partagez votre expérience</h3>
            <p class="modal-sub" data-i18n="testi.modal.sub">Votre avis aide d'autres gestionnaires à découvrir Lokativ.</p>

            <form id="testiForm" novalidate>
                @csrf

                <!-- Nom -->
                <div style="margin-bottom:1rem;">
                    <label class="testi-form-label" for="testi-nom"><span data-i18n="testi.modal.name">Votre nom</span> <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="testi-nom" class="testi-form-input" placeholder="Ex : Kofi Mensah" data-i18n-ph="testi.modal.name_ph" maxlength="80" required>
                </div>

                <!-- Rôle -->
                <div style="margin-bottom:1rem;">
                    <label class="testi-form-label" for="testi-role"><span data-i18n="testi.modal.role">Ville</span> <span style="color:#94a3b8;font-weight:400;" data-i18n="testi.modal.role_opt">(facultatif)</span></label>
                    <input type="text" id="testi-role" class="testi-form-input" placeholder="Ex : Propriétaire · Cotonou" data-i18n-ph="testi.modal.role_ph" maxlength="100">
                </div>

                <!-- Note étoiles -->
                <div style="margin-bottom:1rem;">
                    <label class="testi-form-label"><span data-i18n="testi.modal.rating">Note</span> <span style="color:#ef4444;">*</span></label>
                    <div class="testi-star-row" id="testiStarRow" role="group" aria-label="Note de 1 à 5">
                        @for($i = 1; $i <= 5; $i++)
                        <button type="button" class="testi-star-btn" data-value="{{ $i }}" aria-label="{{ $i }} étoile{{ $i > 1 ? 's' : '' }}">★</button>
                        @endfor
                    </div>
                    <input type="hidden" id="testi-etoiles" value="5">
                </div>

                <!-- Avis -->
                <div style="margin-bottom:0.25rem;">
                    <label class="testi-form-label" for="testi-texte"><span data-i18n="testi.modal.review">Votre avis</span> <span style="color:#ef4444;">*</span></label>
                    <textarea id="testi-texte" class="testi-form-textarea" placeholder="Décrivez votre expérience avec Lokativ (minimum 20 caractères)..." data-i18n-ph="testi.modal.review_ph" maxlength="500" required></textarea>
                    <p class="testi-char-count"><span id="testi-char-count">0</span> / 500</p>
                </div>

                <button type="submit" class="testi-submit-btn" id="testiSubmitBtn" data-i18n="testi.modal.submit">
                    Publier mon avis
                </button>
            </form>
        </div>
    </div>

    <script>
    // ── Modal témoignage ──────────────────────────────────────────────────────
    (function () {
        const overlay      = document.getElementById('testiModalOverlay');
        const openBtn      = document.getElementById('openTestiModal');
        const closeBtn     = document.getElementById('closeTestiModal');
        const form         = document.getElementById('testiForm');
        const submitBtn    = document.getElementById('testiSubmitBtn');
        const nomInput     = document.getElementById('testi-nom');
        const roleInput    = document.getElementById('testi-role');
        const texteInput   = document.getElementById('testi-texte');
        const etoilesInput = document.getElementById('testi-etoiles');
        const starBtns     = document.querySelectorAll('.testi-star-btn');
        const charCount    = document.getElementById('testi-char-count');
        const track        = document.getElementById('testi-track');

        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        const STORE_URL = "{{ route('temoignages.store') }}";

        // Doubler les cards pour le défilement infini (–50% translateX)
        Array.from(track.children).forEach(child => track.appendChild(child.cloneNode(true)));

        // Palette de couleurs pour les nouvelles cards
        const PALETTE = [
            { accent:'#2563eb', bg:'#eff6ff', bar:'#2563eb' },
            { accent:'#16a34a', bg:'#f0fdf4', bar:'#16a34a' },
            { accent:'#7c3aed', bg:'#faf5ff', bar:'#7c3aed' },
            { accent:'#0891b2', bg:'#ecfeff', bar:'#0891b2' },
            { accent:'#d97706', bg:'#fffbeb', bar:'#d97706' },
            { accent:'#dc2626', bg:'#fef2f2', bar:'#dc2626' },
            { accent:'#0d9488', bg:'#f0fdfa', bar:'#0d9488' },
            { accent:'#9333ea', bg:'#fdf4ff', bar:'#9333ea' },
        ];

        // ── Ouvrir / fermer ──────────────────────────────────────
        function openModal() {
            overlay.classList.add('open');
            document.body.style.overflow = 'hidden';
            nomInput.focus();
        }

        function closeModal() {
            overlay.classList.remove('open');
            document.body.style.overflow = '';
        }

        if (openBtn)  openBtn.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);

        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && overlay.classList.contains('open')) closeModal();
        });

        // ── Star rating interactif ────────────────────────────────
        let currentRating = 5;

        function updateStars(rating, isHover) {
            starBtns.forEach(btn => {
                const v = parseInt(btn.dataset.value);
                btn.classList.remove('active', 'hover');
                if (isHover) {
                    if (v <= rating) btn.classList.add('hover');
                } else {
                    if (v <= rating) btn.classList.add('active');
                }
            });
        }

        // Initialiser à 5 étoiles
        updateStars(5, false);

        starBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                currentRating = parseInt(this.dataset.value);
                etoilesInput.value = currentRating;
                updateStars(currentRating, false);
            });

            btn.addEventListener('mouseenter', function () {
                updateStars(parseInt(this.dataset.value), true);
            });

            btn.addEventListener('mouseleave', function () {
                updateStars(currentRating, false);
            });
        });

        // ── Compteur de caractères ────────────────────────────────
        texteInput.addEventListener('input', function () {
            charCount.textContent = this.value.length;
        });

        // ── Générer les initiales ─────────────────────────────────
        function getInitials(name) {
            return name.trim().split(/\s+/).slice(0, 2)
                .map(w => w.charAt(0).toUpperCase()).join('');
        }

        // ── Construire une card HTML ──────────────────────────────
        function buildCard(t, colors) {
            const stars = Array.from({length: 5}, (_, i) =>
                `<i class="${i < t.etoiles ? 'fas' : 'far'} fa-star"></i>`
            ).join('');

            const initials = getInitials(t.nom);
            const role = t.role || 'Utilisateur Lokativ';

            return `
            <div class="testi-card testi-card-new" style="border-top-color:${colors.bar};border-top-width:3px;border-top-style:solid;">
                <span class="testi-new-badge">✦ Utilisateur</span>
                <div class="testi-quote-mark">"</div>
                <div class="testi-stars" style="margin-top:18px;">
                    ${stars}
                    <span style="font-size:0.72rem;color:#94a3b8;margin-left:6px;font-weight:600;">${t.etoiles}.0</span>
                </div>
                <p class="testi-text">"${escHtml(t.texte)}"</p>
                <div class="testi-footer">
                    <div class="testi-avatar" style="background:${colors.accent};">${escHtml(initials)}</div>
                    <div style="flex:1;min-width:0;">
                        <div class="testi-name">${escHtml(t.nom)}</div>
                        <div class="testi-role">${escHtml(role)}</div>
                        <div style="margin-top:5px;">
                            <span style="font-size:0.65rem;font-weight:700;padding:2px 8px;border-radius:20px;background:${colors.bg};color:${colors.accent};">Avis vérifié</span>
                        </div>
                    </div>
                    <i class="fas fa-check-circle" style="font-size:16px;color:${colors.accent};opacity:0.7;flex-shrink:0;"></i>
                </div>
            </div>`;
        }

        function escHtml(str) {
            return String(str)
                .replace(/&/g,'&amp;').replace(/</g,'&lt;')
                .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        // ── Soumission du formulaire ──────────────────────────────
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const nom    = nomInput.value.trim();
            const role   = roleInput.value.trim();
            const texte  = texteInput.value.trim();
            const etoiles= parseInt(etoilesInput.value) || 5;

            // Validation côté client
            if (!nom) { nomInput.focus(); return; }
            if (texte.length < 20) {
                texteInput.focus();
                texteInput.style.borderColor = '#ef4444';
                setTimeout(() => texteInput.style.borderColor = '', 1500);
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = 'Publication en cours…';

            try {
                const res = await fetch(STORE_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ nom, role: role || null, texte, etoiles }),
                });

                const data = await res.json();

                if (!res.ok || !data.status) throw new Error(data.message || '{{ __("common.swal_server_error") }}');

                // Choisir la couleur selon l'id
                const colors = PALETTE[data.temoignage.id % PALETTE.length];

                // Injecter la nouvelle card dans les deux moitiés du track (1ère + clone)
                const wrapper = document.createElement('div');
                wrapper.innerHTML = buildCard(data.temoignage, colors);
                const card = wrapper.firstElementChild;
                const realCount = track.children.length / 2; // avant insertion
                track.insertBefore(card, track.children[0]);
                track.insertBefore(card.cloneNode(true), track.children[realCount + 1]);

                // Fermer la modal et réinitialiser
                closeModal();
                form.reset();
                charCount.textContent = '0';
                currentRating = 5;
                etoilesInput.value = 5;
                updateStars(5, false);

                Swal.fire({
                    icon: 'success',
                    title: 'Merci !',
                    text: 'Votre avis a bien été publié.',
                    confirmButtonColor: '#2563eb',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                });

            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("common.swal_error") }}',
                    text: err.message || '{{ __("common.swal_generic_error") }}',
                    confirmButtonColor: '#dc2626',
                });
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Publier mon avis';
            }
        });
    })();
    </script>

    <script>
    // ── Sélecteur de langue ───────────────────────────────────────────────────
    var LANG_DICT = {
        fr: {
            /* Navigation */
            'nav.home'      : 'Accueil',
            'nav.features'  : 'Fonctionnalités',
            'nav.preview'   : 'Aperçu',
            'nav.pricing'   : 'Tarifs',
            'nav.contact'   : 'Contact',
            'nav.login'     : 'Se connecter',
            'nav.start'     : 'Démarrer',
            'mob.language'  : 'Langue',
            'mob.theme'     : 'Préférence d\'affichage',
            'mob.light'     : 'Clair',
            'mob.dark'      : 'Sombre',
            'mob.login'     : 'Se connecter',
            'mob.start'     : 'Démarrer gratuitement',
            /* Hero */
            'hero.badge'    : 'Plateforme de gestion immobilière',
            'hero.new'      : 'Nouveau',
            'hero.h1a'      : 'Gérez votre patrimoine',
            'hero.h1b'      : 'immobilier en ligne',
            'hero.sub'      : 'Centralisez vos biens, automatisez la facturation, suivez les paiements et envoyez des documents via WhatsApp — tout en un seul endroit.',
            'hero.cta1'     : 'Démarrer gratuitement',
            'hero.cta2'     : 'Voir la démo',
            'hero.stat1'    : '500+ gestionnaires',
            'hero.stat2'    : '10 000+ biens gérés',
            'hero.stat3'    : '4.9 / 5 satisfaction',
            /* Sections */
            'sec.features.badge': 'Tout-en-un',
            'sec.features.h2'   : 'Des fonctionnalités puissantes',
            'sec.features.sub'  : 'Tous les outils dont vous avez besoin pour gérer votre patrimoine immobilier au quotidien',
            'sec.how.badge'     : 'En 4 étapes',
            'sec.how.h2'        : 'Comment ça marche ?',
            'sec.how.sub'       : 'Démarrez en quelques minutes et gérez votre patrimoine comme un pro',
            'sec.testi.badge'   : 'Témoignages',
            'sec.testi.h2a'     : 'Ce que disent',
            'sec.testi.h2b'     : 'nos clients',
            'sec.testi.sub'     : 'Des milliers de gestionnaires font confiance à Lokativ pour simplifier leur quotidien.',
            'sec.testi.cta1'        : 'Démarrer gratuitement',
            'sec.testi.cta2'        : '⭐ Partager votre avis',
            'testi.modal.title'     : '⭐ Partagez votre expérience',
            'testi.modal.sub'       : 'Votre avis aide d\'autres gestionnaires à découvrir Lokativ.',
            'testi.modal.name'      : 'Votre nom',
            'testi.modal.name_ph'   : 'Ex : Kofi Mensah',
            'testi.modal.role'      : 'Ville',
            'testi.modal.role_opt'  : '(facultatif)',
            'testi.modal.role_ph'   : 'Ex : Propriétaire · Cotonou',
            'testi.modal.rating'    : 'Note',
            'testi.modal.review'    : 'Votre avis',
            'testi.modal.review_ph' : 'Décrivez votre expérience avec Lokativ (minimum 20 caractères)...',
            'testi.modal.submit'    : 'Publier mon avis',
            'sec.pricing.badge' : 'Tarifs',
            'sec.pricing.h2a'   : 'Des plans adaptés à',
            'sec.pricing.h2b'   : 'chaque besoin',
            'sec.pricing.sub'   : 'Démarrez gratuitement. Évoluez selon votre croissance.',
            'sec.faq.badge'     : 'Aide & Réponses',
            'sec.faq.h2a'       : 'Questions',
            'sec.faq.h2b'       : 'fréquentes',
            'sec.faq.sub'       : 'Tout ce que vous devez savoir avant de démarrer.',
            'sec.cta.h2a'       : 'Prêt à simplifier votre',
            'sec.cta.h2b'       : 'gestion immobilière ?',
            'sec.cta.sub'       : 'Démarrez gratuitement en 2 minutes. Pas de carte bancaire requise, pas d\'engagement. Évoluez à votre rythme.',
            'sec.cta.badge'     : '500+ gestionnaires nous font déjà confiance',
            'sec.cta.btn1'      : 'Créer un compte gratuit',
            'sec.cta.btn2'      : 'Parler à un expert',
            /* Features */
            'feat.prop.title'   : 'Gestion des propriétés',
            'feat.prop.desc'    : 'Centralisez toutes vos propriétés en un seul endroit. Maisons, appartements, chambres — suivez chaque bien, ses documents et son historique.',
            'feat.prop.tag1'    : 'Multi-sites',
            'feat.prop.tag2'    : 'Documents',
            'feat.prop.tag3'    : 'Historique',
            'feat.tenant.title' : 'Gestion des locataires',
            'feat.tenant.desc'  : 'Contrats, paiements, communications et historique complet pour chaque locataire.',
            'feat.billing.title': 'Facturation automatique',
            'feat.billing.desc' : 'Générez vos quittances de loyer et suivez les paiements en temps réel.',
            'feat.dash.title'   : 'Tableau de bord analytique',
            'feat.dash.desc'    : 'Revenus, taux d\'occupation, rentabilité — tout en un coup d\'œil.',
            'feat.alerts.title' : 'Rappels intelligents',
            'feat.alerts.desc'  : 'Notifications automatiques pour loyers et contrats',
            'feat.docs.title'   : 'Documents & Contrats',
            'feat.docs.desc'    : 'Génération de baux et stockage sécurisé',
            /* How it works */
            'step.1.title'      : 'Créez votre compte',
            'step.1.desc'       : 'Inscrivez-vous gratuitement en moins de 2 minutes, sans carte bancaire.',
            'step.2.title'      : 'Ajoutez vos biens',
            'step.2.desc'       : 'Renseignez vos propriétés, chambres et leurs caractéristiques.',
            'step.3.title'      : 'Gérez vos locataires',
            'step.3.desc'       : 'Ajoutez vos locataires, créez leurs contrats et suivez les paiements.',
            'step.4.title'      : 'Automatisez tout',
            'step.4.desc'       : 'Laissez Lokativ générer les factures, rappels et documents automatiquement.',
            'step.cta'          : 'Commencer maintenant',
            /* Stats */
            'stat.users'        : 'Utilisateurs actifs',
            'stat.props'        : 'Propriétés gérées',
            'stat.revenue'      : 'XOF de loyers traités',
            'stat.satisfaction' : 'Taux de satisfaction',
            /* Partners */
            'partners.label'    : 'Ils nous font confiance',
            /* Biens */
            'biens.badge'       : 'Nos offres du moment',
            'biens.h2a'         : 'Biens',
            'biens.h2b'         : 'Immobiliers',
            'biens.h2c'         : 'Disponibles',
            'biens.sub'         : 'Découvrez nos dernières offres immobilières disponibles',
            /* Portfolio */
            'portfolio.badge'   : 'Interface moderne',
            'portfolio.h2a'     : 'Aperçu de la',
            'portfolio.h2b'     : 'plateforme',
            'portfolio.sub'     : 'Explorez les écrans intuitifs conçus pour simplifier votre gestion immobilière',
            'portfolio.tab1'    : 'Dashboard',
            'portfolio.tab2'    : 'Biens',
            'portfolio.tab3'    : 'Locataires',
            'portfolio.tab4'    : 'Facturation',
            'portfolio.tab5'    : 'Statistiques',
            'portfolio.tab6'    : 'Rapports',
            /* Contact */
            'contact.badge'         : 'Support & Contact',
            'contact.h2'            : 'Contactez-nous',
            'contact.sub'           : 'Notre équipe répond en moins de 2h. Posez vos questions, demandez une démo ou signalez un problème.',
            'contact.form.title'    : 'Envoyez-nous un message',
            'contact.form.sub'      : 'Remplissez le formulaire, nous vous répondons rapidement.',
            'contact.btn'           : 'Envoyer le message',
            'contact.info.address'  : 'Adresse',
            'contact.info.phone'    : 'Téléphone',
            'contact.info.email'    : 'Email',
            'contact.info.hours'    : 'Horaires',
            'contact.social'        : 'Suivez-nous',
            'contact.fast'          : 'Réponse rapide garantie',
            'contact.fast.sub'      : 'Temps de réponse moyen : 1h30 en heures ouvrées',
            'contact.label.nom'     : 'Nom',
            'contact.label.prenom'  : 'Prénom',
            'contact.label.email'   : 'Adresse email',
            'contact.label.sujet'   : 'Sujet',
            'contact.label.msg'     : 'Message',
            'contact.ph.nom'        : 'Votre nom',
            'contact.ph.prenom'     : 'Votre prénom',
            'contact.ph.msg'        : 'Décrivez votre demande...',
            'contact.opt.select'    : 'Sélectionnez un sujet',
            'contact.opt.info'      : 'Demande d\'information',
            'contact.opt.demo'      : 'Demande de démonstration',
            'contact.opt.support'   : 'Support technique',
            'contact.opt.partner'   : 'Partenariat',
            'contact.opt.other'     : 'Autre',
            /* FAQ */
            'faq.cat.start'     : 'Démarrage',
            'faq.cat.billing'   : 'Abonnement & Paiement',
            'faq.cat.features'  : 'Fonctionnalités',
            'faq.q1'            : 'Comment puis-je commencer à utiliser Lokativ ?',
            'faq.q2'            : 'Mes données sont-elles sécurisées ?',
            'faq.q3'            : 'Puis-je changer de plan à tout moment ?',
            'faq.q4'            : 'Comment fonctionne la facturation des SMS et WhatsApp ?',
            'faq.q5'            : 'Comment fonctionne la facturation automatique ?',
            'faq.q6'            : 'Y a-t-il une application mobile ?',
            'faq.q7'            : 'Proposez-vous une assistance technique ?',
            'faq.a1'            : 'C\'est très simple ! Créez un compte gratuit en cliquant sur <strong>"Créer un compte"</strong>, renseignez vos informations et vous pourrez immédiatement ajouter vos propriétés. Aucune carte bancaire n\'est requise pour l\'offre d\'essai.',
            'faq.a2'            : 'Absolument. Nous utilisons un chiffrement <strong>SSL de bout en bout</strong> et nos serveurs sont hébergés dans des centres de données sécurisés. Vos données sont sauvegardées quotidiennement et ne sont jamais partagées avec des tiers.',
            'faq.a3'            : 'Oui, vous pouvez passer à un plan supérieur à tout moment depuis votre espace <strong>"Mon abonnement"</strong>. Le paiement se fait via KKiaPay ou FedaPay. Vous pouvez également rétrograder à la fin de votre période d\'abonnement.',
            'faq.a4'            : 'Les envois SMS et WhatsApp sont <strong>pay-per-use</strong> : vous payez uniquement ce que vous utilisez. Avant chaque envoi, le coût total est calculé selon votre pays et le nombre de destinataires. Le paiement s\'effectue via l\'agrégateur (KKiaPay ou FedaPay) et est vérifié avant l\'envoi.',
            'faq.a5'            : 'Une fois vos locataires et les montants de loyer configurés, Lokativ <strong>génère automatiquement</strong> les quittances à chaque échéance. Vous pouvez envoyer des rappels par email, SMS ou WhatsApp et consulter l\'historique complet des paiements.',
            'faq.a6'            : 'Lokativ est <strong>entièrement responsive</strong> et fonctionne parfaitement sur tous les appareils mobiles via votre navigateur. Vous pouvez également l\'installer comme PWA (Progressive Web App) directement depuis votre téléphone.',
            'faq.a7'            : 'Oui ! Les utilisateurs en essai bénéficient d\'un <strong>support par email</strong>. Les plans payants incluent un support prioritaire via WhatsApp et email avec des temps de réponse garantis sous 24h.',
            'faq.cta.text'      : 'Vous n\'avez pas trouvé votre réponse ?',
            'faq.cta.btn'       : 'Envoyer un message',
            /* Compte / inscription */
            /* Hero mini UI & phone screens */
            'hero.mini.props'          : 'Biens',
            'hero.mini.tenants'        : 'Locataires',
            'hero.mini.revenue'        : 'Revenus',
            'hero.mini.chart'          : 'Encaissements — 6 mois',
            'hero.act.1'               : 'Loyer payé · 85 000 F',
            'hero.act.2'               : 'Contrat signé · Studio',
            'hero.act.3'               : 'Rappel WhatsApp',
            'hero.trust'               : 'Données hébergées en sécurité · SSL · RGPD',
            'phone.s1.title'           : 'Tableau de bord',
            'phone.s2.title'           : 'Mes biens',
            'phone.s2.add'             : 'Ajouter',
            'phone.s3.title'           : 'Locataires',
            'phone.s4.title'           : 'Facturation',
            'phone.unpaid'             : 'Impayés',
            'phone.status.rented'      : 'Loué',
            'phone.status.avail'       : 'Disponible',
            'phone.status.maint'       : 'Intervention',
            'phone.pay.ok'             : 'À jour',
            'phone.pay.late'           : 'Impayé',
            'phone.pay.wait'           : 'En attente',
            'phone.pay.paid'           : 'Payé',
            'phone.billing.collected'  : 'Encaissé',
            'phone.billing.pending'    : 'En attente',
            'reg.badge'             : 'Démarrez gratuitement',
            'reg.h2'                : 'Créer votre compte',
            'reg.sub'               : 'Rejoignez des centaines de gestionnaires immobiliers. Configuré en moins de 3 minutes.',
            'reg.feat1.title'       : 'Données sécurisées',
            'reg.feat1.sub'         : 'Chiffrement SSL · Sauvegarde quotidienne',
            'reg.feat2.title'       : 'Sans CB pour l\'essai',
            'reg.feat2.sub'         : '14 jours gratuits · Annulation facile',
            'reg.feat3.title'       : 'Support inclus',
            'reg.feat3.sub'         : 'Assistance email & WhatsApp',
            'reg.step1.label'       : 'Profil',
            'reg.step2.label'       : 'Infos',
            'reg.step3.label'       : 'Plan',
            'reg.step1.title'       : 'Quel est votre profil ?',
            'reg.type.perso'        : 'Particulier',
            'reg.type.perso.desc'   : 'Gérez votre patrimoine personnel en toute simplicité',
            'reg.type.perso.li1'    : 'Jusqu\'à 10 propriétés',
            'reg.type.perso.li2'    : 'Tableau de bord personnalisé',
            'reg.type.perso.li3'    : 'Support par email',
            'reg.type.biz'          : 'Entreprise',
            'reg.type.biz.desc'     : 'Solution complète pour les professionnels de l\'immobilier',
            'reg.type.biz.li1'      : 'Propriétés illimitées',
            'reg.type.biz.li2'      : 'Multi-agences',
            'reg.type.biz.li3'      : 'Support prioritaire',
            'reg.next'              : 'Continuer',
            'reg.prev'              : 'Retour',
            'reg.step2.title'       : 'Vos informations',
            'reg.label.nom'         : 'Nom',
            'reg.label.prenom'      : 'Prénom',
            'reg.label.email'       : 'Adresse email',
            'reg.label.phone'       : 'Téléphone',
            'reg.company.title'     : 'Informations de l\'entreprise',
            'reg.label.company'     : 'Nom de l\'entreprise',
            'reg.label.address'     : 'Adresse',
            'reg.label.company.email': 'Email de l\'entreprise',
            'reg.step3.title'       : 'Choisissez votre plan',
            'reg.submit'            : 'Créer mon compte',
            'reg.quote'             : '« Lokativ a transformé ma façon de gérer mes 12 propriétés. Je gagne des heures chaque mois. »',
            'reg.quote.role'        : 'Promoteur immobilier · Cotonou',
            'reg.cgu.prefix'        : 'J\'accepte les',
            'reg.cgu.link1'         : 'conditions générales d\'utilisation',
            'reg.cgu.and'           : 'et la',
            'reg.cgu.link2'         : 'politique de confidentialité',
            'reg.cgu.dot'           : '.',
            /* Plans */
            'plan.cat.gestion'      : 'Gestion immobilière',
            'plan.cat.comm'         : 'Communication',
            'plan.cat.visib'        : 'Visibilité',
            'plan.feat.dashboard'   : 'Tableau de bord complet',
            'plan.feat.tenants'     : 'Gestion des locataires',
            'plan.feat.billing'     : 'Facturation automatique',
            'plan.popular'          : 'Populaire',
            'plan.badge.free'       : 'Essai gratuit',
            'plan.badge.featured'   : 'Plus populaire',
            'plan.badge.pro'        : 'Entreprise',
            'plan.badge.default'    : 'Débutant',
            'plan.btn.free'         : 'Essayer gratuitement',
            'plan.btn.featured'     : 'Choisir ce plan',
            'plan.btn.pro'          : 'Nous contacter',
            'plan.btn.default'      : 'Commencer',
            'plan.btn.select'       : 'Choisir ce plan',
            'plan.maisons.unlimited': 'Maisons <strong>illimitées</strong>',
            'plan.maisons.upto'     : 'Jusqu\'à',
            'plan.maisons.unit'     : 'maisons',
            'plan.annexes.unlimited': 'Agences <strong>illimitées</strong>',
            'plan.annexes.upto'     : 'Jusqu\'à',
            'plan.annexes.unit'     : 'agence(s)',
            'plan.annexes.none'     : 'Multi-agences',
            'plan.trial.text'       : 'Pendant <strong>14 jours</strong> · Sans CB requise',
            'plan.price.soit'       : 'soit',
            'plan.price.permonth'   : '/ mois',
            'plan.emails.unlimited' : '<strong>Emails illimités</strong> / mois',
            'plan.emails.suffix'    : 'email(s) / mois',
            'plan.emails.none'      : 'Envoi par email',
            'plan.rappels.unlimited': '<strong>Rappels loyer illimités</strong> (email)',
            'plan.rappels.suffix'   : 'rappel(s) loyer / mois (email)',
            'plan.rappels.none'     : 'Rappels de loyer',
            'plan.preavis.unlimited': '<strong>Préavis illimités</strong> / mois (email)',
            'plan.preavis.suffix'   : 'préavis / mois (email)',
            'plan.preavis.none'     : 'Envoi de préavis',
            'plan.sms_wa'           : 'SMS & WhatsApp',
            'plan.ppu'              : 'Pay-per-use',
            'plan.docs_wa'          : 'Envoi docs WhatsApp',
            'plan.ads.unlimited'    : '<strong>Publicités illimitées</strong>',
            'plan.ads.suffix'       : 'publicité(s) max',
            'plan.note'             : 'Tous les plans incluent une <strong>période d\'essai</strong> · Annulation facile · Support inclus',
            /* Footer */
            'footer.desc'       : 'La plateforme de gestion immobilière conçue pour les propriétaires et agences d\'Afrique de l\'Ouest.',
            'footer.nav'        : 'Navigation',
            'footer.features'   : 'Fonctionnalités',
            'footer.legal'      : 'Légal',
            'footer.newsletter' : 'Restez informé',
            'footer.copy'       : '© __YEAR__ Lokativ — Tous droits réservés. Fabriqué avec ❤ en Afrique de l\'Ouest.',
            'footer.payment'    : 'Paiements acceptés :',
            /* Formulaire inscription — états bouton & erreurs paiement */
            'reg.submit_pay'             : 'Payer et créer mon compte',
            'reg.checking'               : 'Vérification...',
            'reg.opening_payment'        : 'Ouverture du paiement...',
            'reg.creating_account'       : 'Création du compte...',
            'payment.failed_title'       : 'Paiement échoué',
            'payment.kkiapay_failed'     : 'Le paiement KKiaPay n\'a pas abouti. Veuillez réessayer.',
            'payment.fedapay_failed'     : 'Le paiement FedaPay n\'a pas été approuvé. Veuillez réessayer.',
            'payment.retry'              : 'Réessayer',
            'payment.unavailable_title'  : 'Service indisponible',
            'payment.kkiapay_unavailable': 'Le widget de paiement KKiaPay n\'a pas pu être chargé. Vérifiez votre connexion internet et réessayez.',
        },
        en: {
            /* Navigation */
            'nav.home'      : 'Home',
            'nav.features'  : 'Features',
            'nav.preview'   : 'Preview',
            'nav.pricing'   : 'Pricing',
            'nav.contact'   : 'Contact',
            'nav.login'     : 'Log in',
            'nav.start'     : 'Get started',
            'mob.language'  : 'Language',
            'mob.theme'     : 'Display preference',
            'mob.light'     : 'Light',
            'mob.dark'      : 'Dark',
            'mob.login'     : 'Log in',
            'mob.start'     : 'Start for free',
            /* Hero */
            'hero.badge'    : 'Property management platform',
            'hero.new'      : 'New',
            'hero.h1a'      : 'Manage your real estate',
            'hero.h1b'      : 'portfolio online',
            'hero.sub'      : 'Centralize your properties, automate billing, track payments and send documents via WhatsApp — all in one place.',
            'hero.cta1'     : 'Get started for free',
            'hero.cta2'     : 'Watch the demo',
            'hero.stat1'    : '500+ managers',
            'hero.stat2'    : '10,000+ properties managed',
            'hero.stat3'    : '4.9 / 5 satisfaction',
            /* Sections */
            'sec.features.badge': 'All-in-one',
            'sec.features.h2'   : 'Powerful features',
            'sec.features.sub'  : 'All the tools you need to manage your real estate portfolio daily',
            'sec.how.badge'     : 'In 4 steps',
            'sec.how.h2'        : 'How does it work?',
            'sec.how.sub'       : 'Get started in minutes and manage your properties like a pro',
            'sec.testi.badge'   : 'Testimonials',
            'sec.testi.h2a'     : 'What our',
            'sec.testi.h2b'     : 'clients say',
            'sec.testi.sub'     : 'Thousands of managers trust Lokativ to simplify their daily work.',
            'sec.testi.cta1'        : 'Get started for free',
            'sec.testi.cta2'        : '⭐ Share your review',
            'testi.modal.title'     : '⭐ Share your experience',
            'testi.modal.sub'       : 'Your review helps other managers discover Lokativ.',
            'testi.modal.name'      : 'Your name',
            'testi.modal.name_ph'   : 'E.g.: Kofi Mensah',
            'testi.modal.role'      : 'City',
            'testi.modal.role_opt'  : '(optional)',
            'testi.modal.role_ph'   : 'E.g.: Property owner · Lagos',
            'testi.modal.rating'    : 'Rating',
            'testi.modal.review'    : 'Your review',
            'testi.modal.review_ph' : 'Describe your experience with Lokativ (minimum 20 characters)...',
            'testi.modal.submit'    : 'Publish my review',
            'sec.pricing.badge' : 'Pricing',
            'sec.pricing.h2a'   : 'Plans tailored to',
            'sec.pricing.h2b'   : 'every need',
            'sec.pricing.sub'   : 'Start for free. Scale as you grow.',
            'sec.faq.badge'     : 'Help & Answers',
            'sec.faq.h2a'       : 'Frequently asked',
            'sec.faq.h2b'       : 'questions',
            'sec.faq.sub'       : 'Everything you need to know before getting started.',
            'sec.cta.h2a'       : 'Ready to simplify your',
            'sec.cta.h2b'       : 'property management?',
            'sec.cta.sub'       : 'Get started for free in 2 minutes. No credit card required, no commitment. Grow at your own pace.',
            'sec.cta.badge'     : '500+ managers already trust us',
            'sec.cta.btn1'      : 'Create a free account',
            'sec.cta.btn2'      : 'Talk to an expert',
            /* Features */
            'feat.prop.title'   : 'Property Management',
            'feat.prop.desc'    : 'Centralize all your properties in one place. Houses, apartments, rooms — track each property, its documents and full history.',
            'feat.prop.tag1'    : 'Multi-site',
            'feat.prop.tag2'    : 'Documents',
            'feat.prop.tag3'    : 'History',
            'feat.tenant.title' : 'Tenant Management',
            'feat.tenant.desc'  : 'Contracts, payments, communications and complete history for each tenant.',
            'feat.billing.title': 'Automatic Billing',
            'feat.billing.desc' : 'Generate rent receipts and track payments in real time.',
            'feat.dash.title'   : 'Analytics Dashboard',
            'feat.dash.desc'    : 'Revenue, occupancy rate, profitability — all at a glance.',
            'feat.alerts.title' : 'Smart Reminders',
            'feat.alerts.desc'  : 'Automatic notifications for rents and contracts',
            'feat.docs.title'   : 'Documents & Contracts',
            'feat.docs.desc'    : 'Lease generation and secure storage',
            /* How it works */
            'step.1.title'      : 'Create your account',
            'step.1.desc'       : 'Sign up for free in under 2 minutes, no credit card required.',
            'step.2.title'      : 'Add your properties',
            'step.2.desc'       : 'Enter your properties, rooms and their characteristics.',
            'step.3.title'      : 'Manage your tenants',
            'step.3.desc'       : 'Add your tenants, create their contracts and track payments.',
            'step.4.title'      : 'Automate everything',
            'step.4.desc'       : 'Let Lokativ automatically generate invoices, reminders and documents.',
            'step.cta'          : 'Get started now',
            /* Stats */
            'stat.users'        : 'Active users',
            'stat.props'        : 'Properties managed',
            'stat.revenue'      : 'XOF in rents processed',
            'stat.satisfaction' : 'Satisfaction rate',
            /* Partners */
            'partners.label'    : 'They trust us',
            /* Biens */
            'biens.badge'       : 'Current listings',
            'biens.h2a'         : 'Available',
            'biens.h2b'         : 'Real Estate',
            'biens.h2c'         : 'Properties',
            'biens.sub'         : 'Discover our latest available real estate listings',
            /* Portfolio */
            'portfolio.badge'   : 'Modern interface',
            'portfolio.h2a'     : 'Preview of the',
            'portfolio.h2b'     : 'platform',
            'portfolio.sub'     : 'Explore the intuitive screens designed to simplify your property management',
            'portfolio.tab1'    : 'Dashboard',
            'portfolio.tab2'    : 'Properties',
            'portfolio.tab3'    : 'Tenants',
            'portfolio.tab4'    : 'Billing',
            'portfolio.tab5'    : 'Statistics',
            'portfolio.tab6'    : 'Reports',
            /* Contact */
            'contact.badge'         : 'Support & Contact',
            'contact.h2'            : 'Contact us',
            'contact.sub'           : 'Our team responds in under 2 hours. Ask your questions, request a demo or report an issue.',
            'contact.form.title'    : 'Send us a message',
            'contact.form.sub'      : 'Fill in the form, we\'ll get back to you quickly.',
            'contact.btn'           : 'Send message',
            'contact.info.address'  : 'Address',
            'contact.info.phone'    : 'Phone',
            'contact.info.email'    : 'Email',
            'contact.info.hours'    : 'Hours',
            'contact.social'        : 'Follow us',
            'contact.fast'          : 'Fast response guaranteed',
            'contact.fast.sub'      : 'Average response time: 1h30 during business hours',
            'contact.label.nom'     : 'Last name',
            'contact.label.prenom'  : 'First name',
            'contact.label.email'   : 'Email address',
            'contact.label.sujet'   : 'Subject',
            'contact.label.msg'     : 'Message',
            'contact.ph.nom'        : 'Your last name',
            'contact.ph.prenom'     : 'Your first name',
            'contact.ph.msg'        : 'Describe your request...',
            'contact.opt.select'    : 'Select a subject',
            'contact.opt.info'      : 'Information request',
            'contact.opt.demo'      : 'Demo request',
            'contact.opt.support'   : 'Technical support',
            'contact.opt.partner'   : 'Partnership',
            'contact.opt.other'     : 'Other',
            /* FAQ */
            'faq.cat.start'     : 'Getting started',
            'faq.cat.billing'   : 'Subscription & Payment',
            'faq.cat.features'  : 'Features',
            'faq.q1'            : 'How do I get started with Lokativ?',
            'faq.q2'            : 'Is my data secure?',
            'faq.q3'            : 'Can I change my plan at any time?',
            'faq.q4'            : 'How does SMS and WhatsApp billing work?',
            'faq.q5'            : 'How does automatic billing work?',
            'faq.q6'            : 'Is there a mobile app?',
            'faq.q7'            : 'Do you offer technical support?',
            'faq.a1'            : 'It\'s easy! Create a free account by clicking <strong>"Create an account"</strong>, fill in your information and you can immediately add your properties. No credit card is required for the trial offer.',
            'faq.a2'            : 'Absolutely. We use <strong>end-to-end SSL encryption</strong> and our servers are hosted in secure data centers. Your data is backed up daily and never shared with third parties.',
            'faq.a3'            : 'Yes, you can upgrade to a higher plan at any time from your <strong>"My subscription"</strong> space. Payment is made via KKiaPay or FedaPay. You can also downgrade at the end of your subscription period.',
            'faq.a4'            : 'SMS and WhatsApp sending is <strong>pay-per-use</strong>: you only pay for what you use. Before each send, the total cost is calculated based on your country and the number of recipients. Payment is processed via the aggregator (KKiaPay or FedaPay) and verified before sending.',
            'faq.a5'            : 'Once your tenants and rent amounts are configured, Lokativ <strong>automatically generates</strong> receipts at each due date. You can send reminders by email, SMS or WhatsApp and view the full payment history.',
            'faq.a6'            : 'Lokativ is <strong>fully responsive</strong> and works perfectly on all mobile devices via your browser. You can also install it as a PWA (Progressive Web App) directly from your phone.',
            'faq.a7'            : 'Yes! Trial users benefit from <strong>email support</strong>. Paid plans include priority support via WhatsApp and email with guaranteed response times within 24h.',
            'faq.cta.text'      : 'Didn\'t find your answer?',
            'faq.cta.btn'       : 'Send a message',
            /* Compte / inscription */
            /* Hero mini UI & phone screens */
            'hero.mini.props'          : 'Properties',
            'hero.mini.tenants'        : 'Tenants',
            'hero.mini.revenue'        : 'Revenue',
            'hero.mini.chart'          : 'Collections — 6 months',
            'hero.act.1'               : 'Rent paid · 85,000 F',
            'hero.act.2'               : 'Contract signed · Studio',
            'hero.act.3'               : 'WhatsApp reminder',
            'hero.trust'               : 'Securely hosted data · SSL · GDPR',
            'phone.s1.title'           : 'Dashboard',
            'phone.s2.title'           : 'My properties',
            'phone.s2.add'             : 'Add',
            'phone.s3.title'           : 'Tenants',
            'phone.s4.title'           : 'Billing',
            'phone.unpaid'             : 'Unpaid',
            'phone.status.rented'      : 'Rented',
            'phone.status.avail'       : 'Available',
            'phone.status.maint'       : 'Intervention',
            'phone.pay.ok'             : 'Up to date',
            'phone.pay.late'           : 'Unpaid',
            'phone.pay.wait'           : 'Pending',
            'phone.pay.paid'           : 'Paid',
            'phone.billing.collected'  : 'Collected',
            'phone.billing.pending'    : 'Pending',
            'reg.badge'             : 'Get started for free',
            'reg.h2'                : 'Create your account',
            'reg.sub'               : 'Join hundreds of property managers. Set up in under 3 minutes.',
            'reg.feat1.title'       : 'Secure data',
            'reg.feat1.sub'         : 'SSL encryption · Daily backup',
            'reg.feat2.title'       : 'No card for trial',
            'reg.feat2.sub'         : '14 days free · Easy cancellation',
            'reg.feat3.title'       : 'Support included',
            'reg.feat3.sub'         : 'Email & WhatsApp assistance',
            'reg.step1.label'       : 'Profile',
            'reg.step2.label'       : 'Info',
            'reg.step3.label'       : 'Plan',
            'reg.step1.title'       : 'What is your profile?',
            'reg.type.perso'        : 'Individual',
            'reg.type.perso.desc'   : 'Manage your personal assets with ease',
            'reg.type.perso.li1'    : 'Up to 10 properties',
            'reg.type.perso.li2'    : 'Personalized dashboard',
            'reg.type.perso.li3'    : 'Email support',
            'reg.type.biz'          : 'Business',
            'reg.type.biz.desc'     : 'Complete solution for real estate professionals',
            'reg.type.biz.li1'      : 'Unlimited properties',
            'reg.type.biz.li2'      : 'Multi-agency',
            'reg.type.biz.li3'      : 'Priority support',
            'reg.next'              : 'Continue',
            'reg.prev'              : 'Back',
            'reg.step2.title'       : 'Your information',
            'reg.label.nom'         : 'Last name',
            'reg.label.prenom'      : 'First name',
            'reg.label.email'       : 'Email address',
            'reg.label.phone'       : 'Phone',
            'reg.company.title'     : 'Company information',
            'reg.label.company'     : 'Company name',
            'reg.label.address'     : 'Address',
            'reg.label.company.email': 'Company email',
            'reg.step3.title'       : 'Choose your plan',
            'reg.submit'            : 'Create my account',
            'reg.quote'             : '"Lokativ transformed the way I manage my 12 properties. I save hours every month."',
            'reg.quote.role'        : 'Real estate developer · Cotonou',
            'reg.cgu.prefix'        : 'I accept the',
            'reg.cgu.link1'         : 'terms of use',
            'reg.cgu.and'           : 'and the',
            'reg.cgu.link2'         : 'privacy policy',
            'reg.cgu.dot'           : '.',
            /* Plans */
            'plan.cat.gestion'      : 'Property management',
            'plan.cat.comm'         : 'Communication',
            'plan.cat.visib'        : 'Visibility',
            'plan.feat.dashboard'   : 'Full dashboard',
            'plan.feat.tenants'     : 'Tenant management',
            'plan.feat.billing'     : 'Automatic billing',
            'plan.popular'          : 'Popular',
            'plan.badge.free'       : 'Free trial',
            'plan.badge.featured'   : 'Most popular',
            'plan.badge.pro'        : 'Enterprise',
            'plan.badge.default'    : 'Starter',
            'plan.btn.free'         : 'Start for free',
            'plan.btn.featured'     : 'Choose this plan',
            'plan.btn.pro'          : 'Contact us',
            'plan.btn.default'      : 'Get started',
            'plan.btn.select'       : 'Choose this plan',
            'plan.maisons.unlimited': 'Unlimited <strong>houses</strong>',
            'plan.maisons.upto'     : 'Up to',
            'plan.maisons.unit'     : 'houses',
            'plan.annexes.unlimited': 'Unlimited <strong>agencies</strong>',
            'plan.annexes.upto'     : 'Up to',
            'plan.annexes.unit'     : 'agency(ies)',
            'plan.annexes.none'     : 'Multi-agency',
            'plan.trial.text'       : 'For <strong>14 days</strong> · No card required',
            'plan.price.soit'       : 'i.e.',
            'plan.price.permonth'   : '/ month',
            'plan.emails.unlimited' : '<strong>Unlimited emails</strong> / month',
            'plan.emails.suffix'    : 'email(s) / month',
            'plan.emails.none'      : 'Email sending',
            'plan.rappels.unlimited': '<strong>Unlimited rent reminders</strong> (email)',
            'plan.rappels.suffix'   : 'reminder(s) / month (email)',
            'plan.rappels.none'     : 'Rent reminders',
            'plan.preavis.unlimited': '<strong>Unlimited notices</strong> / month (email)',
            'plan.preavis.suffix'   : 'notice(s) / month (email)',
            'plan.preavis.none'     : 'Notice sending',
            'plan.sms_wa'           : 'SMS & WhatsApp',
            'plan.ppu'              : 'Pay-per-use',
            'plan.docs_wa'          : 'Send docs via WhatsApp',
            'plan.ads.unlimited'    : '<strong>Unlimited ads</strong>',
            'plan.ads.suffix'       : 'ad(s) max',
            'plan.note'             : 'All plans include a <strong>trial period</strong> · Easy cancellation · Support included',
            /* Footer */
            'footer.desc'       : 'The property management platform designed for owners and agencies in West Africa.',
            'footer.nav'        : 'Navigation',
            'footer.features'   : 'Features',
            'footer.legal'      : 'Legal',
            'footer.newsletter' : 'Stay informed',
            'footer.copy'       : '© __YEAR__ Lokativ — All rights reserved. Made with ❤ in West Africa.',
            'footer.payment'    : 'Accepted payments:',
            /* Registration form — button states & payment errors */
            'reg.submit_pay'             : 'Pay and create my account',
            'reg.checking'               : 'Checking...',
            'reg.opening_payment'        : 'Opening payment...',
            'reg.creating_account'       : 'Creating account...',
            'payment.failed_title'       : 'Payment failed',
            'payment.kkiapay_failed'     : 'The KKiaPay payment failed. Please try again.',
            'payment.fedapay_failed'     : 'The FedaPay payment was not approved. Please try again.',
            'payment.retry'              : 'Try again',
            'payment.unavailable_title'  : 'Service unavailable',
            'payment.kkiapay_unavailable': 'The KKiaPay payment widget could not be loaded. Check your internet connection and try again.',
        }
    };

    var currentLang = localStorage.getItem('lokativ-lang') || 'fr';

    function _t(key) {
        return ((LANG_DICT[currentLang] || LANG_DICT['fr']) || {})[key] || key;
    }

    function setLang(lang) {
        currentLang = lang;
        localStorage.setItem('lokativ-lang', lang);
        // Cookie synchrone lu directement par le middleware PHP (pas de race condition)
        document.cookie = 'lokativ_locale=' + lang + '; path=/; max-age=31536000; SameSite=Lax';
        fetch('/locale/' + lang).catch(function(){});

        var t = LANG_DICT[lang];
        if (!t) return;

        /* Appliquer les traductions via data-i18n */
        document.querySelectorAll('[data-i18n]').forEach(function(el) {
            var key = el.getAttribute('data-i18n');
            if (t[key] !== undefined) {
                var val = t[key].replace('__YEAR__', new Date().getFullYear());
                el.textContent = val;
            }
        });

        /* Appliquer les placeholders via data-i18n-ph */
        document.querySelectorAll('[data-i18n-ph]').forEach(function(el) {
            var key = el.getAttribute('data-i18n-ph');
            if (t[key] !== undefined) el.placeholder = t[key];
        });

        /* Appliquer via data-i18n-html (innerHTML, pour les balises <strong> etc.) */
        document.querySelectorAll('[data-i18n-html]').forEach(function(el) {
            var key = el.getAttribute('data-i18n-html');
            if (t[key] !== undefined) el.innerHTML = t[key];
        });

        /* Mettre à jour les boutons actifs (desktop + mobile) */
        document.querySelectorAll('[data-lang]').forEach(function(btn) {
            var isActive = btn.getAttribute('data-lang') === lang;
            btn.classList.toggle('active', isActive);
        });

        /* Attribut lang sur <html> */
        document.documentElement.setAttribute('lang', lang);
    }

    /* Init au chargement */
    setLang(currentLang);
    </script>

    <script>
    // ── Carrousel téléphone hero ──────────────────────────────────────────────
    (function () {
        var track  = document.getElementById('phone-track');
        var dots   = document.querySelectorAll('.phone-nav-dot');
        var total  = 4;
        var cur    = 0;
        var timer;

        function goTo(n) {
            cur = ((n % total) + total) % total;
            track.style.transform = 'translateX(-' + (cur * 25) + '%)';
            var isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            dots.forEach(function (d, i) {
                d.style.width      = i === cur ? '18px' : '6px';
                d.style.background = i === cur ? '#3b82f6' : (isDark ? 'rgba(255,255,255,0.22)' : 'rgba(15,23,42,0.18)');
            });
        }

        function restart() {
            clearInterval(timer);
            timer = setInterval(function () { goTo(cur + 1); }, 3600);
        }

        dots.forEach(function (d, i) {
            d.addEventListener('click', function () { goTo(i); restart(); });
        });

        restart();

        // Allow theme toggle to refresh dot colors
        window.phoneRefreshDots = function () { goTo(cur); };
    })();
    </script>

    <script>
    // ── Thème clair / sombre ──────────────────────────────────────────────────
    (function () {
        var html        = document.documentElement;
        var btnDesktop  = document.getElementById('theme-toggle-desktop');
        var iconDesktop = document.getElementById('theme-icon-desktop');
        var btnMobile   = document.getElementById('theme-toggle-mobile');
        var knob        = document.getElementById('theme-toggle-knob');
        var labelMob    = document.getElementById('theme-label-mob');

        // Lire la préférence sauvegardée (ou détecter le système)
        var saved = localStorage.getItem('lokativ-theme');
        var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        var isDark = saved ? saved === 'dark' : prefersDark;

        function apply(dark) {
            isDark = dark;
            html.setAttribute('data-theme', dark ? 'dark' : 'light');
            localStorage.setItem('lokativ-theme', dark ? 'dark' : 'light');

            // Icône desktop
            if (iconDesktop) {
                iconDesktop.className = dark ? 'fas fa-sun' : 'fas fa-moon';
            }
            // Toggle mobile
            if (knob)      knob.textContent   = dark ? '🌙' : '☀️';
            if (knob)      knob.style.transform = dark ? 'translateX(20px)' : 'translateX(0)';
            if (btnMobile) btnMobile.style.background = dark ? '#2563eb' : 'rgba(255,255,255,0.15)';
            if (labelMob)  labelMob.textContent = dark ? 'Sombre' : 'Clair';
            // Refresh phone carousel dot colors
            if (window.phoneRefreshDots) window.phoneRefreshDots();
        }

        // Appliquer au chargement
        apply(isDark);

        // Bouton desktop
        if (btnDesktop) btnDesktop.addEventListener('click', function () { apply(!isDark); });
        // Bouton mobile
        if (btnMobile)  btnMobile.addEventListener('click',  function () { apply(!isDark); });
    })();
    </script>

</body>

</html>