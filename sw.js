const CACHE_NAME = 'rigel-app-v1';
const urlsToCache = [
  './index.php',
  './css/style.css',
  './css/auth.css',
  './css/dashboard.css',
  './images/logo.png'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        return response || fetch(event.request);
      })
  );
});
