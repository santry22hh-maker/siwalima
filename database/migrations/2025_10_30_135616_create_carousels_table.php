<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('carousels', function (Blueprint $table) {
            $table->id();
            $table->string('title');        // Judul besar (cth: Pengembangan Ekowisata)
            $table->text('description')->nullable(); // Deskripsi kecil di bawahnya
            $table->string('image_path');   // Path gambar background
            $table->string('link_url')->nullable();  // Link tombol "Baca Selengkapnya"
            $table->boolean('is_active')->default(true); // Untuk menyembunyikan slide tanpa menghapus
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carousels');
    }
};
