<?php

namespace App\Models;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        // Ganti 'emails.password-reset' dengan view kustom jika Anda mau,
        // atau gunakan MailMessage standar di bawah ini.

        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $this->getEmailForPasswordReset(),
        ], false));

        // Anda bisa mengkustomisasi email di sini
        $mailMessage = (new MailMessage)
            ->subject('Notifikasi Reset Password Anda')
            ->line('Anda menerima email ini karena kami menerima permintaan reset kata sandi untuk akun Anda.') // <-- GANTI TEKS INI
            ->action('Reset Password', $url) // <-- Ini tombolnya
            ->line('Link reset password ini akan kedaluwarsa dalam 60 menit.') // <-- GANTI TEKS INI
            ->line('Jika Anda tidak merasa meminta reset password, abaikan email ini.');

        $this->notify(new class($mailMessage) extends ResetPasswordNotification
        {
            public $mailMessage;

            public function __construct($mailMessage)
            {
                $this->mailMessage = $mailMessage;
            }

            public function toMail($notifiable)
            {
                return $this->mailMessage;
            }
        });
    }

    public function sendEmailVerificationNotification()
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email Anda')
                ->line('Silakan klik tombol di bawah untuk memverifikasi alamat email Anda.') // <-- GANTI TEKS INI
                ->action('Verifikasi Email', $url)
                ->line('Jika Anda tidak membuat akun ini, abaikan email ini.');
        });

        $this->notify(new VerifyEmail);
    }
}
