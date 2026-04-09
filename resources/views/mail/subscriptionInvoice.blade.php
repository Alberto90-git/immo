<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Facture d'abonnement Lokativ</title>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background-color: #f0f2f5;
      padding: 30px 10px;
      color: #333;
    }
    .wrapper {
      max-width: 580px;
      margin: 0 auto;
    }
    /* ── Header ── */
    .header {
      background: linear-gradient(135deg, #1a56db 0%, #0e3a9c 100%);
      border-radius: 12px 12px 0 0;
      padding: 32px 24px;
      text-align: center;
    }
    .header .brand {
      font-size: 28px;
      font-weight: 700;
      color: #fff;
      letter-spacing: 1px;
    }
    .header .brand span { color: #93c5fd; }
    .header p {
      color: #bfdbfe;
      margin-top: 6px;
      font-size: 14px;
    }
    .badge-invoice {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(255,255,255,0.15);
      border-radius: 20px;
      padding: 5px 14px;
      font-size: 13px;
      color: #fde68a;
      margin-top: 12px;
    }
    /* ── Body ── */
    .body {
      background: #ffffff;
      padding: 36px 32px;
    }
    .body h2 {
      font-size: 20px;
      color: #1e293b;
      margin-bottom: 16px;
    }
    .body p {
      font-size: 15px;
      color: #475569;
      line-height: 1.7;
      margin-bottom: 12px;
    }
    /* ── Boîte plan ── */
    .info-card {
      margin: 20px 0;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      overflow: hidden;
    }
    .info-card .card-header {
      background: #f1f5f9;
      padding: 12px 18px;
      font-size: 12px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 1.5px;
      color: #64748b;
      border-bottom: 1px solid #e2e8f0;
    }
    .info-row {
      display: flex;
      align-items: center;
      padding: 14px 18px;
      border-bottom: 1px solid #f1f5f9;
      gap: 12px;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .ir-icon {
      width: 36px;
      height: 36px;
      border-radius: 8px;
      background: #eff6ff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 17px;
      flex-shrink: 0;
    }
    .info-row .ir-label {
      font-size: 12px;
      color: #94a3b8;
      margin-bottom: 2px;
    }
    .info-row .ir-value {
      font-size: 15px;
      color: #1e293b;
      font-weight: 600;
      word-break: break-all;
    }
    .info-row .ir-value code {
      font-size: 13px;
      background: #f1f5f9;
      border-radius: 4px;
      padding: 2px 6px;
      color: #1e40af;
    }
    /* ── Badge PAYÉ ── */
    .badge-paid {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      background: #dcfce7;
      color: #15803d;
      font-size: 11px;
      font-weight: 700;
      padding: 2px 8px;
      border-radius: 12px;
      vertical-align: middle;
      margin-left: 6px;
    }
    /* ── Icône carte paiement verte ── */
    .info-row.payment .ir-icon { background: #f0fdf4; }
    /* ── Bouton ── */
    .btn-connect {
      display: block;
      width: 100%;
      text-align: center;
      background: linear-gradient(135deg, #1a56db 0%, #0e3a9c 100%);
      color: #fff !important;
      text-decoration: none;
      padding: 14px 24px;
      border-radius: 8px;
      font-size: 15px;
      font-weight: 600;
      margin: 28px 0 20px;
      letter-spacing: 0.3px;
    }
    /* ── Info box verte (PDF joint) ── */
    .info-box {
      display: flex;
      gap: 12px;
      align-items: flex-start;
      background: #f0fdf4;
      border-left: 4px solid #22c55e;
      border-radius: 6px;
      padding: 14px 16px;
      margin-top: 8px;
    }
    .info-box .icon { font-size: 18px; line-height: 1; flex-shrink: 0; }
    .info-box p { font-size: 13px; color: #14532d; margin: 0; line-height: 1.6; }
    /* ── Divider ── */
    .divider {
      border: none;
      border-top: 1px solid #e2e8f0;
      margin: 28px 0;
    }
    /* ── Footer ── */
    .footer {
      background: #f8faff;
      border-radius: 0 0 12px 12px;
      padding: 20px 24px;
      text-align: center;
    }
    .footer p { font-size: 12px; color: #94a3b8; line-height: 1.8; }
    .footer a { color: #1a56db; text-decoration: none; }
  </style>
</head>
<body>
  <div class="wrapper">

    <!-- Header -->
    <div class="header">
      <div class="brand">Loka<span>tiv</span></div>
      <p>Plateforme de gestion immobilière</p>
      <div class="badge-invoice">🧾 Facture d'abonnement</div>
    </div>

    <!-- Body -->
    <div class="body">
      <h2>Merci pour votre confiance, {{ $invoiceData['user']['prenom'] }} !</h2>
      <p>
        Bonjour <strong>{{ $invoiceData['user']['nom'] }} {{ $invoiceData['user']['prenom'] }}</strong>,<br>
        Votre abonnement <strong>Lokativ</strong> est confirmé. Vous trouverez ci-dessous le récapitulatif ainsi que la facture PDF en pièce jointe.
      </p>

      <!-- Plan -->
      <div class="info-card">
        <div class="card-header">Plan souscrit</div>

        <div class="info-row">
          <div class="ir-icon">📦</div>
          <div>
            <div class="ir-label">Plan</div>
            <div class="ir-value">{{ $invoiceData['plan']['nom'] }}</div>
          </div>
        </div>

        <div class="info-row">
          <div class="ir-icon">📅</div>
          <div>
            <div class="ir-label">Période</div>
            <div class="ir-value">
              {{ \Carbon\Carbon::parse($invoiceData['direction']['abonnement_debut'])->format('d/m/Y') }}
              &rarr;
              {{ \Carbon\Carbon::parse($invoiceData['direction']['abonnement_fin'])->format('d/m/Y') }}
            </div>
          </div>
        </div>

        <div class="info-row">
          <div class="ir-icon">💰</div>
          <div>
            <div class="ir-label">Montant</div>
            <div class="ir-value">
              {{ $invoiceData['plan']['prix_annuel'] == 0
                  ? 'Gratuit (essai)'
                  : number_format($invoiceData['plan']['prix_annuel'], 0, ',', ' ') . ' XOF' }}
            </div>
          </div>
        </div>
      </div>

      <!-- Paiement (si applicable) -->
      @if(!empty($invoiceData['payment']))
      <div class="info-card">
        <div class="card-header">Confirmation de paiement</div>

        <div class="info-row payment">
          <div class="ir-icon">🏦</div>
          <div>
            <div class="ir-label">Prestataire</div>
            <div class="ir-value">
              {{ $invoiceData['payment']['provider'] }}
              <span class="badge-paid">✓ PAYÉ</span>
            </div>
          </div>
        </div>

        <div class="info-row payment">
          <div class="ir-icon">🔖</div>
          <div>
            <div class="ir-label">Référence transaction</div>
            <div class="ir-value"><code>{{ $invoiceData['payment']['transaction_id'] }}</code></div>
          </div>
        </div>

        <div class="info-row payment">
          <div class="ir-icon">💳</div>
          <div>
            <div class="ir-label">Montant débité</div>
            <div class="ir-value">{{ number_format($invoiceData['plan']['prix_annuel'], 0, ',', ' ') }} XOF</div>
          </div>
        </div>

        <div class="info-row payment">
          <div class="ir-icon">🗓️</div>
          <div>
            <div class="ir-label">Date</div>
            <div class="ir-value">{{ \Carbon\Carbon::now()->format('d/m/Y à H:i') }}</div>
          </div>
        </div>

        @if($invoiceData['payment']['sandbox'])
        <div class="info-row payment">
          <div class="ir-icon">⚠️</div>
          <div>
            <div class="ir-label">Mode</div>
            <div class="ir-value" style="color:#d97706;">Sandbox (test)</div>
          </div>
        </div>
        @endif
      </div>
      @endif

      <!-- Bouton connexion -->
      <a href="{{ config('app.url') }}/login" class="btn-connect">
        Accéder à mon espace →
      </a>

      <!-- Note PDF -->
      <div class="info-box">
        <div class="icon">📎</div>
        <p>La <strong>facture PDF</strong> est jointe à cet email pour vos archives. Conservez-la pour toute demande de remboursement ou justificatif comptable.</p>
      </div>

      <hr class="divider">
      <p style="font-size:13px; color:#94a3b8;">
        Des questions ? Contactez notre support à
        <a href="mailto:support@lokativ.com" style="color:#1a56db;">support@lokativ.com</a>.
      </p>
    </div>

    <!-- Footer -->
    <div class="footer">
      <p>
        &copy; {{ date('Y') }} Lokativ. Tous droits réservés.<br>
        <a href="#">Politique de confidentialité</a> &nbsp;|&nbsp; <a href="#">Aide</a>
      </p>
    </div>

  </div>
</body>
</html>
