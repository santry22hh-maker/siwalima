<?php

use App\Http\Controllers\DataIgtController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JigDashboardController;
use App\Http\Controllers\KlarifikasiDashboardController;
use App\Http\Controllers\LaporanPenggunaanController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PenelaahController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\PermohonanSpasialController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatistikController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyPenggunaanController; // <-- HAPUS INI (Sudah diganti SurveyController)
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
// Route Halaman Arsip Berita
Route::get('/berita', [NewsController::class, 'index'])->name('news.index');

// Route Detail Berita (Persiapan untuk nanti)
Route::get('/berita/{slug}', [NewsController::class, 'show'])->name('news.show');
Route::get('/interaktifmap', function () {
    return view('mapinteraktif');
});

Route::get('/documents', [DocumentController::class, 'index']);

// --- Rute Publik (Guest) ---
Route::get('/dashboard', [HomeController::class, 'indexdashboard']
)->middleware(['auth', 'verified'])->name('dashboard');

// --- Rute yang Membutuhkan Login (Autentikasi) ---
Route::middleware(['auth', 'verified'])->group(function () {
    // ===================================
    // 1. RUTE PROFIL PENGGUNA
    // ===================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ===================================
    // 2. RUTE APLIKASI LAIN (JIG, KLARIFIKASI, ANALISIS)
    // ===================================
    Route::get('/jig/index', [JigDashboardController::class, 'index'])->name('jig.index');
    Route::get('/spasial', [JigDashboardController::class, 'showmap'])->name('jig.showmap');

    Route::get('/klarifikasi', [KlarifikasiDashboardController::class, 'index'])->name('klarifikasi.index');
    Route::get('/klarifikasi/statistik', [KlarifikasiDashboardController::class, 'statistik'])->name('klarifikasi.statistik');
    Route::get('/klarifikasi/input', [KlarifikasiDashboardController::class, 'input'])->name('klarifikasi.input');
    Route::post('/klarifikasi/input', [KlarifikasiDashboardController::class, 'store'])->name('klarifikasi.store');

    // ===================================
    // 3. ALUR KERJA KATALOG IGT
    // ===================================
    Route::get('/daftarigt', [DataIgtController::class, 'index'])->name('daftarigt.index');
    Route::get('/daftarigt/{daftarigt}', [DataIgtController::class, 'show'])->name('daftarigt.show');

    // ===================================
    // 4. ALUR KERJA PENGGUNA (PERMOHONAN, PENGADUAN, SURVEY)
    // ===================================
    Route::middleware(['role:Pengguna'])->group(function () {
        // Permohonan
        Route::get('/permohonan/tambah', [PermohonanSpasialController::class, 'create'])->name('permohonanspasial.create');
        Route::post('/permohonan', [PermohonanSpasialController::class, 'store'])->name('permohonanspasial.store');
        Route::get('/permohonan-saya', [PermohonanSpasialController::class, 'myPermohonan'])->name('permohonanspasial.saya');
        Route::get('/permohonan-saya/{permohonan}/generate-ba', [PermohonanSpasialController::class, 'generateAndDownloadBA'])->name('permohonanspasial.generateBA');
        Route::post('/permohonan-saya/{permohonan}/upload-ba-ttd', [PermohonanSpasialController::class, 'uploadBaTtd'])->name('permohonanspasial.uploadBaTtd');
        Route::get('/permohonan/{permohonan}/revisi', [PermohonanSpasialController::class, 'editRevisi'])->name('permohonanspasial.revisi.edit');
        Route::put('/permohonan/{permohonan}/revisi', [PermohonanSpasialController::class, 'updateRevisi'])->name('permohonanspasial.revisi.update');

        // Laporan
        Route::get('/laporan-penggunaan', [LaporanPenggunaanController::class, 'index'])->name('laporanpenggunaan.index');
        Route::post('/laporan-penggunaan/{permohonan}', [LaporanPenggunaanController::class, 'store'])->name('laporanpenggunaan.store');

        // Survey (Pengguna IGT)
        Route::get('/survey', [SurveyController::class, 'index'])->name('survey.index');
        Route::post('/survey', [SurveyController::class, 'store'])->name('survey.store');

        // Pengaduan
        Route::get('/layanan-pengaduan', [PengaduanController::class, 'index'])->name('pengaduan.index');
        Route::post('/layanan-pengaduan', [PengaduanController::class, 'store'])->name('pengaduan.store');
        Route::get('/pengaduan-saya', [PengaduanController::class, 'myComplaints'])->name('pengaduan.saya');
        Route::post('/pengaduan-saya/{pengaduan}/cancel', [PengaduanController::class, 'cancelComplaint'])->name('pengaduan.cancel');
    });

    // ===================================
    // 5. ALUR KERJA STAF (ADMIN & PENELAAH)
    // ===================================
    Route::middleware(['role:Admin IGT|Admin Klarifikasi|Penelaah IGT|Penelaah Klarifikasi'])->prefix('admin')->group(function () {
        // Manajemen Katalog IGT (Akses bersama)
        Route::post('/daftarigt', [DataIgtController::class, 'store'])->name('daftarigt.store');
        Route::get('/daftarigt/{daftarigt}/edit', [DataIgtController::class, 'edit'])->name('daftarigt.edit');
        Route::put('/daftarigt/{daftarigt}', [DataIgtController::class, 'update'])->name('daftarigt.update');
        Route::delete('/daftarigt/{daftarigt}', [DataIgtController::class, 'destroy'])->name('daftarigt.destroy');
        Route::get('/daftarigt/create', [DataIgtController::class, 'create'])->name('daftarigt.create');

        // Manajemen Permohonan (Akses bersama)
        Route::get('/daftar-permohonan', [PermohonanSpasialController::class, 'index'])->name('permohonanspasial.index');
        Route::get('/permohonan/{permohonan}', [PermohonanSpasialController::class, 'show'])->name('permohonanspasial.show');
        Route::post('/permohonan/{permohonan}/complete', [PermohonanSpasialController::class, 'completePermohonan'])->name('permohonanspasial.complete');
        Route::get('/permohonan/{permohonan}/edit', [PermohonanSpasialController::class, 'edit'])->name('permohonanspasial.edit');
        Route::put('/permohonan/{permohonan}', [PermohonanSpasialController::class, 'update'])->name('permohonanspasial.update');
        Route::delete('/permohonan/{permohonan}', [PermohonanSpasialController::class, 'destroy'])->name('permohonanspasial.destroy');

        // Manajemen Laporan & Survey
        Route::get('/review-laporan-penggunaan', [LaporanPenggunaanController::class, 'reviewIndex'])->name('laporanpenggunaan.review');

        // --- REKAP SURVEY IGT (DIPERBAIKI) ---
        // Menggunakan SurveyController, bukan SurveyPenggunaanController
        Route::get('/survey-igt', [SurveyController::class, 'rekapIgt'])->name('survey.rekap.igt');
        Route::get('/survey-igt/data', [SurveyController::class, 'getDataIgt'])->name('survey.rekap.igt.data');
        Route::get('/survey-igt/export-csv', [SurveyController::class, 'exportSurveyIgt'])->name('survey.rekap.igt.export');

        // (Rute lama 'surveypenggunaan.index' kita hapus atau redirect ke 'survey.rekap.igt')
        Route::get('/monitoring-tunggakan', [SurveyPenggunaanController::class, 'index'])->name('surveypenggunaan.index');
        Route::get('/monitoring-tunggakan/data', [SurveyPenggunaanController::class, 'getData'])->name('surveypenggunaan.data');

        // Manajemen Pengaduan (Akses bersama)
        Route::get('/daftar-pengaduan', [PengaduanController::class, 'list'])->name('pengaduan.list');
        Route::get('/pengaduan/{pengaduan}', [PengaduanController::class, 'show'])->name('pengaduan.show');

        // Halaman Statistik (Akses bersama)
        Route::get('/statistik/igt', [StatistikController::class, 'igt'])->name('statistik.igt');
        Route::get('/statistik/survey', [StatistikController::class, 'survey'])->name('statistik.survey');
        Route::get('/statistik/pengaduan', [StatistikController::class, 'pengaduan'])->name('statistik.pengaduan');

        // Manajemen Pengguna (Akses bersama)
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('penelaah', [PenelaahController::class, 'index'])->name('penelaah.index');
    });

    // --- Rute Khusus Penelaah (Terpisah) ---
    Route::middleware(['role:Penelaah IGT'])->prefix('admin')->group(function () {
        // Permohonan
        Route::get('/permohonan/{permohonan}/editor-ba', [PermohonanSpasialController::class, 'showEditorBA'])->name('permohonanspasial.showEditorBA');
        Route::post('/permohonan/{permohonan}/generate-ba-final', [PermohonanSpasialController::class, 'generateBAFromEditor'])->name('permohonanspasial.generateBAFromEditor');
        Route::post('/permohonan/{permohonan}/reject', [PermohonanSpasialController::class, 'rejectPermohonan'])->name('permohonanspasial.reject');
        // Pengaduan
        Route::post('/pengaduan/{pengaduan}/submit-review', [PengaduanController::class, 'submitReview'])->name('pengaduan.submitReview');
    });

    // --- Rute Khusus Admin (Terpisah) ---
    Route::middleware(['role:Admin IGT|Admin Klarifikasi'])->prefix('admin')->group(function () {
        // Permohonan
        Route::post('/permohonan/{permohonan}/assign', [PermohonanSpasialController::class, 'assign'])->name('permohonanspasial.assign');
        // Pengaduan
        Route::post('/pengaduan/{pengaduan}/approve', [PengaduanController::class, 'approve'])->name('pengaduan.approve');
        Route::post('/pengaduan/{pengaduan}/reject', [PengaduanController::class, 'reject'])->name('pengaduan.reject');

        // --- TAMBAHAN: Manajemen Penelaah ---
        Route::get('/penelaah/data', [PenelaahController::class, 'getData'])->name('penelaah.data');
        Route::post('/penelaah/{user}/toggle', [PenelaahController::class, 'toggleRole'])->name('penelaah.toggleRole');
        Route::resource('penelaah', PenelaahController::class);

        // Manajemen User
        Route::resource('users', UserController::class)->except(['index']);
    });

});

require __DIR__.'/klarifikasi.php';
require __DIR__.'/auth.php';
