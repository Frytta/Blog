<!DOCTYPE html>
<html lang="pl" class="h-full bg-gray-50">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog - Lista Postów</title>

    @vite(['resources/css/app.css'])
</head>

<body class="h-full min-h-screen flex flex-col">
    @include('partials.navigation')

    <div class="flex-1">
        {{ $slot }}
    </div>

    @include('partials.footer')

    @vite(['resources/js/app.js'])
</body>

</html>
