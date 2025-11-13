<x-jiglayout>
    <div class="px-2 mb-4">
        {{-- Card 1: Detail Pengaduan (Selalu Tampil) --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-white/[0.03] mb-6">
            <h3
                class="text-base font-medium text-gray-800 dark:text-white/90 border-b border-gray-200 dark:border-gray-800 pb-3 mb-4">
                Detail Pengaduan #{{ $pengaduan->id }}
            </h3>

            {{-- (Seluruh kode untuk menampilkan detail pengaduan: Nama, Instansi, Pesan, dll. TIDAK BERUBAH) --}}
            {{-- ... (Pastikan kode detail Anda ada di sini) ... --}}
            <div class="space-y-4 text-sm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                    <dt class="font-medium text-gray-500 dark:text-gray-400">Nama Pelapor:</dt>
                    <dd class="md:col-span-2 text-gray-900 dark:text-white/90">{{ $pengaduan->nama }}</dd>
                </div>
                {{-- ... (Sisa detail: Instansi, Email, Status, Lampiran, Pesan) ... --}}
                <div class="grid grid-cols-1 gap-2 pt-2">
                    <dt class="font-medium text-gray-500 dark:text-gray-400 mb-1">Isi Pesan:</dt>
                    <dd
                        class="text-gray-900 dark:text-white/90 bg-gray-50 dark:bg-gray-900 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                        <p style="white-space: pre-wrap;">{{ $pengaduan->pesan }}</p>
                    </dd>
                </div>
            </div>

        </div>

        {{-- 
          =================================================
          FORMULIR AKSI DINAMIS
          =================================================
        --}}

        {{-- A. FORM UNTUK PENELAAH (Status Diproses / Revisi) --}}
        @if (in_array($pengaduan->status, ['Diproses', 'Revisi']) && Auth::user()->hasRole('Penelaah'))

            {{-- Jika ini adalah 'Revisi', tampilkan catatan dari Admin --}}
            @if ($pengaduan->status == 'Revisi' && $pengaduan->catatan_admin)
                <div
                    class="rounded-2xl border border-red-300 bg-red-50 p-4 shadow-sm sm:p-6 dark:border-red-700 dark:bg-red-900/20 mb-6">
                    <h3 class="text-base font-medium text-red-800 dark:text-red-300 flex items-center gap-2">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        Catatan Revisi dari Admin
                    </h3>
                    <p class="mt-2 text-sm text-red-700 dark:text-red-200" style="white-space: pre-wrap;">
                        {{ $pengaduan->catatan_admin }}
                    </p>
                </div>
            @endif

            {{-- Form untuk Penelaah mengisi balasan --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3
                    class="text-base font-medium text-gray-800 dark:text-white/90 border-b border-gray-200 dark:border-gray-800 pb-3 mb-4">
                    Formulir Draf Balasan (Penelaah)
                </h3>

                {{-- == TAMBAHKAN BLOK ERROR INI == --}}
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">Oops! Terjadi kesalahan:</strong>
                        <ul class="list-disc list-inside mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- ============================== --}}

                <form action="{{ route('pengaduan.submitReview', $pengaduan) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="balasan_penelaah"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Draf
                            Balasan:</label>
                        <textarea id="balasan_penelaah" name="balasan_penelaah" rows="6"
                            class="block mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:text-white/90"
                            required>{{ old('balasan_penelaah', $pengaduan->balasan_penelaah) }}</textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800">
                            Kirim untuk Persetujuan Admin
                        </button>
                    </div>
                </form>
            </div>

            {{-- B. FORM UNTUK ADMIN (Status Menunggu Persetujuan) --}}
        @elseif($pengaduan->status == 'Menunggu Persetujuan' && Auth::user()->hasRole('Admin'))
            <div
                class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3
                    class="text-base font-medium text-gray-800 dark:text-white/90 border-b border-gray-200 dark:border-gray-800 pb-3 mb-4">
                    Review Draf Balasan (Admin)
                </h3>

                {{-- == TAMBAHKAN BLOK ERROR INI == --}}
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                        role="alert">
                        <strong class="font-bold">Oops! Terjadi kesalahan:</strong>
                        <ul class="list-disc list-inside mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- ============================== --}}

                {{-- Tampilkan draf dari Penelaah (read-only) --}}
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-400">Draf Balasan dari
                        Penelaah:</label>
                    <div
                        class="mt-2 p-3 bg-gray-50 dark:bg-gray-900 rounded-md border border-gray-200 dark:border-gray-700">
                        <p class="text-sm" style="white-space: pre-wrap;">{{ $pengaduan->balasan_penelaah }}</p>
                    </div>
                </div>

                <hr class="my-4 dark:border-gray-700">

                {{-- Form untuk Admin (Setuju / Tolak) --}}
                <div>
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white/90 mb-3">Tindakan Persetujuan:</h4>

                    {{-- Form untuk Revisi/Tolak --}}
                    <form action="{{ route('pengaduan.reject', $pengaduan) }}" method="POST"
                        class="mb-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                        @csrf
                        <div class="mb-2">
                            <label for="catatan_admin"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400">Catatan Revisi (Wajib
                                diisi jika ditolak):</label>
                            <textarea id="catatan_admin" name="catatan_admin" rows="3"
                                class="block mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:text-white/90"
                                required>{{ old('catatan_admin') }}</textarea>
                        </div>
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-medium text-white shadow-sm ...">
                            Kembalikan ke Penelaah (Revisi)
                        </button>
                    </form>

                    {{-- Form untuk Setuju --}}
                    <form action="{{ route('pengaduan.approve', $pengaduan) }}" method="POST"
                        class="p-4 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        @csrf
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Jika draf balasan sudah sesuai, setujui
                            untuk menyelesaikan pengaduan.</p>
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm ...">
                            Setujui dan Selesai
                        </button>
                    </form>
                </div>
            </div>

            {{-- C. TAMPILAN JIKA SUDAH SELESAI / DIBATALKAN --}}
        @else
            <div
                class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Tindak Lanjut Ditutup
                </h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Pengaduan ini telah ditandai sebagai <strong>{{ $pengaduan->status }}</strong> dan tidak memerlukan
                    tindak lanjut lagi.
                </p>

                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('pengaduan.list') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-500 px-4 py-2 text-sm font-medium text-white shadow-sm ...">
                        <svg class="w-4 h-4" ... (ikon kembali) ...></svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>
        @endif
    </div>
</x-jiglayout>
