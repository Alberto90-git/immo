const CACHE_VERSION = 'lokativ-v5';
const STATIC_CACHE  = `${CACHE_VERSION}-static`;
const DYNAMIC_CACHE = `${CACHE_VERSION}-dynamic`;
const CDN_CACHE     = `${CACHE_VERSION}-cdn`;

// Assets statiques à pré-cacher
const STATIC_ASSETS = [
    '/',
    '/offline.html',
    '/favicon.ico',
    '/favicon-32x32.png',
    '/logo/LOGO.jpg',
    '/logo/logo2.jpg',
    '/assets/img/logo.png',
    '/manifest.json',
];

// CDN resources à cacher (stale-while-revalidate)
const CDN_PATTERNS = [
    'cdn.jsdelivr.net',
    'cdnjs.cloudflare.com',
    'cdn.tailwindcss.com',
    'cdn.kkiapay.me',
    'cdn.fedapay.com',
    'cdn.jsdelivr.net/npm/sweetalert2',
    'cdn.jsdelivr.net/npm/bootstrap',
    'cdnjs.cloudflare.com/ajax/libs/font-awesome',
];

// ─── INSTALL ────────────────────────────────────────────────────────────────
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => {
                return cache.addAll(
                    STATIC_ASSETS.map(url => new Request(url, { cache: 'reload' }))
                );
            })
            .then(() => self.skipWaiting())
            .catch((err) => {
                console.warn('[SW] Install cache partial failure:', err);
                // Ne pas bloquer l'install si certains assets manquent
                return self.skipWaiting();
            })
    );
});

// ─── ACTIVATE ───────────────────────────────────────────────────────────────
self.addEventListener('activate', (event) => {
    const validCaches = [STATIC_CACHE, DYNAMIC_CACHE, CDN_CACHE];
    event.waitUntil(
        caches.keys()
            .then((names) => Promise.all(
                names
                    .filter((name) => !validCaches.includes(name))
                    .map((name) => caches.delete(name))
            ))
            .then(() => self.clients.claim())
    );
});

// ─── FETCH ──────────────────────────────────────────────────────────────────
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Ignorer les requêtes non-GET et les extensions navigateur
    if (request.method !== 'GET') return;
    if (url.protocol === 'chrome-extension:') return;

    // 1. CDN assets → Stale-While-Revalidate
    if (CDN_PATTERNS.some(p => url.hostname.includes(p) || url.href.includes(p))) {
        event.respondWith(staleWhileRevalidate(request, CDN_CACHE));
        return;
    }

    // 2. Navigation HTML → Network-First avec fallback offline
    if (request.mode === 'navigate') {
        event.respondWith(networkFirstWithOfflineFallback(request));
        return;
    }

    // 3. Assets statiques same-origin (images, CSS, JS) → Cache-First
    if (url.origin === self.location.origin) {
        event.respondWith(cacheFirst(request, DYNAMIC_CACHE));
        return;
    }

    // 4. Autres → Network uniquement
    event.respondWith(fetch(request).catch(() => new Response('', { status: 503 })));
});

// ─── STRATÉGIES ─────────────────────────────────────────────────────────────

async function networkFirstWithOfflineFallback(request) {
    try {
        const response = await fetch(request);
        // Mettre en cache la réponse fraîche
        if (response.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        // Réseau indisponible → chercher dans le cache
        const cached = await caches.match(request);
        if (cached) return cached;

        // Fallback vers la page offline
        return caches.match('/offline.html');
    }
}

async function cacheFirst(request, cacheName) {
    const cached = await caches.match(request);
    if (cached) return cached;

    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(cacheName);
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        return new Response('', { status: 503, statusText: 'Offline' });
    }
}

async function staleWhileRevalidate(request, cacheName) {
    const cached = await caches.match(request);

    // Revalider en arrière-plan
    const fetchPromise = fetch(request).then(async (response) => {
        if (response.ok) {
            const cache = await caches.open(cacheName);
            cache.put(request, response.clone());
        }
        return response;
    }).catch(() => cached);

    return cached || fetchPromise;
}

// ─── MESSAGE (skip waiting / check update) ──────────────────────────────────
self.addEventListener('message', (event) => {
    if (event.data?.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    if (event.data?.type === 'CACHE_URLS') {
        const urls = event.data.payload || [];
        caches.open(STATIC_CACHE).then(cache => cache.addAll(urls));
    }
});
