@extends('layouts.master', ['title' => trans_choice('modules.country', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-5">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.country', 1)]) }}</h3>
                </div>

                <form action="{{ route('countries.update', ['country' => $country->id]) }}" method="POST" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $country->name) }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="currency" class="col-form-label">{{ __('labels.currency') }} <span class="text-red">*</span></label>
                            <select name="currency" id="currency" class="form-control select2 @error('currency') is-invalid @enderror">
                                <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.currency'))]) }} ---</option>
                                @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}" {{ old('currency', $country->currency_id) == $currency->id ? 'selected' : null }}>{{ $currency->name_with_code }}</option>
                                @endforeach
                            </select>
                            @error('currency')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="dial" class="col-form-label">{{ __('labels.dial_code') }} <span class="text-red">*</span></label>
                            <input type="text" id="dial" name="dial" value="{{ old('dial', $country->formatted_dial_code) }}" class="form-control @error('dial') is-invalid @enderror" placeholder="Eg: 60, 61">
                            @error('dial')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <div class="icheck-primary">
                                <input type="checkbox" name="set_default" id="set_default" {{ old('set_default', $country->set_default) ? "checked" : null }}>
                                <label for="set_default">{{ __('labels.default') }}</label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('countries.index') }}" class="btn btn-light mx-2 btn-rounded-corner">
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

        <div class="col-12 col-md-7">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ trans_choice('labels.country_state', 2) }}</h3>
                    <div class="card-tools">
                        <a href="#countryStateModal" class="btn btn-outline-primary btn-rounded-corner" data-toggle="modal">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('modules.country_state', 1)]) }}
                        </a>
                    </div>
                </div>
                <div class="card-body table-responsive">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>

</div>

@include('country.country_state.create')

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush