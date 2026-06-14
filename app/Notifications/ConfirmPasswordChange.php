<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmPasswordChange extends Notification
{
    use Queueable;

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('password.change.confirm', ['token' => $this->token]);

        return (new MailMessage)
            ->subject('Подтверждение смены пароля')
            ->line('Вы запросили смену пароля на нашем сайте.')
            ->action('Подтвердить смену пароля', $url)
            ->line('Если вы не запрашивали смену пароля, просто проигнорируйте это письмо.');
    }
}