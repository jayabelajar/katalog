<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gray-100 text-gray-900">
    <div class="min-h-screen flex">
        @include('admin.partials.sidebar')
        <div class="flex-1">
            @include('admin.partials.topbar')
            <main class="px-6 py-6">
                @yield('content')
            </main>
            @include('admin.partials.footer')
        </div>
    </div>
    @livewireScripts
</body>
</html>
