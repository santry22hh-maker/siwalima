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
        Schema::create('data_igt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permohonan_id');
            $table->string('jenis_data');
            $table->string('cakupan');
            $table->string('periode_update');
            $table->string('format_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_igt');
    }
};