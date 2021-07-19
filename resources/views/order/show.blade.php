@extends('layouts.master', [ 'title' => trans_choice('modules.order', 2)])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="invoice p-3 mb-3">

                <div class="mb-5">
                    <div class="row">
                        <div class="col-12">
                            <h4>
                                {{ __('labels.purchase_order') }}
                                <span class="float-right">
                                    {!! $order->status_label !!}
                                </span>
                            </h4>
                        </div>
                    </div>

                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            <strong>{{ __('labels.order_no') . ': ' }}</strong> {{ $order->order_no }}<br>
                            <strong>{{ __('labels.order_date') . ': ' }}</strong> {{ $order->created_at->format('jS M Y') }} <br>
                        </div>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 5%">{{ __('#') }}</th>
                                    <th style="width: 40%">{{ trans_choice('labels.item', 1) }}</th>
                                    <th style="width: 15%" class="text-center">{{ __('labels.quantity') }}</th>
                                    <th style="width: 20%" class="text-center">{{ __('labels.unit_price') . ' ('. $order->currency->code. ')' }}</th>
                                    <th style="width: 20%" class="text-center">{{ __('labels.total') . ' ('. $order->currency->code. ')' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems()->get() as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->item }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">{{ $item->unit_price }}</td>
                                    <td class="text-center">{{ $item->total_price }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row mb-5">
                    <div class="col-6">
                        <p class="lead">{{ __('labels.paid_by') }} :</p>
                        {{ $order->paid_by->name ?? '-' }} <br>
                        {{ $order->transaction->transaction_no }}
                    </div>
                    <div class="col-6">
                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th style="width:55%">{{ __('labels.sub_total') }}</th>
                                    <th style="width:5%">:</th>
                                    <td class="text-center">{{ $order->sub_total_with_currency }}</td>
                                </tr>
                                <tr>
                                    <th>{{ __('labels.grand_total') }}</th>
                                    <th>:</th>
                                    <td class="text-center"><strong>{{ $order->grand_total_with_currency }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row no-print">
                    <div class="col-12">
                        <a href="{{ route('orders.index') }}" role="button" class="btn btn-light btn-rounded-corner float-right">
                            <i class="fas fa-chevron-left"></i>
                            {{ __('labels.back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection