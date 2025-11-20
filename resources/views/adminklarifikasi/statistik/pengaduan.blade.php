<x-klarifikasi-layout>

    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-4">
            <h3 class="text-xl font-medium text-gray-800 dark:text-white/90">
                Statistik Pengaduan Klarifikasi
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Ringkasan status semua pengaduan layanan klarifikasi.
            </p>
        </div>
    </div>

    <div class="px-2 mb-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

        <div class="rounded-lg border border-yellow-300 bg-white dark:border-yellow-700 dark:bg-gray-800 p-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 bg-yellow-100 dark:bg-yellow-900 rounded-lg p-3">
                    <i class="fas fa-inbox fa-lg text-yellow-600 dark:text-yellow-300"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengaduan Baru</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $baru }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-blue-300 bg-white dark:border-blue-700 dark:bg-gray-800 p-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 bg-blue-100 dark:bg-blue-900 rounded-lg p-3">
                    <i class="fas fa-spinner fa-lg text-blue-600 dark:text-blue-300"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ditindaklanjuti</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $diproses }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-green-300 bg-white dark:border-green-700 dark:bg-gray-800 p-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 bg-green-100 dark:bg-green-900 rounded-lg p-3">
                    <i class="fas fa-check-circle fa-lg text-green-600 dark:text-green-300"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Selesai</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $selesai }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-gray-300 bg-white dark:border-gray-700 dark:bg-gray-800 p-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg p-3">
                    <i class="fas fa-comments fa-lg text-gray-600 dark:text-gray-300"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengaduan</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $total }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-gray-300 bg-white dark:border-gray-700 dark:bg-gray-800 p-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg p-3">
                    <i class="fas fa-reply fa-lg text-gray-600 dark:text-gray-300"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Respons</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $avgResponseTime }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-gray-300 bg-white dark:border-gray-700 dark:bg-gray-800 p-4 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="flex-shrink-0 bg-gray-100 dark:bg-gray-700 rounded-lg p-3">
                    <i class="fas fa-hourglass-end fa-lg text-gray-600 dark:text-gray-300"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Selesai</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $avgResolveTime }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Container Grafik & Tabel --}}
    <div class="px-2 mb-4 grid grid-cols-1 lg:grid-cols-2 gap-4">

        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <h4 class="text-lg font-medium text-gray-800 dark:text-white/90">Proporsi Status Pengaduan</h4>
            <div class="mt-4" style="height: 350px; position: relative; margin: auto;">
                <canvas id="pengaduanChartStatus"></canvas>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <h4 class="text-lg font-medium text-gray-800 dark:text-white/90">Kinerja Penelaah (Pengaduan)</h4>
            <div class="mt-4" style="height: 350px; position: relative; margin: auto;">
                <canvas id="pengaduanChartPenelaah"></canvas>
            </div>
        </div>
    </div>

    {{-- Tabel Rangkuman --}}
    <div class="px-2 mb-4">
        <div class="rounded-lg border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
            <h4 class="text-lg font-medium text-gray-800 dark:text-white/90">Rangkuman Data Status</h4>
            <div class="mt-4 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th
                                class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Status</th>
                            <th
                                class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Jumlah</th>
                            <th
                                class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                Persentase</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">
                                Baru</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 text-right">
                                {{ $baru }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 text-right">
                                {{ $total > 0 ? number_format(($baru / $total) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">
                                Ditindaklanjuti</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 text-right">
                                {{ $diproses }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 text-right">
                                {{ $total > 0 ? number_format(($diproses / $total) * 100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">
                                Selesai</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 text-right">
                                {{ $selesai }}</td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300 text-right">
                                {{ $total > 0 ? number_format(($selesai / $total) * 100, 1) : 0 }}%</td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                TOTAL</td>
                            <td
                                class="px-4 py-2 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white text-right">
                                {{ $total }}</td>
                            <td
                                class="px-4 py-2 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white text-right">
                                100%</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Memuat Chart.js --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // Fungsi helper untuk warna teks dark mode
                function getChartTextColor() {
                    return document.documentElement.classList.contains('dark') ? '#E5E7EB' : '#374151';
                }
                const newGridColor = () => document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB';


                // === 1. Chart Status Pengaduan (Pie) ===
                const ctxPie = document.getElementById('pengaduanChartStatus').getContext('2d');
                const chartDataPie = {!! $chartDataStatus !!};

                const myPieChart = new Chart(ctxPie, {
                    type: 'pie',
                    data: chartDataPie,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: getChartTextColor()
                                }
                            }
                        }
                    },
                });

                // === 2. Chart Kinerja Penelaah (Bar) ===
                const ctxBar = document.getElementById('pengaduanChartPenelaah').getContext('2d');
                const chartDataBar = {!! $chartDataPenelaah !!};

                const myBarChart = new Chart(ctxBar, {
                    type: 'bar',
                    data: chartDataBar,
                    options: {
                        indexAxis: 'y', // <-- Bar horizontal
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            x: {
                                ticks: {
                                    color: getChartTextColor()
                                },
                                grid: {
                                    color: newGridColor()
                                }
                            },
                            y: {
                                ticks: {
                                    color: getChartTextColor()
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    },
                });


                // === 3. Observer untuk Dark Mode (Memperbarui KEDUA chart) ===
                const observer = new MutationObserver(mutations => {
                    mutations.forEach(mutation => {
                        if (mutation.attributeName === 'class') {
                            const newTextColor = getChartTextColor();

                            // Update Pie Chart
                            myPieChart.options.plugins.legend.labels.color = newTextColor;

                            // Update Bar Chart
                            myBarChart.options.scales.x.ticks.color = newTextColor;
                            myBarChart.options.scales.y.ticks.color = newTextColor;
                            myBarChart.options.scales.x.grid.color = newGridColor();

                            // Render ulang kedua chart
                            myPieChart.update();
                            myBarChart.update();
                        }
                    });
                });

                observer.observe(document.documentElement, {
                    attributes: true
                });
            });
        </script>
    @endpush
</x-klarifikasi-layout>
