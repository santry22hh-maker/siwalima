<?php

namespace Database\Seeders;

use App\Models\Carousel;
use Illuminate\Database\Seeder;

class CarouselSeeder extends Seeder
{
    public function run()
    {
        // Pastikan Anda punya file gambar dummy di storage/app/public/slider/
        // Misalnya: forest1.jpg, forest2.jpg

        Carousel::create([
            'title' => 'Pengembangan Ekowisata Berbasis Masyarakat',
            'description' => 'Ekowisata berkelanjutan mulai dikembangkan oleh masyarakat lokal untuk meningkatkan ekonomi dan menjaga kelestarian hutan.',
            'image_path' => 'slider/forest_sample_1.jpg', // Pastikan file ini ada
            'link_url' => '#',
            'is_active' => true,
        ]);

        Carousel::create([
            'title' => 'Pemetaan Batas Kawasan Hutan Wilayah IX',
            'description' => 'Memastikan kepastian hukum dan tata kelola hutan yang baik melalui teknologi pemetaan geospasial terkini.',
            'image_path' => 'slider/forest_sample_2.jpg',
            'link_url' => '/spasial',
            'is_active' => true,
        ]);
    }
}
