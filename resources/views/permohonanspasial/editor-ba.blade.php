<x-jig-layout>
    @push('styles')
        {{-- CSS TinyMCE Fullscreen Z-index Fix --}}
        <style>
            .tox-fullscreen {
                /* Nilai ini harus lebih tinggi dari z-index sidebar/header Anda */
                z-index: 100000 !important;
            }
        </style>
    @endpush

    <div x-data="{ page: 'Buat Berita Acara' }">

        {{-- Konten Utama Halaman --}}
        <div class="px-2 mb-4">
            <div
                class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-white/[0.03]">

                <form method="POST" action="{{ route('permohonanspasial.generateBAFromEditor', $permohonan->id) }}">
                    @csrf

                    {{-- HEADER: Tombol Kembali & Judul --}}
                    <div
                        class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 pb-4 mb-6 dark:border-gray-800">
                        <div>
                            <a href="{{ route('permohonanspasial.index') }}"
                                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800">
                                <i class="fas fa-arrow-left"></i>
                                Kembali ke Daftar
                            </a>
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                                Buat BA untuk: <span class="font-bold">{{ $permohonan->nama_pemohon }}</span>
                            </h2>
                        </div>
                    </div>

                    {{-- Editor TinyMCE --}}
                    <div>
                        <label for="isi_surat_final" class="block text-base font-bold text-gray-800 dark:text-white/90">
                            Konten Berita Acara
                        </label>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            Konten di bawah ini sudah terisi otomatis. Anda bisa mengeditnya jika perlu.
                        </p>

                        <textarea id="isi_surat_final" name="isi_surat_final">
                            {!! old('isi_surat_final', $finalContent) !!}
                        </textarea>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end mt-6 pt-4 border-t dark:border-gray-700">
                        <a href="{{ route('permohonanspasial.index') }}"
                            class="text-sm font-medium text-gray-700 hover:text-gray-900 mr-4 dark:text-gray-400 dark:hover:text-white">
                            Batal
                        </a>

                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white shadow-sm ring-1 ring-inset ring-brand-500 transition hover:bg-blue-600 dark:bg-brand-500 dark:text-white dark:ring-brand-500 dark:hover:bg-brand-600">
                            <i class="fas fa-file-pdf"></i>
                            Generate PDF & Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.tiny.cloud/1/k0hnnhkue5tdhjjtorcn9f5wsyx89261nyq8rtatyphnamz3/tinymce/6/tinymce.min.js"
            referrerpolicy="origin"></script>

        <script>
            document.addEventListener('alpine:init', () => {

                // 1. Fungsi untuk menginisialisasi TinyMCE
                function initTinyMCE(isDark) {
                    // Hancurkan instance lama jika ada
                    const oldEditor = tinymce.get('isi_surat_final');
                    if (oldEditor) {
                        oldEditor.destroy();
                    }

                    tinymce.init({
                        selector: 'textarea#isi_surat_final',
                        plugins: 'code table lists image media link fullscreen preview pagebreak',
                        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image media link | preview | pagebreak | fullscreen',
                        height: 600,
                        skin: isDark ? 'oxide-dark' : 'oxide',
                        content_css: isDark ? 'dark' : 'default',

                        // === INI ADALAH PERBAIKANNYA ===
                        // 1. Jangan ubah URL menjadi relatif
                        relative_urls: false,
                        // 2. Jangan hapus domain dari URL
                        remove_script_host: false,
                        // 3. (Opsional) Pastikan URL asset() Anda dianggap benar
                        document_base_url: '{{ config('app.url') }}',
                        // === AKHIR PERBAIKAN ===

                        // Penting: Ambil konten dari textarea secara otomatis
                        init_instance_callback: function(editor) {
                            var initialContent = document.getElementById('isi_surat_final').value;
                            if (initialContent) {
                                editor.setContent(initialContent);
                            }
                        }
                    });
                }

                // 2. Ambil status dark mode awal
                let currentIsDark = window.Alpine.store('theme').isDark;

                // 3. Inisialisasi editor PERTAMA KALI
                initTinyMCE(currentIsDark);

                // 4. Tonton perubahan pada $store.theme.isDark
                window.Alpine.effect(() => {
                    let newIsDark = window.Alpine.store('theme').isDark;

                    // 5. HANYA jalankan jika status 'isDark' BERUBAH
                    if (newIsDark !== currentIsDark) {
                        const editor = tinymce.get('isi_surat_final');
                        let currentContent = '';

                        if (editor) {
                            currentContent = editor.getContent(); // Simpan konten
                        }

                        // Inisialisasi ulang editor dengan tema yang baru
                        initTinyMCE(newIsDark);

                        // Kembalikan konten ke editor yang baru dibuat
                        setTimeout(() => {
                            const newEditor = tinymce.get('isi_surat_final');
                            if (newEditor) {
                                newEditor.setContent(currentContent);
                            }
                        }, 100); // Beri jeda 100ms agar editor siap

                        // Perbarui status 'currentIsDark'
                        currentIsDark = newIsDark;
                    }
                });
            });
        </script>
    @endpush
</x-jig-layout>
