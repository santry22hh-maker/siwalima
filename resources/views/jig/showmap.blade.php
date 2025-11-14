<x-jig-layout>
    @push('styles')
        {{-- 1. STYLESHEET (Termasuk Fullscreen & Geolocation) --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@latest/Control.FullScreen.css" />
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.css" />

        <style>
            /* 2. UKURAN PETA DIBUAT RESPONSIF */
            #map {
                height: 80vh;
                /* Tinggi 80% dari layar */
                width: 100%;
                border-radius: 0.5rem;
                /* rounded-lg */
            }

            /* 3. PERBAIKAN LEGENDA (LEBIH MODERN & DARK MODE) */
            .legend-control-container {
                background-color: rgba(255, 255, 255, 0.9);
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
                border-radius: 8px;
                transition: all 0.3s ease;
                max-height: 50vh;
                overflow-y: auto;
            }

            .dark .legend-control-container {
                background-color: rgba(30, 41, 59, 0.9);
                color: #cbd5e1;
                border: 1px solid #334155;
            }

            .legend-toggle-button {
                width: 36px;
                height: 36px;
                line-height: 36px;
                font-size: 1.5rem;
                font-family: 'Georgia', serif;
                font-weight: bold;
                text-align: center;
                cursor: pointer;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            }

            .dark .legend-toggle-button {
                background-color: #1e293b;
                color: #e2e8f0;
                border: 1px solid #334155;
            }

            .legend-content {
                display: none;
                padding: 12px;
                line-height: 1.5;
                font-size: 13px;
                color: #333;
            }

            .dark .legend-content {
                color: #cbd5e1;
            }

            .legend-content h4 {
                margin: 0 0 10px;
                font-weight: bold;
                font-size: 1rem;
                border-bottom: 1px solid #eee;
                padding-bottom: 5px;
            }

            .dark .legend-content h4 {
                border-bottom-color: #334155;
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

            /* Style untuk Geolocation */
            .leaflet-control-locate a {
                font-size: 1.2rem;
            }

            .leaflet-control-locate.active a {
                color: #2563eb;
            }
        </style>
    @endpush

    <main>
        <div class="px-2 mb-4">
            <div
                class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
                <div id="map-wrapper" class="flex-grow rounded-lg overflow-hidden">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </main>

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet.fullscreen@latest/Control.FullScreen.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/leaflet.locatecontrol@0.79.0/dist/L.Control.Locate.min.js"></script>

        {{-- Pastikan file ini ada dan berisi variabel style Anda --}}
        <script src="{{ asset('src/js/map_styles.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // 1. BASEMAP LEBIH MODERN
                const positron = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
                });
                const darkMatter = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
                });
                const satellite = L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Tiles &copy; Esri'
                    });

                // Tentukan basemap default berdasarkan tema
                const isDark = window.Alpine.store('theme').isDark;
                const defaultBasemap = isDark ? darkMatter : positron;

                // 2. Inisialisasi Peta
                const map = L.map('map', {
                    layers: [defaultBasemap] // Muat basemap default
                }).setView([-5.7, 130.5], 7);

                // 3. Kontrol Layer
                const baseMaps = {
                    "Peta Light (Modern)": positron,
                    "Peta Dark (Modern)": darkMatter,
                    "Citra Satelit": satellite
                };
                const overlayMaps = {};
                const layerControl = L.control.layers(baseMaps, overlayMaps, {
                    position: 'topright'
                }).addTo(map);

                // 4. KONTROL TAMBAHAN
                map.addControl(L.control.fullscreen()); // <-- PERBAIKAN: huruf 'f' kecil
                L.control.scale({
                    imperial: false,
                    position: 'bottomleft'
                }).addTo(map);
                L.control.locate({
                    position: 'topleft',
                    strings: {
                        title: "Tampilkan lokasi saya"
                    },
                    flyTo: true
                }).addTo(map);

                // 5. STYLE HIGHLIGHT
                const highlightStyle = {
                    weight: 3,
                    color: '#00FFFF', // Cyan
                    fillOpacity: 0.5
                };

                // Variabel untuk menyimpan referensi layer
                let kawasanHutanLayer = null;
                let pl2023Layer = null;
                let PPTPKHLayer = null;

                // 6. Pemuatan Data Layer (DENGAN FUNGSI INTERAKTIF)
                fetch("{{ asset('DataDasar/KwsHutan_Maluku250.geojson') }}").then(r => r.json()).then(data => {
                    kawasanHutanLayer = L.geoJSON(data, {
                        style: styleKawasanHutan,
                        onEachFeature: function(feature, layer) { // Didefinisikan di sini
                            // Popup
                            const prop = feature.properties;
                            // Pastikan Anda menggunakan 'FUNGSIKWS' (sesuai nama atribut di file GeoJSON Anda)
                            if (prop && prop.FUNGSIKWS) {
                                layer.bindPopup(`<b>Kawasan Hutan</b><br>${prop.FUNGSIKWS}`);
                            }
                            // === AKHIR PERBAIKAN ===
                            // Highlight
                            layer.on({
                                mouseover: (e) => e.target.setStyle(highlightStyle),
                                mouseout: (e) => kawasanHutanLayer.resetStyle(e.target)
                            });
                        }
                    });
                    layerControl.addOverlay(kawasanHutanLayer, "Kawasan Hutan");
                }).catch(err => console.error("Gagal memuat data Kawasan Hutan:", err));

                fetch("{{ asset('DataDasar/Pl2023_Maluku250.geojson') }}").then(r => r.json()).then(data => {
                    pl2023Layer = L.geoJSON(data, {
                        style: stylePL2023,
                        onEachFeature: function(feature, layer) { // Didefinisikan di sini
                            const prop = feature.properties;
                            if (prop && prop.PL2023_ID) {
                                layer.bindPopup(`<b>Tutupan Lahan</b><br>${prop.PL2023_ID}`);
                            }
                            layer.on({
                                mouseover: (e) => e.target.setStyle(highlightStyle),
                                mouseout: (e) => pl2023Layer.resetStyle(e.target)
                            });
                        }
                    });
                    layerControl.addOverlay(pl2023Layer, "Tutupan Lahan 2023");
                }).catch(err => console.error("Gagal memuat data PL2023:", err));

                fetch("{{ asset('DataDasar/PPTPKH_Revisi_II.geojson') }}").then(r => r.json()).then(data => {
                    PPTPKHLayer = L.geoJSON(data, {
                        style: stylePPTPKH,
                        onEachFeature: function(feature, layer) { // Didefinisikan di sini
                            const prop = feature.properties;
                            if (prop && prop.KRITERIA) {
                                layer.bindPopup(`<b>Indikatif PPTPKH</b><br>${prop.KRITERIA}`);
                            }
                            layer.on({
                                mouseover: (e) => e.target.setStyle(highlightStyle),
                                mouseout: (e) => PPTPKHLayer.resetStyle(e.target)
                            });
                        }
                    });
                    layerControl.addOverlay(PPTPKHLayer, "Indikatif PPTPKH");
                }).catch(err => console.error("Gagal memuat data PPTPKH:", err));

                // 7. Kontrol Legenda Interaktif (Kode Anda)
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
                    const contentDiv = L.DomUtil.create('div', 'legend-content', container);

                    if (isDark) L.DomUtil.addClass(container, 'dark'); // Tambahkan dark mode

                    L.DomEvent.on(button, 'click', e => {
                        L.DomEvent.stop(e);
                        L.DomUtil.addClass(container, 'active');
                        updateLegend();
                    });
                    L.DomEvent.disableClickPropagation(container);
                    return container;
                };
                legend.addTo(map);

                // 8. Fungsi Legenda Dinamis (Kode Anda, sedikit dimodifikasi)
                function updateLegend() {
                    const legendContentDiv = document.querySelector('.legend-content');
                    if (!legendContentDiv) return;

                    let content =
                        '<h4 style="cursor: pointer;" title="Sembunyikan Legenda">Legenda &times;</h4>'; // Ikon tutup
                    let hasContent = false;

                    if (kawasanHutanLayer && map.hasLayer(kawasanHutanLayer)) {
                        content += '<b>Kawasan Hutan</b><br>';
                        for (const key in kawasanHutanStyles) {
                            content +=
                                `<i style="background:${kawasanHutanStyles[key].color}"></i> ${kawasanHutanStyles[key].label}<br>`;
                        }
                        hasContent = true;
                    }
                    if (pl2023Layer && map.hasLayer(pl2023Layer)) {
                        content += hasContent ? '<br>' : '';
                        content += '<b>Tutupan Lahan</b><br>';
                        for (const key in pl2023Styles) {
                            content +=
                                `<i style="background:${pl2023Styles[key].color}"></i> ${pl2023Styles[key].label}<br>`;
                        }
                        hasContent = true; // Ditambahkan
                    }
                    if (PPTPKHLayer && map.hasLayer(PPTPKHLayer)) {
                        content += hasContent ? '<br>' : '';
                        content += '<b>Kriteria PPTPKH</b><br>';
                        for (const key in PPTPKHStyles) {
                            content +=
                                `<i style="background:${PPTPKHStyles[key].color}"></i> ${PPTPKHStyles[key].label}<br>`;
                        }
                    }
                    legendContentDiv.innerHTML = content;

                    // Event listener untuk tombol 'x' (close)
                    L.DomEvent.on(legendContentDiv.querySelector('h4'), 'click', e => {
                        L.DomEvent.stop(e);
                        L.DomUtil.removeClass(legendContentDiv.parentElement, 'active');
                    });
                }

                // 9. Event Listener untuk sinkronisasi legenda
                map.on('overlayadd overlayremove', updateLegend);

                // 10. SINKRONISASI DARK MODE
                window.Alpine.effect(() => {
                    const isDark = window.Alpine.store('theme').isDark;
                    const legendContainer = document.querySelector('.legend-control-container');

                    if (isDark) {
                        if (!map.hasLayer(darkMatter)) map.addLayer(darkMatter);
                        if (map.hasLayer(positron)) map.removeLayer(positron);
                        if (legendContainer) L.DomUtil.addClass(legendContainer, 'dark');
                    } else {
                        if (!map.hasLayer(positron)) map.addLayer(positron);
                        if (map.hasLayer(darkMatter)) map.removeLayer(darkMatter);
                        if (legendContainer) L.DomUtil.removeClass(legendContainer, 'dark');
                    }
                });
            });
        </script>
    @endpush
</x-jig-layout>
