@extends('merchant.layouts.master', ['parent_title' => trans_choice('modules.project', 2), 'title' => __('modules.create', ['module' => trans_choice('modules.project', 1)])])

@section('content')

<div class="container-fluid">
    @csrf

    <div class="row">
        <div class="col-12">
            <div class="card shadow">

                <div class="card-header">
                    <h3 class="card-title">{!! __('messages.complete_fields') !!}</h3>
                </div>

                <form action="{{ route('merchant.projects.store') }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5 col-sm-3">
                                <div class="nav flex-column nav-tabs h-100" id="project-tabs" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="general-tab" data-toggle="pill" href="#general" role="tab" aria-controls="general" aria-selected="true">{{ __('labels.general') }}</a>
                                    <a class="nav-link" id="details-tab" data-toggle="pill" href="#details" role="tab" aria-controls="details" aria-selected="false">{{ __('labels.details') }}</a>
                                    <a class="nav-link" id="location-tab" data-toggle="pill" href="#location" role="tab" aria-controls="location" aria-selected="false">{{ __('labels.location') }}</a>
                                    <a class="nav-link" id="image-tab" data-toggle="pill" href="#image" role="tab" aria-controls="image" aria-selected="false">{{ trans_choice('labels.image', 2) }}</a>
                                    <a class="nav-link" id="ads-tab" data-toggle="pill" href="#ads" role="tab" aria-controls="ads" aria-selected="false">{{ __('labels.boost_ads') }}</a>
                                </div>
                            </div>
                            <div class="col-7 col-sm-9">
                                <div class="tab-content" id="project-tabs-tabContent">
                                    <div class="tab-pane text-left fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="title_en" class="col-form-label">{{ __('labels.project_title', ['lang' => __('labels.english')]) }} <span class="text-red">*</span></label>
                                                    <input type="text" name="title_en" id="title_en" class="form-control @error('title_en') is-invalid @enderror sluggable" value="{{ old('title_en') }}">
                                                    @error('title_en')
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
                                                    <label for="title_cn" class="col-form-label">{{ __('labels.project_title', ['lang' => __('labels.chinese')]) }}</label>
                                                    <input type="text" name="title_cn" id="title_cn" class="form-control @error('title_cn') is-invalid @enderror" value="{{ old('title_cn') }}">
                                                    @error('title_cn')
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
                                                    <label for="status" class="col-form-label">{{ __('labels.status') }} <span class="text-red">*</span></label>
                                                    <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror">
                                                        @foreach ($statuses as $status => $text)
                                                        <option value="{{ $status }}" {{ old('status', 'published') == $status ? 'selected' : null }}>{{ $text }}</option>
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

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="description" class="col-form-label">{{ __('labels.description') }} <span class="text-red">*</span></label>
                                                    <textarea name="description" id="description" cols="100" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                                    @error('description')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane text-left fade show" id="details" role="tabpanel" aria-labelledby="details-tab">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="unit_value" class="col-form-label">{{ __('labels.unit_value') }}</label>
                                                    <input type="number" name="unit_value" id="unit_value" class="form-control @error('unit_value') is-invalid @enderror" value="{{ old('unit_value', '0.00') }}" min="0.00" step="0.01">
                                                    @error('unit_value')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="unit" class="col-form-label">{{ __('labels.unit') }}</label>
                                                    <select name="unit" id="unit" class="form-control select2 @error('unit') is-invalid @enderror">
                                                        <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.unit'))]) }} ---</option>
                                                        @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}" {{ old('unit') == $unit->id ? 'selected' : null }}>{{ $unit->full_display }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('unit')
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
                                                    <label for="currency" class="col-form-label">{{ __('labels.currency') }}</label>
                                                    <select name="currency" class="form-control select2 @error('currency') is-invalid @enderror">
                                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder' , ['label' => strtolower(__('labels.currency'))]) }} ---</option>
                                                        @foreach ($currencies as $currency)
                                                        <option value="{{ $currency->id }}" {{ old('currency', $default_currency->id) == $currency->id ? 'selected' : null }}>{{ $currency->name_with_code }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('currency')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="unit_price" class="col-form-label">{{ __('labels.unit_price') }}</label>
                                                    <input type="number" name="unit_price" class="form-control" value="{{ old('unit_price', '0.00') }}" min="0.00" step="0.01">
                                                    @error('unit_price')
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
                                                    <label for="materials" class="col-form-label">{{ __('labels.material_used') }}</label>
                                                    <textarea name="materials" id="meterials" cols="100" rows="5" placeholder="({{ __('labels.optional') }})" class="form-control @error('materials') is-invalid @enderror">{{ old('materials') }}</textarea>
                                                    @error('materials')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="location" role="tabpanel" aria-labelledby="location-tab">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="address_1" class="col-form-label">{{ __('labels.address_1') }} <span class="text-red">*</span></label>
                                                    <input type="text" name="address_1" id="address_1" class="form-control @error('address_1') is-invalid @enderror" value="{{ old('address_1') }}">
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
                                                    <input type="text" name="address_2" id="address_2" class="form-control @error('address_2') is-invalid @enderror" value="{{ old('address_2') }}" placeholder="({{ __('labels.optional') }})">
                                                    @error('address_2')
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
                                                    <label for="postcode" class="col-form-label">{{ __('labels.postcode') }} <span class="text-red">*</span></label>
                                                    <input type="text" name="postcode" id="postcode" class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode') }}">
                                                    @error('postcode')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="country" class="col-form-label">{{ trans_choice('labels.country', 1) }} <span class="text-red">*</span></label>
                                                    <select name="country" id="country" class="form-control select2 @error('country') is-invalid @enderror country-state-filter">
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
                                                <div class="form-group">
                                                    <label for="country_state" class="col-form-label">{{ trans_choice('labels.country_state', 1) }} <span class="text-red">*</span></label>
                                                    <select name="country_state" id="country_state" class="form-control select2 @error('country_state') is-invalid @enderror country-state-dropdown city-filter" data-selected="{{ old('country_state') }}"
                                                        data-country-state-route="{{ route('merchant.data.countries.country-states', ['__REPLACE__']) }}">
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
                                                <div class="form-group">
                                                    <label for="city" class="col-form-label">{{ trans_choice('labels.city', 1) }} <span class="text-red">*</span></label>
                                                    <select name="city" id="city" class="form-control select2 @error('city') is-invalid @enderror city-dropdown" data-selected="{{ old('city') }}"
                                                        data-city-route="{{ route('merchant.data.countries.country-states.cities', ['__FIRST_REPLACE__', '__SECOND_REPLACE__']) }}">
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

                                    <div class="tab-pane fade" id="image" role="tabpanel" aria-labelledby="image-tab">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="thumbnail" class="col-form-label">{{ __('labels.upload_thumbnail') }} <span class="text-red">*</span></label>
                                                    <div class="row">
                                                        <div class="col-12 col-md-3">
                                                            <img src="{{ $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail d-block mx-auto">
                                                        </div>
                                                        <div class="col-12 col-md-9">
                                                            <input type="file" id="thumbnail" name="thumbnail" class="form-control-file custom-img-input @error('thumbnail') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                                            @error('thumbnail')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                            @enderror
                                                            <ul class="list-unstyled mt-3">{!! trans_choice('messages.upload_image_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG']) !!}</ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="files" class="col-form-label">{{ trans_choice('labels.upload_image', 2) }}</label>
                                                    <div class="dropzone" id="myDropzone" data-max-files="{{ $max_files }}" data-accepted-files=".jpg,.jpeg,.png">
                                                        <div class="dz-default dz-message">
                                                            <h1><i class="fas fa-cloud-upload-alt"></i></h1>
                                                            <h4>{{ __('messages.drag_and_drop') }}</h4>
                                                            <ul class="list-unstyled">{!! trans_choice('messages.upload_image_rules', 2, ['maxsize' => '10mb', 'extensions' => 'JPG,JPEG, PNG', 'maxfiles' => $max_files]) !!}</ul>
                                                        </div>
                                                    </div>
                                                    @error('files')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="ads" role="tabpanel" aria-labelledby="ads-tab">

                                        <p class="card-text">
                                            <span class="font-weight-bold">{{ __('messages.boosts_ads_preference_text') }}</span>
                                            <br>
                                            {{ __('messages.select_prefer_boosts_ads_days') }}
                                        </p>

                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="ads_type" class="col-form-label">{{ __('labels.ads_type') }}</label>
                                                    <select name="ads_type" id="ads_type" class="form-control select2 @error('ads_type') is-invalid @enderror ads-date-filter ads-dropdown" data-merchant-ads-route="{{ route('merchant.data.merchants.ads-quota', '__REPLACE__') }}">
                                                        <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.ads_type'))]) }} ---</option>
                                                        @forelse ($ads_boosters as $product)
                                                        <option value="{{ $product->id }}" {{ old('ads_type') == $product->id ? 'selected' : null }}>{{ $product->name }}</option>
                                                        @empty
                                                        @endforelse
                                                    </select>
                                                    @error('ads_type')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="date_from" class="col-form-label">{{ __('labels.first_boosting_date') }}</label>
                                                    <div class="input-group">
                                                        <input type="text" id="date_from" name="date_from" class="form-control ads-date-picker @error('date_from') is-invalid @enderror" placeholder="yyyy-mm-dd"
                                                            data-ads-date-filter-route="{{ route('merchant.data.ads.unavailable-date', ['ads' => '__REPLACE__']) }}" value="{{ old('start_from') }}" {{ old('start_from') ? null : 'disabled' }}>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text bg-white"><i class="far fa-calendar"></i></span>
                                                        </div>
                                                        @error('date_from')
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
                        <a role="button" href="{{ route('merchant.projects.index') }}" class="btn btn-light mx-2 btn-rounded-corner">
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

@endsection