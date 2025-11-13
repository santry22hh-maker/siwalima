<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Laporan extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'polygon_id',
        'status',
        'keterangan',
    ];

    protected static function boot()
    {
        parent::boot();

        // Saat 'membuat' Laporan baru...
        static::creating(function ($laporan) {
            // Jika slug belum diatur, buatkan satu
            if (empty($laporan->slug)) {
                // Kita gunakan UUID (string acak unik) karena laporan tidak punya 'judul'
                // Ini akan menghasilkan sesuatu like: "a1b2c3d4-..."
                $laporan->slug = (string) Str::uuid();
            }
        });
    }

    /**
     * Mendapatkan poligon yang terkait dengan laporan.
     */
    public function polygon()
    {
        return $this->belongsTo(Polygon::class);
    }
}
