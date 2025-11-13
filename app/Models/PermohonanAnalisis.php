<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <-- Penting untuk UUID

class PermohonanAnalisis extends Model
{
    use HasFactory;

    /**
     * Tentukan nama tabel secara eksplisit (karena 'permohonananalisis' adalah jamak)
     */
    protected $table = 'permohonananalisis';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'user_id',
        'slug',
        'tipe',
        'status',
        'nama_pemohon',
        'hp_pemohon',
        'email_pemohon',
        'nomor_surat',
        'tanggal_surat',
        'perihal_surat',
        'file_surat_path',
        'keterangan',
        'form_userid',  
        'form_groupid'  
    ];

    /**
     * Boot model untuk membuat slug secara otomatis.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($permohonan) {
            // Buat slug unik menggunakan UUID
            if (empty($permohonan->slug)) {
                $permohonan->slug = (string) Str::uuid();
            }
        });
    }

    /**
     * Memberitahu Laravel untuk menggunakan 'slug' untuk URL, bukan 'id'.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Relasi: Satu Permohonan HANYA MEMILIKI SATU Data Spasial.
     */
    public function dataSpasial()
    {
        return $this->hasOne(DataSpasial::class, 'permohonananalisis_id');
    }

    /**
     * Relasi: Satu Permohonan DIMILIKI OLEH SATU User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
