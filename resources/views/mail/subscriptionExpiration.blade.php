<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notification d'expiration d'abonnement</title>
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

    /* Alerte expiration */
    .expiry-card {
      border-radius: 12px;
      padding: 22px 20px;
      margin: 20px 0;
    }
    .expiry-card.warning  { background: #fef3c7; border: 2px solid #f59e0b; }
    .expiry-card.orange   { background: #fff7ed; border: 2px solid #f97316; }
    .expiry-card.danger   { background: #fef2f2; border: 2px solid #ef4444; }
    .expiry-card .ec-icon { font-size: 32px; text-align: center; margin-bottom: 10px; }
    .expiry-card .ec-title {
      font-size: 15px; font-weight: 700; text-align: center; margin-bottom: 6px;
    }
    .expiry-card.warning  .ec-title { color: #92400e; }
    .expiry-card.orange   .ec-title { color: #c2410c; }
    .expiry-card.danger   .ec-title { color: #dc2626; }

    /* Info card */
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
      background: #f1f5f9; display: flex; align-items: center;
      justify-content: center; font-size: 17px; flex-shrink: 0;
    }
    .info-row .ir-label { font-size: 12px; color: #94a3b8; margin-bottom: 2px; }
    .info-row .ir-value { font-size: 14px; color: #1e293b; font-weight: 600; }

    /* Bouton */
    .btn-wrap { text-align: center; margin: 24px 0; }
    .btn-primary {
      display: inline-block;
      background: linear-gradient(135deg, #1a56db 0%, #0e3a9c 100%);
      color: #fff !important; text-decoration: none;
      padding: 14px 36px; border-radius: 8px;
      font-size: 15px; font-weight: 600;
    }

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
      .info-row { display: block !important; padding: 12px 14px !important; }
      .info-row .ir-icon { margin-bottom: 8px; }
      .btn-primary { display: block !important; width: 100% !important; text-align: center; }
    }
  </style>
</head>
<body>
  @php
    $days = $notificationData['jours_restants'];
    $nom  = $notificationData['destinataire_nom'] ?? $notificationData['entreprise'];

    if ($days < 0) {
        $cardClass = 'danger';
        $icon      = '🔴';
        $title     = 'Abonnement expiré';
        $alertMsg  = 'Votre abonnement a expiré depuis ' . abs($days) . ' jour(s). Vos accès seront limités tant que vous n\'aurez pas renouvelé.';
    } elseif ($days == 0) {
        $cardClass = 'danger';
        $icon      = '🚨';
        $title     = 'Expiration aujourd\'hui';
        $alertMsg  = 'Votre abonnement expire aujourd\'hui. Renouvelez-le maintenant pour éviter toute interruption.';
    } elseif ($days == 1) {
        $cardClass = 'danger';
        $icon      = '⚠️';
        $title     = 'Expiration demain';
        $alertMsg  = 'Votre abonnement expire demain. Pensez à le renouveler dès maintenant.';
    } elseif ($days <= 3) {
        $cardClass = 'orange';
        $icon      = '⏰';
        $title     = 'Expiration dans ' . $days . ' jours';
        $alertMsg  = 'Votre abonnement expire dans ' . $days . ' jours. Pensez au renouvellement.';
    } else {
        $cardClass = 'warning';
        $icon      = '📅';
        $title     = 'Expiration dans ' . $days . ' jours';
        $alertMsg  = 'Votre abonnement expire dans ' . $days . ' jours. Anticipez le renouvellement.';
    }
  @endphp

  <div class="wrapper">

    <div class="header">
      <div class="brand">Loka<span>tiv</span></div>
      <div class="subtitle">Plateforme de gestion immobilière</div>
      <div class="badge">🔔 Abonnement</div>
    </div>

    <div class="body">
      <h2>Bonjour {{ $nom }},</h2>
      <p>{{ $alertMsg }}</p>

      <div class="expiry-card {{ $cardClass }}">
        <div class="ec-icon">{{ $icon }}</div>
        <div class="ec-title">{{ $title }}</div>
      </div>

      <div class="info-card">
        <div class="card-header">Détails de l'abonnement</div>
        <div class="info-row">
          <div class="ir-icon">🏢</div>
          <div>
            <div class="ir-label">Entreprise</div>
            <div class="ir-value">{{ $notificationData['entreprise'] }}</div>
          </div>
        </div>
        <div class="info-row">
          <div class="ir-icon">📦</div>
          <div>
            <div class="ir-label">Plan actuel</div>
            <div class="ir-value">{{ $notificationData['plan_nom'] }}</div>
          </div>
        </div>
        <div class="info-row">
          <div class="ir-icon">📆</div>
          <div>
            <div class="ir-label">Date d'expiration</div>
            <div class="ir-value">{{ $notificationData['date_expiration'] }}</div>
          </div>
        </div>
        <div class="info-row">
          <div class="ir-icon">⏳</div>
          <div>
            @if($days >= 0)
              <div class="ir-label">Jours restants</div>
              <div class="ir-value">{{ $days }} jour(s)</div>
            @else
              <div class="ir-label">Expiré depuis</div>
              <div class="ir-value">{{ abs($days) }} jour(s)</div>
            @endif
          </div>
        </div>
      </div>

      <div class="btn-wrap">
        <a href="{{ config('app.url') }}/login" class="btn-primary">Renouveler mon abonnement</a>
      </div>

      <hr class="divider">
      <p style="font-size:13px; color:#94a3b8;">
        Pour toute question ou assistance, contactez notre support à
        <a href="mailto:support@lokativ.com" style="color:#1a56db;">support@lokativ.com</a>.
      </p>
    </div>

    <div class="footer">
      <p>
        &copy; {{ date('Y') }} Lokativ. Tous droits réservés.<br>
        Ce message a été envoyé automatiquement — merci de ne pas y répondre directement.
      </p>
    </div>

  </div>
</body>
</html>
