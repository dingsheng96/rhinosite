@extends('admin.layouts.master', [ 'title' => trans_choice('modules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('labels.attribute', 1)]) }}</h3>
                </div>


                <div class="card-body">

                    <div class="form-group row">
                        <label for="name" class="col-form-label col-sm-3">{{ trans_choice('labels.product', 1) }}</label>
                        <div class="col-sm-9">
                            <span class="form-control-plaintext" id="name">{{ $product->name }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="sku" class="col-form-label col-sm-3">{{ __('labels.sku') }}</label>
                        <div class="col-sm-9">
                            <span name="sku" id="sku" class="form-control-plaintext">{{ $attribute->sku }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="stock_type" class="col-form-label col-sm-3">{{ __('labels.stock_type') }}</label>
                        <div class="col-sm-9">
                            <span name="stock_type" id="stock_type" class="form-control-plaintext">{{ Str::title($attribute->stock_type) }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="stock_quantity" class="col-form-label col-sm-3">{{ __('labels.stock_quantity') }}</label>
                        <div class="col-sm-9">
                            <span name="stock_quantity" id="stock_quantity" class="form-control-plaintext">{{ $attribute->stock_quantity }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="quantity" class="col-form-label col-sm-3">{{ __('labels.variation_quantity') }}</label>
                        <div class="col-sm-9">
                            <span name="quantity" id="quantity" class="form-control-plaintext">{{ $attribute->quantity }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="validity" class="col-form-label col-sm-3">{{ __('labels.validity') }}</label>
                        <div class="col-sm-9">
                            <span name="validity" id="validity" class="form-control-plaintext">{{ $attribute->validity . ' ' . $attribute->validity_type }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-form-label col-sm-3">{{ __('labels.status') }}</label>
                        <div class="col-sm-9">
                            <span name="status" id="status">{!! $attribute->status_label !!}</span>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <label for="unit_price" class="col-form-label col-sm-3">{{ __('labels.unit_price') }}</label>
                        <div class="col-sm-9">
                            <span name="unit_price" id="unit_price" class="form-control-plaintext">{{ $default_price->unit_price_with_currency }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="discount" class="col-form-label col-sm-3">{{ __('labels.discount') }}</label>
                        <div class="col-sm-9">
                            <span name="discount" id="discount" class="form-control-plaintext">{{ $default_price->discount_with_currency }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="discount_perc" class="col-form-label col-sm-3">{{ __('labels.discount') . ' (%)' }}</label>
                        <div class="col-sm-9">
                            <span name="discount_perc" id="discount_perc" class="form-control-plaintext">{{ $default_price->discount_with_percentage }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="selling_price" class="col-form-label col-sm-3">{{ __('labels.selling_price') }}</label>
                        <div class="col-sm-9">
                            <span name="selling_price" id="selling_price" class="form-control-plaintext">{{ $default_price->selling_price_with_currency }}</span>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('admin.products.edit', ['product' => $product->id]) }}" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection