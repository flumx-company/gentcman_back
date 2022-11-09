<?php

namespace Gentcmen\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coupons';

    protected $fillable = ['name', 'cost', 'discount', 'expires_at'];

    protected $dates = ['deleted_at'];

    public function scopeNotExpired(Builder $query): Builder
    {
        return $query->whereDate('expires_at', '>=', now());
    }

    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot( 'available', 'status', 'deleted_at');
    }

    /**
     * Get the parent couponable model.
     */
    public function couponable()
    {
        return $this->morphTo();
    }
}
