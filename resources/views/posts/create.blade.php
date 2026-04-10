<x-layout>
    <main class="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-2xl border border-gray-200 bg-white shadow-xl">
            <div class="border-b border-gray-100 px-6 py-5 sm:px-8">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Nowy post</h1>
                <p class="mt-1 text-sm text-gray-600">Uzupełnij pola i opcjonalnie dodaj zdjęcie bannera.</p>
            </div>

            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-6 px-6 py-6 sm:px-8">
                @csrf

                @if ($errors->any())
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                        <p class="mb-1 font-semibold">Formularz zawiera błędy:</p>
                        <ul class="list-disc space-y-0.5 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="title" class="mb-1.5 block text-sm font-semibold text-gray-800">Tytuł</label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" />
                        @error('title')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="slug" class="mb-1.5 block text-sm font-semibold text-gray-800">Przyjazny adres</label>
                        <input id="slug" type="text" name="slug" value="{{ old('slug') }}"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" />
                        @error('slug')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="author" class="mb-1.5 block text-sm font-semibold text-gray-800">Autor</label>
                        <input id="author" type="text" name="author" value="{{ old('author') }}"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200" />
                        @error('author')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="lead" class="mb-1.5 block text-sm font-semibold text-gray-800">Zajawka</label>
                        <textarea id="lead" name="lead" rows="3"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">{{ old('lead') }}</textarea>
                        @error('lead')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="content" class="mb-1.5 block text-sm font-semibold text-gray-800">Treść</label>
                        <textarea id="content" name="content" rows="8"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="photo" class="mb-1.5 block text-sm font-semibold text-gray-800">Zdjęcie bannera</label>
                        <input id="photo" type="file" name="photo" accept="image/*"
                            class="block w-full cursor-pointer rounded-xl border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:cursor-pointer file:rounded-lg file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:font-semibold file:text-white file:transition-colors hover:file:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                        <p class="mt-1 text-xs text-gray-500">PNG, JPG, WEBP do 5MB</p>
                        @error('photo')
                            <div class="mt-1 text-sm text-red-600">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4">
                    <a href="{{ route('posts.index') }}"
                        class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-100">
                        Anuluj
                    </a>
                    <button type="submit"
                        class="inline-flex rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:-translate-y-0.5 hover:bg-indigo-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2">
                        Dodaj post
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layout>
