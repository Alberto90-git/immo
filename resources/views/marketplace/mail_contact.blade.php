<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: Arial, sans-serif; background: #f5f5f9; margin: 0; padding: 0; }
    .wrapper { max-width: 600px; margin: 30px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
    .header { background: #696cff; color: #fff; padding: 24px 30px; }
    .header h2 { margin: 0; font-size: 1.3rem; }
    .body { padding: 30px; }
    .row { border-bottom: 1px solid #f0f0f0; padding: 10px 0; }
    .label { font-size: .8rem; color: #999; margin-bottom: 2px; }
    .value { font-size: .95rem; color: #333; font-weight: 600; }
    .message-box { background: #f9f9f9; border-left: 4px solid #696cff; padding: 14px 18px; border-radius: 0 8px 8px 0; margin-top: 8px; }
    .footer { text-align: center; padding: 16px; font-size: .8rem; color: #aaa; }
  </style>
</head>
<body>
<div class="wrapper">
  <div class="header">
    <h2>Nouveau contact depuis le Marketplace</h2>
    <p style="margin:4px 0 0;opacity:.85;font-size:.9rem">Annonce : {{ $annonce->localisation }}</p>
  </div>
  <div class="body">
    <div class="row">
      <div class="label">Nom</div>
      <div class="value">{{ $nomCont }}</div>
    </div>
    <div class="row">
      <div class="label">Email</div>
      <div class="value"><a href="mailto:{{ $emailCont }}" style="color:#696cff">{{ $emailCont }}</a></div>
    </div>
    @if($telCont)
    <div class="row">
      <div class="label">Téléphone</div>
      <div class="value"><a href="tel:{{ $telCont }}" style="color:#696cff">{{ $telCont }}</a></div>
    </div>
    @endif
    <div class="row">
      <div class="label">Bien concerné</div>
      <div class="value">{{ $annonce->localisation }} — {{ format_price((float)$annonce->price, null, $deviseCode ?? 'XOF') }}</div>
    </div>
    <div style="margin-top:16px">
      <div class="label">Message</div>
      <div class="message-box">{{ $msgCont }}</div>
    </div>
  </div>
  <div class="footer">Lokativ Marketplace — Ce message a été envoyé depuis la fiche annonce publique</div>
</div>
</body>
</html>
