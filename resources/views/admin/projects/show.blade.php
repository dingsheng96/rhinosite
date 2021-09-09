@extends('admin.layouts.master', ['title' => trans_choice('modules.project', 2)])

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-transparent">
                <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.project', 1)]) }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="title_en" class="col-form-label">{{ __('labels.project_title', ['lang' => __('labels.english')]) }} <span class="text-red">*</span></label>
                            <p class="form-control" id="title_en">{{ $project->english_title }}</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="title_cn" class="col-form-label">{{ __('labels.project_title', ['lang' => __('labels.chinese')]) }}</label>
                            <p id="title_cn" class="form-control">{{ $project->chinese_title }}</p>
                        </div>
                    </div>
                </div>

                @admin
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="merchant" class="col-form-label">{{ __('labels.merchant') }} <span class="text-red">*</span></label>
                            <select name="merchant" id="merchant" class="form-control select2 @error('merchant') is-invalid @enderror">
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

                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="status" class="col-form-label">{{ __('labels.status') }} <span class="text-red">*</span></label>
                            <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror">
                                @foreach ($statuses as $status => $text)
                                <option value="{{ $status }}" {{ old('status', $project->status) == $status ? 'selected' : null }}>{{ $text }}</option>
                                @endforeach
                            </select>
                            @error('status')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-group">
                            <label for="services" class="col-form-label">{{ __('labels.services') }} <span class="text-red">*</span></label>
                            <select name="services[]" id="services" class="form-control select2-multiple @error('services') is-invalid @enderror" multiple="multiple" data-placeholder="{{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.services'))]) }}">
                                @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ collect(old('services', $project->services->pluck('id')))->contains($service->id) ? 'selected' : null }}>{{ $service->name }}</option>
                                @endforeach
                            </select>
                            @error('services')
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
                            <textarea name="description" id="description" cols="100" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $project->description) }}</textarea>
                            @error('description')
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

@endsection