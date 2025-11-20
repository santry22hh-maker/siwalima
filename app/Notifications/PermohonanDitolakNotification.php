<?php

namespace App\Notifications;

use App\Models\PermohonanAnalisis;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PermohonanDitolakNotification extends Notification
{
    use Queueable;

    protected $permohonan;

    protected $alasan;

    /**
     * Create a new notification instance.
     */
    public function __construct(PermohonanAnalisis $permohonan, $alasan)
    {
        $this->permohonan = $permohonan;
        $this->alasan = $alasan;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $url = route('permohonananalisis.show', $this->permohonan->slug);

        return (new MailMessage)
            ->subject('Permohonan Analisis Ditolak: '.$this->permohonan->kode_pelacakan)
            ->greeting('Halo, '.$this->permohonan->nama_pemohon.'.')
            ->line('Mohon maaf, permohonan analisis spasial Anda dengan kode pelacakan '.$this->permohonan->kode_pelacakan.' telah ditolak.')
            ->line('**Alasan Penolakan:**')
            ->line($this->alasan)
            ->line('Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi kami melalui menu "Pengaduan".')
            ->action('Lihat Detail Permohonan', $url)
            ->line('Terima kasih.')
            ->salutation('Salam Hormat, '.config('app.name'));
    }
}
