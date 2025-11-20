<x-klarifikasi-layout>
    @push('styles')
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
            #permohonanTable tbody td {
                padding: 0.5rem 1.25rem;
                font-size: 0.875rem;
                color: #374151;
                vertical-align: middle;
                white-space: nowrap;
            }

            .dark #permohonanTable tbody td {
                color: #d1d5db;
            }
        </style>
    @endpush

    <div class="px-2 mb-4">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-t border-gray-100 p-2 sm:p-2 dark:border-gray-800">

                {{-- Header Pengguna (dengan Tombol + Ajukan) --}}
                <div class="flex items-center justify-between border-b border-gray-200 px-2 py-2 dark:border-gray-800">
                    <div class="px-4 py-2 sm:px-4 sm:py-2">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                            Daftar Permohonan Resmi Saya
                        </h3>
                    </div>
                    <div class="flex gap-3.5">
                        <div class="flex justify-between items-center">
                            <a href="{{ route('permohonananalisis.create') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-inset  transition hover:bg-green-600 ">
                                {{ __('+ Ajukan Permohonan Baru') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Pesan Sukses (jika ada) --}}
                @if (session('success'))
                    <div class="m-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif
                @if (session('error'))
                    <div class="m-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif


                <div
                    class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible px-5 sm:px-6 dark:text-gray-400">

                    {{-- PERBAIKAN: ID Tabel diganti menjadi "permohonanTable" --}}
                    <table id="permohonanTable" class="min-w-full hover ">
                        <thead class="border-y border-gray-100 py-3 dark:border-gray-800">
                            <tr>
                                {{-- PERBAIKAN: Kolom disesuaikan dengan Controller Pengguna --}}
                                <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                    <p class="text-theme-lg text-gray-500 dark:text-gray-400">Kode Pelacakan</p>
                                </th>
                                <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                    <p class="text-theme-lg text-gray-500 dark:text-gray-400">Nama Pemohon</p>
                                </th>
                                <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                    <p class="text-theme-lg text-gray-500 dark:text-gray-400">Lokasi</p>
                                </th>
                                <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                    <p class="text-theme-lg text-gray-500 dark:text-gray-400">Kabupaten</p>
                                </th>
                                <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                    <p class="text-theme-lg text-gray-500 dark:text-gray-400">Tanggal Diajukan</p>
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
                            {{-- Data akan diisi oleh JavaScript (AJAX) --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('src/js/jquery-3.7.1.js') }}"></script>
        <script src="{{ asset('src/js/dataTables.js') }}"></script>
        <script>
            $(document).ready(function() {
                // --- PERBAIKAN: Inisialisasi DataTables ---
                new DataTable('#permohonanTable', {
                    "processing": true,
                    "serverSide": true,
                    "pagingType": "simple_numbers",
                    "ajax": "{{ route('permohonananalisis.data') }}", // Menggunakan Rute Pengguna
                    "columns": [{
                            "data": "kode_pelacakan",
                            "name": "kode_pelacakan"
                        },
                        {
                            "data": "nama_pemohon", // <-- Sesuai Controller Pengguna
                            "name": "nama_pemohon"
                        },
                        {
                            "data": "lokasi", // <-- Sesuai Controller Pengguna
                            "name": "dataSpasial.nama_areal"
                        },
                        {
                            "data": "kabupaten", // <-- Sesuai Controller Pengguna
                            "name": "dataSpasial.kabupaten"
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
                        "url": "{{ asset('src/js/id.json') }}" // Menggunakan file lokal
                    },
                    // Menggunakan DOM kustom dari file Anda
                });
            });
        </script>
    @endpush
</x-klarifikasi-layout>
