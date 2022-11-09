<?php

namespace Gentcmen\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MarkAsReadAllNotifications extends Notification
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'success' => true
        ]);
    }

    public function broadcastAs (): string
    {
        return 'mark-all-notifications-as-read';
    }

    public function broadcastOn()
    {
        return [new Channel('mark-all-notifications-as-read')];
    }
}
