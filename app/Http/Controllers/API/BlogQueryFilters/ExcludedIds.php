<?php

namespace Gentcmen\Http\Controllers\API\BlogQueryFilters;

use Illuminate\Database\Eloquent\Builder;

class ExcludedIds extends Filter
{
    protected function applyFilters(Builder $builder): Builder
    {
        $excludeIds = explode(",", request($this->filterName()));
        return $builder->whereNotIn('id', $excludeIds);
    }
}
