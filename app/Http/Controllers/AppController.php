<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use App\Models\Rating;
use App\Models\Project;
use App\Models\Service;
use App\Models\AdsBooster;
use App\Models\CountryState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\View\Composers\TopRecordsComposer;

class AppController extends Controller
{
    public function home()
    {
        $top_merchants = User::with([
            'address' => function ($query) {
                $query->with(['city', 'countryState']);
            },
            'media' => function ($query) {
                $query->logo();
            },
            'projects' => function ($query) {
                $query->with([
                    'prices' => function ($query) {
                        $query->priceWithDefaultCurrency();
                    },
                    'services'
                ]);
            }
        ])
            // ->sortMerchantByRating()
            ->merchant()->active()->inRandomOrder()
            ->limit(6)->get();

        return view('app.home', compact('top_merchants'));
    }

    public function about()
    {
        return view('app.about');
    }

    public function partner()
    {
        return view('app.partner');
    }

    public function contact()
    {
        return view('app.contact');
    }

    public function project(Request $request)
    {
        $services = Service::orderBy('name', 'asc')->get();

        $areas = CountryState::withCount([
            'addresses' => function ($query) {
                $query->whereHasMorph('sourceable', Project::class);
            }
        ])->hasDefaultCountry()->orderBy('name', 'asc')->get()
            ->filter(function ($value) {
                return $value->addresses_count > 0;
            });

        $projects = Project::with([
            'user.ratedBy', 'translations', 'address', 'unit',
            'prices' => function ($query) {
                $query->defaultPrice();
            },
            'media' => function ($query) {
                $query->thumbnail();
            },
            'adsBoosters' => function ($query) {
                $query->boosting();
            }
        ])->published()->sortByCategoryBump()
            ->searchable($request->get('q'))->filter($request)
            ->paginate(15, ['*'], 'page', $request->get('page', 1));

        return view('app.project.index', compact('projects', 'areas', 'services'));
    }

    public function showProject(Project $project)
    {
        $project = $project->load([
            'user.ratedBy', 'translations', 'address',
            'unit', 'media', 'services',
            'prices' => function ($query) {
                $query->defaultPrice();
            },
        ]);

        $project_services = $project->services;

        $similar_projects = Project::published()
            ->where('user_id', '!=', $project->user_id)
            ->whereHas('services', function ($query) use ($project_services) {
                $query->whereIn('id', $project_services->pluck('id')->toArray());
            })->inRandomOrder()->take(3)->get();

        return view('app.project.show', compact('project', 'similar_projects', 'project_services'));
    }

    public function showMerchant(Request $request, User $merchant)
    {
        $merchant = $merchant->load([
            'projects.services',
            'userDetails' => function ($query) {
                $query->approvedDetails();
            }
        ]);

        $projects = Project::with([
            'user.ratedBy', 'translations', 'address', 'unit',
            'prices' => function ($query) {
                $query->defaultPrice();
            },
            'media' => function ($query) {
                $query->thumbnail();
            }
        ])->published()
            ->where('user_id', $merchant->id)
            ->paginate(15, ['*'], 'page', $request->get('page', 1));

        return view('app.merchant', compact('merchant', 'projects'));
    }

    public function termsPolicies()
    {
        return view('app.terms');
    }

    public function privacyPolicies()
    {
        return view('app.privacy');
    }
}
