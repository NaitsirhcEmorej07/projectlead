<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">

        <div class="bg-white rounded-2xl shadow-md p-6 w-full max-w-md">

            <h2 class="text-lg font-semibold text-center mb-4">
                Select Your Church
            </h2>

            <form method="POST" action="{{ route('select-church.store') }}">
                @csrf

                <div class="space-y-3">
                    @foreach ($churches as $church)
                        <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="church_id" value="{{ $church->id }}" required
                                class="mr-3">
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

        </div>
    </div>
</x-guest-layout>