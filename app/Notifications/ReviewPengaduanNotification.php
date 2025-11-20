<?php

namespace App\Notifications;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReviewPengaduanNotification extends Notification
{
    use Queueable;

    protected $pengaduan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pengaduan $pengaduan)
    {
        $this->pengaduan = $pengaduan;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('adminklarifikasi.pengaduan.show', $this->pengaduan->kode_pelacakan);

        return (new MailMessage)
            ->subject('Tindakan Diperlukan: Pengaduan '.$this->pengaduan->kode_pelacakan)
            ->greeting('Yth. Admin,')
            ->line('Terdapat pengaduan baru yang memerlukan review dan tindak lanjut Anda.')
            ->line('**Nama Pelapor:** '.$this->pengaduan->nama)
            ->line('**Instansi:** '.$this->pengaduan->instansi)
            ->line('**Isi Pesan (Ringkasan):** '.\Illuminate\Support\Str::limit($this->pengaduan->pesan, 100))
            ->line('Silakan tinjau pengaduan ini melalui tautan di bawah untuk memastikan tindakan yang tepat.')
            ->action('Tinjau Pengaduan', $url)
            ->salutation('Terima kasih atas perhatian dan tindak lanjut Anda.')
            ->salutation('Salam Hormat, '.config('app.name'));
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
