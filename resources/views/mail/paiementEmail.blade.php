<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Facture de paiement</title>
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
      background-color: #007bff;
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
      margin: 10px 0;
      font-size: 16px;
    }

    .facture {
      border: 1px solid #ddd;
      padding: 15px;
      border-radius: 5px;
      background: #f9f9f9;
    }

    .facture p {
      margin: 8px 0;
      font-size: 16px;
    }

    .facture p strong {
      color: #007bff;
    }

    .btn {
      display: block;
      text-align: center;
      background-color: #007bff;
      color: #fff;
      text-decoration: none;
      padding: 12px;
      border-radius: 5px;
      font-size: 16px;
      margin-top: 20px;
    }

    .footer {
      text-align: center;
      color: #777;
      padding: 10px;
      font-size: 14px;
      margin-top: 20px;
    }

    .footer a {
      color: #007bff;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="header">
      <h1>Facture de paiement</h1>
    </div>
    <div class="content">
      <p>Bonjour <strong>{{ $client['nom_prenom'] }}</strong>,</p>
      <p>Nous vous remercions pour votre confiance. Voici les détails de votre facture :</p>

      <div class="facture">
        <p><strong>Nom du client :</strong> {{ $client['nom_prenom'] }}</p>
        <p><strong>Montant :</strong> <span style="color: #d9534f;">{{ number_format($client['montant'] * $client['nombre_mois'], 2) }} XOF</span></p>
        <p><strong>Méthode de paiement :</strong> Mobile Money</p>
        <p><strong>Numéro de paiement :</strong> <span style="color: #5cb85c;">+229 01 61 08 22 60</span></p>
        <p><strong>Code de paiement :</strong> {{ $client['code_inscription'] }}</p>
        <p><strong>Plan choisi :</strong> {{ $client['plan_key'] }}</p>
        <p><strong>Durée d'abonnement :</strong> {{ $client['nombre_mois'] }} mois</p>
        <p><strong>Date d'échéance :</strong> {{ $client['created'] }}</p>
      </div>   
      
      <p><strong>Important :</strong> Veuillez effectuer le paiement avant la date d'échéance pour éviter toute interruption de service.</p>
      <a href="{{ request()->getSchemeAndHttpHost() }}" class="btn">Voir ma facture</a>
    </div>
    <div class="footer">
      <p>&copy; {{ date('Y') }} Immo. Tous droits réservés.</p>
      <p><a href="#">Politique de confidentialité</a> | <a href="#">Se désabonner</a></p>
    </div>
  </div>

</body>
</html>
