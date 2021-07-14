@extends('layouts.master', ['title' => __('modules.dashboard')])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card card-widget widget-user">
                <div class="widget-user-header bg-info">
                    <h3 class="widget-user-username">{{ Auth::user()->name ?? '' }}</h3>
                    <h6 class="widget-user-desc">
                        {{ __('labels.joined_since') . ': ' . Auth::user()->created_at->format('Y') }}
                    </h6>
                </div>

                <div class="widget-user-image">
                    <img src="{{ optional(Auth::user()->logo)->full_file_path ?? $default_preview }}" alt="user" class="elevation-2 img-circle img-responsive" style="height: 10vh;">
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 d-flex flex-column align-items-md-end align-items-center mt-3 mt-md-0">
                            <a role="button" href="{{ route('account.index', '#profile') }}" class="mt-auto">
                                <i class="fas fa-edit"></i>
                                {{ __('labels.edit', ['module' => __('modules.profile')]) }}
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header"><i class="fas fa-phone"></i></h5>
                                <span class="description-text">{{ Auth::user()->formatted_phone_number ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header"><i class="fas fa-envelope"></i></h5>
                                <span class="description-text">{{ Auth::user()->email ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header"><i class="fas fa-map-marker-alt"></i></h5>
                                <span class="description-text">{{ Auth::user()->full_address ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card-deck">
                <div class="card small-box bg-info">
                    <div class="card-body">
                        <div class="inner">
                            <p>{{ __('labels.listed_projects') }}</p>
                            <h3>{{ $projects_count ?? 0 }}</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                </div>
                <div class="card small-box bg-teal">
                    <div class="card-body">
                        <div class="inner">
                            <p>{{ __('labels.current_ads_boosting') }}</p>
                            <h3>150</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-rocket"></i>
                        </div>
                    </div>
                </div>
                <div class="card small-box bg-purple">
                    <div class="card-body">
                        <div class="inner">
                            <p>{{ __('labels.total_ads_available') }}</p>
                            <h3>150</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-images"></i>
                        </div>
                    </div>
                </div>
                <div class="card small-box bg-light">
                    <div class="card-body">
                        <div class="inner">
                            <p>{{ __('labels.current_bought_product') }}</p>
                            <h3>150</h3>
                        </div>
                        <div class="icon">
                            <i class="fas fa-cubes"></i>
                        </div>
                    </div>
                    <a href="#" class="small-box-footer">{{ __('modules.add', ['module' => trans_choice('modules.product', 1)]) }} <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>
    </div>

    {{-- LATEST 4 PROJECTS LISTING --}}
    @if ($projects_list->count() > 0)
    <hr>
    <div class="row">
        <div class="col-12 my-3">
            <h5 class="font-weight-bold d-inline">{{ __('labels.listed_projects') }}</h5>
            <a role="button" href="" class="float-right">{{ __('labels.view_all') . ' >' }}</a>
        </div>

        <div class="col-12 my-md-3 my-0">
            <div class="card-deck">
                @foreach ($projects_list as $project)
                <div class="card">
                    <img src="" alt="image" class="card-img-top">
                    <div class="card-body">
                        @foreach ($project->translations->get() as $title)
                        <h5 class="card-title">{{ $title->value ?? '-' }}</h5>
                        @endforeach
                        <h4 class="card-text font-weight-bold">{{ $project->price ?? '-' }}</h4>
                        <p class="card-text"><small class="text-muted">{{ $project->location ?? '-' }}</small></p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif


    <hr>
    <div class="row">
        <div class="col-12 my-3">
            <h5 class="font-weight-bold d-inline">{{ __('labels.current_ads_boosting') }}</h5>
            <a role="button" href="" class="float-right">{{ __('labels.view_all') . ' >' }}</a>
        </div>

        <div class="col-12 my-md-3 my-0">
            <div class="card-deck">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="ribbon-wrapper ribbon ribbon-lg">
                                    <div class="ribbon bg-warning">
                                        Highlighted
                                    </div>
                                </div>
                                <img src="" alt="image">
                            </div>
                            <div class="col-12 col-md-8">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="ribbon-wrapper ribbon ribbon-lg">
                                    <div class="ribbon bg-success">
                                        Top Ads
                                    </div>
                                </div>
                                <img src="" alt="image">
                            </div>
                            <div class="col-12 col-md-8">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection