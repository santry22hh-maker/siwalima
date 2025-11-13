<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyPelayanan extends Model
{
    use HasFactory;

    /**
     * Tentukan kolom yang bisa diisi.
     */
    protected $fillable = [
        'permohonan_id',
        'nama_lengkap',
        'jenis_kelamin',
        'pekerjaan',
        'instansi',
        'email',
        'telepon',
        'tanggal_pelayanan',
        'kebutuhan_pelayanan',
        'tujuan_penggunaan',
        'pernah_layanan',
        'info_layanan',
        'cara_layanan',
        'q_petugas_ditemui',
        'q_petugas_dihubungi',
        'q_kompetensi',
        'q_kesopanan',
        'q_info_jelas',
        'q_syarat_sesuai',
        'q_syarat_wajar',
        'q_prosedur_mudah',
        'q_waktu_cepat',
        'q_biaya',
        'q_hasil_sesuai',
        'q_kualitas_rekaman',
        'q_layanan_keseluruhan',
        'q_sarpras',
        'q_penanganan_pengaduan',
        'kritik_saran',
    ];

    /**
     * Konversi 'kebutuhan_pelayanan' dari JSON ke array dan sebaliknya.
     */
    protected $casts = [
        'kebutuhan_pelayanan' => 'array',
    ];
}
