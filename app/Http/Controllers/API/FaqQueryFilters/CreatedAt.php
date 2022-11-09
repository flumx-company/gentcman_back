<?php


namespace Gentcmen\Http\Controllers\API\FaqQueryFilters;

use Gentcmen\Http\Controllers\API\OrderQueryFilters\Filter;
use Illuminate\Database\Eloquent\Builder;

class CreatedAt extends Filter
{
    protected function applyFilters(Builder $builder)
    {
        return $builder->whereDate($this->filterName(), '>=' , request($this->filterName()));
    }
}
