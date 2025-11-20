<?php

namespace App\Http\Controllers\Adminklarifikasi;

use App\Http\Controllers\Controller;
use App\Models\PermohonanAnalisis;
use App\Models\User;
use App\Notifications\TugasBaruNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Pastikan ini ada
// <-- Pastikan ini ada
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

// <-- TAMBAHKAN INI

class PermohonanController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama untuk Admin/Penelaah.
     */
    public function index()
    {
        if (! Auth::user()->can('access klarifikasi backend')) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Ambil statistik (ini tidak berubah)
        $stats = PermohonanAnalisis::where('tipe', 'RESMI')
            ->select(
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'Diajukan' THEN 1 ELSE 0 END) as baru"),
                DB::raw("SUM(CASE WHEN status = 'Diproses' THEN 1 ELSE 0 END) as diproses"),
                DB::raw("SUM(CASE WHEN status = 'Selesai' THEN 1 ELSE 0 END) as selesai")
            )
            ->first();

        return view('adminklarifikasi.permohonan.index', [
            'stats_total' => $stats->total ?? 0,
            'stats_baru' => $stats->baru ?? 0,
            'stats_diproses' => $stats->diproses ?? 0,
            'stats_selesai' => $stats->selesai ?? 0,
        ]);
    }

    /**
     * Melayani data JSON untuk DataTables di dashboard Admin.
     */
    // File: app/Http/Controllers/Adminklarifikasi/PermohonanController.php

    public function getData(Request $request)
    {
        $query = PermohonanAnalisis::where('tipe', 'RESMI')
            ->with('dataSpasial', 'user', 'penelaah');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Filter Berdasarkan Tujuan (Role Admin IGT/Klarifikasi)
        if ($user->hasRole(['Admin IGT', 'Penelaah IGT'])) {
            $query->where('tujuan_analisis', 'Perizinan');
        } elseif ($user->hasRole(['Admin Klarifikasi', 'Penelaah Klarifikasi'])) {
            $query->where('tujuan_analisis', 'Klarifikasi Kawasan Hutan');
        }

        // 2. Filter Khusus Penelaah (Hanya Tugas Saya)
        if ($user->hasRole(['Penelaah', 'Penelaah IGT', 'Penelaah Klarifikasi']) && ! $user->hasRole(['Admin', 'Admin IGT', 'Admin Klarifikasi'])) {
            $query->where('penelaah_id', $user->id);
        }

        return DataTables::of($query)
            ->addColumn('kode_pelacakan', function ($row) {
                return '<strong class="font-mono">'.($row->kode_pelacakan ?? $row->id).'</strong>';
            })
            ->addColumn('pemohon', function ($row) {
                $nama = $row->user->name ?? $row->nama_pemohon;

                return $nama.'<br><small class="text-gray-500">'.($row->user->email ?? $row->email_pemohon).'</small>';
            })

            // --- BAGIAN INI YANG HILANG DAN MENYEBABKAN ERROR ---
            ->addColumn('tujuan', function ($row) {
                return $row->tujuan_analisis ?? '-';
            })
            // ---------------------------------------------------

            ->addColumn('penelaah', function ($row) {
                if ($row->penelaah) {
                    return $row->penelaah->name;
                }

                return '<span class="text-xs italic text-gray-500">Belum ditugaskan</span>';
            })
            ->addColumn('tanggal_dibuat', function ($row) {
                return $row->created_at->isoFormat('D MMM YYYY, HH:mm');
            })
            ->editColumn('status', function ($row) {
                $status = strtolower($row->status);
                $badgeColor = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300';
                if ($status == 'selesai') {
                    $badgeColor = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                }
                if ($status == 'diproses') {
                    $badgeColor = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                }
                if ($status == 'diajukan') {
                    $badgeColor = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                }
                if ($status == 'ditolak') {
                    $badgeColor = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
                }

                return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium '.$badgeColor.'">'.
                       Str::title($row->status).
                       '</span>';
            })
            ->addColumn('aksi', function ($row) {
                $showUrl = route('adminklarifikasi.permohonan.show', $row->slug);
                $btn = '<a href="'.$showUrl.'" class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-3 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-600">
                            Proses <i class="fas fa-arrow-right"></i>
                        </a>';

                return $btn;
            })
            ->rawColumns(['kode_pelacakan', 'pemohon', 'status', 'penelaah', 'aksi'])
            ->make(true);
    }

    /**
     * Menampilkan halaman detail permohonan untuk Admin/Penelaah.
     * (Fungsi ini tidak berubah)
     */
    public function show($slug)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (! $user->can('access klarifikasi backend')) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $permohonan = PermohonanAnalisis::where('slug', $slug)->firstOrFail();

        // --- PERBAIKAN: Otorisasi Penelaah ---
        // Jika user adalah Penelaah, periksa apakah ID penelaah di permohonan == ID user
        if ($user->hasRole('Penelaah Klarifikasi') && $permohonan->penelaah_id !== $user->id) {
            // Jika bukan, tolak akses
            abort(403, 'Anda tidak memiliki izin untuk melihat permohonan ini.');
        }
        // --- AKHIR PERBAIKAN ---

        $permohonan->load('dataSpasial', 'user', 'penelaah');
        $penelaahList = User::role('Penelaah Klarifikasi')->get();

        return view('adminklarifikasi.permohonan.show', compact('permohonan', 'penelaahList'));
    }

    /**
     * Menyimpan data disposisi (Penugasan Penelaah)
     * (Fungsi ini tidak berubah)
     */
    public function assign(Request $request, $slug)
    {
        if (! Auth::user()->hasRole('Admin Klarifikasi')) {
            abort(403, 'Hanya Admin yang dapat melakukan disposisi.');
        }

        $permohonan = PermohonanAnalisis::where('slug', $slug)->firstOrFail();

        $validated = $request->validate([
            'penelaah_id' => 'required|exists:users,id',
        ]);

        $penelaah = User::find($validated['penelaah_id']);
        if (! $penelaah->hasRole('Penelaah Klarifikasi')) {
            return back()->with('error', 'User yang dipilih bukan Penelaah Klarifikasi.');
        }

        // Update permohonan
        $permohonan->update([
            'penelaah_id' => $validated['penelaah_id'],
            'status' => 'Diproses',
        ]);

        // --- TAMBAHAN BARU: Kirim Notifikasi ke Penelaah ---
        try {
            $penelaah->notify(new TugasBaruNotification($permohonan));
        } catch (\Exception $e) {
            // Jika email gagal, jangan gagalkan seluruh proses
            // Cukup redirect dengan pesan error tambahan
            return redirect()->route('adminklarifikasi.permohonan.index')
                ->with('success', 'Permohonan berhasil ditugaskan ke '.$penelaah->name.' (Notifikasi email gagal dikirim).');
        }
        // --- AKHIR TAMBAHAN ---

        return redirect()->route('adminklarifikasi.permohonan.index')
            ->with('success', 'Permohonan berhasil ditugaskan ke '.$penelaah->name.' dan notifikasi telah terkirim.');
    }

    // --- FUNGSI BARU: MENYELESAIKAN TUGAS ---
    /**
     * Menyimpan hasil analisis dari Penelaah dan mengubah status ke "Selesai".
     */
    public function complete(Request $request, $slug)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $permohonan = PermohonanAnalisis::where('slug', $slug)->firstOrFail();

        // Otorisasi: Hanya Admin atau Penelaah yang ditugaskan
        if (
            ! $user->hasRole('Admin Klarifikasi') &&
            $permohonan->penelaah_id !== $user->id
        ) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Validasi input
        $validated = $request->validate([
            'catatan_penelaah' => 'nullable|string|max:5000',
            'file_surat_balasan' => 'required|file|mimes:pdf|max:5120', // Wajib 5MB PDF
            'file_paket_final' => 'required|file|mimes:zip|max:20480', // Wajib 20MB ZIP
        ]);

        $disk = 'public';
        $folder = 'permohonan_resmi/'.$permohonan->form_groupid.'/hasil'; // Simpan di folder hasil

        // Simpan file-file
        $suratBalasanPath = $request->file('file_surat_balasan')->store($folder, $disk);
        $paketFinalPath = $request->file('file_paket_final')->store($folder, $disk);

        // Update permohonan
        $permohonan->update([
            'status' => 'Selesai',
            'catatan_penelaah' => $validated['catatan_penelaah'],
            'file_surat_balasan_path' => $suratBalasanPath,
            'file_paket_final_path' => $paketFinalPath,
        ]);

        // Observer 'PermohonanAnalisisObserver' akan otomatis mengirim email notifikasi

        return redirect()->route('adminklarifikasi.permohonan.show', $permohonan->slug)
            ->with('success', 'Permohonan telah diselesaikan dan notifikasi telah dikirim ke pengguna.');
    }

    public function revert(Request $request, $slug)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $permohonan = PermohonanAnalisis::where('slug', $slug)->firstOrFail();

        // Otorisasi: Hanya Admin atau Penelaah yang ditugaskan
        if (
            ! $user->hasRole('Admin Klarifikasi') &&
            $permohonan->penelaah_id !== $user->id
        ) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // 1. Hapus file lama dari storage
        if ($permohonan->file_surat_balasan_path) {
            Storage::disk('public')->delete($permohonan->file_surat_balasan_path);
        }
        if ($permohonan->file_paket_final_path) {
            Storage::disk('public')->delete($permohonan->file_paket_final_path);
        }

        // 2. Update database: kembalikan status dan hapus path file
        $permohonan->update([
            'status' => 'Diproses', // Kembalikan ke status "Diproses"
            'file_surat_balasan_path' => null,
            'file_paket_final_path' => null,
            'catatan_penelaah' => 'Status dikembalikan untuk re-upload oleh '.$user->name,
        ]);

        // Penting: Kita harus 'mematikan' observer notifikasi di sini
        // agar pengguna tidak mendapat email "Selesai" lagi saat kita update.
        // Tapi karena kita ubah statusnya KE 'Diproses', observer 'Selesai' tidak akan terpicu.
        // Jadi kita aman.

        return redirect()->route('adminklarifikasi.permohonan.show', $permohonan->slug)
            ->with('success', 'Hasil telah dibatalkan. Silakan unggah ulang file yang benar.');
    }

    public function reject(Request $request, $slug)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $permohonan = PermohonanAnalisis::where('slug', $slug)->firstOrFail();

        // Otorisasi: Hanya Admin atau Penelaah yang ditugaskan
        if (
            ! $user->hasRole('Admin Klarifikasi') &&
            $permohonan->penelaah_id !== $user->id
        ) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Validasi input
        $validated = $request->validate([
            'alasan_penolakan' => 'required|string|min:10|max:5000',
        ]);

        // Update permohonan
        $permohonan->update([
            'status' => 'Ditolak',
            'catatan_penelaah' => $validated['alasan_penolakan'], // Simpan alasan
            'file_surat_balasan_path' => null, // Pastikan file hasil kosong
            'file_paket_final_path' => null,
        ]);

        // Observer 'PermohonanAnalisisObserver' akan otomatis mengirim email notifikasi

        return redirect()->route('adminklarifikasi.permohonan.show', $permohonan->slug)
            ->with('success', 'Permohonan telah ditolak dan notifikasi telah dikirim ke pengguna.');
    }
}
