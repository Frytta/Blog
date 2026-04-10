<x-layout>
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Najnowsze Posty</h2>
            <p class="mt-2 text-gray-600">Odkryj najnowsze artykuły z świata programowania</p>
        </div>

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

        <!-- Filters/Search Bar -->
        <form method="GET" action="{{ route('posts.index') }}" class="mb-6 flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Szukaj postów..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <button type="submit"
                class="px-4 py-2 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                Szukaj
            </button>
        </form>

        <!-- Posts Grid -->
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">

            @forelse ($posts as $post)
                <article
                    class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                    @if ($post->photo)
                        <div class="h-48 bg-gray-100">
                            <img src="{{ asset('storage/' . $post->photo) }}" alt="Banner: {{ $post->title }}"
                                class="h-full w-full object-cover">
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <span class="text-6xl">📝</span>
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-3">
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
                        <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-indigo-600 cursor-pointer">
                            <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            {{ $post->lead ?? Str::limit(strip_tags($post->content), 150) }}
                        </p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                            <div class="flex items-center gap-2">
                                <div
                                    class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center text-sm font-semibold">
                                    {{ strtoupper(substr($post->author, 0, 2)) }}
                                </div>
                                <span class="text-sm text-gray-700 font-medium">{{ $post->author }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-medium text-gray-500">👁 {{ number_format($post->views) }}</p>
                                <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                                <form method="POST" action="{{ route('posts.destroy', $post->slug) }}" class="mt-1"
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
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-gray-500 text-lg">Brak postów do wyświetlenia.</p>
                    <a href="/posts/create" class="text-indigo-600 hover:text-indigo-700 font-medium mt-2 inline-block">
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
    </script>


</x-layout>
