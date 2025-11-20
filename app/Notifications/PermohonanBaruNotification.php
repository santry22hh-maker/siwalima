<?php

namespace App\Notifications;

use App\Models\PermohonanAnalisis;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermohonanBaruNotification extends Notification
{
    use Queueable;

    protected $permohonan;

    /**
     * Buat instance notifikasi baru.
     */
    public function __construct(PermohonanAnalisis $permohonan)
    {
        $this->permohonan = $permohonan;
    }

    /**
     * Tentukan channel pengiriman.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Buat representasi email dari notifikasi.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // 1. Siapkan URL tujuan tombol
        $url = route('adminklarifikasi.permohonan.show', $this->permohonan->slug);

        return (new MailMessage)
            ->subject('Permohonan Baru: '.$this->permohonan->tujuan_analisis)
            ->greeting('Halo Admin,')

                    // Baris-baris teks
            ->line('Sebuah permohonan analisis spasial baru telah masuk.')
            ->line('**Kode:** '.$this->permohonan->kode_pelacakan)
            ->line('**Pemohon:** '.$this->permohonan->nama_pemohon)
            ->line('**Tujuan:** '.$this->permohonan->tujuan_analisis)
            ->line('**Perihal:** '.$this->permohonan->perihal_surat)
            ->line('Mohon segera ditindaklanjuti.')
                    // --- BAGIAN INI YANG MEMBUAT TOMBOL ---
                    // Parameter 1: Teks pada tombol
                    // Parameter 2: URL tujuan saat tombol diklik
            ->action('Tinjau Permohonan', $url)
                    // --------------------------------------
            ->salutation('Salam Hormat, '.config('app.name'));
    }
}
