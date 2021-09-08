@extends('admin.layouts.master', ['title' => trans_choice('modules.currency', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.currency', 1)]) }}</h3>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="name" class="col-form-label col-sm-2">{{ __('labels.name') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="name">{{ $currency->name ?? null }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="code" class="col-form-label col-sm-2">{{ __('labels.code') }}</label>
                        <div class="col-sm-10">
                            <span class="form-control-plaintext" id="code">{{ $currency->code ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="tbl_rate" class="col-form-label col-sm-2">{{ __('labels.conversion_rate') }}</label>
                        <div class="col-sm-10">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped" id="tbl_rate">
                                    <thead>
                                        <tr>
                                            <th scope="col" style="width: 10%;">{{ __('#') }}</th>
                                            <th scope="col" style="width: 40%;">{{ __('labels.to_currency') }}</th>
                                            <th scope="col" style="width: 50%;">{{ __('labels.rate') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($currencies as $item)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $item->name_with_code }}</td>
                                            <td>
                                                {{ $currency->getConversionRate($item->id)->rate ?? null }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('admin.currencies.index') }}" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection