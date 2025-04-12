<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Change mot de passe</title>
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
      padding: 10px;
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

    .btn {
      display: inline-block;
      background-color: #007bff;
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
      color: #007bff;
      text-decoration: none;
    }

  </style>
</head>
<body>

  <div class="container">
    <div class="header">
      <h1>Bienvenue chez Immo Manager</h1>
    </div>
    <div class="content">
      <p>Hello {{ $user ? $user['email'] : '' }},</p>
      <p>Vous avez reçu un message d'initialisation de votre mot de passe.</p>
      
      
      <p style="margin:10px 0 12px 0;font-size:14px;line-height:24px;font-family:Arial,sans-serif;">
        Veuillez cliquer sur le boutton ci-dessous pour terminer le processus:
     </p>
      
     
     <a href="http://127.0.0.1/ImmobilierApk/public/forgot-password/{{ $user['token'] }}" class="btn">Réinitialiser son mot de passe</a>
     {{-- <a href="{{ request()->getSchemeAndHttpHost().'/forgot-password/'.$user['token']}}" class="btn">Rénitialiser son mot de passe</a> --}}

      <p>Si vous avez des questions, n'hésitez pas à répondre à cet e-mail.</p>
    </div>
    <div class="footer">
      <p>&copy; 2024 Immo Manager. Tous droits réservés.</p>
      <p><a href="#">Politique de confidentialité</a> | <a href="#">Se désabonner</a></p>
    </div>
  </div>

</body>
</html>
