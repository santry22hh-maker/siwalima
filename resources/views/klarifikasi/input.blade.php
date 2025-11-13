<x-klarifikasilayout>
    @push('styles')
        {{-- <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" /> --}}

        <style>
            #map-wrapper {
                min-height: 700px;
            }

            #preview-map {
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

            .menu-item-inactive {
                /* Garis bawah transparan, teks abu-abu */
                border-color: transparent;
                color: #6b7280;
                /* gray-500 */
            }

            .menu-item-inactive:hover {
                /* Hover abu-abu muda */
                border-color: #d1d5db;
                /* gray-300 */
                color: #374151;
                /* gray-700 */
            }
        </style>
    @endpush

    <div class="px-2 mb-2">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-2">
            <div class="w-full py-2 sm:px-6 lg:px-8">
                <h4 class="border-gray-200 text-base font-medium text-gray-800 dark:border-gray-800 dark:text-white/90">
                    Input Data Spasial
                </h4>
                <p class="border-gray-200 text-sm  text-gray-800 dark:border-gray-800 dark:text-white/90 ">
                    Unggah foto dengan geotag, shapefile (.zip) atau masukkan
                    koordinat
                    poligon secara manual untuk menambahkannya ke peta. Pratinjau akan muncul di peta sebelum
                    disimpan.</p>
            </div>

        </div>
    </div>

    {{-- Mulai Input --}}
    <div class="px-2 mb-4">
        <div class="grid grid-cols-1 gap-2 sm:gap-2 lg:grid-cols-7">
            <div
                class="lg:col-span-3 flex flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="space-y-6  border-gray-100 p-4 sm:p-4 dark:border-gray-800">
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
                            <p class="font-bold">Error</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif
                    <form id="input-form" action="{{ route('klarifikasi.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="source_type" id="source_type" value="photo">
                        <input type="hidden" name="geojson_data" id="geojson_data">

                        <div>
                            <label for="lokasi"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Lokasi</label>
                            <input type="text" name="lokasi" id="lokasi" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Contoh: Desa Sukamaju, Kecamatan Cianjur">
                        </div>
                        <div class="mt-3">
                            <label for="kabupaten"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400">Kabupaten</label>
                            <input type="text" name="kabupaten" id="kabupaten" required
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Contoh: Kabupaten Cianjur">
                        </div>

                        <div class="mt-3">
                            <label for="keterangan"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                placeholder="Tambahkan catatan atau keterangan lain..."></textarea>
                        </div>

                        {{-- Area Tab Input --}}
                        <div class="rounded-lg border border-gray-200 px-4 dark:border-gray-800 mt-3"
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
                                    <div>
                                        <label for="userid"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-400">User
                                            ID</label>
                                        <input type="text" name="userid" id="userid"
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                            placeholder="Masukkan ID pengguna">
                                    </div>
                                    <div class="mt-3">
                                        <label for="groupid"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-400">Group
                                            ID</label>
                                        <input type="text" name="groupid" id="groupid"
                                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                            placeholder="Masukkan ID grup laporan">
                                    </div>
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
                                            stroke="currentColor" fill="none" viewBox="0 0 48 48"
                                            aria-hidden="true">
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

                        {{-- ERROR BOX INLINE (SARAN 1) --}}
                        <div id="js-error-box"
                            class="hidden w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative my-3"
                            role="alert">
                            <strong class="font-bold">Error: </strong>
                            <span class="block sm:inline" id="js-error-message"></span>
                        </div>

                        {{-- Tombol Aksi Form --}}
                        <div class="pt-5 border-t border-gray-200 flex items-center gap-4">
                            <button type="submit" id="btn-submit" disabled
                                class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-black bg-green-600 hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:bg-gray-400 disabled:cursor-not-allowed">
                                {{-- SPAN UNTUK LOADING (SARAN 2) --}}
                                <span id="btn-submit-text">Simpan Poligon</span>
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

                {{-- INFO BOX PLACEHOLDER (SARAN 3) --}}
                <div id="file-info-box"
                    class="bg-white border border-gray-300 rounded-lg p-3 mb-4 text-sm space-y-1  dark:border-gray-800 dark:bg-white/[0.03]">
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
                    <div id="preview-map" class="h-full w-full"></div>
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
        <script src="{{ asset('src/js/map_styles.js') }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // 1. INISIALISASI PETA & VARIABEL
                const previewMap = L.map('preview-map').setView([-3.69, 128.17], 9);
                const shapefileUpload = document.getElementById('shapefile-upload');
                const photoUpload = document.getElementById('photo-upload');
                const fileInfoContent = document.getElementById('file-info-content');
                const manualCoords = document.getElementById('manual-coords');
                const btnPreviewManual = document.getElementById('btn-preview-manual');
                const btnSubmit = document.getElementById('btn-submit');
                const geojsonField = document.getElementById('geojson_data');
                // const nameField = document.getElementById('name'); // <-- DIHAPUS
                const btnClearMap = document.getElementById('btn-clear-map');
                const luasInfo = document.getElementById('luas-info');
                let kawasanHutanLayer = null;
                let pl2023Layer = null;

                // Variabel untuk elemen UI baru
                const jsErrorBox = document.getElementById('js-error-box');
                const jsErrorMessage = document.getElementById('js-error-message');
                const btnSubmitText = document.getElementById('btn-submit-text');


                // 2. SETUP BASEMAP & KONTROL LAYER
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
                const drawnItems = new L.FeatureGroup();
                previewMap.addLayer(drawnItems);
                const overlayMaps = {
                    "Area Usulan": drawnItems
                };
                const layerControl = L.control.layers(baseMaps, overlayMaps, {
                    position: 'topright'
                }).addTo(previewMap);

                // 3. FUNGSI LEGENDA DINAMIS
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

                // 4. PEMUATAN LAYER OVERLAY
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

                // 5. KONTROL LEGENDA
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

                // 6. FUNGSI INTERAKTIF INPUT & DRAWING

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
                        btnSubmit.disabled = true;
                        btnSubmitText.textContent = 'Sedang Memproses...';
                        shapefileUpload.disabled = true;
                        photoUpload.disabled = true;
                        manualCoords.disabled = true;
                        btnPreviewManual.disabled = true;
                        btnClearMap.disabled = true;
                    } else {
                        btnSubmitText.textContent = 'Simpan Poligon';
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
                    btnSubmit.disabled = false;
                }

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
                });

                previewMap.on(L.Draw.Event.EDITED, e => e.layers.eachLayer(layer => updateGeoJsonInput(layer)));
                previewMap.on(L.Draw.Event.DELETED, () => {
                    if (drawnItems.getLayers().length === 0) {
                        geojsonField.value = '';
                        btnSubmit.disabled = true;
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
                        shp(event.target.result).then(updatePreview)
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
                                name: 'Poligon dari Foto' // <-- JS Diubah
                            },
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
                                name: 'Poligon Manual' // <-- JS Diubah
                            },
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

                // Inisialisasi status placeholder saat DOM load
                clearPreview();
                // Fix ukuran peta
                setTimeout(() => previewMap.invalidateSize(), 500);
            });
        </script>
    @endpush
    </x-layout-klarifikasi>
