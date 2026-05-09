<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Benefice general agence</title>
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
            border-bottom: 3px solid #9b59b6;
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
            background-color: #f5eef8;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #9b59b6;
        }
        .info-box p {
            margin: 4px 0;
            font-size: 11px;
        }
        .info-box strong {
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #9b59b6;
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
            background-color: #9b59b6 !important;
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
            color: #9b59b6;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
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
    <h1>BENEFICE GLOBAL DE L'AGENCE</h1>
    <p class="subtitle">Tous proprietaires - Periode du {{ Carbon\Carbon::parse($element2['date_debut'])->format('d/m/Y') }} au {{ Carbon\Carbon::parse($element2['date_fin'])->format('d/m/Y') }}</p>
</div>

<div class="info-box">
    <p><strong>Type de rapport:</strong> Synthese generale</p>
    <p><strong>Commission:</strong> Taux individuel par proprietaire</p>
    <p><strong>Nombre de transactions:</strong> {{ isset($element2['donnees']) ? count($element2['donnees']) : 0 }}</p>
    <p><strong>Date d'edition:</strong> {{ Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 12%;">Agence</th>
            <th style="width: 16%;">Maison</th>
            <th style="width: 16%;">Quartier</th>
            <th style="width: 14%;">Chambre</th>
            <th style="width: 14%;" class="amount">Loyer percu</th>
            <th style="width: 8%;" style="text-align:center;">Taux</th>
            <th style="width: 20%;" class="amount">Commission agence</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($element2['donnees']) && count($element2['donnees']) > 0)
            @foreach($element2['donnees'] as $items)
                <tr>
                    <td>{{ $items->designation }}</td>
                    <td><strong>{{ $items->nom_maison }}</strong></td>
                    <td>{{ $items->quartier }}</td>
                    <td>{{ $items->type_chambre }} (N {{ $items->numero_chambre }})</td>
                    <td class="amount">{{ format_price($items->montant) }}</td>
                    <td style="text-align:center;">{{ $items->pourcentage }} %</td>
                    <td class="amount" style="color: #9b59b6;">{{ format_price($items->commission) }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px;">Aucune donnee disponible</td>
            </tr>
        @endif
        <tr class="total-row">
            <td colspan="6" style="text-align: right;">TOTAL BENEFICE GLOBAL:</td>
            <td class="amount">{{ format_price($element2['garde']) }}</td>
        </tr>
    </tbody>
</table>

<div class="summary-box">
    <h3>RESUME GLOBAL</h3>
    <p>Benefice total realise sur l'ensemble des proprietaires</p>
    <p class="total">{{ format_price($element2['garde']) }}</p>
</div>


<div class="footer">
    <p>Document de synthese globale des benefices de l'agence.</p>
    <p>Genere le {{ Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
</div>

</body>
</html>
