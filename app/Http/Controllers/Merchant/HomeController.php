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
    public $user;

    public function index()
    {
        $this->user = Auth::user();

        if ($this->user->is_admin) {
            $data = $this->adminDashboard();
        } elseif ($this->user->is_merchant) {
            $data = $this->merchantDashboard();
        } elseif ($this->user->is_member) {
            $data = $this->memberDashboard();
        }

        return view('dashboard.' . Auth::user()->folder_name, $data);
    }

    private function adminDashboard(): array
    {
        $total_contractors = User::with(['userDetail'])
            ->merchant()->active()
            ->whereHas('userDetail', function ($query) {
                $query->approvedDetails();
            })->count();

        $total_members = User::member()->active()->count();

        $weekly_transaction_amount = Transaction::whereBetween('created_at', [today()->startOfWeek(), today()->endOfWeek()])
            ->success()->sum('amount');

        $listing_projects = Project::with([
            'user' => function ($query) {
                $query->with(['userDetail', 'userSubscriptions']);
            }
        ])->published()->withValidMerchant();

        $total_listing_projects = (clone $listing_projects)->count();

        $monthly_listing_projects = (clone $listing_projects)->select(
            DB::raw('sum(id) as monthly_projects_count'),
            DB::raw("DATE_FORMAT(created_at,'%M %Y') as months")
        )->whereYear('created_at', today()->format('Y'))
            ->groupBy('months')
            ->orderByDesc('months')
            ->get()->toArray();

        $current_boosting_projects = (clone $listing_projects)
            ->whereHas('adsBoosters', function ($query) {
                $query->boosting();
            })->limit(4)->get()->unique();

        $monthly_sales_data = Transaction::select(
            DB::raw('CAST((sum(amount) / 100) AS DECIMAL(11,2)) as sums'),
            DB::raw("DATE_FORMAT(created_at,'%M %Y') as months")
        )->success()->whereYear('created_at', today()->format('Y'))
            ->groupBy('months')
            ->orderByDesc('months')
            ->get()->toArray();

        return [
            'total_contractors' => $total_contractors,
            'total_members' => $total_members,
            'weekly_transaction_amount' => number_format(PriceFacade::convertIntToFloat($weekly_transaction_amount), 2),
            'total_listing_projects' => $total_listing_projects,
            'monthly_listing_projects' => $monthly_listing_projects,
            'current_boosting_projects' => $current_boosting_projects,
            'monthly_sales_data' => $monthly_sales_data,
        ];
    }

    private function merchantDashboard(): array
    {
        $this->user->load([
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
            ->whereHas('user', function ($query) {
                $query->where('id', $this->user->id)->merchant()->active();
            })
            ->orderByDesc('created_at')
            ->limit(4)
            ->get();

        $boosting_projects = Project::with(['user', 'adsBoosters.product.productCategory'])
            ->published()
            ->whereHas('user', function ($query) {
                $query->where('id', $this->user->id)->merchant()->active();
            })
            ->whereHas('adsBoosters', function ($query) {
                $query->whereDate('boosted_at', today());
            })->get()->unique();

        $total_ads_quotas = $this->user->userAdsQuotas->sum('quantity') ?? 0;

        return [
            'user' => $this->user,
            'projects' => $projects,
            'boosting_projects' => $boosting_projects,
            'total_ads_quotas' => $total_ads_quotas
        ];
    }

    private function memberDashboard(): array
    {
        $this->user->load([
            'address', 'favouriteProjects',
            'media' => function ($query) {
                $query->logo();
            }
        ]);

        $projects = Project::with(['translations', 'user.userDetail', 'wishlistedBy'])
            ->published()
            ->whereHas('user', function ($query) {
                $query->merchant()->active();
            })
            ->whereHas('wishlistedBy', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        return [
            'user' => $this->user,
            'projects' => $projects
        ];
    }
}
