<?php

namespace App\Notifications;

use App\Models\Permohonan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
// Sesuaikan dengan nama model IGT Anda (misal: PermohonanSpasial)
use Illuminate\Notifications\Notification;

class IgtPermohonanBaruNotification extends Notification
{
    use Queueable;

    protected $permohonan;

    public function __construct(Permohonan $permohonan)
    {
        $this->permohonan = $permohonan;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        // URL ke halaman detail Admin IGT
        $url = route('permohonanspasial.show', $this->permohonan->id);

        return (new MailMessage)
            ->subject('Permohonan Data IGT Baru: '.$this->permohonan->kode_pelacakan ?? '-')
            ->greeting('Halo Admin IGT,')
            ->line('Ada permohonan data spasial baru yang perlu ditindaklanjuti.')
            ->line('**Pemohon:** '.$this->permohonan->nama_pemohon ?? $this->permohonan->user->name)
            ->line('**Instansi:** '.$this->permohonan->instansi)
            ->action('Tinjau Permohonan', $url)
            ->line('Terima kasih.')
            ->salutation('Salam Hormat, '.config('app.name'));
    }
}
