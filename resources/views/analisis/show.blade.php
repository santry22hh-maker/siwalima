<x-klarifikasi-layout>
    <x-slot name="header">
        <h2 class="font-semibold px- text-xl text-gray-800 leading-tight">
            Detail Poligon: {{ $permohonan->id }}
        </h2>
    </x-slot>

    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <style>
            /* ... (Semua CSS Anda sebelumnya tetap sama) ... */
            #map-wrapper {
                min-height: 500px;
                flex-grow: 1;
            }

            #map {
                width: 100%;
                height: 100%;
                z-index: 0;
            }

            #analysisResult table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 8px;
            }

            #analysisResult th,
            #analysisResult td {
                border: 1px solid #ccc;
                padding: 4px 6px;
                font-size: 13px;
            }

            #analysisResult th {
                background: #f0f0f0;
            }

            .dark #analysisResult th {
                background: #374151;
                border-color: #4b5563;
            }

            .dark #analysisResult td {
                border-color: #4b5563;
            }

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
                max-height: 40vh;
                overflow-y: auto;
            }

            .legend-content h4 {
                margin: 0 0 5px;
                font-weight: bold;
                cursor: pointer;
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

    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="grid grid-cols-1 gap-2 sm:gap-2 lg:grid-cols-7">

                <div class="lg:col-span-3 flex flex-col gap-4 p-4">

                    {{-- 1. KOTAK INFORMASI --}}
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 p-4">
                        <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                            <div class="py-2 grid grid-cols-3 gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nama Area</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                    {{ $permohonan->dataSpasial->nama_areal ?? 'N/A' }}</dd>
                            </div>
                            <div class="py-2 grid grid-cols-3 gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tgl. Dibuat</dt>
                                <dd class="text-sm text-gray-900 dark:text-white col-span-2">
                                    {{ $permohonan->created_at->format('d M Y, H:i') }}</dd>
                            </div>
                            @if ($permohonan->dataSpasial && $permohonan->dataSpasial->geojson_path)
                                <div class="py-2 grid grid-cols-3 gap-4">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">File GeoJSON</dt>
                                    <dd class="text-sm text-gray-900 dark:text-white col-span-2 font-mono ">
                                        {{ basename($permohonan->dataSpasial->geojson_path) }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    {{-- 2. KOTAK AKSI (Dropdown & Tombol) --}}
                    <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 flex flex-col gap-4">
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
                            <button id="btnAnalisis"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-blue-500 shadow-theme-xs hover:bg-blue-600">
                                Mulai Analisis
                            </button>
                            <a href="{{ route('data.list') }}"
                                class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-gray-200 dark:bg-gray-800 px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-theme-xs ring-1 ring-inset ring-gray-300 dark:ring-gray-700 transition hover:bg-gray-50 dark:hover:bg-gray-700">
                                Batal
                            </a>
                        </div>
                    </div>

                    {{-- 3. KOTAK GRAFIK --}}
                    <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4"
                        id="chart-container" style="display: none;">
                        <canvas id="analysisChartCanvas"></canvas>
                    </div>

                    {{-- 4. KOTAK HASIL ANALISIS --}}
                    <div id="analysisResult"
                        class="mt-2 p-3 bg-gray-100 dark:bg-gray-800 rounded-md text-sm text-gray-800 dark:text-gray-300">
                        Belum ada analisis yang dilakukan.
                    </div>

                    {{-- 5. KOTAK TOMBOL AKSI (PDF, dll) --}}

                    <div id="nextSteps" class="hidden  flex-row items-center gap-3">

                        {{-- 2. Tambahkan 'flex-1' ke tombol --}}
                        <button id="btnDownloadPdf"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-green-600 px-4 py-3 text-sm font-medium text-white shadow-theme-xs transition hover:bg-green-700">
                            <i class="fas fa-file-pdf"></i> Unduh Hasil (PDF)
                        </button>

                        @if ($permohonan->status === 'Draft')
                            {{-- 3. Tambahkan 'flex-1' ke link --}}
                            <a href="{{ route('permohonananalisis.create', ['from_slug' => $permohonan->slug]) }}"
                                id="btnAjukan"
                                class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-gray-200 px-4 py-3 text-sm font-sm text-gray-700 shadow-theme-xs transition hover:bg-gray-50">
                                <i class="fas fa-paper-plane"></i> Ajukan Permohonan Resmi
                            </a>
                        @else
                            {{-- 4. Tambahkan 'flex-1' ke status div --}}
                            <div
                                class="flex-1 inline-flex items-center justify-center gap-2 rounded-lg bg-yellow-600 px-4 py-3 text-sm font-medium text-white shadow-theme-xs">
                                <i class="fas fa-check-circle"></i> Status: {{ $permohonan->status }}
                            </div>
                        @endif
                    </div>
                </div>

                {{-- KOLOM KANAN (PETA) --}}
                <div
                    class="lg:col-span-4 flex flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div id="map-wrapper" class="rounded-lg overflow-hidden">

                        <div id="map" class="map-container" data-usulan-geojson="{!! htmlspecialchars($usulanGeoJson, ENT_QUOTES, 'UTF-8') !!}"
                            data-slug="{{ $permohonan->slug }}">

                            {{-- HAPUS BARIS data-marker-icon-url DARI SINI --}}

                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Pustaka Eksternal --}}

        {{-- KEMBALIKAN KE LEAFLET VERSI TERBARU --}}
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

        <script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
        <script src="https://unpkg.com/leaflet.browser.print/dist/leaflet.browser.print.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>

        {{-- KEMBALI MENGGUNAKAN 'leaflet-image' --}}
        <script src="https://npmcdn.com/leaflet-image/leaflet-image.js"></script>

        {{-- File map_styles.js Anda --}}
        <script src="{{ asset('src/js/map_styles.js') }}"></script>

        {{-- File JavaScript halaman ini (HAPUS 'defer') --}}
        <script src="{{ asset('src/js/page-analisis-show.js') }}"></script>
    @endpush
</x-klarifikasi-layout>
