<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #1e293b; margin: 0; padding: 0; }
    .header {
      background: #696cff; color: white; padding: 20px 28px;
      display: flex; justify-content: space-between; align-items: center;
    }
    .header h1 { font-size: 17px; margin: 0; font-weight: 700; letter-spacing: 1px; }
    .header .sub { font-size: 10px; opacity: 0.85; margin-top: 4px; }
    .content { padding: 24px 28px; }
    .section-title {
      font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px;
      color: #696cff; padding-bottom: 6px; border-bottom: 1px solid #e2e8f0; margin: 20px 0 10px;
    }
    table { width: 100%; border-collapse: collapse; font-size: 11px; }
    table td { padding: 7px 10px; }
    table td:first-child { color: #64748b; width: 40%; font-weight: 600; }
    table tr { border-bottom: 1px dashed #e2e8f0; }
    table tr:last-child { border-bottom: none; }
    .sha-box {
      background: #1e293b; color: #94a3b8; font-family: monospace; font-size: 9px;
      padding: 8px 12px; border-radius: 4px; word-break: break-all; margin: 4px 0;
    }
    .sig-img { max-width: 160px; border: 1px solid #e2e8f0; padding: 6px; background: white; border-radius: 4px; }
    .audit-table th { background: #f1f5f9; padding: 6px 10px; font-size: 10px; font-weight: 700; text-align: left; }
    .audit-table td { font-size: 10px; padding: 5px 10px; border-bottom: 1px solid #f1f5f9; }
    .audit-table tr:nth-child(even) td { background: #f8faff; }
    .legal-box {
      margin-top: 24px; padding: 12px 16px;
      background: #f8faff; border-left: 3px solid #696cff;
      border-radius: 0 8px 8px 0; font-size: 10px; color: #475569; line-height: 1.7;
    }
    .badge-success { background: #dcfce7; color: #15803d; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 700; }
    .watermark {
      position: fixed; top: 40%; left: 15%; transform: rotate(-30deg);
      font-size: 72px; color: rgba(34, 197, 94, 0.08); font-weight: 900;
      letter-spacing: 6px; z-index: -1; pointer-events: none;
    }
    .footer { margin-top: 30px; padding-top: 10px; border-top: 1px solid #e2e8f0;
      font-size: 9px; color: #94a3b8; text-align: center; }
  </style>
</head>
<body>

<div class="watermark">SIGNÉ</div>

<div class="header">
  <div>
    <h1>{{ __('sig.cert_title') }}</h1>
    <div class="sub">{{ config('app.name') }} — {{ now()->format('d/m/Y à H:i:s') }}</div>
  </div>
  <div style="text-align:right; font-size:10px; opacity:0.85;">
    #SIG-{{ str_pad($demande->id, 6, '0', STR_PAD_LEFT) }}<br>
    <span class="badge-success" style="color:#15803d; background:rgba(255,255,255,0.2); padding:2px 8px;">✓ {{ __('sig.statut_signe') }}</span>
  </div>
</div>

<div class="content">

  <div class="section-title">{{ __('sig.cert_document') }}</div>
  <table>
    <tr><td>{{ __('sig.form_doc_type') }}</td><td>{{ $demande->getDocumentTypeLabel() }}</td></tr>
    <tr><td>{{ __('sig.col_document') }}</td><td><strong>{{ $demande->document_titre }}</strong></td></tr>
    @if($demande->document_description)
    <tr><td>{{ __('sig.cert_description') }}</td><td>{{ $demande->document_description }}</td></tr>
    @endif
  </table>

  <div class="section-title">{{ __('sig.cert_signataire') }}</div>
  <table>
    <tr><td>{{ __('sig.cert_signataire') }}</td><td><strong>{{ $demande->signataire_nom }}</strong></td></tr>
    @if($demande->signataire_email)
    <tr><td>{{ __('sig.cert_email') }}</td><td>{{ $demande->signataire_email }}</td></tr>
    @endif
    @if($demande->signataire_telephone)
    <tr><td>{{ __('sig.cert_telephone') }}</td><td>{{ $demande->signataire_telephone }}</td></tr>
    @endif
  </table>

  <div class="section-title">{{ __('sig.cert_date') }}</div>
  <table>
    <tr>
      <td>{{ __('sig.cert_date') }}</td>
      <td><strong>{{ $demande->signe_at ? $demande->signe_at->format('d/m/Y à H:i:s') : '—' }}</strong></td>
    </tr>
    <tr><td>{{ __('sig.cert_ip') }}</td><td>{{ $demande->ip_adresse ?? '—' }}</td></tr>
  </table>

  @if($demande->signature_image)
  <div class="section-title">{{ __('sig.sign_draw') }}</div>
  <img src="{{ $demande->signature_image }}" class="sig-img" alt="Signature">
  @endif

  <div class="section-title">{{ __('sig.cert_sha_section') }}</div>
  <table>
    @if($demande->sha256_document)
    <tr>
      <td>{{ __('sig.cert_sha_before') }}</td>
      <td><div class="sha-box">{{ $demande->sha256_document }}</div></td>
    </tr>
    @endif
    @if($demande->sha256_signe)
    <tr>
      <td>{{ __('sig.cert_sha_after') }}</td>
      <td><div class="sha-box">{{ $demande->sha256_signe }}</div></td>
    </tr>
    @endif
  </table>

  @if($demande->audits && $demande->audits->count())
  <div class="section-title">{{ __('sig.cert_journal') }}</div>
  <table class="audit-table">
    <thead>
      <tr>
        <th>{{ __('sig.cert_col_date') }}</th>
        <th>{{ __('sig.cert_col_action') }}</th>
        <th>{{ __('sig.cert_col_canal') }}</th>
        <th>{{ __('sig.cert_col_ip') }}</th>
      </tr>
    </thead>
    <tbody>
      @foreach($demande->audits as $audit)
      <tr>
        <td>{{ $audit->created_at ? $audit->created_at->format('d/m/Y H:i:s') : '—' }}</td>
        <td>{{ __('sig.audit_' . $audit->action) }}</td>
        <td>{{ $audit->canal ?? '—' }}</td>
        <td>{{ $audit->ip_adresse ?? '—' }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @endif

  <div class="legal-box">
    <strong>{{ __('sig.cert_legal_label') }}</strong> {{ __('sig.cert_legal_note') }}<br>
    {{ __('sig.cert_generated_on') }} {{ now()->format('d/m/Y H:i:s') }} — Référence #SIG-{{ str_pad($demande->id, 6, '0', STR_PAD_LEFT) }}
  </div>

</div>

<div class="footer">
  {{ config('app.name') }} — {{ __('sig.cert_footer') }}
</div>

</body>
</html>
