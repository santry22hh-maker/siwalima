<?php

use App\Http\Controllers\JigDashboardController;
use App\Http\Controllers\KlarifikasiDashboardController;
// 1. GANTI AnalisisController menjadi LaporanDataController
use App\Http\Controllers\LaporanDataController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PermohonanAnalisisController;

/*
|--------------------------------------------------------------------------
| File Route (klarifikasi.php) DENGAN NAMA BARU
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
    Route::post('/klarifikasi/input', [KlarifikasiDashboardController::class, 'store'])->name('klarifikasi.store');

    // ===================================
    // 2. RUTE DATA LAPORAN (NAMA BARU)
    // ===================================
    // Ganti dari /analisis -> /data
    Route::get('/data', [LaporanDataController::class, 'index'])->name('data.list');
    // Ganti dari /analisis/data -> /data/json
    Route::get('/data/json', [LaporanDataController::class, 'getDataJson'])->name('data.json');
    // Ganti dari /analisis/{slug} -> /data/{slug}
    Route::get('/data/{slug}', [LaporanDataController::class, 'show'])->name('data.detail');
    //Ganti dari /laporans/{slug}/edit -> /data/{slug}/edit
    Route::get('/data/{slug}/edit', [LaporanDataController::class, 'edit'])->name('data.edit');
    // Ganti dari /laporans/{slug} -> /data/{slug}
    Route::put('/data/{slug}', [LaporanDataController::class, 'update'])->name('data.update');
    // Ganti dari /laporans/{slug} -> /data/{slug}
    Route::delete('/data/{slug}', [LaporanDataController::class, 'destroy'])->name('data.delete');
    // Ganti dari /analisis/{slug} -> /data/{slug}/analyze
    Route::post('/data/{slug}/analyze', [LaporanDataController::class, 'analyze'])->name('data.analyze');
    Route::controller(PermohonanAnalisisController::class)->group(function () {
        // Halaman tabel "Daftar Permohonan Saya" (GET)
        Route::get('/permohonananalisis', 'index')->name('permohonananalisis.index');
        // Halaman form "Ajukan Permohonan Baru" (GET)
        Route::get('/permohonananalisis/create', 'create')->name('permohonananalisis.create');
        // Proses simpan dari form "Ajukan Permohonan Baru" (POST)
        Route::post('/permohonananalisis', 'store')->name('permohonananalisis.store');
        // Endpoint AJAX untuk tabel "Daftar Permohonan Saya" (GET)
        Route::get('/permohonananalisis/data', 'getData')->name('permohonananalisis.data');
        // (Kita juga akan butuh show, edit, update, destroy nanti)
    });
});
// });
