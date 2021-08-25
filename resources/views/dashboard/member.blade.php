@extends('layouts.master', ['title' => __('modules.dashboard'), 'guest_view' => true, 'body' => 'enduser'])

@section('content')


<div id="subpage-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('app.user_dashboard_main_title') }}</h1>
            </div>
        </div>
    </div>
</div>

<div id="user-directory" class="d-xl-none d-block">
    <div class="container">
        <ul class="account">
            <li class="title">
                <a data-toggle="collapse" data-target="#userdirectory" aria-expanded="false" aria-controls="userdirectory" class="collapsed">
                    {{ __('app.user_dashboard_sidebar_title') }}
                </a>
            </li>
            <div class="collapse" id="userdirectory">
                <li class="{{ Nav::hasSegment('dashboard', 1, 'active') }}">
                    <a href="{{ route('dashboard') }}">{{ __('app.user_dashboard_sidebar_profile') }}</a>
                </li>
                <li class="{{ Nav::hasSegment('account', 1, 'active') }}">
                    <a href="{{ route('account.index') }}">{{ __('app.user_dashboard_sidebar_profile') }}</a>
                </li>
                <li class="{{ Nav::hasSegment('wishlist', 1, 'active') }}">
                    <a href="{{ route('app.wishlist.index') }}">{{ __('app.user_dashboard_sidebar_wishlist') }}</a>
                </li>
            </div>
        </ul>
    </div>
</div>

<div id="user">
    <div class="container">
        <div class="d-flex">
            <div class="sidebar">
                <ul class="account">
                    <li class="title">{{ __('app.user_dashboard_sidebar_title') }}</li>
                    <li class="{{ Nav::hasSegment('dashboard', 1, 'active') }}">
                        <a href="{{ route('dashboard') }}">{{ __('app.user_dashboard_sidebar_dashboard') }}</a>
                    </li>
                    <li class="{{ Nav::hasSegment('account', 1, 'active') }}">
                        <a href="{{ route('account.index') }}">{{ __('app.user_dashboard_sidebar_profile') }}</a>
                    </li>
                    <li class="{{ Nav::hasSegment('wishlist', 1, 'active') }}">
                        <a href="{{ route('app.wishlist.index') }}">{{ __('app.user_dashboard_sidebar_wishlist') }}</a>
                    </li>
                </ul>
            </div>
            <div class="content">
                <div id="user-credential">
                    <div class="row">
                        <div class="col-12">
                            <h2>{{ __('modules.dashboard') }}</h2>
                        </div>
                    </div>
                    <div class="row align-items-top mb-5 pb-4 border-bottom">
                        <div class="col-md-3 col-xl-2">
                            <img src="{{ $user->logo->full_file_path ?? $default_preview }}" class="user-img rounded-circle img-thumbnail">
                        </div>
                        <div class="col-md-9 col-xl-10">
                            <div class="row my-3 my-sm-0">
                                <div class="col-sm-4 col-lg-3">
                                    <p>{{ __('labels.name') }}:
                                </div>
                                <div class="col-sm-8 col-lg-9">
                                    <p>{{ $user->name }}</p>
                                </div>
                            </div>
                            <div class="row mb-3 mb-sm-0">
                                <div class="col-sm-4 col-lg-3">
                                    <p>{{ __('labels.email') }}:</p>
                                </div>
                                <div class="col-sm-8 col-lg-9">
                                    <p>{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="row mb-3 mb-sm-0">
                                <div class="col-sm-4 col-lg-3">
                                    <p>{{ __('labels.contact_no') }}:</p>
                                </div>
                                <div class="col-sm-8 col-lg-9">{{ $user->formatted_phone_number }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="user-profile">
                    <div class="mb-5">
                        <h3 class="d-inline">{{ __('app.user_dashboard_wishlist') }}</h3>
                        <a href="{{ route('app.wishlist.index') }}" class="float-md-right">{{ __('app.btn_view_more') }} &raquo;</a>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                @forelse ($projects as $project)
                                <div class="col-md-6 col-lg-4 d-inline-flex">
                                    <div class="merchant-card {{ $project->has_active_highlight ? 'highlight' : null }}">
                                        <a href="{{ route('app.project.show', ['project' => $project->id]) }}">
                                            <div class="merchant-image-container">
                                                <img src="{{ $project->media->first()->full_file_path }}" alt="{{ $project->user->name }}" class="merchant-image">
                                            </div>
                                            <div class="merchant-body">
                                                @if ($project->has_active_highlight)
                                                <img src="{{ asset('storage/adboost.png') }}" class="highlight-img">
                                                @endif
                                                <p class="merchant-title">{{ $project->english_title }}</p>
                                                {{-- <p class="merchant-subtitle">{{ $project->chinese_title }}</p> --}}
                                                <p class="merchant-subtitle">{{ $project->user->name }}</p>
                                                <p class="merchant-subtitle">
                                                    <span class="badge badge-pill badge-info badge-padding">{{ $project->user->service->name ?? '-' }}</span>
                                                </p>
                                            </div>
                                            <div class="merchant-footer">
                                                {{-- <span class="merchant-footer-left">{{ __('app.price_from') . ' ' .$project->price_without_unit }}</span> --}}
                                                <span class="merchant-footer-right">
                                                    <i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $project->location }}
                                                </span>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 d-inline-flex justify-content-center">{{ __('messages.wishlist_empty') }}</div>
                                <div class="col-12 d-flex justify-content-center my-5">
                                    <a href="{{ route('app.project.index') }}" class="btn btn-orange">{{ __('app.user_dashboard_wishlist_btn_explore_service') }}</a>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection