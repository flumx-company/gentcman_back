<?php

namespace Gentcmen\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

use JetBrains\PhpStorm\Pure;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class PostOfferNotification extends Notification implements ShouldBroadcast
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
        return  ['broadcast', 'database', TelegramChannel::class]; //TelegramChannel::class
    }

    /**
     * Get the telegram representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return TelegramMessage
     */

    public function toTelegram($notifiable)
    {
        $message =
            "Theme is ".$this->content->theme.
            ".\nProblem is ".$this->content->content.
            ".\nMessage is ".$this->content->message.
            ".\nMy name is ". $this->content->user_name.
            ".\nMy email is ". $this->content->email;

        return TelegramMessage::create()
            ->to(env('TELEGRAM_CHANNEL_NAME'))
            ->content($message);
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'notification_id' => 1, //$this->content['notification_id'],
            'theme' => $this->content->theme,
            'text' => $this->content->text,
            'message is' => $this->content->message,
            'user_name' =>  $this->content->user_name,
            'email' =>  $this->content->email
        ]);
    }

    public function toDatabase() {
        return [
            'theme' => $this->content->theme,
            'text' => $this->content->text,
            'user_name' =>  $this->content->user_name,
            'email' =>  $this->content->email
        ];
    }

    public function broadcastAs (): string
    {
        return 'post-offer';
    }

    #[Pure] public function broadcastOn()
    {
        return [new Channel('post-offer')];
    }
}
