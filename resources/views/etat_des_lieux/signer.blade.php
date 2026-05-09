<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('ui.edl.signer_page_title') }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * { box-sizing: border-box; }
    body { background: #f0f2ff; font-family: 'Segoe UI', DejaVu Sans, sans-serif; font-size: 14px; color: #1e293b; }
    .sign-wrapper { max-width: 860px; margin: 30px auto 60px; padding: 0 12px; }

    /* ── En-tête agence ─────────────────────────────────────────── */
    .header-agence {
      background: linear-gradient(135deg, #696cff 0%, #5a5fba 100%);
      color: white;
      border-radius: 12px 12px 0 0;
      padding: 20px 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 12px;
    }
    .header-logo-wrap { display: flex; align-items: center; gap: 14px; }
    .header-logo { max-height: 52px; max-width: 80px; object-fit: contain; border-radius: 6px; background: white; padding: 4px; }
    .header-logo-placeholder {
      width: 52px; height: 52px; border-radius: 8px;
      background: rgba(255,255,255,0.2);
      display: flex; align-items: center; justify-content: center;
      font-size: 22px; font-weight: bold;
    }
    .agence-name { font-size: 16px; font-weight: 700; }
    .agence-sub  { font-size: 12px; opacity: 0.8; margin-top: 2px; }
    .doc-badge {
      background: rgba(255,255,255,0.2);
      border: 1px solid rgba(255,255,255,0.4);
      border-radius: 20px;
      padding: 5px 14px;
      font-size: 12px;
      font-weight: 700;
      letter-spacing: 0.5px;
    }
    .doc-badge.sortie { background: rgba(245,158,11,0.3); border-color: rgba(245,158,11,0.6); }

    /* ── Card principale ────────────────────────────────────────── */
    .main-card {
      background: white;
      border-radius: 0 0 12px 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08);
      padding: 24px;
    }

    /* ── Titre doc ──────────────────────────────────────────────── */
    .titre-doc {
      text-align: center;
      font-size: 15px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 2px;
      color: #1e293b;
      padding-bottom: 14px;
      border-bottom: 1px dashed #cbd5e1;
      margin-bottom: 20px;
    }

    /* ── Parties ────────────────────────────────────────────────── */
    .parties { display: flex; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    .partie-block {
      flex: 1; min-width: 220px;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 12px 14px;
      background: #f8faff;
    }
    .partie-titre {
      font-size: 10px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #696cff;
      margin-bottom: 6px;
      padding-bottom: 5px;
      border-bottom: 1px solid #e2e8f0;
    }
    .partie-valeur { font-weight: 700; font-size: 14px; color: #1e293b; }
    .partie-sous { font-size: 12px; color: #64748b; margin-top: 3px; }

    /* ── Pièce ──────────────────────────────────────────────────── */
    .piece-wrap { margin-top: 18px; }
    .piece-titre {
      background: linear-gradient(90deg, #696cff 0%, #8b8eff 100%);
      color: white;
      padding: 7px 14px;
      font-size: 13px;
      font-weight: 700;
      border-radius: 7px 7px 0 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .piece-degra-badge {
      background: rgba(255,255,255,0.25);
      border-radius: 12px;
      padding: 2px 10px;
      font-size: 11px;
    }

    /* ── Tableau éléments ───────────────────────────────────────── */
    .table-elements { font-size: 12px; border: 1px solid #e2e8f0; border-radius: 0 0 7px 7px; overflow: hidden; }
    .table-elements th {
      background: #f1f5f9;
      font-size: 11px;
      font-weight: 700;
      color: #475569;
      padding: 7px 10px;
      text-align: center;
      border-bottom: 1px solid #e2e8f0;
    }
    .table-elements th.th-left { text-align: left; }
    .table-elements td { padding: 6px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .table-elements tbody tr:last-child td { border-bottom: none; }
    .table-elements tbody tr:nth-child(even) td { background: #f8faff; }
    .table-elements tbody tr.degradee td { background: #fff5f5; }

    /* ── Badges ─────────────────────────────────────────────────── */
    .badge-el {
      display: inline-block; padding: 3px 9px; border-radius: 12px;
      font-size: 11px; font-weight: 700;
    }
    .b-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .b-info    { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .b-warning { background: #fef9c3; color: #a16207; border: 1px solid #fde68a; }
    .b-danger  { background: #fee2e2; color: #dc2626; border: 1px solid #fecaca; }
    .b-dark    { background: #f1f5f9; color: #374151; border: 1px solid #d1d5db; }
    .b-secondary { background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; }

    /* ── Retenue ────────────────────────────────────────────────── */
    .retenue-header {
      background: #fee2e2;
      border: 1px solid #fecaca;
      border-radius: 7px 7px 0 0;
      padding: 7px 14px;
      font-size: 13px;
      font-weight: 700;
      color: #dc2626;
      margin-top: 20px;
    }
    .table-retenue { font-size: 12px; border: 1px solid #fecaca; border-radius: 0 0 7px 7px; overflow: hidden; }
    .table-retenue th { background: #dc2626; color: white; padding: 7px 10px; font-size: 11px; font-weight: 700; }
    .table-retenue td { padding: 6px 10px; border-bottom: 1px solid #fef2f2; }
    .table-retenue tfoot td {
      background: #fee2e2; font-weight: 700; font-size: 13px; color: #dc2626; border-bottom: none;
    }

    /* ── Notes ──────────────────────────────────────────────────── */
    .notes-block {
      border-left: 3px solid #696cff;
      background: #f8faff;
      border-radius: 0 8px 8px 0;
      padding: 10px 14px;
      margin-top: 18px;
      font-size: 13px;
      color: #475569;
    }

    /* ── Bloc signature pad ─────────────────────────────────────── */
    .sig-pad-card {
      margin-top: 30px;
      border: 2px solid #696cff;
      border-radius: 12px;
      overflow: hidden;
    }
    .sig-pad-header {
      background: linear-gradient(135deg, #696cff 0%, #5a5fba 100%);
      color: white;
      padding: 16px 20px;
      text-align: center;
    }
    .sig-pad-header h5 { margin: 0; font-size: 17px; font-weight: 700; }
    .sig-pad-header p  { margin: 6px 0 0; font-size: 13px; opacity: 0.85; }
    .sig-pad-body { padding: 20px; background: white; }
    .sig-identity {
      background: #f8faff;
      border: 1px solid #e2e8f0;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 13px;
      color: #475569;
      margin-bottom: 16px;
      text-align: center;
    }
    .canvas-container {
      border: 2px dashed #cbd5e1;
      border-radius: 8px;
      background: #fafafa;
      position: relative;
      cursor: crosshair;
    }
    .canvas-container canvas { display: block; border-radius: 6px; touch-action: none; }
    .canvas-hint {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 13px;
      color: #94a3b8;
      pointer-events: none;
      text-align: center;
      transition: opacity 0.2s;
    }
    .sig-actions { display: flex; gap: 10px; margin-top: 14px; flex-wrap: wrap; }
    .sig-actions .btn { flex: 1; min-width: 120px; }
    .btn-clear { color: #475569; border-color: #cbd5e1; }
    .btn-clear:hover { background: #f1f5f9; }
    .btn-valider { font-size: 15px; font-weight: 600; padding: 12px; }

    /* ── Déjà signé ─────────────────────────────────────────────── */
    .signe-block { background: #d4edda; border-radius: 12px; padding: 36px 20px; text-align: center; }

    @media (max-width: 600px) {
      .parties { flex-direction: column; }
      .header-agence { flex-direction: column; text-align: center; }
    }
  </style>
</head>
<body>
<div class="sign-wrapper">

  {{-- ── Déjà signé ──────────────────────────────────────────────────────── --}}
  @if(!empty($dejaSigné))
    <div class="header-agence">
      <div class="header-logo-wrap">
        @if(!empty($agence['logo_base64'] ?? null))
          <img src="{{ $agence['logo_base64'] }}" alt="Logo" class="header-logo">
        @else
          <div class="header-logo-placeholder">{{ mb_substr($etat->idannexe_ref ?? 'L', 0, 1) }}</div>
        @endif
        <div>
          <div class="agence-name">{{ $agence['designation'] ?? config('app.name') }}</div>
        </div>
      </div>
    </div>
    <div class="main-card">
      <div class="signe-block">
        <div style="font-size:48px; margin-bottom:12px">✅</div>
        <h4 class="text-success fw-bold">{{ __('ui.edl.already_signed') }}</h4>
        <p class="text-muted">
          {{ __('ui.edl.already_signed_text', ['date' => \Carbon\Carbon::parse($etat->signe_locataire_at)->format('d/m/Y à H:i')]) }}
        </p>
        <p class="text-muted small">{{ __('ui.edl.thank_signature') }}</p>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
  </html>
  @php return; @endphp
  @endif

  {{-- ── En-tête agence ──────────────────────────────────────────────────── --}}
  <div class="header-agence">
    <div class="header-logo-wrap">
      @if(!empty($agence['logo_base64']))
        <img src="{{ $agence['logo_base64'] }}" alt="Logo" class="header-logo">
      @else
        <div class="header-logo-placeholder">{{ mb_substr($agence['designation'] ?? 'L', 0, 1) }}</div>
      @endif
      <div>
        <div class="agence-name">{{ $agence['designation'] ?? config('app.name') }}</div>
        @if(!empty($agence['adresse']))<div class="agence-sub">{{ $agence['adresse'] }}</div>@endif
        @if(!empty($agence['telephone']))<div class="agence-sub">{{ $agence['telephone'] }}</div>@endif
      </div>
    </div>
    <div>
      <span class="doc-badge {{ $etat->type === 'sortie' ? 'sortie' : '' }}">
        {{ $etat->type === 'entree' ? "État d'entrée" : "État de sortie" }}
      </span>
      <div style="font-size:12px; opacity:0.8; text-align:right; margin-top:4px">
        {{ $etat->date_etat->format('d/m/Y') }}
      </div>
    </div>
  </div>

  {{-- ── Carte principale ─────────────────────────────────────────────────── --}}
  <div class="main-card">

    <div class="titre-doc">{{ __('ui.edl.pv_title') }}</div>

    {{-- Parties --}}
    <div class="parties">
      <div class="partie-block">
        <div class="partie-titre">👤 {{ __('ui.edl.partie_tenant') }}</div>
        <div class="partie-valeur">{{ $etat->locataire->nom ?? '–' }} {{ $etat->locataire->prenom ?? '' }}</div>
        @if(!empty($etat->locataire->telephone))<div class="partie-sous">📞 {{ $etat->locataire->telephone }}</div>@endif
        @if(!empty($etat->locataire->email))<div class="partie-sous">✉ {{ $etat->locataire->email }}</div>@endif
      </div>
      <div class="partie-block">
        <div class="partie-titre">🏠 {{ __('ui.edl.partie_property') }}</div>
        <div class="partie-valeur">{{ $etat->maison->nom_maison ?? '–' }}</div>
        <div class="partie-sous">{{ __('ui.edl.partie_room') }} {{ $etat->chambre->numero_chambre ?? '–' }}</div>
        @if(!empty($etat->maison->quartier))<div class="partie-sous">📍 {{ $etat->maison->quartier }}</div>@endif
      </div>
      @if($etat->type === 'sortie' && $etat->etatEntree)
      <div class="partie-block">
        <div class="partie-titre">📋 {{ __('ui.edl.partie_entry_ref') }}</div>
        <div class="partie-valeur">{{ __('ui.edl.entry_on') }} {{ $etat->etatEntree->date_etat->format('d/m/Y') }}</div>
        <div class="partie-sous">{{ __('ui.edl.occupation_months', ['n' => $etat->etatEntree->date_etat->diffInMonths($etat->date_etat)]) }}</div>
      </div>
      @endif
    </div>

    {{-- Pièces & éléments --}}
    @foreach($etat->pieces as $piece)
      @php $nbDegra = $piece->elements->where('degradation_detectee', true)->count(); @endphp
      <div class="piece-wrap">
        <div class="piece-titre">
          <span>{{ $piece->nom_piece }}</span>
          @if($nbDegra > 0)
            <span class="piece-degra-badge">⚠ {{ $nbDegra }} dégradation(s)</span>
          @endif
        </div>
        <div class="table-responsive table-elements">
          <table class="table table-sm mb-0" style="font-size:12px">
            <thead>
              <tr>
                <th class="th-left" style="width:20%; text-align:left">{{ __('ui.edl.element') }}</th>
                @if($etat->type === 'sortie' && $etat->etatEntree)
                  <th style="width:13%">{{ __('ui.edl.signer_entry_col') }}</th>
                @endif
                <th style="width:13%">{{ __('ui.edl.signer_exit_col') }}</th>
                <th class="th-left" style="text-align:left">Observations</th>
                @if($etat->type === 'sortie')
                  <th style="width:11%">{{ __('ui.edl.damage_col') }}</th>
                  <th style="width:14%; text-align:right">{{ __('ui.edl.deposit_col') }}</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($piece->elements as $el)
                <tr class="{{ $el->degradation_detectee ? 'degradee' : '' }}">
                  <td style="font-weight:700">{{ $el->element }}</td>
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
                    <td style="text-align:right; {{ $el->montant_retenue > 0 ? 'color:#dc2626;font-weight:700' : 'color:#94a3b8' }}">
                      {{ $el->montant_retenue > 0 ? format_price($el->montant_retenue, $etat->iddirection_ref ?? null) : '–' }}
                    </td>
                  @endif
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    @endforeach

    {{-- Retenue --}}
    @if($etat->type === 'sortie' && $etat->retenue_caution > 0)
      <div class="retenue-header">{{ __('ui.edl.deposit_recap') }}</div>
      <div class="table-responsive table-retenue">
        <table class="table table-sm mb-0" style="font-size:12px">
          <thead>
            <tr>
              <th>{{ __('ui.edl.recap_room') }}</th>
              <th>{{ __('ui.edl.recap_element') }}</th>
              <th>{{ __('ui.edl.recap_damage') }}</th>
              <th style="text-align:right">{{ __('ui.edl.recap_amount') }}</th>
            </tr>
          </thead>
          <tbody>
            @foreach($etat->pieces as $piece)
              @foreach($piece->elements->where('degradation_detectee', true) as $el)
                <tr>
                  <td>{{ $piece->nom_piece }}</td>
                  <td>{{ $el->element }}</td>
                  <td style="color:#64748b">{{ $el->description ?: '–' }}</td>
                  <td style="text-align:right">{{ format_price($el->montant_retenue, $etat->iddirection_ref ?? null) }}</td>
                </tr>
              @endforeach
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3" style="text-align:right; font-weight:700; color:#dc2626">{{ __('ui.edl.deposit_total') }}</td>
              <td style="text-align:right; font-weight:700; color:#dc2626">{{ format_price($etat->retenue_caution, $etat->iddirection_ref ?? null) }}</td>
            </tr>
          </tfoot>
        </table>
      </div>
    @endif

    {{-- Notes --}}
    @if($etat->notes_generales)
      <div class="notes-block">
        <strong style="color:#696cff">{{ __('ui.edl.notes_section') }}</strong> {{ $etat->notes_generales }}
      </div>
    @endif

    {{-- ── Bloc signature pad ───────────────────────────────────────────────── --}}
    <div class="sig-pad-card" id="sigPadCard">
      <div class="sig-pad-header">
        <h5>✍ {{ __('ui.edl.sig_pad_title') }}</h5>
        <p>{{ __('ui.edl.sig_pad_subtitle') }}</p>
      </div>
      <div class="sig-pad-body">
        <div class="sig-identity">
          <strong>{{ $etat->locataire->nom ?? '' }} {{ $etat->locataire->prenom ?? '' }}</strong>
          &nbsp;—&nbsp; Le {{ now()->format('d/m/Y') }}
        </div>
        <div class="canvas-container" id="canvasContainer">
          <canvas id="signaturePad"></canvas>
          <div class="canvas-hint" id="canvasHint">
            ✍ {{ __('ui.edl.sig_hint') }}
          </div>
        </div>
        <div class="sig-actions">
          <button type="button" class="btn btn-outline-secondary btn-clear" id="btnEffacer">
            🗑 {{ __('ui.edl.sig_clear') }}
          </button>
          <button type="button" class="btn btn-success btn-valider" id="btnValider">
            ✅ {{ __('ui.edl.sig_validate') }}
          </button>
        </div>
      </div>
    </div>

  </div>{{-- fin .main-card --}}

  <div class="text-center text-muted small mt-3 mb-4" style="font-size:11px">
    {{ __('ui.edl.sig_confidential') }}
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.2.0/dist/signature_pad.umd.min.js"></script>
<script>
// ── Initialisation signature_pad ──────────────────────────────────────────────
const canvas    = document.getElementById('signaturePad');
const container = document.getElementById('canvasContainer');
const hint      = document.getElementById('canvasHint');

function resizeCanvas() {
    const ratio  = Math.max(window.devicePixelRatio || 1, 1);
    const w      = container.offsetWidth;
    const h      = Math.max(160, Math.round(w * 0.3));
    canvas.width  = w * ratio;
    canvas.height = h * ratio;
    canvas.style.width  = w + 'px';
    canvas.style.height = h + 'px';
    canvas.getContext('2d').scale(ratio, ratio);
    signaturePad.clear();
}

const signaturePad = new SignaturePad(canvas, {
    minWidth: 1,
    maxWidth: 3,
    penColor: '#1e293b',
    backgroundColor: 'rgba(0,0,0,0)',
});

signaturePad.addEventListener('beginStroke', () => {
    hint.style.opacity = '0';
});

resizeCanvas();
window.addEventListener('resize', resizeCanvas);

// ── Effacer ───────────────────────────────────────────────────────────────────
document.getElementById('btnEffacer').addEventListener('click', () => {
    signaturePad.clear();
    hint.style.opacity = '1';
});

// ── Valider et signer ─────────────────────────────────────────────────────────
document.getElementById('btnValider').addEventListener('click', function () {
    if (signaturePad.isEmpty()) {
        Swal.fire({
            icon: 'warning',
            title: '{{ __("ui.edl.sig_missing_title") }}',
            text: '{{ __("ui.edl.sig_missing_text") }}',
            confirmButtonColor: '#696cff',
        });
        return;
    }

    Swal.fire({
        title: '{{ __("ui.edl.sig_confirm_title") }}',
        html: '{!! __("ui.edl.sig_confirm_html") !!}',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#198754',
        confirmButtonText: '{{ __("ui.edl.sig_confirm_btn") }}',
        cancelButtonText: '{{ __("ui.common.cancel") }}',
    }).then(result => {
        if (!result.isConfirmed) return;

        const btn     = document.getElementById('btnValider');
        const effacer = document.getElementById('btnEffacer');
        btn.disabled     = true;
        effacer.disabled = true;
        btn.innerHTML    = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("ui.edl.sig_validating") }}';

        const signatureImage = signaturePad.toDataURL('image/png');

        fetch('{{ route("etat_des_lieux.signer_confirmer", $token) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ signature_image: signatureImage }),
        })
        .then(r => {
            if (!r.ok) {
                return r.text().then(txt => {
                    // Extraire le message si c'est du JSON, sinon code HTTP
                    try {
                        const j = JSON.parse(txt);
                        throw new Error(j.message || ('{{ __("common.swal_error") }} ' + r.status));
                    } catch(_) {
                        throw new Error('{{ __("common.swal_server_error") }} ' + r.status);
                    }
                });
            }
            return r.json();
        })
        .then(data => {
            if (data.status) {
                document.getElementById('sigPadCard').outerHTML = `
                  <div class="signe-block mt-4">
                    <div style="font-size:48px;margin-bottom:12px">✅</div>
                    <h4 class="text-success fw-bold">{{ __("ui.edl.sig_done_title") }}</h4>
                    <p class="text-muted">{{ __("ui.edl.sig_done_text", ["nom" => ($etat->locataire->nom ?? "") . " " . ($etat->locataire->prenom ?? "")]) }}</p>
                  </div>`;
            } else {
                Swal.fire('{{ __("common.swal_error") }}', data.message, 'error');
                btn.disabled     = false;
                effacer.disabled = false;
                btn.innerHTML    = '✅ {{ __("ui.edl.sig_validate") }}';
            }
        })
        .catch(err => {
            Swal.fire('{{ __("common.swal_error") }}', err.message || '{{ __("common.swal_generic_error") }}', 'error');
            btn.disabled     = false;
            effacer.disabled = false;
            btn.innerHTML    = '✅ {{ __("ui.edl.sig_validate") }}';
        });
    });
});
</script>
</body>
</html>
