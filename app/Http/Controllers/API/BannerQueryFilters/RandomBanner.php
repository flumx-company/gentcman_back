<?php


namespace Gentcmen\Http\Controllers\API\BannerQueryFilters;

use Illuminate\Database\Eloquent\Builder;

class RandomBanner extends Filter
{
    protected function applyFilters(Builder $builder): Builder
    {
        return $builder->inRandomOrder();
    }
}
