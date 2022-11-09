<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_status_id',
        'coupon_id',
        'grand_total',
        'shipping_cost',
        'billing_email',
        'billing_phone',
        'billing_delivery_type',
        'billing_payment_type',
	'billing_city',
	'billing_user_name',
	'billing_street',
	'billing_house',
	'billing_apartment',
	'message',
	'message_from_user',
	'department'
    ];

    /**
     * Set the user's phone number.
     *
     * @param  string  $value
     * @return void
     */

    public function setBillingPhoneAttribute(string $value)
    {
        $this->attributes['billing_phone'] = $value;
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderStatus(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function orderProducts(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this
	    ->hasMany(OrderProduct::class)
	    ->with('product');
    }

}
