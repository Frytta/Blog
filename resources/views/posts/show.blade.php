<x-layout>
    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Post Header -->
        <article class="mb-8 overflow-hidden rounded-lg bg-white shadow-md transition-colors duration-300 dark:bg-gray-900 dark:shadow-gray-950/60">
            <!-- Featured Image -->
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

            @if ($post->photo)
                <div class="h-96 bg-gray-100 dark:bg-gray-800">
                    <img src="{{ asset('storage/' . $post->photo) }}" alt="Banner: {{ $post->title }}"
                        class="h-full w-full object-cover">
                </div>
            @else
                <div class="h-96" style="{{ $fallbackGradientStyle }}"></div>
            @endif

            <!-- Post Content -->
            <div class="p-8">
                <!-- Meta Info -->
                <div class="mb-6 flex items-center gap-4 border-b border-gray-200 pb-6 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-300 text-lg font-semibold text-gray-900 dark:bg-gray-700 dark:text-gray-100">
                            {{ strtoupper(substr($post->author, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $post->author }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Opublikowano: {{ $post->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                    <div class="ml-auto flex gap-2 items-center" data-reaction-group>
                        <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold text-indigo-800 dark:bg-indigo-950/40 dark:text-indigo-300">
                            Wyświetlenia: {{ number_format($post->views) }}
                        </span>
                        <form method="POST" action="{{ route('posts.like', $post) }}" data-reaction-form>
                            @csrf
                            <button type="submit"
                                class="rounded-full border border-orange-200 bg-orange-100 px-3 py-1 text-xs font-semibold text-orange-700 transition-colors hover:bg-orange-200 dark:border-orange-900/60 dark:bg-orange-950/40 dark:text-orange-300 dark:hover:bg-orange-900/50">
                                ▲ <span data-like-count>{{ number_format($post->likes) }}</span>
                            </button>
                        </form>
                        <form method="POST" action="{{ route('posts.dislike', $post) }}" data-reaction-form>
                            @csrf
                            <button type="submit"
                                class="rounded-full border border-blue-200 bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 transition-colors hover:bg-blue-200 dark:border-blue-900/60 dark:bg-blue-950/40 dark:text-blue-300 dark:hover:bg-blue-900/50">
                                ▼ <span data-dislike-count>{{ number_format($post->dislikes) }}</span>
                            </button>
                        </form>

                        <form method="POST" action="{{ route('posts.destroy', $post->slug) }}"
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
                </div>

                <!-- Title -->
                <h1 class="mb-4 text-4xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $post->title }}
                </h1>

                @if ($post->lead)
                    <!-- Lead -->
                    <p class="mb-8 text-xl leading-relaxed text-gray-600 dark:text-gray-300">
                        {{ $post->lead }}
                    </p>
                @endif

                <!-- Content -->
                <div class="prose prose-lg max-w-none">
                    <p class="mb-4 whitespace-pre-line leading-relaxed text-gray-700 dark:text-gray-200">
                        {!! $post->content !!}
                    </p>
                </div>

                <!-- Tags -->
                <div class="mt-8 border-t border-gray-200 pt-6 dark:border-gray-700">
                    <p class="mb-3 text-sm text-gray-600 dark:text-gray-300">Tagi:</p>
                    <div class="flex flex-wrap gap-2">
                        @forelse ($post->tags as $tag)
                            <span
                                class="cursor-pointer rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700 transition-colors hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                #{{ $tag->name }}
                            </span>
                        @empty
                            <span class="text-sm text-gray-500 dark:text-gray-400">Brak tagów.</span>
                        @endforelse
                    </div>
                </div>

                <!-- Social Share -->
                <div class="mt-6 flex items-center gap-4">
                    <span class="text-sm text-gray-600 dark:text-gray-300">Udostępnij:</span>
                    <button class="text-blue-600 transition-colors hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </button>
                    <button class="text-sky-500 transition-colors hover:text-sky-600 dark:text-sky-400 dark:hover:text-sky-300">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </button>
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <section id="comments" class="rounded-lg bg-white p-8 shadow-md transition-colors duration-300 dark:bg-gray-900 dark:shadow-gray-950/60">
            <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-gray-100">
                Komentarze ({{ $commentsCount }})
            </h2>

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

            <!-- Comment Form -->
            <div class="mb-8 border-b border-gray-200 pb-8 dark:border-gray-700">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">Dodaj komentarz</h3>

                @if ($errors->any())
                    <ul class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-700 dark:bg-red-950/40 dark:text-red-300">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form method="POST" action="{{ route('comments.store', $post->slug) }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="author" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Twoje imię *
                        </label>
                        <input type="text" id="author" name="author" required value="{{ old('author') }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder:text-gray-400 dark:focus:ring-indigo-500/50"
                            placeholder="Jan Kowalski">
                    </div>

                    <!-- Comment Content -->
                    <div>
                        <label for="content" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Komentarz *
                        </label>
                        <textarea id="content" name="content" required rows="5"
                            class="w-full resize-none rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder:text-gray-400 dark:focus:ring-indigo-500/50"
                            placeholder="Podziel się swoimi przemyśleniami...">{{ old('content') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center gap-4">
                        <button type="submit"
                            class="rounded-lg bg-indigo-600 px-6 py-2 font-medium text-white transition-colors hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400">
                            Opublikuj komentarz
                        </button>
                        <p class="text-sm text-gray-500 dark:text-gray-400">* Pola wymagane</p>
                    </div>
                </form>
            </div>

            <!-- Comments List -->
            @if ($comments->count() > 0)
                <div class="space-y-6">
                    @foreach ($comments as $comment)
                        <div class="flex gap-5">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-lg">
                                    {{ strtoupper(substr($comment->author, 0, 2)) }}
                                </div>
                            </div>
                            <div class="flex-1 space-y-3">
                                <div class="rounded-lg bg-gray-50 px-5 py-2.5 dark:bg-gray-800" data-comment-card>
                                    <div class="flex items-center justify-between mb-0.5">
                                        <div class="flex items-center gap-2" data-reaction-group>
                                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $comment->author }}</h4>

                                            <form method="POST" action="{{ route('comments.like', $comment) }}" data-reaction-form>
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-300 bg-orange-50/70 px-2.5 py-0.5 text-sm font-bold text-orange-600 shadow-sm transition-colors hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-orange-300 dark:border-orange-900/60 dark:bg-orange-950/30 dark:text-orange-300 dark:hover:bg-orange-900/40 dark:focus:ring-orange-500/40">
                                                    <span aria-hidden="true" class="text-lg leading-none">▲</span>
                                                    <span data-like-count>{{ $comment->likes }}</span>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('comments.dislike', $comment) }}" data-reaction-form>
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-blue-300 bg-blue-50/70 px-2.5 py-0.5 text-sm font-bold text-blue-900 shadow-sm transition-colors hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-blue-900/60 dark:bg-blue-950/30 dark:text-blue-300 dark:hover:bg-blue-900/40 dark:focus:ring-blue-500/40">
                                                    <span aria-hidden="true" class="text-lg leading-none">▼</span>
                                                    <span data-dislike-count>{{ $comment->dislikes }}</span>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="flex flex-col items-end gap-2">
                                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            <div class="flex items-center gap-2">
                                                <button type="button" data-comment-edit-toggle
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50 text-blue-600 transition-colors hover:bg-blue-100 dark:border-blue-900/60 dark:bg-blue-950/40 dark:text-blue-300 dark:hover:bg-blue-900/40"
                                                    title="Edytuj komentarz" aria-label="Edytuj komentarz">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.592c.55 0 1.02.398 1.11.94l.213 1.279a1.125 1.125 0 001.057.93l1.285.058c.544.024.99.44 1.06.98l.334 2.57c.071.54-.25 1.062-.761 1.233l-1.21.404a1.125 1.125 0 00-.733 1.015l-.058 1.285a1.125 1.125 0 00.54 1.03l1.084.665c.46.282.64.865.426 1.364l-1.017 2.372a1.125 1.125 0 01-1.28.64l-1.265-.284a1.125 1.125 0 00-1.154.45l-.74 1.05a1.125 1.125 0 01-1.82 0l-.74-1.05a1.125 1.125 0 00-1.154-.45l-1.265.284a1.125 1.125 0 01-1.28-.64L3.7 17.835a1.125 1.125 0 01.426-1.364l1.084-.665a1.125 1.125 0 00.54-1.03l-.058-1.285a1.125 1.125 0 00-.733-1.015l-1.21-.404a1.125 1.125 0 01-.761-1.233l.334-2.57a1.125 1.125 0 011.06-.98l1.285-.058a1.125 1.125 0 001.057-.93l.213-1.279z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                </button>

                                                <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                                                    onsubmit="return confirm('Czy na pewno chcesz usunąć komentarz?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 transition-colors hover:bg-red-100 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-300 dark:hover:bg-red-900/40"
                                                        title="Usuń komentarz" aria-label="Usuń komentarz">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6 7.5h12m-9.75 0V6A1.5 1.5 0 019.75 4.5h4.5A1.5 1.5 0 0115.75 6v1.5m-7.5 0v10.75c0 .966.784 1.75 1.75 1.75h4.5a1.75 1.75 0 001.75-1.75V7.5" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="m-1 text-[17px] text-gray-700 leading-relaxed whitespace-pre-line dark:text-gray-200" data-comment-content>
                                        {{ $comment->content }}
                                    </p>

                                    <form method="POST" action="{{ route('comments.update', $comment) }}"
                                        class="hidden mt-2 space-y-2" data-comment-edit-form>
                                        @csrf
                                        @method('PATCH')
                                        <textarea name="content" required rows="3"
                                            class="w-full resize-none rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 dark:focus:ring-indigo-500/50">{{ $comment->content }}</textarea>
                                        <div class="flex items-center gap-2">
                                            <button type="submit"
                                                class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white hover:bg-amber-600 transition-colors">
                                                Zapisz zmiany
                                            </button>
                                            <button type="button" data-comment-edit-cancel
                                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                                Anuluj
                                            </button>
                                        </div>
                                    </form>

                                    @if ($comment->replies->isNotEmpty())
                                        <div class="mt-4 space-y-3 border-l-2 border-gray-200 pl-4 dark:border-gray-700">
                                            @foreach ($comment->replies as $reply)
                                                <div class="rounded-lg bg-white px-4 py-2 dark:bg-gray-900" data-comment-card>
                                                    <div class="mb-0.5 flex items-center justify-between">
                                                        <div class="flex items-center gap-2" data-reaction-group>
                                                            <h5 class="text-base font-semibold text-gray-900 dark:text-gray-100">{{ $reply->author }}</h5>

                                                            <form method="POST" action="{{ route('comments.like', $reply) }}" data-reaction-form>
                                                                @csrf
                                                                <button type="submit"
                                                                    class="inline-flex items-center justify-center gap-1 rounded-lg border border-orange-300 bg-orange-50/70 px-2 py-1 text-xs font-semibold text-orange-600 transition-colors hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-orange-300 dark:border-orange-900/60 dark:bg-orange-950/30 dark:text-orange-300 dark:hover:bg-orange-900/40 dark:focus:ring-orange-500/40">
                                                                    <span aria-hidden="true" class="text-base leading-none">▲</span>
                                                                    <span data-like-count>{{ $reply->likes }}</span>
                                                                </button>
                                                            </form>

                                                            <form method="POST" action="{{ route('comments.dislike', $reply) }}" data-reaction-form>
                                                                @csrf
                                                                <button type="submit"
                                                                    class="inline-flex items-center justify-center gap-1 rounded-lg border border-blue-300 bg-blue-50/70 px-2 py-1 text-xs font-semibold text-blue-900 transition-colors hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-300 dark:border-blue-900/60 dark:bg-blue-950/30 dark:text-blue-300 dark:hover:bg-blue-900/40 dark:focus:ring-blue-500/40">
                                                                    <span aria-hidden="true" class="text-base leading-none">▼</span>
                                                                    <span data-dislike-count>{{ $reply->dislikes }}</span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <div class="flex flex-col items-end gap-1.5">
                                                            <span
                                                                class="text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                            <div class="flex items-center gap-2">
                                                                <button type="button" data-comment-edit-toggle
                                                                    class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-blue-200 bg-blue-50 text-blue-600 transition-colors hover:bg-blue-100 dark:border-blue-900/60 dark:bg-blue-950/40 dark:text-blue-300 dark:hover:bg-blue-900/40"
                                                                    title="Edytuj odpowiedź" aria-label="Edytuj odpowiedź">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                        stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.592c.55 0 1.02.398 1.11.94l.213 1.279a1.125 1.125 0 001.057.93l1.285.058c.544.024.99.44 1.06.98l.334 2.57c.071.54-.25 1.062-.761 1.233l-1.21.404a1.125 1.125 0 00-.733 1.015l-.058 1.285a1.125 1.125 0 00.54 1.03l1.084.665c.46.282.64.865.426 1.364l-1.017 2.372a1.125 1.125 0 01-1.28.64l-1.265-.284a1.125 1.125 0 00-1.154.45l-.74 1.05a1.125 1.125 0 01-1.82 0l-.74-1.05a1.125 1.125 0 00-1.154-.45l-1.265.284a1.125 1.125 0 01-1.28-.64L3.7 17.835a1.125 1.125 0 01.426-1.364l1.084-.665a1.125 1.125 0 00.54-1.03l-.058-1.285a1.125 1.125 0 00-.733-1.015l-1.21-.404a1.125 1.125 0 01-.761-1.233l.334-2.57a1.125 1.125 0 011.06-.98l1.285-.058a1.125 1.125 0 001.057-.93l.213-1.279z" />
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    </svg>
                                                                </button>

                                                                <form method="POST" action="{{ route('comments.destroy', $reply) }}"
                                                                    onsubmit="return confirm('Czy na pewno chcesz usunąć odpowiedź?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 transition-colors hover:bg-red-100 dark:border-red-900/60 dark:bg-red-950/40 dark:text-red-300 dark:hover:bg-red-900/40"
                                                                        title="Usuń odpowiedź" aria-label="Usuń odpowiedź">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                                            stroke-width="2" stroke="currentColor" class="h-3.5 w-3.5">
                                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                                d="M6 7.5h12m-9.75 0V6A1.5 1.5 0 019.75 4.5h4.5A1.5 1.5 0 0115.75 6v1.5m-7.5 0v10.75c0 .966.784 1.75 1.75 1.75h4.5a1.75 1.75 0 001.75-1.75V7.5" />
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="m-0 text-[17px] text-gray-700 leading-relaxed whitespace-pre-line dark:text-gray-200" data-comment-content>
                                                        {{ $reply->content }}
                                                    </p>

                                                    <form method="POST" action="{{ route('comments.update', $reply) }}"
                                                        class="hidden mt-2 space-y-2" data-comment-edit-form>
                                                        @csrf
                                                        @method('PATCH')
                                                        <textarea name="content" required rows="2"
                                                            class="w-full resize-none rounded-lg border border-gray-300 px-2.5 py-1.5 text-xs focus:border-transparent focus:ring-2 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:ring-indigo-500/50">{{ $reply->content }}</textarea>
                                                        <div class="flex items-center gap-2">
                                                            <button type="submit"
                                                                class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-amber-600 transition-colors">
                                                                Zapisz
                                                            </button>
                                                            <button type="button" data-comment-edit-cancel
                                                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 transition-colors hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700">
                                                                Anuluj
                                                            </button>
                                                        </div>
                                                    </form>

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div data-reply-container class="mt-4 rounded-lg border border-gray-200 bg-white p-3 dark:border-gray-700 dark:bg-gray-900">
                                        <div class="flex justify-start">
                                            <button type="button" data-reply-toggle
                                                class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 transition-colors hover:bg-indigo-100 hover:text-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 dark:bg-indigo-950/40 dark:text-indigo-300 dark:hover:bg-indigo-900/50 dark:hover:text-indigo-200 dark:focus:ring-indigo-500/50">
                                                Odpowiedz
                                            </button>
                                        </div>

                                        <div data-reply-panel class="mt-3 overflow-hidden transition-all duration-300 ease-out"
                                            style="max-height: 0; opacity: 0;">
                                            <form method="POST" action="{{ route('comments.store', $post->slug) }}"
                                                class="space-y-2">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">

                                                <input type="text" name="author" required
                                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder:text-gray-400 dark:focus:ring-indigo-500/50"
                                                    placeholder="Twoje imię">

                                                <textarea name="content" required rows="3"
                                                    class="w-full resize-none rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:placeholder:text-gray-400 dark:focus:ring-indigo-500/50"
                                                    placeholder="Napisz odpowiedź..."></textarea>

                                                <button type="submit"
                                                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400">
                                                    Dodaj odpowiedź
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="py-8 text-center text-gray-500 dark:text-gray-400">Brak komentarzy. Bądź pierwszą osobą, która skomentuje.</p>
            @endif
        </section>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const shouldScrollToComments = @json((bool) session('scrollToComments'));

            if (shouldScrollToComments) {
                document.getElementById('comments')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                });
            }

            document.querySelectorAll('[data-reply-toggle]').forEach((toggleButton) => {
                const container = toggleButton.closest('[data-reply-container]');
                const panel = container?.querySelector('[data-reply-panel]');

                if (!panel) {
                    return;
                }

                let isOpen = false;

                toggleButton.addEventListener('click', () => {
                    isOpen = !isOpen;

                    if (isOpen) {
                        panel.style.maxHeight = `${panel.scrollHeight}px`;
                        panel.style.opacity = '1';
                    } else {
                        panel.style.maxHeight = '0';
                        panel.style.opacity = '0';
                    }
                });
            });

            document.querySelectorAll('[data-reaction-form]').forEach((form) => {
                form.addEventListener('submit', async (event) => {
                    event.preventDefault();

                    const reactionGroup = form.closest('[data-reaction-group]');

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
                            throw new Error('Nie udało się zapisać reakcji.');
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

            document.querySelectorAll('[data-comment-edit-toggle]').forEach((toggleButton) => {
                const card = toggleButton.closest('[data-comment-card]');
                const content = card?.querySelector('[data-comment-content]');
                const editForm = card?.querySelector('[data-comment-edit-form]');

                if (!content || !editForm) {
                    return;
                }

                toggleButton.addEventListener('click', () => {
                    content.classList.add('hidden');
                    editForm.classList.remove('hidden');
                });

                const cancelButton = editForm.querySelector('[data-comment-edit-cancel]');

                cancelButton?.addEventListener('click', () => {
                    editForm.classList.add('hidden');
                    content.classList.remove('hidden');
                });
            });
        });
    </script>
</x-layout>
