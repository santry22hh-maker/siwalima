<?php 

// database/migrations/xxxx_xx_xx_xxxxxx_create_permohonan_table.php

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
        Schema::create('permohonans', function (Blueprint $table) {
            $table->id(); // Kolom ID auto-increment (Primary Key)

            // Data Pemohon
            $table->string('nama_pemohon');
            $table->string('nip', 50);
            $table->string('jabatan');
            $table->string('instansi');
            $table->string('email');
            $table->string('no_hp', 20);

            // Data Surat
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->text('perihal')->nullable(); // Boleh kosong
            $table->string('file_surat'); // Untuk menyimpan path/nama file

            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonans');
    }
};