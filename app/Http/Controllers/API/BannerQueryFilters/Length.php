<?php


namespace Gentcmen\Http\Controllers\API\BannerQueryFilters;

use Illuminate\Database\Eloquent\Builder;

class Length extends Filter
{
    protected function applyFilters(Builder $builder): Builder
    {
//dd($this->filterName());
        return $builder->take(request($this->filterName()));
    }
}
