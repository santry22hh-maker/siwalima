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
        Schema::create('permohonananalisis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // ID Pengguna yang login
            $table->uuid('slug')->unique(); // Untuk URL yang aman

            // Kolom Kunci untuk membedakan alur
            $table->string('tipe'); // Tipe: 'MANDIRI' atau 'RESMI'
            $table->string('status'); // Status: 'Draft', 'Diajukan', 'Diproses', 'Selesai', 'Ditolak'

            // Data Pemohon (untuk 'RESMI')
            $table->string('nama_pemohon')->nullable();
            $table->string('hp_pemohon')->nullable();
            $table->string('email_pemohon')->nullable();

            // Data Surat (untuk 'RESMI')
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->text('perihal_surat')->nullable();
            $table->string('file_surat_path')->nullable(); // Path ke PDF surat

            // Keterangan umum
            $table->text('keterangan')->nullable(); // Tujuan/catatan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonananalisis');
    }
};
