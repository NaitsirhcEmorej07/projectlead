<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center px-4 bg-gray-100">

        <!-- Card -->
        <div class="w-full max-w-md bg-white shadow-md rounded-2xl p-6 sm:p-8">

            <!-- Logo -->
            <div class="text-center mb-2">
                <img id="leadLogo" src="{{ asset('images/lead_icon.png') }}" alt="Project LEAD Logo"
                    class="w-20 h-20 mx-auto object-contain">
            </div>


            <!-- Heading -->
            <div class="text-center mb-6">
                <h1 class="text-xl font-semibold text-gray-900 tracking-wide">
                    PROJECT LEAD
                </h1>
                <h3 class="text-sm text-gray-500 mt-0 leading-tight">
                    Worship team management made simple.
                </h3>
            </div>



            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full rounded-lg" type="email" name="email"
                        :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full rounded-lg" type="password" name="password"
                        required />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember + Forgot -->
                <div class="flex items-center justify-between mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-gray-500 hover:text-gray-700 underline"
                            href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif
                </div>

                <!-- Button -->
                <div class="mt-6">
                    <x-primary-button class="w-full justify-center rounded-lg">
                        Log in
                    </x-primary-button>
                </div>

                <!-- Back to Landing -->
                <div class="text-center mt-4 ">
                    <span class="text-sm text-gray-500">Don't have an account?</span>
                    <a href="{{ url('/registration_page') }}"
                        class="text-sm text-gray-700 underline hover:text-gray-900 ml-1">
                        Register
                    </a>
                </div>

            </form>

            <!-- Footer -->
            <div class="text-center mt-6 text-xs text-gray-400">
                © {{ date('Y') }} Project LEAD. All rights reserved.
            </div>

        </div>
    </div>

    <!-- INSTALL MODAL -->
    <div id="installModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl shadow-xl p-6 w-[90%] max-w-sm text-center">

            <img src="{{ asset('images/lead_icon_192.png') }}" class="w-16 h-16 mx-auto mb-3">

            <h2 class="text-lg font-semibold">Install LEAD</h2>
            <p class="text-sm text-gray-500 mb-4">
                Get faster access and app-like experience.
            </p>

            <div class="flex gap-2">
                <button id="installNow" class="flex-1 bg-indigo-600 text-white py-2 rounded-lg">
                    Install
                </button>

                <button id="closeInstall" class="flex-1 bg-gray-200 text-gray-700 py-2 rounded-lg">
                    Not now
                </button>
            </div>

        </div>
    </div>

    {{-- OLD SCRIPT POP UP --}}
    {{-- <script>
        let deferredPrompt;

        // CHECK IF ALREADY DISMISSED
        if (!localStorage.getItem('installDismissed')) {

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;

                // show modal
                document.getElementById('installModal').classList.remove('hidden');
            });
        }

        // INSTALL CLICK
        document.getElementById('installNow').addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();

                const {
                    outcome
                } = await deferredPrompt.userChoice;

                if (outcome === 'accepted') {
                    console.log('User installed');
                }

                deferredPrompt = null;
                document.getElementById('installModal').classList.add('hidden');
            }
        });

        // CLOSE CLICK
        document.getElementById('closeInstall').addEventListener('click', () => {
            document.getElementById('installModal').classList.add('hidden');

            // save dismissed state
            localStorage.setItem('installDismissed', true);
        });



        let logoClickCount = parseInt(localStorage.getItem('logoClickCount')) || 0;

        document.getElementById('leadLogo').addEventListener('click', () => {
            logoClickCount++;

            localStorage.setItem('logoClickCount', logoClickCount);

            console.log('Logo clicks:', logoClickCount);

            if (logoClickCount >= 3) {
                // RESET install dismissed
                localStorage.removeItem('installDismissed');

                // SHOW MODAL AGAIN
                document.getElementById('installModal').classList.remove('hidden');

                // RESET counter
                localStorage.setItem('logoClickCount', 0);
            }
        });
    </script> --}}

    <script>
        let deferredPrompt;

        // CAPTURE install event (no auto show)
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
        });

        // INSTALL CLICK
        document.getElementById('installNow').addEventListener('click', async () => {
            if (deferredPrompt) {
                deferredPrompt.prompt();

                const {
                    outcome
                } = await deferredPrompt.userChoice;

                if (outcome === 'accepted') {
                    console.log('User installed');
                }

                deferredPrompt = null;
                document.getElementById('installModal').classList.add('hidden');
            } else {
                alert('Install not available yet 😅');
            }
        });

        // CLOSE CLICK
        document.getElementById('closeInstall').addEventListener('click', () => {
            document.getElementById('installModal').classList.add('hidden');
        });

        // LOGO CLICK (3x trigger)
        let logoClickCount = 0;

        document.getElementById('leadLogo').addEventListener('click', () => {
            logoClickCount++;

            console.log('Logo clicks:', logoClickCount);

            if (logoClickCount >= 3) {
                if (deferredPrompt) {
                    document.getElementById('installModal').classList.remove('hidden');
                } else {
                    alert('Install not available yet 😅');
                }

                logoClickCount = 0;
            }
        });
    </script>



</x-guest-layout>
