<?php

namespace Gentcmen\Providers;

use Gentcmen\Models\Basket;
use Gentcmen\Models\Coupon;
use Gentcmen\Models\Review;
use Gentcmen\Models\Role;
use Gentcmen\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\Response;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
//         'Gentcmen\Models\Model' => 'Gentcmen\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        If (! $this->app->routesAreCached()) {
            Passport::routes();
        }

        Gate::define('update-review', function (User $user, Review $review) {
            return $user->id === $review->user_id ? Response::allow() : Response::deny('You do not have any rights and permissions');
        });

        Gate::define('update-admin', function (User $user, User $admin) {
            $isAllow = array_search(Role::IS_ADMIN, array_column($admin->roles->toArray(), 'id')) !== false;
            return $isAllow ? Response::allow() : Response::deny('You do not have any rights and permissions');
        });

        Gate::define('update-bucket', function (User $user, Basket $bucket) {
           return $bucket->user_id === auth()->id() ? Response::allow() : Response::deny('You do not have any rights and permissions');
        });

        Gate::define('buy-coupon', function (User $user, Coupon $coupon) {
            return $user->bonusPoints->points >= $coupon->cost
                ? Response::allow()
                : Response::deny('Insufficient number of points on the account');
        });
    }
}
