<?php

namespace Gentcmen\Http\Controllers\API\BlogQueryFilters;

use Illuminate\Database\Eloquent\Builder;

class BlogTypeId extends Filter
{
    protected function applyFilters(Builder $builder): Builder
    {
        return $builder->whereHas('type', function ($query) {
            $query->where('id', request($this->filterName()));
        });
    }
}
