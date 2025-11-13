<?php

// Pastikan namespace sudah benar
namespace App\Http\Controllers;

// Import Model baru
use App\Models\PermohonanAnalisis;
use App\Models\DataSpasial;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

// ===================================
// GANTI NAMA CLASS DI SINI
// ===================================
class PermohonanAnalisisController extends Controller // <-- GANTI INI
{
    /**
     * Menampilkan halaman 'Daftar Permohonan Saya' (Tabel Data).
     */
    public function index()
    {
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
            // Kita cari dari model LAMA (Laporan)
            // Pastikan Anda juga meng-import App\Models\Laporan di atas jika belum
            $laporanFrom = \App\Models\Laporan::where('slug', $request->from_slug)
                ->with('polygon')
                ->first();

            if ($laporanFrom && $laporanFrom->polygon && $laporanFrom->polygon->geojson_path) {
                $path = $laporanFrom->polygon->geojson_path;
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
            'perihal_surat' => 'nullable|string',
            'file_surat' => 'required|file|mimes:pdf|max:5120',
            'lokasi' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'geojson_data' => 'required|json',
            'source_type' => 'required|string|in:shapefile,photo,manual,prefilled',
            'shapefile_input' => 'required_if:source_type,shapefile|file|mimes:zip|max:10240',
            'photos' => 'required_if:source_type,photo|array',
            'photos.*' => 'image|mimes:jpeg,jpg|max:5120',
        ]);

        // 2. Inisialisasi Data
        $geometry = json_decode($validated['geojson_data']);
        $groupId = Str::uuid();
        $shapefilePath = null;
        $photoPaths = null;
        $fileSuratPath = null;
        $disk = 'public';

        // 3. Simpan File (Surat, SHP, Foto)
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
            ],
            'geometry' => $geometry,
        ];
        $geojsonFileName = $groupId . '_spasial.geojson';
        $geojsonDbPath = "permohonan_resmi/{$groupId}/spasial/" . $geojsonFileName;
        Storage::disk($disk)->put($geojsonDbPath, json_encode($feature, JSON_PRETTY_PRINT));

        // 5. Simpan Entitas ke Database (dalam Transaksi)
        try {
            DB::transaction(function () use ($validated, $geometry, $geojsonDbPath, $shapefilePath, $photoPaths, $fileSuratPath, $groupId) {

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
                    'perihal_surat' => $validated['perihal_surat'],
                    'file_surat_path' => $fileSuratPath,
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

        // 6. Redirect dengan Pesan Sukses
        return redirect()->route('permohonananalisis.index')->with('success', 'Permohonan Anda berhasil diajukan!');
    }

    /**
     * Menyediakan data AJAX untuk tabel 'Daftar Permohonan Saya'.
     */
    public function getData(Request $request)
    {
        // Ambil hanya permohonan TIPE RESMI
        $query = PermohonanAnalisis::with('dataSpasial')
            ->where('tipe', 'RESMI')
            // (Opsional) Filter hanya untuk user yang login
            // ->where('user_id', Auth::id()) 
            ->select('permohonananalisis.*');

        return DataTables::of($query)
            ->addColumn('keterangan', function ($permohonan) {
                return $permohonan->keterangan ?? '-';
            })
            ->addColumn('jenis_data', function ($permohonan) {
                $source = $permohonan->dataSpasial->source_type ?? 'N/A';
                if ($source == 'shapefile') return 'Shapefile';
                if ($source == 'photo') return 'Foto Geotag';
                if ($source == 'manual') return 'Input Manual';
                if ($source == 'prefilled') return 'Data Analisis';
                return $source;
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
                $status = $permohonan->status ?? 'N/A';
                $badgeColor = 'bg-gray-500';
                switch (strtolower($status)) {
                    case 'diajukan':
                        $badgeColor = 'bg-blue-500';
                        break;
                    case 'diproses':
                        $badgeColor = 'bg-yellow-500';
                        break;
                    case 'selesai':
                        $badgeColor = 'bg-green-500';
                        break;
                    case 'ditolak':
                        $badgeColor = 'bg-red-500';
                        break;
                }
                return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $badgeColor . ' text-white">' .
                    htmlspecialchars($status) .
                    '</span>';
            })
            ->addColumn('aksi', function ($permohonan) {
                $showUrl = route('permohonananalisis.show', $permohonan->slug);
                $editUrl = route('permohonananalisis.edit', $permohonan->slug);
                $deleteUrl = route('permohonananalisis.destroy', $permohonan->slug);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                $buttons = '<div class="flex items-center">';
                $buttons .= '<a href="' . $showUrl . '" class="font-medium text-blue-600 hover:underline mr-3"><i class="fas fa-file-alt"></i></a>';
                if (strtolower($permohonan->status) == 'diajukan') {
                    $buttons .= '<a href="' . $editUrl . '" class="font-medium text-yellow-600 hover:underline mr-3"><i class="fas fa-edit"></i></a>';
                    $buttons .= '<form action="' . $deleteUrl . '" method="POST" class="inline" onsubmit="return confirm(\'Apakah Anda yakin?\');">';
                    $buttons .= $csrf . $method;
                    $buttons .= '<button type="submit" class="font-medium text-red-600 hover:underline"><i class="fas fa-trash-alt"></i></button>';
                    $buttons .= '</form>';
                }
                $buttons .= '</div>';

                return $buttons;
            })
            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }

    // =============================================
    // (Placeholder untuk Show, Edit, Update, Destroy)
    // =============================================

    public function show($slug)
    {
        $permohonan = PermohonanAnalisis::where('slug', $slug)->with('dataSpasial')->firstOrFail();
        // Anda perlu membuat view 'permohonan.show'
        // Anda bisa menyalin 'analisis.show.blade.php' sebagai permulaan
        return view('permohonananalisis.show', compact('permohonan'));
    }

    public function edit($slug)
    {
        $permohonan = PermohonanAnalisis::where('slug', $slug)->with('dataSpasial')->firstOrFail();
        // Anda perlu membuat view 'permohonan.edit'
        // Anda bisa menyalin 'permohonan.create.blade.php' dan mengisinya dengan data '$permohonan'
        return view('permohonananalisis.edit', compact('permohonan'));
    }

    public function update(Request $request, $slug)
    {
        // (Logika untuk update permohonan)
        $permohonan = PermohonanAnalisis::where('slug', $slug)->firstOrFail();

        // ... (Tambahkan logika validasi & update di sini) ...

        return redirect()->route('permohonananalisis.index')->with('success', 'Permohonan berhasil diperbarui.');
    }

    public function destroy($slug)
    {
        $permohonan = PermohonanAnalisis::where('slug', $slug)->firstOrFail();

        // (Tambahkan logika untuk menghapus file dari storage di sini)

        $permohonan->delete(); // Ini akan otomatis menghapus data_spasial-nya (karena onDelete('cascade'))

        return redirect()->route('permohonananalisis.index')->with('success', 'Permohonan berhasil dihapus.');
    }
}
