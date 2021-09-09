@extends('admin.layouts.master', ['title' => trans_choice('modules.transaction', 2)])

@section('content')

<div class="container-fluid">

    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-transparent">
                    <h3 class="card-title">
                        {{ __('modules.view', ['module' => trans_choice('modules.transaction', 2)]) }}
                    </h3>
                </div>

                <div class="card-body">

                    <div class="form-group row">
                        <label for="transaction_no" class="col-form-label col-sm-3">{{ __('labels.transaction_no') }}</label>
                        <div class="col-sm-9">
                            <span class="form-control-plaintext" id="transaction_no">{{ $transaction->transaction_no ?? null }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="amount" class="col-form-label col-sm-3">{{ __('labels.amount') }}</label>
                        <div class="col-sm-9">
                            <span class="form-control-plaintext" id="amount">{{ $transaction->amount_with_currency_code }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="payment_method" class="col-form-label col-sm-3">{{ __('labels.payment_method') }}</label>
                        <div class="col-sm-9">
                            <span class="form-control-plaintext" id="payment_method">{{ $transaction->paymentMethod->name }}</span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-form-label col-sm-3">{{ __('labels.status') }}</label>
                        <div class="col-sm-9">
                            <span class="form-control-plaintext" id="status">{!! $transaction->status_label !!}</span>
                        </div>
                    </div>

                    @if ($transaction->sourceable)
                    <div class="form-group row">
                        <label for="order_no" class="col-form-label col-sm-3">{{ __('labels.order_no') }}</label>
                        <div class="col-sm-9">
                            <span class="form-control-plaintext" id="order_no">
                                <a href="{{ route('admin.orders.show', ['order'=> $transaction->sourceable->id ]) }}" target="_blank" rel="noopener noreferrer">
                                    <i class="fas fa-external-link-alt"></i>
                                    {{ $transaction->sourceable->order_no }}
                                </a>
                            </span>
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="details" class="col-form-label">{{ __('labels.transaction_details') }}</label>
                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-transparent text-md-right text-center">
                    <a href="{{ route('admin.transactions.index') }}" role="button" class="btn btn-light btn-rounded-corner">
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