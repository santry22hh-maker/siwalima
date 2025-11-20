<x-klarifikasi-layout>
    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
            <h3 class="text-xl font-medium text-gray-800 dark:text-white/90">
                📨 Ajukan Pengaduan (Klarifikasi Spasial)
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Harap jelaskan keluhan Anda terkait proses atau hasil analisis.
            </p>
        </div>
    </div>

    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Pengaduan Gagal Dikirim:</p>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pengaduan.klarifikasi.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="category" value="KLARIFIKASI">

                <h4
                    class="text-base font-medium text-gray-800 dark:text-white/90 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                    1. Data Pelapor
                </h4>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Nama Pelapor --}}
                    <div>
                        <label for="nama"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" required
                            value="{{ old('nama', $user->name ?? '') }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 px-3 py-2 text-sm text-gray-800 dark:text-white/90"
                            placeholder="Nama Sesuai Akun">
                        @error('nama')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Instansi --}}
                    <div>
                        <label for="instansi"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Instansi /
                            Organisasi</label>
                        <input type="text" name="instansi" id="instansi" required
                            value="{{ old('instansi', $user->instansi ?? 'Perorangan') }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 px-3 py-2 text-sm text-gray-800 dark:text-white/90"
                            placeholder="Contoh: Dinas Pertanian atau Perorangan">
                        @error('instansi')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Alamat Email</label>
                        <input type="email" name="email" id="email"
                            value="{{ old('email', $user->email ?? '') }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 px-3 py-2 text-sm text-gray-800 dark:text-white/90"
                            placeholder="email@anda.com">
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nomor HP --}}
                    <div>
                        <label for="hp_pelapor"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">No. HP /
                            WhatsApp</label>
                        <input type="text" name="hp_pelapor" id="hp_pelapor" required value="{{ old('hp_pelapor') }}"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 px-3 py-2 text-sm text-gray-800 dark:text-white/90"
                            placeholder="08xxxxxxxxxx">
                        @error('hp_pelapor')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <h4
                    class="text-base font-medium text-gray-800 dark:text-white/90 border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 mt-6">
                    2. Detail Keluhan
                </h4>

                <div class="mt-4">
                    <label for="tujuan" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tujuan Pengaduan
                    </label>
                    <select name="tujuan" id="tujuan"
                        class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200">
                        <option value="Perizinan">Perizinan</option>
                        <option value="Klarifikasi Kawasan Hutan">Klarifikasi Kawasan Hutan</option>
                    </select>
                    @error('tujuan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Isi Pengaduan --}}
                <div class="mt-3">
                    <label for="pesan" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Isi
                        Laporan / Keluhan Utama</label>
                    <textarea name="pesan" id="pesan" rows="6" required
                        class="w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-800 px-3 py-2 text-sm text-gray-800 dark:text-white/90"
                        placeholder="Jelaskan masalahnya secara detail...">{{ old('pesan') }}</textarea>
                    @error('pesan')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Bukti Pengaduan --}}
                <div class="mt-3">
                    <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-400 mb-1">Upload
                        Bukti (Foto/PDF - Opsional)</label>
                    <input type="file" name="file" id="file" accept=".jpg,.jpeg,.png,.pdf"
                        class="block w-full text-sm border rounded-lg text-gray-500 border-gray-300 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Format: JPG, PNG, atau PDF. Maks. 2MB.</p>
                    @error('file')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div class="pt-5 border-t border-gray-200 flex justify-end mt-6">
                    <button type="submit"
                        class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        Kirim Pengaduan
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-klarifikasi-layout>
