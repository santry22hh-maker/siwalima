<?php

namespace Database\Seeders;

use App\Models\Newsfeed;
use Illuminate\Database\Seeder; // Pastikan pakai Model, bukan DB facade agar timestamp otomatis

class NewsSeeder extends Seeder
{
    public function run()
    {
        // 1. Kosongkan tabel dulu agar bersih (Reset)
        Newsfeed::truncate();

        // 2. Data Dummy (Sekarang ada 6 berita)
        $data = [
            [
                'title' => 'Balai Pemantapan Kawasan Hutan Wilayah IX Gelar Rakor Tata Batas',
                'slug' => 'balai-pemantapan-gelar-rakor',
                'content' => 'Balai Pemantapan Kawasan Hutan (BPKH) Wilayah IX Ambon menyelenggarakan rapat koordinasi teknis terkait tata batas kawasan hutan lindung di Pulau Seram. Kegiatan ini dihadiri oleh berbagai pemangku kepentingan daerah untuk memastikan sinergitas data.',
                'image_path' => 'news/news1.jpg',
                'published_at' => now(),
            ],
            [
                'title' => 'Penghargaan Bhumandala Award 2025 untuk Inovasi Geospasial',
                'slug' => 'penghargaan-bhumandala-2025',
                'content' => 'Kementerian Kehutanan kembali meraih penghargaan bergengsi Bhumandala Award atas keberhasilan dalam pengelolaan informasi geospasial kehutanan yang transparan dan akuntabel. Inovasi ini dinilai mempercepat proses perizinan berusaha.',
                'image_path' => 'news/news2.jpg',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Monitoring Tutupan Lahan Hutan Mangrove di Maluku Tengah',
                'slug' => 'monitoring-mangrove-maluku',
                'content' => 'Tim teknis BPKH Wilayah IX melakukan monitoring rutin terhadap perubahan tutupan lahan khususnya ekosistem mangrove. Mangrove memegang peranan penting dalam mitigasi perubahan iklim dan perlindungan pesisir dari abrasi.',
                'image_path' => 'news/news3.jpg',
                'published_at' => now()->subDays(5),
            ],
            // --- DATA TAMBAHAN ---
            [
                'title' => 'Program Perhutanan Sosial Tingkatkan Ekonomi Warga Seram Bagian Barat',
                'slug' => 'perhutanan-sosial-sbb',
                'content' => 'Kelompok Tani Hutan (KTH) di Seram Bagian Barat berhasil memanen hasil hutan bukan kayu berupa minyak kayu putih kualitas ekspor. Ini adalah bukti nyata keberhasilan program Perhutanan Sosial dalam mensejahterakan masyarakat sekitar hutan.',
                'image_path' => 'news/news4.jpg',
                'published_at' => now()->subWeeks(1),
            ],
            [
                'title' => 'Pelepasliaran Satwa Endemik Burung Nuri Maluku ke Habitat Asli',
                'slug' => 'pelepasliaran-nuri-maluku',
                'content' => 'BKSDA Maluku kembali melepasliarkan 20 ekor Burung Nuri Maluku hasil sitaan operasi penertiban. Satwa-satwa ini telah menjalani proses rehabilitasi selama 3 bulan sebelum dikembalikan ke alam liar.',
                'image_path' => 'news/news5.jpg',
                'published_at' => now()->subWeeks(2),
            ],
            [
                'title' => 'Antisipasi El Nino, Manggala Agni Siaga Kebakaran Hutan',
                'slug' => 'manggala-agni-siaga-karhutla',
                'content' => 'Menghadapi musim kemarau panjang akibat dampak El Nino, tim Manggala Agni Daops Maluku meningkatkan patroli terpadu di titik-titik rawan kebakaran hutan dan lahan (Karhutla) di sekitar Kota Ambon.',
                'image_path' => 'news/news6.jpg',
                'published_at' => now()->subMonth(),
            ],
        ];

        // Insert data menggunakan Model create (looping agar timestamp created_at terisi)
        foreach ($data as $item) {
            Newsfeed::create($item);
        }
    }
}
