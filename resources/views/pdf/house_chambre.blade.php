<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Maisons et chambres</title>
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
            border-bottom: 3px solid #f39c12;
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
            background-color: #fef9e7;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #f39c12;
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
            color: #f39c12;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #f39c12;
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
        .group-header {
            background-color: #fef9e7;
            font-weight: bold;
        }
        .group-header td {
            padding: 10px 8px;
            color: #f39c12;
            border-bottom: 2px solid #f39c12;
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
    <h1>LISTE DES MAISONS ET CHAMBRES</h1>
    <p class="subtitle">Rapport genere le {{ Carbon\Carbon::now()->format('d/m/Y') }} a {{ Carbon\Carbon::now()->format('H:i') }}</p>
</div>

<div class="info-box">
    <p><strong>Proprietaire:</strong></p>
    <p class="proprio-name">{{ $element2['nom'] }} {{ $element2['prenom'] }}</p>
    <p><strong>Nombre de chambres:</strong> {{ isset($element2['house']) ? count($element2['house']) : 0 }}</p>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 15%;">Agence</th>
            <th style="width: 20%;">Maison</th>
            <th style="width: 20%;">Quartier</th>
            <th style="width: 12%;">Chambre</th>
            <th style="width: 15%;">Type</th>
            <th style="width: 18%;" class="amount">Prix mensuel</th>
        </tr>
    </thead>
    <tbody>
        @php
            $currentMaison = '';
        @endphp
        @if(isset($element2['house']) && count($element2['house']) > 0)
            @foreach($element2['house'] as $items)
                @if($currentMaison != $items->nom_maison)
                    @php $currentMaison = $items->nom_maison; @endphp
                    <tr class="group-header">
                        <td colspan="6">Maison: {{ $items->nom_maison }} - {{ $items->quartier }}</td>
                    </tr>
                @endif
                <tr>
                    <td>{{ $items->designation }}</td>
                    <td>{{ $items->nom_maison }}</td>
                    <td>{{ $items->quartier }}</td>
                    <td>N {{ $items->numero_chambre }}</td>
                    <td>{{ $items->type_chambre }}</td>
                    <td class="amount">{{ format_price($items->prix) }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px;">Aucune donnee disponible</td>
            </tr>
        @endif
    </tbody>
</table>

<div class="footer">
    <p>Document de listing des maisons et chambres du proprietaire.</p>
    <p>Genere le {{ Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
</div>

</body>
</html>
