<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4 border border-indigo-200">
        <div class="bg-white rounded-2xl shadow-md p-6 w-full max-w-md">

            <!-- Logo -->
            <div class="text-center mb-2">
                <img src="{{ asset('images/lead_icon.png') }}" alt="Project LEAD Logo"
                    class="w-20 h-20 mx-auto object-contain">
            </div>


            <h2 class="text-lg font-semibold text-center mb-4">
                Select Your Church
            </h2>

            <form method="POST" action="{{ route('select-church.store') }}">
                @csrf

                <div class="space-y-3">
                    @foreach ($churches as $church)
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="church_id" value="{{ $church->id }}" required class="mr-3">
                            <span>{{ $church->name }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-5">
                    <x-primary-button class="w-full justify-center">
                        Continue
                    </x-primary-button>
                </div>

            </form>

            <div class="text-center mt-6">
                <h3 class="text-xs font-normal italic text-gray-500 mt-4 leading-tight">
                    "A worship team management app that organize team members, manage lineups, and plan
                    schedules—keeping
                    your team aligned for every service."
                </h3>
            </div>


        </div>
    </div>
</x-guest-layout>
