@extends('layouts.master', [ 'title' => trans_choice('modules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row my-3">
        <div class="col-12">
            <h4 class="font-weight-bold">{{ __('labels.add_on_products') }}</h4>
            <h5>{{ __('messages.add_on_product') }}</h5>
        </div>
    </div>

    <div class="row my-3">
        <div class="col-12">
            <p class="h5">{{ __('labels.available_add_on') }} :</p>
        </div>
    </div>

    <form action="{{ route('carts.store') }}" method="post" enctype="multipart/form-data" role="form">
        @csrf

        <input type="hidden" name="from_page" value="1">
        <div class="row my-3">
            @forelse ($products as $index => $product)
            <div class="col-12 col-md-4">
                <div class="card card-body shadow border">
                    <h5 class="font-weight-bold">{{ $product->name }}</h5>
                    <p class="card-text text-muted">{!! $product->description !!}</p>

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="item_{{ $loop->iteration }}" class="col-form-label">{{ __('labels.variant') }}</label>
                                <select name="item[{{ $index }}][variant]" id="item_{{ $loop->iteration }}" class="form-control select2 @error('item.'.$index.'.variant') is-invalid @enderror">
                                    <option value="0" disabled selected>{{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.variant')) ]) }}</option>
                                    @forelse ($product->productAttributes as $attribute)
                                    <option value="{{ $attribute->id }}" {{ old('item.'.$index.'.variant') == $attribute->id ? 'selected' : null }}>{{ trans_choice('labels.item_unit', $attribute->quantity, ['value' => $attribute->quantity]) }} -
                                        {{ $attribute->prices->first()->selling_price_with_currency }}
                                    </option>
                                    @empty
                                    @endforelse
                                </select>
                                @error('item.'.$index.'.variant')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="quantity_{{ $loop->iteration }}" class="col-form-label">{{ __('labels.quantity') }}</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-outline-secondary btn-decrement" type="button">-</button>
                                    </div>
                                    <input type="number" name="item[{{ $index }}][quantity]" id="quantity_{{ $loop->iteration }}" value="{{ old('item.'.$index.'.quantity', 0) }}" min="0"
                                        class="form-control quantity-input text-center disable-spinbox bg-white @error('item.'.$index.'.quantity') is-invalid @enderror" readonly>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary btn-increment" type="button">+</button>
                                    </div>
                                    @error('item.'.$index.'.quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @empty
            <div class="col-12">
                <p class="text-center">{{ __('messages.no_records') }}</p>
            </div>
            @endforelse
        </div>

        <div class="row my-3">
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-outline-primary btn-rounded-corner">{{ __('labels.add_to_cart') }}</button>
            </div>
        </div>

    </form>

</div>

@endsection