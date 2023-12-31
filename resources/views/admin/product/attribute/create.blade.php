@extends('admin.layouts.master', [ 'title' => trans_choice('modules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.create', ['module' => trans_choice('labels.attribute', 1)]) }}</h3>
                </div>

                <form action="{{ route('admin.products.attributes.store', ['product' => $product->id]) }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf

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
                                    <label for="sku" class="col-form-label">{{ __('labels.sku') }} <span class="text-red">*</span></label>
                                    <input type="text" name="sku" id="sku" class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}">
                                    @error('sku')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="icheck-primary">
                                        <input type="checkbox" name="trial_mode" id="trial_mode" class="@error('trial_mode') is-invalid @enderror" {{ old('trial_mode') ? 'checked' : null }}>
                                        <label for="trial_mode">{{ __('labels.enable_trial') }}</label>
                                        @error('trial_mode')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="icheck-primary">
                                        <input type="checkbox" name="recurring" id="recurring" class="@error('recurring') is-invalid @enderror" {{ old('recurring') ? 'checked' : null }}>
                                        <label for="recurring">{{ __('labels.require_recurring_payment') }}</label>
                                        @error('recurring')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="icheck-primary">
                                        <input type="checkbox" name="published" id="published" class="@error('published') is-invalid @enderror" {{ old('published') ? 'checked' : null }}>
                                        <label for="published">{{ __('labels.on_listing') }}</label>
                                        @error('published')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="stock_type" class="col-form-label">{{ __('labels.stock_type') }} <span class="text-red">*</span></label>
                                    <select name="stock_type" id="stock_type" class="form-control select2 @error('stock_type') is-invalid @enderror">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.stock_type'))]) }} ---</option>
                                        @foreach ($stock_types as $type)
                                        <option value="{{ $type }}" {{ old('stock_type') == $type ? 'selected' : null }}>{{ Str::title($type) }}</option>
                                        @endforeach
                                    </select>
                                    @error('stock_type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="stock_quantity" class="col-form-label">{{ __('labels.stock_quantity') }}</label>
                                    <input type="number" name="stock_quantity" id="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', 0) }}" min="0" step="1">
                                    @error('stock_quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="quantity" class="col-form-label">{{ __('labels.variation_quantity') }} <span class="text-red">*</span></label>
                                    <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', 1) }}" min="1" step="1">
                                    @error('quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="status" class="col-form-label">{{ __('labels.status') }} <span class="text-red">*</span></label>
                                    <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.status'))]) }} ---</option>
                                        @foreach ($statuses as $status => $text)
                                        <option value="{{ $status }}" {{ old('status', 'active') == $status ? 'selected' : null }}>{{ Str::title($text) }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="validity_type" class="col-form-label">{{ __('labels.validity_type') }}</label>
                                    <select name="validity_type" id="validity_type" class="form-control select2 @error('validity_type') is-invalid @enderror">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.validity_type'))]) }} ---</option>
                                        @foreach ($validity_types as $type)
                                        <option value="{{ $type }}" {{ old('validity_type') == $type ? 'selected' : null }}>{{ Str::title($type) }}</option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="validity" class="col-form-label">{{ __('labels.validity_in_validity_type') }}</label>
                                    <input type="number" name="validity" id="validity" class="form-control @error('validity') is-invalid @enderror" value="{{ old('validity') }}" step="1">
                                    @error('validity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="currency" class="col-form-label">{{ __('labels.currency') }} <span class="text-red">*</span></label>
                                    <select name="currency" id="currency" class="form-control select2 @error('currency') is-invalid @enderror">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.currency'))]) }} ---</option>
                                        @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}" {{ old('currency') == $currency->id ? 'selected' : null }}>{{ $currency->name_with_code }}</option>
                                        @endforeach
                                    </select>
                                    @error('currency')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="unit_price" class="col-form-label">{{ __('labels.unit_price') }} <span class="text-red">*</span></label>
                                    <input type="number" name="unit_price" id="unit_price" class="form-control @error('unit_price') is-invalid @enderror uprice-input" value="{{ old('unit_price', '0.00') }}" min="0.00" step="0.01">
                                    @error('unit_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="discount" class="col-form-label">{{ __('labels.discount') }} <span class="text-red">*</span></label>
                                    <input type="number" name="discount" id="discount" class="form-control @error('discount') is-invalid @enderror disc-input" value="{{ old('discount', '0.00') }}" min="0.00" step="0.01">
                                    @error('discount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="discount" class="col-form-label">{{ __('labels.discount') . ' (%)' }}</label>
                                    <input type="number" name="discount" id="discount" class="form-control @error('discount') is-invalid @enderror disc-perc-input" disabled value="{{ old('discount', '0.00') }}">
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="selling_price" class="col-form-label">{{ __('labels.selling_price') }}</label>
                                    <input type="number" name="selling_price" id="selling_price" class="form-control @error('selling_price') is-invalid @enderror sale-price-input" disabled value="{{ old('selling_price', '0.00') }}">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('admin.products.edit', ['product' => $product->id]) }}" class="btn btn-light mx-2 btn-rounded-corner">
                            <i class="fas fa-caret-left"></i>
                            {{ __('labels.back') }}
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