@extends('layouts.master', ['title' => __('modules.ads')])

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

                <form action="{{ route('ads.store') }}" method="POST" role="form" enctype="multipart/form-data">
                    @csrf

                    <p class="text-muted">Coming soon...</p>
                    {{-- <div class="card-body">
                        <div class="form-group">
                            <label for="project" class="col-form-label">{{ trans_choice('labels.project', 1) }} <span class="text-red">*</span></label>
                    <select name="project" id="project" class="form-control select2 @error('project') is-invalid @enderror">
                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.project', 1))]) }} ---</option>
                        @foreach ($projects as $project)
                        <option value="{{ $project->id }}" {{ old('project') == $project->id ? 'selected' : null }}>{{ $project->english_title . ' (' .$project->chinese_title. ')' }}</option>
                        @endforeach
                    </select>
                    @error('project')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
            </div>
            <div class="form-group">
                <label for="ads_type" class="col-form-label">{{ __('labels.ads_type') }} <span class="text-red">*</span></label>
                <select name="ads_type" id="ads_type" class="form-control select2 @error('ads_type') is-invalid @enderror ads-slot-filter" data-filter-route="{{  }}">
                    <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(trans_choice('labels.ads_type', 1))]) }} ---</option>
                    @foreach ($ads_types as $ads)
                    <option value="{{ $ads->product_attribute_id }}" {{ old('ads_type') == $ads->product_attribute_id ? 'selected' : null }}>{{ $ads->product->name }}</option>
                    @endforeach
                </select>
                @error('ads_type')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <label for="boost_date">{{ __('labels.select') .' '. trans_choice('labels.boosted_at', 2) }} <span class="text-red">*</span></label>
                <div class="input-group">
                    <input type="text" id="boost_date" name="boost_date" class="form-control ads-date-picker @error('boost_date') is-invalid @enderror bg-white" readonly placeholder="dd/mm/yyyy">
                    <div class="input-group-append">
                        <span class="input-group-text bg-white"><i class="far fa-calendar"></i></span>
                    </div>
                </div>
                @error('boost_date')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

        </div> --}}

        <div class="card-footer bg-transparent text-md-right text-center">
            <a role="button" href="{{ route('currencies.index') }}" class="btn btn-light mx-2 btn-rounded-corner">
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