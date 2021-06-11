@extends('layouts.master', ['title' => __('modules.dashboard')])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <img src="{{ 'https://ui-avatars.com/api/?background=f6993f&color=ffffff&rounded=true&name=?' }}" alt="user" class="img-size-50 img-circle">
                            <h4 class="ml-3 d-inline">{{ Auth::user()->name ?? '-' }}</h4>
                        </div>
                        <div class="col-12 col-md-6 d-flex flex-column align-items-md-end align-items-center mt-3 mt-md-0">
                            <p class="mb-0">
                                <label class="mr-2">{{ __('labels.joined_since') . ' :' }}</label>
                                {{ Auth::user()->created_at->format('d M Y') }}
                            </p>
                            <p class="mb-0">
                                <label class="mr-2">{{ __('labels.industry_experience') . ' :' }}</label>
                                {{ Auth::user()->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item border-0 px-0">
                                    <img src="{{ 'https://ui-avatars.com/api/?background=f6993f&color=ffffff&rounded=true&name=?' }}" alt="user" class="img-size-32 img-circle">
                                    <span class="ml-3">{{ Auth::user()->mobile_no ?? '-' }}</span>
                                </li>
                                <li class="list-group-item border-0 px-0">
                                    <img src="{{ 'https://ui-avatars.com/api/?background=f6993f&color=ffffff&rounded=true&name=?' }}" alt="user" class="img-size-32 img-circle">
                                    <span class="ml-3">{{ Auth::user()->email ?? '-' }}</span>
                                </li>
                                <li class="list-group-item border-0 px-0">
                                    <img src="{{ 'https://ui-avatars.com/api/?background=f6993f&color=ffffff&rounded=true&name=?' }}" alt="user" class="img-size-32 img-circle">
                                    <span class="ml-3">{{ Auth::user()->full_address ?? '-' }}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-12 col-md-6 d-flex flex-column align-items-md-end align-items-center mt-3 mt-md-0">
                            <a role="button" href="#" class="mt-auto">
                                <i class="fas fa-edit"></i>
                                {{ __('labels.edit', ['module' => __('modules.profile')]) }}
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 col-12">
            <div class="small-box bg-info">
                <div class="inner">
                    <p>{{ __('labels.listed_projects') }}</p>
                    <h3>150</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-briefcase"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="small-box bg-teal">
                <div class="inner">
                    <p>{{ __('labels.current_ads_boosting') }}</p>
                    <h3>150</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-rocket"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="small-box bg-purple">
                <div class="inner">
                    <p>{{ __('labels.total_ads_available') }}</p>
                    <h3>150</h3>
                </div>
                <div class="icon">
                    <i class="fas fa-images"></i>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-12">
            <div class="small-box bg-light">
                <div class="inner">
                    <p>{{ __('labels.current_bought_product') }}</p>
                    <h3>150</h3>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-12 my-3">
            <h5 class="font-weight-bold d-inline">{{ __('labels.listed_projects') }}</h5>
            <a role="button" href="" class="float-right">{{ __('labels.view_all') . ' >' }}</a>
        </div>

        <div class="col-12 my-md-3 my-0">
            <div class="card-deck">
                <div class="card">
                    <img src="" alt="image" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                    </div>
                </div>
                <div class="card">
                    <img src="" alt="image" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                    </div>
                </div>
                <div class="card">
                    <img src="" alt="image" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                    </div>
                </div>
                <div class="card">
                    <img src="" alt="image" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr>

    <div class="row">
        <div class="col-12 my-3">
            <h5 class="font-weight-bold d-inline">{{ __('labels.current_ads_boosting') }}</h5>
            <a role="button" href="" class="float-right">{{ __('labels.view_all') . ' >' }}</a>
        </div>

        <div class="col-12 my-md-3 my-0">
            <div class="card-deck">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="ribbon-wrapper ribbon ribbon-lg">
                                    <div class="ribbon bg-warning">
                                        Highlighted
                                    </div>
                                </div>
                                <img src="" alt="image">
                            </div>
                            <div class="col-12 col-md-8">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-4">
                                <div class="ribbon-wrapper ribbon ribbon-lg">
                                    <div class="ribbon bg-success">
                                        Top Ads
                                    </div>
                                </div>
                                <img src="" alt="image">
                            </div>
                            <div class="col-12 col-md-8">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection