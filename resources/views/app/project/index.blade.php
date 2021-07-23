@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.merchant')])

@section('content')

<div id="merchant-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('app.project_title_main') }}</h1>
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
                    <a class="text-muted" href="{{ route('app.project.index', ['q' => $service->name]) }}">
                        {{ Str::title($service->name) }}
                    </a>
                </li>
                @empty
                @endforelse
            </ul>
        </div>
    </div>
</div>

<div id="merchant-2">
    <div class="container">
        <div class="d-flex">
            <div class="sidebar mb-5">
                <ul class="service">
                    <li class="title">{{ __('app.project_sidebar_service') }}</li>
                    @forelse ($services->take(5) as $service)
                    <li>
                        <a href="{{ route('app.project.index', ['q' => $service->name]) }}" class="text-muted">{{ $service->name }}</a>
                    </li>
                    @empty
                    @endforelse
                    <li class="end">
                        <a href="" class="txtorange">{{ __('app.btn_view_all') }}</a>
                    </li>
                </ul>
                <form action="{{ route('app.project.index') }}" method="GET" role="form" id="filterForm">
                    <ul class="range">
                        <li class="title">{{ __('app.project_sidebar_price') }}</li>
                        <li>
                            <input type="number" class="range" placeholder="{{ __('app.min') }}">
                            <span> - </span>
                            <input type="number" class="range" placeholder="{{ __('app.max') }}">
                        </li>
                    </ul>
                    <ul class="radio">
                        <li>
                            <input type="radio" id="rm1000" name="price" value="0,1000">
                            <label for="rm1000">&lsaquo; MYR 1,000 </label>
                        </li>
                        <li>
                            <input type="radio" id="rm5000" name="price" value="0,5000">
                            <label for="rm5000">&lsaquo; MYR 5,000 </label>
                        </li>
                        <li>
                            <input type="radio" id="rm10000" name="price" value="0,10000">
                            <label for="rm10000">&lsaquo; MYR 10,000 </label>
                        </li>
                        <li>
                            <input type="radio" id="rm15000" name="price" value="0,15000">
                            <label for="rm15000">&lsaquo; MYR 15,000 </label>
                        </li>
                        <li>
                            <input type="radio" id="rm20000" name="price" value="0,20000">
                            <label for="rm20000">&lsaquo; MYR 20,000 </label>
                        </li>
                    </ul>
                    <ul class="radio">
                        <li class="title">Locations</li>
                        @forelse ($areas as $area)
                        <li>
                            <input type="radio" id="{{ $loop->iteration }}" name="location" value="{{ $area->id }}">
                            <label for="{{ $loop->iteration }}">{{ $area->name . ' (' . $area->addresses_count . ')' }}</label>
                        </li>
                        @empty
                        @endforelse
                    </ul>
                    <ul class="radio rating">
                        <li class="title">Rating</li>
                        @for ($y = 1; $y <= 5; $y++) <li>
                            <input type="radio" id="star{{ $y }}" name="rating" value="{{ $y }}">
                            @for ($x=0; $x < $y; $x++) <label for="star{{ $y }}"><i class="fas fa-star"></i></label>
                                @endfor
                                </li>
                                @endfor
                    </ul>
                    <button type="submit" class="btn btn-orange w-100 mx-0 mb-3">Filter</button>
                    <button type="submit" class="btn btn-black w-100 m-0">Reset Filter</button>
                </form>
            </div>

            <div class="gap"></div>

            <div class="content">
                <div class="container">

                    <h2>{{ str_replace('+', ' ', request()->get('q')) }}</h2>

                    <div class="search-filter-result">
                        @if (request()->has('q') && !empty(request()->get('q')))
                        <span class="h5">{{ trans_choice('app.project_search_items', 2, ['total' => $projects->total(), 'search' => str_replace('+', ' ', request()->get('q'))]) }}</span>
                        @else
                        <span class="h5">{{ trans_choice('app.project_search_items', 1, ['total' => $projects->total()]) }}</span>
                        @endif
                        <button id="compare" name="compare" class="btn btn-orange ml-auto">Compare Merchant</button>
                    </div>

                    <div class="row search-filter-result compare" style="display: none;">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <span>Choose 2 contractor to compare now</span>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <span class="ml-auto mr-2">Selected : 2</span>
                            <a href="compare.html" class="btn btn-black mx-0">View Result</a>
                        </div>
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
                                                <p class="merchant-subtitle">{{ $project->chinese_title }}</p>
                                            </div>
                                            <div class="merchant-footer">
                                                <span class="merchant-footer-left">From {{ $project->price_without_unit }}</span>
                                                <span class="merchant-footer-right">{{ $project->location }}</span>
                                            </div>
                                        </a>
                                        <button class="btn-compare d-none">Add to Compare</button>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 d-inline-flex">{{ __('messages.no_records') }}</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            {!! $projects->withQueryString()->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- mobile filter button -->
<button class="btn filter-btn d-block d-xl-none">Filter <br><i class="fa fa-filter" aria-hidden="true"></i></button>
<!-- mobile filter  -->
<div class="filter-overlay">
    <div class="container">
        <button class="closebtn btn">&times;</button>
        <div class="sidebar mobile">
            <ul class="service">
                <li class="title">{{ __('app.project_sidebar_service') }}</li>
                @forelse ($services->take(5) as $service)
                <li>
                    <a href="{{ route('app.project.index', ['q' => $service->name]) }}" class="text-muted">{{ $service->name }}</a>
                </li>
                @empty
                @endforelse
                <li class="end">
                    <a href="" class="txtorange">{{ __('app.btn_view_all') }}</a>
                </li>
            </ul>
            <form action="">
                <ul class="range">
                    <li class="title">Price Range</li>
                    <li>
                        <input type="number" class="range" placeholder="{{ __('app.min') }}">
                        <span> - </span>
                        <input type="number" class="range" placeholder="{{ __('app.max') }}">
                    </li>
                </ul>
                <ul class="radio">
                    <li>
                        <input type="radio" id="rm1000m" name="price" value="0,1000">
                        <label for="rm1000m">
                            &lsaquo; MYR 1,000 </label> </li>
                    <li>
                        <input type="radio" id="rm5000m" name="price" value="0,5000">
                        <label for="rm5000m">
                            &lsaquo; MYR 5,000 </label> </li>
                    <li>
                        <input type="radio" id="rm10000m" name="price" value="0,10000">
                        <label for="rm10000m">
                            &lsaquo; MYR 10,000 </label> </li>
                    <li>
                        <input type="radio" id="rm15000m" name="price" value="0,15000">
                        <label for="rm15000m">
                            &lsaquo; MYR 15,000 </label> </li>
                    <li>
                        <input type="radio" id="rm20000m" name="price" value="0,20000">
                        <label for="rm20000m">
                            &lsaquo; MYR 20,000 </label> </li>
                </ul>
                <ul class="radio">
                    <li class="title">Locations</li>
                    @forelse ($areas as $area)
                    <li>
                        <input type="radio" id="m{{ $loop->iteration }}" name="location" value="{{ $area->id }}">
                        <label for="m{{ $loop->iteration }}">{{ $area->name . ' (' . $area->addresses_count . ')' }}</label>
                    </li>
                    @empty
                    @endforelse
                </ul>
                <ul class="radio rating">
                    <li class="title">Rating</li>
                    @for ($y = 1; $y <= 5; $y++) <li>
                        <input type="radio" id="mstar{{ $y }}" name="rating" value="{{ $y }}">
                        @for ($x=0; $x < $y; $x++) <label for="mstar{{ $y }}"><i class="fas fa-star"></i></label>
                            @endfor
                            </li>
                            @endfor
                </ul>
                <button type="submit" class="btn btn-orange w-100 mx-0 mb-3">Filter</button>
                <button type="submit" class="btn btn-black w-100 mx-0 mb-3">Reset Filter</button>
            </form>
        </div>
    </div>
</div>

@endsection