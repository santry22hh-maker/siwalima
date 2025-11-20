<?php

namespace App\Http\Controllers;

// Import Model baru
use App\Models\DataSpasial;
use App\Models\PermohonanAnalisis;
// Import Model LAMA (untuk pre-fill)

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PermohonanAnalisisController extends Controller
{
    /**
     * Menampilkan halaman 'Daftar Permohonan Saya' (Tabel Data).
     */
    public function index()
    {
        // View ini (permohonan.index) akan kita perbaiki di langkah 6
        return view('permohonananalisis.index');
    }

    /**
     * Menampilkan halaman 'Ajukan Permohonan Baru' (Form).
     */
    public function create(Request $request)
    {
        $laporanFrom = null;
        $usulanGeoJson = null;

        if ($request->has('from_slug')) {
            $laporanFrom = PermohonanAnalisis::where('slug', $request->from_slug)
                ->where('tipe', 'MANDIRI')
                ->with('dataSpasial')
                ->first();

            if ($laporanFrom && $laporanFrom->dataSpasial && $laporanFrom->dataSpasial->geojson_path) {
                $path = $laporanFrom->dataSpasial->geojson_path;
                if (Storage::disk('public')->exists($path)) {
                    $usulanGeoJson = Storage::disk('public')->get($path);
                }
            }
        }

        return view('permohonananalisis.create', compact('laporanFrom', 'usulanGeoJson'));
    }

    /**
     * Menyimpan Permohonan Resmi yang baru
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (sesuai form baru Anda)
        $validated = $request->validate([
            'nama_pemohon' => 'required|string|max:255',
            'hp_pemohon' => 'required|string|max:20',
            'email_pemohon' => 'nullable|email|max:255',
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tujuan_analisis' => 'required|string|in:Perizinan,Klarifikasi Kawasan Hutan',
            'perihal_surat' => 'nullable|string',
            'file_surat' => 'required|file|mimes:pdf|max:10240',
            'lokasi' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'geojson_data' => 'required|json',
            'luas_ha' => 'required|numeric|min:0.0001',
            'source_type' => 'required|string|in:shapefile,photo,manual,prefilled',
            'shapefile_input' => 'required_if:source_type,shapefile|file|mimes:zip|max:10240',
            'photos' => 'required_if:source_type,photo|array',
            // 'photos.*' => 'image|mimes:jpeg,jpg|max:10240',
            // 'userid' => 'nullable|string', (Sudah kita hapus)
            // 'groupid' => 'nullable|string', (Sudah kita hapus)
        ]);

        // 2. Inisialisasi Data
        $groupId = (string) Str::uuid(); // Buat ID unik
        $shapefilePath = null;
        $photoPaths = null;
        $fileSuratPath = null;
        $disk = 'public';

        $incomingJson = json_decode($validated['geojson_data']);
        $geometry = $incomingJson->geometry ?? $incomingJson; // <-- $geometry DIDEFINISIKAN DI SINI

        // 3. Simpan File
        if ($request->hasFile('file_surat')) {
            $fileSuratPath = $request->file('file_surat')->store("permohonan_resmi/{$groupId}/surat", $disk);
        }
        if ($validated['source_type'] === 'shapefile' && $request->hasFile('shapefile_input')) {
            $shapefilePath = $request->file('shapefile_input')->store("permohonan_resmi/{$groupId}/spasial", $disk);
        }
        if ($validated['source_type'] === 'photo' && $request->hasFile('photos')) {
            $paths = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store("permohonan_resmi/{$groupId}/spasial", $disk);
                $paths[] = $path;
            }
            $photoPaths = json_encode($paths);
        }

        // 4. Buat dan Simpan File GeoJSON
        $feature = [
            'type' => 'Feature',
            'properties' => [
                'nama_areal' => $validated['lokasi'],
                'groupid' => $groupId,
                'nama_pemohon' => $validated['nama_pemohon'],
                'kabupaten' => $validated['kabupaten'],
                'userid' => Auth::id(), // <-- Diisi otomatis
            ],
            'geometry' => $geometry,
        ];
        $geojsonFileName = $groupId.'_spasial.geojson';
        $geojsonDbPath = "permohonan_resmi/{$groupId}/spasial/".$geojsonFileName;
        Storage::disk($disk)->put($geojsonDbPath, json_encode($feature, JSON_PRETTY_PRINT));

        // 5. Simpan Entitas ke Database (dalam Transaksi)
        $permohonan = null; // Inisialisasi di luar try-catch
        try {

            // --- PERBAIKAN: Tambahkan '$geometry' ke dalam 'use' ---
            DB::transaction(function () use ($validated, $geometry, $geojsonDbPath, $shapefilePath, $photoPaths, $fileSuratPath, $groupId, &$permohonan) {

                // A. Buat PermohonanAnalisis (Tabel Utama)
                $permohonan = PermohonanAnalisis::create([
                    'user_id' => Auth::id(),
                    'tipe' => 'RESMI',
                    'status' => 'Diajukan',
                    'keterangan' => $validated['keterangan'],
                    'nama_pemohon' => $validated['nama_pemohon'],
                    'hp_pemohon' => $validated['hp_pemohon'],
                    'email_pemohon' => $validated['email_pemohon'],
                    'nomor_surat' => $validated['nomor_surat'],
                    'tanggal_surat' => $validated['tanggal_surat'],
                    'tujuan_analisis' => $validated['tujuan_analisis'],
                    'perihal_surat' => $validated['perihal_surat'],
                    'file_surat_path' => $fileSuratPath,
                    'form_userid' => Auth::id(), // <-- Diisi otomatis
                    'form_groupid' => $groupId,
                ]);

                // B. Buat DataSpasial (Tabel Pelayan)
                DataSpasial::create([
                    'permohonananalisis_id' => $permohonan->id,
                    'nama_areal' => $validated['lokasi'],
                    'kabupaten' => $validated['kabupaten'],
                    'coordinates' => $geometry, // <-- BARIS 101 (Sekarang $geometry dikenali)
                    'geojson_path' => $geojsonDbPath,
                    'shapefile_path' => $shapefilePath,
                    'photo_paths' => $photoPaths,
                    'source_type' => $validated['source_type'],
                    'luas_ha' => $validated['luas_ha'],
                ]);
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan data: '.$e->getMessage())->withInput();
        }

        // 6. Redirect dengan Pesan Sukses (Menampilkan Kode Pelacakan)
        $successMessage = 'Permohonan Anda berhasil diajukan! Kode Pelacakan Anda: '.$permohonan->kode_pelacakan;

        return redirect()->route('permohonananalisis.index')->with('success', $successMessage);
    }

    /**
     * Menyediakan data AJAX untuk tabel 'Daftar Permohonan Saya'.
     * PERBAIKAN: Menambahkan 'kode_pelacakan'
     */
    public function getData(Request $request)
    {
        $query = PermohonanAnalisis::with('dataSpasial')
            ->where('tipe', 'RESMI')
            ->where('user_id', Auth::id())
            ->select('permohonananalisis.*');

        return DataTables::of($query)
            ->addColumn('kode_pelacakan', function ($permohonan) {
                return '<strong class="font-mono">'.($permohonan->kode_pelacakan ?? 'N/A').'</strong>';
            })
            ->addColumn('nama_pemohon', function ($permohonan) {
                return $permohonan->nama_pemohon ?? '-';
            })
            ->addColumn('lokasi', function ($permohonan) {
                return $permohonan->dataSpasial->nama_areal ?? '-';
            })
            ->addColumn('kabupaten', function ($permohonan) {
                return $permohonan->dataSpasial->kabupaten ?? '-';
            })
            ->editColumn('tanggal_dibuat', function ($permohonan) {
                return $permohonan->created_at->format('d M Y, H:i');
            })
            ->editColumn('status', function ($permohonan) {
                $status = strtolower($permohonan->status ?? 'N/A');
                $badgeColor = 'bg-gray-500';
                if ($status == 'diajukan') {
                    $badgeColor = 'bg-blue-500';
                }
                if ($status == 'diproses') {
                    $badgeColor = 'bg-yellow-500';
                }
                if ($status == 'selesai') {
                    $badgeColor = 'bg-green-500';
                }
                if ($status == 'ditolak') {
                    $badgeColor = 'bg-red-500';
                }

                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '.$badgeColor.' text-white">'.
                       htmlspecialchars(Str::title($permohonan->status)).
                       '</span>';
            })
            ->addColumn('aksi', function ($permohonan) {
                $showUrl = route('permohonananalisis.show', $permohonan->slug);
                $editUrl = route('permohonananalisis.edit', $permohonan->slug);
                $deleteUrl = route('permohonananalisis.destroy', $permohonan->slug);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                $buttons = '<div class="flex items-center">';
                $buttons .= '<a href="'.$showUrl.'" class="font-medium text-blue-600 hover:underline mr-3"><i class="fas fa-file-alt"></i></a>';

                // --- PERBAIKAN: Izinkan Edit jika "Diajukan" ATAU "Ditolak" ---
                if (in_array(strtolower($permohonan->status), ['diajukan', 'ditolak'])) {
                    $buttons .= '<a href="'.$editUrl.'" class="font-medium text-yellow-600 hover:underline mr-3"><i class="fas fa-edit"></i></a>';
                }
                // Izinkan Hapus hanya jika masih 'Diajukan'
                if (strtolower($permohonan->status) == 'diajukan') {
                    $buttons .= '<form action="'.$deleteUrl.'" method="POST" class="inline" onsubmit="return confirm(\'Apakah Anda yakin?\');">';
                    $buttons .= $csrf.$method;
                    $buttons .= '<button type="submit" class="font-medium text-red-600 hover:underline"><i class="fas fa-trash-alt"></i></button>';
                    $buttons .= '</form>';
                }
                $buttons .= '</div>';

                return $buttons;
            })
            ->rawColumns(['aksi', 'status', 'kode_pelacakan'])
            ->make(true);
    }

    public function show($slug)
    {
        $permohonan = PermohonanAnalisis::where('slug', $slug)
            ->where('tipe', 'RESMI')
            ->where('user_id', Auth::id())
            ->with('dataSpasial', 'penelaah') // <-- Tambahkan relasi 'penelaah'
            ->firstOrFail();

        return view('permohonananalisis.show', compact('permohonan'));
    }

    public function edit($slug)
    {
        $permohonan = PermohonanAnalisis::where('slug', $slug)
            ->where('tipe', 'RESMI')
            ->where('user_id', Auth::id())
            ->with('dataSpasial')
            ->firstOrFail();

        // Otorisasi: Izinkan Edit jika "Diajukan" ATAU "Ditolak"
        if (! in_array(strtolower($permohonan->status), ['diajukan', 'ditolak'])) {
            return redirect()->route('permohonananalisis.show', $permohonan->slug)
                ->with('error', 'Permohonan yang sedang atau sudah diproses tidak dapat diedit.');
        }

        // --- TAMBAHAN: Muat GeoJSON yang sudah ada ---
        $usulanGeoJson = null;
        if ($permohonan->dataSpasial && $permohonan->dataSpasial->geojson_path) {
            $path = $permohonan->dataSpasial->geojson_path;
            if (Storage::disk('public')->exists($path)) {
                $usulanGeoJson = Storage::disk('public')->get($path);

                // Ambil hanya 'geometry'-nya saja untuk disisipkan
                $feature = json_decode($usulanGeoJson, true);
                if (isset($feature['geometry'])) {
                    $usulanGeoJson = json_encode($feature['geometry']);
                }
            }
        }
        // --- AKHIR TAMBAHAN ---

        return view('permohonananalisis.edit', compact('permohonan', 'usulanGeoJson'));
    }

    public function update(Request $request, $slug)
    {
        $permohonan = PermohonanAnalisis::where('slug', $slug)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // --- PERBAIKAN: Otorisasi (Sama seperti di atas) ---
        if (! in_array(strtolower($permohonan->status), ['diajukan', 'ditolak'])) {
            return redirect()->route('permohonananalisis.show', $permohonan->slug)
                ->with('error', 'Permohonan yang sedang atau sudah diproses tidak dapat diedit.');
        }

        // 1. Validasi Input
        $validated = $request->validate([
            // ... (semua validasi Anda dari 'store()') ...
            'nama_pemohon' => 'required|string|max:255',
            'hp_pemohon' => 'required|string|max:20',
            'email_pemohon' => 'nullable|email|max:255',
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'tujuan_analisis' => 'required|string|in:Perizinan,Klarifikasi Kawasan Hutan',
            'perihal_surat' => 'nullable|string',
            'file_surat' => 'nullable|file|mimes:pdf|max:10240', // Opsional saat update
            'lokasi' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'geojson_data' => 'required|json',
            'luas_ha' => 'required|numeric|min:0.0001',
            'source_type' => 'required|string|in:shapefile,photo,manual,prefilled',
            'shapefile_input' => 'nullable|file|mimes:zip|max:10240',
            'photos' => 'nullable|array',
            'photos.*' => 'nullable|image|mimes:jpeg,jpg|max:10240',
        ]);

        // ... (Logika Inisialisasi Data, Handle Upload File, Simpan GeoJSON Anda) ...
        // (Saya salin dari 'store' Anda)
        $disk = 'public';
        $dataSpasial = $permohonan->dataSpasial;
        $oldFileSuratPath = $permohonan->file_surat_path;
        $oldShapefilePath = $dataSpasial->shapefile_path;
        $oldPhotoPaths = $dataSpasial->photo_paths;
        $oldGeojsonDbPath = $dataSpasial->geojson_path;
        $fileSuratPath = $oldFileSuratPath;
        $shapefilePath = $oldShapefilePath;
        $photoPaths = $oldPhotoPaths;
        $incomingJson = json_decode($validated['geojson_data']);
        $geometry = $incomingJson->geometry ?? $incomingJson;
        if ($request->hasFile('file_surat')) {
            $fileSuratPath = $request->file('file_surat')->store("permohonan_resmi/{$permohonan->form_groupid}/surat", $disk);
        }
        if ($request->hasFile('shapefile_input')) {
            $shapefilePath = $request->file('shapefile_input')->store("permohonan_resmi/{$permohonan->form_groupid}/spasial", $disk);
            $photoPaths = null;
        }
        if ($request->hasFile('photos')) {
            $paths = [];
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store("permohonan_resmi/{$permohonan->form_groupid}/spasial", $disk);
                $paths[] = $path;
            }
            $photoPaths = json_encode($paths);
            $shapefilePath = null;
        }
        $feature = [
            'type' => 'Feature',
            'properties' => [
                'nama_areal' => $validated['lokasi'],
                'groupid' => $permohonan->form_groupid,
                'nama_pemohon' => $validated['nama_pemohon'],
                'kabupaten' => $validated['kabupaten'],
                'userid' => Auth::id(),
            ],
            'geometry' => $geometry,
        ];
        Storage::disk($disk)->put($oldGeojsonDbPath, json_encode($feature, JSON_PRETTY_PRINT));

        // 5. Simpan Entitas ke Database
        try {
            DB::transaction(function () use ($validated, $geometry, $shapefilePath, $photoPaths, $fileSuratPath, $permohonan, $dataSpasial) {

                // A. Update PermohonanAnalisis
                $permohonan->update([
                    'keterangan' => $validated['keterangan'],
                    'nama_pemohon' => $validated['nama_pemohon'],
                    'hp_pemohon' => $validated['hp_pemohon'],
                    'email_pemohon' => $validated['email_pemohon'],
                    'nomor_surat' => $validated['nomor_surat'],
                    'tanggal_surat' => $validated['tanggal_surat'],
                    'tujuan_analisis' => $validated['tujuan_analisis'],
                    'perihal_surat' => $validated['perihal_surat'],
                    'file_surat_path' => $fileSuratPath,
                    'form_userid' => Auth::id(),

                    // --- PERBAIKAN: Reset status jika Ditolak ---
                    'status' => 'Diajukan',
                ]);

                // B. Update DataSpasial
                $dataSpasial->update([
                    'nama_areal' => $validated['lokasi'],
                    'kabupaten' => $validated['kabupaten'],
                    'coordinates' => $geometry,
                    'luas_ha' => $validated['luas_ha'],
                    'shapefile_path' => $shapefilePath,
                    'photo_paths' => $photoPaths,
                    'source_type' => $validated['source_type'],
                ]);
            });
            // ... (Logika Hapus file-file lama Anda) ...

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: '.$e->getMessage())->withInput();
        }

        // Redirect kembali ke halaman daftar (index)
        return redirect()->route('permohonananalisis.index')->with('success', 'Permohonan berhasil diperbarui dan diajukan kembali.');
    }

    public function destroy($slug)
    {
        $permohonan = PermohonanAnalisis::where('slug', $slug)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        try {
            DB::transaction(function () use ($permohonan) {
                if ($permohonan->dataSpasial) {
                    $geojsonPath = $permohonan->dataSpasial->geojson_path;
                    if ($geojsonPath) {
                        $folderPath = dirname(dirname($geojsonPath));
                        Storage::disk('public')->deleteDirectory($folderPath);
                    }
                }
                $permohonan->delete();
            });
        } catch (\Exception $e) {
            return redirect()->route('permohonananalisis.index')->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }

        return redirect()->route('permohonananalisis.index')->with('success', 'Permohonan berhasil dihapus.');
    }
}
