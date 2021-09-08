@extends('layouts.master', ['title' => trans_choice('modules.currency', 2)])

@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">
                        {{ __('modules.create', ['module' => trans_choice('modules.currency', 1)]) }}
                    </h3>
                </div>

                <form action="{{ route('currencies.update', ['currency' => $currency->id]) }}" method="POST" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card-body">

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">{{ __('labels.name') }}</label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $currency->name) }}" class="form-control ucfirst @error('name') is-invalid @enderror">
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="code" class="col-form-label">{{ __('labels.code') }}</label>
                                    <input type="text" id="code" name="code" value="{{ old('code', $currency->code) }}" class="form-control ucall @error('code') is-invalid @enderror">
                                    @error('code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="tbl_rate" class="col-form-label">{{ __('labels.conversion_rate') }}</label>
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped" id="tbl_rate">
                                        <thead>
                                            <tr>
                                                <th scope="col" style="width: 10%;">{{ __('#') }}</th>
                                                <th scope="col" style="width: 30%;">{{ __('labels.to_currency') }}</th>
                                                <th scope="col" style="width: 35%;">{{ __('labels.rate') }}</th>
                                                <th scope="col" style="width: 25%;">{{ __('labels.updated_at') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($currencies as $item)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $item->name_with_code }}</td>
                                                <td>
                                                    @if ($item->id == $currency->id)
                                                    {{ $currency->getConversionRate($item->id)->rate ?? null }}
                                                    @else
                                                    <input type="number" name="rate[{{ $item->id }}]" value="{{ old('rate.'.$item->id, $currency->getConversionRate($item->id)->rate ?? null) }}" class="form-control @error('rate.' . $item->id) is-invalid @enderror" step="0.00001">
                                                    @endif
                                                    @error('rate.' . $item->id)
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </td>
                                                <td>{{ $currency->getConversionRate($item->id)->updated_at ?? null }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    {{ __('messages.no_records') }}
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

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