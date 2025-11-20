<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('survey_analisis', function (Blueprint $table) {
            $table->id();

            // Foreign key ke permohonananalisis
            $table->unsignedBigInteger('permohonananalisis_id')->nullable();
            $table->foreign('permohonananalisis_id')
                ->references('id')
                ->on('permohonananalisis')
                ->onDelete('set null');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('category')->nullable();

            $table->string('nama_lengkap');
            $table->string('jenis_kelamin');
            $table->string('pekerjaan');
            $table->string('instansi');
            $table->string('email');
            $table->string('telepon');
            $table->date('tanggal_pelayanan');

            $table->json('kebutuhan_pelayanan')->nullable();
            $table->string('tujuan_penggunaan');
            $table->string('pernah_layanan');
            $table->string('info_layanan');
            $table->string('cara_layanan');

            // Semua pertanyaan (copy dari survey_pelayanans)
            $table->string('q_petugas_ditemui');
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

            $table->text('kritik_saran')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
