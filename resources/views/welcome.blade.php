<!DOCTYPE html>
<html class="bg-white dark:bg-gray-950 scheme-light dark:scheme-dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ 'SIWALIMA', config('app.name') }}</title>
    <link rel="icon" href="{{ asset('logo-dark.ico') }}">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />

    <!-- Styles / Scripts -->
    @vite('resources/css/app.css')

    <!-- Scripts -->
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    <style>
        .hero-bg {
            background-image: url("{{ asset('src/images/background/hero-background.jpg') }}");
            background-size: cover;
            background-position: center;
        }
    </style>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col ">
        <!--Header-->
        <header id="header" class="fixed top-0 left-0 right-0 z-30 transition-all duration-300">
            <nav
                class="flex items-center justify-between p-3 lg:px-8 bg-transparent after:pointer-events-none after:absolute after:inset-x-0 after:bottom-0 ">
                <div class="flex lg:flex-1">
                    <a href="{{ url('/') }}" class="-m-1.5 p-1.5">
                        {{-- Teks untuk screen reader sekarang menggunakan span --}}
                        <span class="sr-only">SIGALIMA</span>
                        <img src="{{ asset('src/images/logo/logo-icon.png') }}" alt="Logo" class="h-10 w-auto" />
                    </a>
                </div>
                <div class="flex lg:hidden">
                    <button type="button" command="show-modal" commandfor="mobile-menu"
                        class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-200">
                        <span class="sr-only">Open main menu</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            data-slot="icon" aria-hidden="true" class="size-6">
                            <path d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </button>
                </div>

                {{-- menu bar --}}
                <div class="hidden lg:flex lg:gap-x-4">
                    <a href="/"
                        class="text-white py-1 px-3 rounded hover:text-green-400 hover:rounded hover:bg-gray-100/30  transition duration-300">Beranda</a>
                    <a href="klarifikasi/statistik"
                        class="text-white py-1 px-3 rounded hover:text-green-400 hover:rounded hover:bg-gray-100/30 transition duration-300">
                        Klarifikasi Kawasan Hutan</a>
                    <a href="/spasial"
                        class="text-white py-1 px-3 rounded hover:text-green-400 hover:rounded hover:bg-gray-100/30 transition duration-300">Informasi
                        Geospasial</a>
                </div>
                {{-- drop down profile/menu --}}
                <div class="hidden lg:flex lg:flex-1 lg:justify-end">
                    <div class="px-2 flex space-x-2">
                        @if (Route::has('login'))
                            @auth
                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:text-green-400 over:rounded hover:bg-gray-100/30  transition duration-300">
                                            <div>{{ Auth::user()->name }}</div>

                                            <div class="ml-1">
                                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>
                                    </x-slot>

                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profile') }}
                                        </x-dropdown-link>

                                        <!-- Authentication -->
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @else
                                <a href="{{ route('login') }}"
                                    class="ml-1 bg-green-500 text-white py-1 px-6 rounded-md hover:bg-green-600 transition duration-300 hidden md:block">Masuk</a>

                            @endauth
                        @endif
                    </div>
                </div>
            </nav>

            <!-- Mobile menu, show/hide based on menu open state. -->
            <el-dialog>
                <dialog id="mobile-menu" class="backdrop:bg-transparent lg:hidden">
                    <div tabindex="0" class="fixed inset-0 focus:outline-none">
                        <el-dialog-panel
                            class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-gray-900 p-6 sm:max-w-sm sm:ring-1 sm:ring-gray-100/10">
                            <div class="flex items-center justify-between">
                                <a href="#" class="-m-1.5 p-1.5">
                                    <span class="sr-only">Siwalima</span>
                                    <img src="{{ asset('src/images/logo/logo-icon.png') }}" alt=""
                                        class="h-8 w-auto" />
                                </a>
                                <button type="button" command="close" commandfor="mobile-menu"
                                    class="-m-2.5 rounded-md p-2.5 text-gray-200">
                                    <span class="sr-only">Close menu</span>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                        data-slot="icon" aria-hidden="true" class="size-6">
                                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-6 flow-root">
                                <div class="-my-6 divide-y divide-white/10">
                                    <div class="space-y-2 py-6">
                                        <a href="/"
                                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Beranda</a>
                                        <a href="/klarifikasi"
                                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Klarifikasi
                                            Kawasan Hutan</a>
                                        <a href="/spasial"
                                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Informasi
                                            Geospasial</a>
                                    </div>
                                    {{-- PERBAIKAN: Ganti blok @if Anda dengan ini --}}
                                    <div class="py-6"> {{-- Memberi jarak pemisah --}}
                                        @if (Route::has('login'))
                                            @auth
                                                {{-- Ini adalah blok untuk pengguna yang sudah login --}}
                                                {{-- <x-dropdown> mungkin tidak terlihat bagus di mobile, --}}
                                                {{-- tapi kita biarkan dulu sesuai kode Anda --}}
                                                <x-dropdown align="right" width="48">
                                                    <x-slot name="trigger">
                                                        <button
                                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white hover:text-green-400 over:rounded hover:bg-gray-100/30  transition duration-300">
                                                            <div>{{ Auth::user()->name }}</div>
                                                            <div class="ml-1">
                                                                <svg class="fill-current h-4 w-4"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd"
                                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                        </button>
                                                    </x-slot>
                                                    <x-slot name="content">
                                                        <x-dropdown-link :href="route('profile.edit')">
                                                            {{ __('Profile') }}
                                                        </x-dropdown-link>
                                                        <form method="POST" action="{{ route('logout') }}">
                                                            @csrf
                                                            <x-dropdown-link :href="route('logout')"
                                                                onclick="event.preventDefault(); this.closest('form').submit();">
                                                                {{ __('Log Out') }}
                                                            </x-dropdown-link>
                                                        </form>
                                                    </x-slot>
                                                </x-dropdown>
                                            @else
                                                {{-- INI PERBAIKANNYA --}}
                                                {{-- Menghapus 'hidden md:block' dan menyesuaikan styling --}}
                                                <a href="{{ route('login') }}"
                                                    class="-mx-3 block rounded-lg px-3 py-2.5 text-base/7 font-semibold text-white bg-green-500 hover:bg-green-600 transition duration-300 text-center">
                                                    Masuk
                                                </a>
                                            @endauth
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </el-dialog-panel>
                    </div>
                </dialog>
            </el-dialog>
        </header>

        <!-- Hero Section -->
        <section class="hero-bg relative">
            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-75"></div>
            <div class="relative container mx-auto px-6 pt-48 pb-40 text-center text-white">
                <h1 class="text-6xl font-extrabold leading-tight mb-4">SIWALIMA</h1>
                <h2 class="text-4xl font-semibold mb-6">Sistem Informasi Sub Wali Data Geospasial Kehutanan
                    Maluku
                </h2>
                <p class="text-xl max-w-4xl mx-auto mb-10">
                    Platform canggih untuk Data Informasi Kehutanan di Provinsi Maluku, monitoring, analisis, dan
                    pengelolaan data kehutanan Indonesia. Integrasikan data
                    spasial dengan teknologi foto geotagging untuk analisis lokasi yang akurat.
                </p>
                <div
                    class="flex flex-col items-center space-y-4 md:flex-row md:justify-center md:space-y-0 md:space-x-4">
                    <a href="#"
                        class="bg-green-500 text-white py-4 px-8 rounded-full text-xl font-semibold hover:bg-green-600 transition duration-300 transform hover:scale-105">
                        Jelajahi Peta &rarr;
                    </a>
                    <a href="#"
                        class="bg-gray-700 bg-opacity-50 text-white py-4 px-8 rounded-full text-xl font-semibold hover:bg-gray-800 transition duration-300 transform hover:scale-105">
                        Mulai Analisis
                    </a>
                </div>
            </div>
        </section>
        <!-- Features Section -->
        <section class="py-24 bg-gray-50">
            <div class="container mx-auto px-6 text-center">
                <h2 class="text-5xl font-bold text-gray-800 mb-6">Fitur Unggulan SIGALIMA</h2>
                <p class="text-gray-600 max-w-3xl mx-auto text-lg">
                    Solusi komprehensif untuk pengelolaan data kehutanan dengan teknologi geospasial terdepan
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mt-16">
                    <!-- Feature 1 -->
                    <div
                        class="bg-white p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition duration-500">
                        <div class="text-5xl text-green-500 mb-6">🗺️</div>
                        <h3 class="text-black text-2xl font-bold mb-3">Peta Interaktif</h3>
                        <p class="text-gray-600">Visualisasi data kawasan hutan, penutupan lahan, dan izin PPKH dalam
                            format GeoJSON yang interaktif dan real-time.</p>
                    </div>
                    <!-- Feature 2 -->
                    <div
                        class="bg-white p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition duration-500">
                        <div class="text-5xl text-green-500 mb-6">📸</div>
                        <h3 class="text-black text-2xl font-bold mb-3">Analisis Foto GPS</h3>
                        <p class="text-gray-600">Upload foto dengan metadata GPS untuk analisis otomatis lokasi
                            terhadap
                            kawasan hutan dan tata guna lahan.</p>
                    </div>
                    <!-- Feature 3 -->
                    <div
                        class="bg-white p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition duration-500">
                        <div class="text-5xl text-green-500 mb-6">📊</div>
                        <h3 class="text-black text-2xl font-bold mb-3">Laporan Analisis</h3>
                        <p class="text-gray-600">Dapatkan laporan komprehensif hasil analisis spasial dengan data
                            kawasan
                            hutan dan izin terkait.</p>
                    </div>
                    <!-- Feature 4 -->
                    <div
                        class="bg-white p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition duration-500">
                        <div class="text-5xl text-green-500 mb-6">🌳</div>
                        <h3 class="text-black text-2xl font-bold mb-3">Data Kehutanan</h3>
                        <p class="text-gray-600">Akses lengkap data kawasan hutan lindung, produksi, dan konservasi
                            berdasarkan SK Menteri LHK terbaru.</p>
                    </div>
                    <!-- Feature 5 -->
                    <div
                        class="bg-white p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition duration-500">
                        <div class="text-5xl text-green-500 mb-6">⚙️</div>
                        <h3 class="text-black text-2xl font-bold mb-3">Pengajuan Permohonan</h3>
                        <p class="text-gray-600">Permohonan telaah dan klarifikasi kawasan hutan, submitting permohonan
                            secara online</p>
                    </div>
                    <!-- Feature 6 -->
                    <div
                        class="bg-white p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition duration-500">
                        <div class="text-5xl text-green-500 mb-6">🔗</div>
                        <h3 class="text-black text-2xl font-bold mb-3">API GeoJSON</h3>
                        <p class="text-gray-600">Akses data melalui REST API dalam format GeoJSON untuk integrasi
                            dengan
                            sistem eksternal.</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- CTA Section -->
        <section class="bg-green-600 text-white">
            <div class="container mx-auto px-6 py-24 text-center">
                <h2 class="text-5xl font-bold mb-6">Siap Memulai Analisis Kehutanan?</h2>
                <p class="max-w-3xl mx-auto text-lg mb-10">
                    Bergabunglah dengan SIGALIMA dan rasakan kemudahan pengelolaan data kehutanan dengan teknologi
                    geospasial terdepan.
                </p>
                <div
                    class="flex flex-col items-center space-y-4 md:flex-row md:justify-center md:space-y-0 md:space-x-4">
                    <a href="#"
                        class="bg-white text-green-600 py-4 px-12 rounded-full text-xl font-semibold hover:bg-gray-200 transition duration-300 transform hover:scale-105">
                        Daftar Sekarang
                    </a>
                    <a href="#"
                        class="border-2 border-white text-white py-4 px-7 rounded-full text-xl font-semibold hover:bg-white hover:text-green-600 transition duration-300 transform hover:scale-105">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </section>
        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="container mx-auto px-6 py-2">

                <div class="text-center text-gray-500 py-4  border-t border-gray-800">
                    &copy; 2025 SIWALIMA, Dikembangkan untuk pengelolaan data kehutanan di Provinsi Maluku.
                </div>
            </div>
        </footer>
    </div>
</body>
<!-- Script untuk efek header scroll -->
<script>
    window.addEventListener("scroll", function() {
        const header = document.getElementById("header");
        if (window.scrollY > 50) {
            header.classList.add("bg-gray-900/90", "backdrop-blur-sm");
        } else {
            header.classList.remove("bg-gray-900/90", "backdrop-blur-sm");
        }
    });
</script>

</html>
