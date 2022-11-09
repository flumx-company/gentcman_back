<?php


namespace Gentcmen\Http\Controllers\API\BlogQueryFilters;


use Illuminate\Database\Eloquent\Builder;

class AuthorEmail extends Filter
{
    protected function applyFilters(Builder $builder): Builder
    {
        return $builder->whereHas('author', function ($query) {
            $query->where('email', request($this->filterName()));
        });
    }
}
