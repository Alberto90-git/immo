<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ __('pdf.attestation_residence_title') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 12px; color: #333; padding: 30px 40px; }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #2c3e50; }
        .header .agency-name { font-size: 16px; font-weight: bold; color: #2c3e50; text-transform: uppercase; }
        .header .agency-sub { font-size: 10px; color: #777; margin-top: 4px; }
        .header img { max-width: 70px; max-height: 70px; display: block; margin: 0 auto 8px; }
        .logo-placeholder { width: 60px; height: 60px; background: #eee; border-radius: 50%; display: block; margin: 0 auto 8px; }
        .doc-title { text-align: center; margin: 30px 0 25px; }
        .doc-title h2 { font-size: 20px; color: #2c3e50; text-transform: uppercase; letter-spacing: 2px; border-bottom: 3px double #2c3e50; display: inline-block; padding-bottom: 6px; }
        .ref-badge { text-align: right; margin-bottom: 20px; font-size: 10px; color: #888; font-style: italic; }
        .body-text { font-size: 12px; line-height: 2; text-align: justify; margin-bottom: 20px; }
        .highlight { font-weight: bold; color: #2c3e50; text-decoration: underline; }
        .details-box { background: #f4f6f7; border: 1px solid #d5d8dc; border-left: 5px solid #2c3e50; border-radius: 4px; padding: 14px 16px; margin: 20px 0; font-size: 11px; line-height: 1.8; }
        .details-box .row { display: flex; gap: 8px; }
        .details-box .row label { width: 170px; font-weight: bold; color: #555; flex-shrink: 0; }
        .legal-note { font-size: 11px; color: #555; text-align: center; font-style: italic; margin: 25px 0 30px; line-height: 1.6; }
        .signature-area { display: flex; justify-content: space-between; align-items: flex-end; margin-top: 40px; }
        .sig-block { text-align: center; width: 220px; }
        .sig-block .sig-line { border-top: 1px solid #333; margin-top: 50px; }
        .sig-block .sig-label { font-size: 10px; color: #777; margin-top: 4px; }
        .sig-block img { max-width: 80px; max-height: 50px; display: block; margin: 10px auto 0; }
        .date-line { font-size: 11px; color: #555; text-align: center; margin-bottom: 15px; }
        .footer { margin-top: 40px; padding-top: 10px; border-top: 1px solid #ddd; font-size: 8px; color: #aaa; text-align: center; }
        .stamp-area { width: 100px; height: 100px; border: 2px dashed #ccc; border-radius: 50%; display: inline-block; margin-top: -20px; }
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
        Réf : ATT-RES-{{ str_pad($locataire->id, 5, '0', STR_PAD_LEFT) }}-{{ $dateEmission->format('dmY') }}
    </div>

    <div class="doc-title">
        <h2>{{ __('pdf.attestation_residence_title') }}</h2>
    </div>

    <div class="body-text">
        {!! __('pdf.att_res_body_1', [
            'user'   => '<span class="highlight">' . e(($user->nom ?? '') . ' ' . ($user->prenom ?? '')) . '</span>',
            'agence' => '<span class="highlight">' . e($agence['designation'] ?? config('app.name')) . '</span>',
        ]) !!}
    </div>

    <div class="details-box">
        <div class="row"><label>{{ __('pdf.att_res_details_nom') }}</label><span><strong>{{ $locataire->nom }} {{ $locataire->prenom }}</strong></span></div>
        @if($locataire->profession ?? false)
        <div class="row"><label>{{ __('pdf.info_profession') }}</label><span>{{ $locataire->profession }}</span></div>
        @endif
        @if($locataire->telephone ?? false)
        <div class="row"><label>{{ __('pdf.info_telephone') }}</label><span>{{ $locataire->telephone }}</span></div>
        @endif
        <div class="row"><label>{{ __('pdf.att_res_details_maison') }}</label><span>{{ $locataire->nom_maison }}</span></div>
        @if($locataire->quartier_maison ?? false)
        <div class="row"><label>{{ __('pdf.att_res_details_quartier') }}</label><span>{{ $locataire->quartier_maison }}</span></div>
        @endif
        <div class="row"><label>{{ __('pdf.att_res_details_logement') }}</label><span>{{ $locataire->type_chambre }} N° {{ $locataire->numero_chambre }}</span></div>
        <div class="row"><label>{{ __('pdf.att_res_details_date_entree') }}</label><span>{{ $locataire->date_entree ? \Carbon\Carbon::parse($locataire->date_entree)->translatedFormat('d F Y') : '—' }}</span></div>
    </div>

    <div class="body-text">
        {{ __('pdf.att_res_body_2') }}
    </div>

    <div class="legal-note">
        {{ __('pdf.att_res_legal_note') }}
    </div>

    <div class="date-line">
        {{ __('pdf.fait_a', ['lieu' => '________________________________', 'date' => $dateEmission->translatedFormat('d F Y')]) }}
    </div>

    <div class="signature-area">
        <div class="sig-block">
            <div style="font-size:10px;color:#777;">{{ __('pdf.sig_le_locataire') }}</div>
            <div style="font-style:italic;font-size:10px;color:#999;">{{ __('pdf.sig_signature') }}</div>
            <div class="sig-line"></div>
            <div class="sig-label">{{ $locataire->nom }} {{ $locataire->prenom }}</div>
        </div>
        <div class="sig-block">
            <div style="font-size:10px;color:#777;margin-bottom:8px;">{{ __('pdf.sig_agence') }}</div>
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
        {{ __('pdf.generated_on') }} {{ $dateEmission->format('d/m/Y') }} — {{ __('pdf.footer_officiel') }}
    </div>
</body>
</html>
