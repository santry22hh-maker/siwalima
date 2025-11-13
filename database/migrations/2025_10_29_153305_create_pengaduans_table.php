<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // User yg submit
            $table->string('nama');
            $table->string('instansi');
            $table->string('email');
            $table->text('pesan');
            $table->string('file')->nullable(); // Path ke file
            $table->string('status')->default('Baru'); // Baru, Diproses, Selesai
            $table->text('catatan_admin')->nullable(); // Balasan dari admin
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
