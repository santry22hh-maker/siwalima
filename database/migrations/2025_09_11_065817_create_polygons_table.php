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
        Schema::create('polygons', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nama_pemohon')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('kabupaten')->nullable();
            $table->json('coordinates');
            $table->string('shapefile_path')->nullable();
            $table->string('geojson_path')->nullable();
            $table->string('groupid')->nullable();
            $table->json('photo_paths')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('polygons');
    }
};
