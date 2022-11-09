<?php

namespace Gentcmen\Http\Middleware;
use Gentcmen\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Closure;

class Role extends ApiController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user()->roles;
        $userRole = $user[0]->name ?? '';

        return in_array($userRole, $roles)
            ? $next($request)
            : $this->respondForbidden("You don't have any permissions for this action");
    }
}
