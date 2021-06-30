@extends('layouts.master', ['parent_title' => __('modules.ecommerce'), 'title' => trans_choice('modules.submodules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12 col-md-5">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.submodules.product_attribute', 1)]) }}</h3>
                </div>

                <form action="{{ route('ecommerce.products.attributes.update', ['product' => $product->id, 'attribute' => $attribute->id]) }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf
                    @method('put')

                    <div class="card-body">
                        <div class="form-group">
                            <div class="icheck-primary">
                                <input type="checkbox" name="is_available" id="is_available" {{ old('is_available', $attribute->is_available) ? 'checked' : null }}>
                                <label for="is_available">{{ __('labels.available') }}</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sku">{{ __('labels.sku') }}</label>
                            <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku', $attribute->sku) }}">
                        </div>
                        <div class="form-group">
                            <label for="stock_type">{{ __('labels.stock_type') }}</label>
                            <select name="stock_type" id="stock_type" class="select2 form-control">
                                <option value="0" selected disabled>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.stock_type'))]) }} ---</option>
                                @foreach ($stock_types as $type => $text)
                                <option value="{{ $type }}" {{ old('stock_type', $attribute->stock_type) == $type ? 'selected' : null }}>{{ $text }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantity">{{ __('labels.quantity') }}</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $attribute->quantity) }}" min="0" step="1">
                        </div>
                        <div class="form-group">
                            <label for="validity">{{ __('labels.validity') . '(' . trans_choice('labels.day', 2) . ')' }}</label>
                            <input type="number" name="validity" id="validity" class="form-control" value="{{ old('validity', $attribute->validity) }}" min="0" step="1">
                        </div>
                        <div class="form-group">
                            <label for="color">{{ __('labels.color') }}</label>
                            <input type="color" name="color" id="color" class="form-control form-control-color" value="{{ old('color', $attribute->color) }}">
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('ecommerce.products.edit', ['product' => $product->id]) }}" class="btn btn-light mx-2 btn-rounded-corner">
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