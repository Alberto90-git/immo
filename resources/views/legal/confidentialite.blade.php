@extends('layouts.legal')

@php $isEn = app()->getLocale() === 'en'; @endphp

@section('title', $isEn ? 'Privacy Policy' : 'Politique de confidentialité')
@section('breadcrumb', $isEn ? 'Privacy Policy' : 'Politique de confidentialité')
@section('page-title', $isEn ? 'Privacy Policy' : 'Politique de confidentialité')
@section('update-date', $isEn ? 'Updated February 28, 2026' : 'Mise à jour le 28 février 2026')

@section('toc')
@if($isEn)
    <li><a href="#art1">Art. 1 — Data Controller</a></li>
    <li><a href="#art2">Art. 2 — Data Collected</a></li>
    <li><a href="#art3">Art. 3 — Purposes & Legal Basis</a></li>
    <li><a href="#art4">Art. 4 — Retention Periods</a></li>
    <li><a href="#art5">Art. 5 — Recipients</a></li>
    <li><a href="#art6">Art. 6 — International Transfers</a></li>
    <li><a href="#art7">Art. 7 — Your Rights</a></li>
    <li><a href="#art8">Art. 8 — Security</a></li>
    <li><a href="#art9">Art. 9 — Minors</a></li>
@else
    <li><a href="#art1">Art. 1 — Responsable du traitement</a></li>
    <li><a href="#art2">Art. 2 — Données collectées</a></li>
    <li><a href="#art3">Art. 3 — Finalités et base légale</a></li>
    <li><a href="#art4">Art. 4 — Durée de conservation</a></li>
    <li><a href="#art5">Art. 5 — Destinataires</a></li>
    <li><a href="#art6">Art. 6 — Transferts internationaux</a></li>
    <li><a href="#art7">Art. 7 — Vos droits</a></li>
    <li><a href="#art8">Art. 8 — Sécurité</a></li>
    <li><a href="#art9">Art. 9 — Mineurs</a></li>
@endif
@endsection

@section('content')

@if($isEn)
<div class="mb-6 p-4 rounded-xl" style="background:#f0fdf4;border-left:4px solid #10b981;">
    <p class="text-sm text-green-700 mb-0">
        <i class="fas fa-shield-alt me-1"></i>
        This policy is established in accordance with <strong>Law No. 2017-20 of April 20, 2018</strong> on the
        Digital Code of the Republic of Benin and the <strong>ECOWAS Supplementary Act A/SA.1/01/10 of February 16, 2010</strong>
        on personal data protection.
    </p>
</div>
@else
<div class="mb-6 p-4 rounded-xl" style="background:#f0fdf4;border-left:4px solid #10b981;">
    <p class="text-sm text-green-700 mb-0">
        <i class="fas fa-shield-alt me-1"></i>
        Cette politique est établie conformément à la <strong>Loi n° 2017-20 du 20 avril 2018</strong> portant Code
        du numérique de la République du Bénin et à l'<strong>Acte additionnel CEDEAO A/SA.1/01/10 du 16 février 2010</strong>
        sur la protection des données personnelles.
    </p>
</div>
@endif

{{-- ART 1 --}}
<h2 id="art1" class="legal-section-title">{{ $isEn ? 'Article 1 — Data Controller' : 'Article 1 — Responsable du traitement' }}</h2>
<p class="text-gray-700 leading-relaxed">{{ $isEn ? 'The data controller for personal data collected via the Lokativ Platform is:' : 'Le responsable du traitement des données à caractère personnel collectées via la Plateforme Lokativ est :' }}</p>
<div class="mt-3 p-4 rounded-xl text-gray-700" style="background:#f8fafc;border:1px solid #e2e8f0;">
    <p class="mb-1"><strong>[{{ $isEn ? 'COMPANY NAME' : 'NOM DE LA SOCIÉTÉ' }}]</strong></p>
    <p class="mb-1"><i class="fas fa-map-marker-alt text-blue-400 me-2"></i>[{{ $isEn ? 'FULL ADDRESS' : 'ADRESSE COMPLÈTE' }}], Cotonou, {{ $isEn ? 'Republic of Benin' : 'République du Bénin' }}</p>
    <p class="mb-1"><i class="fas fa-envelope text-blue-400 me-2"></i>[{{ $isEn ? 'CONTACT EMAIL' : 'EMAIL CONTACT' }}]</p>
    <p class="mb-1"><i class="fas fa-phone text-blue-400 me-2"></i>[{{ $isEn ? 'NUMBER' : 'NUMÉRO' }}]</p>
    <p class="mb-0 mt-2 text-sm text-gray-500">
        <i class="fas fa-certificate text-blue-300 me-1"></i>
        {{ $isEn
            ? 'Processing declared with the APDP (Personal Data Protection Authority of Benin) — Number:'
            : 'Traitement déclaré auprès de l\'APDP (Autorité de Protection des Données à caractère Personnel du Bénin) — Numéro :'
        }} <strong>[{{ $isEn ? 'APDP NUMBER' : 'NUMÉRO APDP' }}]</strong>
    </p>
</div>

{{-- ART 2 --}}
<h2 id="art2" class="legal-section-title">{{ $isEn ? 'Article 2 — Data Collected' : 'Article 2 — Données collectées' }}</h2>

<p class="text-gray-700 font-semibold mt-3 mb-2">{{ $isEn ? '2.1 User data (agencies / collaborators)' : '2.1 Données des Utilisateurs (agences / collaborateurs)' }}</p>
<ul class="space-y-1 text-gray-700 list-none ps-0">
    @foreach($isEn ? [
        'Identity: last name, first name, role',
        'Contact details: professional email address, phone number',
        'Login data: credentials, access logs, IP address',
        'Subscription data: subscribed plan, payment history',
    ] : [
        'Identité : nom, prénom, fonction',
        'Coordonnées : adresse email professionnelle, numéro de téléphone',
        'Données de connexion : identifiants, logs d\'accès, adresse IP',
        'Données d\'abonnement : plan souscrit, historique de paiement',
    ] as $item)
        <li class="flex gap-2"><i class="fas fa-angle-right text-blue-400 mt-1 flex-shrink-0"></i><span>{{ $item }}</span></li>
    @endforeach
</ul>

<p class="text-gray-700 font-semibold mt-4 mb-2">{{ $isEn ? '2.2 Third-party data managed (tenants, owners)' : '2.2 Données des tiers gérés (locataires, propriétaires)' }}</p>
<p class="text-sm text-gray-500 mb-2">{{ $isEn ? 'Entered by Subscribers in the context of their property management activity:' : 'Saisies par les Abonnés dans le cadre de leur activité de gestion immobilière :' }}</p>
<ul class="space-y-1 text-gray-700 list-none ps-0">
    @foreach($isEn ? [
        'Full civil identity',
        'Contact details (address, phone, email)',
        'Financial data: rent amounts, payment history, balances',
        'Contractual documents: leases, receipts, contracts',
    ] : [
        'Identité civile complète',
        'Coordonnées (adresse, téléphone, email)',
        'Données financières : montants de loyer, historique de paiement, soldes',
        'Documents contractuels : baux, quittances, contrats',
    ] as $item)
        <li class="flex gap-2"><i class="fas fa-angle-right text-blue-400 mt-1 flex-shrink-0"></i><span>{{ $item }}</span></li>
    @endforeach
</ul>

<p class="text-gray-700 font-semibold mt-4 mb-2">{{ $isEn ? '2.3 Technical data' : '2.3 Données techniques' }}</p>
<ul class="space-y-1 text-gray-700 list-none ps-0">
    @foreach($isEn ? [
        'Connection logs (server logs)',
        'Navigation and Platform usage data',
        'Data from cookies (see Cookie Policy)',
    ] : [
        'Journaux de connexion (logs serveur)',
        'Données de navigation et d\'utilisation de la Plateforme',
        'Données issues des cookies (voir Politique de Cookies)',
    ] as $item)
        <li class="flex gap-2"><i class="fas fa-angle-right text-blue-400 mt-1 flex-shrink-0"></i><span>{{ $item }}</span></li>
    @endforeach
</ul>

{{-- ART 3 --}}
<h2 id="art3" class="legal-section-title">{{ $isEn ? 'Article 3 — Purposes and Legal Basis' : 'Article 3 — Finalités et base légale du traitement' }}</h2>
<div class="overflow-x-auto mt-3">
    <table class="table table-bordered table-legal text-sm w-100">
        <thead>
            <tr>
                <th>{{ $isEn ? 'Purpose' : 'Finalité' }}</th>
                <th>{{ $isEn ? 'Legal basis' : 'Base légale' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($isEn ? [
                ['Provision of contractual services', 'Contract performance (Art. 5 ECOWAS Act)'],
                ['Billing and subscription management', 'Legal obligation / Contract performance'],
                ['Sending documents to tenants/owners', 'Legitimate interest / Consent'],
                ['Security and fraud prevention', 'Legitimate interest'],
                ['Platform improvement', 'Legitimate interest'],
                ['Commercial communication', 'Explicit consent'],
                ['Compliance with legal obligations', 'Legal obligation'],
            ] : [
                ['Fourniture des services contractuels', 'Exécution du contrat (art. 5 Acte CEDEAO)'],
                ['Facturation et gestion des abonnements', 'Obligation légale / Exécution du contrat'],
                ['Envoi de documents aux locataires/propriétaires', 'Intérêt légitime / Consentement'],
                ['Sécurité et lutte contre la fraude', 'Intérêt légitime'],
                ['Amélioration de la Plateforme', 'Intérêt légitime'],
                ['Communication commerciale', 'Consentement explicite'],
                ['Respect des obligations légales', 'Obligation légale'],
            ] as [$fin, $base])
                <tr>
                    <td class="text-gray-700">{{ $fin }}</td>
                    <td class="text-gray-600">{{ $base }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ART 4 --}}
<h2 id="art4" class="legal-section-title">{{ $isEn ? 'Article 4 — Retention Periods' : 'Article 4 — Durée de conservation' }}</h2>
<div class="overflow-x-auto mt-3">
    <table class="table table-bordered table-legal text-sm w-100">
        <thead>
            <tr>
                <th>{{ $isEn ? 'Category' : 'Catégorie' }}</th>
                <th>{{ $isEn ? 'Duration' : 'Durée' }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($isEn ? [
                ['Active account data', 'Duration of the contractual relationship'],
                ['Data after termination', '5 years (common civil limitation period under Beninese law)'],
                ['Connection logs', '12 months'],
                ['Payment data', '10 years (accounting obligations — Art. 18 OHADA AUDCG)'],
                ['Contractual documents (leases, receipts)', '10 years'],
            ] : [
                ['Données de compte actif', 'Durée de la relation contractuelle'],
                ['Données après résiliation', '5 ans (prescription civile de droit commun béninois)'],
                ['Logs de connexion', '12 mois'],
                ['Données de paiement', '10 ans (obligations comptables — art. 18 AUDCG OHADA)'],
                ['Documents contractuels (baux, quittances)', '10 ans'],
            ] as [$cat, $dur])
                <tr>
                    <td class="text-gray-700">{{ $cat }}</td>
                    <td class="text-gray-600 font-medium">{{ $dur }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- ART 5 --}}
<h2 id="art5" class="legal-section-title">{{ $isEn ? 'Article 5 — Data Recipients' : 'Article 5 — Destinataires des données' }}</h2>
<ul class="mt-2 space-y-2 text-gray-700 list-none ps-0">
    <li class="flex gap-2"><i class="fas fa-users text-blue-400 mt-1 flex-shrink-0"></i><span><strong>{{ $isEn ? 'Authorized Lokativ staff' : 'Personnel habilité de Lokativ' }}</strong> : {{ $isEn ? 'strictly within their assigned responsibilities' : 'dans la stricte limite de leurs attributions' }}</span></li>
    <li class="flex gap-2"><i class="fas fa-server text-blue-400 mt-1 flex-shrink-0"></i><span><strong>{{ $isEn ? 'Technical service providers' : 'Prestataires techniques' }}</strong> : {{ $isEn ? 'hosting provider, email sending provider (under subcontracting agreements ensuring an equivalent level of protection)' : 'hébergeur, fournisseur d\'envoi email (dans le cadre de contrats de sous-traitance garantissant un niveau de protection équivalent)' }}</span></li>
    <li class="flex gap-2"><i class="fas fa-landmark text-blue-400 mt-1 flex-shrink-0"></i><span><strong>{{ $isEn ? 'Competent authorities' : 'Autorités compétentes' }}</strong> : {{ $isEn ? 'upon judicial requisition or legal obligation (Police, Prosecution, Tax authorities)' : 'sur réquisition judiciaire ou obligation légale (Police, Parquet, Administration fiscale)' }}</span></li>
</ul>
<div class="mt-4 p-3 rounded-lg text-sm text-green-700" style="background:#f0fdf4;border:1px solid #bbf7d0;">
    <i class="fas fa-lock me-1"></i>
    {{ $isEn
        ? 'Lokativ never sells, rents or transfers personal data to third parties for commercial purposes.'
        : 'Lokativ ne vend, ne loue et ne cède jamais les données personnelles à des tiers à des fins commerciales.'
    }}
</div>

{{-- ART 6 --}}
<h2 id="art6" class="legal-section-title">{{ $isEn ? 'Article 6 — International Transfers' : 'Article 6 — Transferts internationaux' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    In the event of a data transfer outside Beninese territory, Lokativ ensures that such transfer
    is made to a country offering an adequate level of protection or on the basis of appropriate
    safeguards (standard contractual clauses, subcontracting agreement), in accordance with
    <strong>Articles 33 et seq. of ECOWAS Supplementary Act A/SA.1/01/10</strong>.
@else
    En cas de transfert de données hors du territoire béninois, Lokativ s'assure que ce transfert s'effectue vers
    un pays offrant un niveau de protection adéquat ou sur la base de garanties appropriées (clauses contractuelles
    types, accord de sous-traitance), conformément aux <strong>articles 33 et suivants de l'Acte additionnel
    CEDEAO A/SA.1/01/10</strong>.
@endif
</p>

{{-- ART 7 --}}
<h2 id="art7" class="legal-section-title">{{ $isEn ? 'Article 7 — Your Rights' : 'Article 7 — Vos droits' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    In accordance with Law No. 2017-20 of April 20, 2018 and the ECOWAS Supplementary Act, any person
    whose data is processed has the following rights:
@else
    Conformément à la Loi n° 2017-20 du 20 avril 2018 et à l'Acte additionnel CEDEAO, toute personne dont
    les données sont traitées dispose des droits suivants :
@endif
</p>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
    @foreach($isEn ? [
        ['fa-eye', 'Right of access', 'Obtain confirmation of processing and a copy of your data'],
        ['fa-edit', 'Right of rectification', 'Have inaccurate or incomplete data corrected'],
        ['fa-ban', 'Right to object', 'Object to processing for legitimate reasons'],
        ['fa-trash-alt', 'Right to erasure', 'Request deletion in cases provided by law'],
        ['fa-pause-circle', 'Right to restriction', 'Obtain restriction of processing in certain cases'],
        ['fa-download', 'Right to portability', 'Receive your data in a structured, readable format'],
    ] : [
        ['fa-eye', 'Droit d\'accès', 'Obtenir confirmation du traitement et copie de ses données'],
        ['fa-edit', 'Droit de rectification', 'Faire corriger des données inexactes ou incomplètes'],
        ['fa-ban', 'Droit d\'opposition', 'S\'opposer au traitement pour des raisons légitimes'],
        ['fa-trash-alt', 'Droit à l\'effacement', 'Demander la suppression dans les cas prévus par la loi'],
        ['fa-pause-circle', 'Droit à la limitation', 'Obtenir la restriction du traitement dans certains cas'],
        ['fa-download', 'Droit à la portabilité', 'Recevoir ses données dans un format structuré et lisible'],
    ] as [$icon, $titre, $desc])
        <div class="p-3 rounded-xl" style="background:#f8fafc;border:1px solid #e2e8f0;">
            <div class="flex items-center gap-2 mb-1">
                <i class="fas {{ $icon }} text-blue-500"></i>
                <span class="font-semibold text-gray-800 text-sm">{{ $titre }}</span>
            </div>
            <p class="text-gray-600 text-xs mb-0">{{ $desc }}</p>
        </div>
    @endforeach
</div>
<div class="mt-4 p-4 rounded-xl text-sm text-gray-700" style="background:#eff6ff;border-left:4px solid #3b82f6;">
    <p class="font-semibold mb-1"><i class="fas fa-paper-plane me-1 text-blue-500"></i>{{ $isEn ? 'Exercise your rights' : 'Exercer vos droits' }}</p>
    <p class="mb-1">{{ $isEn ? 'Send your request to:' : 'Adressez votre demande à :' }} <strong>[{{ $isEn ? 'DPO EMAIL' : 'EMAIL DPO' }}]</strong></p>
    <p class="mb-1">{{ $isEn ? 'Or by mail:' : 'Ou par courrier :' }} <strong>[{{ $isEn ? 'COMPANY NAME' : 'NOM DE LA SOCIÉTÉ' }}] — {{ $isEn ? 'Data Protection' : 'Protection des données' }}, [{{ $isEn ? 'ADDRESS' : 'ADRESSE' }}]</strong></p>
    <p class="mb-1">{{ $isEn ? 'Response time: 30 days from receipt (proof of identity may be requested).' : 'Délai de réponse : 30 jours à compter de la réception (une pièce d\'identité peut être demandée).' }}</p>
    <p class="mb-0 mt-2 text-gray-500">
        {{ $isEn
            ? 'If unsatisfied with the response, contact the APDP — Personal Data Protection Authority of Benin:'
            : 'En cas de réponse insatisfaisante, saisissez l\'APDP — Autorité de Protection des Données à caractère Personnel du Bénin :'
        }} <strong>www.apdp.bj</strong>
    </p>
</div>

{{-- ART 8 --}}
<h2 id="art8" class="legal-section-title">{{ $isEn ? 'Article 8 — Data Security' : 'Article 8 — Sécurité des données' }}</h2>
<p class="text-gray-700 leading-relaxed">{{ $isEn ? 'Lokativ implements appropriate technical and organizational measures to ensure data security:' : 'Lokativ met en œuvre les mesures techniques et organisationnelles appropriées pour garantir la sécurité des données :' }}</p>
<ul class="mt-2 space-y-2 text-gray-700 list-none ps-0">
    @foreach($isEn ? [
        ['fa-lock', 'Data encryption in transit (HTTPS/TLS protocol)'],
        ['fa-user-shield', 'Access control by granular roles and permissions'],
        ['fa-clipboard-list', 'Activity logging (audit trail)'],
        ['fa-database', 'Regular encrypted backups'],
        ['fa-eye-slash', 'Data access restricted to duly authorized personnel'],
    ] : [
        ['fa-lock', 'Chiffrement des données en transit (protocole HTTPS/TLS)'],
        ['fa-user-shield', 'Contrôle d\'accès par rôles et permissions granulaires'],
        ['fa-clipboard-list', 'Journalisation des activités (audit trail)'],
        ['fa-database', 'Sauvegardes régulières et chiffrées'],
        ['fa-eye-slash', 'Accès aux données restreint au personnel dûment habilité'],
    ] as [$icon, $text])
        <li class="flex gap-2"><i class="fas {{ $icon }} text-green-500 mt-1 flex-shrink-0"></i><span>{{ $text }}</span></li>
    @endforeach
</ul>
<p class="text-gray-700 leading-relaxed mt-3">
@if($isEn)
    In the event of a personal data breach likely to create a risk to the rights and freedoms of
    individuals, Lokativ will notify the <strong>APDP</strong> as soon as possible.
@else
    En cas de violation de données à caractère personnel susceptible d'engendrer un risque pour les droits
    et libertés des personnes, Lokativ notifiera l'<strong>APDP</strong> dans les meilleurs délais.
@endif
</p>

{{-- ART 9 --}}
<h2 id="art9" class="legal-section-title">{{ $isEn ? 'Article 9 — Minors\' Data' : 'Article 9 — Données des mineurs' }}</h2>
<p class="text-gray-700 leading-relaxed">
@if($isEn)
    The Platform is not intended for persons under 18 years of age. Lokativ does not knowingly
    collect data relating to minors.
@else
    La Plateforme n'est pas destinée aux mineurs de moins de 18 ans. Lokativ ne collecte pas sciemment de
    données relatives à des mineurs.
@endif
</p>

<div class="mt-8 p-4 rounded-xl text-sm text-gray-500" style="background:#f1f5f9;">
    <i class="fas fa-envelope me-1"></i>
    {{ $isEn ? 'For any questions regarding personal data protection:' : 'Pour toute question relative à la protection de vos données :' }} <strong>[{{ $isEn ? 'DPO EMAIL' : 'EMAIL DPO' }}]</strong>
</div>

@endsection
