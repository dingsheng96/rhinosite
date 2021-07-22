@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.service')])

@section('content')

<div id="services-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>Services</h1>
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

<div id="services-1">
    <div class="container">
        <div class="row">
            <div class="col">
                <ol class="breadcrumb bg-transparent pl-0">
                    <li class="breadcrumb-item"><a href="{{ route('app.home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('app.project.index') }}">Services</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Awning</li>
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
                            <img src="{{ $project->user->logo->full_file_path }}" alt="service_brand" class="services-img">
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
                            <a role="button" class="btn"><i class="far fa-heart txtorange services-icon" aria-hidden="true" title="Add to wishlist"></i></a>
                            <a role="button" href="https://wa.me/{{ $project->user->phone }}" class="btn" target="_blank"><i class="fab fa-whatsapp txtgreen services-icon" aria-hidden="true" title="Whatsapp"></i></a>
                        </div>
                        <div class="col-12">
                            <p class="services-subtitle">{{ $project->chinese_title }}</p>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3 mb-sm-2">
                        <div class="col-sm-6">
                            <p>Price</p>
                        </div>
                        <div class="col-sm-6 text-sm-right txtblk">
                            <p>
                                <span class="services-from">From</span>
                                <span class="services-price pl-2">{{ $project->price_with_unit }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3 mb-sm-2">
                        <div class="col-sm-6">
                            <p>Location</p>
                        </div>
                        <div class="col-sm-6 txtblk text-sm-right">
                            <p class="font-medium">{{ $project->location }}</p>
                        </div>
                    </div>
                    <div class="row align-items-center mb-3 mb-sm-2">
                        <div class="col-sm-6">
                            <p>Contact Contractor</p>
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
        <h3>Services Provided</h3>
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


@if (count($similar_projects) > 0)
<div id="services-5">
    <div class="container">
        <h3>Similar Projects</h3>
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
                            <span class="merchant-footer-left">From {{ $project->price_with_unit }}</span>
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