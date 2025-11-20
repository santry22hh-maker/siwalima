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
            // Kolom untuk catatan dari Penelaah (opsional)
            $table->text('catatan_penelaah')->nullable()->after('penelaah_id');
            // Kolom untuk file Surat Balasan (PDF)
            $table->string('file_surat_balasan_path')->nullable()->after('catatan_penelaah');
            // Kolom untuk paket data final (ZIP)
            $table->string('file_paket_final_path')->nullable()->after('file_surat_balasan_path');
        });
    }

    public function down(): void
    {
        Schema::table('permohonananalisis', function (Blueprint $table) {
            $table->dropColumn(['catatan_penelaah', 'file_surat_balasan_path', 'file_paket_final_path']);
        });
    }
};
