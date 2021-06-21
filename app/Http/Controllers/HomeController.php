<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Models\Settings\Role\Role;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $projects = Project::listing(true)
            ->with(['translations'])
            ->when(Auth::user()->role_name != Role::ROLE_SUPER_ADMIN, function ($query) {
                $query->where('user_id', Auth::user()->id);
            })
            ->orderBy('created_at', 'desc');

        $projects_count =   (clone $projects)->count();
        $projects_list  =   (clone $projects)->take(4);

        $data = compact('projects_count', 'projects_list');

        return view('dashboard.' . Auth::user()->folder_name, $data);
    }
}
