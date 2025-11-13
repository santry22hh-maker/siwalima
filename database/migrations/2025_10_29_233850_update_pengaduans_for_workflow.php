<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengaduans', function (Blueprint $table) {
            // 1. Tambah kolom penelaah_id (siapa yang menangani)
            $table->foreignId('penelaah_id')->nullable()->after('user_id')
                ->constrained('users')->onDelete('set null');

            // 2. Tambah kolom untuk draf balasan Penelaah
            $table->text('balasan_penelaah')->nullable()->after('catatan_admin');

            // 3. Ubah kolom status untuk menerima nilai baru
            $table->string('status')->default('Baru')
                ->comment('Baru, Diproses, Menunggu Persetujuan, Revisi, Selesai, Dibatalkan')
                ->change();
        });
    }

    // (Method down() bisa Anda isi untuk me-rollback jika perlu)
};
