<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Polygon extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nama_pemohon',
        'lokasi',
        'kabupaten',
        'coordinates',
        'groupid',        // Ditambahkan untuk mass assignment
        'geojson_path',   // Ditambahkan untuk mass assignment
        'shapefile_path',
        'photo_paths',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'coordinates' => 'array',
        'photo_paths' => 'array',
    ];

    /**
     * Get the laporan associated with the polygon.
     */
    public function laporan()
    {
        return $this->hasOne(Laporan::class);
    }
}
