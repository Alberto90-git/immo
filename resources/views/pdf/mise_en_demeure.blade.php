<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 10.5px; color: #1e293b; line-height: 1.6; padding: 32px 42px; }

  /* ── En-tête agence ──────────────────────────────────────────────────────── */
  .agency-header {
    text-align: center;
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 3px solid #1e293b;
  }
  .agency-header img {
    max-width: 70px;
    max-height: 70px;
    margin-bottom: 6px;
  }
  .agency-header h2 {
    color: #2c3e50;
    font-size: 16px;
    margin: 3px 0;
  }
  .agency-header p {
    color: #7f8c8d;
    font-size: 10px;
    margin: 2px 0;
  }

  /* ── Badge document ──────────────────────────────────────────────────────── */
  .doc-badge {
    text-align: center;
    margin-bottom: 20px;
  }
  .doc-badge span {
    background: #1e293b; color: white;
    padding: 6px 20px; border-radius: 4px;
    font-size: 12px; font-weight: bold;
    letter-spacing: 1px; text-transform: uppercase;
  }

  /* ── Corps ───────────────────────────────────────────────────────────────── */
  .ref-line { font-size: 9px; color: #64748b; margin-bottom: 28px; }

  .destinataire-block {
    background: #f8fafc; border: 1px solid #e2e8f0;
    border-left: 4px solid #1e293b;
    padding: 12px 16px; margin-bottom: 24px;
  }
  .destinataire-block .label { font-size: 8px; text-transform: uppercase; color: #64748b; font-weight: bold; margin-bottom: 4px; }
  .destinataire-block .nom   { font-size: 12px; font-weight: bold; }
  .destinataire-block .sub   { font-size: 9.5px; color: #475569; }

  .objet-line { font-weight: bold; font-size: 11px; margin-bottom: 20px; }
  .objet-line span { text-decoration: underline; }

  .body-text p { margin-bottom: 12px; text-align: justify; }

  .montant-box {
    background: #fff7ed; border: 2px solid #f97316;
    border-radius: 6px; padding: 12px 16px;
    margin: 20px 0; text-align: center;
  }
  .montant-box .label-m { font-size: 9px; text-transform: uppercase; color: #ea580c; font-weight: bold; }
  .montant-box .value-m { font-size: 20px; font-weight: bold; color: #c2410c; }

  .delai-box {
    background: #fef2f2; border-left: 4px solid #ef4444;
    padding: 10px 14px; margin: 16px 0; font-size: 10px;
  }
  .delai-box strong { color: #dc2626; }

  /* ── Signature ───────────────────────────────────────────────────────────── */
  .signature-section {
    margin-top: 40px;
    text-align: right;
    page-break-inside: avoid;
  }
  .signature-block { display: inline-block; text-align: center; width: 280px; }
  .signature-block .sig-label { font-size: 9px; color: #64748b; margin-bottom: 2px; }
  .signature-block .sig-img   { max-width: 160px; max-height: 65px; display: block; margin: 0 auto 2px auto; }
  .signature-block .cachet-img { display: block; margin: 0 auto 6px auto; max-width: 65px; max-height: 65px; opacity: 0.85; }
  .signature-block .sig-name  { font-size: 10px; font-weight: bold; margin-top: 4px; }
  .signature-block .sig-role  { font-size: 8.5px; color: #64748b; margin-top: 1px; }

  /* ── Pied de page ────────────────────────────────────────────────────────── */
  .footer {
    margin-top: 40px; padding-top: 10px;
    border-top: 1px solid #e2e8f0;
    text-align: center; font-size: 8px; color: #94a3b8;
  }
</style>
</head>
<body>

  {{-- ── En-tête agence ─────────────────────────────────────────────────────── --}}
  @if(isset($annexeData) && $annexeData)
  <div class="agency-header">
    @if(!empty($annexeData['logo_base64']))
      <img src="{{ $annexeData['logo_base64'] }}" alt="Logo">
    @endif
    <h2>{{ strtoupper($annexeData['designation']) }}</h2>
    @if($annexeData['siege_social'])
      <p>{{ $annexeData['siege_social'] }}</p>
    @endif
    @if($annexeData['telephone'] || $annexeData['email'])
      <p>
        @if($annexeData['telephone']) Tél. : {{ $annexeData['telephone'] }} @endif
        @if($annexeData['telephone'] && $annexeData['email'])  &nbsp;·&nbsp;  @endif
        @if($annexeData['email']) {{ $annexeData['email'] }} @endif
      </p>
    @endif
  </div>
  @endif

  {{-- ── Badge document ─────────────────────────────────────────────────────── --}}
  <div class="doc-badge"><span>{{ __('pdf.med_titre') }}</span></div>

  {{-- ── Références ───────────────────────────────────────────────────────── --}}
  <div class="ref-line">
    {{ __('pdf.med_ref') }} MED-{{ str_pad($dossier->id, 5, '0', STR_PAD_LEFT) }} &nbsp;|&nbsp; {{ __('pdf.med_le') }} {{ $date }}
  </div>

  {{-- ── Destinataire ─────────────────────────────────────────────────────── --}}
  <div class="destinataire-block">
    <div class="label">{{ __('pdf.med_destinataire') }}</div>
    <div class="nom">{{ $locataire->prenom }} {{ $locataire->nom }}</div>
    <div class="sub">
      {{ $locataire->maison->nom_maison ?? '' }}
      @if($locataire->chambre) — {{ __('pdf.med_logement') }} {{ $locataire->chambre->numero_chambre }} @endif
      <br>{{ __('pdf.med_tph') }} {{ $locataire->telephone }}
      @if($locataire->email) &nbsp;|&nbsp; {{ $locataire->email }} @endif
    </div>
  </div>

  {{-- ── Objet ────────────────────────────────────────────────────────────── --}}
  <div class="objet-line">
    {{ __('pdf.med_objet_label') }} <span>{{ __('pdf.med_objet') }}</span>
  </div>

  {{-- ── Corps ───────────────────────────────────────────────────────────── --}}
  <div class="body-text">
    <p>{{ __('pdf.med_salutation') }} <strong>{{ $locataire->prenom }} {{ $locataire->nom }}</strong>,</p>

    <p>
      {{ __('pdf.med_corps_1a') }}
      @if($dossier->relances->count())
        {{ __('pdf.med_corps_1b_relances', ['n' => $dossier->relances->count()]) }}
      @else
        {{ __('pdf.med_corps_1b_attente') }}
      @endif
      {{ __('pdf.med_corps_1c') }}
    </p>

    <div class="montant-box">
      <div class="label-m">{{ __('pdf.med_montant_label') }}</div>
      <div class="value-m">{{ format_price($dossier->montant_du - $dossier->montant_recouvre) }}</div>
      @if($dossier->nb_mois_impayes > 0)
        <div style="font-size:8.5px;color:#9a3412;margin-top:4px;">{{ __('pdf.med_mois_impayes', ['n' => $dossier->nb_mois_impayes]) }}</div>
      @endif
    </div>

    <div class="delai-box">
      <strong>{{ __('pdf.med_injonction_label') }}</strong> {{ __('pdf.med_injonction', ['n' => $delaiJours, 'lettres' => $delaiLettre]) }}
    </div>

    <p>{{ __('pdf.med_corps_2') }}</p>

    <p>{{ __('pdf.med_corps_3') }}</p>

    @if($dossier->notes_juridiques)
    <p><em>{{ __('pdf.med_note') }} {{ $dossier->notes_juridiques }}</em></p>
    @endif
  </div>

  {{-- ── Signature (toujours sur la même page) ──────────────────────────── --}}
  <div style="page-break-inside: avoid;">
  <div class="signature-section">
    <div class="signature-block">
      <div class="sig-label">{{ __('pdf.fait_a', ['lieu' => $siegeSocial ?: '___________', 'date' => $date]) }}</div>
      @if(!empty($annexeData['sig_base64']))
        <img src="{{ $annexeData['sig_base64'] }}" class="sig-img" alt="Signature">
      @else
        <div style="height:10px;"></div>
      @endif
      @if(!empty($annexeData['cachet_base64']))
        <img src="{{ $annexeData['cachet_base64'] }}" class="cachet-img" alt="Cachet">
      @endif
      <div class="sig-name">{{ $nomDirecteur }}</div>
      <div class="sig-role">{{ __('pdf.med_le_directeur') }}</div>
    </div>
  </div>

  </div>{{-- /page-break-inside --}}

  {{-- ── Pied de page ─────────────────────────────────────────────────────── --}}
  <div class="footer">
    {{ $annexeData['designation'] ?? config('app.name') }}
    @if(!empty($annexeData['siege_social']))
      &nbsp;·&nbsp; {{ $annexeData['siege_social'] }}
    @endif
    &nbsp;·&nbsp; {{ __('pdf.med_generated') }} {{ $date }}
  </div>

</body>
</html>
