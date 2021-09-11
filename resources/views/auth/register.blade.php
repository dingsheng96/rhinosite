@extends('layouts.master', ['guest_view' => true, 'body' => 'enduser', 'title' => __('modules.app.register')])

@section('content')

<div id="subpage-header">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-11">
                <h1>{{ __('app.register_title_main') }}</h1>
            </div>
        </div>
    </div>
</div>

<div id="register-1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 d-inline-flex">
                <div class="login-container">
                    <p class="login-title">{{ __('app.register_form_title') }}</p>

                    <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" role="form">
                        @csrf

                        <input type="hidden" name="role" value="{{ request()->get('role') }}">

                        <div class="input-group mb-3">
                            <p class="login-text">{{ __('labels.name') }} <span class="text-danger">*</span></p>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" class="@error('name') is-invalid @enderror">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <p class="login-text">{{ __('labels.contact_no') }} <span class="text-danger">*</span></p>
                            <input type="text" name="phone" id="phone" value="{{ old('phone') }}" class="@error('phone') is-invalid @enderror" placeholder="Eg: 60123456789">
                            @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <p class="login-text">{{ __('labels.email') }} <span class="text-danger">*</span></p>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="@error('email') is-invalid @enderror">
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <p class="login-text">{{ __('labels.password') }} <span class="text-danger">*</span></p>
                            <input type="password" name="password" id="password" value="{{ old('password') }}" class="@error('password') is-invalid @enderror">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <small>* {{ __('messages.password_format') }}</small>
                        </div>

                        <div class="input-group mb-3">
                            <p class="login-text">{{ __('labels.password_confirmation') }} <span class="text-danger">*</span></p>
                            <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" class="@error('password-confirmation') is-invalid @enderror">
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <p class="login-text">{{ __('labels.address_1') }} <span class="text-danger">*</span></p>
                            <input type="text" name="address_1" id="address_1" value="{{ old('address_1') }}" class="@error('address_1') is-invalid @enderror">
                            @error('address_1')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <p class="login-text">{{ __('labels.address_2') }}<span class="text-danger">*</span></p>
                            <input type="text" name="address_2" id="address_2" value="{{ old('address_2') }}" class="@error('address_2') is-invalid @enderror">
                            @error('address_2')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <p class="login-text">{{ __('labels.postcode') }} <span class="text-danger">*</span></p>
                                    <input type="text" name="postcode" id="postcode" value="{{ old('postcode') }}" class="@error('postcode') is-invalid @enderror">
                                    @error('postcode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="input-group mb-3">
                                    <p class="login-text">{{ trans_choice('labels.country', 1) }} <span class="text-danger">*</span></p>
                                    <select name="country" id="country" class="@error('country') is-invalid @enderror country-state-filter">
                                        <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.country', 1))]) }} ---</option>
                                        @foreach ($countries as $country)
                                        <option value="{{ $country->id }}" {{ (old('country') == $country->id || $country->set_default) ? 'selected' : null }}>{{ $country->name }}</option>
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
                                    <p class="login-text">{{ trans_choice('labels.country_state', 1) }} <span class="text-danger">*</span></p>
                                    <select name="country_state" id="country_state" class="@error('country_state') is-invalid @enderror country-state-dropdown city-filter" data-selected="{{ old('country_state') }}"
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
                                    <p class="login-text">{{ trans_choice('labels.city', 1) }} <span class="text-danger">*</span></p>
                                    <select name="city" id="city" class="@error('city') is-invalid @enderror city-dropdown" data-selected="{{ old('city') }}" data-city-route="{{ route('data.countries.country-states.cities', ['__FIRST_REPLACE__', '__SECOND_REPLACE__']) }}">
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

                        <p class="text-break">
                            <input type="checkbox" id="agree" name="agree" class="mt-2 @error('agree') is-invalid @enderror" {{ old('agree') ? 'checked' : null }}>
                            <label class="login-text" for="agree" class="mb-0">{!! __('app.register_agreement') !!}</label>
                            @error('agree')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </p>

                        <button type="submit" class="btn btn-black w-100 ml-0 mb-3">{{ __('app.register_btn_submit') }}</button>

                    </form>
                </div>
            </div>
            <div class="col-lg-6 d-inline-flex px-0">
                <img src="{{ asset('storage/register.jpg') }}" alt="register_image" class="res-img">
            </div>
        </div>
    </div>
</div>

@endsection