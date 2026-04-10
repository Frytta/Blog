<x-layout>
    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Post Header -->
        <article class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <!-- Featured Image -->
            @if ($post->photo)
                <div class="h-96 bg-gray-100">
                    <img src="{{ asset('storage/' . $post->photo) }}" alt="Banner: {{ $post->title }}"
                        class="h-full w-full object-cover">
                </div>
            @else
                <div class="h-96 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                    <span class="text-9xl">📝</span>
                </div>
            @endif

            <!-- Post Content -->
            <div class="p-8">
                <!-- Meta Info -->
                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center text-lg font-semibold">
                            {{ strtoupper(substr($post->author, 0, 2)) }}
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $post->author }}</p>
                            <p class="text-sm text-gray-500">Opublikowano: {{ $post->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                    <div class="ml-auto flex gap-2">
                        <span class="px-3 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">
                            Wyświetlenia: {{ number_format($post->views) }}
                        </span>

                        <form method="POST" action="{{ route('posts.destroy', $post->slug) }}"
                            onsubmit="return confirm('Czy na pewno chcesz usunąć ten post?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 transition-colors hover:bg-red-100"
                                title="Usuń post" aria-label="Usuń post">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 7.5h12m-9.75 0V6A1.5 1.5 0 019.75 4.5h4.5A1.5 1.5 0 0115.75 6v1.5m-7.5 0v10.75c0 .966.784 1.75 1.75 1.75h4.5a1.75 1.75 0 001.75-1.75V7.5" />
                                </svg>
                            </button>
                        </form>

                        @if ($post->is_published)
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                Opublikowany
                            </span>
                        @else
                            <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                Szkic
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Title -->
                <h1 class="text-4xl font-bold text-gray-900 mb-4">
                    {{ $post->title }}
                </h1>

                @if ($post->lead)
                    <!-- Lead -->
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        {{ $post->lead }}
                    </p>
                @endif

                <!-- Content -->
                <div class="prose prose-lg max-w-none">
                    <p class="text-gray-700 mb-4 leading-relaxed whitespace-pre-line">
                        {!! $post->content !!}
                    </p>
                </div>

                <!-- Tags -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-3">Tagi:</p>
                    <div class="flex flex-wrap gap-2">
                        <span
                            class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-gray-200 cursor-pointer">
                            #laravel
                        </span>
                        <span
                            class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-gray-200 cursor-pointer">
                            #php
                        </span>
                        <span
                            class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-gray-200 cursor-pointer">
                            #docker
                        </span>
                        <span
                            class="px-3 py-1 bg-gray-100 text-gray-700 text-sm rounded-full hover:bg-gray-200 cursor-pointer">
                            #tutorial
                        </span>
                    </div>
                </div>

                <!-- Social Share -->
                <div class="mt-6 flex items-center gap-4">
                    <span class="text-sm text-gray-600">Udostępnij:</span>
                    <button class="text-blue-600 hover:text-blue-700">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </button>
                    <button class="text-sky-500 hover:text-sky-600">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                        </svg>
                    </button>
                </div>
            </div>
        </article>

        <!-- Comments Section -->
        <section id="comments" class="bg-white rounded-lg shadow-md p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">
                Komentarze ({{ $commentsCount }})
            </h2>

            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-100 px-4 py-3 text-sm font-medium text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-100 px-4 py-3 text-sm font-medium text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Comment Form -->
            <div class="mb-8 pb-8 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Dodaj komentarz</h3>

                @if ($errors->any())
                    <ul class="mb-4 rounded-lg bg-red-100 px-4 py-3 text-sm text-red-700">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <form method="POST" action="{{ route('comments.store', $post->slug) }}" class="space-y-4">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700 mb-2">
                            Twoje imię *
                        </label>
                        <input type="text" id="author" name="author" required value="{{ old('author') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Jan Kowalski">
                    </div>

                    <!-- Comment Content -->
                    <div>
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            Komentarz *
                        </label>
                        <textarea id="content" name="content" required rows="5"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                            placeholder="Podziel się swoimi przemyśleniami...">{{ old('content') }}</textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex items-center gap-4">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-medium hover:bg-indigo-700 transition-colors">
                            Opublikuj komentarz
                        </button>
                        <p class="text-sm text-gray-500">* Pola wymagane</p>
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
                                <div class="bg-gray-50 rounded-lg px-5 py-2.5" data-comment-card>
                                    <div class="flex items-center justify-between mb-0.5">
                                        <div class="flex items-center gap-2" data-reaction-group>
                                            <h4 class="text-lg font-semibold text-gray-900">{{ $comment->author }}</h4>

                                            <form method="POST" action="{{ route('comments.like', $comment) }}" data-reaction-form>
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-orange-300 bg-orange-50/70 px-2.5 py-0.5 text-sm font-bold text-orange-600 shadow-sm transition-colors hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                    <span aria-hidden="true" class="text-lg leading-none">▲</span>
                                                    <span data-like-count>{{ $comment->likes }}</span>
                                                </button>
                                            </form>

                                            <form method="POST" action="{{ route('comments.dislike', $comment) }}" data-reaction-form>
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-blue-300 bg-blue-50/70 px-2.5 py-0.5 text-sm font-bold text-blue-900 shadow-sm transition-colors hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                                    <span aria-hidden="true" class="text-lg leading-none">▼</span>
                                                    <span data-dislike-count>{{ $comment->dislikes }}</span>
                                                </button>
                                            </form>
                                        </div>
                                        <div class="flex flex-col items-end gap-2">
                                            <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                            <div class="flex items-center gap-2">
                                                <button type="button" data-comment-edit-toggle
                                                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-blue-200 bg-blue-50 text-blue-600 transition-colors hover:bg-blue-100"
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
                                                        class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 transition-colors hover:bg-red-100"
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
                                    <p class="m-1 text-[17px] text-gray-700 leading-relaxed whitespace-pre-line" data-comment-content>
                                        {{ $comment->content }}
                                    </p>

                                    <form method="POST" action="{{ route('comments.update', $comment) }}"
                                        class="hidden mt-2 space-y-2" data-comment-edit-form>
                                        @csrf
                                        @method('PATCH')
                                        <textarea name="content" required rows="3"
                                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-indigo-500 resize-none">{{ $comment->content }}</textarea>
                                        <div class="flex items-center gap-2">
                                            <button type="submit"
                                                class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-medium text-white hover:bg-amber-600 transition-colors">
                                                Zapisz zmiany
                                            </button>
                                            <button type="button" data-comment-edit-cancel
                                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                                                Anuluj
                                            </button>
                                        </div>
                                    </form>

                                    @if ($comment->replies->isNotEmpty())
                                        <div class="mt-4 space-y-3 border-l-2 border-gray-200 pl-4">
                                            @foreach ($comment->replies as $reply)
                                                <div class="rounded-lg bg-white px-4 py-2" data-comment-card>
                                                    <div class="mb-0.5 flex items-center justify-between">
                                                        <div class="flex items-center gap-2" data-reaction-group>
                                                            <h5 class="text-base font-semibold text-gray-900">{{ $reply->author }}</h5>

                                                            <form method="POST" action="{{ route('comments.like', $reply) }}" data-reaction-form>
                                                                @csrf
                                                                <button type="submit"
                                                                    class="inline-flex items-center justify-center gap-1 rounded-lg border border-orange-300 bg-orange-50/70 px-2 py-1 text-xs font-semibold text-orange-600 transition-colors hover:bg-orange-100 focus:outline-none focus:ring-2 focus:ring-orange-300">
                                                                    <span aria-hidden="true" class="text-base leading-none">▲</span>
                                                                    <span data-like-count>{{ $reply->likes }}</span>
                                                                </button>
                                                            </form>

                                                            <form method="POST" action="{{ route('comments.dislike', $reply) }}" data-reaction-form>
                                                                @csrf
                                                                <button type="submit"
                                                                    class="inline-flex items-center justify-center gap-1 rounded-lg border border-blue-300 bg-blue-50/70 px-2 py-1 text-xs font-semibold text-blue-900 transition-colors hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-300">
                                                                    <span aria-hidden="true" class="text-base leading-none">▼</span>
                                                                    <span data-dislike-count>{{ $reply->dislikes }}</span>
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <div class="flex flex-col items-end gap-1.5">
                                                            <span
                                                                class="text-xs text-gray-500">{{ $reply->created_at->diffForHumans() }}</span>
                                                            <div class="flex items-center gap-2">
                                                                <button type="button" data-comment-edit-toggle
                                                                    class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-blue-200 bg-blue-50 text-blue-600 transition-colors hover:bg-blue-100"
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
                                                                        class="inline-flex h-7 w-7 items-center justify-center rounded-lg border border-red-200 bg-red-50 text-red-600 transition-colors hover:bg-red-100"
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
                                                    <p class="m-0 text-[17px] text-gray-700 leading-relaxed whitespace-pre-line" data-comment-content>
                                                        {{ $reply->content }}
                                                    </p>

                                                    <form method="POST" action="{{ route('comments.update', $reply) }}"
                                                        class="hidden mt-2 space-y-2" data-comment-edit-form>
                                                        @csrf
                                                        @method('PATCH')
                                                        <textarea name="content" required rows="2"
                                                            class="w-full rounded-lg border border-gray-300 px-2.5 py-1.5 text-xs focus:border-transparent focus:ring-2 focus:ring-indigo-500 resize-none">{{ $reply->content }}</textarea>
                                                        <div class="flex items-center gap-2">
                                                            <button type="submit"
                                                                class="rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-amber-600 transition-colors">
                                                                Zapisz
                                                            </button>
                                                            <button type="button" data-comment-edit-cancel
                                                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-100 transition-colors">
                                                                Anuluj
                                                            </button>
                                                        </div>
                                                    </form>

                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div data-reply-container class="mt-4 rounded-lg border border-gray-200 bg-white p-3">
                                        <div class="flex justify-start">
                                            <button type="button" data-reply-toggle
                                                class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 transition-colors hover:bg-indigo-100 hover:text-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-300">
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
                                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-indigo-500"
                                                    placeholder="Twoje imię">

                                                <textarea name="content" required rows="3"
                                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-indigo-500 resize-none"
                                                    placeholder="Napisz odpowiedź..."></textarea>

                                                <button type="submit"
                                                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 transition-colors">
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
                <p class="text-center text-gray-500 py-8">Brak komentarzy. Bądź pierwszą osobą, która skomentuje.</p>
            @endif
        </section>

        <!-- Related Posts -->
        <section class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Powiązane artykuły</h2>
            <div class="grid gap-6 md:grid-cols-3">

                <!-- Related Post 1 -->
                <a href="#" class="group">
                    <article
                        class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                        <div
                            class="h-32 bg-gradient-to-br from-green-500 to-teal-600 flex items-center justify-center">
                            <span class="text-5xl">🤖</span>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 line-clamp-2 mb-2">
                                GitHub Copilot Agent Mode w praktyce
                            </h3>
                            <p class="text-sm text-gray-500">8 min czytania</p>
                        </div>
                    </article>
                </a>

                <!-- Related Post 2 -->
                <a href="#" class="group">
                    <article
                        class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                        <div class="h-32 bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center">
                            <span class="text-5xl">⚛️</span>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 line-clamp-2 mb-2">
                                Inertia.js - most między Laravel a React
                            </h3>
                            <p class="text-sm text-gray-500">12 min czytania</p>
                        </div>
                    </article>
                </a>

                <!-- Related Post 3 -->
                <a href="#" class="group">
                    <article
                        class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                        <div
                            class="h-32 bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center">
                            <span class="text-5xl">🎨</span>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 line-clamp-2 mb-2">
                                Laravel Filament - admin panel w 15 minut
                            </h3>
                            <p class="text-sm text-gray-500">6 min czytania</p>
                        </div>
                    </article>
                </a>

            </div>
        </section>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
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
