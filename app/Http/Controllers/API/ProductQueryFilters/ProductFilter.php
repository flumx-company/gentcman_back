<?php

namespace Gentcmen\Http\Controllers\API\ProductQueryFilters;

use Illuminate\Http\Request;
use Gentcmen\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class ProductFilter
{

    public static function apply(Request $filters): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $query = static::applyDecoratorsFromRequest($filters, (new Product)->newQuery());
        return static::getResults($query, $filters);
    }

    private static function applyDecoratorsFromRequest(Request $request, Builder $query): Builder
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
     * @param $name
     * @return string
     */

    private static function createFilterDecorator($name): string
    {
        $filter_param = Str::studly($name);
        return __NAMESPACE__ . "\\Filters\\" . $filter_param;
    }

    /**
     * @param $decorator
     * @return boolean
     */

    private static function isValidDecorator($decorator): bool
    {
        return class_exists($decorator);
    }

    /**
     * @param Builder $query
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */

    private static function getResults(Builder $query, Request $request): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $take = $request->query('limit') ?? 10;

        return $query
	    ->with('productCategories', 'productStatus', 'discounts')
	    ->withCount('reviews')
	    ->paginate($take);
    }
}
