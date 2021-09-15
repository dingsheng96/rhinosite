@extends('layouts.master', ['title' => __('modules.checkout')])

@section('content')

<div class="container" style="padding-top: 7rem; padding-bottom: 5rem;">

    <form action="{{ route('merchant.checkout.store') }}" method="post" enctype="multipart/form-data" role="form">
        @csrf

        <div class="row mt-3">
            <div class="col-12">

                <div class="card card-body p-4">

                    <h3 class="card-title">{{ __('labels.order_summary') }}</h3>

                    <div class="table-responsive my-3">
                        <table class="table" role="presentation">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 10%;">{{ __('#') }}</th>
                                    <th scope="col" style="width: 60%;">{{ trans_choice('labels.item', 2) }}</th>
                                    <th scope="col" style="width: 10%; text-align: center;">{{ __('labels.quantity') }}</th>
                                    <th scope="col" style="width: 20%; text-align: right;">{{ __('labels.price') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($carts as $item)
                                <tr>
                                    <th scope="row">{{ $loop->iteration }}</th>
                                    <td>
                                        <span class="font-weight-bold">{{ $item['name'] }}</span>
                                        <br>
                                        <span class="text-muted">{{ __('labels.variant').': '.$item['variant'] }}</span>
                                        <br>
                                        <span class="text-muted">{!! $item['description'] !!}</span>
                                    </td>
                                    <td class="text-center"><span class="form-control form-control-sm">{{ $item['quantity'] }}</span></td>
                                    <td class="text-right">{{ $item['currency'] .' '. $item['price'] }}</td>
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

                    <hr>

                    <h4 class="font-weight-bold text-success">
                        {{ __('labels.grand_total') }}
                        <span class="float-right">
                            {{ $cart_currency .' '. number_format($sub_total, 2, '.', '') }}
                        </span>
                    </h4>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                @if (count($carts) > 0)
                <button type="submit" class="btn btn-orange btn-lg mt-3 float-right ml-2" name="pay">
                    {{ strtoupper(__('labels.pay_now')) }}
                </button>
                <a href="{{ route('merchant.products.index') }}" class="btn btn-black btn-lg mt-3 float-right mr-2" name="cancel">
                    {{ strtoupper(__('labels.cancel')) }}
                </a>
                @endif
            </div>
        </div>
    </form>

</div>

@endsection