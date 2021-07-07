@extends('layouts.master', ['parent_title' => trans_choice('modules.project', 2), 'title' => __('modules.create', ['module' => trans_choice('modules.project', 1)])])

@section('content')

<div class="container-fluid">
    <form action="{{ route('projects.store') }}" method="post" enctype="multipart/form-data" role="form">
        @csrf

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title">{{ __('labels.general') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
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
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="title_cn" class="col-form-label">{{ __('labels.project_title', ['lang' => __('labels.chinese')]) }} <span class="text-red">*</span></label>
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
                            @admin
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="merchant" class="col-form-label">{{ __('labels.merchant') }} <span class="text-red">*</span></label>
                                    <select name="merchant" id="merchant" class="form-control select2 @error('merchant') is-invalid @enderror">
                                        <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.merchant'))]) }} ---</option>
                                        @foreach ($merchants as $merchant)
                                        <option value="{{ $merchant->id }}" {{ old('merchant') == $merchant->id ? 'selected' : null }}>{{ $merchant->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('merchant')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            @endadmin
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="publish" class="col-form-label">{{ __('labels.status') }}</label>
                                    <div class="icheck-primary">
                                        <input type="checkbox" name="publish" id="publish" {{ old('publish', 'on') ? 'checked' : null }}>
                                        <label for="publish">{{ __('labels.publish_now') }}</label>
                                    </div>
                                    @error('category')
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
                                    <label for="unit_value" class="col-form-label">{{ __('labels.unit_value') }} <span class="text-red">*</span></label>
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
                                    <label for="unit" class="col-form-label">{{ __('labels.unit') }} <span class="text-red">*</span></label>
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
                                    <label for="currency" class="col-form-label">{{ __('labels.currency') }} <span class="text-red">*</span></label>
                                    <select name="currency" class="form-control select2 @error('currency') is-invalid @enderror">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder' , ['label' => strtolower(__('labels.currency'))]) }} ---</option>
                                        @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ old('currency') == $currency->id ? 'selected' : null }}>{{ $currency->name_with_code }}</option>
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
                                    <label for="unit_price" class="col-form-label">{{ __('labels.unit_price') }} <span class="text-red">*</span></label>
                                    <input type="number" name="unit_price" class="form-control" value="{{ old('unit_price', '0.00') }}" min="0.00" step="0.01">
                                    @error('unit_price')
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title">{{ __('labels.details') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="col-form-label">{{ __('labels.description') }} <span class="text-red">*</span></label>
                                    <textarea name="description" id="description" cols="100" rows="5" placeholder="{{ __('labels.text_placeholder', ['label' => strtolower(__('labels.description'))]) }}"
                                        class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                                    @error('description')
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
                                    <label for="materials" class="col-form-label">{{ __('labels.material_used') }} <span class="text-red">*</span></label>
                                    <textarea name="materials" id="meterials" cols="100" rows="5" placeholder="{{ __('labels.text_placeholder', ['label' => strtolower(__('labels.material_used'))]) }}" class="form-control @error('materials') is-invalid @enderror">{{ old('materials') }}</textarea>
                                    @error('materials')
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
                                    <label for="services" class="col-form-label">{{ __('labels.services') }} <span class="text-red">*</span></label>
                                    <textarea name="services" id="services" cols="100" rows="5" placeholder="{{ __('labels.text_placeholder', ['label' => strtolower(__('labels.services'))]) }}" class="form-control @error('services') is-invalid @enderror">{{ old('services') }}</textarea>
                                    @error('services')
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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title">{{ __('labels.location') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
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
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="address_2" class="col-form-label">{{ __('labels.address_2') }} <span class="text-red">*</span></label>
                                    <input type="text" name="address_2" id="address_2" class="form-control @error('address_2') is-invalid @enderror" value="{{ old('address_2') }}">
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
                                        <option value="{{ $country->id }}" {{ old('country') == $country->id ? 'selected' : null }}>{{ $country->name }}</option>
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
                                    <input type="text" name="postcode" id="postcode" class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode') }}">
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
                                    <select name="country_state" id="country_state" class="form-control select2 @error('country_state') is-invalid @enderror country-state-dropdown city-filter" data-selected="{{ old('country_state') }}"
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
                                    <select name="city" id="city" class="form-control select2 @error('city') is-invalid @enderror city-dropdown" data-selected="{{ old('city') }}" data-city-route="{{ route('data.countries.country-states.cities', ['__FIRST_REPLACE__', '__SECOND_REPLACE__']) }}">
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
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title">
                            {{ trans_choice('labels.image', 2) }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="thumbnail" class="col-form-label">{{ __('labels.upload_thumbnail') }} <span class="text-red">*</span></label>
                                    <div class="row">
                                        <div class="col-12 col-md-3">
                                            <img src="{{ $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail d-block mx-auto">
                                        </div>
                                        <div class="col-12 col-md-9">
                                            <div class="custom-file">
                                                <input type="file" id="thumbnail" name="thumbnail" class="custom-file-input custom-img-input @error('thumbnail') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                                <label class="custom-file-label" for="thumbnail">Choose file</label>
                                                @error('thumbnail')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                            <ul>{!! trans_choice('messages.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG', 'dimension' => '1024x1024']) !!}</ul>
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
                                        </div>
                                    </div>
                                    @error('files')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <ul>{!! trans_choice('messages.upload_file_rules', 2, ['maxsize' => '10mb', 'extensions' => 'JPG,JPEG, PNG', 'maxfiles' => $max_files, 'dimension' => '1024x1024']) !!}</ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title">
                            {{ __('labels.boost_ads',) }}
                        </h3>
                    </div>
                    <div class="card-body">

                        <p class="cart-text">
                            <span class="font-weight-bold">{{ __('messages.boosts_ads_preference_text') }}</span>
                            <br>
                            {{ __('messages.select_prefer_boosts_ads_days') }}
                        </p>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="ads_type" class="col-form-label">{{ __('labels.ads_type') }}</label>
                                    <select name="ads_type" id="ads_type" class="form-control select2 @error('ads_type') is-invalid @enderror disabled-date-filter">
                                        <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.ads_type'))]) }} ---</option>
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
                                    <label for="boost_ads_date" class="col-form-label">{{ __('labels.boosts_ads_date') }}</label>
                                    <div class="input-group">
                                        <input type="text" id="boost_ads_date" name="boost_ads_date" class="form-control date-picker @error('boosts_ads_date') is-invalid @enderror bg-white" readonly placeholder="dd/mm/yyyy"
                                            data-disabled-date-route="{{ route('data.ads-boosters.available-date', ['__REPLACE__']) }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-white"><i class="far fa-calendar"></i></span>
                                        </div>
                                    </div>
                                    @error('boosts_ads_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-outline-primary btn-rounded-corner float-right">
                                    <i class="fas fa-paper-plane"></i>
                                    {{ __('labels.submit') }}
                                </button>
                                <a role="button" href="{{ route('projects.index') }}" class="btn btn-light mx-2 btn-rounded-corner float-right">
                                    <i class="fas fa-times"></i>
                                    {{ __('labels.cancel') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
    let datepicker = new Pikaday({
        field: $('.date-picker')[0],
        minDate: new Date(),
        format: 'DD/MM/YYYY'
    });
</script>
@endpush