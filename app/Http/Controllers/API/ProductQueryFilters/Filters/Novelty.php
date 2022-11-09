<?php


namespace Gentcmen\Http\Controllers\API\ProductQueryFilters\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Novelty implements Filter
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
        return $builder->whereDate('created_at', '>' , Carbon::today()->subDays(7));
    }
}
