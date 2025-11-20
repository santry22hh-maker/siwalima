<x-klarifikasi-layout>
    @push('styles')
        <style>
            .ikm-score-circle {
                width: 160px;
                height: 160px;
                border-radius: 50%;
                background-color: #f0fdf4;
                border: 8px solid #22c55e;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                margin: 2rem auto;
            }

            .dark .ikm-score-circle {
                background-color: #166534;
                border-color: #86efac;
            }

            .ikm-score-value {
                font-size: 2.75rem;
                font-weight: 700;
                color: #15803d;
            }

            .dark .ikm-score-value {
                color: #f0fdf4;
            }

            .ikm-score-label {
                font-size: 0.875rem;
                font-weight: 500;
                color: #4b5563;
                dark: color: #9ca3af;
            }
        </style>
    @endpush
    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
            <h3 class="text-xl font-medium text-gray-800 dark:text-white/90">
                📊 Laporan Indeks Kepuasan Masyarakat (IKM)
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Layanan Klarifikasi Mandiri
            </p>
        </div>
    </div>

    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <div class="text-center">
                <h4 class="text-lg font-medium text-gray-800 dark:text-white/90">Nilai IKM Gabungan (Skala 100)</h4>
                <div class="ikm-score-circle">
                    <span class="ikm-score-value">{{ $ikmScore }}</span>
                    <span class="ikm-score-label">Mutu: {{ $ikmMutu }}</span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400 max-w-lg mx-auto">
                    Nilai dihitung dari total **{{ $totalResponden }} responden** dari layanan Analisis Status dan
                    Fungsi Kawasan Hutan.
                </p>
            </div>
        </div>
    </div>

    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <h4 class="text-lg font-medium text-gray-800 dark:text-white/90">Rincian Nilai IKM per Unsur Pelayanan</h4>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Unsur Pelayanan</th>
                            <th
                                class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Nilai IKM (Skala 100)</th>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Mutu</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                        @forelse ($ikmPerUnsur as $unsur)
                            <tr>
                                <td
                                    class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">
                                    {{ $unsur['label'] }}</td>
                                <td
                                    class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 text-right">
                                    {{ $unsur['score'] }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                                    {{ $unsur['mutu'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3"
                                    class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada data survei yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-klarifikasi-layout>
