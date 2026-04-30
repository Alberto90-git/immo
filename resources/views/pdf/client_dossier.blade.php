<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dossiers clients</title>
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
            border-bottom: 3px solid #16a085;
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
            background-color: #e8f6f3;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #16a085;
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
            background-color: #16a085;
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
        .amount {
            text-align: right;
            font-weight: bold;
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
    <h1>LISTE DES DOSSIERS CLIENTS TRAITES</h1>
    <p class="subtitle">Periode du {{ Carbon\Carbon::parse($element2['date_debut'])->format('d/m/Y') }} au {{ Carbon\Carbon::parse($element2['date_fin'])->format('d/m/Y') }}</p>
</div>

<div class="info-box">
    <p><strong>Document:</strong> Rapport des dossiers clients</p>
    <p><strong>Agence:</strong> {{ get_active_annexe_name() }}</p>
    <p><strong>Nombre de dossiers:</strong> {{ isset($element2['donnees']) ? count($element2['donnees']) : 0 }}</p>
    <p><strong>Date d'edition:</strong> {{ Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 12%;">Agence</th>
            <th style="width: 18%;">Nom & prenom</th>
            <th style="width: 13%;">Telephone</th>
            <th style="width: 15%;">Zone voulue</th>
            <th style="width: 12%;">Superficie</th>
            <th style="width: 15%;" class="amount">Budget</th>
            <th style="width: 15%;">Date cloture</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($element2['donnees']) && count($element2['donnees']) > 0)
            @foreach($element2['donnees'] as $items)
                <tr>
                    <td>{{ $items->designation }}</td>
                    <td><strong>{{ $items->nom }} {{ $items->prenom }}</strong></td>
                    <td>{{ $items->telephone }}</td>
                    <td>{{ $items->zone_voulu }}</td>
                    <td>{{ $items->superficie }} m2</td>
                    <td class="amount">{{ format_price($items->budget) }}</td>
                    <td>{{ Carbon\Carbon::parse($items->status)->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="7" style="text-align: center; padding: 20px; color: #7f8c8d;">
                    Aucune donnee disponible
                </td>
            </tr>
        @endif
    </tbody>
</table>

<div class="footer">
    <p>Document de synthese des dossiers clients traites.</p>
    <p>Genere le {{ Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
</div>

</body>
</html>
