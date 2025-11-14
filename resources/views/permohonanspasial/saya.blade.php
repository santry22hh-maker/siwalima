<x-jig-layout>
    <div class="px-2 mb-4">
        <div
            class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">

            {{-- Header Card --}}
            <div
                class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 pb-3 mb-4 dark:border-gray-800">
                <div>
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        Riwayat Permohonan Saya
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Pantau status, unduh Berita Acara, dan unggah berkas TTD Anda di halaman ini.
                    </p>
                </div>
            </div>

            {{-- Tampilkan Pesan Sukses/Error --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                    role="alert">
                    <strong class="font-bold">Oops! Terjadi kesalahan upload:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Daftar Permohonan --}}
            <div class="space-y-2">
                @forelse ($permohonans as $permohonan)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden">
                        {{-- Header Card: Status & Tanggal --}}
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:px-5 sm:py-4">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                {{-- Status --}}
                                <div>
                                    @if ($permohonan->status == 'Menunggu TTD Pengguna')
                                        <span
                                            class="inline-flex items-center rounded-full bg-blue-600 px-3 py-1 text-sm font-medium text-white shadow-sm dark:bg-blue-500">
                                            Menunggu TTD Pengguna
                                        </span>
                                    @elseif ($permohonan->status == 'Menunggu Verifikasi Staf')
                                        <span
                                            class="inline-flex items-center rounded-full bg-yellow-500 px-3 py-1 text-sm font-medium text-white shadow-sm dark:bg-yellow-400 dark:text-gray-900">
                                            Menunggu Verifikasi Staf
                                        </span>
                                    @elseif ($permohonan->status == 'Selesai')
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-600 px-3 py-1 text-sm font-medium text-white shadow-sm dark:bg-green-500">
                                            Selesai
                                        </span>
                                    @else
                                        {{-- Pending, Diproses, Ditolak, dll --}}
                                        <span
                                            class="inline-flex items-center rounded-full bg-gray-500 px-3 py-1 text-sm font-medium text-white shadow-sm dark:bg-gray-400 dark:text-gray-900">
                                            {{ $permohonan->status }}
                                        </span>
                                    @endif
                                </div>
                                {{-- Tanggal --}}
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <time datetime="{{ $permohonan->created_at->toIso8601String() }}">
                                        Diajukan pada: {{ $permohonan->created_at->format('d F Y, H:i') }}
                                    </time>
                                </div>
                            </div>
                        </div>

                        {{-- Body Card: Detail & Aksi --}}
                        <div class="p-4 sm:p-5 grid grid-cols-1 lg:grid-cols-2 gap-6">

                            {{-- Kolom Kiri: Detail Permohonan --}}
                            <div class="space-y-2">

                                {{-- === BLOK BARU 1: NAMA PEMOHON === --}}
                                <div>
                                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Nama
                                        Pemohon:</label>
                                    <p class="text-sm text-gray-800 dark:text-white/90 mt-1">
                                        {{ $permohonan->nama_pemohon }}
                                    </p>
                                </div>

                                {{-- === BLOK BARU 2: INSTANSI === --}}
                                <div>
                                    <label
                                        class="text-sm font-semibold text-gray-700 dark:text-gray-300">Instansi:</label>
                                    <p class="text-sm text-gray-800 dark:text-white/90 mt-1">
                                        {{ $permohonan->instansi }}
                                    </p>
                                </div>

                                {{-- Nomor Surat (Sudah ada) --}}
                                <div>
                                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Nomor
                                        Surat:</label>
                                    <p class="text-sm text-gray-800 dark:text-white/90 mt-1">
                                        {{ $permohonan->nomor_surat }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">File Surat
                                        (Asli)
                                        :</label>
                                    <p class="mt-1">
                                        <a href="{{ Storage::url($permohonan->file_surat) }}" target="_blank"
                                            class="text-sm text-brand-500 hover:underline">
                                            Download Surat Permohonan.pdf
                                        </a>
                                    </p>
                                </div>

                                {{-- Daftar IGT yang Diminta --}}
                                <div>
                                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">Data IGT yang
                                        Diminta:</label>
                                    <ul class="list-disc list-inside mt-1 space-y-1 text-sm">
                                        @foreach ($permohonan->detailPermohonan as $detail)
                                            <li class="text-gray-800 dark:text-white/90">
                                                {{ $detail->dataIgt->jenis_data ?? 'Data tidak ditemukan' }}
                                                <span class="text-gray-500 dark:text-gray-400">
                                                    (Cakupan: {{ $detail->cakupan_wilayah }})
                                                    @if ($detail->keterangan)
                                                        (Ket: {{ $detail->keterangan }})
                                                    @endif
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            {{-- Kolom Kanan: Aksi --}}
                            <div
                                class="border-t lg:border-t-0 lg:border-l border-gray-200 dark:border-gray-700 pt-4 lg:pt-0 lg:pl-6">

                                {{-- Tampilkan aksi berdasarkan status --}}
                                @if ($permohonan->status == 'Menunggu TTD Pengguna')
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-gray-800 dark:text-white/90">Aksi Diperlukan</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Silakan unduh Berita Acara (BA), tandatangani, lalu unggah kembali.
                                        </p>
                                        {{-- Tombol Download BA (Template) --}}
                                        <a href="{{ route('permohonanspasial.generateBA', $permohonan) }}"
                                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700 w-full justify-center">
                                            <i class="fas fa-download"></i>
                                            1. Buat & Download Berita Acara
                                        </a>

                                        {{-- Form Upload BA (TTD) --}}
                                        <form action="{{ route('permohonanspasial.uploadBaTtd', $permohonan) }}"
                                            method="POST" enctype="multipart/form-data"
                                            class="pt-3 border-t dark:border-gray-700">
                                            @csrf
                                            <label for="file_ba_ttd_{{ $permohonan->id }}"
                                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                                2. Upload Berita Acara (TTD)
                                            </label>
                                            <input type="file" name="file_ba_ttd"
                                                id="file_ba_ttd_{{ $permohonan->id }}"
                                                class="block w-full text-sm border rounded-lg text-gray-500 border-gray-300 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600"
                                                required>
                                            <button type="submit"
                                                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-green-700 w-full justify-center mt-3">
                                                <i class="fas fa-upload"></i>
                                                Upload File
                                            </button>
                                        </form>
                                    </div>

                                    {{-- 2. Status: Menunggu Verifikasi Staf --}}
                                @elseif ($permohonan->status == 'Menunggu Verifikasi Staf')
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-gray-800 dark:text-white/90">Menunggu Verifikasi
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Berita Acara (TTD) Anda telah terkirim. Staf akan segera memverifikasi dan
                                            meng-upload data final Anda.
                                        </p>
                                        <a href="{{ Storage::url($permohonan->file_ba_ttd) }}" target="_blank"
                                            class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 shadow-sm w-full justify-center dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                                            Lihat BA (TTD) yang Diupload
                                        </a>
                                    </div>
                                @elseif ($permohonan->status == 'Revisi')
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-red-600 dark:text-red-400">Permohonan Ditolak
                                            (Perlu Revisi)</h4>

                                        {{-- Tampilkan Catatan dari Penelaah --}}
                                        <div
                                            class="p-3 rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700/30">
                                            <p class="text-sm font-semibold text-red-800 dark:text-red-300">
                                                <i class="fas fa-exclamation-triangle mr-1"></i> Catatan dari Penelaah:
                                            </p>
                                            <p class="text-sm text-red-700 dark:text-red-200 mt-2 whitespace-pre-wrap">
                                                {{ $permohonan->catatan_revisi ?? 'Tidak ada catatan.' }}
                                            </p>
                                        </div>

                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Silakan perbaiki data permohonan Anda dan kirim ulang.
                                        </p>

                                        {{-- Tombol untuk Edit --}}
                                        <a href="{{ route('permohonanspasial.revisi.edit', $permohonan) }}"
                                            class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-yellow-600 w-full justify-center">
                                            <i class="fas fa-edit"></i>
                                            Perbaiki Permohonan
                                        </a>
                                    </div>
                                    {{-- 3. Status: Selesai --}}
                                @elseif ($permohonan->status == 'Selesai')
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-green-600 dark:text-green-400">Permohonan Selesai
                                        </h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Permohonan Anda telah disetujui. Silakan unduh berkas Anda.
                                        </p>

                                        @if ($permohonan->survey)
                                            <div
                                                class="inline-flex items-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500 w-full justify-center dark:bg-gray-700 dark:text-gray-400">
                                                <i class="fas fa-check-circle"></i>
                                                Anda sudah mengisi survei
                                            </div>
                                        @else
                                            <div
                                                class="p-3 rounded-md bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700/30">
                                                <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-300">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i> Tindakan Diperlukan
                                                </p>
                                                <p class="text-sm text-yellow-700 dark:text-yellow-200 mt-1">
                                                    Harap isi survei kepuasan untuk permohonan ini agar Anda tidak
                                                    diblokir untuk permohonan berikutnya.
                                                </p>
                                            </div>
                                            <a href="{{ route('survey.index', ['permohonan_id' => $permohonan->id]) }}"
                                                class="inline-flex items-center gap-2 rounded-lg bg-yellow-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-yellow-600 w-full justify-center">
                                                <i class="fas fa-star"></i>
                                                Isi Survey Kepuasan (Wajib)
                                            </a>
                                        @endif

                                        <a href="{{ Storage::url($permohonan->file_paket_final) }}"
                                            class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-green-700">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                            Download Paket Data (ZIP)
                                        </a>
                                    </div>
                                    {{-- 4. Status: Lainnya (Dibatalkan, Ditolak, dll) --}}
                                @else
                                    <div class="space-y-3">
                                        <h4 class="font-semibold text-gray-800 dark:text-white/90">Status</h4>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Permohonan Anda sedang dalam status:
                                            <strong>{{ $permohonan->status }}</strong>.
                                        </p>
                                    </div>
                                @endif

                            </div>

                        </div>
                    </div>
                @empty
                    {{-- Tampilan jika belum ada permohonan --}}
                    <div class="text-center text-gray-500 dark:text-gray-400 py-10 px-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white/90">Belum Ada Permohonan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum pernah mengajukan
                            permohonan
                            data.</p>
                        <div class="mt-6">
                            <a href="{{ route('daftarigt.index') }}" {{-- Arahkan ke KATALOG --}}
                                class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-sm ring-1 ring-inset ring-brand-500 transition hover:bg-brand-600 dark:bg-brand-500 dark:text-white dark:ring-brand-500 dark:hover:bg-brand-600">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                Buat Permohonan Baru
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Link Paginasi --}}
            @if ($permohonans->hasPages())
                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                    {{ $permohonans->links() }}
                </div>
            @endif

        </div>
    </div>
</x-jig-layout>
