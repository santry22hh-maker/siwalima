<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataIgt extends Model
{
    use HasFactory;
    
    // Tentukan nama tabel secara eksplisit jika tidak mengikuti konvensi jamak
    protected $table = 'data_igts';

    protected $fillable = [
        'jenis_data',
        'periode_update',
        'format_data',
    ];
}