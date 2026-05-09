<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('pdf.contrat_doc_title') }}</title>
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
        <h1>{{ $contratConfig->titre_contrat ?? __('pdf.contrat_doc_title') }}</h1>
    </div>
    <div class="contract-ref">
        {{ $contratConfig->sous_titre ?? __('pdf.contrat_subtitle') }}
    </div>

    <p style="margin-bottom: 15px;"><strong>{{ __('pdf.contrat_entre_soussignes') }}</strong></p>

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
        <div class="article-title">{{ __('pdf.contrat_art1_titre') }}</div>

        <div class="party-info">
            <div class="party-label">{{ __('pdf.contrat_bailleur_label') }}</div>
            <p>
                <strong>{{ $agence['designation'] ?? '' }}</strong><br>
                @if(!empty($agence['siege_social'])){{ __('pdf.contrat_bailleur_siege') }} {{ $agence['siege_social'] }}<br>@endif
                @if(!empty($agence['telephone'])){{ __('pdf.contrat_bailleur_tel') }} {{ $agence['telephone'] }}<br>@endif
                @if(!empty($agence['email'])){{ __('pdf.contrat_bailleur_email') }} {{ $agence['email'] }}<br>@endif
            </p>
            <p>{!! __('pdf.contrat_denomme_bailleur') !!}</p>
        </div>

        <p style="text-align: center; font-weight: bold; margin: 10px 0;">{{ __('pdf.contrat_et') }}</p>

        <div class="party-info">
            <div class="party-label">{{ __('pdf.contrat_locataire_label') }}</div>
            <p>
                <strong>M./Mme {{ $data->nom ?? '' }} {{ $data->prenom ?? '' }}</strong><br>
                @if(!empty($data->profession)){{ __('pdf.contrat_profession') }} {{ $data->profession }}<br>@endif
                @if(!empty($data->telephone)){{ __('pdf.contrat_bailleur_tel') }} {{ $data->telephone }}<br>@endif
                @if(!empty($data->quartier)){{ __('pdf.contrat_domicile') }} {{ $data->quartier }}<br>@endif
            </p>
            <p>{!! __('pdf.contrat_denomme_locataire') !!}</p>
        </div>

        <p style="margin-top: 10px;"><strong>{{ __('pdf.contrat_convenu') }}</strong></p>
    </div>

    <!-- Article 2 : Objet du contrat -->
    <div class="article">
        <div class="article-title">{{ __('pdf.contrat_art2_titre') }}</div>
        <p>{{ __('pdf.contrat_art2_intro') }}</p>
        <table class="recap-table">
            <tr>
                <td>{{ __('pdf.contrat_art2_col_maison') }}</td>
                <td>{{ $data->nom_maison ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>{{ __('pdf.contrat_art2_col_quartier') }}</td>
                <td>{{ $data->quartier ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>{{ __('pdf.contrat_art2_col_type') }}</td>
                <td>{{ $data->type_chambre ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td>{{ __('pdf.contrat_art2_col_numero') }}</td>
                <td>{{ $data->numero_chambre ?? 'N/A' }}</td>
            </tr>
        </table>
        <p>{{ __('pdf.contrat_art2_usage') }}</p>
    </div>

    <!-- Article 3 : Durée du bail -->
    <div class="article">
        <div class="article-title">{{ __('pdf.contrat_art3_titre') }}</div>
        <p>
            {{ __('pdf.contrat_art3_body1') }}
            <strong>{{ isset($data->date_entree) ? \Carbon\Carbon::parse($data->date_entree)->translatedFormat('d F Y') : 'N/A' }}</strong>.
        </p>
        <p>{{ __('pdf.contrat_art3_body2') }}</p>
    </div>

    <!-- Article 4 : Loyer -->
    <div class="article">
        <div class="article-title">{{ __('pdf.contrat_art4_titre') }}</div>
        <p>
            {!! __('pdf.contrat_art4_loyer', [
                'montant'  => '<strong>' . format_price($data->prix_mois ?? 0, $data->iddirection_ref ?? null) . '</strong>',
                'echeance' => '<strong>' . __('pdf.contrat_art4_echeance') . '</strong>',
            ]) !!}
        </p>
        <p>
            {!! __('pdf.contrat_art4_paiement', [
                'mode' => $data->mode_paiement ?? __('pdf.contrat_art4_mode_default') ?? '—',
            ]) !!}
        </p>
        <p>{{ __('pdf.contrat_art4_loi') }}</p>
    </div>

    <!-- Article 5 : Caution -->
    <div class="article">
        <div class="article-title">{{ __('pdf.contrat_art5_titre') }}</div>
        <p>
            {!! __('pdf.contrat_art5_body1', [
                'n_mois' => '<strong>' . ($data->nombre_caution ?? 0) . '</strong>',
                'montant' => '<strong>' . format_price(($data->nombre_caution ?? 0) * ($data->prix_mois ?? 0), $data->iddirection_ref ?? null) . '</strong>',
            ]) !!}
        </p>
        <p>{{ __('pdf.contrat_art5_body2') }}</p>
        <table class="recap-table">
            <tr>
                <td>{{ __('pdf.contrat_art5_col_courant') }}</td>
                <td>{{ format_price($data->caution_courant ?? 0, $data->iddirection_ref ?? null) }}</td>
            </tr>
            <tr>
                <td>{{ __('pdf.contrat_art5_col_eau') }}</td>
                <td>{{ format_price($data->caution_eau ?? 0, $data->iddirection_ref ?? null) }}</td>
            </tr>
            <tr>
                <td>{{ __('pdf.contrat_art5_col_total') }}</td>
                <td><strong>{{ format_price((($data->nombre_caution ?? 0) * ($data->prix_mois ?? 0)) + ($data->caution_courant ?? 0) + ($data->caution_eau ?? 0), $data->iddirection_ref ?? null) }}</strong></td>
            </tr>
        </table>
        <p>{{ __('pdf.contrat_art5_restitution') }}</p>
    </div>

    <!-- Article 6 : Avance -->
    <div class="article">
        <div class="article-title">{{ __('pdf.contrat_art6_titre') }}</div>
        <p>
            {!! __('pdf.contrat_art6_body1', [
                'n_mois' => '<strong>' . ($data->nombre_avance ?? 0) . '</strong>',
                'montant' => '<strong>' . format_price(($data->nombre_avance ?? 0) * ($data->prix_mois ?? 0), $data->iddirection_ref ?? null) . '</strong>',
            ]) !!}
        </p>
        <p>
            {!! __('pdf.contrat_art6_body2', [
                'n_mois' => $data->nombre_avance ?? 0,
            ]) !!}
        </p>
    </div>

    <!-- Article 7 : Obligations du Bailleur -->
    <div class="article">
        <div class="article-title">{{ __('pdf.contrat_art7_titre') }}</div>
        <p>{{ __('pdf.contrat_art7_intro') }}</p>
        <ul>
            <li>{{ __('pdf.contrat_art7_li1') }}</li>
            <li>{{ __('pdf.contrat_art7_li2') }}</li>
            <li>{{ __('pdf.contrat_art7_li3') }}</li>
            <li>{{ __('pdf.contrat_art7_li4') }}</li>
            <li>{{ __('pdf.contrat_art7_li5') }}</li>
        </ul>
    </div>

    <!-- Article 8 : Obligations du Locataire -->
    <div class="article">
        <div class="article-title">{{ __('pdf.contrat_art8_titre') }}</div>
        <p>{{ __('pdf.contrat_art8_intro') }}</p>
        <ul>
            <li>{{ __('pdf.contrat_art8_li1') }}</li>
            <li>{{ __('pdf.contrat_art8_li2') }}</li>
            <li>{{ __('pdf.contrat_art8_li3') }}</li>
            <li>{{ __('pdf.contrat_art8_li4') }}</li>
            <li>{{ __('pdf.contrat_art8_li5') }}</li>
            <li>{{ __('pdf.contrat_art8_li6') }}</li>
            <li>{{ __('pdf.contrat_art8_li7') }}</li>
            <li>{{ __('pdf.contrat_art8_li8') }}</li>
        </ul>
    </div>

    <!-- Article 9 : Résiliation -->
    <div class="article">
        <div class="article-title">{{ __('pdf.contrat_art9_titre') }}</div>
        <p>{{ __('pdf.contrat_art9_body1') }}</p>
        <p>{{ __('pdf.contrat_art9_body2') }}</p>
        <ul>
            <li>{{ __('pdf.contrat_art9_li1') }}</li>
            <li>{{ __('pdf.contrat_art9_li2') }}</li>
            <li>{{ __('pdf.contrat_art9_li3') }}</li>
        </ul>
    </div>

    <!-- Article 10 : État des lieux -->
    <div class="article">
        <div class="article-title">{{ __('pdf.contrat_art10_titre') }}</div>
        <p>{{ __('pdf.contrat_art10_body1') }}</p>
        <p>{{ __('pdf.contrat_art10_body2') }}</p>
    </div>

    <!-- Article 11 : Clause résolutoire -->
    <div class="article">
        <div class="article-title">{{ __('pdf.contrat_art11_titre') }}</div>
        <p>{{ __('pdf.contrat_art11_body') }}</p>
    </div>

    <!-- Article 12 : Élection de domicile -->
    <div class="article">
        <div class="article-title">{{ __('pdf.contrat_art12_titre') }}</div>
        <p>{{ __('pdf.contrat_art12_body1') }}</p>
        <p>{{ __('pdf.contrat_art12_body2') }}</p>
    </div>

    @endif

    <!-- Modes de paiement mobile -->
    @if(!empty($agence['cash_electronique']) || !empty($agence['cash_electronique_image_base64']))
    <div class="article" style="margin-top: 15px; page-break-inside: avoid;">
@if(!empty($agence['cash_electronique']))
        <p style="white-space: pre-line; color:#444;">{{ $agence['cash_electronique'] }}</p>
        @endif
    </div>
    @endif

    <!-- Fait à ... -->
    <div class="fait-a">
        <p>{{ __('pdf.fait_a', ['lieu' => $agence['siege_social'] ?? '.........................', 'date' => \Carbon\Carbon::now()->translatedFormat('d F Y')]) }}</p>
        <p>{{ __('pdf.en_deux_exemplaires') }}</p>
    </div>

    <!-- Signatures -->
    <table style="width:100%; margin-top:40px; border-collapse:collapse; page-break-inside:avoid;">
        {{-- Ligne 1 : labels --}}
        <tr>
            <td style="width:50%; text-align:center; padding:4px 20px; border:none;">
                <u><strong style="color:#012970; font-size:12px;">{{ __('pdf.sig_bailleur') }}</strong></u>
            </td>
            <td style="width:50%; text-align:center; padding:4px 20px; border:none;">
                <u><strong style="color:#012970; font-size:12px;">{{ __('pdf.sig_locataire_contrat') }}</strong></u>
            </td>
        </tr>
        {{-- Ligne 2 : image signature --}}
        @php
            $sigSrc = null;
            if (!empty($agence['signature_base64'])) {
                $sigSrc = $agence['signature_base64'];
            } elseif (!empty($agence['cash_electronique_image_base64'])) {
                $sigSrc = $agence['cash_electronique_image_base64'];
            }
        @endphp
        <tr>
            <td style="width:50%; text-align:center; padding:4px 20px; height:65px; border:none;">@if($sigSrc)<img src="{{ $sigSrc }}" style="max-height:55px; max-width:110px;">@endif</td>
            <td style="width:50%; text-align:center; padding:4px 20px; height:65px; border:none;">&nbsp;</td>
        </tr>
        {{-- Ligne 3 : trait de signature --}}
        <tr>
            <td style="width:50%; text-align:center; padding:2px 20px; border:none;">
                <hr style="width:70%; margin:0 auto; border:none; border-top:1px solid #333;">
            </td>
            <td style="width:50%; text-align:center; padding:2px 20px; border:none;">
                <hr style="width:70%; margin:0 auto; border:none; border-top:1px solid #333;">
            </td>
        </tr>
        {{-- Ligne 4 : noms --}}
        <tr>
            <td style="width:50%; text-align:center; padding:4px 20px; border:none;">
                <span style="font-size:11px;">{{ $agence['designation'] ?? '' }}</span>
            </td>
            <td style="width:50%; text-align:center; padding:4px 20px; border:none;">
                <span style="font-size:11px;">{{ $data->nom ?? '' }} {{ $data->prenom ?? '' }}</span>
            </td>
        </tr>
    </table>

    <!-- Pied de page -->
    <div class="page-footer">
        {{ $agence['designation'] ?? '' }} | {{ __('pdf.contrat_footer_loi') }}
    </div>

</body>
</html>
