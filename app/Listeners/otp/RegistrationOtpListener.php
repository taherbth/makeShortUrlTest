<?php

namespace App\Listeners\otp;

use App\Events\otp\RegistrationOtpEvent;
use App\Mail\OTP\RegistrationOTP;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class RegistrationOtpListener
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
    public function handle(RegistrationOtpEvent $event)
    {
        Mail::to($event->email)->send(new RegistrationOTP($event->random));
    }
}
