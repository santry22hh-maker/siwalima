<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengaduan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'penelaah_id', // <-- TAMBAHKAN
        'nama',
        'instansi',
        'email',
        'pesan',
        'file',
        'status',
        'catatan_admin',
        'balasan_penelaah', // <-- TAMBAHKAN
    ];

    // Relasi ke user yang mengirim (jika login)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // --- TAMBAHKAN RELASI BARU INI ---
    // Relasi ke user Penelaah yang menangani
    public function penelaah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penelaah_id');
    }
}
