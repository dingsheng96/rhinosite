@extends('layouts.master', ['title' => __('modules.submodules.cart')])

@section('content')

<div class="container-fluid">

    <div class="row py-2">
        <div class="col-12">
            <div class="card">

                <div class="card-header bg-transparent">
                    <h3 class="card-title">{{ __('labels.my_cart_summary') }}</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 5%;">{{ __('#') }}</th>
                                        <th style="width: 30%;">{{ trans_choice('labels.item', 1) }}</th>
                                        <th style="width: 10%;" class="text-center">{{ __('labels.type') }}</th>
                                        <th style="width: 20%;" class="text-center">{{ __('labels.quantity') }}</th>
                                        <th style="width: 15%;" class="text-center">{{ __('labels.unit_price') }}</th>
                                        <th style="width: 15%;" class="text-center">{{ __('labels.total') }}</th>
                                        <th style="width: 5%;" class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!empty($cart))
                                    @foreach ($cart->cartItems()->get() as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->item_name }}</td>
                                        <td class="text-center">{{ Str::title($item->type) }}</td>
                                        <td class="text-center">

                                            @if ($item->type == \App\Models\CartItem::TYPE_PACKAGE)
                                            <div class="row">
                                                <div class="col-12 col-md-3"></div>
                                                <div class="col-12 col-md-6">
                                                    <input type="number" class="form-control form-control-sm text-center bg-white disable-spinbox" disabled value="{{ $item->quantity ?? 0 }}" step="1">
                                                </div>
                                                <div class="col-12 col-md-3"></div>
                                            </div>
                                            @else
                                            <div class="row">
                                                <div class="col-12 col-md-3 px-2">
                                                    <button class="btn btn-danger btn-sm btn-decrement text-center px-3" type="button">
                                                        <span class="font-weight-bold">-</span>
                                                    </button>
                                                </div>
                                                <div class="col-12 col-md-6 px-2">
                                                    <input type="number" class="form-control form-control-sm text-center bg-white disable-spinbox" disabled value="{{ $item->quantity ?? 0 }}" step="1">
                                                </div>
                                                <div class="col-12 col-md-3 px-2">
                                                    <button class="btn btn-success btn-sm btn-increment text-center px-3" type="button">
                                                        <span class="font-weight-bold">+</span>
                                                    </button>
                                                </div>
                                            </div>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->unit_price ?? 0.00 }}</td>
                                        <td class="text-center">{{ $item->total_price ?? 0.00 }}</td>
                                        <td class="text-center">
                                            <a role="button" href="#" class="btn btn-danger btn-sm"
                                                onclick="event.preventDefault(); deleteAlert('{{ __('messages.confirm_question') }}', '{{ __('messages.delete_info') }}', '{{ route('ecommerce.carts.cart-items.destroy', ['cart' => $cart->id, 'cart_item' => $item->id]) }}')">
                                                <i class=" fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="7" class="text-center">{{ __('messages.empty_list', ['list' => strtolower(trans_choice('labels.item', 2))]) }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if(!empty($cart))
                    <div class="row">
                        <div class="col-6"></div>
                        <div class="col-6">
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th style="width:65%">{{ __('labels.sub_total') }}</th>
                                        <th style="width:5%">:</th>
                                        <td class="text-center">{{ $cart->sub_total ?? 0.00 }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('labels.discount') }}</th>
                                        <th>:</th>
                                        <td class="text-center">{{ $cart->discount ?? 0.00 }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ __('labels.grand_total') }}</th>
                                        <th>:</th>
                                        <td class="font-weight-bold text-center">{{ $cart->grand_total ?? 0.00 }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>

                @if(!empty($cart))
                <div class="card-footer bg-transparent text-right">
                    <a role="button" class="btn btn-primary">
                        <i class="far fa-credit-card"></i>
                        {{ __('labels.make_payment') }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection