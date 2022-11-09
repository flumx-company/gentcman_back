<?php


namespace Gentcmen\Http\Controllers\API\BannerQueryFilters;

use Illuminate\Database\Eloquent\Builder;

class Published extends Filter
{
    protected function applyFilters(Builder $builder): Builder
    {
        return $builder->where($this->filterName(), request($this->filterName()));
    }
}
