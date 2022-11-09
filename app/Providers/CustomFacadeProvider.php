<?php


namespace Gentcmen\Providers;


use Gentcmen\Http\Services\Discounts;
use Gentcmen\Http\Services\ImageService;
use Illuminate\Support\ServiceProvider;

class CustomFacadeProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('images', function () {
            return $this->app->make(ImageService::class);
        });

        $this->app->singleton('discounts', function () {
            return $this->app->make(Discounts::class);
        });
    }
}
