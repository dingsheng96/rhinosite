@extends('layouts.master', ['title' => trans_choice('modules.merchant', 2), 'guest_view' => true])

@section('content')

<div class="container" style="padding-top: 7rem; padding-bottom: 5rem;">

    <form action="{{ route('verifications.store') }}" method="post" role="form" enctype="multipart/form-data">
        @csrf

        <div class="row mt-3">
            <div class="col-12 ">
                <div class="card card-body shadow">

                    <h5 class="font-weight-bold">{{ __('app.complete_profile_title') }}</h5>
                    <p class="text-muted">{{ __('app.complete_profile_subtitle') }}</p>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name ?? null)  }}" class="form-control @error('name') is-invalid @enderror">
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
                                <label for="phone" class="col-form-label">{{ __('labels.contact_no') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white">+</span>
                                    </div>
                                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone ?? null) }}" class="form-control @error('phone') is-invalid @enderror">
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
                                <label for="email" class="col-form-label">{{ __('labels.email') }} <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email ?? null) }}" class="form-control @error('email') is-invalid @enderror">
                                @error('email')
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
                                <label for="service" class="col-form-label">{{ __('labels.service') }} <span class="text-red">*</span></label>
                                <select name="service" id="service" class="form-control select2 @error('service') is-invalid @enderror">
                                    <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.service'))]) }} ---</option>
                                    @forelse($services as $service)
                                    <option value="{{ $service->id }}" {{ collect(old('service'))->contains($service->id) ? 'selected' : null }}>{{ $service->name }}</option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('service')
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
                                <label for="business_since" class="col-form-label">{{ __('labels.business_since') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" name="business_since" id="business_since" value="{{ old('business_since', $user->userDetail->business_since ?? null) }}" class="form-control date-picker @error('business_since') is-invalid @enderror bg-white" readonly>
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
                                <label for="reg_no" class="col-form-label">{{ __('labels.reg_no') }} <span class="text-danger">*</span></label>
                                <input type="text" name="reg_no" id="reg_no" value="{{ old('reg_no', $user->userDetail->reg_no ?? null) }}" class="form-control @error('reg_no') is-invalid @enderror">
                                @error('reg_no')
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
                                <label for="logo" class="col-form-label">{{ __('labels.logo') }} <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <input type="file" id="logo" name="logo" class="form-control-file custom-img-input @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                        @error('logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                        <ul class="pl-3 mt-3">{!! trans_choice('messages.upload_image_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG']) !!}</ul>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        <img src="{{ $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail d-block mx-auto">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="ssm_cert" class="col-form-label">{{ __('labels.ssm_cert') }} <span class="text-danger">*</span></label>
                                <input type="file" name="ssm_cert" id="ssm_cert" class="form-control-file @error('ssm_cert') is-invalid @enderror" accept="application/pdf">
                                @error('ssm_cert')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <ul class="px-3">{!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'PDF']) !!}</ul>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="website" class="col-form-label">{{ __('labels.website') }}</label>
                                <input type="url" name="website" id="website" value="{{ old('website', $user->userDetail->website ?? null) }}" class="form-control @error('website') is-invalid @enderror" placeholder="{{ __('labels.optional') }}">
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
                                <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $user->userDetail->facebook ?? null) }}" class="form-control @error('facebook') is-invalid @enderror" placeholder="{{ __('labels.optional') }}">
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
                                <label for="whatsapp" class="col-form-label">{{ __('labels.whatsapp') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white">+</span>
                                    </div>
                                    <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $user->userDetail->whatsapp ?? null) }}" class="form-control @error('whatsapp') is-invalid @enderror">
                                </div>
                                @error('whatsapp')
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
                                <label for="address_1" class="col-form-label">{{ __('labels.address_1') }} <span class="text-danger">*</span></label>
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
                                <label for="country" class="col-form-label">{{ trans_choice('labels.country', 1) }} <span class="text-danger">*</span></label>
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
                                <label for="postcode" class="col-form-label">{{ __('labels.postcode') }} <span class="text-danger">*</span></label>
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
                                <label for="country_state" class="col-form-label">{{ trans_choice('labels.country_state', 1) }} <span class="text-danger">*</span></label>
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
                                <label for="city" class="col-form-label">{{ trans_choice('labels.city', 1) }} <span class="text-danger">*</span></label>
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

                    <hr>

                    <h5 class="font-weight-bold">Complete Person In Charge Information As Below</h5>
                    <p class="text-muted">Let us know who are the person in charge to be contact if there is any issues occur.</p>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="pic_name" class="col-form-label">{{ __('labels.pic_name') }} <span class="text-danger">*</span></label>
                                <input type="text" name="pic_name" id="pic_name" value="{{ old('pic_name', $user->userDetail->pic_name ?? null) }}" class="form-control @error('pic_name') is-invalid @enderror">
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
                                <label for="pic_phone" class="col-form-label">{{ __('labels.pic_contact') }} <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-white">+</span>
                                    </div>
                                    <input type="text" name="pic_phone" id="pic_phone" value="{{ old('pic_phone', $user->userDetail->pic_phone ?? null)  }}" class="form-control @error('pic_phone') is-invalid @enderror">
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
                                <label for="pic_email" class="col-form-label">{{ __('labels.pic_email') }} <span class="text-danger">*</span></label>
                                <input type="email" name="pic_email" id="pic_email" value="{{ old('pic_email', $user->userDetail->pic_email ?? null) }}" class="form-control @error('pic_email') is-invalid @enderror">
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