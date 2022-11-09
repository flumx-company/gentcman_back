<?php

namespace Gentcmen\Http\Controllers\API\ProductQueryFilters\Filters;
use Illuminate\Database\Eloquent\Builder;

class WidthMaterial implements Filter
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
        [$from , $to] = explode(',', $value);
        return $builder->whereBetween('content->width_material->value', [$from, $to]);
    }
}
