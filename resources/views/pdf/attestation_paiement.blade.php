<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Attestation de paiement</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #333; padding: 30px 40px; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #27ae60; }
        .header .agency-name { font-size: 16px; font-weight: bold; color: #2c3e50; text-transform: uppercase; }
        .header .agency-sub { font-size: 10px; color: #777; margin-top: 4px; }
        .header img { max-width: 70px; max-height: 70px; display: block; margin: 0 auto 8px; }
        .logo-placeholder { width: 60px; height: 60px; background: #eee; border-radius: 50%; display: block; margin: 0 auto 8px; }
        .doc-title { text-align: center; margin: 30px 0 25px; }
        .doc-title h2 { font-size: 20px; color: #2c3e50; text-transform: uppercase; letter-spacing: 2px; border-bottom: 3px double #27ae60; display: inline-block; padding-bottom: 6px; }
        .ref-badge { text-align: right; margin-bottom: 20px; font-size: 10px; color: #888; font-style: italic; }
        .body-text { font-size: 12px; line-height: 2; text-align: justify; margin-bottom: 20px; }
        .highlight { font-weight: bold; color: #2c3e50; text-decoration: underline; }
        .details-box { background: #f0faf4; border: 1px solid #c3e6cb; border-left: 5px solid #27ae60; border-radius: 4px; padding: 14px 16px; margin: 20px 0; font-size: 11px; line-height: 1.9; }
        .details-box .row { display: flex; gap: 8px; }
        .details-box .row label { width: 190px; font-weight: bold; color: #555; flex-shrink: 0; }
        .amount-highlight { font-size: 15px; font-weight: bold; color: #27ae60; }
        .legal-note { font-size: 11px; color: #555; text-align: center; font-style: italic; margin: 25px 0 30px; line-height: 1.6; }
        .signature-area { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 40px; }
        .sig-block { text-align: center; width: 220px; }
        .sig-block .sig-line { border-top: 1px solid #333; margin-top: 50px; }
        .sig-block .sig-label { font-size: 10px; color: #777; margin-top: 4px; }
        .sig-block img { max-width: 80px; max-height: 50px; display: block; margin: 10px auto 0; }
        .date-line { font-size: 11px; color: #555; text-align: center; margin-bottom: 15px; }
        .footer { margin-top: 40px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 8px; color: #aaa; text-align: center; }
        .stamp-area { width: 100px; height: 100px; border: 2px dashed #ccc; border-radius: 50%; display: inline-block; margin-top: -20px; }
        .ref-code { font-family: 'Courier New', monospace; background: #f8f9fa; border: 1px solid #ddd; padding: 3px 8px; border-radius: 3px; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        @if($agence && !empty($agence['logo_base64']))
            <img src="{{ $agence['logo_base64'] }}" alt="Logo">
        @else
            <div class="logo-placeholder"></div>
        @endif
        <div class="agency-name">{{ $agence['designation'] ?? config('app.name') }}</div>
        <div class="agency-sub">
            {{ $agence['siege_social'] ?? '' }}
            @if($agence['telephone'] ?? false) — Tél : {{ $agence['telephone'] }} @endif
            @if($agence['email'] ?? false) — {{ $agence['email'] }} @endif
        </div>
    </div>

    <div class="ref-badge">
        Réf : <span class="ref-code">{{ $facture->numero_quittance ?? ('ATT-PAI-' . str_pad($facture->id, 6, '0', STR_PAD_LEFT)) }}</span>
    </div>

    <div class="doc-title">
        <h2>Attestation de paiement</h2>
    </div>

    <div class="body-text">
        Je soussigné(e), <span class="highlight">{{ $user->nom ?? '' }} {{ $user->prenom ?? '' }}</span>,
        représentant l'agence immobilière <span class="highlight">{{ $agence['designation'] ?? config('app.name') }}</span>,
        certifie avoir reçu de :
    </div>

    <div class="details-box">
        <div class="row"><label>Locataire :</label><span><strong>{{ $facture->nom }} {{ $facture->prenom }}</strong></span></div>
        @if($facture->profession ?? false)
        <div class="row"><label>Profession :</label><span>{{ $facture->profession }}</span></div>
        @endif
        <div class="row"><label>Maison :</label><span>{{ $facture->nom_maison }}</span></div>
        <div class="row"><label>Chambre :</label><span>{{ $facture->ch_type ?? $facture->type_chambre }} N° {{ $facture->ch_numero ?? $facture->numero_chambre }}</span></div>
        <div class="row"><label>Période (mois) :</label><span><strong>{{ $facture->mois }}</strong></span></div>
        <div class="row"><label>Montant reçu :</label><span class="amount-highlight">{{ format_price($facture->montant, $facture->iddirection_ref) }}</span></div>
        <div class="row"><label>Date du paiement :</label><span>{{ $facture->date_paiement ? \Carbon\Carbon::parse($facture->date_paiement)->format('d/m/Y') : '—' }}</span></div>
        <div class="row"><label>Mode de paiement :</label><span>{{ $facture->mode_paiement ?? '—' }}</span></div>
        <div class="row"><label>Type :</label><span>{{ $facture->type_paiement === 'direct' ? 'Paiement direct' : 'Sur avance' }}</span></div>
    </div>

    <div class="body-text">
        au titre du loyer mensuel de la période indiquée ci-dessus, pour le logement désigné.
        La présente attestation est établie à la demande de l'intéressé(e), pour servir et valoir ce que de droit.
    </div>

    <div class="legal-note">
        Ce document tient lieu de justificatif de paiement à usage administratif.
    </div>

    <div class="date-line">
        Fait à ________________________________, le {{ $dateEmission->translatedFormat('d F Y') }}
    </div>

    <div class="signature-area">
        <div class="sig-block">
            <div style="font-size:10px;color:#777;">Le locataire (pour information)</div>
            <div style="font-style:italic;font-size:10px;color:#999;">(Prise de connaissance)</div>
            <div class="sig-line"></div>
            <div class="sig-label">{{ $facture->nom }} {{ $facture->prenom }}</div>
        </div>
        <div class="sig-block">
            <div style="font-size:10px;color:#777;margin-bottom:8px;">Cachet et signature de l'agence</div>
            @if($agence && !empty($agence['signature_path']) && file_exists($agence['signature_path']))
                <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents($agence['signature_path'])) }}" alt="Signature">
            @else
                <div class="stamp-area"></div>
            @endif
            <div class="sig-line"></div>
            <div class="sig-label">{{ $agence['designation'] ?? '' }}</div>
        </div>
    </div>

    <div class="footer">
        Document généré le {{ $dateEmission->format('d/m/Y à H:i') }} par {{ config('app.name') }} — Document officiel, ne pas reproduire sans autorisation
    </div>
</body>
</html>
