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
            // Tambahkan kolom untuk data dari form 'klarifikasi.input'
            $table->string('form_userid')->nullable()->after('keterangan');
            $table->string('form_groupid')->nullable()->after('form_userid');
        });
    }

    public function down(): void
    {
        Schema::table('permohonananalisis', function (Blueprint $table) {
            $table->dropColumn(['form_userid', 'form_groupid']);
        });
    }
};
