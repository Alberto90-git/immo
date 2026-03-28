<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rappel de paiement de loyer</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; color: #333; }
        .container { max-width: 600px; margin: 0 auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .header { background: #f59e0b; color: #fff; padding: 24px 30px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 6px 0 0; font-size: 14px; opacity: .9; }
        .body { padding: 30px; }
        .body p { line-height: 1.7; font-size: 15px; margin-bottom: 14px; }
        .amount-box {
            background: #fffbeb;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 18px 22px;
            margin: 20px 0;
            text-align: center;
        }
        .amount-box .label { font-size: 13px; color: #92400e; margin-bottom: 4px; }
        .amount-box .amount { font-size: 28px; font-weight: 700; color: #d97706; }
        .amount-box .month { font-size: 14px; color: #78350f; margin-top: 4px; }
        .message-box { background: #f0f4ff; border-left: 4px solid #3b82f6; padding: 14px 18px; border-radius: 4px; margin: 20px 0; font-style: italic; }
        .footer { background: #f5f5f5; padding: 18px 30px; font-size: 12px; color: #888; border-top: 1px solid #eee; }
        .icon { font-size: 18px; margin-right: 6px; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>⏰ Rappel de paiement de loyer</h1>
        <p>{{ $data['agence_nom'] }}</p>
    </div>
    <div class="body">
        <p>Bonjour <strong>{{ $data['destinataire_nom'] }}</strong>,</p>
        <p>
            Nous vous adressons ce rappel concernant le paiement de votre loyer pour le mois en cours.
        </p>

        <div class="amount-box">
            <div class="label">Montant du loyer dû</div>
            <div class="amount">{{ $data['montant_loyer'] }} XOF</div>
            <div class="month">{{ $data['mois_courant'] }}</div>
        </div>

        @if (!empty($data['logement']))
        <p>
            <strong>Logement :</strong> {{ $data['logement'] }}
        </p>
        @endif

        <p>
            Merci de bien vouloir procéder au règlement dans les meilleurs délais afin d'éviter tout désagrément.
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
