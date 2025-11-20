<?php

namespace App\Providers;

use App\Models\Pengaduan;
use App\Models\Permohonan;
use App\Models\PermohonanAnalisis;
use App\Observers\PengaduanObserver;
use App\Observers\PermohonanAnalisisObserver;
use App\Observers\PermohonanSpasialObserver; // Model IGT
use Illuminate\Support\ServiceProvider; // Observer IGT

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PermohonanAnalisis::observe(PermohonanAnalisisObserver::class);
        Pengaduan::observe(PengaduanObserver::class);
        Permohonan::observe(PermohonanSpasialObserver::class);
    }
}
