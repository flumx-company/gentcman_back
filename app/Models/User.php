<?php

namespace Gentcmen\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'referred_by',
        'name',
        'surname',
        'avatar',
        'phone',
        'email',
        'country',
        'email_verified_at',
        'city',
        'house',
        'street',
        'apartment',
        'password',
        'google_id',
        'facebook_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * Set the user's phone number.
     *
     * @param  string  $value
     * @return void
     */

    public function setPhoneAttribute(string $value)
    {
        $this->attributes['phone'] = '+' . $value;
    }

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    /**
     * Helpers
     */

    /**
     * Increment user's bonus points
     * @param int $amount
     * @param array $extra
     * @return int
     */

    public function incrementBonusPoints(int $amount = 1, array $extra = []): int
    {
        return $this->bonusPoints()->increment('points', $amount, $extra);
    }

    /**
     * Decrement bonus points for user
     * @param int $amount
     * @param array $extra
     * @return int
     */

    public function decrementBonusPoints(int $amount = 1, array $extra = []): int
    {
        return $this->bonusPoints()->decrement('points', $amount, $extra);
    }

    /**
     * Attach coupons to user
     * @param array|int|Model $coupons
     * @param string $status
     * @return bool
     */

    public function attachCoupon(Model|array|int $coupons, string $status): bool
    {
        $this->coupons()->attach($coupons, [
            'status' => $status
        ]);

        return true;
    }

    /**
     * Detach coupons from user
     * @param int|array|Model $coupons
     * @param string $status
     * @param bool $forceDelete
     * @return bool
     */

    public function detachCoupon(Model|int|array $coupons, string $status, bool $forceDelete = false): bool
    {
	
        if ($forceDelete) {
            $this->coupons()->detach($coupons);
        } else {
            $this->coupons()->syncWithPivotValues($coupons, [
                'status' => $status,
                'deleted_at' => Carbon::now()
            ], false);
        }

        return true;
    }

    /**
     * Set up user's available referrals links
     * @return void
     */

    public function initReferrals()
    {
        \Gentcmen\Models\ReferralProgram::all()
            ->map(function ($program) {
                return  \Gentcmen\Models\ReferralLink::getReferral($this, $program);
            });
    }

    /**
     * Relationships
     */

    /**
     * Get referral links
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function referralLinks(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ReferralLink::class);
    }

    /**
     * Fetch admins
     * @return \Illuminate\Database\Eloquent\Collection|Builder[]
     */

    static public function scopeFetchAdmins(Builder $query): array|\Illuminate\Database\Eloquent\Collection
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('roles.id', Role::IS_ADMIN);
        })
            ->get();
    }

    /**
     * Get user's roles
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * Fetch favorites user's items
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function favorites(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get user's orders
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the reviews the user has made.
     */
    public function reviews(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the bonus points that the user has
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */

    public function bonusPoints(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BonusPoint::class);
    }

    /**
     * Get user's coupons
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function coupons(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Coupon::class)
            ->withTimestamps()
            ->withPivot('available', 'status', 'deleted_at');
    }

    /**
     * Invited in the system by user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function referred_by(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return array|string
     */
    public function routeNotificationForMail($notification): array|string
    {
        return env('MAIL_FROM_ADDRESS');
    }
}
