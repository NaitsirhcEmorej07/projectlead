const CACHE_NAME = 'lead-cache-v3';

const urlsToCache = [
    // '/',
    // '/login',

    '/manifest.json',
    '/offline.html',

    '/images/lead_icon.png',
    '/images/leadv2_icon.png',
    '/images/lead_icon_192.png',
    '/images/lead_icon_512.png',
];

// 🧩 INSTALL → cache all important files
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Caching files...');
                return cache.addAll(urlsToCache);
            })
    );
});

// 🧹 ACTIVATE → delete old caches
self.addEventListener('activate', event => {
    const whitelist = [CACHE_NAME];

    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.map(name => {
                    if (!whitelist.includes(name)) {
                        return caches.delete(name);
                    }
                })
            );
        })
    );
});

// 🌐 FETCH → cache first, then network, fallback offline
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {

                // ✅ 1. If nasa cache → return agad
                if (response) {
                    return response;
                }

                // 🌐 2. Try network
                return fetch(event.request)
                    .catch(() => {

                        // ⚠️ 3. If page navigation → show offline page
                        if (event.request.mode === 'navigate') {
                            return caches.match('/offline.html');
                        }

                    });
            })
    );
});