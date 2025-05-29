<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ config('app.name') }}</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen">
        {{ $slot }}
    </div>

    @livewireScripts
    @vite('resources/js/app.js')
</body>
</html>
