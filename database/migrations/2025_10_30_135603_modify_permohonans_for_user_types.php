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
        Schema::table('permohonans', function (Blueprint $table) {
            // 1. Tambahkan kolom baru untuk menyimpan tipe pemohon
            $table->string('tipe_pemohon')->after('status')->nullable();

            // 2. Ubah kolom NIP dan Jabatan agar boleh kosong (nullable)
            // PASTIKAN Anda menginstal 'doctrine/dbal'
            // ( jalankan: composer require doctrine/dbal )
            $table->string('nip', 50)->nullable()->change();
            $table->string('jabatan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            //
        });
    }
};
