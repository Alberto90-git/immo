<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Réinitialisation de mot de passe – Lokativ</title>
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
    /* Reset button block */
    .reset-box {
      margin: 28px 0;
      text-align: center;
      background: #f8faff;
      border: 2px dashed #93c5fd;
      border-radius: 12px;
      padding: 32px 24px;
    }
    .reset-box .label {
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: #64748b;
      margin-bottom: 20px;
    }
    .reset-btn {
      display: inline-block;
      background: linear-gradient(135deg, #1a56db 0%, #0e3a9c 100%);
      color: #ffffff !important;
      text-decoration: none;
      padding: 14px 36px;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      letter-spacing: 0.3px;
      box-shadow: 0 4px 14px rgba(26,86,219,0.35);
    }
    .reset-box .expiry {
      margin-top: 18px;
      font-size: 13px;
      color: #94a3b8;
    }
    .reset-box .expiry strong {
      color: #ef4444;
    }
    /* Fallback URL */
    .fallback-url {
      background: #f1f5f9;
      border-radius: 8px;
      padding: 12px 16px;
      margin-top: 20px;
      word-break: break-all;
      font-size: 12px;
      color: #64748b;
      line-height: 1.7;
    }
    .fallback-url a {
      color: #1a56db;
      text-decoration: none;
    }
    /* Warning box */
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
    .reset-box { padding: 24px 14px !important; }
    .reset-btn {
      display: block !important;
      width: 100% !important;
      box-sizing: border-box !important;
      text-align: center;
    }
    .warning-box { display: block !important; }
    .warning-box .icon { display: block; margin-bottom: 8px; }
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
      <h2>Réinitialisation de votre mot de passe</h2>
      <p>Bonjour {{ $user ? $user['email'] : '' }},</p>
      <p>
        Nous avons reçu une demande de réinitialisation du mot de passe associé à votre compte Lokativ.
        Cliquez sur le bouton ci-dessous pour créer un nouveau mot de passe.
      </p>

      <div class="reset-box">
        <div class="label">Lien de réinitialisation</div>
        <a href="{{ request()->getSchemeAndHttpHost().'/forgot-password/'.$user['token'] }}" class="reset-btn">
          Réinitialiser mon mot de passe
        </a>
        <div class="expiry">Ce lien expire dans <strong>60 minutes</strong></div>
      </div>

      <p style="font-size:14px; color:#64748b;">
        Si le bouton ne fonctionne pas, copiez et collez ce lien dans votre navigateur :
      </p>
      <div class="fallback-url">
        <a href="{{ request()->getSchemeAndHttpHost().'/forgot-password/'.$user['token'] }}">
          {{ request()->getSchemeAndHttpHost().'/forgot-password/'.$user['token'] }}
        </a>
      </div>

      <div class="warning-box">
        <div class="icon">⚠️</div>
        <p>
          <strong>Attention :</strong> Si vous n'avez pas demandé la réinitialisation de votre mot de passe,
          ignorez simplement cet e-mail — votre mot de passe actuel reste inchangé.
          Ne partagez jamais ce lien avec qui que ce soit.
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
