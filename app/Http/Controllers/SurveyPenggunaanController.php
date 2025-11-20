<?php

namespace App\Http\Controllers;

use App\Models\Permohonan;
use Carbon\Carbon; // Sesuaikan dengan Model IGT Anda
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class SurveyPenggunaanController extends Controller
{
    /**
     * Menampilkan halaman Monitoring Tunggakan Survey.
     */
    public function index()
    {
        // Otorisasi: Hanya Admin/Penelaah
        if (! Auth::user()->hasRole(['Admin IGT', 'Penelaah IGT'])) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        return view('surveypenggunaan.index');
    }

    /**
     * Data JSON untuk Tabel Monitoring.
     */
    public function getData(Request $request)
    {
        // 1. Ambil Permohonan yang sudah 'Selesai'
        // Pastikan menggunakan Model yang benar (PermohonanSpasial untuk IGT)
        $query = Permohonan::where('status', 'Selesai')
            ->with(['user', 'survey']); // Eager load user dan survey

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('tanggal_selesai', function ($row) {
                return $row->updated_at ? Carbon::parse($row->updated_at)->isoFormat('D MMMM YYYY') : '-';
            })
            ->addColumn('nama_pemohon', function ($row) {
                // Ambil dari tabel permohonan, atau fallback ke tabel user
                return $row->nama_pemohon ?? $row->user->name ?? '-';
            })
            ->addColumn('instansi', function ($row) {
                return $row->instansi ?? $row->user->instansi ?? '-';
            })
            ->addColumn('status_survey', function ($row) {
                // --- LOGIKA TUNGGAKAN ---
                // Cek apakah relasi 'survey' ada datanya
                if ($row->survey) {
                    return '<span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">Sudah Mengisi</span>';
                } else {
                    return '<span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">Belum Mengisi</span>';
                }
            })
            ->addColumn('aksi', function ($row) {
                // Opsional: Tombol untuk mengirim email reminder manual
                if (! $row->survey) {
                    return '<button class="text-blue-600 hover:underline text-xs" onclick="alert(\'Fitur Reminder Email belum aktif\')">Kirim Reminder</button>';
                }

                return '-';
            })
            ->rawColumns(['status_survey', 'aksi'])
            ->make(true);
    }
}
