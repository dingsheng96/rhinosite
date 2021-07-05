@extends('layouts.master', ['title' => __('modules.submodules.cart')])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card p-3 mb-3">

                <div class="card-header bg-transparent">
                    <h3 class="card-title font-weight-bold">{{ __('labels.my_cart_summary') }}</h3>
                </div>

                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">{{ __('#') }}</th>
                                    <th style="width: 50%;">{{ trans_choice('labels.item', 1) }}</th>
                                    <th style="width: 15%;" class="text-center">{{ __('labels.quantity') }}</th>
                                    <th style="width: 15%;" class="text-center">{{ __('labels.unit_price') }}</th>
                                    <th style="width: 15%;" class="text-center">{{ __('labels.total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($cart))
                                @foreach ($cart->cartItems()->get() as $item)
                                <tr>
                                    <td>1</td>
                                    <td>Call of Duty</td>
                                    <td>455-981-221</td>
                                    <td>El snort testosterone trophy driving gloves handsome</td>
                                    <td>$64.50</td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5" class="text-center">{{ __('labels.empty_list', ['list' => strtolower(trans_choice('labels.item', 2))]) }}</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                @if(!empty($cart))
                <div class="row">

                    <div class="col-6">

                    </div>

                    <div class="col-6">

                        <p class="lead">{{ __('labels.total_items', ['quantity' => 0]) }}</p>

                        <div class="table-responsive">
                            <table class="table">
                                <tr>
                                    <th style="width:65%">{{ __('labels.sub_total') }}</th>
                                    <th style="width:5%">:</th>
                                    <td class="text-center">0.00</td>
                                </tr>
                                <tr>
                                    <th>{{ __('labels.discount') }}</th>
                                    <th>:</th>
                                    <td class="text-center">0.00</td>
                                </tr>
                                <tr>
                                    <th>{{ __('labels.grand_total') }}</th>
                                    <th>:</th>
                                    <td class="font-weight-bold text-center">0.00</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>

                <div class="row">
                    <div class="col-12">
                        <a role="button" class="btn btn-light bg-orange float-right">
                            <i class="far fa-credit-card"></i>
                            {{ __('labels.make_payment') }}
                        </a>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

@endsection