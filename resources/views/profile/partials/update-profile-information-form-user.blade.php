<section>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-3">
        @csrf
        @method('patch')

        <!-- PROFILE PICTURE -->
        <div x-data="{
            preview: @js($user->profile_picture ? Storage::url($user->profile_picture) : '')
        }">

            <div class="flex justify-center">

                <div class="relative">

                    <!-- Avatar -->
                    <template x-if="preview">
                        <img :src="preview" class="h-16 w-16 rounded-full object-cover border">
                    </template>

                    <!-- No Image -->
                    <template x-if="!preview">
                        <div
                            class="h-16 w-16 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 text-xs border">
                            No Photo
                        </div>
                    </template>

                    <!-- Camera Icon -->
                    <label for="profile_picture"
                        class="absolute bottom-0 right-0 bg-gray-800 text-white p-1 rounded-full cursor-pointer hover:bg-gray-700">

                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h4l2-2h6l2 2h4v12H3V7z" />
                        </svg>
                    </label>

                    <!-- Hidden File Input -->
                    <input id="profile_picture" name="profile_picture" type="file" class="hidden"
                        @change="
                            const file = $event.target.files[0];
                            if (file) preview = URL.createObjectURL(file);
                        ">
                </div>

            </div>

            <x-input-error class="mt-1" :messages="$errors->get('profile_picture')" />
        </div>

        {{-- ROLE  --}}
        <div class="space-y-1">
            <x-input-label for="roles" :value="__('Role')" />
            <select id="roles" name="roles[]" multiple class="block w-full">
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}" @if (isset($userRoleIds) && in_array($role->id, $userRoleIds)) selected @endif>
                        {{ $role->role_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- NAME -->
        <div class="space-y-1">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="block w-full text-sm" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-1" :messages="$errors->get('name')" />
        </div>

        <!-- EMAIL -->
        <div class="space-y-1">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="block w-full text-sm" :value="old('email', $user->email)"
                required autocomplete="username" />
            <x-input-error class="mt-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="text-xs">
                    <p class="text-gray-700">
                        {{ __('Your email is unverified.') }}

                        <button form="send-verification" class="underline text-gray-600 hover:text-gray-900">
                            {{ __('Resend email') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-green-600">
                            {{ __('Verification link sent.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- CONTACT NUMBER -->
        <div class="space-y-1">
            <x-input-label for="contact_number" :value="__('Contact Number')" />
            <x-text-input id="contact_number" name="contact_number" type="text" class="block w-full text-sm"
                :value="old('contact_number', $user->contact_number)" />
            <x-input-error class="mt-1" :messages="$errors->get('contact_number')" />
        </div>

        <!-- DESCRIPTION -->
        <div class="space-y-1">
            <x-input-label for="describe" :value="__('Share your Story')" />
            <textarea name="describe" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" rows="5">{{ old('describe', $user->describe) }}</textarea>
            <x-input-error class="mt-1" :messages="$errors->get('describe')" />
        </div>

        <!-- ACTION -->
        <div class="flex items-center gap-2 mt-2">
            <x-primary-button class="px-4 py-2 text-sm">
                {{ __('Save') }}
            </x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 1500)"
                    class="text-xs text-gray-500">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>

    </form>
</section>


<script>
    new TomSelect('#roles', {
        plugins: ['remove_button'],
        placeholder: 'Select roles...',
    });
</script>
