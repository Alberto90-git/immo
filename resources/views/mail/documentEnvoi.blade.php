<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['type_document_label'] }}</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .header { background: #3498db; color: #fff; padding: 24px 30px; }
        .header h1 { margin: 0; font-size: 22px; }
        .header p { margin: 6px 0 0; font-size: 14px; opacity: .85; }
        .body { padding: 30px; }
        .body p { line-height: 1.7; font-size: 15px; }
        .message-box { background: #f0f4ff; border-left: 4px solid #3498db; padding: 14px 18px; border-radius: 4px; margin: 20px 0; font-style: italic; }
        .footer { background: #f5f5f5; padding: 18px 30px; font-size: 12px; color: #888; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>{{ $data['agence_nom'] }}</h1>
        <p>{{ $data['type_document_label'] }}</p>
    </div>
    <div class="body">
        <p>Bonjour <strong>{{ $data['destinataire_nom'] }}</strong>,</p>
        <p>Veuillez trouver ci-joint votre document : <strong>{{ $data['type_document_label'] }}</strong>.</p>

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
