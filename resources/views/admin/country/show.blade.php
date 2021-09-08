@extends('admin.layouts.master', ['title' => trans_choice('modules.country', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.country', 1)]) }}</h3>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="name" class="col-form-label col-sm-2">{{ __('labels.name') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="name">{{ $country->name ?? null }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="currency" class="col-form-label col-sm-2">{{ __('labels.currency') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="currency">{{ $country->currency->name_with_code ?? null }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="dial" class="col-form-label col-sm-2">{{ __('labels.dial_code') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="dial">{{ $country->formatted_dial_code ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="country-table" class="col-form-label">{{ trans_choice('labels.country_state', 2) }}</label>
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('admin.countries.index') }}" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush