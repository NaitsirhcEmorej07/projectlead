<x-app-layout>
    <div class="p-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6 border border-gray-300">

                {{-- HEADER --}}
                @php
                    $church = Auth::user()->churches()->where('church_id', session('church_id'))->first();
                @endphp

                <div class="flex items-center justify-between mb-3">

                    <h2 class="text-md font-semibold text-gray-800">
                        {{ $church->abbr ?? 'Church' }} SONGS ARCHIVE
                    </h2>

                    <button onclick="openAddModal()" class="text-gray-400 hover:text-indigo-600 transition"
                        title="Add Song">
                        <i class="pi pi-plus text-lg"></i>
                    </button>

                </div>

                {{-- SUCCESS --}}
                @if (session('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.opacity
                        class="mb-4 text-green-600 text-sm">
                        {{ session('success') }}
                    </div>
                @endif



                {{-- SEARCH --}}
                <form method="GET" class="mb-3" x-data="songSearchComponent()">
                    <div class="relative">

                        <input type="text" name="search" x-model="query" @input="filterSongs"
                            @keydown.enter="$el.form.submit()" value="{{ request('search') }}"
                            placeholder="Search songs..." autocomplete="off"
                            class="w-full border rounded-lg px-3 py-2 text-sm focus:ring-1 focus:ring-indigo-500">

                        <div x-show="filtered.length > 0" @click.outside="filtered = []"
                            class="absolute z-10 w-full bg-white border rounded-lg mt-1 shadow">

                            <template x-for="song in filtered" :key="song.id">
                                <div @click="selectSong(song.song_title); $el.closest('form').submit()"
                                    class="px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm">

                                    <div class="font-medium" x-text="song.song_title"></div>
                                    <div class="text-xs text-gray-500" x-text="song.song_by"></div>

                                </div>
                            </template>

                        </div>

                    </div>
                </form>

                {{-- EMPTY STATE --}}
                @if ($songs->isEmpty())
                    <div class="text-center text-gray-500 py-10">
                        No songs yet. Add your first song 🎵
                    </div>
                @endif


                {{-- CARDS --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">

                    @foreach ($songs as $song)
                        <div
                            class="border rounded-xl px-3 py-2 shadow-sm 
                                    hover:shadow-md hover:-translate-y-0.5 
                                    transition-all duration-200">

                            <div class="flex justify-between items-start gap-2">

                                {{-- LEFT --}}
                                <div class="min-w-0 flex flex-col gap-0.5">

                                    <div class="font-medium text-gray-800 text-sm truncate">
                                        {{ $song->song_title }}
                                    </div>

                                    <div class="text-xs text-gray-500 truncate">
                                        {{ $song->song_by ?? 'Unknown Artist' }}
                                    </div>

                                    <div class="text-xs text-gray-500 truncate">
                                        Original key: {{ $song->original_key ?? 'Unknown Artist' }}
                                    </div>

                                </div>

                                {{-- ACTIONS --}}
                                <div class="flex gap-2 shrink-0 pt-0.5">

                                    <!-- EDIT -->
                                    <button
                                        onclick="openEditModal(
                                            {{ $song->id }},
                                            '{{ addslashes($song->song_title) }}',
                                            '{{ addslashes($song->song_by) }}',
                                            '{{ $song->song_reference }}',
                                            '{{ $song->original_key }}'
                                        )"
                                        class="text-gray-400 hover:text-blue-600 transition">
                                        <i class="pi pi-pencil text-sm"></i>
                                    </button>

                                    <!-- DELETE -->
                                    <form method="POST" action="{{ route('songs.destroy', $song->id) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" onclick="return confirm('Delete this song?')"
                                            class="text-gray-400 hover:text-red-600 transition">
                                            <i class="pi pi-trash text-sm"></i>
                                        </button>
                                    </form>

                                    <!-- LINK -->
                                    @if ($song->song_reference)
                                        <a href="{{ $song->song_reference }}" target="_blank"
                                            class="text-gray-400 hover:text-indigo-600 transition">
                                            <i class="pi pi-link text-sm"></i>
                                        </a>
                                    @endif

                                </div>

                            </div>

                        </div>
                    @endforeach

                </div>

            </div>
        </div>
    </div>

    {{-- ADD MODAL --}}
    <div id="addModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-end sm:items-center justify-center z-50 px-4">
        <div class="bg-white rounded-t-2xl sm:rounded-lg w-full sm:max-w-md p-5">

            <h2 class="text-lg font-semibold mb-4">Add Song</h2>

            <form method="POST" action="{{ route('songs.store') }}" class="space-y-3">
                @csrf

                <div>
                    <label class="text-sm">Song Title</label>
                    <input type="text" name="song_title" required class="w-full border rounded-lg p-2 mt-1">
                </div>

                <div>
                    <label class="text-sm">Song By</label>
                    <input type="text" name="song_by" class="w-full border rounded-lg p-2 mt-1">
                </div>

                {{-- ✅ ORIGINAL KEY --}}
                <div>
                    <label class="text-sm">Original Key</label>
                    <input type="text" name="original_key" class="w-full border rounded-lg p-2 mt-1">
                </div>

                <div>
                    <label class="text-sm">Reference Link</label>
                    <input type="text" name="song_reference" class="w-full border rounded-lg p-2 mt-1">
                </div>

                <div class="flex justify-center gap-3 pt-2">

                    <!-- ❌ Cancel -->
                    <button type="button" onclick="closeAddModal()"
                        class="p-2 text-gray-500 hover:text-red-500 transition" title="Cancel">
                        <i class="pi pi-times text-lg"></i>
                    </button>

                    <!-- ✅ Save -->
                    <button type="submit" class="p-2 text-gray-500 hover:text-indigo-600 transition" title="Save">
                        <i class="pi pi-check text-lg"></i>
                    </button>

                </div>
            </form>

        </div>
    </div>

    {{-- EDIT MODAL --}}
    <div id="editModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden items-end sm:items-center justify-center z-50 px-4">
        <div class="bg-white rounded-t-2xl sm:rounded-lg w-full sm:max-w-md p-5">

            <h2 class="text-lg font-semibold mb-4">Edit Song</h2>

            <form method="POST" id="editForm" class="space-y-3">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-sm">Song Title</label>
                    <input type="text" name="song_title" id="editTitle" required
                        class="w-full border rounded-lg p-2 mt-1">
                </div>

                <div>
                    <label class="text-sm">Song By</label>
                    <input type="text" name="song_by" id="editBy" class="w-full border rounded-lg p-2 mt-1">
                </div>

                {{-- ✅ ORIGINAL KEY --}}
                <div>
                    <label class="text-sm">Original Key</label>
                    <input type="text" name="original_key" id="editKey"
                        class="w-full border rounded-lg p-2 mt-1">
                </div>

                <div>
                    <label class="text-sm">Reference Link</label>
                    <input type="text" name="song_reference" id="editRef"
                        class="w-full border rounded-lg p-2 mt-1">
                </div>

                <div class="flex justify-center gap-3 pt-2">

                    <!-- ❌ Cancel -->
                    <button type="button" onclick="closeEditModal()"
                        class="p-2 text-gray-500 hover:text-red-500 transition" title="Cancel">
                        <i class="pi pi-times text-lg"></i>
                    </button>

                    <!-- 🔄 Update -->
                    <button type="submit" class="p-2 text-gray-500 hover:text-blue-600 transition" title="Update">
                        <i class="pi pi-pencil text-lg"></i>
                    </button>

                </div>
            </form>

        </div>
    </div>

    {{-- JS --}}
    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.getElementById('addModal').classList.remove('flex');
        }

        function openEditModal(id, title, by, ref, key) {
            const modal = document.getElementById('editModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.getElementById('editTitle').value = title;
            document.getElementById('editBy').value = by;
            document.getElementById('editRef').value = ref;
            document.getElementById('editKey').value = key ?? '';

            document.getElementById('editForm').action = '/songs/' + id;
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        function songSearchComponent() {
            return {
                query: @json(request('search') ?? ''),
                songs: @json($allSongs),
                filtered: [],

                filterSongs() {
                    if (this.query.length < 1) {
                        this.filtered = [];
                        return;
                    }

                    this.filtered = this.songs.filter(song =>
                        song.song_title.toLowerCase().includes(this.query.toLowerCase()) ||
                        (song.song_by && song.song_by.toLowerCase().includes(this.query.toLowerCase()))
                    ).slice(0, 5);
                },

                selectSong(title) {
                    this.query = title;
                    this.filtered = [];
                }
            }
        }
    </script>

</x-app-layout>
