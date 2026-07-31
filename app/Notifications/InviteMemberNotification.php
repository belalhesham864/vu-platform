<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteMemberNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $token;
    public function __construct($token)
    {
        $this->token=$token;
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
    $url=config('app.frontend_url')
    .'/set-password?token='
    .$this->token
    .'&email='
    .urlencode($notifiable->email);

    return (new MailMessage)
        ->subject('VU Platform Invitation')
        ->greeting('Hello '.$notifiable->name)
        ->line('You have been invited to join VU Platform.')
        ->line('Use the following token to set your password:')
        ->action('Set Password', $url)
        ->line('Email: '.$notifiable->email);
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
