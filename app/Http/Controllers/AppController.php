<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Service;
use App\Models\Rateable;
use App\Models\CountryState;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Models\UserSubscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

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
            $query->validMerchant()
                ->orWhere(function ($query) {
                    $query->freeTierMerchant();
                })
                ->where(app(User::class)->getTable() . '.id', '!=', $project->user_id)
                ->whereHas('service', function ($query) use ($project) {
                    $query->where(app(Service::class)->getTable() . '.id', $project->user->service->id);
                });
        })->inRandomOrder()->take(3)->get();

        $user = '';
        if (auth()->check()) {
            $user = Auth::user()->load([
                'favouriteProjects'
            ]);
        }

        return view('app.project.show', compact('project', 'similar_projects', 'user'));
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

        $ratings = '';

        if (auth()->guard('web')->check()) {

            $user = auth()->user();
            $user->load([
                'ratedBy' => function ($query) use ($merchant) {
                    $query->where('id', $merchant->id);
                }
            ]);

            $rate = $user->ratedBy->first()->pivot->scale ?? null;

            if (!is_null($rate)) {
                for ($i = 0; $i < $rate; $i++) {
                    $ratings .= '<i class="fas fa-star star"></i>';
                }

                for ($y = 0; $y < User::MAX_RATING_SCALE - $rate; $y++) {
                    $ratings .= '<i class="far fa-star star"></i>';
                }
            }
        }

        return view('app.merchant', compact('merchant', 'projects', 'ratings'));
    }

    public function termsPolicies()
    {
        return view('app.terms');
    }

    public function privacyPolicies()
    {
        return view('app.privacy');
    }

    public function management()
    {
        return view('app.management');
    }

    public function updateFreeTrialAccount()
    {
        try {
            DB::beginTransaction();

            $users = User::with([
                'userSubscriptions' => function ($query) {
                    $query->orderByDesc('activated_at');
                }
            ])->whereHas('userSubscriptions', function ($query) {
                $query->inactive();
            })->whereDoesntHave('userSubscriptions', function ($query) {
                $query->active();
            })->freeTierMerchant(false)->get();

            foreach ($users as $user) {
                $user->free_tier = 1;
                $user->save();
                Log::info('id: ' . $user->id . 'name: ' . $user->name . 'Free Tier changed successfully');
            }

            DB::commit();

            return 'Completed';
        } catch (\Exception $message) {
            DB::rollback();
            Log::error($message);

            return 'Something went wrong';
        }
    }
}
