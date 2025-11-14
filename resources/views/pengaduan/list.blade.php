<x-jig-layout>
    @push('styles')
        {{-- CSS Kustom untuk DataTables (Disalin dari file daftarigt) --}}
        <style>
            /* Kontrol (Search, Entries, Paging) */
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

            div.dt-layout-row.dt-layout-table {
                padding: 0;
                margin: 0;
                border-bottom: none;
                overflow-x: auto;
            }

            div.dt-layout-row.dt-layout-table>div {
                width: 100%;
            }

            div.dt-layout-row:last-child {
                border-top: 1px solid #f3f4f6;
                border-bottom: none;
            }

            .dark div.dt-layout-row:last-child {
                border-color: #1f2937;
            }

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

            /* Hover Row */
            table.dataTable tbody tr:hover td {
                background-color: #f9fafb !important;
            }

            .dark table.dataTable tbody tr:hover td {
                background-color: #1f2937 !important;
            }

            /* PERBAIKAN: Style Kustom untuk Header Tabel Pengaduan */
            table#pengaduan-table thead th {
                background-color: #ffffff;
                /* bg-white */
                border-bottom: 2px solid #e5e7eb;
                /* border-b-2 border-gray-200 */
                font-weight: 600;
                /* font-semibold */
            }

            .dark table#pengaduan-table thead th {
                background-color: transparent;
                /* dark:bg-transparent */
                border-color: #374151;
                /* dark:border-gray-700 */
            }
        </style>
    @endpush

    {{-- Konten Utama Halaman --}}
    <div class="px-2 mb-4">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">

            {{-- Header Card: Judul --}}
            <div
                class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 px-4 py-3 sm:px-6 dark:border-gray-800">
                <div>
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{-- PERBAIKAN: Judul diubah --}}
                        {{ __('Daftar Pengaduan Masuk') }}
                    </h3>
                </div>
                {{-- Tombol Tambah dihapus, karena form ada di halaman terpisah --}}
            </div>

            {{-- Wrapper untuk Tabel DataTables --}}
            <div class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible">

                {{-- PERBAIKAN: ID Tabel diubah --}}
                <table id="pengaduan-table" class="min-w-full hover stripe" style="width:100%">
                    <thead class="py-3">
                        <tr>
                            {{-- PERBAIKAN: Kolom header diubah --}}
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase dark:text-white">
                                <p class="text-theme-lg text-gray-600 dark:text-white">No</p>
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase dark:text-white">
                                <p class="text-theme-lg text-gray-600 dark:text-white">Nama</p>
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase dark:text-white">
                                <p class="text-theme-lg text-gray-600 dark:text-white">Instansi</p>
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase dark:text-white">
                                <p class="text-theme-lg text-gray-600 dark:text-white">Pesan</p>
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase dark:text-white">
                                <p class="text-theme-lg text-gray-600 dark:text-white">Status</p>
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase dark:text-white">
                                <p class="text-theme-lg text-gray-600 dark:text-white">Ditangani Oleh</p>
                            </th>

                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase dark:text-white">
                                <p class="text-theme-lg text-gray-600 dark:text-white">Aksi</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-transparent">
                        {{-- Data akan diisi oleh Yajra DataTables (JavaScript) --}}
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
                // PERBAIKAN: ID Tabel diubah
                new DataTable('#pengaduan-table', {
                    "pagingType": "simple_numbers",
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('pengaduan.list') }}", // PERBAIKAN: Rute AJAX diubah
                    "columns": [
                        // PERBAIKAN: Kolom data diubah
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama',
                            name: 'nama'
                        },
                        {
                            data: 'instansi',
                            name: 'instansi'
                        },
                        {
                            data: 'pesan',
                            name: 'pesan'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'penelaah_name',
                            name: 'penelaah.name',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],

                    // PERBAIKAN: Menambahkan 'createdRow' untuk styling per baris
                    "createdRow": function(row, data, dataIndex) {
                        // Tambahkan padding vertikal ke setiap sel di baris
                        $(row).find('td').addClass('px-6 py-4 whitespace-nowrap text-sm');

                        // Style untuk kolom nama (kolom ke-2, index 1)
                        $(row).find('td:eq(1)').addClass('font-medium text-gray-900 dark:text-white/90');

                        // Style untuk kolom data lainnya (index 2, 3, 4)
                        // Kolom 'status' (index 4) akan ditimpa oleh HTML dari controller
                        $(row).find('td:not(:eq(0)):not(:eq(1)):not(:eq(5))').addClass(
                            'text-gray-500 dark:text-gray-400');

                        // Style untuk kolom aksi (kolom ke-6, index 5)
                        $(row).find('td:eq(5)').addClass('font-medium');
                    }
                });
            });
        </script>
    @endpush
</x-jig-layout>
