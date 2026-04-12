<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 0; }
  .container { max-width: 600px; margin: 30px auto; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
  .header { background: linear-gradient(135deg, #696cff, #5a5fba); color: white; padding: 30px 24px; text-align: center; }
  .header h2 { margin: 0 0 6px; font-size: 20px; }
  .header p { margin: 0; opacity: .85; font-size: 13px; }
  .body { padding: 24px; color: #333; }
  .body p { font-size: 14px; line-height: 1.6; }
  .btn-sign { display: block; width: fit-content; margin: 24px auto; background: #696cff; color: white; text-decoration: none; padding: 14px 36px; border-radius: 30px; font-size: 15px; font-weight: bold; }
  .info-box { background: #f8f9fa; border-left: 4px solid #696cff; padding: 12px 16px; border-radius: 4px; margin: 16px 0; font-size: 13px; }
  .footer { background: #f8f9fa; padding: 16px 24px; text-align: center; font-size: 11px; color: #999; border-top: 1px solid #eee; }
  .warning { font-size: 11px; color: #999; margin-top: 12px; }
</style>
</head>
<body>
<div class="container">
  <div class="header">
    <h2>{{ $agence }}</h2>
    <p>État des lieux – Demande de signature</p>
  </div>
  <div class="body">
    <p>Bonjour <strong>{{ $nom }}</strong>,</p>
    <p>
      Votre agence vous invite à signer votre <strong>{{ $typeLabel }}</strong>
      établi le <strong>{{ $dateEtat }}</strong>.
    </p>

    <div class="info-box">
      <strong>Document :</strong> {{ $typeLabel }}<br>
      <strong>Date :</strong> {{ $dateEtat }}<br>
      <strong>Agence :</strong> {{ $agence }}
    </div>

    <p>Cliquez sur le bouton ci-dessous pour consulter l'état des lieux et apposer votre signature électronique :</p>

    <a href="{{ $lien }}" class="btn-sign">Consulter et signer →</a>

    <p class="warning">
      Si vous ne parvenez pas à cliquer sur le bouton, copiez ce lien dans votre navigateur :<br>
      <a href="{{ $lien }}" style="color:#696cff; word-break:break-all;">{{ $lien }}</a>
    </p>
  </div>
  <div class="footer">
    Ce message a été envoyé par {{ $agence }}.<br>
    Si vous n'êtes pas concerné par ce document, ignorez cet email.
  </div>
</div>
</body>
</html>
