<?php

namespace Gentcmen\Http\Controllers\API\ProductQueryFilters\Filters;
use Illuminate\Database\Eloquent\Builder;

class OrderBy implements Filter
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
        $array = explode(',', $value);
	$order = array_key_exists(0, $array) ? $array[0] : null;
	$column = array_key_exists(1, $array) ? $array[1] : null;

	return $builder->orderBy($column ? $column : 'cost', $order == 1 ? 'desc' : 'asc');
    }
}
