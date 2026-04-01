<section>
    <header>
        <h2 class="text-lg font-semibold text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6 ">
        @csrf
        @method('patch')

        <!-- LOGO UPLOAD -->
        <div x-data="{ preview: '{{ $user->churches->first()?->logo ? asset('storage/' . $user->churches->first()->logo) : '' }}' }">
            {{-- <x-input-label for="logo" :value="__('Profile Logo')" /> --}}

            <div class="mt-4 flex justify-center">

                <!-- Avatar Wrapper -->
                <div class="relative">

                    <!-- Avatar -->
                    <template x-if="preview">
                        <img :src="preview" class="h-20 w-20 rounded-full object-cover border">
                    </template>

                    <template x-if="!preview">
                        <div
                            class="h-20 w-20 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 text-xs border">
                            No Logo
                        </div>
                    </template>

                    <!-- Camera Icon -->
                    <label for="logo"
                        class="absolute bottom-0 right-0 bg-gray-800 text-white p-1.5 rounded-full cursor-pointer hover:bg-gray-700">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h4l2-2h6l2 2h4v12H3V7z" />
                        </svg>
                    </label>

                    <!-- Hidden File Input -->
                    <input id="logo" name="logo" type="file" class="hidden"
                        @change="
                    const file = $event.target.files[0];
                    if (file) preview = URL.createObjectURL(file);
                ">
                </div>

            </div>

            <x-input-error class="mt-2" :messages="$errors->get('logo')" />
        </div>

        <!-- NAME -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- EMAIL -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- ACTION -->
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
