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
        Schema::table('permohonananalisis', function (Blueprint $table) {
            // Kita tambahkan kolom string setelah 'tanggal_surat' (sesuai urutan form)
            $table->string('tujuan_analisis')->nullable()->after('tanggal_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonananalisis', function (Blueprint $table) {
            $table->dropColumn('tujuan_analisis');
        });
    }
};
