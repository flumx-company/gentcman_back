<?php

namespace Gentcmen\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendFaqAnswerNotFoundMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $request;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->request['email'], $this->request['name'])
                    ->replyTo($this->request['email'])
                    ->subject($this->request['theme'])
                    ->view('email.faqAnswerNotFound')
                    ->with('issue', $this->request['content']);
    }
}
