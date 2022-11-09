<?php

namespace Gentcmen\Http\Controllers\API\BlogQueryFilters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Actual extends Filter
{
    protected function applyFilters(Builder $builder): Builder
    {
        $from = Carbon::now()->subDays(7);
        $to = Carbon::now();
        return $builder->whereBetween('created_at', [$from, $to]);
    }
}
