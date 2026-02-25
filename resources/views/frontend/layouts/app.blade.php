<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Katalog Produk')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
    @include('frontend.partials.navbar')

    <main class="container mx-auto px-4 py-8">
        @yield('content')
    </main>

    @include('frontend.partials.footer')
    @livewireScripts
</body>
</html>
