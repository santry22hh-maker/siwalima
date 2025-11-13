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
        Schema::table('permohonans', function (Blueprint $table) {
            // Tambahkan user_id setelah 'id'
            $table->foreignId('user_id')->nullable()->after('id')
                ->constrained()->onDelete('set null');

            // Tambahkan status di akhir
            $table->string('status')->default('Pending')->after('file_surat');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permohonans', function (Blueprint $table) {
            //
        });
    }
};
