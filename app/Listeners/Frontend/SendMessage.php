<?php

namespace App\Listeners\Frontend;

use Illuminate\Support\Facades\Mail;
use App\Mail\Frontend\SendMessageMail;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Frontend\SendMessageRequest;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendMessage
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
     * @param  SendMessageRequest  $event
     * @return void
     */
    public function handle(SendMessageRequest $event)
    {
        Mail::to($event->email)->send(new SendMessageMail($event->msg));
    }
}
