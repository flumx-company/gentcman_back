<?php


namespace Gentcmen\Http\Controllers\API\BlogQueryFilters;
use Illuminate\Database\Eloquent\Builder;

class Title extends Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */

protected function applyFilters(Builder $builder): Builder
    {
        return $builder->where('title', 'like', '%' . request($this->filterName()) . '%');//->orWhere('description', '%' . request($this->filterName()) . '%');
    }

}
