<?php

namespace Gentcmen\Http\Controllers\API\OrderQueryFilters;
use Illuminate\Database\Eloquent\Builder;

class OrderBy implements Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value)
    {
        [$order, $column] = explode(',', $value);
        return $builder->orderBy($column ? $column : 'cost', $order == 1 ? 'desc' : 'asc');
    }
}
