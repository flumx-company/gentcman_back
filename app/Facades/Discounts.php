<?php


namespace Gentcmen\Facades;

use Illuminate\Support\Facades\Facade;

class Discounts extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'discounts';
    }
}
