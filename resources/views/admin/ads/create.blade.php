@extends('admin.layouts.master', ['title' => __('modules.ads')])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">
                        {{ __('modules.create', ['module' => __('modules.ads')]) }}
                    </h3>
                </div>

                <form action="{{ route('admin.ads-boosters.store') }}" method="POST" role="form" enctype="multipart/form-data">
                    @csrf

                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="merchant" class="col-form-label">{{ trans_choice('labels.merchant', 1) }} <span class="text-red">*</span></label>
                                    <select name="merchant" id="merchant" class="form-control select2 @error('merchant') is-invalid @enderror project-ads-filter">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.merchant', 1))]) }} ---</option>
                                        @forelse ($merchants as $merchant)
                                        <option value="{{ $merchant->id }}" {{ old('merchant') == $merchant->id ? 'selected' : null }}>{{ $merchant->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('project')
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
                                    <label for="project" class="col-form-label">{{ trans_choice('labels.project', 1) }} <span class="text-red">*</span></label>
                                    <select name="project" id="project" class="form-control select2 @error('project') is-invalid @enderror project-dropdown" data-merchant-project-route="{{ route('data.merchants.projects', '__REPLACE__') }}">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.project', 1))]) }} ---</option>
                                        @forelse ($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project') == $project->id ? 'selected' : null }}>{{ $project->english_title }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                    @error('project')
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
                                    <label for="ads_type" class="col-form-label">{{ __('labels.ads_type') }} <span class="text-red">*</span></label>
                                    <select name="ads_type" id="ads_type" class="form-control select2 @error('ads_type') is-invalid @enderror ads-date-filter ads-dropdown" data-merchant-ads-route="{{ route('data.merchants.ads-quota', '__REPLACE__') }}">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.ads_type', 1))]) }} ---</option>
                                        @forelse ($ads_types as $ads)
                                        <option value="{{ $ads->product_id }}" {{ old('ads_type') == $ads->product_id ? 'selected' : null }}>{{ $ads->product->name }}</option>
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
                                    <label for="date_from" class="col-form-label">{{ __('labels.first_boosting_date') }} <span class="text-red">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="date_from" name="date_from" class="form-control ads-date-picker @error('date_from') is-invalid @enderror" placeholder="yyyy-mm-dd" data-ads-date-filter-route="{{ route('data.ads.unavailable-date', ['ads' => '__REPLACE__']) }}"
                                            value="{{ old('start_from') }}" {{ old('start_from') ? null : 'disabled' }}>
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

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('admin.ads-boosters.index') }}" class="btn btn-light mx-2 btn-rounded-corner">
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