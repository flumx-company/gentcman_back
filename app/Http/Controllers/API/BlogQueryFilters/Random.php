<?php

namespace Gentcmen\Http\Controllers\API\BlogQueryFilters;

use Illuminate\Database\Eloquent\Builder;

class Random extends Filter
{
    protected function applyFilters(Builder $builder): Builder
    {
        return $builder->inRandomOrder();
    }
}
