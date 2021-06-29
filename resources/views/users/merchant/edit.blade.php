@extends('layouts.master', ['parent_title' => trans_choice('modules.user', 2), 'title' => trans_choice('modules.submodules.merchant', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.submodules.merchant', 1)]) }}</h3>
                </div>

                <form action="{{ route('users.merchants.update', ['merchant' => $merchant->id]) }}" method="post" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card-body">

                        <div class="row">
                            <div class="col-5 col-md-3">
                                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                                    <a class="nav-link active" id="vert-tabs-general-tab" data-toggle="pill" href="#vert-tabs-general" role="tab" aria-controls="vert-tabs-general" aria-selected="true">{{ __('labels.general') }}</a>
                                    <a class="nav-link" id="vert-tabs-details-tab" data-toggle="pill" href="#vert-tabs-details" role="tab" aria-controls="vert-tabs-details" aria-selected="false">{{ __('labels.details') }}</a>
                                    <a class="nav-link" id="vert-tabs-location-tab" data-toggle="pill" href="#vert-tabs-location" role="tab" aria-controls="vert-tabs-location" aria-selected="false">{{ __('labels.location') }}</a>
                                </div>
                            </div>
                            <div class="col-7 col-md-9">
                                <div class="tab-content" id="vert-tabs-tabContent">
                                    <div class="tab-pane text-left fade show active" id="vert-tabs-general" role="tabpanel" aria-labelledby="vert-tabs-general-tab">
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                                                    <input type="text" name="name" id="name" value="{{ old('name', $merchant->name) ?? null }}" class="form-control ucfirst @error('name') is-invalid @enderror" required>
                                                    @error('name')
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
                                                        <option value="{{ $status }}" {{ old('status', $merchant->status) == $status }}>{{ $display }}</option>
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
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="phone" class="col-form-label">{{ __('labels.contact_no') }} <span class="text-red">*</span></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text bg-white">+</span>
                                                        </div>
                                                        <input type="text" name="phone" id="phone" value="{{ old('phone', $merchant->phone) ?? null }}" class="form-control @error('phone') is-invalid @enderror" required>
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
                                                    <input type="email" name="email" id="email" value="{{ old('email', $merchant->email) ?? null }}" class="form-control @error('email') is-invalid @enderror" required>
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
                                                    <input type="url" name="website" id="website" value="{{ old('website', $user_details->website ?? null) }}" class="form-control @error('website') is-invalid @enderror">
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
                                                    <input type="url" name="facebook" id="facebook" value="{{ old('facebook', $user_details->facebook ?? null) }}" class="form-control @error('facebook') is-invalid @enderror">
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
                                                    <label for="category" class="col-form-label">{{ __('labels.category') }} <span class="text-red">*</span></label>
                                                    <select name="category" id="category" class="form-control select2 @error('category') is-invalid @enderror" required>
                                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.category'))]) }} ---</option>
                                                        @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}" {{ old('category', $merchant->user_category->id ?? null) == $category->id ? 'selected' : null }}> {{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('category')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-group">
                                                    <label for="experience" class="col-form-label">{{ __('labels.years_of_experience') }} <span class="text-red">*</span></label>
                                                    <input type="number" name="experience" id="experience" value="{{ old('experience', $user_details->years_of_experience ?? 0) }}" class="form-control @error('experience') is-invalid @enderror" min="0" step="1" required>
                                                    @error('experience')
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
                                                            <img src="{{ $merchant->logo->full_file_path ?? $default_preview }}" alt="preview" class="custom-img-preview img-thumbnail d-block mx-auto">
                                                        </div>
                                                        <div class="col-12 col-md-9">
                                                            <div class="custom-file">
                                                                <input type="file" id="logo" name="logo" class="custom-file-input custom-img-input @error('logo') is-invalid @enderror" accept=".jpg,.jpeg,.png">
                                                                <label class="custom-file-label" for="logo">Choose file</label>
                                                                <ul>{!! trans_choice('labels.upload_file_rules', 1, ['maxsize' => '2mb', 'extensions' => 'JPG,JPEG, PNG']) !!}</ul>
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

                                    <div class="tab-pane text-left fade show" id="vert-tabs-details" role="tabpanel" aria-labelledby="vert-tabs-details-tab">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="pic_name" class="col-form-label">{{ __('labels.pic_name') }} <span class="text-red">*</span></label>
                                                    <input type="text" name="pic_name" id="pic_name" value="{{ old('pic_name', $user_details->pic_name ?? null) }}" class="form-control @error('pic_name') is-invalid @enderror" required>
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
                                                        <input type="text" name="pic_phone" id="pic_phone" value="{{ old('pic_phone', $user_details->pic_phone ?? null)  }}" class="form-control @error('pic_phone') is-invalid @enderror" required>
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
                                                    <input type="email" name="pic_email" id="pic_email" value="{{ old('pic_name', $user_details->pic_email ?? null) }}" class="form-control @error('pic_email') is-invalid @enderror" required>
                                                    @error('pic_email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12">
                                                <div class="form-group table-responsive">
                                                    <label for="media">{{ trans_choice('labels.document', 2) }}</label>
                                                    <table class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 10%;">{{ __('#') }}</th>
                                                                <th style="width: 70%;">{{ __('labels.filename') }}</th>
                                                                <th style="width: 20%">{{ __('labels.action') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($documents as $document)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>
                                                                    <a href="{{ $document->full_file_path }}" target="_blank">
                                                                        <i class="fas fa-external-link-alt"></i>
                                                                        {{ $document->filename }}
                                                                    </a>
                                                                </td>
                                                                <td>
                                                                    <a href="{{ $document->full_file_path }}" class="btn btn-link" download>
                                                                        <i class="fas fa-download"></i>
                                                                        {{ __('labels.download') }}
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
                                    </div>

                                    <div class="tab-pane text-left fade show" id="vert-tabs-location" role="tabpanel" aria-labelledby="vert-tabs-location-tab">
                                        <div class="form-group">
                                            <label for="address_1" class="col-form-label">{{ __('labels.address_1') }} <span class="text-red">*</span></label>
                                            <input type="text" name="address_1" id="address_1" class="form-control @error('address_1') is-invalid @enderror" value="{{ old('address_1', $merchant->address->address_1 ?? null) }}" required>
                                            @error('address_1')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="address_2" class="col-form-label">{{ __('labels.address_2') }} <span class="text-red">*</span></label>
                                            <input type="text" name="address_2" id="address_2" class="form-control @error('address_2') is-invalid @enderror" value="{{ old('address_2', $merchant->address->address_2 ?? null) }}" required>
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
                                                        <option value="{{ $country->id }}" {{ old('country', $merchant->address->city->country->id ?? 0) == $country->id ? 'selected' : null }}>{{ $country->name }}</option>
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
                                                    <input type="text" name="postcode" id="postcode" class="form-control @error('postcode') is-invalid @enderror" value="{{ old('postcode', $merchant->address->postcode ?? null) }}" required>
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
                                                        data-country-state-route="{{ route('data.countries.country-states', ['__REPLACE__']) }}" required>
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
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a href="{{ route('users.merchants.index') }}" role="button" class="btn btn-light mx-2 btn-rounded-corner">
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
</div>

@endsection