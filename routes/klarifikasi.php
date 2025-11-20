<?php

use App\Http\Controllers\Adminklarifikasi\PenelaahKlarifikasiController;
use App\Http\Controllers\Adminklarifikasi\PermohonanController;
use App\Http\Controllers\Adminklarifikasi\StatistikKlarifikasiController;
use App\Http\Controllers\Adminklarifikasi\UserKlarifikasiController;
use App\Http\Controllers\KlarifikasiDashboardController;
use App\Http\Controllers\LaporanDataController;
use App\Http\Controllers\PengaduanKlarifikasiController;
// --- TAMBAHKAN CONTROLLER ADMIN YANG BARU ---
use App\Http\Controllers\PermohonanAnalisisController;
use App\Http\Controllers\PusatInformasiKlarifikasiController;
use App\Http\Controllers\SurveyPelayananController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| File Route (klarifikasi.php)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(
    function () {
        // ===================================
        // 1. RUTE KLARIFIKASI (Input Data)
        // ===================================
        Route::get('/klarifikasi', [KlarifikasiDashboardController::class, 'index'])->name('klarifikasi.index');
        Route::get('/klarifikasi/statistik', [KlarifikasiDashboardController::class, 'statistik'])->name('klarifikasi.statistik');
        Route::get('/klarifikasi/input', [KlarifikasiDashboardController::class, 'input'])->name('klarifikasi.input');
        // Route::post('/klarifikasi/input', [KlarifikasiDashboardController::class, 'store'])->name('klarifikasi.store');
        Route::post('/klarifikasi/proses-analisis', [KlarifikasiDashboardController::class, 'prosesAnalisis'])
            ->name('klarifikasi.proses');

        // ===================================
        // 2. RUTE DATA LAPORAN (NAMA BARU)
        // ===================================
        Route::get('/data', [LaporanDataController::class, 'index'])->name('data.list');
        Route::get('/data/json', [LaporanDataController::class, 'getDataJson'])->name('data.json');
        Route::get('/data/{slug}', [LaporanDataController::class, 'show'])->name('data.detail');
        Route::get('/data/{slug}/edit', [LaporanDataController::class, 'edit'])->name('data.edit');
        Route::put('/data/{slug}', [LaporanDataController::class, 'update'])->name('data.update');
        Route::delete('/data/{slug}', [LaporanDataController::class, 'destroy'])->name('data.delete');
        Route::post('/data/{slug}/analyze', [LaporanDataController::class, 'analyze'])->name('data.analyze');

        // ===================================
        // 3. RUTE PERMOHONAN RESMI (PENGGUNA)
        // ===================================
        Route::get('/permohonananalisis/create', [PermohonanAnalisisController::class, 'create'])->name('permohonananalisis.create');
        Route::get('permohonananalisis/data', [PermohonanAnalisisController::class, 'getData'])->name('permohonananalisis.data');
        Route::resource('permohonananalisis', PermohonanAnalisisController::class);
        // ===================================
        // 4. RUTE PENGADUAN KLARIFIKASI (PENGGUNA)
        // ===================================
        Route::prefix('pengaduan-klarifikasi')->name('pengaduan.klarifikasi.')->group(function () {
            Route::get('/', [PengaduanKlarifikasiController::class, 'index'])->name('index');
            Route::get('/create', [PengaduanKlarifikasiController::class, 'create'])->name('create');
            Route::post('/', [PengaduanKlarifikasiController::class, 'store'])->name('store');
            Route::delete('/{id}', [PengaduanKlarifikasiController::class, 'destroy'])->name('destroy');
        });

        // ===================================
        // 5. RUTE SURVEY KLARIFIKASI (PENGGUNA)
        // ===================================
        Route::prefix('surveyklarifikasi')->name('surveyklarifikasi.')->group(function () {
            Route::get('/', [SurveyPelayananController::class, 'index'])->name('index');

            // tambahkan parameter slug di sini
            Route::get('/isisurvey/{permohonan}', [SurveyPelayananController::class, 'create'])
                ->name('create');

            Route::post('/', [SurveyPelayananController::class, 'store'])->name('store');
        });

        // ===================================
        // 6. RUTE PUSAT INFORMASI (PENGGUNA)
        // ===================================
        Route::prefix('pusat-informasi')->name('info.')->group(function () {
            Route::get('/panduan', [PusatInformasiKlarifikasiController::class, 'panduan'])->name('panduan');
            Route::get('/sop', [PusatInformasiKlarifikasiController::class, 'sop'])->name('sop');
            Route::get('/kontak', [PusatInformasiKlarifikasiController::class, 'kontak'])->name('kontak');
        });

        // ===================================
        // 7. RUTE ADMIN KLARIFIKASI (BACKEND)
        // ===================================
        Route::middleware(['permission:access klarifikasi backend'])
            ->prefix('adminklarifikasi')
            ->name('adminklarifikasi.') // <-- Menggunakan titik
            ->group(function () {

                // Dashboard Permohonan
                Route::get('/permohonan', [PermohonanController::class, 'index'])->name('permohonan.index');
                Route::get('/permohonan/data', [PermohonanController::class, 'getData'])->name('permohonan.data');
                Route::get('/permohonan/{slug}', [PermohonanController::class, 'show'])->name('permohonan.show');
                Route::post('/permohonan/{slug}/assign', [PermohonanController::class, 'assign'])->name('permohonan.assign');
                Route::post('/permohonan/{slug}/complete', [PermohonanController::class, 'complete'])->name('permohonan.complete');
                Route::post('/permohonan/{slug}/revert', [PermohonanController::class, 'revert'])->name('permohonan.revert');
                Route::post('/permohonan/{slug}/reject', [PermohonanController::class, 'reject'])->name('permohonan.reject');
                // --- AKHIR PERBAIKAN ---

                // --- TAMBAHAN: Manajemen Penelaah ---
                Route::get('/penelaah', [PenelaahKlarifikasiController::class, 'index'])->name('penelaah.index');
                Route::get('/penelaah/data', [PenelaahKlarifikasiController::class, 'getData'])->name('penelaah.data');
                Route::get('/penelaah/create', [PenelaahKlarifikasiController::class, 'create'])->name('penelaah.create');
                Route::post('/penelaah/{user}/toggle', [PenelaahKlarifikasiController::class, 'toggleRole'])->name('penelaah.toggleRole');

                // --- TAMBAHAN: Manajemen Pengaduan Klarifikasi ---
                Route::get('/pengaduan', [PengaduanKlarifikasiController::class, 'adminIndex'])->name('pengaduan.index');
                Route::get('/pengaduan/data', [PengaduanKlarifikasiController::class, 'adminGetData'])->name('pengaduan.data');
                Route::get('/pengaduan/{kode_pelacakan}', [PengaduanKlarifikasiController::class, 'adminShow'])->name('pengaduan.show');
                Route::post('/pengaduan/{kode_pelacakan}/assign', [PengaduanKlarifikasiController::class, 'adminAssign'])->name('pengaduan.assign');
                Route::post('/pengaduan/{kode_pelacakan}/submit-review', [PengaduanKlarifikasiController::class, 'adminSubmitReview'])->name('pengaduan.submitReview');
                Route::post('/pengaduan/{kode_pelacakan}/approve', [PengaduanKlarifikasiController::class, 'adminApprove'])->name('pengaduan.approve');
                Route::post('/pengaduan/{kode_pelacakan}/reject-review', [PengaduanKlarifikasiController::class, 'adminRejectReview'])->name('pengaduan.rejectReview');

                Route::get('/survey-rekap', [SurveyPelayananController::class, 'rekap'])->name('survey.rekap');
                Route::get('/survey-rekap/data', [SurveyPelayananController::class, 'getData'])->name('survey.rekap.data');
                Route::get('/survey-rekap/export-csv', [SurveyPelayananController::class, 'exportSurvey'])->name('survey.rekap.export');

                Route::prefix('statistik')->name('statistik.')->group(function () {
                    Route::get('/permohonan', [StatistikKlarifikasiController::class, 'permohonan'])->name('permohonan');
                    Route::get('/pengaduan', [StatistikKlarifikasiController::class, 'pengaduan'])->name('pengaduan');
                    Route::get('/survey', [StatistikKlarifikasiController::class, 'survey'])->name('survey');

                });

                Route::resource('/pengguna', UserKlarifikasiController::class);
            });
    }
);
