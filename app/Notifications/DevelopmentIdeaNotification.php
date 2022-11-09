<?php

namespace Gentcmen\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class DevelopmentIdeaNotification extends Notification implements ShouldQueue
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
        $this->channelName = 'development-idea';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast', 'database'];//, TelegramChannel::class];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'user_name' => $this->content['user_name'],
            'email' => $this->content['email'],
            'idea' => $this->content['idea'],
	    'theme' => $this->content['theme']
        ]);
    }

    public function toDatabase() {
        return [
            'user_name' => $this->content['user_name'],
            'email' => $this->content['email'],
            'idea' => $this->content['idea'],
	    'theme' => $this->content['theme']
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
