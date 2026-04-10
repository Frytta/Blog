<x-layout>
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Najnowsze Posty</h2>
            <p class="mt-2 text-gray-600 dark:text-gray-300">Odkryj najnowsze artykuły z świata programowania</p>
        </div>

        @if (session('success'))
            <div class="mb-6 rounded-lg bg-green-100 px-4 py-3 text-sm font-medium text-green-800 dark:bg-green-950/40 dark:text-green-300">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 rounded-lg bg-red-100 px-4 py-3 text-sm font-medium text-red-800 dark:bg-red-950/40 dark:text-red-300">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filters/Search Bar -->
        <form method="GET" action="{{ route('posts.index') }}" class="mb-6 flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Szukaj postów..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:placeholder:text-gray-400 dark:focus:ring-indigo-500/50">
            </div>
            <button type="submit"
                class="rounded-lg bg-indigo-600 px-4 py-2 font-medium text-white transition-colors hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400">
                Szukaj
            </button>
        </form>

        <!-- Posts Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

            @forelse ($posts as $post)
                @php
                    $gradientPalettes = [
                        [
                            ['#ef4444', '#f97316', '#facc15'],
                            ['#dc2626', '#fb7185', '#f59e0b'],
                            ['#b91c1c', '#ea580c', '#fde047'],
                        ],
                        [
                            ['#22c55e', '#14b8a6', '#2dd4bf'],
                            ['#16a34a', '#10b981', '#84cc16'],
                            ['#15803d', '#0d9488', '#65a30d'],
                        ],
                        [
                            ['#3b82f6', '#0ea5e9', '#22d3ee'],
                            ['#1d4ed8', '#2563eb', '#06b6d4'],
                            ['#1e40af', '#0284c7', '#67e8f9'],
                        ],
                        [
                            ['#8b5cf6', '#6366f1', '#06b6d4'],
                            ['#7c3aed', '#4f46e5', '#38bdf8'],
                            ['#6d28d9', '#4338ca', '#0ea5e9'],
                        ],
                        [
                            ['#ec4899', '#f43f5e', '#f97316'],
                            ['#db2777', '#e11d48', '#fb7185'],
                            ['#be185d', '#f43f5e', '#fdba74'],
                        ],
                    ];

                    $gradientSeed = abs(crc32($post->slug . '-' . $post->id));
                    $paletteIndex = $gradientSeed % count($gradientPalettes);
                    $palette = $gradientPalettes[$paletteIndex];
                    $variantIndex = intdiv($gradientSeed, max(count($gradientPalettes), 1)) % count($palette);
                    [$colorA, $colorB, $colorC] = $palette[$variantIndex];
                    $angle = [120, 130, 135, 145][$gradientSeed % 4];
                    $fallbackGradientStyle = "background-image: linear-gradient({$angle}deg, {$colorA}, {$colorB}, {$colorC});";
                @endphp

                <article
                    class="overflow-hidden rounded-lg bg-white shadow-md transition-all duration-300 hover:shadow-xl dark:bg-gray-900 dark:shadow-gray-950/50 dark:hover:shadow-2xl dark:hover:shadow-gray-950/70">
                    @if ($post->photo)
                        <div class="h-48 bg-gray-100 dark:bg-gray-800">
                            <img src="{{ asset('storage/' . $post->photo) }}" alt="Banner: {{ $post->title }}"
                                class="h-full w-full object-cover">
                        </div>
                    @else
                        <div class="h-48" style="{{ $fallbackGradientStyle }}"></div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
                            @if ($post->is_published)
                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-800 dark:bg-green-950/40 dark:text-green-300">
                                    Opublikowany
                                </span>
                            @else
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                                    Szkic
                                </span>
                            @endif
                        </div>
                        <h3 class="mb-2 cursor-pointer text-xl font-bold text-gray-900 transition-colors hover:text-indigo-600 dark:text-gray-100 dark:hover:text-indigo-300">
                            <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                        </h3>
                        <p class="mb-4 line-clamp-3 text-sm text-gray-600 dark:text-gray-300">
                            {{ $post->lead ?? Str::limit(strip_tags($post->content), 150) }}
                        </p>
                        @if ($post->tags->isNotEmpty())
                            <div class="mb-4 flex flex-wrap gap-2">
                                @foreach ($post->tags as $tag)
                                    <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                        #{{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                        <div class="flex items-center justify-between border-t border-gray-100 pt-4 dark:border-gray-800">
                            <div class="flex items-center gap-2">
                                <div
                                    class="flex h-8 w-8 items-center justify-center rounded-full bg-gray-300 text-sm font-semibold text-gray-900 dark:bg-gray-700 dark:text-gray-100">
                                    {{ strtoupper(substr($post->author, 0, 2)) }}
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $post->author }}</span>
                            </div>
                            <div class="text-right space-y-1" data-post-reaction-group>
                                <div class="flex items-center justify-end gap-1.5 text-xs font-medium">
                                    <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-indigo-800 dark:bg-indigo-950/40 dark:text-indigo-300">👁 {{ number_format($post->views) }}</span>
                                    <form method="POST" action="{{ route('posts.like', $post) }}" data-post-reaction-form>
                                        @csrf
                                        <button type="submit"
                                            class="rounded-full border border-orange-200 bg-orange-100 px-2 py-0.5 text-orange-700 transition-colors hover:bg-orange-200 dark:border-orange-900/60 dark:bg-orange-950/40 dark:text-orange-300 dark:hover:bg-orange-900/50">
                                            ▲ <span data-like-count>{{ number_format($post->likes) }}</span>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('posts.dislike', $post) }}" data-post-reaction-form>
                                        @csrf
                                        <button type="submit"
                                            class="rounded-full border border-blue-200 bg-blue-100 px-2 py-0.5 text-blue-700 transition-colors hover:bg-blue-200 dark:border-blue-900/60 dark:bg-blue-950/40 dark:text-blue-300 dark:hover:bg-blue-900/50">
                                            ▼ <span data-dislike-count>{{ number_format($post->dislikes) }}</span>
                                        </button>
                                    </form>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                                <form method="POST" action="{{ route('posts.destroy', $post->slug) }}" class="mt-1"
                                    onsubmit="return confirm('Czy na pewno chcesz usunąć ten post?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 transition-colors hover:bg-red-100 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-300 dark:hover:bg-red-900/40"
                                        title="Usuń post" aria-label="Usuń post">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 7.5h12m-9.75 0V6A1.5 1.5 0 019.75 4.5h4.5A1.5 1.5 0 0115.75 6v1.5m-7.5 0v10.75c0 .966.784 1.75 1.75 1.75h4.5a1.75 1.75 0 001.75-1.75V7.5" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-lg text-gray-500 dark:text-gray-400">Brak postów do wyświetlenia.</p>
                    <a href="/posts/create" class="mt-2 inline-block font-medium text-indigo-600 transition-colors hover:text-indigo-700 dark:text-indigo-300 dark:hover:text-indigo-200">
                        Dodaj pierwszy post
                    </a>
                </div>
            @endforelse

        </div>
    </main>

    <script>
        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                window.location.reload();
            }
        });

        document.querySelectorAll('[data-post-reaction-form]').forEach((form) => {
            form.addEventListener('submit', async (event) => {
                event.preventDefault();

                const reactionGroup = form.closest('[data-post-reaction-group]');

                if (!reactionGroup) {
                    return;
                }

                const submitButton = form.querySelector('button[type="submit"]');

                if (submitButton) {
                    submitButton.disabled = true;
                }

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        body: new FormData(form),
                    });

                    if (!response.ok) {
                        throw new Error('Nie udalo sie zapisac reakcji.');
                    }

                    const payload = await response.json();
                    const likeCount = reactionGroup.querySelector('[data-like-count]');
                    const dislikeCount = reactionGroup.querySelector('[data-dislike-count]');

                    if (likeCount) {
                        likeCount.textContent = String(payload.likes ?? 0);
                    }

                    if (dislikeCount) {
                        dislikeCount.textContent = String(payload.dislikes ?? 0);
                    }
                } catch (error) {
                    console.error(error);
                } finally {
                    if (submitButton) {
                        submitButton.disabled = false;
                    }
                }
            });
        });
    </script>


</x-layout>
