<?php


namespace Gentcmen\Http\Controllers\API\ProductQueryFilters\Filters;
use Illuminate\Database\Eloquent\Builder;

class PromotionalGoods implements Filter
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
        return $builder->whereHas('discounts')
            ->orWhereHas('productCategories.discounts')
            ->limit(intval($value) > 7 ? 7 : $value);
    }
}
