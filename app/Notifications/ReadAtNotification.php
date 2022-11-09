<?php

namespace Gentcmen\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\Channel;

class ReadAtNotification extends Notification
{
    use Queueable;
    protected $content;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

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
            'id_read' => $this->content['id_read'],
	        'read_at' => $this->content['read_at']
        ]);
    }

    public function broadcastAs () {
        return 'read-at';
    }

    public function broadcastOn()
    {
        return [new Channel('read-at')];
    }
}
