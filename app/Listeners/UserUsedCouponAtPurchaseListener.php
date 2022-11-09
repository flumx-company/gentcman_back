<?php

namespace Gentcmen\Listeners;

use Gentcmen\Events\UserUsedCouponAtPurchaseEvent;
use Gentcmen\Models\CouponUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UserUsedCouponAtPurchaseListener
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
     * @param  UserUsedCouponAtPurchaseEvent  $event
     * @return void
     */
    public function handle(UserUsedCouponAtPurchaseEvent $event)
    {
        $event->coupon->users()->updateExistingPivot($event->userId, ['status' => CouponUser::STATUS_APPLIED]);
    }
}
