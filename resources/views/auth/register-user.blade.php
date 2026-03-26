<x-guest-layout>

    <style>
        .ts-control {
            border-radius: 0.5rem !important;
            padding: 10px !important;
            border: 1px solid #d1d5db !important;
        }

        .ts-control:focus-within {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 1px #6366f1 !important;
        }

        .ts-control,
        .ts-dropdown,
        .ts-dropdown .option {
            font-family: inherit !important;
            font-size: 12x;
        }
    </style>

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
                <h1 class="text-xl font-semibold text-gray-900">
                    PROJECT LEAD
                </h1>
                <h3 class="text-sm font-normal text-gray-500 mt-0 leading-tight">
                    Welcome Worship Team!
                </h3>
                <h3 class="text-sm font-normal text-gray-500 mt-0 leading-tight">
                    Select your church and create your account
                </h3>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <input type="hidden" name="type" value="member">

                <!-- Church Selection -->
                <div>
                    <x-input-label for="church" :value="__('Church')" />

                    <select id="church_tom" name="church_id">
                        <option value="">Select Church</option>

                        @foreach ($churches as $church)
                            <option value="{{ $church->id }}">
                                {{ $church->name }}
                            </option>
                        @endforeach
                    </select>

                    <x-input-error :messages="$errors->get('church_id')" class="mt-2" />
                </div>

                <!-- Name -->
                <div class="mt-4">
                    <x-input-label for="name" :value="__('Name')" />

                    <x-text-input id="name" class="block mt-1 w-full rounded-lg" type="text" name="name"
                        :value="old('name')" required autofocus />

                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="mt-4">
                    <x-input-label for="email" :value="__('Email')" />

                    <x-text-input id="email" class="block mt-1 w-full rounded-lg" type="email" name="email"
                        :value="old('email')" required />

                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input id="password" class="block mt-1 w-full rounded-lg" type="password" name="password"
                        required />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-4">
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

    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
        new TomSelect("#church_tom", {
            create: false,
            sortField: {
                field: "text",
                direction: "asc"
            }
        });
    </script>


</x-guest-layout>
