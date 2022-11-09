<?php


namespace Gentcmen\Http\Controllers\API\OrderQueryFilters;

use Illuminate\Database\Eloquent\Builder;

class OrderStatusId extends Filter
{
    protected function applyFilters(Builder $builder): Builder
    {
        $params = request($this->filterName());
        $paramsToArray = explode(',', $params);

        return $builder->whereIn($this->filterName(), $paramsToArray);
    }
}
