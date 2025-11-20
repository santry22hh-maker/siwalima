<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class PengaduanController extends Controller
{
    public function index()
    {
        return view('pengaduan.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'pesan' => 'required|string|max:5000',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // === TAMBAHKAN BARIS INI ===
        $filePath = null;
        // ==========================

        if ($request->hasFile('file')) {
            // Simpan file ke storage/app/public/pengaduan_files
            $filePath = $request->file('file')->store('pengaduan_files', 'public');
        }

        Pengaduan::create([
            'user_id' => Auth::id(),
            'nama' => $validated['nama'],
            'instansi' => $validated['instansi'],
            'email' => $validated['email'],
            'pesan' => $validated['pesan'],
            'file' => $filePath, // <-- Sekarang variabel ini dijamin ada
            'status' => 'Baru',
        ]);

        return redirect()->route('pengaduan.saya')
            ->with('success', 'Pengaduan Anda telah berhasil dikirim. Terima kasih.');
    }

    /**
     * Menampilkan daftar pengaduan (BERBEDA untuk Admin dan Penelaah).
     */
    public function list(Request $request)
    {
        if ($request->ajax()) {
            $query = Pengaduan::where('category', 'UMUM')
                ->with('user', 'penelaah');

            $user = Auth::user();

            // Ambil SEMUA data, tapi load relasi 'penelaah'
            // $data = Pengaduan::query()->with('penelaah');

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('pesan', fn ($row) => Str::limit($row->pesan, 70))

                // 1. TAMBAHKAN KOLOM PENELAAH
                ->addColumn('penelaah_name', function ($row) {
                    if ($row->status == 'Baru') {
                        return '<span class="text-xs text-gray-500 italic">Belum ditangani</span>';
                    }

                    // Tampilkan nama penelaah jika ada
                    return $row->penelaah->name ?? '-';
                })

                ->editColumn('status', function ($row) {

                    if ($row->status == 'Baru') {
                        // Biru (Info)
                        return '<span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">Baru</span>';
                    } elseif ($row->status == 'Diproses') {
                        // Kuning (Warning)
                        return '<span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Diproses</span>';

                        // === PASTIKAN EJAAN DI SINI BENAR ===
                    } elseif ($row->status == 'Menunggu Persetujuan') {
                        // Ungu (Custom)
                        return '<span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800 dark:bg-purple-900 dark:text-purple-300">Menunggu Persetujuan</span>';
                    } elseif ($row->status == 'Revisi') {
                        // Oranye (Custom)
                        return '<span class="inline-flex items-center rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-medium text-orange-800 dark:bg-orange-900 dark:text-orange-300">Revisi</span>';
                    } elseif ($row->status == 'Selesai') {
                        // Hijau (Success)
                        return '<span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">Selesai</span>';
                    } elseif ($row->status == 'Dibatalkan') {
                        // Abu-abu (Neutral)
                        return '<span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-900 dark:text-gray-300">Dibatalkan</span>';
                    }

                    // Fallback jika ada status lain
                    return $row->status;
                })

                // 2. LOGIKA AKSI (TOMBOL) BARU
                ->addColumn('action', function ($row) {
                    $user = Auth::user();
                    $showUrl = route('pengaduan.show', $row->kode_pelacakan);
                    $text = 'Lihat Detail'; // Default

                    // --- Logika Tombol untuk Penelaah ---
                    if ($user->hasRole('Penelaah IGT')) {
                        if ($row->status == 'Baru') {
                            $text = 'Proses Pengaduan';
                        } elseif (in_array($row->status, ['Diproses', 'Revisi']) && $row->penelaah_id == $user->id) {
                            $text = ($row->status == 'Diproses') ? 'Lanjutkan Proses' : 'Proses Revisi';
                        } elseif (in_array($row->status, ['Diproses', 'Revisi']) && $row->penelaah_id != $user->id) {
                            // TERKUNCI oleh penelaah lain
                            return '<span class="text-xs text-gray-500 italic">Ditangani penelaah lain</span>';
                        }
                    }

                    // --- Logika Tombol untuk Admin ---
                    if ($user->hasRole('Admin IGT') && $row->status == 'Menunggu Persetujuan') {
                        $text = 'Review Persetujuan';
                    }

                    return '<a href="'.$showUrl.'" class="text-indigo-600 ...">'.$text.'</a>';
                })
                ->rawColumns(['status', 'action', 'penelaah_name']) // Tambahkan 'penelaah_name'
                ->make(true);
        }

        return view('pengaduan.list');
    }

    /**
     * Menampilkan detail pengaduan untuk dibalas.
     */
    public function show(Pengaduan $pengaduan)
    {
        $user = Auth::user();

        // --- LOGIKA KUNCI / LOCK ---
        if ($user->hasRole('Penelaah IGT')) {
            // 1. Klaim tiket 'Baru'
            if ($pengaduan->status == 'Baru') {
                $pengaduan->update([
                    'status' => 'Diproses',
                    'penelaah_id' => $user->id,
                ]);
            }
            // 2. Kunci tiket jika sudah diambil orang lain
            elseif (in_array($pengaduan->status, ['Diproses', 'Revisi', 'Menunggu Persetujuan']) && $pengaduan->penelaah_id != $user->id) {
                // Paksa kembali ke daftar
                return redirect()->route('pengaduan.list')
                    ->with('error', 'Pengaduan ini sedang ditangani oleh Penelaah lain.');
            }
        }
        // --- AKHIR LOGIKA KUNCI ---
        // (Admin dan pemilik tiket yang sah bisa lanjut)
        $pengaduan->load('penelaah');

        // Tampilkan view seperti biasa
        // (Logika di dalam view sudah benar, akan menampilkan/menyembunyikan form berdasarkan peran)
        return view('pengaduan.show', compact('pengaduan'));
    }

    /**
     * Method baru: Penelaah mengirimkan draf balasan.
     */
    public function submitReview(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'balasan_penelaah' => 'required|string|max:5000',
        ]);

        $pengaduan->update([
            'balasan_penelaah' => $validated['balasan_penelaah'],
            'status' => 'Menunggu Persetujuan',
        ]);

        return redirect()->route('pengaduan.list')->with('success', 'Draf balasan telah dikirim ke Admin untuk persetujuan.');
    }

    /**
     * Method baru: Admin menyetujui draf.
     */
    public function approve(Request $request, Pengaduan $pengaduan)
    {
        $pengaduan->update([
            'status' => 'Selesai',
            'catatan_admin' => 'Telah disetujui oleh '.Auth::user()->name, // Catatan persetujuan
        ]);

        // (Di sini Anda bisa menambahkan logika kirim email ke Pengguna)

        return redirect()->route('pengaduan.list')->with('success', 'Pengaduan telah disetujui dan ditutup.');
    }

    /**
     * Method baru: Admin menolak/merevisi draf.
     */
    public function reject(Request $request, Pengaduan $pengaduan)
    {
        $validated = $request->validate([
            'catatan_admin' => 'required|string|max:2000', // Catatan revisi wajib diisi
        ]);

        $pengaduan->update([
            'status' => 'Revisi',
            'catatan_admin' => $validated['catatan_admin'], // Simpan catatan revisi
        ]);

        return redirect()->route('pengaduan.list')->with('success', 'Pengaduan telah dikembalikan ke Penelaah untuk revisi.');
    }

    public function myComplaints()
    {
        // Ambil semua pengaduan yang user_id-nya sama dengan user yang login
        // Urutkan dari yang terbaru, dan gunakan paginasi
        $pengaduans = Pengaduan::where('user_id', Auth::id())
            ->latest()
            ->paginate(10); // 10 pengaduan per halaman

        // Kirim data ke view baru 'pengaduan.saya'
        // Pastikan Anda membuat file ini di Langkah 3
        return view('pengaduan.saya', compact('pengaduans'));
    }

    public function cancelComplaint(Pengaduan $pengaduan)
    {
        // Pastikan hanya pemilik yang bisa membatalkan
        if ($pengaduan->user_id != Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // Hanya bisa dibatalkan jika statusnya masih "Baru"
        if ($pengaduan->status == 'Baru') {
            $pengaduan->update(['status' => 'Dibatalkan']);

            return back()->with('success', 'Pengaduan telah berhasil dibatalkan.');
        }

        return back()->with('error', 'Pengaduan yang sudah diproses tidak dapat dibatalkan.');
    }
}
