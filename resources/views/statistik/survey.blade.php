<x-jiglayout>
    {{-- Memuat library Chart.js --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <div class="px-2 mb-4 space-y-4">

        {{-- 1. HEADER --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-6 py-4">
            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                Statistik Survey Pelayanan Publik
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Ringkasan hasil survei kepuasan pelanggan.
            </p>
        </div>

        {{-- 2. STAT CARDS (KPI) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Total Responden --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-5 py-3">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-blue-100 dark:bg-blue-900/50 p-3">
                        <i class="fas fa-users fa-lg text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Responden</p>
                        <p class="text-3xl font-semibold text-gray-900 dark:text-white/90">{{ $totalResponden }}</p>
                    </div>
                </div>
            </div>

            {{-- Rata-rata Kepuasan --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-5 py-3">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-yellow-100 dark:bg-yellow-900/50 p-3">
                        <i class="fas fa-star-half-alt fa-lg text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Skor</p>
                        <p class="text-3xl font-semibold text-gray-900 dark:text-white/90">
                            {{ $avgKepuasan ?? 0 }} <span class="text-xl text-gray-500">/ 4</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- 3. CHART (GRAFIK) --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <h4 class="text-base font-medium text-gray-800 dark:text-white/90 mb-4">
                Distribusi Skor Kepuasan
            </h4>
            {{-- Kanvas untuk Chart.js (Bar Chart) --}}
            <canvas id="surveyChart"></canvas>
        </div>

        {{-- 4. TABEL SARAN & MASUKAN --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <h4 class="text-base font-medium text-gray-800 dark:text-white/90 mb-4">
                Kritik & Saran Terbaru
            </h4>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">
                                Responden</th>
                            {{-- Ganti "Saran/Masukan" menjadi "Kritik & Saran" --}}
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">
                                Kritik & Saran</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase dark:text-gray-400">
                                Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-transparent dark:divide-gray-700">
                        @forelse ($saranTerbaru as $saran)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <p class="font-medium text-gray-900 dark:text-white/90">{{ $saran->nama_lengkap }}
                                    </p>
                                    <p class="text-gray-500 dark:text-gray-400">{{ $saran->instansi }}</p>
                                </td>
                                {{-- Ganti $saran->saran_masukan menjadi $saran->kritik_saran --}}
                                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300"
                                    style="white-space: pre-wrap; min-width: 300px;">{{ $saran->kritik_saran }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $saran->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3"
                                    class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada kritik atau saran yang diterima.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Script untuk inisialisasi Chart.js --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {
                const ctx = document.getElementById('surveyChart').getContext('2d');

                // Ambil data dari controller
                const labels = @json($chartLabels);
                const data = @json($chartData);
                const isDark = window.Alpine.store('theme').isDark;
                const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
                const labelColor = isDark ? '#FFF' : '#374151';

                new Chart(ctx, {
                    type: 'bar', // Tipe chart: 'bar'
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Responden',
                            data: data,
                            backgroundColor: [
                                'rgba(239, 68, 68, 0.7)', // 1 - Red
                                'rgba(249, 115, 22, 0.7)', // 2 - Orange
                                'rgba(245, 158, 11, 0.7)', // 3 - Amber
                                'rgba(132, 204, 22, 0.7)', // 4 - Lime
                                'rgba(16, 185, 129, 0.7)', // 5 - Emerald
                                // Tambahkan warna lain jika ada lebih banyak status
                            ],
                            borderColor: [
                                'rgba(239, 68, 68, 1)',
                                'rgba(249, 115, 22, 1)',
                                'rgba(245, 158, 11, 1)',
                                'rgba(132, 204, 22, 1)',
                                'rgba(16, 185, 129, 1)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        // indexAxis: 'y', // <-- Hapus baris ini untuk membuatnya vertikal
                        plugins: {
                            legend: {
                                display: false // Sembunyikan legenda, sudah jelas dari label
                            }
                        },
                        scales: {
                            // Konfigurasi sumbu X (Kategori/Label)
                            x: {
                                ticks: {
                                    color: labelColor
                                },
                                grid: {
                                    display: false
                                } // Sembunyikan grid vertikal
                            },
                            // Konfigurasi sumbu Y (Nilai/Jumlah)
                            y: {
                                ticks: {
                                    color: labelColor
                                },
                                grid: {
                                    color: gridColor
                                }, // Tampilkan grid horizontal
                                beginAtZero: true // Mulai dari 0
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-jiglayout>
