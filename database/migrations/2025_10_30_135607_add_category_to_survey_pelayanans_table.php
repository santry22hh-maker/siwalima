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
            // Tambahkan kolom 'category' setelah 'permohonan_id'
            // Kita buat 'nullable' agar data lama tidak error
            $table->string('category')->nullable()->after('permohonan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_pelayanans', function (Blueprint $table) {
            $table->dropColumn('category');
        });
    }
};
