<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'AMS') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">

    <div class="flex min-h-screen">

        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Area -->
        <div class="flex-1 flex flex-col ml-[260px]">

            <!-- Top Navigation -->
            <x-navigation />

            <!-- Header -->
            @isset($header)
                <header class="bg-white border-b border-gray-200 px-8 py-5">
                    {{ $header }}
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 p-8">
                {{ $slot }}
            </main>

        </div>

    </div>

</body>
</html>