<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ __('sig.sign_page_title') }}</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    * { box-sizing: border-box; }
    body { background: #f0f2ff; font-family: 'Segoe UI', DejaVu Sans, sans-serif; font-size: 14px; color: #1e293b; }
    .sign-wrapper { max-width: 700px; margin: 30px auto 60px; padding: 0 12px; }
    .header-agence {
      background: linear-gradient(135deg, #696cff 0%, #5a5fba 100%);
      color: white; border-radius: 12px 12px 0 0;
      padding: 20px 24px; display: flex; justify-content: space-between;
      align-items: center; flex-wrap: wrap; gap: 12px;
    }
    .agence-name { font-size: 16px; font-weight: 700; }
    .agence-sub  { font-size: 12px; opacity: 0.8; margin-top: 2px; }
    .doc-badge {
      background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4);
      border-radius: 20px; padding: 5px 14px; font-size: 12px; font-weight: 700;
    }
    .main-card {
      background: white; border-radius: 0 0 12px 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.08); padding: 28px 24px;
    }
    .doc-info-block {
      background: #f8faff; border: 1px solid #e2e8f0; border-radius: 10px;
      padding: 16px 20px; margin-bottom: 20px;
    }
    .doc-info-block .label { font-size: 11px; font-weight: 700; text-transform: uppercase;
      letter-spacing: 1px; color: #696cff; margin-bottom: 3px; }
    .doc-info-block .value { font-weight: 600; color: #1e293b; font-size: 15px; }
    .doc-info-block .sub { color: #64748b; font-size: 12px; margin-top: 3px; }
    .sig-pad-card { border: 2px solid #696cff; border-radius: 12px; overflow: hidden; margin-top: 24px; }
    .sig-pad-header {
      background: linear-gradient(135deg, #696cff 0%, #5a5fba 100%);
      color: white; padding: 14px 20px; display: flex; justify-content: space-between; align-items: center;
    }
    .sig-pad-title { font-size: 13px; font-weight: 700; }
    .btn-clear {
      background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.4);
      color: white; border-radius: 8px; padding: 5px 14px; font-size: 12px; font-weight: 600; cursor: pointer;
    }
    .btn-clear:hover { background: rgba(255,255,255,0.35); }
    #canvasContainer { position: relative; background: #fafafe; touch-action: none; }
    #signaturePad { display: block; width: 100%; cursor: crosshair; }
    .sig-pad-footer { padding: 14px 20px; background: #f8faff; }
    .sig-valid-btn {
      width: 100%; padding: 14px; font-size: 15px; font-weight: 700;
      border-radius: 10px; border: none; cursor: pointer;
      background: linear-gradient(135deg, #696cff 0%, #5a5fba 100%);
      color: white; transition: opacity .2s;
    }
    .sig-valid-btn:hover { opacity: 0.9; }
    .sig-valid-btn:disabled { opacity: 0.5; cursor: not-allowed; }
    .legal-note { font-size: 11px; color: #94a3b8; text-align: center; margin-top: 12px; line-height: 1.6; }
    .expire-badge { background: #fef3c7; border: 1px solid #fde68a; border-radius: 8px;
      padding: 8px 14px; font-size: 12px; color: #92400e; }
  </style>
</head>
<body>

<div class="sign-wrapper">
  @php
    $agence = get_annexe_details_for_invoice($demande->idannexe_ref);
  @endphp

  <div class="header-agence">
    <div>
      <div class="agence-name">{{ $agence['designation'] ?? config('app.name') }}</div>
      @if(!empty($agence['email']))<div class="agence-sub">{{ $agence['email'] }}</div>@endif
    </div>
    <span class="doc-badge">{{ $demande->getDocumentTypeLabel() }}</span>
  </div>

  <div class="main-card">
    <p class="text-muted mb-3">{{ __('sig.sign_intro') }}</p>

    <div class="doc-info-block">
      <div class="row g-3">
        <div class="col-12">
          <div class="label">{{ __('sig.cert_document') }}</div>
          <div class="value">{{ $demande->document_titre }}</div>
          @if($demande->document_description)
            <div class="sub">{{ $demande->document_description }}</div>
          @endif
        </div>
        <div class="col-sm-6">
          <div class="label">{{ __('sig.cert_signataire') }}</div>
          <div class="value">{{ $demande->signataire_nom }}</div>
        </div>
        <div class="col-sm-6">
          @if($demande->expires_at)
            <div class="expire-badge">
              {{ __('sig.sign_expires', ['date' => $demande->expires_at->format('d/m/Y à H:i')]) }}
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Nom complet -->
    <div class="mb-3">
      <label class="form-label fw-semibold">{{ __('sig.sign_name') }} <span class="text-danger">*</span></label>
      <input type="text" id="nomComplet" class="form-control" placeholder="{{ $demande->signataire_nom }}"
             value="{{ $demande->signataire_nom }}">
    </div>

    <!-- Pad de signature -->
    <div class="sig-pad-card">
      <div class="sig-pad-header">
        <span class="sig-pad-title">{{ __('sig.sign_draw') }}</span>
        <button class="btn-clear" onclick="effacerSignature()" type="button">
          {{ __('sig.sign_clear') }}
        </button>
      </div>
      <div id="canvasContainer">
        <canvas id="signaturePad" height="180"></canvas>
      </div>
      <div class="sig-pad-footer">
        <button class="sig-valid-btn" id="btnValider" onclick="validerSignature()" type="button">
          ✍️ {{ __('sig.sign_btn') }}
        </button>
        <p class="legal-note">{{ __('sig.sign_legal') }}</p>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.2.0/dist/signature_pad.umd.min.js"></script>
<script>
const canvas    = document.getElementById('signaturePad');
const container = document.getElementById('canvasContainer');

function resizeCanvas() {
  const ratio = Math.max(window.devicePixelRatio || 1, 1);
  canvas.width  = container.offsetWidth * ratio;
  canvas.height = 180 * ratio;
  canvas.style.width  = container.offsetWidth + 'px';
  canvas.style.height = '180px';
  canvas.getContext('2d').scale(ratio, ratio);
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

const signaturePad = new SignaturePad(canvas, {
  minWidth: 1, maxWidth: 3, penColor: '#1e293b',
  backgroundColor: 'rgba(0,0,0,0)'
});

function effacerSignature() {
  signaturePad.clear();
}

async function validerSignature() {
  if (signaturePad.isEmpty()) {
    Swal.fire({
      icon: 'warning',
      title: '{{ __("common.swal_warning") }}',
      text: '{{ __("ui.edl.sig_missing_text") }}',
      confirmButtonColor: '#696cff'
    });
    return;
  }

  const nom = document.getElementById('nomComplet').value.trim();
  if (!nom) {
    Swal.fire({
      icon: 'warning',
      title: '{{ __("common.swal_warning") }}',
      text: '{{ __("sig.sign_name") }}',
      confirmButtonColor: '#696cff'
    });
    return;
  }

  const btn = document.getElementById('btnValider');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>{{ __("common.processing") }}';

  try {
    const r = await fetch('{{ route("signature.confirmer", $demande->token) }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}',
        'Accept': 'application/json',
      },
      body: JSON.stringify({
        signature_data: signaturePad.toDataURL('image/png'),
        nom_complet: nom,
      })
    });

    if (!r.ok) throw new Error('{{ __("common.swal_server_error") }} ' + r.status);
    const data = await r.json();

    if (data.status) {
      // Redirection vers la page de confirmation
      window.location.href = '{{ route("signature.confirme", $demande->token) }}';
    } else {
      Swal.fire('{{ __("common.swal_error") }}', data.message, 'error');
      btn.disabled = false;
      btn.innerHTML = '✍️ {{ __("sig.sign_btn") }}';
    }
  } catch (e) {
    Swal.fire('{{ __("common.swal_error") }}', e.message || '{{ __("common.swal_generic_error") }}', 'error');
    btn.disabled = false;
    btn.innerHTML = '✍️ {{ __("sig.sign_btn") }}';
  }
}
</script>
</body>
</html>
