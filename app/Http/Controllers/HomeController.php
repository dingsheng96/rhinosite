<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load([
            'address', 'userAdsQuotas',
            'userDetail' => function ($query) {
                $query->approvedDetails();
            },
            'media' => function ($query) {
                $query->when(Auth::user()->is_member, function ($query) {
                    $query->logo();
                })->when(Auth::user()->is_merchant, function ($query) {
                    $query->logo();
                });
            }
        ]);

        $projects = Project::with(['translations', 'user.userDetail'])
            ->published()
            ->whereHas('user', function ($query) {
                $query->merchant()->active();
            })
            ->when(Auth::user()->is_merchant, function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->when(Auth::user()->is_member, function ($query) {
                $query->whereHas('wishlists', function ($query) {
                    $query->where('user_id', Auth::id());
                });
            })
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $boosting_projects = Project::with('adsBoosters.productAttribute.product.productCategory')
            ->whereHas('adsBoosters', function ($query) {
                $query->whereDate('boosted_at', today());
            })->get();

        $total_ads_quotas = $user->userAdsQuotas->sum('quantity') ?? 0;

        return view('dashboard.' . Auth::user()->folder_name, compact('user', 'projects', 'boosting_projects', 'total_ads_quotas'));
    }
}
