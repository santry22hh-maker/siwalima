<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pengaduan extends Model
{
    use HasFactory;

    // Kolom-kolom di tabel Anda (berdasarkan gambar)
    protected $fillable = [
        'user_id',
        'penelaah_id',
        'category',
        'kode_pelacakan', // <-- Tambahkan ini
        'nama',
        'instansi',
        'tujuan',
        'email',
        'pesan',
        'file',
        'status',
        'catatan_admin',
        'balasan_penelaah',
    ];

    // Status awal yang akan diterapkan oleh database
    protected $attributes = [
        'status' => 'Baru',
    ];

    /**
     * Boot function untuk membuat kode pelacakan secara otomatis.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->kode_pelacakan)) {
                $model->kode_pelacakan = 'PGD-'.time().Str::random(5);
            }
        });
    }

    /**
     * Beri tahu Laravel untuk menggunakan KODE_PELACAKAN di URL, bukan ID.
     */
    public function getRouteKeyName()
    {
        return 'kode_pelacakan';
    }

    /**
     * Relasi ke User (Pelapor).
     */
    public function user()
    {
        // Asumsi tabel 'pengaduans' punya kolom 'user_id'
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke User (Penelaah yang ditugaskan).
     */
    public function penelaah()
    {
        // Asumsi tabel 'pengaduans' punya kolom 'penelaah_id'
        return $this->belongsTo(User::class, 'penelaah_id');
    }
}
