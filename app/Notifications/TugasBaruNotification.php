<?php

namespace App\Notifications;

use App\Models\PermohonanAnalisis;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TugasBaruNotification extends Notification
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
        // URL ini akan mengarahkan Penelaah ke halaman 'show' di backend
        $url = route('adminklarifikasi.permohonan.show', $this->permohonan->slug);

        return (new MailMessage)
            ->subject('Tugas Baru: Permohonan Analisis '.$this->permohonan->kode_pelacakan)
            ->greeting('Halo, '.$notifiable->name.'!')
            ->line('Anda telah ditugaskan untuk menelaah permohonan analisis spasial baru.')
            ->line('**Kode Pelacakan:** '.$this->permohonan->kode_pelacakan)
            ->line('**Nama Pemohon:** '.$this->permohonan->nama_pemohon)
            ->action('Lihat Detail Tugas', $url)
            ->line('Harap segera ditindaklanjuti.')
            ->salutation('Salam Hormat, '.config('app.name'));
    }
}
