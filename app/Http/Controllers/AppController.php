<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\User;
use App\Models\Rating;
use App\Models\Project;
use App\Models\Service;
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
            'ratedBy',
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
        ])->active()->merchant()->whereHas('ratedBy')->get()
            ->mapWithKeys(function ($item) {
                return [
                    strval($item->ratedBy->avg('scale')) => $item
                ];
            })->sortKeysDesc()->take(6)->flatten()->all();


        $top_services = collect($top_merchants)
            ->flatMap(function ($item) {
                return collect($item->project_services)
                    ->map(function ($item) {
                        return $item->name;
                    });
            })->unique()->shuffle()->take(4)->all();

        return view('app.home', compact('top_merchants', 'top_services'));
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
        $search     =   str_replace('+', ' ', request()->get('search'));
        $price      =   $request->get('price');
        $location   =   $request->get('location');
        $rating     =   $request->get('rating');
        $page       =   $request->get('page', 1);

        $top_services = User::with(['ratedBy', 'projects.services'])
            ->active()->merchant()->whereHas('ratedBy')->get()
            ->mapWithKeys(function ($item) {
                return [strval($item->ratedBy->avg('scale')) => $item];
            })
            ->sortKeysDesc()->take(6)->flatMap(function ($item) {
                return collect($item->project_services)
                    ->map(function ($item) {
                        return $item->name;
                    });
            })->unique()->shuffle()->take(6)->all();

        $areas = CountryState::withCount([
            'addresses' => function ($query) {
                $query->whereHasMorph('sourceable', Project::class);
            }
        ])->whereHas('country', function ($query) {
            $query->defaultCountry();
        })->orderBy('name', 'asc')->get()->filter(function ($value) {
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
        ])
            ->published()
            ->when(!empty($search), function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->orWhereHas('translations', function ($query) use ($search) {
                        $query->where('value', 'like', "%{$search}%");
                    })->orWhereHas('services', function ($query) use ($search) {
                        $query->where(app(Service::class)->getTable() . '.name', 'like', "%{$search}%");
                    })->orWhereHas('user', function ($query) use ($search) {
                        $query->where(app(User::class)->getTable() . '.name', 'like', "%{$search}%");
                    })->orWhereHas('address', function ($query) use ($search) {
                        $query->where(function ($query) use ($search) {
                            $query->orWhereHas('city', function ($query) use ($search) {
                                $query->where(app(City::class)->getTable() . '.name', 'like', "%{$search}%");
                            })->orWhereHas('countryState', function ($query) use ($search) {
                                $query->where(app(CountryState::class)->getTable() . '.name', 'like', "%{$search}%");
                            });
                        });
                    });
                });
            })
            ->when(!empty($price), function ($query) use ($price) {
                $range = explode('-', $price);
                $query->whereHas('prices', function ($query) use ($range) {
                    $query->defaultPrice()->priceRange($range[0], $range[1]);
                });
            })
            ->when(!empty($location), function ($query) use ($location) {
                $query->whereHas('address', function ($query) use ($location) {
                    $query->whereHas('countryState', function ($query) use ($location) {
                        $query->where('id', $location);
                    });
                });
            })
            ->when(!empty($request->get('rating')), function ($query) use ($request) {
                $query->whereHas('user', function ($query) use ($request) {
                    $query->filterGivenRatings($request->get('rating'));
                });
            })
            ->paginate(15, ['*'], 'page', $page);

        $links = $projects->appends([
            'search' => $search,
            'price' => $price,
            'location' => $location,
            'rating' => $rating
        ])->links();

        return view('app.project.index', compact('projects', 'links', 'top_services', 'areas'));
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

        $links = $projects->fragment('profile-3')->links();

        return view('app.merchant', compact('merchant', 'projects', 'links'));
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
