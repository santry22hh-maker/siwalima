<x-klarifikasi-layout>
    @push('styles')
        {{-- Memuat CSS Leaflet --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <style>
            #map {
                height: 600px;
                z-index: 0;
            }

            /* Style untuk Legenda */
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

    {{-- Judul Halaman --}}
    <div class="px-2 mb-4">
        <div
            class="flex flex-wrap justify-between items-center gap-4 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
            <div>
                <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">
                    Detail Permohonan
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Kode Pelacakan: <strong>{{ $permohonan->kode_pelacakan }}</strong>
                </p>
            </div>
            <a href="{{ route('permohonananalisis.index') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Daftar
            </a>
        </div>
    </div>

    {{-- Pesan Error (jika survei sudah diisi) --}}
    @if (session('error'))
        <div class="px-2 mb-4">
            <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-lg" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        </div>
    @endif

    {{-- Konten Halaman: Grid 2 Kolom --}}
    <div class="px-2 mb-4">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-5">

            <div class="lg:col-span-2 flex flex-col gap-4">

                @php
                    $status = strtolower($permohonan->status);
                    $permohonan->load('survey');
                @endphp

                {{-- 1. Jika DITOLAK --}}
                @if ($status == 'ditolak')
                    <div class="rounded-lg border border-red-300 bg-red-50 dark:border-red-700 dark:bg-red-900/30">
                        <div class="px-4 py-3 border-b border-red-300 dark:border-red-700">
                            <h4 class="text-base font-medium text-red-800 dark:text-red-300">
                                <i class="fas fa-times-circle mr-2"></i> Permohonan Ditolak
                            </h4>
                        </div>
                        <div class="p-4 space-y-3">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Mohon maaf, permohonan Anda ditolak oleh petugas kami.
                            </p>
                            {{-- TAMPILKAN ALASAN PENOLAKAN --}}
                            <div
                                class="p-3 bg-white rounded-md border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400">Alasan
                                    Penolakan:</label>
                                <p class="text-sm text-gray-700 dark:text-gray-300 italic">
                                    {{ $permohonan->catatan_penelaah ?? 'Tidak ada alasan spesifik yang diberikan.' }}
                                </p>
                            </div>
                        </div>
                        <div class="p-4 border-t border-red-200 dark:border-red-700">
                            {{-- TOMBOL PERBAIKI --}}
                            <a href="{{ route('permohonananalisis.edit', $permohonan->slug) }}"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-yellow-500 shadow-theme-xs hover:bg-yellow-600">
                                <i class="fas fa-edit"></i> Perbaiki dan Ajukan Ulang Permohonan
                            </a>
                        </div>
                    </div>

                    {{-- 2. Jika SELESAI --}}
                @elseif ($status == 'selesai')
                    <div
                        class="rounded-lg border border-green-300 bg-green-50 dark:border-green-700 dark:bg-green-900/30">
                        <div class="px-4 py-3 border-b border-green-300 dark:border-green-700">
                            <h4 class="text-base font-medium text-green-800 dark:text-green-300">
                                <i class="fas fa-check-circle mr-2"></i> Permohonan Selesai
                            </h4>
                        </div>
                        <div class="p-4 space-y-3">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Permohonan Anda telah selesai diproses. Silakan unduh hasil analisis melalui tombol di
                                bawah ini.
                            </p>
                            {{-- Tombol Unduh Surat Balasan --}}
                            @if ($permohonan->file_surat_balasan_path)
                                <a href="{{ Storage::url($permohonan->file_surat_balasan_path) }}" target="_blank"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-center text-blue-700 transition rounded-lg bg-blue-50 ring-1 ring-blue-300 hover:bg-blue-100 dark:bg-blue-900 dark:text-blue-300 dark:ring-blue-700 dark:hover:bg-blue-800">
                                    <i class="fas fa-file-pdf"></i> Unduh Surat Balasan (.pdf)
                                </a>
                            @endif
                            {{-- Tombol Unduh Paket Final --}}
                            @if ($permohonan->file_paket_final_path)
                                <a href="{{ Storage::url($permohonan->file_paket_final_path) }}" target="_blank"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-center text-gray-700 transition rounded-lg bg-gray-50 ring-1 ring-gray-300 hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-300 dark:ring-gray-700 dark:hover:bg-gray-800">
                                    <i class="fas fa-file-archive"></i> Unduh Paket Data Final (.zip)
                                </a>
                            @endif
                        </div>
                        {{-- Tombol Isi Survey --}}
                        <div class="p-4 border-t border-green-200 dark:border-green-700">
                            @if ($permohonan->survey)
                                <div
                                    class="text-center p-3 rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-medium text-green-700 dark:text-green-300">
                                        <i class="fas fa-check-circle mr-1"></i> Terima kasih, Anda sudah mengisi
                                        survei.
                                    </p>
                                </div>
                            @else
                                <a href="{{ route('surveyklarifikasi.create', $permohonan->slug) }}"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-yellow-500 shadow-theme-xs hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700">
                                    <i class="fas fa-star"></i> Isi Survei Kepuasan
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- 3. Jika DIPROSES atau DIAJUKAN --}}
                @else
                    <div class="rounded-lg border border-gray-300 bg-white dark:border-gray-700 dark:bg-gray-800">
                        <div class="px-4 py-3 border-b dark:border-gray-700">
                            <h4 class="text-base font-medium text-gray-800 dark:text-white/90">
                                Status Permohonan
                            </h4>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                                @php
                                    $badgeColor =
                                        $status == 'diproses'
                                            ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300'
                                            : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                @endphp
                                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $badgeColor }}">
                                    {{ Str::title($permohonan->status) }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 pt-2">
                                Permohonan Anda sedang ditinjau oleh tim kami. Notifikasi akan dikirimkan melalui email
                                setelah permohonan selesai diproses.
                            </p>
                        </div>
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
                            Data Areal & Unduhan
                        </h4>
                    </div>
                    @if ($permohonan->dataSpasial)
                        {{-- ... (kode Anda untuk menampilkan Nama, Kabupaten, Luas, Sumber) ... --}}

                        <div class="p-4 border-t dark:border-gray-700 space-y-2">
                            @if ($permohonan->dataSpasial->shapefile_path)
                                <a href="{{ Storage::url($permohonan->dataSpasial->shapefile_path) }}" ...>
                                    <i class="fas fa-file-archive"></i> Unduh Shapefile Asli (.zip)
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="p-4">
                            <p class="text-sm text-gray-500">Data spasial tidak ditemukan.</p>
                        </div>
                    @endif

                    {{-- Muat relasi survei (ini penting agar ->survey tidak error) --}}
                    @php $permohonan->load('survey'); @endphp

                    @if (strtolower($permohonan->status) == 'selesai')
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                            @if ($permohonan->survey)
                                {{-- Jika survei sudah diisi --}}
                                <div
                                    class="text-center p-3 rounded-lg bg-green-100 dark:bg-green-900 border border-green-200 dark:border-green-700">
                                    <p class="text-sm font-medium text-green-700 dark:text-green-300">
                                        <i class="fas fa-check-circle mr-1"></i> Terima kasih, Anda sudah mengisi
                                        survei
                                        untuk layanan ini.
                                    </p>
                                </div>
                            @else
                                {{-- Jika survei BELUM diisi --}}

                                <a href="{{ route('surveyklarifikasi.create', $permohonan->slug) }}"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-yellow-500 shadow-theme-xs hover:bg-yellow-600">
                                    <i class="fas fa-star"></i> Isi Survei Kepuasan
                                </a>
                            @endif
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


    @push('scripts')
        {{-- Memuat JS Leaflet --}}
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

        <script src="{{ asset('src/js/map_styles.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const mapElement = document.getElementById('map');
                const geojsonUrl = mapElement.dataset.geojsonUrl;

                // 1. Inisialisasi Peta & Basemap DULUAN
                const map = L.map('map').setView([-3.69, 128.17], 9);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors',
                    crossOrigin: 'Anonymous'
                }).addTo(map);

                // Variabel untuk layer
                const polygonLayer = L.featureGroup().addTo(map);
                let kawasanHutanLayer = null;
                let pl2023Layer = null;

                // 2. Tambahkan Kontrol Layer
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
                baseMaps["Peta Jalan"].addTo(map); // Set default

                const overlayMaps = {
                    "Area Permohonan": polygonLayer
                };
                const layerControl = L.control.layers(baseMaps, overlayMaps, {
                    position: 'topright'
                }).addTo(map);

                // 3. Cek apakah ada URL GeoJSON
                if (!geojsonUrl || geojsonUrl.trim() === "") {
                    map.openPopup('<p class="text-center p-2">Data spasial tidak ditemukan.</p>', map.getCenter());
                } else {
                    // 4. Jika URL ADA, ambil (fetch) file GeoJSON
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
                                    color: '#007cff', // Biru
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

                // 5. Fungsi untuk Legenda
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

                // 6. Muat Layer Overlay (Data Dasar)
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

                // 7. Tambahkan Kontrol Legenda
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

                // 8. Fix ukuran peta
                setTimeout(() => {
                    map.invalidateSize();
                }, 500);
            });
        </script>
    @endpush
</x-klarifikasi-layout>
