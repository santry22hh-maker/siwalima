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
            // Tambahkan kolom 'penelaah_id' setelah 'user_id'
            // 'nullable()' agar data lama Anda tidak error
            // 'constrained('users')' akan menghubungkannya ke tabel 'users'
            // 'onDelete('set null')' berarti jika Penelaah dihapus, permohonan tetap ada
            $table->foreignId('penelaah_id')
                ->nullable()
                ->after('user_id')
                ->constrained('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonananalisis', function (Blueprint $table) {
            // Ini adalah kebalikan dari 'up()'
            $table->dropForeign(['penelaah_id']);
            $table->dropColumn('penelaah_id');
        });
    }
};
