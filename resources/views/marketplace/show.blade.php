<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- SEO Meta -->
  <title>{{ $annonce->localisation }} — Marketplace Immobilier</title>
  <meta name="description" content="{{ $annonce->meta_description ?: Str::limit($annonce->description, 160) }}">
  <meta property="og:title" content="{{ $annonce->localisation }}">
  <meta property="og:description" content="{{ Str::limit($annonce->description, 200) }}">
  @if($annonce->image_url)
  <meta property="og:image" content="{{ asset('storage/'.$annonce->image_url) }}">
  @endif
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:type" content="website">

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <!-- BoxIcons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

  <style>
    :root { --brand: #696cff; --brand-dark: #5a5cdb; }
    body { background: #f5f5f9; font-family: 'Public Sans', sans-serif; }
    .mkp-nav { background: #fff; border-bottom: 1px solid #e9e9e9; padding: 12px 0; }
    .mkp-nav .brand { font-size: 1.3rem; font-weight: 700; color: var(--brand); text-decoration: none; }

    /* Galerie */
    .gallery-main { border-radius: 14px; overflow: hidden; height: 380px; background: #ddd; position: relative; }
    .gallery-main img, .gallery-main video { width: 100%; height: 100%; object-fit: cover; }
    .gallery-thumbs { display: flex; gap: 8px; margin-top: 8px; flex-wrap: nowrap; overflow-x: auto; }
    .gallery-thumb { width: 80px; height: 60px; border-radius: 8px; overflow: hidden; cursor: pointer; border: 2px solid transparent; flex-shrink: 0; }
    .gallery-thumb.active { border-color: var(--brand); }
    .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .gallery-thumb.video-thumb { display: flex; align-items: center; justify-content: center; background: #222; color: #fff; font-size: 1.5rem; }

    /* Info sidebar */
    .info-card { background: #fff; border-radius: 14px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,.08); position: sticky; top: 16px; }
    .price-big { font-size: 2rem; font-weight: 800; color: var(--brand); }
    .detail-row { display: flex; gap: 8px; align-items: center; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
    .detail-row:last-child { border-bottom: none; }
    .detail-icon { color: var(--brand); font-size: 1.1rem; width: 24px; text-align: center; }
    .badge-sponsored { background: linear-gradient(90deg,#f7b731,#f0932b); color: #fff; font-size: .75rem; padding: 4px 10px; border-radius: 20px; }

    /* Contact form */
    .contact-card { background: #fff; border-radius: 14px; padding: 24px; box-shadow: 0 2px 12px rgba(0,0,0,.08); }

    /* Similaires */
    .sim-card { background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.07); transition: transform .2s; }
    .sim-card:hover { transform: translateY(-3px); }
    .sim-card img { width: 100%; height: 140px; object-fit: cover; }
    .sim-card .p-3 { padding: 12px; }

    /* Map mini */
    #mapDetail { height: 280px; border-radius: 12px; }

    @media (max-width: 768px) {
      .gallery-main { height: 240px; }
      .price-big { font-size: 1.5rem; }
      .info-card { position: static; }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="mkp-nav">
  <div class="container d-flex align-items-center justify-content-between">
    <a href="{{ route('marketplace.index') }}" class="brand"><i class='bx bx-building-house me-1'></i>Marketplace</a>
    <div class="d-flex gap-2">
      <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary btn-sm"><i class='bx bx-arrow-back me-1'></i>Retour</a>
      @auth
      <a href="{{ url('/home') }}" class="btn btn-outline-primary btn-sm"><i class='bx bx-grid-alt me-1'></i>Dashboard</a>
      @endauth
    </div>
  </div>
</nav>

<div class="container py-4">

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb small">
      <li class="breadcrumb-item"><a href="{{ route('marketplace.index') }}" class="text-decoration-none">Marketplace</a></li>
      @if($annonce->ville)<li class="breadcrumb-item text-muted">{{ $annonce->ville }}</li>@endif
      <li class="breadcrumb-item active text-truncate" style="max-width:200px">{{ $annonce->localisation }}</li>
    </ol>
  </nav>

  <div class="row g-4">
    <!-- Colonne gauche : Galerie + Détails -->
    <div class="col-lg-8">

      <!-- Galerie -->
      <div id="galleryMain" class="gallery-main mb-1">
        @php $images = $annonce->images; @endphp
        @if(count($images))
        <img id="mainMedia" src="{{ asset('storage/'.$images[0]) }}" alt="{{ $annonce->localisation }}" id="mainImg">
        @elseif($annonce->video_url)
        <video id="mainMedia" controls autoplay muted loop src="{{ $annonce->video_url }}" style="width:100%;height:100%;object-fit:cover"></video>
        @else
        <div class="d-flex align-items-center justify-content-center h-100 text-muted flex-column">
          <i class='bx bx-image' style="font-size:4rem"></i><span>Pas de photo disponible</span>
        </div>
        @endif

        <!-- Badges flottants -->
        <div class="position-absolute top-0 start-0 p-3 d-flex gap-2">
          @if($annonce->is_sponsored_active)
          <span class="badge-sponsored"><i class='bx bxs-star me-1'></i>Sponsorisée</span>
          @endif
          @if($annonce->type_bien)
          <span class="badge bg-light text-dark" style="font-size:.75rem">{{ $annonce->type_label }}</span>
          @endif
        </div>
      </div>

      <!-- Miniatures -->
      <div class="gallery-thumbs" id="thumbs">
        @foreach($images as $i => $img)
        <div class="gallery-thumb {{ $i===0?'active':'' }}" data-type="image" data-src="{{ asset('storage/'.$img) }}" onclick="switchMedia(this)">
          <img src="{{ asset('storage/'.$img) }}" alt="Photo {{ $i+1 }}">
        </div>
        @endforeach
        @if($annonce->video_url)
        <div class="gallery-thumb video-thumb" data-type="video" data-src="{{ $annonce->video_url }}" onclick="switchMedia(this)">
          <i class='bx bx-play-circle'></i>
        </div>
        @endif
      </div>

      <!-- Description -->
      <div class="bg-white rounded-3 p-4 mt-4 shadow-sm">
        <h5 class="fw-bold mb-3">Description</h5>
        <p class="text-muted" style="line-height:1.8; white-space:pre-line">{{ $annonce->description }}</p>
      </div>

      <!-- Caractéristiques -->
      <div class="bg-white rounded-3 p-4 mt-3 shadow-sm">
        <h5 class="fw-bold mb-3">Caractéristiques</h5>
        <div class="row g-0">
          @if($annonce->type_bien)
          <div class="col-12 col-sm-6">
            <div class="detail-row">
              <i class='bx bx-home detail-icon'></i>
              <div><small class="text-muted d-block">Type</small><strong>{{ $annonce->type_label }}</strong></div>
            </div>
          </div>
          @endif
          @if($annonce->Superficie)
          <div class="col-12 col-sm-6">
            <div class="detail-row">
              <i class='bx bx-area detail-icon'></i>
              <div><small class="text-muted d-block">Superficie</small><strong>{{ $annonce->Superficie }} m²</strong></div>
            </div>
          </div>
          @endif
          @if($annonce->ville)
          <div class="col-12 col-sm-6">
            <div class="detail-row">
              <i class='bx bx-map detail-icon'></i>
              <div><small class="text-muted d-block">Ville</small><strong>{{ $annonce->ville }}</strong></div>
            </div>
          </div>
          @endif
          @if($annonce->quartier)
          <div class="col-12 col-sm-6">
            <div class="detail-row">
              <i class='bx bx-map-pin detail-icon'></i>
              <div><small class="text-muted d-block">Quartier</small><strong>{{ $annonce->quartier }}</strong></div>
            </div>
          </div>
          @endif
          @if($annonce->telephone)
          <div class="col-12 col-sm-6">
            <div class="detail-row">
              <i class='bx bx-phone detail-icon'></i>
              <div><small class="text-muted d-block">Téléphone</small><strong>{{ $annonce->telephone }}</strong></div>
            </div>
          </div>
          @endif
          @if($annonce->published_at)
          <div class="col-12 col-sm-6">
            <div class="detail-row">
              <i class='bx bx-calendar detail-icon'></i>
              <div><small class="text-muted d-block">Publié le</small><strong>{{ $annonce->published_at->format('d/m/Y') }}</strong></div>
            </div>
          </div>
          @endif
        </div>
      </div>

      <!-- Carte localisation -->
      @if($annonce->lat && $annonce->lng)
      <div class="bg-white rounded-3 p-4 mt-3 shadow-sm">
        <h5 class="fw-bold mb-3"><i class='bx bx-map me-2 text-primary'></i>Localisation</h5>
        <div id="mapDetail"></div>
      </div>
      @endif

    </div>

    <!-- Colonne droite : Prix + Contact + Partage -->
    <div class="col-lg-4">
      <div class="info-card">
        @php $devAnn = \App\Parametre::deviseConfig($deviseMap[$annonce->iddirection_ref] ?? 'XOF'); @endphp
        <div class="price-big mb-1">{{ format_price((float)$annonce->price, null, $devAnn['code']) }}</div>
        <p class="text-muted small mb-3">
          <i class='bx bx-map-pin me-1'></i>{{ implode(', ', array_filter([$annonce->quartier, $annonce->ville, $annonce->localisation])) }}
        </p>

        @if($annonce->telephone)
        <a href="tel:{{ $annonce->telephone }}" class="btn btn-success w-100 mb-2">
          <i class='bx bx-phone me-2'></i>Appeler {{ $annonce->telephone }}
        </a>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$annonce->telephone) }}?text={{ urlencode('Bonjour, je suis intéressé par votre annonce : '.$annonce->localisation.' ('.format_price((float)$annonce->price, null, $devAnn['code']).') — '.url()->current()) }}" target="_blank" class="btn w-100 mb-3" style="background:#25d366;color:#fff">
          <i class='bx bxl-whatsapp me-2'></i>WhatsApp
        </a>
        @endif

        <hr>
        <h6 class="fw-bold mb-3">Envoyer un message</h6>

        <div id="contactSuccess" class="alert alert-success d-none" role="alert">
          <i class='bx bx-check-circle me-2'></i>Votre message a bien été envoyé !
        </div>
        <div id="contactError" class="alert alert-danger d-none" role="alert"></div>

        <form id="contactForm">
          @csrf
          <div class="mb-2">
            <input type="text" name="nom" class="form-control form-control-sm" placeholder="Votre nom *" required>
          </div>
          <div class="mb-2">
            <input type="email" name="email" class="form-control form-control-sm" placeholder="Votre email *" required>
          </div>
          <div class="mb-2">
            <input type="tel" name="tel" class="form-control form-control-sm" placeholder="Votre téléphone">
          </div>
          <div class="mb-3">
            <textarea name="message" class="form-control form-control-sm" rows="4" placeholder="Bonjour, je suis intéressé(e) par cette annonce..." required></textarea>
          </div>
          <button type="submit" class="btn btn-primary w-100" id="btnContact">
            <i class='bx bx-send me-2'></i>Envoyer
          </button>
        </form>

        <hr>
        <h6 class="fw-bold mb-2">Partager</h6>
        @php
          $shareUrl = urlencode(url()->current());
          $shareText = urlencode($annonce->localisation.' — '.format_price((float)$annonce->price, null, $devAnn['code']));
        @endphp
        <div class="d-flex gap-2">
          <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" class="btn flex-1" style="background:#25d366;color:#fff;flex:1">
            <i class='bx bxl-whatsapp me-1'></i>WhatsApp
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" class="btn flex-1" style="background:#1877f2;color:#fff;flex:1">
            <i class='bx bxl-facebook me-1'></i>Facebook
          </a>
          <button class="btn btn-outline-secondary" onclick="copyLink()" title="Copier le lien">
            <i class='bx bx-copy'></i>
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Annonces similaires -->
  @if($similaires->count())
  <div class="mt-5">
    <h5 class="fw-bold mb-3">Annonces similaires</h5>
    <div class="row g-3">
      @foreach($similaires as $sim)
      <div class="col-sm-6 col-lg-3">
        <a href="{{ route('marketplace.show', $sim->slug ?? $sim->id) }}" class="sim-card d-block text-decoration-none text-dark">
          @if($sim->image_url)
          <img src="{{ asset('storage/'.$sim->image_url) }}" alt="{{ $sim->localisation }}">
          @else
          <div style="height:140px;background:#eee;display:flex;align-items:center;justify-content:center;color:#bbb"><i class='bx bx-image' style="font-size:2.5rem"></i></div>
          @endif
          <div class="p-3">
            <div class="fw-bold text-primary" style="font-size:.9rem">{{ format_price((float)$sim->price, null, $deviseMap[$sim->iddirection_ref] ?? 'XOF') }}</div>
            <div class="small text-muted mt-1">{{ Str::limit($sim->localisation, 50) }}</div>
          </div>
        </a>
      </div>
      @endforeach
    </div>
  </div>
  @endif

</div>

<!-- Footer -->
<footer class="bg-white border-top py-3 mt-5 text-center text-muted small">
  &copy; {{ date('Y') }} Lokativ Marketplace — Tous droits réservés
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Galerie
function switchMedia(thumb) {
  document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
  thumb.classList.add('active');
  const main = document.getElementById('galleryMain');
  const type = thumb.dataset.type;
  const src  = thumb.dataset.src;
  if (type === 'video') {
    main.innerHTML = `<video controls autoplay muted loop src="${src}" style="width:100%;height:100%;object-fit:cover"></video>`;
  } else {
    main.innerHTML = `<img src="${src}" style="width:100%;height:100%;object-fit:cover" alt="Photo">`;
  }
}

// Contact AJAX
document.getElementById('contactForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const btn = document.getElementById('btnContact');
  btn.disabled = true;
  btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Envoi...';

  const fd = new FormData(this);
  try {
    const resp = await fetch('{{ route('marketplace.contact', $annonce->slug ?? $annonce->id) }}', {
      method: 'POST',
      body: fd,
      headers: { 'X-CSRF-TOKEN': document.querySelector('[name=_token]').value, 'Accept': 'application/json' }
    });
    const data = await resp.json();
    if (data.status) {
      document.getElementById('contactSuccess').classList.remove('d-none');
      this.reset();
    } else {
      document.getElementById('contactError').textContent = data.message;
      document.getElementById('contactError').classList.remove('d-none');
    }
  } catch(err) {
    document.getElementById('contactError').textContent = '{{ __("common.swal_network_error") }}. {{ __("common.swal_network_msg") }}';
    document.getElementById('contactError').classList.remove('d-none');
  }
  btn.disabled = false;
  btn.innerHTML = '<i class="bx bx-send me-2"></i>Envoyer';
});

// Copier lien
function copyLink() {
  navigator.clipboard.writeText(window.location.href).then(() => {
    const t = document.createElement('div');
    t.style.cssText = 'position:fixed;bottom:20px;left:50%;transform:translateX(-50%);background:#333;color:#fff;padding:8px 18px;border-radius:20px;font-size:.85rem;z-index:9999';
    t.textContent = 'Lien copié !';
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 2000);
  });
}

@if($annonce->lat && $annonce->lng)
// Carte détail
const mapD = L.map('mapDetail').setView([{{ $annonce->lat }}, {{ $annonce->lng }}], 15);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(mapD);
L.marker([{{ $annonce->lat }}, {{ $annonce->lng }}]).addTo(mapD)
  .bindPopup('<strong>{{ addslashes($annonce->localisation) }}</strong>').openPopup();
@endif
</script>
</body>
</html>
