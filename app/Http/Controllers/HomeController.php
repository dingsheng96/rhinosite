<?php

namespace App\Http\Controllers;

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


        return view('member.dashboard', compact('user', 'projects'));
    }
}
