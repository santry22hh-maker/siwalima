<x-klarifikasi-layout>
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <style>
            #map {
                height: 600px;
                z-index: 0;
            }

            /* ... (style legenda Anda) ... */
            .legend-control-container {
                background-color: white;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
                border-radius: 5px;
            }

            .legend-toggle-button {
                width: 30px;
                height: 30px;
                line-height: 30px;
                font-size: 1.2rem;
                font-weight: bold;
                text-align: center;
                cursor: pointer;
                background-color: #fff;
                border-radius: 5px;
            }

            .legend-toggle-button:hover {
                background-color: #f4f4f4;
            }

            .legend-content {
                display: none;
                padding: 10px;
                line-height: 1.5;
                font-size: 13px;
                color: #333;
                max-height: 300px;
                overflow-y: auto;
            }

            .legend-content h4 {
                margin: 0 0 5px;
                font-weight: bold;
            }

            .legend-content i {
                width: 18px;
                height: 18px;
                float: left;
                margin-right: 8px;
                opacity: 0.8;
                border: 1px solid #666;
            }

            .legend-control-container.active .legend-content {
                display: block;
            }

            .legend-control-container.active .legend-toggle-button {
                display: none;
            }
        </style>
    @endpush
    {{-- ============================================= --}}
    {{-- KONTEN HALAMAN (dengan x-data untuk modal) --}}
    {{-- ============================================= --}}
    <div x-data="{ rejectModalOpen: false }">

        {{-- Judul Halaman --}}
        <div class="px-2 mb-4">
            <div
                class="flex flex-wrap justify-between items-center gap-4 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
                <div>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">
                        Proses Permohonan
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Kode Pelacakan: <strong>{{ $permohonan->kode_pelacakan }}</strong>
                    </p>
                </div>
                <a href="{{ route('adminklarifikasi.permohonan.index') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        {{-- Pesan Sukses/Error --}}
        <div class="px-2 mb-4">
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                    <p class="font-bold">Oops! Ada yang salah:</p>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        {{-- Konten Halaman: Grid 2 Kolom --}}
        <div class="px-2 mb-4">
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-5">

                <div class="lg:col-span-2 flex flex-col gap-4">

                    @php
                        $user = Auth::user();
                        $status = strtolower($permohonan->status);
                        $isAdmin = $user->hasRole('Admin Klarifikasi');
                        $isPenelaahBertugas = $user->id == $permohonan->penelaah_id;
                    @endphp

                    {{-- 1. TAMPILKAN FORM DISPOSISI (Hanya untuk Admin & jika status 'Diajukan') --}}
                    @if ($isAdmin && $status == 'diajukan')
                        <div
                            class="rounded-lg border border-blue-300 bg-blue-50 dark:border-blue-700 dark:bg-blue-900/30">
                            <div class="px-4 py-3 border-b border-blue-300 dark:border-blue-700">
                                <h4 class="text-base font-medium text-blue-800 dark:text-blue-300">
                                    <i class="fas fa-users-cog mr-2"></i> Disposisi ke Penelaah
                                </h4>
                            </div>
                            <form action="{{ route('adminklarifikasi.permohonan.assign', $permohonan->slug) }}"
                                method="POST">
                                @csrf
                                <div class="p-4 space-y-3">
                                    <label for="penelaah_id"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                        Tugaskan ke Penelaah:
                                    </label>
                                    <select name="penelaah_id" id="penelaah_id" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                        <option value="" disabled selected>-- Pilih Penelaah Klarifikasi --
                                        </option>
                                        @foreach ($penelaahList as $penelaah)
                                            <option value="{{ $penelaah->id }}"
                                                {{ $permohonan->penelaah_id == $penelaah->id ? 'selected' : '' }}>
                                                {{ $penelaah->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="p-4 border-t border-blue-200 dark:border-blue-700 flex gap-2">
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-blue-600 shadow-theme-xs hover:bg-blue-700">
                                        <i class="fas fa-paper-plane"></i> Tugaskan
                                    </button>
                                    {{-- TOMBOL TOLAK --}}
                                    <button type="button" @click="rejectModalOpen = true"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-red-600 shadow-theme-xs hover:bg-red-700">
                                        <i class="fas fa-times-circle"></i> Tolak
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- 2. TAMPILKAN FORM SELESAIKAN (Jika status 'Diproses' DAN Anda adalah Admin atau Penelaah yang ditugaskan) --}}
                    @elseif (($isPenelaahBertugas || $isAdmin) && $status == 'diproses')
                        <div
                            class="rounded-lg border border-green-300 bg-green-50 dark:border-green-700 dark:bg-green-900/30">
                            <div class="px-4 py-3 border-b border-green-300 dark:border-green-700">
                                <h4 class="text-base font-medium text-green-800 dark:text-green-300">
                                    <i class="fas fa-tasks mr-2"></i> Selesaikan Tugas
                                </h4>
                            </div>
                            <form action="{{ route('adminklarifikasi.permohonan.complete', $permohonan->slug) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="p-4 space-y-4">
                                    <div>
                                        <label for="file_surat_balasan"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                            1. Upload Surat Balasan (Wajib PDF)
                                        </label>
                                        <input type="file" name="file_surat_balasan" id="file_surat_balasan" required
                                            accept=".pdf"
                                            class="block w-full text-sm border rounded-lg text-gray-500 border-gray-300 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600">
                                    </div>
                                    <div>
                                        <label for="file_paket_final"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                            2. Upload Paket Data Final (Wajib ZIP)
                                        </label>
                                        <input type="file" name="file_paket_final" id="file_paket_final" required
                                            accept=".zip"
                                            class="block w-full text-sm border rounded-lg text-gray-500 border-gray-300 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-50 file:text-gray-700 hover:file:bg-gray-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600">
                                    </div>
                                    <div>
                                        <label for="catatan_penelaah"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                            3. Catatan (Opsional)
                                        </label>
                                        <textarea name="catatan_penelaah" id="catatan_penelaah" rows="3"
                                            class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                            placeholder="Catatan internal untuk Admin atau Pengguna...">{{ old('catatan_penelaah') }}</textarea>
                                    </div>
                                </div>
                                <div class="p-4 border-t border-green-200 dark:border-green-700 flex gap-2">
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-green-600 shadow-theme-xs hover:bg-green-700">
                                        <i class="fas fa-check-circle"></i> Selesaikan
                                    </button>
                                    {{-- TOMBOL TOLAK --}}
                                    <button type="button" @click="rejectModalOpen = true"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-red-600 shadow-theme-xs hover:bg-red-700">
                                        <i class="fas fa-times-circle"></i> Tolak
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- 3. TAMPILKAN INFO (Jika status Selesai, Ditolak, atau status lain) --}}
                    @else
                        <div class="rounded-lg border border-gray-300 bg-white dark:border-gray-700 dark:bg-gray-800">
                            <div class="px-4 py-3 border-b dark:border-gray-700">
                                <h4 class="text-base font-medium text-gray-800 dark:text-white/90">
                                    <i class="fas fa-info-circle mr-2"></i> Status Permohonan
                                </h4>
                            </div>
                            <div class="p-4 space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                                    <span
                                        class="text-sm font-medium text-gray-800 dark:text-white/90">{{ Str::title($permohonan->status) }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Ditugaskan ke</span>
                                    <span
                                        class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $permohonan->penelaah->name ?? 'N/A' }}</span>
                                </div>
                            </div>

                            {{-- TAMPILKAN TOMBOL BATAL/RE-UPLOAD (Hanya jika Selesai & Punya Izin) --}}
                            @if ($status == 'selesai' && ($isPenelaahBertugas || $isAdmin))
                                <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                                    <form action="{{ route('adminklarifikasi.permohonan.revert', $permohonan->slug) }}"
                                        method="POST"
                                        onsubmit="return confirm('Anda yakin ingin membatalkan hasil ini? File yang sudah diunggah akan dihapus dan status akan dikembalikan ke \'Diproses\'.');">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-red-600 shadow-theme-xs hover:bg-red-700">
                                            <i class="fas fa-undo"></i> Batalkan / Re-upload Hasil
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endif


                    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                        <div class="px-4 py-3 border-b dark:border-gray-700">
                            <h4 class="text-base font-medium text-gray-800 dark:text-white/90">
                                Data Pemohon
                            </h4>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Nama Pemohon</span>
                                <span
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $permohonan->nama_pemohon }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">No. HP</span>
                                <span
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $permohonan->hp_pemohon }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Email</span>
                                <span
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $permohonan->email_pemohon ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal Diajukan</span>
                                <span
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $permohonan->created_at->format('d F Y, H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                        <div class="px-4 py-3 border-b dark:border-gray-700">
                            <h4 class="text-base font-medium text-gray-800 dark:text-white/90">
                                Detail Surat
                            </h4>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Nomor Surat</span>
                                <span
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $permohonan->nomor_surat }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal Surat</span>
                                <span
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">{{ \Carbon\Carbon::parse($permohonan->tanggal_surat)->isoFormat('D MMMM YYYY') }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Perihal</span>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90 mt-1">
                                    {{ $permohonan->perihal_surat ?? ($permohonan->keterangan ?? '-') }}</p>
                            </div>
                        </div>
                        @if ($permohonan->file_surat_path)
                            <div class="p-4 border-t dark:border-gray-700">
                                <a href="{{ Storage::url($permohonan->file_surat_path) }}" target="_blank"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-center text-blue-700 transition rounded-lg bg-blue-50 ring-1 ring-blue-300 hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-300 dark:ring-blue-700 dark:hover:bg-blue-800">
                                    <i class="fas fa-file-pdf"></i> Unduh File Surat (.pdf)
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                        <div class="px-4 py-3 border-b dark:border-gray-700">
                            <h4 class="text-base font-medium text-gray-800 dark:text-white/90">
                                Data Areal
                            </h4>
                        </div>
                        @if ($permohonan->dataSpasial)
                            <div class="p-4 space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Nama Areal/Lokasi</span>
                                    <span
                                        class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $permohonan->dataSpasial->nama_areal ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Kabupaten/Kota</span>
                                    <span
                                        class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $permohonan->dataSpasial->kabupaten ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Estimasi Luas</span>
                                    <span
                                        class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $permohonan->dataSpasial->luas_ha ? number_format($permohonan->dataSpasial->luas_ha, 4, ',', '.') . ' ha' : '0 ha' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-400">Sumber Data</span>
                                    <span
                                        class="text-sm font-medium text-gray-800 dark:text-white/90">{{ Str::title($permohonan->dataSpasial->source_type ?? '-') }}</span>
                                </div>
                            </div>

                            <div class="p-4 border-t dark:border-gray-700 space-y-2">
                                @if ($permohonan->dataSpasial->shapefile_path)
                                    <a href="{{ Storage::url($permohonan->dataSpasial->shapefile_path) }}"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-center text-gray-700 transition rounded-lg bg-gray-50 ring-1 ring-gray-300 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-300 dark:ring-gray-700 dark:hover:bg-gray-800">
                                        <i class="fas fa-file-archive"></i> Unduh Shapefile Asli (.zip)
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="p-4">
                                <p class="text-sm text-gray-500">Data spasial tidak ditemukan.</p>
                            </div>
                        @endif
                    </div>

                </div>

                <div
                    class="lg:col-span-3 flex flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="px-4 py-3 border-b dark:border-gray-700">
                        <h4 class="text-base font-medium text-gray-800 dark:text-white/90">
                            Peta Areal Permohonan
                        </h4>
                    </div>
                    <div class="p-2">
                        <div id="map" class="rounded-lg"
                            data-geojson-url="{{ $permohonan->dataSpasial && $permohonan->dataSpasial->geojson_path ? Storage::url($permohonan->dataSpasial->geojson_path) : '' }}">
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div x-show="rejectModalOpen" x-cloak x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-50 dark:bg-opacity-75">

            <div @click.outside="rejectModalOpen = false" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="w-full max-w-lg bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden mx-4">

                <form action="{{ route('adminklarifikasi.permohonan.reject', $permohonan->slug) }}" method="POST">
                    @csrf
                    <div class="p-6">
                        <div class="flex items-start">
                            <div
                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-300"></i>
                            </div>
                            <div class="ml-4 text-left">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="modal-title">
                                    Tolak Permohonan
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Anda akan menolak permohonan ini. Status akan diubah menjadi "Ditolak" dan
                                        notifikasi akan dikirim ke pengguna.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5">
                            <label for="alasan_penolakan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alasan Penolakan
                                (Wajib)</label>
                            <textarea name="alasan_penolakan" id="alasan_penolakan" rows="4" required minlength="10"
                                class="mt-1 block w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-red-500 focus:ring-red-500 dark:text-white/90"
                                placeholder="Tuliskan alasan yang jelas mengapa permohonan ini ditolak...">{{ old('alasan_penolakan') }}</textarea>
                            <x-input-error :messages="$errors->get('alasan_penolakan')" class="mt-2" />
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                        <button type="submit"
                            class="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 sm:w-auto sm:text-sm">
                            Ya, Tolak Permohonan
                        </button>
                        <button type="button" @click="rejectModalOpen = false"
                            class="inline-flex justify-center w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- AKHIR MODAL --}}

    </div> {{-- Penutup x-data --}}
    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="{{ asset('src/js/map_styles.js') }}"></script>
        <script>
            // ... (Kode JavaScript Peta Anda) ...
            document.addEventListener('DOMContentLoaded', function() {
                const mapElement = document.getElementById('map');
                const geojsonUrl = mapElement.dataset.geojsonUrl;
                const map = L.map('map').setView([-3.69, 128.17], 9);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    crossOrigin: 'Anonymous'
                }).addTo(map);
                const polygonLayer = L.featureGroup().addTo(map);
                let kawasanHutanLayer = null;
                let pl2023Layer = null;
                const baseMaps = {
                    "Peta Jalan": L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap',
                        crossOrigin: 'Anonymous'
                    }),
                    "Citra Satelit": L.tileLayer(
                        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                            attribution: 'Tiles &copy; Esri',
                            crossOrigin: 'Anonymous'
                        }),
                };
                baseMaps["Peta Jalan"].addTo(map);
                const overlayMaps = {
                    "Area Permohonan": polygonLayer
                };
                const layerControl = L.control.layers(baseMaps, overlayMaps, {
                    position: 'topright'
                }).addTo(map);
                if (!geojsonUrl || geojsonUrl.trim() === "") {
                    map.openPopup('<p class="text-center p-2">Data spasial tidak ditemukan.</p>', map.getCenter());
                } else {
                    fetch(geojsonUrl)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Gagal memuat file GeoJSON: ${response.statusText}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            const layer = L.geoJSON(data, {
                                style: {
                                    color: '#007cff',
                                    weight: 3,
                                    opacity: 1.0,
                                    fillColor: '#00bfff',
                                    fillOpacity: 0.5
                                }
                            });
                            polygonLayer.addLayer(layer);
                            map.fitBounds(polygonLayer.getBounds());
                        })
                        .catch(e => {
                            console.error('Gagal memuat GeoJSON:', e);
                            map.openPopup('<p class="text-center p-2 text-red-500">Data spasial rusak.</p>', map
                                .getCenter());
                        });
                }

                function updateLegend() {
                    const legendContentDiv = document.querySelector('.legend-content');
                    if (!legendContentDiv) return;
                    let content = '<h4 style="cursor: pointer;" title="Sembunyikan Legenda">Legenda &#x25BC;</h4>';
                    let legendHasContent = false;
                    if (kawasanHutanLayer && map.hasLayer(kawasanHutanLayer)) {
                        content += '<b>Kawasan Hutan</b><br>';
                        for (const key in kawasanHutanStyles) {
                            content +=
                                `<i style="background:${kawasanHutanStyles[key].color}"></i> ${kawasanHutanStyles[key].label}<br>`;
                        }
                        legendHasContent = true;
                    }
                    if (pl2023Layer && map.hasLayer(pl2023Layer)) {
                        content += legendHasContent ? '<br>' : '';
                        content += '<b>Tutupan Lahan</b><br>';
                        for (const key in pl2023Styles) {
                            content +=
                                `<i style="background:${pl2023Styles[key].color}"></i> ${pl2023Styles[key].label}<br>`;
                        }
                        legendHasContent = true;
                    }
                    legendContentDiv.innerHTML = content;
                    if (legendHasContent) {
                        L.DomEvent.on(legendContentDiv.querySelector('h4'), 'click', e => L.DomEvent.stop(e) && L
                            .DomUtil.removeClass(legendContentDiv.parentElement, 'active'));
                    }
                }
                fetch("{{ asset('DataDasar/KwsHutan_Maluku250.geojson') }}").then(r => r.json()).then(data => {
                    kawasanHutanLayer = L.geoJSON(data, {
                        style: styleKawasanHutan,
                        onEachFeature: (feature, layer) => {
                            if (feature.properties?.FUNGSIKWS) {
                                layer.bindPopup(
                                    `<b>Kawasan Hutan:</b> ${feature.properties.FUNGSIKWS}`);
                            }
                        }
                    });
                    layerControl.addOverlay(kawasanHutanLayer, "Kawasan Hutan Maluku");
                });
                fetch("{{ asset('DataDasar/Pl2023_Maluku250.geojson') }}").then(r => r.json()).then(data => {
                    pl2023Layer = L.geoJSON(data, {
                        style: stylePL2023,
                        onEachFeature: (feature, layer) => {
                            if (feature.properties?.PL2023_ID) {
                                layer.bindPopup(
                                    `<b>Tutupan Lahan:</b> ${feature.properties.PL2023_ID}`);
                            }
                        }
                    });
                    layerControl.addOverlay(pl2023Layer, "Tutupan Lahan 2023");
                });
                const legend = L.control({
                    position: 'bottomright'
                });
                legend.onAdd = function(map) {
                    const container = L.DomUtil.create('div',
                        'leaflet-control leaflet-bar legend-control-container');
                    const button = L.DomUtil.create('a', 'legend-toggle-button', container);
                    button.innerHTML = 'i';
                    button.href = '#';
                    button.title = 'Tampilkan Legenda';
                    L.DomUtil.create('div', 'legend-content', container);
                    L.DomEvent.on(button, 'click', e => {
                        L.DomEvent.stop(e);
                        L.DomUtil.addClass(container, 'active');
                        updateLegend();
                    });
                    L.DomEvent.disableClickPropagation(container);
                    return container;
                };
                legend.addTo(map);
                map.on('overlayadd overlayremove', updateLegend);
                setTimeout(() => {
                    map.invalidateSize();
                }, 500);
            });
        </script>
    @endpush
</x-klarifikasi-layout>
