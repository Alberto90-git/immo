@extends('layouts.legal')

@section('title', 'Politique de cookies')
@section('breadcrumb', 'Politique de cookies')
@section('page-title', 'Politique de cookies')
@section('update-date', 'Mise à jour le 28 février 2026')

@section('toc')
    <li><a href="#art1">Art. 1 — Qu'est-ce qu'un cookie ?</a></li>
    <li><a href="#art2">Art. 2 — Base légale</a></li>
    <li><a href="#art3">Art. 3 — Types de cookies</a></li>
    <li><a href="#art4">Art. 4 — Gestion du consentement</a></li>
    <li><a href="#art5">Art. 5 — Paramétrer votre navigateur</a></li>
    <li><a href="#art6">Art. 6 — Durée et droits</a></li>
    <li><a href="#art7">Art. 7 — Mise à jour</a></li>
@endsection

@section('content')

<div class="mb-6 p-4 rounded-xl" style="background:#f5f3ff;border-left:4px solid #8b5cf6;">
    <p class="text-sm text-purple-700 mb-0">
        <i class="fas fa-cookie-bite me-1"></i>
        Cette politique est établie conformément aux <strong>articles 401 à 420 de la Loi n° 2017-20 du 20 avril 2018</strong>
        portant Code du numérique de la République du Bénin et aux décisions de l'<strong>APDP</strong> en matière
        de traçage en ligne.
    </p>
</div>

{{-- ART 1 --}}
<h2 id="art1" class="legal-section-title">Article 1 — Qu'est-ce qu'un cookie ?</h2>
<p class="text-gray-700 leading-relaxed">
    Un cookie est un petit fichier texte déposé sur l'équipement terminal de l'Utilisateur (ordinateur, tablette,
    smartphone) lors de la navigation sur la Plateforme. Les cookies permettent à la Plateforme de mémoriser des
    informations sur la session et les préférences de l'Utilisateur, afin d'améliorer l'expérience de navigation.
</p>

{{-- ART 2 --}}
<h2 id="art2" class="legal-section-title">Article 2 — Base légale</h2>
<p class="text-gray-700 leading-relaxed">La collecte de données via les cookies est encadrée par :</p>
<ul class="mt-2 space-y-2 text-gray-700 list-none ps-0">
    <li class="flex gap-2"><i class="fas fa-book text-purple-400 mt-1 flex-shrink-0"></i><span>Les <strong>articles 401 à 420 de la Loi n° 2017-20 du 20 avril 2018</strong> portant Code du numérique en République du Bénin</span></li>
    <li class="flex gap-2"><i class="fas fa-book text-purple-400 mt-1 flex-shrink-0"></i><span>L'<strong>Acte additionnel CEDEAO A/SA.1/01/10</strong> relatif à la protection des données personnelles</span></li>
    <li class="flex gap-2"><i class="fas fa-book text-purple-400 mt-1 flex-shrink-0"></i><span>Les décisions de l'<strong>APDP</strong> en matière de traçage en ligne</span></li>
</ul>

{{-- ART 3 --}}
<h2 id="art3" class="legal-section-title">Article 3 — Types de cookies utilisés</h2>

{{-- Strictement nécessaires --}}
<div class="mt-4 rounded-xl overflow-hidden" style="border:1px solid #e2e8f0;">
    <div class="px-4 py-3 flex items-center justify-between" style="background:#f0fdf4;">
        <div class="flex items-center gap-2">
            <i class="fas fa-lock text-green-600"></i>
            <span class="font-semibold text-green-800 text-sm">Cookies strictement nécessaires</span>
        </div>
        <span class="text-xs font-semibold text-green-700 px-2 py-1 rounded-full" style="background:#dcfce7;">
            Pas de consentement requis
        </span>
    </div>
    <p class="px-4 py-2 text-xs text-gray-500">Ces cookies sont indispensables au fonctionnement de la Plateforme. Ils ne peuvent pas être désactivés.</p>
    <div class="overflow-x-auto">
        <table class="table table-sm table-bordered table-legal text-sm mb-0">
            <thead>
                <tr>
                    <th>Cookie</th>
                    <th>Finalité</th>
                    <th>Durée</th>
                </tr>
            </thead>
            <tbody>
                @foreach([
                    ['XSRF-TOKEN', 'Protection contre les attaques CSRF', 'Session'],
                    ['laravel_session', 'Maintien de la session utilisateur authentifié', 'Session (2h)'],
                    ['remember_token', 'Connexion persistante (option « rester connecté »)', '60 jours'],
                ] as [$c, $f, $d])
                    <tr>
                        <td class="font-mono text-xs text-blue-700">{{ $c }}</td>
                        <td class="text-gray-700">{{ $f }}</td>
                        <td class="text-gray-600 font-medium">{{ $d }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Performance --}}
<div class="mt-4 rounded-xl overflow-hidden" style="border:1px solid #e2e8f0;">
    <div class="px-4 py-3 flex items-center justify-between" style="background:#fffbeb;">
        <div class="flex items-center gap-2">
            <i class="fas fa-chart-bar text-yellow-600"></i>
            <span class="font-semibold text-yellow-800 text-sm">Cookies de performance et d'analyse</span>
        </div>
        <span class="text-xs font-semibold text-yellow-700 px-2 py-1 rounded-full" style="background:#fef3c7;">
            Consentement requis
        </span>
    </div>
    <p class="px-4 py-2 text-xs text-gray-500">Ces cookies permettent d'analyser l'utilisation de la Plateforme afin de l'améliorer.</p>
    <div class="overflow-x-auto">
        <table class="table table-sm table-bordered table-legal text-sm mb-0">
            <thead>
                <tr>
                    <th>Cookie</th>
                    <th>Finalité</th>
                    <th>Durée</th>
                </tr>
            </thead>
            <tbody>
                @foreach([
                    ['_ga', 'Analyse statistique d\'utilisation', '2 ans'],
                    ['_gid', 'Distinction des utilisateurs uniques', '24 heures'],
                ] as [$c, $f, $d])
                    <tr>
                        <td class="font-mono text-xs text-blue-700">{{ $c }}</td>
                        <td class="text-gray-700">{{ $f }}</td>
                        <td class="text-gray-600 font-medium">{{ $d }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Préférences --}}
<div class="mt-4 rounded-xl overflow-hidden" style="border:1px solid #e2e8f0;">
    <div class="px-4 py-3 flex items-center justify-between" style="background:#f0f9ff;">
        <div class="flex items-center gap-2">
            <i class="fas fa-sliders-h text-blue-600"></i>
            <span class="font-semibold text-blue-800 text-sm">Cookies de préférences</span>
        </div>
        <span class="text-xs font-semibold text-blue-700 px-2 py-1 rounded-full" style="background:#dbeafe;">
            Consentement requis
        </span>
    </div>
    <p class="px-4 py-2 text-xs text-gray-500">Ces cookies mémorisent vos préférences d'affichage.</p>
    <div class="overflow-x-auto">
        <table class="table table-sm table-bordered table-legal text-sm mb-0">
            <thead>
                <tr>
                    <th>Cookie</th>
                    <th>Finalité</th>
                    <th>Durée</th>
                </tr>
            </thead>
            <tbody>
                @foreach([
                    ['lokativ_theme', 'Préférence d\'affichage (thème clair/sombre)', '1 an'],
                    ['lokativ_lang', 'Langue d\'interface choisie', '1 an'],
                ] as [$c, $f, $d])
                    <tr>
                        <td class="font-mono text-xs text-blue-700">{{ $c }}</td>
                        <td class="text-gray-700">{{ $f }}</td>
                        <td class="text-gray-600 font-medium">{{ $d }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 p-3 rounded-lg text-sm text-gray-600" style="background:#f8fafc;border:1px solid #e2e8f0;">
    <i class="fas fa-info-circle text-blue-400 me-1"></i>
    <strong>Cookies tiers :</strong> La Plateforme peut intégrer des services tiers (API WhatsApp, services de paiement mobile money)
    susceptibles de déposer leurs propres cookies. Lokativ n'est pas responsable de ces cookies et invite l'Utilisateur
    à consulter les politiques de confidentialité des prestataires concernés.
</div>

{{-- ART 4 --}}
<h2 id="art4" class="legal-section-title">Article 4 — Gestion du consentement</h2>
<p class="text-gray-700 leading-relaxed">
    Conformément aux dispositions de la Loi n° 2017-20, les cookies non essentiels ne sont déposés qu'après recueil
    du <strong>consentement libre, spécifique, éclairé et univoque</strong> de l'Utilisateur via la bannière de cookies
    affichée lors de la première visite.
</p>
<p class="text-gray-700 leading-relaxed mt-3">
    L'Utilisateur peut modifier ses choix à tout moment depuis :
</p>
<ul class="mt-2 space-y-1 text-gray-700 list-none ps-0">
    <li class="flex gap-2"><i class="fas fa-cog text-blue-400 mt-1 flex-shrink-0"></i><span>Le <strong>panneau de gestion des cookies</strong> accessible en bas de chaque page</span></li>
    <li class="flex gap-2"><i class="fas fa-globe text-blue-400 mt-1 flex-shrink-0"></i><span>Les <strong>paramètres de son navigateur</strong></span></li>
</ul>

{{-- ART 5 --}}
<h2 id="art5" class="legal-section-title">Article 5 — Paramétrer votre navigateur</h2>
<p class="text-gray-700 leading-relaxed mb-3">
    Vous pouvez configurer votre navigateur pour accepter ou refuser les cookies. Les procédures varient selon le navigateur :
</p>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
    @foreach([
        ['fab fa-chrome', 'Google Chrome', 'Paramètres → Confidentialité et sécurité → Cookies et autres données des sites'],
        ['fab fa-firefox', 'Mozilla Firefox', 'Options → Vie privée et sécurité → Cookies et données de sites'],
        ['fab fa-edge', 'Microsoft Edge', 'Paramètres → Confidentialité, recherche et services → Cookies'],
        ['fab fa-safari', 'Safari', 'Préférences → Confidentialité'],
    ] as [$icon, $nav, $desc])
        <div class="p-3 rounded-xl flex gap-3" style="background:#f8fafc;border:1px solid #e2e8f0;">
            <i class="{{ $icon }} text-2xl text-gray-400 mt-1 flex-shrink-0"></i>
            <div>
                <p class="font-semibold text-gray-800 text-sm mb-1">{{ $nav }}</p>
                <p class="text-gray-600 text-xs mb-0">{{ $desc }}</p>
            </div>
        </div>
    @endforeach
</div>
<div class="mt-4 p-3 rounded-lg text-sm text-orange-700" style="background:#fff7ed;border:1px solid #fed7aa;">
    <i class="fas fa-exclamation-triangle me-1"></i>
    <strong>Attention :</strong> Le blocage des cookies strictement nécessaires peut altérer le fonctionnement de la
    Plateforme, notamment empêcher la connexion à votre compte.
</div>

{{-- ART 6 --}}
<h2 id="art6" class="legal-section-title">Article 6 — Durée de conservation et droits</h2>
<p class="text-gray-700 leading-relaxed">
    Les données collectées via les cookies sont conservées pour les durées indiquées dans les tableaux ci-dessus.
    L'Utilisateur dispose des mêmes droits que ceux prévus à la Politique de confidentialité (accès, rectification,
    effacement, opposition).
</p>
<p class="text-gray-700 leading-relaxed mt-3">
    Pour exercer ces droits : <strong>[EMAIL DPO]</strong>
</p>

{{-- ART 7 --}}
<h2 id="art7" class="legal-section-title">Article 7 — Mise à jour de la politique</h2>
<p class="text-gray-700 leading-relaxed">
    La présente politique peut être mise à jour à tout moment pour tenir compte de l'évolution des technologies
    ou de la réglementation. La date de dernière mise à jour est indiquée en haut du document.
</p>

<div class="mt-8 p-4 rounded-xl text-sm text-gray-500" style="background:#f1f5f9;">
    <i class="fas fa-envelope me-1"></i>
    Contact : <strong>[EMAIL CONTACT]</strong> &nbsp;|&nbsp;
    <i class="fas fa-map-marker-alt ms-2 me-1"></i>
    <strong>[NOM DE LA SOCIÉTÉ]</strong>, [ADRESSE], Cotonou, Bénin
</div>

@endsection
