<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Polygon;
use App\Models\Laporan; // Mengimpor model Laporan

class InputController extends Controller
{
    /**
     * Menampilkan halaman formulir input.
     */
    public function index()
    {
        return view('input.index');
    }

    /**
     * Menyimpan data poligon yang diinput.
     */
    public function store(Request $request)
    {
     
    // 1. Validasi Input
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'nama_pemohon' => 'required|string|max:255',
        'lokasi' => 'required|string|max:255',
        'kabupaten' => 'required|string|max:255',
        'geojson_data' => 'required|json',
        'source_type' => 'required|string|in:shapefile,photo,manual',
        'shapefile_input' => 'required_if:source_type,shapefile|file|mimes:zip',
        'photos' => 'required_if:source_type,photo|array',
        'photos.*' => 'image|mimes:jpeg,jpg',
        'userid' => 'nullable|string',
        'groupid' => 'nullable|string',
    ]);

    // dd('Test 2: Validasi Berhasil', $validated);
    // 2. Inisialisasi Data
    $geometry = json_decode($validated['geojson_data']);
    $groupId = $validated['groupid'] ?? Str::uuid();

    $shapefilePath = null;
    $photoPaths = null;

    // 3. Simpan File Asli (Shapefile atau Foto)
    if ($validated['source_type'] === 'shapefile' && $request->hasFile('shapefile_input')) {
        $shapefilePath = $request->file('shapefile_input')->store("shapefiles/{$groupId}");
    }

    // 4. Validasi Geometri
    if (!isset($geometry->type) || !in_array($geometry->type, ['Polygon', 'MultiPolygon'])) {
        return back()->with('error', 'Data geometri tidak valid.')->withInput();
    }

    // 5. Buat dan Simpan File GeoJSON
    $feature = [
        'type' => 'Feature',
        'properties' => [
            'name' => $validated['name'],
            'groupid' => $groupId,
            'nama_pemohon' => $validated['nama_pemohon'],
            'lokasi' => $validated['lokasi'],
            'kabupaten' => $validated['kabupaten'],
        ],
        'geometry' => $geometry,
    ];
    
    $geojsonFileName = $groupId . '_spasial.geojson';
    $geojsonDbPath = 'spasials/' . $geojsonFileName;
    Storage::put($geojsonDbPath, json_encode($feature, JSON_PRETTY_PRINT));

      // 6. Simpan Entitas Poligon ke Database
    $polygon = Polygon::create([
        'name' => $validated['name'],
        'nama_pemohon' => $validated['nama_pemohon'],
        'lokasi' => $validated['lokasi'],
        'kabupaten' => $validated['kabupaten'],
        'groupid' => $groupId,
        'coordinates' => json_encode($geometry->coordinates),
        'geojson_path' => $geojsonDbPath,
        'shapefile_path' => $shapefilePath,
        'photo_paths' => $photoPaths,
    ]);

    // 7. Buat Entri Laporan
    Laporan::create([
        'polygon_id' => $polygon->id,
        'status' => 'Masuk',
        'keterangan' => 'Laporan dibuat secara otomatis dari input data spasial.'
    ]);

    // 8. Redirect dengan Pesan Sukses
    return redirect()->route('analisis.index')->with('success', 'Poligon "' . $validated['name'] . '" berhasil disimpan!');
    }

}
