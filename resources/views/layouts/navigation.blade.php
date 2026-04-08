<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center space-x-2">
                    <a href="{{ route('worship-team') }}" class="flex items-center space-x-2">

                        @php
                            $church =
                                Auth::user()->churches()->where('church_id', session('church_id'))->first() ??
                                Auth::user()->churches()->first();
                        @endphp

                        @if ($church && $church->logo)
                            <!-- Actual Church Logo -->
                            <img src="{{ Storage::url($church->logo) }}" alt="Church Logo"
                                class="h-9 w-9 object-cover rounded-full">
                        @else
                            <!-- Default App Logo -->
                            <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                        @endif

                        <!-- Church Name -->
                        <span class="text-sm font-semibold text-gray-700 break-words line-clamp-2">
                            {{ $church->name ?? 'No Church' }}
                        </span>

                    </a>
                </div>

                <!-- Navigation Links -->
                {{-- <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('events')" :active="request()->routeIs('events')">
                        {{ __('Events') }}
                    </x-nav-link>
                </div> --}}

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('worship-team')" :active="request()->routeIs('worship-team')">
                        {{ __('Worship Team') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('worship-schedule')" :active="request()->routeIs('worship-schedule')">
                        {{ __('Worship Schedule') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->email }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile Settings') }}
                        </x-dropdown-link>

                        @auth
                            @if (auth()->user()->churches->count() > 1)
                                <x-dropdown-link :href="route('select-church')">
                                    {{ __('Church Selection') }}
                                </x-dropdown-link>
                            @endif
                        @endauth


                        @churchAdmin
                            <x-dropdown-link :href="route('approval')">
                                {{ __('Approval Settings') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('songs.index')">
                                {{ __('Song Settings') }}
                            </x-dropdown-link>
                        @endchurchAdmin



                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" class="logout-btn">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Floating Responsive Menu -->
    <div x-show="open" @click.away="open = false" x-transition
        class="absolute right-4 top-16 w-56 bg-white rounded-lg shadow-lg border z-50 sm:hidden">
        <div class="py-2">

            <!-- User Info -->
            <div class="px-4 py-2">
                <div class="text-gray-800 font-medium text-sm">{{ Auth::user()->name }}</div>
                <div class="text-gray-500 text-xs">{{ Auth::user()->email }}</div>
            </div>

            <!-- Divider -->
            <div class="border-t my-2"></div>

            <!-- Menu -->
            {{-- <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Dashboard
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('events')" :active="request()->routeIs('events')">
                Events
            </x-responsive-nav-link> --}}

            <x-responsive-nav-link :href="route('worship-team')" :active="request()->routeIs('worship-team')">
                Worship Teams
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('worship-schedule')" :active="request()->routeIs('worship-schedule')">
                Worship Schedule
            </x-responsive-nav-link>

            <!-- Divider -->
            <div class="border-t my-2"></div>

            <!-- Profile -->
            <x-responsive-nav-link :href="route('profile.edit')">
                Profile Settings
            </x-responsive-nav-link>

            @auth
                @if (auth()->user()->churches()->count() > 1)
                    <x-responsive-nav-link :href="route('select-church')">
                        Church Selection
                    </x-responsive-nav-link>
                @endif
            @endauth


            @churchAdmin
                <x-responsive-nav-link :href="route('approval')">
                    Approval Settings
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('songs.index')">
                    Song Settings
                </x-responsive-nav-link>
            @endchurchAdmin


            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" class="logout-btn">
                    Log Out
                </x-responsive-nav-link>
            </form>

        </div>
    </div>
</nav>


<script>
    document.addEventListener('DOMContentLoaded', () => {

        let isLoggingOut = false;

        document.querySelectorAll('.logout-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                if (isLoggingOut) return;

                isLoggingOut = true;

                this.innerText = 'Logging out...';

                this.closest('form').submit();
            });
        });

    });
</script>
