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
        $user = Auth::user();

        if (!$user->is_merchant || $user->userDetails()->approvedDetails()->exists()) { // user is not merchant or merchant user has verified details

            return $next($request);
        }

        if ($user->userDetails()->pendingDetails()->exists()) {

            return redirect()->route('verifications.notify');
        }

        return redirect()->route('verifications.create');
    }
}
