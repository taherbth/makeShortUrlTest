<?php

namespace App\Listeners\otp;

use App\Events\otp\ForgetPasswordOtpEvent;
use App\Mail\OTP\ForgetPasswordOTP;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordOtpListner
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(ForgetPasswordOtpEvent $event)
    {
        Mail::to($event->email)->send(new ForgetPasswordOTP($event->random));
    }
}
