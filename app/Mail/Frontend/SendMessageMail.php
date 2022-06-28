<?php

namespace App\Mail\Frontend;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    private $name,$email,$message;
    public function __construct($name,$email,$message)
    {
        $this->name=$name;
        $this->email=$email;
        $this->message=$message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Message from a website visitor')
            ->markdown('emails.frontend.send_message')
            ->with([
            'name' => $this->name,
            'email' => $this->email,
            'message' => $this->message,
        ]);
    }
}
