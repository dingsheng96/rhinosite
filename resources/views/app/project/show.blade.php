@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.service')])

@section('content')

{{-- <div id="services-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('app.project_details_title_main') }}</h1>
            </div>
        </div>
    </div>
    <div id="searchbar">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    @include('app.search')
                </div>
            </div>
        </div>
    </div>
</div> --}}

@include('app.topservice')

<div id="services-1">
    <div class="container">
        <div class="row">
            <div class="col">
                <ol class="breadcrumb bg-transparent pl-0">
                    <li class="breadcrumb-item"><a href="{{ route('app.home') }}">{{ __('modules.app.home') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('app.project.index') }}">{{ __('modules.app.project') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $project->english_title }}</li>
                </ol>
            </div>
        </div>
        <div class="services-card py-4 px-4 px-md-5">
            <div class="d-lg-flex align-items-center">
                <img src="{{ $project->user->logo->full_file_path ?? $default_preview }}" alt="service_brand" class="services-img ">
                <div class="d-lg-flex align-items-center justify-content-between w-100">
                    <div class="ml-lg-4">
                        <p class="services-title font-weight-bold txtblk mt-4 mt-lg-0">{{ $project->user->name }}</p>
                        <p class="paragraph mb-3">{{ __('app.merchant_joined_date', ['date' => $project->user->joined_date]) }}</p>
                        <p id="rating-stars">{!! $project->user->rating_stars !!}</p>
                    </div>
                    <div>
                        <p class="paragraph font-medium mb-3">{{ __('app.merchant_industry_year', ['year' => trans_choice('labels.year', $project->user->userDetail->years_of_experience, ['value' => $project->user->userDetail->years_of_experience])]) }}</p>
                        <a href="{{ route('app.merchant.show', ['merchant' => $project->user->id]) }}" class="btn btn-round shadow">{{ __('app.project_btn_merchant') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6 col-xxl-7 d-inline-flex flex-column align-self-center mb-lg-0 mb-3">
                <div class="slider slider-for">
                    <img src="{{ $project->thumbnail->full_file_path }}" alt="{{ $project->thumbnail->original_file_name }}" class="services-main-img">
                    @forelse ($project->media()->image()->get() as $image)
                    <img src="{{ $image->full_file_path }}" alt="{{ $image->original_file_name }}" class="services-main-img">
                    @empty
                    @endforelse
                </div>
                <div class="slider slider slider-nav">
                    <img src="{{ $project->thumbnail->full_file_path }}" alt="{{ $project->thumbnail->original_file_name }}" class="services-small-img">
                    @forelse ($project->media()->image()->get() as $image)
                    <img src="{{ $image->full_file_path }}" alt="{{ $image->original_file_name }}" class="services-small-img">
                    @empty
                    @endforelse
                </div>
            </div>
            <div class="col-lg-6 col-xxl-5 d-inline-flex">
                <div class="container bg-white services-details">
                    {{-- <div class="row align-items-center py-4">
                        <div class="col-5 col-md-6">
                            <img src="{{ $project->user->logo->full_file_path ?? $default_preview }}" alt="service_brand" class="services-img">
                        </div>
                        <div class="col-7 col-md-6 btn-right">
                            <a href="{{ route('app.merchant.show', ['merchant' => $project->user->id]) }}" class="btn btn-black">{{ __('app.project_btn_view_merchant') }}</a>
                        </div>
                    </div>
                    <div class="row border-bottom mb-4 pb-4">
                        <div class="col-sm-7">
                            <p class="services-title">{{ $project->english_title }}</p>
                            <p class="services-subtitle mb-md-0">{{ $project->user->name }}</p>
                        </div>
                        <div class="col-sm-5 text-sm-right">
                            @auth
                            <button type="button" class="btn btn-custom-wishlist" data-wishlist="{{ route('app.wishlist.store') }}" data-project="{{ $project->id }}">
                                @if (Auth::user()->favouriteProjects()->get()->contains($project->id))
                                <i class="fas fa-heart txtorange services-icon" aria-hidden="true" title="{{ __('app.project_details_btn_add_wishlist') }}"></i>
                                @else
                                <i class="far fa-heart txtorange services-icon" aria-hidden="true" title="{{ __('app.project_details_btn_add_wishlist') }}"></i>
                                @endif
                            </button>
                            @else
                            <button type="button" class="btn btn-custom-wishlist" data-wishlist="{{ route('login') }}">
                                <i class="far fa-heart txtorange services-icon" aria-hidden="true" title="{{ __('app.project_details_btn_add_wishlist') }}"></i>
                            </button>
                            @endauth
                            @if (!empty($project->user->userDetail->whatsapp))
                            <a role="button" href="https://api.whatsapp.com/send?phone={{ $project->user->userDetail->whatsapp }}&text={{ urlencode(__('messages.whatsapp_message', ['name' => $project->user->name, 'link' => route('app.project.show', ['project' => $project->id])])) }}" class="btn"
                                target="_blank">
                                <i class="fab fa-whatsapp txtgreen services-icon" aria-hidden="true" title="{{ __('app.project_details_btn_whatsapp') }}"></i>
                            </a>
                            @else
                            <a role="button" href="tel:{{ $project->user->formatted_phone_number }}" class="btn" target="_blank">
                                <i class="fas fa-phone text-secondary services-icon" aria-hidden="true" title="{{ __('app.project_details_btn_call') }}"></i>
                            </a>
                            @endif
                        </div>
                    </div> --}}
                    <div class="row border-bottom mb-4 pb-4">
                        <div class="col-12">
                            <p class="font-medium">{{ $project->user->service->name ?? '-' }}</p>
                            <p class="services-title font-weight-bold txtblk mb-0">{{ $project->english_title }}</p>
                        </div>
                    </div>
                    {{-- <div class="row align-items-center mb-3 mb-sm-2">
                        <div class="col-sm-6">
                            <p>{{ __('app.project_details_price') }}</p>
                        </div>
                        <div class="col-sm-6 text-sm-right txtblk">
                            <p>
                                <span class="services-from">{{ __('app.price_from') }}</span>
                                <span class="services-price pl-2">{{ $project->price_without_unit }}</span>
                            </p>
                        </div>
                    </div> --}}
                    <div class="d-sm-flex align-items-center mb-5 mb-sm-4">
                        <img src="{{ $project->user->logo->full_file_path ?? $default_preview }}" alt="service_brand" class="services-img">
                        <p class="txtblk font-medium mt-sm-0 mt-3 ml-sm-3">{{ $project->user->name }}</p>
                    </div>
                    <div class="row align-items-center mb-3 mb-sm-2">
                        <div class="col-sm-6">
                            <p>{{ __('app.project_details_location') }}</p>
                        </div>
                        <div class="col-sm-6 txtblk text-sm-right">
                            <p class="font-medium">{{ $project->location }}</p>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3 mb-sm-2">
                        <div class="col-sm-6">
                            <p>{{ __('app.project_details_merchant') }}</p>
                        </div>
                        <div class="col-sm-6 txtblk text-sm-right">
                            <p>{{ $project->user->formatted_phone_number }}</p>
                        </div>
                    </div>
                    <div class="row my-4">
                        @if (!empty($project->user->userDetail->website))
                        <div class="col-4 text-center mb-3">
                            <a href="{{ $project->user->userDetail->website }}" title="{{ $project->user->userDetail->website }}" class="txtblk"><i class="fas fa-globe-asia" style="font-size: 40px;"></i></a>
                        </div>
                        @endif
                        @if (!empty($project->user->userDetail->facebook))
                        <div class="col-4 text-center mb-3">
                            <a href="{{ $project->user->userDetail->facebook }}" title="{{ $project->user->userDetail->facebook }}" class="txtblk"><i class="fab fa-facebook-square" style="font-size: 40px;"></i></a>
                        </div>
                        @endif
                        @if (!empty($project->user->userDetail->instagram))
                        <div class="col-4 text-center mb-3">
                            <a href="{{ $project->user->userDetail->instagram }}" title="{{ $project->user->userDetail->instagram }}" class="txtblk"><i class="fab fa-instagram" style="font-size: 40px;"></i></a>
                        </div>
                        @endif
                    </div>
                    <div class="row justify-content-center">
                        <div class="col-10">
                            @if (!empty($project->user->userDetail->whatsapp))
                            <a class="btn btn-round green d-flex align-items-center justify-content-center shadow" role="button"
                                href="https://api.whatsapp.com/send?phone={{ $project->user->userDetail->whatsapp }}&text={{ urlencode(__('messages.whatsapp_message', ['name' => $project->user->name, 'link' => route('app.project.show', ['project' => $project->id])])) }}" class="btn" target="_blank">
                                <i class="fab fa-whatsapp services-icon text-white mr-3" aria-hidden="true" title="{{ __('app.project_details_btn_whatsapp') }}"></i>
                                {!! __('app.project_details_btn_whatsapp') !!}
                            </a>
                            @else
                            <a role="button" href="tel:{{ $project->user->formatted_phone_number }}" class="btn btn-round green d-flex align-items-center justify-content-center shadow" target="_blank">
                                <i class="fas fa-phone services-icon text-white mr-3" aria-hidden="true" title="{{ __('app.project_details_btn_call') }}"></i>
                                {!! __('app.project_details_btn_whatsapp') !!}
                            </a>
                            @endif
                            <a href="{{ route('app.merchant.show', ['merchant' => $project->user->id]) }}" class="btn btn-round d-flex align-items-center justify-content-center mt-4">
                                {{ __('app.project_btn_view_merchant') }}
                            </a>
                            @auth('web')
                            <button type="button" class="btn btn-custom-wishlist d-flex align-items-center text-danger font-weight-bold w-100 justify-content-center mt-4" data-wishlist="{{ route('app.wishlist.store') }}" data-project="{{ $project->id }}">
                                <i class="fas fa-heart services-icon mr-2" aria-hidden="true" title="{{ __('app.project_details_btn_add_wishlist') }}"></i>
                                <u>{{ __('app.project_details_btn_add_wishlist') }}</u>
                            </button>
                            @else
                            <a href="{{ route('login') }}" role="button" class="btn btn-custom-wishlist d-flex align-items-center text-danger font-weight-bold w-100 justify-content-center mt-4">
                                <i class="far fa-heart services-icon mr-3" aria-hidden="true"></i>
                                <u>{{ __('app.project_details_btn_add_wishlist_login') }}</u>
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="services-card py-4 px-4 px-md-5 mt-5">
            <div class="row">
                <div class="col-lg-5">
                    <p class="services-title font-weight-bold txtblk">{{ __('app.project_details_subtitle_description') }}</p>
                    <p class="paragraph">
                        {!! nl2br($project->description) !!}
                    </p>
                </div>
                <div class="border-left mx-auto"></div>
                <div class="col-lg-5">
                    <p class="services-title font-weight-bold txtblk">{{ __('app.project_details_subtitle_service') }}</p>
                    <div class="paragraph">
                        <i class="fa fa-check bg-orange text-white rounded-circle p-2" aria-hidden="true"></i><span class="ml-3 text-break">{{ $project->user->service->name ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div id="services-2">
    <div class="container">
        <h3>{{ __('app.project_details_subtitle_description') }}</h3>
        <div class="row">
            <div class="col-12">
                <p class="paragraph">
                    {!! nl2br($project->description) !!}
                </p>
            </div>
        </div>
    </div>
</div>

<div id="services-2">
    <div class="container">
        <h3>{{ __('app.project_details_subtitle_service') }}</h3>
        <div class="row">
            <div class="col-md-6 col-lg-3">
                <div class="provided-services">
                    <i class="fa fa-check" aria-hidden="true"></i><span class="ml-3 text-break">{{ $project->user->service->name ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@if (!empty($project->materials))
<div id="services-2">
    <div class="container">
        <h3>{{ __('app.project_details_subtitle_material') }}</h3>
        <div class="row">
            <div class="col-12">
                <p class="paragraph">
                    {!! $project->materials !!}
                </p>
            </div>
        </div>
    </div>
</div>
@endif --}}


@if (count($similar_projects) > 0)
<div id="services-5">
    <div class="container">
        <h3>{{ __('app.project_details_subtitle_similar_projects') }}</h3>
        <div class="row">
            @foreach ($similar_projects as $project)
            <div class="col-md-6 col-lg-4 d-inline-flex">
                <div class="merchant-card">
                    <a href="{{ route('app.project.show', ['project' => $project->id]) }}">
                        <div class="merchant-image-container">
                            <img src="{{ $project->thumbnail->full_file_path }}" alt="{{ $project->original_file_name }}" class="merchant-image">
                        </div>
                        <div class="merchant-body">
                            <p class="merchant-title">{{ $project->english_title }}</p>
                            {{-- <p class="merchant-subtitle">{{ $project->chinese_title }}</p> --}}
                            <p class="merchant-subtitle">{{ $project->user->name }}</p>
                            <p class="merchant-subtitle">
                                <span class="badge badge-pill badge-info badge-padding">{{ $project->user->service->name ?? '-' }}</span>
                            </p>
                        </div>
                        <div class="merchant-footer">
                            {{-- <span class="merchant-footer-left">From {{ $project->price_without_unit }}</span> --}}
                            <span class="merchant-footer-right"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $project->location }}</span>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach

        </div>
    </div>
</div>
@endif


@endsection