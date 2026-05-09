@extends('layouts.legal')

@php $isEn = app()->getLocale() === 'en'; @endphp

@section('title', $isEn ? 'Legal Notice' : 'Mentions légales')
@section('breadcrumb', $isEn ? 'Legal Notice' : 'Mentions légales')
@section('page-title', $isEn ? 'Legal Notice' : 'Mentions légales')
@section('update-date', $isEn ? 'Updated February 28, 2026' : 'Mise à jour le 28 février 2026')

@section('toc')
@if($isEn)
    <li><a href="#editeur">Publisher</a></li>
    <li><a href="#hebergement">Hosting</a></li>
    <li><a href="#propriete">Intellectual Property</a></li>
    <li><a href="#responsabilite">Liability</a></li>
    <li><a href="#liens">Hyperlinks</a></li>
    <li><a href="#droit">Governing Law</a></li>
    <li><a href="#mediation">Mediation</a></li>
@else
    <li><a href="#editeur">Éditeur de la Plateforme</a></li>
    <li><a href="#hebergement">Hébergement</a></li>
    <li><a href="#propriete">Propriété intellectuelle</a></li>
    <li><a href="#responsabilite">Responsabilité</a></li>
    <li><a href="#liens">Liens hypertextes</a></li>
    <li><a href="#droit">Droit applicable</a></li>
    <li><a href="#mediation">Médiation</a></li>
@endif
@endsection

@section('content')

@if($isEn)
<div class="mb-6 p-4 rounded-xl" style="background:#fef3c7;border-left:4px solid #f59e0b;">
    <p class="text-sm text-yellow-800 mb-0">
        <i class="fas fa-balance-scale me-1"></i>
        Legal notice established in accordance with <strong>Articles 60 to 63 of Law No. 2017-20 of April 20, 2018</strong>
        on the Digital Code of the Republic of Benin.
    </p>
</div>
@else
<div class="mb-6 p-4 rounded-xl" style="background:#fef3c7;border-left:4px solid #f59e0b;">
    <p class="text-sm text-yellow-800 mb-0">
        <i class="fas fa-balance-scale me-1"></i>
        Mentions légales établies conformément aux <strong>articles 60 à 63 de la Loi n° 2017-20 du 20 avril 2018</strong>
        portant Code du numérique en République du Bénin.
    </p>
</div>
@endif

{{-- PUBLISHER --}}
<h2 id="editeur" class="legal-section-title">{{ $isEn ? 'Platform Publisher' : 'Éditeur de la Plateforme' }}</h2>
<div class="mt-3 rounded-xl overflow-hidden" style="border:1px solid #e2e8f0;">
    <div class="p-3 font-semibold text-white text-sm" style="background:#1e40af;">
        <i class="fas fa-building me-2"></i>{{ $isEn ? 'Publisher company information' : 'Informations sur la société éditrice' }}
    </div>
    <div class="p-4">
        @foreach($isEn ? [
            ['Company name', '[COMPANY NAME]'],
            ['Legal form', '[LLC / Corp / etc.]'],
            ['Share capital', '[AMOUNT]'],
            ['Registered office', '[FULL ADDRESS], Cotonou, Republic of Benin'],
            ['RCCM', '[NUMBER] — Commercial Court of Cotonou'],
            ['Tax ID (IFU)', '[NUMBER]'],
            ['Phone', '[NUMBER]'],
            ['Email', '[EMAIL]'],
            ['Publication director', '[FULL NAME], [Manager / CEO]'],
        ] : [
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

{{-- HOSTING --}}
<h2 id="hebergement" class="legal-section-title">{{ $isEn ? 'Hosting' : 'Hébergement' }}</h2>
<div class="mt-3 rounded-xl overflow-hidden" style="border:1px solid #e2e8f0;">
    <div class="p-3 font-semibold text-white text-sm" style="background:#1e40af;">
        <i class="fas fa-server me-2"></i>{{ $isEn ? 'Platform hosting provider' : 'Hébergeur de la Plateforme' }}
    </div>
    <div class="p-4">
        @foreach($isEn ? [
            ['Host', '[HOST NAME]'],
            ['Address', '[HOST ADDRESS]'],
            ['Country', '[COUNTRY]'],
            ['Website / Contact', '[WEBSITE / CONTACT]'],
        ] : [
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

{{-- INTELLECTUAL PROPERTY --}}
<h2 id="propriete" class="legal-section-title">{{ $isEn ? 'Intellectual Property' : 'Propriété intellectuelle' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    The <strong>Lokativ</strong> Platform, its interface, features, source code, database, trademarks, logos
    and all content published therein constitute intellectual works protected by applicable provisions in
    the Republic of Benin on intellectual property, notably:
@else
    La Plateforme <strong>Lokativ</strong>, son interface, ses fonctionnalités, son code source, sa base de données,
    ses marques, logos et tous les contenus qui y sont publiés constituent des œuvres de l'esprit protégées
    par les dispositions applicables en République du Bénin en matière de propriété intellectuelle, notamment :
@endif
</p>
<ul class="mt-3 space-y-2 text-gray-700 list-none ps-0">
    <li class="flex gap-2"><i class="fas fa-gavel text-blue-400 mt-1 flex-shrink-0"></i><span>{{ $isEn ? 'Law No. 2005-30 of April 5, 2006 on the protection of copyright and related rights in the Republic of Benin' : 'La Loi n° 2005-30 du 5 avril 2006 relative à la protection du droit d\'auteur et des droits voisins en République du Bénin' }}</span></li>
    <li class="flex gap-2"><i class="fas fa-gavel text-blue-400 mt-1 flex-shrink-0"></i><span>{{ $isEn ? 'The Revised Bangui Agreement (OAPI) on intellectual property' : 'L\'Accord de Bangui révisé (OAPI) sur la propriété intellectuelle' }}</span></li>
    <li class="flex gap-2"><i class="fas fa-gavel text-blue-400 mt-1 flex-shrink-0"></i><span>{{ $isEn ? 'The Berne Convention for the Protection of Literary and Artistic Works' : 'La Convention de Berne pour la protection des œuvres littéraires et artistiques' }}</span></li>
</ul>
<div class="mt-4 p-3 rounded-lg text-sm text-red-700" style="background:#fff1f2;border:1px solid #fecdd3;">
    <i class="fas fa-exclamation-triangle me-1"></i>
    @if($isEn)
    Any reproduction, representation, modification, publication, adaptation or transmission of all or part
    of the Platform, by any means whatsoever, is <strong>prohibited without prior written authorization</strong>
    from <strong>[COMPANY NAME]</strong>.
    @else
    Toute reproduction, représentation, modification, publication, adaptation ou transmission de tout ou partie
    de la Plateforme, par quelque moyen que ce soit, est <strong>interdite sans autorisation écrite préalable</strong>
    de <strong>[NOM DE LA SOCIÉTÉ]</strong>.
    @endif
</div>

{{-- LIABILITY --}}
<h2 id="responsabilite" class="legal-section-title">{{ $isEn ? 'Liability' : 'Responsabilité' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    <strong>[COMPANY NAME]</strong> strives to ensure the accuracy and currency of the information available
    on the Platform. However, it cannot guarantee the accuracy, precision or completeness of the information
    made available. It disclaims all liability for damages resulting from service interruption, external
    intrusion or the presence of computer viruses.
@else
    <strong>[NOM DE LA SOCIÉTÉ]</strong> s'efforce d'assurer l'exactitude et la mise à jour des informations
    disponibles sur la Plateforme. Toutefois, elle ne peut garantir l'exactitude, la précision ou l'exhaustivité
    des informations mises à la disposition. Elle décline toute responsabilité pour les dommages résultant d'une
    interruption de service, d'une intrusion extérieure ou de la présence de virus informatiques.
@endif
</p>

{{-- HYPERLINKS --}}
<h2 id="liens" class="legal-section-title">{{ $isEn ? 'Hyperlinks' : 'Liens hypertextes' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    The Platform may contain links to third-party websites. <strong>[COMPANY NAME]</strong> has no control
    over these sites and disclaims all liability for their content.
@else
    La Plateforme peut contenir des liens vers des sites tiers. <strong>[NOM DE LA SOCIÉTÉ]</strong> n'exerce
    aucun contrôle sur ces sites et décline toute responsabilité quant à leur contenu.
@endif
</p>

{{-- GOVERNING LAW --}}
<h2 id="droit" class="legal-section-title">{{ $isEn ? 'Governing Law' : 'Droit applicable' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    This legal notice is governed by the law of the <strong>Republic of Benin</strong>. Any dispute
    relating to its interpretation shall be submitted to the competent courts of <strong>Cotonou</strong>.
@else
    Les présentes mentions légales sont régies par le droit de la <strong>République du Bénin</strong>. Tout litige
    relatif à leur interprétation sera soumis aux juridictions compétentes du ressort de <strong>Cotonou</strong>.
@endif
</p>

{{-- MEDIATION --}}
<h2 id="mediation" class="legal-section-title">{{ $isEn ? 'Mediation and Dispute Resolution' : 'Médiation et règlement des litiges' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    In accordance with Law No. 2017-20, disputes relating to electronic commerce may be subject to
    mediation before any legal action. Users may contact our customer service at
    <strong>[EMAIL]</strong> for any attempt at amicable resolution.
@else
    Conformément à la Loi n° 2017-20, les litiges relatifs au commerce électronique peuvent faire l'objet d'une
    médiation avant tout recours judiciaire. L'Utilisateur peut contacter notre service client à
    <strong>[EMAIL]</strong> pour toute tentative de résolution amiable.
@endif
</p>

<div class="mt-8 p-4 rounded-xl text-sm text-gray-500" style="background:#f1f5f9;">
    <i class="fas fa-envelope me-1"></i>
    {{ $isEn ? 'Contact:' : 'Contact :' }} <strong>[{{ $isEn ? 'CONTACT EMAIL' : 'EMAIL CONTACT' }}]</strong> &nbsp;|&nbsp;
    <i class="fas fa-map-marker-alt ms-2 me-1"></i>
    <strong>[{{ $isEn ? 'COMPANY NAME' : 'NOM DE LA SOCIÉTÉ' }}]</strong>, [{{ $isEn ? 'ADDRESS' : 'ADRESSE' }}], Cotonou, {{ $isEn ? 'Benin' : 'Bénin' }}
</div>

@endsection
