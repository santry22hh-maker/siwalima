<x-klarifikasi-layout>
    @push('styles')
        <style>
            /* ... (Semua style CSS DataTables Anda tetap di sini) ... */

            /* Style untuk memotong teks Keterangan */
            #example tbody td:nth-child(1) {
                max-width: 300px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                font-style: italic;
                color: #6b7280;
                /* text-gray-500 */
            }

            .dark #example tbody td:nth-child(1) {
                color: #9ca3af;
                /* dark:text-gray-400 */
            }
        </style>
    @endpush

    <div class="px-2 mb-4">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-t border-gray-100 p-2 sm:p-2 dark:border-gray-800">
                <div class="flex items-center justify-between border-b border-gray-200 px-2 py-2 dark:border-gray-800">
                    <div class="px-4 py-2 sm:px-4 sm:py-2">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                            Daftar Permohonan Resmi Saya
                        </h3>
                    </div>
                    <div class="flex gap-3.5">
                        <div class="flex justify-between items-center">
                            {{-- PERUBAHAN 1: Route 'permohonan.create' --}}
                            <a href="{{ route('permohonan.create') }}"
                                class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                                {{ __('+ Ajukan Permohonan Baru') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div
                    class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible px-5 sm:px-6 dark:text-gray-400">

                    <table id="example" class="min-w-full hover ">
                        <thead class="border-y border-gray-100 py-3 dark:border-gray-800">
                            <tr>
                                <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Keterangan</p>
                                </th>
                                <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Jenis Data</p>
                                </th>
                                <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Lokasi</p>
                                </th>
                                <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Kabupaten</p>
                                </th>
                                <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Tanggal Dibuat</p>
                                </th>
                                <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Status</p>
                                </th>
                                <th class="px-5 py-3 font-normal whitespace-nowrap sm:px-6">
                                    <p class="text-theme-sm text-gray-500 dark:text-gray-400">Aksi</p>
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

                        {{-- PERUBAHAN 2: Route 'permohonan.data' --}} "ajax": "{{ route('permohonan.data') }}",

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
                                "name": "polygon.lokasi"
                            },
                            {
                                "data": "kabupaten",
                                "name": "polygon.kabupaten"
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
                        ]
                    });
                });
            </script>
        @endpush
    </div>
</x-klarifikasi-layout>
