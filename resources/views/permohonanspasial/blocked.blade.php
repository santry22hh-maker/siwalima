<x-jig-layout>
    <div class="px-2 mb-4">
        {{-- Card Pesan Error --}}
        <div class="rounded-2xl border-4 border-red-300 bg-red-50 p-6 shadow-lg dark:border-red-700 dark:bg-red-900/30">

            <div class="flex items-center gap-4">
                <div class="rounded-full bg-red-100 dark:bg-red-900/50 p-3">
                    <i class="fas fa-ban fa-2x text-red-600 dark:text-red-400"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-red-800 dark:text-red-200">
                        Akses Pembuatan Permohonan Ditolak
                    </h3>
                </div>
            </div>

            <div class="mt-4 text-red-700 dark:text-red-200 space-y-2">
                <p>Anda tidak dapat mengajukan permohonan data baru saat ini.</p>
                <p>
                    Sistem kami mencatat Anda memiliki <strong>{{ $tunggakan }} permohonan</strong> (dari batas
                    {{ $limit }}) yang telah berstatus 'Selesai' namun
                    <strong>{{ $jenis_tunggakan }}</strong>-nya belum Anda selesaikan.
                </p>

                {{-- Tampilkan pesan & link yang sesuai --}}
                @if ($jenis_tunggakan == 'Laporan Penggunaan Data')
                    <p class="font-semibold pt-2">
                        Silakan unggah laporan Anda terlebih dahulu melalui menu "Laporan Penggunaan" untuk dapat
                        mengajukan permohonan baru.
                    </p>
                @else
                    <p class="font-semibold pt-2">
                        Silakan isi survei kepuasan terlebih dahulu melalui menu "Permohonan Saya" (klik tombol "Isi
                        Survey" pada permohonan yang relevan).
                    </p>
                @endif
            </div>

            <div class="mt-6">
                {{-- Arahkan ke halaman yang sesuai --}}
                @if ($jenis_tunggakan == 'Laporan Penggunaan Data')
                    <a href="{{ route('laporanpenggunaan.index') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <i class="fas fa-arrow-left"></i>
                        Buka Halaman Laporan Penggunaan
                    </a>
                @else
                    <a href="{{ route('permohonanspasial.saya') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <i class="fas fa-arrow-left"></i>
                        Buka Halaman Permohonan Saya
                    </a>
                @endif
            </div>

        </div>
    </div>
</x-jig-layout>
