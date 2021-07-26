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
        $merchants = User::with([
            'media' => function ($query) {
                $query->logo();
            },
        ])->merchant()->active()->inRandomOrder()->limit(6)->get();

        $projects = Project::with([
            'user.ratedBy', 'translations', 'address', 'unit', 'services',
            'prices' => function ($query) {
                $query->defaultPrice();
            },
            'media' => function ($query) {
                $query->thumbnail();
            },
            'adsBoosters' => function ($query) {
                $query->boosting();
            }
        ])->published()->inRandomOrder()->limit(6)->get();

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
        ])->whereHas('user', function ($query) {
            $query->merchant()->active();
        })->published()->sortByCategoryBump()
            ->searchable($request->get('q'))->filterable($request)
            ->paginate(12, ['*'], 'page', $request->get('page', 1));

        return view('app.project.index', compact('projects', 'areas'));
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
                $query->merchant()->active();
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

        return view('app.compare');
    }

    public function addToCompareList(Request $request)
    {
        $request->validate([
            'target' => 'required|in:project',
            'target_id' => 'required|exists:' . Project::class . ',id'
        ]);

        $action     =   Permission::ACTION_UPDATE;
        $module     =   strtolower(trans_choice('modules.project', 1));
        $status     =   'fail';
        $message    =   Message::instance()->format($action, $module);
        $count = 0;

        DB::beginTransaction();

        try {

            $project = Project::findOrFail($request->get('target_id'));

            $compare_lists = Auth::user()->comparisons();

            throw_if($compare_lists->count() >= 3, new \Exception(__('messages.compare_list_reached_limit')));

            $compare_lists->sync([$project->id]);

            $compare_lists->fresh();

            $count = $compare_lists->count();

            DB::commit();
        } catch (\Error | \Exception $ex) {

            DB::rollBack();

            $message = $ex->getMessage();
            $status = false;
        }

        return Response::instance()
            ->withStatusCode('modules.project', 'actions.' . $action . $status)
            ->withStatus($status)
            ->withMessage($message, true)
            ->withData(['projects_count' => $count])
            ->sendJson();
    }
}
