<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'order_products';

    protected $fillable = [
        'order_id',
        'coupon_id',
        'product_id',
        'quantity'
    ];

    /**
     * Get product
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function product(): \Illuminate\Database\Eloquent\Relations\hasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id')->select('id', 'name', 'cost', 'amount', 'banner_image', 'product_status_id', 'created_at');
    }
}
