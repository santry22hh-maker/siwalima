<x-jiglayout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('src/css/datatable-custom.css') }}">
    @endpush
    {{-- Konten Utama Halaman --}}
    <div class="px-2 mb-4">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            {{-- Header Card: Judul dan Tombol Tambah --}}
            <div
                class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 px-4 py-3 sm:px-6 dark:border-gray-800">
                <div>
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{ __('Daftar IGT') }}
                    </h3>
                </div>
                <div>
                    <a href="{{ route('jig.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-sm ring-1 ring-inset ring-brand-500 transition hover:bg-brand-600 dark:bg-brand-500 dark:text-white dark:ring-brand-500 dark:hover:bg-brand-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        {{ __('Tambah Data IGT') }}
                    </a>
                </div>
            </div>

            {{-- Wrapper untuk Tabel DataTables --}}
            <div class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible">
                {{-- Ganti ID tabel agar unik jika perlu, tapi 'example' juga boleh --}}
                <table id="igt-table" class="min-w-full hover stripe" style="width:100%">
                    <thead class="border-b border-gray-100 py-3 dark:border-gray-800">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">No</p>
                            </th>
                            <th class="px-5 py-3 text-left font-medium whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Jenis Data</p>
                            </th>
                            <th class="px-5 py-3 text-left font-medium whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Cakupan</p>
                            </th>
                            <th class="px-5 py-3 text-left font-medium whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Periode Update</p>
                            </th>
                            <th class="px-5 py-3 text-left font-medium whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Format Data</p>
                            </th>
                            <th class="px-5 py-3 text-left font-medium whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Aksi</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
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
                // Inisialisasi DataTables untuk server-side
                new DataTable('#igt-table', {
                    "pagingType": "simple_numbers",
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('jig.index') }}", // Pastikan route ini menunjuk ke DataIgtController@index
                    "columns": [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'jenis_data',
                            name: 'jenis_data'
                        },
                        {
                            data: 'cakupan',
                            name: 'cakupan'
                        },
                        {
                            data: 'periode_update',
                            name: 'periode_update'
                        },
                        {
                            data: 'format_data',
                            name: 'format_data'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });
            });
        </script>
    @endpush
</x-jiglayout>
