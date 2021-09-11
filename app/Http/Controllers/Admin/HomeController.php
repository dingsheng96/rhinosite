<?php

namespace App\Http\Controllers\Admin;

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
        $total_contractors = User::with(['userDetail'])
            ->merchant()->active()
            ->whereHas('userDetail', function ($query) {
                $query->approvedDetails();
            })->count();

        $total_members = User::member()->active()->count();

        $weekly_transaction_amount = Transaction::whereBetween('created_at', [today()->startOfWeek(), today()->endOfWeek()])
            ->success()->sum('amount');

        $listing_projects = Project::with([
            'adsBoosters' => function ($query) {
                $query->boosting();
            },
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

        return view('admin.dashboard', [
            'total_contractors' => $total_contractors,
            'total_members' => $total_members,
            'weekly_transaction_amount' => number_format(PriceFacade::convertIntToFloat($weekly_transaction_amount), 2),
            'total_listing_projects' => $total_listing_projects,
            'monthly_listing_projects' => $monthly_listing_projects,
            'current_boosting_projects' => $current_boosting_projects,
            'monthly_sales_data' => $monthly_sales_data,
        ]);
    }
}
