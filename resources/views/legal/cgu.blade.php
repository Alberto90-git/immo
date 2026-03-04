@extends('layouts.legal')

@section('title', "Conditions Générales d'Utilisation")
@section('breadcrumb', "Conditions d'utilisation")
@section('page-title', "Conditions Générales d'Utilisation")
@section('update-date', 'Version en vigueur au 28 février 2026')

@section('toc')
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
@endsection

@section('content')

<div class="mb-6 p-4 rounded-xl" style="background:#eff6ff;border-left:4px solid #3b82f6;">
    <p class="text-sm text-blue-700 mb-0">
        <i class="fas fa-info-circle me-1"></i>
        Les présentes Conditions Générales d'Utilisation sont régies par la
        <strong>Loi n° 2017-20 du 20 avril 2018</strong> portant Code du numérique de la République du Bénin.
        En accédant à la Plateforme, vous acceptez ces conditions sans réserve.
    </p>
</div>

{{-- ART 1 --}}
<h2 id="art1" class="legal-section-title">Article 1 — Objet et champ d'application</h2>
<p class="text-gray-700 leading-relaxed">
    Les présentes Conditions Générales d'Utilisation (ci-après « <strong>CGU</strong> ») régissent l'accès et l'utilisation de la plateforme
    <strong>Lokativ</strong> (ci-après « la Plateforme »), service en ligne de gestion immobilière édité par
    <strong>[NOM DE LA SOCIÉTÉ]</strong>, société de droit béninois dont le siège social est établi à
    <strong>[ADRESSE COMPLÈTE], Cotonou, République du Bénin</strong>, immatriculée au Registre du Commerce et du Crédit
    Mobilier (RCCM) sous le numéro <strong>[NUMÉRO RCCM]</strong>, avec l'Identifiant Fiscal Unique (IFU) <strong>[NUMÉRO IFU]</strong>.
</p>
<p class="text-gray-700 leading-relaxed mt-3">
    Toute connexion et utilisation de la Plateforme emporte acceptation pleine et entière des présentes CGU,
    conformément aux dispositions des <strong>articles 64 et suivants de la Loi n° 2017-20 du 20 avril 2018</strong>
    portant Code du numérique en République du Bénin.
</p>

{{-- ART 2 --}}
<h2 id="art2" class="legal-section-title">Article 2 — Définitions</h2>
<p class="text-gray-700 leading-relaxed">Au sens des présentes CGU :</p>
<ul class="text-gray-700 space-y-2 mt-2 list-none ps-0">
    <li class="flex gap-2"><i class="fas fa-dot-circle text-blue-400 mt-1 flex-shrink-0"></i><span><strong>« Plateforme »</strong> : le service en ligne Lokativ accessible à l'adresse <strong>[URL]</strong>, incluant toutes ses fonctionnalités.</span></li>
    <li class="flex gap-2"><i class="fas fa-dot-circle text-blue-400 mt-1 flex-shrink-0"></i><span><strong>« Utilisateur »</strong> : toute personne physique ou morale accédant à la Plateforme.</span></li>
    <li class="flex gap-2"><i class="fas fa-dot-circle text-blue-400 mt-1 flex-shrink-0"></i><span><strong>« Abonné »</strong> : l'agence ou direction immobilière ayant souscrit un abonnement payant.</span></li>
    <li class="flex gap-2"><i class="fas fa-dot-circle text-blue-400 mt-1 flex-shrink-0"></i><span><strong>« Données »</strong> : toutes informations saisies ou générées lors de l'utilisation de la Plateforme.</span></li>
    <li class="flex gap-2"><i class="fas fa-dot-circle text-blue-400 mt-1 flex-shrink-0"></i><span><strong>« Contenu »</strong> : tout document, rapport, quittance, contrat ou fichier produit via la Plateforme.</span></li>
</ul>

{{-- ART 3 --}}
<h2 id="art3" class="legal-section-title">Article 3 — Accès à la Plateforme</h2>
<p class="text-gray-700 font-semibold mt-3 mb-1">3.1 Conditions d'accès</p>
<p class="text-gray-700 leading-relaxed">
    L'accès à la Plateforme est réservé aux personnes majeures (18 ans révolus) et aux entités légalement constituées
    selon le droit béninois ou celui de tout État membre de la CEDEAO. Toute inscription implique la fourniture
    d'informations exactes, complètes et à jour.
</p>
<p class="text-gray-700 font-semibold mt-3 mb-1">3.2 Compte utilisateur</p>
<p class="text-gray-700 leading-relaxed">
    L'Utilisateur est responsable de la confidentialité de ses identifiants. Il s'engage à notifier immédiatement
    l'éditeur de toute utilisation non autorisée de son compte. Lokativ ne saurait être tenu responsable
    des dommages résultant d'un accès non autorisé lié à une négligence de l'Utilisateur.
</p>
<p class="text-gray-700 font-semibold mt-3 mb-1">3.3 Suspension et résiliation</p>
<p class="text-gray-700 leading-relaxed">
    Lokativ se réserve le droit de suspendre ou résilier tout compte en cas de violation des présentes CGU,
    de défaut de paiement ou de comportement frauduleux, sans préjudice de tout recours judiciaire.
</p>

{{-- ART 4 --}}
<h2 id="art4" class="legal-section-title">Article 4 — Description des services</h2>
<p class="text-gray-700 leading-relaxed">La Plateforme offre notamment les fonctionnalités suivantes :</p>
<ul class="mt-2 space-y-1 text-gray-700 list-none ps-0">
    @foreach([
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
    Les services sont fournis en l'état, en mode <strong>SaaS</strong> (Software as a Service),
    sans installation requise côté utilisateur.
</p>

{{-- ART 5 --}}
<h2 id="art5" class="legal-section-title">Article 5 — Conditions financières</h2>
<p class="text-gray-700 font-semibold mt-3 mb-1">5.1 Tarification</p>
<p class="text-gray-700 leading-relaxed">
    L'accès aux fonctionnalités complètes est conditionné à la souscription d'un abonnement selon les plans
    tarifaires en vigueur, affichés sur la Plateforme. Les prix sont exprimés en <strong>Francs CFA (XOF)</strong>,
    toutes taxes applicables comprises.
</p>
<p class="text-gray-700 font-semibold mt-3 mb-1">5.2 Paiement</p>
<p class="text-gray-700 leading-relaxed">
    Le paiement s'effectue par les moyens proposés sur la Plateforme (mobile money, virement bancaire ou tout
    autre moyen accepté). Tout abonnement est dû à échéance et le défaut de paiement entraîne la suspension
    du service après mise en demeure restée sans effet pendant <strong>7 jours</strong>.
</p>
<p class="text-gray-700 font-semibold mt-3 mb-1">5.3 Remboursement</p>
<p class="text-gray-700 leading-relaxed">
    En application de la législation béninoise sur le commerce électronique (articles 109 et suivants de la
    Loi n° 2017-20), aucun remboursement n'est accordé pour les périodes d'abonnement déjà consommées.
    Un crédit pourra être accordé au cas par cas.
</p>

{{-- ART 6 --}}
<h2 id="art6" class="legal-section-title">Article 6 — Obligations de l'Utilisateur</h2>
<p class="text-gray-700 leading-relaxed">L'Utilisateur s'engage à :</p>
<ul class="mt-2 space-y-2 text-gray-700 list-none ps-0">
    @foreach([
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
    Toute violation est susceptible d'engager la responsabilité pénale de l'Utilisateur, conformément aux
    <strong>articles 370 et suivants de la Loi n° 2017-20</strong> relatifs à la cybercriminalité.
</p>

{{-- ART 7 --}}
<h2 id="art7" class="legal-section-title">Article 7 — Propriété intellectuelle</h2>
<p class="text-gray-700 leading-relaxed">
    L'ensemble des éléments constituant la Plateforme (logiciel, base de données, interface graphique, logos,
    marques, algorithmes) est protégé par le droit de la propriété intellectuelle applicable en République du
    Bénin et par les conventions internationales ratifiées. Toute reproduction, représentation, modification
    ou exploitation non autorisée est strictement interdite et constitue une contrefaçon passible de sanctions.
</p>
<p class="text-gray-700 leading-relaxed mt-3">
    Les <strong>données saisies par l'Abonné</strong> restent sa propriété exclusive.
    Lokativ ne revendique aucun droit de propriété sur ces données.
</p>

{{-- ART 8 --}}
<h2 id="art8" class="legal-section-title">Article 8 — Disponibilité et maintenance</h2>
<p class="text-gray-700 leading-relaxed">
    Lokativ s'engage à assurer un accès continu à la Plateforme avec un objectif de disponibilité de
    <strong>99 % par mois</strong>. Des interruptions planifiées de maintenance peuvent survenir et seront
    notifiées à l'avance. Lokativ ne saurait être tenu responsable des interruptions dues à des cas de
    force majeure, pannes de réseau ou incidents chez des prestataires tiers.
</p>

{{-- ART 9 --}}
<h2 id="art9" class="legal-section-title">Article 9 — Limitation de responsabilité</h2>
<p class="text-gray-700 leading-relaxed">
    Dans les limites autorisées par le droit béninois, la responsabilité de Lokativ est limitée au montant
    des abonnements effectivement payés par l'Utilisateur au cours des <strong>3 derniers mois</strong>
    précédant le fait générateur du dommage. Lokativ ne peut être tenu responsable des dommages indirects,
    pertes d'exploitation ou préjudices consécutifs.
</p>

{{-- ART 10 --}}
<h2 id="art10" class="legal-section-title">Article 10 — Modifications des CGU</h2>
<p class="text-gray-700 leading-relaxed">
    Lokativ se réserve le droit de modifier les présentes CGU à tout moment. Les modifications entrent en
    vigueur <strong>15 jours</strong> après notification aux Utilisateurs par email. La poursuite de l'utilisation
    de la Plateforme après ce délai vaut acceptation des nouvelles CGU.
</p>

{{-- ART 11 --}}
<h2 id="art11" class="legal-section-title">Article 11 — Droit applicable et juridiction compétente</h2>
<p class="text-gray-700 leading-relaxed">
    Les présentes CGU sont régies par le droit de la <strong>République du Bénin</strong>. Tout litige relatif
    à leur interprétation ou exécution sera soumis aux juridictions compétentes du ressort de <strong>Cotonou</strong>,
    nonobstant pluralité de défendeurs ou appel en garantie. Les parties privilégieront la résolution amiable
    avant tout recours judiciaire.
</p>

<div class="mt-8 p-4 rounded-xl text-sm text-gray-500" style="background:#f1f5f9;">
    <i class="fas fa-envelope me-1"></i>
    Pour toute question relative aux présentes CGU : <strong>[EMAIL CONTACT]</strong>
</div>

@endsection
