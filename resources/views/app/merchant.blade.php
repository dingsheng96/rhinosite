@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.merchant_profile')])

@section('content')

<div id="profile-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('app.merchant_title_main') }}</h1>
            </div>
        </div>
    </div>
    <div id="searchbar">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10 position-relative">
                    @include('app.search')
                </div>
            </div>
        </div>
    </div>
</div>

<div id="merchant-category">
    <div class="container">
        <div class="d-flex px-3">
            <span>{{ __('app.top_search_services') }}</span>
            <ul>
                @forelse ($top_services as $service)
                <li class="active">
                    <a href="{{ route('app.project.index', ['q' => $service->name]) }}" class="text-muted">{{ $service->name }}</a>
                </li>
                @empty
                @endforelse
            </ul>
        </div>
    </div>
</div>

<div id="profile-1">
    <div class="container">
        <div class="row">
            <div class="col">
                <ol class="breadcrumb bg-transparent pl-0">
                    <li class="breadcrumb-item"><a href="{{ route('app.home') }}">{{ __('modules.app.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('app.project.index') }}">{{ __('modules.app.merchant') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $merchant->name }}</li>
                </ol>
            </div>
        </div>
        <div class="profile-card">
            <div class="col-lg-7 col-xl-8 align-self-center">
                <img src="{{ $merchant->logo->full_file_path }}" alt="merchant_profile_pic" class="profile-img">
                <span class="profile-name">{{ $merchant->name }}</span>
            </div>
            <div class="col-lg-5 col-xl-4 text-right align-self-center">
                <p>Joined Since : {{ $merchant->joined_date }}</p>
                <p>Year in Industry : {{ trans_choice('labels.year', $merchant->userDetails->first()->years_of_experience, ['value' => $merchant->userDetails->first()->years_of_experience]) }}</p>
                <p>{!! $merchant->rating_stars !!}</p>

                @auth
                @member
                <button type="button" class="btn btn-orange" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ __('app.merchant_btn_rate_merchant') }}
                </button>
                <div class="dropdown-menu dropdown-menu-right merchant-rate text-center">
                    <form action="">
                        <p class="mb-0">{{ __('app.merchant_rate_dropdown_title') }}</p>
                        <div class="rate">
                            <input type="radio" id="star5" name="rate" value="5" />
                            <label for="star5"></label>
                            <input type="radio" id="star4" name="rate" value="4" />
                            <label for="star4"></label>
                            <input type="radio" id="star3" name="rate" value="3" />
                            <label for="star3"></label>
                            <input type="radio" id="star2" name="rate" value="2" />
                            <label for="star2"></label>
                            <input type="radio" id="star1" name="rate" value="1" />
                            <label for="star1"></label>
                        </div>
                    </form>
                </div>
                @endmember
                @endauth

                @guest
                <a role="button" href="{{ route('login') }}" class="btn btn-orange" aria-haspopup="true" aria-expanded="false">
                    {{ __('app.merchant_btn_login_rate') }}
                </a>
                @endguest
            </div>
            <div class="profile-line"></div>
            <div class="col-lg-8">
                <div class="d-flex mb-3 align-items-center"><i class="fa fa-phone profile-icon" aria-hidden="true"></i><span class="ml-3">{{ $merchant->formatted_phone_number }}</span></div>
                <div class="d-flex mb-3 align-items-center"><i class="fa fa-map-marker profile-icon location" aria-hidden="true"></i><span class="ml-3">{{ $merchant->full_address }}</span></div>
            </div>
            <div class="col-lg-4">
                <div class="d-flex mb-3 align-items-center text-break"><i class="fa fa-envelope profile-icon mail" aria-hidden="true"></i><span class="ml-3">{{ $merchant->email }}</span></div>
            </div>
        </div>
    </div>
</div>

<div id="profile-2">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2>{{ __('app.merchant_subtitle_service') }}:</h2>
            </div>
            <div class="col-12 mb-3">
                <p class="txtgrey">{{ __('app.merchant_service_title') }}</p>
            </div>

            @forelse ($merchant->project_services as $service)
            <div class="col-md-6 col-lg-3">
                <div class="provided-services">
                    <i class="fa fa-check" aria-hidden="true"></i>
                    <span class="ml-3">{{ $service->name }}</span>
                </div>
            </div>
            @empty
            @endforelse

        </div>
    </div>
</div>

<div id="profile-3">
    <div class="container">
        <h4>{{ __('app.merchant_subtitle_projects', ['merchant' => $merchant->name]) }}</h4>
        <div class="row justify-content-start">

            @forelse ($projects as $project)
            <div class="col-md-6 col-lg-4 d-inline-flex">
                <div class="merchant-card">
                    <a href="{{ route('projects.show', ['project' => $project->id]) }}">
                        <div class="merchant-image-container">
                            <img src="{{ $project->thumbnail->full_file_path }}" alt="{{ $project->user->name }}" class="merchant-image">
                        </div>
                        <div class="merchant-body">
                            <p class="merchant-title">{{ Str::limit($project->english_title, 20, '...') }}</p>
                            <p class="merchant-title">{{ Str::limit($project->chinese_title, 20, '...') }}</p>
                            <p class="merchant-subtitle">{{ $project->user->name }}</p>
                        </div>
                        <div class="merchant-footer">
                            <span class="merchant-footer-left">{{ $project->price_with_unit }}</span>
                            <span class="merchant-footer-right">{{ $project->location }}</span>
                        </div>
                    </a>
                </div>
            </div>
            @empty
            <div class="col-12 d-inline-flex">{{ __('messages.no_records') }}</div>
            @endforelse

        </div>

        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {!! $projects->fragment('profile-3')->links() !!}
            </div>
        </div>
    </div>
</div>

@endsection