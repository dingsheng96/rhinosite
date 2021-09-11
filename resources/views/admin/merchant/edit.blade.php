@extends('admin.layouts.master', ['title' => trans_choice('modules.merchant', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.merchant', 1)]) }}</h3>
                </div>

                <form action="{{ route('admin.merchants.update', ['merchant' => $merchant->id]) }}" method="post" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card-body">

                        <div class="row">
                            <div class="col-5 col-md-3">
                                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="vert-tabs-general-tab" data-toggle="pill" href="#vert-tabs-general" role="tab" aria-controls="vert-tabs-general" aria-selected="true">{{ __('labels.general') }}</a>
                                    <a class="nav-link" id="vert-tabs-details-tab" data-toggle="pill" href="#vert-tabs-details" role="tab" aria-controls="vert-tabs-details" aria-selected="false">{{ __('labels.details') }}</a>
                                    <a class="nav-link" id="vert-tabs-location-tab" data-toggle="pill" href="#vert-tabs-location" role="tab" aria-controls="vert-tabs-location" aria-selected="false">{{ __('labels.location') }}</a>
                                    <a class="nav-link" id="vert-tabs-subscription-tab" data-toggle="pill" href="#vert-tabs-subscription" role="tab" aria-controls="vert-tabs-subscription" aria-selected="false">{{ __('labels.subscription') }}</a>
                                </div>
                            </div>
                            <div class="col-7 col-md-9">
                                <div class="tab-content" id="vert-tabs-tabContent">
                                    <div class="tab-pane text-left fade show active" id="vert-tabs-general" role="tabpanel" aria-labelledby="vert-tabs-general-tab">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                                                    <input type="text" name="name" id="name" value="{{ old('name', $merchant->name) ?? null }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                                                    @error('name')
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
                                                        <option value="{{ $service->id }}" {{ old('service', $merchant->userDetail->service_id) == $service->id ? 'selected' : null }}>{{ $service->name }}</option>
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
                                                    <label for="phone" class="col-form-label">{{ __('labels.contact_no') }} <span class="text-red">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-white">+</span>
                                                        </div>
                                                        <input type="text" name="phone" id="phone" value="{{ old('phone', $merchant->phone) ?? null }}" class="form-control @error('phone') is-invalid @enderror">
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
                                                    <input type="email" name="email" id="email" value="{{ old('email', $merchant->email) ?? null }}" class="form-control @error('email') is-invalid @enderror">
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
                                                    <input type="text" name="reg_no" id="reg_no" value="{{ old('reg_no', $merchant->userDetail->reg_no ?? null) }}" class="form-control @error('reg_no') is-invalid @enderror">
                                                    @error('reg_no')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="business_since" class="col-form-label">{{ __('labels.business_since') }} <span class="text-red">*</span></label>
                                                    <div class="input-group">
                                                        <input type="text" name="business_since" id="business_since" value="{{ old('business_since', $merchant->userDetail->business_since ?? null) }}" class="form-control date-picker @error('business_since') is-invalid @enderror bg-white" readonly>
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
                                        </div>

                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="website" class="col-form-label">{{ __('labels.website') }}</label>
                                                    <input type="url" name="website" id="website" value="{{ old('website', $merchant->userDetail->website ?? null) }}" class="form-control @error('website') is-invalid @enderror">
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
                                                    <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $merchant->userDetail->facebook ?? null) }}" class="form-control @error('facebook') is-invalid @enderror">
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
                                                        <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $merchant->userDetail->whatsapp) }}" class="form-control @error('whatsapp') is-invalid @enderror">
                                                    </div>
                                                    @error('whatsapp')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="status" class="col-form-label">{{ __('labels.status') }}</label>
                                                    <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror">
                                                        @foreach ($statuses as $status => $display)
                                                        <option value="{{ $status }}" {{ old('status', $merchant->status) == $status ? 'selected' : null }}>{{ $display }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('status')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="password" class="col-form-label">{{ __('labels.new_password') }}</label>
                                                    <input type="password" name="password" id="password" value="{{ old('password')  }}" class="form-control @error('password') is-invalid @enderror">
                                                    <small>{!! __('messages.password_format') !!}</small>
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
                                                    <label for="password_confirmation" class="col-form-label">{{ __('labels.new_password_confirmation') }}</label>
                                                    <input type="password" name="password_confirmation" id="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control @error('password_confirmation') is-invalid @enderror">
                                                    @error('password_confirmation')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="tab-pane text-left fade show" id="vert-tabs-details" role="tabpanel" aria-labelledby="vert-tabs-details-tab">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="pic_name" class="col-form-label">{{ __('labels.pic_name') }} <span class="text-red">*</span></label>
                                                    <input type="text" name="pic_name" id="pic_name" value="{{ old('pic_name', $merchant->userDetail->pic_name ?? null) }}" class="form-control @error('pic_name') is-invalid @enderror">
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
                                                        <input type="text" name="pic_phone" id="pic_phone" value="{{ old('pic_phone', $merchant->userDetail->pic_phone ?? null)  }}" class="form-control @error('pic_phone') is-invalid @enderror">
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
                                                    <input type="email" name="pic_email" id="pic_email" value="{{ old('pic_email', $merchant->userDetail->pic_email ?? null) }}" class="form-control @error('pic_email') is-invalid @enderror">
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
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="logo" class="col-form-label">{{ __('labels.change_logo') }}</label>
                                                    <input type="file" id="logo" name="logo" class="form-control-file custom-img-input @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                                    @error('logo')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                    <ul class="pl-3 my-3">{!! trans_choice('messages.upload_image_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG']) !!}</ul>
                                                    <img src="{{ $merchant->logo->full_file_path ?? $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail d-block mx-auto">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="file" class="col-form-label">{{ __('labels.change_ssm_cert') }}</label>
                                                    <input type="file" name="ssm_cert" id="ssm_cert" value="{{ old('ssm_cert') }}" class="form-control-file @error('ssm_cert') is-invalid @enderror" accept="application/pdf">
                                                    @error('ssm_cert')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                    <ul class="pl-3 mt-3">{!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'PDF']) !!}</ul>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="form-group table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 10%;">{{ __('#') }}</th>
                                                        <th style="width: 15%;">{{ __('labels.type') }}</th>
                                                        <th style="width: 55%;">{{ __('labels.filename') }}</th>
                                                        <th style="width: 20%">{{ __('labels.action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($merchant->media as $document)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ strtoupper($document->type) }}</td>
                                                        <td>
                                                            <a href="{{ $document->full_file_path }}" target="_blank">
                                                                <i class="fas fa-external-link-alt"></i>
                                                                {{ $document->original_filename }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            @include('components.action', [
                                                            'download' => [
                                                            'route' => $document->full_file_path,
                                                            'attribute' => 'download'
                                                            ]
                                                            ])
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">{{ __('messages.no_records') }}</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div class="tab-pane text-left fade show" id="vert-tabs-location" role="tabpanel" aria-labelledby="vert-tabs-location-tab">

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="address_1" class="col-form-label">{{ __('labels.address_1') }} <span class="text-red">*</span></label>
                                                    <input type="text" name="address_1" id="address_1" class="form-control @error('address_1') is-invalid @enderror" value="{{ old('address_1', $merchant->address->address_1 ?? null) }}">
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
                                                    <input type="text" name="address_2" id="address_2" class="form-control @error('address_2') is-invalid @enderror" value="{{ old('address_2', $merchant->address->address_2 ?? null) }}">
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
                                                    <select name="country" id="country" class="form-control select2 @error('country') is-invalid @enderror country-state-filter">
                                                        <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.country', 1))]) }} ---</option>
                                                        @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" {{ old('country', $merchant->address->city->country->id ?? 0) == $country->id || $country->set_default ? 'selected' : null }}>{{ $country->name }}</option>
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
                                                    <input type="text" name="postcode" id="postcode" class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode', $merchant->address->postcode ?? null) }}">
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
                                                    <select name="country_state" id="country_state" class="form-control select2 @error('country_state') is-invalid @enderror country-state-dropdown city-filter" data-selected="{{ old('country_state', $merchant->address->city->countryState->id ?? 0) }}"
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
                                                    <select name="city" id="city" class="form-control select2 @error('city') is-invalid @enderror city-dropdown" data-selected="{{ old('city', $merchant->address->city->id ?? 0) }}"
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
                                    </div>

                                    <div class="tab-pane text-left fade show" id="vert-tabs-subscription" role="tabpanel" aria-labelledby="vert-tabs-subscription-tab">

                                        @if (!empty($subscription))
                                        <div class="form-group row">
                                            <label for="current_plan" class="col-form-label col-sm-3">{{ __('labels.current_plan') }}</label>
                                            <div class="col-sm-9">
                                                <span id="current_plan" class="form-control-plaintext">{{ $subscription->subscribable->name ?? '-' }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="subscribed_at" class="col-form-label col-sm-3">{{ trans_choice('labels.subscribed_at', 1) }}</label>
                                            <div class="col-sm-9">
                                                <span id="subscribed_at" class="form-control-plaintext">{{ $subscription->activated_at ?? '-' }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="subscribed_at" class="col-form-label col-sm-3">{{ trans_choice('labels.next_billing_at', 1) }}</label>
                                            <div class="col-sm-9">
                                                <span id="subscribed_at" class="form-control-plaintext">{{ $subscription->next_billing_at ?? '-' }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="terminated_at" class="col-form-label col-sm-3">{{ trans_choice('labels.terminated_at', 1) }}</label>
                                            <div class="col-sm-9">
                                                @if ($subscription->terminated_at)
                                                <span id="terminated_at" class="form-control-plaintext">{{ $subscription->terminated_at }}</span>
                                                @else
                                                <span id="terminated_at" class="form-control-plaintext">
                                                    <a href="#" onclick="event.preventDefault(); document.getElementById('subscriptionTerminateForm').submit()">{{ __('labels.click_here_to_terminate') }}</a>
                                                </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="renewed_at" class="col-form-label col-sm-3">{{ trans_choice('labels.renewed_at', 1) }}</label>
                                            <div class="col-sm-9">
                                                <span id="renewed_at" class="form-control-plaintext">{{ $subscription_log->renewed_at ?? '-' }}</span>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="subscribed_at" class="col-form-label col-sm-3">{{ trans_choice('labels.expired_at', 1) }}</label>
                                            <div class="col-sm-9">
                                                <span id="subscribed_at" class="form-control-plaintext">{{ $subscription_log->expired_at ?? '-' }}</span>
                                            </div>
                                        </div>

                                        <hr>

                                        @endif

                                        <div class="form-group">
                                            <label for="subscription_history" class="col-form-label">{{ __('labels.subscription_history') }}</label>
                                            <div class="col-12">
                                                <div class="table-responsive">
                                                    {!! $dataTable->table() !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a href="{{ route('admin.merchants.index') }}" role="button" class="btn btn-light mx-2 btn-rounded-corner">
                            <i class="fas fa-caret-left"></i>
                            {{ __('labels.back') }}
                        </a>
                        <button type="submit" class="btn btn-outline-primary btn-rounded-corner">
                            <i class="fas fa-paper-plane"></i>
                            {{ __('labels.submit') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>


<form action="{{ route('admin.subscriptions.terminate', ['subscription' => $subscription->id]) }}" method="post" id="subscriptionTerminateForm">
    @csrf
</form>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}

@endpush