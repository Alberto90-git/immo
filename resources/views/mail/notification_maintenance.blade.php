<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ __('mail.maintenance.title') }}</title>
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
      background: linear-gradient(135deg, #1a56db 0%, #0e3a9c 100%);
      border-radius: 12px 12px 0 0;
      padding: 32px 24px;
      text-align: center;
    }
    .header .brand { font-size: 28px; font-weight: 700; color: #fff; letter-spacing: 1px; }
    .header .brand span { color: #93c5fd; }
    .header .subtitle { color: #bfdbfe; margin-top: 4px; font-size: 13px; }
    .header .badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
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
    .status-card {
      border-radius: 12px;
      padding: 24px;
      margin: 24px 0;
      text-align: center;
    }
    .status-card.en_cours  { background: #fffbeb; border: 2px solid #f59e0b; }
    .status-card.resolu    { background: #f0fdf4; border: 2px solid #22c55e; }
    .status-card.cloture   { background: #eff6ff; border: 2px solid #3b82f6; }
    .status-card .status-icon { font-size: 36px; margin-bottom: 8px; }
    .status-card .status-label {
      font-size: 13px; font-weight: 700; text-transform: uppercase;
      letter-spacing: 1px; margin-bottom: 6px;
    }
    .status-card.en_cours  .status-label { color: #92400e; }
    .status-card.resolu    .status-label { color: #166534; }
    .status-card.cloture   .status-label { color: #1e40af; }
    .status-card .status-text { font-size: 15px; font-weight: 600; }
    .status-card.en_cours  .status-text { color: #b45309; }
    .status-card.resolu    .status-text { color: #15803d; }
    .status-card.cloture   .status-text { color: #1d4ed8; }
    .info-card {
      margin: 20px 0;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      overflow: hidden;
    }
    .info-card .card-header-row {
      background: #f1f5f9;
      padding: 12px 18px;
      font-size: 12px; font-weight: 700; text-transform: uppercase;
      letter-spacing: 1.5px; color: #64748b; border-bottom: 1px solid #e2e8f0;
    }
    .info-row {
      display: flex; align-items: center;
      padding: 14px 18px; border-bottom: 1px solid #f1f5f9; gap: 12px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .ir-icon {
      width: 36px; height: 36px; border-radius: 8px;
      background: #f1f5f9; display: flex; align-items: center;
      justify-content: center; font-size: 17px; flex-shrink: 0;
    }
    .info-row .ir-label { font-size: 12px; color: #94a3b8; margin-bottom: 2px; }
    .info-row .ir-value { font-size: 15px; color: #1e293b; font-weight: 600; }
    .note-box {
      display: flex; gap: 12px; align-items: flex-start;
      background: #f0f9ff; border-left: 4px solid #3b82f6;
      border-radius: 6px; padding: 14px 16px; margin-top: 20px;
    }
    .note-box p { font-size: 13px; color: #1e40af; margin: 0; line-height: 1.6; }
    .divider { border: none; border-top: 1px solid #e2e8f0; margin: 28px 0; }
    .footer {
      background: #f8faff; border-radius: 0 0 12px 12px;
      padding: 20px 24px; text-align: center;
    }
    .footer p { font-size: 12px; color: #94a3b8; line-height: 1.8; }

  @media only screen and (max-width: 600px) {
    body { padding: 0 !important; }
    .wrapper { width: 100% !important; }
    .header { border-radius: 0 !important; padding: 24px 16px !important; }
    .body { padding: 24px 16px !important; }
    .footer { border-radius: 0 !important; }
    .body h2 { font-size: 18px !important; }
    .status-card { padding: 18px 12px !important; }
    .info-row { display: block !important; padding: 12px 14px !important; }
    .info-row .ir-icon { margin-bottom: 8px; }
    .note-box { display: block !important; }
  }
  </style>
</head>
<body>
  <div class="wrapper">

    <div class="header">
      <div class="brand">Loka<span>tiv</span></div>
      <div class="subtitle">{{ config('app.name') }}</div>
      <div class="badge">{{ __('mail.maintenance.badge') }}</div>
    </div>

    <div class="body">
      <h2>{{ __('mail.greeting', ['name' => $locataire_nom]) }}</h2>
      <p>{{ __('mail.maintenance.intro') }}</p>

      {{-- Status card --}}
      <div class="status-card {{ $statut }}">
        <div class="status-icon">
          @if($statut === 'en_cours') ⏳
          @elseif($statut === 'resolu') ✅
          @else 🔒
          @endif
        </div>
        <div class="status-label">
          @if($statut === 'en_cours') {{ __('mail.maintenance.status_en_cours') }}
          @elseif($statut === 'resolu') {{ __('mail.maintenance.status_resolu') }}
          @else {{ __('mail.maintenance.status_cloture') }}
          @endif
        </div>
        <div class="status-text">{{ $message }}</div>
      </div>

      {{-- Ticket details --}}
      <div class="info-card">
        <div class="card-header-row">{{ __('mail.maintenance.status_header') }}</div>
        <div class="info-row">
          <div class="ir-icon">🔧</div>
          <div>
            <div class="ir-label">{{ __('mail.maintenance.status_ticket') }}</div>
            <div class="ir-value">{{ $ticket_titre }}</div>
          </div>
        </div>
        @if($logement)
        <div class="info-row">
          <div class="ir-icon">🏠</div>
          <div>
            <div class="ir-label">{{ __('mail.maintenance.status_logement') }}</div>
            <div class="ir-value">{{ $logement }}</div>
          </div>
        </div>
        @endif
        @if($categorie)
        <div class="info-row">
          <div class="ir-icon">📋</div>
          <div>
            <div class="ir-label">{{ __('mail.maintenance.status_categorie') }}</div>
            <div class="ir-value">{{ $categorie }}</div>
          </div>
        </div>
        @endif
      </div>

      <div class="note-box">
        <p>ℹ️ &nbsp;{{ __('mail.maintenance.note') }}</p>
      </div>

      <hr class="divider">
      <p style="font-size:13px; color:#94a3b8; text-align:center;">
        {{ __('mail.footer_rights', ['year' => date('Y')]) }}
      </p>
    </div>

    <div class="footer">
      <p>{{ __('mail.footer_auto') }}</p>
    </div>

  </div>
</body>
</html>
