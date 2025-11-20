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
        Schema::table('survey_pelayanans', function (Blueprint $table) {
            // Tambahkan kolom user_id setelah 'id'
            // 'nullable()' agar data lama Anda tidak error
            // 'constrained()' akan otomatis link ke tabel 'users'
            // 'onDelete('set null')' berarti jika user dihapus, surveinya tetap ada tapi user_id-nya jadi null
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_pelayanans', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
