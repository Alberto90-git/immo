<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Marketplace immobilier — trouvez appartements, maisons, terrains et locaux commerciaux">
  <title>Marketplace Immobilier</title>

  <!-- Bootstrap 5 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <!-- BoxIcons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
  <!-- Leaflet -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

  <style>
    :root {
      --brand: #696cff;
      --brand-dark: #5a5cdb;
    }
    body { background: #f5f5f9; font-family: 'Public Sans', sans-serif; }
    /* Navbar */
    .mkp-nav { background: #fff; border-bottom: 1px solid #e9e9e9; padding: 12px 0; }
    .mkp-nav .brand { font-size: 1.3rem; font-weight: 700; color: var(--brand); text-decoration: none; }
    /* Hero filter */
    .hero-filter { background: linear-gradient(135deg, var(--brand) 0%, #9b59b6 100%); padding: 40px 0 30px; }
    .hero-filter h1 { color: #fff; font-size: 2rem; font-weight: 700; }
    .hero-filter p { color: rgba(255,255,255,.85); }
    .filter-card { background: #fff; border-radius: 16px; padding: 20px 24px; box-shadow: 0 4px 24px rgba(0,0,0,.12); }
    /* Annonce card */
    .annonce-card { background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.08); transition: transform .2s, box-shadow .2s; height: 100%; display: flex; flex-direction: column; }
    .annonce-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(0,0,0,.15); }
    .annonce-card .card-img { position: relative; height: 200px; overflow: hidden; background: #eee; }
    .annonce-card .card-img img { width: 100%; height: 100%; object-fit: cover; }
    .annonce-card .card-img .no-photo { display: flex; align-items: center; justify-content: center; height: 100%; color: #bbb; flex-direction: column; }
    .annonce-card .card-body { padding: 16px; flex: 1; display: flex; flex-direction: column; }
    .annonce-card .price-tag { font-size: 1.2rem; font-weight: 700; color: var(--brand); }
    .badge-sponsored { background: linear-gradient(90deg,#f7b731,#f0932b); color: #fff; font-size: .7rem; padding: 3px 8px; border-radius: 20px; }
    .badge-type { background: #e8e8ff; color: var(--brand); font-size: .7rem; padding: 3px 8px; border-radius: 20px; }
    .share-btn { border: none; background: none; padding: 4px 8px; border-radius: 6px; font-size: .8rem; cursor: pointer; }
    .share-wa { color: #25d366; }
    .share-fb { color: #1877f2; }
    /* Map */
    #map { height: 420px; border-radius: 12px; }
    @media (max-width: 576px) { #map { height: 240px; } }
    /* Pagination */
    .pagination .page-link { color: var(--brand); }
    .pagination .page-item.active .page-link { background: var(--brand); border-color: var(--brand); }
    /* Responsive */
    @media (max-width: 768px) {
      .hero-filter h1 { font-size: 1.4rem; }
      #map { height: 280px; }
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="mkp-nav">
  <div class="container d-flex align-items-center justify-content-between">
    <a href="{{ route('marketplace.index') }}" class="brand"><i class='bx bx-building-house me-1'></i>Marketplace</a>
    @auth
    <a href="{{ url('/home') }}" class="btn btn-outline-primary btn-sm"><i class='bx bx-grid-alt me-1'></i>Dashboard</a>
    @else
    <a href="{{ route('login') }}" class="btn btn-primary btn-sm"><i class='bx bx-log-in me-1'></i>Connexion agence</a>
    @endauth
  </div>
</nav>

<!-- Hero + Filtres -->
<div class="hero-filter">
  <div class="container">
    <h1 class="mb-1"><i class='bx bx-search-alt me-2'></i>Trouvez votre bien idéal</h1>
    <p class="mb-4">Appartements, maisons, terrains, bureaux — toutes les annonces en un seul endroit</p>

    <div class="filter-card">
      <form method="GET" action="{{ route('marketplace.index') }}" id="filterForm">
        <div class="row g-3 align-items-end">
          <!-- Recherche libre -->
          <div class="col-12 col-md-4">
            <label class="form-label fw-semibold small mb-1">Recherche libre</label>
            <div class="input-group">
              <span class="input-group-text"><i class='bx bx-search'></i></span>
              <input type="text" name="q" class="form-control" placeholder="Quartier, description..." value="{{ request('q') }}">
            </div>
          </div>
          <!-- Type de bien -->
          <div class="col-6 col-md-2">
            <label class="form-label fw-semibold small mb-1">Type</label>
            <select name="type_bien" class="form-select">
              <option value="">Tous</option>
              @foreach(['appartement'=>'Appartement','maison'=>'Maison','villa'=>'Villa','terrain'=>'Terrain','bureau'=>'Bureau','commerce'=>'Local commercial','chambre'=>'Chambre','studio'=>'Studio'] as $v=>$l)
              <option value="{{ $v }}" {{ request('type_bien')==$v?'selected':'' }}>{{ $l }}</option>
              @endforeach
            </select>
          </div>
          <!-- Ville -->
          <div class="col-6 col-md-2">
            <label class="form-label fw-semibold small mb-1">Ville</label>
            <input type="text" name="ville" list="villesList" class="form-control" placeholder="Cotonou..." value="{{ request('ville') }}">
            <datalist id="villesList">
              @foreach($villes as $v)<option value="{{ $v }}">@endforeach
            </datalist>
          </div>
          <!-- Quartier -->
          <div class="col-6 col-md-2">
            <label class="form-label fw-semibold small mb-1">Quartier</label>
            <input type="text" name="quartier" class="form-control" placeholder="Fidjrossè..." value="{{ request('quartier') }}">
          </div>
          <!-- Budget max -->
          <div class="col-12 col-md-2 d-flex gap-2">
            <div class="flex-1" style="flex:1">
              <label class="form-label fw-semibold small mb-1">Budget max</label>
              <input type="number" name="budget_max" class="form-control" placeholder="500000" value="{{ request('budget_max') }}">
            </div>
          </div>
          <!-- Bouton -->
          <div class="col-12 d-flex justify-content-end gap-2 mt-1">
            <a href="{{ route('marketplace.index') }}" class="btn btn-outline-secondary btn-sm"><i class='bx bx-x me-1'></i>Réinitialiser</a>
            <button type="submit" class="btn btn-primary"><i class='bx bx-filter me-1'></i>Filtrer</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="container py-4">

  <!-- Carte interactive -->
  <div class="mb-4">
    <div class="d-flex align-items-center justify-content-between mb-2">
      <h6 class="fw-bold mb-0"><i class='bx bx-map me-1 text-primary'></i>Carte des annonces</h6>
      <button class="btn btn-sm btn-outline-secondary" id="toggleMap">
        <i class='bx bx-hide me-1'></i>Masquer la carte
      </button>
    </div>
    <div id="mapContainer">
      <div id="map"></div>
    </div>
  </div>

  <!-- Résultats -->
  <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
    <div>
      <span class="fw-bold text-dark">{{ $annonces->total() }}</span>
      <span class="text-muted"> annonce{{ $annonces->total() > 1 ? 's' : '' }} trouvée{{ $annonces->total() > 1 ? 's' : '' }}</span>
      @if(request()->hasAny(['q','type_bien','ville','quartier','budget_max']))
      <a href="{{ route('marketplace.index') }}" class="ms-2 small text-danger"><i class='bx bx-x'></i>Effacer les filtres</a>
      @endif
    </div>
  </div>

  @if($annonces->count())
  <div class="row g-4">
    @foreach($annonces as $annonce)
    <div class="col-sm-6 col-lg-4 col-xl-3">
      <div class="annonce-card">
        <!-- Image -->
        <a href="{{ route('marketplace.show', $annonce->slug ?? $annonce->id) }}" class="card-img d-block text-decoration-none">
          @if($annonce->image_url)
          <img src="{{ asset('storage/'.$annonce->image_url) }}" alt="{{ $annonce->localisation }}" loading="lazy">
          @else
          <div class="no-photo"><i class='bx bx-image' style="font-size:3rem"></i><small>Pas de photo</small></div>
          @endif
          <!-- Badges overlay -->
          <div class="position-absolute top-0 start-0 p-2 d-flex gap-1 flex-wrap">
            @if($annonce->is_sponsored_active)
            <span class="badge-sponsored"><i class='bx bxs-star me-1'></i>Sponsorisée</span>
            @endif
            @if($annonce->type_bien)
            <span class="badge-type">{{ $annonce->type_label }}</span>
            @endif
          </div>
        </a>

        <!-- Corps -->
        <div class="card-body">
          @php $devAnn = \App\Parametre::deviseConfig($deviseMap[$annonce->iddirection_ref] ?? 'XOF'); @endphp
          <div class="price-tag mb-1">{{ format_price((float)$annonce->price, null, $devAnn['code']) }}</div>
          <h6 class="mb-1 fw-semibold" style="font-size:.9rem">{{ Str::limit($annonce->localisation, 60) }}</h6>
          @if($annonce->ville || $annonce->quartier)
          <p class="text-muted small mb-1">
            <i class='bx bx-map-pin me-1'></i>{{ implode(', ', array_filter([$annonce->quartier, $annonce->ville])) }}
          </p>
          @endif
          @if($annonce->Superficie)
          <p class="text-muted small mb-2"><i class='bx bx-area me-1'></i>{{ $annonce->Superficie }} m²</p>
          @endif
          <p class="text-muted small mb-3">{{ Str::limit($annonce->description, 80) }}</p>

          <div class="mt-auto d-flex align-items-center justify-content-between">
            <a href="{{ route('marketplace.show', $annonce->slug ?? $annonce->id) }}" class="btn btn-primary btn-sm"><i class='bx bx-show me-1'></i>Voir</a>
            <div>
              @php $url = route('marketplace.show', $annonce->slug ?? $annonce->id); $txt = urlencode($annonce->localisation . ' — ' . format_price((float)$annonce->price, null, $devAnn['code'])); @endphp
              <button class="share-btn share-wa" onclick="window.open('https://wa.me/?text={{ $txt }}%20{{ urlencode($url) }}','_blank')" title="Partager sur WhatsApp">
                <i class='bx bxl-whatsapp' style="font-size:1.2rem"></i>
              </button>
              <button class="share-btn share-fb" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u={{ urlencode($url) }}','_blank')" title="Partager sur Facebook">
                <i class='bx bxl-facebook-circle' style="font-size:1.2rem"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  <!-- Pagination -->
  @if($annonces->hasPages())
  <nav class="d-flex justify-content-center mt-5">
    <ul class="pagination">
      {{-- Précédent --}}
      @if($annonces->onFirstPage())
        <li class="page-item disabled"><span class="page-link">&laquo;</span></li>
      @else
        <li class="page-item"><a class="page-link" href="{{ $annonces->previousPageUrl() }}">&laquo;</a></li>
      @endif

      {{-- Pages --}}
      @foreach($annonces->getUrlRange(max(1,$annonces->currentPage()-2), min($annonces->lastPage(),$annonces->currentPage()+2)) as $page => $url)
        <li class="page-item {{ $page == $annonces->currentPage() ? 'active' : '' }}">
          <a class="page-link" href="{{ $url }}">{{ $page }}</a>
        </li>
      @endforeach

      {{-- Suivant --}}
      @if($annonces->hasMorePages())
        <li class="page-item"><a class="page-link" href="{{ $annonces->nextPageUrl() }}">&raquo;</a></li>
      @else
        <li class="page-item disabled"><span class="page-link">&raquo;</span></li>
      @endif
    </ul>
  </nav>
  @endif

  @else
  <div class="text-center py-5">
    <i class='bx bx-search-alt' style="font-size:4rem;color:#ccc"></i>
    <h5 class="text-muted mt-3">Aucune annonce trouvée</h5>
    <p class="text-muted small">Modifiez vos critères de recherche ou revenez plus tard.</p>
    <a href="{{ route('marketplace.index') }}" class="btn btn-outline-primary btn-sm mt-2">Voir toutes les annonces</a>
  </div>
  @endif

</div>

<!-- Footer simple -->
<footer class="bg-white border-top py-3 mt-5 text-center text-muted small">
  &copy; {{ date('Y') }} Lokativ Marketplace — Tous droits réservés
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Carte Leaflet (OpenStreetMap)
const mapData = @json($forMap);

const map = L.map('map').setView([6.3703, 2.3912], 7); // Bénin par défaut

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

const customIcon = L.divIcon({
  html: '<div style="background:var(--brand,#696cff);color:#fff;border-radius:50%;width:32px;height:32px;display:flex;align-items:center;justify-content:center;font-size:14px;box-shadow:0 2px 6px rgba(0,0,0,.3)"><i class="bx bx-building-house"></i></div>',
  iconSize: [32, 32],
  iconAnchor: [16, 32],
  className: '',
});

if (mapData.length > 0) {
  const bounds = [];
  mapData.forEach(a => {
    if (a.lat && a.lng) {
      const popup = `
        <div style="min-width:180px">
          ${a.image_url ? `<img src="${a.image_url}" style="width:100%;height:90px;object-fit:cover;border-radius:6px;margin-bottom:6px">` : ''}
          <strong style="font-size:.85rem">${a.localisation}</strong><br>
          <span style="color:#696cff;font-weight:700">${parseFloat(a.price).toLocaleString('fr-FR')} ${a.devise || 'XOF'}</span><br>
          <a href="/marketplace/annonce/${a.slug || a.id}" class="btn btn-sm btn-primary mt-1" style="font-size:.75rem;padding:2px 8px">Voir l'annonce</a>
        </div>`;
      L.marker([a.lat, a.lng], { icon: customIcon }).addTo(map).bindPopup(popup);
      bounds.push([a.lat, a.lng]);
    }
  });
  if (bounds.length > 0) {
    try { map.fitBounds(bounds, { padding: [40, 40] }); } catch(e){}
  }
}

// Toggle carte
document.getElementById('toggleMap').addEventListener('click', function() {
  const c = document.getElementById('mapContainer');
  const hidden = c.style.display === 'none';
  c.style.display = hidden ? '' : 'none';
  this.innerHTML = hidden
    ? "<i class='bx bx-hide me-1'></i>Masquer la carte"
    : "<i class='bx bx-map me-1'></i>Afficher la carte";
  if (hidden) setTimeout(() => map.invalidateSize(), 100);
});
</script>
</body>
</html>
