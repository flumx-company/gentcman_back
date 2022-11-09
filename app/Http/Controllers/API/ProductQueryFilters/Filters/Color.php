<?php

namespace Gentcmen\Http\Controllers\API\ProductQueryFilters\Filters;
use Illuminate\Database\Eloquent\Builder;

class Color implements Filter
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
        $arr = explode(',', $value);
        return $builder->whereIn('content->color->value', $arr);
    }
}
