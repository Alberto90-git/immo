<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ __('mail.verify_email.title') }}</title>
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
    .badge-success {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255,255,255,0.15);
      border-radius: 20px;
      padding: 5px 14px;
      font-size: 13px;
      color: #bbf7d0;
      margin-top: 12px;
    }
    .body {
      background: #ffffff;
      padding: 36px 32px;
    }
    .body h2 {
      font-size: 20px;
      color: #1e293b;
      margin-bottom: 16px;
    }
    .body p {
      font-size: 15px;
      color: #475569;
      line-height: 1.7;
      margin-bottom: 12px;
    }
    .credentials-box {
      margin: 24px 0;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      overflow: hidden;
    }
    .credentials-box .box-header {
      background: #f1f5f9;
      padding: 12px 18px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: #64748b;
      border-bottom: 1px solid #e2e8f0;
    }
    .credential-row {
      display: flex;
      align-items: center;
      padding: 14px 18px;
      border-bottom: 1px solid #f1f5f9;
      gap: 12px;
    }
    .credential-row:last-child {
      border-bottom: none;
    }
    .credential-row .cr-icon {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      background: #eff6ff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 17px;
      flex-shrink: 0;
    }
    .credential-row .cr-label {
      font-size: 12px;
      color: #94a3b8;
      margin-bottom: 2px;
    }
    .credential-row .cr-value {
      font-size: 15px;
      color: #1e293b;
      font-weight: 600;
      word-break: break-all;
    }
    .btn-connect {
      display: block;
      width: 100%;
      text-align: center;
      background: linear-gradient(135deg, #1a56db 0%, #0e3a9c 100%);
      color: #fff !important;
      text-decoration: none;
      padding: 14px 24px;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      margin: 28px 0 20px;
      letter-spacing: 0.3px;
    }
    .info-box {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      background: #f0fdf4;
      border-left: 4px solid #22c55e;
      border-radius: 6px;
      padding: 14px 16px;
      margin-top: 8px;
    }
    .info-box .icon {
      font-size: 18px;
      line-height: 1;
      flex-shrink: 0;
    }
    .info-box p {
      font-size: 13px;
      color: #14532d;
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
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="header">
      <div class="brand">Loka<span>tiv</span></div>
      <p>{{ __('mail.verify_email.platform') }}</p>
      <div class="badge-success">{{ __('mail.verify_email.badge') }}</div>
    </div>

    <div class="body">
      <h2>{{ __('mail.verify_email.welcome', ['prenom' => $newuser['prenom']]) }}</h2>
      <p>
        {{ __('mail.verify_email.greeting', ['nom' => $newuser['nom'], 'prenom' => $newuser['prenom']]) }}
      </p>
      <p>
        {{ __('mail.verify_email.created') }}
      </p>

      <div class="credentials-box">
        <div class="box-header">{{ __('mail.verify_email.credentials_header') }}</div>
        <div class="credential-row">
          <div class="cr-icon">✉️</div>
          <div>
            <div class="cr-label">{{ __('mail.verify_email.email_label') }}</div>
            <div class="cr-value">{{ $newuser['email'] }}</div>
          </div>
        </div>
        <div class="credential-row">
          <div class="cr-icon">🔑</div>
          <div>
            <div class="cr-label">{{ __('mail.verify_email.password_label') }}</div>
            <div class="cr-value">{{ $newuser['password'] }}</div>
          </div>
        </div>
      </div>

      <a href="{{ request()->getSchemeAndHttpHost() . '/login/' }}" class="btn-connect">
        {{ __('mail.verify_email.btn_text') }}
      </a>

      <div class="info-box">
        <div class="icon">💡</div>
        <p>{!! __('mail.verify_email.security_note') !!}</p>
      </div>

      <hr class="divider">
      <p style="font-size:13px; color:#94a3b8;">
        {{ __('mail.verify_email.contact_note') }}
        <a href="mailto:support@lokativ.com" style="color:#1a56db;">support@lokativ.com</a>.
      </p>
    </div>

    <div class="footer">
      <p>
        {!! __('mail.footer_rights', ['year' => date('Y')]) !!}<br>
        <a href="#">{{ __('mail.verify_email.footer_privacy') }}</a>
        &nbsp;|&nbsp;
        <a href="#">{{ __('mail.verify_email.footer_help') }}</a>
      </p>
    </div>
  </div>
</body>
</html>
