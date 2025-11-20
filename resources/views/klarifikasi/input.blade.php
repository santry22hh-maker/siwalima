<x-klarifikasi-layout>
    @push('styles')
        {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" /> --}}

        <style>
            #map-wrapper {
                min-height: 700px;
            }

            #map {
                /* height: 100%; width: 100%; */
                z-index: 0;
            }

            /* ... style legenda Anda ... */
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

            /* STYLING TAB AKTIF (SARAN 4) */
            .tab-item-active {
                /* Garis bawah hijau, teks hijau */
                border-color: #22c55e;
                /* green-500 */
                color: #16a34a;
                /* green-600 */
                font-weight: 600;
            }

            /*  */

            /* Style untuk tabel hasil */
            #analysisResult table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            #analysisResult th,
            #analysisResult td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
                font-size: 12px;
            }

            #analysisResult th {
                background-color: #f2f2f2;
                dark: bg-gray-700;
            }
        </style>
    @endpush

    <div class="px-2 mb-2">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-2">
            <div class="w-full py-2 sm:px-6 lg:px-8">
                <h4 class="border-gray-200 text-base font-medium text-gray-800 dark:border-gray-800 dark:text-white/90">
                    Input Data Spasial
                </h4>
                <p class="border-gray-200 text-sm  text-gray-800 dark:border-gray-800 dark:text-white/90 ">
                    Unggah foto dengan geotag, shapefile (.zip) atau masukkan
                    koordinat
                    poligon secara manual untuk menambahkannya ke peta. Pratinjau akan muncul di peta sebelum
                    diproses.</p>
            </div>

        </div>
    </div>

    {{-- Mulai Input --}}
    <div class="px-2 mb-4">
        <div class="grid grid-cols-1 gap-2 sm:gap-2 lg:grid-cols-7">
            <div
                class="lg:col-span-3 flex flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="space-y-6  border-gray-100 p-4 sm:p-4 dark:border-gray-800">
                    @if ($errors->any())
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Data Gagal Diproses:</p>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Error</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    {{-- Form ini tidak lagi memiliki tombol submit, jadi tidak akan terkirim --}}
                    <form id="input-form" action="{{ route('klarifikasi.proses') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="source_type" id="source_type" value="photo">
                        <input type="hidden" name="geojson_data" id="geojson_data" value="{{ old('geojson_data') }}">

                        {{-- Area Tab Input --}}
                        <div class="rounded-lg border border-gray-200 px-4 dark:border-gray-800 "
                            x-data="{ tab: 'photo' }">
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
                                    <div class="px-2 py-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Upload
                                            Foto</label>
                                        <div
                                            class="mt-1 flex flex-col items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor"
                                                fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path
                                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
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
                                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Pilih beberapa foto
                                            (.jpg) yang memiliki data geotag. Minimal 3 foto.</p>
                                    </div>
                                </div>
                                {{-- Tab Panel 2: Shapefile --}}
                                <div x-show="tab === 'shapefile'" class="space-y-2 dark:border-gray-800 pb-4"
                                    style="display: none;">
                                    <label
                                        class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">Upload
                                        File .ZIP</label>
                                    <div
                                        class="mt-1 flex flex-col items-center justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-400"
                                            stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                            <path
                                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="text-sm text-gray-600 px-2 py-4">
                                            <label for="shapefile-upload"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                                <span>Pilih sebuah file</span>
                                                <input id="shapefile-upload" name="shapefile_input" type="file"
                                                    class="sr-only" accept=".zip">
                                            </label>
                                            <span class="pl-1 dark:text-gray-400">atau seret dan lepas</span>
                                        </div>
                                    </div>
                                </div>
                                {{-- Tab Panel 3: Manual --}}
                                <div x-show="tab === 'manual'" class="space-y-2 dark:border-gray-800"
                                    style="display: none;">
                                    <label for="manual-coords"
                                        class="block text-sm font-medium text-gray-700 mb-1 dark:text-white/90">Koordinat</label>
                                    <textarea id="manual-coords" rows="8"
                                        class="p-2 dark:text-white/90 font-normal text-sm w-full border bottom-1 border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500"
                                        placeholder="Masukkan daftar koordinat, satu per baris.&#10;Format: latitude, longitude&#10;-6.2088, 106.8456&#10;-6.2188, 106.8556&#10;-6.2088, 106.8656"></textarea>
                                    <button type="button" id="btn-preview-manual"
                                        class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-50 dark:text-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500"
                                        style="margin-bottom:8px">Tampilkan Pratinjau</button>
                                </div>
                            </div>
                        </div>

                        {{-- 2. KOTAK AKSI (Dropdown & Tombol) --}}
                        <div
                            class="rounded-lg border border-gray-200 dark:border-gray-700 p-2 flex flex-col gap-4 mt-4">
                            <div>
                                <label for="geojsonDropdown"
                                    class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Data
                                    Dasar</label>
                                <select id="geojsonDropdown"
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                    <option value="">-- Pilih GeoJSON --</option>
                                    @foreach ($dataDasarFiles as $file)
                                        <option value="{{ $file['url'] }}"
                                            data-style="{{ $file['style_function'] ?? '' }}">
                                            {{ $file['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex flex-col sm:flex-row items-center gap-3">
                                <button type="button" id="btnAnalisis"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-blue-500 shadow-theme-xs hover:bg-blue-600">
                                    Mulai Analisis
                                </button>
                                <button type="button" id="btn-clear-map"
                                    class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-100 hover:bg-gray-50 dark:text-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500">
                                    Bersihkan Peta
                                </button>
                            </div>
                        </div>

                        <div id="analysis-results-wrapper" class="mt-4 space-y-4" style="display: none;">
                            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                <h5 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Hasil Analisis</h5>
                                <div id="analysisResult"
                                    class="text-sm text-gray-700 dark:text-gray-300 overflow-x-auto">
                                </div>
                            </div>

                            <div id="chart-container"
                                class="rounded-lg border border-gray-200 dark:border-gray-700 p-4"
                                style="display: none;">
                                <canvas id="analysisChartCanvas"></canvas>
                            </div>

                            <div id="nextSteps"
                                class="hidden rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                <h5 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Langkah Berikutnya
                                </h5>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <button type="button" id="btnDownloadPdf"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-normal text-center text-white transition rounded-lg bg-red-600 shadow-theme-xs hover:bg-red-700">
                                        <i class="fas fa-file-pdf"></i> Unduh PDF
                                    </button>

                                    {{-- TODO: Pastikan route 'permohonan.create' ini sudah Anda buat --}}
                                    <a href="{{ route('permohonananalisis.create') }}"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-xs font-normal text-center text-white transition rounded-lg bg-green-600 shadow-theme-xs hover:bg-green-700">
                                        <i class="fas fa-file-signature"></i> Ajukan Permohonan Resmi
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- ERROR BOX INLINE (SARAN 1) --}}
                        <div id="js-error-box"
                            class="hidden w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative my-3"
                            role="alert">
                            <strong class="font-bold">Error: </strong>
                            <span class="block sm:inline" id="js-error-message"></span>
                        </div>


                    </form>
                </div>
            </div>

            {{-- Kolom Kanan: Peta dan Info Box --}}
            <div
                class="lg:col-span-4 flex flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

                {{-- INFO BOX PLACEHOLDER (SARAN 3) --}}
                <div id="file-info-box"
                    class="bg-white border border-gray-300 rounded-lg p-3 mb-4 text-sm space-y-1  dark:border-gray-800 dark:bg-white/[0.03]">
                    {{-- Baris untuk Nama File --}}
                    <div class="flex">
                        <p
                            class="font-semibold text-gray-800 w-28 flex-shrink-0 dark:text-white/90 dark:placeholder:text-white/30">
                            File dipilih : </p>
                        <p id="file-info-content"
                            class="text-gray-500 italic break-words ml-2 dark:text-white/90 dark:placeholder:text-white/30">
                            Belum ada file
                        </p>
                    </div>
                    {{-- Baris untuk Info Luas --}}
                    <div class="flex">
                        <p
                            class="font-semibold text-gray-800 w-28 flex-shrink-0 dark:text-white/90 dark:placeholder:text-white/30">
                            Estimasi Luas : </p>
                        <p id="luas-info"
                            class="text-gray-500 italic font-medium ml-2 dark:text-white/90 dark:placeholder:text-white/30">
                            -
                        </p>
                    </div>
                </div>

                <div id="map-wrapper" class="flex-grow rounded-lg overflow-hidden">
                    <div id="map" class="h-full w-full"></div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Pustaka Eksternal --}}
        <script src="https://unpkg.com/shpjs@3.6.0/dist/shp.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/exif-js"></script>
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/leaflet-image@0.4.0/leaflet-image.min.js"></script>

        <script src="{{ asset('src/js/map_styles.js') }}"></script>

        <script>
            // public/js/klarifikasiMap.js

            document.addEventListener('DOMContentLoaded', function() {
                // 1. INISIALISASI PETA & VARIABEL
                // --- PERBAIKAN 1: Tambahkan renderer: L.canvas() ---
                const previewMap = L.map('map', {
                    renderer: L.canvas()
                }).setView([-3.69, 128.17], 9);

                const shapefileUpload = document.getElementById('shapefile-upload');
                const photoUpload = document.getElementById('photo-upload');
                const fileInfoContent = document.getElementById('file-info-content');
                const manualCoords = document.getElementById('manual-coords');
                const btnPreviewManual = document.getElementById('btn-preview-manual');
                const geojsonField = document.getElementById('geojson_data');
                const btnClearMap = document.getElementById('btn-clear-map');
                const luasInfo = document.getElementById('luas-info');

                const jsErrorBox = document.getElementById('js-error-box');
                const jsErrorMessage = document.getElementById('js-error-message');

                // Variabel untuk dropdown dan analisis
                let dropdownLayer = null;
                let dropdownData = null;
                let activeStyleFunction = null;
                let usulanData = null;
                let hasilLayer = null;
                let analysisChart = null;
                let currentResults = [];
                let currentTotalArea = 0;

                let currentDisplayHeader = 'Keterangan';

                const analysisResultsWrapper = document.getElementById('analysis-results-wrapper');
                const analysisResultDiv = document.getElementById('analysisResult');
                const chartContainer = document.getElementById('chart-container');
                const nextStepsDiv = document.getElementById('nextSteps');


                // 2. SETUP BASEMAP & KONTROL LAYER
                // --- PERBAIKAN 2: Tambahkan crossOrigin: 'Anonymous' dan Hapus print: false ---
                const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    crossOrigin: 'Anonymous'
                });
                const satellite = L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Tiles &copy; Esri',
                        crossOrigin: 'Anonymous'
                    });
                const openTopo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
                    maxZoom: 17,
                    attribution: 'Map data: &copy; OpenStreetMap contributors',
                    crossOrigin: 'Anonymous'
                });
                osm.addTo(previewMap);

                const baseMaps = {
                    "Peta Jalan": osm,
                    "Citra Satelit": satellite,
                    "Topografi": openTopo
                };
                const drawnItems = new L.FeatureGroup();
                previewMap.addLayer(drawnItems);

                hasilLayer = L.geoJSON(null, {
                    style: {
                        color: "red",
                        fillOpacity: 0.5,
                        weight: 2
                    }
                }).addTo(previewMap);

                const overlayMaps = {
                    "Area Usulan": drawnItems,
                    "Hasil Analisis": hasilLayer
                };
                const layerControl = L.control.layers(baseMaps, overlayMaps, {
                    position: 'topright'
                }).addTo(previewMap);

                // FUNGSI HELPER BARU (SARAN 1 & 2)
                function showError(message) {
                    jsErrorMessage.textContent = message;
                    jsErrorBox.classList.remove('hidden');
                }

                function hideError() {
                    jsErrorMessage.textContent = '';
                    jsErrorBox.classList.add('hidden');
                }

                function setLoading(isLoading) {
                    if (isLoading) {
                        shapefileUpload.disabled = true;
                        photoUpload.disabled = true;
                        manualCoords.disabled = true;
                        btnPreviewManual.disabled = true;
                        btnClearMap.disabled = true;
                    } else {
                        shapefileUpload.disabled = false;
                        photoUpload.disabled = false;
                        manualCoords.disabled = false;
                        btnPreviewManual.disabled = false;
                        btnClearMap.disabled = false;
                    }
                }

                // FUNGSI HELPER YANG DIMODIFIKASI (SARAN 3)
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
                        luasInfo.innerHTML =
                            `<strong>${formattedHectares} ha</strong> (${formattedMeters} m²)`;
                        luasInfo.classList.remove('text-gray-500', 'italic');
                    } catch (e) {
                        luasInfo.innerHTML = '<span class="text-red-500">Gagal menghitung luas.</span>';
                        luasInfo.classList.remove('text-gray-500', 'italic');
                    }
                }

                function updateGeoJsonInput(layer) {
                    const geojson = layer.toGeoJSON();
                    geojsonField.value = JSON.stringify(geojson.geometry);
                }

                // clearPreview dimodifikasi untuk membersihkan semua layer
                function clearPreview() {
                    hideError();

                    // 1. Membersihkan Peta Usulan (drawnItems)
                    drawnItems.clearLayers();
                    usulanData = null; // Hapus data usulan yang tersimpan

                    // 2. Membersihkan Data Dasar dari Dropdown (dropdownLayer)
                    if (dropdownLayer) {
                        previewMap.removeLayer(dropdownLayer);
                        layerControl.removeLayer(dropdownLayer); // Hapus juga dari kontrol layer
                        dropdownLayer = null;
                        dropdownData = null;
                    }
                    // Reset pilihan dropdown ke default ("-- Pilih GeoJSON --")
                    document.getElementById('geojsonDropdown').selectedIndex = 0;

                    // 3. Membersihkan Layer Hasil Analisis (hasilLayer)
                    if (hasilLayer) {
                        hasilLayer.clearLayers();
                    }

                    // 4. Reset Form Input
                    geojsonField.value = '';
                    shapefileUpload.value = '';
                    photoUpload.value = '';
                    manualCoords.value = '';

                    // 5. Reset Info Box
                    fileInfoContent.textContent = 'Belum ada file';
                    fileInfoContent.classList.add('text-gray-500', 'italic');
                    luasInfo.innerHTML = '-';
                    luasInfo.classList.add('text-gray-500', 'italic');

                    // 6. Sembunyikan dan Reset Hasil Analisis
                    if (analysisChart) {
                        analysisChart.destroy();
                        analysisChart = null;
                    }
                    analysisResultsWrapper.style.display = 'none';
                    analysisResultDiv.innerHTML = '';
                    chartContainer.style.display = 'none';
                    nextStepsDiv.classList.add('hidden');
                    currentResults = []; // Reset data hasil
                    currentTotalArea = 0; // Reset data hasil

                    // Perbarui ukuran peta setelah membersihkan
                    setTimeout(() => {
                        previewMap.invalidateSize();
                    }, 200);
                }

                // updatePreview dimodifikasi untuk menyimpan usulanData
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

                    usulanData = combinedFeature; // <-- PENTING: Simpan data usulan
                }

                // EVENT HANDLER YANG DIMODIFIKASI
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

                previewMap.on(L.Draw.Event.CREATED, e => {
                    hideError();
                    drawnItems.clearLayers();
                    drawnItems.addLayer(e.layer);
                    updateGeoJsonInput(e.layer);
                    fileInfoContent.textContent = 'Poligon digambar dari peta';
                    fileInfoContent.classList.remove('text-gray-500', 'italic');
                    calculateAndDisplayArea(e.layer.toGeoJSON());
                    usulanData = e.layer.toGeoJSON(); // <-- Simpan data usulan
                });

                previewMap.on(L.Draw.Event.EDITED, e => e.layers.eachLayer(layer => {
                    updateGeoJsonInput(layer);
                    usulanData = layer.toGeoJSON(); // <-- Update data usulan
                    calculateAndDisplayArea(layer.toGeoJSON()); // <-- Update luas
                }));
                previewMap.on(L.Draw.Event.DELETED, () => {
                    if (drawnItems.getLayers().length === 0) {
                        geojsonField.value = '';
                        usulanData = null; // <-- Hapus data usulan
                    }
                });

                btnClearMap.addEventListener('click', clearPreview);

                shapefileUpload.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) {
                        clearPreview();
                        return;
                    }
                    setLoading(true);
                    hideError();
                    fileInfoContent.textContent = file.name;
                    fileInfoContent.classList.remove('text-gray-500', 'italic');
                    luasInfo.textContent = 'Menghitung...';
                    luasInfo.classList.remove('text-gray-500', 'italic');
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        shp(event.target.result).then(
                                updatePreview) // updatePreview akan menyimpan usulanData
                            .catch(err => {
                                showError('Gagal memproses shapefile: ' + err.message);
                                clearPreview();
                            })
                            .finally(() => setLoading(false));
                    };
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
                            showError(
                                'Tidak cukup foto dengan geotag yang valid untuk membuat poligon.'
                            );
                            clearPreview();
                            return;
                        }
                        coords.push(coords[0]);
                        const geojson = {
                            type: 'Feature',
                            properties: {
                                name: 'Poligon dari Foto'
                            },
                            geometry: {
                                type: 'Polygon',
                                coordinates: [coords]
                            }
                        };
                        updatePreview(geojson); // Ini akan mengisi 'usulanData'
                    }).catch(error => {
                        showError(error.message);
                        clearPreview();
                    }).finally(() => setLoading(false));
                });

                btnPreviewManual.addEventListener('click', function() {
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
                            properties: {
                                name: 'Poligon Manual'
                            },
                            geometry: {
                                type: 'Polygon',
                                coordinates: [coords]
                            }
                        };
                        updatePreview(geojson); // Ini akan mengisi 'usulanData'
                    } catch (err) {
                        showError('Gagal memproses: ' + err.message);
                        clearPreview();
                    }
                });

                // Logika untuk Dropdown
                document.getElementById('geojsonDropdown').addEventListener('change', function(e) {
                    const option = e.target.options[e.target.selectedIndex];
                    const url = option.value;
                    const styleFuncName = option.dataset.style;

                    if (styleFuncName && typeof window[styleFuncName] === 'function') {
                        activeStyleFunction = window[styleFuncName];
                    } else {
                        activeStyleFunction = null;
                    }

                    let layerStyle = activeStyleFunction ?
                        activeStyleFunction : {
                            color: "#008000",
                            weight: 2,
                            opacity: 0.7
                        };

                    if (dropdownLayer) {
                        previewMap.removeLayer(dropdownLayer);
                        layerControl.removeLayer(dropdownLayer);
                        dropdownLayer = null;
                        dropdownData = null;
                    }

                    if (!url) {
                        return;
                    }

                    fetch(url)
                        .then(res => {
                            if (!res.ok) {
                                throw new Error(`Gagal memuat file: ${res.statusText}`);
                            }
                            return res.json();
                        })
                        .then(data => {
                            dropdownData = data; // Simpan data untuk analisis
                            dropdownLayer = L.geoJSON(data, {
                                style: layerStyle,
                                onEachFeature: function(feature, layer) {
                                    let popupContent = '';
                                    if (feature.properties.FUNGSIKWS) popupContent =
                                        `<b>Fungsi Kawasan:</b><br>${feature.properties.FUNGSIKWS}`;
                                    else if (feature.properties.PL2023_ID) popupContent =
                                        `<b>Penutupan Lahan:</b><br>${feature.properties.PL2023_ID}`;
                                    else popupContent = '<pre>' + JSON.stringify(feature
                                            .properties, null, 2).substring(0, 300) +
                                        '...</pre>';
                                    layer.bindPopup(popupContent);
                                }
                            }).addTo(previewMap);

                            layerControl.addOverlay(dropdownLayer, `Data Dasar: ${option.text}`);

                        }).catch(err => {
                            showError('Gagal memuat data layer: ' + err.message);
                            console.error(err);
                        });
                });

                // Logika untuk Tombol Analisis

                // Fungsi Helper untuk Grafik
                function renderAnalysisChart(resultsArray, styleFunction) {
                    try {
                        chartContainer.style.display = 'block';
                        const ctx = document.getElementById('analysisChartCanvas').getContext('2d');
                        const labels = [];
                        const dataPoints = [];
                        const backgroundColors = [];
                        const defaultColor = 'rgba(54, 162, 235, 0.7)';
                        const isDarkMode = document.documentElement.classList.contains('dark');
                        const textColor = isDarkMode ? '#FFF' : '#333';

                        resultsArray.forEach(res => {
                            labels.push(res.groupValue || 'Data');
                            dataPoints.push((res.totalArea / 10000).toFixed(4));

                            let color = defaultColor;
                            if (styleFunction) {
                                try {
                                    let dummyFeature = {
                                        properties: res.properties
                                    };
                                    let styleObject = styleFunction(dummyFeature);
                                    color = styleObject.fillColor || styleObject.color || defaultColor;
                                } catch (e) {
                                    color = defaultColor;
                                }
                            }
                            backgroundColors.push(color);
                        });

                        if (analysisChart) {
                            analysisChart.destroy();
                        }

                        analysisChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Luas (ha)',
                                    data: dataPoints,
                                    backgroundColor: backgroundColors,
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                indexAxis: 'y',
                                responsive: true,
                                scales: {
                                    x: {
                                        ticks: {
                                            color: textColor
                                        }
                                    },
                                    y: {
                                        ticks: {
                                            color: textColor
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    title: {
                                        display: true,
                                        text: 'Grafik Batang Hasil Analisis',
                                        color: textColor
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                let label = context.label || '';
                                                let value = parseFloat(context.raw) || 0;
                                                return `${label}: ${value.toLocaleString('id-ID', { maximumFractionDigits: 4 })} ha`;
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    } catch (e) {
                        console.error("Gagal merender grafik:", e.message);
                        chartContainer.style.display = 'none';
                    }
                }

                // Fungsi Helper untuk ekstrak koordinat (untuk PDF)
                function extractCoordinates(geometry) {
                    if (!geometry || !geometry.coordinates) return [];
                    if (geometry.type === 'Polygon') return geometry.coordinates[0];
                    if (geometry.type === 'MultiPolygon') return geometry.coordinates[0][0];
                    return [];
                }

                // Event Listener Tombol Analisis
                document.getElementById('btnAnalisis').addEventListener('click', function() {
                    if (!usulanData) {
                        showError("Data usulan (dari input foto, shp, manual, atau gambar) harus ada!");
                        return;
                    }
                    if (!dropdownData) {
                        showError("Data dasar (dari dropdown) harus dipilih!");
                        return;
                    }

                    // Bersihkan hasil sebelumnya
                    hasilLayer.clearLayers();
                    hideError();
                    analysisResultsWrapper.style.display = 'block';
                    analysisResultDiv.innerHTML = '<p>Sedang menganalisis, mohon tunggu...</p>';
                    if (analysisChart) {
                        analysisChart.destroy();
                        analysisChart = null;
                    }
                    chartContainer.style.display = 'none';
                    nextStepsDiv.classList.add('hidden');

                    let totalArea = 0,
                        groupedData = {};

                    // Bungkus dalam setTimeout agar UI sempat update "Loading"
                    setTimeout(() => {

                        let groupingKey = null;
                        currentDisplayHeader = 'Keterangan';

                        if (dropdownData && dropdownData.features && dropdownData.features.length > 0) {
                            const props = dropdownData.features[0].properties;
                            if (props.hasOwnProperty('PL2023_ID')) {
                                groupingKey = 'PL2023_ID';
                                currentDisplayHeader = 'PL2023_ID';
                            } else if (props.hasOwnProperty('FUNGSIKWS')) {
                                groupingKey = 'FUNGSIKWS';
                                currentDisplayHeader = 'Fungsi Kawasan';
                            }
                        }

                        try {
                            (usulanData.features || [usulanData]).forEach(u => {
                                (dropdownData.features || [dropdownData]).forEach(d => {
                                    const intersection = turf.intersect(u, d);
                                    if (intersection) {
                                        const area = turf.area(intersection);
                                        totalArea += area;

                                        let key;
                                        if (groupingKey) {
                                            key = d.properties[groupingKey];
                                        } else {
                                            key = 'Properti Tidak Dikenali';
                                        }

                                        if (!groupedData[key]) {
                                            groupedData[key] = {
                                                groupValue: key,
                                                properties: d.properties,
                                                totalArea: 0
                                            };
                                        }
                                        groupedData[key].totalArea += area;
                                    }
                                });
                            });
                        } catch (err) {
                            showError(`Error saat analisis: ${err.message}`);
                            analysisResultDiv.innerHTML =
                                `<span class="text-red-600">Error saat analisis: ${err.message}. Ini mungkin terjadi jika data saling memotong dengan cara yang kompleks.</span>`;
                            return;
                        }

                        const resultsArray = Object.values(groupedData);
                        currentResults = resultsArray;
                        currentTotalArea = totalArea;

                        if (resultsArray.length > 0) {
                            let html = `<strong>Hasil Analisis:</strong>`;

                            html +=
                                `<p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Total Luas Irisan: <strong>${(totalArea/10000).toLocaleString('id-ID', { maximumFractionDigits: 4 })} ha</strong></p>`;

                            html += `<table><thead><tr>`;
                            html += `<th>${currentDisplayHeader}</th>`;
                            html += `<th>Luas (ha)</th></tr></thead><tbody>`;

                            resultsArray.forEach(res => {
                                html += `<tr>`;
                                html += `<td>${res.groupValue ?? 'N/A'}</td>`;
                                html +=
                                    `<td style="text-align:right;">${(res.totalArea/10000).toLocaleString('id-ID', { maximumFractionDigits: 4 })}</td></tr>`;
                            });

                            html += `</tbody></table>`;
                            html +=
                                `<p class="mt-2 text-xs text-gray-600 dark:text-gray-400 italic">Luas yang ditampilkan adalah estimasi luas geodesik.</p>`;
                            html += `<div class="mt-4 p-3 bg-yellow-100 dark:bg-yellow-800 border-l-4 border-yellow-500 text-yellow-800 dark:text-yellow-200 text-sm">
                    <strong>Catatan Penting:</strong><br>
                    Hasil ini merupakan analisis cepat dan tidak dapat dijadikan pegangan/rujukan resmi. Untuk hasil resmi, silakan ajukan permohonan.
               </div>`;

                            analysisResultDiv.innerHTML = html;

                            try {
                                if (hasilLayer.getLayers().length > 0) {
                                    previewMap.fitBounds(hasilLayer.getBounds());
                                }
                            } catch (e) {
                                console.error("Gagal melakukan fitBounds:", e.message);
                            }

                            nextStepsDiv.classList.remove('hidden');
                            renderAnalysisChart(resultsArray, activeStyleFunction);

                            setTimeout(() => {
                                previewMap.invalidateSize();
                            }, 200);

                        } else {
                            analysisResultDiv.innerHTML =
                                `<span class="text-blue-600">Tidak ada hasil irisan (intersection). Poligon usulan tidak tumpang tindih dengan data dasar.</span>`;
                            nextStepsDiv.classList.add('hidden');

                            setTimeout(() => {
                                previewMap.invalidateSize();
                            }, 200);
                        }
                    }, 50); // Penundaan 50ms
                });

                // Logika untuk Tombol Download PDF
                document.getElementById('btnDownloadPdf').addEventListener('click', function() {
                    if (currentResults.length === 0 || !usulanData) {
                        showError("Tidak ada hasil analisis untuk diunduh.");
                        return;
                    }
                    if (!analysisChart) {
                        console.warn("Grafik tidak ter-render. PDF akan dibuat tanpa grafik.");
                    }

                    const pdfButton = this;
                    const originalButtonText = pdfButton.innerHTML;
                    pdfButton.disabled = true;
                    pdfButton.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Membuat PDF...`;

                    // Ambil gambar peta
                    leafletImage(previewMap, function(err, canvas) {
                        if (err) {
                            alert(
                                'Gagal mengambil gambar peta. Pastikan "print: false" ada di layer basemap.'
                            );
                            console.error(err);
                            pdfButton.disabled = false;
                            pdfButton.innerHTML = originalButtonText;
                            return;
                        }

                        const mapImgData = canvas.toDataURL('image/png', 0.95);
                        const {
                            jsPDF
                        } = window.jspdf;
                        const doc = new jsPDF('p', 'mm', 'a4');
                        let currentY = 15;
                        const pageMargin = 15;
                        const pageWidth = doc.internal.pageSize.getWidth();
                        const contentWidth = pageWidth - (pageMargin * 2);

                        doc.setFontSize(18);
                        doc.text("Hasil Analisis Cepat Spasial", pageMargin, currentY);
                        currentY += 8;
                        doc.setFontSize(10);
                        doc.text("Laporan ini dibuat pada: " + new Date().toLocaleString('id-ID'),
                            pageMargin, currentY);
                        currentY += 8;
                        doc.setFont("helvetica", "italic");
                        doc.setTextColor(255, 0, 0);
                        doc.text(
                            "Catatan: Ini adalah analisis cepat dan tidak dapat dijadikan rujukan resmi.",
                            pageMargin, currentY);
                        doc.setTextColor(0, 0, 0);
                        doc.setFont("helvetica", "normal");
                        currentY += 10;
                        doc.setFontSize(14);
                        doc.text("Peta Lokasi Analisis", pageMargin, currentY);
                        currentY += 5;

                        const mapImgProps = doc.getImageProperties(mapImgData);
                        const mapImgRatio = mapImgProps.height / mapImgProps.width;
                        const mapImgHeight = contentWidth * mapImgRatio;
                        doc.addImage(mapImgData, 'PNG', pageMargin, currentY, contentWidth,
                            mapImgHeight);
                        currentY += mapImgHeight + 10;

                        if (currentY > 260) {
                            doc.addPage();
                            currentY = 15;
                        }

                        doc.setFontSize(14);
                        doc.text("Data Koordinat Usulan", 15, currentY);
                        currentY += 5;
                        const coords = extractCoordinates(usulanData.geometry);
                        const coordTableHead = [
                            ["#", "Latitude", "Longitude"]
                        ];
                        const coordTableBody = coords.map((p, index) => {
                            if (index < coords.length - 1) return [index + 1, p[1].toFixed(6),
                                p[0].toFixed(6)
                            ];
                            return null;
                        }).filter(row => row !== null);

                        doc.autoTable({
                            head: coordTableHead,
                            body: coordTableBody,
                            startY: currentY,
                            theme: 'striped',
                            headStyles: {
                                fillColor: [100, 100, 100]
                            }
                        });
                        currentY = doc.autoTable.previous.finalY + 10;

                        if (currentY > 180) {
                            doc.addPage();
                            currentY = 15;
                        }

                        // Hanya tambahkan grafik jika ada
                        if (analysisChart) {
                            const chartImgData = analysisChart.toBase64Image('image/png', 1.0);
                            doc.addImage(chartImgData, 'PNG', pageMargin, currentY, contentWidth,
                                contentWidth * 0.5);
                            currentY += (contentWidth * 0.5) + 10;

                            if (currentY > 260) {
                                doc.addPage();
                                currentY = 15;
                            }
                        }

                        const tableHead = [currentDisplayHeader, "Luas (ha)"];
                        const tableBody = currentResults.map(res => {
                            let row = [];
                            row.push(res.groupValue ?? 'N/A');
                            row.push((res.totalArea / 10000).toFixed(4));
                            return row;
                        });

                        doc.autoTable({
                            head: [tableHead],
                            body: tableBody,
                            startY: currentY,
                            theme: 'grid',
                            headStyles: {
                                fillColor: [54, 162, 235]
                            }
                        });
                        currentY = doc.autoTable.previous.finalY;

                        doc.setFontSize(12);
                        doc.setFont("helvetica", "bold");
                        let totalHa = (currentTotalArea / 10000).toFixed(4);

                        doc.text(`Total Luas Irisan: ${totalHa} ha`, 15, currentY + 10);

                        doc.setFontSize(10);
                        doc.setFont("helvetica", "italic");
                        doc.text("Luas yang ditampilkan adalah estimasi luas geodesik.", 15, currentY +
                            15);

                        doc.save(`Hasil-Analisis-Cepat.pdf`);

                        pdfButton.disabled = false;
                        pdfButton.innerHTML = originalButtonText;

                    });
                });


                // Inisialisasi status placeholder saat DOM load
                clearPreview();
                // Fix ukuran peta
                setTimeout(() => previewMap.invalidateSize(), 500);
            });
        </script>
    @endpush

</x-klarifikasi-layout>
