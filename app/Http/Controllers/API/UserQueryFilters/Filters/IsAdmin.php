<?php


namespace Gentcmen\Http\Controllers\API\UserQueryFilters\Filters;

use Gentcmen\Models\Role;
use Illuminate\Database\Eloquent\Builder;

class IsAdmin implements Filter
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value): Builder
    {
	$role = $value === 1 ? Role::IS_ADMIN : Role::IS_USER;

	
        return $builder->whereHas('roles', function ($query) use ($role) {
            $query->where('roles.id', $role);
        });
    }
}
