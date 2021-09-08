@extends('layouts.master', [ 'title' => trans_choice('modules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('labels.attribute', 1)]) }}</h3>
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
                                <p name="sku" id="sku" class="form-control">{{ $attribute->sku }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="stock_type" class="col-form-label">{{ __('labels.stock_type') }}</label>
                                <p name="stock_type" id="stock_type" class="form-control">{{ Str::title($attribute->stock_type) }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="quantity" class="col-form-label">{{ __('labels.quantity') }}</label>
                                <p name="quantity" id="quantity" class="form-control">{{ $attribute->quantity }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="validity_type" class="col-form-label">{{ __('labels.validity_type') }}</label>
                                <p name="validity_type" id="validity_type" class="form-control">{{ $attribute->validity_type }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="validity" class="col-form-label">{{ __('labels.validity_in_validity_type') }}</label>
                                <p name="validity" id="validity" class="form-control">{{ $attribute->validity }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="status" class="col-form-label">{{ __('labels.status') }}</label>
                                <p name="status" id="status">{!! $attribute->status_label !!}</p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="unit_price" class="col-form-label">{{ __('labels.unit_price') }}</label>
                                <p name="unit_price" id="unit_price" class="form-control">{{ $default_price->unit_price_with_currency }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="discount" class="col-form-label">{{ __('labels.discount') }}</label>
                                <p name="discount" id="discount" class="form-control">{{ $default_price->discount_with_currency }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="discount_perc" class="col-form-label">{{ __('labels.discount') . ' (%)' }}</label>
                                <p name="discount_perc" id="discount_perc" class="form-control">{{ $default_price->discount_with_percentage }}</p>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="selling_price" class="col-form-label">{{ __('labels.selling_price') }}</label>
                                <p name="selling_price" id="selling_price" class="form-control">{{ $default_price->selling_price_with_currency }}</p>
                            </div>
                        </div>
                    </div>

                    @if ($product->productCategory->enable_slot)
                    <hr>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="slot_type">{{ __('labels.slot_type') }}</label>
                                <p name="slot_type" id="slot_type" class="form-control">{{ Str::title($attribute->slot_type) }}</p>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="slot">{{ __('labels.total_listed_slots_per_slot_type') }}</label>
                                <p name="slot" id="slot" class="form-control">{{ $attribute->slot }}</p>
                            </div>
                        </div>
                    </div>

                    @endif
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('products.edit', ['product' => $product->id]) }}" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-times"></i>
                        {{ __('labels.cancel') }}
                    </a>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection