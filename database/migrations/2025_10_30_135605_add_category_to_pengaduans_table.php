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
        Schema::table('pengaduans', function (Blueprint $table) {
            // Tambahkan kolom 'category' setelah 'user_id'
            // Kita gunakan ENUM untuk membatasi nilai yang masuk
            $table->enum('category', ['IGT', 'KLARIFIKASI', 'UMUM'])->default('UMUM')->after('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
