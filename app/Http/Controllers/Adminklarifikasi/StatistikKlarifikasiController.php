<?php

namespace App\Http\Controllers\Adminklarifikasi;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\PermohonanAnalisis;
use App\Models\SurveyAnalisis;
use Illuminate\Support\Facades\DB;

class StatistikKlarifikasiController extends Controller
{
    // Peta Skor untuk IKM
    // Pastikan teks ini SAMA PERSIS dengan di survey/create.blade.php
    protected $scoresMap = [
        'Tidak Sesuai' => 1, 'Kurang Sesuai' => 2, 'Sesuai' => 3, 'Sangat Sesuai' => 4,
        'Tidak Wajar' => 1, 'Kurang Wajar' => 2, 'Wajar' => 3, 'Sangat Wajar' => 4,
        'Tidak Mudah' => 1, 'Kurang Mudah' => 2, 'Mudah' => 3, 'Sangat Mudah' => 4,
        'Tidak Cepat' => 1, 'Kurang Cepat' => 2, 'Cepat' => 3, 'Sangat Cepat' => 4,
        'Iya, sangat Mahal' => 1, 'Iya, cukup Mahal' => 2, 'Iya, Murah' => 3, 'Tidak, Gratis' => 4,
        'Tidak Sesuai' => 1, 'Kurang Sesuai' => 2, 'Sesuai' => 3, 'Sangat Sesuai' => 4,
        'Tidak Memuaskan' => 1, 'Kurang Memuaskan' => 2, 'Memuaskan' => 3, 'Sangat Memuaskan' => 4,
        'Buruk' => 1, 'Cukup' => 2, 'Baik' => 3, 'Sangat Baik' => 4,
        'Tidak ada sarana' => 1, 'Ada tetapi tidak berfungsi' => 2, 'Berfungsi kurang maksimal' => 3, 'Dikelola dengan baik' => 4,
        'Tidak Kompeten' => 1, 'Kurang Kompeten' => 2, 'Kompeten' => 3, 'Sangat Kompeten' => 4,
        'Tidak sopan dan ramah' => 1, 'Kurang sopan dan ramah' => 2, 'Sopan dan ramah' => 3, 'Sangat sopan dan ramah' => 4,
        'Tidak Informatif' => 1, 'Kurang Informatif' => 2, 'Cukup Informatif' => 3, 'Sangat Informatif' => 4,
    ];

    // 9 unsur pelayanan utama (sesuai pertanyaan 18-26 di form Anda)
    protected $ikmQuestions = [
        'q_syarat_sesuai',
        'q_syarat_wajar',
        'q_prosedur_mudah',
        'q_waktu_cepat',
        'q_biaya',
        'q_hasil_sesuai',
        'q_kualitas_rekaman',
        'q_sarpras',
        'q_penanganan_pengaduan',
        'q_kompetensi',
        'q_kesopanan',
        'q_info_jelas',
    ];

    /**
     * Menampilkan statistik Permohonan.
     */
    public function permohonan()
    {
        // 1. Ambil data Status (untuk K-Stats dan Pie Chart)
        $statsPermohonan = PermohonanAnalisis::where('tipe', 'RESMI')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $baru = $statsPermohonan->get('Diajukan', 0);
        $diproses = $statsPermohonan->get('Diproses', 0);
        $selesai = $statsPermohonan->get('Selesai', 0);
        $ditolak = $statsPermohonan->get('Ditolak', 0);
        $total = $baru + $diproses + $selesai + $ditolak;

        // 2. Siapkan data untuk Pie Chart (Status)
        $colorMap = ['Diajukan' => '#FBBF24', 'Diproses' => '#3B82F6', 'Selesai' => '#10B981', 'Ditolak' => '#EF4444'];
        $chartLabels = [];
        $chartDataValues = [];
        $chartColors = [];

        foreach ($statsPermohonan as $status => $totalData) {
            if ($totalData > 0) {
                $chartLabels[] = $status;
                $chartDataValues[] = $totalData;
                $chartColors[] = $colorMap[$status] ?? '#9CA3AF';
            }
        }

        $chartDataStatus = [
            'labels' => $chartLabels,
            'datasets' => [[
                'label' => 'Jumlah Permohonan',
                'data' => $chartDataValues,
                'backgroundColor' => $chartColors,
                'borderColor' => '#ffffff',
                'borderWidth' => 2,
            ]],
        ];

        // 3. --- TAMBAHAN BARU: Ambil data Beban Kerja Penelaah ---
        $statsByPenelaah = PermohonanAnalisis::where('tipe', 'RESMI')
            ->whereNotNull('penelaah_id') // Hanya yang sudah ditugaskan
            ->join('users', 'permohonananalisis.penelaah_id', '=', 'users.id') // Join ke tabel users
            ->select('users.name', DB::raw('count(*) as total'))
            ->groupBy('users.name')
            ->orderBy('total', 'desc') // Urutkan dari yang terbanyak
            ->pluck('total', 'name');

        $penelaahLabels = $statsByPenelaah->keys();
        $penelaahData = $statsByPenelaah->values();

        $chartDataPenelaah = [
            'labels' => $penelaahLabels,
            'datasets' => [[
                'label' => 'Jumlah Tugas Selesai',
                'data' => $penelaahData,
                'backgroundColor' => ['#3B82F6', '#10B981', '#FBBF24', '#EF4444', '#6366F1', '#EC4899'], // Palet warna
                'borderWidth' => 1,
            ]],
        ];
        // --- AKHIR TAMBAHAN BARU ---

        // 4. Kirim semua data ke view
        return view('adminklarifikasi.statistik.permohonan', [
            'stats' => $statsPermohonan,
            'total' => $total,
            'baru' => $baru,
            'diproses' => $diproses,
            'selesai' => $selesai,
            'ditolak' => $ditolak,
            'chartDataStatus' => json_encode($chartDataStatus),
            'chartDataPenelaah' => json_encode($chartDataPenelaah), // <-- Kirim data chart baru
        ]);
    }

    /**
     * Menampilkan statistik Pengaduan.
     */
    public function pengaduan()
    {
        // 1. Ambil data Status (untuk K-Stats dan Pie Chart)
        $statsPengaduan = Pengaduan::where('category', 'KLARIFIKASI')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $baru = $statsPengaduan->get('Baru', 0);
        $diproses = $statsPengaduan->get('Ditindaklanjuti', 0);
        $selesai = $statsPengaduan->get('Selesai', 0);
        $total = $statsPengaduan->sum();

        // 2. Siapkan data untuk Pie Chart (Status)
        $colorMap = [
            'Baru' => '#FBBF24', // Kuning
            'Ditindaklanjuti' => '#3B82F6', // Biru
            'Selesai' => '#10B981', // Hijau
        ];
        $chartLabelsStatus = [];
        $chartDataValuesStatus = [];
        $chartColorsStatus = [];
        foreach ($statsPengaduan as $status => $totalData) {
            if ($totalData > 0) {
                $chartLabelsStatus[] = $status;
                $chartDataValuesStatus[] = $totalData;
                $chartColorsStatus[] = $colorMap[$status] ?? '#9CA3AF';
            }
        }
        $chartDataStatus = [
            'labels' => $chartLabelsStatus,
            'datasets' => [[
                'label' => 'Jumlah Pengaduan',
                'data' => $chartDataValuesStatus,
                'backgroundColor' => $chartColorsStatus,
                'borderColor' => '#ffffff',
                'borderWidth' => 2,
            ]],
        ];

        // 3. Ambil data Kinerja Penelaah (Bar Chart)
        $statsByPenelaah = Pengaduan::where('category', 'KLARIFIKASI')
            ->whereNotNull('penelaah_id')
            ->join('users', 'pengaduans.penelaah_id', '=', 'users.id')
            ->select('users.name', DB::raw('count(*) as total'))
            ->groupBy('users.name')
            ->orderBy('total', 'desc')
            ->pluck('total', 'name');

        $chartDataPenelaah = [
            'labels' => $statsByPenelaah->keys(),
            'datasets' => [[
                'label' => 'Jumlah Pengaduan Ditangani',
                'data' => $statsByPenelaah->values(),
                'backgroundColor' => ['#3B82F6', '#10B981', '#FBBF24', '#EF4444', '#6366F1', '#EC4899'],
                'borderWidth' => 1,
            ]],
        ];

        // 4. --- TAMBAHAN BARU: Hitung Waktu Respons & Penyelesaian ---
        $avgResponseSeconds = Pengaduan::where('category', 'KLARIFIKASI')
            ->whereIn('status', ['Ditindaklanjuti', 'Selesai'])
            ->avg(DB::raw('TIMESTAMPDIFF(SECOND, created_at, updated_at)'));

        $avgResolveSeconds = Pengaduan::where('category', 'KLARIFIKASI')
            ->where('status', 'Selesai')
            ->avg(DB::raw('TIMESTAMPDIFF(SECOND, created_at, updated_at)'));

        // Panggil helper untuk format waktu
        $avgResponseTime = $this->formatSeconds($avgResponseSeconds);
        $avgResolveTime = $this->formatSeconds($avgResolveSeconds);
        // --- AKHIR TAMBAHAN BARU ---

        // 5. Kirim semua data ke view
        return view('adminklarifikasi.statistik.pengaduan', [
            'stats' => $statsPengaduan,
            'total' => $total,
            'baru' => $baru,
            'diproses' => $diproses,
            'selesai' => $selesai,
            'chartDataStatus' => json_encode($chartDataStatus),
            'chartDataPenelaah' => json_encode($chartDataPenelaah),
            'avgResponseTime' => $avgResponseTime, // <-- Data baru
            'avgResolveTime' => $avgResolveTime,  // <-- Data baru
        ]);
    }

    private function formatSeconds($seconds)
    {
        if ($seconds === null || $seconds == 0) {
            return 'N/A';
        }

        $days = floor($seconds / (3600 * 24));
        $seconds -= $days * 3600 * 24;
        $hours = floor($seconds / 3600);
        $seconds -= $hours * 3600;
        $minutes = floor($seconds / 60);

        $result = '';
        if ($days > 0) {
            $result .= $days.' Hari ';
        }
        if ($hours > 0) {
            $result .= $hours.' Jam ';
        }
        if ($days == 0 && $minutes > 0) {
            $result .= $minutes.' Menit';
        } // Hanya tampilkan menit jika < 1 hari

        return trim($result) ?: 'Sangat Cepat';
    }

    /**
     * Menampilkan statistik Survey (Laporan IKM).
     */
    public function survey()
    {
        $surveys = SurveyAnalisis::where('category', 'KLARIFIKASI');
        $totalResponden = $surveys->count();

        // Inisialisasi untuk IKM per unsur
        $unsurScores = [];
        foreach ($this->ikmQuestions as $questionField) {
            $unsurScores[$questionField] = [
                'totalScore' => 0,
                'count' => 0,
                'label' => $this->getLabelForQuestion($questionField), // Helper function
            ];
        }

        $ikmPerUnsur = []; // Inisialisasi array kosong

        if ($totalResponden == 0) {
            $ikmScore = 0;
            $ikmMutu = 'N/A';
        } else {
            $totalNilaiRataRataTertimbang = 0;

            foreach ($surveys as $survey) {
                $surveyScorePerUnsur = 0;
                foreach ($this->ikmQuestions as $questionField) {
                    $answer = $survey->{$questionField} ?? null; // Ambil jawaban
                    if ($answer && isset($this->scoresMap[$answer])) {
                        $score = $this->scoresMap[$answer];
                        $unsurScores[$questionField]['totalScore'] += $score;
                        $unsurScores[$questionField]['count']++;
                        $surveyScorePerUnsur += $score;
                    }
                }
                $totalNilaiRataRataTertimbang += ($surveyScorePerUnsur / count($this->ikmQuestions));
            }

            // Hitung IKM Keseluruhan
            $avgScore = $totalNilaiRataRataTertimbang / $totalResponden;
            $ikmScore = $avgScore * 25; // Konversi ke skala 100
            $ikmMutu = $this->getMutu($ikmScore);

            // Hitung IKM Per Unsur
            foreach ($this->ikmQuestions as $questionField) {
                if ($unsurScores[$questionField]['count'] > 0) {
                    $avgUnsurScore = ($unsurScores[$questionField]['totalScore'] / $unsurScores[$questionField]['count']);
                    $ikmUnsurScore = $avgUnsurScore * 25;
                    $ikmPerUnsur[] = [
                        'label' => $unsurScores[$questionField]['label'],
                        'score' => round($ikmUnsurScore, 2),
                        'mutu' => $this->getMutu($ikmUnsurScore),
                    ];
                }
            }
        }

        return view('adminklarifikasi.statistik.survey', [
            'ikmScore' => round($ikmScore, 2),
            'ikmMutu' => $ikmMutu,
            'totalResponden' => $totalResponden,
            'ikmPerUnsur' => $ikmPerUnsur, // <-- Kirim data rincian ke view
        ]);
    }

    private function getMutu($ikmScore)
    {
        if ($ikmScore >= 88.31) {
            return 'A (Sangat Baik)';
        }
        if ($ikmScore >= 76.61) {
            return 'B (Baik)';
        }
        if ($ikmScore >= 65.00) {
            return 'C (Cukup Baik)';
        }

        return 'D (Kurang Baik)';
    }

    private function getLabelForQuestion($questionField)
    {
        $labels = [
            'q_syarat_sesuai' => 'Kesesuaian Persyaratan',
            'q_syarat_wajar' => 'Kewajaran Persyaratan',
            'q_prosedur_mudah' => 'Kemudahan Prosedur',
            'q_waktu_cepat' => 'Kecepatan Waktu',
            'q_biaya' => 'Kewajaran Biaya',
            'q_hasil_sesuai' => 'Kesesuaian Hasil',
            'q_kualitas_rekaman' => 'Kualitas Rekaman',
            'q_sarpras' => 'Kualitas Sarpras',
            'q_penanganan_pengaduan' => 'Penanganan Pengaduan',
        ];

        return $labels[$questionField] ?? $questionField;
    }
}
