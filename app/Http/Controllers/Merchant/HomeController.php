<?php

namespace App\Http\Controllers\Merchant;

use App\Models\User;
use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Support\Facades\PriceFacade;
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
                $query->logo();
            }
        ]);

        $projects = Project::with(['translations', 'user.userDetail', 'address'])
            ->published()
            ->whereHas('user', function ($query) use ($user) {
                $query->where('id', $user->id)->validMerchant();
            })
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $boosting_projects = Project::with([
            'user', 'adsBoosters' => function ($query) {
                $query->boosting();
            }
        ])->published()->whereHas('user', function ($query) use ($user) {
            $query->where('id', $user->id)->validMerchant();
        })->whereHas('adsBoosters', function ($query) {
            $query->whereDate('boosted_at', today());
        })->get()->unique();

        $total_ads_quotas = $user->userAdsQuotas->sum('quantity') ?? 0;

        return view('merchant.dashboard', [
            'user' => $user,
            'projects' => $projects,
            'boosting_projects' => $boosting_projects,
            'total_ads_quotas' => $total_ads_quotas
        ]);
    }
}
