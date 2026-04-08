<x-app-layout>
    <div class="p-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <div class="bg-white shadow-sm rounded-2xl p-5 sm:p-6">

                <!-- HEADER -->
                <div class="mb-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                        <div>
                            @php
                                $church = \App\Models\Church::find(session('church_id'));
                            @endphp

                            <h1 class="text-lg sm:text-2xl font-bold text-gray-900">
                                {{ $church->abbr ?? 'Church' }} Worship Schedule
                            </h1>
                            <p class="text-xs text-gray-500">
                                Stay updated upcoming schedules and events
                            </p>
                        </div>

                        <!-- DESKTOP NAV -->
                        <div class="hidden sm:flex items-center gap-2">

                            <button class="px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm transition">
                                Prev
                            </button>

                            <div class="px-4 py-2 rounded-xl bg-gray-50 text-gray-600 text-sm font-medium">
                                Month Year
                            </div>

                            <button class="px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm transition">
                                Next
                            </button>

                        </div>
                    </div>
                </div>

                <!-- WEEK DAYS -->
                <div class="grid grid-cols-7 gap-2 mb-2">
                    @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                        <div class="text-center text-xs sm:text-sm font-semibold text-gray-400 py-2">
                            {{ $day }}
                        </div>
                    @endforeach
                </div>

                <!-- CALENDAR GRID -->
                <div class="grid grid-cols-7 gap-1.5 sm:gap-2">

                    @for ($day = 1; $day <= 35; $day++)
                        <div
                            class="min-h-[70px] sm:min-h-[120px]
                                   rounded-xl sm:rounded-2xl
                                   border border-gray-200
                                   bg-white
                                   hover:border-indigo-300
                                   hover:shadow-sm
                                   transition cursor-pointer p-2">

                            <!-- DATE -->
                            <div class="flex justify-end">
                                <span class="text-xs sm:text-sm font-semibold text-gray-700">
                                    {{ $day }}
                                </span>
                            </div>

                        </div>
                    @endfor

                </div>

                <!-- MOBILE NAV -->
                <div class="mt-5 flex sm:hidden justify-center">
                    <div class="flex items-center gap-3">

                        <button class="p-1.5 text-gray-500 hover:text-indigo-600 transition">
                            <i class="pi pi-chevron-left text-sm"></i>
                        </button>

                        <div class="px-3 py-1.5 text-sm font-medium text-gray-700">
                            Month
                        </div>

                        <button class="p-1.5 text-gray-500 hover:text-indigo-600 transition">
                            <i class="pi pi-chevron-right text-sm"></i>
                        </button>

                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
