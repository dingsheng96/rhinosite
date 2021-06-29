@extends('layouts.master', ['parent_title' => trans_choice('modules.setting', 2), 'title' => trans_choice('modules.submodules.country_state', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12 col-md-5">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.submodules.country_state', 1)]) }}</h3>
                </div>

                <form action="{{ route('settings.countries.country-states.update', ['country' => $country->id, 'country_state' => $country_state->id]) }}" method="POST" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        <div class="form-group">
                            <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $country_state->name) }}" class="form-control ucfirst @error('name') is-invalid @enderror" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('settings.countries.edit', ['country' => $country->id]) }}" class="btn btn-light mx-2 btn-rounded-corner">
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
                    <h3 class="card-title">{{ trans_choice('labels.city', 2) }}</h3>
                    <div class="card-tools">
                        <a href="#cityModal" class="btn btn-outline-primary btn-rounded-corner" data-toggle="modal">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('modules.submodules.city', 1)]) }}
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

@include('settings.country.country_state.city.create')

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush