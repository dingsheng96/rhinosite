@extends('layouts.master', ['title' => trans_choice('modules.project', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.project', 1)]) }}</h3>
                </div>

                <form action="{{ route('projects.update', ['project' => $project->id]) }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        <div class="row">
                            <div class="col-5 col-md-3">
                                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="vert-tabs-general-tab" data-toggle="pill" href="#vert-tabs-general" role="tab" aria-controls="vert-tabs-general" aria-selected="true">{{ __('labels.general') }}</a>
                                    <a class="nav-link" id="vert-tabs-cost-tab" data-toggle="pill" href="#vert-tabs-cost" role="tab" aria-controls="vert-tabs-cost" aria-selected="false">{{ __('labels.cost') }}</a>
                                    <a class="nav-link" id="vert-tabs-media-tab" data-toggle="pill" href="#vert-tabs-media" role="tab" aria-controls="vert-tabs-media" aria-selected="false">{{ __('labels.media') }}</a>
                                    <a class="nav-link" id="vert-tabs-location-tab" data-toggle="pill" href="#vert-tabs-location" role="tab" aria-controls="vert-tabs-location" aria-selected="false">{{ __('labels.location') }}</a>
                                    <a class="nav-link" id="vert-tabs-ads-tab" data-toggle="pill" href="#vert-tabs-ads" role="tab" aria-controls="vert-tabs-ads" aria-selected="false">{{ __('labels.boost_ads') }}</a>
                                </div>
                            </div>
                            <div class="col-7 col-md-9">
                                <div class="tab-content" id="vert-tabs-tabContent">

                                    <div class="tab-pane text-left fade show active" id="vert-tabs-general" role="tabpanel" aria-labelledby="vert-tabs-general-tab">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="title_en" class="col-form-label">{{ __('labels.project_title', ['lang' => __('labels.english')]) }} <span class="text-red">*</span></label>
                                                    <input type="text" name="title_en" id="title_en" class="form-control @error('title_en') is-invalid @enderror sluggable" value="{{ old('title_en', $project->english_title) }}" required>
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
                                                    <label for="title_cn" class="col-form-label">{{ __('labels.project_title', ['lang' => __('labels.chinese')]) }} <span class="text-red">*</span></label>
                                                    <input type="text" name="title_cn" id="title_cn" class="form-control @error('title_cn') is-invalid @enderror" value="{{ old('title_cn', $project->chinese_title) }}" required>
                                                    @error('title_cn')
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
                                                    <label for="slug" class="col-form-label">{{ __('labels.slug') }}</label>
                                                    <input type="text" name="slug" id="slug" class="form-control @error('slug') is-invalid @enderror sluggable-input" value="{{ old('slug', $project->slug) }}" readonly>
                                                    @error('slug')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="publish" class="col-form-label">{{ __('labels.status') }}</label>
                                                    <div class="icheck-primary">
                                                        <input type="checkbox" name="publish" id="publish" {{ old('publish', $project->published) ? 'checked' : null }}>
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

                                        @admin
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="merchant" class="col-form-label">{{ __('labels.merchant') }} <span class="text-red">*</span></label>
                                                    <select name="merchant" id="merchant" class="form-control select2 @error('merchant') is-invalid @enderror" required>
                                                        <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.merchant'))]) }} ---</option>
                                                        @foreach ($merchants as $merchant)
                                                        <option value="{{ $merchant->id }}" {{ old('merchant', $project->user_id) == $merchant->id ? 'selected' : null }}>{{ $merchant->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('merchant')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        @endadmin

                                        <div class="form-group">
                                            <label for="description" class="col-form-label">{{ __('labels.description') }} <span class="text-red">*</span></label>
                                            <textarea name="description" id="description" cols="100" rows="5" placeholder="{{ __('labels.text_placeholder', ['label' => strtolower(__('labels.description'))]) }}" required
                                                class="form-control @error('description') is-invalid @enderror">{{ old('description', $project->description) }}</textarea>
                                            @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="materials" class="col-form-label">{{ __('labels.material_used') }} <span class="text-red">*</span></label>
                                            <textarea name="materials" id="meterials" cols="100" rows="5" placeholder="{{ __('labels.text_placeholder', ['label' => strtolower(__('labels.material_used'))]) }}" required
                                                class="form-control @error('materials') is-invalid @enderror">{{ old('materials', $project->materials) }}</textarea>
                                            @error('materials')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="services" class="col-form-label">{{ __('labels.services') }} <span class="text-red">*</span></label>
                                            <textarea name="services" id="services" cols="100" rows="5" placeholder="{{ __('labels.text_placeholder', ['label' => strtolower(__('labels.services'))]) }}" class="form-control @error('services') is-invalid @enderror"
                                                required>{{ old('services', $project->services) }}</textarea>
                                            @error('services')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="vert-tabs-cost" role="tabpanel" aria-labelledby="vert-tabs-cost-tab">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="unit_value" class="col-form-label">{{ __('labels.unit_value') }} <span class="text-red">*</span></label>
                                                    <input type="number" name="unit_value" id="unit_value" class="form-control @error('unit_value') is-invalid @enderror" value="{{ old('unit_value', $project->unit_value) }}" min="0.00" step="0.01" required>
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
                                                    <select name="unit" id="unit" class="form-control select2 @error('unit') is-invalid @enderror" required>
                                                        <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.unit'))]) }} ---</option>
                                                        @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}" {{ old('unit', $project->unit_id) == $unit->id ? 'selected' : null }}>{{ $unit->full_display }}</option>
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
                                            <div class="col-12 table-responsive">
                                                <table class="table table-bordered" cellspacing="0" cellpadding="0" id="priceDynamicForm" data-object="{{ json_encode(old('price')) }}">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" style="width: 45%;">{{ __('labels.currency') }}</th>
                                                            <th scope="col" style="width: 45%;">{{ __('labels.unit_price') }}</th>
                                                            <th scope="col" style="width: 10%;">{{ __('labels.action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($prices as $price)
                                                        <tr>
                                                            <td>
                                                                {{ $price->currency->name }}
                                                            </td>
                                                            <td>
                                                                {{ $price->unit_price }}
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger" data-toggle="modal"
                                                                    onclick="event.preventDefault(); deleteAlert('{{ __('labels.delete_confirm_question') }}', '{{ __('labels.delete_info') }}', '{{ route('projects.price.destroy', ['project' => $project->id, 'price' => $price->id]) }}');">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        @endforelse

                                                        <tr id="price_clone_template" hidden="true" aria-hidden="true">
                                                            <td>
                                                                <select name="prices[__REPLACE__][currency]" class="form-control" required disabled>
                                                                    <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder' , ['label' => strtolower(__('labels.currency'))]) }} ---</option>
                                                                    @foreach ($currencies as $currency)
                                                                    <option value="{{ $currency->id }}">{{ $currency->name_with_code }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="number" name="prices[__REPLACE__][unit_price]" class="form-control" value="0.00" min="0.00" step="0.01" required disabled>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-danger btn-remove-row"><i class="fas fa-trash"></i></button>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3">
                                                                <button type="button" class="btn btn-primary btn-add-row"><i class="fas fa-plus"></i>{{ __('labels.add_more') }}</button>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="vert-tabs-media" role="tabpanel" aria-labelledby="vert-tabs-media-tab">

                                        <div class="form-group">
                                            <label for="thumbnail" class="col-form-label">{{ __('labels.upload_thumbnail') }} <span class="text-red">*</span></label>
                                            <div class="row">
                                                <div class="col-12 col-md-3">
                                                    <img src="{{ $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail d-block mx-auto">
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <div class="custom-file">
                                                        <input type="file" id="thumbnail" name="thumbnail" class="custom-file-input custom-img-input @error('thumbnail') is-invalid @enderror" required accept=".jpg,.jpeg,.png">
                                                        <label class="custom-file-label" for="thumbnail">Choose file</label>
                                                        <ul>{!! trans_choice('labels.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG', 'dimension' => '1024x1024']) !!}</ul>
                                                        @error('thumbnail')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if ($max_files > 0)
                                        <div class="form-group">
                                            <label for="files" class="col-form-label">{{ trans_choice('labels.upload_image', 2) }}</label>
                                            <div class="dropzone" id="myDropzone" data-max-files="{{ $max_files }}" data-accepted-files=".jpg,.jpeg,.png" data-action="update">
                                                <div class="dz-default dz-message">
                                                    <h1><i class="fas fa-cloud-upload-alt"></i></h1>
                                                    <h4>{{ __('labels.drag_and_drop') }}</h4>
                                                </div>
                                            </div>
                                            @error('files')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                            <ul>{!! trans_choice('labels.upload_file_rules', 2, ['maxsize' => '10mb', 'extensions' => 'JPG,JPEG, PNG', 'maxfiles' => $max_files, 'dimension' => '1024x1024']) !!}</ul>
                                        </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="tbl_image">{{ __('modules.view', ['module' => trans_choice('labels.image', 2)]) }}</label>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover" id="tbl_image">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 5%;">{{ __('#') }}</th>
                                                            <th style="width: 15%">{{ __('labels.type') }}</th>
                                                            <th style="width: 65%;">{{ __('labels.filename') }}</th>
                                                            <th style="width: 15%">{{ __('labels.action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>1</td>
                                                            <td>{{ $project->thumbnail->type }}</td>
                                                            <td>
                                                                <img src="{{ $project->thumbnail->full_file_path }}" alt="{{ $project->thumbnail->filename }}" class="table-img-preview">
                                                                <a href="{{ $project->thumbnail->full_file_path }}" target="_blank" class="ml-2">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                    {{ $project->thumbnail->filename }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a role="button" href="{{ $project->thumbnail->full_file_path }}" class="btn btn-success mr-2" download title="{{ __('labels.download') }}">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        @forelse ($media as $image)
                                                        <tr>
                                                            <td>{{ ($loop->iteration + 1) }}</td>
                                                            <td>{{ $image->type }}</td>
                                                            <td>
                                                                <img src="{{ $image->full_file_path }}" alt="{{ $image->filename }}" class="table-img-preview">
                                                                <a href="{{ $image->full_file_path }}" target="_blank" class="ml-2">
                                                                    <i class="fas fa-external-link-alt"></i>
                                                                    {{ $image->filename }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                <a role="button" href="{{ $image->full_file_path }}" class="btn btn-success mr-2" download title="{{ __('labels.download') }}">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                                <a role="button" href="#" class="btn btn-danger ml-2" title="{{ __('labels.delete') }}" data-toggle="modal"
                                                                    onclick="event.preventDefault(); deleteAlert('{{ __('labels.delete_confirm_question') }}', '{{ __('labels.delete_info') }}', '{{ route('projects.media.destroy', ['project' => $project->id, 'media' => $image->id]) }}')">
                                                                    <i class="fas fa-trash"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="3" class="text-center">{{ __('labels.no_records') }}</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="vert-tabs-location" role="tabpanel" aria-labelledby="vert-tabs-location-tab">
                                        <div class="form-group">
                                            <label for="address_1" class="col-form-label">{{ __('labels.address_1') }} <span class="text-red">*</span></label>
                                            <input type="text" name="address_1" id="address_1" class="form-control @error('address_1') is-invalid @enderror" value="{{ old('address_1', $project->address->address_1 ?? null) }}" required>
                                            @error('address_1')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="address_2" class="col-form-label">{{ __('labels.address_2') }} <span class="text-red">*</span></label>
                                            <input type="text" name="address_2" id="address_2" class="form-control @error('address_2') is-invalid @enderror" value="{{ old('address_2', $project->address->address_2 ?? null) }}" required>
                                            @error('address_2')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="form-group">
                                                    <label for="country" class="col-form-label">{{ trans_choice('labels.country', 1) }} <span class="text-red">*</span></label>
                                                    <select name="country" id="country" class="form-control select2 @error('country') is-invalid @enderror country-state-filter" required>
                                                        <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.country', 1))]) }} ---</option>
                                                        @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" {{ old('country', $project->address->city->country->id ?? null) == $country->id ? 'selected' : null }}>{{ $country->name }}</option>
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
                                                    <input type="text" name="postcode" id="postcode" class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode', $project->address->postcode) }}" required>
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
                                                    <select name="country_state" id="country_state" class="form-control select2 @error('country_state') is-invalid @enderror country-state-dropdown city-filter"
                                                        data-selected="{{ old('country_state', $project->address->city->countryState->id ?? null) }}" data-country-state-route="{{ route('data.countries.country-states', ['__REPLACE__']) }}" required>
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
                                                    <select name="city" id="city" class="form-control select2 @error('city') is-invalid @enderror city-dropdown" data-selected="{{ old('city', $project->address->city->id ?? null) }}"
                                                        data-city-route="{{ route('data.countries.country-states.cities', ['__FIRST_REPLACE__', '__SECOND_REPLACE__']) }}" required>
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

                                    <div class="tab-pane fade" id="vert-tabs-ads" role="tabpanel" aria-labelledby="vert-tabs-ads-tab">
                                        <div class="form-group">
                                            <label for="boost_ads_text">{{ __('labels.boosts_ads_preference_text') }}</label>
                                            <p id="boost_ads_text">{{ __('labels.select_prefer_boosts_ads_days') }}</p>
                                        </div>

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
                                                    <div class="input-group date-picker">
                                                        <input type="text" id="boost_ads_date" name="boost_ads_date" class="form-control @error('boosts_ads_date') is-invalid @enderror bg-white" readonly placeholder="dd/mm/yyyy"
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('projects.index') }}" class="btn btn-light mx-2 btn-rounded-corner">
                            <i class="fas fa-times"></i>
                            {{ __('labels.cancel') }}
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

    @include('components.alert')

</div>

@endsection