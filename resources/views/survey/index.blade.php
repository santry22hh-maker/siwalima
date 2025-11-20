<x-klarifikasi-layout> {{-- Atau <x-jig-layout> sesuai layout Anda --}}

    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6 sm:p-10">

            @if (session('success'))
                {{-- 1. Tampilan "Terima Kasih" (jika baru selesai submit) --}}
                <div class="text-center">
                    <i class="fas fa-check-circle fa-4x text-green-500 mb-4"></i>
                    <h3 class="text-2xl font-medium text-gray-800 dark:text-white/90">
                        Terima Kasih, {{ Auth::user()->name }}!
                    </h3>
                    <p class="text-base text-gray-600 dark:text-gray-400 mt-3">
                        {{ session('success') }}
                    </p>
                    <p class="text-base text-gray-600 dark:text-gray-400 mt-1">
                        Masukan Anda akan menjadi bahan perbaikan kualitas pelayanan kami.
                    </p>

                    {{-- Tombol Kembali --}}
                    <a href="{{ route('permohonananalisis.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-3 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 mt-8">
                        Kembali ke Daftar Permohonan
                    </a>
                </div>
            @elseif (session('error'))
                {{-- 2. Tampilan "Error" (jika mencoba mengisi survei yang sudah diisi) --}}
                <div class="text-center">
                    <i class="fas fa-exclamation-triangle fa-4x text-yellow-500 mb-4"></i>
                    <h3 class="text-2xl font-medium text-gray-800 dark:text-white/90">
                        Survei Telah Diisi
                    </h3>
                    <p class="text-base text-gray-600 dark:text-gray-400 mt-3">
                        {{ session('error') }}
                    </p>

                    {{-- Tombol Kembali --}}
                    <a href="{{ route('permohonananalisis.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03] mt-8">
                        Kembali ke Daftar Permohonan
                    </a>
                </div>
            @else
                {{-- 3. Tampilan Default (jika pengguna mendarat di sini tanpa submit) --}}
                <div class="text-center">
                    <h3 class="text-xl font-medium text-gray-800 dark:text-white/90">
                        Halaman Survei
                    </h3>

                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                        “Terima kasih Bapak/Ibu {{ Auth::user()->name }} atas partisipasi Anda dalam mengisi survei
                        pelayanan. Hasil survei ini akan kami
                        gunakan sebagai dasar perbaikan dan pengembangan layanan analisis.”
                    </p>

                    <a href="{{ route('permohonananalisis.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03] mt-6">
                        Kembali
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-klarifikasi-layout>
