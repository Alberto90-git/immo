<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: 'Segoe UI', Arial, sans-serif; background: #f0f2ff; margin: 0; padding: 30px 10px; color: #333; }
  .wrapper { max-width: 580px; margin: 0 auto; }
  .header { background: linear-gradient(135deg, #22c55e, #16a34a); color: white; padding: 30px 24px; text-align: center; border-radius: 12px 12px 0 0; }
  .header h2 { margin: 0 0 6px; font-size: 20px; font-weight: 700; }
  .header p { margin: 0; opacity: .85; font-size: 13px; }
  .body { background: #fff; padding: 32px 28px; color: #333; }
  .body p { font-size: 14px; line-height: 1.7; margin-bottom: 12px; color: #475569; }
  .info-box {
    background: #f0fdf4; border-left: 4px solid #22c55e;
    padding: 14px 16px; border-radius: 4px; margin: 16px 0;
    font-size: 13px; color: #475569; line-height: 1.7;
  }
  .footer { background: #f8faff; padding: 16px 24px; text-align: center; font-size: 11px; color: #94a3b8;
    border-top: 1px solid #e2e8f0; border-radius: 0 0 12px 12px; }
  @media only screen and (max-width: 600px) {
    body { padding: 0 !important; }
    .wrapper { width: 100% !important; }
    .header, .footer { border-radius: 0 !important; }
    .header { padding: 24px 16px !important; }
    .body { padding: 20px 16px !important; }
  }
</style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h2>✅ {{ __('sig.confirm_title') }}</h2>
    <p>{{ __('sig.mail_signed_subject') }}</p>
  </div>
  <div class="body">
    <p>{{ __('mail.greeting', ['name' => $nom]) }}</p>
    <p>{{ __('sig.confirm_msg') }}</p>

    <div class="info-box">
      <strong>{{ __('sig.cert_document') }} :</strong> {{ $titre }}<br>
      <strong>{{ __('sig.cert_date') }} :</strong> {{ $date }}
    </div>

    <p style="color:#94a3b8; font-size:12px;">{{ __('sig.cert_legal_note') }}</p>
  </div>
  <div class="footer">
    {{ __('mail.footer_auto') }}<br>
    {{ __('mail.signature_edl.footer_ignore') }}
  </div>
</div>
</body>
</html>
