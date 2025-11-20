<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyPelayanan extends Model
{
    use HasFactory;

    /**
     * PERBAIKAN:
     * Tentukan nama tabel secara eksplisit agar cocok dengan database Anda.
     */
    protected $table = 'survey_pelayanans';

    /**
     * Izinkan mass assignment untuk semua kolom.
     */
    protected $guarded = [];

    /**
     * Cast kolom 'kebutuhan_pelayanan' sebagai array.
     */
    protected $casts = [
        'kebutuhan_pelayanan' => 'array',
    ];

    /**
     * Relasi ke Permohonan (jika digunakan nanti).
     */
    public function permohonan()
    {
        return $this->belongsTo(PermohonanAnalisis::class, 'permohonan_id');
    }

    /**
     * Relasi ke User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
