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
        $user = Auth::user()->load([
            'userDetail',
            'userSubscriptions' => function ($query) {
                $query->active();
            }
        ]);

        if (!$user->is_merchant || ($user->is_merchant && $user->userDetail()->approvedDetails()->exists() && $user->userSubscriptions->first())) { // non-merchant or merchant with approved details, and active subsriptions

            return $next($request);
        }

        if (empty($user->userDetail) || $user->userDetail()->where(function ($query) { // merchant with pending or rejected details, redirect to verification notify page
            $query->pendingDetails();
        })->orWhere(function ($query) {
            $query->rejectedDetails();
        })->exists()) {

            return redirect()->route('verifications.notify');
        }

        if (!$user->userSubscriptions->first()) { // merchant without any subscriptions, redirect to subscription list

            return redirect()->route('subscriptions.index');
        }

        return redirect()->route('verifications.create');
    }
}
