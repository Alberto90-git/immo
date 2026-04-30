<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $sujet }}</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background-color: #f0f2f5;
      padding: 30px 10px;
      color: #333;
    }
    .wrapper { max-width: 580px; margin: 0 auto; }
    .header {
      background: linear-gradient(135deg, {{ $headerFrom }} 0%, {{ $headerTo }} 100%);
      border-radius: 12px 12px 0 0;
      padding: 32px 24px;
      text-align: center;
    }
    .header .brand { font-size: 28px; font-weight: 700; color: #fff; letter-spacing: 1px; }
    .header .brand span { color: #93c5fd; }
    .header .subtitle { color: #bfdbfe; margin-top: 4px; font-size: 13px; }
    .badge {
      display: inline-block;
      background: rgba(255,255,255,0.15);
      border-radius: 20px;
      padding: 5px 14px;
      font-size: 13px;
      color: {{ $badgeColor }};
      margin-top: 12px;
    }
    .body { background: #ffffff; padding: 36px 32px; }
    .body h2 { font-size: 20px; color: #1e293b; margin-bottom: 16px; }
    .body p { font-size: 15px; color: #475569; line-height: 1.7; margin-bottom: 12px; }
    .highlight-card {
      background: {{ $cardBg }};
      border: 2px solid {{ $cardBorder }};
      border-radius: 12px;
      padding: 22px 24px;
      margin: 20px 0;
    }
    .highlight-card .hc-icon { font-size: 28px; margin-bottom: 10px; display: block; text-align: center; }
    .highlight-card .hc-label {
      font-size: 11px; font-weight: 700; text-transform: uppercase;
      letter-spacing: 1.2px; color: {{ $cardLabelColor }}; margin-bottom: 6px; text-align: center;
    }
    .highlight-card .hc-value {
      font-size: 28px; font-weight: 800; color: {{ $cardValueColor }};
      line-height: 1.1; text-align: center;
    }
    .highlight-card .hc-sub {
      font-size: 13px; color: {{ $cardSubColor }}; margin-top: 6px; text-align: center;
    }
    .message-paragraphs { margin: 20px 0; }
    .message-paragraphs p {
      font-size: 15px; color: #475569; line-height: 1.7; margin-bottom: 10px;
    }
    .alert-box {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      background: {{ $alertBg }};
      border-left: 4px solid {{ $alertBorder }};
      border-radius: 6px;
      padding: 14px 16px;
      margin-top: 20px;
    }
    .alert-box .icon { font-size: 18px; line-height: 1; flex-shrink: 0; }
    .alert-box p { font-size: 13px; color: {{ $alertTextColor }}; margin: 0; line-height: 1.6; }
    .divider { border: none; border-top: 1px solid #e2e8f0; margin: 28px 0; }
    .footer {
      background: #f8faff;
      border-radius: 0 0 12px 12px;
      padding: 20px 24px;
      text-align: center;
    }
    .footer p { font-size: 12px; color: #94a3b8; line-height: 1.8; }
    .footer a { color: #1a56db; text-decoration: none; }

    @media only screen and (max-width: 600px) {
      body { padding: 0 !important; }
      .wrapper { width: 100% !important; }
      .header { border-radius: 0 !important; padding: 24px 16px !important; }
      .body { padding: 24px 16px !important; }
      .footer { border-radius: 0 !important; }
      .highlight-card .hc-value { font-size: 22px !important; }
      .alert-box { display: block !important; }
      .alert-box .icon { display: block; margin-bottom: 8px; }
    }
  </style>
</head>
<body>
  <div class="wrapper">

    <div class="header">
      <div class="brand">Loka<span>tiv</span></div>
      <div class="subtitle">{{ __('mail.automation.subtitle') }}</div>
      <div class="badge">{{ $badgeIcon }} {{ $badgeLabel }}</div>
    </div>

    <div class="body">
      <h2>{{ __('mail.greeting', ['name' => $nom]) }}</h2>

      <div class="message-paragraphs">
        @foreach($paragraphes as $para)
          <p>{{ $para }}</p>
        @endforeach
      </div>

      @if($highlightValue)
      <div class="highlight-card">
        <span class="hc-icon">{{ $highlightIcon }}</span>
        <div class="hc-label">{{ $highlightLabel }}</div>
        <div class="hc-value">{{ $highlightValue }}</div>
        @if($highlightSub)
        <div class="hc-sub">{{ $highlightSub }}</div>
        @endif
      </div>
      @endif

      <div class="alert-box">
        <div class="icon">{{ $alertIcon }}</div>
        <p>{{ $alertText }}</p>
      </div>

      <hr class="divider">
      <p style="font-size:13px; color:#94a3b8;">
        {{ __('mail.auto_note') }}
      </p>
    </div>

    <div class="footer">
      <p>
        {{ __('mail.footer_rights', ['year' => date('Y')]) }}<br>
        {{ __('mail.automation.footer_auto') }}
      </p>
    </div>

  </div>
</body>
</html>
