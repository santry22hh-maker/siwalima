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
        <div
            class="flex flex-wrap justify-between items-center gap-4 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
            <div>
                <h3 class="text-xl font-medium text-gray-800 dark:text-white/90">
                    📋 Riwayat Pengaduan (Klarifikasi Spasial)
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Daftar pengaduan yang Anda ajukan terkait proses analisis spasial.
                </p>
            </div>

        </div>
    </div>

    <div class="px-2 mb-4">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($pengaduans as $pengaduan)
                <div
                    class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] shadow-sm overflow-hidden">

                    {{-- Header Kartu: Status & Tanggal --}}
                    <div class="px-4 py-3 sm:px-6 flex justify-between items-center bg-gray-50 dark:bg-gray-800">
                        <div>
                            @php
                                $status = strtolower($pengaduan->status);
                                $badgeClass = 'bg-blue-500 text-white dark:bg-blue-900 dark:text-blue-300'; // Default "Baru"
                                if ($status == 'ditindaklanjuti') {
                                    $badgeClass =
                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                                }
                                if ($status == 'selesai') {
                                    $badgeClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                                }
                            @endphp
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $badgeClass }}">
                                {{ Str::title($pengaduan->status) }}
                            </span>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $pengaduan->created_at->isoFormat('D MMMM YYYY, HH:mm') }}
                        </span>
                    </div>

                    {{-- Isi Kartu: Pesan Anda --}}
                    <div class="px-4 py-5 sm:p-6">
                        <h5 class="text-sm font-medium text-gray-800 dark:text-white/90">Pesan Anda:</h5>
                        <div class="mt-2 p-4 rounded-md bg-gray-50 dark:bg-gray-800">
                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                                {{ $pengaduan->pesan }}</p>
                        </div>

                        {{-- Bukti Lampiran (jika ada) --}}
                        @if ($pengaduan->file)
                            <div class="mt-4">
                                <h5 class="text-sm font-medium text-gray-800 dark:text-white/90">Bukti Terlampir:</h5>
                                <a href="{{ Storage::url($pengaduan->file) }}" target="_blank"
                                    class="inline-flex items-center gap-2 mt-1 text-sm text-blue-600 hover:underline dark:text-blue-400">
                                    <i class="fas fa-paperclip"></i>
                                    Lihat Bukti
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Footer Kartu: Balasan & Aksi --}}
                    <div class="px-4 py-4 sm:px-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="border-t border-gray-200 dark:border-gray-700 px-6 py-4">
                            @if (strtolower($pengaduan->status) == 'selesai' && $pengaduan->catatan_admin)
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Tanggapan dari Petugas:</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 italic mt-2">
                                    "{{ $pengaduan->catatan_admin }}" {{-- <-- PASTIKAN MENGGUNAKAN 'catatan_admin' --}}
                                </p>
                            @else
                                <p class="text-sm text-center text-gray-500 italic">
                                    Belum ada tanggapan dari petugas.
                                </p>
                            @endif
                        </div>

                        {{-- Tombol Aksi --}}
                        @if (strtolower($pengaduan->status) == 'baru' || strtolower($pengaduan->status) == 'diajukan')
                            <div class="mt-4 text-right">
                                <form action="{{ route('pengaduan.klarifikasi.destroy', $pengaduan->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pengaduan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-sm font-medium text-red-600 hover:text-red-800 dark:text-red-500 dark:hover:text-red-400">
                                        Batalkan Pengaduan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div
                    class="rounded-lg border border-dashed border-gray-300 dark:border-gray-700 bg-white dark:bg-white/[0.03] p-8 text-center">
                    <i class="fas fa-comment-slash fa-3x text-gray-400"></i>
                    <h4 class="mt-4 text-lg font-medium text-gray-800 dark:text-white/90">Belum Ada Pengaduan</h4>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum pernah mengajukan pengaduan
                        terkait layanan klarifikasi.</p>
                </div>
            @endforelse

            {{-- Pagination Links --}}
            <div class="mt-6">
                {{ $pengaduans->links() }}
            </div>
        </div>
    </div>
</x-klarifikasi-layout>
