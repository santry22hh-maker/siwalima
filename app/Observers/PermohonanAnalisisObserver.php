<?php

namespace App\Observers;

use App\Models\PermohonanAnalisis;
use App\Models\User; // <-- TAMBAHKAN INI
use App\Notifications\PermohonanBaruNotification; // <-- TAMBAHKAN INI
use App\Notifications\PermohonanDitolakNotification;
use App\Notifications\PermohonanSelesaiNotification;

class PermohonanAnalisisObserver
{
    /**
     * Menangani event "created" (dibuat) pada PermohonanAnalisis.
     * INI ADALAH FUNGSI BARU ANDA.
     */
    public function created(PermohonanAnalisis $permohonan): void
    {
        // Kita hanya memproses tipe 'RESMI' (karena Mandiri tidak perlu notif ke admin)
        if (strtoupper($permohonan->tipe) === 'RESMI') {

            $targetAdmins = collect(); // Koleksi kosong

            // Cek Tujuan Analisis
            if ($permohonan->tujuan_analisis === 'Perizinan') {
                // Jika Perizinan -> Kirim ke Admin IGT
                $targetAdmins = User::role('Admin IGT')->get();
            } elseif ($permohonan->tujuan_analisis === 'Klarifikasi Kawasan Hutan') {
                // Jika Klarifikasi -> Kirim ke Admin Klarifikasi
                $targetAdmins = User::role('Admin Klarifikasi')->get();
            }

            // Kirim notifikasi ke semua admin yang sesuai
            foreach ($targetAdmins as $admin) {
                $admin->notify(new PermohonanBaruNotification($permohonan));
            }
        }
    }

    /**
     * Menangani event "updated" pada PermohonanAnalisis.
     * (Ini adalah fungsi Anda yang sudah ada)
     */
    public function updated(PermohonanAnalisis $permohonan): void
    {
        // Ambil relasi user dari permohonan
        $user = $permohonan->user;
        if (! $user) {
            return; // Tidak ada pengguna untuk dikirimi notifikasi
        }

        // 1. Cek apakah status baru saja diubah menjadi "Selesai"
        if ($permohonan->wasChanged('status') && strtolower($permohonan->status) === 'selesai') {
            $user->notify(new PermohonanSelesaiNotification($permohonan));
        }

        // 2. Cek apakah status baru saja diubah menjadi "Ditolak"
        if ($permohonan->wasChanged('status') && strtolower($permohonan->status) === 'ditolak') {
            // Ambil alasan dari catatan penelaah (yang kita isi di controller)
            $alasan = $permohonan->catatan_penelaah ?? 'Tidak ada alasan spesifik yang diberikan.';
            $user->notify(new PermohonanDitolakNotification($permohonan, $alasan));
        }
    }
}
