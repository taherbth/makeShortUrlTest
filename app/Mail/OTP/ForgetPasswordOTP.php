<?php

namespace App\Mail\OTP;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetPasswordOTP extends Mailable
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
            ->subject('Password reset instruction')
            ->markdown('emails.otp.forget_password')
            ->with([
            'otp' => $this->random,
        ]);
    }
}
