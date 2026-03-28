<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préavis de fin de bail</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .header { background: #ef4444; color: #fff; padding: 24px 30px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 6px 0 0; font-size: 14px; opacity: .9; }
        .body { padding: 30px; }
        .body p { line-height: 1.7; font-size: 15px; margin-bottom: 14px; }
        .date-box {
            background: #fef2f2;
            border: 2px solid #ef4444;
            border-radius: 8px;
            padding: 18px 22px;
            margin: 20px 0;
            text-align: center;
        }
        .date-box .label { font-size: 13px; color: #991b1b; margin-bottom: 4px; }
        .date-box .date-val { font-size: 24px; font-weight: 700; color: #dc2626; }
        .info-row { background: #f8f9fa; border-radius: 6px; padding: 12px 16px; margin-bottom: 12px; font-size: 14px; }
        .info-row strong { display: inline-block; min-width: 120px; color: #555; }
        .message-box { background: #f0f4ff; border-left: 4px solid #3b82f6; padding: 14px 18px; border-radius: 4px; margin: 20px 0; font-style: italic; }
        .footer { background: #f5f5f5; padding: 18px 30px; font-size: 12px; color: #888; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>📋 Préavis de fin de bail</h1>
        <p>{{ $data['agence_nom'] }}</p>
    </div>
    <div class="body">
        <p>Bonjour <strong>{{ $data['destinataire_nom'] }}</strong>,</p>
        <p>
            Par la présente, nous vous notifions officiellement la fin de votre contrat de bail.
        </p>

        @if (!empty($data['date_fin_bail']))
        <div class="date-box">
            <div class="label">Date de fin de bail</div>
            <div class="date-val">{{ $data['date_fin_bail'] }}</div>
        </div>
        @endif

        @if (!empty($data['logement']))
        <div class="info-row">
            <strong>Logement :</strong> {{ $data['logement'] }}
        </div>
        @endif

        <p>
            Conformément aux termes de votre contrat de location, vous êtes prié(e) de libérer
            les lieux et de restituer les clés avant la date indiquée ci-dessus.
        </p>
        <p>
            Nous vous invitons à prendre contact avec notre agence afin de planifier l'état des lieux de sortie.
        </p>

        @if (!empty($data['message_personnalise']))
        <div class="message-box">
            {{ $data['message_personnalise'] }}
        </div>
        @endif

        <p>Pour toute question, n'hésitez pas à nous contacter.</p>
        <p>Cordialement,<br><strong>{{ $data['agence_nom'] }}</strong></p>
    </div>
    <div class="footer">
        Ce message a été envoyé automatiquement par <strong>Lokativ</strong>. Merci de ne pas répondre directement à cet email.
    </div>
</div>
</body>
</html>
