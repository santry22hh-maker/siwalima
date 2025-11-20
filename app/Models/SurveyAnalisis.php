<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyAnalisis extends Model
{
    use HasFactory;

    protected $table = 'survey_analisis';

    protected $fillable = [
        'permohonananalisis_id',
        'category',
        'user_id',
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

    protected $casts = [
        'kebutuhan_pelayanan' => 'array',
    ];

    public function permohonanAnalisis()
    {
        return $this->belongsTo(PermohonanAnalisis::class, 'permohonananalisis_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function surveyAnalisis()
    {
        return $this->hasOne(SurveyAnalisis::class, 'permohonananalisis_id');
    }
}
