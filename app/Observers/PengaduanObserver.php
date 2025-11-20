<?php

namespace App\Observers;

use App\Models\Pengaduan;
use App\Models\User;
use App\Notifications\PengaduanBaruNotification;
use App\Notifications\PengaduanSelesaiNotification;
use App\Notifications\ReviewPengaduanNotification;
use App\Notifications\TugasPengaduanNotification;

class PengaduanObserver
{
    /**
     * Helper untuk mendapatkan Admin yang tepat (IGT vs Klarifikasi)
     */
    private function getAdmins(Pengaduan $pengaduan)
    {
        $role = ($pengaduan->category == 'IGT') ? 'Admin IGT' : 'Admin Klarifikasi';

        return User::role($role)->get();
    }

    /**
     * 1. Saat Pengaduan Baru Dibuat (User -> Admin)
     */
    public function created(Pengaduan $pengaduan): void
    {
        $admins = $this->getAdmins($pengaduan);
        foreach ($admins as $admin) {
            $admin->notify(new PengaduanBaruNotification($pengaduan));
        }
    }

    /**
     * 2. Saat Status Berubah
     */
    public function updated(Pengaduan $pengaduan): void
    {
        // Cek jika status berubah
        if ($pengaduan->wasChanged('status')) {
            $status = strtolower($pengaduan->status);

            // A. Admin menugaskan ke Penelaah (Status: Ditugaskan)
            if ($status == 'ditugaskan') {
                if ($pengaduan->penelaah) {
                    $pengaduan->penelaah->notify(new TugasPengaduanNotification($pengaduan));
                }
            }

            // B. Penelaah mengajukan review (Status: Direview) -> Notif ke Admin
            if ($status == 'direview') {
                $admins = $this->getAdmins($pengaduan);
                foreach ($admins as $admin) {
                    $admin->notify(new ReviewPengaduanNotification($pengaduan));
                }
            }

            // C. Admin menyelesaikan/membalas (Status: Selesai) -> Notif ke User
            if ($status == 'selesai') {
                if ($pengaduan->user) {
                    $pengaduan->user->notify(new PengaduanSelesaiNotification($pengaduan));
                }
            }
        }
    }
}
