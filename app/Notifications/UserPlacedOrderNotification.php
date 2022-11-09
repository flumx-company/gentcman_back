<?php

namespace Gentcmen\Notifications;

use Illuminate\Broadcasting\Channel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class UserPlacedOrderNotification extends Notification implements ShouldBroadcast
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
	var_dump($content);
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
        return ['broadcast', 'database']; //TelegramChannel::class
    }

    /**
     * Get the telegram representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return TelegramMessage
     */

    public function toTelegram($notifiable)
    {
        $message = "new order created by user";

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
            'products' => $this->content['products'],
            'billing_email' => $this->content['billing_email'],
            'order_id' => $this->content['order_id'],
            'user_id' => $this->content['user_id'],
            'billing_phone' => $this->content['billing_phone'],
	    'billing_delivery_type' => $this->content['billing_delivery_type'],
	    'billing_payment_type' => $this->content['billing_payment_type'],
	    'grand_total' => $this->content['grand_total']
        ]);
    }

    public function toDatabase() {
        return [
            'products' => $this->content['products'],
            'billing_email' => $this->content['billing_email'],
            'order_id' => $this->content['order_id'],
            'user_id' => $this->content['user_id'],
	    'billing_phone' => $this->content['billing_phone'],
	    'billing_delivery_type' => $this->content['billing_delivery_type'],
	    'billing_payment_type' => $this->content['billing_payment_type'],
	    'grand_total' => $this->content['grand_total']
        ];
    }

    public function broadcastAs () {
        return 'user-placed-order';
    }

    public function broadcastOn()
    {
        return [new Channel('user-placed-order')];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
