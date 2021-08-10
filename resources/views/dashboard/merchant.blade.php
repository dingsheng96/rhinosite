@extends('layouts.master', ['title' => __('modules.dashboard')])

@section('content')

<div class="container-fluid">

    {{-- Profile --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border">

                <div class="card-body box-profile p-0 bg-dark pb-3">
                    <div class="text-right p-0 pr-md-3 pt-md-3">
                        <a href="{{ route('account.index') }}" role="button" class="btn bg-orange">
                            <i class="fas fa-edit"></i>
                            {{ __('modules.edit', ['module' => __('labels.profile')]) }}
                        </a>
                    </div>
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle mb-3" src="{{ optional($user->logo)->full_file_path ?? $default_preview }}" alt="profile">
                        <h3 class="profile-username my-3">{{ $user->name }}</h3>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header mb-3"><i class="fas fa-address-book text-primary"></i> <br> {{ __('labels.contact') }}</h5>
                                <span class="description-text">
                                    {{ $user->formatted_phone_number }}
                                    <br>
                                    {{ $user->email }}
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-4 border-right">
                            <div class="description-block">
                                <h5 class="description-header mb-3"><i class="fas fa-briefcase text-success"></i> <br> {{ __('labels.info') }}</h5>
                                <span class="description-text">

                                    {{ __('labels.joined_since') }} : {{ $user->created_at->format('jS M Y') }}
                                    <br>
                                    @if ($user->userDetail->years_of_experience > 0)
                                    {{ __('labels.years_of_experience') }} : {{ trans_choice('labels.year', $user->userDetail->years_of_experience, ['value' => $user->userDetail->years_of_experience]) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="description-block">
                                <h5 class="description-header mb-3"><i class="fas fa-map-marker-alt text-cyan"></i> <br> {{ __('labels.address') }}</h5>
                                <span class="description-text">{{ $user->full_address }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Widgets --}}
    <div class="row mb-4">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow">
                <span class="info-box-icon bg-info elevation-1"><i class="fas fa-briefcase"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('labels.listed_projects') }}</span>
                    <span class="info-box-number">
                        {{ $projects->count() ?? 0 }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow">
                <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-rocket"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('labels.current_ads_boosting') }}</span>
                    <span class="info-box-number">
                        {{ $boosting_projects->count() ?? 0 }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow">
                <span class="info-box-icon bg-success elevation-1"><i class="fas fa-wallet"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('labels.total_ads_quota') }}</span>
                    <span class="info-box-number">
                        {{ $total_ads_quotas }}
                    </span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow">
                <span class="info-box-icon bg-purple elevation-1"><i class="fas fa-bookmark"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">{{ __('labels.current_plan') }}</span>
                    <span class="info-box-number">
                        {{ $user->userSubscriptions->first()->name ?? '-' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- LATEST 4 PROJECTS LISTING --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-body px-0 bg-transparent shadow-none">

                <div class="row">
                    <div class="col-12">
                        <h5 class="font-weight-bold d-inline">{{ __('labels.listed_projects') }}</h5>
                        <a role="button" href="{{ route('projects.index') }}" class="float-right">{{ __('labels.view_all') . ' >' }}</a>
                    </div>
                </div>

                <hr>

                <div class="row">
                    @forelse ($projects as $project)
                    <div class="col-md-3 col-12">
                        <a href="{{ route('projects.show', ['project' => $project->id]) }}">
                            <div class="card shadow-lg border h-100">
                                <img src="{{ $project->thumbnail->full_file_path ?? $default_preview }}" alt="image" class="card-img-top" style="height: 250px; width: 100%; min-height: 210px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold text-secondary">{{ $project->english_title ?? '-' }}</h5>
                                    <p class="card-text">
                                        @foreach ($project->services as $service)
                                        <span class="badge badge-pill badge-info">{{ $service->name }}</span>
                                        @endforeach
                                    </p>
                                </div>
                                <div class="card-footer bg-white">
                                    <p class="card-text text-muted"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $project->location ?? '-' }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-muted">{{ __('messages.no_records') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- BOOSTING ADS --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-body px-0 bg-transparent shadow-none">

                <div class="row">
                    <div class="col-12">
                        <h5 class="font-weight-bold d-inline">{{ __('labels.current_ads_boosting') }}</h5>
                    </div>
                </div>

                <hr>

                <div class="row">
                    @forelse ($boosting_projects as $project)
                    <div class="col-md-3 col-12">
                        <a href="{{ route('projects.show', ['project' => $project->id]) }}">
                            <div class="card shadow-lg border h-100">
                                <div class="ribbon-wrapper ribbon ribbon-lg">
                                    <div class="ribbon bg-warning">
                                        Highlighted
                                    </div>
                                </div>
                                <img src="{{ $project->thumbnail->full_file_path ?? $default_preview }}" alt="image" class="card-img-top" style="height: 250px; width: 100%; min-height: 210px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title font-weight-bold text-secondary">{{ $project->english_title ?? '-' }}</h5>
                                    <p class="card-text">
                                        @foreach ($project->services as $service)
                                        <span class="badge badge-pill badge-info">{{ $service->name }}</span>
                                        @endforeach
                                    </p>
                                </div>
                                <div class="card-footer bg-white">
                                    <p class="card-text text-muted"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $project->location ?? '-' }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="col-12">
                        <p class="text-muted">{{ __('messages.no_records') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>

@endsection