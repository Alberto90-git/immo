<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('mail.paiement.title') }}</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background-color: #f0f2f5;
      padding: 30px 10px;
      color: #333;
      line-height: 1.6;
    }
    .wrapper { max-width: 580px; margin: 0 auto; }
    .header {
      background: linear-gradient(135deg, #1a56db 0%, #0e3a9c 100%);
      border-radius: 12px 12px 0 0;
      padding: 32px 24px;
      text-align: center;
    }
    .header .brand { font-size: 28px; font-weight: 700; color: #fff; letter-spacing: 1px; }
    .header .brand span { color: #93c5fd; }
    .header .subtitle { color: #bfdbfe; margin-top: 4px; font-size: 13px; }
    .header .badge {
      display: inline-block;
      background: rgba(255,255,255,0.15);
      border-radius: 20px;
      padding: 5px 14px;
      font-size: 13px;
      color: #fde68a;
      margin-top: 12px;
    }
    .body { background: #ffffff; padding: 36px 32px; }
    .body h2 { font-size: 20px; color: #1e293b; margin-bottom: 16px; }
    .body p { font-size: 15px; color: #475569; line-height: 1.7; margin-bottom: 12px; }
    .amount-card {
      background: #eff6ff;
      border: 2px solid #1a56db;
      border-radius: 12px;
      padding: 24px;
      margin: 20px 0;
      text-align: center;
    }
    .amount-card .ac-label {
      font-size: 12px; font-weight: 600; text-transform: uppercase;
      letter-spacing: 1px; color: #1e40af; margin-bottom: 8px;
    }
    .amount-card .ac-amount { font-size: 32px; font-weight: 800; color: #1a56db; line-height: 1; }
    .amount-card .ac-currency { font-size: 16px; font-weight: 600; color: #1d4ed8; margin-left: 4px; }
    .info-card { margin: 20px 0; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; }
    .info-card .card-header {
      background: #f1f5f9; padding: 12px 18px;
      font-size: 12px; font-weight: 600; text-transform: uppercase;
      letter-spacing: 1.5px; color: #64748b; border-bottom: 1px solid #e2e8f0;
    }
    .info-row {
      display: flex; align-items: center;
      padding: 12px 18px; border-bottom: 1px solid #f1f5f9; gap: 12px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .ir-icon {
      width: 36px; height: 36px; border-radius: 8px;
      background: #eff6ff; display: flex; align-items: center;
      justify-content: center; font-size: 17px; flex-shrink: 0;
    }
    .info-row .ir-label { font-size: 12px; color: #94a3b8; margin-bottom: 2px; }
    .info-row .ir-value { font-size: 14px; color: #1e293b; font-weight: 600; }
    .btn-wrap { text-align: center; margin: 24px 0; }
    .btn-primary {
      display: inline-block;
      background: linear-gradient(135deg, #1a56db 0%, #0e3a9c 100%);
      color: #fff !important; text-decoration: none;
      padding: 14px 36px; border-radius: 8px;
      font-size: 15px; font-weight: 600;
    }
    .alert-box {
      display: flex; gap: 12px; align-items: flex-start;
      background: #fffbeb; border-left: 4px solid #f59e0b;
      border-radius: 6px; padding: 14px 16px; margin: 20px 0;
    }
    .alert-box .icon { font-size: 18px; line-height: 1; flex-shrink: 0; }
    .alert-box p { font-size: 13px; color: #92400e; margin: 0; line-height: 1.6; }
    .divider { border: none; border-top: 1px solid #e2e8f0; margin: 24px 0; }
    .footer {
      background: #f8faff; border-radius: 0 0 12px 12px;
      padding: 20px 24px; text-align: center;
    }
    .footer p { font-size: 12px; color: #94a3b8; line-height: 1.8; }
    .footer a { color: #1a56db; text-decoration: none; }

    @media only screen and (max-width: 600px) {
      body { padding: 0 !important; }
      .wrapper { width: 100% !important; }
      .header { border-radius: 0 !important; padding: 24px 16px !important; }
      .body { padding: 24px 16px !important; }
      .footer { border-radius: 0 !important; }
      .body h2 { font-size: 18px !important; }
      .amount-card .ac-amount { font-size: 26px !important; }
      .info-row { display: block !important; padding: 12px 14px !important; }
      .info-row .ir-icon { margin-bottom: 8px; }
      .alert-box { display: block !important; }
      .alert-box .icon { display: block; margin-bottom: 8px; }
      .btn-primary { display: block !important; width: 100% !important; text-align: center; }
    }
  </style>
</head>
<body>
  <div class="wrapper">

    <div class="header">
      <div class="brand">Loka<span>tiv</span></div>
      <div class="subtitle">{{ __('mail.paiement.subtitle') }}</div>
      <div class="badge">{{ __('mail.paiement.badge') }}</div>
    </div>

    <div class="body">
      <h2>{{ __('mail.greeting', ['name' => $client['nom_prenom']]) }}</h2>
      <p>{{ __('mail.paiement.intro') }}</p>

      <div class="amount-card">
        <div class="ac-label">{{ __('mail.paiement.amount_label') }}</div>
        <div>
          <span class="ac-amount">{{ number_format($client['montant'] * $client['nombre_mois'], 0, ',', ' ') }}</span>
          <span class="ac-currency">{{ $client['devise'] ?? get_symbole_devise('XOF') }}</span>
        </div>
      </div>

      <div class="info-card">
        <div class="card-header">{{ __('mail.paiement.plan_header') }}</div>
        <div class="info-row">
          <div class="ir-icon">👤</div>
          <div>
            <div class="ir-label">{{ __('mail.paiement.client_label') }}</div>
            <div class="ir-value">{{ $client['nom_prenom'] }}</div>
          </div>
        </div>
        <div class="info-row">
          <div class="ir-icon">📦</div>
          <div>
            <div class="ir-label">{{ __('mail.paiement.plan_label') }}</div>
            <div class="ir-value">{{ $client['plan_key'] }}</div>
          </div>
        </div>
        <div class="info-row">
          <div class="ir-icon">📅</div>
          <div>
            <div class="ir-label">{{ __('mail.paiement.duration_label') }}</div>
            <div class="ir-value">{{ __('mail.paiement.duration_value', ['n' => $client['nombre_mois']]) }}</div>
          </div>
        </div>
        <div class="info-row">
          <div class="ir-icon">🔑</div>
          <div>
            <div class="ir-label">{{ __('mail.paiement.code_label') }}</div>
            <div class="ir-value">{{ $client['code_inscription'] }}</div>
          </div>
        </div>
        <div class="info-row">
          <div class="ir-icon">📱</div>
          <div>
            <div class="ir-label">{{ __('mail.paiement.mobile_label') }}</div>
            <div class="ir-value">+229 01 61 08 22 60</div>
          </div>
        </div>
        <div class="info-row">
          <div class="ir-icon">📆</div>
          <div>
            <div class="ir-label">{{ __('mail.paiement.date_label') }}</div>
            <div class="ir-value">{{ $client['created'] }}</div>
          </div>
        </div>
      </div>

      <div class="alert-box">
        <div class="icon">⚠️</div>
        <p>{{ __('mail.paiement.alert') }}</p>
      </div>

      <div class="btn-wrap">
        <a href="{{ request()->getSchemeAndHttpHost() }}" class="btn-primary">{{ __('mail.paiement.btn_text') }}</a>
      </div>

      <hr class="divider">
      <p style="font-size:13px; color:#94a3b8;">
        {{ __('mail.paiement.contact_note') }}
        <a href="mailto:support@lokativ.com" style="color:#1a56db;">support@lokativ.com</a>.
      </p>
    </div>

    <div class="footer">
      <p>
        {{ __('mail.footer_rights', ['year' => date('Y')]) }}<br>
        {{ __('mail.footer_auto') }}
      </p>
    </div>

  </div>
</body>
</html>
