<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Code de vérification – Lokativ</title>
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
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="header">
      <div class="brand">Loka<span>tiv</span></div>
      <p>Plateforme de gestion immobilière</p>
    </div>

    <div class="body">
      <h2>Vérification de votre identité</h2>
      <p>Bonjour,</p>
      <p>
        Une tentative de connexion a été détectée sur votre compte. Pour confirmer que c'est bien vous, veuillez utiliser le code de vérification ci-dessous.
      </p>

      <div class="otp-box">
        <div class="label">Votre code de connexion</div>
        <div class="otp-code">{{ $userinfo['code_login'] }}</div>
        <div class="expiry">Ce code expire dans <strong>10 minutes</strong></div>
      </div>

      <p>Saisissez ce code dans la fenêtre de connexion pour accéder à votre espace Lokativ.</p>

      <div class="warning-box">
        <div class="icon">⚠️</div>
        <p>
          <strong>Attention :</strong> Ne partagez jamais ce code avec qui que ce soit, y compris notre équipe support. Lokativ ne vous demandera jamais votre code de vérification.
          Si vous n'avez pas initié cette connexion, ignorez cet email et sécurisez votre compte.
        </p>
      </div>

      <hr class="divider">
      <p style="font-size:13px; color:#94a3b8;">
        Si vous avez des questions, contactez notre support à
        <a href="mailto:support@lokativ.com" style="color:#1a56db;">support@lokativ.com</a>.
      </p>
    </div>

    <div class="footer">
      <p>
        &copy; {{ date('Y') }} Lokativ. Tous droits réservés.<br>
        <a href="#">Politique de confidentialité</a> &nbsp;|&nbsp; <a href="#">Aide</a>
      </p>
    </div>
  </div>
</body>
</html>
