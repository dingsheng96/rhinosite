@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.merchant_profile')])

@section('content')

<div id="profile-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>Merchant Profile</h1>
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
            <span>Top Search Categories</span>
            <ul>
                <li class="active">Awning</li>
                <li class="active">Partition</li>
                <li>Wall Drilling & Mounting</li>
                <li>Flooring Installation</li>
                <li>Glasswork</li>
            </ul>
        </div>
    </div>
</div>

<div id="profile-1">
    <div class="container">
        <div class="row">
            <div class="col">
                <ol class="breadcrumb bg-transparent pl-0">
                    <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('app.project.index') }}">Services</a></li>
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
                    Rate Merchant
                </button>
                <div class="dropdown-menu dropdown-menu-right merchant-rate text-center">
                    <form action="">
                        <p class="mb-0">Give us a rate!</p>
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
                    Login to Rate
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
                <h2>Provided services:</h2>
            </div>
            <div class="col-12 mb-3">
                <p class="txtgrey">Services:</p>
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
        <h4>{{ $merchant->name }}'s Projects</h4>
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
                            <span class="merchant-footer-left">From RM1,200</span>
                            <span class="merchant-footer-right">Kuala Lumpur</span>
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
                {!! $links !!}
            </div>
        </div>
    </div>
</div>

@endsection