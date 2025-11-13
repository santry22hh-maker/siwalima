<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('survey_pelayanans', function (Blueprint $table) {
            $table->id();

            // === Halaman 1: Informasi Personal ===
            $table->string('nama_lengkap');
            $table->string('jenis_kelamin');
            $table->string('pekerjaan');
            $table->string('instansi');
            $table->string('email');
            $table->string('telepon');

            // === Halaman 2: Informasi Layanan ===
            $table->date('tanggal_pelayanan');
            $table->json('kebutuhan_pelayanan'); // Menyimpan array dari checkbox
            $table->string('tujuan_penggunaan');

            // === Halaman 3: Pendapat Responden (Q10 - Q27) ===
            $table->string('pernah_layanan'); // Sudah / Belum
            $table->string('info_layanan');
            $table->string('cara_layanan');
            $table->string('q_petugas_ditemui'); // (Tidak Mudah, Kurang Mudah, ...)
            $table->string('q_petugas_dihubungi');
            $table->string('q_kompetensi');
            $table->string('q_kesopanan');
            $table->string('q_info_jelas');
            $table->string('q_syarat_sesuai');
            $table->string('q_syarat_wajar');
            $table->string('q_prosedur_mudah');
            $table->string('q_waktu_cepat');
            $table->string('q_biaya');
            $table->string('q_hasil_sesuai');
            $table->string('q_kualitas_rekaman');
            $table->string('q_layanan_keseluruhan');
            $table->string('q_sarpras');
            $table->string('q_penanganan_pengaduan');

            // === Halaman 4: Kritik & Saran ===
            $table->text('kritik_saran')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_pelayanans');
    }
};
