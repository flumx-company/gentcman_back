<?php

namespace Gentcmen\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendIdeaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var object
     */
    protected $content;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(object $content)
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->from($this->content->user_email, $this->content->name)
            ->subject('Idea notification')
            ->view('email.sendIdea', ['content' => $this->content]);
    }
}
