<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facture d'abonnement</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      line-height: 1.6;
    }
    .container {
      max-width: 600px;
      margin: 20px auto;
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .header {
      text-align: center;
      background-color: #1e40af;
      padding: 15px;
      border-radius: 8px 8px 0 0;
      color: #fff;
    }
    .header h1 {
      margin: 0;
      font-size: 24px;
    }
    .content {
      padding: 20px;
      color: #333;
    }
    .content p {
      margin: 15px 0;
    }
    .plan-box {
      background-color: #f0f4ff;
      border-left: 4px solid #1e40af;
      padding: 15px;
      margin: 15px 0;
      border-radius: 0 8px 8px 0;
    }
    .plan-box h3 {
      margin: 0 0 10px 0;
      color: #1e40af;
    }
    .btn {
      display: inline-block;
      background-color: #1e40af;
      color: #fff;
      text-decoration: none;
      padding: 10px 20px;
      border-radius: 4px;
      margin-top: 10px;
    }
    .footer {
      text-align: center;
      color: #777;
      padding: 10px;
      font-size: 14px;
    }
    .footer a {
      color: #1e40af;
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h1>Facture d'abonnement ImmoManager</h1>
    </div>
    <div class="content">
      <p>Bonjour {{ $invoiceData['user']['nom'] }} {{ $invoiceData['user']['prenom'] }},</p>
      <p>Merci d'avoir créé votre compte sur <strong>ImmoManager</strong> ! Veuillez trouver ci-joint la facture de votre abonnement.</p>

      <div class="plan-box">
        <h3>Plan {{ $invoiceData['plan']['nom'] }}</h3>
        <p><strong>Période :</strong>
          {{ \Carbon\Carbon::parse($invoiceData['direction']['abonnement_debut'])->format('d/m/Y') }}
          -
          {{ \Carbon\Carbon::parse($invoiceData['direction']['abonnement_fin'])->format('d/m/Y') }}
        </p>
        <p><strong>Montant :</strong> {{ number_format($invoiceData['plan']['prix_annuel'], 0, ',', '.') }} XOF</p>
      </div>

      <p>La facture PDF est jointe à cet email pour vos archives.</p>

      <a href="{{ config('app.url') }}/login" class="btn">Se connecter</a>

      <p>Si vous avez des questions, n'hésitez pas à nous contacter.</p>
    </div>
    <div class="footer">
      <p>&copy; {{ date('Y') }} ImmoManager. Tous droits réservés.</p>
    </div>
  </div>
</body>
</html>
