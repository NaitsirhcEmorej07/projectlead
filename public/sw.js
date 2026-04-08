const CACHE_NAME = 'lead-cache-v1';

const urlsToCache = [
    '/',
    '/manifest.json',
    '/images/lead_icon_192.png',
    '/images/lead_icon_512.png'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Caching files...');
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