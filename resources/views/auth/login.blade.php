<x-guest-layout>

    <div class="min-h-screen flex items-center justify-center px-4 bg-gray-100">

        <!-- Card -->
        <div class="w-full max-w-md bg-white shadow-md rounded-2xl p-6 sm:p-8">

            <!-- Logo -->
            <div class="text-center mb-2">
                <img src="{{ asset('images/lead_icon.png') }}" alt="Project LEAD Logo"
                    class="w-20 h-20 mx-auto object-contain">
            </div>


            <!-- Heading -->
            <div class="text-center mb-6">
                <h1 class="text-xl font-semibold text-gray-900">PROJECT LEAD</h1>
                <h3 class="text-sm font-normal text-gray-500 mt-0 leading-tight">
                    Worship team management, made simple.
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
                    <a href="{{ url('/') }}" class="text-sm text-gray-700 underline hover:text-gray-900 ml-1">
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

</x-guest-layout>
