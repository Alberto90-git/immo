<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2ff; margin: 0; padding: 30px 10px; color: #333; }
  .wrapper { max-width: 580px; margin: 0 auto; }
  .header { background: linear-gradient(135deg, #696cff, #5a5fba); color: white; padding: 30px 24px; text-align: center; border-radius: 12px 12px 0 0; }
  .header h2 { margin: 0 0 6px; font-size: 20px; font-weight: 700; }
  .header p { margin: 0; opacity: .85; font-size: 13px; }
  .body { background: #fff; padding: 32px 28px; color: #333; }
  .body p { font-size: 14px; line-height: 1.7; margin-bottom: 12px; color: #475569; }
  .btn-sign {
    display: block;
    margin: 24px auto;
    background: linear-gradient(135deg, #696cff, #5a5fba);
    color: white !important;
    text-decoration: none;
    padding: 14px 36px;
    border-radius: 30px;
    font-size: 15px;
    font-weight: bold;
    text-align: center;
    max-width: 280px;
  }
  .info-box {
    background: #f8f9ff;
    border-left: 4px solid #696cff;
    padding: 14px 16px;
    border-radius: 4px;
    margin: 16px 0;
    font-size: 13px;
    color: #475569;
    line-height: 1.7;
  }
  .warning {
    font-size: 12px;
    color: #94a3b8;
    margin-top: 16px;
    line-height: 1.6;
    word-break: break-all;
  }
  .warning a { color: #696cff; }
  .footer { background: #f8faff; padding: 16px 24px; text-align: center; font-size: 11px; color: #94a3b8; border-top: 1px solid #e2e8f0; border-radius: 0 0 12px 12px; }

  @media only screen and (max-width: 600px) {
    body { padding: 0 !important; }
    .wrapper { width: 100% !important; }
    .header { border-radius: 0 !important; padding: 24px 16px !important; }
    .body { padding: 20px 16px !important; }
    .footer { border-radius: 0 !important; }
    .btn-sign { max-width: 100% !important; width: 100% !important; }
  }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h2>{{ $agence }}</h2>
    <p>{{ __('mail.signature_edl.subtitle') }}</p>
  </div>
  <div class="body">
    <p>{{ __('mail.greeting', ['name' => $nom]) }}</p>
    <p>{!! __('mail.signature_edl.intro', ['typeLabel' => $typeLabel, 'date' => $dateEtat]) !!}</p>

    <div class="info-box">
      <strong>{{ __('mail.signature_edl.doc_label') }}</strong> {{ $typeLabel }}<br>
      <strong>{{ __('mail.signature_edl.date_label') }}</strong> {{ $dateEtat }}<br>
      <strong>{{ __('mail.signature_edl.agency_label') }}</strong> {{ $agence }}
    </div>

    <p>{{ __('mail.signature_edl.cta_text') }}</p>

    <a href="{{ $lien }}" class="btn-sign">{{ __('mail.signature_edl.btn_text') }}</a>

    <p class="warning">
      {{ __('mail.signature_edl.fallback') }}<br>
      <a href="{{ $lien }}">{{ $lien }}</a>
    </p>
  </div>
  <div class="footer">
    {{ __('mail.signature_edl.footer_sent', ['agence' => $agence]) }}<br>
    {{ __('mail.signature_edl.footer_ignore') }}
  </div>
</div>
</body>
</html>
