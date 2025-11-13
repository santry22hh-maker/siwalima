<x-jiglayout>

    <div class="max-w-2xl mx-auto mt-2 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-lg">
        <h1 class="text-2xl font-semibold text-green-700 mb-2">Layanan Pengaduan</h1>
        <p class="text-gray-600 dark:text-gray-300 mb-6">
            Gunakan formulir ini untuk menyampaikan pengaduan atau masukan terkait layanan Balai Pemantapan Kawasan
            Hutan.
        </p>

        @if (session('success'))
            <div class="mb-4 p-4 text-green-800 bg-green-100 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('pengaduan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <input type="text" name="nama" class="w-full border-gray-300 rounded-lg p-2"
                    placeholder="Nama Lengkap" required>
            </div>
            <div>
                <input type="text" name="instansi" class="w-full border-gray-300 rounded-lg p-2"
                    placeholder="Instansi / Asal Pengguna" required>
            </div>
            <div>
                <input type="email" name="email" class="w-full border-gray-300 rounded-lg p-2"
                    placeholder="Alamat Email / Kontak" required>
            </div>
            <div>
                <textarea name="pesan" rows="4" class="w-full border-gray-300 rounded-lg p-2"
                    placeholder="Isi Pengaduan atau Masukan" required></textarea>
            </div>
            <div>
                <label class="block text-sm text-gray-600 mb-1">Unggah Berkas Pendukung (opsional)</label>
                <input type="file" name="file"
                    class="block w-full text-sm border rounded-lg text-gray-500 border-gray-300 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600">
            </div>
            <div class="pt-3">
                <button type="submit"
                    class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg w-full font-semibold">
                    Kirim Pengaduan
                </button>
            </div>
        </form>
    </div>
</x-jiglayout>
