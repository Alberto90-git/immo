<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relevé de compte locataire</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; padding-bottom: 12px; border-bottom: 3px solid #3498db; }
        .header-left img { max-width: 60px; max-height: 60px; }
        .header-left .logo-placeholder { width: 55px; height: 55px; background: #eee; border-radius: 4px; }
        .header-center { flex: 1; text-align: center; padding: 0 10px; }
        .header-center h1 { color: #2c3e50; font-size: 16px; text-transform: uppercase; letter-spacing: 1px; }
        .header-center .doc-title { font-size: 13px; color: #3498db; font-weight: bold; margin-top: 4px; text-transform: uppercase; }
        .header-right { text-align: right; font-size: 9px; color: #777; }
        .agency-info { text-align: center; font-size: 10px; color: #555; margin-bottom: 15px; }
        .agency-info strong { font-size: 11px; color: #2c3e50; }
        .info-section { display: flex; gap: 15px; margin-bottom: 18px; }
        .info-box { flex: 1; border: 1px solid #ddd; border-radius: 4px; padding: 10px 12px; background: #f8fbff; border-left: 4px solid #3498db; }
        .info-box h3 { font-size: 9px; text-transform: uppercase; color: #3498db; margin-bottom: 8px; letter-spacing: 0.5px; }
        .info-box p { margin: 3px 0; font-size: 10px; }
        .info-box strong { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 18px; }
        thead th { background-color: #3498db; color: white; padding: 8px 6px; text-align: left; font-size: 9px; text-transform: uppercase; }
        tbody td { padding: 7px 6px; border-bottom: 1px solid #e8e8e8; font-size: 10px; }
        tbody tr:nth-child(even) { background: #f9f9f9; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge-direct { background: #d4edda; color: #155724; padding: 2px 6px; border-radius: 3px; font-size: 9px; }
        .badge-avance { background: #fff3cd; color: #856404; padding: 2px 6px; border-radius: 3px; font-size: 9px; }
        .summary-box { background: #2c3e50; color: white; border-radius: 6px; padding: 12px 15px; margin-bottom: 18px; display: flex; justify-content: space-between; }
        .summary-item { text-align: center; }
        .summary-item .value { font-size: 15px; font-weight: bold; color: #2ecc71; }
        .summary-item .label { font-size: 9px; color: #bdc3c7; margin-top: 2px; text-transform: uppercase; }
        .footer { margin-top: 20px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 9px; color: #aaa; text-align: center; }
        .no-data { text-align: center; color: #999; padding: 20px; font-style: italic; }
        .ref { font-family: 'Courier New', monospace; font-size: 9px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="header-left">
            @if($agence && !empty($agence['logo_base64']))
                <img src="{{ $agence['logo_base64'] }}" alt="Logo">
            @else
                <div class="logo-placeholder"></div>
            @endif
        </div>
        <div class="header-center">
            <h1>{{ $agence['designation'] ?? config('app.name') }}</h1>
            <div class="doc-title">Relevé de compte locataire</div>
        </div>
        <div class="header-right">
            <div>Émis le {{ \Carbon\Carbon::now()->format('d/m/Y') }}</div>
            <div style="margin-top:4px;font-size:8px;color:#bbb;">{{ \Carbon\Carbon::now()->format('H:i') }}</div>
        </div>
    </div>

    @if($agence)
    <div class="agency-info">
        <strong>{{ strtoupper($agence['designation']) }}</strong><br>
        {{ $agence['siege_social'] ?? '' }} — Tél : {{ $agence['telephone'] ?? '' }} — {{ $agence['email'] ?? '' }}
    </div>
    @endif

    <div class="info-section">
        <div class="info-box">
            <h3>Informations locataire</h3>
            <p><strong>Nom complet :</strong> {{ $locataire->nom }} {{ $locataire->prenom }}</p>
            <p><strong>Profession :</strong> {{ $locataire->profession ?? '—' }}</p>
            @if($locataire->email ?? false)
            <p><strong>Email :</strong> {{ $locataire->email }}</p>
            @endif
            @if($locataire->telephone ?? false)
            <p><strong>Téléphone :</strong> {{ $locataire->telephone }}</p>
            @endif
        </div>
        <div class="info-box">
            <h3>Logement</h3>
            <p><strong>Maison :</strong> {{ $locataire->nom_maison }}</p>
            <p><strong>Chambre :</strong> {{ $locataire->type_chambre }} N° {{ $locataire->numero_chambre }}</p>
            <p><strong>Entrée :</strong> {{ $locataire->date_entree ? \Carbon\Carbon::parse($locataire->date_entree)->format('d/m/Y') : '—' }}</p>
            <p><strong>Loyer mensuel :</strong> {{ format_price($locataire->prix_mois, $locataire->iddirection_ref) }}</p>
        </div>
    </div>

    <div class="summary-box">
        <div class="summary-item">
            <div class="value">{{ $factures->count() }}</div>
            <div class="label">Paiements enregistrés</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ format_price($totalPaye, $locataire->iddirection_ref) }}</div>
            <div class="label">Total payé</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ $factures->where('type_paiement', 'direct')->count() }}</div>
            <div class="label">Paiements directs</div>
        </div>
        <div class="summary-item">
            <div class="value">{{ $factures->where('type_paiement', 'avance')->count() }}</div>
            <div class="label">Sur avance</div>
        </div>
    </div>

    @if($factures->isNotEmpty())
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Date paiement</th>
                <th>Mois concerné</th>
                <th class="text-right">Montant</th>
                <th class="text-center">Type</th>
                <th>Mode</th>
                <th>Référence</th>
            </tr>
        </thead>
        <tbody>
            @foreach($factures as $i => $f)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $f->date_paiement ? \Carbon\Carbon::parse($f->date_paiement)->format('d/m/Y') : '—' }}</td>
                <td>{{ $f->mois ?? '—' }}</td>
                <td class="text-right"><strong>{{ format_price($f->montant, $f->iddirection_ref) }}</strong></td>
                <td class="text-center">
                    @if($f->type_paiement === 'direct')
                        <span class="badge-direct">Direct</span>
                    @else
                        <span class="badge-avance">Avance</span>
                    @endif
                </td>
                <td>{{ $f->mode_paiement ?? '—' }}</td>
                <td class="ref">{{ $f->numero_quittance ?? ('FACT-' . str_pad($f->id, 6, '0', STR_PAD_LEFT)) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">Aucun paiement enregistré pour ce locataire.</div>
    @endif

    <div class="footer">
        Document généré le {{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }} — Confidentiel — Usage interne et administratif uniquement
    </div>
</body>
</html>
