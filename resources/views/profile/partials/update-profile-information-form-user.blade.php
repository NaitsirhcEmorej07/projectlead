<section x-data="{ tab: 'profile' }">

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">

        @csrf
        @method('patch')

        <!-- TABS -->
        <div class="flex border-b mb-2 text-sm">

            <button type="button" @click="tab = 'profile'"
                :class="tab === 'profile' ? 'border-b-2 border-indigo-500 text-indigo-600 font-medium' : 'text-gray-500'"
                class="px-3 py-2">
                MY PROFILE
            </button>

            <button type="button" @click="tab = 'song'"
                :class="tab === 'song' ? 'border-b-2 border-indigo-500 text-indigo-600 font-medium' : 'text-gray-500'"
                class="px-3 py-2">
                MY SONG
            </button>

            <button type="button" @click="tab = 'social'"
                :class="tab === 'social' ? 'border-b-2 border-indigo-500 text-indigo-600 font-medium' : 'text-gray-500'"
                class="px-3 py-2">
                MY SOCIALS
            </button>

        </div>

        <!-- ===================== -->
        <!-- 🔹 PROFILE TAB -->
        <!-- ===================== -->
        <div x-show="tab === 'profile'" class="mt-5">

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
            <div class="mb-3">
                <x-input-label class="mb-1" for="roles" :value="__('Role')" />
                <select id="roles" name="roles[]" multiple class="block w-full">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" @if (isset($userRoleIds) && in_array($role->id, $userRoleIds)) selected @endif>
                            {{ $role->role_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- NAME -->
            <div class="mb-3">
                <x-input-label value="Name" />
                <x-text-input name="name" type="text" class="block w-full text-sm mt-1" :value="old('name', $user->name)"
                    required />
            </div>

            <!-- EMAIL -->
            <div class="mb-3">
                <x-input-label value="Email" />
                <x-text-input name="email" type="email" class="block w-full text-sm mt-1" :value="old('email', $user->email)"
                    required />
            </div>

            <!-- CONTACT -->
            <div class="mb-3">
                <x-input-label value="Contact Number" />
                <x-text-input name="contact_number" type="text" class="block w-full text-sm mt-1"
                    :value="old('contact_number', $user->contact_number)" />
            </div>

            <!-- DESCRIPTION -->
            <div class="mb-3">
                <x-input-label value="Share your Story" />
                <textarea name="describe" class="block w-full border-gray-300 rounded-md text-sm mt-1" rows="5">{{ old('describe', $user->describe) }}</textarea>
            </div>

        </div>

        <!-- ===================== -->
        <!-- 🎵 SONG TAB -->
        <!-- ===================== -->
        <div x-data="songSuggest()" x-show="tab === 'song'" class="mt-5">

            <!-- ADD BUTTON -->
            <div class="flex justify-end mb-2">
                <button type="button" @click="showAdd = !showAdd; editIndex = null"
                    class="flex items-center gap-1 px-3 py-1.5 rounded-lg border hover:bg-gray-100 transition text-sm text-gray-700">

                    <i class="pi text-xs" :class="showAdd ? 'pi-chevron-up' : 'pi-chevron-down'"></i>
                    <span x-text="editIndex !== null ? 'Cancel Edit' : 'Add new'"></span>

                </button>
            </div>

            <!-- ADD FORM -->
            <div x-show="showAdd" x-transition class="mb-3 border rounded-lg p-2 bg-gray-50">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-2">

                    <!-- SONG TITLE WITH AUTOCOMPLETE -->
                    <div class="relative">
                        <input type="text" name="song_title" x-model="query" @input="filterSongs"
                            placeholder="Song Title" class="border-gray-300 rounded-md text-sm w-full">

                        <!-- SUGGESTIONS -->
                        <div x-show="results.length > 0"
                            class="absolute z-10 bg-white border w-full mt-1 rounded shadow max-h-40 overflow-y-auto">

                            <template x-for="song in results" :key="song.id">
                                <div @click="selectSong(song)"
                                    class="px-2 py-1 text-sm hover:bg-gray-100 cursor-pointer">

                                    <span x-text="song.song_title"></span>
                                    <span class="text-xs text-gray-500"> - </span>
                                    <span class="text-xs text-gray-400" x-text="song.song_by"></span>

                                </div>
                            </template>

                        </div>
                    </div>

                    <!-- SONG BY -->
                    <input type="text" name="song_by" placeholder="Song By"
                        class="border-gray-300 rounded-md text-sm w-full">

                    <!-- KEY -->
                    <input type="text" name="user_key" placeholder="Key (C, D, G...)"
                        class="border-gray-300 rounded-md text-sm w-full">

                    <!-- REFERENCE -->
                    <input type="text" name="song_reference" placeholder="Reference / Link"
                        class="border-gray-300 rounded-md text-sm w-full">

                </div>

                <div class="flex justify-center">
                    <button type="button" @click="insertSong"
                        :class="editIndex !== null ?
                            'bg-yellow-500 hover:bg-yellow-600' :
                            'bg-indigo-500 hover:bg-indigo-600'"
                        class="text-xs px-3 py-1 text-white rounded">

                        <span x-text="editIndex !== null ? 'Update' : 'Insert'"></span>

                    </button>
                </div>
            </div>

            <!-- SONG CARDS (DYNAMIC) -->
            <div class="grid grid-cols-1 gap-2">

                <template x-for="(song, index) in userSongs" :key="song.id">
                    <div class="border rounded-lg p-2 hover:shadow transition">

                        <div class="flex justify-between items-center">

                            <!-- LEFT -->
                            <div class="min-w-0">
                                <div class="text-sm font-medium text-gray-800 truncate" x-text="song.song_title">
                                </div>

                                <div class="text-xs text-gray-500">
                                    By: <span x-text="song.song_by"></span>
                                </div>

                                <div class="text-xs text-gray-500">
                                    My key: <span x-text="song.user_key"></span>
                                </div>
                            </div>

                            <!-- RIGHT -->
                            <div class="flex items-center gap-2">



                                <!-- EDIT -->
                                <button type="button" @click="editSong(index)" class=" hover:text-blue-700">
                                    <i class="pi pi-pencil text-xs"></i>
                                </button>

                                <!-- DELETE -->
                                <button type="button" @click="deleteSong(song.id, index)"
                                    class="  hover:text-red-700">
                                    <i class="pi pi-trash text-xs"></i>
                                </button>

                            </div>

                        </div>

                    </div>
                </template>

                <!-- EMPTY STATE -->
                <div x-show="userSongs.length === 0" class="text-center text-gray-400 text-sm py-4">
                    No songs yet
                </div>

            </div>

        </div>

        <!-- ===================== -->
        <!-- 🌐 SOCIAL TAB -->
        <!-- ===================== -->
        <div x-show="tab === 'social'" class="mt-5">


            <!-- FACEBOOK -->
            <div class="flex items-center border border-gray-300 rounded-md mb-2 px-2">
                <i class="pi pi-facebook text-blue-600 text-sm mr-2"></i>
                <input type="text" placeholder="Facebook" class="w-full text-sm border-0 focus:ring-0">
            </div>

            <!-- INSTAGRAM -->
            <div class="flex items-center border border-gray-300 rounded-md mb-2 px-2">
                <i class="pi pi-instagram text-pink-500 text-sm mr-2"></i>
                <input type="text" placeholder="Instagram" class="w-full text-sm border-0 focus:ring-0">
            </div>

            <!-- TIKTOK -->
            <div class="flex items-center border border-gray-300 rounded-md mb-2 px-2">
                <i class="pi pi-tiktok text-black text-sm mr-2"></i>
                <input type="text" placeholder="Tiktok" class="w-full text-sm border-0 focus:ring-0">
            </div>

            <!-- YOUTUBE -->
            <div class="flex items-center border border-gray-300 rounded-md mb-2 px-2">
                <i class="pi pi-youtube text-red-600 text-sm mr-2"></i>
                <input type="text" placeholder="YouTube" class="w-full text-sm border-0 focus:ring-0">
            </div>

        </div>

        <!-- ACTION -->
        <div class="mt-5">
            <x-primary-button class="px-4 py-2 text-sm">
                Save
            </x-primary-button>
        </div>

    </form>
</section>
<script>
    const songList = @json($songs);
    const initialSongs = @json($userSongs);

    new TomSelect('#roles', {
        plugins: ['remove_button'],
        placeholder: 'Select roles...',
    });

    function songSuggest() {
        return {
            showAdd: false,
            query: '',
            results: [],
            songs: songList,
            userSongs: initialSongs,
            editIndex: null,
            loading: false,

            // 🔍 AUTOCOMPLETE
            filterSongs() {
                if (!this.query) {
                    this.results = [];
                    return;
                }

                this.results = this.songs
                    .filter(song =>
                        (song.song_title || '')
                        .toLowerCase()
                        .includes(this.query.toLowerCase())
                    )
                    .slice(0, 5);
            },

            // 🎯 SELECT
            selectSong(song) {
                this.query = song.song_title || '';
                this.results = [];

                this.setField('song_by', song.song_by);
                this.setField('user_key', song.original_key);
                this.setField('song_reference', song.song_reference);
            },

            // ✏️ EDIT
            editSong(index) {
                const song = this.userSongs[index];

                this.query = song.song_title;
                this.setField('song_by', song.song_by);
                this.setField('user_key', song.user_key);
                this.setField('song_reference', song.song_reference);

                this.showAdd = true;
                this.editIndex = index;
            },

            // 📦 HELPERS
            getField(name) {
                return this.$root.querySelector(`[name="${name}"]`).value;
            },

            setField(name, value) {
                this.$root.querySelector(`[name="${name}"]`).value = value || '';
            },

            // 🧹 RESET
            resetForm() {
                this.query = '';
                this.results = [];
                this.editIndex = null;

                this.setField('song_by', '');
                this.setField('user_key', '');
                this.setField('song_reference', '');
            },

            // 💾 INSERT / UPDATE
            insertSong() {

                const payload = {
                    song_title: this.query,
                    song_by: this.getField('song_by'),
                    user_key: this.getField('user_key'),
                    song_reference: this.getField('song_reference')
                };

                this.loading = true;

                // 🔥 UPDATE
                if (this.editIndex !== null) {

                    const song = this.userSongs[this.editIndex];

                    fetch(`/profile-song/update/${song.id}`, {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify(payload)
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.userSongs[this.editIndex] = data.data;
                            this.resetForm();
                        })
                        .catch(err => console.error(err))
                        .finally(() => this.loading = false);

                    return;
                }

                // 🔥 CREATE
                fetch("{{ route('profile-song.store') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.userSongs.unshift(data.data);
                        this.resetForm();
                    })
                    .catch(err => console.error(err))
                    .finally(() => this.loading = false);
            },

            // 🗑️ DELETE
            deleteSong(id, index) {

                if (!confirm("Delete this song?")) return;

                this.loading = true;

                fetch(`/profile-song/delete/${id}`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    })
                    .then(res => res.json())
                    .then(() => {
                        this.userSongs.splice(index, 1);
                    })
                    .catch(err => console.error(err))
                    .finally(() => this.loading = false);
            }
        }
    }
</script>
