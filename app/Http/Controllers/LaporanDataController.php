<?php

namespace App\Http\Controllers;

// MODEL BARU
use App\Models\PermohonanAnalisis;
use App\Models\DataSpasial;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

class LaporanDataController extends Controller
{
    /**
     * Menampilkan halaman 'Data Saya' (Tabel Data).
     * Tidak ada perubahan di sini.
     */
    public function index()
    {
        return view('analisis.index');
    }

    /**
     * Menyediakan data AJAX untuk tabel 'Data Saya'.
     * SEKARANG MENGAMBIL DARI TABEL 'permohonananalisis'
     */
    public function getDataJson(Request $request)
    {
        // Ambil hanya permohonan TIPE MANDIRI dan milik user yang login
        $query = PermohonanAnalisis::with('dataSpasial')
            ->where('tipe', 'MANDIRI')
            ->where('user_id', Auth::id())
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
                $badgeColor = 'bg-gray-500'; // Default
                switch (strtolower($status)) {
                    case 'draft':
                        $badgeColor = 'bg-gray-500';
                        break; // Status baru kita
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
                return '<span class.="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' . $badgeColor . ' text-white">' .
                    htmlspecialchars($status) .
                    '</span>';
            })
            ->addColumn('aksi', function ($permohonan) {
                // Link aksi sekarang sudah benar mengarah ke route 'data.detail', 'data.edit', dll.
                $showUrl = route('data.detail', $permohonan->slug);
                $editUrl = route('data.edit', $permohonan->slug);
                $deleteUrl = route('data.delete', $permohonan->slug);
                $csrf = csrf_field();
                $method = method_field('DELETE');

                $buttons = '<div class="flex items-center">';
                $buttons .= '<a href="' . $showUrl . '" class="font-medium text-blue-600 hover:underline mr-3"><i class="fas fa-file-alt"></i></a>';
                // Izinkan edit/delete jika status masih 'Draft'
                if (strtolower($permohonan->status) == 'draft') {
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

    /**
     * Menampilkan halaman detail 'Data Saya'
     * SEKARANG MENGGUNAKAN MODEL BARU
     */
    public function show($slug)
    {
        $permohonan = PermohonanAnalisis::where('slug', $slug)
            ->where('tipe', 'MANDIRI')
            ->where('user_id', Auth::id())
            ->with('dataSpasial')
            ->firstOrFail();

        $usulanGeoJson = null;
        if ($permohonan->dataSpasial && $permohonan->dataSpasial->geojson_path) {
            $path = $permohonan->dataSpasial->geojson_path;
            if (Storage::disk('public')->exists($path)) {
                $usulanGeoJson = Storage::disk('public')->get($path);
            }
        }

        // Logika DataDasar (untuk dropdown analisis)
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

        // Kirim $permohonan (BUKAN $laporan) ke view
        return view('analisis.show', compact('permohonan', 'usulanGeoJson', 'dataDasarFiles'));
    }

    /**
     * Menampilkan form edit 'Data Saya'
     * SEKARANG MENGGUNAKAN MODEL BARU
     */
    public function edit($slug)
    {
        $permohonan = PermohonanAnalisis::where('slug', $slug)
            ->where('tipe', 'MANDIRI')
            ->where('user_id', Auth::id())
            ->with('dataSpasial')
            ->firstOrFail();

        // (View 'analisis.edit' perlu diperbarui untuk menggunakan $permohonan)
        return view('analisis.edit', compact('permohonan'));
    }

    /**
     * Meng-update data 'Data Saya'
     * SEKARANG MENGGUNAKAN MODEL BARU
     */
    public function update(Request $request, $slug)
    {
        $permohonan = PermohonanAnalisis::where('slug', $slug)
            ->where('tipe', 'MANDIRI')
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Validasi form (hanya field yang bisa diedit di 'analisis.edit')
        $validated = $request->validate([
            'lokasi' => 'required|string|max:255',
            'kabupaten' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        try {
            DB::transaction(function () use ($validated, $permohonan) {
                // Update tabel permohonananalisis
                $permohonan->update([
                    'keterangan' => $validated['keterangan']
                ]);
                // Update tabel data_spasials
                if ($permohonan->dataSpasial) {
                    $permohonan->dataSpasial->update([
                        'nama_areal' => $validated['lokasi'],
                        'kabupaten' => $validated['kabupaten'],
                    ]);
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }

        return redirect()->route('data.list')->with('success', 'Data analisis mandiri berhasil diperbarui.');
    }

    /**
     * Menghapus data 'Data Saya'
     * SEKARANG MENGGUNAKAN MODEL BARU
     */
    public function destroy($slug)
    {
        $permohonan = PermohonanAnalisis::where('slug', $slug)
            ->where('tipe', 'MANDIRI')
            ->where('user_id', Auth::id())
            ->with('dataSpasial')
            ->firstOrFail();

        try {
            DB::transaction(function () use ($permohonan) {
                // Hapus file/folder dari storage
                if ($permohonan->dataSpasial) {
                    // Ambil path unik (groupId)
                    $geojsonPath = $permohonan->dataSpasial->geojson_path;
                    if ($geojsonPath) {
                        // Path folder = "analisis_mandiri/{$groupId}"
                        $folderPath = dirname($geojsonPath);
                        Storage::disk('public')->deleteDirectory($folderPath);
                    }
                }

                // Hapus dari database (onDelete('cascade') akan menghapus data_spasials)
                $permohonan->delete();
            });
        } catch (\Exception $e) {
            return redirect()->route('data.list')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }

        return redirect()->route('data.list')->with('success', 'Data analisis mandiri berhasil dihapus.');
    }

    /**
     * (Fungsi ini tidak berubah, hanya namanya saja)
     */
    public function analyze(Request $request, $slug)
    {
        return back()->with('error', 'Fungsi analisis tidak tersedia.');
    }
}
