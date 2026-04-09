<x-app-layout>

    <div class="p-3 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto space-y-3">

            <!-- CREATE POST -->
            <div onclick="openModal()" class="bg-white shadow-sm rounded-lg p-3 border border-gray-300 cursor-pointer">
                <div class="flex items-center space-x-3">
                    <img src="{{ Auth::user()->profile_picture
                        ? Storage::url(Auth::user()->profile_picture)
                        : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}"
                        class="w-10 h-10 rounded-full object-cover">
                    <div class="flex-1">
                        <div class="w-full border rounded-full px-4 py-2 text-sm text-gray-500 hover:bg-gray-50">
                            Share your devotion ...
                        </div>
                    </div>
                </div>
            </div>

            <!-- POSTS -->
            @foreach ($devotions as $devotion)
                <div class="bg-white shadow-sm rounded-lg p-3 border border-gray-300" id="post-{{ $devotion->id }}">

                    <!-- Header -->
                    <div class="flex items-center space-x-3">
                        <img src="{{ $devotion->user->profile_picture
                            ? Storage::url($devotion->user->profile_picture)
                            : 'https://ui-avatars.com/api/?name=' . urlencode($devotion->user->name) }}"
                            class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <p class="font-semibold text-sm">{{ $devotion->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $devotion->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="mt-0 text-xs text-gray-700 whitespace-pre-line leading-tight">
                        {{ $devotion->content }}
                    </div>

                    <div class="flex justify-between mt-4 text-sm text-gray-500 border-t pt-2">

                        @php
                            $myReaction = $devotion->likes->where('user_id', Auth::id())->first()->reaction ?? null;
                        @endphp

                        <div class="relative group">

                            <!-- MAIN BUTTON -->
                            <button id="react-btn-{{ $devotion->id }}"
                                class="flex items-center gap-1 transition hover:text-blue-500 
        {{ $myReaction ? 'text-blue-500' : '' }}">
                                <span id="react-icon-{{ $devotion->id }}">
                                    @if ($myReaction == 'like')
                                        👍
                                    @elseif($myReaction == 'heart')
                                        ❤️
                                    @elseif($myReaction == 'wow')
                                        😮
                                    @elseif($myReaction == 'haha')
                                        😂
                                    @else
                                        👍
                                    @endif
                                </span>

                                <span id="reaction-count-{{ $devotion->id }}">
                                    {{ $devotion->likes->count() }}
                                </span>
                            </button>

                            <!-- REACTION PICKER -->
                            <div
                                class="absolute hidden group-hover:flex space-x-2 bg-white shadow p-2 rounded -top-12 left-0 z-10">

                                <button onclick="react({{ $devotion->id }}, 'like')">👍</button>
                                <button onclick="react({{ $devotion->id }}, 'heart')">❤️</button>
                                <button onclick="react({{ $devotion->id }}, 'wow')">😮</button>
                                <button onclick="react({{ $devotion->id }}, 'haha')">😂</button>

                            </div>
                        </div>

                        <!-- COMMENT -->
                        <button onclick="toggleComments({{ $devotion->id }})"
                            class="flex items-center gap-1 hover:text-blue-500 transition">

                            <i class="pi pi-comment"></i>
                            <span>Comment</span>
                        </button>

                        <!-- DELETE / PLACEHOLDER -->
                        @if ($devotion->user_id === Auth::user()->id)
                            <form action="{{ route('worship.devotions.destroy', $devotion->id) }}" method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')

                                <button type="submit" onclick="return confirm('Delete this devotion?')"
                                    class="flex items-center gap-1 hover:text-red-500 transition">

                                    <i class="pi pi-trash"></i>
                                    <span>Delete</span>
                                </button>
                            </form>
                        @else
                            <!-- 👇 Invisible placeholder -->
                            <div class="flex items-center gap-1 opacity-0 pointer-events-none">
                                <i class="pi pi-trash"></i>
                                <span>Delete</span>
                            </div>
                        @endif

                    </div>

                    <!-- COMMENTS -->
                    <div id="comments-{{ $devotion->id }}" class="mt-3 hidden">

                        <!-- COMMENT FORM -->
                        <form onsubmit="submitComment(event, {{ $devotion->id }})"
                            class="flex items-center gap-2 mt-2">

                            <!-- INPUT -->
                            <input type="text" name="comment" placeholder="Write a comment..."
                                class="flex-1 bg-gray-100 rounded-full px-4 py-2 text-sm 
               focus:outline-none focus:ring-2 focus:ring-blue-400">

                            <!-- BUTTON -->
                            <button type="submit"
                                class="text-blue-500 text-sm font-semibold hover:text-blue-600 transition">
                                Post
                            </button>

                        </form>

                        <!-- COMMENT LIST -->
                        <div id="comment-list-{{ $devotion->id }}" class="space-y-3 mt-5">

                            @foreach ($devotion->comments->where('parent_id', null) as $comment)
                                <div class="flex items-start gap-2">

                                    <img src="{{ $comment->user && $comment->user->profile_picture
                                        ? Storage::url($comment->user->profile_picture)
                                        : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name ?? 'User') }}"
                                        onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($comment->user->name ?? 'User') }}';"
                                        class="w-8 h-8 rounded-full object-cover">

                                    <!-- CONTENT -->
                                    <div class="flex flex-col">

                                        <!-- BUBBLE -->
                                        <div class="bg-gray-100 px-3 py-2 rounded-2xl max-w-md">

                                            <div class="text-xs font-semibold text-gray-800">
                                                {{ $comment->user->name }}
                                            </div>

                                            <div class="text-xs text-gray-700">
                                                {{ $comment->comment }}
                                            </div>

                                        </div>

                                        <!-- TIME ONLY -->
                                        <div class="text-[11px] text-gray-400 mt-1 ml-2">
                                            {{ $comment->created_at->diffForHumans() }}
                                        </div>

                                    </div>

                                </div>
                            @endforeach

                        </div>
                    </div>

                </div>
            @endforeach

            @if ($devotions->hasPages())
                <div class="flex justify-center items-center gap-1 mt-4 text-sm">

                    {{-- PREVIOUS --}}
                    @if ($devotions->onFirstPage())
                        <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded flex items-center gap-1">
                            <i class="pi pi-chevron-left text-xs"></i>
                        </span>
                    @else
                        <a href="{{ $devotions->previousPageUrl() }}"
                            class="px-3 py-1 bg-white border rounded hover:bg-gray-100 transition flex items-center gap-1">
                            <i class="pi pi-chevron-left text-xs"></i>
                        </a>
                    @endif

                    {{-- CURRENT PAGE --}}
                    <span class="px-3 py-1 bg-blue-500 text-white rounded">
                        {{ $devotions->currentPage() }}
                    </span>

                    {{-- NEXT --}}
                    @if ($devotions->hasMorePages())
                        <a href="{{ $devotions->nextPageUrl() }}"
                            class="px-3 py-1 bg-white border rounded hover:bg-gray-100 transition flex items-center gap-1">
                            <i class="pi pi-chevron-right text-xs"></i>
                        </a>
                    @else
                        <span class="px-3 py-1 bg-gray-100 text-gray-400 rounded flex items-center gap-1">
                            <i class="pi pi-chevron-right text-xs"></i>
                        </span>
                    @endif

                </div>
            @endif

        </div>
    </div>

    <!-- MODAL -->
    <div id="postModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-3">
        <div class="bg-white rounded-lg w-full max-w-md p-4">

            <h2 class="font-semibold mb-2">Create Devotion</h2>

            <form id="postForm">
                @csrf
                <textarea name="content" class="w-full border rounded p-2 text-sm" rows="17"></textarea>
                <div class="flex justify-end mt-2 space-x-2">

                    <button type="button" onclick="closeModal()"
                        class="flex items-center gap-1 px-3 py-1 text-sm text-gray-600 hover:text-gray-800">

                        <i class="pi pi-times"></i>
                        <span>Cancel</span>
                    </button>

                    <button
                        class="flex items-center gap-1 bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">

                        <i class="pi pi-send"></i>
                        <span>Post</span>
                    </button>

                </div>
            </form>

        </div>
    </div>

    <script>
        // MODAL
        function openModal() {
            document.getElementById('postModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('postModal').classList.add('hidden');
        }

        // POST DEVOTION (AJAX)
        document.getElementById('postForm').addEventListener('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("{{ route('worship.devotions.store') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(res => location.reload()); // simple refresh (later realtime insert)
        });

        // TOGGLE COMMENTS
        function toggleComments(id) {
            document.getElementById('comments-' + id).classList.toggle('hidden');
        }

        // COMMENT SUBMIT
        function submitComment(e, id) {
            e.preventDefault();

            let form = e.target;
            let input = form.querySelector('input[name="comment"]');

            fetch("{{ route('worship.devotions.comment') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        worship_devotion_id: id,
                        comment: input.value
                    })
                })
                .then(res => location.reload());
        }

        function react(id, reaction) {
            fetch(`/worship-devotions/${id}/react`, {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        reaction: reaction
                    })
                })
                .then(res => res.json())
                .then(data => {

                    // 🔥 UPDATE TOTAL COUNT
                    let total = 0;
                    Object.values(data.counts).forEach(v => total += v);
                    document.getElementById('reaction-count-' + id).innerText = total;

                    const btn = document.getElementById('react-btn-' + id);
                    const icon = document.getElementById('react-icon-' + id);

                    // 🔥 ICON MAP
                    const icons = {
                        like: '👍',
                        heart: '❤️',
                        wow: '😮',
                        haha: '😂'
                    };

                    if (data.status === 'removed') {
                        // ❌ UNLIKE
                        btn.classList.remove('text-blue-500');
                        icon.innerText = '👍';
                    } else {
                        // ✅ ADD / UPDATE
                        btn.classList.add('text-blue-500');
                        icon.innerText = icons[reaction];
                    }

                    // 🔥 SMALL ANIMATION
                    btn.classList.add('scale-110');
                    setTimeout(() => btn.classList.remove('scale-110'), 150);

                });
        }
    </script>

</x-app-layout>
