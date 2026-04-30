<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('sig.confirm_title') }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
    body { background: #f0f2ff; font-family: 'Segoe UI', sans-serif; }
    .wrapper { max-width: 600px; margin: 60px auto; padding: 0 16px; }
    .card { border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
    .card-header-custom {
      background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
      color: white; border-radius: 16px 16px 0 0; padding: 28px 32px; text-align: center;
    }
    .card-header-custom .icon { font-size: 48px; margin-bottom: 8px; }
    .card-header-custom h2 { font-size: 22px; font-weight: 700; margin: 0; }
    .card-body-custom { padding: 28px 32px; }
    .info-row { display: flex; justify-content: space-between; padding: 10px 0;
      border-bottom: 1px dashed #e2e8f0; font-size: 13px; }
    .info-row:last-child { border-bottom: none; }
    .info-label { color: #64748b; font-weight: 600; }
    .sha-block {
      background: #1e293b; color: #94a3b8; font-family: monospace; font-size: 11px;
      padding: 12px 16px; border-radius: 8px; word-break: break-all; margin-top: 8px;
    }
    .sig-preview { max-width: 200px; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px; background: white; }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="card">
    <div class="card-header-custom">
      <div class="icon">✅</div>
      <h2>{{ __('sig.confirm_title') }}</h2>
    </div>
    <div class="card-body-custom">
      <p class="text-muted mb-4">{{ __('sig.confirm_msg') }}</p>

      <div class="info-row">
        <span class="info-label">{{ __('sig.cert_document') }}</span>
        <span class="fw-semibold">{{ $demande->document_titre }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">{{ __('sig.cert_signataire') }}</span>
        <span class="fw-semibold">{{ $demande->signataire_nom }}</span>
      </div>
      <div class="info-row">
        <span class="info-label">{{ __('sig.cert_date') }}</span>
        <span class="fw-semibold">{{ $demande->signe_at ? $demande->signe_at->format('d/m/Y à H:i:s') : '' }}</span>
      </div>
      @if($demande->signature_image)
      <div class="info-row">
        <span class="info-label">{{ __('sig.sign_draw') }}</span>
        <img src="{{ $demande->signature_image }}" class="sig-preview" alt="signature">
      </div>
      @endif
      @if($demande->sha256_signe)
      <div class="mt-4">
        <p class="text-muted mb-1" style="font-size:12px;">{{ __('sig.confirm_sha') }}</p>
        <div class="sha-block">{{ $demande->sha256_signe }}</div>
      </div>
      @endif
    </div>
  </div>
</div>
</body>
</html>
