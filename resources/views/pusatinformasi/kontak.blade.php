<x-klarifikasi-layout>
    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
            <h3 class="text-xl font-medium text-gray-800 dark:text-white/90">
                ☎️ Kontak Layanan
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Hubungi kami jika Anda membutuhkan bantuan.
            </p>
        </div>
    </div>

    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Info Kontak --}}
                <div class="space-y-4">
                    <div>
                        <h4 class="text-lg font-medium text-gray-800 dark:text-white/90">Alamat Kantor</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Balai Pemantapan Kawasan Hutan (BPKH) Wilayah IX
                            <br>
                            Jalan. (Alamat lengkap kantor Anda)
                            <br>
                            Ambon, Maluku, Indonesia
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-gray-800 dark:text-white/90">Jam Operasional</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Senin - Jumat
                            <br>
                            08:00 - 16:00 WIT
                        </p>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium text-gray-800 dark:text-white/90">Kontak</h4>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Email: <a href="mailto:kontak@bpkh9ambon.go.id"
                                class="text-blue-600 hover:underline">kontak@bpkh9ambon.go.id</a>
                            <br>
                            Telepon: (0911) 123-456
                        </p>
                    </div>
                </div>

                {{-- Peta Lokasi Kantor (Opsional) --}}
                <div>
                    <h4 class="text-lg font-medium text-gray-800 dark:text-white/90 mb-2">Lokasi Kami</h4>
                    <div class="rounded-lg overflow-hidden border dark:border-gray-700">
                        {{-- Ganti 'embed' ini dengan Google Maps kantor Anda --}}
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3981.428544976418!2d128.1754963152646!3d-3.702084997290466!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d6cef0a8f4b5a3b%3A0x60412613d09c6933!2sBPKH%20Wilayah%20IX%20Ambon!5e0!3m2!1sid!2sid!4v1678888888888!5m2!1sid!2sid"
                            width="100%" height="350" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-klarifikasi-layout>
