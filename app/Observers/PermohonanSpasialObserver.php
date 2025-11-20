<?php

namespace App\Observers;

use App\Models\Permohonan; // Sesuaikan Model
use App\Models\User;
use App\Notifications\IgtPermohonanBaruNotification;
use App\Notifications\IgtPermohonanDitolakNotification;
use App\Notifications\IgtPermohonanSelesaiNotification;
use App\Notifications\IgtTugasBaruNotification;

class PermohonanSpasialObserver
{
    /**
     * 1. Saat Permohonan Baru Dibuat (User -> Admin IGT)
     */
    public function created(Permohonan $permohonan): void
    {
        // Kirim ke semua user dengan role 'Admin IGT'
        $admins = User::role('Admin IGT')->get();

        foreach ($admins as $admin) {
            $admin->notify(new IgtPermohonanBaruNotification($permohonan));
        }
    }

    /**
     * 2. Saat Status Berubah
     */
    public function updated(Permohonan $permohonan): void
    {
        // Ambil status terbaru dan ubah jadi huruf kecil semua agar aman di VPS
        // Contoh: "Direvisi" -> "direvisi", "Selesai" -> "selesai"
        $status = strtolower($permohonan->status);

        // A. Disposisi
        if ($permohonan->wasChanged('penelaah_id') && $permohonan->penelaah_id) {
            $penelaah = User::find($permohonan->penelaah_id);
            if ($penelaah) {
                $penelaah->notify(new IgtTugasBaruNotification($permohonan));
            }
        }

        // B. Selesai
        if ($permohonan->wasChanged('status') && $status === 'selesai') {
            $user = $permohonan->user;
            if ($user) {
                $user->notify(new IgtPermohonanSelesaiNotification($permohonan));
            }
        }

        // C. Direvisi (PERBAIKAN DI SINI)
        // Kita cek apakah statusnya "direvisi" (sesuai database Anda "Direvisi")
        // Atau "revisi" (untuk jaga-jaga jika ada ketidakkonsistenan)
        if ($permohonan->wasChanged('status') && ($status === 'direvisi' || $status === 'revisi')) {
            $user = $permohonan->user;
            if ($user) {
                // Menggunakan notifikasi Ditolak/Revisi (sesuaikan nama kelas notifikasi Anda)
                // Pastikan Anda mengirim alasan jika ada
                $user->notify(new IgtPermohonanDitolakNotification($permohonan));
            }
        }

        // D. Ditolak (Jika ada status 'Ditolak' murni)
        if ($permohonan->wasChanged('status') && $status === 'ditolak') {
            $user = $permohonan->user;
            if ($user) {
                $user->notify(new IgtPermohonanDitolakNotification($permohonan));
            }
        }
    }
}
