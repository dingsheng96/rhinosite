<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Helpers\Message;
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
            })->inRandomOrder()->limit(6)->get();

        $projects = Project::with([
            'user.ratedBy', 'translations',
            'unit', 'services', 'address.countryState',
            'prices' => function ($query) {
                $query->defaultPrice();
            },
            'media' => function ($query) {
                $query->thumbnail();
            },
            'adsBoosters' => function ($query) {
                $query->boosting();
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
            'translations', 'address', 'unit', 'services', 'user.ratedBy',
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
            'translations', 'address',
            'unit', 'media', 'services',
            'prices' => function ($query) {
                $query->defaultPrice();
            },
            'user.userDetail' => function ($query) {
                $query->approvedDetails();
            }
        ]);

        $project_services = $project->services;

        $similar_projects = Project::with([
            'services',
            'prices' => function ($query) {
                $query->defaultPrice();
            },
        ])->published()
            ->where('user_id', '!=', $project->user_id)
            ->whereHas('services', function ($query) use ($project_services) {
                $query->whereIn('id', $project_services->pluck('id')->toArray());
            })
            ->whereHas('user', function ($query) {
                $query->with(['userDetail'])->merchant()->active();
            })->inRandomOrder()->take(3)->get();

        return view('app.project.show', compact('project', 'similar_projects', 'project_services'));
    }

    public function showMerchant(Request $request, User $merchant)
    {
        $merchant = $merchant->load([
            'projects.services',
            'userDetail' => function ($query) {
                $query->approvedDetails();
            }
        ]);

        $projects = Project::with([
            'user.ratedBy', 'translations', 'address', 'unit', 'services',
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

    public function compareList()
    {
        $compare_lists = Auth::user()->comparisons()
            ->with([
                'user', 'media', 'translations', 'address', 'services',
                'prices' => function ($query) {
                    $query->defaultPrice();
                }
            ])->get();

        return view('app.compare', compact('compare_lists'));
    }

    public function addToCompareList(Request $request)
    {
        $request->validate([
            'target' => 'required|in:project',
            'target_id' => 'required|exists:' . Project::class . ',id',
        ]);

        $action     =   Permission::ACTION_UPDATE;
        $status     =   'fail';
        $message    =   'Unable to add project to compare list.';
        $data       =   [];

        DB::beginTransaction();

        try {

            $compare_lists = Auth::user()->comparisons();

            if ($request->get('target') == 'project') {

                $project = Project::findOrFail($request->get('target_id'));

                if ($compare_lists->get()->contains($project->id)) {

                    $compare_lists->detach($project->id);

                    $message = 'Project removed from compare list.';
                } else {

                    throw_if($compare_lists->count() >= 3, new \Exception(__('messages.compare_list_reached_limit')));

                    $compare_lists->attach($project->id);

                    $message = 'Project added to compare list.';
                }
            }

            $status = 'success';
            $data = [
                'selected' => $compare_lists->count(),
                'attached' => $compare_lists->pluck('id')
            ];

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();
            $message = $ex->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.project', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->withData($data)
            ->sendJson();
    }

    public function rateUser(Request $request)
    {
        $request->validate([
            'merchant' => ['required', 'exists:' . User::class . ',id'],
            'rating' => ['required', 'in:1,2,3,4,5']
        ]);

        $action     =   Permission::ACTION_CREATE;
        $status     =   'fail';
        $message    =   'Unable to rate contractor.';
        $data       =   [];

        DB::beginTransaction();

        try {

            Auth::user()->ratedBy()->attach([
                $request->get('merchant') => ['scale' => $request->get('rating'), 'created_at' => now()]
            ]);

            DB::commit();

            $rating = User::with('ratings')->where('id', $request->get('merchant'))->first();

            $status = 'success';
            $message = 'Contractor rated successfully.';
            $data = [
                'rated' => true,
                'rating' => $rating->rating_stars
            ];
        } catch (\Error | \Exception $ex) {

            DB::rollBack();
            $message = $ex->getMessage();
        }

        return Response::instance()
            ->withStatusCode('modules.merchant', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message)
            ->withData($data)
            ->sendJson();
    }
}
