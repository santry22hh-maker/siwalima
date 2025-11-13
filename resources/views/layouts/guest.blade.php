<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite('resources/css/app.css')
    <style>
        .hero-bg {
            background-image: url("{{ asset('src/images/background/hero-background.jpg') }}");
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">

    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 hero-bg">
        <div class="flex flex-col items-center mb-2">
            <a href="/" class="flex flex-col items-center space-y-2">
                <x-application-logo />
                <h1 class="text-4xl font-bold text-white">SIWALIMA</h1>
            </a>
        </div>

        <div
            class="relative z-10 w-full sm:max-w-md px-4 py-4 bg-gray-200/40 shadow-red-500 overflow-hidden rounded-2xl border border-white">
            {{ $slot }}
        </div>

        <div class="relative z-10 mt-6 text-center">
            <a href="{{ url('/') }}"
                class="text-sm text-stone-100  hover:text-stone-300 hover:underline transition duration-150 ease-in-out">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </div>

</body>

</html>
