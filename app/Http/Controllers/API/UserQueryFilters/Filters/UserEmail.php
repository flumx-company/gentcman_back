<?php

namespace Gentcmen\Http\Controllers\API\UserQueryFilters\Filters;
use Illuminate\Database\Eloquent\Builder;

class UserEmail implements Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value)
    {
        return $builder->where('email', 'like', '%' . $value . '%');
    }
}
