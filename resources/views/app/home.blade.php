@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.home')])

@section('content')

<div id="home-s1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8 col-xs-12 text-center">
                <h1>
                    {{ __('app.home_title_main') }}
                    <br>
                    <span class="home-s1-subtitle text-white">{{ __('app.home_subtitle_main') }}</span>
                </h1>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xs-12 position-relative">
                @include('app.search')
            </div>
        </div>
    </div>
</div>

<div id="home-category">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="category-title">{{ __('app.top_search_services') }}</p>
            </div>

            @forelse ($services->take(4) as $service)
            <div class="col-md-6 col-lg-3 col-xs-12 d-inline-flex">
                <a href="{{ route('app.project.index', ['q' => $service->name]) }}" class="category-item">
                    <img src="{{ asset('assets/home/icon1.png') }}" alt="shopping_icon" class="category-image">
                    <div class="orange-background"></div>
                    <span>{{ strtoupper($service->name) }}</span>
                </a>
            </div>
            @empty
            <div class="col-12 d-inline-flex">&nbsp;</div>
            @endforelse

        </div>
    </div>
</div>

<div id="home-whyrhino">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5">
                <h2 class="text-center d-lg-none d-block">Why Rhinosite?</h2>
                <img src="{{ asset('assets/about/whyrhinosite.jpg') }}" class="res-img mb-3 mb-lg-0">
            </div>
            <div class="col-lg-7 pl-lg-5">
                <h2 class="mb-0 d-lg-block d-none">Why Rhinosite?</h2>
                <!-- Tab panes -->
                <div class="tab-content about-tabbed text-lg-left text-center">
                    <div id="menu1" class="tab-pane active"><br>
                        <h3 class="font-weight-bold txtblk mb-2">1. Increase your exposure and presence online</h3>
                        <p class="paragraph mb-0">Rhinosite allows you to grow your business by increasing the chances of your business exposure online – to connect property owners to contractors in a single platform.</p>
                    </div>
                    <div id="menu2" class="tab-pane fade"><br>
                        <h3 class="font-weight-bold txtblk mb-2">2. Transparent & Hassle-Free</h3>
                        <p class="paragraph mb-2">By listing with us, contractors are able to provide their nature of business, past portfolios, location, contact numbers, asking price etc. so that the end users are able to filter according to their needs in a more time-efficient manner.</p>
                        <p class="paragraph mb-2">No other hidden costs and commission charged. </p>
                    </div>
                    <div id="menu3" class="tab-pane fade"><br>
                        <h3 class="font-weight-bold txtblk mb-2">3. Trustable & Reliable Contractors</h3>
                        <p class="paragraph mb-2">Rhinosite team will have a due diligence process to ensure that only reliable and quality contractors are able to be listed on our platform.</p>
                        <p class="paragraph mb-2">Rhinosite will investigate thoroughly on those Contractors whom received poor ratings/ feedback. The Contractor will be terminated if the issue remains unsolved.</p>
                    </div>
                    <div id="menu4" class="tab-pane fade"><br>
                        <h3 class="font-weight-bold txtblk mb-2">4. Convenient & Time-Efficient</h3>
                        <p class="paragraph mb-2">Users are able to search and filter according to their needs and preference with just one-click away. </p>
                        <p class="paragraph mb-2">Users are able to view all the Contractors and select according to their preference. </p>
                    </div>
                    <div id="menu5" class="tab-pane fade"><br>
                        <h3 class="font-weight-bold txtblk mb-2">5. Safe and Secure</h3>
                        <p class="paragraph mb-2">Although Users liaise directly with the Contractors, they are still bound by Terms & Conditions to safeguard both Contractors’ and Users’ concerns. </p>
                        <p class="paragraph mb-2">For any payment related matters, Rhinosite also has payment gateway incorporated to ensure that your payments are dealt in a secure and convenient way. </p>
                    </div>
                    <div id="menu6" class="tab-pane fade"><br>
                        <h3 class="font-weight-bold txtblk mb-2">6. Build a Long-Term Relationship with your Users/ Grow your Business</h3>
                        <p class="paragraph mb-2">Once a User is satisfied with your services, they are more than happy to come back to you the next time they are looking for a Contractor. </p>
                        <p class="paragraph mb-2">Users will also be delighted to introduce you to their friends or families who are looking for a reliable & trustable Contractor. </p>
                    </div>
                </div>
                <!-- Nav pills -->
                <ul class="nav nav-pills about-pills justify-content-lg-start justify-content-center" role="tablist">
                    <li class="nav-item pl-0">
                        <a class="nav-link active" data-toggle="pill" href="#menu1">1</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#menu2">2</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#menu3">3</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#menu4">4</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#menu5">5</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#menu6">6</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="home-tryrhino">
    <div class="container">
        <div class="row">
            <div class="col-12 try-rhino-background">
                <div class="row">
                    <div class="col-lg-6 try-rhino-content">
                        <h2 class="text-white">Try Rhinosite</h2>
                        <p class="paragraph text-white">Subscribe with us today to create more business opportunities and build a long-term relationship with your clients via a hassle-free & transparent process.</p>
                        <a href="{{ route('app.partner') }}" class="btn btn-round">Learn More</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="home-s2">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <h2>{{ __('app.home_subtitle_merchant') }}</h2>
            </div>
            <div class="col-12">
                <div class="row justify-content-center">
                    @forelse ($projects as $project)
                    <div class="col-md-6 col-lg-4 d-inline-flex">
                        <div class="merchant-card">
                            <a href="{{ route('app.project.show', ['project' => $project->id]) }}">
                                <div class="merchant-image-container">
                                    <img src="{{ $project->media->first()->full_file_path }}" alt="{{ $project->title }}" class="merchant-image">
                                </div>
                                <div class="merchant-body">
                                    <p class="merchant-title">{{ $project->english_title }}</p>
                                    {{-- <p class="merchant-subtitle">{{ $project->chinese_title }}</p> --}}
                                    <p class="merchant-subtitle">{{ $project->user->name }}</p>
                                    <p class="merchant-subtitle">
                                        <span class="badge badge-pill badge-info badge-padding">{{ $project->user->service->name }}</span>
                                    </p>
                                </div>
                                <div class="merchant-footer">
                                    {{-- <span class="merchant-footer-left">{{ __('app.price_from') . ' '. $project->price_without_unit }}</span> --}}
                                    <span class="merchant-footer-right"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $project->location }}</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 d-inline-flex">{{ __('messages.no_records') }}</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col text-center">
                <a href="{{ route('app.project.index') }}" class="btn btn-round">{{ __('app.btn_view_more') }}</a>
            </div>
        </div>
    </div>
</div>

<div id="home-s3">
    <img src="{{ asset('assets/home/s3-left-update.jpg') }}" alt="s3_image" class="home-s3-left">
    <div class="home-s3-right">
        <p>{!! __('app.home_text_join_merchant') !!}</p>
        <a href="{{ route('app.partner') }}" class="btn btn-round">{{ __('app.home_btn_join_merchant') }}</a>
    </div>
</div>

<div id="home-s4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="text-center">{{ __('app.home_subtitle_partner') }}</h2>
            </div>
            <div class="col-lg-8">
                <p class="paragraph">
                    Rhinosite team will have a due diligence process to ensure that only reliable and quality
                    contractors are able to be listed on our platform. Rhinosite will investigate thoroughly on
                    those Contractors whom received poor ratings/ feedback. The Contractor will be
                    terminated if the issue remains unsolved.
                </p>
            </div>
            <div class="col-lg-10">
                <div class="row justify-content-center">
                    <div class="col-12">
                        <div class="slider slider slider-home">
                            @forelse ($merchants as $merchant)
                            <div>
                                <img src="{{ $merchant->media->first()->full_file_path ?? $default_preview }}" alt="partner_image_1" class="home-s4-img">
                            </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection