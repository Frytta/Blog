<x-layout>
    <main class="mx-auto w-full max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="rounded-2xl border border-gray-200 bg-white shadow-xl transition-colors duration-300 dark:border-gray-700 dark:bg-gray-900">
            <div class="border-b border-gray-100 px-6 py-5 sm:px-8 dark:border-gray-700">
                <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">Nowy post</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Uzupełnij pola i opcjonalnie dodaj zdjęcie bannera.</p>
            </div>

            <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" class="space-y-6 px-6 py-6 sm:px-8">
                @csrf

                @if ($errors->any())
                    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900/70 dark:bg-red-950/40 dark:text-red-300">
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
                        <label for="title" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Tytuł</label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-500/40" />
                        @error('title')
                            <div class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="slug" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Przyjazny adres</label>
                        <input id="slug" type="text" name="slug" value="{{ old('slug') }}"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-500/40" />
                        @error('slug')
                            <div class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="author" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Autor</label>
                        <input id="author" type="text" name="author" value="{{ old('author') }}"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-500/40" />
                        @error('author')
                            <div class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <p class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Tagi</p>
                        <div class="rounded-xl border border-gray-300 bg-white p-3 dark:border-gray-700 dark:bg-gray-800">
                            <div class="flex flex-wrap gap-2">
                                @foreach ($tags as $tag)
                                    <label class="cursor-pointer">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                            class="peer sr-only" @checked(in_array($tag->id, old('tags', [])))>
                                        <span
                                            class="inline-flex rounded-full border border-gray-300 px-3 py-1 text-xs font-medium text-gray-700 transition-all peer-checked:border-indigo-500 peer-checked:bg-indigo-600 peer-checked:text-white hover:border-indigo-300 dark:border-gray-600 dark:text-gray-300 dark:hover:border-indigo-500 dark:peer-checked:border-indigo-400 dark:peer-checked:bg-indigo-500 dark:peer-checked:text-white">
                                            #{{ $tag->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kliknij, aby zaznaczyć lub odznaczyć tag. Tagi tworzysz w panelu Filament.</p>
                        @error('tags')
                            <div class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                        @error('tags.*')
                            <div class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="lead" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Zajawka</label>
                        <textarea id="lead" name="lead" rows="3"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-500/40">{{ old('lead') }}</textarea>
                        @error('lead')
                            <div class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="content" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Treść</label>
                        <textarea id="content" name="content" rows="8"
                            class="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 shadow-sm outline-none transition-colors focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 dark:focus:border-indigo-400 dark:focus:ring-indigo-500/40">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="photo" class="mb-1.5 block text-sm font-semibold text-gray-800 dark:text-gray-200">Zdjęcie bannera</label>
                        <input id="photo" type="file" name="photo" accept="image/*"
                            class="block w-full cursor-pointer rounded-xl border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:cursor-pointer file:rounded-lg file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:font-semibold file:text-white file:transition-colors hover:file:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 dark:file:bg-indigo-500 dark:hover:file:bg-indigo-400 dark:focus:ring-indigo-500/40" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, WEBP do 5MB</p>
                        @error('photo')
                            <div class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4 dark:border-gray-700">
                    <a href="{{ route('posts.index') }}"
                        class="rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-100 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-800">
                        Anuluj
                    </a>
                    <button type="submit"
                        class="inline-flex rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-md transition-all duration-200 hover:-translate-y-0.5 hover:bg-indigo-700 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 dark:bg-indigo-500 dark:hover:bg-indigo-400 dark:focus:ring-indigo-500/60 dark:focus:ring-offset-gray-900">
                        Dodaj post
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layout>
