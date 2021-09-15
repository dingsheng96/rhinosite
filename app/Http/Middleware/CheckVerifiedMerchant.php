<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Models\UserDetail;
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
        if (User::where('id', Auth::id())->validMerchant()->exists()) { // Valid Merchant: merchant type, active, active subscription, approved details, has service

            return $next($request);
        }

        $merchant = User::withCount([
            'userDetail', 'userSubscriptions' => function ($query) {
                $query->active();
            }
        ])->with(['userDetail'])->where('id', Auth::id())->merchant()->active()->first();

        if ($merchant->user_detail_count > 0 && $merchant->userDetail->status != UserDetail::STATUS_APPROVED) { // merchant without user detail, redirect to details form

            return redirect()->route('merchant.verifications.notify');
        } else {

            return redirect()->route('merchant.verifications.create');
        }

        if ($merchant->user_subscriptions_count < 1) { // merchant without any subscriptions, redirect to subscription list
            return redirect()->route('merchant.subscriptions.index');
        }
    }
}
