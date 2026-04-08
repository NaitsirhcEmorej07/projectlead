<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- TITLE --}}
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- LOGO / FAVICON -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/lead_icon_192.png') }}">

    <!-- PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#4f46e5">

    <!-- iOS -->
    <link rel="apple-touch-icon" href="{{ asset('images/lead_icon_192.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Prime icons --}}
    <link rel="stylesheet" href="https://unpkg.com/primeicons/primeicons.css">

    {{-- Tom Select  --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>


    <script>
        // SERVICE WORKER REGISTER
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => {
                        console.log('SW registered 🔥', reg);
                    })
                    .catch(err => {
                        console.log('SW failed ❌', err);
                    });
            });
        }

        document.addEventListener('DOMContentLoaded', () => {

            // ✅ INIT (important)
            window.deferredPrompt = null;

            // ❗ CHECK IF ALREADY INSTALLED
            if (window.matchMedia('(display-mode: standalone)').matches) {
                console.log('Already installed ✅');
                return;
            }

            // CAPTURE install event
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();

                // ✅ better UX (user choice based)
                if (localStorage.getItem('installDismissed')) return;

                window.deferredPrompt = e;
                console.log('INSTALL READY 🔥');

                // AUTO SHOW MODAL
                const modal = document.getElementById('installModal');
                if (modal) {
                    modal.classList.remove('hidden');
                }
            });

            // INSTALL CLICK
            const installBtn = document.getElementById('installNow');
            if (installBtn) {
                installBtn.addEventListener('click', async () => {

                    if (window.deferredPrompt) {
                        window.deferredPrompt.prompt();

                        const {
                            outcome
                        } = await window.deferredPrompt.userChoice;

                        if (outcome === 'accepted') {
                            console.log('User installed ✅');

                            // optional tracking
                            localStorage.setItem('pwaInstalled', 'true');
                        }

                        window.deferredPrompt = null;

                        const modal = document.getElementById('installModal');
                        if (modal) modal.classList.add('hidden');

                    } else {
                        alert('Install not available yet 😅');
                    }

                });
            }

            // CLOSE CLICK
            const closeBtn = document.getElementById('closeInstall');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    const modal = document.getElementById('installModal');
                    if (modal) modal.classList.add('hidden');

                    // ✅ mark as dismissed (important)
                    localStorage.setItem('installDismissed', 'true');
                });
            }

            // ✅ BONUS: detect installed
            window.addEventListener('appinstalled', () => {
                console.log('PWA installed 🎉');
                localStorage.setItem('pwaInstalled', 'true');
            });

        });
    </script>



</body>

</html>
