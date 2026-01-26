<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Liste des proprietaires et maisons</title>
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
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #3498db;
        }
        .header h1 {
            color: #2c3e50;
            font-size: 22px;
            margin-bottom: 5px;
        }
        .header .subtitle {
            color: #7f8c8d;
            font-size: 12px;
        }
        .info-box {
            background-color: #ecf0f1;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info-box p {
            margin: 3px 0;
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
            background-color: #3498db;
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
        table tr:hover {
            background-color: #e8f4fc;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
        }
        .stats {
            background-color: #27ae60;
            color: white;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
        .stats span {
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>LISTE DES PROPRIETAIRES ET LEURS MAISONS</h1>
    <p class="subtitle">Rapport genere le {{ Carbon\Carbon::now()->format('d/m/Y') }} a {{ Carbon\Carbon::now()->format('H:i') }}</p>
</div>

<div class="info-box">
    <p><strong>Document:</strong> Rapport des proprietaires et maisons</p>
    <p><strong>Agence:</strong> {{ get_active_annexe_name() }}</p>
    <p><strong>Nombre total:</strong> {{ isset($element) ? count($element) : 0 }} enregistrements</p>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 15%;">Agence</th>
            <th style="width: 20%;">Proprietaire</th>
            <th style="width: 15%;">Telephone</th>
            <th style="width: 15%;">Adresse</th>
            <th style="width: 15%;">Maison</th>
            <th style="width: 20%;">Quartier</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($element) && count($element) > 0)
            @foreach($element as $items)
                <tr>
                    <td>{{ $items->designation }}</td>
                    <td><strong>{{ $items->nom }} {{ $items->prenom }}</strong></td>
                    <td>{{ $items->telephone }}</td>
                    <td>{{ $items->adresse }}</td>
                    <td>{{ $items->nom_maison }}</td>
                    <td>{{ $items->quartier }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; color: #7f8c8d;">
                    Aucune donnee disponible
                </td>
            </tr>
        @endif
    </tbody>
</table>

<div class="footer">
    <p>Ce document a ete genere automatiquement par le systeme de gestion immobiliere.</p>
    <p>{{ Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
</div>

</body>
</html>
