<?php

namespace App\Providers;

use App\Events\IncrementUptimeCount;
use App\Listeners\IncrementUptimeCountListener;
use App\Listeners\MonitorEventSubscriber;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class
        ],
        IncrementUptimeCount::class => [
            IncrementUptimeCountListener::class
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        MonitorEventSubscriber::class,
    ];
}
