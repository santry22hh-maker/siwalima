<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGAP KLHK - Nuansa Hutan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #F3F4F6;
        }

        .hero-gradient {
            background: linear-gradient(90deg, #14532d 0%, #166534 50%, #14532d 100%);
        }

        .hero-pattern {
            background-image: url('https://www.transparenttextures.com/patterns/wood-pattern.png');
            opacity: 0.15;
        }
    </style>
</head>

<body>
    <!--Header-->
    <header id="header" class="fixed top-0 left-0 right-0 z-30 transition-all duration-300">
        <nav
            class="flex items-center justify-between p-3 lg:px-8 bg-transparent after:pointer-events-none after:absolute after:inset-x-0 after:bottom-0 ">
            <div class="flex lg:flex-1">
                <a href="{{ url('/') }}" class="-m-1.5 p-1.5">
                    {{-- Teks untuk screen reader sekarang menggunakan span --}}
                    <span class="sr-only">SIGALIMA</span>
                    <img src="{{ asset('src/images/logo/logo_kemenhut.png') }}" alt="Logo" class="h-10 w-auto" />
                </a>
            </div>
            <div class="flex lg:hidden">
                <button type="button" command="show-modal" commandfor="mobile-menu"
                    class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-gray-200">
                    <span class="sr-only">Open main menu</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                        aria-hidden="true" class="size-6">
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
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
                                    <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
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

                                    <div class="relative group">
                                        <button
                                            class="-mx-3 w-full text-left flex items-center justify-between rounded-lg px-3 py-2 text-base/7 font-semibold text-white hover:bg-white/5 transition duration-300">
                                            <span>Publikasi</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>

                                        <div class="hidden group-hover:block pl-4 space-y-1 mt-1">
                                            {{-- Opsi A: Jika ingin submenu menjorok ke bawah (Standard Mobile Menu) --}}
                                            <a href="/documents"
                                                class="-mx-3 block rounded-lg px-3 py-2 text-sm font-medium text-gray-300 hover:text-white hover:bg-white/5">
                                                Dokumen Elektronik
                                            </a>
                                        </div>
                                    </div>
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
                                                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
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

    {{-- Konten --}}
    <div class="relative hero-gradient h-[250px] w-full flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 hero-pattern z-0"></div>
        <div class="absolute top-0 left-10 w-64 h-64 border-2 border-green-300/20 rounded-full"></div>
        <div class="absolute bottom-0 right-20 w-96 h-96 border-2 border-green-300/20 rounded-full"></div>
        <h2
            class="relative z-10 text-2xl lg:text-4xl font-bold mt-20 text-white tracking-wide drop-shadow-lg uppercase text-center">
            Dokumen Elektronik
        </h2>
    </div>

    <div class="relative z-20 max-w-4xl mx-auto px-4 -mt-8">
        <form action="{{ url()->current() }}" method="GET">
            <div
                class="bg-white rounded-xl shadow-xl p-4 flex flex-col md:flex-row items-center h-auto md:h-24 border-b-4 border-green-600">
                <div class="flex-grow w-full h-full px-2">
                    <div class="relative h-full flex items-center">
                        <svg class="w-6 h-6 text-green-700 ml-2" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Ketik nama dokumen, SK, atau nomor peta..."
                            class="w-full h-full px-4 text-gray-700 placeholder-gray-400 outline-none text-lg font-medium">
                    </div>
                </div>
                <div class="w-full md:w-auto mt-3 md:mt-0">
                    <button type="submit"
                        class="w-full bg-green-700 hover:bg-green-800 text-white font-bold py-3 px-8 rounded-lg transition shadow-md">
                        CARI
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="container mx-auto px-4 pt-12 pb-20 max-w-6xl">
        <div class="flex justify-between items-end mb-6 border-b pb-2 border-gray-300">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Dokumen</h2>
            <span class="text-sm text-gray-500">Menampilkan {{ $documents->count() }} dari {{ $documents->total() }}
                data</span>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            @forelse($documents as $doc)
                <div
                    class="flex bg-white shadow-md rounded-lg overflow-hidden hover:shadow-xl transition border border-gray-100 h-full">

                    <div
                        class="w-32 bg-gray-100 flex items-center justify-center flex-shrink-0 overflow-hidden relative">
                        @if ($doc->image_path)
                            <img src="{{ asset('storage/' . $doc->image_path) }}" alt="{{ $doc->title }}"
                                class="w-full h-full object-cover absolute inset-0">
                        @else
                            <span class="text-4xl">
                                @if (stripos($doc->type, 'peta') !== false)
                                    🗺️
                                @elseif(stripos($doc->type, 'sk') !== false)
                                    ⚖️
                                @else
                                    📄
                                @endif
                            </span>
                        @endif
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <h2 class="text-lg font-bold text-green-800 mb-1 line-clamp-2">{{ $doc->title }}</h2>

                        <p class="text-xs font-semibold text-green-600 uppercase tracking-wide mb-2">
                            {{ $doc->type ?? 'Umum' }}
                        </p>

                        <p class="text-gray-600 text-sm line-clamp-3 mb-4 flex-grow">
                            {{ $doc->description }}
                        </p>

                        <div class="flex gap-2 mt-auto">
                            @if ($doc->file_path)
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded shadow transition">
                                    Unduh
                                </a>
                            @else
                                <button disabled
                                    class="px-4 py-2 bg-gray-300 text-gray-500 text-sm font-medium rounded cursor-not-allowed">
                                    Tidak ada file
                                </button>
                            @endif
                            <a href="{{ $doc->link_doc }}" target="_blank"
                                class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-600 text-sm font-medium rounded transition">
                                Link Tautan
                            </a>

                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-10 bg-white rounded-lg shadow">
                    <p class="text-gray-500 text-lg">Tidak ada dokumen ditemukan untuk pencarian Anda.</p>
                    <a href="{{ url()->current() }}" class="text-green-600 hover:underline mt-2 block">Reset
                        Pencarian</a>
                </div>
            @endforelse
        </div>

        <div class="mt-8 flex justify-center">
            {{ $documents->withQueryString()->links() }}
        </div>
    </div>

    <div class="h-20"></div>

    <footer class="bg-gray-900 text-white">
        <div class="container mx-auto px-6 py-2">
            <div class="text-center text-gray-500 py-4 border-t border-gray-800">
                &copy; {{ date('Y') }} SIWALIMA, Dikembangkan untuk pengelolaan data kehutanan di Provinsi Maluku.
            </div>
        </div>
    </footer>

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
