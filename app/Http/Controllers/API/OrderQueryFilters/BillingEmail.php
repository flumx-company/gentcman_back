<?php


namespace Gentcmen\Http\Controllers\API\OrderQueryFilters;

use Illuminate\Database\Eloquent\Builder;

class BillingEmail extends Filter
{
    protected function applyFilters(Builder $builder): Builder
    {
        return $builder->where($this->filterName(), 'like', '%' . request($this->filterName()) . '%');
    }
}
