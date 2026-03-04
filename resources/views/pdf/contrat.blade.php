<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrat de Bail d'Habitation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 30px 40px;
        }

        /* En-tête */
        .header {
            text-align: center;
            border-bottom: 3px double #012970;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-logo {
            margin-bottom: 8px;
        }
        .header-logo img {
            max-width: 80px;
            max-height: 80px;
        }
        .agency-name {
            font-size: 18px;
            font-weight: bold;
            color: #012970;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .agency-info {
            font-size: 10px;
            color: #555;
            margin-top: 3px;
        }
        .contract-title {
            text-align: center;
            margin: 25px 0 5px 0;
        }
        .contract-title h1 {
            font-size: 16px;
            color: #012970;
            text-transform: uppercase;
            text-decoration: underline;
            letter-spacing: 2px;
        }
        .contract-ref {
            text-align: center;
            font-size: 10px;
            color: #666;
            font-style: italic;
            margin-bottom: 20px;
        }

        /* Articles */
        .article {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .article-title {
            font-size: 13px;
            font-weight: bold;
            color: #012970;
            margin-bottom: 6px;
            padding-bottom: 3px;
            border-bottom: 1px solid #ddd;
        }
        .article p {
            margin-bottom: 6px;
            text-align: justify;
        }
        .article ul {
            margin-left: 25px;
            margin-bottom: 8px;
        }
        .article ul li {
            margin-bottom: 3px;
        }
        .party-info {
            background-color: #f8f9fa;
            padding: 10px 15px;
            border-left: 3px solid #012970;
            margin: 8px 0;
        }
        .party-label {
            font-weight: bold;
            color: #012970;
            font-size: 11px;
            text-transform: uppercase;
        }

        /* Tableau récapitulatif */
        .recap-table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .recap-table td {
            padding: 6px 10px;
            border: 1px solid #ddd;
            font-size: 11px;
        }
        .recap-table td:first-child {
            font-weight: bold;
            background-color: #f8f9fa;
            width: 40%;
            color: #012970;
        }

        /* Signatures */
        .signatures {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signatures-row {
            display: table;
            width: 100%;
        }
        .signature-block {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        .signature-label {
            font-weight: bold;
            color: #012970;
            text-decoration: underline;
            margin-bottom: 5px;
            font-size: 12px;
        }
        .signature-name {
            margin-top: 5px;
            font-size: 11px;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #333;
            width: 70%;
            display: inline-block;
        }

        .fait-a {
            margin-top: 30px;
            text-align: right;
            font-style: italic;
        }

        .page-footer {
            position: fixed;
            bottom: 20px;
            left: 40px;
            right: 40px;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <!-- En-tête de l'agence -->
    <div class="header">
        @if(!empty($agence['logo_base64']))
        <div class="header-logo">
            <img src="{{ $agence['logo_base64'] }}" alt="Logo">
        </div>
        @endif
        <div class="agency-name">{{ $agence['designation'] ?? 'Agence Immobilière' }}</div>
        <div class="agency-info">
            @if(!empty($agence['siege_social'])){{ $agence['siege_social'] }}@endif
            @if(!empty($agence['telephone'])) | Tél: {{ $agence['telephone'] }}@endif
            @if(!empty($agence['email'])) | {{ $agence['email'] }}@endif
        </div>
    </div>

    <!-- Titre du contrat -->
    <div class="contract-title">
        <h1>{{ $contratConfig->titre_contrat ?? "Contrat de Bail d'Habitation" }}</h1>
    </div>
    <div class="contract-ref">
        {{ $contratConfig->sous_titre ?? "Conformément à la Loi N°2022-30 du 20 décembre 2022 portant régime juridique du bail à usage d'habitation en République du Bénin" }}
    </div>

    <p style="margin-bottom: 15px;"><strong>ENTRE LES SOUSSIGNÉS :</strong></p>

    @if($articlesCustom)
    {{-- Articles personnalisés par la direction --}}
    @foreach($articlesCustom as $index => $article)
    <div class="article">
        <div class="article-title">Article {{ $index + 1 }} - {{ $article['titre'] }}</div>
        <p style="white-space: pre-line;">{!! nl2br(e($article['contenu'])) !!}</p>
    </div>
    @endforeach
    @else
    <!-- Article 1 : Les parties -->
    <div class="article">
        <div class="article-title">Article 1 - Les Parties</div>

        <div class="party-info">
            <div class="party-label">Le Bailleur (Agence Immobilière)</div>
            <p>
                <strong>{{ $agence['designation'] ?? '' }}</strong><br>
                @if(!empty($agence['siege_social']))Siège social : {{ $agence['siege_social'] }}<br>@endif
                @if(!empty($agence['telephone']))Téléphone : {{ $agence['telephone'] }}<br>@endif
                @if(!empty($agence['email']))Email : {{ $agence['email'] }}<br>@endif
            </p>
            <p>Ci-après dénommé(e) <strong>« le Bailleur »</strong>,</p>
        </div>

        <p style="text-align: center; font-weight: bold; margin: 10px 0;">ET</p>

        <div class="party-info">
            <div class="party-label">Le Locataire</div>
            <p>
                <strong>M./Mme {{ $data->nom ?? '' }} {{ $data->prenom ?? '' }}</strong><br>
                @if(!empty($data->profession))Profession : {{ $data->profession }}<br>@endif
                @if(!empty($data->telephone))Téléphone : {{ $data->telephone }}<br>@endif
                @if(!empty($data->quartier))Domicile : {{ $data->quartier }}<br>@endif
            </p>
            <p>Ci-après dénommé(e) <strong>« le Locataire »</strong>,</p>
        </div>

        <p style="margin-top: 10px;"><strong>IL A ÉTÉ CONVENU ET ARRÊTÉ CE QUI SUIT :</strong></p>
    </div>

    <!-- Article 2 : Objet du contrat -->
    <div class="article">
        <div class="article-title">Article 2 - Objet du Contrat</div>
        <p>
            Le Bailleur donne en location au Locataire, qui accepte, un logement dont les caractéristiques sont les suivantes :
        </p>
        <table class="recap-table">
            <tr>
                <td>Maison</td>
                <td>{{ $data->nom_maison ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Quartier / Localisation</td>
                <td>{{ $data->quartier ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Type de chambre</td>
                <td>{{ $data->type_chambre ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>Numéro de chambre</td>
                <td>{{ $data->numero_chambre ?? 'N/A' }}</td>
            </tr>
        </table>
        <p>Le logement est destiné exclusivement à l'usage d'habitation du Locataire et de sa famille.</p>
    </div>

    <!-- Article 3 : Durée du bail -->
    <div class="article">
        <div class="article-title">Article 3 - Durée du Bail</div>
        <p>
            Le présent bail est consenti et accepté pour une durée indéterminée, prenant effet à compter du
            <strong>{{ isset($data->date_entree) ? \Carbon\Carbon::parse($data->date_entree)->translatedFormat('d F Y') : 'N/A' }}</strong>.
        </p>
        <p>
            Conformément à la loi, chacune des parties pourra mettre fin au bail sous réserve du respect d'un préavis de trois (3) mois, notifié par écrit.
        </p>
    </div>

    <!-- Article 4 : Loyer -->
    <div class="article">
        <div class="article-title">Article 4 - Loyer</div>
        <p>
            Le loyer mensuel est fixé à la somme de <strong>{{ number_format($data->prix_mois ?? 0, 0, ',', '.') }} F CFA</strong>, payable au plus tard le <strong>cinq (5) de chaque mois</strong>.
        </p>
        <p>
            Le paiement s'effectue par {{ $data->mode_paiement ?? 'tout moyen convenu entre les parties' }}.
        </p>
        <p>
            Conformément à l'article 12 de la Loi N°2022-30, le loyer ne pourra excéder huit pour cent (8%) de la valeur vénale du local par an.
        </p>
    </div>

    <!-- Article 5 : Caution -->
    <div class="article">
        <div class="article-title">Article 5 - Dépôt de Garantie (Caution)</div>
        <p>
            Conformément à l'article 14 de la Loi N°2022-30, le Locataire verse au Bailleur un dépôt de garantie correspondant à
            <strong>{{ $data->nombre_caution ?? 0 }} mois</strong> de loyer, soit la somme de
            <strong>{{ number_format(($data->nombre_caution ?? 0) * ($data->prix_mois ?? 0), 0, ',', '.') }} F CFA</strong>.
        </p>
        <p>Ce dépôt de garantie ne pourra excéder trois (3) mois de loyer.</p>
        <table class="recap-table">
            <tr>
                <td>Caution électricité</td>
                <td>{{ number_format($data->caution_courant ?? 0, 0, ',', '.') }} F CFA</td>
            </tr>
            <tr>
                <td>Caution eau</td>
                <td>{{ number_format($data->caution_eau ?? 0, 0, ',', '.') }} F CFA</td>
            </tr>
            <tr>
                <td>Total caution</td>
                <td><strong>{{ number_format((($data->nombre_caution ?? 0) * ($data->prix_mois ?? 0)) + ($data->caution_courant ?? 0) + ($data->caution_eau ?? 0), 0, ',', '.') }} F CFA</strong></td>
            </tr>
        </table>
        <p>
            Le dépôt de garantie sera restitué au Locataire dans un délai maximum de trois (3) mois après la restitution des clés, déduction faite des sommes restant dues et des réparations locatives à la charge du Locataire.
        </p>
    </div>

    <!-- Article 6 : Avance -->
    <div class="article">
        <div class="article-title">Article 6 - Avance sur Loyer</div>
        <p>
            Le Locataire verse au Bailleur une avance de
            <strong>{{ $data->nombre_avance ?? 0 }} mois</strong> de loyer, soit la somme de
            <strong>{{ number_format(($data->nombre_avance ?? 0) * ($data->prix_mois ?? 0), 0, ',', '.') }} F CFA</strong>.
        </p>
        <p>
            Cette avance couvre les {{ $data->nombre_avance ?? 0 }} premiers mois de location à compter de la date d'effet du bail.
        </p>
    </div>

    <!-- Article 7 : Obligations du Bailleur -->
    <div class="article">
        <div class="article-title">Article 7 - Obligations du Bailleur</div>
        <p>Conformément aux articles 22 à 24 de la Loi N°2022-30, le Bailleur s'engage à :</p>
        <ul>
            <li>Délivrer au Locataire le logement en bon état d'usage et de réparation, ainsi que les équipements mentionnés au contrat ;</li>
            <li>Assurer au Locataire la jouissance paisible du logement ;</li>
            <li>Entretenir les locaux en état de servir à l'usage prévu, notamment les gros ouvrages (toiture, murs porteurs, fondations, canalisations principales) ;</li>
            <li>Ne pas s'opposer aux aménagements réalisés par le Locataire, dès lors qu'ils ne constituent pas une transformation de la chose louée ;</li>
            <li>Remettre au Locataire une quittance de loyer à chaque paiement.</li>
        </ul>
    </div>

    <!-- Article 8 : Obligations du Locataire -->
    <div class="article">
        <div class="article-title">Article 8 - Obligations du Locataire</div>
        <p>Conformément aux articles 25 à 27 de la Loi N°2022-30, le Locataire s'engage à :</p>
        <ul>
            <li>Payer le loyer et les charges aux termes convenus ;</li>
            <li>User des locaux loués en bon père de famille et suivant la destination qui leur a été donnée ;</li>
            <li>Répondre des dégradations et pertes qui surviennent pendant la durée du contrat, à moins de prouver qu'elles ont eu lieu par cas de force majeure ;</li>
            <li>Ne pas transformer les locaux sans l'accord écrit préalable du Bailleur ;</li>
            <li>Ne pas sous-louer tout ou partie du logement sans l'accord écrit du Bailleur ;</li>
            <li>Signaler sans délai au Bailleur toute dégradation ou tout dysfonctionnement nécessitant une réparation à la charge de celui-ci ;</li>
            <li>Laisser exécuter dans les locaux les travaux d'amélioration ou d'entretien nécessaires ;</li>
            <li>Restituer les locaux en bon état à la fin du bail, sous réserve de l'usure normale.</li>
        </ul>
    </div>

    <!-- Article 9 : Résiliation -->
    <div class="article">
        <div class="article-title">Article 9 - Résiliation du Bail</div>
        <p>
            Conformément à l'article 30 de la Loi N°2022-30, chacune des parties peut mettre fin au bail moyennant un préavis de
            <strong>trois (3) mois</strong>, notifié à l'autre partie par lettre recommandée avec accusé de réception ou par tout moyen permettant de rapporter la preuve de la notification.
        </p>
        <p>Le bail peut être résilié de plein droit :</p>
        <ul>
            <li>En cas de défaut de paiement du loyer ou des charges dans les délais prévus, après mise en demeure restée infructueuse pendant un mois ;</li>
            <li>En cas de non-respect des obligations contractuelles par l'une des parties ;</li>
            <li>En cas de destruction totale du logement par cas de force majeure.</li>
        </ul>
    </div>

    <!-- Article 10 : État des lieux -->
    <div class="article">
        <div class="article-title">Article 10 - État des Lieux</div>
        <p>
            Un état des lieux sera établi contradictoirement par les parties lors de la remise et de la restitution des clés.
        </p>
        <p>
            L'état des lieux d'entrée et de sortie sera annexé au présent contrat. À défaut d'état des lieux, le Locataire est présumé avoir reçu les locaux en bon état de réparations locatives.
        </p>
    </div>

    <!-- Article 11 : Clause résolutoire -->
    <div class="article">
        <div class="article-title">Article 11 - Clause Résolutoire</div>
        <p>
            À défaut de paiement à son échéance d'un seul terme de loyer ou de charges, et un mois après un commandement de payer ou une mise en demeure restés sans effet, le présent bail sera résilié de plein droit, si bon semble au Bailleur, sans qu'il soit besoin de faire prononcer cette résiliation en justice.
        </p>
    </div>

    <!-- Article 12 : Élection de domicile -->
    <div class="article">
        <div class="article-title">Article 12 - Élection de Domicile et Juridiction Compétente</div>
        <p>
            Pour l'exécution du présent contrat, les parties élisent domicile en leurs adresses respectives indiquées ci-dessus.
        </p>
        <p>
            En cas de litige relatif à l'interprétation ou à l'exécution du présent contrat, les parties s'engagent à rechercher un règlement amiable. À défaut d'accord, le tribunal compétent du lieu de situation de l'immeuble sera saisi.
        </p>
    </div>

    @endif

    <!-- Fait à ... -->
    <div class="fait-a">
        <p>
            Fait à {{ $agence['siege_social'] ?? '.........................' }}, le {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </p>
        <p>En deux (2) exemplaires originaux, un pour chaque partie.</p>
    </div>

    <!-- Signatures -->
    <div class="signatures">
        <div class="signatures-row">
            <div class="signature-block">
                <div class="signature-label">Pour le Bailleur</div>
                <div class="signature-name">{{ $agence['designation'] ?? '' }}</div>
                @if(!empty($agence['signature_base64']))
                <div style="margin-top: 10px;">
                    <img src="{{ $agence['signature_base64'] }}" alt="Signature" style="max-width: 120px; max-height: 60px;">
                </div>
                @else
                <div class="signature-line"></div>
                @endif
            </div>
            <div class="signature-block">
                <div class="signature-label">Pour le Locataire</div>
                <div class="signature-name">{{ $data->nom ?? '' }} {{ $data->prenom ?? '' }}</div>
                <div class="signature-line"></div>
            </div>
        </div>
    </div>

    <!-- Pied de page -->
    <div class="page-footer">
        {{ $agence['designation'] ?? '' }} | Contrat de bail d'habitation | Loi N°2022-30 du 20/12/2022
    </div>

</body>
</html>
