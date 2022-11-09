<?php


namespace Gentcmen\Http\Controllers\API\FaqQueryFilters;

use Gentcmen\Http\Controllers\API\OrderQueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class Status extends Filter
{
    protected function applyFilters(Builder $builder): Builder
    {
        return $builder->where($this->filterName(), request($this->filterName()));
    }
}
