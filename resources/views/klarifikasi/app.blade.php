<!doctype html>
{{-- 1. x-data="theme()" diganti menggunakan Alpine.store agar lebih bersih --}}
<html lang="en" x-data :class="{ 'dark': $store.theme.isDark }">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>SI-GALIMA</title>
    <link rel="icon" href="{{ asset('logo-dark.ico') }}">


    {{-- Link CSS Anda --}}
    <link href="{{ asset('src/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('src/fontawesome/css/all.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />

    {{-- Menggunakan Vite untuk CSS dan JS (Ini sudah benar) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<head>

<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen">
    {{-- ===== Preloader ===== --}}
    {{-- 2. Logika x-init disederhanakan dan dibuat independen --}}
    <div x-data="{ loaded: true }" x-show="loaded" x-init="setTimeout(() => loaded = false, 300)"
        class="fixed left-0 top-0 z-999999 flex h-screen w-screen items-center justify-center bg-white dark:bg-black">
        <div class="h-16 w-16 animate-spin rounded-full border-4 border-solid border-brand-500 border-t-transparent">
        </div>
    </div>
    {{-- ===== Preloader End ===== --}}

    <div class="flex h-screen overflow-hidden">
        {{-- Komponen Blade ini HARUS menggunakan $store.sidebar.open --}}
        <x-klarifikasilayouts.sidebar />
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            {{-- 3. Menggunakan $store.sidebar.open untuk mengontrol overlay --}}
            <div @click="$store.sidebar.open = false" :class="$store.sidebar.open ? 'block lg:hidden' : 'hidden'"
                class="fixed w-full h-screen z-9 bg-gray-900/50"></div>
            {{-- Komponen Blade ini HARUS menggunakan $store.sidebar.open dan $store.theme.isDark --}}
            <x-klarifikasilayouts.header />
            <main>
                <div class="px-2 py-2 mb-2">
                    {{-- <x-klarifikasilayouts.breadcrumb /> --}}
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>
    {{-- 4. HAPUS SCRIPT INI. Ini menyebabkan konflik dengan @vite --}}
    {{-- <script defer src="{{ asset('src/js/bundle.js') }}"></script> --}}

    @stack('scripts')
</body>

</html>
