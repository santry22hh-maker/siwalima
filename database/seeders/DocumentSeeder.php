<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Pastikan model diimport

class DocumentSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel dulu agar tidak duplikat saat dijalankan ulang
        DB::table('documents')->truncate();

        $data = [
            [
                'title' => 'SK.666/MENLHK/PKTL/PLA.2/9/2024 tentang Penetapan Kawasan Hutan',
                'description' => 'Keputusan Menteri Lingkungan Hidup dan Kehutanan tentang Penetapan Kawasan Hutan Lindung pada Kelompok Hutan Gunung Salak seluas 15.000 Hektar di Provinsi Jawa Barat.',
                'type' => 'SK Menteri',
                'file_path' => 'documents/sk_penetapan_2024.pdf', // Contoh path (dummy)
                'image_path' => null, // Biarkan null agar muncul icon default
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Peta Indikatif Alokasi Kawasan Hutan untuk Penyediaan Sumber Tanah Obyek Reforma Agraria (TORA)',
                'description' => 'Peta lampiran revisi IV peta indikatif alokasi kawasan hutan untuk penyediaan sumber tanah obyek reforma agraria (TORA) skala 1:250.000.',
                'type' => 'Peta & Spasial',
                'file_path' => 'documents/peta_tora_revisi_iv.pdf',
                'image_path' => null,
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'title' => 'Laporan Kinerja Direktorat Jenderal Planologi Kehutanan Tahun 2023',
                'description' => 'Laporan akuntabilitas kinerja instansi pemerintah yang memuat capaian kinerja sasaran strategis dan indikator kinerja utama tahun anggaran 2023.',
                'type' => 'Laporan Kinerja',
                'file_path' => 'documents/lakip_pktl_2023.pdf',
                'image_path' => null,
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(5),
            ],
            [
                'title' => 'Peta Perkembangan Pengukuhan Kawasan Hutan Provinsi Maluku',
                'description' => 'Update data spasial perkembangan tata batas kawasan hutan di wilayah Balai Pemantapan Kawasan Hutan Wilayah IX Ambon sampai dengan periode Desember 2024.',
                'type' => 'Peta & Spasial',
                'file_path' => 'documents/peta_maluku_2024.pdf',
                'image_path' => null,
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'title' => 'Surat Edaran Dirjen PKTL Nomor SE.4/PKTL/REN/PLA.0/3/2024',
                'description' => 'Petunjuk Teknis pelaksanaan inventarisasi hutan menyeluruh berkala (IHMB) pada kesatuan pengelolaan hutan (KPH).',
                'type' => 'Surat Edaran',
                'file_path' => 'documents/se_juknis_ihmb.pdf',
                'image_path' => null,
                'created_at' => now()->subMonth(),
                'updated_at' => now()->subMonth(),
            ],
            [
                'title' => 'Dokumen Kajian Lingkungan Hidup Strategis (KLHS) RDTR',
                'description' => 'Dokumen validasi KLHS untuk Rencana Detail Tata Ruang (RDTR) kawasan perkotaan baru.',
                'type' => 'Dokumen Lingkungan',
                'file_path' => 'documents/klhs_rdtr.pdf',
                'image_path' => null,
                'created_at' => now()->subWeeks(2),
                'updated_at' => now()->subWeeks(2),
            ],
        ];

        // Insert data
        Document::insert($data);
    }
}
