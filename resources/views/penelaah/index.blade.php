<x-jiglayout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('src/css/datatable-custom.css') }}">
    @endpush

    <div class="px-2 mb-4">
        <div
            class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">

            {{-- Header Card --}}
            <div
                class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 px-4 py-3 sm:px-6 dark:border-gray-800">
                <div>
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        Manajemen Penelaah
                    </h3>
                </div>
                <div>
                    <a href="{{ route('penelaah.create') }}"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-brand-600">
                        <i class="fas fa-plus"></i>
                        Tambah Penelaah Baru
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="m-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Wrapper Tabel DataTables --}}
            <div class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible">
                <table id="penelaah-table" class="min-w-full hover stripe" style="width:100%">
                    <thead class="py-3">
                        <tr>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">No</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">Nama</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">Email</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">Aksi</p>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-transparent"></tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('src/js/jquery-3.7.1.js') }}"></script>
        <script src="{{ asset('src/js/dataTables.js') }}"></script>
        <script>
            $(document).ready(function() {
                new DataTable('#penelaah-table', {
                    "pagingType": "simple_numbers",
                    "processing": true,
                    "serverSide": true,
                    "ajax": "{{ route('penelaah.index') }}",
                    "columns": [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'dt-center'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'action',
                            name: 'action',
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
</x-jiglayout>
