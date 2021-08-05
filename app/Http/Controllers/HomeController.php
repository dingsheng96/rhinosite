<?php

namespace App\Http\Controllers;

use App\Models\Project;
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
        return [];
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

        $projects = Project::with(['translations', 'user.userDetail'])
            ->published()
            ->whereHas('user', function ($query) {
                $query->merchant()->active();
            })
            ->when($this->user->is_merchant, function ($query) {
                $query->where('user_id', $this->user->id);
            })
            ->when($this->user->is_member, function ($query) {
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
            'address', 'wishlists',
            'media' => function ($query) {
                $query->logo();
            }
        ]);

        return [
            'user' => $this->user
        ];
    }
}
