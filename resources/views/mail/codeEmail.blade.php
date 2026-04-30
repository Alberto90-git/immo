<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ __('mail.code_email.subject') }}</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background-color: #f0f2f5;
      padding: 30px 10px;
      color: #333;
    }
    .wrapper {
      max-width: 580px;
      margin: 0 auto;
    }
    .header {
      background: linear-gradient(135deg, #1a56db 0%, #0e3a9c 100%);
      border-radius: 12px 12px 0 0;
      padding: 32px 24px;
      text-align: center;
    }
    .header .brand {
      font-size: 28px;
      font-weight: 700;
      color: #fff;
      letter-spacing: 1px;
    }
    .header .brand span {
      color: #93c5fd;
    }
    .header p {
      color: #bfdbfe;
      margin-top: 6px;
      font-size: 14px;
    }
    .body {
      background: #ffffff;
      padding: 36px 32px;
    }
    .body h2 {
      font-size: 20px;
      color: #1e293b;
      margin-bottom: 12px;
    }
    .body p {
      font-size: 15px;
      color: #475569;
      line-height: 1.7;
      margin-bottom: 12px;
    }
    .otp-box {
      margin: 28px 0;
      text-align: center;
      background: #f8faff;
      border: 2px dashed #93c5fd;
      border-radius: 12px;
      padding: 28px 20px;
    }
    .otp-box .label {
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: #64748b;
      margin-bottom: 12px;
    }
    .otp-code {
      font-size: 48px;
      font-weight: 800;
      letter-spacing: 12px;
      color: #1a56db;
      line-height: 1;
    }
    .otp-box .expiry {
      margin-top: 14px;
      font-size: 13px;
      color: #94a3b8;
    }
    .otp-box .expiry strong {
      color: #ef4444;
    }
    .warning-box {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      background: #fff7ed;
      border-left: 4px solid #f97316;
      border-radius: 6px;
      padding: 14px 16px;
      margin-top: 24px;
    }
    .warning-box .icon {
      font-size: 20px;
      line-height: 1;
      flex-shrink: 0;
    }
    .warning-box p {
      font-size: 13px;
      color: #7c2d12;
      margin: 0;
      line-height: 1.6;
    }
    .divider {
      border: none;
      border-top: 1px solid #e2e8f0;
      margin: 28px 0;
    }
    .footer {
      background: #f8faff;
      border-radius: 0 0 12px 12px;
      padding: 20px 24px;
      text-align: center;
    }
    .footer p {
      font-size: 12px;
      color: #94a3b8;
      line-height: 1.8;
    }
    .footer a {
      color: #1a56db;
      text-decoration: none;
    }

  @media only screen and (max-width: 600px) {
    body { padding: 0 !important; }
    .wrapper { width: 100% !important; }
    .header { border-radius: 0 !important; padding: 24px 16px !important; }
    .body { padding: 24px 16px !important; }
    .footer { border-radius: 0 !important; }
    .body h2 { font-size: 18px !important; }
    .otp-code { font-size: 34px !important; letter-spacing: 6px !important; }
    .warning-box { display: block !important; }
    .warning-box .icon { display: block; margin-bottom: 8px; }
  }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="header">
      <div class="brand">Loka<span>tiv</span></div>
      <p>{{ __('mail.code_email.subtitle') }}</p>
    </div>

    <div class="body">
      <h2>{{ __('mail.code_email.title') }}</h2>
      <p>{{ __('mail.code_email.greeting') }}</p>
      <p>{{ __('mail.code_email.intro') }}</p>

      <div class="otp-box">
        <div class="label">{{ __('mail.code_email.code_label') }}</div>
        <div class="otp-code">{{ $userinfo['code_login'] }}</div>
        <div class="expiry">{!! __('mail.code_email.expiry') !!}</div>
      </div>

      <p>{{ __('mail.code_email.instructions') }}</p>

      <div class="warning-box">
        <div class="icon">⚠️</div>
        <p>{!! __('mail.code_email.warning') !!}</p>
      </div>

      <hr class="divider">
      <p style="font-size:13px; color:#94a3b8;">
        {{ __('mail.code_email.contact_note') }}
        <a href="mailto:support@lokativ.com" style="color:#1a56db;">support@lokativ.com</a>.
      </p>
    </div>

    <div class="footer">
      <p>
        {{ __('mail.footer_rights', ['year' => date('Y')]) }}<br>
        <a href="#">{{ __('mail.code_email.privacy') }}</a> &nbsp;|&nbsp; <a href="#">{{ __('mail.code_email.help') }}</a>
      </p>
    </div>
  </div>
</body>
</html>
