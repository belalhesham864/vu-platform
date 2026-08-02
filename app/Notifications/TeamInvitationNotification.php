<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamInvitationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $invitation;

    public function __construct($invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Team Invitation',
            'greeting' => 'Hello ' . $notifiable->name,
            'message' => 'You have been invited to join the company team.',
            'role' => $this->invitation->role,
            'expires_at' => $this->invitation->expires_at->format('Y-m-d H:i'),
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Team Invitation')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('You have been invited to join the company team.')
            ->line('Assigned Role: **' . $this->invitation->role . '**')
            ->line('This invitation will expire on ' . $this->invitation->expires_at->format('Y-m-d H:i'))
            ->line('If you were not expecting this invitation, you can safely ignore this email.');
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
