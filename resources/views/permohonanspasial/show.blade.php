<x-jig-layout>
    <div class="px-2 mb-4 space-y-6"> {{-- space-y-6 ditambahkan untuk jarak antar kartu --}}

        {{-- Card 1: Detail Permohonan (Selalu Tampil) --}}
        <div
            class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-800 pb-3 mb-4">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Detail Permohonan #{{ $permohonan->id }}
                </h3>
                <a href="{{ route('permohonanspasial.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg px-3 py-1 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                    </svg>
                    Kembali ke Daftar
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                {{-- Kolom Kiri: Info Pemohon & Surat --}}
                <div class="space-y-4">
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Nama Pemohon:</dt>
                        <dd class="text-gray-900 dark:text-white/90 font-semibold">{{ $permohonan->nama_pemohon }} (NIP:
                            {{ $permohonan->nip ?? '-' }})</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Instansi:</dt>
                        <dd class="text-gray-900 dark:text-white/90">{{ $permohonan->instansi }} (Jabatan:
                            {{ $permohonan->jabatan ?? '-' }})</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Kontak:</dt>
                        <dd class="text-gray-900 dark:text-white/90">{{ $permohonan->email }} | {{ $permohonan->no_hp }}
                        </dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Surat Permohonan:</dt>
                        <dd class="mt-1">
                            <a href="{{ Storage::url($permohonan->file_surat) }}" target="_blank"
                                class="text-brand-500 hover:underline">
                                Download Surat Permohonan
                            </a>
                        </dd>
                    </div>

                    {{-- Tampilkan link BA (TTD) jika ada --}}
                    @if ($permohonan->file_ba_ttd)
                        <div>
                            <dt class="font-medium text-red-500 dark:text-red-400">Berita Acara (TTD) dari Pengguna:
                            </dt>
                            <dd class="mt-1">
                                <a href="{{ Storage::url($permohonan->file_ba_ttd) }}" target="_blank"
                                    class="text-brand-500 hover:underline">
                                    Download Berita Acara (TTD)
                                </a>
                            </dd>
                        </div>
                    @endif
                </div>

                {{-- Kolom Kanan: Info Data IGT --}}
                <div class="space-y-4">
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">Data IGT yang Diminta:</dt>
                        <dd class="mt-2">
                            <ul class="list-disc list-inside space-y-2">
                                @foreach ($permohonan->detailPermohonan as $detail)
                                    <li class="text-gray-900 dark:text-white/90">
                                        <span class="font-medium">{{ $detail->dataIgt->jenis_data ?? 'N/A' }}</span>
                                        <div class="pl-5 text-xs text-gray-600 dark:text-gray-400">
                                            Cakupan: {{ $detail->cakupan_wilayah }}
                                            @if ($detail->keterangan)
                                                | Ket: {{ $detail->keterangan }}
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </dd>
                    </div>
                </div>
            </div>
        </div>

        {{-- 
          =================================================
          CARD 2: FORM AKSI DINAMIS (DIPERBARUI)
          =================================================
        --}}

        @if ($errors->any())
            <div class="mb-4 bg-red-100 ...">
                <strong class="font-bold">Oops! Terjadi kesalahan:</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 1. FORM DISPOSISI (Hanya untuk Admin & jika Pending) --}}
        @if ($permohonan->status == 'Pending' && Auth::user()->hasRole('Admin'))
            <div
                class="rounded-2xl border border-blue-300 bg-blue-50 p-4 shadow-sm sm:p-6 dark:border-blue-700 dark:bg-blue-900/20">
                <h3
                    class="text-base font-medium text-blue-800 dark:text-blue-300 border-b border-blue-300 dark:border-blue-700 pb-3 mb-4">
                    Formulir Disposisi (Kepala Seksi)
                </h3>
                <p class="text-sm text-blue-700 dark:text-blue-200 mb-4">
                    Permohonan ini masih 'Pending'. Silakan pilih Penelaah untuk menindaklanjuti.
                </p>
                <form action="{{ route('permohonanspasial.assign', $permohonan) }}" method="POST">
                    @csrf
                    <label for="penelaah_id" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                        Tugaskan kepada: <span class="text-red-500">*</span>
                    </label>
                    <select id="penelaah_id" name="penelaah_id" required
                        class="mt-1 block w-full md:w-1/2 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Penelaah --</option>
                        @foreach ($daftarPenelaah as $penelaah)
                            <option value="{{ $penelaah->id }}">{{ $penelaah->name }}</option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('penelaah_id')" class="mt-2" />

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700">
                            Tugaskan Sekarang
                        </button>
                    </div>
                </form>
            </div>

            {{-- 2. (DIPERBARUI) KARTU AKSI PENELAAH (Hanya untuk Penelaah & status Diproses) --}}
        @elseif ($permohonan->status == 'Diproses' && $permohonan->penelaah_id == Auth::id())
            <div
                class="rounded-2xl border border-indigo-300 bg-indigo-50 p-4 shadow-sm sm:p-6 dark:border-indigo-700 dark:bg-indigo-900/20">
                <h3
                    class="text-base font-medium text-indigo-800 dark:text-indigo-300 border-b border-indigo-300 dark:border-indigo-700 pb-3 mb-4">
                    Aksi Penelaah
                </h3>
                <p class="text-sm text-indigo-700 dark:text-indigo-200 mb-4">
                    Harap tinjau kesesuaian antara Surat Permohonan dan Data IGT yang diminta dengan nama pemohon,
                    penandatangan berita acara serah terima data adalah pejabat minimal setingkat kepala bidang.
                </p>
                <div class="flex items-center justify-end mt-4 space-x-3">
                    {{-- Tombol Tolak/Revisi (BARU) --}}
                    <button type="button" @click.prevent="$dispatch('open-modal', 'reject-modal')"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-red-700">
                        <i class="fas fa-times"></i>
                        Tolak / Kembalikan (Revisi)
                    </button>

                    {{-- Tombol Lanjutkan ke Editor BA --}}
                    <a href="{{ route('permohonanspasial.showEditorBA', $permohonan) }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                        <i class="fas fa-check"></i>
                        Lanjutkan (Buat/Cek BA)
                    </a>
                </div>
            </div>

            {{-- 3. FORM VERIFIKASI (Staf & Menunggu Verifikasi) --}}
        @elseif (
            $permohonan->status == 'Menunggu Verifikasi Staf' &&
                (Auth::user()->hasRole('Admin') || $permohonan->penelaah_id == Auth::id()))
            <div
                class="rounded-2xl border border-yellow-300 bg-yellow-50 p-4 shadow-sm sm:p-6 dark:border-yellow-700 dark:bg-yellow-900/20">
                <h3
                    class="text-base font-medium text-yellow-800 dark:text-yellow-300 border-b border-yellow-300 dark:border-yellow-700 pb-3 mb-4">
                    Formulir Verifikasi & Upload Data Final
                </h3>

                {{-- Tambahkan paragraf deskripsi jika belum ada --}}
                <p class="text-sm text-yellow-700 dark:text-yellow-200 mb-4">
                    Pengguna telah meng-upload Berita Acara (TTD). Silakan verifikasi file BA (TTD) di atas. Jika valid,
                    upload data final (ZIP/RAR) dan surat balasan (opsional) di bawah ini untuk menyelesaikan
                    permohonan.
                </p>

                <form action="{{ route('permohonanspasial.complete', $permohonan) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    {{-- Tambahkan space-y-4 untuk jarak --}}
                    <div class="space-y-4">

                        {{-- Upload Surat Balasan (Opsional) --}}
                        <div>
                            <label for="file_surat_balasan"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">
                                Upload Surat Balasan (Opsional, PDF)
                            </label>
                            {{-- Style untuk input file --}}
                            <input type="file" name="file_surat_balasan" id="file_surat_balasan"
                                class="block w-full text-sm border rounded-lg text-gray-500 border-gray-300 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600">
                            <x-input-error :messages="$errors->get('file_surat_balasan')" class="mt-2" />
                        </div>

                        {{-- Upload Data Final (Wajib) --}}
                        <div>
                            <label for="file_data_final"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">
                                Upload Data Final (ZIP/RAR) <span class="text-red-500">*</span>
                            </label>
                            {{-- Style untuk input file --}}
                            <input type="file" name="file_data_final" id="file_data_final"
                                class="block w-full text-sm border rounded-lg text-gray-500 border-gray-300 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600"
                                required>
                            <x-input-error :messages="$errors->get('file_data_final')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        {{-- Style untuk tombol submit (menggunakan style Primary Button) --}}
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:ring-offset-gray-800">
                            <i class="fas fa-check-circle"></i>
                            Setujui & Selesaikan Permohonan
                        </button>
                    </div>
                </form>
            </div>

            {{-- 4. TAMPILAN STATUS LAINNYA --}}
        @else
            <div
                class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">
                <h3 ...>Status Permohonan</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Saat ini permohonan berstatus: <strong>{{ $permohonan->status }}</strong>.
                    @if ($permohonan->penelaah)
                        <br>Ditangani oleh: <strong>{{ $permohonan->penelaah->name }}</strong>
                    @elseif($permohonan->status == 'Selesai' && $permohonan->file_paket_final)
                        <br>
                        <a href="{{ Storage::url($permohonan->file_paket_final) }}" ...>
                            Download Paket Data Final (Terkirim)
                        </a>
                    @else
                        <br>Belum ada Penelaah yang ditugaskan.
                    @endif
                </p>
            </div>
        @endif


        {{-- === (BARU) MODAL UNTUK REVISI === --}}
        <x-modal name="reject-modal" :show="$errors->reject->isNotEmpty()" focusable>
            <form method="post" action="{{ route('permohonanspasial.reject', $permohonan) }}" class="p-6">
                @csrf
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    Kembalikan Permohonan untuk Revisi
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Tuliskan alasan penolakan atau apa yang harus diperbaiki oleh pengguna. Catatan ini akan ditampilkan
                    di halaman 'Permohonan Saya' milik pengguna.
                </p>

                <div class="mt-6">
                    <x-input-label for="catatan_revisi" value="Catatan Revisi" class="sr-only" />
                    <textarea id="catatan_revisi" name="catatan_revisi" rows="5"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        placeholder="Contoh: Data IGT yang diminta di formulir tidak sesuai dengan yang terlampir di surat permohonan. Harap perbaiki.">{{ old('catatan_revisi') }}</textarea>
                    {{-- Kita gunakan error bag 'default' karena kita tidak memvalidasi di 'rejectPermohonan' --}}
                    <x-input-error :messages="$errors->get('catatan_revisi')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Batal') }}
                    </x-secondary-button>

                    <x-danger-button class="ml-3">
                        {{ __('Kirim Revisi') }}
                    </x-danger-button>
                </div>
            </form>
        </x-modal>

    </div>
</x-jig-layout>
