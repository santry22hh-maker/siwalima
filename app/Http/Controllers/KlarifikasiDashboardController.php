<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class KlarifikasiDashboardController extends Controller
{
    /**
     * Menampilkan dashboard (tidak berubah)
     */
    public function index()
    {
        return view('klarifikasi.index');
    }

    public function statistik()
    {
        return view('klarifikasi.statistik');
    }

    /**
     * Menampilkan form input mandiri
     */
    public function input()
    {
        // Blade Anda (klarifikasi.input.blade.php) memanggil 'map_styles.js'
        // dan melakukan 'fetch' ke 'DataDasar'. Kita HARUS menyediakan data
        // yang sama di sini agar JavaScript tidak error.
        $styleMapping = [
            'KwsHutan_Maluku250.geojson' => 'styleKawasanHutan',
            'Pl2023_Maluku250.geojson' => 'stylePL2023',
        ];

        $dataDasarPath = public_path('DataDasar');
        $dataDasarFiles = [];

        if (File::exists($dataDasarPath)) {
            foreach (File::files($dataDasarPath) as $file) {
                $filename = $file->getFilename();
                $dataDasarFiles[] = [
                    'name' => $filename,
                    'url' => asset('DataDasar/'.$filename),
                    'style_function' => $styleMapping[$filename] ?? '',
                ];
            }
        }

        return view('klarifikasi.input', compact('dataDasarFiles'));
    }

    /**
     * Memproses data mandiri TANPA menyimpan ke database.
     */
    public function prosesAnalisis(Request $request)
    {
        // 1. Validasi Input (Sesuai form Anda)
        $validated = $request->validate([
            // 'lokasi' => 'required|string|max:255',
            // 'kabupaten' => 'required|string|max:255',
            // 'keterangan' => 'nullable|string',
            'geojson_data' => 'required|json',
            'source_type' => 'required|string|in:shapefile,photo,manual',
            'shapefile_input' => 'required_if:source_type,shapefile|file|mimes:zip|max:10240',
            'photos' => 'required_if:source_type,photo|array',
            // 'photos.*' => 'image|mimes:jpeg,jpg|max:10240',
            // 'userid' => 'nullable|string',
            // 'groupid' => 'nullable|string',
        ]);

        // 2. Ambil GeoJSON yang diinput
        $incomingJson = json_decode($validated['geojson_data']);
        $geometry = $incomingJson->geometry ?? $incomingJson; // Dapatkan objek geometrinya

        // 3. Buat "Objek Permohonan Palsu" (stdClass)
        $permohonan = new \stdClass;
        $permohonan->id = 'N/A'; // ID Palsu untuk header
        $permohonan->slug = 'mandiri-'.Str::uuid();
        // $permohonan->keterangan = $validated['keterangan'];
        $permohonan->created_at = now();
        $permohonan->status = 'Draft';
        // $permohonan->form_userid = $validated['userid'];
        // $permohonan->form_groupid = $validated['groupid'];

        // Buat objek spasial palsu
        $permohonan->dataSpasial = new \stdClass;
        // $permohonan->dataSpasial->nama_areal = $validated['lokasi'];
        // $permohonan->dataSpasial->kabupaten = $validated['kabupaten'];
        $permohonan->dataSpasial->geojson_path = $validated['source_type'].'.geojson';

        // 4. Buat String GeoJSON Feature LENGKAP untuk dikirim ke view
        $feature = [
            'type' => 'Feature',
            'properties' => [
                // 'nama_areal' => $validated['lokasi'],
            ],
            'geometry' => $geometry, // Gunakan objek geometri yang sudah diekstrak
        ];

        // ======================================================
        //      PERBAIKAN: Ubah $usulanGeoJson menjadi string
        // ======================================================
        // Ini adalah string JSON yang akan dikirim ke view
        $usulanGeoJson = json_encode($feature);

        // 5. Ambil Data Dasar (Wajib untuk halaman analisis.show)
        $styleMapping = [
            'KwsHutan_Maluku250.geojson' => 'styleKawasanHutan',
            'Pl2023_Maluku250.geojson' => 'stylePL2023',
        ];
        $dataDasarPath = public_path('DataDasar');
        $dataDasarFiles = [];
        if (File::exists($dataDasarPath)) {
            foreach (File::files($dataDasarPath) as $file) {
                $filename = $file->getFilename();
                $dataDasarFiles[] = [
                    'name' => $filename,
                    'url' => asset('DataDasar/'.$filename),
                    'style_function' => $styleMapping[$filename] ?? '',
                ];
            }
        }

        // 6. Kembalikan View Analisis
        return view('analisis.show', compact(
            'permohonan',
            'usulanGeoJson', // <-- $usulanGeoJson sekarang adalah STRING
            'dataDasarFiles'
        ));
    }
}
