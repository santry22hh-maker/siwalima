<x-jig-layout>
    {{-- 
      Gunakan Alpine.js untuk mengontrol halaman (step) 
      x-data="{ step: 1 }"
    --}}
    <div class="px-2 mb-4" x-data="{ step: 1 }">
        <div
            class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">

            {{-- Header Card --}}
            <div class="border-b border-gray-200 pb-4 mb-6 dark:border-gray-800">
                <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                    Kuesioner Pelayanan Publik
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1" x-text="'Halaman ' + step + ' dari 4'"></p>
            </div>

            {{-- Menampilkan error validasi JIKA ADA --}}
            @if ($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Oops! Ada yang salah.</strong>
                    <span class="block sm:inline">Mohon periksa kembali isian Anda di semua halaman.</span>
                </div>
            @endif

            {{-- Form Survey --}}
            <form action="{{ route('survey.store') }}" method="POST">
                @csrf

                {{-- === TAMBAHKAN BLOK INPUT TERSEMBUNYI INI === --}}
                @if (isset($permohonan_id))
                    <input type="hidden" name="permohonan_id" value="{{ $permohonan_id }}">
                @endif
                {{-- ========================================== --}}


                {{-- =============================================== --}}
                {{-- Halaman 1: Informasi Personal --}}
                {{-- =============================================== --}}

                <div x-show="step === 1" class="space-y-6">
                    <h4 class="font-semibold text-gray-800 dark:text-white/90">Informasi Personal Pengguna Layanan</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- === PERUBAHAN 1: NAMA LENGKAP === --}}
                        <div>
                            <label for="nama_lengkap"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">1. Nama Lengkap
                                <span class="text-red-500">*</span></label>
                            <x-text-input id="nama_lengkap" type="text" name="nama_lengkap" {{-- Isi value dari controller, tambahkan style disabled --}}
                                :value="old('nama_lengkap', $permohonan->nama_pemohon ?? '')" required readonly class="bg-gray-100 dark:bg-gray-800" />
                            <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">2. Jenis
                                Kelamin <span class="text-red-500">*</span></label>
                            <select name="jenis_kelamin"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-900 dark:border-gray-700"
                                required>
                                <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
                                <option value="LAKI-LAKI" @if (old('jenis_kelamin') == 'LAKI-LAKI') selected @endif>LAKI-LAKI
                                </option>
                                <option value="PEREMPUAN" @if (old('jenis_kelamin') == 'PEREMPUAN') selected @endif>PEREMPUAN
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">3. Pekerjaan
                                <span class="text-red-500">*</span></label>
                            <select name="pekerjaan"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-900 dark:border-gray-700"
                                required>
                                <option value="" disabled selected>-- Pilih Pekerjaan --</option>
                                <option value="PEGAWAI PEMERINTAH" @if (old('pekerjaan') == 'PEGAWAI PEMERINTAH') selected @endif>
                                    PEGAWAI PEMERINTAH</option>
                                <option value="SWASTA" @if (old('pekerjaan') == 'SWASTA') selected @endif>SWASTA</option>
                                <option value="DOSEN/MAHASISWA/AKADEMISI"
                                    @if (old('pekerjaan') == 'DOSEN/MAHASISWA/AKADEMISI') selected @endif>DOSEN/MAHASISWA/AKADEMISI</option>
                                <option value="LEMBAGA MASYARAKAT" @if (old('pekerjaan') == 'LEMBAGA MASYARAKAT') selected @endif>
                                    LEMBAGA MASYARAKAT</option>
                                <option value="LAINNYA" @if (old('pekerjaan') == 'LAINNYA') selected @endif>LAINNYA
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('pekerjaan')" class="mt-2" />
                        </div>

                        {{-- === PERUBAHAN 2: INSTANSI === --}}
                        <div>
                            <label for="instansi"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">4.
                                Instansi/Swasta/Lembaga <span class="text-red-500">*</span></label>
                            <x-text-input id="instansi" type="text" name="instansi" :value="old('instansi', $permohonan->instansi ?? '')" required
                                readonly class="bg-gray-100 dark:bg-gray-800" />
                            <x-input-error :messages="$errors->get('instansi')" class="mt-2" />
                        </div>

                        {{-- === PERUBAHAN 3: EMAIL === --}}
                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">5. E-Mail <span
                                    class="text-red-500">*</span></label>
                            <x-text-input id="email" type="email" name="email" :value="old('email', $permohonan->email ?? '')" required
                                readonly class="bg-gray-100 dark:bg-gray-800" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        {{-- === PERUBAHAN 4: TELEPON === --}}
                        <div>
                            <label for="telepon"
                                class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">6. Nomor Telepon
                                <span class="text-red-500">*</span></label>
                            <x-text-input id="telepon" type="text" name="telepon" :value="old('telepon', $permohonan->no_hp ?? '')" required
                                readonly class="bg-gray-100 dark:bg-gray-800" />
                            <x-input-error :messages="$errors->get('telepon')" class="mt-2" />
                        </div>
                    </div>
                </div>

                {{-- =============================================== --}}
                {{-- Halaman 2: Informasi Layanan --}}
                {{-- =============================================== --}}
                <div x-show="step === 2" class="space-y-6" style="display: none;">
                    <h4 class="font-semibold text-gray-800 dark:text-white/90">Informasi Layanan</h4>

                    <div>
                        <label for="tanggal_pelayanan"
                            class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">7. Tanggal
                            Permohonan Pelayanan <span class="text-red-500">*</span></label>
                        <x-datepicker-input id="tanggal_pelayanan" name="tanggal_pelayanan" :value="old('tanggal_pelayanan')"
                            required />
                        <x-input-error :messages="$errors->get('tanggal_pelayanan')" class="mt-2" /> {{-- PESAN ERROR --}}
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">8. Kebutuhan Pelayanan
                            <span class="text-red-500">*</span></label>
                        <p class="text-xs text-gray-500 mb-2">Boleh pilih lebih dari satu.</p>
                        <div class="space-y-2 mt-2">
                            {{-- ... (semua checkbox) ... --}}
                            <label class="flex items-center">
                                <input type="checkbox" name="kebutuhan_pelayanan[]" value="Permintaan Data IGT"
                                    class="rounded text-brand-500"
                                    {{ in_array('Permintaan Data IGT', old('kebutuhan_pelayanan', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Permintaan Data dan
                                    Informasi Geospasial Tematik (IGT) KLHK</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="kebutuhan_pelayanan[]" value="Analisis Status Kawasan"
                                    class="rounded text-brand-500"
                                    {{ in_array('Analisis Status Kawasan', old('kebutuhan_pelayanan', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Analisis Status dan Fungsi
                                    Kawasan Hutan</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="kebutuhan_pelayanan[]" value="Permintaan Peta Tematik"
                                    class="rounded text-brand-500"
                                    {{ in_array('Permintaan Peta Tematik', old('kebutuhan_pelayanan', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Permintaan Peta Tematik
                                    Kehutanan</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="kebutuhan_pelayanan[]" value="Permohonan Tata Batas"
                                    class="rounded text-brand-500"
                                    {{ in_array('Permohonan Tata Batas', old('kebutuhan_pelayanan', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Permohonan Tata Batas Areal
                                    Kerja</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="kebutuhan_pelayanan[]" value="Lainnya"
                                    class="rounded text-brand-500"
                                    {{ in_array('Lainnya', old('kebutuhan_pelayanan', [])) ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Yang lain: (Jelaskan di
                                    Kritik/Saran)</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('kebutuhan_pelayanan')" class="mt-2" /> {{-- PESAN ERROR --}}
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">9. Tujuan Penggunaan
                            Hasil Pelayanan <span class="text-red-500">*</span></label>
                        <div class="space-y-2 mt-2">
                            <label class="flex items-center">
                                <input type="radio" name="tujuan_penggunaan" value="Tujuan Kedinasan/Pekerjaan"
                                    class="form-radio text-brand-500" required
                                    {{ old('tujuan_penggunaan') == 'Tujuan Kedinasan/Pekerjaan' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Tujuan
                                    Kedinasan/Pekerjaan</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="tujuan_penggunaan" value="Penelitian/Riset"
                                    class="form-radio text-brand-500"
                                    {{ old('tujuan_penggunaan') == 'Penelitian/Riset' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Penelitian/Riset</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="tujuan_penggunaan" value="Informasi Umum"
                                    class="form-radio text-brand-500"
                                    {{ old('tujuan_penggunaan') == 'Informasi Umum' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Mendapatkan informasi
                                    umum</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="tujuan_penggunaan" value="Publikasi"
                                    class="form-radio text-brand-500"
                                    {{ old('tujuan_penggunaan') == 'Publikasi' ? 'checked' : '' }}>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Publikasi/Penyebarluasan
                                    Informasi</span>
                            </label>
                        </div>
                        <x-input-error :messages="$errors->get('tujuan_penggunaan')" class="mt-2" /> {{-- PESAN ERROR --}}
                    </div>
                </div>

                {{-- =============================================== --}}
                {{-- Halaman 3: Pendapat Responden --}}
                {{-- =============================================== --}}
                <div x-show="step === 3" class="space-y-6" style="display: none;">
                    <h4 class="font-semibold text-gray-800 dark:text-white/90">Pendapat Responden tentang Petugas
                        Layanan</h4>

                    @php
                        function renderRadioGroup($name, $question, $options, $errors)
                        {
                            $html =
                                '<div class="pt-4 border-t dark:border-gray-700"><label class="block text-sm font-medium text-gray-700 dark:text-gray-400">' .
                                $question .
                                ' <span class="text-red-500">*</span></label><div class="mt-2 space-y-2">';
                            $oldValue = old($name);
                            foreach ($options as $option) {
                                $checked = $oldValue == $option ? 'checked' : '';
                                $html .=
                                    '<label class="flex items-center"><input type="radio" name="' .
                                    $name .
                                    '" value="' .
                                    $option .
                                    '" class="form-radio text-brand-500" required ' .
                                    $checked .
                                    '><span class="ml-2 text-sm text-gray-700 dark:text-gray-300">' .
                                    $option .
                                    '</span></label>';
                            }
                            $html .= '</div>';
                            if ($errors->has($name)) {
                                $html .= '<div class="mt-2 text-sm text-red-600">' . $errors->first($name) . '</div>';
                            }
                            $html .= '</div>';
                            return $html;
                        }
                    @endphp

                    {!! renderRadioGroup(
                        'pernah_layanan',
                        '10. Apakah Saudara sudah pernah mendapatkan layanan publik dari BPKH Wilayah IX Ambon sebelumnya?',
                        ['Belum', 'Sudah'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'info_layanan',
                        '11. Bagaimana Saudara mendapatkan informasi tentang layanan?',
                        ['Dari Teman', 'Dari Media Sosial', 'Dari Youtube', 'Lainnya'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'cara_layanan',
                        '12. Bagaimana langkah saudara untuk mendapatkan layanan publik?',
                        ['Mengirimkan surat/email', 'Melalui telepon / Whatsapp', 'Mendatangi kantor langsung', 'Lainnya'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_petugas_ditemui',
                        '13. Kemudahan petugas layanan untuk ditemui?',
                        ['Tidak Mudah', 'Kurang Mudah', 'Mudah', 'Sangat Mudah'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_petugas_dihubungi',
                        '14. Kemudahan petugas layanan untuk dihubungi?',
                        ['Tidak Mudah', 'Kurang Mudah', 'Mudah', 'Sangat Mudah'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_kompetensi',
                        '15. Kompetensi/pemahaman petugas dalam pelayanan?',
                        ['Tidak Kompeten', 'Kurang Kompeten', 'Kompeten', 'Sangat Kompeten'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_kesopanan',
                        '16. Perilaku petugas terkait kesopanan dan keramahan?',
                        ['Tidak sopan dan ramah', 'Kurang sopan dan ramah', 'Sopan dan ramah', 'Sangat sopan dan ramah'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_info_jelas',
                        '17. Perilaku petugas dalam memberikan kejelasan informasi?',
                        ['Tidak Informatif', 'Kurang Informatif', 'Cukup Informatif', 'Sangat Informatif'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_syarat_sesuai',
                        '18. Kesesuaian persyaratan pelayanan dengan jenis pelayanannya?',
                        ['Tidak Sesuai', 'Kurang Sesuai', 'Sesuai', 'Sangat Sesuai'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_syarat_wajar',
                        '19. Kewajaran syarat dalam pemenuhan layanan?',
                        ['Tidak Wajar', 'Kurang Wajar', 'Wajar', 'Sangat Wajar'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_prosedur_mudah',
                        '20. Kemudahan prosedur/tahap pelayanan?',
                        ['Tidak Mudah', 'Kurang Mudah', 'Mudah', 'Sangat Mudah'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_waktu_cepat',
                        '21. Kecepatan waktu dalam memberikan pelayanan?',
                        ['Tidak Cepat', 'Kurang Cepat', 'Cepat', 'Sangat Cepat'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_biaya',
                        '22. Apakah Saudara dikenakan biaya dalam mendapatkan Pelayanan?',
                        ['Iya, sangat Mahal', 'Iya, cukup Mahal', 'Iya, Murah', 'Tidak, Gratis'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_hasil_sesuai',
                        '23. Kesesuaian hasil pelayanan yang diberikan?',
                        ['Tidak Sesuai', 'Kurang Sesuai', 'Sesuai', 'Sangat Sesuai'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_kualitas_rekaman',
                        '24. Kualitas rekaman (softcopy atau hardcopy) yang diberikan?',
                        ['Tidak Memuaskan', 'Kurang Memuaskan', 'Memuaskan', 'Sangat Memuaskan'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_layanan_keseluruhan',
                        '25. Tanggapan Saudara tentang layanan publik secara keseluruhan?',
                        ['Tidak Memuaskan', 'Kurang Memuaskan', 'Memuaskan', 'Sangat Memuaskan'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_sarpras',
                        '26. Kualitas sarana dan prasarana pendukung pelayanan?',
                        ['Buruk', 'Cukup', 'Baik', 'Sangat Baik'],
                        $errors,
                    ) !!}
                    {!! renderRadioGroup(
                        'q_penanganan_pengaduan',
                        '27. Penanganan pengaduan pengguna layanan?',
                        ['Tidak ada sarana', 'Ada tetapi tidak berfungsi', 'Berfungsi kurang maksimal', 'Dikelola dengan baik'],
                        $errors,
                    ) !!}
                </div>

                {{-- =============================================== --}}
                {{-- Halaman 4: Kritik & Saran --}}
                {{-- =============================================== --}}
                <div x-show="step === 4" class="space-y-6" style="display: none;">
                    <h4 class="font-semibold text-gray-800 dark:text-white/90">Kritik & Saran</h4>

                    <div>
                        <label for="kritik_saran"
                            class="block text-sm font-medium text-gray-700 mb-1 dark:text-gray-400">
                            28. Kritik, saran dan harapan terhadap perbaikan kualitas layanan publik.
                        </label>
                        <textarea id="kritik_saran" name="kritik_saran" rows="8"
                            class="block mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 shadow-sm focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:text-white/90">{{ old('kritik_saran') }}</textarea>
                        <x-input-error :messages="$errors->get('kritik_saran')" class="mt-2" /> {{-- PESAN ERROR --}}
                    </div>
                </div>

                {{-- Tombol Navigasi Halaman --}}
                <div class="flex items-center justify-between mt-6 pt-4 border-t dark:border-gray-700">
                    {{-- Tombol Kembali --}}
                    <button type="button" x-show="step > 1" @click.prevent="step--"
                        class="inline-flex items-center gap-2 rounded-lg bg-gray-200 px-4 py-2 text-sm font-medium text-gray-800 transition hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        <i class="fas fa-arrow-left"></i>
                        Kembali
                    </button>
                    {{-- Placeholder agar tombol 'Berikutnya' tetap di kanan --}}
                    <div x-show="step === 1" style="display: none;"></div>

                    {{-- Tombol Berikutnya --}}
                    <button type="button" x-show="step < 4" @click.prevent="step++"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-brand-600">
                        Berikutnya
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    {{-- Tombol Kirim (Hanya di halaman terakhir) --}}
                    <button type="submit" x-show="step === 4"
                        class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-green-700">
                        <i class="fas fa-paper-plane"></i>
                        Kirim Survei
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-jig-layout>
