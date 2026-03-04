@extends('layouts.legal')

@section('title', 'Mentions légales')
@section('breadcrumb', 'Mentions légales')
@section('page-title', 'Mentions légales')
@section('update-date', 'Mise à jour le 28 février 2026')

@section('toc')
    <li><a href="#editeur">Éditeur de la Plateforme</a></li>
    <li><a href="#hebergement">Hébergement</a></li>
    <li><a href="#propriete">Propriété intellectuelle</a></li>
    <li><a href="#responsabilite">Responsabilité</a></li>
    <li><a href="#liens">Liens hypertextes</a></li>
    <li><a href="#droit">Droit applicable</a></li>
    <li><a href="#mediation">Médiation</a></li>
@endsection

@section('content')

<div class="mb-6 p-4 rounded-xl" style="background:#fef3c7;border-left:4px solid #f59e0b;">
    <p class="text-sm text-yellow-800 mb-0">
        <i class="fas fa-balance-scale me-1"></i>
        Mentions légales établies conformément aux <strong>articles 60 à 63 de la Loi n° 2017-20 du 20 avril 2018</strong>
        portant Code du numérique en République du Bénin.
    </p>
</div>

{{-- ÉDITEUR --}}
<h2 id="editeur" class="legal-section-title">Éditeur de la Plateforme</h2>
<div class="mt-3 rounded-xl overflow-hidden" style="border:1px solid #e2e8f0;">
    <div class="p-3 font-semibold text-white text-sm" style="background:#1e40af;">
        <i class="fas fa-building me-2"></i>Informations sur la société éditrice
    </div>
    <div class="p-4">
        @foreach([
            ['Dénomination sociale', '[NOM DE LA SOCIÉTÉ]'],
            ['Forme juridique', '[SARL / SA / GIE / etc.]'],
            ['Capital social', '[MONTANT] FCFA'],
            ['Siège social', '[ADRESSE COMPLÈTE], Cotonou, République du Bénin'],
            ['RCCM', '[NUMÉRO] — Tribunal de Commerce de Cotonou'],
            ['IFU', '[NUMÉRO]'],
            ['Téléphone', '[NUMÉRO]'],
            ['Email', '[EMAIL]'],
            ['Directeur de la publication', '[NOM ET PRÉNOM], [Gérant / Directeur Général]'],
        ] as [$label, $value])
            <div class="flex flex-col sm:flex-row sm:items-center py-2 border-b border-gray-100 last:border-0">
                <span class="text-gray-500 text-sm sm:w-52 flex-shrink-0">{{ $label }}</span>
                <span class="text-gray-800 font-medium text-sm">{{ $value }}</span>
            </div>
        @endforeach
    </div>
</div>

{{-- HÉBERGEMENT --}}
<h2 id="hebergement" class="legal-section-title">Hébergement</h2>
<div class="mt-3 rounded-xl overflow-hidden" style="border:1px solid #e2e8f0;">
    <div class="p-3 font-semibold text-white text-sm" style="background:#1e40af;">
        <i class="fas fa-server me-2"></i>Hébergeur de la Plateforme
    </div>
    <div class="p-4">
        @foreach([
            ['Hébergeur', '[NOM DE L\'HÉBERGEUR]'],
            ['Adresse', '[ADRESSE DE L\'HÉBERGEUR]'],
            ['Pays', '[PAYS]'],
            ['Site web / Contact', '[SITE WEB / CONTACT]'],
        ] as [$label, $value])
            <div class="flex flex-col sm:flex-row sm:items-center py-2 border-b border-gray-100 last:border-0">
                <span class="text-gray-500 text-sm sm:w-52 flex-shrink-0">{{ $label }}</span>
                <span class="text-gray-800 font-medium text-sm">{{ $value }}</span>
            </div>
        @endforeach
    </div>
</div>

{{-- PROPRIÉTÉ INTELLECTUELLE --}}
<h2 id="propriete" class="legal-section-title">Propriété intellectuelle</h2>
<p class="text-gray-700 leading-relaxed">
    La Plateforme <strong>Lokativ</strong>, son interface, ses fonctionnalités, son code source, sa base de données,
    ses marques, logos et tous les contenus qui y sont publiés constituent des œuvres de l'esprit protégées
    par les dispositions applicables en République du Bénin en matière de propriété intellectuelle, notamment :
</p>
<ul class="mt-3 space-y-2 text-gray-700 list-none ps-0">
    <li class="flex gap-2"><i class="fas fa-gavel text-blue-400 mt-1 flex-shrink-0"></i><span>La <strong>Loi n° 2005-30 du 5 avril 2006</strong> relative à la protection du droit d'auteur et des droits voisins en République du Bénin</span></li>
    <li class="flex gap-2"><i class="fas fa-gavel text-blue-400 mt-1 flex-shrink-0"></i><span>L'<strong>Accord de Bangui révisé</strong> (OAPI) sur la propriété intellectuelle</span></li>
    <li class="flex gap-2"><i class="fas fa-gavel text-blue-400 mt-1 flex-shrink-0"></i><span>La <strong>Convention de Berne</strong> pour la protection des œuvres littéraires et artistiques</span></li>
</ul>
<div class="mt-4 p-3 rounded-lg text-sm text-red-700" style="background:#fff1f2;border:1px solid #fecdd3;">
    <i class="fas fa-exclamation-triangle me-1"></i>
    Toute reproduction, représentation, modification, publication, adaptation ou transmission de tout ou partie
    de la Plateforme, par quelque moyen que ce soit, est <strong>interdite sans autorisation écrite préalable</strong>
    de <strong>[NOM DE LA SOCIÉTÉ]</strong>.
</div>

{{-- RESPONSABILITÉ --}}
<h2 id="responsabilite" class="legal-section-title">Responsabilité</h2>
<p class="text-gray-700 leading-relaxed">
    <strong>[NOM DE LA SOCIÉTÉ]</strong> s'efforce d'assurer l'exactitude et la mise à jour des informations
    disponibles sur la Plateforme. Toutefois, elle ne peut garantir l'exactitude, la précision ou l'exhaustivité
    des informations mises à la disposition. Elle décline toute responsabilité pour les dommages résultant d'une
    interruption de service, d'une intrusion extérieure ou de la présence de virus informatiques.
</p>

{{-- LIENS --}}
<h2 id="liens" class="legal-section-title">Liens hypertextes</h2>
<p class="text-gray-700 leading-relaxed">
    La Plateforme peut contenir des liens vers des sites tiers. <strong>[NOM DE LA SOCIÉTÉ]</strong> n'exerce
    aucun contrôle sur ces sites et décline toute responsabilité quant à leur contenu.
</p>

{{-- DROIT APPLICABLE --}}
<h2 id="droit" class="legal-section-title">Droit applicable</h2>
<p class="text-gray-700 leading-relaxed">
    Les présentes mentions légales sont régies par le droit de la <strong>République du Bénin</strong>. Tout litige
    relatif à leur interprétation sera soumis aux juridictions compétentes du ressort de <strong>Cotonou</strong>.
</p>

{{-- MÉDIATION --}}
<h2 id="mediation" class="legal-section-title">Médiation et règlement des litiges</h2>
<p class="text-gray-700 leading-relaxed">
    Conformément à la Loi n° 2017-20, les litiges relatifs au commerce électronique peuvent faire l'objet d'une
    médiation avant tout recours judiciaire. L'Utilisateur peut contacter notre service client à
    <strong>[EMAIL]</strong> pour toute tentative de résolution amiable.
</p>

<div class="mt-8 p-4 rounded-xl text-sm text-gray-500" style="background:#f1f5f9;">
    <i class="fas fa-envelope me-1"></i>
    Contact : <strong>[EMAIL CONTACT]</strong> &nbsp;|&nbsp;
    <i class="fas fa-map-marker-alt ms-2 me-1"></i>
    <strong>[NOM DE LA SOCIÉTÉ]</strong>, [ADRESSE], Cotonou, Bénin
</div>

@endsection
