<x-jiglayout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('src/css/datatable-custom.css') }}">
    @endpush

    {{-- Konten Utama Halaman --}}
    <div class="px-2 mb-4">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
            {{-- Header Card: Judul dan Tombol --}}
            <div
                class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 px-4 py-3 sm:px-6 dark:border-gray-800">
                <div>
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{ __('Daftar IGT') }}
                    </h3>
                    {{-- Deskripsi untuk Pengguna --}}
                    @role('Pengguna')
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Silakan pilih data IGT yang Anda perlukan, lalu klik tombol "Buat Permohonan".
                        </p>
                    @endrole
                </div>
                {{-- Tombol 'Tambah Data' hanya untuk Admin --}}
                @role('Admin|Penelaah')
                    <div>
                        <a href="{{ route('daftarigt.create') }}"
                            class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-sm ring-1 ring-inset ring-brand-500 transition hover:bg-brand-600 dark:bg-brand-500 dark:text-white dark:ring-brand-500 dark:hover:bg-brand-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            {{ __('Tambah Data IGT') }}
                        </a>
                    </div>
                @endrole
            </div>

            {{-- Wrapper untuk Tabel DataTables --}}
            <div class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible">
                <table id="igt-table" class="min-w-full hover stripe" style="width:100%">
                    <thead class="py-3">
                        <tr>
                            {{-- 2. Checkbox "Pilih Semua" --}}
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <input type="checkbox" id="select-all-checkbox"
                                    class="rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900">
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">No</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">Jenis Data</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">Periode Update</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">Format Data</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">Aksi</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-transparent">
                        {{-- Data akan diisi oleh Yajra DataTables --}}
                    </tbody>
                </table>
            </div>

            {{-- Tombol "Buat Permohonan" untuk Pengguna --}}
            @role('Pengguna')
                <div class="flex items-center justify-end border-t border-gray-200 px-4 py-3 sm:px-6 dark:border-gray-800">
                    <button id="buat-permohonan-btn"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:ring-offset-gray-800">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        Buat Permohonan (Data IGT Terpilih)
                    </button>
                </div>
            @endrole

        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('src/js/jquery-3.7.1.js') }}"></script>
        <script src="{{ asset('src/js/dataTables.js') }}"></script>

        <script>
            $(document).ready(function() {
                // Simpan instance tabel ke variabel
                var table = new DataTable('#igt-table', {
                    "pagingType": "simple_numbers",
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('daftarigt.index') }}",

                    // 3. Hapus 'createdRow' dan gunakan 'columnDefs' + 'columns'

                    // Terapkan kelas padding default ke SEMUA kolom
                    "columnDefs": [{
                        "targets": "_all",
                        "className": "px-6 py-4 whitespace-nowrap text-sm"
                    }],

                    "columns": [{
                            data: 'checkbox',
                            name: 'checkbox',
                            orderable: false,
                            searchable: false,
                            className: 'dt-center' // Tengahkan checkbox
                        },
                        {
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'dt-center text-gray-500 dark:text-gray-400' // Tengahkan nomor
                        },
                        {
                            data: 'jenis_data',
                            name: 'jenis_data',
                            className: 'font-medium text-gray-900 dark:text-white/90' // Buat tebal
                        },

                        {
                            data: 'periode_update',
                            name: 'periode_update',
                            className: 'text-gray-500 dark:text-gray-400'
                        },
                        {
                            data: 'format_data',
                            name: 'format_data',
                            className: 'text-gray-500 dark:text-gray-400'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'font-medium text-right' // Rata kanan
                        }
                    ]
                });

                // 4. Logika untuk "Pilih Semua"
                $('#select-all-checkbox').on('click', function() {
                    // Dapatkan semua checkbox di tabel
                    var rows = table.rows({
                        'search': 'applied'
                    }).nodes();
                    $('input.igt-checkbox', rows).prop('checked', this.checked);
                });

                // 5. Logika untuk "Buat Permohonan" (tidak berubah)
                $('#buat-permohonan-btn').on('click', function() {
                    var selectedIds = [];
                    // Dapatkan ID dari checkbox yang dicentang di SEMUA halaman
                    table.$('input.igt-checkbox:checked').each(function() {
                        selectedIds.push($(this).data('id'));
                    });

                    if (selectedIds.length === 0) {
                        alert('Pilih minimal satu data IGT untuk diajukan permohonan.');
                        return;
                    }

                    var baseUrl = "{{ route('permohonanspasial.create') }}";
                    var queryParams = $.param({
                        igt_ids: selectedIds
                    });

                    window.location.href = baseUrl + '?' + queryParams;
                });
            });
        </script>
    @endpush
</x-jiglayout>
