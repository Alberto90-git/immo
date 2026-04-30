<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1e293b; }
  .page { padding: 22px 28px; }

  /* ── En-tête ─────────────────────────────────────────────────────────── */
  .header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-bottom: 14px;
    margin-bottom: 16px;
    border-bottom: 3px solid #696cff;
  }
  .header-left { display: flex; align-items: center; gap: 12px; }
  .header-logo { max-height: 56px; max-width: 80px; object-fit: contain; }
  .header-logo-placeholder {
    width: 56px; height: 56px; border-radius: 8px;
    background: #696cff; display: flex; align-items: center; justify-content: center;
    color: white; font-size: 18px; font-weight: bold;
  }
  .agence-name { font-size: 13px; font-weight: bold; color: #696cff; }
  .agence-sub  { font-size: 8.5px; color: #64748b; line-height: 1.5; margin-top: 2px; }

  .header-right { text-align: right; }
  .doc-badge {
    display: inline-block;
    background: #696cff;
    color: white;
    padding: 5px 14px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: bold;
    letter-spacing: 0.5px;
    text-transform: uppercase;
  }
  .doc-badge.sortie { background: #f59e0b; }
  .doc-meta { font-size: 8.5px; color: #64748b; margin-top: 5px; line-height: 1.6; }

  /* ── Titre principal ─────────────────────────────────────────────────── */
  .titre-principal {
    text-align: center;
    font-size: 13px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin: 10px 0 14px;
    color: #1e293b;
    border-bottom: 1px dashed #cbd5e1;
    padding-bottom: 10px;
  }

  /* ── Bloc parties ────────────────────────────────────────────────────── */
  .parties { display: flex; gap: 10px; margin-bottom: 16px; }
  .partie-block {
    flex: 1;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 9px 11px;
    background: #f8faff;
  }
  .partie-titre {
    font-size: 8px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #696cff;
    margin-bottom: 5px;
    padding-bottom: 4px;
    border-bottom: 1px solid #e2e8f0;
  }
  .partie-valeur  { font-size: 10px; font-weight: bold; color: #1e293b; }
  .partie-sous    { font-size: 8.5px; color: #64748b; margin-top: 2px; }

  /* ── Pièce titre ─────────────────────────────────────────────────────── */
  .piece-wrap { margin-top: 14px; }
  .piece-titre {
    background: linear-gradient(90deg, #696cff 0%, #8b8eff 100%);
    color: white;
    padding: 5px 10px;
    font-size: 9.5px;
    font-weight: bold;
    border-radius: 5px 5px 0 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  .piece-degra-badge {
    background: rgba(255,255,255,0.25);
    border-radius: 10px;
    padding: 1px 7px;
    font-size: 8px;
  }

  /* ── Tableau éléments ────────────────────────────────────────────────── */
  table { width: 100%; border-collapse: collapse; font-size: 9px; }
  table.elements { border: 1px solid #e2e8f0; }
  table.elements th {
    background: #f1f5f9;
    padding: 5px 7px;
    font-size: 8.5px;
    text-align: center;
    border: 1px solid #e2e8f0;
    color: #475569;
    font-weight: bold;
  }
  table.elements th.th-left { text-align: left; }
  table.elements td { padding: 4px 7px; border: 1px solid #e2e8f0; vertical-align: middle; }
  table.elements tr:nth-child(even) td { background: #f8faff; }
  table.elements tr.degradee td { background: #fff5f5; }
  .text-center { text-align: center; }
  .text-right  { text-align: right; }

  /* ── Badges état ─────────────────────────────────────────────────────── */
  .badge { display: inline-block; padding: 2px 7px; border-radius: 10px; font-size: 7.5px; font-weight: bold; }
  .bg-success   { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
  .bg-info      { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
  .bg-warning   { background: #fef9c3; color: #a16207; border: 1px solid #fde68a; }
  .bg-danger    { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
  .bg-dark      { background: #f1f5f9; color: #374151; border: 1px solid #d1d5db; }
  .bg-secondary { background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; }

  /* ── Récapitulatif retenues ──────────────────────────────────────────── */
  .recap-titre {
    background: #fee2e2;
    border: 1px solid #fecaca;
    border-radius: 5px 5px 0 0;
    padding: 5px 10px;
    font-size: 9.5px;
    font-weight: bold;
    color: #dc2626;
    margin-top: 16px;
  }
  table.retenue { border: 1px solid #fecaca; }
  table.retenue th { background: #dc2626; color: white; padding: 5px 8px; font-size: 8.5px; }
  table.retenue td { padding: 4px 8px; border: 1px solid #fecaca; font-size: 9px; }
  table.retenue tr:nth-child(even) td { background: #fff5f5; }
  table.retenue tfoot td {
    background: #fee2e2;
    font-weight: bold;
    font-size: 10px;
    color: #dc2626;
  }

  /* ── Notes ───────────────────────────────────────────────────────────── */
  .notes-block {
    border-left: 3px solid #696cff;
    background: #f8faff;
    border-radius: 0 5px 5px 0;
    padding: 8px 12px;
    margin-top: 14px;
    font-size: 9px;
    color: #475569;
  }
  .notes-titre { font-weight: bold; color: #696cff; margin-bottom: 3px; font-size: 9px; }

  /* ── Bloc signatures ─────────────────────────────────────────────────── */
  .signatures { display: flex; gap: 16px; margin-top: 28px; }
  .sig-block {
    flex: 1;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 12px;
    text-align: center;
    background: #fafafa;
  }
  .sig-titre {
    font-size: 8.5px;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: #64748b;
    margin-bottom: 10px;
    padding-bottom: 6px;
    border-bottom: 1px solid #e2e8f0;
  }
  .sig-zone { min-height: 48px; display: flex; align-items: center; justify-content: center; }
  .sig-ok {
    background: #dcfce7;
    border: 1px solid #bbf7d0;
    border-radius: 5px;
    padding: 5px 12px;
    color: #15803d;
    font-weight: bold;
    font-size: 8.5px;
  }
  .sig-en-attente {
    border: 1px dashed #cbd5e1;
    border-radius: 5px;
    padding: 5px 12px;
    color: #94a3b8;
    font-size: 8.5px;
  }
  .sig-nom {
    margin-top: 8px;
    font-size: 8.5px;
    color: #475569;
    font-weight: bold;
  }
  .sig-date { font-size: 7.5px; color: #94a3b8; margin-top: 2px; }
  .sig-img { max-height: 55px; max-width: 130px; }

  /* ── Pied de page ────────────────────────────────────────────────────── */
  .footer {
    margin-top: 18px;
    border-top: 1px solid #e2e8f0;
    padding-top: 7px;
    font-size: 7.5px;
    color: #94a3b8;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
</style>
</head>
<body>
<div class="page">

  {{-- ── En-tête ─────────────────────────────────────────────────────────── --}}
  <div class="header">
    <div class="header-left">
      @if(!empty($agence['logo_base64']))
        <img src="{{ $agence['logo_base64'] }}" alt="Logo" class="header-logo">
      @else
        <div class="header-logo-placeholder">
          {{ mb_substr($agence['designation'] ?? 'L', 0, 1) }}
        </div>
      @endif
      <div>
        <div class="agence-name">{{ $agence['designation'] ?? config('app.name') }}</div>
        <div class="agence-sub">
          @if(!empty($agence['adresse'])){{ $agence['adresse'] }}<br>@endif
          @if(!empty($agence['telephone']))Tél : {{ $agence['telephone'] }}@endif
          @if(!empty($agence['email'])) &nbsp;|&nbsp; {{ $agence['email'] }}@endif
        </div>
      </div>
    </div>
    <div class="header-right">
      <div class="doc-badge {{ $etat->type === 'sortie' ? 'sortie' : '' }}">
        {{ $etat->type === 'entree' ? "État d'Entrée" : "État de Sortie" }}
      </div>
      <div class="doc-meta">
        Ref. N° {{ str_pad($etat->id, 4, '0', STR_PAD_LEFT) }}<br>
        Date : <strong>{{ $etat->date_etat->format('d/m/Y') }}</strong><br>
        Statut : <strong>{{ ucfirst($etat->statut) }}</strong>
      </div>
    </div>
  </div>

  {{-- ── Titre ───────────────────────────────────────────────────────────── --}}
  <div class="titre-principal">Procès-verbal d'état des lieux</div>

  {{-- ── Parties ─────────────────────────────────────────────────────────── --}}
  <div class="parties">
    <div class="partie-block">
      <div class="partie-titre">👤 Locataire</div>
      <div class="partie-valeur">{{ $etat->locataire->nom ?? '–' }} {{ $etat->locataire->prenom ?? '' }}</div>
      @if(!empty($etat->locataire->telephone))<div class="partie-sous">📞 {{ $etat->locataire->telephone }}</div>@endif
      @if(!empty($etat->locataire->email))<div class="partie-sous">✉ {{ $etat->locataire->email }}</div>@endif
    </div>
    <div class="partie-block">
      <div class="partie-titre">🏠 Logement</div>
      <div class="partie-valeur">{{ $etat->maison->nom_maison ?? '–' }}</div>
      <div class="partie-sous">Chambre N° {{ $etat->chambre->numero_chambre ?? '–' }}</div>
      @if(!empty($etat->maison->quartier))<div class="partie-sous">📍 {{ $etat->maison->quartier }}</div>@endif
    </div>
    @if($etat->type === 'sortie' && $etat->etatEntree)
    <div class="partie-block">
      <div class="partie-titre">📋 Référence entrée</div>
      <div class="partie-valeur">Entrée du {{ $etat->etatEntree->date_etat->format('d/m/Y') }}</div>
      <div class="partie-sous">Durée d'occupation : {{ $etat->etatEntree->date_etat->diffInMonths($etat->date_etat) }} mois</div>
    </div>
    @endif
  </div>

  {{-- ── Pièces & éléments ───────────────────────────────────────────────── --}}
  @foreach($etat->pieces as $piece)
    @php $nbDegra = $piece->elements->where('degradation_detectee', true)->count(); @endphp
    <div class="piece-wrap">
      <div class="piece-titre">
        <span>{{ $piece->nom_piece }}</span>
        @if($nbDegra > 0)
          <span class="piece-degra-badge">⚠ {{ $nbDegra }} dégradation(s)</span>
        @endif
      </div>
      <table class="elements">
        <thead>
          <tr>
            <th class="th-left" style="width:18%">Élément</th>
            @if($etat->type === 'sortie' && $etat->etatEntree)
              <th style="width:12%">État entrée</th>
            @endif
            <th style="width:13%">État {{ $etat->type === 'sortie' ? 'sortie' : '' }}</th>
            <th class="th-left">Observations</th>
            @if($etat->type === 'sortie')
              <th style="width:11%">Dégradation</th>
              <th style="width:13%" class="text-right">Retenue ({{ get_symbole_devise() }})</th>
            @endif
          </tr>
        </thead>
        <tbody>
          @foreach($piece->elements as $el)
            <tr class="{{ $el->degradation_detectee ? 'degradee' : '' }}">
              <td style="font-weight:bold">{{ $el->element }}</td>
              @if($etat->type === 'sortie' && $etat->etatEntree)
                <td class="text-center">
                  @php $elE = $entreeMap[$piece->nom_piece][$el->element] ?? null; @endphp
                  @if($elE)
                    <span class="badge {{ $elE->etat_badge_class }}">{{ $elE->etat_label }}</span>
                  @else <span style="color:#94a3b8">–</span>
                  @endif
                </td>
              @endif
              <td class="text-center">
                <span class="badge {{ $el->etat_badge_class }}">{{ $el->etat_label }}</span>
              </td>
              <td style="color:#475569">{{ $el->description ?: '–' }}</td>
              @if($etat->type === 'sortie')
                <td class="text-center">
                  @if($el->degradation_detectee)
                    <span class="badge bg-danger">OUI</span>
                  @else
                    <span class="badge bg-success">NON</span>
                  @endif
                </td>
                <td class="text-right" style="{{ $el->montant_retenue > 0 ? 'color:#dc2626;font-weight:bold' : 'color:#94a3b8' }}">
                  {{ $el->montant_retenue > 0 ? format_price($el->montant_retenue) : '–' }}
                </td>
              @endif
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endforeach

  {{-- ── Récapitulatif retenues ──────────────────────────────────────────── --}}
  @if($etat->type === 'sortie' && $etat->retenue_caution > 0)
    <div class="recap-titre">Récapitulatif des retenues sur caution</div>
    <table class="retenue">
      <thead>
        <tr>
          <th>Pièce</th>
          <th>Élément</th>
          <th>Description</th>
          <th class="text-right">Montant ({{ get_symbole_devise() }})</th>
        </tr>
      </thead>
      <tbody>
        @foreach($etat->pieces as $piece)
          @foreach($piece->elements->where('degradation_detectee', true) as $el)
            <tr>
              <td>{{ $piece->nom_piece }}</td>
              <td>{{ $el->element }}</td>
              <td style="color:#64748b">{{ $el->description ?: '–' }}</td>
              <td class="text-right">{{ format_price($el->montant_retenue) }}</td>
            </tr>
          @endforeach
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="text-right">TOTAL RETENU SUR CAUTION</td>
          <td class="text-right">{{ format_price($etat->retenue_caution) }}</td>
        </tr>
      </tfoot>
    </table>
  @endif

  {{-- ── Notes générales ─────────────────────────────────────────────────── --}}
  @if($etat->notes_generales)
    <div class="notes-block">
      <div class="notes-titre">Notes générales</div>
      {{ $etat->notes_generales }}
    </div>
  @endif

  {{-- ── Signatures ───────────────────────────────────────────────────────── --}}
  <div class="signatures">

    {{-- Locataire (gauche) --}}
    <div class="sig-block">
      <div class="sig-titre">Signature du locataire</div>
      <div class="sig-zone">
        @if($etat->signe_locataire)
          @if(!empty($etat->signature_locataire_image))
            <img src="{{ $etat->signature_locataire_image }}" alt="Signature" class="sig-img">
          @else
            <div class="sig-ok">✓ Signé électroniquement</div>
          @endif
        @else
          <div class="sig-en-attente">En attente de signature</div>
        @endif
      </div>
      <div class="sig-nom">{{ $etat->locataire->nom ?? '' }} {{ $etat->locataire->prenom ?? '' }}</div>
      @if($etat->signe_locataire)
        <div class="sig-date">Le {{ \Carbon\Carbon::parse($etat->signe_locataire_at)->format('d/m/Y à H:i') }}</div>
      @endif
    </div>

    {{-- Directeur (droite) — signature uniquement si l'agent a signé --}}
    <div class="sig-block">
      <div class="sig-titre">Signature du directeur</div>
      <div class="sig-zone">
        @if($etat->signe_agent)
          @if(!empty($agence['cash_electronique_image_base64']))
            <img src="{{ $agence['cash_electronique_image_base64'] }}" alt="Signature" class="sig-img">
          @else
            <div class="sig-ok">✓ Signé électroniquement</div>
          @endif
        @else
          <div class="sig-en-attente">En attente de signature</div>
        @endif
      </div>
      <div class="sig-nom">{{ $agence['designation'] ?? '' }}</div>
      @if($etat->signe_agent)
        <div class="sig-date">Le {{ \Carbon\Carbon::parse($etat->signe_agent_at)->format('d/m/Y à H:i') }}</div>
      @endif
    </div>

  </div>

  {{-- ── Pied de page ─────────────────────────────────────────────────────── --}}
  <div class="footer">
    <span>{{ config('app.name') }} &mdash; Document confidentiel</span>
    <span>Généré le {{ now()->format('d/m/Y à H:i') }}</span>
  </div>

</div>
</body>
</html>
