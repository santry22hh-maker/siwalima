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
    {{-- Judul Halaman --}}
    <div class="px-2 mb-4">
        <div
            class="flex flex-wrap justify-between items-center gap-4 rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
            <div>
                <h3 class="text-lg font-medium text-gray-800 dark:text-white/90">
                    Proses Pengaduan
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Kode Pelacakan: <strong>{{ $pengaduan->kode_pelacakan ?? $pengaduan->id }}</strong>
                </p>
            </div>
            <a href="{{ route('adminklarifikasi.pengaduan.index') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs ring-1 ring-inset ring-gray-300 transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-white/[0.03]">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>
        </div>
    </div>

    {{-- Pesan Sukses/Error --}}
    <div class="px-2 mb-4">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                <p class="font-bold">Oops! Ada yang salah:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    {{-- Konten Halaman: Grid 2 Kolom --}}
    <div class="px-2 mb-4">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-5">

            <div class="lg:col-span-2 flex flex-col gap-4">

                @php
                    $user = Auth::user();
                    $status = strtolower($pengaduan->status);
                    $isAdmin = $user->hasRole('Admin Klarifikasi');
                    $isPenelaahBertugas = $user->id == $pengaduan->penelaah_id;
                @endphp

                {{-- 1. TAMPILKAN FORM DISPOSISI (Hanya untuk Admin & jika status 'Baru') --}}
                @if ($isAdmin && $status == 'baru')
                    <div class="rounded-lg border border-blue-300 bg-blue-50 dark:border-blue-700 dark:bg-blue-900/30">
                        <div class="px-4 py-3 border-b border-blue-300 dark:border-blue-700">
                            <h4 class="text-base font-medium text-blue-800 dark:text-blue-300">
                                <i class="fas fa-users-cog mr-2"></i> Disposisi Pengaduan
                            </h4>
                        </div>
                        <form action="{{ route('adminklarifikasi.pengaduan.assign', $pengaduan->kode_pelacakan) }}"
                            method="POST">
                            @csrf
                            <div class="p-4 space-y-3">
                                <label for="penelaah_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                    Tugaskan ke Penelaah:
                                </label>
                                <select name="penelaah_id" id="penelaah_id" required
                                    class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                    <option value="" disabled selected>-- Pilih Penelaah Klarifikasi --</option>
                                    @foreach ($penelaahList as $penelaah)
                                        <option value="{{ $penelaah->id }}">{{ $penelaah->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="p-4 border-t border-blue-200 dark:border-blue-700">
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-blue-600 shadow-theme-xs hover:bg-blue-700">
                                    <i class="fas fa-paper-plane"></i> Tugaskan Sekarang
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- 2. TAMPILKAN FORM BALASAN (Hanya untuk Penelaah ybs & jika status 'Ditugaskan') --}}
                @elseif ($isPenelaahBertugas && $status == 'ditugaskan')
                    <div
                        class="rounded-lg border border-yellow-300 bg-yellow-50 dark:border-yellow-700 dark:bg-yellow-900/30">
                        <div class="px-4 py-3 border-b border-yellow-300 dark:border-yellow-700">
                            <h4 class="text-base font-medium text-yellow-800 dark:text-yellow-300">
                                <i class="fas fa-edit mr-2"></i> Tulis Draft Balasan
                            </h4>
                        </div>
                        {{-- Tampilkan catatan revisi dari Admin (jika ada) --}}
                        @if ($pengaduan->catatan_admin)
                            <div class="p-4 border-b border-yellow-300 dark:border-yellow-700">
                                <p class="text-sm font-medium text-red-700 dark:text-red-300">Catatan Perbaikan dari
                                    Admin:</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 italic mt-1">
                                    "{{ $pengaduan->catatan_admin }}"</p>
                            </div>
                        @endif
                        <form
                            action="{{ route('adminklarifikasi.pengaduan.submitReview', $pengaduan->kode_pelacakan) }}"
                            method="POST">
                            @csrf
                            <div class="p-4 space-y-3">
                                <label for="balasan_penelaah"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                    Tulis draft balasan untuk direview Admin:
                                </label>
                                <textarea name="balasan_penelaah" id="balasan_penelaah" rows="5" required minlength="10"
                                    class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                                    placeholder="Tuliskan balasan atau solusi untuk pengaduan ini...">{{ old('balasan_penelaah', $pengaduan->balasan_penelaah) }}</textarea>
                            </div>
                            <div class="p-4 border-t border-yellow-200 dark:border-yellow-700">
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-yellow-600 shadow-theme-xs hover:bg-yellow-700">
                                    <i class="fas fa-paper-plane"></i> Ajukan untuk Review
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- 3. TAMPILKAN FORM APPROVAL (Hanya untuk Admin & jika status 'Direview') --}}
                @elseif ($isAdmin && $status == 'direview')
                    <div
                        class="rounded-lg border border-green-300 bg-green-50 dark:border-green-700 dark:bg-green-900/30">
                        <div class="px-4 py-3 border-b border-green-300 dark:border-green-700">
                            <h4 class="text-base font-medium text-green-800 dark:text-green-300">
                                <i class="fas fa-check-double mr-2"></i> Review Balasan Penelaah
                            </h4>
                        </div>

                        {{-- Draft dari Penelaah --}}
                        <div class="p-4">
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">Draft Balasan dari Penelaah:
                            </p>
                            <p
                                class="text-sm text-gray-600 dark:text-gray-400 italic mt-1 p-3 bg-white dark:bg-gray-800 rounded-md border dark:border-gray-700">
                                "{{ $pengaduan->balasan_penelaah }}"
                            </p>
                        </div>

                        <hr class="border-green-300 dark:border-green-700">

                        {{-- Form Aksi Admin --}}
                        <div class="p-4" x-data="{ action: 'approve' }">

                            {{-- Form Setujui --}}
                            <form x-show="action === 'approve'"
                                action="{{ route('adminklarifikasi.pengaduan.approve', $pengaduan->kode_pelacakan) }}"
                                method="POST">
                                @csrf
                                <label for="balasan_final"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                    Balasan Final (Edit jika perlu):
                                </label>
                                <textarea name="balasan_final" id="balasan_final" rows="4" required
                                    class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 ... dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    {{ old('balasan_final', $pengaduan->balasan_penelaah) }}
                                </textarea>
                                <button type="submit"
                                    class="w-full mt-3 inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-green-600 shadow-theme-xs hover:bg-green-700">
                                    <i class="fas fa-check-circle"></i> Setujui & Selesaikan
                                </button>
                            </form>

                            {{-- Form Tolak --}}
                            <form x-show="action === 'reject'"
                                action="{{ route('adminklarifikasi.pengaduan.rejectReview', $pengaduan->kode_pelacakan) }}"
                                method="POST">
                                @csrf
                                <label for="catatan_perbaikan"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">
                                    Catatan Perbaikan (Wajib):
                                </label>
                                <textarea name="catatan_perbaikan" id="catatan_perbaikan" rows="4" required
                                    class="dark:bg-dark-900 shadow-theme-xs w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 ... dark:border-gray-700 dark:bg-gray-900 dark:text-white/90"
                                    placeholder="Jelaskan apa yang perlu diperbaiki oleh Penelaah..."></textarea>
                                <button type="submit"
                                    class="w-full mt-3 inline-flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium text-center text-white transition rounded-lg bg-red-600 shadow-theme-xs hover:bg-red-700">
                                    <i class="fas fa-undo"></i> Kembalikan ke Penelaah
                                </button>
                            </form>

                            {{-- Tombol Ganti Aksi --}}
                            <div class="text-center mt-3">
                                <button type="button" x-show="action === 'approve'" @click="action = 'reject'"
                                    class="text-sm text-red-600 dark:text-red-400 hover:underline">
                                    Tolak balasan ini?
                                </button>
                                <button type="button" x-show="action === 'reject'" @click="action = 'approve'"
                                    class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                    Batal (Setujui balasan)
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- 4. TAMPILKAN INFO (Jika sudah Selesai, atau status lain) --}}
                @else
                    <div class="rounded-lg border border-gray-300 bg-white dark:border-gray-700 dark:bg-gray-800">
                        <div class="px-4 py-3 border-b dark:border-gray-700">
                            <h4 class="text-base font-medium text-gray-800 dark:text-white/90">
                                <i class="fas fa-info-circle mr-2"></i> Status Pengaduan
                            </h4>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                                <span
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">{{ Str::title($pengaduan->status) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Ditugaskan ke</span>
                                <span
                                    class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $pengaduan->penelaah->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                        {{-- Tampilkan balasan jika sudah selesai --}}
                        @if ($status == 'selesai' && $pengaduan->catatan_admin)
                            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">Balasan Final:</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 italic mt-1">
                                    "{{ $pengaduan->catatan_admin }}"
                                </p>
                            </div>
                        @endif
                    </div>
                @endif


                <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    {{-- ... (Kode Detail Pelapor Anda) ... --}}
                </div>
            </div>

            <div
                class="lg:col-span-3 flex flex-col rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                {{-- ... (Kode Isi Pengaduan & Tombol Unduh Bukti Anda) ... --}}
            </div>

        </div>
    </div>
</x-klarifikasi-layout>
