<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $data['type_document_label'] }}</title>
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
      color: #e0f2fe;
      margin-top: 12px;
    }
    .body { background: #ffffff; padding: 36px 32px; }
    .body h2 { font-size: 20px; color: #1e293b; margin-bottom: 16px; }
    .body p { font-size: 15px; color: #475569; line-height: 1.7; margin-bottom: 12px; }
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
      background: #eff6ff;
      display: flex; align-items: center; justify-content: center;
      font-size: 17px; flex-shrink: 0;
    }
    .info-row .ir-label { font-size: 12px; color: #94a3b8; margin-bottom: 2px; }
    .info-row .ir-value { font-size: 15px; color: #1e293b; font-weight: 600; }
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
    .info-box {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      background: #f0fdf4;
      border-left: 4px solid #22c55e;
      border-radius: 6px;
      padding: 14px 16px;
      margin-top: 20px;
    }
    .info-box .icon { font-size: 18px; line-height: 1; flex-shrink: 0; }
    .info-box p { font-size: 13px; color: #14532d; margin: 0; line-height: 1.6; }
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
      <div class="badge">📎 Document joint</div>
    </div>

    <div class="body">
      <h2>Bonjour {{ $data['destinataire_nom'] }},</h2>
      <p>
        Veuillez trouver ci-joint le document suivant, transmis par <strong>{{ $data['agence_nom'] }}</strong>.
      </p>

      <div class="info-card">
        <div class="card-header">Document transmis</div>
        <div class="info-row">
          <div class="ir-icon">📄</div>
          <div>
            <div class="ir-label">Type de document</div>
            <div class="ir-value">{{ $data['type_document_label'] }}</div>
          </div>
        </div>
        <div class="info-row">
          <div class="ir-icon">🏢</div>
          <div>
            <div class="ir-label">Envoyé par</div>
            <div class="ir-value">{{ $data['agence_nom'] }}</div>
          </div>
        </div>
      </div>

      @if (!empty($data['message_personnalise']))
      <div class="message-box">
        <div class="icon">💬</div>
        <p>{{ $data['message_personnalise'] }}</p>
      </div>
      @endif

      <div class="info-box">
        <div class="icon">📎</div>
        <p>Le document est disponible en <strong>pièce jointe PDF</strong> de cet email. Conservez-le pour vos archives.</p>
      </div>

      <hr class="divider">
      <p style="font-size:13px; color:#94a3b8;">
        Pour toute question, contactez directement votre agence ou écrivez à
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
