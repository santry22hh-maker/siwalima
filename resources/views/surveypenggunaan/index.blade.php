<x-jig-layout>
    @push('styles')
        {{-- Memuat file CSS DataTables kustom Anda --}}
        {{-- PASTIKAN PATH INI BENAR: 'css/datatable-custom.css' --}}
        <link rel="stylesheet" href="{{ asset('src/css/datatable-custom.css') }}">
    @endpush

    <div class="px-2 mb-4">
        <div
            class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">

            {{-- Header Card (Diperbarui) --}}
            <div class="border-b border-gray-200 px-4 py-3 sm:px-6 dark:border-gray-800">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Monitoring Status Survey
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Daftar semua permohonan yang 'Selesai' dan status pengisian surveinya.
                </p>
            </div>

            {{-- Wrapper Tabel DataTables --}}
            <div class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible">
                <table id="monitoring-table" class="min-w-full hover stripe" style="width:100%">
                    <thead class="py-3">
                        <tr>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase dark:text-white">
                                <p class="text-theme-lg text-gray-600 dark:text-white">No</p>
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase dark:text-white">
                                <p class="text-theme-lg text-gray-600 dark:text-white">Nama Pemohon</p>
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase dark:text-white">
                                <p class="text-theme-lg text-gray-600 dark:text-white">Instansi</p>
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase dark:text-white">
                                <p class="text-theme-lg text-gray-600 dark:text-white">Tgl. Selesai Permohonan</p>
                            </th>
                            <th class="px-5 py-3 text-left text-xs font-bold text-gray-600 uppercase dark:text-white">
                                <p class="text-theme-lg text-gray-600 dark:text-white">Status Survey</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-transparent">
                        {{-- Data akan diisi oleh Yajra DataTables --}}
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
                new DataTable('#monitoring-table', {
                    "pagingType": "simple_numbers",
                    "processing": true,
                    "serverSide": true,
                    // Pastikan rute ini SAMA DENGAN yang ada di web.php
                    "ajax": "{{ route('surveypenggunaan.index') }}",
                    "columns": [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'dt-center'
                        },
                        {
                            data: 'nama_pemohon',
                            name: 'nama_pemohon'
                        },
                        {
                            data: 'instansi',
                            name: 'instansi'
                        },
                        {
                            data: 'tanggal_selesai',
                            name: 'updated_at'
                        }, // 'updated_at' untuk sorting
                        {
                            data: 'status_survey',
                            name: 'status_survey',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    "createdRow": function(row, data, dataIndex) {
                        $(row).find('td').addClass('px-6 py-3 whitespace-nowrap text-sm');
                        $(row).find('td:eq(1)').addClass('font-medium text-gray-900 dark:text-white/90');
                        $(row).find('td:not(:eq(0)):not(:eq(1))').addClass(
                            'text-gray-500 dark:text-gray-400');
                    }
                });
            });
        </script>
    @endpush
</x-jig-layout>
