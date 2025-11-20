<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsfeed extends Model
{
    use HasFactory;

    // Nama tabel (opsional, tapi baik untuk memastikan)
    protected $table = 'news';

    // Kolom yang boleh diisi (Wajib ada untuk Seeder/Input Admin)
    protected $fillable = [
        'title',
        'slug',
        'content',
        'image_path',
        'published_at',
    ];

    // Mengubah kolom published_at menjadi objek Tanggal (Carbon) otomatis
    // Supaya di Blade bisa pakai format() seperti: ->format('d M Y')
    protected $casts = [
        'published_at' => 'datetime',
    ];
}
