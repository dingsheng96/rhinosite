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

@include('app.topservice')

<div id="merchant-2">
    <div class="container">
        <div class="d-flex">
            <div class="sidebar mb-5">
                @isset($services)
                <ul class="service">
                    <li class="title">{{ __('app.project_sidebar_service') }}</li>
                    @foreach ($services->sortBy('name') as $service)
                    <li @if($loop->iteration > 5) {!! 'class="more d-none"' !!} @endif>
                        <a href="{{ route('app.project.index', ['q' => $service->name]) }}" class="text-muted">
                            {{ $service->name_with_project_count }}
                        </a>
                    </li>
                    @endforeach
                    <li class="end">
                        <a href="#" class="txtorange btn-view-more" data-text-replace="{{ __('app.btn_view_less') }}">{{ __('app.btn_view_more') }}</a>
                    </li>
                </ul>
                @endisset

                <form action="{{ route('app.project.index') }}" method="GET" role="form">
                    <ul class="range">
                        <li class="title">{{ __('app.project_sidebar_price_range') }}</li>
                        {{-- <li>
                            <input type="number" class="range" placeholder="{{ __('app.min') }}">
                        <span> - </span>
                        <input type="number" class="range" placeholder="{{ __('app.max') }}">
                        </li> --}}
                    </ul>
                    <ul class="radio">
                        <li>
                            <input type="radio" id="rm1000" name="price" value="0,1000" {{ request()->get('price') == '0,1000' ? 'checked' : null }}>
                            <label for="rm1000">&lsaquo; MYR 1,000 </label>
                        </li>
                        <li>
                            <input type="radio" id="rm5000" name="price" value="1001,5000" {{ request()->get('price') == '1001,5000' ? 'checked' : null }}>
                            <label for="rm5000">MYR 1,001 - MYR 5,000 </label>
                        </li>
                        <li>
                            <input type="radio" id="rm10000" name="price" value="5001,10000" {{ request()->get('price') == '5001,10000' ? 'checked' : null }}>
                            <label for="rm10000">MYR 5,001 - MYR 10,000 </label>
                        </li>
                        <li>
                            <input type="radio" id="rm15000" name="price" value="10001,15000" {{ request()->get('price') == '10001,15000' ? 'checked' : null }}>
                            <label for="rm15000">MYR 10,001 - MYR 15,000 </label>
                        </li>
                        <li>
                            <input type="radio" id="rm15001" name="price" value="15001," {{ request()->get('price') == '15001,' ? 'checked' : null }}>
                            <label for="rm15001">&rsaquo; MYR 15,000 </label>
                        </li>
                    </ul>
                    <ul class="radio">
                        <li class="title">{{ __('app.project_sidebar_location') }}</li>
                        @forelse ($areas as $area)
                        <li>
                            <input type="radio" id="{{ $loop->iteration }}" name="location" value="{{ $area->id }}" {{ request()->get('location') == $area->id ? 'checked' : null }}>
                            <label for="{{ $loop->iteration }}">{{ $area->name . ' (' . $area->addresses_count . ')' }}</label>
                        </li>
                        @empty
                        @endforelse
                    </ul>
                    <ul class="radio rating">
                        <li class="title">{{ __('app.project_sidebar_rating') }}</li>
                        @for ($y = 1; $y <= 5; $y++) <li>
                            <input type="radio" id="star{{ $y }}" name="rating" value="{{ $y }}" {{ request()->get('rating') == $y ? 'checked' : null }}>
                            @for ($x=0; $x < $y; $x++) <label for="star{{ $y }}"><i class="fas fa-star"></i></label>
                                @endfor
                                </li>
                                @endfor
                    </ul>
                    <button type="submit" class="btn btn-orange w-100 mx-0 mb-3">{{ __('app.project_sidebar_btn_filter') }}</button>
                    <button type="button" class="btn btn-black w-100 m-0 btn-reset-filter">{{ __('app.project_sidebar_btn_reset') }}</button>
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

                        @auth
                        <button id="compare" name="compare" class="btn btn-orange ml-auto btn-collapse">
                            {{ __('app.project_btn_compare') }}
                        </button>
                        @else
                        <a role="button" href="{{ route('login') }}" class="btn btn-orange ml-auto">{{ __('app.project_btn_login_to_compare') }}</a>
                        @endauth
                    </div>

                    @auth
                    <div class="row search-filter-result compare collapse">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <span>Choose a maximum of 3 contractors to compare now</span>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <span class="ml-auto mr-2">Selected : <span class="compare-count">{{ Auth::user()->comparisons()->count() }}</span></span>
                            <button type="button" onclick="return window.location.href = '{{ route('app.comparisons.index') }}';" class="btn btn-black mx-0 btn-view-result" {{ Auth::user()->comparisons()->count() < 2 ? 'disabled' : null }}>View Result</a>
                        </div>
                    </div>
                    @endauth

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
                                                <img src="{{ asset('assets/adboost.png') }}" class="highlight-img">
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
                                                <span class="merchant-footer-right"><i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $project->location }}</span>
                                            </div>
                                        </a>

                                        @auth
                                        @if (Auth::user()->comparisons()->get()->contains($project->id))
                                        <button class="btn btn-compare collapse bg-danger" data-compare-route="{{ route('app.comparisons.store') }}" data-compare-target="{{ strtolower(class_basename(get_class($project))) }}" data-compare-target-id="{{ $project->id }}"
                                            data-compare-text="{{ __('app.project_btn_add_compare') }}">{{ __('app.project_btn_remove_compare') }}</button>
                                        @else
                                        <button class="btn btn-compare collapse" data-compare-route="{{ route('app.comparisons.store') }}" data-compare-target="{{ strtolower(class_basename(get_class($project))) }}" data-compare-target-id="{{ $project->id }}"
                                            data-compare-text="{{ __('app.project_btn_remove_compare') }}">{{ __('app.project_btn_add_compare') }}</button>
                                        @endif
                                        @endauth

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
                        <div class="col-12 d-flex justify-content-center">
                            <p class="text-secondary">{!! $projects->firstItem() !!} - {!! $projects->lastItem() !!} of {!! $projects->total()!!} services for peace of mind</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- mobile filter button -->
<button class="btn filter-btn d-block d-xl-none">{{ __('app.project_mobile_sidebar_toggle_title') }} <br><i class="fa fa-filter" aria-hidden="true"></i></button>
<!-- mobile filter  -->
<div class="filter-overlay">
    <div class="container">
        <button class="closebtn btn">&times;</button>
        <div class="sidebar mobile">
            <ul class="service">
                <li class="title">{{ __('app.project_sidebar_service') }}</li>
                @foreach ($services as $service)
                <li @if($loop->iteration > 5) {!! 'class="more d-none"' !!} @endif><a href="{{ route('app.project.index', ['q' => $service->name]) }}" class="text-muted">{{ $service->name }}</a></li>
                @endforeach
                <li class="end">
                    <a href="#" class="txtorange btn-view-more" data-text-replace="{{ __('app.btn_view_less') }}">{{ __('app.btn_view_more') }}</a>
                </li>
            </ul>
            <form action="">
                <ul class="range">
                    <li class="title">{{ __('app.project_sidebar_price_range') }}</li>
                    {{-- <li>
                        <input type="number" class="range" placeholder="{{ __('app.min') }}">
                    <span> - </span>
                    <input type="number" class="range" placeholder="{{ __('app.max') }}">
                    </li> --}}
                </ul>
                <ul class="radio">
                    <li>
                        <input type="radio" id="rm1000m" name="price" value="0,1000" {{ request()->get('price') == '0,1000' ? 'checked' : null }}>
                        <label for="rm1000m">&lsaquo; MYR 1,000 </label>
                    </li>
                    <li>
                        <input type="radio" id="rm5000m" name="price" value="1001,5000" {{ request()->get('price') == '1001,5000' ? 'checked' : null }}>
                        <label for="rm5000m">MYR 1,001 - MYR 5,000 </label>
                    </li>
                    <li>
                        <input type="radio" id="rm10000m" name="price" value="5001,10000" {{ request()->get('price') == '5001,10000' ? 'checked' : null }}>
                        <label for="rm10000m">MYR 5,001 - MYR 10,000 </label>
                    </li>
                    <li>
                        <input type="radio" id="rm15000m" name="price" value="10001,15000" {{ request()->get('price') == '10001,15000' ? 'checked' : null }}>
                        <label for="rm15000m">MYR 10,001 - MYR 15,000 </label>
                    </li>
                    <li>
                        <input type="radio" id="rm15001m" name="price" value="15001," {{ request()->get('price') == '15001,' ? 'checked' : null }}>
                        <label for="rm15001m">&rsaquo; MYR 15,000 </label>
                    </li>
                </ul>
                <ul class="radio">
                    <li class="title">{{ __('app.project_sidebar_location') }}</li>
                    @forelse ($areas as $area)
                    <li>
                        <input type="radio" id="m{{ $loop->iteration }}" name="location" value="{{ $area->id }}" {{ request()->get('location') == $area->id ? 'checked' : null }}>
                        <label for="m{{ $loop->iteration }}">{{ $area->name . ' (' . $area->addresses_count . ')' }}</label>
                    </li>
                    @empty
                    @endforelse
                </ul>
                <ul class="radio rating">
                    <li class="title">{{ __('app.project_sidebar_rating') }}</li>
                    @for ($y = 1; $y <= 5; $y++) <li>
                        <input type="radio" id="mstar{{ $y }}" name="rating" value="{{ $y }}" {{ request()->get('rating') == $y ? 'checked' : null }}>
                        @for ($x=0; $x < $y; $x++) <label for="mstar{{ $y }}"><i class="fas fa-star"></i></label>
                            @endfor
                            </li>
                            @endfor
                </ul>
                <button type="submit" class="btn btn-orange w-100 mx-0 mb-3">{{ __('app.project_sidebar_btn_filter') }}</button>
                <button type="button" class="btn btn-black w-100 m-0 btn-reset-filter">{{ __('app.project_sidebar_btn_reset') }}</button>
            </form>
        </div>
    </div>
</div>

@endsection