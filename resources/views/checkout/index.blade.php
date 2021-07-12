@extends('layouts.master', ['title' => __('modules.checkout')])

@section('content')

<div class="container-fluid">

    <form action="{{ route('checkout.store') }}" method="post" enctype="multipart/form-data" role="form">
        @csrf

        <div class="row">
            <div class="col-12 col-md-8">

                <div class="card p-3">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title">
                            {{ __('labels.order_review') }}
                        </h3>
                    </div>

                    <div class="table-responsive">
                        <table class="table" role="presentation">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10%;">{{ __('#') }}</th>
                                    <th scope="col" style="width: 60%;">{{ trans_choice('labels.item', 2) }}</th>
                                    <th scope="col" style="width: 10%; text-align: center;">{{ __('labels.quantity') }}</th>
                                    <th scope="col" style="width: 20%; text-align: center;">{{ __('labels.price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($carts as $item)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td><span class="font-weight-bold">{{ $item['name'] }}</span></td>
                                    <td class="text-center"><span class="form-control form-control-sm">{{ $item['quantity'] }}</span></td>
                                    <td class="text-center">{{ $item['currency'] . $item['price'] }}</td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="4">
                                        <p class="text-center">{{ __('messages.empty_list', ['list' => strtolower(trans_choice('labels.item', 2))]) }}</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- SUMMARY AND PAYMENT METHOD --}}
            <div class="col-12 col-md-4">
                <div class="card p-3">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title">{{ __('labels.summary') }}</h3>
                    </div>
                    <div class="card-footer bg-transparent">
                        <h4 class="font-weight-bold text-success">
                            {{ __('labels.grand_total') }}
                            <span class="float-right">
                                {{ $cart_currency . number_format($sub_total, 2, '.', '') }}
                            </span>
                        </h4>
                    </div>
                </div>

                <div class="card p-3">
                    <div class="card-header bg-transparent">
                        <h3 class="card-title">{{ __('labels.select_payment_method') }}</h3>
                    </div>
                    <div class="card-body">
                        @foreach ($payment_methods as $method)
                        <div class="form-group clearfix">
                            <div class="icheck-primary">
                                <input type="radio" name="payment_method" id="payment_method_{{ $loop->iteration }}" value="{{ $method->id }}" {{ old('payment_method', 1) ? 'checked' : null }}>
                                <label for="payment_method_{{ $loop->iteration }}">{{ $method->name }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                @if (count($carts) > 0)
                <button type="submit" class="btn btn-outline-primary btn-block btn-lg">
                    <i class="far fa-credit-card mr-2"></i>
                    {{ __('labels.pay_now') }}
                </button>
                @endif
            </div>

        </div>
    </form>

</div>

@endsection