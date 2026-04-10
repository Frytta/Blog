<!DOCTYPE html>
<html lang="pl" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Lista Postów</title>

    <script>
        (() => {
            const storedTheme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const shouldUseDark = storedTheme ? storedTheme === 'dark' : prefersDark;

            document.documentElement.classList.toggle('dark', shouldUseDark);
        })();
    </script>

    @vite(['resources/css/app.css'])
</head>

<body class="h-full min-h-screen flex flex-col bg-gray-50 text-gray-900 transition-colors duration-300 dark:bg-gray-950 dark:text-gray-100">
    @include('partials.navigation')

    <div class="flex-1">
        {{ $slot }}
    </div>

    @include('partials.footer')

    @vite(['resources/js/app.js'])
</body>

</html>
