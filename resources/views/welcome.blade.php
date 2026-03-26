<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project LEAD</title>

    <!-- PrimeIcons -->
    <link href="https://unpkg.com/primeicons/primeicons.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased bg-gray-100">

    <div class="min-h-screen flex items-center justify-center p-4">

        <!-- Main Card Wrapper -->
        <div class="w-full max-w-md bg-white shadow-md rounded-2xl p-4 sm:p-8">

            <!-- Logo -->
            <div class="text-center mb-0">
                <img src="{{ asset('images/lead_icon.png') }}" alt="Project LEAD Logo"
                    class="w-20 h-20 mx-auto object-contain">
            </div>

            <!-- Heading -->
            <div class="text-center mb-6">
                <h1 class="text-xl font-semibold text-gray-900 tracking-wide">
                    PROJECT LEAD
                </h1>
                <h3 class="text-sm text-gray-500 mt-0 leading-tight" style="font-family: 'Dancing Script', cursive;">
                    Worship team management, made simple.
                </h3>
            </div>

            <!-- Login Link -->
            <div class="text-center my-2">
                <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700 underline">
                    Already have an account? Login
                </a>
            </div>

            <!-- Divider -->
            <div class="flex items-center my-2">
                <div class="flex-1 border-t border-gray-200"></div>
                <span class="px-3 text-xs text-gray-400 uppercase tracking-wide">or</span>
                <div class="flex-1 border-t border-gray-200"></div>
            </div>

            <!-- Register Text -->
            <div class="text-center mt-2 mb-6">
                <p class="text-sm text-gray-500">
                    Create a new account below
                </p>
            </div>

            <!-- Options -->
            <div class="space-y-4">

                <!-- Admin Card -->
                <a href="{{ route('register') }}" class="block">
                    <div
                        class="w-full p-5 bg-gray-50 border border-gray-200 rounded-xl hover:border-gray-300 hover:bg-white hover:shadow-sm transition">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 min-w-[40px] min-h-[40px] flex items-center justify-center rounded-full bg-white border">
                                <i class="pi pi-shield text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Admin</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Register as a church admin to manage your worship team, songs, and schedules.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>

                <!-- User Card -->
                <a href="{{ route('register.user') }}" class="block">
                    <div
                        class="w-full p-5 bg-gray-50 border border-gray-200 rounded-xl hover:border-gray-300 hover:bg-white hover:shadow-sm transition">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-10 h-10 min-w-[40px] min-h-[40px] flex items-center justify-center rounded-full bg-white border">
                                <i class="pi pi-users text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">User</h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    Find your church to manage your profile and worship assignments.
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="text-center mt-6">
                <h3 class="text-xs font-normal italic text-gray-500 mt-4 leading-tight">
                    "Worship team management app that organize team members, manage songs, and plan schedules—keeping
                    your team aligned for every service."
                </h3>
            </div>

            <div class="text-center mt-6 text-xs text-gray-400">
                © {{ date('Y') }} Project LEAD. All rights reserved.
            </div>

        </div>
    </div>

</body>

</html>
