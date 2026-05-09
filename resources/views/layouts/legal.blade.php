<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Lokativ</title>

    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e40af" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">

    {{-- Anti-flash dark mode --}}
    <script>
        (function () {
            var saved = localStorage.getItem('lokativ-theme');
            var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            var dark = saved ? saved === 'dark' : prefersDark;
            if (dark) document.documentElement.setAttribute('data-theme', 'dark');
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                        secondary: '#1e3a8a',
                        accent: '#3b82f6',
                    }
                }
            }
        }
    </script>

    <style>
        *, *::before, *::after {
            transition: background-color 0.25s ease, color 0.18s ease,
                        border-color 0.25s ease, box-shadow 0.25s ease;
        }

        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; }

        /* ══════════════════════════════════════════════
           NAVIGATION (identique à welcome.blade.php)
        ══════════════════════════════════════════════ */
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
        .nav-scrolled .theme-toggle-btn { background: #eff6ff; border-color: #bfdbfe; color: #2563eb; }
        .nav-scrolled .lang-switcher { background: #eff6ff; border-color: #bfdbfe; }
        .nav-scrolled .lang-btn { color: #94a3b8; }
        .nav-scrolled .lang-btn.active { background: #2563eb; color: #fff; border-radius: 18px; }

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

        /* ══════════════════════════════════════════════
           LEGAL SPECIFICS
        ══════════════════════════════════════════════ */
        .legal-hero { background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%); }
        .legal-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(30,64,175,0.07);
        }
        .legal-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e40af;
            border-left: 4px solid #3b82f6;
            padding-left: 12px;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }
        .legal-toc a {
            color: #3b82f6;
            text-decoration: none;
            transition: color 0.2s;
        }
        .legal-toc a:hover { color: #1e40af; text-decoration: underline; }
        .legal-toc li { margin-bottom: 0.4rem; }
        .table-legal th { background: #1e40af; color: #fff; }
        .table-legal td, .table-legal th { padding: 0.65rem 1rem; }
        .badge-updated {
            background: #dbeafe;
            color: #1e40af;
            border-radius: 20px;
            padding: 2px 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        /* ══════════════════════════════════════════════
           FOOTER (identique à welcome.blade.php)
        ══════════════════════════════════════════════ */
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

        /* ══════════════════════════════════════════════
           RESPONSIVE
        ══════════════════════════════════════════════ */
        @media (max-width: 1023px) {
            .footer-grid { grid-template-columns: 1fr 1fr !important; gap: 32px !important; }
        }
        @media (max-width: 768px) {
            .nav-glass, .nav-scrolled { padding-left: 0; padding-right: 0; }
            .footer-grid { grid-template-columns: 1fr 1fr !important; gap: 28px !important; }
        }
        @media (max-width: 640px) {
            .footer-grid { grid-template-columns: 1fr !important; gap: 28px !important; }
            .footer-copyright { flex-direction: column; align-items: flex-start !important; gap: 10px !important; }
        }
        @media (max-width: 480px) {
            .footer-newsletter { flex-direction: column !important; }
            .footer-newsletter input { width: 100% !important; }
        }

        @media print {
            #main-header, .legal-hero, footer, .no-print { display: none !important; }
            .legal-card { box-shadow: none; }
        }

        /* ══════════════════════════════════════════════
           DARK MODE
        ══════════════════════════════════════════════ */
        html[data-theme="dark"] body { background: #0f172a; color: #e2e8f0; }
        html[data-theme="dark"] main { background: #0f172a; }

        html[data-theme="dark"] .legal-card {
            background: #1e293b;
            box-shadow: 0 4px 24px rgba(0,0,0,0.4);
        }
        html[data-theme="dark"] .legal-section-title {
            color: #93c5fd;
            border-left-color: #3b82f6;
        }
        html[data-theme="dark"] .text-gray-500 { color: #94a3b8 !important; }
        html[data-theme="dark"] .legal-toc a { color: #60a5fa; }
        html[data-theme="dark"] .legal-toc a:hover { color: #93c5fd; }
        html[data-theme="dark"] .text-gray-700 { color: #cbd5e1 !important; }
        html[data-theme="dark"] .text-gray-600 { color: #94a3b8 !important; }
        html[data-theme="dark"] .text-gray-800 { color: #e2e8f0 !important; }
        html[data-theme="dark"] .text-gray-400 { color: #64748b !important; }
        html[data-theme="dark"] .text-blue-700   { color: #93c5fd !important; }
        html[data-theme="dark"] .text-green-700  { color: #6ee7b7 !important; }
        html[data-theme="dark"] .text-green-800  { color: #a7f3d0 !important; }
        html[data-theme="dark"] .text-purple-700 { color: #c4b5fd !important; }
        html[data-theme="dark"] .text-yellow-800 { color: #fde68a !important; }
        html[data-theme="dark"] .text-red-700    { color: #fca5a5 !important; }
        html[data-theme="dark"] .text-orange-700 { color: #fdba74 !important; }

        /* Boîtes d'info (inline styles → !important) */
        html[data-theme="dark"] [style*="background:#eff6ff"] { background: rgba(37,99,235,0.14) !important; }
        html[data-theme="dark"] [style*="background:#f0fdf4"] { background: rgba(16,185,129,0.10) !important; }
        html[data-theme="dark"] [style*="background:#f5f3ff"] { background: rgba(139,92,246,0.12) !important; }
        html[data-theme="dark"] [style*="background:#fef3c7"] { background: rgba(245,158,11,0.12) !important; }
        html[data-theme="dark"] [style*="background:#fff1f2"] { background: rgba(239,68,68,0.10) !important; }
        html[data-theme="dark"] [style*="background:#fff7ed"] { background: rgba(249,115,22,0.10) !important; }
        html[data-theme="dark"] [style*="background:#fffbeb"] { background: rgba(245,158,11,0.08) !important; }
        html[data-theme="dark"] [style*="background:#f0f9ff"] { background: rgba(14,165,233,0.08) !important; }
        html[data-theme="dark"] [style*="background:#f8fafc"] { background: #1e293b !important; }
        html[data-theme="dark"] [style*="background:#f1f5f9"] { background: #1e293b !important; }
        html[data-theme="dark"] [style*="background:#dcfce7"] { background: rgba(16,185,129,0.18) !important; }
        html[data-theme="dark"] [style*="background:#dbeafe"] { background: rgba(37,99,235,0.18) !important; }
        html[data-theme="dark"] [style*="background:#d1fae5"] { background: rgba(16,185,129,0.18) !important; }
        html[data-theme="dark"] [style*="color:#065f46"] { color: #6ee7b7 !important; }
        html[data-theme="dark"] [style*="color:#1e40af"] { color: #93c5fd !important; }
        html[data-theme="dark"] [style*="border:1px solid #e2e8f0"] { border-color: #334155 !important; }
        html[data-theme="dark"] [style*="border:1px solid #bbf7d0"] { border-color: rgba(16,185,129,0.3) !important; }
        html[data-theme="dark"] [style*="border:1px solid #fecdd3"] { border-color: rgba(239,68,68,0.3) !important; }
        html[data-theme="dark"] [style*="border:1px solid #fed7aa"] { border-color: rgba(249,115,22,0.3) !important; }
        html[data-theme="dark"] [style*="border-left:4px solid #3b82f6"] { border-left-color: #3b82f6 !important; }
        html[data-theme="dark"] [style*="border-left:4px solid #10b981"] { border-left-color: #10b981 !important; }
        html[data-theme="dark"] [style*="border-left:4px solid #8b5cf6"] { border-left-color: #8b5cf6 !important; }
        html[data-theme="dark"] [style*="border-left:4px solid #f59e0b"] { border-left-color: #f59e0b !important; }
        html[data-theme="dark"] .table { color: #cbd5e1; }
        html[data-theme="dark"] .table-bordered > :not(caption) > * { border-color: #334155; }
        html[data-theme="dark"] .table-bordered > :not(caption) > * > * { border-color: #334155; background-color: transparent; }
        html[data-theme="dark"] .table > tbody > tr > td { color: #cbd5e1; }
        html[data-theme="dark"] .border-b { border-bottom-color: #334155 !important; }
        html[data-theme="dark"] .border-gray-100 { border-color: #334155 !important; }

        /* Nav dark mode (nav-scrolled + glass) */
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

        /* Footer dark mode */
        html[data-theme="dark"] .footer-main { background: #060c18 !important; }
        html[data-theme="dark"] .footer-main [style*="color:#475569"] { color: #94a3b8 !important; }
        html[data-theme="dark"] .footer-main [style*="color:#334155"] { color: #64748b !important; }
        html[data-theme="dark"] .footer-main [style*="color:#64748b"] { color: #94a3b8 !important; }
        html[data-theme="dark"] .footer-link       { color: #64748b !important; }
        html[data-theme="dark"] .footer-link:hover { color: #93c5fd !important; }
        html[data-theme="dark"] .footer-grid { border-bottom-color: rgba(255,255,255,0.06) !important; }
        html[data-theme="dark"] .footer-copyright p { color: #64748b !important; }
        html[data-theme="dark"] .footer-copyright strong { color: #94a3b8 !important; }
        html[data-theme="dark"] .footer-copyright i.fab { color: #64748b !important; }
        html[data-theme="dark"] .footer-copyright [style*="background:rgba(255,255,255,0.06)"] {
            background: rgba(255,255,255,0.06) !important; color: #64748b !important;
        }

        /* Footer light mode */
        html[data-theme="light"] .footer-main { background: #f1f5f9 !important; }
        html[data-theme="light"] .footer-main span[style*="color:#fff"],
        html[data-theme="light"] .footer-main p[style*="color:#fff"],
        html[data-theme="light"] .footer-main h4[style*="color:#fff"] { color: #1e293b !important; }
        html[data-theme="light"] .footer-link       { color: #64748b !important; }
        html[data-theme="light"] .footer-link:hover { color: #2563eb !important; }
        html[data-theme="light"] .footer-main [style*="background:rgba(255,255,255,0.04)"] {
            background: #e2e8f0 !important; border-color: #cbd5e1 !important;
        }
        html[data-theme="light"] .footer-grid { border-bottom-color: #e2e8f0 !important; }
        html[data-theme="light"] .footer-newsletter input {
            background: #fff !important; border-color: #e2e8f0 !important; color: #1e293b !important;
        }
        html[data-theme="light"] .footer-newsletter input::placeholder { color: #94a3b8 !important; }
        html[data-theme="light"] .footer-social { filter: brightness(0.8) saturate(1.2); }
        html[data-theme="light"] .footer-copyright [style*="color:#334155"] { color: #475569 !important; }
        html[data-theme="light"] .footer-copyright i.fab { color: #94a3b8 !important; }
        html[data-theme="light"] .footer-copyright [style*="background:rgba(255,255,255,0.06)"] {
            background: #e2e8f0 !important; color: #475569 !important;
        }
    </style>
</head>
<body>

    {{-- ===== NAVIGATION ===== --}}
    <nav id="main-header" class="fixed z-50 w-full nav-glass" aria-label="Navigation principale">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between" style="height:64px;">

                <!-- Logo -->
                <a href="{{ route('accueil') }}" class="flex items-center gap-2.5" style="text-decoration:none;">
                    <div class="logo-icon flex items-center justify-center rounded-xl" style="width:36px;height:36px;background:linear-gradient(135deg,rgba(255,255,255,0.25),rgba(255,255,255,0.1));border:1px solid rgba(255,255,255,0.25);flex-shrink:0;">
                        <i class="fas fa-home" style="font-size:15px;color:#fff;"></i>
                    </div>
                    <span class="logo-text font-extrabold text-white" style="font-size:1.3rem;letter-spacing:-0.02em;">Lokativ</span>
                </a>

                <!-- Desktop links -->
                <div class="items-center hidden gap-7 md:flex">
                    <a href="{{ route('accueil') }}#accueil"        class="text-white nav-link" style="color:rgba(255,255,255,0.88);" data-i18n="nav.home">Accueil</a>
                    <a href="{{ route('accueil') }}#fonctionnalites" class="nav-link"            style="color:rgba(255,255,255,0.88);" data-i18n="nav.features">Fonctionnalités</a>
                    <a href="{{ route('accueil') }}#portfolio"       class="nav-link"            style="color:rgba(255,255,255,0.88);" data-i18n="nav.preview">Aperçu</a>
                    <a href="{{ route('accueil') }}#tarifs"          class="nav-link"            style="color:rgba(255,255,255,0.88);" data-i18n="nav.pricing">Tarifs</a>
                    <a href="{{ route('accueil') }}#contact"         class="nav-link"            style="color:rgba(255,255,255,0.88);" data-i18n="nav.contact">Contact</a>
                </div>

                <!-- CTA + langue + thème -->
                <div class="items-center hidden gap-3 md:flex">
                    <div class="lang-switcher" role="group" aria-label="Langue">
                        <button class="lang-btn active" data-lang="fr" onclick="setLang('fr')">FR</button>
                        <button class="lang-btn"        data-lang="en" onclick="setLang('en')">EN</button>
                    </div>
                    <button id="theme-toggle-desktop" class="theme-toggle-btn" aria-label="Changer le thème" title="Thème clair / sombre">
                        <i class="fas fa-moon" id="theme-icon-desktop"></i>
                    </button>
                    <a href="{{ route('login') }}" class="header-btn-login">
                        <i class="fas fa-sign-in-alt" style="font-size:11px;"></i>
                        <span data-i18n="nav.login">Se connecter</span>
                    </a>
                    <a href="{{ route('accueil') }}#compte" class="header-btn-register">
                        <i class="fas fa-rocket" style="font-size:11px;"></i>
                        <span data-i18n="nav.start">Démarrer</span>
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

        <div style="flex:1;padding:8px 0;">
            <a href="{{ route('accueil') }}#accueil"        class="mob-nav-link"><i class="fas fa-home"></i>    <span data-i18n="nav.home">Accueil</span></a>
            <a href="{{ route('accueil') }}#fonctionnalites" class="mob-nav-link"><i class="fas fa-bolt"></i>    <span data-i18n="nav.features">Fonctionnalités</span></a>
            <a href="{{ route('accueil') }}#portfolio"       class="mob-nav-link"><i class="fas fa-desktop"></i> <span data-i18n="nav.preview">Aperçu</span></a>
            <a href="{{ route('accueil') }}#tarifs"          class="mob-nav-link"><i class="fas fa-tags"></i>    <span data-i18n="nav.pricing">Tarifs</span></a>
            <a href="{{ route('accueil') }}#contact"         class="mob-nav-link"><i class="fas fa-envelope"></i><span data-i18n="nav.contact">Contact</span></a>
        </div>

        <!-- Langue mobile -->
        <div style="padding:0.75rem 1.5rem;border-top:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.85rem;color:rgba(255,255,255,0.75);font-weight:600;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-globe" style="font-size:13px;color:#93c5fd;"></i>
                <span data-i18n="mob.language">Langue</span>
            </span>
            <div class="mob-lang-switcher">
                <button class="mob-lang-btn active" data-lang="fr" onclick="setLang('fr')">FR</button>
                <button class="mob-lang-btn"        data-lang="en" onclick="setLang('en')">EN</button>
            </div>
        </div>

        <!-- Thème mobile -->
        <div style="padding:0.75rem 1.5rem;border-top:1px solid rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:0.85rem;color:rgba(255,255,255,0.75);font-weight:600;display:flex;align-items:center;gap:8px;">
                <i class="fas fa-palette" style="font-size:13px;color:#93c5fd;"></i>
                <span data-i18n="mob.theme">Préférence d'affichage</span>
            </span>
            <div style="display:flex;align-items:center;gap:8px;">
                <span id="theme-label-mob" style="font-size:0.75rem;color:rgba(255,255,255,0.55);" data-i18n="mob.light">Clair</span>
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
                <i class="fas fa-sign-in-alt" style="margin-right:6px;font-size:12px;"></i>
                <span data-i18n="mob.login">Se connecter</span>
            </a>
            <a href="{{ route('accueil') }}#compte" style="display:block;text-align:center;padding:12px;border-radius:50px;font-weight:700;font-size:0.9rem;color:#fff;background:linear-gradient(135deg,#1d4ed8,#2563eb);text-decoration:none;box-shadow:0 4px 14px rgba(37,99,235,0.35);">
                <i class="fas fa-rocket" style="margin-right:6px;font-size:12px;"></i>
                <span data-i18n="mob.start">Démarrer gratuitement</span>
            </a>
        </div>
    </div>

    {{-- ===== HERO ===== --}}
    <div class="legal-hero text-white py-12" style="padding-top:calc(3rem + 64px);">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <nav aria-label="{{ __('legal.breadcrumb_aria') }}" class="mb-3">
                <ol class="flex items-center space-x-2 text-sm text-blue-200">
                    <li><a href="{{ route('accueil') }}" class="hover:text-white transition-colors">{{ __('legal.home') }}</a></li>
                    <li><i class="fas fa-chevron-right text-xs mx-1"></i></li>
                    <li class="text-white font-medium">@yield('breadcrumb')</li>
                </ol>
            </nav>
            <h1 class="text-3xl font-bold sm:text-4xl">@yield('page-title')</h1>
            <p class="mt-2 text-blue-200 text-sm">
                <i class="fas fa-calendar-alt me-1"></i>
                @yield('update-date')
                &nbsp;·&nbsp;
                <i class="fas fa-map-marker-alt me-1"></i>
                {{ __('legal.country') }}
            </p>
        </div>
    </div>

    {{-- ===== CONTENU ===== --}}
    <main class="py-10">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-4">

                {{-- Table des matières --}}
                <aside class="lg:col-span-1 no-print">
                    <div class="legal-card p-5 sticky top-24">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-0">
                                <i class="fas fa-list-ul me-1"></i> {{ __('legal.toc') }}
                            </h2>
                            <button onclick="window.print()" class="text-gray-400 hover:text-blue-600 transition-colors" title="{{ __('legal.print') }}" style="background:none;border:none;padding:4px 6px;cursor:pointer;font-size:13px;">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>
                        <ul class="legal-toc text-sm list-none ps-0">
                            @yield('toc')
                        </ul>
                    </div>
                </aside>

                {{-- Corps du document --}}
                <div class="lg:col-span-3">
                    <div class="legal-card p-6 p-md-8">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- ===== FOOTER ===== --}}
    <footer class="footer-main" style="padding:4rem 0 0;" role="contentinfo">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="footer-grid" style="display:grid;grid-template-columns:1.6fr 1fr 1fr 1fr;gap:48px;padding-bottom:3rem;border-bottom:1px solid rgba(255,255,255,0.06);">

                <!-- Colonne 1 — Brand -->
                <div>
                    <a href="{{ route('accueil') }}" style="display:inline-flex;align-items:center;gap:10px;text-decoration:none;margin-bottom:1.25rem;">
                        <div style="width:38px;height:38px;border-radius:11px;background:linear-gradient(135deg,#1d4ed8,#3b82f6);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-home" style="color:#fff;font-size:15px;"></i>
                        </div>
                        <span style="font-size:1.35rem;font-weight:900;color:#fff;letter-spacing:-0.02em;">Lokativ</span>
                    </a>
                    <p style="font-size:0.83rem;color:#475569;line-height:1.75;margin-bottom:1.5rem;max-width:260px;" data-i18n="footer.desc">
                        La plateforme de gestion immobilière conçue pour les propriétaires et agences d'Afrique de l'Ouest.
                    </p>
                    <div style="display:flex;gap:8px;margin-bottom:2rem;">
                        <a href="#" class="footer-social" style="background:rgba(59,89,152,0.2);color:#7b9ed9;" title="Facebook"><i class="fab fa-facebook-f" style="font-size:12px;"></i></a>
                        <a href="#" class="footer-social" style="background:rgba(29,161,242,0.15);color:#5aabee;" title="Twitter"><i class="fab fa-twitter" style="font-size:12px;"></i></a>
                        <a href="#" class="footer-social" style="background:rgba(225,48,108,0.15);color:#e1306c;" title="Instagram"><i class="fab fa-instagram" style="font-size:12px;"></i></a>
                        <a href="#" class="footer-social" style="background:rgba(10,102,194,0.15);color:#5d9dd5;" title="LinkedIn"><i class="fab fa-linkedin-in" style="font-size:12px;"></i></a>
                        <a href="#" class="footer-social" style="background:rgba(37,211,102,0.12);color:#25d366;" title="WhatsApp"><i class="fab fa-whatsapp" style="font-size:13px;"></i></a>
                    </div>
                    <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:10px;padding:8px 12px;">
                        <i class="fas fa-lock" style="color:#4ade80;font-size:11px;"></i>
                        <span style="font-size:0.72rem;color:#475569;font-weight:600;">SSL · Données sécurisées · RGPD</span>
                    </div>
                </div>

                <!-- Colonne 2 — Navigation -->
                <div>
                    <h4 style="font-size:0.72rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:1.1rem;" data-i18n="footer.nav">Navigation</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:2px;">
                        <li><a href="{{ route('accueil') }}#accueil"        class="footer-link"><i class="fas fa-chevron-right"></i><span data-i18n="nav.home">Accueil</span></a></li>
                        <li><a href="{{ route('accueil') }}#fonctionnalites" class="footer-link"><i class="fas fa-chevron-right"></i><span data-i18n="nav.features">Fonctionnalités</span></a></li>
                        <li><a href="{{ route('accueil') }}#portfolio"       class="footer-link"><i class="fas fa-chevron-right"></i><span data-i18n="nav.preview">Aperçu</span></a></li>
                        <li><a href="{{ route('accueil') }}#tarifs"          class="footer-link"><i class="fas fa-chevron-right"></i><span data-i18n="nav.pricing">Tarifs</span></a></li>
                        <li><a href="{{ route('accueil') }}#contact"         class="footer-link"><i class="fas fa-chevron-right"></i><span data-i18n="nav.contact">Contact</span></a></li>
                        <li><a href="{{ route('login') }}"                   class="footer-link"><i class="fas fa-chevron-right"></i><span data-i18n="nav.login">Se connecter</span></a></li>
                    </ul>
                </div>

                <!-- Colonne 3 — Fonctionnalités -->
                <div>
                    <h4 style="font-size:0.72rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:1.1rem;" data-i18n="footer.features">Fonctionnalités</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:2px;">
                        @foreach([
                            ['fr'=>'Gestion des biens',       'en'=>'Property management'],
                            ['fr'=>'Locataires & contrats',   'en'=>'Tenants & contracts'],
                            ['fr'=>'Facturation automatique', 'en'=>'Automatic billing'],
                            ['fr'=>'Rapports PDF',            'en'=>'PDF reports'],
                            ['fr'=>'Notifications WhatsApp',  'en'=>'WhatsApp notifications'],
                            ['fr'=>'Multi-agences',           'en'=>'Multi-agency'],
                        ] as $feat)
                        <li>
                            <a href="{{ route('accueil') }}#fonctionnalites" class="footer-link">
                                <i class="fas fa-chevron-right"></i>
                                {{ app()->getLocale() === 'en' ? $feat['en'] : $feat['fr'] }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Colonne 4 — Légal + Newsletter -->
                <div>
                    <h4 style="font-size:0.72rem;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:1.1rem;" data-i18n="footer.legal">Légal</h4>
                    <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:2px;">
                        <li><a href="{{ route('legal.cgu') }}"             class="footer-link"><i class="fas fa-chevron-right"></i>{{ __('legal.link_cgu') }}</a></li>
                        <li><a href="{{ route('legal.confidentialite') }}" class="footer-link"><i class="fas fa-chevron-right"></i>{{ __('legal.link_privacy') }}</a></li>
                        <li><a href="{{ route('legal.mentions') }}"        class="footer-link"><i class="fas fa-chevron-right"></i>{{ __('legal.link_mentions') }}</a></li>
                        <li><a href="{{ route('legal.cookies') }}"         class="footer-link"><i class="fas fa-chevron-right"></i>{{ __('legal.link_cookies') }}</a></li>
                    </ul>

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

            <!-- Copyright -->
            <div class="footer-copyright" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px;padding:1.5rem 0;">
                <p data-i18n-html="footer.copy" style="font-size:0.78rem;color:#334155;margin:0;">
                    &copy; {{ date('Y') }} <strong style="color:#475569;">Lokativ</strong> — {{ __('legal.rights') }}
                    Fabriqué avec <i class="fas fa-heart" style="color:#ef4444;font-size:10px;"></i> en Afrique de l'Ouest.
                </p>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="font-size:0.72rem;color:#334155;font-weight:600;" data-i18n="footer.payment">Paiements acceptés :</span>
                    <i class="fab fa-cc-visa"       style="font-size:1.4rem;color:#334155;"></i>
                    <i class="fab fa-cc-mastercard" style="font-size:1.4rem;color:#334155;"></i>
                    <span style="font-size:0.72rem;color:#334155;font-weight:700;background:rgba(255,255,255,0.06);padding:3px 8px;border-radius:6px;">Mobile Money</span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bouton retour en haut -->
    <button id="back-to-top" aria-label="Retour en haut" style="display:none;position:fixed;bottom:28px;right:28px;z-index:999;width:44px;height:44px;border-radius:50%;border:none;background:linear-gradient(135deg,#1d4ed8,#2563eb);color:#fff;font-size:15px;cursor:pointer;box-shadow:0 4px 18px rgba(37,99,235,0.4);transition:all 0.22s;align-items:center;justify-content:center;"
        onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(37,99,235,0.5)'"
        onmouseout="this.style.transform='';this.style.boxShadow='0 4px 18px rgba(37,99,235,0.4)'">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Smooth scroll pour le sommaire --}}
    <script>
        document.querySelectorAll('.legal-toc a[href^="#"]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                var target = document.querySelector(link.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    </script>

    {{-- Mobile menu --}}
    <script>
    (function () {
        var btn     = document.getElementById('mobile-menu-btn');
        var overlay = document.getElementById('mobile-menu-overlay');
        var panel   = document.getElementById('mobile-menu-panel');
        var closeBtn= document.getElementById('mobile-menu-close');
        var burger  = document.getElementById('hamburger');

        function open() {
            overlay.classList.add('active');
            panel.classList.add('active');
            burger.classList.add('active');
            document.body.style.overflow = 'hidden';
            btn && btn.setAttribute('aria-expanded', 'true');
        }
        function close() {
            overlay.classList.remove('active');
            panel.classList.remove('active');
            burger.classList.remove('active');
            document.body.style.overflow = 'auto';
            btn && btn.setAttribute('aria-expanded', 'false');
        }

        if (btn)     btn.addEventListener('click', open);
        if (closeBtn) closeBtn.addEventListener('click', close);
        if (overlay) overlay.addEventListener('click', close);
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') close(); });
    })();
    </script>

    {{-- Nav scroll --}}
    <script>
    (function () {
        var header = document.getElementById('main-header');
        function tick() {
            var hero = document.querySelector('.legal-hero');
            var heroH = hero ? hero.offsetHeight : 80;
            if (window.scrollY > heroH - 80) {
                header.classList.add('nav-scrolled');
            } else {
                header.classList.remove('nav-scrolled');
            }
        }
        window.addEventListener('scroll', tick, { passive: true });
        tick();
    })();
    </script>

    {{-- Retour en haut --}}
    <script>
    (function () {
        var btn = document.getElementById('back-to-top');
        if (!btn) return;
        window.addEventListener('scroll', function() {
            btn.style.display = window.scrollY > 500 ? 'flex' : 'none';
        }, { passive: true });
        btn.addEventListener('click', function() {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    })();
    </script>

    {{-- Sélecteur de langue + traductions nav/footer --}}
    <script>
    var LANG_DICT = {
        fr: {
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
            'footer.desc'   : 'La plateforme de gestion immobilière conçue pour les propriétaires et agences d\'Afrique de l\'Ouest.',
            'footer.nav'    : 'Navigation',
            'footer.features': 'Fonctionnalités',
            'footer.legal'  : 'Légal',
            'footer.newsletter': 'Restez informé',
            'footer.copy'   : '© __YEAR__ <strong style="color:#475569;">Lokativ</strong> — Tous droits réservés. Fabriqué avec <i class="fas fa-heart" style="color:#ef4444;font-size:10px;"></i> en Afrique de l\'Ouest.',
            'footer.payment': 'Paiements acceptés :',
        },
        en: {
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
            'footer.desc'   : 'The property management platform designed for landlords and agencies in West Africa.',
            'footer.nav'    : 'Navigation',
            'footer.features': 'Features',
            'footer.legal'  : 'Legal',
            'footer.newsletter': 'Stay informed',
            'footer.copy'   : '© __YEAR__ <strong style="color:#475569;">Lokativ</strong> — All rights reserved. Made with <i class="fas fa-heart" style="color:#ef4444;font-size:10px;"></i> in West Africa.',
            'footer.payment': 'Accepted payments:',
        }
    };

    var currentLang = localStorage.getItem('lokativ-lang') || '{{ app()->getLocale() }}';

    function setLang(lang) {
        currentLang = lang;
        localStorage.setItem('lokativ-lang', lang);
        document.cookie = 'lokativ_locale=' + lang + '; path=/; max-age=31536000; SameSite=Lax';
        fetch('/locale/' + lang).catch(function(){});

        var t = LANG_DICT[lang];
        if (!t) return;

        document.querySelectorAll('[data-i18n]').forEach(function(el) {
            var key = el.getAttribute('data-i18n');
            if (t[key] !== undefined) el.textContent = t[key].replace('__YEAR__', new Date().getFullYear());
        });

        document.querySelectorAll('[data-i18n-html]').forEach(function(el) {
            var key = el.getAttribute('data-i18n-html');
            if (t[key] !== undefined) el.innerHTML = t[key].replace('__YEAR__', new Date().getFullYear());
        });

        document.querySelectorAll('[data-lang]').forEach(function(btn) {
            btn.classList.toggle('active', btn.getAttribute('data-lang') === lang);
        });

        document.documentElement.setAttribute('lang', lang);
    }

    setLang(currentLang);
    </script>

    {{-- Toggle thème --}}
    <script>
    (function () {
        var html      = document.documentElement;
        var btnDesk   = document.getElementById('theme-toggle-desktop');
        var iconDesk  = document.getElementById('theme-icon-desktop');
        var btnMob    = document.getElementById('theme-toggle-mobile');
        var knobMob   = document.getElementById('theme-toggle-knob');
        var labelMob  = document.getElementById('theme-label-mob');

        function apply(dark) {
            html.setAttribute('data-theme', dark ? 'dark' : 'light');
            localStorage.setItem('lokativ-theme', dark ? 'dark' : 'light');

            if (iconDesk) iconDesk.className = dark ? 'fas fa-sun' : 'fas fa-moon';

            if (knobMob) {
                knobMob.style.transform = dark ? 'translateX(20px)' : 'translateX(0)';
                knobMob.textContent = dark ? '🌙' : '☀️';
            }
            if (labelMob) {
                var t = LANG_DICT[currentLang] || {};
                labelMob.textContent = dark ? (t['mob.dark'] || 'Sombre') : (t['mob.light'] || 'Clair');
            }
        }

        var isDark = html.getAttribute('data-theme') === 'dark';
        apply(isDark);

        if (btnDesk) {
            btnDesk.addEventListener('click', function () {
                isDark = html.getAttribute('data-theme') !== 'dark';
                apply(isDark);
            });
        }
        if (btnMob) {
            btnMob.addEventListener('click', function () {
                isDark = html.getAttribute('data-theme') !== 'dark';
                apply(isDark);
            });
        }
    })();
    </script>
</body>
</html>
