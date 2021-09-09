@extends('admin.layouts.master', [ 'title' => trans_choice('modules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.edit', ['module' => trans_choice('modules.product', 1)]) }}</h3>
                </div>

                <form action="{{ route('admin.products.update', ['product' => $product->id]) }}" method="post" enctype="multipart/form-data" role="form">
                    @csrf
                    @method('put')

                    <div class="card-body">

                        <div class="form-group">
                            <label for="name" class="col-form-label">{{ __('labels.name') }} <span class="text-red">*</span></label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                            @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label for="category" class="col-form-label">{{ __('labels.category') }} <span class="text-red">*</span></label>
                                    <select name="category" id="category" class="form-control select2 @error('category') is-invalid @enderror product-category-dropdown">
                                        <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.category'))]) }} ---</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category', $product->product_category_id) == $category->id ? 'selected' : null }} data-toggle-slot="{{ $category->enable_slot }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category')
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
                                        @foreach ($statuses as $status => $text)
                                        <option value="{{ $status }}" {{ old('status', $product->status) == $status ? 'selected' : null }}>{{ $text }}</option>
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

                        <div class="form-group">
                            <label for="description" class="col-form-label">{{ __('labels.description') }}</label>
                            <textarea name="description" id="description" cols="100" rows="5" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div id="slot_panel" class="{{ !$product->productCategory->enable_slot ? 'd-none' : null }}">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="slot_type">{{ __('labels.slot_type') }}</label>
                                        <select name="slot_type" id="slot_type" class="form-control select2 @error('slot_type') is-invalid @enderror">
                                            <option value="0" disabled selected>--- {{ __('labels.dropdown_placeholder', ['label' => strtolower(__('labels.slot_type'))]) }} ---</option>
                                            @foreach ($slot_types as $type)
                                            <option value="{{ $type }}" {{ old('slot_type', $product->slot_type) == $type ? 'selected' : null }}>{{ Str::title($type) }}</option>
                                            @endforeach
                                        </select>
                                        @error('slot_type')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="slot">{{ __('labels.total_listed_slots_per_slot_type') }}</label>
                                        <input type="number" name="slot" id="slot" value="{{ old('slot', $product->total_slots) }}" class="form-control @error('slot') is-invalid @enderror">
                                        @error('slot')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-transparent text-md-right text-center">
                        <a role="button" href="{{ route('admin.products.index') }}" class="btn btn-light mx-2 btn-rounded-corner">
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

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">
                        {{ __('modules.view', ['module' => trans_choice('labels.attribute', 2)]) }}
                    </h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.products.attributes.create', ['product' => $product->id]) }}" role="button" class="btn btn-outline-primary btn-rounded-corner mx-2">
                            <i class="fas fa-plus"></i>
                            {{ __('modules.create', ['module' => trans_choice('labels.attribute', 1)]) }}
                        </a>
                    </div>
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