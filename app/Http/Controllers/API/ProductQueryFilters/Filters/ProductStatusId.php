<?php

namespace Gentcmen\Http\Controllers\API\ProductQueryFilters\Filters;
use Illuminate\Database\Eloquent\Builder;

class ProductStatusId implements Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value): Builder
    {
        $productStatusId = explode(',', $value);

        return $builder->where('product_status_id','=', $productStatusId);
    }
}
