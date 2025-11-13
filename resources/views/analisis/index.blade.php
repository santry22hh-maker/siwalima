<x-klarifikasilayout>
    @push('styles')
        <style>
            /* [STYLE CSS ANDA DARI SEBELUMNYA TETAP SAMA, TIDAK BERUBAH] */
            /* ... (Semua style CSS Anda dari .dt-layout-row hingga .dark table.dataTable...) ... */

            /* Kontainer utama (Show entries, Search, Info, Paging) */
            div.dt-layout-row {
                display: flex;
                flex-wrap: wrap;
                align-items: center;
                justify-content: space-between;
                gap: 1rem;
                /* 16px */
                padding: 0.75rem 1rem;
                /* 12px 16px */
                border-bottom: 1px solid #f3f4f6;
                /* gray-100 */
            }

            .dark div.dt-layout-row {
                border-color: #1f2937;
                /* dark:border-gray-800 */
            }

            /* Posisikan tabel agar tidak ada padding/margin tambahan */
            div.dt-layout-row.dt-layout-table {
                padding: 0;
                margin: 0;
                border-bottom: none;
                overflow-x: auto;
                /* Memastikan tabel bisa scroll jika perlu */
            }

            div.dt-layout-row.dt-layout-table>div {
                width: 100%;
                /* Memastikan wrapper tabel mengisi penuh */
            }

            /* Atur border untuk baris paginasi di bawah */
            div.dt-layout-row:last-child {
                border-top: 1px solid #f3f4f6;
                /* gray-100 */
                border-bottom: none;
            }

            .dark div.dt-layout-row:last-child {
                border-color: #1f2937;
                /* dark:border-gray-800 */
            }

            /* --- Mengatur Kontrol Atas (Entries & Search) --- */
            div.dt-length {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                /* 8px */
            }

            div.dt-search {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                /* 8px */
            }

            /* Label (Teks "Show entries" dan "Search:") */
            div.dt-length label,
            div.dt-search label {
                font-size: 0.875rem;
                /* text-sm */
                font-weight: 500;
                /* font-medium */
                color: #374151;
                /* text-gray-700 */
            }

            .dark div.dt-length label,
            .dark div.dt-search label {
                color: #9ca3af;
                /* dark:text-gray-400 */
            }

            /* Input dan Select */
            div.dt-search input,
            div.dt-length select {
                height: 2.25rem;
                /* 36px (h-9) */
                padding: 0.375rem 0.75rem;
                /* py-1.5 px-3 */
                font-size: 0.875rem;
                /* text-sm */
                color: #1f2937;
                /* text-gray-800 */
                background-color: #ffffff;
                /* bg-white */
                border: 1px solid #d1d5db;
                /* border-gray-300 */
                border-radius: 0.5rem;
                /* rounded-lg */
                box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                /* shadow-sm */
            }

            /* === PERBAIKAN LEBAR DROPDOWN === */
            div.dt-length select {
                width: 5.5rem;
                /* w-22 (cukup untuk '100') */
                padding-right: 2rem;
                /* Beri ruang untuk panah */
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

            /* === AKHIR PERBAIKAN DROPDOWN === */

            .dark div.dt-search input,
            .dark div.dt-length select {
                color: rgba(255, 255, 255, 0.9);
                background-color: #111827;
                /* dark:bg-gray-900 */
                border-color: #374151;
                /* dark:border-gray-700 */
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
                /* placeholder:text-gray-400 */
            }

            .dark div.dt-search input::placeholder {
                color: rgba(255, 255, 255, 0.3);
            }

            /* Focus state */
            div.dt-search input:focus,
            div.dt-length select:focus {
                border-color: #9cb9ff;
                /* focus:border-brand-300 */
                box-shadow: 0 0 0 3px rgba(70, 95, 255, 0.1);
                /* ring-3 ring-brand-500/10 */
                outline: none;
            }

            .dark div.dt-search input:focus,
            .dark div.dt-length select:focus {
                border-color: #252dae;
                /* dark:focus:border-brand-800 */
            }

            /* --- CSS PAGINASI --- */

            div.dt-info {
                padding-top: 0.5rem;
                /* pt-2 */
                font-size: 0.875rem;
                /* text-sm */
                color: #4b5563;
                /* text-gray-600 */
            }

            .dark div.dt-info {
                color: #9ca3af;
                /* dark:text-gray-400 */
            }

            /* Wrapper paginasi */
            div.dt-paging {
                display: flex;
                align-items: center;
                justify-content: flex-end;
                gap: 0.25rem;
                /* 4px */
            }

            @media (max-width: 640px) {
                div.dt-paging {
                    justify-content: center;
                }
            }

            /* Tombol Paginasi (termasuk "Previous", "Next", dan "1", "2" ...) */
            div.dt-paging .dt-paging-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 2.25rem;
                /* w-9 */
                height: 2.25rem;
                /* h-9 */
                padding: 0.25rem 0.75rem;
                /* Padding untuk "Previous" / "Next" */
                border-radius: 0.5rem;
                /* rounded-lg */
                font-size: 0.875rem;
                /* text-sm */
                font-weight: 500;
                /* font-medium */
                color: #4b5563;
                /* text-gray-600 */
                transition: all 0.15s ease;
                border: 1px solid transparent;
                cursor: pointer;
                text-decoration: none;
                /* Hapus garis bawah link */
                user-select: none;
                /* Cegah teks terseleksi */
            }

            .dark div.dt-paging .dt-paging-button {
                color: #9ca3af;
                /* dark:text-gray-400 */
            }

            /* Tombol hover */
            div.dt-paging .dt-paging-button:not(.disabled):not(.current):hover {
                background-color: #f3f4f6;
                /* hover:bg-gray-100 */
            }

            .dark div.dt-paging .dt-paging-button:not(.disabled):not(.current):hover {
                background-color: #1f2937;
                /* dark:hover:bg-gray-800 */
            }

            /* Tombol halaman aktif */
            div.dt-paging .dt-paging-button.current,
            div.dt-paging .dt-paging-button.current:hover {
                background-color: #465fff;
                /* bg-brand-500 */
                color: #ffffff;
                /* text-white */
                cursor: default;
                border-color: #465fff;
                /* Pastikan border konsisten */
            }

            .dark div.dt-paging .dt-paging-button.current,
            .dark div.dt-paging .dt-paging-button.current:hover {
                background-color: #465fff;
                /* dark:bg-brand-500 */
                color: #ffffff;
                /* dark:text-white */
                border-color: #465fff;
            }

            /* Tombol disabled */
            div.dt-paging .dt-paging-button.disabled,
            div.dt-paging .dt-paging-button.disabled:hover {
                color: #d1d5db;
                /* text-gray-300 */
                cursor: not-allowed;
                background-color: transparent;
            }

            .dark div.dt-paging .dt-paging-button.disabled,
            .dark div.dt-paging .dt-paging-button.disabled:hover {
                color: #4b5563;
                /* dark:text-gray-600 */
            }

            /* Mengatur '...' (ellipsis) */
            div.dt-paging span.ellipsis {
                padding: 0.25rem 0.5rem;
                color: #6b7280;
                /* text-gray-500 */
            }

            .dark div.dt-paging span.ellipsis {
                color: #9ca3af;
                /* dark:text-gray-400 */
            }

            /* === PERBAIKAN HOVER ROW === */
            table.dataTable tbody tr:hover td,
            /* Untuk browser standar */
            table.dataTable tbody tr.dtr-hover td

            /* Fallback jika DataTables menambah class */
                {
                background-color: #f9fafb !important;
                /* bg-gray-50 */
            }

            .dark table.dataTable tbody tr:hover td,
            .dark table.dataTable tbody tr.dtr-hover td {
                background-color: #1f2937 !important;
                /* dark:bg-gray-800 */
            }

            /* PERBAIKAN STYLE TABEL (dari respons sebelumnya) */
            /* 1. Style Default untuk SEMUA sel (td) */
            #example tbody td {
                padding: 0.75rem 1.25rem;
                /* py-3 px-5 (disesuaikan) */
                font-size: 0.875rem;
                /* text-sm */
                color: #374151;
                /* text-gray-700 */
                vertical-align: middle;
                white-space: nowrap;
                /* Jaga agar tidak wrap */
            }

            .dark #example tbody td {
                color: #d1d5db;
                /* dark:text-gray-300 */
            }

            /* 2. Style KHUSUS untuk Kolom 1 (Keterangan) */
            /* #example tbody td:nth-child(1) {
                            max-width: 300px;
                            overflow: hidden;
                            color: #6b7280;
                            /* text-gray-500 */
            /* }  */
            /*  */
        </style>
    @endpush

    <div class="px-2 mb-4">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-t border-gray-100 p-2 sm:p-2 dark:border-gray-800">
                <div class="flex items-center justify-between border-b border-gray-200 px-2 py-2 dark:border-gray-800">
                    <div class="px-4 py-2 sm:px-4 sm:py-2">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                            Data Analisis Mandiri Saya
                        </h3>
                    </div>
                    <div class="flex gap-3.5">
                        <div class="flex justify-between items-center">

                            <a href="{{ route('klarifikasi.input') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                                {{ __('+ Tambah Data Baru') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div
                    class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible px-5 sm:px-6 dark:text-gray-400">

                    <table id="example" class="min-w-full hover ">
                        <thead class="border-y border-gray-100 py-3 dark:border-gray-800">
                            <tr>
                                <th class="px-5 py-3 font-bold whitespace-nowrap sm:px-6">
                                    <p class="text-theme-lg text-gray-500 dark:text-gray-400">Keterangan</p>
                                </th>
                                <th class="px-5 py-3 font-bold whitespace-nowrap sm:px-6">
                                    <p class="text-theme-lg text-gray-500 dark:text-gray-400">Jenis Data</p>
                                </th>
                                <th class="px-5 py-3 font-bold whitespace-nowrap sm:px-6">
                                    <p class="text-theme-lg text-gray-500 dark:text-gray-400">Lokasi</p>
                                </th>
                                <th class="px-5 py-3 font-bold whitespace-nowrap sm:px-6">
                                    <p class="text-theme-lg text-gray-500 dark:text-gray-400">Kabupaten</p>
                                </th>
                                <th class="px-5 py-3 font-bold whitespace-nowrap sm:px-6">
                                    <p class="text-theme-lg text-gray-500 dark:text-gray-400">Tanggal Dibuat</p>
                                </th>

                                <th class="px-5 py-3 font-bold whitespace-nowrap sm:px-6">
                                    <p class="text-theme-lg text-gray-500 dark:text-gray-400">Status</p>
                                </th>

                                <th class="px-5 py-3 font-bold whitespace-nowrap sm:px-6">
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

        @push('scripts')
            <script src="{{ asset('src/js/jquery-3.7.1.js') }}"></script>
            <script src="{{ asset('src/js/dataTables.js') }}"></script>
            <script>
                $(document).ready(function() {
                    new DataTable('#example', {
                        "processing": true,
                        "serverSide": true,
                        "pagingType": "simple_numbers",
                        "ajax": "{{ route('data.json') }}", // Ini sudah benar

                        "columns": [{
                                "data": "keterangan",
                                "name": "keterangan"
                            },
                            {
                                "data": "jenis_data",
                                "name": "jenis_data",
                                "orderable": false,
                                "searchable": false
                            },
                            {
                                "data": "lokasi",
                                "name": "dataSpasial.nama_areal"
                            }, // Sesuaikan name ke tabel baru
                            {
                                "data": "kabupaten",
                                "name": "dataSpasial.kabupaten"
                            }, // Sesuaikan name ke tabel baru
                            {
                                "data": "tanggal_dibuat",
                                "name": "created_at"
                            },
                            {
                                "data": "status",
                                "name": "status"
                            }, // <-- KOLOM BARU
                            {
                                "data": "aksi",
                                "name": "aksi",
                                "orderable": false,
                                "searchable": false
                            }
                        ]
                    });
                });
            </script>
        @endpush
    </div>
</x-klarifikasilayout>
