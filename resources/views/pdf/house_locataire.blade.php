<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des locataires</title>
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
            border-bottom: 3px solid #2980b9;
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
            background-color: #eaf2f8;
            padding: 12px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #2980b9;
        }
        .info-box p {
            margin: 4px 0;
            font-size: 11px;
        }
        .info-box strong {
            color: #2c3e50;
        }
        .house-name {
            font-size: 16px;
            color: #2980b9;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #2980b9;
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
    <h1>LISTE DES LOCATAIRES</h1>
    <p class="subtitle">Rapport genere le {{ Carbon\Carbon::now()->format('d/m/Y') }} a {{ Carbon\Carbon::now()->format('H:i') }}</p>
</div>

<div class="info-box">
    <p><strong>Maison:</strong></p>
    <p class="house-name">{{ $element2['house_name'] }}</p>
    <p><strong>Nombre de locataires:</strong> {{ isset($element2['house']) ? count($element2['house']) : 0 }}</p>
    <p><strong>Agence:</strong> {{ get_active_annexe_name() }}</p>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 12%;">Agence</th>
            <th style="width: 10%;">N chambre</th>
            <th style="width: 13%;">Type chambre</th>
            <th style="width: 20%;">Locataire</th>
            <th style="width: 15%;">Telephone</th>
            <th style="width: 12%;">Avance</th>
            <th style="width: 18%;">Date d'entree</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($element2['house']) && count($element2['house']) > 0)
            @foreach($element2['house'] as $items)
                <tr>
                    <td>{{ $items->designation }}</td>
                    <td>{{ $items->numero_chambre }}</td>
                    <td>{{ $items->type_chambre }}</td>
                    <td><strong>{{ $items->nom }} {{ $items->prenom }}</strong></td>
                    <td>{{ $items->telephone }}</td>
                    <td>{{ $items->nombre_avance }} mois</td>
                    <td>{{ Carbon\Carbon::parse($items->date_entree)->format('d/m/Y') }}</td>
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
    <p>Document de listing des locataires de la maison.</p>
    <p>Genere le {{ Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
</div>

</body>
</html>
