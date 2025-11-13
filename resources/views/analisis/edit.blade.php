<x-klarifikasilayout>
    <x-slot name="header">
        <h2 class="font-semibold px- text-xl text-gray-800 leading-tight">
            Edit Laporan: {{ $laporan->slug }}
        </h2>
    </x-slot>

    <div class="px-2 mb-4">
        <div
            class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4 sm:p-6 lg:p-8">

            {{-- 
              ======================================================
              PERUBAHAN 1: 'action' MENGARAH KE ROUTE BARU 'data.update'
              ======================================================
            --}}
            <form action="{{ route('data.update', $laporan->slug) }}" method="POST">
                @csrf
                @method('PUT') {{-- Penting untuk method update --}}

                <div class="space-y-6">
                    {{-- 1. Field Lokasi --}}
                    <div>
                        <label for="lokasi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Lokasi</label>
                        <input type="text" name="lokasi" id="lokasi" required
                            value="{{ old('lokasi', $laporan->polygon->lokasi ?? '') }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            placeholder="Contoh: Desa Sukamaju, Kecamatan Cianjur">
                        @error('lokasi')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 2. Field Kabupaten --}}
                    <div class="mt-3">
                        <label for="kabupaten"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-400">Kabupaten</label>
                        <input type="text" name="kabupaten" id="kabupaten" required
                            value="{{ old('kabupaten', $laporan->polygon->kabupaten ?? '') }}"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-8 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            placeholder="Contoh: Kabupaten Cianjur">
                        @error('kabupaten')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- 3. Field Keterangan --}}
                    <div class="mt-3">
                        <label for="keterangan"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" rows="3"
                            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30"
                            placeholder="Tambahkan catatan atau keterangan lain...">{{ old('keterangan', $laporan->keterangan ?? '') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="pt-5 border-t border-gray-200 dark:border-gray-700 flex items-center gap-4">
                        <button type="submit"
                            class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500">
                            Simpan Perubahan
                        </button>

                        {{-- 
                          ======================================================
                          PERUBAHAN 2: 'href' MENGARAH KE ROUTE BARU 'data.list'
                          ======================================================
                        --}}
                        <a href="{{ route('data.list') }}"
                            class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            Batal
                        </a>
                    </div>
                </div>
            </form>

        </div>
    </div>
</x-klarifikasilayout>
