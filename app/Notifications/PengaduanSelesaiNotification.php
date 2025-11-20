<?php

namespace App\Notifications;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengaduanSelesaiNotification extends Notification
{
    use Queueable;

    public $pengaduan;

    /**
     * Create a new notification instance.
     */
    public function __construct(Pengaduan $pengaduan)
    {
        $this->pengaduan = $pengaduan;
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
        // Link ke halaman detail pengaduan
        $url = route('pengaduan.show', $this->pengaduan->kode_pelacakan);

        return (new MailMessage)
            ->subject('Pengaduan Selesai: '.$this->pengaduan->kode_pelacakan)
            ->greeting('Yth. '.$this->pengaduan->nama.',')
            ->line('Pengaduan Anda dengan kode '.$this->pengaduan->kode_pelacakan.' telah selesai ditindaklanjuti.')
            ->line('**Instansi yang Menangani:** '.$this->pengaduan->instansi)
            ->line('**Ringkasan Pesan:** '.\Illuminate\Support\Str::limit($this->pengaduan->pesan, 100))
            ->line('Anda dapat melihat rincian tindak lanjut melalui tautan berikut.')
            ->action('Lihat Pengaduan', $url)
            ->salutation('Terima kasih atas partisipasi Anda dalam proses pengaduan.')
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
