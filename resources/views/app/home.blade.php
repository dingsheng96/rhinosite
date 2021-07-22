@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.home')])

@section('content')

<div id="home-s1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8 col-xs-12 text-center">
                <h1>
                    {{ __('app.home_title_main') }}<br>
                    <span class="home-s1-subtitle">{{ __('app.home_subtitle_main') }}</span>
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
                <p class="category-title">Top Search Categories</p>
            </div>

            @forelse ($top_services as $service)
            <div class="col-md-6 col-lg-3 col-xs-12 d-inline-flex">
                <a href="{{ route('app.project.index', ['search' => $service]) }}" class="category-item">
                    <img src="{{ asset('storage/assets/home/icon1.png') }}" alt="shopping_icon" class="category-image">
                    <div class="orange-background"></div>
                    <span>{{ strtoupper($service) }}</span>
                </a>
            </div>
            @empty
            @endforelse

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
                <div class="row justify-content-start">

                    @forelse ($top_merchants as $merchant)
                    <div class="col-md-6 col-lg-4 d-inline-flex">
                        <div class="merchant-card">
                            <a href="{{ route('app.merchant.show', ['merchant' => $merchant->id]) }}">
                                <div class="merchant-image-container">
                                    <img src="{{ $merchant->media()->logo()->first()->full_file_path ?? $default_preview }}" alt="{{ $merchant->name }}" class="merchant-image">
                                </div>
                                <div class="merchant-body">
                                    <p class="merchant-title">{{ $merchant->name }}</p>
                                    {{-- <p class="merchant-subtitle"></p> --}}
                                    @forelse ($merchant->project_services as $service)
                                    <span class="badge badge-pill badge-primary">{{ $service->name }}</span>
                                    @empty
                                    @endforelse
                                </div>
                                <div class="merchant-footer">
                                    <span class="merchant-footer-left">{{ __('app.price_from') . ' ' . $merchant->min_project_price }}</span>
                                    <span class="merchant-footer-right">{{ $merchant->location_with_city_state }}</span>
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
                <a href="{{ route('app.project.index') }}" class="btn btn-orange">{{ __('app.home_btn_more_service') }}</a>
            </div>
        </div>

    </div>
</div>

<div id="home-s3">
    <img src="{{ asset('storage/assets/home/s3-left.png') }}" alt="s3_image" class="home-s3-left">
    <div class="home-s3-right">
        <p>{{ __('app.home_text_join_merchant') }}</p>
        <a href="{{ route('app.partner') }}" class="btn btn-orange">{{ __('app.home_btn_join_merchant') }}</a>
    </div>
</div>

<div id="home-s4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <h2 class="text-center">{{ __('app.home_subtitle_partner') }}</h2>
            </div>
            <div class="col-lg-8">
                <p class="paragraph">Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam</p>
            </div>
            <div class="col-lg-10">
                <div class="row justify-content-center">
                    <div class="col-6 col-md"><img src="{{ asset('storage/assets/home/1.jpg') }}" alt="partner_image_1" class="home-s4-img"></div>
                    <div class="col-6 col-md"><img src="{{ asset('storage/assets/home/2.jpg') }}" alt="partner_image_2" class="home-s4-img"></div>
                    <div class="col-6 col-md"><img src="{{ asset('storage/assets/home/3.jpg') }}" alt="partner_image_3" class="home-s4-img"></div>
                    <div class="col-6 col-md"><img src="{{ asset('storage/assets/home/4.png') }}" alt="partner_image_4" class="home-s4-img"></div>
                    <div class="col-6 col-md"><img src="{{ asset('storage/assets/home/5.jpg') }}" alt="partner_image_5" class="home-s4-img"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection