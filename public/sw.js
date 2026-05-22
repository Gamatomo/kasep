const CACHE_NAME = 'bontings-cache-v1';
const PRECACHE_URLS = [
  '/',
  '/offline.html',
  '/manifest.json',
  '/css/modern.css',
  '/AdminLTE-2/dist/css/AdminLTE.min.css',
  '/AdminLTE-2/dist/css/skins/_all-skins.min.css',
  '/AdminLTE-2/bower_components/bootstrap/dist/css/bootstrap.min.css',
  '/AdminLTE-2/bower_components/font-awesome/css/font-awesome.min.css',
  '/AdminLTE-2/dist/js/adminlte.min.js',
  '/AdminLTE-2/bower_components/jquery/dist/jquery.min.js',
  '/js/validator.min.js'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(PRECACHE_URLS))
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(clients.claim());
  event.waitUntil(
    caches.keys().then(keys => Promise.all(
      keys.filter(key => key !== CACHE_NAME).map(key => caches.delete(key))
    ))
  );
});

self.addEventListener('fetch', event => {
  if (event.request.method !== 'GET') return;

  // Handle navigation requests with network-first, fallback to offline page
  if (event.request.mode === 'navigate' || (event.request.headers.get('accept') && event.request.headers.get('accept').includes('text/html'))) {
    event.respondWith(
      fetch(event.request)
        .then(response => {
          // Put a copy in the cache
          const copy = response.clone();
          caches.open(CACHE_NAME).then(cache => cache.put(event.request, copy));
          return response;
        })
        .catch(() => caches.match('/offline.html'))
    );
    return;
  }

  // For other requests, try cache first then network
  event.respondWith(
    caches.match(event.request).then(cached => {
      if (cached) return cached;
      return fetch(event.request).then(response => {
        // Cache fetched resources for future
        if (!response || response.status !== 200 || response.type !== 'basic') return response;
        const copy = response.clone();
        caches.open(CACHE_NAME).then(cache => cache.put(event.request, copy));
        return response;
      }).catch(() => {
        // no-op: allow failures to bubble up for non-navigation requests
      });
    })
  );
});
