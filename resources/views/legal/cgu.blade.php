@extends('layouts.legal')

@php $isEn = app()->getLocale() === 'en'; @endphp

@section('title', $isEn ? 'Terms of Use' : "Conditions Générales d'Utilisation")
@section('breadcrumb', $isEn ? 'Terms of Use' : "Conditions d'utilisation")
@section('page-title', $isEn ? 'Terms of Use' : "Conditions Générales d'Utilisation")
@section('update-date', $isEn ? 'Version effective February 28, 2026' : 'Version en vigueur au 28 février 2026')

@section('toc')
@if($isEn)
    <li><a href="#art1">Art. 1 — Object</a></li>
    <li><a href="#art2">Art. 2 — Definitions</a></li>
    <li><a href="#art3">Art. 3 — Platform Access</a></li>
    <li><a href="#art4">Art. 4 — Services</a></li>
    <li><a href="#art5">Art. 5 — Financial Terms</a></li>
    <li><a href="#art6">Art. 6 — Obligations</a></li>
    <li><a href="#art7">Art. 7 — Intellectual Property</a></li>
    <li><a href="#art8">Art. 8 — Availability</a></li>
    <li><a href="#art9">Art. 9 — Liability</a></li>
    <li><a href="#art10">Art. 10 — Amendments</a></li>
    <li><a href="#art11">Art. 11 — Governing Law</a></li>
@else
    <li><a href="#art1">Art. 1 — Objet</a></li>
    <li><a href="#art2">Art. 2 — Définitions</a></li>
    <li><a href="#art3">Art. 3 — Accès à la Plateforme</a></li>
    <li><a href="#art4">Art. 4 — Services</a></li>
    <li><a href="#art5">Art. 5 — Conditions financières</a></li>
    <li><a href="#art6">Art. 6 — Obligations</a></li>
    <li><a href="#art7">Art. 7 — Propriété intellectuelle</a></li>
    <li><a href="#art8">Art. 8 — Disponibilité</a></li>
    <li><a href="#art9">Art. 9 — Limitation de responsabilité</a></li>
    <li><a href="#art10">Art. 10 — Modifications</a></li>
    <li><a href="#art11">Art. 11 — Droit applicable</a></li>
@endif
@endsection

@section('content')

@if($isEn)
<div class="mb-6 p-4 rounded-xl" style="background:#eff6ff;border-left:4px solid #3b82f6;">
    <p class="text-sm text-blue-700 mb-0">
        <i class="fas fa-info-circle me-1"></i>
        These Terms of Use are governed by <strong>Law No. 2017-20 of April 20, 2018</strong> on the Digital Code of the Republic of Benin.
        By accessing the Platform, you accept these terms without reservation.
    </p>
</div>
@else
<div class="mb-6 p-4 rounded-xl" style="background:#eff6ff;border-left:4px solid #3b82f6;">
    <p class="text-sm text-blue-700 mb-0">
        <i class="fas fa-info-circle me-1"></i>
        Les présentes Conditions Générales d'Utilisation sont régies par la
        <strong>Loi n° 2017-20 du 20 avril 2018</strong> portant Code du numérique de la République du Bénin.
        En accédant à la Plateforme, vous acceptez ces conditions sans réserve.
    </p>
</div>
@endif

{{-- ART 1 --}}
<h2 id="art1" class="legal-section-title">{{ $isEn ? 'Article 1 — Object and Scope' : 'Article 1 — Objet et champ d\'application' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    These Terms of Use (hereinafter "ToU") govern access to and use of the <strong>Lokativ</strong> platform
    (hereinafter "the Platform"), an online property management service published by
    <strong>[COMPANY NAME]</strong>, a company incorporated under Beninese law, with registered office at
    <strong>[FULL ADDRESS], Cotonou, Republic of Benin</strong>, registered in the Commercial and Personal
    Property Credit Register (RCCM) under number <strong>[RCCM NUMBER]</strong>, with Unique Tax
    Identifier (IFU) <strong>[IFU NUMBER]</strong>.
@else
    Les présentes Conditions Générales d'Utilisation (ci-après « <strong>CGU</strong> ») régissent l'accès et l'utilisation de la plateforme
    <strong>Lokativ</strong> (ci-après « la Plateforme »), service en ligne de gestion immobilière édité par
    <strong>[NOM DE LA SOCIÉTÉ]</strong>, société de droit béninois dont le siège social est établi à
    <strong>[ADRESSE COMPLÈTE], Cotonou, République du Bénin</strong>, immatriculée au Registre du Commerce et du Crédit
    Mobilier (RCCM) sous le numéro <strong>[NUMÉRO RCCM]</strong>, avec l'Identifiant Fiscal Unique (IFU) <strong>[NUMÉRO IFU]</strong>.
@endif
</p>
<p class="text-gray-700 leading-relaxed mt-3">
@if($isEn)
    Any access to and use of the Platform constitutes full and unconditional acceptance of these ToU,
    in accordance with <strong>Articles 64 et seq. of Law No. 2017-20 of April 20, 2018</strong> on
    the Digital Code of the Republic of Benin.
@else
    Toute connexion et utilisation de la Plateforme emporte acceptation pleine et entière des présentes CGU,
    conformément aux dispositions des <strong>articles 64 et suivants de la Loi n° 2017-20 du 20 avril 2018</strong>
    portant Code du numérique en République du Bénin.
@endif
</p>

{{-- ART 2 --}}
<h2 id="art2" class="legal-section-title">{{ $isEn ? 'Article 2 — Definitions' : 'Article 2 — Définitions' }}</h2>
<p class="text-gray-700 leading-relaxed">{{ $isEn ? 'For the purposes of these ToU:' : 'Au sens des présentes CGU :' }}</p>
<ul class="text-gray-700 space-y-2 mt-2 list-none ps-0">
    @foreach($isEn ? [
        ['"Platform"', 'the online Lokativ service accessible at <strong>[URL]</strong>, including all its features.'],
        ['"User"', 'any natural or legal person accessing the Platform.'],
        ['"Subscriber"', 'the real estate agency or direction that has taken out a paid subscription.'],
        ['"Data"', 'all information entered or generated when using the Platform.'],
        ['"Content"', 'any document, report, receipt, contract or file produced via the Platform.'],
    ] : [
        ['« Plateforme »', 'le service en ligne Lokativ accessible à l\'adresse <strong>[URL]</strong>, incluant toutes ses fonctionnalités.'],
        ['« Utilisateur »', 'toute personne physique ou morale accédant à la Plateforme.'],
        ['« Abonné »', 'l\'agence ou direction immobilière ayant souscrit un abonnement payant.'],
        ['« Données »', 'toutes informations saisies ou générées lors de l\'utilisation de la Plateforme.'],
        ['« Contenu »', 'tout document, rapport, quittance, contrat ou fichier produit via la Plateforme.'],
    ] as [$term, $def])
        <li class="flex gap-2"><i class="fas fa-dot-circle text-blue-400 mt-1 flex-shrink-0"></i><span><strong>{{ $term }}</strong> : {!! $def !!}</span></li>
    @endforeach
</ul>

{{-- ART 3 --}}
<h2 id="art3" class="legal-section-title">{{ $isEn ? 'Article 3 — Platform Access' : 'Article 3 — Accès à la Plateforme' }}</h2>
<p class="text-gray-700 font-semibold mt-3 mb-1">{{ $isEn ? '3.1 Access conditions' : '3.1 Conditions d\'accès' }}</p>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    Access to the Platform is restricted to persons of legal age (18 years or older) and legally constituted
    entities under Beninese law or that of any ECOWAS member state. Registration requires providing
    accurate, complete and up-to-date information.
@else
    L'accès à la Plateforme est réservé aux personnes majeures (18 ans révolus) et aux entités légalement constituées
    selon le droit béninois ou celui de tout État membre de la CEDEAO. Toute inscription implique la fourniture
    d'informations exactes, complètes et à jour.
@endif
</p>
<p class="text-gray-700 font-semibold mt-3 mb-1">{{ $isEn ? '3.2 User account' : '3.2 Compte utilisateur' }}</p>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    The User is responsible for the confidentiality of their credentials. They agree to immediately
    notify the publisher of any unauthorized use of their account. Lokativ cannot be held liable
    for damages resulting from unauthorized access due to User negligence.
@else
    L'Utilisateur est responsable de la confidentialité de ses identifiants. Il s'engage à notifier immédiatement
    l'éditeur de toute utilisation non autorisée de son compte. Lokativ ne saurait être tenu responsable
    des dommages résultant d'un accès non autorisé lié à une négligence de l'Utilisateur.
@endif
</p>
<p class="text-gray-700 font-semibold mt-3 mb-1">{{ $isEn ? '3.3 Suspension and termination' : '3.3 Suspension et résiliation' }}</p>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    Lokativ reserves the right to suspend or terminate any account in the event of a breach of these ToU,
    non-payment or fraudulent behavior, without prejudice to any legal action.
@else
    Lokativ se réserve le droit de suspendre ou résilier tout compte en cas de violation des présentes CGU,
    de défaut de paiement ou de comportement frauduleux, sans préjudice de tout recours judiciaire.
@endif
</p>

{{-- ART 4 --}}
<h2 id="art4" class="legal-section-title">{{ $isEn ? 'Article 4 — Service Description' : 'Article 4 — Description des services' }}</h2>
<p class="text-gray-700 leading-relaxed">{{ $isEn ? 'The Platform notably offers the following features:' : 'La Plateforme offre notamment les fonctionnalités suivantes :' }}</p>
<ul class="mt-2 space-y-1 text-gray-700 list-none ps-0">
    @foreach($isEn ? [
        'Management of owners, tenants, properties and leases',
        'Generation of receipts, contracts, account statements and PDF documents',
        'Payment tracking, outstanding amounts and financial statistics',
        'Document delivery by email and/or WhatsApp',
        'Multi-agency and multi-user management with role and permission control',
        'Statistical dashboard and reporting',
    ] : [
        'Gestion des propriétaires, locataires, biens immobiliers et baux',
        'Édition de quittances, contrats, relevés de compte et documents PDF',
        'Suivi des paiements, des impayés et des statistiques financières',
        'Envoi de documents par email et/ou WhatsApp',
        'Gestion multi-agences et multi-utilisateurs avec contrôle des rôles et permissions',
        'Tableau de bord statistique et reporting',
    ] as $item)
        <li class="flex gap-2"><i class="fas fa-check-circle text-green-500 mt-1 flex-shrink-0"></i><span>{{ $item }}</span></li>
    @endforeach
</ul>
<p class="text-gray-700 leading-relaxed mt-3">
    {{ $isEn
        ? 'Services are provided as-is, in SaaS (Software as a Service) mode, with no installation required on the user side.'
        : 'Les services sont fournis en l\'état, en mode SaaS (Software as a Service), sans installation requise côté utilisateur.'
    }}
</p>

{{-- ART 5 --}}
<h2 id="art5" class="legal-section-title">{{ $isEn ? 'Article 5 — Financial Terms' : 'Article 5 — Conditions financières' }}</h2>
<p class="text-gray-700 font-semibold mt-3 mb-1">{{ $isEn ? '5.1 Pricing' : '5.1 Tarification' }}</p>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    Access to full features requires a subscription under the current pricing plans displayed on the
    Platform. Prices are expressed in the applicable currency, inclusive of all applicable taxes.
@else
    L'accès aux fonctionnalités complètes est conditionné à la souscription d'un abonnement selon les plans
    tarifaires en vigueur, affichés sur la Plateforme. Les prix sont exprimés en <strong>Francs CFA (XOF)</strong>,
    toutes taxes applicables comprises.
@endif
</p>
<p class="text-gray-700 font-semibold mt-3 mb-1">{{ $isEn ? '5.2 Payment' : '5.2 Paiement' }}</p>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    Payment is made via the methods available on the Platform (mobile money, bank transfer or any other
    accepted method). Subscriptions are due at the due date and non-payment will result in service
    suspension after formal notice remains without effect for <strong>7 days</strong>.
@else
    Le paiement s'effectue par les moyens proposés sur la Plateforme (mobile money, virement bancaire ou tout
    autre moyen accepté). Tout abonnement est dû à échéance et le défaut de paiement entraîne la suspension
    du service après mise en demeure restée sans effet pendant <strong>7 jours</strong>.
@endif
</p>
<p class="text-gray-700 font-semibold mt-3 mb-1">{{ $isEn ? '5.3 Refunds' : '5.3 Remboursement' }}</p>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    In accordance with Beninese e-commerce legislation (Articles 109 et seq. of Law No. 2017-20),
    no refund is granted for subscription periods already consumed. A credit may be granted on a
    case-by-case basis.
@else
    En application de la législation béninoise sur le commerce électronique (articles 109 et suivants de la
    Loi n° 2017-20), aucun remboursement n'est accordé pour les périodes d'abonnement déjà consommées.
    Un crédit pourra être accordé au cas par cas.
@endif
</p>

{{-- ART 6 --}}
<h2 id="art6" class="legal-section-title">{{ $isEn ? 'Article 6 — User Obligations' : 'Article 6 — Obligations de l\'Utilisateur' }}</h2>
<p class="text-gray-700 leading-relaxed">{{ $isEn ? 'The User agrees to:' : 'L\'Utilisateur s\'engage à :' }}</p>
<ul class="mt-2 space-y-2 text-gray-700 list-none ps-0">
    @foreach($isEn ? [
        'Use the Platform only for lawful purposes and in compliance with applicable Beninese laws',
        'Not attempt to circumvent, hack or alter the Platform\'s computer systems',
        'Not enter fictitious, erroneous or third-party data without their consent',
        'Respect third-party rights, particularly regarding personal data protection of tenants and owners',
        'Maintain the confidentiality of generated documents and not disclose them without authorization',
    ] : [
        'Utiliser la Plateforme uniquement à des fins licites et conformément aux lois béninoises en vigueur',
        'Ne pas tenter de contourner, pirater ou altérer les systèmes informatiques de la Plateforme',
        'Ne pas saisir de données fictives, erronées ou appartenant à des tiers sans leur consentement',
        'Respecter les droits des tiers, notamment en matière de protection des données personnelles des locataires et propriétaires',
        'Assurer la confidentialité des documents générés et ne pas les divulguer sans autorisation',
    ] as $item)
        <li class="flex gap-2"><i class="fas fa-exclamation-circle text-orange-400 mt-1 flex-shrink-0"></i><span>{{ $item }}</span></li>
    @endforeach
</ul>
<p class="text-gray-700 leading-relaxed mt-3">
@if($isEn)
    Any violation may engage the User's criminal liability, in accordance with
    <strong>Articles 370 et seq. of Law No. 2017-20</strong> on cybercrime.
@else
    Toute violation est susceptible d'engager la responsabilité pénale de l'Utilisateur, conformément aux
    <strong>articles 370 et suivants de la Loi n° 2017-20</strong> relatifs à la cybercriminalité.
@endif
</p>

{{-- ART 7 --}}
<h2 id="art7" class="legal-section-title">{{ $isEn ? 'Article 7 — Intellectual Property' : 'Article 7 — Propriété intellectuelle' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    All elements constituting the Platform (software, database, graphical interface, logos, trademarks,
    algorithms) are protected by intellectual property law applicable in the Republic of Benin and by
    ratified international conventions. Any unauthorized reproduction, representation, modification or
    exploitation is strictly prohibited and constitutes infringement subject to sanctions.
@else
    L'ensemble des éléments constituant la Plateforme (logiciel, base de données, interface graphique, logos,
    marques, algorithmes) est protégé par le droit de la propriété intellectuelle applicable en République du
    Bénin et par les conventions internationales ratifiées. Toute reproduction, représentation, modification
    ou exploitation non autorisée est strictement interdite et constitue une contrefaçon passible de sanctions.
@endif
</p>
<p class="text-gray-700 leading-relaxed mt-3">
@if($isEn)
    <strong>Data entered by the Subscriber</strong> remains their exclusive property.
    Lokativ claims no ownership rights over such data.
@else
    Les <strong>données saisies par l'Abonné</strong> restent sa propriété exclusive.
    Lokativ ne revendique aucun droit de propriété sur ces données.
@endif
</p>

{{-- ART 8 --}}
<h2 id="art8" class="legal-section-title">{{ $isEn ? 'Article 8 — Availability and Maintenance' : 'Article 8 — Disponibilité et maintenance' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    Lokativ undertakes to ensure continuous access to the Platform with an availability target of
    <strong>99% per month</strong>. Planned maintenance interruptions may occur and will be notified
    in advance. Lokativ cannot be held liable for interruptions due to force majeure, network failures
    or incidents at third-party service providers.
@else
    Lokativ s'engage à assurer un accès continu à la Plateforme avec un objectif de disponibilité de
    <strong>99 % par mois</strong>. Des interruptions planifiées de maintenance peuvent survenir et seront
    notifiées à l'avance. Lokativ ne saurait être tenu responsable des interruptions dues à des cas de
    force majeure, pannes de réseau ou incidents chez des prestataires tiers.
@endif
</p>

{{-- ART 9 --}}
<h2 id="art9" class="legal-section-title">{{ $isEn ? 'Article 9 — Limitation of Liability' : 'Article 9 — Limitation de responsabilité' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    To the extent permitted by Beninese law, Lokativ's liability is limited to the amount of subscriptions
    actually paid by the User over the <strong>3 months preceding</strong> the event giving rise to the
    damage. Lokativ cannot be held liable for indirect damages, loss of business or consequential losses.
@else
    Dans les limites autorisées par le droit béninois, la responsabilité de Lokativ est limitée au montant
    des abonnements effectivement payés par l'Utilisateur au cours des <strong>3 derniers mois</strong>
    précédant le fait générateur du dommage. Lokativ ne peut être tenu responsable des dommages indirects,
    pertes d'exploitation ou préjudices consécutifs.
@endif
</p>

{{-- ART 10 --}}
<h2 id="art10" class="legal-section-title">{{ $isEn ? 'Article 10 — Amendments to the ToU' : 'Article 10 — Modifications des CGU' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    Lokativ reserves the right to modify these ToU at any time. Changes take effect
    <strong>15 days</strong> after notification to Users by email. Continued use of the Platform
    after this period constitutes acceptance of the new ToU.
@else
    Lokativ se réserve le droit de modifier les présentes CGU à tout moment. Les modifications entrent en
    vigueur <strong>15 jours</strong> après notification aux Utilisateurs par email. La poursuite de l'utilisation
    de la Plateforme après ce délai vaut acceptation des nouvelles CGU.
@endif
</p>

{{-- ART 11 --}}
<h2 id="art11" class="legal-section-title">{{ $isEn ? 'Article 11 — Governing Law and Jurisdiction' : 'Article 11 — Droit applicable et juridiction compétente' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    These ToU are governed by the law of the <strong>Republic of Benin</strong>. Any dispute relating
    to their interpretation or performance shall be submitted to the competent courts of
    <strong>Cotonou</strong>, notwithstanding plurality of defendants or warranty claims. The parties
    shall favor amicable resolution before any legal action.
@else
    Les présentes CGU sont régies par le droit de la <strong>République du Bénin</strong>. Tout litige relatif
    à leur interprétation ou exécution sera soumis aux juridictions compétentes du ressort de <strong>Cotonou</strong>,
    nonobstant pluralité de défendeurs ou appel en garantie. Les parties privilégieront la résolution amiable
    avant tout recours judiciaire.
@endif
</p>

<div class="mt-8 p-4 rounded-xl text-sm text-gray-500" style="background:#f1f5f9;">
    <i class="fas fa-envelope me-1"></i>
    {{ $isEn ? 'For any questions regarding these ToU:' : 'Pour toute question relative aux présentes CGU :' }} <strong>[EMAIL CONTACT]</strong>
</div>

@endsection
