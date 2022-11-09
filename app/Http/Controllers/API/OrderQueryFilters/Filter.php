<?php


namespace Gentcmen\Http\Controllers\API\OrderQueryFilters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    public function handle(Builder $request, Closure $next)
    {
        if( ! request()->has($this->filterName())){
            return $next($request);
        }

        $builder = $next($request);

        return $this->applyFilters($builder);
    }

    protected abstract function applyFilters(Builder $builder);

    protected function filterName(): string
    {
        return classNameToSnakeCase($this);
    }
}
