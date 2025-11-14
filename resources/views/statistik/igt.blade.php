<x-jig-layout>
    {{-- Memuat library Chart.js --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <div class="px-2 mb-4 space-y-4"> {{-- PERBAIKAN: space-y-4 menjadi space-y-6 untuk jarak --}}

        {{-- 1. HEADER --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-6 py-4">
            {{-- PERBAIKAN: px-6 py-4 menjadi p-6 --}}
            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                Statistik Layanan Data IGT
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Ringkasan jumlah permohonan data IGT berdasarkan status.
            </p>
        </div>

        {{-- 2. STAT CARDS (KPI) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"> {{-- PERBAIKAN: gap-3 menjadi gap-6 --}}
            {{-- Total Permohonan --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-blue-100 dark:bg-blue-900/50 p-3">
                        <i class="fas fa-file-alt fa-lg text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Permohonan</p>
                        <p class="text-3xl font-semibold text-gray-900 dark:text-white/90">{{ $totalPermohonan }}</p>
                    </div>
                </div>
            </div>

            {{-- Pending --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-gray-100 dark:bg-gray-900/50 p-3">
                        <i class="fas fa-pause-circle fa-lg text-gray-600 dark:text-gray-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending</t>
                        <p class="text-3xl font-semibold text-gray-900 dark:text-white/90">{{ $totalPending }}</p>
                    </div>
                </div>
            </div>

            {{-- Sedang Diproses --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-yellow-100 dark:bg-yellow-900/50 p-3">
                        <i class="fas fa-hourglass-half fa-lg text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sedang Diproses</p>
                        <p class="text-3xl font-semibold text-gray-900 dark:text-white/90">{{ $totalDiproses }}</p>
                    </div>
                </div>
            </div>

            {{-- Selesai --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-green-100 dark:bg-green-900/50 p-3">
                        <i class="fas fa-check-circle fa-lg text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Selesai</p>
                        <p class="text-3xl font-semibold text-gray-900 dark:text-white/90">{{ $totalSelesai }}</p>
                    </div>
                </div>
            </div>

            {{-- Rata-rata Penyelesaian --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-teal-100 dark:bg-teal-900/50 p-3">
                        <i class="fas fa-calendar-check fa-lg text-teal-600 dark:text-teal-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Rata-rata Penyelesaian</p>
                        <p class="text-3xl font-semibold text-gray-900 dark:text-white/90">
                            {{ $avgCompletionDays ?? 0 }} <span class="text-xl">Hari</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Anda bisa tambahkan card ke-6 di sini jika mau --}}

        </div>

        {{-- 3. CHART (GRAFIK) --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            {{-- Chart Distribusi Status --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
                <h4 class="text-base font-medium text-gray-800 dark:text-white/90 mb-4">
                    Distribusi Status Permohonan
                </h4>
                <div style="max-width:350px; margin-left: auto; margin-right: auto;">
                    <canvas id="igtChart"></canvas>
                </div>
            </div>

            {{-- === BLOK 4: TABEL RINCIAN DIGANTI DENGAN CHART BARU === --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] p-6">
                <h4 class="text-base font-medium text-gray-800 dark:text-white/90 mb-4">
                    Beban Kerja Penelaah (Total Permohonan Ditangani)
                </h4>
                {{-- Kanvas untuk Chart.js (Bar Chart) --}}
                <canvas id="penelaahChart"></canvas>
            </div>
            {{-- === AKHIR BLOK PENGGANTI === --}}
        </div>
    </div>

    {{-- Script untuk inisialisasi Chart.js --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {

                // Ambil variabel global untuk tema
                const isDark = window.Alpine.store('theme').isDark;
                const labelColor = isDark ? '#FFF' : '#374151';
                const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

                // === 1. CHART DONAT (DISTRIBUSI STATUS) ===
                const ctx = document.getElementById('igtChart').getContext('2d');
                const labels = @json($chartLabels);
                const data = @json($chartData);

                // --- PERBAIKAN: Ambil warna dari controller ---
                const doughnutColors = @json($doughnutChartColors);
                const doughnutBorders = @json($doughnutBorderColors);

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Permohonan',
                            data: data,
                            backgroundColor: doughnutColors, // <-- Gunakan variabel baru
                            borderColor: doughnutBorders, // <-- Gunakan variabel baru
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: labelColor // Gunakan variabel
                                }
                            }
                        }
                    }
                });

                // === 2. CHART BEBAN KERJA PENELAAH (STACKED) ===
                const ctxPenelaah = document.getElementById('penelaahChart').getContext('2d');
                const penelaahLabels = @json($penelaahChartLabels);

                // Data ini sekarang sudah berisi warna yang benar dari controller
                const penelaahDatasets = @json($penelaahChartDatasets);

                new Chart(ctxPenelaah, {
                    type: 'bar',
                    data: {
                        labels: penelaahLabels,
                        datasets: penelaahDatasets // <-- Gunakan variabel baru
                    },
                    options: {
                        indexAxis: 'y', // Horizontal Bar Chart
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: labelColor
                                }
                            }
                        },
                        scales: {
                            x: {
                                stacked: true, // Nyalakan stacking
                                beginAtZero: true,
                                ticks: {
                                    color: labelColor
                                },
                                grid: {
                                    color: gridColor
                                }
                            },
                            y: {
                                stacked: true, // Nyalakan stacking
                                ticks: {
                                    color: labelColor
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });

            });
        </script>
    @endpush
</x-jig-layout>
