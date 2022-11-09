<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CouponUser extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Constants
     * @var String
     */
    public const STATUS_ACCRUED = 'accrued';
    public const STATUS_DELETED = 'deleted';
    public const STATUS_APPLIED = 'applied';
    public const STATUS_BOUGHT = 'bought';

    protected $table = 'coupon_user';

    protected $dates = ['deleted_at'];

    /**
     * Relationships
     */

    public function coupon(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }
}
