<?php

namespace Gentcmen\Events;

use Gentcmen\Models\Coupon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserUsedCouponAtPurchaseEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $coupon;
    public $userId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Coupon $coupon, int $userId)
    {
        $this->coupon = $coupon;
        $this->userId = $userId;
    }
}
