<?php

namespace App\Http\Controllers;

// MODEL BARU
use App\Models\PermohonanAnalisis;
use App\Models\DataSpasial;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

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
     * (Fungsi ini harus mengirim $dataDasarFiles agar blade Anda berfungsi)
     */
    public function input()
    {
        // Blade Anda (klarifikasi.input.blade.php) melakukan fetch
        // ke DataDasar dan memanggil style dari map_styles.js.
        // Kita HARUS mengirimkan variabel ini.
        $styleMapping = [
            'KwsHutan_Maluku250.geojson' => 'styleKawasanHutan',
            'Pl2023_Maluku250.geojson'   => 'stylePL2023',
        ];

        $dataDasarPath = public_path('DataDasar');
        $dataDasarFiles = [];

        if (File::exists($dataDasarPath)) {
            foreach (File::files($dataDasarPath) as $file) {
                $filename = $file->getFilename();
                $dataDasarFiles[] = [
                    'name' => $filename,
                    'url'  => asset('DataDasar/' . $filename),
                    'style_function' => $styleMapping[$filename] ?? '',
                ];
            }
        }

        return view('klarifikasi.input', compact('dataDasarFiles'));
    }

    /**
     * ======================================================
     * FUNGSI STORE YANG DISESUAIKAN
     * ======================================================
     * Menyimpan data dari 'klarifikasi.input.blade.php' ke tabel BARU
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (Sesuai form Anda)
        $validated = $request->validate([
            'lokasi' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'geojson_data' => 'required|json',
            'source_type' => 'required|string|in:shapefile,photo,manual',
            'shapefile_input' => 'required_if:source_type,shapefile|file|mimes:zip|max:10240',
            'photos' => 'required_if:source_type,photo|array',
            'photos.*' => 'image|mimes:jpeg,jpg',
            'userid' => 'nullable|string', // <-- Dari Blade Anda
            'groupid' => 'nullable|string', // <-- Dari Blade Anda
        ]);

        // dd($validated); // <-- Debug Anda sekarang akan berjalan

        // 2. Inisialisasi Data
        $geometry = json_decode($validated['geojson_data']);
        $groupId = $validated['groupid'] ?? Str::uuid();
        $shapefilePath = null;
        $photoPaths = null;
        $disk = 'public';

        // 3. Simpan File Spasial
        if ($validated['source_type'] === 'shapefile' && $request->hasFile('shapefile_input')) {
            $shapefilePath = $request->file('shapefile_input')->store("analisis_mandiri/{$groupId}", $disk);
        }
        if ($validated['source_type'] === 'photo' && $request->hasFile('photos')) {
            $paths = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store("analisis_mandiri/{$groupId}", $disk);
                $paths[] = $path;
            }
            $photoPaths = json_encode($paths);
        }

        // 4. Buat dan Simpan File GeoJSON
        $feature = [
            'type' => 'Feature',
            'properties' => [
                'nama_areal' => $validated['lokasi'],
                'groupid' => $groupId, // Menyimpan groupid lama di properti GeoJSON
                'kabupaten' => $validated['kabupaten'],
                'userid' => $validated['userid'], // Menyimpan userid lama di properti GeoJSON
            ],
            'geometry' => $geometry,
        ];
        $geojsonFileName = $groupId . '_spasial.geojson';
        $geojsonDbPath = "analisis_mandiri/{$groupId}/" . $geojsonFileName;
        Storage::disk($disk)->put($geojsonDbPath, json_encode($feature, JSON_PRETTY_PRINT));

        // 5. Simpan Entitas ke Database (dalam Transaksi)
        try {
            DB::transaction(function () use ($validated, $geometry, $geojsonDbPath, $shapefilePath, $photoPaths, $groupId) {

                // A. Buat PermohonanAnalisis (Tabel Utama)
                $permohonan = PermohonanAnalisis::create([
                    'user_id' => Auth::id(), // ID pengguna yang login
                    'tipe' => 'MANDIRI',
                    'status' => 'Draft',
                    'keterangan' => $validated['keterangan'],
                    'form_userid' => $validated['userid'], // <-- Menyimpan userid dari form
                    'form_groupid' => $groupId, // <-- Menyimpan groupid dari form/buatan
                ]);

                // B. Buat DataSpasial (Tabel Pelayan)
                DataSpasial::create([
                    'permohonananalisis_id' => $permohonan->id,
                    'nama_areal' => $validated['lokasi'],
                    'kabupaten' => $validated['kabupaten'],
                    'coordinates' => json_encode($geometry->coordinates),
                    'geojson_path' => $geojsonDbPath,
                    'shapefile_path' => $shapefilePath,
                    'photo_paths' => $photoPaths,
                    'source_type' => $validated['source_type'],
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }

        // 6. Redirect ke halaman tabel "Data Saya" (data.list)
        return redirect()->route('data.list')->with('success', 'Data analisis mandiri berhasil disimpan!');
    }
}
