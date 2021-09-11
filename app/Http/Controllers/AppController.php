<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Service;
use App\Helpers\Response;
use App\Models\Permission;
use App\Models\CountryState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

class AppController extends Controller
{
    public function home()
    {
        $merchants = User::with(['userDetail', 'media', 'userSubscriptions'])
            ->merchant()->active()
            ->withActiveSubscription()
            ->withApprovedDetails()
            ->whereHas('media', function ($query) {
                $query->logo();
            })->inRandomOrder()->limit(12)->get();

        $projects = Project::with([
            'translations', 'unit', 'address.countryState',
            'prices' => function ($query) {
                $query->defaultPrice();
            },
            'media' => function ($query) {
                $query->thumbnail();
            },
            'adsBoosters' => function ($query) {
                $query->boosting();
            },
            'user' => function ($query) {
                $query->with(['ratedBy', 'service']);
            }
        ])->published()->withValidMerchant()
            ->inRandomOrder()->limit(6)->get();

        return view('app.home', compact('projects', 'merchants'));
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
        $areas = CountryState::withCount([
            'addresses' => function ($query) {
                $query->whereHasMorph('sourceable', Project::class);
            }
        ])->hasDefaultCountry()->orderBy('name', 'asc')->get()
            ->filter(function ($value) {
                return $value->addresses_count > 0;
            });

        $projects = Project::with([
            'translations', 'address', 'unit', 'user.ratedBy',
            'prices' => function ($query) {
                $query->defaultPrice();
            },
            'media' => function ($query) {
                $query->thumbnail();
            },
            'adsBoosters' => function ($query) {
                $query->boosting();
            }
        ])->withValidMerchant()->published()->sortByCategoryBump()
            ->searchable($request->get('q'))->filterable($request)
            ->paginate(12, ['*'], 'page', $request->get('page', 1));

        return view('app.project.index', compact('projects', 'areas'));
    }

    public function showProject(Project $project)
    {
        $project = $project->load([
            'translations', 'address', 'unit', 'media',
            'prices' => function ($query) {
                $query->defaultPrice();
            },
            'user' => function ($query) {
                $query->with([
                    'userDetail' => function ($query) {
                        $query->with(['service'])->approvedDetails();
                    }
                ]);
            }
        ]);

        $similar_projects = Project::with([
            'prices' => function ($query) {
                $query->defaultPrice();
            },
            'user' => function ($query) {
                $query->with([
                    'service',
                    'userDetail' => function ($query) {
                        $query->approvedDetails();
                    }
                ]);
            }
        ])->published()->whereHas('user', function ($query) use ($project) {
            $query->merchant()->active()
                ->where(app(User::class)->getTable() . '.id', '!=', $project->user_id)
                ->whereHas('service', function ($query) use ($project) {
                    $query->where(app(Service::class)->getTable() . '.id', $project->user->service->id);
                });
        })->inRandomOrder()->take(3)->get();

        return view('app.project.show', compact('project', 'similar_projects'));
    }

    public function showMerchant(Request $request, User $merchant)
    {
        $merchant = $merchant->load([
            'projects' => function ($query) {
                $query->published();
            },
            'userDetail' => function ($query) {
                $query->with(['service'])->approvedDetails();
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
