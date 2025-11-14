<x-jig-layout>
    <div class="px-2 mb-4">
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6"
            x-data="{ userType: '{{ old('tipe_pemohon', $permohonan->tipe_pemohon) }}' }"> {{-- 2. Isi x-data dengan data lama --}}

            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('permohonanspasial.revisi.update', $permohonan) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- 4. Gunakan method PUT untuk update --}}

                {{-- Dropdown Tipe Pemohon --}}
                <div class="border-b dark:border-gray-700 pb-6 mb-6">
                    <h4 class="font-semibold text-lg text-gray-800 dark:text-white/90">Tipe Pemohon</h4>
                    <div class="mt-4">
                        <label for="tipe_pemohon"
                            class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">
                            Saya adalah perwakilan dari: <span class="text-red-500">*</span>
                        </label>
                        <select id="tipe_pemohon" name="tipe_pemohon" x-model="userType"
                            class="mt-1 block w-full md:w-1/2 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                            <option value="" disabled>-- Pilih Tipe Pemohon --</option>
                            <option value="pemerintah">Instansi Pemerintah</option>
                            <option value="akademisi">Dosen / Mahasiswa / Akademisi</option>
                        </select>
                        <x-input-error :messages="$errors->get('tipe_pemohon')" class="mt-2" />
                    </div>
                </div>

                {{-- Form Dinamis --}}
                <div x-show="userType" x-transition class="space-y-6">

                    {{-- === BAGIAN DATA PEMOHON (Dinamis) === --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div class="col-span-1 md:col-span-2 font-bold text-lg text-gray-800 dark:text-white/90">Data
                            Pemohon</div>

                        {{-- Nama Pemohon (Selalu Tampil) --}}
                        <div>
                            <label for="nama_pemohon"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">
                                <span
                                    x-text="userType === 'masyarakat' ? 'Nama Lengkap (sesuai KTP)' : 'Nama Lengkap Pemohon'"></span>
                                <span class="text-red-500">*</span>
                            </label>
                            <x-text-input id="nama_pemohon" type="text" name="nama_pemohon" :value="old('nama_pemohon', $permohonan->nama_pemohon)"
                                required />
                            <x-input-error :messages="$errors->get('nama_pemohon')" class="mt-2" />
                        </div>

                        {{-- Instansi (Selalu Tampil, kecuali Masyarakat) --}}
                        <div x-show="userType !== 'masyarakat'">
                            <label for="instansi"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">
                                <span
                                    x-text="userType === 'akademisi' ? 'Nama Universitas' : (userType === 'swasta' ? 'Nama Perusahaan/Organisasi' : 'Nama Instansi')"></span>
                                <span class="text-red-500">*</span>
                            </label>
                            <x-text-input id="instansi" type="text" name="instansi" :value="old('instansi', $permohonan->instansi)" required />
                            <x-input-error :messages="$errors->get('instansi')" class="mt-2" />
                        </div>

                        {{-- NIP (Kondisional & Wajib untuk Pemerintah) --}}
                        <div x-show="userType !== 'masyarakat'">
                            <label for="nip"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">
                                <span
                                    x-text="userType === 'akademisi' ? 'NIM / NIDN' : (userType === 'swasta' ? 'NIK / ID Karyawan' : 'NIP')"></span>
                                {{-- Tanda bintang dinamis --}}
                                <span x-show="userType === 'pemerintah'" class="text-red-500">*</span>
                            </label>
                            <x-text-input id="nip" type="text" name="nip" :value="old('nip', $permohonan->nip)"
                                x-bind:required="userType === 'pemerintah'" />
                            <x-input-error :messages="$errors->get('nip')" class="mt-2" />
                        </div>

                        {{-- Jabatan (Kondisional & Wajib untuk Pemerintah) --}}
                        <div x-show="userType !== 'masyarakat'">
                            <label for="jabatan"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">
                                <span
                                    x-text="userType === 'akademisi' ? 'Status (Dosen/Mahasiswa)' : (userType === 'swasta' ? 'Posisi / Jabatan' : 'Jabatan')"></span>
                                {{-- Tanda bintang dinamis --}}
                                <span x-show="userType === 'pemerintah'" class="text-red-500">*</span>
                            </label>
                            <x-text-input id="jabatan" type="text" name="jabatan" :value="old('jabatan', $permohonan->jabatan)"
                                x-bind:required="userType === 'pemerintah'" />
                            <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                            <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
                        </div>

                        {{-- Email (Selalu Tampil) --}}
                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">E-Mail <span
                                    class="text-red-500">*</span></label>
                            <x-text-input id="email" type="email" name="email" :value="old('email', $permohonan->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- Nomor HP (Selalu Tampil) --}}
                        <div>
                            <label for="no_hp"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">No. Telepon /
                                Ext <span class="text-red-500">*</span></label>
                            <x-text-input id="no_hp" type="text" name="no_hp" :value="old('no_hp', $permohonan->no_hp)" required />
                            <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                        </div>
                    </div>

                    {{-- === BAGIAN DATA SURAT (Selalu Tampil) === --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div
                            class="col-span-1 md:col-span-2 font-bold text-lg text-gray-800 dark:text-white/90 pt-4 border-t dark:border-gray-700">
                            Data Surat
                        </div>
                        <div>
                            <label for="nomor_surat"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">Nomor Surat
                                <span class="text-red-500">*</span></label>
                            <x-text-input id="nomor_surat" type="text" name="nomor_surat" :value="old('nomor_surat', $permohonan->nomor_surat)"
                                required />
                        </div>
                        <div>
                            <label for="tanggal_surat"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">Tanggal Surat
                                <span class="text-red-500">*</span></label>
                            <x-datepicker-input id="tanggal_surat" name="tanggal_surat" :value="old('tanggal_surat', $permohonan->tanggal_surat)" required />
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label for="perihal"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">Perihal</label>
                            <textarea id="perihal" name="perihal" rows="3" class="block mt-1 w-full rounded-lg border ...">{{ old('perihal', $permohonan->perihal) }}</textarea>
                        </div>
                        <div class="col-span-1 md:col-span-2">
                            <label for="file_surat"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">Upload
                                File Surat dan Porposal Penelitian Untuk Dosen/Mahasiswa/Akademisi</label>
                            <input type="file" name="file_surat" id="file_surat"
                                class="block w-full text-sm border rounded-lg text-gray-500 border-gray-300 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600">
                            <span class="text-sm text-gray-500">File saat ini: <a
                                    href="{{ Storage::url($permohonan->file_surat) }}" target="_blank"
                                    class="text-emerald-500 hover:underline">Lihat File</a></span>
                            <x-input-error :messages="$errors->get('file_surat')" class="mt-2" />
                        </div>
                    </div>

                    {{-- === BAGIAN DETAIL IGT (Data dari halaman katalog) === --}}
                    <div class="mt-4 pt-4 border-t dark:border-gray-700">
                        <h3 class="font-bold text-lg text-gray-800 pb-2 dark:text-white/90">
                            Detail Data IGT yang Diminta
                        </h3>
                        <div class="space-y-4">
                            @foreach ($permohonan->detailPermohonan as $index => $detail)
                                <div
                                    class="grid grid-cols-1 lg:grid-cols-2 gap-4 p-4 border rounded-lg dark:border-gray-700">
                                    {{-- 1. Data IGT (Read-only) --}}
                                    <div class="lg:col-span-1">
                                        <label for="data_igt_{{ $index }}"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Jenis Data
                                        </label>
                                        <p id="data_igt_{{ $index }}"
                                            class="mt-1 p-2 bg-gray-50 dark:bg-gray-800 rounded-md border dark:border-gray-700 font-medium text-gray-900 dark:text-white/90">
                                            {{ $detail->dataIgt->jenis_data }}
                                            <span class="text-xs text-gray-500">(Format:
                                                {{ $detail->dataIgt->format_data }})</span>
                                        </p>
                                        {{-- Input tersembunyi untuk ID IGT --}}
                                        <input type="hidden"
                                            name="requested_data[{{ $index }}][daftar_igt_id]"
                                            value="{{ $detail->daftar_igt_id }}">
                                    </div>
                                    {{-- 2. Dropdown Cakupan (Wajib diisi) --}}
                                    <div class="lg:col-span-1">
                                        <label for="cakupan_{{ $index }}"
                                            class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                                            Cakupan Wilayah <span class="text-red-500">*</span>
                                        </label>
                                        <select id="cakupan_{{ $index }}"
                                            name="requested_data[{{ $index }}][cakupan_wilayah]" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">-- Pilih Cakupan --</option>
                                            @foreach ($cakupanOptions as $cakupan)
                                                <option value="{{ $cakupan }}" @selected(old('requested_data.' . $index . '.cakupan_wilayah', $detail->cakupan_wilayah) == $cakupan)>
                                                    {{ $cakupan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-center justify-end mt-6 pt-4 border-t dark:border-gray-700">
                    <a href="{{ route('permohonanspasial.saya') }}"
                        class="text-sm text-gray-600 hover:text-gray-900 mr-4 dark:text-gray-400 dark:hover:text-white">
                        Batal
                    </a>

                    {{-- Tombol Simpan (nonaktif jika IGT tidak ada ATAU tipe belum dipilih) --}}
                    <x-primary-button :disabled="!isset($selectedIgt) || $selectedIgt->isEmpty()"
                        x-bind:disabled="userType === '' ||
                            {{ !isset($selectedIgt) || $selectedIgt->isEmpty() ? 'true' : 'false' }}">
                        Simpan Data
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-jig-layout>
