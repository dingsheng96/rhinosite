<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user()->load([
            'userDetail', 'address',
            'media' => function ($query) {
                $query->when(Auth::user()->is_member, function ($query) {
                    $query->logo();
                })->when(Auth::user()->is_merchant, function ($query) {
                    $query->logo();
                });
            }
        ]);

        $projects = Project::published()
            ->with(['translations', 'user.userDetail'])
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
            ->limit(6)
            ->get();

        return view('dashboard.' . Auth::user()->folder_name, compact('user', 'projects'));
    }
}
