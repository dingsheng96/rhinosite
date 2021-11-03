{{-- <div id="merchant-category">
    <div class="container">
        <div class="d-flex align-items-xl-center px-3">
            <span>{{ __('app.top_search_services') }}</span>
            <ul>
                @forelse ($services->take(6) as $service)
                <li class="top-service">
                    <a href="{{ route('app.project.index', ['q' => $service->name]) }}" class="font-weight-bold">{{ $service->name }}</a>
                </li>
                @empty
                @endforelse
            </ul>
        </div>
    </div>
</div> --}}

<div id="home-s1">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8 col-xs-12 text-center">
                <h1>
                    {{ __('app.merchant_title_main') }}
                    <br>
                    <span class="home-s1-subtitle text-white">{{ __('app.merchant_subtitle_main') }}</span>
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