<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Fiche de paie proprietaire</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #27ae60;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 20px;
            margin-bottom: 5px;
        }
        .header .subtitle {
            color: #7f8c8d;
            font-size: 12px;
        }
        .info-box {
            background-color: #e8f5e9;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #27ae60;
        }
        .info-box p {
            margin: 4px 0;
            font-size: 11px;
        }
        .info-box strong {
            color: #2c3e50;
        }
        .proprio-name {
            font-size: 16px;
            color: #27ae60;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #27ae60;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        table td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .total-row {
            background-color: #27ae60 !important;
            color: white;
            font-weight: bold;
        }
        .total-row td {
            border-bottom: none;
            padding: 12px 8px;
            font-size: 12px;
        }
        .amount {
            text-align: right;
            font-weight: bold;
        }
        .summary-box {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .summary-box h3 {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .summary-box .total {
            font-size: 22px;
            font-weight: bold;
            color: #27ae60;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
        }
        .signature-box {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .signature {
            width: 45%;
            text-align: center;
            padding-top: 40px;
            border-top: 1px solid #333;
        }
        .agency-header {
            text-align: center;
            margin-bottom: 10px;
        }
        .agency-header img {
            max-width: 60px;
            max-height: 60px;
            margin-bottom: 5px;
        }
        .agency-header h2 {
            color: #2c3e50;
            font-size: 16px;
            margin: 3px 0;
        }
        .agency-header p {
            color: #7f8c8d;
            font-size: 10px;
            margin: 2px 0;
        }
    </style>
</head>
<body>

@if(isset($annexeData) && $annexeData)
<div class="agency-header">
    @if(!empty($annexeData['logo_base64']))
        <img src="{{ $annexeData['logo_base64'] }}" alt="Logo">
    @endif
    <h2>{{ strtoupper($annexeData['designation']) }}</h2>
    <p>{{ $annexeData['siege_social'] }}</p>
    <p>Tel: {{ $annexeData['telephone'] }} - Email: {{ $annexeData['email'] }}</p>
</div>
@endif

<div class="header">
    <h1>FICHE DE PAIE PROPRIETAIRE</h1>
    <p class="subtitle">Periode du {{ Carbon\Carbon::parse($element2['date_debut'])->format('d/m/Y') }} au {{ Carbon\Carbon::parse($element2['date_fin'])->format('d/m/Y') }}</p>
</div>

<div class="info-box">
    <p class="proprio-name">{{ $element2['proprio_nom'] }} {{ $element2['proprio_prenom'] }}</p>
    <p><strong>Pourcentage agence:</strong> {{ $element2['pourcentage'] }}%</p>
    <p><strong>Pourcentage proprietaire:</strong> {{ 100 - $element2['pourcentage'] }}%</p>
    <p><strong>Date d'edition:</strong> {{ Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 12%;">Agence</th>
            <th style="width: 15%;">Maison</th>
            <th style="width: 15%;">Quartier</th>
            <th style="width: 10%;">Chambre</th>
            <th style="width: 12%;">Type</th>
            <th style="width: 18%;" class="amount">Loyer percu</th>
            <th style="width: 18%;" class="amount">Part proprietaire</th>
        </tr>
    </thead>
    <tbody>
        @php
            $currentMaison = '';
            $totalMaison = 0;
        @endphp
        @if(isset($element2['donnees']) && count($element2['donnees']) > 0)
            @foreach($element2['donnees'] as $items)
                <tr>
                    <td>{{ $items->designation }}</td>
                    <td><strong>{{ $items->nom_maison }}</strong></td>
                    <td>{{ $items->quartier }}</td>
                    <td>N {{ $items->numero_chambre }}</td>
                    <td>{{ $items->type_chambre }}</td>
                    <td class="amount">{{ number_format($items->montant, 0, ',', '.') }} XOF</td>
                    <td class="amount" style="color: #27ae60;">{{ number_format(($items->montant * (100 - $element2['pourcentage'])) / 100, 0, ',', '.') }} XOF</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">Aucune donnee disponible</td>
            </tr>
        @endif
        <tr class="total-row">
            <td colspan="6" style="text-align: right;">TOTAL A PAYER AU PROPRIETAIRE:</td>
            <td class="amount">{{ number_format($element2['garde'], 0, ',', '.') }} XOF</td>
        </tr>
    </tbody>
</table>

<div class="summary-box">
    <h3>RESUME DU PAIEMENT</h3>
    <p>Montant total a verser au proprietaire <strong>{{ $element2['proprio_nom'] }} {{ $element2['proprio_prenom'] }}</strong></p>
    <p class="total">{{ number_format($element2['garde'], 0, ',', '.') }} XOF</p>
</div>

<table style="margin-top: 40px; border: none;">
    <tr style="background: none;">
        <td style="width: 50%; text-align: center; border: none; padding-top: 50px;">
            <div style="border-top: 1px solid #333; width: 80%; margin: 0 auto; padding-top: 5px;">
                Signature du proprietaire
            </div>
        </td>
        <td style="width: 50%; text-align: center; border: none; padding-top: 50px;">
            <div style="border-top: 1px solid #333; width: 80%; margin: 0 auto; padding-top: 5px;">
                Cachet et signature de l'agence
            </div>
        </td>
    </tr>
</table>

<div class="footer">
    <p>Ce document fait foi de fiche de paie pour la periode indiquee.</p>
    <p>Document genere le {{ Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
</div>

</body>
</html>
