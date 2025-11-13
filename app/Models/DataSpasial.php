<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataSpasial extends Model
{
    use HasFactory;

    /**
     * Tentukan nama tabel secara eksplisit (karena 'data_spasials' adalah jamak)
     */
    protected $table = 'data_spasials';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'permohonananalisis_id',
        'nama_areal',
        'kabupaten',
        'coordinates', // Sebaiknya gunakan 'geometry' jika Anda pakai PostGIS
        'geojson_path',
        'shapefile_path',
        'photo_paths',
        'source_type',
    ];

    /**
     * Memberitahu Laravel untuk otomatis mengubah kolom JSON menjadi array.
     */
    protected $casts = [
        'coordinates' => 'array',
        'photo_paths' => 'array',
    ];

    /**
     * Relasi: Satu Data Spasial DIMILIKI OLEH SATU Permohonan.
     */
    public function permohonanAnalisis()
    {
        return $this->belongsTo(PermohonanAnalisis::class, 'permohonananalisis_id');
    }
}
