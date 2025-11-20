<x-klarifikasi-layout>
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />
        <style>
            #map-wrapper {
                min-height: 700px;
            }

            #preview-map {
                z-index: 0;
            }

            /* ... style legenda ... */
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

            /* STYLING TAB AKTIF */
            .tab-item-active {
                border-color: #22c55e;
                color: #16a34a;
                font-weight: 600;
            }

            /* Style kustom untuk file input */
            input[type="file"]::file-selector-button {
                margin-right: 1rem;
                padding: 0.5rem 1rem;
                font-weight: 600;
                font-size: 0.75rem;
                color: #374151;
                background-color: #f3f4f6;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                cursor: pointer;
                transition: background-color 0.2s ease-in-out;
            }

            input[type="file"]::file-selector-button:hover {
                background-color: #e5e7eb;
            }

            .dark input[type="file"]::file-selector-button {
                color: #e5e7eb;
                background-color: #374151;
                border-color: #4b5563;
            }

            .dark input[type="file"]::file-selector-button:hover {
                background-color: #4b5563;
            }
        </style>
    @endpush
    <div class="px-2 mb-2">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
            <h3 class="text-xl font-medium text-gray-800 dark:text-white/90">
                Edit Permohonan Analisis Resmi
            </h3>
            <p class="border-gray-200 text-sm text-gray-800 dark:border-gray-800 dark:text-white/90 mt-1">
                Perbarui data permohonan Anda dan ajukan kembali.
            </p>
        </div>
    </div>

    {{-- Mulai Input --}}
    <div class="px-2 mb-4">
        <div class="grid grid-cols-1 gap-2 sm:gap-2 lg:grid-cols-7">
            <div
                class="lg:col-span-3 flex flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="space-y-6  border-gray-100 p-4 sm:p-6 dark:border-gray-800">

                    @if (strtolower($permohonan->status) == 'ditolak')
                        <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg"
                            role="alert">
                            <p class="font-bold"><i class="fas fa-exclamation-triangle mr-2"></i> Permohonan Anda
                                Ditolak</p>
                            <p class="mt-1">Permohonan Anda sebelumnya ditolak. Harap perbaiki data Anda berdasarkan
                                alasan berikut dan ajukan kembali.</p>
                            <div class="mt-3 p-3 bg-white border border-red-300 rounded-md">
                                <p class="text-sm font-medium">Alasan dari Petugas:</p>
                                <p class="text-sm italic text-gray-700 mt-1">
                                    {{ $permohonan->catatan_penelaah ?? 'Tidak ada alasan spesifik yang diberikan.' }}
                                </p>
                            </div>
                        </div>
                    @endif
                    {{-- Menampilkan error validasi Laravel --}}
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Data Gagal Disimpan:</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Error:</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- PERBAIKAN: Form action ke 'update' dan @method('PUT') --}}
                    <form id="input-form" action="{{ route('permohonananalisis.update', $permohonan->slug) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <input type="hidden" name="source_type" id="source_type"
                            value="{{ old('source_type', $permohonan->dataSpasial->source_type ?? 'photo') }}">
                        <input type="hidden" name="geojson_data" id="geojson_data"
                            value="{{ old('geojson_data', $usulanGeoJson ?? '') }}">
                        <input type="hidden" name="luas_ha" id="luas_ha"
                            value="{{ old('luas_ha', $permohonan->dataSpasial->luas_ha ?? 0) }}">

                        <h4
                            class="text-lg font-medium text-gray-800 dark:text-white/90 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                            1. Data Pemohon
                        </h4>

                        <div>
                            <label for="nama_pemohon"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Nama
                                Pemohon</label>
                            <input type="text" name="nama_pemohon" id="nama_pemohon" required
                                value="{{ old('nama_pemohon', $permohonan->nama_pemohon) }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Nama Lengkap Sesuai KTP">
                            <x-input-error :messages="$errors->get('nama_pemohon')" class="mt-2" />
                        </div>

                        <div class="mt-3">
                            <label for="hp_pemohon"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">No. HP/Telepon
                                (WhatsApp)</label>
                            <input type="text" name="hp_pemohon" id="hp_pemohon" required
                                value="{{ old('hp_pemohon', $permohonan->hp_pemohon) }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="08xxxxxxxxxx">
                            <x-input-error :messages="$errors->get('hp_pemohon')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                            <label for="email_pemohon"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Alamat
                                Email</label>
                            <input type="email" name="email_pemohon" id="email_pemohon"
                                value="{{ old('email_pemohon', $permohonan->email_pemohon) }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="email@anda.com">
                            <x-input-error :messages="$errors->get('email_pemohon')" class="mt-2" />
                        </div>

                        <hr class="my-4 border-gray-200 dark:border-gray-700">

                        <h4
                            class="text-lg font-medium text-gray-800 dark:text-white/90 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                            2. Data Surat Permohonan
                        </h4>

                        <div class="mt-3">
                            <label for="nomor_surat"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Nomor
                                Surat</label>
                            <input type="text" name="nomor_surat" id="nomor_surat" required
                                value="{{ old('nomor_surat', $permohonan->nomor_surat) }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Contoh: 01/ABC/II/2025">
                            <x-input-error :messages="$errors->get('nomor_surat')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                            <label for="tanggal_surat"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Tanggal
                                Surat</label>
                            <input type="date" name="tanggal_surat" id="tanggal_surat" required
                                value="{{ old('tanggal_surat', $permohonan->tanggal_surat) }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            <x-input-error :messages="$errors->get('tanggal_surat')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                            <label for="perihal_surat"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Perihal</label>
                            <textarea name="perihal_surat" id="perihal_surat" rows="2"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Contoh: Permohonan Analisis Status Kawasan Hutan...">{{ old('perihal_surat', $permohonan->perihal_surat) }}</textarea>
                            <x-input-error :messages="$errors->get('perihal_surat')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                            <label for="file_surat"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Upload File
                                Surat (Wajib PDF)</label>
                            <input type="file" name="file_surat" id="file_surat" accept=".pdf"
                                class="block w-full text-sm border rounded-lg text-gray-500 border-gray-300 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Kosongkan jika tidak ingin mengubah
                                file surat.</span>
                            <x-input-error :messages="$errors->get('file_surat')" class="mt-2" />
                        </div>

                        <hr class="my-4 border-gray-200 dark:border-gray-700">
                        <h4
                            class="text-lg font-medium text-gray-800 dark:text-white/90 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                            3. Data Spasial
                        </h4>

                        <div>
                            <label for="lokasi"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Lokasi/Nama
                                Areal</label>
                            <input type="text" name="lokasi" id="lokasi" required
                                value="{{ old('lokasi', $permohonan->dataSpasial->nama_areal ?? '') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Contoh: Areal Tambang Desa Sukamaju">
                            <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                            <label for="kabupaten"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400">Kabupaten</label>
                            <input type="text" name="kabupaten" id="kabupaten" required
                                value="{{ old('kabupaten', $permohonan->dataSpasial->kabupaten ?? '') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Contoh: Kabupaten Cianjur">
                            <x-input-error :messages="$errors->get('kabupaten')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                            <label for="keterangan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Keterangan
                                (Tujuan Permohonan)</label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Contoh: Untuk kelengkapan Izin Pertambangan...">{{ old('keterangan', $permohonan->keterangan ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>

                        {{-- Area Tab Input (Foto, SHP, Manual) --}}
                        <div id="spasial-input-tabs"
                            class="rounded-lg border border-gray-200 px-4 dark:border-gray-800 mt-3"
                            x-data="{ tab: '{{ old('source_type', $permohonan->dataSpasial->source_type ?? 'photo') }}' }">

                            <div class="border-b border-gray-200 dark:border-gray-800 mt-2">
                                <nav class="-mb-px flex space-x-2" aria-label="Tabs">
                                    <button type="button"
                                        @click="tab = 'photo'; document.getElementById('source_type').value = 'photo';"
                                        :class="(tab === 'photo') ? 'tab-item-active' : 'menu-item-inactive'"
                                        class="inline-flex items-center gap-2 border-b-2 px-2.5 py-2 text-sm font-medium transition-colors duration-200 ease-in-out">
                                        <i class="fas fa-qrcode"></i> Foto Geotag
                                    </button>
                                    <button type="button"
                                        @click="tab = 'shapefile'; document.getElementById('source_type').value = 'shapefile';"
                                        :class="(tab === 'shapefile') ? 'tab-item-active' : 'menu-item-inactive'"
                                        class="inline-flex items-center gap-2 border-b-2 px-2.5 py-2 text-sm font-medium transition-colors duration-200 ease-in-out">
                                        <i class="fas fa-shapes"></i> Shapefile
                                    </button>
                                    <button type="button"
                                        @click="tab = 'manual'; document.getElementById('source_type').value = 'manual';"
                                        :class="(tab === 'manual') ? 'tab-item-active' : 'menu-item-inactive'"
                                        class="inline-flex items-center gap-2 border-b-2 px-2.5 py-2 text-sm font-medium transition-colors duration-200 ease-in-out">
                                        <i class="fas fa-diagnoses"></i> Manual
                                    </button>
                                </nav>
                            </div>
                            <div class="pt-4 dark:border-gray-800">
                                {{-- Tab Panel 1: Foto Geotag --}}
                                <div x-show="tab === 'photo'" class="space-y-4 dark:border-gray-800"
                                    style="display: none;">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Kosongkan jika tidak ingin
                                        mengubah data spasial.</p>
                                    <div class="pt-2">
                                        <label
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-400">Upload
                                            Foto</label>
                                        <div
                                            class="mt-1 flex flex-col items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8"
                                                    stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <div class="text-sm text-gray-600 mt-2 dark:text-gray-400">
                                                <label for="photo-upload"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500 dark:text-gray-400">
                                                    <span>Pilih beberapa file</span>
                                                    <input id="photo-upload" name="photos[]" type="file"
                                                        class="sr-only" multiple accept="image/jpeg">
                                                </label>
                                                <span class="pl-1">atau seret dan lepas</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Tab Panel 2: Shapefile --}}
                                <div x-show="tab === 'shapefile'" class="pb-4" style="display: none;">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Kosongkan jika tidak ingin
                                        mengubah data spasial.</p>
                                    <div class="py-2">
                                        <label
                                            class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">Upload
                                            File .ZIP</label>
                                        <div
                                            class="mt-1 flex flex-col items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-400"
                                                stroke="currentColor" fill="none" viewBox="0 0 48 48"
                                                aria-hidden="true">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8"
                                                    stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <div class="text-sm text-gray-600 px-2 py-4">
                                                <label for="shapefile-upload"
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                                    <span>Pilih sebuah file</span>
                                                    <input id="shapefile-upload" name="shapefile_input"
                                                        type="file" class="sr-only" accept=".zip">
                                                </label>
                                                <span class="pl-1 dark:text-gray-400">atau seret dan lepas</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Tab Panel 3: Manual --}}
                                <div x-show="tab === 'manual'" class="space-y-2" style="display: none;">
                                    <div class="py-2">
                                        <label for="manual-coords"
                                            class="block text-sm font-medium text-gray-700 mb-1 dark:text-white/90">Koordinat</label>
                                        <textarea id="manual-coords" rows="8"
                                            class="p-2 dark:text-white/90 font-normal text-sm w-full border bottom-1 border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500"
                                            placeholder="Masukkan daftar koordinat, satu per baris.&#10;Format: latitude, longitude&#10;-6.2088, 106.8456&#10;-6.2188, 106.8556&#10;-6.2088, 106.8656"></textarea>
                                        <button type="button" id="btn-preview-manual"
                                            class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-50 dark:text-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500"
                                            style="margin-bottom:8px">Tampilkan
                                            Pratinjau</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="js-error-box"
                            class="hidden w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative my-3"
                            role="alert">
                            <strong class="font-bold">Error: </strong>
                            <span class="block sm:inline" id="js-error-message"></span>
                        </div>

                        <div class="pt-5 border-t border-gray-200 flex items-center gap-4">
                            <button type="submit" id="btn-submit"
                                class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-black bg-green-600 hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                <span id="btn-submit-text">Simpan Perubahan & Ajukan Ulang</span>
                            </button>
                            <button type="button" id="btn-clear-map"
                                class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-50 dark:text-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500">
                                Bersihkan Peta
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Kolom Kanan: Peta dan Info Box --}}
            <div
                class="lg:col-span-4 flex flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div id="file-info-box"
                    class="bg-white border border-gray-300 rounded-lg p-3 mb-4 text-sm space-y-1  dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="flex">
                        <p class="font-semibold text-gray-800 w-28 flex-shrink-0 dark:text-white/90">File dipilih :</p>
                        <p id="file-info-content" class="text-gray-500 italic break-words ml-2 dark:text-white/90">
                            Data spasial sudah ada</p>
                    </div>
                    <div class="flex">
                        <p class="font-semibold text-gray-800 w-28 flex-shrink-0 dark:text-white/90">Estimasi Luas :
                        </p>
                        <p id="luas-info" class="text-gray-500 italic font-medium ml-2 dark:text-white/90">-</p>
                    </div>
                </div>

                <div id="map-wrapper" class="flex-grow rounded-lg overflow-hidden">
                    <div id="preview-map" class="h-full w-full" data-usulan-geojson="{!! htmlspecialchars($usulanGeoJson ?? '', ENT_QUOTES, 'UTF-8') !!}">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://unpkg.com/shpjs@3.6.0/dist/shp.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
        <script src="{{ asset('src/js/map_styles.js') }}"></script>

        <script>
            // Salin SEMUA JavaScript dari 'create.blade.php' Anda ke sini
            // (Kode yang Anda berikan di 20:47:11)
            let usulanData = null;

            document.addEventListener('DOMContentLoaded', function() {

                const previewMap = L.map('preview-map').setView([-3.69, 128.17], 9);
                const shapefileUpload = document.getElementById('shapefile-upload');
                const photoUpload = document.getElementById('photo-upload');
                const fileInfoContent = document.getElementById('file-info-content');
                const manualCoords = document.getElementById('manual-coords');
                const btnPreviewManual = document.getElementById('btn-preview-manual');
                const btnSubmit = document.getElementById('btn-submit');
                const geojsonField = document.getElementById('geojson_data');
                const btnClearMap = document.getElementById(
                    'btn-clear-map'); // <-- PERBAIKAN 2: Ini sekarang akan ditemukan
                const luasInfo = document.getElementById('luas-info');

                let kawasanHutanLayer = null;
                let pl2023Layer = null;

                const jsErrorBox = document.getElementById('js-error-box');
                const jsErrorMessage = document.getElementById('js-error-message');
                const btnSubmitText = document.getElementById('btn-submit-text');

                // Setup Basemap & Layer Control
                const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                });
                const satellite = L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Tiles &copy; Esri'
                    });
                const openTopo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
                    maxZoom: 17,
                    attribution: 'Map data: &copy; OpenStreetMap contributors'
                });
                osm.addTo(previewMap);

                const baseMaps = {
                    "Peta Jalan": osm,
                    "Citra Satelit": satellite,
                    "Topografi": openTopo
                };
                const drawnItems = new L.FeatureGroup().addTo(previewMap);
                const overlayMaps = {
                    "Area Usulan": drawnItems
                };
                const layerControl = L.control.layers(baseMaps, overlayMaps, {
                    position: 'topright'
                }).addTo(previewMap);

                // Fungsi Legenda
                function updateLegend() {
                    const legendContentDiv = document.querySelector('.legend-content');
                    if (!legendContentDiv) return;
                    let content = '<h4 style="cursor: pointer;" title="Sembunyikan Legenda">Legenda &#x25BC;</h4>';
                    let legendHasContent = false;
                    if (kawasanHutanLayer && previewMap.hasLayer(kawasanHutanLayer)) {
                        content += '<b>Kawasan Hutan</b><br>';
                        for (const key in kawasanHutanStyles) {
                            content +=
                                `<i style="background:${kawasanHutanStyles[key].color}"></i> ${kawasanHutanStyles[key].label}<br>`;
                        }
                        legendHasContent = true;
                    }
                    if (pl2023Layer && previewMap.hasLayer(pl2023Layer)) {
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

                // Pemuatan Layer Overlay
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

                // Kontrol Legenda
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
                legend.addTo(previewMap);
                previewMap.on('overlayadd overlayremove', updateLegend);

                // Setup Leaflet Draw
                const drawControl = new L.Control.Draw({
                    edit: {
                        featureGroup: drawnItems
                    },
                    draw: {
                        polygon: {
                            allowIntersection: false,
                            showArea: true
                        },
                        polyline: false,
                        rectangle: true,
                        circle: false,
                        marker: false
                    }
                });
                previewMap.addControl(drawControl);

                // Fungsi helper UI
                function showError(message) {
                    if (jsErrorMessage) jsErrorMessage.textContent = message;
                    if (jsErrorBox) jsErrorBox.classList.remove('hidden');
                }

                function hideError() {
                    if (jsErrorMessage) jsErrorMessage.textContent = '';
                    if (jsErrorBox) jsErrorBox.classList.add('hidden');
                }

                function setLoading(isLoading) {
                    if (isLoading) {
                        btnSubmit.disabled = true;
                        if (btnSubmitText) btnSubmitText.textContent = 'Sedang Memproses...';
                        shapefileUpload.disabled = true;
                        photoUpload.disabled = true;
                        manualCoords.disabled = true;
                        btnPreviewManual.disabled = true;
                        btnClearMap.disabled = true;
                    } else {
                        if (btnSubmitText) btnSubmitText.textContent =
                            'Simpan Perubahan & Ajukan Ulang'; // Teks Tombol Edit
                        shapefileUpload.disabled = false;
                        photoUpload.disabled = false;
                        manualCoords.disabled = false;
                        btnPreviewManual.disabled = false;
                        btnClearMap.disabled = false;
                    }
                }

                // Fungsi Hitung Luas
                function calculateAndDisplayArea(geojsonFeature) {
                    if (!geojsonFeature) {
                        luasInfo.innerHTML = '-';
                        luasInfo.classList.add('text-gray-500', 'italic');
                        return;
                    }
                    try {
                        const areaInMeters = turf.area(geojsonFeature);
                        const areaInHectares = areaInMeters / 10000;
                        const formattedMeters = areaInMeters.toLocaleString('id-ID', {
                            maximumFractionDigits: 2
                        });
                        const formattedHectares = areaInHectares.toLocaleString('id-ID', {
                            maximumFractionDigits: 4
                        });
                        luasInfo.innerHTML = `<strong>${formattedHectares} ha</strong> (${formattedMeters} m²)`;
                        luasInfo.classList.remove('text-gray-500', 'italic');
                        document.getElementById('luas_ha').value = areaInHectares;
                    } catch (e) {
                        luasInfo.innerHTML = '<span class="text-red-500">Gagal menghitung luas.</span>';
                        luasInfo.classList.remove('text-gray-500', 'italic');
                    }
                }

                function updateGeoJsonInput(layer) {
                    const geojson = layer.toGeoJSON();
                    geojsonField.value = JSON.stringify(geojson.geometry);
                    btnSubmit.disabled = false;
                }

                previewMap.on(L.Draw.Event.CREATED, e => {
                    hideError();
                    drawnItems.clearLayers();
                    drawnItems.addLayer(e.layer);
                    updateGeoJsonInput(e.layer);
                    fileInfoContent.textContent = 'Poligon digambar dari peta';
                    fileInfoContent.classList.remove('text-gray-500', 'italic');
                    calculateAndDisplayArea(e.layer.toGeoJSON());
                });
                previewMap.on(L.Draw.Event.EDITED, e => e.layers.eachLayer(layer => {
                    updateGeoJsonInput(layer);
                    calculateAndDisplayArea(layer.toGeoJSON());
                }));
                previewMap.on(L.Draw.Event.DELETED, () => {
                    if (drawnItems.getLayers().length === 0) {
                        geojsonField.value = '';
                        btnSubmit.disabled = true;
                    }
                });

                // Fungsi Bersihkan Peta
                function clearPreview() {
                    hideError();
                    drawnItems.clearLayers();
                    geojsonField.value = '';
                    btnSubmit.disabled = true;
                    shapefileUpload.value = '';
                    photoUpload.value = '';
                    manualCoords.value = '';
                    fileInfoContent.textContent = 'Belum ada file';
                    fileInfoContent.classList.add('text-gray-500', 'italic');
                    luasInfo.innerHTML = '-';
                    luasInfo.classList.add('text-gray-500', 'italic');
                    document.getElementById('source_type').value = 'photo';
                    document.getElementById('luas_ha').value = 0;

                    // Tampilkan kembali tab input
                    const spasialInputTabs = document.getElementById('spasial-input-tabs');
                    if (spasialInputTabs) spasialInputTabs.style.display = 'block';

                    const prefillNotification = document.getElementById('prefill-notification');
                    if (prefillNotification) {
                        prefillNotification.style.display = 'none';
                    }
                    // Kita tidak reset data form (nama, surat, dll) saat clear peta
                }

                function updatePreview(geojson) {
                    drawnItems.clearLayers();
                    if (!geojson || (geojson.type === 'FeatureCollection' && geojson.features.length === 0)) {
                        showError('File tidak mengandung feature yang dapat ditampilkan.');
                        return;
                    }
                    let combinedFeature = null;
                    if (geojson.type === 'FeatureCollection') {
                        const polygons = geojson.features.filter(f => f.geometry && (f.geometry.type === 'Polygon' || f
                            .geometry.type === 'MultiPolygon'));
                        if (polygons.length === 0) {
                            showError('Shapefile tidak mengandung feature Polygon atau MultiPolygon.');
                            return;
                        }
                        const allCoords = polygons.flatMap(p => p.geometry.type === 'Polygon' ? [p.geometry
                            .coordinates
                        ] : p.geometry.coordinates);
                        combinedFeature = {
                            type: 'Feature',
                            properties: polygons[0].properties,
                            geometry: {
                                type: 'MultiPolygon',
                                coordinates: allCoords
                            }
                        };
                    } else if (geojson.geometry && (geojson.geometry.type === 'Polygon' || geojson.geometry.type ===
                            'MultiPolygon')) {
                        combinedFeature = geojson.type === 'Feature' ? geojson : {
                            type: 'Feature',
                            properties: {},
                            geometry: geojson
                        };
                    } else {
                        showError('Geometri yang didukung hanya Polygon atau MultiPolygon.');
                        return;
                    }
                    calculateAndDisplayArea(combinedFeature);
                    const previewLayer = L.geoJSON(combinedFeature, {
                        style: {
                            color: '#22c55e',
                            weight: 3,
                            opacity: 0.8,
                            fillColor: '#86efac',
                            fillOpacity: 0.5
                        }
                    });
                    previewLayer.eachLayer(layer => layer.addTo(drawnItems));
                    previewMap.fitBounds(drawnItems.getBounds());
                    geojsonField.value = JSON.stringify(combinedFeature.geometry);
                    btnSubmit.disabled = false;
                }

                btnClearMap.addEventListener('click', clearPreview);

                shapefileUpload.addEventListener('change', function(e) {
                    // ... (logika shapefileUpload Anda) ...
                });
                photoUpload.addEventListener('change', function(e) {
                    // ... (logika photoUpload Anda) ...
                });
                btnPreviewManual.addEventListener('click', function() {
                    // ... (logika btnPreviewManual Anda) ...
                });

                // --- PERBAIKAN 3: Logika Pre-load Data untuk Halaman EDIT ---
                const mapElement = document.getElementById('preview-map');
                const rawData = mapElement.dataset.usulanGeojson; // Mengambil dari data-usulan-geojson

                if (rawData && rawData.trim() !== "") {
                    try {
                        const geojsonData = JSON.parse(rawData);

                        // Buat fitur yang valid untuk di-load (data Anda adalah GEOMETRY, bukan FEATURE)
                        const feature = {
                            "type": "Feature",
                            "properties": {},
                            "geometry": geojsonData
                        };

                        updatePreview(feature);

                        fileInfoContent.textContent = 'Data permohonan sebelumnya dimuat.';
                        fileInfoContent.classList.remove('text-gray-500', 'italic');

                        // Sembunyikan tab input
                        const spasialInputTabs = document.getElementById('spasial-input-tabs');
                        if (spasialInputTabs) spasialInputTabs.style.display = 'none';

                    } catch (e) {
                        console.error("Gagal mem-parse GeoJSON yang sudah ada:", e);
                        showError("Gagal memuat data peta tersimpan. Format mungkin rusak. Harap gambar ulang.");
                        btnSubmit.disabled = true; // Nonaktifkan submit jika data rusak
                    }
                } else {
                    // Jika tidak ada data geojson (mungkin permohonan lama/rusak), 
                    // nonaktifkan tombol submit sampai peta digambar ulang
                    btnSubmit.disabled = true;
                    showError("Data peta tidak ditemukan. Harap gambar ulang atau unggah data spasial baru.");
                }
                // --- AKHIR PERBAIKAN 3 ---

                setTimeout(() => previewMap.invalidateSize(), 500);
            });
        </script>
    @endpush
</x-klarifikasi-layout>
