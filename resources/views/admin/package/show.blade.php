@extends('admin.layouts.master', [ 'title' => trans_choice('modules.package', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('modules.view', ['module' => trans_choice('modules.package', 1)]) }}</h3>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name" class="col-form-label">{{ __('labels.name') }}</label>
                                <p id="name" class="form-control">{{ $package->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="stock_type" class="col-form-label">{{ __('labels.stock_type') }}</label>
                                <p id="stock_type" class="form-control">{{ Str::title($package->stock_type) }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="quantity" class="col-form-label">{{ __('labels.quantity') }}</label>
                                <p id="quantity" class="form-control">{{ $package->quantity }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="status" class="col-form-label">{{ __('labels.status') }}</label>
                                <p id="status">{!! $package->status_label !!}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">{{ __('labels.description') }}</label>
                                <textarea id="description" class="form-control bg-white" rows="5" cols="100" disabled>{{ $package->description }}</textarea>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="unit_price" class="col-form-label">{{ __('labels.unit_price') }}</label>
                                <p id="unit_price" class="form-control">{{ $price->unit_price_with_currency }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="discount" class="col-form-label">{{ __('labels.discount') }}</label>
                                <p id="discount" class="form-control">{{ $price->discount_with_currency }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="discount_percentage" class="col-form-label">{{ __('labels.discount') . ' (%)' }}</label>
                                <p id="discount_percentage" class="form-control">{{ $price->discount_with_percentage }}</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="selling_price" class="col-form-label">{{ __('labels.selling_price') }}</label>
                                <p id="selling_price" class="form-control">{{ $price->selling_price_with_currency }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                <label for="purchase_limit" class="col-form-label">{{ __('labels.purchase_limit') }}</label>
                                <p id="purchase_limit" class="form-control">{{ $package->purchase_limit }}</p>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group table-responsive">
                                @include('package.items', ['items' => $items, 'no_action' => true])
                            </div>
                        </div>
                    </div>

                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a role="button" href="{{ route('packages.index') }}" class="btn btn-light btn-rounded-corner">
                        <i class="fas fa-times"></i>
                        {{ __('labels.cancel') }}
                    </a>
                </div>


            </div>
        </div>
    </div>
</div>

@endsection