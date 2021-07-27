<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckVerifiedMerchant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user()->load(['userDetail']);

        if (!$user->is_merchant || $user->userDetail()->approvedDetails()->exists()) { // user is not merchant or merchant user has verified details

            return $next($request);
        }

        if ($user->userDetail()->where(function ($query) { // merchant with pending or rejected details
            $query->pendingDetails();
        })->orWhere(function ($query) {
            $query->rejectedDetails();
        })->exists()) {

            return redirect()->route('verifications.notify');
        }

        return redirect()->route('verifications.create');
    }
}
