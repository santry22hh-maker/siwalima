<?php

namespace App\Http\Controllers;

// Impor semua kelas yang kita butuhkan
use App\Models\DataIgt;
use App\Models\DetailPermohonan;
use App\Models\Permohonan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use ZipArchive;

class PermohonanSpasialController extends Controller
{
    // Daftar cakupan untuk dropdown
    private $cakupanOptions = [
        'Provinsi Maluku',
        'Kabupaten Buru',
        'Kabupaten Buru Selatan',
        'Kabupaten Seram Bagian Barat',
        'Kabupaten Maluku Tengah',
        'Kabupaten Seram Bagian Timur',
        'Kabupaten Maluku Tenggara',
        'Kabupaten Kepulauan Aru',
        'Kabupaten Kepulauan Tanimbar',
        'Kota Tual',
        'Kabupaten Maluku Barat Daya',
        'Kota Ambon',
    ];

    /**
     * Menampilkan daftar permohonan (DataTables) untuk Staf (Admin/Penelaah).
     */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($request->ajax()) {

            $query = Permohonan::query()->with('user', 'penelaah');

            // --- Filter berdasarkan Peran ---
            if ($user->hasRole('Penelaah')) {
                $query->where('penelaah_id', $user->id);
            }

            // --- Filter berdasarkan TAB STATUS ---
            $status = $request->get('status_filter');

            if ($user->hasRole('Admin IGT')) {
                if ($status == 'Tugas') {
                    $query->whereIn('status', ['Pending', 'Menunggu Verifikasi Staf']);
                } elseif ($status == 'Selesai') {
                    $query->where('status', 'Selesai');
                }
            } elseif ($user->hasRole('Penelaah')) {
                if ($status == 'Tugas') {
                    $query->whereIn('status', ['Diproses', 'Revisi', 'Menunggu Verifikasi Staf']);
                } elseif ($status == 'Selesai') {
                    $query->where('status', 'Selesai');
                }
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('nama_pemohon', function ($row) {
                    return $row->nama_pemohon.
                        '<br><small class="text-gray-500 dark:text-gray-400">'.$row->instansi.'</small>';
                })
                ->addColumn('tanggal_surat', function ($row) {
                    return Carbon::parse($row->tanggal_surat)->isoFormat('D MMMM YYYY');
                })
                ->addColumn('status', function ($row) {
                    // ... (logika badge status Anda) ...
                    if ($row->status == 'Pending') {
                        return '<span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800 dark:bg-red-900 dark:text-red-300">Pending (Admin)</span>';
                    } elseif ($row->status == 'Diproses') {
                        return '<span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">Diproses (Penelaah)</span>';
                    } elseif ($row->status == 'Menunggu TTD Pengguna') {
                        return '<span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900 dark:text-blue-300">Menunggu TTD</span>';
                    } elseif ($row->status == 'Menunggu Verifikasi Staf') {
                        return '<span class="inline-flex items-center rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">Menunggu Verifikasi</span>';
                    } elseif ($row->status == 'Selesai') {
                        return '<span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 dark:bg-green-900 dark:text-green-300">Selesai</span>';
                    }

                    return $row->status;
                })
                ->addColumn('penelaah', function ($row) {
                    return $row->penelaah->name ?? '<span class="text-xs italic text-gray-500">Belum ditugaskan</span>';
                })
                ->addColumn('action', function ($row) {
                    /** @var \App\Models\User $user */
                    $user = Auth::user();
                    $showUrl = route('permohonanspasial.show', $row->id);
                    $text = 'Lihat Detail'; // Default

                    // --- INI LOGIKA BARU UNTUK TOMBOL AKSI ---
                    if ($user->hasRole('Admin IGT')) {
                        if ($row->status == 'Pending') {
                            $text = 'Disposisi Sekarang';
                        }
                        if ($row->status == 'Menunggu Verifikasi Staf') {
                            $text = 'Verifikasi (Final)';
                        }
                    } elseif ($user->hasRole('Penelaah')) {
                        if ($row->penelaah_id == $user->id) {
                            if ($row->status == 'Diproses') {
                                $text = 'Proses (Buat BA)';
                            } // Teks baru
                            if ($row->status == 'Menunggu Verifikasi Staf') {
                                $text = 'Verifikasi (Upload Final)';
                            }
                        }
                    }
                    // --- AKHIR LOGIKA BARU ---

                    $btn = '<a href="'.$showUrl.'" 
                               style="background-color: #3b82f6; color: white; padding: 4px 8px; border-radius: 0.25rem; font-size: 0.75rem; text-decoration: none;"
                               onmouseover="this.style.backgroundColor=\'#2563eb\'"
                               onmouseout="this.style.backgroundColor=\'#3b82f6\'">
                               '.$text.'
                            </a>';

                    if ($user->hasRole('Admin IGT')) {
                        $deleteUrl = route('permohonanspasial.destroy', $row->id);
                        $btn .= ' <form action="'.$deleteUrl.'" method="POST" class="inline" onsubmit="return confirm(\'Anda yakin ingin menghapus data ini?\');">'
                            .csrf_field().method_field('DELETE')
                            .'<button type="submit" 
                                   style="background-color: #ef4444; color: white; padding: 4px 8px; border-radius: 0.25rem; font-size: 0.75rem; border: none; cursor: pointer;"
                                   onmouseover="this.style.backgroundColor=\'#dc2626\'"
                                   onmouseout="this.style.backgroundColor=\'#ef4444\'">
                                   Hapus
                                </button>'
                            .'</form>';
                    }

                    return $btn;
                })
                ->rawColumns(['nama_pemohon', 'status', 'penelaah', 'action'])
                ->make(true);
        }

        return view('permohonanspasial.index');
    }

    /**
     * Menampilkan form create (dengan logika banning).
     */
    public function create(Request $request)
    {
        $userId = Auth::id();
        $limit = 1; // Tentukan batas (3 kali)

        // 1. LOGIKA BLOKIR: LAPORAN PENGGUNAAN
        $laporanTertunggak = Permohonan::where('user_id', $userId)
            ->where('status', 'Selesai')
            ->whereNull('laporan_penggunaan_path')
            ->count();

        if ($laporanTertunggak >= $limit) {
            return view('permohonanspasial.blocked', [
                'tunggakan' => $laporanTertunggak,
                'limit' => $limit,
                'jenis_tunggakan' => 'Laporan Penggunaan Data',
            ]);
        }

        // 2. LOGIKA BLOKIR: SURVEY
        $surveyTertunggak = Permohonan::where('user_id', $userId)
            ->where('status', 'Selesai')
            ->doesntHave('survey') // Cek permohonan yang TIDAK punya relasi survei
            ->count();

        if ($surveyTertunggak >= $limit) {
            return view('permohonanspasial.blocked', [
                'tunggakan' => $surveyTertunggak,
                'limit' => $limit,
                'jenis_tunggakan' => 'Survey Kepuasan Pelayanan',
            ]);
        }

        // --- Jika tidak diblokir, lanjutkan ---
        $igtIds = $request->query('igt_ids', []);
        $selectedIgt = DataIgt::whereIn('id', $igtIds)->get();
        $jenisDataOptions = DataIgt::select('id', 'jenis_data', 'format_data')->get();

        return view('permohonanspasial.create', [
            'selectedIgt' => $selectedIgt,
            'cakupanOptions' => $this->cakupanOptions,
            'jenisDataOptions' => $jenisDataOptions,
        ]);
    }

    /**
     * Menyimpan permohonan (Status: Pending).
     */
    public function store(Request $request)
    {
        // 1. Validasi data
        $validated = $request->validate([
            'tipe_pemohon' => 'required|string|in:pemerintah,akademisi', // Validasi baru
            'nama_pemohon' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:20',
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'file_surat' => 'required|file|mimes:pdf,doc,docx|max:2048',

            // Ubah NIP dan Jabatan menjadi 'nullable'
            'nip' => 'required_if:tipe_pemohon,pemerintah|nullable|string|max:50',
            'jabatan' => 'required_if:tipe_pemohon,pemerintah|nullable|string|max:255',

            // (Data IGT Anda sudah benar)
            'requested_data' => 'required|array|min:1',
            'requested_data.*.daftar_igt_id' => 'required|exists:data_igts,id',
            'requested_data.*.cakupan_wilayah' => 'required|string',
            'requested_data.*.keterangan' => 'nullable|string|max:255',

            // Hapus 'perihal' jika tidak ada di form Anda, atau tambahkan 'nullable'
            'perihal' => 'nullable|string',
        ]);

        $filePath = $request->file('file_surat')->store('surat_permohonan', 'public');

        // 2. Buat data Permohonan
        $permohonan = Permohonan::create([
            'user_id' => Auth::id(),
            'tipe_pemohon' => $validated['tipe_pemohon'], // <-- Simpan data baru
            'nama_pemohon' => $validated['nama_pemohon'],
            'nip' => $validated['nip'], // Akan null jika tidak diisi
            'jabatan' => $validated['jabatan'], // Akan null jika tidak diisi
            'instansi' => $validated['instansi'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'perihal' => $validated['perihal'],
            'file_surat' => $filePath,
            'status' => 'Pending',
        ]);

        // ... (sisa logika 'foreach' Anda) ...
        foreach ($validated['requested_data'] as $detail) {
            DetailPermohonan::create([
                'permohonan_id' => $permohonan->id,
                'daftar_igt_id' => $detail['daftar_igt_id'],
                'cakupan_wilayah' => $detail['cakupan_wilayah'],
                'keterangan' => $detail['keterangan'] ?? null,
            ]);
        }

        return redirect()->route('permohonanspasial.saya')
            ->with('success', 'Permohonan berhasil diajukan dan sedang menunggu disposisi.');
    }

    /**
     * Menampilkan detail permohonan untuk Staf.
     */
    public function show(Permohonan $permohonan)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Otorisasi
        if ($user->hasRole('Penelaah') && $permohonan->penelaah_id != $user->id && $permohonan->status != 'Pending') {
            return redirect()->route('permohonanspasial.index')
                ->with('error', 'Anda tidak ditugaskan untuk permohonan ini.');
        }

        $daftarPenelaah = [];
        if ($user->hasRole('Admin IGT') && $permohonan->status == 'Pending') {
            $daftarPenelaah = User::role('Penelaah IGT')->get();
        }

        $permohonan->load('detailPermohonan.dataIgt', 'penelaah');

        return view('permohonanspasial.show', compact('permohonan', 'daftarPenelaah'));
    }

    /**
     * Menampilkan form edit (untuk Staf).
     */
    public function edit(Permohonan $permohonan)
    {
        abort(501, 'Fitur Edit Belum Diimplementasikan.');
    }

    /**
     * Update permohonan (untuk Staf).
     */
    public function update(Request $request, Permohonan $permohonan)
    {
        abort(501, 'Fitur Update Belum Diimplementasikan.');
    }

    /**
     * Hapus permohonan (untuk Staf).
     */
    public function destroy(Permohonan $permohonan)
    {
        if (! Auth::user()->hasRole('Admin IGT')) {
            abort(403);
        }

        if ($permohonan->file_surat) {
            Storage::disk('public')->delete($permohonan->file_surat);
        }
        if ($permohonan->file_berita_acara) {
            Storage::disk('public')->delete($permohonan->file_berita_acara);
        }
        if ($permohonan->file_ba_ttd) {
            Storage::disk('public')->delete($permohonan->file_ba_ttd);
        }
        if ($permohonan->file_surat_balasan) {
            Storage::disk('public')->delete($permohonan->file_surat_balasan);
        }
        if ($permohonan->file_data_final) {
            Storage::disk('public')->delete($permohonan->file_data_final);
        }
        if ($permohonan->file_paket_final) {
            Storage::disk('public')->delete($permohonan->file_paket_final);
        }

        $permohonan->delete();

        return redirect()->route('permohonanspasial.index')->with('success', 'Data permohonan berhasil dihapus.');
    }

    /**
     * Admin (Kepala Seksi) menugaskan Penelaah.
     */
    public function assign(Request $request, Permohonan $permohonan)
    {
        $validated = $request->validate([
            'penelaah_id' => 'required|exists:users,id',
        ]);

        $permohonan->update([
            'penelaah_id' => $validated['penelaah_id'],
            'status' => 'Diproses',
        ]);

        return redirect()->route('permohonanspasial.index')
            ->with('success', 'Permohonan telah berhasil didisposisikan ke Penelaah.');
    }

    public function showEditorBA(Permohonan $permohonan)
    {
        // 1. Otorisasi
        if (Auth::id() != $permohonan->penelaah_id || $permohonan->status != 'Diproses') {
            return redirect()->route('permohonanspasial.index')
                ->with('error', 'Aksi tidak diizinkan.');
        }

        $permohonan->load('detailPermohonan.dataIgt');

        // 2. Siapkan Data Pihak Pertama dan Tanggal
        $pihakPertama = [
            'nama' => 'Marleen Annete Tuakora, S.Hut, M.Si', // Ganti dengan data staf Anda
            'nip' => '19900513 201402 2 005',
            'jabatan' => 'Kepala Seksi Sumber Daya Hutan',
        ];
        Carbon::setLocale('id');
        $now = Carbon::now(); // <-- Simpan waktu saat ini
        $tanggal_ba_terbilang = $now->isoFormat('dddd, D MMMM YYYY');

        $bulanAngka = $now->format('m'); // '11' (November)

        // 3. GENERATE TABEL DETAIL DATA (HTML Mentah)
        $tableRowsHtml = '';
        $nomor = 1;
        foreach ($permohonan->detailPermohonan as $detail) {
            $tableRowsHtml .= '<tr style="text-align: left;">'.
                '<td style="border: 1px solid black; padding: 8px; text-align: center;">'.$nomor++.'</td>'.
                '<td style="border: 1px solid black; padding: 8px;">'.e($detail->dataIgt->jenis_data ?? 'N/A').'</td>'.
                '<td style="border: 1px solid black; padding: 8px;">'.e($detail->cakupan_wilayah).'</td>'.
                '<td style="border: 1px solid black; padding: 8px;">'.e($detail->dataIgt->periode_update ?? '-').'</td>'.
                '<td style="border: 1px solid black; padding: 8px;">'.e($detail->dataIgt->format_data ?? 'N/A').'</td>'.
                '</tr>';
        }

        // 4. Ganti Placeholder di Template BA
        $templateHtml = view('permohonanspasial.ba_template_editor')->render();

        $logoUrl = asset('src/images/logo/logo_kemenhut.png');
        $logoIsoUrl = asset('src/images/logo/logo_iso.png');

        $placeholders = [
            '[[tanggal_terbilang]]' => $tanggal_ba_terbilang,
            '[[nomor_ba]]' => $bulanAngka,
            '[[nama_pihak_pertama]]' => $pihakPertama['nama'],
            '[[nip_pihak_pertama]]' => $pihakPertama['nip'],
            '[[jabatan_pihak_pertama]]' => $pihakPertama['jabatan'],
            '[[nama_pemohon]]' => $permohonan->nama_pemohon,
            '[[nip]]' => $permohonan->nip,
            '[[jabatan]]' => $permohonan->jabatan,
            '[[instansi]]' => $permohonan->instansi,
            '[[email]]' => $permohonan->email,
            '[[no_hp]]' => $permohonan->no_hp,
            '[[DATA_TABLE_ROWS]]' => $tableRowsHtml,

            // === INI ADALAH PERBAIKANNYA ===
            // Ganti 'URL_LOGO_KEMENHUT' dengan path yang benar ke logo Anda
            // Pastikan file ini ada di 'public/src/images/logo/logo_kemenhut.png'
            'URL_LOGO_KEMENHUT' => $logoUrl,
            'URL_LOGO_ISO' => $logoIsoUrl, //
        ];

        $finalContent = str_replace(array_keys($placeholders), array_values($placeholders), $templateHtml);

        // 5. Kirim ke view editor
        return view('permohonanspasial.editor-ba', [
            'permohonan' => $permohonan,
            'finalContent' => $finalContent,
        ]);
    }

    /**
     * Menerima konten HTML dari editor, men-generate PDF final, dan mengubah status.
     */
    public function generateBAFromEditor(Request $request, Permohonan $permohonan)
    {
        if (Auth::id() != $permohonan->penelaah_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $request->validate(['isi_surat_final' => 'required|string']);
        $finalContent = $request->input('isi_surat_final');

        // 2. Konversi URL logo menjadi Base64
        $logoPath = public_path('src/images/logo/logo_kemenhut.png');
        $logoUrl = asset('src/images/logo/logo_kemenhut.png');

        $logoIsoPath = public_path('src/images/logo/logo_iso.png');
        $logoIsoUrl = asset('src/images/logo/logo_iso.png');

        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoType = mime_content_type($logoPath);
            $base64Logo = 'data:'.$logoType.';base64,'.$logoData;

            $finalContent = str_replace($logoUrl, $base64Logo, $finalContent);
            // dd($finalContent);
        } else {
            $finalContent = str_replace($logoUrl, '', $finalContent);

        }

        if (file_exists($logoIsoPath)) {
            $logoIsoData = base64_encode(file_get_contents($logoIsoPath));
            $logoIsoType = mime_content_type($logoIsoPath);
            $base64LogoIso = 'data:'.$logoIsoType.';base64,'.$logoIsoData;
            // Penting: Ganti URL asset() yang mungkin sudah ada di konten TinyMCE
            $finalContent = str_replace($logoIsoUrl, $base64LogoIso, $finalContent);
        } else {
            $finalContent = str_replace($logoIsoUrl, '', $finalContent);
        }

        $marginStyle = '
        <style>
            @page {
                margin: 1.5cm 1.5cm 2.5cm 1.5cm;
            }

            body {
                font-family: Arial, sans-serif;
            }

            footer {
                position: fixed;
                bottom: -55px;   /* <— ubah jadi kecil agar logo menempel ke bawah */
                left: 40px;    /* jarak dari kiri halaman */
                text-align: left;
            }

            footer img {
                height: 55px;  /* sedikit lebih kecil supaya proporsional */
                width: auto;
                opacity: 0.85;
            }
        </style>';

        $footerHtml = '
        <footer>
            <img src="'.$base64LogoIso.'" alt="Logo ISO 9001:2015">
        </footer>
    ';

        $finalHtmlForPdf = $marginStyle.$finalContent.$footerHtml;

        $pdf = PDF::loadHTML($finalHtmlForPdf);

        $namaFile = 'berita_acara/BA-'.$permohonan->id.'-'.time().'.pdf';
        Storage::disk('public')->put($namaFile, $pdf->output());

        $permohonan->update([
            'file_berita_acara' => $namaFile,
            'status' => 'Menunggu TTD Pengguna',
        ]);

        return redirect()->route('permohonanspasial.index')->with('success', 'Berita Acara berhasil diarsipkan dan dikirim ke pengguna untuk TTD.');
    }

    public function rejectPermohonan(Request $request, Permohonan $permohonan)
    {
        // 1. Otorisasi
        if (Auth::id() != $permohonan->penelaah_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        // 2. Validasi (alasan/catatan wajib diisi)
        $validated = $request->validate([
            'catatan_revisi' => 'required|string|min:10',
        ]);

        // 3. Update permohonan
        $permohonan->update([
            'status' => 'Revisi', // Set status ke 'Revisi'
            'catatan_revisi' => $validated['catatan_revisi'],
            'file_berita_acara' => null, // Hapus BA lama jika ada
        ]);

        // (Opsional: Kirim email notifikasi ke pengguna bahwa permohonan ditolak)

        return redirect()->route('permohonanspasial.index')
            ->with('success', 'Permohonan telah dikembalikan ke pengguna untuk revisi.');
    }

    /**
     * Staf (Penelaah atau Admin) menyelesaikan permohonan.
     */
    public function completePermohonan(Request $request, Permohonan $permohonan)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // === BUG 2 DIPERBAIKI: Izinkan Admin ATAU Penelaah yang ditugaskan ===
        if (! $user->hasRole('Admin IGT') && $user->id != $permohonan->penelaah_id) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        $validated = $request->validate([
            'file_data_final' => 'required|file|mimes:zip,rar|max:512000',
            'file_surat_balasan' => 'nullable|file|mimes:pdf|max:2048',
        ]);

        $finalDataPath = $request->file('file_data_final')->store('data_final', 'public');
        $suratBalasanPath = null;
        if ($request->hasFile('file_surat_balasan')) {
            $suratBalasanPath = $request->file('file_surat_balasan')->store('surat_balasan', 'public');
        }

        $zip = new ZipArchive;
        $zipFileName = 'paket_final/PAKET_PERMOHONAN_'.$permohonan->id.'_'.time().'.zip';
        $zipPath = storage_path('app/public/'.$zipFileName);
        Storage::disk('public')->makeDirectory('paket_final');

        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            $zip->addFile(Storage::disk('public')->path($finalDataPath), basename($finalDataPath));
            if ($suratBalasanPath) {
                $zip->addFile(Storage::disk('public')->path($suratBalasanPath), basename($suratBalasanPath));
            }
            $zip->close();
        }

        $permohonan->update([
            'file_data_final' => $finalDataPath,
            'file_surat_balasan' => $suratBalasanPath,
            'file_paket_final' => $zipFileName,
            'status' => 'Selesai',
        ]);

        return redirect()->route('permohonanspasial.index')
            ->with('success', 'Paket data final (ZIP) telah dibuat dan permohonan diselesaikan.');
    }

    // ===================================
    // === METHOD UNTUK PENGGUNA ===
    // ===================================

    public function myPermohonan()
    {
        $permohonans = Permohonan::where('user_id', Auth::id())
            ->with('detailPermohonan.dataIgt', 'survey')
            ->latest()
            ->paginate(10);

        return view('permohonanspasial.saya', compact('permohonans'));
    }

    public function generateAndDownloadBA(Permohonan $permohonan)
    {
        if ($permohonan->user_id != Auth::id()) {
            abort(403, 'Aksi tidak diizinkan.');
        }

        if ($permohonan->file_berita_acara) {
            return Storage::disk('public')->download($permohonan->file_berita_acara);
        }

        return redirect()->route('permohonanspasial.saya')
            ->with('error', 'Berita Acara belum dibuat oleh Penelaah.');
    }

    public function uploadBaTtd(Request $request, Permohonan $permohonan)
    {
        if ($permohonan->user_id != Auth::id()) {
            abort(403);
        }
        $validated = $request->validate([
            'file_ba_ttd' => 'required|file|mimes:pdf|max:2048',
        ]);
        $filePath = $request->file('file_ba_ttd')->store('ba_ttd', 'public');
        $permohonan->update([
            'file_ba_ttd' => $filePath,
            'status' => 'Menunggu Verifikasi Staf',
        ]);

        return back()->with('success', 'Berita Acara (TTD) berhasil di-upload.');
    }

    public function editRevisi(Permohonan $permohonan)
    {
        // Otorisasi: Pastikan ini milik user dan statusnya 'Revisi'
        if ($permohonan->user_id != Auth::id() || $permohonan->status != 'Revisi') {
            abort(403);
        }

        // Muat data detail yang sudah ada
        $permohonan->load('detailPermohonan');

        // Ambil data IGT yang sudah dipilih untuk ditampilkan
        $selectedIgtIds = $permohonan->detailPermohonan->pluck('daftar_igt_id');
        $selectedIgt = DataIgt::whereIn('id', $selectedIgtIds)->get();

        // Ambil semua opsi cakupan
        $cakupanOptions = $this->cakupanOptions;

        // Kirim data lama ke view 'create' (kita gunakan view yang sama)
        return view('permohonanspasial.revisi_edit', compact('permohonan', 'selectedIgt', 'cakupanOptions'));
    }

    /**
     * === METHOD REVISI 2: SIMPAN PERUBAHAN ===
     * Menyimpan data yang sudah direvisi oleh pengguna.
     */
    public function updateRevisi(Request $request, Permohonan $permohonan)
    {
        // 1. Otorisasi (Sudah Benar)
        if ($permohonan->user_id != Auth::id() || $permohonan->status != 'Revisi') {
            abort(403);
        }

        // 2. Validasi (Sudah Benar)
        $validated = $request->validate([
            'tipe_pemohon' => 'required|string|in:pemerintah,akademisi',
            'nama_pemohon' => 'required|string|max:255',
            'instansi' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|string|max:20',
            'nomor_surat' => 'required|string|max:255',
            'tanggal_surat' => 'required|date',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // Opsional
            'nip' => 'required_if:tipe_pemohon,pemerintah|nullable|string|max:50',
            'jabatan' => 'required_if:tipe_pemohon,pemerintah|nullable|string|max:255',
            'perihal' => 'nullable|string',
            'requested_data' => 'required|array|min:1',
            'requested_data.*.daftar_igt_id' => 'required|exists:data_igts,id',
            'requested_data.*.cakupan_wilayah' => 'required|string',
            'requested_data.*.keterangan' => 'nullable|string|max:255',
        ]);

        // 3. Handle upload file (Sudah Benar)
        $filePath = $permohonan->file_surat;
        if ($request->hasFile('file_surat')) {
            if ($filePath) {
                Storage::disk('public')->delete($filePath);
            }
            $filePath = $request->file('file_surat')->store('surat_permohonan', 'public');
        }

        // 4. Update data permohonan (INI PERUBAHANNYA)
        $permohonan->update([
            'tipe_pemohon' => $validated['tipe_pemohon'],
            'nama_pemohon' => $validated['nama_pemohon'],
            'nip' => $validated['nip'],
            'jabatan' => $validated['jabatan'],
            'instansi' => $validated['instansi'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'],
            'nomor_surat' => $validated['nomor_surat'],
            'tanggal_surat' => $validated['tanggal_surat'],
            'perihal' => $validated['perihal'],
            'file_surat' => $filePath,

            // --- LOGIKA DIPERBAIKI ---
            // Kembalikan status ke 'Diproses' agar kembali ke Penelaah
            'status' => 'Diproses',
            'catatan_revisi' => null, // Kosongkan catatan revisi
            // 'penelaah_id' TIDAK diubah, sehingga tetap milik penelaah yang lama.
            // --- AKHIR PERBAIKAN ---
        ]);

        // 5. Hapus detail lama dan masukkan detail baru (Sudah Benar)
        $permohonan->detailPermohonan()->delete();
        foreach ($validated['requested_data'] as $detail) {
            DetailPermohonan::create([
                'permohonan_id' => $permohonan->id,
                'daftar_igt_id' => $detail['daftar_igt_id'],
                'cakupan_wilayah' => $detail['cakupan_wilayah'],
                'keterangan' => $detail['keterangan'] ?? null,
            ]);
        }

        return redirect()->route('permohonanspasial.saya')
            ->with('success', 'Permohonan berhasil diperbarui dan dikirim kembali ke Penelaah.');
    }
}
