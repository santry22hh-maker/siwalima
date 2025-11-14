<x-jig-layout>
    <div class="px-2 mb-4">
        <div
            class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">

            {{-- Header Card --}}
            <div
                class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 pb-3 mb-4 dark:border-gray-800">
                <div>
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{ __('Riwayat Pengaduan Saya') }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Pantau status dan lihat balasan untuk semua pengaduan yang telah Anda kirim.
                    </p>
                </div>
            </div>

            {{-- Daftar Pengaduan --}}
            <div class="space-y-6">
                @forelse ($pengaduans as $pengaduan)
                    {{-- Setiap pengaduan ditampilkan sebagai card --}}
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden">

                        {{-- PERBAIKAN: Header Card dirapikan, hanya berisi Status dan Tanggal --}}
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:px-5 sm:py-4">
                            <div class="flex flex-wrap items-center justify-between gap-3">

                                {{-- Status --}}
                                {{-- GANTI BLOK STATUS LAMA ANDA DENGAN INI --}}
                                <div>
                                    @if ($pengaduan->status == 'Baru')
                                        {{-- Warna Biru (Info) --}}
                                        <span
                                            class="inline-flex items-center rounded-full bg-blue-600 px-3 py-1 text-sm font-medium text-white shadow-sm dark:bg-blue-500">
                                            Baru
                                        </span>

                                        {{-- KELOMPOKKAN SEMUA STATUS PROSES JADI SATU UNTUK PENGGUNA --}}
                                    @elseif (in_array($pengaduan->status, ['Diproses', 'Menunggu Persetujuan', 'Revisi']))
                                        {{-- Warna Kuning (Warning) --}}
                                        <span
                                            class="inline-flex items-center rounded-full bg-yellow-500 px-3 py-1 text-sm font-medium text-white shadow-sm dark:bg-yellow-400 dark:text-gray-900">
                                            Sedang Diproses
                                        </span>
                                    @elseif ($pengaduan->status == 'Selesai')
                                        {{-- Warna Hijau (Success) --}}
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-600 px-3 py-1 text-sm font-medium text-white shadow-sm dark:bg-green-500">
                                            Selesai
                                        </span>
                                    @elseif ($pengaduan->status == 'Dibatalkan')
                                        {{-- Warna Abu-abu --}}
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-500 px-3 py-1 text-sm font-medium text-white shadow-sm dark:bg-gray-400 dark:text-gray-900">
                                            Dibatalkan
                                        </span>
                                    @endif
                                </div>
                                {{-- Tombol Batal DIHAPUS DARI SINI --}}

                                {{-- Tanggal --}}
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <time datetime="{{ $pengaduan->created_at->toIso8601String() }}">
                                        {{ $pengaduan->created_at->format('d F Y, H:i') }}
                                    </time>
                                </div>
                            </div>
                        </div>

                        {{-- PERBAIKAN: Padding diubah dari 'py-2' menjadi 'p-4' agar konsisten --}}
                        <div class="p-4 sm:p-5 space-y-4">
                            {{-- Pesan Anda --}}
                            <div>
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Pesan
                                    Anda:</label>
                                {{-- Kotak Pesan --}}
                                <div
                                    class="mt-2 p-3 bg-gray-50 dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700">
                                    <p class="text-sm text-gray-800 dark:text-white/90" style="white-space: pre-wrap;">
                                        {{ $pengaduan->pesan }}</p>
                                </div>
                            </div>

                            {{-- Tampilkan balasan HANYA JIKA status 'Selesai' dan ada balasan --}}
                            @if ($pengaduan->status == 'Selesai' && $pengaduan->balasan_penelaah)
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <label class="text-sm font-semibold text-brand-600 ...">
                                        Tanggapan Petugas:
                                    </label>
                                    <div class="mt-2 p-3 bg-green-50 ...">
                                        {{-- Tampilkan balasan_penelaah, BUKAN catatan_admin --}}
                                        <p ... style="white-space: pre-wrap;">{{ $pengaduan->balasan_penelaah }}</p>
                                    </div>
                                </div>
                            @else
                                {{-- Keterangan jika belum ada balasan --}}
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <p class="text-sm text-center text-gray-500 ... italic">
                                        @if ($pengaduan->status == 'Dibatalkan')
                                            Pengaduan dibatalkan oleh Anda.
                                        @else
                                            Belum ada tanggapan dari petugas.
                                        @endif
                                    </p>
                                </div>
                            @endif

                            {{-- PERBAIKAN: Tombol Batal dipindah ke sini (Card Footer) --}}
                            @if ($pengaduan->status == 'Baru')
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4 text-right">
                                    <form action="{{ route('pengaduan.cancel', $pengaduan) }}" method="POST"
                                        onsubmit="return confirm('Anda yakin ingin membatalkan pengaduan ini?');">
                                        @csrf
                                        <button type="submit"
                                            class="text-sm font-medium text-red-600 hover:text-red-500 hover:underline">
                                            Batalkan Pengaduan
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                @empty
                    {{-- Tampilan jika belum ada pengaduan --}}
                    <div class="text-center text-gray-500 dark:text-gray-400 py-10 px-6">
                        {{-- Ikon SVG --}}
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" aria-hidden="true">
                            <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>

                        <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white/90">Belum Ada Pengaduan</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum pernah mengirimkan
                            pengaduan.</p>

                        {{-- Tombol Aksi --}}
                        <div class="mt-6">
                            <a href="{{ route('pengaduan.index') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-sm ring-1 ring-inset ring-brand-500 transition hover:bg-brand-600 dark:bg-brand-500 dark:text-white dark:ring-brand-500 dark:hover:bg-brand-600">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Buat Pengaduan Baru
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Link Paginasi --}}
            @if ($pengaduans->hasPages())
                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                    {{ $pengaduans->links() }}
                </div>
            @endif

        </div>
    </div>
</x-jig-layout>
