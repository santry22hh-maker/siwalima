<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Jelajahi Peta</title>

    {{-- Font & Icons --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Leaflet & Plugins CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@latest/Control.FullScreen.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" />

    {{-- CSS Sidepanel (Lokal) --}}
    <link rel="stylesheet" href="{{ asset('src/css/leaflet-sidepanel.css') }}" />

    {{-- Vite Resources --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        /* Header Navbar */
        .map-header {
            height: 60px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            position: relative;
            z-index: 1001;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .dark .map-header {
            background: #111827;
            border-color: #374151;
            color: white;
        }

        /* Peta Full Height */
        #map {
            height: calc(100vh - 60px);
            width: 100%;
            z-index: 1;
        }

        /* Popup Style */
        .leaflet-popup-content-wrapper {
            border-radius: 8px;
            overflow: hidden;
            padding: 0;
        }

        .leaflet-popup-content {
            margin: 0;
            width: 280px !important;
        }

        .popup-header {
            background: #10B981;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 14px;
        }

        .popup-body {
            padding: 10px;
            font-size: 12px;
            color: #333;
            max-height: 200px;
            overflow-y: auto;
        }

        /* Sidepanel Adjustment */
        .leaflet-sidepanel {
            top: 60px !important;
            height: calc(100% - 60px) !important;
            z-index: 2000 !important;
        }

        .sidepanel-content {
            font-family: 'Figtree', sans-serif;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900" x-data="{ darkMode: false }" :class="{ 'dark': darkMode }">

    {{-- HEADER --}}
    <header class="map-header">
        <div class="flex items-center gap-3">
            <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('src/images/logo/logo_kemenhut.png') }}"
                    class="h-8 w-auto transition group-hover:scale-105" alt="Logo">
                <span class="font-bold text-lg text-emerald-600 dark:text-emerald-400 tracking-tight hidden sm:block">
                    SIWALIMA <span class="font-normal text-gray-600 dark:text-gray-400 text-sm">| WebGIS</span>
                </span>
            </a>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ url('/') }}"
                class="text-sm font-medium text-gray-600 hover:text-emerald-600 dark:text-gray-300 dark:hover:text-emerald-400 transition-colors">Beranda</a>
            <div class="h-4 w-px bg-gray-300 dark:bg-gray-600"></div>
            @auth
                <a href="{{ route('dashboard') }}"
                    class="text-sm font-medium text-gray-600 hover:text-emerald-600 dark:text-gray-300 transition-colors">Dashboard</a>
            @else
                <a href="{{ route('login') }}"
                    class="px-4 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-full shadow-sm transition">Masuk</a>
            @endauth
            <button @click="darkMode = !darkMode"
                class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 transition-colors">
                <i class="fas" :class="darkMode ? 'fa-sun' : 'fa-moon'"></i>
            </button>
        </div>
    </header>

    {{-- CONTAINER PETA --}}
    <div class="relative w-full">
        <div id="map-loader"
            style="position:absolute; top:60px; left:0; right:0; bottom:0; background:rgba(255,255,255,0.8); z-index:3000; display:flex; flex-direction:column; justify-content:center; align-items:center; color:#4B5563;">
            <svg class="animate-spin h-10 w-10 text-emerald-500 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                </circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
            <span class="font-semibold">Memuat Peta...</span>
        </div>

        <div id="map">
            {{-- SIDEPANEL --}}
            <x-leafletpanel />
        </div>
    </div>

    @stack('scripts')
</body>

</html>
