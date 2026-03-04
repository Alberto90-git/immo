<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notification d'expiration d'abonnement</title>
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
    .alert-box {
      padding: 15px;
      margin: 15px 0;
      border-radius: 0 8px 8px 0;
    }
    .alert-box h3 {
      margin: 0 0 10px 0;
    }
    .alert-box p {
      margin: 5px 0;
    }
    .alert-warning {
      background-color: #fef3c7;
      border-left: 4px solid #f59e0b;
    }
    .alert-warning h3 { color: #b45309; }
    .alert-orange {
      background-color: #fff7ed;
      border-left: 4px solid #f97316;
    }
    .alert-orange h3 { color: #c2410c; }
    .alert-danger {
      background-color: #fef2f2;
      border-left: 4px solid #ef4444;
    }
    .alert-danger h3 { color: #dc2626; }
    .btn {
      display: inline-block;
      background-color: #1e40af;
      color: #fff;
      text-decoration: none;
      padding: 12px 24px;
      border-radius: 4px;
      margin-top: 15px;
      font-weight: bold;
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
      <h1>Lokativ - Abonnement</h1>
    </div>
    <div class="content">
      @php
        $days = $notificationData['jours_restants'];
        $nom = $notificationData['destinataire_nom'] ?? $notificationData['entreprise'];

        if ($days < 0) {
            $alertClass = 'alert-danger';
            $alertTitle = 'Abonnement expiré';
            $alertMessage = 'Votre abonnement a expiré depuis ' . abs($days) . ' jour(s). Vos accès seront limités tant que vous n\'aurez pas renouvelé votre abonnement.';
        } elseif ($days == 0) {
            $alertClass = 'alert-danger';
            $alertTitle = 'Expiration aujourd\'hui';
            $alertMessage = 'Votre abonnement expire aujourd\'hui. Renouvelez-le maintenant pour éviter toute interruption de service.';
        } elseif ($days == 1) {
            $alertClass = 'alert-danger';
            $alertTitle = 'Expiration demain';
            $alertMessage = 'Votre abonnement expire demain. Pensez à le renouveler pour maintenir l\'accès à toutes les fonctionnalités.';
        } elseif ($days <= 3) {
            $alertClass = 'alert-orange';
            $alertTitle = 'Expiration dans ' . $days . ' jours';
            $alertMessage = 'Votre abonnement expire dans ' . $days . ' jours. Il est temps de penser au renouvellement.';
        } else {
            $alertClass = 'alert-warning';
            $alertTitle = 'Expiration dans ' . $days . ' jours';
            $alertMessage = 'Votre abonnement expire dans ' . $days . ' jours. Anticipez le renouvellement pour éviter toute interruption.';
        }
      @endphp

      <p>Bonjour <strong>{{ $nom }}</strong>,</p>

      <p>{{ $alertMessage }}</p>

      <div class="alert-box {{ $alertClass }}">
        <h3>{{ $alertTitle }}</h3>
        <p><strong>Entreprise :</strong> {{ $notificationData['entreprise'] }}</p>
        <p><strong>Plan actuel :</strong> {{ $notificationData['plan_nom'] }}</p>
        <p><strong>Date d'expiration :</strong> {{ $notificationData['date_expiration'] }}</p>
        @if($days >= 0)
          <p><strong>Jours restants :</strong> {{ $days }} jour(s)</p>
        @else
          <p><strong>Expiré depuis :</strong> {{ abs($days) }} jour(s)</p>
        @endif
      </div>

      <p style="text-align: center;">
        <a href="{{ config('app.url') }}/login" class="btn">Renouveler mon abonnement</a>
      </p>

      <p>Si vous avez des questions ou besoin d'assistance pour le renouvellement, n'hésitez pas à nous contacter.</p>
    </div>
    <div class="footer">
      <p>&copy; {{ date('Y') }} Lokativ. Tous droits réservés.</p>
    </div>
  </div>
</body>
</html>
