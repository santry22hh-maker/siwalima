<x-jig-layout>
    {{-- Memuat library Chart.js --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endpush

    <div class="px-2 mb-4 space-y-4">

        {{-- 1. HEADER --}}
        <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-6 py-4">
            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                Statistik Layanan Pengaduan
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Ringkasan jumlah pengaduan yang diterima berdasarkan status.
            </p>
        </div>

        {{-- 2. STAT CARDS (KPI) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {{-- Total Pengaduan --}}
            <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-blue-100 dark:bg-blue-900/50 p-3">
                        <i class="fas fa-file-alt fa-lg text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengaduan</p>
                        <p class="text-3xl font-semibold text-gray-900 dark:text-white/90">{{ $totalPengaduan }}</p>
                    </div>
                </div>
            </div>

            {{-- Pending (Baru) --}}
            <div
                class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-6 py-4">
                <div class="flex items-center gap-4">
                    <div class="rounded-full bg-red-100 dark:bg-red-900/50 p-3">
                        <i class="fas fa-pause-circle fa-lg text-red-600 dark:text-red-400"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending (Baru)</p>
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
        </div>

        {{-- 3. CHART (GRAFIK) --}}
        <div class="grid grid-cols-1">
            <div
                class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] px-6 py-4">
                <h4 class="text-base font-medium text-gray-800 dark:text-white/90 mb-4">
                    Distribusi Status Pengaduan
                </h4>
                <div style="max-width: 450px; margin-left: auto; margin-right: auto;">
                    {{-- Kanvas untuk Chart.js --}}
                    <canvas id="pengaduanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk inisialisasi Chart.js --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', (event) => {

                const ctx = document.getElementById('pengaduanChart').getContext('2d');
                const labels = @json($chartLabels);
                const data = @json($chartData);
                const doughnutColors = @json($doughnutChartColors);
                const doughnutBorders = @json($doughnutBorderColors);
                const isDark = window.Alpine.store('theme').isDark;
                const labelColor = isDark ? '#FFF' : '#374151';

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Pengaduan',
                            data: data,
                            backgroundColor: doughnutColors, // Data dari controller
                            borderColor: doughnutBorders, // Data dari controller
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: labelColor
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
</x-jig-layout>
