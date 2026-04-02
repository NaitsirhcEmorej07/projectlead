<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center p-4 bg-gray-100">

        <!-- Card -->
        <div class="w-full max-w-md bg-white shadow-md rounded-2xl p-6 sm:p-8">

            <!-- Logo -->
            <div class="text-center mb-2">
                <img src="{{ asset('images/lead_icon.png') }}" alt="Project LEAD Logo"
                    class="w-20 h-20 mx-auto object-contain">
            </div>

            <!-- Heading -->
            <div class="text-center mb-6">
                <h1 class="text-xl font-semibold text-gray-900 tracking-wide">
                    PROJECT LEAD
                </h1>
                <h3 class="text-sm text-gray-500 mt-0 leading-tight">
                    Worship team management, made simple.
                </h3>
                {{-- <h3 class="text-sm font-normal text-gray-500 mt-1 leading-tight">
                    Reset your password
                </h3> --}}
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-center" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="__('Enter email')" />
                    <x-text-input id="email" class="block mt-1 w-full rounded-lg" type="email" name="email"
                        :value="old('email')" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Button -->
                <div class="mt-6">
                    <x-primary-button class="w-full justify-center rounded-lg">
                        Send Reset Link
                    </x-primary-button>
                </div>

                
                <!-- Back -->
                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700 underline">
                        Back to login
                    </a>
                </div>

            </form>

            <!-- Footer -->
            <div class="text-center mt-6 text-xs text-gray-400">
                © {{ date('Y') }} Project LEAD. All rights reserved.
            </div>

        </div>
    </div>

</x-guest-layout>
