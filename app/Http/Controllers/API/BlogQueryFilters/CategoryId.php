<?php

namespace Gentcmen\Http\Controllers\API\BlogQueryFilters;

use Illuminate\Database\Eloquent\Builder;

class CategoryId extends Filter
{
    protected function applyFilters(Builder $builder): Builder
    {
        return $builder->where($this->filterName(), request($this->filterName()));
    }
}
