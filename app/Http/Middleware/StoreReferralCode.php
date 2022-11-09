<?php

namespace Gentcmen\Http\Middleware;

use Gentcmen\Models\ReferralLink;
use Closure;
use Illuminate\Http\Request;

class StoreReferralCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('ref'))
        {
            $referral = ReferralLink::whereCode($request->get('ref'))->first();
            $request->headers->add(['referral_id' => $referral->id]);
//            $next($request)->cookie('ref', $referral->id, $referral->lifetime_minutes);
        }

        return $next($request);
    }
}
