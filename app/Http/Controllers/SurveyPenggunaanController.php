<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permohonan;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class SurveyPenggunaanController extends Controller
{
    public function monitoringTunggakan(Request $request)
    {
        if ($request->ajax()) {

            // --- PERUBAHAN QUERY ---
            // Ambil SEMUA permohonan 'Selesai',
            // dan muat relasi 'survey'-nya (jika ada)
            $query = Permohonan::where('status', 'Selesai')
                ->with('survey') // Ganti from doesntHave('survey')
                ->with('user');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nama_pemohon', function ($row) {
                    return $row->nama_pemohon ?? $row->user->name ?? 'N/A';
                })
                ->addColumn('instansi', function ($row) {
                    return $row->instansi ?? 'N/A';
                })
                ->addColumn('tanggal_selesai', function ($row) {
                    return Carbon::parse($row->updated_at)->isoFormat('D MMMM YYYY');
                })
                ->addColumn('status_survey', function ($row) {
                    // --- PERUBAHAN LOGIKA ---
                    // Cek apakah relasi 'survey' ada (berhasil di-load)
                    if ($row->survey) {
                        // Jika ada, berarti sudah mengisi
                        return '<span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">Sudah Mengisi</span>';
                    } else {
                        // Jika tidak ada (null), berarti belum mengisi
                        return '<span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">Belum Mengisi</span>';
                    }
                })
                ->rawColumns(['status_survey'])
                ->make(true);
        }

        // Nama view ini ('permohonanspasial.monitoring_tunggakan') sudah benar
        return view('surveypenggunaan.index');
    }
}
