<?php

namespace Gentcmen\Providers;

use Gentcmen\Models\Coupon;
use Gentcmen\Observers\CouponObserver;
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
        \Gentcmen\Events\ProductRedeemedEvent::class => [
            \Gentcmen\Listeners\ProductRedeemedListener::class
        ],
        \Gentcmen\Events\UserReferredEvent::class => [
            \Gentcmen\Listeners\RewardRegisteredUserListener::class,
        ],
        \Gentcmen\Events\UserUsedCouponAtPurchaseEvent::class => [
            \Gentcmen\Listeners\UserUsedCouponAtPurchaseListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Coupon::observe(CouponObserver::class);
    }
}
