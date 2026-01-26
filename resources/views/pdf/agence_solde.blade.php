<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Benefice agence</title>
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
            border-bottom: 3px solid #e74c3c;
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
            background-color: #fdeaea;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #e74c3c;
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
            color: #e74c3c;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #e74c3c;
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
            background-color: #e74c3c !important;
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
            color: #e74c3c;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>BENEFICE REALISE PAR L'AGENCE</h1>
    <p class="subtitle">Periode du {{ Carbon\Carbon::parse($element2['date_debut'])->format('d/m/Y') }} au {{ Carbon\Carbon::parse($element2['date_fin'])->format('d/m/Y') }}</p>
</div>

<div class="info-box">
    <p><strong>Proprietaire concerne:</strong></p>
    <p class="proprio-name">{{ $element2['proprio_nom'] }} {{ $element2['proprio_prenom'] }}</p>
    <p><strong>Commission agence:</strong> {{ $element2['pourcentage'] }}%</p>
    <p><strong>Date d'edition:</strong> {{ Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 12%;">Agence</th>
            <th style="width: 18%;">Maison</th>
            <th style="width: 18%;">Quartier</th>
            <th style="width: 15%;">Chambre</th>
            <th style="width: 17%;" class="amount">Loyer percu</th>
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
                    <td class="amount">{{ number_format($items->montant, 0, ',', '.') }} XOF</td>
                    <td class="amount" style="color: #e74c3c;">{{ number_format(($items->montant * $element2['pourcentage']) / 100, 0, ',', '.') }} XOF</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">Aucune donnee disponible</td>
            </tr>
        @endif
        <tr class="total-row">
            <td colspan="5" style="text-align: right;">TOTAL BENEFICE AGENCE:</td>
            <td class="amount">{{ number_format($element2['garde'], 0, ',', '.') }} XOF</td>
        </tr>
    </tbody>
</table>

<div class="summary-box">
    <h3>RESUME</h3>
    <p>Benefice total realise chez le proprietaire <strong>{{ $element2['proprio_nom'] }} {{ $element2['proprio_prenom'] }}</strong></p>
    <p class="total">{{ number_format($element2['garde'], 0, ',', '.') }} XOF</p>
</div>

<div class="footer">
    <p>Document de synthese des benefices de l'agence.</p>
    <p>Genere le {{ Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
</div>

</body>
</html>
