<?php

namespace Gentcmen\Http\Controllers\API\ProductQueryFilters\Filters;
use Illuminate\Database\Eloquent\Builder;

class Price implements Filter
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
	$value = $value != null ? $value : '';
//dd($value);

	if($value != null) {
	    [$from , $to] = explode(',', $value);
	    return $builder->whereBetween('cost', [$from, $to]);
	} else {
	    return $builder;
	}
    }
}
