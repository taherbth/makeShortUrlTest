<?php

namespace App\Providers;

use App\Events\OTPrequest;
use App\Listeners\OTPsend;
use App\Events\SendMessageRequest;
use App\Listeners\SendMessage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        OTPrequest::class => [
            OTPsend::class,
        ],
        SendMessageRequest::class => [
            SendMessage::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
