@extends('admin.layouts.master', [ 'title' => trans_choice('modules.package', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.package', 1)]) }}</h3>
                </div>

                <form action="{{ route('admin.packages.update', ['package' => $package->id]) }}" method="post" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card-body">

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $package->name) }}">
                                    @error('name')
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
                                        <input type="checkbox" name="recurring" id="recurring" class="@error('recurring') is-invalid @enderror" {{ old('recurring', $package->recurring) ? 'checked' : null }}>
                                        <label for="recurring">{{ __('labels.recurring') }}</label>
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
                                        <input type="checkbox" name="published" id="published" class="@error('published') is-invalid @enderror" {{ old('published', $package->published) ? 'checked' : null }}>
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
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="stock_type" class="col-form-label">{{ __('labels.stock_type') }} <span class="text-red">*</span></label>
                                    <select name="stock_type" id="stock_type" class="form-control select2 @error('stock_type') is-invalid @enderror">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.stock_type'))]) }} ---</option>
                                        @foreach ($stock_types as $type)
                                        <option value="{{ $type }}" {{ old('stock_type', $package->stock_type) == $type ? 'selected' : null }}>{{ Str::title($type) }}</option>
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
                                    <input type="number" name="stock_quantity" id="stock_quantity" class="form-control @error('stock_quantity') is-invalid @enderror" value="{{ old('stock_quantity', $package->stock_quantity) }}" min="0" step="1">
                                    @error('stock_quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="quantity" class="col-form-label">{{ __('labels.variation_quantity') }} <span class="text-red">*</span></label>
                                    <input type="number" name="quantity" id="quantity" class="form-control @error('quantity') is-invalid @enderror" value="{{ old('quantity', $package->quantity) }}" min="1" step="1">
                                    @error('quantity')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="status" class="col-form-label">{{ __('labels.status') }} <span class="text-red">*</span></label>
                                    <select name="status" id="status" class="form-control select2 @error('status') is-invalid @enderror">
                                        @foreach ($statuses as $status => $text)
                                        <option value="{{ $status }}" {{ old('status', $package->status) == $status ? 'selected' : null }}>{{ Str::title($text) }}</option>
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
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description">{{ __('labels.description') }}</label>
                                    <textarea name="description" id="description" cols="100" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $package->description) }}</textarea>
                                    @error('description')
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
                                        <option value="{{ $currency->id }}" {{ old('currency', $price->currency_id) == $currency->id ? 'selected' : null }}>{{ $currency->name_with_code }}</option>
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
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="unit_price" class="col-form-label">{{ __('labels.unit_price') }} <span class="text-red">*</span></label>
                                    <input type="number" name="unit_price" id="unit_price" class="form-control @error('unit_price') is-invalid @enderror uprice-input" value="{{ old('unit_price', $price->unit_price) }}" min="0.00" step="0.01">
                                    @error('unit_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="discount" class="col-form-label">{{ __('labels.discount') }} <span class="text-red">*</span></label>
                                    <input type="number" name="discount" id="discount" class="form-control @error('discount') is-invalid @enderror disc-input" value="{{ old('discount', $price->discount) }}" min="0.00" step="0.01">
                                    @error('discount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="discount_percentage" class="col-form-label">{{ __('labels.discount') . ' (%)' }}</label>
                                    <input type="number" id="discount_percentage" name="discount_percentage" class="form-control disc-perc-input" disabled value="{{ old('discount_percentage', $price->discount_percentage) }}" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="form-group">
                                    <label for="selling_price" class="col-form-label">{{ __('labels.selling_price') }}</label>
                                    <input type="number" id="selling_price" name="selling_price" class="form-control sale-price-input" disabled value="{{ old('selling_price', $price->selling_price) }}" min="0" step="0.01">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group table-responsive">
                                    @include('admin.package.items', ['items' => $items, 'no_action' => false])
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('admin.packages.index') }}" class="btn btn-light mx-2 btn-rounded-corner">
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