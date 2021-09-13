@extends('merchant.layouts.master', ['title' => __('labels.user_account')])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12 col-md-4">
            <div class="card shadow">
                <div class="card-body box-profile">
                    <div class="text-center">
                        <img class="profile-user-img img-fluid img-circle" src="{{ optional($user->logo)->full_file_path ?? $default_preview }}" alt="profile">
                        <h3 class="profile-username">{{ $user->name }}</h3>
                        <h5 class="mb-3">{!! $user->status_label !!}</h5>
                    </div>

                    <ul class="list-group list-group-unbordered mb-3">
                        @if (!empty($user->userDetail))
                        <li class="list-group-item">
                            <strong><i class="fas fa-cube mr-1 text-cyan"></i> {{ __('labels.service') }}</strong>
                            <p class="text-muted">{{ $user->userDetail->service->name }}</p>
                        </li>
                        <li class="list-group-item">
                            <strong><i class="fas fa-briefcase mr-1 text-purple"></i> {{ __('labels.years_of_experience') }}</strong>
                            <p class="text-muted">{{ trans_choice('labels.year', $user->userDetail->years_of_experience, ['value' => $user->userDetail->years_of_experience]) }}</p>
                        </li>
                        <li class="list-group-item">
                            <strong><i class="fas fa-globe-asia mr-1 text-indigo"></i> {{ __('labels.website') }}</strong>
                            <p class="text-muted">{{ $user->userDetail->website ?? '-' }}</p>
                        </li>
                        <li class="list-group-item">
                            <strong><i class="fab fa-facebook mr-1 text-primary"></i> {{ __('labels.facebook') }}</strong>
                            <p class="text-muted">{{ $user->userDetail->facebook ?? '-' }}</p>
                        </li>
                        <li class="list-group-item">
                            <strong><i class="fas fa-user mr-1 text-secondary"></i> {{ __('labels.person_in_charge') }}</strong>
                            <p class="text-muted">
                                {{ $user->userDetail->pic_name }} <br />
                                {{ $user->userDetail->formatted_pic_phone }} <br />
                                {{ $user->userDetail->pic_email }}
                            </p>
                        </li>
                        @endif
                        <li class="list-group-item">
                            <strong><i class="fas fa-clock mr-1 text-orange"></i> {{ __('labels.last_login_at') }}</strong>
                            <p class="text-muted">{{ $user->last_login_at->toDateTimeString() ?? '-' }}</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-8">

            <div class="row">
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-header bg-transparent p-2">
                            <ul class="nav nav-pills">
                                <li class="nav-item"><a class="nav-link active" href="#profile" data-toggle="tab">{{ __('labels.profile') }}</a></li>
                                <li class="nav-item"><a class="nav-link" href="#location" data-toggle="tab">{{ __('labels.location') }}</a></li>
                                <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">{{ __('labels.settings') }}</a></li>
                            </ul>
                        </div>
                        <form action="{{ route('merchant.account.store') }}" method="post" role="form" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="profile">

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) ?? null }}" class="form-control ucfirst @error('name') is-invalid @enderror">
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
                                                        <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) ?? null }}" class="form-control @error('phone') is-invalid @enderror">
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
                                                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) ?? null }}" class="form-control @error('email') is-invalid @enderror">
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
                                                    <label for="reg_no" class="col-form-label">{{ __('labels.reg_no') }} <span class="text-red">*</span></label>
                                                    <input type="text" name="reg_no" id="reg_no" value="{{ old('reg_no', $user->userDetail->reg_no ?? null) }}" class="form-control @error('reg_no') is-invalid @enderror">
                                                    @error('reg_no')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="business_since" class="col-form-label">{{ __('labels.year_of_experience') }} <span class="text-red">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" name="business_since" id="business_since" value="{{ old('business_since', $user->userDetail->business_since) }}" class="form-control date-picker @error('business_since') is-invalid @enderror">
                                                        @error('experience')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                        <div class="input-group-append">
                                                            <div class="input-group-text bg-white">
                                                                <i class="far fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="website" class="col-form-label">{{ __('labels.website') }}</label>
                                                    <input type="url" name="website" id="website" value="{{ old('website', $user->userDetail->website ?? null) }}" class="form-control @error('website') is-invalid @enderror">
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
                                                    <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $user->userDetail->facebook ?? null) }}" class="form-control @error('facebook') is-invalid @enderror">
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
                                                        <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $user->userDetail->whatsapp) ?? null }}" class="form-control @error('whatsapp') is-invalid @enderror">
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
                                                    <label for="pic_name" class="col-form-label">{{ __('labels.pic_name') }} <span class="text-red">*</span></label>
                                                    <input type="text" name="pic_name" id="pic_name" value="{{ old('pic_name', $user->userDetail->pic_name ?? null) }}" class="form-control @error('pic_name') is-invalid @enderror" required>
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
                                                        <input type="text" name="pic_phone" id="pic_phone" value="{{ old('pic_phone', $user->userDetail->pic_phone ?? null)  }}" class="form-control @error('pic_phone') is-invalid @enderror" required>
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
                                                    <input type="email" name="pic_email" id="pic_email" value="{{ old('pic_name', $user->userDetail->pic_email ?? null) }}" class="form-control @error('pic_email') is-invalid @enderror" required>
                                                    @error('pic_email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane fade show" id="location">

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="address_1" class="col-form-label">{{ __('labels.address_1') }} <span class="text-red">*</span></label>
                                                    <input type="text" name="address_1" id="address_1" class="form-control @error('address_1') is-invalid @enderror" value="{{ old('address_1', $user->address->address_1 ?? null) }}" required>
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
                                                    <input type="text" name="address_2" id="address_2" class="form-control @error('address_2') is-invalid @enderror" value="{{ old('address_2', $user->address->address_2 ?? null) }}" required>
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
                                                    <select name="country" id="country" class="form-control select2 @error('country') is-invalid @enderror country-state-filter" required>
                                                        <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.country', 1))]) }} ---</option>
                                                        @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" {{ old('country', $user->address->city->country->id ?? 0) == $country->id ? 'selected' : null }}>{{ $country->name }}</option>
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
                                                    <input type="text" name="postcode" id="postcode" class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode', $user->address->postcode ?? null) }}" required>
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
                                                    <select name="country_state" id="country_state" class="form-control select2 @error('country_state') is-invalid @enderror country-state-dropdown city-filter" data-selected="{{ old('country_state', $user->address->city->countryState->id ?? 0) }}"
                                                        data-country-state-route="{{ route('merchant.data.countries.country-states', ['__REPLACE__']) }}" required>
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
                                                    <select name="city" id="city" class="form-control select2 @error('city') is-invalid @enderror city-dropdown" data-selected="{{ old('city', $user->address->city->id ?? 0) }}"
                                                        data-city-route="{{ route('merchant.data.countries.country-states.cities', ['__FIRST_REPLACE__', '__SECOND_REPLACE__']) }}" required>
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
                                    </div>
                                    <div class="tab-pane fade show" id="settings">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="password">{{ __('labels.new_password') }}</label>
                                                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
                                                    <p>* {{ __('messages.password_format') }}</p>
                                                    @error('password')
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
                                                    <label for="password_confirmation">{{ __('labels.new_password_confirmation') }}</label>
                                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror">
                                                    @error('password_confirmation')
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
                                                    <label for="logo" class="col-form-label">{{ __('labels.change_logo') }}</label>
                                                    <div class="row">
                                                        <div class="col-12 col-md-3">
                                                            <img src="{{ $user->logo->full_file_path ?? $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail d-block mx-auto">
                                                        </div>
                                                        <div class="col-12 col-md-9">
                                                            <div class="custom-file">
                                                                <input type="file" id="logo" name="logo" class="custom-file-input custom-img-input @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                                                <label class="custom-file-label" for="logo">Choose file</label>
                                                                <ul>{!! trans_choice('messages.upload_image_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG']) !!}</ul>
                                                                @error('logo')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent text-md-right text-center">
                                <button type="submit" class="btn btn-outline-primary btn-rounded-corner float-right">
                                    <i class="fas fa-paper-plane"></i>
                                    {{ __('labels.submit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if (!empty($user->userAdsQuotas))
            <div class="row">
                @forelse ($user->userAdsQuotas as $quota)
                <div class="col-12 col-md-4">
                    <div class="small-box bg-white shadow">
                        <div class="inner">
                            <h3>{{ $quota->quantity }}</h3>
                            <p>{{ $quota->product->name }}</p>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
            </div>
            @endif

            <div class="row">
                <div class="col-12">
                    @if ($user->active_subscription)
                    <div class="alert alert-info shadow">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <h5 class="alert-heading">
                                    {{ __('labels.current_plan') . ':' }}
                                </h5>
                                <h3 class="alert-heading">{{ $user->active_subscription->name }}</h3>
                            </div>
                            <div class="col-12 col-md-6">
                                @if (empty($user->active_subscription->terminated_at))
                                <p class="alert-heading text-md-right">
                                    <a href="#" role="button" onclick="event.preventDefault();
                                subscriptionTerminationAlert('{{ __('messages.confirm_question') }}', '{{ __('messages.delete_info') }}', '{{ route('merchant.subscriptions.terminate', ['subscription' => $user->active_subscription->id]) }}')">
                                        {{ __('labels.terminate_plan') }}
                                    </a>
                                </p>
                                @else
                                <p class="alert-heading text-md-right">
                                    <a href="{{ route('subscriptions.index') }}" role="button">
                                        {{ __('labels.change_plan') }}
                                    </a>
                                </p>
                                @endif
                            </div>
                        </div>

                        <hr>

                        <div class="form-group row">
                            <label for="subscribed_at" class="col-form-label col-sm-2">{{ trans_choice('labels.subscribed_at', 1) }}</label>
                            <div class="col-sm-10">
                                <span id="subscribed_at" class="form-control-plaintext text-white">{{ $user->active_subscription->subscription_date ?? '-' }}</span>
                            </div>
                        </div>

                        @if (!empty($user->active_subscription->terminated_at))
                        <div class="form-group row">
                            <label for="terminated_at" class="col-form-label col-sm-2">{{ trans_choice('labels.terminated_at', 1) }}</label>
                            <div class="col-sm-10">
                                <span id="terminated_at" class="form-control-plaintext text-white">{{ $user->active_subscription->terminated_date ?? '-' }}</span>
                            </div>
                        </div>
                        @else
                        <div class="form-group row">
                            <label for="next_billing_at" class="col-form-label col-sm-2">{{ trans_choice('labels.next_billing_at', 1) }}</label>
                            <div class="col-sm-10">
                                <span id="next_billing_at" class="form-control-plaintext text-white">{{ $user->active_subscription->next_billing_date ?? '-' }}</span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="renewed_at" class="col-form-label col-sm-2">{{ trans_choice('labels.renewed_at', 1) }}</label>
                            <div class="col-sm-10">
                                <span id="renewed_at" class="form-control-plaintext text-white">{{ $user->active_subscription_latest_log->renewed_date ?? '-' }}</span>
                            </div>
                        </div>
                        @endif

                        <div class="form-group row">
                            <label for="expired_at" class="col-form-label col-sm-2">{{ trans_choice('labels.expired_at', 1) }}</label>
                            <div class="col-sm-10">
                                <span id="expired_at" class="form-control-plaintext text-white">{{ $user->active_subscription_latest_log->expired_date ?? '-' }}</span>
                            </div>
                        </div>

                    </div>
                    @else
                    <div class="card card-body shadow">
                        <div class="row">
                            <div class="col-12 text-center">
                                <h4>{{ __('messages.no_subscription') }}</h4>

                                <a role="button" href="{{ route('merchant.subscriptions.index') }}" class="btn btn-outline-primary btn-rounded-corner my-3">
                                    {{ __('labels.sign_up_a_plan') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

</div>

@endsection