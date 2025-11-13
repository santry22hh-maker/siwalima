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
        Schema::create('data_spasials', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel utama
            $table->foreignId('permohonananalisis_id')
                ->constrained('permohonananalisis')
                ->onDelete('cascade'); // Jika permohonan dihapus, data spasialnya ikut terhapus

            // Data dari form spasial
            $table->string('nama_areal'); // Ini adalah 'lokasi' dari form Anda
            $table->string('kabupaten');

            // Data Peta (menggunakan JSON seperti sebelumnya)
            $table->json('coordinates'); // Menyimpan geometri poligon
            $table->string('geojson_path')->nullable(); // Path ke file GeoJSON

            // Path ke file asli
            $table->string('shapefile_path')->nullable();
            $table->json('photo_paths')->nullable();
            $table->string('source_type'); // Sumber: 'shp', 'foto', 'manual', 'prefilled'

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_spasials');
    }
};
