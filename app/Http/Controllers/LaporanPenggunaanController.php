<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class LaporanPenggunaanController extends Controller
{
    /**
     * Menampilkan daftar permohonan yang sudah 'Selesai'
     * untuk di-upload laporannya oleh pengguna.
     */
    public function index()
    {
        $laporans = Permohonan::where('user_id', Auth::id())
            ->where('status', 'Selesai') // Hanya tampilkan yang Selesai
            ->latest('updated_at')
            ->paginate(10);

        return view('laporanpenggunaan.index', compact('laporans'));
    }

    /**
     * Menyimpan file laporan penggunaan yang di-upload pengguna.
     */
    public function store(Request $request, Permohonan $permohonan)
    {
        // 1. Otorisasi: Pastikan ini milik user
        if ($permohonan->user_id != Auth::id()) {
            abort(403);
        }

        // 2. Validasi file
        $validated = $request->validate([
            'laporan_penggunaan' => 'required|file|mimes:pdf,doc,docx|max:2048', // 2MB
        ]);

        // 3. Simpan file
        $filePath = $request->file('laporan_penggunaan')->store('laporan_penggunaan', 'public');

        // 4. Update permohonan
        $permohonan->update([
            'laporan_penggunaan_path' => $filePath
        ]);

        return back()->with('success', 'Laporan penggunaan data berhasil di-upload.');
    }

    public function reviewIndex(Request $request)
    {
        if ($request->ajax()) {

            // Ambil semua permohonan 'Selesai' yang SUDAH upload laporan
            $query = Permohonan::where('status', 'Selesai')
                ->whereNotNull('laporan_penggunaan_path')
                ->with('user'); // Ambil data pemohon

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nama_pemohon', function ($row) {
                    return $row->nama_pemohon ?? $row->user->name ?? 'N/A';
                })
                ->addColumn('instansi', function ($row) {
                    return $row->instansi ?? 'N/A';
                })
                ->addColumn('tanggal_selesai', function ($row) {
                    // Kapan permohonan ditandai 'Selesai'
                    return Carbon::parse($row->updated_at)->isoFormat('D MMMM YYYY');
                })
                ->addColumn('aksi', function ($row) {
                    // Tombol untuk men-download laporan yang di-upload pengguna
                    $url = Storage::url($row->laporan_penggunaan_path);
                    return '<a href="' . $url . '" target="_blank"
                               class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-700">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                </svg>
                                Lihat Laporan
                            </a>';
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        // Tampilkan view DataTables
        return view('laporanpenggunaan.review');
    }
}
