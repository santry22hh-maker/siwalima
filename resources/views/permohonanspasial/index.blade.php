<x-jig-layout>
    @push('styles')
        {{-- Memuat file CSS DataTables kustom Anda --}}
        <link rel="stylesheet" href="{{ asset('src/css/datatable-custom.css') }}">

        {{-- Style untuk Tab Filter --}}
        <style>
            .tab-btn {
                display: inline-flex;
                /* Diubah ke inline-flex */
                align-items: center;
                /* Menengahkan ikon dan teks */
                gap: 0.5rem;
                /* Jarak antara ikon dan teks */
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                font-weight: 500;
                color: #6b7280;
                /* text-gray-500 */
                border-bottom: 2px solid transparent;
                transition: all 0.2s ease-in-out;
            }

            .tab-btn .fas {
                /* Atur warna ikon */
                color: #9ca3af;
                /* text-gray-400 */
                transition: all 0.2s ease-in-out;
            }

            .tab-btn:hover {
                color: #1f2937;
                /* hover:text-gray-800 */
                border-bottom-color: #d1d5db;
                /* hover:border-gray-300 */
            }

            .tab-btn:hover .fas {
                color: #6b7280;
                /* hover:text-gray-500 */
            }

            .tab-btn.active {
                color: #465fff;
                /* text-brand-500 */
                border-bottom-color: #465fff;
                /* border-brand-500 */
            }

            .tab-btn.active .fas {
                color: #465fff;
                /* text-brand-500 */
            }

            /* Dark mode styles */
            .dark .tab-btn {
                color: #9ca3af;
                /* dark:text-gray-400 */
            }

            .dark .tab-btn .fas {
                color: #6b7280;
                /* dark:text-gray-500 */
            }

            .dark .tab-btn:hover {
                color: #f3f4f6;
                /* dark:hover:text-gray-100 */
                border-bottom-color: #4b5563;
                /* dark:hover:border-gray-600 */
            }

            .dark .tab-btn:hover .fas {
                color: #9ca3af;
                /* dark:hover:text-gray-400 */
            }

            .dark .tab-btn.active {
                color: #7c8bff;
                /* dark:text-brand-400 */
                border-bottom-color: #7c8bff;
                /* dark:border-brand-400 */
            }

            .dark .tab-btn.active .fas {
                color: #7c8bff;
                /* dark:text-brand-400 */
            }
        </style>
    @endpush

    <div class="px-2 mb-4">
        <div
            class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">

            {{-- Header Card --}}
            <div class="border-b border-gray-200 px-4 py-3 sm:px-6 dark:border-gray-800">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Daftar Permohonan Data IGT Masuk
                </h3>
            </div>

            {{-- Pesan untuk Penelaah --}}
            @role('Penelaah')
                <div class="p-4 border-b border-gray-200 dark:border-gray-800 bg-blue-50 dark:bg-blue-900/20">
                    <p class="text-sm text-blue-700 dark:text-blue-300">
                        Hanya menampilkan permohonan yang ditugaskan kepada Anda.
                    </p>
                </div>
            @endrole

            {{-- (TABS UNTUK FILTER DENGAN IKON) --}}
            <div class="border-b border-gray-200 px-4 sm:px-6 dark:border-gray-800">
                <nav class="flex -mb-px space-x-6" id="status-tabs">
                    <a href="#" class="tab-btn active" data-status="Semua">
                        <i class="fas fa-inbox w-4 text-center"></i>
                        <span>Semua</span>
                    </a>
                    <a href="#" class="tab-btn" data-status="Tugas">
                        <i class="fas fa-exclamation-circle w-4 text-center"></i>
                        <span>
                            @role('Admin')
                                Tugas Anda (Pending/Verifikasi)
                            @else
                                Tugas Anda (Diproses/Revisi)
                            @endrole
                        </span>
                    </a>
                    <a href="#" class="tab-btn" data-status="Selesai">
                        <i class="fas fa-check-circle w-4 text-center"></i>
                        <span>Selesai</span>
                    </a>
                </nav>
            </div>

            {{-- Tampilkan pesan sukses/error --}}
            @if (session('success'))
                <div class="m-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="m-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
                    role="alert">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Wrapper Tabel DataTables --}}
            <div class="custom-scrollbar max-w-full overflow-x-auto overflow-y-visible">
                <table id="permohonan-table" class="min-w-full hover stripe" style="width:100%">
                    <thead class="py-3">
                        <tr>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">No</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">Nama Pemohon / Instansi</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">Tanggal Surat</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">Status</p>
                            </th>
                            <th class="px-5 py-3 text-left font-bold whitespace-nowrap sm:px-6">
                                <p class="text-theme-sm text-gray-600 dark:text-white">Ditangani Oleh</p>
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

        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('src/js/jquery-3.7.1.js') }}"></script>
        <script src="{{ asset('src/js/dataTables.js') }}"></script>

        <script>
            $(document).ready(function() {
                var table = new DataTable('#permohonan-table', {
                    "pagingType": "simple_numbers",
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "{{ route('permohonanspasial.index') }}",
                        "data": function(d) {
                            d.status_filter = $('#status-tabs a.active').data('status');
                        }
                    },
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
                            data: 'tanggal_surat',
                            name: 'tanggal_surat'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'penelaah',
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
                    "createdRow": function(row, data, dataIndex) {
                        $(row).find('td').addClass('px-6 py-3 whitespace-nowdotrap text-sm');
                        $(row).find('td:eq(1)').addClass('font-medium text-gray-900 dark:text-white/90');
                        $(row).find('td:not(:eq(0)):not(:eq(1))').addClass(
                            'text-gray-500 dark:text-gray-400');
                        $(row).find('td:eq(5)').addClass('whitespace-nowrap');
                    }
                });

                // Event listener untuk Tab Filter
                $('#status-tabs a').on('click', function(e) {
                    e.preventDefault();
                    $('#status-tabs a').removeClass('active');
                    $(this).addClass('active');
                    table.ajax.reload();
                });
            });
        </script>
    @endpush
</x-jig-layout>
