@extends('layouts.master', [ 'title' => trans_choice('modules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12 col-md-5">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.product_attribute', 1)]) }}</h3>
                    <span class="card-tools">
                        <h4>{!! $attribute->status_label !!}</h4>
                    </span>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name" class="col-form-label">{{ trans_choice('labels.product', 1) }}</label>
                                <p class="form-control" id="name">{{ $product->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="sku" class="col-form-label">{{ __('labels.sku') }}</label>
                                <p id="sku" class="form-control">{{ $attribute->sku }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="stock_type" class="col-form-label">{{ __('labels.stock_type') }}</label>
                                <p id="stock_type" class="form-control">{{ Str::title($attribute->stock_type) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="quantity" class="col-form-label">{{ __('labels.quantity') }}</label>
                                <p id="quantity" class="form-control">{{ $attribute->quantity }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="validity" class="col-form-label">{{ __('labels.validity') . ' (' . trans_choice('labels.day', 2) . ')' }}</label>
                                <p id="validity" class="form-control">{{ $attribute->validity }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ url()->previous() }}" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-chevron-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-7">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.list', ['module' => __('labels.price')]) }}</h3>
                </div>
                <div class="card-body">
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