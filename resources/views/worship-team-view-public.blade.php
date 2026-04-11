<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Worship Profile</title>

    <!-- 🔥 IMPORTANT -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Tailwind -->
    @vite('resources/css/app.css')

    <!-- PrimeIcons -->
    <link href="https://unpkg.com/primeicons/primeicons.css" rel="stylesheet">
</head>

<body class="bg-gray-50">

    <div class="p-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white shadow-sm rounded-lg p-4 sm:p-6 border border-indigo-300">

            <!-- ===================== -->
            <!-- 👤 PROFILE -->
            <!-- ===================== -->
            <div class="flex flex-col items-center text-center mb-6">

                <!-- IMAGE -->
                @if ($user->profile_picture)
                    <img src="{{ Storage::url($user->profile_picture) }}"
                        class="w-20 h-20 rounded-full object-cover border mb-2">
                @else
                    <div
                        class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 text-xs mb-2">
                        No Photo
                    </div>
                @endif

                <!-- NAME -->
                <div class="text-lg font-semibold text-gray-800">
                    {{ $user->name }}
                </div>

                <!-- EMAIL -->
                <div class="text-xs text-gray-500">
                    {{ $user->email }}
                </div>

                <!-- ROLES -->
                <div class="flex flex-wrap justify-center gap-2 mt-2">
                    @foreach ($user->roles as $role)
                        <span class="text-xs px-3 rounded-full bg-indigo-50 text-indigo-600 border border-indigo-100">
                            {{ $role->role_name }}
                        </span>
                    @endforeach
                </div>

                <!-- DESCRIPTION -->
                @if ($user->describe)
                    <div class="text-sm text-gray-500 mt-5 max-w-md leading-tight">
                        {!! nl2br(e($user->describe)) !!}
                    </div>
                @endif

            </div>

            <!-- ===================== -->
            <!-- 🎵 SONGS -->
            <!-- ===================== -->
            @php
                $songs = $user->songs->sortBy('song_title')->values();
                $perPage = 12;
                $page = request()->get('page', 1);

                $total = $songs->count();
                $chunks = $songs->chunk($perPage);
                $currentSongs = $chunks->get($page - 1) ?? collect();
            @endphp

            <div class="mb-6">

                <!-- HEADER -->
                <div class="flex items-center my-4">
                    <div class="flex-1 h-px bg-gray-300"></div>

                    <div class="px-3 text-sm font-semibold text-gray-700 tracking-wide">
                        Worship Songs I Know
                    </div>

                    <div class="flex-1 h-px bg-gray-300"></div>
                </div>

                <!-- SONG GRID -->
                <div class="grid grid-cols-2 gap-2">

                    @forelse($currentSongs as $song)
                        @if ($song->song_reference)
                            <a href="{{ $song->song_reference }}" target="_blank"
                                class="block border border-gray-200 rounded-xl p-2.5 bg-white 
                hover:shadow-md hover:-translate-y-[1px] transition-all duration-200
                hover:scale-[1.02] active:scale-[0.97] cursor-pointer">
                            @else
                                <div
                                    class="border border-gray-200 rounded-xl p-2.5 bg-white 
                hover:shadow-md hover:-translate-y-[1px] transition-all duration-200
                hover:scale-[1.02] cursor-pointer">
                        @endif

                        <div class="text-xs font-semibold text-gray-800 truncate leading-tight">
                            {{ $song->song_title }}
                        </div>

                        <div class="text-xs text-gray-500">
                            {{ $song->song_by }}
                        </div>

                        <div class="text-xs text-gray-600 flex items-center gap-1">
                            <span class="text-gray-400">My Key:</span>
                            <span class="px-2 py-0.2 rounded-full font-semibold bg-blue-100 text-blue-700">
                                {{ $song->user_key }}
                            </span>
                        </div>

                        @if ($song->song_reference)
                            </a>
                        @else
                </div>
                @endif

            @empty
                <div class="col-span-2 text-center text-xs text-gray-400 py-4">
                    No songs yet 🎵
                </div>
                @endforelse

            </div>

            <!-- PAGINATION -->
            @if ($chunks->count() > 1)
                <div class="flex items-center justify-between mt-4 text-xs">

                    <div class="text-gray-500">
                        Page {{ $page }} of {{ $chunks->count() }}
                    </div>

                    <div class="flex items-center gap-2">

                        @if ($page > 1)
                            <a href="?page={{ $page - 1 }}"
                                class="px-3 py-1 border rounded hover:bg-gray-100 transition">
                                ← Prev
                            </a>
                        @endif

                        @if ($page < $chunks->count())
                            <a href="?page={{ $page + 1 }}"
                                class="px-3 py-1 border rounded hover:bg-gray-100 transition">
                                Next →
                            </a>
                        @endif

                    </div>

                </div>
            @endif

        </div>

        <!-- ===================== -->
        <!-- 🌐 SOCIALS -->
        <!-- ===================== -->
        <div class="text-center">

            @php
                $socials = $user->socialLinks->keyBy('platform');
            @endphp

            <!-- HEADER -->
            <div class="flex items-center my-4">
                <div class="flex-1 h-px bg-gray-300"></div>

                <div class="px-3 text-sm font-semibold text-gray-700 tracking-wide">
                    Connect With My Socials
                </div>

                <div class="flex-1 h-px bg-gray-300"></div>
            </div>

            <div class="flex justify-center gap-2 text-md text-gray-500">

                @foreach ($user->socialLinks as $social)
                    @php
                        $platform = strtolower($social->social_platform);
                    @endphp

                    @if ($platform === 'facebook')
                        <a href="{{ $social->social_link }}" target="_blank">
                            <i class="pi pi-facebook hover:text-gray-800 hover:scale-110 transition"></i>
                        </a>
                    @elseif($platform === 'instagram')
                        <a href="{{ $social->social_link }}" target="_blank">
                            <i class="pi pi-instagram hover:text-gray-800 hover:scale-110 transition"></i>
                        </a>
                    @elseif($platform === 'tiktok')
                        <a href="{{ $social->social_link }}" target="_blank">
                            <i class="pi pi-tiktok hover:text-gray-800 hover:scale-110 transition"></i>
                        </a>
                    @elseif($platform === 'youtube')
                        <a href="{{ $social->social_link }}" target="_blank">
                            <i class="pi pi-youtube hover:text-gray-800 hover:scale-110 transition"></i>
                        </a>
                    @endif
                @endforeach

                @if ($user->contact_number)
                    <a href="https://wa.me/{{ $user->contact_number }}" target="_blank">
                        <i class="pi pi-comment hover:text-gray-800 hover:scale-110 transition cursor-pointer"></i>
                    </a>
                @endif

            </div>

        </div>

    </div>
    <div class="text-center mt-4 text-xs text-gray-400">
        Powered by
        <a href="https://project-lead.laravel.cloud/" target="_blank"
            class="font-semibold text-indigo-500 hover:underline">
            Project LEAD
        </a>
    </div>
    </div>

</body>

</html>
