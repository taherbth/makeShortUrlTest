<?php

namespace App\Mail\OTP;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationOTP extends Mailable
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
        return $this
            ->subject('Complete your WAM ACADEMY sign up')
            ->markdown('emails.otp.registration')
            ->with([
            'otp' => $this->random,
        ]);
    }
}
