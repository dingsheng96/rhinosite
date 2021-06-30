@extends('layouts.master', ['parent_title' => __('modules.ecommerce'), 'title' => trans_choice('modules.submodules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12 col-md-5">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.submodules.product_attribute', 1)]) }}</h3>
                    <div class="card-tools">
                        <h4>{!! $attribute->status_label !!}</h4>
                    </div>
                </div>

                <div class="card-body">
                    <div class="form-group">
                        <label for="sku">{{ __('labels.sku') }}</label>
                        <p id="sku" class="form-control">{{ $attribute->sku }}</p>
                    </div>
                    <div class="form-group">
                        <label for="stock_type">{{ __('labels.stock_type') }}</label>
                        <p id="stock_type" class="form-control">{{ Str::title($attribute->stock_type) }}</p>
                    </div>
                    <div class="form-group">
                        <label for="quantity">{{ __('labels.quantity') }}</label>
                        <p id="quantity" class="form-control">{{ $attribute->quantity }}</p>
                    </div>
                    <div class="form-group">
                        <label for="validity">{{ __('labels.validity') . '(' . trans_choice('labels.day', 2) . ')' }}</label>
                        <p id="validity" class="form-control">{{ $attribute->validity ?? null }}</p>
                    </div>
                    <div class="form-group">
                        <label for="color">{{ __('labels.color') }}</label>
                        <input type="color" id="color" class="form-control form-control-color" value="{{ $attribute->color }}" disabled>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ url()->previous() }}" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-times"></i>
                        {{ __('labels.cancel') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-7">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('labels.price') }}</h3>
                    @can('product.create')
                    <div class="card-tools">
                        <a role="button" href="#createPriceModal" class="btn btn-outline-primary btn-rounded-corner" data-toggle="modal">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => __('labels.price')]) }}
                        </a>
                    </div>
                    @endcan
                </div>
                <div class="card-body">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@includeWhen(Auth::user()->can('product.create'), 'ecommerce.product.attribute.price.create')
@includeWhen(Auth::user()->can('product.update'), 'ecommerce.product.attribute.price.edit')

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush