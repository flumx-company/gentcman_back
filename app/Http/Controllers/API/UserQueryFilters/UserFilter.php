<?php

namespace Gentcmen\Http\Controllers\API\UserQueryFilters;

use Illuminate\Http\Request;
use Gentcmen\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class UserFilter
{

    public static function apply(Request $filters)
    {
        $query = static::applyDecoratorsFromRequest($filters, (new User)->newQuery());
        return static::getResults($query, $filters);
    }

    private static function applyDecoratorsFromRequest(Request $request, Builder $query)
    {
        foreach ($request->all() as $filterName => $value) {
            $decorator = static::createFilterDecorator($filterName);
            if (static::isValidDecorator($decorator)) {
                $query = $decorator::apply($query, $value);
            }
        }
        return $query;
    }

    /**
     * @return string
     */
    private static function createFilterDecorator($name)
    {
        $filter_param = Str::studly($name);
        return __NAMESPACE__ . "\\Filters\\" . $filter_param;
    }

    /**
     * @return boolean
     */

    private static function isValidDecorator($decorator)
    {
        return class_exists($decorator);
    }

    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    private static function getResults(Builder $query, Request $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $query
            ->with('orders', 'roles', 'bonusPoints')
            ->paginate(10);
    }
}
