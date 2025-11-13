<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Impor model-model Anda
use App\Models\Permohonan;
use App\Models\SurveyPelayanan;
use App\Models\Pengaduan;

class StatistikController extends Controller
{
    public function igt()
    {
        // 1. Ambil data mentah (Status dan jumlahnya), urutkan agar konsisten
        $statsRaw = Permohonan::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->orderBy('status') // Urutkan berdasarkan abjad status
            ->get();

        $statsMap = $statsRaw->pluck('total', 'status');

        // 2. Siapkan data untuk "Stat Cards" (KPI)
        $totalPermohonan = $statsMap->sum();
        $totalPending = $statsMap->get('Pending', 0);
        $totalSelesai = $statsMap->get('Selesai', 0);
        $totalDiproses = $statsMap->get('Diproses', 0)
            + $statsMap->get('Menunggu TTD Pengguna', 0)
            + $statsMap->get('Menunggu Verifikasi Staf', 0)
            + $statsMap->get('Revisi', 0);
        $avgCompletionDays = Permohonan::where('status', 'Selesai')
            ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
            ->value('avg_days');
        $avgCompletionDays = round($avgCompletionDays, 1);

        // 3. Data untuk Chart Distribusi Status (Donat)
        $chartLabels = $statsRaw->pluck('status');
        $chartData = $statsRaw->pluck('total');

        // === 4. (BARU) DEFINISIKAN PETA WARNA UTAMA ===
        $statusColors = [
            'Selesai' => 'rgba(16, 185, 129, 0.7)',     // Hijau
            'Diproses' => 'rgba(59, 130, 246, 0.7)',    // Biru
            'Menunggu Verifikasi Staf' => 'rgba(245, 158, 11, 0.7)', // Oranye/Amber
            'Revisi' => 'rgba(239, 68, 68, 0.7)',        // Merah
            'Pending' => 'rgba(107, 114, 128, 0.7)',   // Abu-abu
            'Menunggu TTD Pengguna' => 'rgba(139, 92, 246, 0.7)', // Ungu
            'Dibatalkan' => 'rgba(156, 163, 175, 0.7)',  // Abu-abu muda
        ];
        $statusBorderColors = [
            'Selesai' => 'rgba(16, 185, 129, 1)',
            'Diproses' => 'rgba(59, 130, 246, 1)',
            'Menunggu Verifikasi Staf' => 'rgba(245, 158, 11, 1)',
            'Revisi' => 'rgba(239, 68, 68, 1)',
            'Pending' => 'rgba(107, 114, 128, 1)',
            'Menunggu TTD Pengguna' => 'rgba(139, 92, 246, 1)',
            'Dibatalkan' => 'rgba(156, 163, 175, 1)',
        ];

        // 5. (BARU) Buat array warna untuk Donut Chart (sesuai urutan $chartLabels)
        $doughnutChartColors = [];
        $doughnutBorderColors = [];
        foreach ($chartLabels as $label) {
            $doughnutChartColors[] = $statusColors[$label] ?? 'rgba(156, 163, 175, 0.7)'; // Default abu-abu
            $doughnutBorderColors[] = $statusBorderColors[$label] ?? 'rgba(156, 163, 175, 1)';
        }

        // 6. Data Beban Kerja Penelaah (Stacked Bar)
        $penelaahStatsRaw = Permohonan::query()
            ->join('users', 'permohonans.penelaah_id', '=', 'users.id')
            ->whereNotNull('permohonans.penelaah_id')
            ->select('users.name', 'permohonans.status', DB::raw('count(permohonans.id) as total'))
            ->groupBy('users.name', 'permohonans.status')
            ->get();

        $penelaahLabels = $penelaahStatsRaw->pluck('name')->unique()->values();
        $statusLabels = $penelaahStatsRaw->pluck('status')->unique()->values();

        $penelaahChartDatasets = [];
        foreach ($statusLabels as $status) {
            $data = [];
            foreach ($penelaahLabels as $penelaahName) {
                $count = $penelaahStatsRaw
                    ->where('name', $penelaahName)
                    ->where('status', $status)
                    ->first()->total ?? 0;
                $data[] = $count;
            }

            $penelaahChartDatasets[] = [
                'label' => $status,
                'data' => $data,
                'backgroundColor' => $statusColors[$status] ?? 'rgba(156, 163, 175, 0.7)', // Gunakan Peta Warna
                'borderColor' => $statusBorderColors[$status] ?? 'rgba(156, 163, 175, 1)', // Gunakan Peta Warna
                'borderWidth' => 1
            ];
        }

        // 7. Kirim semua data ke view
        return view('statistik.igt', [
            'statsRaw' => $statsRaw,
            'totalPermohonan' => $totalPermohonan,
            'totalPending' => $totalPending,
            'totalSelesai' => $totalSelesai,
            'totalDiproses' => $totalDiproses,
            'avgCompletionDays' => $avgCompletionDays,

            // Data Donut Chart
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'doughnutChartColors' => $doughnutChartColors, // <-- Kirim warna donut
            'doughnutBorderColors' => $doughnutBorderColors, // <-- Kirim border donut

            // Data Bar Chart
            'penelaahChartLabels' => $penelaahLabels,
            'penelaahChartDatasets' => $penelaahChartDatasets,
        ]);
    }

    public function survey()
    {
        // Ganti ini jika nama model Anda berbeda
        $model = new SurveyPelayanan();

        // 1. KPI: Total Responden
        $totalResponden = $model->count();

        // 2. KPI: Rata-rata Skor Kepuasan (KONVERSI TEKS KE ANGKA)
        // Kita harus mengonversi "Sangat Puas" -> 5, "Puas" -> 4, dst.
        // PENTING: Pastikan teks di 'WHEN' sama persis dengan di database Anda!
        $avgKepuasan = $model->select(DB::raw("
                AVG(CASE 
                    WHEN q_layanan_keseluruhan = 'Sangat Memuaskan' THEN 4
                    WHEN q_layanan_keseluruhan = 'Memuaskan' THEN 3
                    WHEN q_layanan_keseluruhan = 'Kurang Memuaskan' THEN 2
                    WHEN q_layanan_keseluruhan = 'Tidak Memuaskan' THEN 1
                    ELSE 0 
                END) as avg_score
            "))->value('avg_score');

        $avgKepuasan = round($avgKepuasan, 1);

        // 3. Chart: Distribusi Skor (Grup berdasarkan teks)
        // Kita gunakan kolom 'q_layanan_keseluruhan'
        $distribusiSkor = $model->select('q_layanan_keseluruhan', DB::raw('count(*) as total'))
            ->whereNotNull('q_layanan_keseluruhan')
            ->groupBy('q_layanan_keseluruhan')
            ->orderBy('q_layanan_keseluruhan')
            ->get();

        // 4. Siapkan data untuk Chart.js
        $chartLabels = $distribusiSkor->pluck('q_layanan_keseluruhan');
        $chartData = $distribusiSkor->pluck('total');

        // 5. Table: Ambil 10 saran & masukan terbaru
        // Ganti 'saran_masukan' menjadi 'kritik_saran'
        $saranTerbaru = $model->whereNotNull('kritik_saran')
            ->where('kritik_saran', '!=', '')
            ->latest()
            ->take(10)
            ->get(['nama_lengkap', 'instansi', 'kritik_saran', 'created_at']);

        // 6. Kirim semua data ke view
        return view('statistik.survey', [
            'totalResponden' => $totalResponden,
            'avgKepuasan' => $avgKepuasan,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'saranTerbaru' => $saranTerbaru,
        ]);
    }

    public function pengaduan()
    {
        // 1. Ambil data mentah (Status dan jumlahnya)
        $statsRaw = Pengaduan::select('status', DB::raw('count(*) as total'))
                            ->groupBy('status')
                            ->orderBy('status')
                            ->get();
        
        $statsMap = $statsRaw->pluck('total', 'status');

        // 2. Siapkan data untuk "Stat Cards" (KPI)
        $totalPengaduan = $statsMap->sum();
        $totalPending = $statsMap->get('Baru', 0); // Status 'Baru'
        $totalDiproses = $statsMap->get('Diproses', 0);
        $totalSelesai = $statsMap->get('Selesai', 0);

        // 3. KPI: Rata-rata Waktu Penyelesaian
        $avgCompletionDays = Pengaduan::where('status', 'Selesai')
            ->selectRaw('AVG(DATEDIFF(updated_at, created_at)) as avg_days')
            ->value('avg_days');
            
        $avgCompletionDays = round($avgCompletionDays, 1);

        // 4. Definisikan Peta Warna
        $statusColors = [
            'Selesai' => 'rgba(16, 185, 129, 0.7)',
            'Diproses' => 'rgba(59, 130, 246, 0.7)',
            'Baru' => 'rgba(239, 68, 68, 0.7)',
            'Dibatalkan' => 'rgba(107, 114, 128, 0.7)',
        ];
        $statusBorderColors = [
            'Selesai' => 'rgba(16, 185, 129, 1)',
            'Diproses' => 'rgba(59, 130, 246, 1)',
            'Baru' => 'rgba(239, 68, 68, 1)',
            'Dibatalkan' => 'rgba(107, 114, 128, 1)',
        ];

        // 5. Data untuk Chart Distribusi Status (Donat)
        $chartLabels = $statsRaw->pluck('status');
        $chartData = $statsRaw->pluck('total');
        
        $doughnutChartColors = [];
        $doughnutBorderColors = [];
        foreach ($chartLabels as $label) {
            $doughnutChartColors[] = $statusColors[$label] ?? 'rgba(156, 163, 175, 0.7)';
            $doughnutBorderColors[] = $statusBorderColors[$label] ?? 'rgba(156, 163, 175, 1)';
        }

        // 6. Kirim semua data ke view
        return view('statistik.pengaduan', [
            'totalPengaduan' => $totalPengaduan,
            'totalPending' => $totalPending,
            'totalDiproses' => $totalDiproses,
            'totalSelesai' => $totalSelesai,
            'avgCompletionDays' => $avgCompletionDays,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'doughnutChartColors' => $doughnutChartColors,
            'doughnutBorderColors' => $doughnutBorderColors,
        ]);
    }
}
