<x-app-layout>
    <div class="p-3 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <div class="bg-white shadow-sm rounded-lg p-4 sm:p-6">

            <!-- TITLE -->
            <h2 class="text-md font-semibold text-gray-800 mb-4 text-center">
                WHO ARE YOU LOOKING FOR
            </h2>

            <!-- SEARCH -->
            <form method="GET" class="mb-4" x-data="searchComponent()">

                <input type="text" name="search" x-model="query" @input="filterUsers" @keydown.enter="$el.form.submit()"
                    value="{{ $search ?? '' }}" placeholder="Search by name..."
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                    

                <!-- SUGGESTIONS -->
                <div x-show="filtered.length > 0" @click.outside="filtered = []"
                    class="bg-white border mt-1 rounded shadow">
                    <template x-for="user in filtered" :key="user.id">
                        <div @click="selectUser(user.name); $el.closest('form').submit()"
                            class="px-3 py-2 hover:bg-gray-100 cursor-pointer text-sm" x-text="user.name"></div>
                    </template>
                </div>

            </form>

            <!-- USERS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                @forelse($users as $user)
                    <div class="border rounded-xl p-3 flex gap-3 items-start bg-white shadow-sm">

                        <!-- PROFILE IMAGE -->
                        <div x-data="{
                            preview: @js($user->profile_picture ? Storage::url($user->profile_picture) : '')
                        }" class="shrink-0">

                            <template x-if="preview">
                                <img :src="preview" class="h-12 w-12 rounded-full object-cover border">
                            </template>

                            <template x-if="!preview">
                                <div
                                    class="h-12 w-12 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 text-[10px] border">
                                    No Photo
                                </div>
                            </template>

                        </div>

                        <!-- CONTENT -->
                        <div class="flex-1 min-w-0">

                            <!-- NAME -->
                            <p class="font-semibold text-gray-800 text-sm leading-tight truncate">
                                {{ $user->name }}
                            </p>

                            <!-- EMAIL + CONTACT -->
                            <div class="text-xs text-gray-500 mt-1 space-y-[2px]">
                                <p class="truncate">{{ $user->email }}</p>
                                <p>{{ $user->contact_number }}</p>
                            </div>

                            <!-- ROLES -->
                            <div class="flex flex-wrap gap-1 mt-2">

                                @foreach ($user->roles->take(2) as $role)
                                    <span class="text-[10px] bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-full">
                                        {{ $role->role_name }}
                                    </span>
                                @endforeach

                                @if ($user->roles->count() > 2)
                                    <span class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                                        +{{ $user->roles->count() - 2 }}
                                    </span>
                                @endif

                            </div>

                        </div>

                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No users found.</p>
                @endforelse

            </div>

        </div>
    </div>

    <!-- SEARCH SCRIPT -->
    <script>
        function searchComponent() {
            return {
                query: @json($search ?? ''),
                users: @json($users),
                filtered: [],

                filterUsers() {
                    if (this.query.length < 1) {
                        this.filtered = [];
                        return;
                    }

                    this.filtered = this.users.filter(user =>
                        user.name.toLowerCase().includes(this.query.toLowerCase())
                    ).slice(0, 5);
                },

                selectUser(name) {
                    this.query = name;
                    this.filtered = [];
                }
            }
        }
    </script>
</x-app-layout>
