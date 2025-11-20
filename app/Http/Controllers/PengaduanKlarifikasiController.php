<?php

namespace App\Http\Controllers;

// Pastikan namespace Anda benar
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class PengaduanKlarifikasiController extends Controller
{
    private $roleName = 'Penelaah Klarifikasi';

    private $category = 'KLARIFIKASI';

    // ===================================
    // FUNGSI UNTUK PENGGUNA (USER)
    // ===================================

    /**
     * Menampilkan halaman 'Riwayat Pengaduan'.
     */
    public function index()
    {
        $pengaduans = Pengaduan::where('user_id', Auth::id())
            ->where('category', $this->category)
            ->latest()
            ->paginate(10);

        return view('pengaduan.klarifikasi_index', compact('pengaduans'));
    }

    /**
     * Menampilkan halaman Form Pengaduan Klarifikasi.
     */
    public function create()
    {
        $user = Auth::user();

        return view('pengaduan.klarifikasi_create', compact('user'));
    }

    /**
     * Menyimpan pengaduan baru (KLARIFIKASI) ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'tujuan' => 'required|string|in:Perizinan,Klarifikasi Kawasan Hutan',
            'email' => 'nullable|email|max:255',
            'hp_pelapor' => 'required|string|max:20',
            'pesan' => 'required|string|max:5000',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'category' => 'required|string|in:KLARIFIKASI', // Memastikan kategori-nya benar
        ]);

        $buktiPath = null;
        if ($request->hasFile('file')) {
            $folder = 'pengaduan/'.Auth::id().'/klarifikasi/'.time();
            $buktiPath = $request->file('file')->store($folder, 'public');
        }

        Pengaduan::create([
            'user_id' => Auth::id(),
            'category' => $validated['category'],
            'nama' => $validated['nama'],
            'instansi' => $validated['instansi'],
            'tujuan' => $validated['tujuan'],
            'email' => $validated['email'],
            'pesan' => 'Kontak HP: '.$validated['hp_pelapor']."\n\n--- Isi Laporan ---\n".$validated['pesan'],
            'file' => $buktiPath,
            'status' => 'Baru',
        ]);

        return redirect()->route('pengaduan.klarifikasi.index')
            ->with('success', 'Pengaduan Klarifikasi Anda berhasil dikirim.');
    }

    public function destroy($id)
    {
        $pengaduan = Pengaduan::where('id', $id)
            ->where('user_id', Auth::id())   // keamanan: hanya pemilik boleh hapus
            ->firstOrFail();

        // Hapus file bukti jika ada
        if ($pengaduan->file && \Storage::disk('public')->exists($pengaduan->file)) {
            \Storage::disk('public')->delete($pengaduan->file);
        }

        $pengaduan->delete();

        return redirect()->back()->with('success', 'Pengaduan berhasil dihapus.');
    }

    // ===================================
    // FUNGSI UNTUK ADMIN / PENELAAH
    // ===================================

    /**
     * Menampilkan dashboard admin untuk pengaduan.
     */
    public function adminIndex()
    {
        if (! Auth::user()->can('access klarifikasi backend')) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        return view('adminklarifikasi.pengaduan.index');
    }

    /**
     * Melayani data JSON untuk DataTables admin.
     */
    /**
     * Melayani data JSON untuk DataTables admin.
     */
    public function adminGetData(Request $request)
    {
        $query = Pengaduan::where('category', 'KLARIFIKASI')
            ->with('user', 'penelaah'); // Load relasi user (pelapor) dan penelaah

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Filter Berdasarkan Tujuan (Role Admin IGT/Klarifikasi)
        if ($user->hasRole(['Admin IGT', 'Penelaah IGT'])) {
            $query->where('tujuan', 'Perizinan');
        } elseif ($user->hasRole(['Admin Klarifikasi', 'Penelaah Klarifikasi'])) {
            $query->where('tujuan', 'Klarifikasi Kawasan Hutan');
        }

        // 2. Filter Khusus Penelaah (Hanya Tugas Saya)
        if ($user->hasRole(['Penelaah', 'Penelaah IGT', 'Penelaah Klarifikasi']) && ! $user->hasRole(['Admin', 'Admin IGT', 'Admin Klarifikasi'])) {
            $query->where('penelaah_id', $user->id);
        }

        return DataTables::of($query)
            ->addColumn('kode_pelacakan', function ($row) {
                // ... (sisa kode kolom Anda)
                return '<strong class="font-mono">'.($row->kode_pelacakan ?? $row->id).'</strong>';
            })
            ->addColumn('pelapor', function ($row) {
                return $row->nama.'<br><small class="text-gray-500">'.$row->email.'</small>';
            })
            ->addColumn('tanggal_dibuat', function ($row) {
                return $row->created_at->isoFormat('D MMM YYYY, HH:mm');
            })
            ->editColumn('status', function ($row) {
                // ... (sisa kode status Anda)
                $status = strtolower($row->status);
                $badgeColor = 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'; // Baru
                if ($status == 'ditindaklanjuti' || $status == 'ditugaskan') {
                    $badgeColor = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300';
                }
                if ($status == 'selesai') {
                    $badgeColor = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300';
                }
                if ($status == 'direview') {
                    $badgeColor = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300';
                }

                return '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium '.$badgeColor.'">'.
                       Str::title($row->status).
                       '</span>';
            })
            ->addColumn('penelaah', function ($row) {
                if ($row->penelaah) {
                    return $row->penelaah->name;
                }

                return '<span class="text-xs italic text-gray-500">Belum ditugaskan</span>';
            })
            ->addColumn('aksi', function ($row) {
                if (empty($row->kode_pelacakan)) {
                    return '<span class="text-xs italic text-red-500">Data rusak</span>';
                }
                $showUrl = route('adminklarifikasi.pengaduan.show', $row->kode_pelacakan);
                $btn = '<a href="'.$showUrl.'" class="inline-flex items-center gap-2 rounded-lg bg-blue-500 px-3 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-blue-600">
                            Proses <i class="fas fa-arrow-right"></i>
                        </a>';

                return $btn;
            })
            ->rawColumns(['kode_pelacakan', 'pelapor', 'status', 'penelaah', 'aksi'])
            ->make(true);
    }

    /**
     * Menampilkan halaman detail pengaduan untuk Admin.
     */
    public function adminShow($kode_pelacakan)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (! $user->can('access klarifikasi backend')) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $pengaduan = Pengaduan::where('kode_pelacakan', $kode_pelacakan)->firstOrFail();

        // --- PERBAIKAN: Otorisasi Penelaah ---
        if ($user->hasRole('Penelaah Klarifikasi') && $pengaduan->penelaah_id !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk melihat pengaduan ini.');
        }
        // --- AKHIR PERBAIKAN ---

        $pengaduan->load('user', 'penelaah');
        $penelaahList = User::role('Penelaah Klarifikasi')->get();

        return view('adminklarifikasi.pengaduan.show', compact('pengaduan', 'penelaahList'));
    }

    /**
     * Menyimpan data disposisi (Penugasan Penelaah) untuk pengaduan.
     */
    public function adminAssign(Request $request, $kode_pelacakan)
    {
        if (! Auth::user()->hasRole('Admin Klarifikasi')) {
            abort(403, 'Hanya Admin yang dapat melakukan disposisi.');
        }

        $pengaduan = Pengaduan::where('kode_pelacakan', $kode_pelacakan)->firstOrFail();

        $validated = $request->validate([
            'penelaah_id' => 'required|exists:users,id',
        ]);

        $penelaah = User::find($validated['penelaah_id']);
        if (! $penelaah->hasRole('Penelaah Klarifikasi')) {
            return back()->with('error', 'User yang dipilih bukan Penelaah Klarifikasi.');
        }

        // Update pengaduan
        $pengaduan->update([
            'penelaah_id' => $validated['penelaah_id'],
            'status' => 'Ditugaskan', // <-- PERBAIKAN: Status baru
        ]);

        // (Opsional: Kirim Notifikasi ke Penelaah)
        // $penelaah->notify(new TugasPengaduanBaruNotification($pengaduan));

        return redirect()->route('adminklarifikasi.pengaduan.index')
            ->with('success', 'Pengaduan berhasil ditugaskan ke '.$penelaah->name);
    }

    /**
     * FUNGSI BARU: (Penelaah) Mengajukan draft balasan ke Admin.
     */
    public function adminSubmitReview(Request $request, $kode_pelacakan)
    {
        $user = Auth::user();
        $pengaduan = Pengaduan::where('kode_pelacakan', $kode_pelacakan)->firstOrFail();

        // Otorisasi: Hanya Penelaah yang ditugaskan
        if ($pengaduan->penelaah_id !== $user->id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $validated = $request->validate([
            'balasan_penelaah' => 'required|string|min:10|max:5000',
        ]);

        $pengaduan->update([
            'balasan_penelaah' => $validated['balasan_penelaah'],
            'status' => 'Direview', // Status baru, menunggu persetujuan Admin
        ]);

        return redirect()->route('adminklarifikasi.pengaduan.show', $pengaduan->kode_pelacakan)
            ->with('success', 'Balasan telah diajukan ke Admin untuk direview.');
    }

    /**
     * FUNGSI BARU: (Admin) Menyetujui balasan dan menyelesaikan pengaduan.
     */
    public function adminApprove(Request $request, $kode_pelacakan)
    {
        if (! Auth::user()->hasRole('Admin Klarifikasi')) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $pengaduan = Pengaduan::where('kode_pelacakan', $kode_pelacakan)->firstOrFail();

        $validated = $request->validate([
            // Admin bisa mengedit balasan final
            'balasan_final' => 'required|string|min:10|max:5000',
        ]);

        $pengaduan->update([
            'catatan_admin' => $validated['balasan_final'], // Balasan final disimpan di 'catatan_admin'
            'status' => 'Selesai',
        ]);

        // (Opsional: Kirim notifikasi ke Pengguna bahwa pengaduan mereka sudah dijawab)
        // $pengaduan->user->notify(new PengaduanDibalasNotification($pengaduan));

        return redirect()->route('adminklarifikasi.pengaduan.show', $pengaduan->kode_pelacakan)
            ->with('success', 'Balasan telah disetujui dan pengaduan ditutup.');
    }

    /**
     * FUNGSI BARU: (Admin) Menolak balasan dan mengembalikan ke Penelaah.
     */
    public function adminRejectReview(Request $request, $kode_pelacakan)
    {
        if (! Auth::user()->hasRole('Admin Klarifikasi')) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $pengaduan = Pengaduan::where('kode_pelacakan', $kode_pelacakan)->firstOrFail();

        $validated = $request->validate([
            'catatan_perbaikan' => 'required|string|min:10|max:5000',
        ]);

        $pengaduan->update([
            'catatan_admin' => $validated['catatan_perbaikan'], // Simpan catatan revisi
            'status' => 'Ditugaskan', // Kembalikan ke Penelaah
        ]);

        // (Opsional: Kirim notifikasi ke Penelaah bahwa balasannya ditolak)
        // $pengaduan->penelaah->notify(new BalasanDitolakNotification($pengaduan));

        return redirect()->route('adminklarifikasi.pengaduan.show', $pengaduan->kode_pelacakan)
            ->with('success', 'Balasan telah dikembalikan ke Penelaah untuk revisi.');
    }
}
