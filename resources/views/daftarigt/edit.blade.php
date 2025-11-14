<x-jig-layout>
    <div class="px-2 mb-4">
        {{-- Card Utama --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">

            {{-- Header Card --}}
            <div class="border-b border-gray-200 pb-4 mb-6 dark:border-gray-800">

                {{-- PERUBAHAN 1: Judul --}}
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    {{ __('Edit Data IGT') }}
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Perbarui detail data IGT yang ada.
                </p>
            </div>

            {{-- Tampilkan Error Validasi --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops! Terjadi kesalahan:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- PERUBAHAN 2: Action Form & Method --}}
            <form action="{{ route('daftarigt.update', $igt->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- Tambahkan ini untuk method UPDATE --}}

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- PERUBAHAN 3: Isi 'value' dengan data yang ada --}}

                    {{-- Jenis Data (Satu baris penuh) --}}
                    <div class="md:col-span-2">
                        <label for="jenis_data" class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">
                            Jenis Data <span class="text-red-500">*</span>
                        </label>
                        {{-- Gunakan old() untuk menjaga data input jika validasi gagal --}}
                        <x-text-input id="jenis_data" type="text" name="jenis_data" :value="old('jenis_data', $igt->jenis_data)"
                            placeholder="Contoh: Peta Kawasan Hutan dan Konservasi Perairan" required autofocus />
                        <x-input-error :messages="$errors->get('jenis_data')" class="mt-2" />
                    </div>

                    {{-- Periode Update --}}
                    <div>
                        <label for="periode_update"
                            class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">
                            Periode Update
                        </label>
                        <x-text-input id="periode_update" type="text" name="periode_update" :value="old('periode_update', $igt->periode_update)"
                            placeholder="Contoh: Setiap 6 Bulan" />
                        <x-input-error :messages="$errors->get('periode_update')" class="mt-2" />
                    </div>

                    {{-- Format Data --}}
                    <div>
                        <label for="format_data"
                            class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">
                            Format Data <span class="text-red-500">*</span>
                        </label>
                        <x-text-input id="format_data" type="text" name="format_data" :value="old('format_data', $igt->format_data)"
                            placeholder="Contoh: Shapefile" required />
                        <x-input-error :messages="$errors->get('format_data')" class="mt-2" />
                    </div>

                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center justify-end mt-6 pt-4 border-t dark:border-gray-700">
                    <a href="{{ route('daftarigt.index') }}"
                        class="text-sm text-gray-600 hover:text-gray-900 mr-4 dark:text-gray-400 dark:hover:text-white">
                        Batal
                    </a>

                    {{-- PERUBAHAN 4: Teks Tombol --}}
                    <x-primary-button>
                        Simpan Perubahan
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-jig-layout>
