<?php

namespace App\Mail\OTP;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailResetOTP extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $random;
    public function __construct($random)
    {
        $this->random=$random;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Email reset instruction')
            ->markdown('emails.otp.email_reset')
            ->with([
            'otp' => $this->random,
        ]);
    }
}
