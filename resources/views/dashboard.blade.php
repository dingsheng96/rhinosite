@extends('layouts.master', ['current_module' => __('modules.dashboard')])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <img src="{{ 'https://ui-avatars.com/api/?background=f6993f&color=ffffff&rounded=true&name=?' }}" alt="user" class="img-responsive img-circle">
                        </div>
                        <div class="col-12 col-md-6 text-md-right">
                            <h5><label class="mr-2">{{ __('labels.joined_since') . ' :' }}</label>{{ Auth::user()->created_at->format('d M Y') }}</h5>
                            <h5><label class="mr-2">{{ __('labels.industry_experience') . ' :' }}</label>{{ Auth::user()->created_at->format('d M Y') }}</h5>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12 col-md-9">
                            <div class="input-group row mb-3">
                                <div class="col-3">
                                    <img src="{{ 'https://ui-avatars.com/api/?background=f6993f&color=ffffff&rounded=true&name=?' }}" alt="user" class="img-responsive img-circle">
                                </div>
                                <div class="col-9">
                                    {{ Auth::user()->mobile_no ?? '-' }}
                                </div>
                            </div>

                            <div class="input-group row mb-3">
                                <div class="col-3">
                                    <img src="{{ 'https://ui-avatars.com/api/?background=f6993f&color=ffffff&rounded=true&name=?' }}" alt="user" class="img-responsive img-circle">
                                </div>
                                <div class="col-9">
                                    {{ Auth::user()->email ?? '-' }}
                                </div>
                            </div>

                            <div class="input-group row mb-3">
                                <div class="col-3">
                                    <img src="{{ 'https://ui-avatars.com/api/?background=f6993f&color=ffffff&rounded=true&name=?' }}" alt="user" class="img-responsive img-circle">
                                </div>
                                <div class="col-9">
                                    {{ Auth::user()->full_address ?? '-' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-3 text-right">
                            <a role="button" href="#" class="btn btn-light bg-orange">
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
        <div class="col-sm-3 col-6">
            <div class="small-box bg-info">
                <div class="inner text-center">
                    <p>{{ __('labels.listed_projects') }}</p>
                    <h3>150</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3 col-6">
            <div class="small-box bg-teal">
                <div class="inner text-center">
                    <p>{{ __('labels.current_ads_boosting') }}</p>
                    <h3>150</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3 col-6">
            <div class="small-box bg-purple">
                <div class="inner text-center">
                    <p>{{ __('labels.total_ads_available') }}</p>
                    <h3>150</h3>
                </div>
            </div>
        </div>
        <div class="col-sm-3 col-6">
            <div class="small-box bg-light">
                <div class="inner text-center">
                    <p>{{ __('labels.current_bought_product') }}</p>
                    <h3>150</h3>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection