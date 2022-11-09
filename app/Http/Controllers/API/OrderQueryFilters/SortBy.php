<?php

namespace Gentcmen\Http\Controllers\API\OrderQueryFilters;

use Illuminate\Database\Eloquent\Builder;

class SortBy extends Filter
{
    protected function applyFilters(Builder $builder): Builder
    {
        return $builder->orderBy('created_at', request($this->filterName()));
    }
}
