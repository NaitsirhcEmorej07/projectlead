<x-app-layout>
    <div class="p-3 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">

                    <h2 class="text-md font-semibold mb-4">Pending Approvals</h2>

                    <div class="overflow-x-auto">
                        <div class="space-y-3">
                            @forelse($users as $user)
                                <div class="border rounded-lg p-4 flex items-center justify-between shadow-sm">

                                    <!-- LEFT INFO -->
                                    <div>
                                        <p class="font-semibold text-gray-800">
                                            {{ $user->name }}
                                        </p>

                                        <p class="text-sm text-gray-500">
                                            {{ $user->email }}
                                        </p>

                                        <span
                                            class="inline-block mt-1 px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">
                                            Pending
                                        </span>
                                    </div>

                                    <!-- ACTIONS -->
                                    <div class="flex gap-3 items-center">

                                        <!-- APPROVE -->
                                        <form method="POST" action="{{ route('approval.approve', $user->id) }}"
                                            onsubmit="return confirm('Approve this user?')">
                                            @csrf
                                            <button class="text-black-500 hover:text-green-700 transition text-lg">
                                                <i class="pi pi-check"></i>
                                            </button>
                                        </form>

                                        <!-- DECLINE -->
                                        <form method="POST" action="{{ route('approval.decline', $user->id) }}"
                                            onsubmit="return confirm('Decline this user?')">
                                            @csrf
                                            <button class="text-black-500 hover:text-red-700 transition text-lg">
                                                <i class="pi pi-times"></i>
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            @empty
                                <div class="text-center text-gray-500 py-4">
                                    No pending approvals
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
