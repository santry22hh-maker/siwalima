<?php

namespace App\Notifications;

use App\Models\PermohonanAnalisis;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermohonanSelesaiNotification extends Notification
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
        return ['mail']; // Kirim via email
    }

    /**
     * Buat representasi email dari notifikasi.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('permohonananalisis.show', $this->permohonan->slug); // Arahkan ke halaman detail

        return (new MailMessage)
            ->subject('Permohonan Analisis Selesai: '.$this->permohonan->kode_pelacakan)
            ->greeting('Halo, '.$this->permohonan->nama_pemohon.'!')
            ->line('Kami informasikan bahwa permohonan analisis spasial Anda dengan kode pelacakan '.$this->permohonan->kode_pelacakan.' telah selesai diproses.')
            ->line('Silakan login ke akun Anda untuk melihat dan mengunduh hasil analisis.')
            ->action('Lihat Detail Permohonan', $url)
            ->line('Terima kasih telah menggunakan layanan kami.')
            ->salutation('Salam Hormat, '.config('app.name'));
    }
}
