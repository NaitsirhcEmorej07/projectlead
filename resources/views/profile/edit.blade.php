<x-app-layout>
    <div class="p-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">

            <!-- Update Profile Church (Admin only) -->
            @churchAdmin
                <div class="flex justify-center">
                    <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6 w-full max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>
            @endchurchAdmin


            <!-- Update Profile User (User only) -->
            @churchUser
                <div class="flex justify-center">
                    <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6 w-full max-w-xl">
                        @include('profile.partials.update-profile-information-form-user')
                    </div>
                </div>
            @endchurchUser

            <!-- Update Password -->
            <div class="flex justify-center">
                <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6 w-full max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User -->
            <div class="flex justify-center">
                <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6 w-full max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            {{-- Reference for centered UI: https://chatgpt.com/g/g-p-69c4a14703c88191b5ff915440a74d2c-project-lead/c/69ce80bf-6728-8320-ac4b-19f3d46848be --}}
        </div>
    </div>
</x-app-layout>
