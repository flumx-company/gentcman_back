<?php

namespace Gentcmen\Http\Controllers\API\ProductQueryFilters\Filters;
use Illuminate\Database\Eloquent\Builder;

class ProductCategoryId implements Filter
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
        $productCategoryIds = explode(',', $value);

        return $builder->whereHas('productCategories', function ($query) use ($productCategoryIds){
            $query->whereIn('product_category_id', $productCategoryIds);
        });
    }
}
