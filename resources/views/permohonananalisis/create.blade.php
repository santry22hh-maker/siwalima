<x-klarifikasi-layout>
    @push('styles')
        {{-- Kita juga harus menyimpan CSS ini secara lokal --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css" />

        {{-- Untuk saat ini, kita muat dari aset lokal (Anda perlu mengunduhnya) --}}
        {{-- <link rel="stylesheet" href="{{ asset('src/js/leaflet.css') }}" /> --}}
        {{-- <link rel="stylesheet" href="{{ asset('src/js/leaflet-draw.css') }}" /> --}}
        <style>
            #map-wrapper {
                min-height: 700px;
            }

            #preview-map {
                z-index: 0;
            }

            /* ... (Semua style Anda yang lain) ... */
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

            .tab-item-active {
                border-color: #22c55e;
                color: #16a34a;
                font-weight: 600;
            }

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
                Formulir Permohonan Analisis Status dan Fungsi Kawasan Hutan Resmi
            </h3>
            <p class="border-gray-200 text-sm text-gray-800 dark:border-gray-800 dark:text-white/90 mt-1">
                Harap lengkapi data pemohon, data surat, dan data spasial di bawah ini.
            </p>
        </div>
    </div>

    {{-- Mulai Input --}}
    <div class="px-2 mb-4">
        <div class="grid grid-cols-1 gap-2 sm:gap-2 lg:grid-cols-7">
            <div
                class="lg:col-span-3 flex flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="space-y-6  border-gray-100 p-4 sm:p-6 dark:border-gray-800">

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

                    <form id="input-form" action="{{ route('permohonananalisis.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="source_type" id="source_type"
                            value="{{ $usulanGeoJson ? 'prefilled' : 'photo' }}">
                        <input type="hidden" name="geojson_data" id="geojson_data"
                            value="{{ old('geojson_data', $usulanGeoJson ?? '') }}">
                        <input type="hidden" name="luas_ha" id="luas_ha" value="{{ old('luas_ha', 0) }}">

                        <h4
                            class="text-lg font-medium text-gray-800 dark:text-white/90 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                            1. Data Surat Permohonan
                        </h4>

                        <div>
                            <label for="nomor_surat"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Nomor
                                Surat</label>
                            <input type="text" name="nomor_surat" id="nomor_surat" required
                                value="{{ old('nomor_surat') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Contoh: 01/ABC/II/2025">
                            <x-input-error :messages="$errors->get('nomor_surat')" class="mt-2" />
                        </div>

                        <div class="mt-3">
                            <label for="tanggal_surat"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Tanggal
                                Surat</label>
                            <input type="date" name="tanggal_surat" id="tanggal_surat" required
                                value="{{ old('tanggal_surat') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
                            <x-input-error :messages="$errors->get('tanggal_surat')" class="mt-2" />
                        </div>

                        {{-- DROPDOWN TUJUAN ANALISIS (BARU) --}}
                        <div class="mt-3">
                            <label for="tujuan_analisis"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Tujuan Analisis
                                Status dan Fungsi Kawasan Hutan</label>
                            <select name="tujuan_analisis" id="tujuan_analisis" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-10 w-full rounded-lg border border-gray-300 bg-transparent px-4 text-sm text-gray-800 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="" disabled selected>-- Pilih Tujuan --</option>
                                <option value="Perizinan" {{ old('tujuan_analisis') == 'Perizinan' ? 'selected' : '' }}>
                                    Perizinan</option>
                                <option value="Klarifikasi Kawasan Hutan"
                                    {{ old('tujuan_analisis') == 'Klarifikasi Kawasan Hutan' ? 'selected' : '' }}>
                                    Klarifikasi Kawasan Hutan</option>
                            </select>
                            <x-input-error :messages="$errors->get('tujuan_analisis')" class="mt-2" />
                        </div>

                        <div class="mt-3">
                            <label for="perihal_surat"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Perihal</label>
                            <textarea name="perihal_surat" id="perihal_surat" rows="2"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Contoh: Permohonan Analisis Status Kawasan Hutan...">{{ old('perihal_surat') }}</textarea>
                            <x-input-error :messages="$errors->get('perihal_surat')" class="mt-2" />
                        </div>

                        <div class="mt-3">
                            <label for="file_surat"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Upload File
                                Surat (Wajib PDF)</label>
                            <input type="file" name="file_surat" id="file_surat" required accept=".pdf"
                                class="block w-full text-sm border rounded-lg text-gray-500 border-gray-300 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600">
                            <x-input-error :messages="$errors->get('file_surat')" class="mt-2" />
                        </div>

                        <hr class="my-4 border-gray-200 dark:border-gray-700">

                        <h4
                            class="text-lg font-medium text-gray-800 dark:text-white/90 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                            2. Data Pemohon
                        </h4>

                        <div>
                            <label for="nama_pemohon"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Nama
                                Pemohon</label>
                            <input type="text" name="nama_pemohon" id="nama_pemohon" required
                                value="{{ old('nama_pemohon') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Nama Lengkap Sesuai KTP">
                            <x-input-error :messages="$errors->get('nama_pemohon')" class="mt-2" />
                        </div>

                        <div class="mt-3">
                            <label for="hp_pemohon"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">No. HP/Telepon
                                (WhatsApp)</label>
                            <input type="text" name="hp_pemohon" id="hp_pemohon" required
                                value="{{ old('hp_pemohon') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="08xxxxxxxxxx">
                            <x-input-error :messages="$errors->get('hp_pemohon')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                            <label for="email_pemohon"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Alamat
                                Email</label>
                            <input type="email" name="email_pemohon" id="email_pemohon"
                                value="{{ old('email_pemohon') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="email@anda.com">
                            <x-input-error :messages="$errors->get('email_pemohon')" class="mt-2" />
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
                                value="{{ old('lokasi', $laporanFrom->dataSpasial->nama_areal ?? '') }}"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Contoh: Areal Tambang Desa Sukamaju">
                            <x-input-error :messages="$errors->get('lokasi')" class="mt-2" />
                        </div>
                        <div class="mt-3">
                            <label for="kabupaten"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400">Kabupaten</label>
                            <input type="text" name="kabupaten" id="kabupaten" required
                                value="{{ old('kabupaten', $laporanFrom->dataSpasial->kabupaten ?? '') }}"
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
                                placeholder="Contoh: Untuk kelengkapan Izin Pertambangan...">{{ old('keterangan', $laporanFrom->keterangan ?? '') }}</textarea>
                            <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
                        </div>

                        {{-- Area Tab Input (Foto, SHP, Manual) --}}
                        <div id="spasial-input-tabs"
                            class="rounded-lg border border-gray-200 px-4 dark:border-gray-800 mt-3"
                            x-data="{ tab: 'photo' }" @if ($usulanGeoJson) style="display: none;" @endif>
                            <div class="border-b border-gray-200 dark:border-gray-800 mt-2">
                                <nav class="-mb-px flex space-x-2" aria-label="Tabs">
                                    <button type="button"
                                        @click="tab = 'photo'; document.getElementById('source_type').value = 'photo';"
                                        :class="(tab === 'photo') ? 'tab-item-active' : 'menu-item-inactive'"
                                        class="inline-flex items-center gap-2 border-b-2 px-2.5 py-2 text-sm font-medium transition-colors duration-200 ease-in-out"><i
                                            class="fas fa-qrcode"></i> Foto Geotag</button>
                                    <button type="button"
                                        @click="tab = 'shapefile'; document.getElementById('source_type').value = 'shapefile';"
                                        :class="(tab === 'shapefile') ? 'tab-item-active' : 'menu-item-inactive'"
                                        class="inline-flex items-center gap-2 border-b-2 px-2.5 py-2 text-sm font-medium transition-colors duration-200 ease-in-out"><i
                                            class="fas fa-shapes"></i> Shapefile</button>
                                    <button type="button"
                                        @click="tab = 'manual'; document.getElementById('source_type').value = 'manual';"
                                        :class="(tab === 'manual') ? 'tab-item-active' : 'menu-item-inactive'"
                                        class="inline-flex items-center gap-2 border-b-2 px-2.5 py-2 text-sm font-medium transition-colors duration-200 ease-in-out"><i
                                            class="fas fa-diagnoses"></i> Manual</button>
                                </nav>
                            </div>
                            <div class="pt-4 dark:border-gray-800">
                                {{-- Tab Panel 1: Foto Geotag --}}
                                <div x-show="tab === 'photo'" class="space-y-4 dark:border-gray-800"
                                    style="display: none;">
                                    <div class="py-2">
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
                                                        class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500 dark:text-gray-400"><span>Pilih
                                                            beberapa file</span><input id="photo-upload"
                                                            name="photos[]" type="file" class="sr-only" multiple
                                                            accept="image/jpeg"></label>
                                                    <span class="pl-1">atau seret dan lepas</span>
                                                </div>
                                            </div>
                                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Pilih beberapa
                                                foto (.jpg) yang memiliki data geotag. Minimal 3 foto.</p>
                                        </div>
                                    </div>
                                </div>
                                {{-- Tab Panel 2: Shapefile --}}
                                <div x-show="tab === 'shapefile'" class="pb-4" style="display: none;">
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
                                                    class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500"><span>Pilih
                                                        sebuah file</span><input id="shapefile-upload"
                                                        name="shapefile_input" type="file" class="sr-only"
                                                        accept=".zip"></label>
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
                                            class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-50 dark:text-gray-300 dark:bg-gray-600 dark:hover:bg-gray-50"
                                            style="margin-bottom:8px">Tampilkan Pratinjau</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($usulanGeoJson)
                            <div id="prefill-notification"
                                class="mt-4 p-3 bg-blue-100 dark:bg-blue-800 text-blue-700 dark:text-blue-200 border border-blue-300 dark:border-blue-600 rounded-lg text-sm">
                                <strong>Data Spasial</strong> telah dimuat dari hasil Analisis Mandiri Anda. Klik
                                "Bersihkan Peta" untuk mengunggah ulang.
                            </div>
                        @endif

                        <div id="js-error-box"
                            class="hidden w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative my-3"
                            role="alert">
                            <strong class="font-bold">Error: </strong>
                            <span class="block sm:inline" id="js-error-message"></span>
                        </div>

                        <div class="pt-5 border-t border-gray-200 flex items-center gap-4">
                            <button type="submit" id="btn-submit" {{ $usulanGeoJson ? '' : 'disabled' }}
                                class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-black bg-green-600 hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                <span id="btn-submit-text">Simpan Permohonan</span>
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
                            Belum ada file</p>
                    </div>
                    <div class="flex">
                        <p class="font-semibold text-gray-800 w-28 flex-shrink-0 dark:text-white/90">Estimasi Luas :
                        </p>
                        <p id="luas-info" class="text-gray-500 italic font-medium ml-2 dark:text-white/90">-</p>
                    </div>
                </div>
                <div id="map-wrapper" class="flex-grow rounded-lg overflow-hidden">
                    <div id="preview-map" class="h-full w-full" data-usulan-geojson="{!! htmlspecialchars($usulanGeoJson ?? '', ENT_QUOTES, 'UTF-8') !!}"></div>
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
                const btnClearMap = document.getElementById('btn-clear-map'); // Baris ini tidak akan error lagi
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

                // Fungsi Legenda (dari blade Anda)
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

                // Pemuatan Layer Overlay (dari blade Anda)
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

                // Kontrol Legenda (dari blade Anda)
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
                        if (btnSubmitText) btnSubmitText.textContent = 'Simpan Permohonan'; // Teks Tombol
                        shapefileUpload.disabled = false;
                        photoUpload.disabled = false;
                        manualCoords.disabled = false;
                        btnPreviewManual.disabled = false;
                        btnClearMap.disabled = false;
                    }
                }

                // Fungsi Hitung Luas (dari blade Anda)
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

                function clearPreview() {
                    hideError();
                    // 1. Bersihkan Data Peta
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

                    // 2. Tampilkan kembali tab input
                    document.getElementById('spasial-input-tabs').style.display = 'block';

                    // 3. Sembunyikan notifikasi prefill
                    const prefillNotification = document.getElementById('prefill-notification');
                    if (prefillNotification) {
                        prefillNotification.style.display = 'none';
                    }

                    // 4. Bersihkan field data pemohon
                    document.getElementById('nama_pemohon').value = '';
                    document.getElementById('hp_pemohon').value = '';
                    document.getElementById('email_pemohon').value = '';

                    // 5. Bersihkan field data surat
                    document.getElementById('nomor_surat').value = '';
                    document.getElementById('tanggal_surat').value = '';
                    document.getElementById('perihal_surat').value = '';
                    document.getElementById('file_surat').value = '';

                    // 6. Bersihkan field data spasial
                    document.getElementById('lokasi').value = '';
                    document.getElementById('kabupaten').value = '';
                    document.getElementById('keterangan').value = '';

                    // 7. Bersihkan field userid/groupid
                    const useridField = document.getElementById('userid');
                    const groupidField = document.getElementById('groupid');
                    if (useridField) useridField.value = '';
                    if (groupidField) groupidField.value = '';
                }

                function updatePreview(geojson) {
                    drawnItems.clearLayers();
                    if (!geojson || (geojson.type === 'FeatureCollection' && geojson.features.length === 0)) {
                        showError('File tidak mengandung feature yang dapat ditampilkan.');
                        return;
                    }
                    let combinedFeature = null;
                    if (geojson.type === 'FeatureCollection') {
                        const polygons = geojson.features.filter(f => f.geometry && (f.geometry.type ===
                            'Polygon' || f.geometry.type === 'MultiPolygon'));
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
                    } else if (geojson.geometry && (geojson.geometry.type === 'Polygon' || geojson.geometry
                            .type === 'MultiPolygon')) {
                        combinedFeature = geojson.type === 'Feature' ? geojson : {
                            type: 'Feature',
                            properties: {},
                            geometry: geojson
                        };
                    } else {
                        showError('Geometri yang didukung hanya Polygon atau MultiPolygon.');
                        return;
                    }

                    calculateAndDisplayArea(combinedFeature); // Hitung luas

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
                    const file = e.target.files[0];
                    if (!file) {
                        clearPreview();
                        return;
                    }

                    document.getElementById('source_type').value = 'shapefile';

                    setLoading(true);
                    hideError();
                    fileInfoContent.textContent = file.name;
                    fileInfoContent.classList.remove('text-gray-500', 'italic');
                    luasInfo.textContent = 'Menghitung...';
                    luasInfo.classList.remove('text-gray-500', 'italic');

                    const reader = new FileReader();
                    reader.onload = (event) => shp(event.target.result).then(updatePreview)
                        .catch(err => {
                            showError('Gagal memproses shapefile: ' + err.message);
                            clearPreview();
                        })
                        .finally(() => setLoading(false));
                    reader.onerror = () => {
                        showError('Gagal membaca file.');
                        clearPreview();
                        setLoading(false);
                    };
                    reader.readAsArrayBuffer(file);
                });

                photoUpload.addEventListener('change', function(e) {
                    const files = e.target.files;
                    if (files.length === 0) {
                        clearPreview();
                        return;
                    }

                    document.getElementById('source_type').value = 'photo';

                    if (files.length < 3) {
                        showError('Silakan pilih minimal 3 foto.');
                        photoUpload.value = '';
                        return;
                    }
                    setLoading(true);
                    hideError();
                    let fileText = (files.length === 1) ? files[0].name : `${files.length} file dipilih`;
                    fileInfoContent.textContent = fileText;
                    fileInfoContent.classList.remove('text-gray-500', 'italic');
                    luasInfo.textContent = 'Memproses geotag...';
                    luasInfo.classList.remove('text-gray-500', 'italic');

                    let coords = [];
                    let promises = Array.from(files).map(file => new Promise((resolve, reject) => {
                        EXIF.getData(file, function() {
                            const lat = EXIF.getTag(this, "GPSLatitude");
                            const lon = EXIF.getTag(this, "GPSLongitude");
                            if (lat && lon) {
                                const latRef = EXIF.getTag(this, "GPSLatitudeRef") || "N";
                                const lonRef = EXIF.getTag(this, "GPSLongitudeRef") || "E";
                                const finalLat = (lat[0] + lat[1] / 60 + lat[2] / 3600) * (
                                    latRef === "S" ? -1 : 1);
                                const finalLon = (lon[0] + lon[1] / 60 + lon[2] / 3600) * (
                                    lonRef === "W" ? -1 : 1);
                                coords.push([finalLon, finalLat]);
                                resolve();
                            } else {
                                reject(new Error(
                                    `Tidak ada data geotag pada file: ${file.name}`
                                ));
                            }
                        });
                    }));
                    Promise.all(promises).then(() => {
                        if (coords.length < 3) {
                            showError('Tidak cukup foto dengan geotag yang valid.');
                            clearPreview();
                            return;
                        }
                        coords.push(coords[0]);
                        const geojson = {
                            type: 'Feature',
                            properties: {},
                            geometry: {
                                type: 'Polygon',
                                coordinates: [coords]
                            }
                        };
                        updatePreview(geojson);
                    }).catch(error => {
                        showError(error.message);
                        clearPreview();
                    }).finally(() => setLoading(false));
                });

                btnPreviewManual.addEventListener('click', function() {

                    document.getElementById('source_type').value = 'manual';

                    hideError();
                    const text = manualCoords.value.trim();
                    if (!text) {
                        showError('Koordinat kosong.');
                        return;
                    }
                    try {
                        const coords = text.split('\n').map(line => {
                            const parts = line.split(',').map(part => parseFloat(part.trim()));
                            if (parts.length !== 2 || isNaN(parts[0]) || isNaN(parts[1]))
                                throw new Error(`Format salah: ${line}`);
                            return [parts[1], parts[0]]; // [lon, lat]
                        });
                        if (coords.length < 3) {
                            showError('Minimal 3 titik koordinat.');
                            return;
                        }
                        coords.push(coords[0]);
                        fileInfoContent.textContent = 'Poligon dari input manual';
                        fileInfoContent.classList.remove('text-gray-500', 'italic');
                        luasInfo.textContent = 'Menghitung...';
                        luasInfo.classList.remove('text-gray-500', 'italic');
                        const geojson = {
                            type: 'Feature',
                            properties: {},
                            geometry: {
                                type: 'Polygon',
                                coordinates: [coords]
                            }
                        };
                        updatePreview(geojson);
                    } catch (err) {
                        showError('Gagal memproses: ' + err.message);
                        clearPreview();
                    }
                });

                // Logika Pre-load Data (sudah benar)
                const mapElement = document.getElementById('preview-map');
                const rawData = mapElement.dataset.usulanGeojson;
                if (rawData && rawData.trim() !== "") {
                    try {
                        const geojsonData = JSON.parse(rawData);
                        // --- PERBAIKAN 7: Data prefill adalah 'geometry', bukan 'feature' ---
                        const feature = {
                            "type": "Feature",
                            "properties": {},
                            "geometry": geojsonData
                        };
                        usulanData = feature;
                        updatePreview(feature, "Data dari Analisis Mandiri");
                        // --- AKHIR PERBAIKAN 7 ---

                        fileInfoContent.textContent = 'Data dimuat dari Analisis Mandiri';
                        fileInfoContent.classList.remove('text-gray-500', 'italic');
                    } catch (e) {
                        console.error("Gagal mem-parse GeoJSON yang sudah ada:", e);
                    }
                }

                setTimeout(() => previewMap.invalidateSize(), 500);
            });
        </script>
    @endpush
</x-klarifikasi-layout>
