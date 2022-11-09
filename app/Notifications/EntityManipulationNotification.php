<?php

namespace Gentcmen\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EntityManipulationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $channel_name;
    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
        $this->channel_name = 'entity-manipulation';
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
            'type' => $this->details->type,
            'modelName' => $this->details->modelName,
        ]);
    }

    public function broadcastAs (): string
    {
        return $this->channel_name;
    }

    public function broadcastOn()
    {
        return [new Channel($this->channel_name)];
    }
}
