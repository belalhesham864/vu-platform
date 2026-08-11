<?php

namespace App\Notifications;

use Ichtrojan\Otp\Otp;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class verfaidEmailOtpNotification extends Notification
{


    public $otp;
    public function __construct()
    {
        $this->otp = new Otp();
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
        $otp = $this->otp->generate($notifiable->email, 'numeric', 6);
        return (new MailMessage)
            ->subject('Verify your email')
            ->line('Your verification code is:')
            ->line($otp->token)
            ->line('This code will expire soon.');
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
