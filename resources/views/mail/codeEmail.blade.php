<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email Template</title>
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

    .copy-btn {
      background-color: #28a745;
      color: white;
      border: none;
      padding: 10px;
      margin-top: 10px;
      cursor: pointer;
      border-radius: 4px;
      font-size: 16px;
    }
    
    .copy-btn:hover {
      background-color: #218838;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="header">
      <h1>Bienvenue chez Immo Manager</h1>
    </div>
    <div class="content">
        <p>Demande de connexion, veuillez confirmez votre demande</p>
        <p>Votre code de confirmation est: <strong id="confirmation-code">{{ $userinfo['code_login'] }}</strong></p>
        <button class="copy-btn" onclick="copyToClipboard()">Copier le code</button>
        <p>Si vous avez des questions, n'hésitez pas à répondre à cet e-mail.</p>
    </div>
    <div class="footer">
      <p>&copy; 2024 Immo Manager. Tous droits réservés.</p>
      <p><a href="#">Politique de confidentialité</a> | <a href="#">Se désabonner</a></p>
    </div>
  </div>

  <script>
    function copyToClipboard() {
      var codeText = document.getElementById("confirmation-code").innerText;
      navigator.clipboard.writeText(codeText).then(function() {
        alert("Code copié: " + codeText);
      }, function(err) {
        console.error('Erreur lors de la copie', err);
      });
    }  
  </script>

</body>
</html>
