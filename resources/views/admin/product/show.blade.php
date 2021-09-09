@extends('admin.layouts.master', [ 'title' => trans_choice('modules.product', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.product', 1)]) }}</h3>
                </div>

                <div class="card-body">

                    <div class="form-group row">
                        <label for="name" class="col-form-label col-sm-3">{{ __('labels.name') }}</label>
                        <div class="col-sm-9">
                            <span id="name" class="form-control-plaintext">{{ $product->name }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-form-label col-sm-3">{{ __('labels.status') }}</label>
                        <div class="col-sm-9">
                            <span id="status" class="form-control-plaintext">{!! $product->status_label !!}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="category" class="col-form-label col-sm-3">{{ __('labels.category') }}</label>
                        <div class="col-sm-9">
                            <span id="category" class="form-control-plaintext">{{ $product->productCategory->name }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="description" class="col-form-label col-sm-3">{{ __('labels.description') }}</label>
                        <div class="col-sm-9">
                            <span id="description" class="form-control-plaintext">{!! nl2br($product->description ?? '-') !!}</span>
                        </div>
                    </div>

                    @if (!empty($product->slot_type))
                    <div class="form-group row">
                        <label for="slot" class="col-form-label col-sm-3">{{ __('labels.total_listed_slots_per_slot_type') }}</label>
                        <div class="col-sm-9">
                            <span id="slot" class="form-control-plaintext">{{ $product->total_slots . ' slots per ' . $product->slot_type }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        {!! $dataTable->table() !!}
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('admin.products.index') }}" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-caret-left"></i>
                        {{ __('labels.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
{!! $dataTable->scripts() !!}
@endpush