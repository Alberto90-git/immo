@extends('layouts.template')

@section('title')
<title>État des lieux – {{ $etat->locataire->nom ?? '' }} – Lokativ</title>
@endsection

@section('content')
@include('notification.display_message')

<div class="container-xxl flex-grow-1 container-p-y">
  {{-- ── En-tête ──────────────────────────────────────────────────────────── --}}
  <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
    <div class="flex-grow-1">
      <h4 class="fw-bold mb-0">
        <span class="text-muted fw-light">États des lieux /</span>
        {{ $etat->type_label }}
        @if($etat->type === 'entree')
          <span class="badge bg-primary ms-1">Entrée</span>
        @else
          <span class="badge bg-warning text-dark ms-1">Sortie</span>
        @endif
        <span id="statutBadge">{!! $etat->statut_badge !!}</span>
      </h4>
    </div>
    <a href="{{ route('etat_des_lieux.index') }}" class="btn btn-icon btn-outline-secondary" title="Retour">
      <i class="bx bx-arrow-back"></i>
    </a>
    <div class="d-flex gap-2 flex-wrap">
      @can('download-etat-des-lieux')
        <button type="button" class="btn btn-outline-secondary" id="btnPreviewPdf" data-id="{{ $etat->id }}">
          <i class="bx bx-file-pdf me-1"></i> PDF
        </button>
      @endcan
      @can('modify-etat-des-lieux')
        <button type="button" class="btn btn-primary" id="btnFinaliser" data-id="{{ $etat->id }}"
                style="{{ $etat->statut !== 'brouillon' ? 'display:none' : '' }}">
          <i class="bx bx-check-circle me-1"></i> Finaliser
        </button>
        <button type="button" class="btn btn-success" id="btnEnvoyerSignature" data-id="{{ $etat->id }}"
                style="{{ (!in_array($etat->statut, ['finalise','signe']) || $etat->signe_locataire) ? 'display:none' : '' }}">
          <i class="bx bx-send me-1"></i> Envoyer pour signature
        </button>
      @endcan
    </div>
  </div>

  {{-- ── Carte récapitulatif ──────────────────────────────────────────────── --}}
  <div class="row g-3 mb-4">
    <div class="col-md-7">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h6 class="text-muted text-uppercase small mb-3">Locataire & logement</h6>
          <div class="row g-2">
            <div class="col-6">
              <div class="text-muted small">Locataire</div>
              <div class="fw-semibold">{{ $etat->locataire->nom ?? '–' }} {{ $etat->locataire->prenom ?? '' }}</div>
            </div>
            <div class="col-6">
              <div class="text-muted small">Téléphone</div>
              <div>{{ $etat->locataire->telephone ?? '–' }}</div>
            </div>
            <div class="col-6">
              <div class="text-muted small">Maison</div>
              <div>{{ $etat->maison->nom_maison ?? '–' }}</div>
            </div>
            <div class="col-6">
              <div class="text-muted small">Chambre</div>
              <div>N° {{ $etat->chambre->numero_chambre ?? '–' }}</div>
            </div>
            <div class="col-6">
              <div class="text-muted small">Date de l'état</div>
              <div class="fw-semibold">{{ $etat->date_etat->format('d/m/Y') }}</div>
            </div>
            @if($etat->type === 'sortie' && $etat->etatEntree)
              <div class="col-6">
                <div class="text-muted small">Référence entrée</div>
                <div>
                  <a href="{{ route('etat_des_lieux.show', $etat->etatEntree->id) }}" class="text-primary">
                    Entrée du {{ $etat->etatEntree->date_etat->format('d/m/Y') }}
                  </a>
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    {{-- Signature & Retenue --}}
    <div class="col-md-5">
      <div class="card h-100 shadow-sm">
        <div class="card-body">
          <h6 class="text-muted text-uppercase small mb-3">Signatures & Retenue</h6>
          <div class="d-flex align-items-center gap-2 mb-2">
            @if($etat->signe_locataire)
              <i class="bx bx-check-circle text-success fs-5"></i>
              <div>
                <div class="fw-semibold small">Locataire</div>
                <div class="text-muted small">Signé le {{ \Carbon\Carbon::parse($etat->signe_locataire_at)->format('d/m/Y à H:i') }}</div>
              </div>
            @else
              <i class="bx bx-time text-warning fs-5"></i>
              <div class="small text-muted">En attente de signature locataire</div>
            @endif
          </div>
          <div class="d-flex align-items-center gap-2 mb-3">
            {{-- État : brouillon, pas encore signé --}}
            <span id="sigAgentBrouillon" class="d-flex align-items-center gap-2"
                  style="{{ ($etat->statut !== 'brouillon' || $etat->signe_agent) ? 'display:none !important' : '' }}">
              <i class="bx bx-minus-circle text-secondary fs-5"></i>
              <span class="small text-muted">Signature après finalisation</span>
            </span>
            {{-- État : finalisé, pas encore signé par l'agent --}}
            @can('modify-etat-des-lieux')
              <button type="button" class="btn btn-sm btn-outline-primary" id="btnSignerAgent" data-id="{{ $etat->id }}"
                      style="{{ ($etat->signe_agent || $etat->statut === 'brouillon') ? 'display:none' : '' }}">
                <i class="bx bx-pen me-1"></i> Signer en tant qu'agent
              </button>
            @endcan
            {{-- État : signé --}}
            <span id="sigAgentOk" class="d-flex align-items-center gap-2"
                  style="{{ !$etat->signe_agent ? 'display:none !important' : '' }}">
              <i class="bx bx-check-circle text-success fs-5"></i>
              <span>
                <div class="fw-semibold small">Agent</div>
                <div class="text-muted small" id="sigAgentDate">
                  Signé le {{ $etat->signe_agent ? \Carbon\Carbon::parse($etat->signe_agent_at)->format('d/m/Y à H:i') : '' }}
                </div>
              </span>
            </span>
          </div>

          @if($etat->type === 'sortie')
            <hr class="my-2">
            <div class="d-flex justify-content-between align-items-center">
              <span class="text-muted small">Retenue sur caution</span>
              <span class="fw-bold {{ $etat->retenue_caution > 0 ? 'text-danger' : 'text-success' }} fs-6">
                {{ number_format($etat->retenue_caution, 0, ',', ' ') }} FCFA
              </span>
            </div>
            @if($etat->retenue_caution > 0)
              <div class="small text-danger mt-1">
                <i class="bx bx-error-circle me-1"></i>
                {{ $etat->pieces->sum(fn($p) => $p->elements->where('degradation_detectee', true)->count()) }} dégradation(s) détectée(s)
              </div>
            @endif
          @endif

          {{-- Lien de signature masqué (champ conservé pour usage JS) --}}
          <input type="hidden" id="lienSignature"
                 value="{{ $etat->signature_token ? route('etat_des_lieux.signer', $etat->signature_token) : '' }}">
        </div>
      </div>
    </div>
  </div>

  {{-- ── Notes générales --}}
  @if($etat->notes_generales)
    <div class="alert alert-light border mb-4">
      <strong><i class="bx bx-note me-1"></i>Notes générales :</strong>
      {{ $etat->notes_generales }}
    </div>
  @endif

  {{-- ── Pièces ──────────────────────────────────────────────────────────────── --}}
  @foreach($etat->pieces as $piece)
    @php $photos = $piece->photos; @endphp
    <div class="card mb-3 shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">
          <i class="bx bx-home me-2 text-primary"></i>{{ $piece->nom_piece }}
          @php $nbDegra = $piece->elements->where('degradation_detectee', true)->count(); @endphp
          @if($nbDegra > 0)
            <span class="badge bg-danger ms-2">{{ $nbDegra }} dégradation(s)</span>
          @endif
        </h6>
        @can('modify-etat-des-lieux')
          @if($etat->statut === 'brouillon')
            @php $nbPhotos = $piece->photos->count(); @endphp
            <button type="button"
                    class="btn btn-sm {{ $nbPhotos >= 2 ? 'btn-secondary' : 'btn-outline-primary' }} btn-ajout-photos"
                    data-piece-id="{{ $piece->id }}"
                    data-piece-nom="{{ $piece->nom_piece }}"
                    data-etat-id="{{ $etat->id }}"
                    data-nb-photos="{{ $nbPhotos }}"
                    {{ $nbPhotos >= 2 ? 'disabled' : '' }}>
              <i class="bx bx-image-add me-1"></i>
              Photos <span class="badge bg-white text-dark ms-1">{{ $nbPhotos }}/2</span>
            </button>
          @endif
        @endcan
      </div>
      <div class="card-body p-0">

        {{-- Tableau des éléments --}}
        <div class="table-responsive">
          <table class="table table-bordered mb-0 align-middle">
            <thead class="table-light text-center small">
              <tr>
                <th class="text-start ps-3" style="width:20%">Élément</th>
                @if($etat->type === 'sortie' && $etat->etatEntree)
                  <th style="width:14%">Entrée</th>
                @endif
                <th style="width:14%">État</th>
                <th class="text-start">Observations</th>
                @if($etat->type === 'sortie')
                  <th style="width:12%">Dégradation</th>
                  <th style="width:13%">Retenue</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($piece->elements as $element)
                <tr class="{{ $element->degradation_detectee ? 'table-danger' : '' }}">
                  <td class="fw-semibold ps-3">{{ $element->element }}</td>
                  @if($etat->type === 'sortie' && $etat->etatEntree)
                    <td class="text-center">
                      @php $elEntree = $entreeMap[$piece->nom_piece][$element->element] ?? null; @endphp
                      @if($elEntree)
                        <span class="badge {{ $elEntree->etat_badge_class }}">{{ $elEntree->etat_label }}</span>
                      @else
                        <span class="text-muted">–</span>
                      @endif
                    </td>
                  @endif
                  <td class="text-center">
                    <span class="badge {{ $element->etat_badge_class }}">{{ $element->etat_label }}</span>
                  </td>
                  <td class="small">{{ $element->description ?: '–' }}</td>
                  @if($etat->type === 'sortie')
                    <td class="text-center">
                      @if($element->degradation_detectee)
                        <span class="badge bg-danger"><i class="bx bx-error-circle me-1"></i>Oui</span>
                      @else
                        <span class="badge bg-success">Non</span>
                      @endif
                    </td>
                    <td class="text-center fw-semibold {{ $element->montant_retenue > 0 ? 'text-danger' : '' }}">
                      {{ $element->montant_retenue > 0 ? number_format($element->montant_retenue, 0, ',', ' ') . ' F' : '–' }}
                    </td>
                  @endif
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Galerie photos --}}
        <div class="p-3 border-top galerie-piece" id="galerie_piece_{{ $piece->id }}"
             data-piece-id="{{ $piece->id }}"
             style="{{ $photos->isEmpty() ? 'display:none;' : '' }}">
          <div class="d-flex flex-wrap gap-2" id="photos_piece_{{ $piece->id }}">
            @foreach($photos as $photo)
              <div class="position-relative" id="photoCard{{ $photo->id }}">
                <a href="{{ $photo->url }}" target="_blank">
                  <img src="{{ $photo->url }}" alt="{{ $photo->nom_original }}"
                       class="rounded border"
                       style="width:90px; height:70px; object-fit:cover;">
                </a>
                @can('modify-etat-des-lieux')
                  @if($etat->statut === 'brouillon')
                    <button type="button"
                            class="btn btn-sm btn-danger btn-suppr-photo position-absolute top-0 end-0 p-0"
                            style="width:18px;height:18px;font-size:10px;line-height:1;"
                            data-photo-id="{{ $photo->id }}"
                            data-piece-id="{{ $piece->id }}">
                      &times;
                    </button>
                  @endif
                @endcan
              </div>
            @endforeach
          </div>
        </div>

      </div>
    </div>
  @endforeach

  {{-- Récapitulatif dégradations (sortie uniquement) --}}
  @if($etat->type === 'sortie' && $etat->retenue_caution > 0)
    <div class="card border-danger mb-4">
      <div class="card-header bg-danger text-white">
        <h6 class="mb-0"><i class="bx bx-money me-2"></i>Récapitulatif des retenues sur caution</h6>
      </div>
      <div class="card-body p-0">
        <table class="table mb-0">
          <thead class="table-light">
            <tr><th>Pièce</th><th>Élément</th><th>Dégradation</th><th class="text-end">Montant retenu</th></tr>
          </thead>
          <tbody>
            @foreach($etat->pieces as $piece)
              @foreach($piece->elements->where('degradation_detectee', true) as $el)
                <tr>
                  <td>{{ $piece->nom_piece }}</td>
                  <td>{{ $el->element }}</td>
                  <td>{{ $el->description ?: '–' }}</td>
                  <td class="text-end text-danger fw-semibold">{{ number_format($el->montant_retenue, 0, ',', ' ') }} FCFA</td>
                </tr>
              @endforeach
            @endforeach
          </tbody>
          <tfoot class="table-danger">
            <tr>
              <td colspan="3" class="fw-bold text-end">Total retenu</td>
              <td class="text-end fw-bold text-danger">{{ number_format($etat->retenue_caution, 0, ',', ' ') }} FCFA</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  @endif

</div>{{-- end container --}}

{{-- Modal upload photos (fichier + caméra) --}}
<div class="modal fade" id="modalUploadPhotos" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUploadTitre">Ajouter des photos</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="uploadLimitAlert" class="alert alert-info py-2 mb-3" style="display:none;"></div>

        {{-- Tabs --}}
        <ul class="nav nav-tabs mb-3" id="uploadTabs">
          <li class="nav-item">
            <button class="nav-link active" id="tab-fichier-btn" data-bs-toggle="tab" data-bs-target="#tab-fichier" type="button">
              <i class="bx bx-upload me-1"></i> Fichier
            </button>
          </li>
          <li class="nav-item">
            <button class="nav-link" id="tab-camera-btn" data-bs-toggle="tab" data-bs-target="#tab-camera" type="button">
              <i class="bx bx-camera me-1"></i> Caméra
            </button>
          </li>
        </ul>

        <div class="tab-content">
          {{-- Onglet fichier --}}
          <div class="tab-pane fade show active" id="tab-fichier">
            <input type="file" id="inputPhotos" class="form-control" multiple accept="image/jpeg,image/jpg,image/png,image/webp">
            <div class="form-text">Formats : jpeg, jpg, png, webp – max 5 Mo par photo</div>
          </div>

          {{-- Onglet caméra --}}
          <div class="tab-pane fade" id="tab-camera">
            <div class="text-center">
              <video id="cameraStream" autoplay playsinline muted
                     class="rounded border w-100" style="max-height:240px; display:none;"></video>
              <canvas id="cameraCanvas" style="display:none;"></canvas>
              <div id="cameraPreview" class="d-flex flex-wrap gap-2 justify-content-center mt-2"></div>
              <div id="cameraError" class="text-danger small mt-2" style="display:none;"></div>
              <div class="d-flex gap-2 justify-content-center mt-2">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="btnDemarrerCamera">
                  <i class="bx bx-camera me-1"></i> Activer la caméra
                </button>
                <button type="button" class="btn btn-sm btn-primary" id="btnCapture" style="display:none;">
                  <i class="bx bx-camera-plus me-1"></i> Capturer
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" id="btnArreterCamera" style="display:none;">
                  <i class="bx bx-stop me-1"></i> Arrêter
                </button>
              </div>
              <div class="form-text mt-1">Les captures seront envoyées lors du clic sur "Envoyer"</div>
            </div>
          </div>
        </div>

        <input type="hidden" id="uploadPieceId">
        <input type="hidden" id="uploadEtatId" value="{{ $etat->id }}">
        <div id="uploadProgress" class="mt-2" style="display:none;">
          <div class="progress"><div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%"></div></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="btnAnnulerUpload">Annuler</button>
        <button type="button" class="btn btn-primary" id="btnConfirmUpload">
          <i class="bx bx-upload me-1"></i> Envoyer
        </button>
      </div>
    </div>
  </div>
</div>

{{-- Modal prévisualisation PDF --}}
<div class="modal fade" id="modalPdfPreview" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:90vw;">
    <div class="modal-content" style="height:90vh;">
      <div class="modal-header py-2">
        <h6 class="modal-title mb-0"><i class="bx bx-file-pdf me-2 text-danger"></i>Aperçu PDF – État des lieux</h6>
        <div class="ms-auto d-flex gap-2 align-items-center">
          <a href="{{ route('etat_des_lieux.pdf', $etat->id) }}" class="btn btn-sm btn-primary" download>
            <i class="bx bx-download me-1"></i> Télécharger
          </a>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
      </div>
      <div class="modal-body p-0">
        <iframe id="pdfIframe" src="" style="width:100%; height:100%; border:none;"></iframe>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ── Helpers AJAX ──────────────────────────────────────────────────────────────
function ajaxPost(url, payload) {
    return fetch(url, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify(payload),
    }).then(r => r.json());
}

// ── Prévisualisation PDF ──────────────────────────────────────────────────────
const modalPdf   = new bootstrap.Modal(document.getElementById('modalPdfPreview'));
const btnPreview = document.getElementById('btnPreviewPdf');
if (btnPreview) {
    btnPreview.addEventListener('click', function () {
        const url = '{{ route("etat_des_lieux.preview", $etat->id) }}';
        document.getElementById('pdfIframe').src = url;
        modalPdf.show();
    });
    // Stopper le flux PDF quand on ferme le modal
    document.getElementById('modalPdfPreview').addEventListener('hidden.bs.modal', function () {
        document.getElementById('pdfIframe').src = '';
    });
}

// ── Upload photos ────────────────────────────────────────────────────────────
const modalUpload   = new bootstrap.Modal(document.getElementById('modalUploadPhotos'));
let cameraStream    = null;
let capturedBlobs   = [];  // blobs pris par la caméra

function stopCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(t => t.stop());
        cameraStream = null;
    }
    document.getElementById('cameraStream').style.display    = 'none';
    document.getElementById('btnCapture').style.display      = 'none';
    document.getElementById('btnArreterCamera').style.display = 'none';
    document.getElementById('btnDemarrerCamera').style.display = '';
}

document.getElementById('modalUploadPhotos').addEventListener('hidden.bs.modal', function () {
    stopCamera();
    capturedBlobs = [];
    document.getElementById('cameraPreview').innerHTML = '';
    document.getElementById('inputPhotos').value = '';
    document.getElementById('uploadLimitAlert').style.display = 'none';
});

document.querySelectorAll('.btn-ajout-photos').forEach(btn => {
    btn.addEventListener('click', function () {
        const nbPhotos = parseInt(this.dataset.nbPhotos || 0);
        const restant  = 2 - nbPhotos;

        document.getElementById('uploadPieceId').value = this.dataset.pieceId;
        document.getElementById('modalUploadTitre').textContent = 'Photos – ' + this.dataset.pieceNom;

        const alert = document.getElementById('uploadLimitAlert');
        if (restant <= 0) {
            alert.textContent = 'Limite atteinte : 2 photos maximum par pièce.';
            alert.style.display = '';
            document.getElementById('btnConfirmUpload').disabled = true;
        } else {
            alert.textContent = restant + ' emplacement(s) disponible(s) sur 2.';
            alert.style.display = '';
            document.getElementById('btnConfirmUpload').disabled = false;
        }

        capturedBlobs = [];
        document.getElementById('cameraPreview').innerHTML = '';
        document.getElementById('inputPhotos').value = '';
        modalUpload.show();
    });
});

// Démarrer caméra
document.getElementById('btnDemarrerCamera').addEventListener('click', async function () {
    const errDiv = document.getElementById('cameraError');
    errDiv.style.display = 'none';
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } });
        const video  = document.getElementById('cameraStream');
        video.srcObject = cameraStream;
        video.style.display = '';
        this.style.display = 'none';
        document.getElementById('btnCapture').style.display      = '';
        document.getElementById('btnArreterCamera').style.display = '';
    } catch(e) {
        errDiv.textContent = 'Impossible d\'accéder à la caméra : ' + e.message;
        errDiv.style.display = '';
    }
});

// Arrêter caméra
document.getElementById('btnArreterCamera').addEventListener('click', stopCamera);

// Capturer
document.getElementById('btnCapture').addEventListener('click', function () {
    const nbPhotos = parseInt(document.querySelector('.btn-ajout-photos[data-piece-id="' + document.getElementById('uploadPieceId').value + '"]')?.dataset.nbPhotos || 0);
    const totalPrev = nbPhotos + capturedBlobs.length;
    if (totalPrev >= 2) {
        Swal.fire('Limite atteinte', 'Maximum 2 photos par pièce.', 'warning');
        return;
    }

    const video  = document.getElementById('cameraStream');
    const canvas = document.getElementById('cameraCanvas');
    canvas.width  = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);

    canvas.toBlob(blob => {
        capturedBlobs.push(blob);
        // Prévisualiser
        const url = URL.createObjectURL(blob);
        const img = document.createElement('img');
        img.src = url;
        img.className = 'rounded border';
        img.style.cssText = 'width:80px;height:60px;object-fit:cover;';
        document.getElementById('cameraPreview').appendChild(img);

        // Mettre à jour le compteur
        const alert = document.getElementById('uploadLimitAlert');
        const total = (parseInt(document.querySelector('.btn-ajout-photos[data-piece-id="' + document.getElementById('uploadPieceId').value + '"]')?.dataset.nbPhotos || 0)) + capturedBlobs.length;
        const restant = 2 - total;
        alert.textContent = restant + ' emplacement(s) disponible(s) sur 2.';

        if (restant <= 0) {
            document.getElementById('btnCapture').disabled = true;
        }
    }, 'image/jpeg', 0.85);
});

// Envoyer photos
document.getElementById('btnConfirmUpload').addEventListener('click', function () {
    const pieceId  = document.getElementById('uploadPieceId').value;
    const etatId   = document.getElementById('uploadEtatId').value;
    const activeTab = document.querySelector('#uploadTabs .nav-link.active').id;
    const formData  = new FormData();

    formData.append('etat_des_lieux_id', etatId);
    formData.append('piece_id', pieceId);
    formData.append('_token', CSRF);

    if (activeTab === 'tab-fichier-btn') {
        const files = document.getElementById('inputPhotos').files;
        if (!files.length) { Swal.fire('Attention', 'Sélectionnez au moins une photo.', 'warning'); return; }
        Array.from(files).forEach(f => formData.append('photos[]', f));
    } else {
        if (!capturedBlobs.length) { Swal.fire('Attention', 'Capturez au moins une photo.', 'warning'); return; }
        capturedBlobs.forEach((blob, i) => formData.append('photos[]', blob, 'capture_' + (i+1) + '.jpg'));
    }

    document.getElementById('uploadProgress').style.display = '';
    this.disabled = true;

    fetch('{{ route("etat_des_lieux.upload_photo") }}', { method: 'POST', body: formData })
    .then(r => r.json())
    .then(data => {
        document.getElementById('uploadProgress').style.display = 'none';
        this.disabled = false;
        if (data.status) {
            // Injecter les nouvelles photos dans la galerie sans recharger
            const galerie    = document.getElementById('galerie_piece_' + pieceId);
            const container  = document.getElementById('photos_piece_' + pieceId);
            if (galerie) galerie.style.display = '';
            data.photos.forEach(p => {
                const div = document.createElement('div');
                div.className = 'position-relative';
                div.id = 'photoCard' + p.id;
                div.innerHTML = `
                    <a href="${p.url}" target="_blank">
                        <img src="${p.url}" class="rounded border" style="width:90px;height:70px;object-fit:cover;">
                    </a>
                    <button type="button" class="btn btn-sm btn-danger btn-suppr-photo position-absolute top-0 end-0 p-0"
                            style="width:18px;height:18px;font-size:10px;line-height:1;"
                            data-photo-id="${p.id}" data-piece-id="${pieceId}">&times;</button>`;
                container.appendChild(div);
                // Attacher event suppression
                div.querySelector('.btn-suppr-photo').addEventListener('click', supprimerPhoto);
            });

            // Mettre à jour le bouton d'ajout
            const btnAjout = document.querySelector('.btn-ajout-photos[data-piece-id="' + pieceId + '"]');
            if (btnAjout) {
                const nbTotal = container.querySelectorAll('[id^="photoCard"]').length;
                btnAjout.dataset.nbPhotos = nbTotal;
                btnAjout.querySelector('.badge').textContent = nbTotal + '/2';
                if (nbTotal >= 2) {
                    btnAjout.disabled = true;
                    btnAjout.classList.replace('btn-outline-primary', 'btn-secondary');
                }
            }

            modalUpload.hide();
            Swal.fire({ icon: 'success', title: 'Succès', text: data.message, timer: 1500, showConfirmButton: false });
        } else {
            Swal.fire('Erreur', data.message, 'error');
        }
    })
    .catch(() => {
        document.getElementById('uploadProgress').style.display = 'none';
        this.disabled = false;
        Swal.fire('Erreur', 'Une erreur réseau est survenue.', 'error');
    });
});

// ── Suppression photo ────────────────────────────────────────────────────────
function supprimerPhoto() {
    const photoId = this.dataset.photoId;
    const pieceId = this.dataset.pieceId;
    Swal.fire({
        title: 'Supprimer cette photo ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonText: 'Annuler',
        confirmButtonText: 'Supprimer',
    }).then(r => {
        if (!r.isConfirmed) return;
        ajaxPost('{{ route("etat_des_lieux.delete_photo") }}', { photo_id: photoId })
        .then(data => {
            if (data.status) {
                document.getElementById('photoCard' + photoId)?.remove();
                // Réactiver le bouton ajout
                const btnAjout = document.querySelector('.btn-ajout-photos[data-piece-id="' + pieceId + '"]');
                if (btnAjout) {
                    const container = document.getElementById('photos_piece_' + pieceId);
                    const nbTotal   = container ? container.querySelectorAll('[id^="photoCard"]').length : 0;
                    btnAjout.dataset.nbPhotos = nbTotal;
                    btnAjout.querySelector('.badge').textContent = nbTotal + '/2';
                    if (nbTotal < 2) {
                        btnAjout.disabled = false;
                        btnAjout.classList.replace('btn-secondary', 'btn-outline-primary');
                    }
                }
            } else {
                Swal.fire('Erreur', data.message, 'error');
            }
        });
    });
}
document.querySelectorAll('.btn-suppr-photo').forEach(btn => btn.addEventListener('click', supprimerPhoto));

// ── Badges statut ─────────────────────────────────────────────────────────────
const STATUT_BADGES = {
    brouillon : '<span class="badge bg-secondary">Brouillon</span>',
    finalise  : '<span class="badge bg-primary">Finalisé</span>',
    signe     : '<span class="badge bg-success">Signé</span>',
};

// ── Finaliser ─────────────────────────────────────────────────────────────────
const btnFinaliser = document.getElementById('btnFinaliser');
if (btnFinaliser) {
    btnFinaliser.addEventListener('click', function () {
        Swal.fire({
            title: 'Finaliser l\'état des lieux ?',
            text: 'Il ne sera plus modifiable une fois finalisé.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Finaliser',
            cancelButtonText: 'Annuler',
        }).then(r => {
            if (!r.isConfirmed) return;
            ajaxPost('{{ route("etat_des_lieux.finaliser") }}', { id: this.dataset.id })
            .then(data => {
                if (data.status) {
                    // Badge statut
                    document.getElementById('statutBadge').innerHTML = STATUT_BADGES.finalise;
                    // Cacher Finaliser
                    btnFinaliser.style.display = 'none';
                    // Afficher Envoyer signature
                    document.getElementById('btnEnvoyerSignature').style.display = '';
                    // Basculer bloc agent : brouillon → bouton signer
                    const elBrouillon = document.getElementById('sigAgentBrouillon');
                    if (elBrouillon) elBrouillon.style.setProperty('display', 'none', 'important');
                    const elSigner = document.getElementById('btnSignerAgent');
                    if (elSigner) elSigner.style.display = '';
                    // Supprimer boutons photos (plus d'ajout après finalisation)
                    document.querySelectorAll('.btn-ajout-photos').forEach(b => b.remove());
                    document.querySelectorAll('.btn-suppr-photo').forEach(b => b.remove());

                    Swal.fire({ icon: 'success', title: 'Finalisé !', text: data.message, timer: 1800, showConfirmButton: false });
                } else {
                    Swal.fire('Erreur', data.message, 'error');
                }
            });
        });
    });
}

// ── Signature agent ───────────────────────────────────────────────────────────
const btnSignerAgent = document.getElementById('btnSignerAgent');
if (btnSignerAgent) {
    btnSignerAgent.addEventListener('click', function () {
        Swal.fire({
            title: 'Signer en tant qu\'agent ?',
            text: 'Vous confirmez avoir réalisé cet état des lieux.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Signer',
            cancelButtonText: 'Annuler',
        }).then(r => {
            if (!r.isConfirmed) return;
            ajaxPost('{{ route("etat_des_lieux.signer_agent") }}', { id: this.dataset.id })
            .then(data => {
                if (data.status) {
                    // Cacher le bouton signer
                    btnSignerAgent.style.display = 'none';
                    // Afficher bloc "signé"
                    const now = new Date();
                    const fmt = now.toLocaleDateString('fr-FR') + ' à ' + now.toLocaleTimeString('fr-FR', { hour:'2-digit', minute:'2-digit' });
                    document.getElementById('sigAgentDate').textContent = 'Signé le ' + fmt;
                    document.getElementById('sigAgentOk').style.setProperty('display', 'flex', 'important');

                    Swal.fire({ icon: 'success', title: 'Signé !', text: data.message, timer: 1800, showConfirmButton: false });
                } else {
                    Swal.fire('Erreur', data.message, 'error');
                }
            });
        });
    });
}

// ── Envoyer pour signature ────────────────────────────────────────────────────
const btnEnvoyerSignature = document.getElementById('btnEnvoyerSignature');
if (btnEnvoyerSignature) {
    btnEnvoyerSignature.addEventListener('click', function () {
        Swal.fire({
            title: 'Envoyer le lien de signature ?',
            text: 'Un lien sera envoyé au locataire par email/SMS.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Envoyer',
            cancelButtonText: 'Annuler',
        }).then(r => {
            if (!r.isConfirmed) return;
            ajaxPost('{{ route("etat_des_lieux.envoyer_signature") }}', { id: this.dataset.id })
            .then(data => {
                if (data.status) {
                    if (data.lien) {
                        document.getElementById('lienSignature').value = data.lien;
                    }
                    let html = data.message;
                    if (data.lien) html += `<br><small class="text-muted">Lien : <a href="${data.lien}" target="_blank">${data.lien}</a></small>`;
                    Swal.fire({ icon: 'success', title: 'Envoyé !', html });
                } else {
                    Swal.fire('Erreur', data.message, 'error');
                }
            });
        });
    });
}

function copierLien() {
    const input = document.getElementById('lienSignature');
    if (input) {
        navigator.clipboard.writeText(input.value).then(() => {
            Swal.fire({ icon: 'success', title: 'Copié !', text: 'Lien copié dans le presse-papier.', timer: 1500, showConfirmButton: false });
        });
    }
}
</script>
@endpush
@endsection
