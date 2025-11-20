<?php

// ini kontroller untuk Klarifikasi

namespace App\Http\Controllers;

use App\Models\PermohonanAnalisis;
use App\Models\SurveyAnalisis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SurveyPelayananController extends Controller
{
    // --- TAMBAHAN: Peta Skor untuk IKM ---
    // (Peta Skor Anda sudah benar)
    protected $scoresMap = [
        // Skor 1 (Tidak Baik)
        'Tidak Mudah' => 1, 'Tidak Kompeten' => 1, 'Tidak sopan dan ramah' => 1, 'Tidak Informatif' => 1,
        'Tidak Sesuai' => 1, 'Tidak Wajar' => 1, 'Tidak Cepat' => 1, 'Iya, sangat Mahal' => 1,
        'Tidak Memuaskan' => 1, 'Buruk' => 1, 'Tidak ada sarana' => 1,

        // Skor 2 (Kurang Baik)
        'Kurang Mudah' => 2, 'Kurang Kompeten' => 2, 'Kurang sopan dan ramah' => 2, 'Kurang Informatif' => 2,
        'Kurang Sesuai' => 2, 'Kurang Wajar' => 2, 'Kurang Cepat' => 2, 'Iya, cukup Mahal' => 2,
        'Kurang Memuaskan' => 2, 'Cukup' => 2, 'Ada tetapi tidak berfungsi' => 2,

        // Skor 3 (Baik)
        'Mudah' => 3, 'Kompeten' => 3, 'Sopan dan ramah' => 3, 'Cukup Informatif' => 3,
        'Sesuai' => 3, 'Wajar' => 3, 'Cepat' => 3, 'Iya, Murah' => 3,
        'Memuaskan' => 3, 'Baik' => 3, 'Berfungsi kurang maksimal' => 3,

        // Skor 4 (Sangat Baik)
        'Sangat Mudah' => 4, 'Sangat Kompeten' => 4, 'Sangat sopan dan ramah' => 4, 'Sangat Informatif' => 4,
        'Sangat Sesuai' => 4, 'Sangat Wajar' => 4, 'Sangat Cepat' => 4, 'Tidak, Gratis' => 4,
        'Sangat Memuaskan' => 4, 'Sangat Baik' => 4, 'Dikelola dengan baik' => 4,
    ];

    // Daftar 9 unsur pelayanan utama (sesuai pertanyaan 18-26 di form Anda)
    protected $ikmQuestions = [
        'q_syarat_sesuai', 'q_syarat_wajar', 'q_prosedur_mudah', 'q_waktu_cepat',
        'q_biaya', 'q_hasil_sesuai', 'q_kualitas_rekaman', 'q_sarpras',
        'q_penanganan_pengaduan',
    ];
    // --- AKHIR TAMBAHAN ---

    /**
     * Menampilkan halaman 'Isi Survey' (Form).
     */
    public function create(PermohonanAnalisis $permohonan)
    {
        $user = Auth::user();

        // Otorisasi 1: Pastikan ini milik user
        if ($permohonan->user_id !== $user->id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Otorisasi 2: Pastikan statusnya "Selesai"
        if (strtolower($permohonan->status) !== 'selesai') {
            return redirect()->route('permohonananalisis.show', $permohonan->slug)
                ->with('error', 'Survei hanya dapat diisi untuk permohonan yang sudah Selesai.');
        }

        // Otorisasi 3: Cek apakah survey sudah pernah diisi
        $existingSurvey = SurveyAnalisis::where('permohonananalisis_id', $permohonan->id)->exists();
        if ($existingSurvey) {
            return redirect()->route('permohonananalisis.show', $permohonan->slug)
                ->with('error', 'Anda sudah pernah mengisi survei untuk permohonan ini.');
        }

        // Kirim data $permohonan (untuk pre-fill) dan $permohonan_id (untuk hidden input)
        return view('survey.klarifikasi_index', [
            'permohonan' => $permohonan, // Untuk pre-fill form
            'permohonan_id' => $permohonan->id, // Untuk hidden input
            'user' => $user,
        ]);
    }

    /**
     * Menyimpan data survey baru dari form 4-halaman.
     * PERBAIKAN: Sekarang memvalidasi permohonan_id
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'permohonananalisis_id' => 'required|integer|exists:permohonananalisis,id',
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'pekerjaan' => 'required|string',
            'instansi' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:20',
            'tanggal_pelayanan' => 'required|date',
            'kebutuhan_pelayanan' => 'required|array|min:1',
            'kebutuhan_pelayanan.*' => 'string',
            'tujuan_penggunaan' => 'required|string',
            'pernah_layanan' => 'required|string',
            'info_layanan' => 'required|string',
            'cara_layanan' => 'required|string',
            'q_petugas_ditemui' => 'required|string',
            'q_petugas_dihubungi' => 'required|string',
            'q_kompetensi' => 'required|string',
            'q_kesopanan' => 'required|string',
            'q_info_jelas' => 'required|string',
            'q_syarat_sesuai' => 'required|string',
            'q_syarat_wajar' => 'required|string',
            'q_prosedur_mudah' => 'required|string',
            'q_waktu_cepat' => 'required|string',
            'q_biaya' => 'required|string',
            'q_hasil_sesuai' => 'required|string',
            'q_kualitas_rekaman' => 'required|string',
            'q_layanan_keseluruhan' => 'required|string',
            'q_sarpras' => 'required|string',
            'q_penanganan_pengaduan' => 'required|string',
            'kritik_saran' => 'nullable|string|max:5000',
        ]);

        $permohonananalisis_id = $validated['permohonananalisis_id'];
        $category = 'PERMOHONAN_ANALISIS';
        $redirectRoute = 'permohonananalisis.index';
        $user_id = Auth::id();

        try {

            DB::transaction(function () use ($validated, $category, $user_id, $permohonananalisis_id) {

                SurveyAnalisis::create([
                    'permohonananalisis_id' => $permohonananalisis_id,
                    'category' => $category,
                    'user_id' => $user_id,

                    // field lainnya
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'jenis_kelamin' => $validated['jenis_kelamin'],
                    'pekerjaan' => $validated['pekerjaan'],
                    'instansi' => $validated['instansi'],
                    'email' => $validated['email'],
                    'telepon' => $validated['telepon'],
                    'tanggal_pelayanan' => $validated['tanggal_pelayanan'],
                    'kebutuhan_pelayanan' => $validated['kebutuhan_pelayanan'],
                    'tujuan_penggunaan' => $validated['tujuan_penggunaan'],
                    'pernah_layanan' => $validated['pernah_layanan'],
                    'info_layanan' => $validated['info_layanan'],
                    'cara_layanan' => $validated['cara_layanan'],
                    'q_petugas_ditemui' => $validated['q_petugas_ditemui'],
                    'q_petugas_dihubungi' => $validated['q_petugas_dihubungi'],
                    'q_kompetensi' => $validated['q_kompetensi'],
                    'q_kesopanan' => $validated['q_kesopanan'],
                    'q_info_jelas' => $validated['q_info_jelas'],
                    'q_syarat_sesuai' => $validated['q_syarat_sesuai'],
                    'q_syarat_wajar' => $validated['q_syarat_wajar'],
                    'q_prosedur_mudah' => $validated['q_prosedur_mudah'],
                    'q_waktu_cepat' => $validated['q_waktu_cepat'],
                    'q_biaya' => $validated['q_biaya'],
                    'q_hasil_sesuai' => $validated['q_hasil_sesuai'],
                    'q_kualitas_rekaman' => $validated['q_kualitas_rekaman'],
                    'q_layanan_keseluruhan' => $validated['q_layanan_keseluruhan'],
                    'q_sarpras' => $validated['q_sarpras'],
                    'q_penanganan_pengaduan' => $validated['q_penanganan_pengaduan'],
                    'kritik_saran' => $validated['kritik_saran'],
                ]);
            });

        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage())
                ->withInput();
        }

        return redirect()->route($redirectRoute)
            ->with('success', 'Terima kasih telah mengisi survei!');
    }

    /**
     * Menampilkan halaman 'Hasil Survey & IKM'.
     */
    public function index()
    {
        // (Logika IKM Anda sudah benar)
        // $ikmResult = $this->calculateIKM('KLARIFIKASI');

        return view('survey.index');
    }

    /**
     * Menampilkan halaman Rekap Survey (untuk Admin)
     */
    public function rekap()
    {
        // (Logika Rekap Anda sudah benar)
        if (! Auth::user()->can('access klarifikasi backend')) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        return view('survey.rekap');
    }

    /**
     * Melayani data JSON untuk DataTables Rekap Survey (untuk Admin)
     */
    public function getData(Request $request)
    {
        // (Logika getData Anda sudah benar)
        $query = SurveyAnalisis::where('category', 'KLARIFIKASI');

        return DataTables::of($query)
            ->addColumn('tanggal', function ($row) {
                return $row->tanggal_pelayanan ? \Carbon\Carbon::parse($row->tanggal_pelayanan)->isoFormat('D MMM YYYY') : '-';
            })
            ->addColumn('kualitas_layanan', function ($row) {
                $score = $this->scoresMap[$row->q_layanan_keseluruhan] ?? 0;
                $color = 'gray';
                if ($score == 4) {
                    $color = 'green';
                }
                if ($score == 3) {
                    $color = 'blue';
                }
                if ($score == 2) {
                    $color = 'yellow';
                }
                if ($score == 1) {
                    $color = 'red';
                }

                return '<span class="text-'.$color.'-600 font-medium">'.$row->q_layanan_keseluruhan.'</span>';
            })
            ->addColumn('layanan_diminta', function ($row) {
                return is_array($row->kebutuhan_pelayanan)
                    ? implode(', ', $row->kebutuhan_pelayanan)
                    : $row->kebutuhan_pelayanan;
            })
            ->addColumn('kritik_saran', function ($row) {
                return Str::limit($row->kritik_saran, 50, '...');
            })
            ->rawColumns(['kualitas_layanan'])
            ->make(true);
    }

    /**
     * FUNGSI HELPER: Kalkulator IKM
     */
    private function calculateIKM($category, $permohonan_id = null)
    {
        // (Logika kalkulator IKM Anda sudah benar)
        $query = SurveyAnalisis::where('category', $category);

        if ($permohonan_id) {
            $query->where('permohonan_id', $permohonan_id);
        } else {
            $query->whereNull('permohonan_id');
        }

        $surveys = $query->get();
        $totalResponden = $surveys->count();

        if ($totalResponden == 0) {
            return ['ikmScore' => 0, 'mutu' => 'N/A', 'totalResponden' => 0];
        }

        $totalScore = 0;
        $totalQuestions = count($this->ikmQuestions);

        foreach ($surveys as $survey) {
            $surveyScore = 0;
            foreach ($this->ikmQuestions as $questionField) {
                $answer = $survey->{$questionField};
                $surveyScore += $this->scoresMap[$answer] ?? 0;
            }
            $totalScore += ($surveyScore / $totalQuestions);
        }

        $avgScore = $totalScore / $totalResponden;
        $ikmScore = $avgScore * 25;

        $mutu = 'D';
        if ($ikmScore > 65) {
            $mutu = 'C';
        }
        if ($ikmScore > 76.6) {
            $mutu = 'B';
        }
        if ($ikmScore > 88.3) {
            $mutu = 'A';
        }

        return [
            'ikmScore' => round($ikmScore, 2),
            'mutu' => $mutu,
            'totalResponden' => $totalResponden,
        ];
    }

    /**
     * FUNGSI HELPER: Ekspor CSV
     */
    public function exportSurvey(Request $request)
    {
        // (Logika ekspor CSV Anda sudah benar)
        if (! Auth::user()->can('access klarifikasi backend')) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $fileName = 'rekap_survey_klarifikasi_'.date('Y-m-d').'.csv';
        $surveys = SurveyAnalisis::where('category', 'KLARIFIKASI')
            ->whereNull('permohonan_id')
            ->get();
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];
        $columns = [
            'ID', 'Tanggal Lapor', 'Nama Lengkap', 'Jenis Kelamin', 'Pekerjaan', 'Instansi', 'Email', 'Telepon',
            'Tanggal Pelayanan', 'Kebutuhan Pelayanan', 'Tujuan Penggunaan',
            'Pernah Layanan?', 'Info Layanan?', 'Cara Layanan?',
            'Q: Petugas Ditemui', 'Q: Petugas Dihubungi', 'Q: Kompetensi', 'Q: Kesopanan',
            'Q: Info Jelas', 'Q: Syarat Sesuai', 'Q: Syarat Wajar', 'Q: Prosedur Mudah',
            'Q: Waktu Cepat', 'Q: Biaya', 'Q: Hasil Sesuai', 'Q: Kualitas Rekaman',
            'Q: Layanan Keseluruhan', 'Q: Sarpras', 'Q: Penanganan Pengaduan',
            'Kritik & Saran',
        ];
        $callback = function () use ($surveys, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($surveys as $survey) {
                $kebutuhan = is_array($survey->kebutuhan_pelayanan)
                                 ? implode(', ', $survey->kebutuhan_pelayanan)
                                 : $survey->kebutuhan_pelayanan;
                $row = [
                    $survey->id,
                    $survey->created_at->format('Y-m-d H:i:s'),
                    $survey->nama_lengkap,
                    $survey->jenis_kelamin,
                    $survey->pekerjaan,
                    $survey->instansi,
                    $survey->email,
                    $survey->telepon,
                    $survey->tanggal_pelayanan,
                    $kebutuhan,
                    $survey->tujuan_penggunaan,
                    $survey->pernah_layanan,
                    $survey->info_layanan,
                    $survey->cara_layanan,
                    $survey->q_petugas_ditemui,
                    $survey->q_petugas_dihubungi,
                    $survey->q_kompetensi,
                    $survey->q_kesopanan,
                    $survey->q_info_jelas,
                    $survey->q_syarat_sesuai,
                    $survey->q_syarat_wajar,
                    $survey->q_prosedur_mudah,
                    $survey->q_waktu_cepat,
                    $survey->q_biaya,
                    $survey->q_hasil_sesuai,
                    $survey->q_kualitas_rekaman,
                    $survey->q_layanan_keseluruhan,
                    $survey->q_sarpras,
                    $survey->q_penanganan_pengaduan,
                    $survey->kritik_saran,
                ];
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
