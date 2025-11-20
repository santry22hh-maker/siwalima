<x-klarifikasi-layout>
    @push('styles')
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/2.0.7/css/dataTables.tailwindcss.min.css">
        <style>
            /* Kontainer utama (Show entries, Search, Info, Paging) */
            div.dt-layout-row {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                padding: 0.75rem 1rem;
                border-bottom: 1px solid #f3f4f6;
            }

            .dark div.dt-layout-row {
                border-color: #1f2937;
            }

            /* Posisikan tabel */
            div.dt-layout-row.dt-layout-table {
                padding: 0;
                margin: 0;
                border-bottom: none;
                overflow-x: auto;
            }

            div.dt-layout-row.dt-layout-table>div {
                width: 100%;
            }

            /* Border paginasi */
            div.dt-layout-row:last-child {
                border-top: 1px solid #f3f4f6;
                border-bottom: none;
            }

            .dark div.dt-layout-row:last-child {
                border-color: #1f2937;
            }

            /* Kontrol Atas (Entries & Search) */
            div.dt-length {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            div.dt-search {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            /* Label */
            div.dt-length label,
            div.dt-search label {
                font-size: 0.875rem;
                font-weight: 500;
                color: #374151;
            }

            .dark div.dt-length label,
            .dark div.dt-search label {
                color: #9ca3af;
            }

            /* Input dan Select */
            div.dt-search input,
            div.dt-length select {
                height: 2.25rem;
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
                color: #1f2937;
                background-color: #ffffff;
                border: 1px solid #d1d5db;
                border-radius: 0.5rem;
                box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            }

            /* Dropdown */
            div.dt-length select {
                width: 5.5rem;
                padding-right: 2rem;
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
                background-position: right 0.5rem center;
                background-repeat: no-repeat;
                background-size: 1.5em 1.5em;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
            }

            .dark div.dt-length select {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            }

            /* Dark mode Input/Select */
            .dark div.dt-search input,
            .dark div.dt-length select {
                color: rgba(255, 255, 255, 0.9);
                background-color: #111827;
                border-color: #374151;
            }

            div.dt-search input {
                width: 100%;
            }

            @media (min-width: 640px) {
                div.dt-search input {
                    width: auto;
                }
            }

            div.dt-search input::placeholder {
                color: #9ca3af;
            }

            .dark div.dt-search input::placeholder {
                color: rgba(255, 255, 255, 0.3);
            }

            /* Focus state */
            div.dt-search input:focus,
            div.dt-length select:focus {
                border-color: #9cb9ff;
                box-shadow: 0 0 0 3px rgba(70, 95, 255, 0.1);
                outline: none;
            }

            .dark div.dt-search input:focus,
            .dark div.dt-length select:focus {
                border-color: #252dae;
            }

            /* --- CSS PAGINASI --- */
            div.dt-info {
                padding-top: 0.5rem;
                font-size: 0.875rem;
                color: #4b5563;
            }

            .dark div.dt-info {
                color: #9ca3af;
            }

            div.dt-paging {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 0.25rem;
            }

            @media (max-width: 640px) {
                div.dt-paging {
                    justify-content: center;
                }
            }

            div.dt-paging .dt-paging-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 2.25rem;
                height: 2.25rem;
                padding: 0.25rem 0.75rem;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                font-weight: 500;
                color: #4b5563;
                transition: all 0.15s ease;
                border: 1px solid transparent;
                cursor: pointer;
                text-decoration: none;
                user-select: none;
            }

            .dark div.dt-paging .dt-paging-button {
                color: #9ca3af;
            }

            div.dt-paging .dt-paging-button:not(.disabled):not(.current):hover {
                background-color: #f3f4f6;
            }

            .dark div.dt-paging .dt-paging-button:not(.disabled):not(.current):hover {
                background-color: #1f2937;
            }

            div.dt-paging .dt-paging-button.current,
            div.dt-paging .dt-paging-button.current:hover {
                background-color: #465fff;
                color: #ffffff;
                cursor: default;
                border-color: #465fff;
            }

            .dark div.dt-paging .dt-paging-button.current,
            .dark div.dt-paging .dt-paging-button.current:hover {
                background-color: #465fff;
                color: #ffffff;
                border-color: #465fff;
            }

            div.dt-paging .dt-paging-button.disabled,
            div.dt-paging .dt-paging-button.disabled:hover {
                color: #d1d5db;
                cursor: not-allowed;
                background-color: transparent;
            }

            .dark div.dt-paging .dt-paging-button.disabled,
            .dark div.dt-paging .dt-paging-button.disabled:hover {
                color: #4b5563;
            }

            div.dt-paging span.ellipsis {
                padding: 0.25rem 0.5rem;
                color: #6b7280;
            }

            .dark div.dt-paging span.ellipsis {
                color: #9ca3af;
            }

            /* === PERBAIKAN HOVER ROW === */
            table.dataTable tbody tr:hover td,
            table.dataTable tbody tr.dtr-hover td {
                background-color: #f9fafb !important;
            }

            .dark table.dataTable tbody tr:hover td,
            .dark table.dataTable tbody tr.dtr-hover td {
                background-color: #1f2937 !important;
            }

            /* --- PERBAIKAN: STYLE TABEL UNTUK HALAMAN PENGGUNA --- */
            #adminPermohonanTable tbody td {
                padding: 0.5rem 1.25rem;
                font-size: 0.875rem;
                color: #374151;
                vertical-align: middle;
                white-space: nowrap;
            }

            .dark #adminPermohonanTable tbody td {
                color: #d1d5db;
            }
        </style>
    @endpush

    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
            <h3 class="text-xl font-medium text-gray-800 dark:text-white/90">
                Dashboard Permohonan (Admin & Penelaah)
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Kelola semua permohonan analisis spasial yang masuk.
            </p>
        </div>
    </div>

    {{-- Statistik Ringkas (Opsional, sesuai controller index) --}}
    <div class="px-2 mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-lg border border-gray-200 bg-white p-4 dark:bg-gray-800 dark:border-gray-700">
            <p class="text-sm text-gray-500 dark:text-gray-400">Total Permohonan</p>
            <h4 class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats_total ?? 0 }}</h4>
        </div>
        <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4 dark:bg-yellow-900/20 dark:border-yellow-800">
            <p class="text-sm text-yellow-600 dark:text-yellow-400">Baru Diajukan</p>
            <h4 class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">{{ $stats_baru ?? 0 }}</h4>
        </div>
        <div class="rounded-lg border border-blue-200 bg-blue-50 p-4 dark:bg-blue-900/20 dark:border-blue-800">
            <p class="text-sm text-blue-600 dark:text-blue-400">Sedang Diproses</p>
            <h4 class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $stats_diproses ?? 0 }}</h4>
        </div>
        <div class="rounded-lg border border-green-200 bg-green-50 p-4 dark:bg-green-900/20 dark:border-green-800">
            <p class="text-sm text-green-600 dark:text-green-400">Selesai</p>
            <h4 class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $stats_selesai ?? 0 }}</h4>
        </div>
    </div>

    <div class="px-2 mb-4">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="p-4 sm:p-6">

                {{-- Tabel Admin --}}
                <table id="adminPermohonanTable" class="min-w-full hover">
                    <thead class="border-y border-gray-100 dark:border-gray-800">
                        <tr>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-lg text-gray-500 dark:text-gray-400">Kode</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-lg text-gray-500 dark:text-gray-400">Pemohon</p>
                            </th>

                            {{-- KOLOM BARU: TUJUAN --}}
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-lg text-gray-500 dark:text-gray-400">Tujuan Analisis</p>
                            </th>

                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-lg text-gray-500 dark:text-gray-400">Penelaah</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-lg text-gray-500 dark:text-gray-400">Diajukan</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-lg text-gray-500 dark:text-gray-400">Status</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-lg text-gray-500 dark:text-gray-400">Aksi</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        {{-- Data via AJAX --}}
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('src/js/jquery-3.7.1.js') }}"></script>
        <script src="{{ asset('src/js/dataTables.js') }}"></script>

        <script>
            $(document).ready(function() {
                new DataTable('#adminPermohonanTable', {
                    "processing": true,
                    "serverSide": true,
                    "pagingType": "simple_numbers",
                    // Pastikan rute ini benar sesuai routes/klarifikasi.php (prefix admin)
                    "ajax": "{{ route('adminklarifikasi.permohonan.data') }}",
                    "columns": [{
                            "data": "kode_pelacakan",
                            "name": "kode_pelacakan"
                        },
                        {
                            "data": "pemohon",
                            "name": "nama_pemohon"
                        }, // Searchable ke nama_pemohon

                        // KOLOM BARU
                        {
                            "data": "tujuan_analisis",
                            "name": "tujuan_analisis"
                        },

                        {
                            "data": "penelaah",
                            "name": "penelaah.name"
                        },
                        {
                            "data": "tanggal_dibuat",
                            "name": "created_at"
                        },
                        {
                            "data": "status",
                            "name": "status"
                        },
                        {
                            "data": "aksi",
                            "name": "aksi",
                            "orderable": false,
                            "searchable": false
                        }
                    ],

                    "language": {
                        "url": "{{ asset('src/js/id.json') }}"
                    },
                });
            });
        </script>
    @endpush
</x-klarifikasi-layout>
