@extends('layouts.master', ['title' => __('modules.dashboard'), 'guest_view' => true, 'body' => 'enduser'])

@section('content')


<div id="subpage-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('app.user_dashboard_main_title') }}</h1>
            </div>
        </div>
    </div>
</div>

<div id="user-directory" class="d-xl-none d-block">
    <div class="container">
        <ul class="account">
            <li class="title">
                <a data-toggle="collapse" data-target="#userdirectory" aria-expanded="false" aria-controls="userdirectory" class="collapsed">
                    {{ __('app.user_dashboard_sidebar_title') }}
                </a>
            </li>
            <div class="collapse" id="userdirectory">
                <li class="{{ Nav::hasSegment('dashboard', 1, 'active') }}">
                    <a href="{{ route('dashboard') }}">{{ __('app.user_dashboard_sidebar_profile') }}</a>
                </li>
                <li class="{{ Nav::hasSegment('account', 1, 'active') }}">
                    <a href="{{ route('account.index') }}">{{ __('app.user_dashboard_sidebar_profile') }}</a>
                </li>
                <li class="{{ Nav::hasSegment('wishlist', 1, 'active') }}">
                    <a href="{{ route('app.wishlist.index') }}">{{ __('app.user_dashboard_sidebar_wishlist') }}</a>
                </li>
            </div>
        </ul>
    </div>
</div>

<div id="user">
    <div class="container">
        <div class="d-flex">
            <div class="sidebar">
                <ul class="account">
                    <li class="title">{{ __('app.user_dashboard_sidebar_title') }}</li>
                    <li class="{{ Nav::hasSegment('dashboard', 1, 'active') }}">
                        <a href="{{ route('dashboard') }}">{{ __('app.user_dashboard_sidebar_dashboard') }}</a>
                    </li>
                    <li class="{{ Nav::hasSegment('account', 1, 'active') }}">
                        <a href="{{ route('account.index') }}">{{ __('app.user_dashboard_sidebar_profile') }}</a>
                    </li>
                    <li class="{{ Nav::hasSegment('wishlist', 1, 'active') }}">
                        <a href="{{ route('app.wishlist.index') }}">{{ __('app.user_dashboard_sidebar_wishlist') }}</a>
                    </li>
                </ul>
            </div>
            <div class="content">

                <div id="user-profile">

                    <div class="row align-items-end mt-md-4 mb-4">
                        <div class="col-md-8">
                            <h3>{{ __('app.user_dashboard_sidebar_profile') }}</h3>
                            <p class="mb-3 mb-md-0">{{ __('app.user_dashboard_profile_subtitle') }}</p>
                        </div>
                    </div>

                    @if (Session::has('success'))
                    <div class="alert alert-success" role="alert">
                        <small>{!! Session::get('success') !!}</small>
                    </div>
                    @endif

                    <!-- forms start here -->
                    <form action="{{ route('account.store') }}" method="POST" role="form" enctype="multipart/form-data">
                        @csrf

                        <div class="input-group mb-3">
                            <label class="font-medium" for="name">{{ __('labels.name') }} <span class="text-red">*</span></label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="@error('name') is-invalid @enderror">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <label class="font-medium" for="email">{{ __('labels.email') }} <span class="text-red">*</span></label>
                            <input type="text" name="email" id="email" value="{{ old('email', $user->email) }}" class="@error('email') is-invalid @enderror">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <label class="font-medium" for="phone">{{ __('labels.contact_no') }} <span class="text-red">*</span></label>
                            <input type="text" name="phone" id="phone" value="+{{ old('phone', $user->phone) }}" class="@error('phone') is-invalid @enderror">
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <label class="font-medium" for="logo">{{ __('labels.upload_profile_image') }}</label>
                            <input type="file" name="logo" id="logo" class="form-control-file @error('logo') is-invalid @enderror">
                            @error('logo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <ul class="pl-3 mt-3">
                                {!! trans_choice('messages.upload_file_rules', 1, ['extensions' => 'JPG,JPEG,PNG', 'maxsize' => '2mb']) !!}
                            </ul>
                        </div>

                        <hr>
                        <div class="input-group mb-3">
                            <label class="font-medium" for="address_1">{{ __('labels.address_1') }} <span class="text-red">*</span></label>
                            <input type="text" name="address_1" id="address_1" value="{{ old('address_1', $address->address_1) }}" class="@error('address_1') is-invalid @enderror">
                            @error('address_1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <label class="font-medium" for="address_2">{{ __('labels.address_2') }} <span class="text-red">*</span></label>
                            <input type="text" name="address_2" id="address_2" value="{{ old('address_2', $address->address_2) }}" class="@error('address_2') is-invalid @enderror">
                            @error('address_2')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <label class="font-medium" for="postcode">{{ __('labels.postcode') }} <span class="text-red">*</span></label>
                                    <input type="text" name="postcode" id="postcode" value="{{ old('postcode', $address->postcode) }}" class="@error('postcode') is-invalid @enderror">
                                    @error('postcode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <label class="font-medium" for="country">{{ trans_choice('labels.country', 1) }} <span class="text-red">*</span></label>
                                    <select name="country" id="country" class="@error('country') is-invalid @enderror country-state-filter">
                                        <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.country', 1))]) }} ---</option>
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" {{ (old('country', $address->city->country->id) == $country->id || $country->set_default) ? 'selected' : null }}>{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <label class="font-medium" for="country_state">{{ trans_choice('labels.country_state', 1) }} <span class="text-red">*</span></label>
                                    <select name="country_state" id="country_state" class="@error('country_state') is-invalid @enderror country-state-dropdown city-filter" data-selected="{{ old('country_state', $address->countryState->id) }}"
                                        data-country-state-route="{{ route('data.countries.country-states', ['__REPLACE__']) }}">
                                        <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.country_state', 1))]) }} ---</option>
                                    </select>
                                    @error('country_state')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <label class="font-medium" for="city">{{ trans_choice('labels.city', 1) }} <span class="text-red">*</span></label>
                                    <select name="city" id="city" class="@error('city') is-invalid @enderror city-dropdown" data-selected="{{ old('city', $address->city->id) }}" data-city-route="{{ route('data.countries.country-states.cities', ['__FIRST_REPLACE__', '__SECOND_REPLACE__']) }}">
                                        <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.city', 1))]) }} ---</option>
                                    </select>
                                    @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="input-group mb-3">
                            <label class="form-medium" for="password">{{ __('labels.new_password') }}</label>
                            <input type="password" name="password" id="password" value="{{ old('password') }}" class="@error('password') is-invalid @enderror">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <small>* {{ __('messages.password_format') }}</small>
                        </div>

                        <div class="input-group mb-3">
                            <label class="form-medium" for="password_confirmation">{{ __('labels.new_password_confirmation') }}</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" class="@error('password-confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-orange">{{ __('app.user_dashboard_profile_btn_update') }}</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection