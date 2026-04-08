<x-app-layout>
    <div class="p-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            @php
                $church = \App\Models\Church::find(session('church_id'));

                if (!isset($currentDate)) {
                    $currentDate = now()->startOfMonth();
                    $month = now()->month;
                    $year = now()->year;
                    $startDayOfWeek = $currentDate->copy()->startOfMonth()->dayOfWeek;
                    $daysInMonth = $currentDate->copy()->endOfMonth()->day;
                }

                $schedules = isset($schedules) ? $schedules->map(fn($item) => $item->values()) : collect();

                $schedulesJson = $schedules->map(function ($daySchedules) {
                    return $daySchedules
                        ->map(function ($sched) {
                            return [
                                'id' => $sched->id,
                                'sched_title' => $sched->sched_title,
                                'sched_description' => $sched->sched_description,
                                'sched_time' => $sched->sched_time,
                                'sched_type' => $sched->sched_type,
                            ];
                        })
                        ->values();
                });
            @endphp

            <div x-data="calendarData()" class="max-w-7xl mx-auto">

                <div class="bg-white shadow-sm rounded-2xl p-5 sm:p-6">

                    <!-- HEADER -->
                    <div class="mb-5">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">

                            <div>
                                <h1 class="text-xl sm:text-2xl font-bold text-gray-900">
                                    {{ $church->abbr ?? 'Church' }} Worship Schedule
                                </h1>
                                <p class="text-sm text-gray-500">
                                    Click a date to view schedules
                                </p>
                            </div>

                            <!-- DESKTOP NAV -->
                            <div class="hidden sm:flex items-center gap-2">
                                <a href="{{ route('worship-schedule', [
                                    'month' => $currentDate->copy()->subMonth()->month,
                                    'year' => $currentDate->copy()->subMonth()->year,
                                ]) }}"
                                    class="px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm">
                                    Prev
                                </a>

                                <div class="px-4 py-2 rounded-xl bg-indigo-50 text-indigo-700 text-sm font-medium">
                                    {{ $currentDate->format('F Y') }}
                                </div>

                                <a href="{{ route('worship-schedule', [
                                    'month' => $currentDate->copy()->addMonth()->month,
                                    'year' => $currentDate->copy()->addMonth()->year,
                                ]) }}"
                                    class="px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-sm">
                                    Next
                                </a>
                            </div>

                        </div>
                    </div>

                    <!-- WEEK DAYS -->
                    <div class="grid grid-cols-7 gap-2 mb-2">
                        @foreach (['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                            <div class="text-center text-xs sm:text-sm font-semibold text-gray-500 py-2">
                                {{ $day }}
                            </div>
                        @endforeach
                    </div>

                    <!-- CALENDAR GRID -->
                    <div class="grid grid-cols-7 gap-1.5 sm:gap-2">

                        @for ($i = 0; $i < $startDayOfWeek; $i++)
                            <div class="min-h-[64px] sm:min-h-[100px] rounded-xl bg-gray-50 border"></div>
                        @endfor

                        @for ($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $fullDate = \Carbon\Carbon::createFromDate($year, $month, $day)->toDateString();
                                $daySchedules = $schedules[$fullDate] ?? collect();
                                $isToday = $fullDate === now()->toDateString();
                                $labelDate = \Carbon\Carbon::createFromDate($year, $month, $day)->format('F d, Y');
                            @endphp

                            <button @click="openModal('{{ $fullDate }}', '{{ $labelDate }}')"
                                class="min-h-[64px] sm:min-h-[120px] rounded-xl border p-1.5 sm:p-3 text-left transition hover:shadow-sm
                                {{ $isToday
                                    ? 'border-indigo-500 bg-indigo-100'
                                    : ($daySchedules->count()
                                        ? 'border-green-500 bg-green-50'
                                        : 'border-gray-200 bg-white hover:border-indigo-300') }}">

                                <div class="flex justify-between items-start">
                                    <span class="text-xs sm:text-sm font-bold">
                                        {{ $day }}
                                    </span>

                                    @if ($daySchedules->count())
                                        <span
                                            class="hidden sm:flex min-w-[22px] h-[22px] px-1 rounded-full bg-green-600 text-white text-[11px] items-center justify-center">
                                            {{ $daySchedules->count() }}
                                        </span>
                                    @endif
                                </div>

                                <!-- PREVIEW -->
                                <div class="hidden sm:block mt-2 space-y-1">
                                    @foreach ($daySchedules->take(2) as $sched)
                                        <div class="rounded-lg bg-gray-100 px-2 py-1 text-[11px] truncate">
                                            {{ $sched->sched_title }}
                                        </div>
                                    @endforeach

                                    @if ($daySchedules->count() > 2)
                                        <div class="text-[11px] text-gray-400">
                                            +{{ $daySchedules->count() - 2 }} more
                                        </div>
                                    @endif
                                </div>

                            </button>
                        @endfor

                    </div>

                    <!-- 🔥 MOBILE NAV (IMPORTANT) -->
                    <div class="mt-5 flex sm:hidden justify-center">
                        <div class="flex items-center gap-3">

                            <a href="{{ route('worship-schedule', [
                                'month' => $currentDate->copy()->subMonth()->month,
                                'year' => $currentDate->copy()->subMonth()->year,
                            ]) }}"
                                class="p-1.5 text-gray-500 hover:text-indigo-600 transition">
                                <i class="pi pi-chevron-left text-sm"></i>
                            </a>

                            <div class="px-3 py-1.5 text-sm font-medium text-gray-700">
                                {{ $currentDate->format('M Y') }}
                            </div>

                            <a href="{{ route('worship-schedule', [
                                'month' => $currentDate->copy()->addMonth()->month,
                                'year' => $currentDate->copy()->addMonth()->year,
                            ]) }}"
                                class="p-1.5 text-gray-500 hover:text-indigo-600 transition">
                                <i class="pi pi-chevron-right text-sm"></i>
                            </a>

                        </div>
                    </div>

                </div>

                <!-- MODAL -->
                <div x-show="open" x-transition
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4" style="display:none;">

                    <div @click.outside="open=false"
                        class="w-full max-w-xl bg-white rounded-2xl shadow-xl p-5 sm:p-6 max-h-[90vh] overflow-y-auto">

                        <!-- HEADER -->
                        <div class="flex items-start justify-between gap-3 mb-5">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Schedules</h2>
                                <p class="text-sm text-gray-500" x-text="selectedDateLabel"></p>
                            </div>

                            <button @click="open=false"
                                class="p-2 rounded-lg text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition">
                                <i class="pi pi-times text-sm"></i>
                            </button>
                        </div>

                        <!-- 🔥 ADD SCHEDULE -->
                        <div x-data="{ openForm: false }"
                            class="border border-gray-200 rounded-2xl mb-5 overflow-hidden bg-white">

                            <!-- TOGGLE -->
                            <button type="button" @click="openForm = !openForm"
                                class="w-full flex items-center justify-between px-4 py-3 hover:bg-gray-50 transition text-left">

                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Add Schedule</h3>
                                    <p class="text-xs text-gray-500">Create schedule for this date</p>
                                </div>

                                <i class="pi text-xs text-gray-400"
                                    :class="openForm ? 'pi-chevron-up' : 'pi-chevron-down'"></i>
                            </button>

                            <!-- FORM -->
                            <div x-show="openForm" x-transition class="border-t border-gray-100 p-3">

                                <form action="{{ route('worship-schedule.store') }}" method="POST" class="space-y-2">
                                    @csrf

                                    <!-- DATE -->
                                    <input type="hidden" name="sched_date" :value="selectedDate">

                                    <!-- TITLE -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Title</label>
                                        <input type="text" name="sched_title"
                                            class="w-full rounded-xl border-gray-300 text-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Enter title">
                                    </div>

                                    <!-- 🔥 TIME + TYPE -->
                                    <div class="grid grid-cols-2 gap-3">

                                        <!-- TIME -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Time</label>
                                            <input type="time" name="sched_time" value="00:00"
                                                class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <!-- TYPE -->
                                        <div>
                                            <label class="block text-xs font-medium text-gray-500 mb-1">Type</label>
                                            <select name="sched_type"
                                                class="w-full rounded-xl border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">

                                                <option value="" disabled selected>Select type</option>
                                                <option value="Worship Ministry">Worship Ministry</option>
                                                <option value="Dance Ministry">Dance Ministry</option>
                                                <option value="Kithcen Ministry">Kithcen Ministry</option>
                                                <option value="Children Ministry">Children Ministry</option>

                                            </select>
                                        </div>

                                    </div>

                                    <!-- DESCRIPTION -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-500 mb-1">Description</label>
                                        <textarea name="sched_description" rows="9"
                                            class="w-full rounded-xl border-gray-300 text-sm placeholder:text-gray-400 focus:border-indigo-500 focus:ring-indigo-500"
                                            placeholder="Add details..."></textarea>
                                    </div>

                                    <!-- BUTTON -->
                                    <div class="pt-1">
                                        <button type="submit"
                                            class="w-full rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 transition">
                                            Save Schedule
                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>

                        <!-- 🔥 LIST -->
                        <template x-if="selectedSchedules.length">
                            <div class="space-y-3">
                                <template x-for="sched in selectedSchedules" :key="sched.id">
                                    <div x-data="{ editMode: false }"
                                        class="rounded-2xl border border-gray-200 bg-gray-50 p-4">

                                        <!-- VIEW MODE -->
                                        <div x-show="!editMode">

                                            <!-- CONTENT -->
                                            <div class="min-w-0">

                                                <!-- TITLE + TIME -->
                                                <p class="font-semibold text-gray-900">
                                                    <span x-text="sched.sched_title"></span>
                                                    <span x-show="sched.sched_time"> AT </span>
                                                    <span x-show="sched.sched_time"
                                                        x-text="new Date('1970-01-01T' + sched.sched_time).toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' })">
                                                    </span>
                                                </p>

                                                <!-- TYPE -->
                                                <template x-if="sched.sched_type">
                                                    <div class="mt-1">
                                                        <span
                                                            class="inline-block text-xs px-2 py-1.5 rounded-full bg-indigo-100 text-indigo-700"
                                                            x-text="sched.sched_type">
                                                        </span>
                                                    </div>
                                                </template>

                                                <!-- DESCRIPTION -->
                                                <template x-if="sched.sched_description">
                                                    <p class="text-sm text-gray-600 mt-5 mb-5 whitespace-pre-line"
                                                        x-text="sched.sched_description">
                                                    </p>
                                                </template>

                                            </div>

                                            <!-- 🔥 ACTIONS (BOTTOM ICONS) -->
                                            <div class="mt-1 flex justify-center gap-1 border-t pt-1">

                                                <!-- EDIT -->
                                                <button @click="editMode = true"
                                                    class="p-1 text-gray-400 hover:text-indigo-600 transition">
                                                    <i class="pi pi-pencil text-sm"></i>
                                                </button>

                                                <!-- DELETE -->
                                                <form :action="`/worship-schedule/${sched.id}`" method="POST"
                                                    onsubmit="return confirm('Delete this schedule?')">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="p-1 text-gray-400 hover:text-red-600 transition">
                                                        <i class="pi pi-trash text-sm"></i>
                                                    </button>
                                                </form>

                                            </div>

                                        </div>

                                        <!-- 🔥 EDIT MODE -->
                                        <div x-show="editMode" x-transition class="mt-2">

                                            <form :action="`/worship-schedule/${sched.id}`" method="POST"
                                                class="space-y-3">
                                                @csrf
                                                @method('PUT')

                                                <input type="text" name="sched_title" :value="sched.sched_title"
                                                    class="w-full rounded-xl border-gray-300 text-sm">

                                                <div class="grid grid-cols-2 gap-2">
                                                    <input type="time" name="sched_time" :value="sched.sched_time"
                                                        class="rounded-xl border-gray-300 text-sm">

                                                    <select name="sched_type"
                                                        class="rounded-xl border-gray-300 text-sm">
                                                        <option value="">Select</option>
                                                        <option value="Worship Ministry">Worship Ministry</option>
                                                        <option value="Dance Ministry">Dance Ministry</option>
                                                        <option value="Kitchen Ministry">Kitchen Ministry</option>
                                                        <option value="Children Ministry">Children Ministry</option>
                                                    </select>
                                                </div>

                                                <textarea name="sched_description" rows="3" class="w-full rounded-xl border-gray-300 text-sm"
                                                    x-text="sched.sched_description"></textarea>

                                                <div class="flex gap-2">
                                                    <button type="submit"
                                                        class="px-3 py-1.5 bg-indigo-600 text-white text-xs rounded-lg">
                                                        Update
                                                    </button>

                                                    <button type="button" @click="editMode = false"
                                                        class="px-3 py-1.5 bg-gray-200 text-gray-700 text-xs rounded-lg">
                                                        Cancel
                                                    </button>
                                                </div>

                                            </form>

                                        </div>

                                    </div>
                                </template>
                            </div>
                        </template>

                        <!-- EMPTY -->
                        <template x-if="!selectedSchedules.length">
                            <div
                                class="rounded-2xl border border-dashed border-gray-300 p-6 text-sm text-gray-500 text-center">
                                No schedules for this day yet.
                            </div>
                        </template>

                    </div>
                </div>

            </div>

            <!-- ALPINE -->
            <script>
                function calendarData() {
                    return {
                        open: false,
                        selectedDateLabel: '',
                        selectedSchedules: [],
                        schedules: @json($schedulesJson),
                        selectedDate: '',

                        openModal(date, label) {
                            this.selectedDate = date;
                            this.selectedDateLabel = label;
                            this.selectedSchedules = this.schedules[date] ?? [];
                            this.open = true;
                        }
                    }
                }
            </script>

        </div>
    </div>
</x-app-layout>
