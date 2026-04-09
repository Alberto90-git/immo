<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Préavis de fin de bail</title>
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
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255,255,255,0.15);
      border-radius: 20px;
      padding: 5px 14px;
      font-size: 13px;
      color: #fecaca;
      margin-top: 12px;
    }
    .body { background: #ffffff; padding: 36px 32px; }
    .body h2 { font-size: 20px; color: #1e293b; margin-bottom: 16px; }
    .body p { font-size: 15px; color: #475569; line-height: 1.7; margin-bottom: 12px; }
    /* Encadré date */
    .date-card {
      background: #fef2f2;
      border: 2px solid #ef4444;
      border-radius: 12px;
      padding: 24px;
      margin: 20px 0;
      text-align: center;
    }
    .date-card .dc-label { font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #991b1b; margin-bottom: 8px; }
    .date-card .dc-date { font-size: 26px; font-weight: 800; color: #dc2626; line-height: 1.2; }
    /* Info card */
    .info-card {
      margin: 20px 0;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      overflow: hidden;
    }
    .info-card .card-header {
      background: #f1f5f9;
      padding: 12px 18px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: #64748b;
      border-bottom: 1px solid #e2e8f0;
    }
    .info-row {
      display: flex;
      align-items: center;
      padding: 14px 18px;
      border-bottom: 1px solid #f1f5f9;
      gap: 12px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .ir-icon {
      width: 36px; height: 36px;
      border-radius: 8px;
      background: #fef2f2;
      display: flex; align-items: center; justify-content: center;
      font-size: 17px; flex-shrink: 0;
    }
    .info-row .ir-label { font-size: 12px; color: #94a3b8; margin-bottom: 2px; }
    .info-row .ir-value { font-size: 15px; color: #1e293b; font-weight: 600; }
    /* Message perso */
    .message-box {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      background: #f0f9ff;
      border-left: 4px solid #3b82f6;
      border-radius: 6px;
      padding: 14px 16px;
      margin: 20px 0;
    }
    .message-box .icon { font-size: 18px; line-height: 1; flex-shrink: 0; }
    .message-box p { font-size: 14px; color: #1e40af; margin: 0; line-height: 1.6; font-style: italic; }
    /* Alerte */
    .alert-box {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      background: #fef2f2;
      border-left: 4px solid #ef4444;
      border-radius: 6px;
      padding: 14px 16px;
      margin-top: 20px;
    }
    .alert-box .icon { font-size: 18px; line-height: 1; flex-shrink: 0; }
    .alert-box p { font-size: 13px; color: #991b1b; margin: 0; line-height: 1.6; }
    .divider { border: none; border-top: 1px solid #e2e8f0; margin: 28px 0; }
    .footer {
      background: #f8faff;
      border-radius: 0 0 12px 12px;
      padding: 20px 24px;
      text-align: center;
    }
    .footer p { font-size: 12px; color: #94a3b8; line-height: 1.8; }
    .footer a { color: #1a56db; text-decoration: none; }
  </style>
</head>
<body>
  <div class="wrapper">

    <div class="header">
      <div class="brand">Loka<span>tiv</span></div>
      <div class="subtitle">{{ $data['agence_nom'] }}</div>
      <div class="badge">📋 Préavis de fin de bail</div>
    </div>

    <div class="body">
      <h2>Bonjour {{ $data['destinataire_nom'] }},</h2>
      <p>
        Par la présente, <strong>{{ $data['agence_nom'] }}</strong> vous notifie officiellement la fin prochaine de votre contrat de bail.
      </p>

      @if (!empty($data['date_fin_bail']))
      <div class="date-card">
        <div class="dc-label">Date de fin de bail</div>
        <div class="dc-date">{{ $data['date_fin_bail'] }}</div>
      </div>
      @endif

      @if (!empty($data['logement']))
      <div class="info-card">
        <div class="card-header">Logement concerné</div>
        <div class="info-row">
          <div class="ir-icon">🏠</div>
          <div>
            <div class="ir-label">Adresse / Référence</div>
            <div class="ir-value">{{ $data['logement'] }}</div>
          </div>
        </div>
      </div>
      @endif

      <p>
        Conformément aux termes de votre contrat de location, vous êtes prié(e) de <strong>libérer les lieux et restituer les clés</strong> avant la date indiquée ci-dessus.
      </p>
      <p>
        Nous vous invitons à prendre contact avec notre agence afin de planifier l'état des lieux de sortie.
      </p>

      @if (!empty($data['message_personnalise']))
      <div class="message-box">
        <div class="icon">💬</div>
        <p>{{ $data['message_personnalise'] }}</p>
      </div>
      @endif

      <div class="alert-box">
        <div class="icon">⚠️</div>
        <p>Passé ce délai sans restitution des lieux, des frais supplémentaires pourront être engagés. Pour tout arrangement ou question, contactez directement votre agence.</p>
      </div>

      <hr class="divider">
      <p style="font-size:13px; color:#94a3b8;">
        Pour toute question, contactez <strong>{{ $data['agence_nom'] }}</strong> ou écrivez à
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
