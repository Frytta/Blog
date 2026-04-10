    <!-- Navigation -->
    <nav class="border-b border-gray-200 bg-white shadow-sm transition-colors duration-300 dark:border-gray-800 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        📝 Blog
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('posts.index') }}"
                        class="rounded-md px-3 py-2 text-sm font-medium text-gray-700 transition-colors hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100">
                        Home
                    </a>
                    <a href="{{ route('posts.create') }}"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-400">
                        Nowy Post
                    </a>

                    <button type="button" data-theme-toggle
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-gray-300 bg-gray-100 text-amber-500 transition-colors hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 dark:border-gray-700 dark:bg-gray-800 dark:text-yellow-300 dark:hover:bg-gray-700 dark:focus:ring-offset-gray-900"
                        title="Przełącz motyw" aria-label="Przełącz motyw">
                        <svg data-theme-icon="sun" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="currentColor" class="h-5 w-5">
                            <path
                                d="M12 2.25a.75.75 0 01.75.75V4.5a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.05 5.99a.75.75 0 011.06 0l1.06 1.06a.75.75 0 11-1.06 1.06L7.05 7.05a.75.75 0 010-1.06zm9.84 0a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 11-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zM12 8.25a3.75 3.75 0 100 7.5 3.75 3.75 0 000-7.5zM4.5 11.25a.75.75 0 010 1.5H3a.75.75 0 010-1.5h1.5zm16.5 0a.75.75 0 010 1.5h-1.5a.75.75 0 010-1.5H21zm-4.11 4.64a.75.75 0 011.06 1.06l-1.06 1.06a.75.75 0 11-1.06-1.06l1.06-1.06zM8.11 15.89a.75.75 0 010 1.06l-1.06 1.06a.75.75 0 11-1.06-1.06l1.06-1.06a.75.75 0 011.06 0zM12 19.5a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-.75A.75.75 0 0112 19.5z" />
                        </svg>
                        <svg data-theme-icon="moon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                            fill="currentColor" class="hidden h-5 w-5">
                            <path
                                d="M9.53 2.47a.75.75 0 01.82.14.75.75 0 01.17.81 8.25 8.25 0 0010.96 10.96.75.75 0 01.95.99A9.75 9.75 0 1110.5 1.52a.75.75 0 01-.97.95z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>
