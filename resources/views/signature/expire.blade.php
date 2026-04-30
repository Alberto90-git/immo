<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('sig.expire_title') }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
    body { background: #f0f2ff; font-family: 'Segoe UI', sans-serif; }
    .wrapper { max-width: 520px; margin: 80px auto; padding: 0 16px; text-align: center; }
    .icon-wrap { font-size: 64px; margin-bottom: 16px; }
    .card { border-radius: 16px; border: none; box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 40px 32px; }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="card">
    @if($demande->statut === 'signe')
      <div class="icon-wrap">✅</div>
      <h3 class="fw-bold mb-3">{{ __('sig.statut_signe') }}</h3>
      <p class="text-muted">{{ __('sig.expire_msg_signed') }}</p>
    @elseif($demande->statut === 'annule')
      <div class="icon-wrap">🚫</div>
      <h3 class="fw-bold mb-3">{{ __('sig.statut_annule') }}</h3>
      <p class="text-muted">{{ __('sig.expire_msg_cancel') }}</p>
    @else
      <div class="icon-wrap">⏰</div>
      <h3 class="fw-bold mb-3">{{ __('sig.expire_title') }}</h3>
      <p class="text-muted">{{ __('sig.expire_msg_expire') }}</p>
    @endif
  </div>
</div>
</body>
</html>
