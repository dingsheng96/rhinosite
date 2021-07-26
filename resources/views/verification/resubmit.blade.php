@extends('layouts.master', ['title' => trans_choice('modules.merchant', 2), 'guest_view' => true])

@section('content')

<div class="container" style="padding-top: 7rem; padding-bottom: 5rem;">

    <form action="{{ route('verifications.store') }}" method="post" role="form" enctype="multipart/form-data">

        @csrf

        <div class="row">
            <div class="col-12 ">
                <div class="card card-body">

                    <h5 class="font-weight-bold">{{ __('app.complete_profile_title') }}</h5>
                    <p class="text-muted">{{ __('app.complete_profile_subtitle') }}</p>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name)  }}" class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="phone" class="col-form-label">{{ __('labels.contact_no') }} <span class="text-red">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white">+</span>
                                    </div>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                                </div>
                                @error('phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="email" class="col-form-label">{{ __('labels.email') }} <span class="text-red">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="website" class="col-form-label">{{ __('labels.website') }}</label>
                                <input type="url" name="website" id="website" value="{{ old('website') }}" class="form-control @error('website') is-invalid @enderror" placeholder="{{ __('labels.optional') }}">
                                @error('website')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="facebook" class="col-form-label">{{ __('labels.facebook') }}</label>
                                <input type="url" name="facebook" id="facebook" value="{{ old('facebook') }}" class="form-control @error('facebook') is-invalid @enderror" placeholder="{{ __('labels.optional') }}">
                                @error('facebook')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="business_since" class="col-form-label">{{ __('labels.business_since') }} <span class="text-red">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="business_since" id="business_since" value="{{ old('business_since') }}" class="form-control date-picker @error('business_since') is-invalid @enderror bg-white" readonly>
                                    <div class="input-group-append">
                                        <div class="input-group-text bg-white">
                                            <i class="far fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                @error('business_since')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="reg_no" class="col-form-label">{{ __('labels.reg_no') }} <span class="text-red">*</span></label>
                                <input type="text" name="reg_no" id="reg_no" value="{{ old('reg_no') }}" class="form-control @error('reg_no') is-invalid @enderror">
                                @error('reg_no')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="address_1" class="col-form-label">{{ __('labels.address_1') }} <span class="text-red">*</span></label>
                                <input type="text" name="address_1" id="address_1" class="form-control @error('address_1') is-invalid @enderror" value="{{ old('address_1', $user->address->address_1) }}">
                                @error('address_1')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="address_2" class="col-form-label">{{ __('labels.address_2') }}</label>
                                <input type="text" name="address_2" id="address_2" class="form-control @error('address_2') is-invalid @enderror" value="{{ old('address_2', $user->address->address_2) }}" placeholder="{{ __('labels.optional') }}">
                                @error('address_2')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="country" class="col-form-label">{{ trans_choice('labels.country', 1) }} <span class="text-red">*</span></label>
                                <select name="country" id="country" class="form-control @error('country') is-invalid @enderror country-state-filter">
                                    <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.country', 1))]) }} ---</option>
                                    @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ old('country') == $country->id || $country->set_default ? 'selected' : null }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="postcode" class="col-form-label">{{ __('labels.postcode') }} <span class="text-red">*</span></label>
                                <input type="text" name="postcode" id="postcode" class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode', $user->address->postcode) }}">
                                @error('postcode')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="country_state" class="col-form-label">{{ trans_choice('labels.country_state', 1) }} <span class="text-red">*</span></label>
                                <select name="country_state" id="country_state" class="form-control select2 @error('country_state') is-invalid @enderror country-state-dropdown city-filter" data-selected="{{ old('country_state', $user->address->countryState->id) }}"
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
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="city" class="col-form-label">{{ trans_choice('labels.city', 1) }} <span class="text-red">*</span></label>
                                <select name="city" id="city" class="form-control select2 @error('city') is-invalid @enderror city-dropdown" data-selected="{{ old('city', $user->address->city_id) }}"
                                    data-city-route="{{ route('data.countries.country-states.cities', ['__FIRST_REPLACE__', '__SECOND_REPLACE__']) }}">
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

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="ssm_cert" class="col-form-label">{{ __('labels.ssm_cert') }} <span class="text-red">*</span></label>
                                <input type="file" name="ssm_cert" id="ssm_cert" class="form-control-file @error('ssm_cert') is-invalid @enderror" accept="application/pdf">
                                @error('ssm_cert')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <ul class="px-3">{!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'PDF', 'dimension' => '1024x1024']) !!}</ul>
                        </div>
                    </div>

                    <hr>

                    <h5 class="font-weight-bold">Complete Person In Charge Information As Below</h5>
                    <p class="text-muted">Let us know who are the person in charge to be contact if there is any issues occur.</p>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="pic_name" class="col-form-label">{{ __('labels.pic_name') }} <span class="text-red">*</span></label>
                                <input type="text" name="pic_name" id="pic_name" value="{{ old('pic_name') }}" class="form-control @error('pic_name') is-invalid @enderror">
                                @error('pic_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="pic_phone" class="col-form-label">{{ __('labels.pic_contact') }} <span class="text-red">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white">+</span>
                                    </div>
                                    <input type="text" name="pic_phone" id="pic_phone" value="{{ old('pic_phone')  }}" class="form-control @error('pic_phone') is-invalid @enderror">
                                </div>
                                @error('pic_phone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="pic_email" class="col-form-label">{{ __('labels.pic_email') }} <span class="text-red">*</span></label>
                                <input type="email" name="pic_email" id="pic_email" value="{{ old('pic_email') }}" class="form-control @error('pic_email') is-invalid @enderror">
                                @error('pic_email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <button type="submit" class="btn btn-dark btn-lg col-12 col-md-4 mx-auto">
                            {{ strtoupper(__('labels.submit')) }}
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </form>
</div>


@endsection