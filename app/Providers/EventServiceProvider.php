<?php

namespace App\Providers;

use App\Events\OrderPaid;
use App\Listeners\SendOrderPaidMail;
use App\Listeners\UpdatePorductSolCount;
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
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderPaid::class => [
            UpdatePorductSolCount::class,
            SendOrderPaidMail::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //event(new App\Events\OrderPaid(App\Models\Order::find(14)))
    }
}
