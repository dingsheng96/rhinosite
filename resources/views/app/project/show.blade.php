@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.service')])

@section('content')

<div id="services-header">
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
                    <div class="row align-items-center py-4">
                        <div class="col-5 col-md-6">
                            <img src="{{ $project->user->logo->full_file_path ?? $default_preview }}" alt="service_brand" class="services-img">
                        </div>
                        <div class="col-7 col-md-6 btn-right">
                            <a href="{{ route('app.merchant.show', ['merchant' => $project->user->id]) }}" class="btn btn-black">{{ __('app.project_btn_view_merchant') }}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-7">
                            <p class="services-title">{{ $project->english_title }}</p>

                        </div>
                        <div class="col-sm-5 text-sm-right">
                            <a role="button" class="btn"><i class="far fa-heart txtorange services-icon" aria-hidden="true" title="{{ __('app.project_details_btn_add_wishlist') }}"></i></a>
                            <a role="button" href="tel:{{ $project->user->phone }}" class="btn" target="_blank"><i class="fas fa-phone txtgreen services-icon" aria-hidden="true" title="{{ __('app.project_details_btn_call') }}"></i></a>
                        </div>
                        <div class="col-12">
                            <p class="services-subtitle">{{ $project->chinese_title }}</p>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3 mb-sm-2">
                        <div class="col-sm-6">
                            <p>{{ __('app.project_details_price') }}</p>
                        </div>
                        <div class="col-sm-6 text-sm-right txtblk">
                            <p>
                                <span class="services-from">{{ __('app.price_from') }}</span>
                                <span class="services-price pl-2">{{ $project->price_without_unit }}</span>
                            </p>
                        </div>
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
                </div>
            </div>
        </div>
    </div>
</div>

<div id="services-2">
    <div class="container">
        <h3>{{ __('app.project_details_subtitle_description') }}</h3>
        <div class="row">
            <div class="col-12">
                <p class="paragraph">
                    {!! $project->description !!}
                </p>
            </div>
        </div>
    </div>
</div>

<div id="services-2">
    <div class="container">
        <h3>{{ __('app.project_details_subtitle_service') }}</h3>
        <div class="row">
            @foreach ($project_services as $service)
            <div class="col-md-6 col-lg-3">
                <div class="provided-services">
                    <i class="fa fa-check" aria-hidden="true"></i><span class="ml-3">{{ $service->name }}</span>
                </div>
            </div>
            @endforeach
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
@endif


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
                            <p class="merchant-subtitle">{{ $project->chinese_title }}</p>
                        </div>
                        <div class="merchant-footer">
                            <span class="merchant-footer-left">From {{ $project->price_without_unit }}</span>
                            <span class="merchant-footer-right">{{ $project->location }}</span>
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