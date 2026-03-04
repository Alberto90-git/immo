@extends('layouts.legal')

@section('title', 'Politique de confidentialité')
@section('breadcrumb', 'Politique de confidentialité')
@section('page-title', 'Politique de confidentialité')
@section('update-date', 'Mise à jour le 28 février 2026')

@section('toc')
    <li><a href="#art1">Art. 1 — Responsable du traitement</a></li>
    <li><a href="#art2">Art. 2 — Données collectées</a></li>
    <li><a href="#art3">Art. 3 — Finalités et base légale</a></li>
    <li><a href="#art4">Art. 4 — Durée de conservation</a></li>
    <li><a href="#art5">Art. 5 — Destinataires</a></li>
    <li><a href="#art6">Art. 6 — Transferts internationaux</a></li>
    <li><a href="#art7">Art. 7 — Vos droits</a></li>
    <li><a href="#art8">Art. 8 — Sécurité</a></li>
    <li><a href="#art9">Art. 9 — Mineurs</a></li>
@endsection

@section('content')

<div class="mb-6 p-4 rounded-xl" style="background:#f0fdf4;border-left:4px solid #10b981;">
    <p class="text-sm text-green-700 mb-0">
        <i class="fas fa-shield-alt me-1"></i>
        Cette politique est établie conformément à la <strong>Loi n° 2017-20 du 20 avril 2018</strong> portant Code
        du numérique de la République du Bénin et à l'<strong>Acte additionnel CEDEAO A/SA.1/01/10 du 16 février 2010</strong>
        sur la protection des données personnelles.
    </p>
</div>

{{-- ART 1 --}}
<h2 id="art1" class="legal-section-title">Article 1 — Responsable du traitement</h2>
<p class="text-gray-700 leading-relaxed">Le responsable du traitement des données à caractère personnel collectées via la Plateforme Lokativ est :</p>
<div class="mt-3 p-4 rounded-xl text-gray-700" style="background:#f8fafc;border:1px solid #e2e8f0;">
    <p class="mb-1"><strong>[NOM DE LA SOCIÉTÉ]</strong></p>
    <p class="mb-1"><i class="fas fa-map-marker-alt text-blue-400 me-2"></i>[ADRESSE COMPLÈTE], Cotonou, République du Bénin</p>
    <p class="mb-1"><i class="fas fa-envelope text-blue-400 me-2"></i>[EMAIL CONTACT]</p>
    <p class="mb-1"><i class="fas fa-phone text-blue-400 me-2"></i>[NUMÉRO]</p>
    <p class="mb-0 mt-2 text-sm text-gray-500">
        <i class="fas fa-certificate text-blue-300 me-1"></i>
        Traitement déclaré auprès de l'<strong>APDP</strong> (Autorité de Protection des Données à caractère Personnel du Bénin) — Numéro : <strong>[NUMÉRO APDP]</strong>
    </p>
</div>

{{-- ART 2 --}}
<h2 id="art2" class="legal-section-title">Article 2 — Données collectées</h2>

<p class="text-gray-700 font-semibold mt-3 mb-2">2.1 Données des Utilisateurs (agences / collaborateurs)</p>
<ul class="space-y-1 text-gray-700 list-none ps-0">
    @foreach([
        'Identité : nom, prénom, fonction',
        'Coordonnées : adresse email professionnelle, numéro de téléphone',
        'Données de connexion : identifiants, logs d\'accès, adresse IP',
        'Données d\'abonnement : plan souscrit, historique de paiement',
    ] as $item)
        <li class="flex gap-2"><i class="fas fa-angle-right text-blue-400 mt-1 flex-shrink-0"></i><span>{{ $item }}</span></li>
    @endforeach
</ul>

<p class="text-gray-700 font-semibold mt-4 mb-2">2.2 Données des tiers gérés (locataires, propriétaires)</p>
<p class="text-sm text-gray-500 mb-2">Saisies par les Abonnés dans le cadre de leur activité de gestion immobilière :</p>
<ul class="space-y-1 text-gray-700 list-none ps-0">
    @foreach([
        'Identité civile complète',
        'Coordonnées (adresse, téléphone, email)',
        'Données financières : montants de loyer, historique de paiement, soldes',
        'Documents contractuels : baux, quittances, contrats',
    ] as $item)
        <li class="flex gap-2"><i class="fas fa-angle-right text-blue-400 mt-1 flex-shrink-0"></i><span>{{ $item }}</span></li>
    @endforeach
</ul>

<p class="text-gray-700 font-semibold mt-4 mb-2">2.3 Données techniques</p>
<ul class="space-y-1 text-gray-700 list-none ps-0">
    @foreach([
        'Journaux de connexion (logs serveur)',
        'Données de navigation et d\'utilisation de la Plateforme',
        'Données issues des cookies (voir Politique de Cookies)',
    ] as $item)
        <li class="flex gap-2"><i class="fas fa-angle-right text-blue-400 mt-1 flex-shrink-0"></i><span>{{ $item }}</span></li>
    @endforeach
</ul>

{{-- ART 3 --}}
<h2 id="art3" class="legal-section-title">Article 3 — Finalités et base légale du traitement</h2>
<div class="overflow-x-auto mt-3">
    <table class="table table-bordered table-legal text-sm w-100">
        <thead>
            <tr>
                <th>Finalité</th>
                <th>Base légale</th>
            </tr>
        </thead>
        <tbody>
            @foreach([
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
<h2 id="art4" class="legal-section-title">Article 4 — Durée de conservation</h2>
<div class="overflow-x-auto mt-3">
    <table class="table table-bordered table-legal text-sm w-100">
        <thead>
            <tr>
                <th>Catégorie</th>
                <th>Durée</th>
            </tr>
        </thead>
        <tbody>
            @foreach([
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
<h2 id="art5" class="legal-section-title">Article 5 — Destinataires des données</h2>
<ul class="mt-2 space-y-2 text-gray-700 list-none ps-0">
    <li class="flex gap-2"><i class="fas fa-users text-blue-400 mt-1 flex-shrink-0"></i><span><strong>Personnel habilité de Lokativ</strong> : dans la stricte limite de leurs attributions</span></li>
    <li class="flex gap-2"><i class="fas fa-server text-blue-400 mt-1 flex-shrink-0"></i><span><strong>Prestataires techniques</strong> : hébergeur, fournisseur d'envoi email (dans le cadre de contrats de sous-traitance garantissant un niveau de protection équivalent)</span></li>
    <li class="flex gap-2"><i class="fas fa-landmark text-blue-400 mt-1 flex-shrink-0"></i><span><strong>Autorités compétentes</strong> : sur réquisition judiciaire ou obligation légale (Police, Parquet, Administration fiscale)</span></li>
</ul>
<div class="mt-4 p-3 rounded-lg text-sm text-green-700" style="background:#f0fdf4;border:1px solid #bbf7d0;">
    <i class="fas fa-lock me-1"></i>
    Lokativ <strong>ne vend, ne loue et ne cède jamais</strong> les données personnelles à des tiers à des fins commerciales.
</div>

{{-- ART 6 --}}
<h2 id="art6" class="legal-section-title">Article 6 — Transferts internationaux</h2>
<p class="text-gray-700 leading-relaxed">
    En cas de transfert de données hors du territoire béninois, Lokativ s'assure que ce transfert s'effectue vers
    un pays offrant un niveau de protection adéquat ou sur la base de garanties appropriées (clauses contractuelles
    types, accord de sous-traitance), conformément aux <strong>articles 33 et suivants de l'Acte additionnel
    CEDEAO A/SA.1/01/10</strong>.
</p>

{{-- ART 7 --}}
<h2 id="art7" class="legal-section-title">Article 7 — Vos droits</h2>
<p class="text-gray-700 leading-relaxed">
    Conformément à la Loi n° 2017-20 du 20 avril 2018 et à l'Acte additionnel CEDEAO, toute personne dont
    les données sont traitées dispose des droits suivants :
</p>
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
    @foreach([
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
    <p class="font-semibold mb-1"><i class="fas fa-paper-plane me-1 text-blue-500"></i>Exercer vos droits</p>
    <p class="mb-1">Adressez votre demande à : <strong>[EMAIL DPO]</strong></p>
    <p class="mb-1">Ou par courrier : <strong>[NOM DE LA SOCIÉTÉ] — Protection des données, [ADRESSE]</strong></p>
    <p class="mb-1">Délai de réponse : <strong>30 jours</strong> à compter de la réception (une pièce d'identité peut être demandée).</p>
    <p class="mb-0 mt-2 text-gray-500">
        En cas de réponse insatisfaisante, saisissez l'<strong>APDP</strong> — Autorité de Protection des Données à caractère
        Personnel du Bénin : <strong>www.apdp.bj</strong>
    </p>
</div>

{{-- ART 8 --}}
<h2 id="art8" class="legal-section-title">Article 8 — Sécurité des données</h2>
<p class="text-gray-700 leading-relaxed">Lokativ met en œuvre les mesures techniques et organisationnelles appropriées pour garantir la sécurité des données :</p>
<ul class="mt-2 space-y-2 text-gray-700 list-none ps-0">
    @foreach([
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
    En cas de violation de données à caractère personnel susceptible d'engendrer un risque pour les droits
    et libertés des personnes, Lokativ notifiera l'<strong>APDP</strong> dans les meilleurs délais.
</p>

{{-- ART 9 --}}
<h2 id="art9" class="legal-section-title">Article 9 — Données des mineurs</h2>
<p class="text-gray-700 leading-relaxed">
    La Plateforme n'est pas destinée aux mineurs de moins de 18 ans. Lokativ ne collecte pas sciemment de
    données relatives à des mineurs.
</p>

<div class="mt-8 p-4 rounded-xl text-sm text-gray-500" style="background:#f1f5f9;">
    <i class="fas fa-envelope me-1"></i>
    Pour toute question relative à la protection de vos données : <strong>[EMAIL DPO]</strong>
</div>

@endsection
