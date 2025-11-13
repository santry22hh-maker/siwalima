<x-jiglayout>
    <div class="px-2 mb-4">
        <div
            class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">

            {{-- Header Card --}}
            <div
                class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 pb-3 mb-4 dark:border-gray-800">
                <div>
                    <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                        {{ __('Data Usage Report') }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Anda wajib mengunggah laporan penggunaan untuk setiap permohonan yang telah selesai.
                    </p>
                </div>
            </div>

            {{-- Tampilkan Pesan Sukses/Error --}}
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                    role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops! Terjadi kesalahan upload:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Daftar Laporan --}}
            <div class="space-y-6">
                @forelse ($laporans as $laporan)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm overflow-hidden">

                        {{-- Header Card: Info Permohonan --}}
                        <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 sm:px-5 sm:py-4">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-white/90">Permohonan
                                        #{{ $laporan->id }}</p>
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <time datetime="{{ $laporan->updated_at->toIso8601String() }}">
                                        Selesai pada: {{ $laporan->updated_at->format('d F Y') }}
                                    </time>
                                </div>
                            </div>
                        </div>

                        {{-- Body Card: Status & Aksi --}}
                        <div class="p-4 sm:p-5 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">

                            {{-- Kolom Kiri: Status Laporan --}}
                            <div>
                                @if ($laporan->laporan_penggunaan_path)
                                    <div class_ ="flex items-center gap-3">
                                        <span class="rounded-full bg-green-100 dark:bg-green-900/50 p-2">
                                            <i class="fas fa-check-circle fa-lg text-green-600 dark:text-green-400"></i>
                                        </span>
                                        <div>
                                            <p class="font-semibold text-green-700 dark:text-green-300">Sudah Diunggah
                                            </p>
                                            <a href="{{ Storage::url($laporan->laporan_penggunaan_path) }}"
                                                target="_blank" class="text-sm text-brand-500 hover:underline">
                                                Lihat Laporan Terunggah
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-3">
                                        <span class="rounded-full bg-yellow-100 dark:bg-yellow-900/50 p-2">
                                            <i
                                                class="fas fa-exclamation-triangle fa-lg text-yellow-600 dark:text-yellow-400"></i>
                                        </span>
                                        <div>
                                            <p class="font-semibold text-yellow-700 dark:text-yellow-300">Belum Diunggah
                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Silakan unggah laporan
                                                penggunaan Anda.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Kolom Kanan: Tombol Upload (jika belum upload) --}}
                            <div>
                                @if (!$laporan->laporan_penggunaan_path)
                                    <form action="{{ route('laporanpenggunaan.store', $laporan) }}" method="POST"
                                        enctype="multipart/form-data" class="flex items-center gap-2">
                                        @csrf
                                        <input type="file" name="laporan_penggunaan"
                                            id="laporan_penggunaan_{{ $laporan->id }}"
                                            class="block w-full text-sm border rounded-lg text-gray-500 border-gray-300 dark:text-gray-400 file:mr-2 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600"
                                            required>
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-indigo-700">
                                            Upload
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    </div>
                @empty
                    {{-- Tampilan jika belum ada permohonan Selesai --}}
                    <div class="text-center text-gray-500 dark:text-gray-400 py-10 px-6">
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white/90">Belum Ada Laporan</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Anda belum memiliki permohonan yang
                            berstatus "Selesai".</p>
                    </div>
                @endforelse
            </div>

            {{-- Link Paginasi --}}
            @if ($laporans->hasPages())
                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                    {{ $laporans->links() }}
                </div>
            @endif

        </div>
    </div>
</x-jiglayout>
