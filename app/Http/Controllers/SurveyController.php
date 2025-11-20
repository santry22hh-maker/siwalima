<?php

// ini controller untuk IGT

namespace App\Http\Controllers;

use App\Models\Permohonan;
use App\Models\SurveyPelayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class SurveyController extends Controller
{
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

    /**
     * Menampilkan halaman form survey.
     */
    public function index(Request $request)
    {
        $permohonan_id = $request->query('permohonan_id');
        $permohonan = null; // Default

        if ($permohonan_id) {
            // 3. Ambil data permohonan
            $permohonan = Permohonan::find($permohonan_id);

            // 4. Validasi
            if (! $permohonan || $permohonan->user_id != Auth::id()) {
                return redirect()->route('permohonanspasial.saya')->with('error', 'Permohonan tidak valid.');
            }

            // 5. Cek apakah survei sudah diisi
            $existingSurvey = SurveyPelayanan::where('permohonan_id', $permohonan_id)->first();
            if ($existingSurvey) {
                return redirect()->route('permohonanspasial.saya')->with('error', 'Anda sudah pernah mengisi survei untuk permohonan ini.');
            }
        }

        // 6. Kirim $permohonan_id DAN objek $permohonan ke view
        return view('survey.igt_index', [
            'permohonan_id' => $permohonan_id,
            'permohonan' => $permohonan, // <-- Kirim objek lengkap
        ]);
    }

    /**
     * Menyimpan hasil survey baru.
     */
    public function store(Request $request)
    {
        // ... (Kode store Anda tetap sama) ...
        $validated = $request->validate([
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
            'permohonan_id' => 'nullable', // Sederhanakan validasi ini dulu
            // 'nama_lengkap' => 'required', // Duplikat, hapus saja
        ]);

        // Tambahkan kategori IGT secara otomatis
        $validated['category'] = 'IGT';
        $validated['user_id'] = Auth::id();

        SurveyPelayanan::create($validated);

        return redirect()->route('permohonanspasial.saya')
            ->with('success', 'Terima kasih telah mengisi survei kepuasan.');
    }

    public function rekapIgt()
    {
        // Otorisasi: Pastikan hanya admin/penelaah yang bisa akses
        // Sesuaikan permission dengan kebutuhan Anda
        if (! Auth::user()->can('access klarifikasi backend')) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Arahkan ke view khusus rekap IGT yang sudah kita buat
        // File: resources/views/adminklarifikasi/survey/rekap-igt.blade.php
        return view('surveypenggunaan.rekap-igt');
    }

    /**
     * 2. Melayani data JSON untuk DataTables Rekap Survey IGT.
     */
    public function getDataIgt(Request $request)
    {
        // (Logika getData Anda sudah benar)
        $query = SurveyPelayanan::where('category', 'IGT');

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
     * 3. Mengekspor data Survey IGT ke CSV.
     */
    public function exportSurveyIgt(Request $request)
    {
        // (Logika ekspor CSV Anda sudah benar)
        if (! Auth::user()->can('access klarifikasi backend')) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $fileName = 'rekap_survey_klarifikasi_'.date('Y-m-d').'.csv';
        $surveys = SurveyPelayanan::where('category', 'IGT')
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
