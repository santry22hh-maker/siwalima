<?php

namespace App\Notifications;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TugasPengaduanNotification extends Notification
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
        // Link ke halaman detail pengaduan untuk penelaah
        $url = route('adminklarifikasi.pengaduan.index', $this->pengaduan->kode_pelacakan);

        return (new MailMessage)
            ->subject('Pengaduan Perlu Ditinjau: '.$this->pengaduan->kode_pelacakan)
            ->greeting('Yth. Penelaah,')
            ->line('Terdapat pengaduan baru yang membutuhkan peninjauan dan evaluasi Anda.')
            ->line('**Nama Pelapor:** '.$this->pengaduan->nama)
            ->line('**Instansi:** '.$this->pengaduan->instansi)
            ->line('**Isi Pesan (Ringkasan):** '.\Illuminate\Support\Str::limit($this->pengaduan->pesan, 100))
            ->line('Silakan klik tautan di bawah ini untuk meninjau pengaduan secara lengkap dan memberikan tindak lanjut yang diperlukan.')
            ->action('Tinjau Pengaduan', $url)
            ->salutation('Terima kasih atas perhatian dan evaluasi Anda.')
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
