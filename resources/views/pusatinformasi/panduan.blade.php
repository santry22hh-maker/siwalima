<x-klarifikasi-layout>
    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
            <h3 class="text-xl font-medium text-gray-800 dark:text-white/90">
                📘 Panduan Layanan
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Alur dan panduan penggunaan layanan kami.
            </p>
        </div>
    </div>

    <div
        class="min-h-screen flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-900 text-gray-800 dark:text-white">
        <div class="text-center px-4">
            <h1 class="text-6xl font-extrabold mb-4 animate-pulse text-blue-600 dark:text-blue-400">🚧</h1>
            <h2 class="text-4xl font-bold mb-2">Coming Soon</h2>
            <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">
                Halaman ini sedang dalam pengembangan. Tetap pantau untuk update terbaru!
            </p>

            <!-- Tombol Kembali -->
            <a href="{{ url('/') }}"
                class="inline-block px-6 py-3 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700 transition">
                Kembali ke Beranda
            </a>
        </div>

        <!-- Optional: Countdown -->
        <div class="mt-10 text-gray-500 dark:text-gray-400">
            <p>Estimasi rilis dalam beberapa minggu...</p>
        </div>
    </div>
</x-klarifikasi-layout>
