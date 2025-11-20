<?php

namespace App\Notifications;

use App\Models\Permohonan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IgtPermohonanDitolakNotification extends Notification
{
    use Queueable;

    protected $permohonan;

    public function __construct(Permohonan $permohonan)
    {
        $this->permohonan = $permohonan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
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
        // URL ke halaman detail permohonan (opsional, misal untuk lihat alasan penolakan)
        $url = route('permohonanspasial.show', $this->permohonan->id);

        return (new MailMessage)
            ->subject('Permohonan Data IGT Ditolak: '.$this->permohonan->kode_pelacakan ?? '-')
            ->greeting('Halo '.$notifiable->name.',')
            ->line('Permohonan data spasial Anda telah <strong>ditolak</strong>.')
            ->line('**Pemohon:** '.$this->permohonan->nama_pemohon ?? $this->permohonan->user->name)
            ->line('**Instansi:** '.$this->permohonan->instansi)
            ->line('**Alasan Penolakan:** '.$this->permohonan->alasan_penolakan ?? '-')
            ->action('Lihat Detail Permohonan', $url)
            ->line('Silakan meninjau kembali atau mengajukan permohonan baru jika diperlukan.')
            ->line('Terimakasih')
            ->salutation('Salam Hormat, '.config('app.name'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
