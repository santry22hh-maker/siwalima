<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // <-- Penting untuk UUID dan Random String

class PermohonanAnalisis extends Model
{
    use HasFactory;

    protected $table = 'permohonananalisis';

    protected $fillable = [
        'user_id',
        'slug',
        'kode_pelacakan',
        'tipe',
        'status',
        'penelaah_id',
        'catatan_penelaah',
        'file_surat_balasan_path',
        'file_paket_final_path',
        'status',
        'nama_pemohon',
        'hp_pemohon',
        'email_pemohon',
        'nomor_surat',
        'tanggal_surat',
        'tujuan_analisis',
        'perihal_surat',
        'file_surat_path',
        'keterangan',
        'form_userid',
        'form_groupid',
    ];

    /**
     * Boot model untuk membuat slug DAN kode pelacakan secara otomatis.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($permohonan) {
            // 1. Buat Slug (seperti sebelumnya)
            if (empty($permohonan->slug)) {
                $permohonan->slug = (string) Str::uuid();
            }

            // 2. Buat Kode Pelacakan HANYA jika tipe 'RESMI'
            if ($permohonan->tipe === 'RESMI') {
                // Format: PAR-YYMMDD-XXXXXX (Contoh: PAR-251115-A8BC2E)
                $prefix = 'PAR';
                $date = now()->format('ymd');
                $random = strtoupper(Str::random(6));

                // Pastikan unik (meskipun kemungkinannya sangat kecil)
                while (static::where('kode_pelacakan', $prefix.'-'.$date.'-'.$random)->exists()) {
                    $random = strtoupper(Str::random(6));
                }

                $permohonan->kode_pelacakan = $prefix.'-'.$date.'-'.$random;
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

    public function penelaah()
    {
        // Asumsi: Tabel 'permohonananalisis' Anda memiliki kolom 'penelaah_id'
        return $this->belongsTo(User::class, 'penelaah_id');
    }

    public function survey()
    {
        return $this->hasOne(SurveyPelayanan::class, 'permohonan_id');
    }
}
