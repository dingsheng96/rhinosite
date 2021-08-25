@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.service')])

@section('content')

<div id="merchant-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('modules.app.service') }}</h1>
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

<div id="compare-1">
    <div class="container">
        <h2>{{ __('app.compare_title_main') }}</h2>
        <p class="txtblk">{{ __('app.compare_subtitle', ['count' => $compare_lists->count()]) }}</p>

        <div class="row">
            @forelse ($compare_lists as $list)
            <div class="col-12 col-lg-4">
                <div class="card card-body shadow-lg h-100">
                    <img src="{{ $list->thumbnail->full_file_path }}" alt="service_main_image" class="compare-img">
                    <p class="compare-title">{{ $list->english_title }}</p>
                    <small>{{ $list->user->name }}</small>

                    <div class="compare-line"></div>

                    {{-- <p class="compare-price">From RM5</p> --}}
                    <p>Location</p>
                    <p class="txtblk font-semibold mb-5">
                        <i class="fas fa-map-marker-alt text-danger mr-1"></i>
                        {{ $list->location }}
                    </p>

                    <p>Services Provided</p>
                    <div class="provided-services">
                        <i class="fa fa-check" aria-hidden="true"></i><span class="ml-3">{{ $list->user->service->name ?? '-' }}</span>
                    </div>
                    @auth
                    @if (!Auth::user()->favouriteProjects()->get()->contains($list->id))
                    <button class="btn btn-orange w-100 mb-3 btn-add-wishlist" data-wishlist="{{ route('app.wishlist.store') }}" data-project="{{ $list->id }}">{{ __('app.project_details_btn_add_wishlist') }}</button>
                    @endif
                    <button class="btn btn-compare" data-compare-route="{{ route('app.comparisons.store') }}" data-compare-target="{{ strtolower(class_basename(get_class($list))) }}" data-compare-target-id="{{ $list->id }}" data-refresh="1">{{ __('app.project_btn_remove_compare') }}</button>
                    @endauth
                </div>
            </div>
            @empty
            <div class="col-12 d-inline-flex justify-content-center">{{ __('messages.empty_list', ['list' => strtolower(__('labels.services'))]) }}</div>
            <div class="col-12 d-flex justify-content-center my-5">
                <a href="{{ route('app.project.index') }}" class="btn btn-orange">{{ __('app.user_dashboard_wishlist_btn_explore_service') }}</a>
            </div>
            @endforelse

        </div>

    </div>
</div>

@endsection