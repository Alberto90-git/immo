<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Lokativ</title>

    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#1e40af">

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
        body { font-family: 'Segoe UI', sans-serif; background: #f8fafc; }
        .legal-nav { background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); }
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
        footer a { color: #9ca3af; transition: color 0.2s; }
        footer a:hover { color: #fff; }
        @media print {
            .legal-nav, .legal-hero, footer, .no-print { display: none !important; }
            .legal-card { box-shadow: none; }
        }
    </style>
</head>
<body>

    {{-- ===== NAVBAR ===== --}}
    <nav class="legal-nav sticky top-0 z-50 shadow-lg">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-3">
                <a href="{{ route('accueil') }}" class="flex items-center space-x-2">
                    <div class="flex items-center justify-center w-9 h-9 rounded-full bg-white/20">
                        <i class="text-white fas fa-home"></i>
                    </div>
                    <span class="text-xl font-bold text-white">Lokativ</span>
                </a>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('accueil') }}" class="flex items-center px-4 py-2 text-sm font-semibold text-blue-600 bg-white rounded-full hover:bg-blue-50 transition-colors">
                        <i class="fas fa-arrow-left me-2"></i> Retour à l'accueil
                    </a>
                    <button onclick="window.print()" class="no-print px-3 py-2 text-sm font-semibold text-white border border-white/30 rounded-full hover:bg-white/10 transition-colors" title="Imprimer / Sauvegarder en PDF">
                        <i class="fas fa-print"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    {{-- ===== HERO ===== --}}
    <div class="legal-hero text-white py-12">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <nav aria-label="Fil d'Ariane" class="mb-3">
                <ol class="flex items-center space-x-2 text-sm text-blue-200">
                    <li><a href="{{ route('accueil') }}" class="hover:text-white transition-colors">Accueil</a></li>
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
                République du Bénin
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
                        <h2 class="text-sm font-bold text-gray-500 uppercase tracking-wider mb-3">
                            <i class="fas fa-list-ul me-1"></i> Sommaire
                        </h2>
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
    <footer class="py-10 text-white bg-gray-900 mt-10">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center space-x-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-gradient-to-r from-blue-600 to-blue-800">
                        <i class="text-sm text-white fas fa-home"></i>
                    </div>
                    <span class="text-lg font-bold">Lokativ</span>
                </div>
                <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-sm text-gray-400">
                    <a href="{{ route('legal.cgu') }}" class="hover:text-white transition-colors">CGU</a>
                    <a href="{{ route('legal.confidentialite') }}" class="hover:text-white transition-colors">Confidentialité</a>
                    <a href="{{ route('legal.mentions') }}" class="hover:text-white transition-colors">Mentions légales</a>
                    <a href="{{ route('legal.cookies') }}" class="hover:text-white transition-colors">Cookies</a>
                </div>
                <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} Lokativ. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    {{-- Smooth scroll pour le sommaire --}}
    <script>
        document.querySelectorAll('.legal-toc a[href^="#"]').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const target = document.querySelector(link.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    </script>
</body>
</html>
