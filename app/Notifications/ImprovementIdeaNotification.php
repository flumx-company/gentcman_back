<?php

namespace Gentcmen\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ImprovementIdeaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected string $channelName;
    protected $content;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
        $this->channelName = 'improvement-idea';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'theme' => $this->content['theme'],
            'email' => $this->content['email'],
            'improvement' => $this->content['improvement']
        ]);
    }

    public function toDatabase() {
        return [
	    'theme' => $this->content['theme'],
            'email' => $this->content['email'],
            'improvement' => $this->content['improvement']
        ];
    }


    public function broadcastAs (): string
    {
        return $this->channelName;
    }

    public function broadcastOn()
    {
        return [new Channel($this->channelName)];
    }
}
