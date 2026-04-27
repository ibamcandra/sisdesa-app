<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserAccountCreated extends Notification
{
    use Queueable;

    public $name;
    public $email;
    public $password;
    public $role;

    public function __construct($name, $email, $password, $role)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Akun Anda Telah Terdaftar - Portal Gawe')
            ->view('emails.new-user-account', [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'role' => $this->role,
            ]);
    }
}
