<!DOCTYPE html>
<html class="bg-white dark:bg-gray-950 scheme-light dark:scheme-dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ 'SIWALIMA', config('app.name') }}</title>
    <link rel="icon" href="{{ asset('logo_bpkh.ico') }}">
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
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
                        <img src="{{ asset('src/images/logo/logo-bpkh.png') }}" alt="Logo" class="h-10 w-auto" />
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
                        class="text-lg text-white py-1 px-3 rounded hover:text-green-400 hover:rounded hover:bg-gray-100/30 transition duration-300">
                        Beranda
                    </a>
                    <a href="klarifikasi/input"
                        class="text-white py-1 px-3 rounded hover:text-green-400 hover:rounded hover:bg-gray-100/30 transition duration-300">
                        Klarifikasi Kawasan Hutan
                    </a>
                    <a href="/spasial"
                        class="text-white py-1 px-3 rounded hover:text-green-400 hover:rounded hover:bg-gray-100/30 transition duration-300">
                        Informasi Geospasial
                    </a>

                    <!-- Menu Publikasi dengan dropdown -->
                    <div class="relative group">
                        <button
                            class="text-white py-1 px-3 rounded hover:text-green-400 hover:rounded hover:bg-gray-100/30 transition duration-300 flex items-center gap-1">
                            Publikasi
                            <svg class="w-4 h-4 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <!-- Dropdown -->
                        <div
                            class="absolute left-0 mt-1 w-48 bg-white rounded shadow-lg opacity-0 group-hover:opacity-100 invisible group-hover:visible transition-all duration-300 z-10">
                            <a href="/documents" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Dokumen
                                Elektronik</a>
                            <!-- Tambahkan submenu lain di sini jika perlu -->
                        </div>
                    </div>
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
                                        <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-6 flow-root">
                                <div class="-my-6 divide-y divide-white/10">
                                    <div class="space-y-2 py-6">
                                        <a href="/"
                                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Beranda</a>
                                        <a href="/klarifikasi/input"
                                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Klarifikasi
                                            Kawasan Hutan</a>
                                        <a href="/spasial"
                                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Informasi
                                            Geospasial</a>
                                        <a href="/"
                                            class="-mx-3 block rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5">Publikasi</a>
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

        {{-- <!-- Hero Section -->
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
                    <a href="/interaktifmap"
                        class="bg-green-500 text-white py-4 px-8 rounded-full text-xl font-semibold hover:bg-green-600 transition duration-300 transform hover:scale-105">
                        Jelajahi Peta &rarr;
                    </a>
                    <a href="#"
                        class="bg-gray-700 bg-opacity-50 text-white py-4 px-8 rounded-full text-xl font-semibold hover:bg-gray-800 transition duration-300 transform hover:scale-105">
                        Mulai Analisis
                    </a>
                </div>
            </div>
        </section> --}}

        <div class="relative w-full h-[500px] lg:h-[600px] group">

            <div class="swiper mySwiper w-full h-full">
                <div class="swiper-wrapper">
                    @forelse($carousels as $slide)
                        <div class="swiper-slide relative w-full h-full block">

                            <div class="absolute inset-0 w-full h-full">
                                <img src="{{ asset('storage/' . $slide->image_path) }}" alt="{{ $slide->title }}"
                                    class="w-full h-full object-cover">

                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent">
                                </div>
                            </div>

                            <div
                                class="relative z-10 h-full flex items-end pb-16 md:pb-24 container mx-auto px-4 lg:px-12">
                                <div class="max-w-4xl text-left">
                                    <h2
                                        class="text-3xl md:text-5xl lg:text-6xl font-bold text-white mb-4 leading-tight drop-shadow-lg animate-fade-in-up">
                                        {{ $slide->title }}
                                    </h2>
                                    <p
                                        class="text-gray-200 text-lg md:text-xl mb-8 leading-relaxed drop-shadow-md max-w-2xl">
                                        {{ $slide->description }}
                                    </p>

                                    @if ($slide->link_url)
                                        <a href="{{ $slide->link_url }}"
                                            class="inline-flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-lg transition-all duration-300 transform hover:-translate-y-1 shadow-lg">
                                            Baca Selengkapnya
                                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide relative w-full h-full flex items-center justify-center bg-gray-800">
                            <div class="text-center text-white">
                                <h2 class="text-2xl font-bold">Data Carousel Kosong</h2>
                                <p>Silakan isi data di database.</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <div class="swiper-button-next !text-white/70 hover:!text-white transition"></div>
                <div class="swiper-button-prev !text-white/70 hover:!text-white transition"></div>

                <div class="swiper-pagination !bottom-8"></div>

            </div>


            <!-- Features Section -->
            <section class="py-10 bg-gray-50">
                <div class="container mx-auto px-6 text-center">
                    <h2 class="text-4xl font-bold text-gray-800 mb-2">Fitur Unggulan SIGALIMA</h2>
                    <p class="text-gray-600 max-w-3xl mx-auto text-lg">
                        Solusi komprehensif untuk pengelolaan data kehutanan dengan teknologi geospasial terdepan
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mt-6">
                        <!-- Feature 1 -->
                        <div
                            class="bg-white p-8 rounded-xl shadow-lg transform hover:-translate-y-2 transition duration-500">
                            <div class="text-5xl text-green-500 mb-6">🗺️</div>
                            <h3 class="text-black text-2xl font-bold mb-3">Peta Interaktif</h3>
                            <p class="text-gray-600">Visualisasi data kawasan hutan, penutupan lahan, dan izin PPKH
                                dalam
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
                            <p class="text-gray-600">Permohonan telaah dan klarifikasi kawasan hutan, submitting
                                permohonan
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

            {{-- News Feed --}}
            <section class="py-16 bg-gray-50">
                <div class="container mx-auto px-4 max-w-6xl">

                    <div class="flex justify-between items-end mb-10">
                        <div>
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-900">Berita & Artikel</h2>
                            <div class="h-1 w-20 bg-green-600 mt-3 rounded-full"></div>
                        </div>
                        <a href="#"
                            class="hidden md:flex items-center text-green-700 font-semibold hover:text-green-800 transition">
                            Lihat Semua Berita
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        @forelse($news as $item)
                            <div
                                class="bg-white rounded-2xl shadow-sm hover:shadow-xl border border-gray-100 overflow-hidden transition-all duration-300 transform hover:-translate-y-2 group">

                                <div class="relative h-52 overflow-hidden">
                                    <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">

                                    <div
                                        class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-lg text-xs font-bold text-green-800 shadow-sm">
                                        {{ \Carbon\Carbon::parse($item->published_at)->format('d M Y') }}
                                    </div>
                                </div>

                                <div class="p-6">
                                    <h3
                                        class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-green-700 transition">
                                        {{ $item->title }}
                                    </h3>

                                    <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                        {{ Str::limit(strip_tags($item->content), 100) }}
                                    </p>

                                    <a href="#"
                                        class="inline-flex items-center text-green-600 font-semibold text-sm hover:text-green-800 transition group/link">
                                        Selengkapnya
                                        <svg class="w-4 h-4 ml-1 transform transition-transform group-hover/link:translate-x-1"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-10 text-gray-500">
                                Belum ada berita terbaru.
                            </div>
                        @endforelse
                    </div>

                </div>
                <div class="mt-12 text-center">
                    <p class="text-gray-500 mb-4 text-sm">Ingin melihat kegiatan kami lainnya?</p>

                    <a href="{{ route('news.index') }}"
                        class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-full text-white bg-green-700 hover:bg-green-800 md:text-lg md:px-10 shadow-lg transform hover:-translate-y-1 transition duration-300">
                        Jelajahi Semua Berita
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
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

    <!-- Script untuk efek header scroll -->

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        var swiper = new Swiper(".mySwiper", {
            loop: true, // Looping terus menerus
            effect: "fade", // Efek memudar (lebih elegan untuk hero)
            speed: 1000, // Kecepatan transisi
            autoplay: {
                delay: 5000, // Ganti slide setiap 5 detik
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    </script>
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

</body>


</html>
