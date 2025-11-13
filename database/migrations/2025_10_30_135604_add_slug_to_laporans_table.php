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
        Schema::table('laporans', function (Blueprint $table) {
            // Tambahkan kolom slug setelah 'polygon_id'
            // unique() -> Memastikan tidak ada slug yang sama
            // nullable() -> Agar data lama Anda tidak error
            $table->string('slug')->unique()->nullable()->after('polygon_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporans', function (Blueprint $table) {
            //
        });
    }
};
