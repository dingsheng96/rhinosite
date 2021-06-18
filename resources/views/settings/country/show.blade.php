@extends('layouts.master', ['parent_title' => trans_choice('modules.setting', 2), 'title' => trans_choice('modules.submodules.country', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12 col-md-5">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.submodules.country', 1)]) }}</h3>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="col-form-label">{{ __('labels.name') }}</label>
                        <p class="form-control" id="name">{{ $country->name ?? null }}</p>
                    </div>
                    <div class="form-group">
                        <label for="currency" class="col-form-label">{{ __('labels.currency') }}</label>
                        <p class="form-control" id="currency">{{ $country->currency->name_with_code ?? null }}</p>
                    </div>
                    <div class="form-group">
                        <label for="dial" class="col-form-label">{{ __('labels.dial_code') }}</label>
                        <p class="form-control" id="dial">{{ $country->dial_code ?? null }}</p>
                    </div>
                </div>
                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('settings.countries.index') }}" class="btn btn-light">
                        <i class="fas fa-chevron-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-7">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ trans_choice('labels.country_state', 2) }}</h3>
                </div>
                <div class="card-body table-responsive">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush