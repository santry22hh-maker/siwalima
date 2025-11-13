<?php

namespace App\Http\Controllers;

use App\Models\SurveyPelayanan;
use Illuminate\Http\Request;
use App\Models\Permohonan;
use Illuminate\Support\Facades\Auth;

class SurveyController extends Controller
{
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
            if (!$permohonan || $permohonan->user_id != Auth::id()) {
                return redirect()->route('permohonanspasial.saya')->with('error', 'Permohonan tidak valid.');
            }

            // 5. Cek apakah survei sudah diisi
            $existingSurvey = SurveyPelayanan::where('permohonan_id', $permohonan_id)->first();
            if ($existingSurvey) {
                return redirect()->route('permohonanspasial.saya')->with('error', 'Anda sudah pernah mengisi survei untuk permohonan ini.');
            }
        }

        // 6. Kirim $permohonan_id DAN objek $permohonan ke view
        return view('survey.index', [
            'permohonan_id' => $permohonan_id,
            'permohonan' => $permohonan // <-- Kirim objek lengkap
        ]);
    }

    /**
     * Menyimpan hasil survey baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi semua data dari form
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'pekerjaan' => 'required|string',
            'instansi' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telepon' => 'required|string|max:20',
            'tanggal_pelayanan' => 'required|date',
            'kebutuhan_pelayanan' => 'required|array|min:1', // Pastikan minimal 1 box dicentang
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
            'permohonan_id' => 'nullable|exists:permohonans,id|unique:survey_pelayanans,permohonan_id', // Validasi baru
            'nama_lengkap' => 'required',
        ]);

        // 2. Simpan ke database
        SurveyPelayanan::create($validated); // Model akan otomatis mengisi permohonan_id

        // Redirect kembali ke halaman "Permohonan Saya"
        return redirect()->route('permohonanspasial.saya')
            ->with('success', 'Terima kasih telah mengisi survei kepuasan.');
    }
}
