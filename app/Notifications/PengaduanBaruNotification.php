<?php

namespace App\Notifications;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PengaduanBaruNotification extends Notification
{
    use Queueable;

    protected $pengaduan;

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
        // Link ke Dashboard Admin
        $url = route('adminklarifikasi.pengaduan.show', $this->pengaduan->kode_pelacakan);

        return (new MailMessage)
            ->subject('Notifikasi Pengaduan Baru: '.$this->pengaduan->kode_pelacakan)
            ->greeting('Yth. Admin,')
            ->line('Terdapat pengaduan baru yang memerlukan perhatian dan tindak lanjut.')
            ->line('**Nama Pelapor:** '.$this->pengaduan->nama)
            ->line('**Instansi:** '.$this->pengaduan->instansi)
            ->line('**Isi Pesan:** '.\Illuminate\Support\Str::limit($this->pengaduan->pesan, 100))
            ->line('Silakan klik tombol di bawah ini untuk meninjau pengaduan secara lengkap.')
            ->action('Tinjau Pengaduan', $url)
            ->salutation('Terima kasih atas perhatian dan kerjasamanya.')
            ->salutation('Salam Hormat, '.config('app.name'));
    }
}
