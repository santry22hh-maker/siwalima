<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany; 
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Permohonan extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     * (Opsional, jika nama tabel Anda 'permohonans')
     */
    protected $table = 'permohonans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Kolom dari migrasi perbaikan
        'user_id',
        'status',
        'catatan_revisi',
        'tipe_pemohon',

        // Kolom dari migrasi asli Anda
        'nama_pemohon',
        'nip',
        'jabatan',
        'instansi',
        'email',
        'no_hp',
        'nomor_surat',
        'tanggal_surat',
        'perihal',
        'file_surat',
        'file_berita_acara',
        'file_berita_acara',
        'file_ba_ttd',
        'file_data_final',
        'file_surat_balasan',
        'penelaah_id',
        'file_paket_final',
        'laporan_penggunaan_path',

        // Kolom 'cakupandata' dan 'jenis_data' dihapus 
        // karena kita ganti dengan pivot table.
    ];

    /**
     * Relasi Many-to-Many ke model DataIgt (tabel daftar_igts).
     */
    public function dataIgts(): BelongsToMany
    {
        // Sesuaikan nama pivot table jika Anda membuatnya berbeda
        return $this->belongsToMany(DataIgt::class, 'daftar_igt_permohonan', 'permohonan_id', 'daftar_igt_id');
    }


    public function detailPermohonan(): HasMany
    {
        return $this->hasMany(DetailPermohonan::class, 'permohonan_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    // Relasi ke Penelaah yang ditugaskan
    public function penelaah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penelaah_id');
    }
    
    public function survey(): HasOne
    {
        return $this->hasOne(SurveyPelayanan::class, 'permohonan_id');
    }
}
