<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Impor

class DetailPermohonan extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'detail_permohonans';

    protected $fillable = [
        'permohonan_id',
        'daftar_igt_id',
        'cakupan_wilayah',
        'periode_update', // <-- TAMBAHKAN INI
        'format_data',    // <-- TAMBAHKAN INI
    ];

    // TAMBAHKAN RELASI INI
    public function dataIgt(): BelongsTo
    {
        return $this->belongsTo(DataIgt::class, 'daftar_igt_id');
    }
}