<div id="panelID" class="sidepanel" aria-label="side panel" aria-hidden="false">
    <div class="sidepanel-inner-wrapper">
        <nav class="sidepanel-tabs-wrapper" aria-label="sidepanel tab navigation">
            <ul class="sidepanel-tabs">
                {{-- TAB 1: LAYER --}}
                <li class="sidepanel-tab">
                    <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                d="M11.99 2.005L2 7.002l9.99 4.997 9.99-4.997L11.99 2.005zM2 12l9.99 5 9.99-5V7l-9.99 5-9.99-5v5zm0 5l9.99 5 9.99-5v-2l-9.99 5-9.99-5v2z" />
                        </svg>
                    </a>
                </li>
                {{-- TAB 2: LEGENDA --}}
                <li class="sidepanel-tab">
                    <a href="#" class="sidebar-tab-link" role="tab" data-tab-link="tab-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                            viewBox="0 0 24 24">
                            <path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z" />
                        </svg>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="sidepanel-content-wrapper">
            <div class="sidepanel-content">

                {{-- ISI TAB 1: KONTROL LAYER --}}
                <div class="sidepanel-tab-content" data-tab-content="tab-1">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b">Kontrol Peta</h3>

                    {{-- Peta Dasar --}}
                    <div class="mb-4">
                        <h4 class="text-xs font-bold text-gray-500 uppercase mb-2">Peta Dasar</h4>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="basemap" value="google" checked
                                    class="text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm font-medium text-gray-700">Citra Satelit</span>
                            </label>
                            <label class="flex items-center gap-3 rounded hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="basemap" value="osm"
                                    class="text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm font-medium text-gray-700">OpenStreetMap</span>
                            </label>

                        </div>
                    </div>

                    {{-- Layer Data (CHECKBOX) --}}
                    <div>
                        <h4 class="text-xs font-bold text-gray-500 uppercase mb-3">Layer Data</h4>
                        <div class="space-y-2">
                            <div>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" id="toggle-kh" checked
                                        class="rounded text-emerald-600 w-5 h-5">
                                    <span class="text-sm font-bold text-gray-800">Kawasan Hutan</span>
                                </label>
                            </div>
                            <div>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" id="toggle-pl" class="rounded text-emerald-600 w-5 h-5">
                                    <span class="text-sm font-bold text-gray-800">Penutupan Lahan
                                        2023</span>
                                </label>
                            </div>
                            <div>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" id="toggle-pptpkh" class="rounded text-emerald-600 w-5 h-5">
                                    <span class="text-sm font-bold text-gray-800">Indikatif PPTPKH</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ISI TAB 2: LEGENDA (KOSONG, AKAN DIISI JS) --}}
                <div class="sidepanel-tab-content" data-tab-content="tab-2">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Legenda Aktif</h3>
                    <div class="space-y-6">
                        {{-- Wadah Legenda --}}
                        <div id="legend-group-kh">
                            <h4 class="text-xs font-bold text-emerald-700 uppercase mb-2">Kawasan Hutan
                            </h4>
                            <div id="legend-container-kh" class="grid grid-cols-1 gap-2 pl-2"></div>
                        </div>

                        <div id="legend-group-pl" style="display:none;">
                            <h4 class="text-xs font-bold text-emerald-700 uppercase mb-2">Penutupan Lahan
                            </h4>
                            <div id="legend-container-pl" class="grid grid-cols-1 gap-2 pl-2"></div>
                        </div>

                        <div id="legend-group-pptpkh" style="display:none;">
                            <h4 class="text-xs font-bold text-emerald-700 uppercase mb-2">Kriteria PPTPKH
                            </h4>
                            <div id="legend-container-pptpkh" class="grid grid-cols-1 gap-2 pl-2"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="sidepanel-toggle-container">
        <button class="sidepanel-toggle-button" type="button" aria-label="toggle side panel"></button>
    </div>
</div>

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.fullscreen@latest/Control.FullScreen.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    {{-- JS Sidepanel Lokal --}}
    <script src="{{ asset('src/js/leaflet-sidepanel.min.js') }}"></script>

    {{-- JS Style Map Lokal --}}
    <script src="{{ asset('src/js/map_styles.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. INIT MAP
            const map = L.map('map', {
                zoomControl: false
            }).setView([-3.6, 128.1], 8);

            // 2. INIT SIDEPANEL
            const panelRight = L.control.sidepanel('panelID', {
                panelPosition: 'right',
                hasTabs: true,
                tabsPosition: 'top',
                pushControls: true,
                darkMode: false,
                startTab: 'tab-1'
            }).addTo(map);

            // 3. BASEMAPS
            const basemaps = {
                'google': L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
                    maxZoom: 20,
                    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
                }),
                'osm': L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'),
                // 'dark': L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png')
            };
            let currentBasemap = basemaps['google'];
            map.addLayer(currentBasemap);

            // 4. CONTROLS
            L.control.zoom({
                position: 'topleft'
            }).addTo(map);
            L.control.fullscreen({
                position: 'topleft'
            }).addTo(map);
            L.control.scale({
                position: 'bottomleft',
                imperial: false
            }).addTo(map);
            L.control.locate({
                position: 'topleft'
            }).addTo(map);

            // 5. LAYER GROUPS
            const layerGroups = {
                kh: L.layerGroup().addTo(map),
                pl: L.layerGroup(),
                pptpkh: L.layerGroup()
            };

            // --- 6. FUNGSI GENERATE LEGENDA OTOMATIS ---
            // Fungsi ini mengambil style dari map_styles.js dan memasukkannya ke HTML
            function generateLegendHTML(styleObj, containerId) {
                const container = document.getElementById(containerId);
                if (!container || !styleObj) return;

                let html = '';
                Object.values(styleObj).forEach(item => {
                    // Hapus kode angka di belakang label (jika ada) untuk tampilan lebih bersih
                    // Contoh: "Hutan Lindung (100100)" -> "Hutan Lindung"
                    let cleanLabel = item.label.replace(/\(\d+\)$/, '').trim();

                    html += `
                        <div class="flex items-center text-xs text-gray-600 dark:text-gray-300">
                            <span class="w-3 h-3 rounded-full mr-2 border border-gray-200 dark:border-gray-600 flex-shrink-0" 
                                  style="background-color: ${item.color}"></span>
                            <span>${cleanLabel}</span>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }

            // Panggil fungsi generate untuk mengisi konten legenda
            // Pastikan map_styles.js sudah termuat
            if (typeof kawasanHutanStyles !== 'undefined') generateLegendHTML(kawasanHutanStyles,
                'legend-container-kh');
            if (typeof pl2023Styles !== 'undefined') generateLegendHTML(pl2023Styles, 'legend-container-pl');
            if (typeof PPTPKHStyles !== 'undefined') generateLegendHTML(PPTPKHStyles, 'legend-container-pptpkh');


            // --- 7. FUNGSI UPDATE TAMPILAN LEGENDA (SHOW/HIDE) ---
            function updateLegendVisibility() {
                const isKhChecked = document.getElementById('toggle-kh').checked;
                const isPlChecked = document.getElementById('toggle-pl').checked;
                const isPptpkhChecked = document.getElementById('toggle-pptpkh').checked;

                document.getElementById('legend-group-kh').style.display = isKhChecked ? 'block' : 'none';
                document.getElementById('legend-group-pl').style.display = isPlChecked ? 'block' : 'none';
                document.getElementById('legend-group-pptpkh').style.display = isPptpkhChecked ? 'block' : 'none';
            }
            // Panggil sekali di awal
            updateLegendVisibility();


            // 8. POPUP FUNCTION (Sama seperti sebelumnya)
            function createPopupContent(title, properties) {
                const labelMap = {
                    'FUNGSIKWS': 'Fungsi Kawasan',
                    'NAMOBJ': 'Nama Objek',
                    'REMARK': 'Keterangan',
                    'TUTUPAN_LA': 'Tutupan Lahan',
                    'KRITERIA': 'Kriteria',
                    'Progres': 'Status',
                    'LUAS_HA': 'Luas (Ha)',
                    'SK_MENHUT': 'SK Menhut',
                    'PL2023_ID': 'Klasifikasi PL'
                };
                let fieldsToShow = [];
                if (title.includes('Kawasan Hutan')) fieldsToShow = ['NAMOBJ', 'FUNGSIKWS', 'LUAS_HA', 'SK_MENHUT'];
                else if (title.includes('Penutupan Lahan')) fieldsToShow = ['PL2023_ID', 'TUTUPAN_LA', 'REMARK'];
                else if (title.includes('PPTPKH')) fieldsToShow = ['KRITERIA', 'Progres'];
                if (fieldsToShow.length === 0) fieldsToShow = Object.keys(properties).filter(key => labelMap[key]);

                let rows = '';
                let hasData = false;
                fieldsToShow.forEach(key => {
                    if (properties[key] !== undefined && properties[key] !== null) {
                        hasData = true;
                        let displayValue = properties[key];
                        let rawValue = properties[key];
                        if (key === 'FUNGSIKWS' && typeof kawasanHutanStyles !== 'undefined' &&
                            kawasanHutanStyles[rawValue]) displayValue = kawasanHutanStyles[rawValue].label
                            .replace(/\(\d+\)$/, '');
                        else if (key === 'PL2023_ID' && typeof pl2023Styles !== 'undefined' && pl2023Styles[
                                String(rawValue)]) displayValue = pl2023Styles[String(rawValue)].label
                            .replace(/\(\d+\)$/, '');
                        else if (key === 'KRITERIA' && typeof PPTPKHStyles !== 'undefined' && PPTPKHStyles[
                                rawValue]) displayValue = PPTPKHStyles[rawValue].label;
                        rows += `<div style="display:flex; justify-content:space-between; border-bottom:1px solid #eee; padding:4px 0; font-size:12px;">
                                    <span style="font-weight:bold; color:#666; width:40%;">${labelMap[key] || key}</span>
                                    <span style="font-weight:500; color:#333; width:60%; text-align:right;">${displayValue}</span>
                                 </div>`;
                    }
                });
                if (!hasData) rows =
                    `<div style="font-size:12px; color:#999; text-align:center;">Detail tidak tersedia</div>`;
                return `<div class="popup-header">${title}</div><div class="popup-body">${rows}</div>`;
            }

            // 9. LOAD DATA
            function loadData(url, group, style, name) {
                fetch(url).then(r => r.json()).then(data => {
                    const gl = L.geoJSON(data, {
                        style: style,
                        onEachFeature: (f, l) => {
                            l.bindPopup(createPopupContent(name, f.properties));
                            l.on({
                                mouseover: (e) => {
                                    e.target.setStyle({
                                        weight: 3,
                                        color: '#00FFFF',
                                        fillOpacity: 0.7
                                    });
                                    e.target.bringToFront();
                                },
                                mouseout: (e) => {
                                    gl.resetStyle(e.target);
                                }
                            });
                        }
                    });
                    group.addLayer(gl);
                    if (name === 'Kawasan Hutan') document.getElementById('map-loader').style.display =
                        'none';
                }).catch(e => {
                    console.error(e);
                    if (name === 'Kawasan Hutan') document.getElementById('map-loader').style.display =
                        'none';
                });
            }

            loadData("{{ asset('DataDasar/KwsHutan_Maluku250.geojson') }}", layerGroups.kh, styleKawasanHutan,
                "Kawasan Hutan");
            loadData("{{ asset('DataDasar/Pl2023_Maluku250.geojson') }}", layerGroups.pl, stylePL2023,
                "Penutupan Lahan");
            loadData("{{ asset('DataDasar/PPTPKH_Revisi_II.geojson') }}", layerGroups.pptpkh, stylePPTPKH,
                "Indikatif PPTPKH");

            // 10. LISTENERS
            document.querySelectorAll('input[name="basemap"]').forEach(radio => {
                radio.addEventListener('change', (e) => {
                    map.removeLayer(currentBasemap);
                    currentBasemap = basemaps[e.target.value];
                    map.addLayer(currentBasemap);
                });
            });

            // Toggle Layer + Update Legend
            document.getElementById('toggle-kh').addEventListener('change', e => {
                e.target.checked ? map.addLayer(layerGroups.kh) : map.removeLayer(layerGroups.kh);
                updateLegendVisibility();
            });
            document.getElementById('toggle-pl').addEventListener('change', e => {
                e.target.checked ? map.addLayer(layerGroups.pl) : map.removeLayer(layerGroups.pl);
                updateLegendVisibility();
            });
            document.getElementById('toggle-pptpkh').addEventListener('change', e => {
                e.target.checked ? map.addLayer(layerGroups.pptpkh) : map.removeLayer(layerGroups.pptpkh);
                updateLegendVisibility();
            });

            // 11. MEASURE TOOL
            var drawnItems = new L.FeatureGroup();
            map.addLayer(drawnItems);
            var drawControl = new L.Control.Draw({
                position: 'topleft',
                draw: {
                    polyline: {
                        metric: true,
                        shapeOptions: {
                            color: '#f357a1'
                        }
                    },
                    polygon: {
                        allowIntersection: false,
                        showArea: true,
                        metric: true,
                        shapeOptions: {
                            color: '#bada55'
                        }
                    },
                    rectangle: false,
                    circle: false,
                    marker: false,
                    circlemarker: false
                },
                edit: {
                    featureGroup: drawnItems,
                    remove: true
                }
            });
            map.addControl(drawControl);
            map.on(L.Draw.Event.CREATED, function(e) {
                drawnItems.addLayer(e.layer);
            });
        });
    </script>
@endpush
