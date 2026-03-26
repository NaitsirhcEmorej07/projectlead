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
                <h1 class="text-lg font-semibold text-gray-900 tracking-wide">
                    CHURCH ADMIN REGISTRATION
                </h1>
                <h3 class="text-sm text-gray-500 mt-0 leading-tight" style="font-family: 'Dancing Script', cursive;">
                    Worship team management, made simple.
                </h3>
                {{-- <h3 class="text-sm font-normal text-gray-500 mt-1 leading-tight">
                    Please provide your church details below
                </h3> --}}
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <input type="hidden" name="type" value="admin">

                <!-- Church Name -->
                <div>
                    <x-input-label for="church_name" :value="__('Church Name')" />
                    <x-text-input id="church_name" class="block mt-1 w-full rounded-lg" type="text"
                        name="church_name" :value="old('church_name')" required autofocus />
                    <x-input-error :messages="$errors->get('church_name')" class="mt-2" />
                </div>

                <!-- Church Abbreviation -->
                <div class="mt-3">
                    <x-input-label for="church_abbr" :value="__('Church Abbreviation')" />
                    <x-text-input id="church_abbr" class="block mt-1 w-full rounded-lg uppercase tracking-wider"
                        type="text" name="church_abbr" :value="old('church_abbr')"
                        oninput="this.value = this.value.toUpperCase()" required />
                    <x-input-error :messages="$errors->get('church_abbr')" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="mt-3">
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full rounded-lg" type="email" name="email"
                        :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-3">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full rounded-lg" type="password" name="password"
                        required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-3">
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-lg" type="password"
                        name="password_confirmation" required />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between mt-6">
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700 underline">
                        Already have an account?
                    </a>

                    <x-primary-button class="rounded-lg px-6">
                        Register
                    </x-primary-button>
                </div>

            </form>

            <!-- Footer -->
            <div class="text-center mt-6 text-xs text-gray-400">
                © {{ date('Y') }} Project LEAD. All rights reserved.
            </div>

        </div>
    </div>

</x-guest-layout>
