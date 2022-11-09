<?php

namespace Gentcmen\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Broadcasting\Channel;

use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class AnswerNotFoundNotification extends Notification implements ShouldQueue
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
        $this->channelName = 'answer-no-found';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast', 'database', TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {
        $message =
            'Theme is '.$this->content['theme'].
            '. Problem is '.$this->content['content'].
            '. My name is '. $this->content['name'].
            '. My email is '. $this->content['email'];

        return TelegramMessage::create()
            ->to(env('TELEGRAM_CHANNEL_NAME'))
            ->content($message);
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'notification_id' => 1, //$this->content['notification_id'],
            'theme' => $this->content['theme'],
            'content' => $this->content['content'],
            'name' =>  $this->content['name'],
            'user_email' =>  $this->content['email']
        ]);
    }

    public function toDatabase() {
        return [
            'theme' => $this->content['theme'],
            'content' => $this->content['content'],
            'name' =>  $this->content['name'],
            'user_email' =>  $this->content['email']
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
