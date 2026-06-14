<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends Notification
{
    public $token;
    public $email;
    public $source;

    public function __construct($token, $email, $source = null)
    {
        $this->token = $token;
        $this->email = $email;
        $this->source = $source;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $this->email,
            'source' => $this->source,
        ]);

        return (new MailMessage)
            ->subject('Смена пароля на форуме')
            ->line('Вы запросили смену пароля для вашей учётной записи.')
            ->action('Сменить пароль', $url)
            ->line('Если вы не запрашивали смену пароля, просто проигнорируйте это письмо.');
    }
}