<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<style>
  * { box-sizing: border-box; }
  body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #333; margin: 0; padding: 0; }
  .page { padding: 20px 25px; }

  /* En-tête */
  .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 2px solid #696cff; padding-bottom: 12px; margin-bottom: 16px; }
  .agence-name { font-size: 14px; font-weight: bold; color: #696cff; }
  .agence-info { font-size: 9px; color: #666; margin-top: 3px; }
  .doc-type { text-align: right; }
  .doc-type-label { background: #696cff; color: white; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; }
  .doc-date { font-size: 9px; color: #666; margin-top: 4px; }

  /* Titre */
  .titre-principal { text-align: center; font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 12px 0; letter-spacing: 1px; }

  /* Infos parties */
  .parties { display: flex; gap: 16px; margin-bottom: 14px; }
  .partie-block { flex: 1; border: 1px solid #ddd; border-radius: 4px; padding: 8px; }
  .partie-titre { font-size: 9px; text-transform: uppercase; color: #696cff; font-weight: bold; margin-bottom: 4px; }
  .partie-valeur { font-size: 10px; font-weight: bold; }
  .partie-sous { font-size: 9px; color: #666; }

  /* Pièce titre */
  .piece-titre { background: #696cff; color: white; padding: 5px 10px; font-size: 10px; font-weight: bold; margin-top: 14px; margin-bottom: 0; border-radius: 4px 4px 0 0; }

  /* Tableau éléments */
  table { width: 100%; border-collapse: collapse; font-size: 9px; }
  table.elements { border: 1px solid #ccc; margin-bottom: 0; }
  table.elements th { background: #f0f0f0; padding: 4px 6px; font-size: 9px; text-align: center; border: 1px solid #ccc; }
  table.elements td { padding: 4px 6px; border: 1px solid #ccc; vertical-align: middle; }
  table.elements tr.degradee td { background: #fff3f3; }
  .th-left { text-align: left !important; }
  .text-center { text-align: center; }
  .text-right { text-align: right; }

  /* Badges état */
  .badge { display: inline-block; padding: 2px 6px; border-radius: 3px; font-size: 8px; font-weight: bold; }
  .bg-success { background: #28a745; color: white; }
  .bg-info { background: #17a2b8; color: white; }
  .bg-warning { background: #ffc107; color: #333; }
  .bg-danger { background: #dc3545; color: white; }
  .bg-dark { background: #343a40; color: white; }
  .bg-secondary { background: #6c757d; color: white; }

  /* Tableau récapitulatif retenue */
  table.retenue { border: 2px solid #dc3545; margin-top: 14px; }
  table.retenue th { background: #dc3545; color: white; padding: 5px 8px; }
  table.retenue td { padding: 4px 8px; border: 1px solid #f5c6cb; }
  table.retenue tfoot td { background: #f8d7da; font-weight: bold; }

  /* Notes */
  .notes-block { border: 1px solid #ddd; border-radius: 4px; padding: 8px; margin-top: 14px; font-size: 9px; }
  .notes-titre { font-weight: bold; margin-bottom: 4px; }

  /* Signatures */
  .signatures { display: flex; gap: 20px; margin-top: 30px; }
  .sig-block { flex: 1; border: 1px solid #ddd; border-radius: 4px; padding: 10px; text-align: center; }
  .sig-titre { font-size: 9px; font-weight: bold; text-transform: uppercase; color: #666; margin-bottom: 30px; }
  .sig-ligne { border-top: 1px solid #999; margin-top: 20px; padding-top: 4px; font-size: 8px; color: #666; }
  .sig-ok { color: #28a745; font-weight: bold; font-size: 9px; }

  .footer { margin-top: 16px; border-top: 1px solid #eee; padding-top: 6px; font-size: 8px; color: #999; text-align: center; }
</style>
</head>
<body>
<div class="page">

  {{-- En-tête --}}
  <div class="header">
    <div>
      <div class="agence-name">{{ $agence['designation'] ?? config('app.name') }}</div>
      @if(!empty($agence['adresse']))<div class="agence-info">{{ $agence['adresse'] }}</div>@endif
      @if(!empty($agence['telephone']))<div class="agence-info">Tél : {{ $agence['telephone'] }}</div>@endif
    </div>
    <div class="doc-type">
      <div class="doc-type-label">
        {{ $etat->type === 'entree' ? 'ÉTAT D\'ENTRÉE' : 'ÉTAT DE SORTIE' }}
      </div>
      <div class="doc-date">Date : {{ $etat->date_etat->format('d/m/Y') }}</div>
      <div class="doc-date">Statut : {{ ucfirst($etat->statut) }}</div>
    </div>
  </div>

  {{-- Titre --}}
  <div class="titre-principal">Procès-verbal d'état des lieux</div>

  {{-- Parties --}}
  <div class="parties">
    <div class="partie-block">
      <div class="partie-titre">Locataire</div>
      <div class="partie-valeur">{{ $etat->locataire->nom ?? '–' }} {{ $etat->locataire->prenom ?? '' }}</div>
      <div class="partie-sous">{{ $etat->locataire->telephone ?? '' }}</div>
      @if(!empty($etat->locataire->email))<div class="partie-sous">{{ $etat->locataire->email }}</div>@endif
    </div>
    <div class="partie-block">
      <div class="partie-titre">Logement</div>
      <div class="partie-valeur">{{ $etat->maison->nom_maison ?? '–' }}</div>
      <div class="partie-sous">Chambre N° {{ $etat->chambre->numero_chambre ?? '–' }}</div>
      @if(!empty($etat->maison->quartier))<div class="partie-sous">{{ $etat->maison->quartier }}</div>@endif
    </div>
    @if($etat->type === 'sortie' && $etat->etatEntree)
    <div class="partie-block">
      <div class="partie-titre">Référence entrée</div>
      <div class="partie-valeur">Entrée du {{ $etat->etatEntree->date_etat->format('d/m/Y') }}</div>
      <div class="partie-sous">Durée : {{ $etat->etatEntree->date_etat->diffInMonths($etat->date_etat) }} mois</div>
    </div>
    @endif
  </div>

  {{-- Pièces & éléments --}}
  @foreach($etat->pieces as $piece)
    @php $nbDegra = $piece->elements->where('degradation_detectee', true)->count(); @endphp
    <div class="piece-titre">
      {{ $piece->nom_piece }}
      @if($nbDegra > 0) — {{ $nbDegra }} dégradation(s) @endif
    </div>
    <table class="elements">
      <thead>
        <tr>
          <th class="th-left" style="width:18%">Élément</th>
          @if($etat->type === 'sortie' && $etat->etatEntree)
            <th style="width:13%">Entrée</th>
          @endif
          <th style="width:13%">État sortie</th>
          <th class="th-left">Observations</th>
          @if($etat->type === 'sortie')
            <th style="width:11%">Dégradation</th>
            <th style="width:12%">Retenue (F)</th>
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
                @else –
                @endif
              </td>
            @endif
            <td class="text-center">
              <span class="badge {{ $el->etat_badge_class }}">{{ $el->etat_label }}</span>
            </td>
            <td>{{ $el->description ?: '–' }}</td>
            @if($etat->type === 'sortie')
              <td class="text-center">
                @if($el->degradation_detectee)
                  <span class="badge bg-danger">OUI</span>
                @else
                  <span class="badge bg-success">NON</span>
                @endif
              </td>
              <td class="text-right" style="{{ $el->montant_retenue > 0 ? 'color:#dc3545;font-weight:bold' : '' }}">
                {{ $el->montant_retenue > 0 ? number_format($el->montant_retenue, 0, ',', ' ') : '–' }}
              </td>
            @endif
          </tr>
        @endforeach
      </tbody>
    </table>
  @endforeach

  {{-- Tableau récapitulatif retenues --}}
  @if($etat->type === 'sortie' && $etat->retenue_caution > 0)
    <table class="retenue">
      <thead>
        <tr>
          <th>Pièce</th><th>Élément</th><th>Description</th><th class="text-right">Montant retenu (F)</th>
        </tr>
      </thead>
      <tbody>
        @foreach($etat->pieces as $piece)
          @foreach($piece->elements->where('degradation_detectee', true) as $el)
            <tr>
              <td>{{ $piece->nom_piece }}</td>
              <td>{{ $el->element }}</td>
              <td>{{ $el->description ?: '–' }}</td>
              <td class="text-right">{{ number_format($el->montant_retenue, 0, ',', ' ') }}</td>
            </tr>
          @endforeach
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="text-right">TOTAL RETENU SUR CAUTION</td>
          <td class="text-right">{{ number_format($etat->retenue_caution, 0, ',', ' ') }} FCFA</td>
        </tr>
      </tfoot>
    </table>
  @endif

  {{-- Notes générales --}}
  @if($etat->notes_generales)
    <div class="notes-block">
      <div class="notes-titre">Notes générales :</div>
      <div>{{ $etat->notes_generales }}</div>
    </div>
  @endif

  {{-- Signatures --}}
  <div class="signatures">
    <div class="sig-block">
      <div class="sig-titre">Signature du locataire</div>
      @if($etat->signe_locataire)
        <div class="sig-ok">✓ Signé électroniquement</div>
        <div class="sig-ligne">{{ $etat->locataire->nom ?? '' }} {{ $etat->locataire->prenom ?? '' }}<br>
          Le {{ \Carbon\Carbon::parse($etat->signe_locataire_at)->format('d/m/Y à H:i') }}
        </div>
      @else
        <div style="height:40px;"></div>
        <div class="sig-ligne">{{ $etat->locataire->nom ?? '' }} {{ $etat->locataire->prenom ?? '' }}</div>
      @endif
    </div>
    <div class="sig-block">
      <div class="sig-titre">Signature de l'agent</div>
      @if($etat->signe_agent)
        @if(!empty($agence['cash_electronique_image_base64']))
          <img src="{{ $agence['cash_electronique_image_base64'] }}" alt="Signature" style="max-height:50px; max-width:120px; display:block; margin:0 auto 4px;">
        @else
          <div class="sig-ok">✓ Signé électroniquement</div>
        @endif
        <div class="sig-ligne">{{ $agence['designation'] ?? '' }}<br>
          Le {{ \Carbon\Carbon::parse($etat->signe_agent_at)->format('d/m/Y à H:i') }}
        </div>
      @else
        @if(!empty($agence['cash_electronique_image_base64']))
          <img src="{{ $agence['cash_electronique_image_base64'] }}" alt="Signature" style="max-height:50px; max-width:120px; display:block; margin:0 auto 4px; opacity:0.3;">
        @else
          <div style="height:40px;"></div>
        @endif
        <div class="sig-ligne">{{ $agence['designation'] ?? '' }}</div>
      @endif
    </div>
  </div>

  <div class="footer">
    Document généré le {{ now()->format('d/m/Y à H:i') }} – {{ config('app.name') }}
  </div>
</div>
</body>
</html>
