<aside class="control-sidebar control-sidebar-light" id="cart">

    <div class="p-3">
        <div>
            <h4>
                {{ __('modules.cart') }}
            </h4>
        </div>

        <hr class="mb-2">

        <div>
            <ul class="list-group list-group-flush" id="cart-items-list">

                @forelse ($carts as $item)

                <li class="list-group-item px-0 py-2">

                    <div class="row">
                        <div class="col-10">
                            <span class="font-weight-bold">{{ $item['name'] }}</span>
                        </div>
                        <div class="col-2">
                            <a href="#" role="button" class="float-right btn-delete-cart-item" data-delete-route="{{ route('carts.destroy', ['cart' => $item['id']]) }}">
                                <i class="fas fa-trash text-red"></i>
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <span>{{ __('labels.variant') }} :</span>
                            <br>
                            <span>{{ $item['variant'] ?? '-' }}</span>
                        </div>
                    </div>

                    @if ($item['enable_quantity_input'])
                    <div class="row mt-3">
                        <div class="col-6">
                            <div class="input-group input-group-sm" data-qty-route="{{ route('carts.update', ['cart' => $item['id']]) }}">
                                <div class="input-group-prepend">
                                    <button class="btn btn-outline-secondary btn-qty-decrement" type="button">-</button>
                                </div>
                                <input type="text" value="{{ $item['quantity'] }}" class="form-control form-control-sm text-center disable-spinbox bg-white" disabled>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary btn-qty-increment" type="button">+</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <p class="text-muted">
                                <span class="float-right">{{ $item['currency'] . $item['price'] }}</span>
                            </p>
                        </div>
                    </div>
                    @else
                    <p class="text-muted mb-0">
                        {{ __('labels.quantity') . ': ' .  $item['quantity'] }}
                        <span class="float-right">
                            {{ $item['currency'] . $item['price'] }}
                        </span>
                    </p>
                    @endif

                </li>

                @empty

                <li class="list-group-item px-0 py-2">
                    <p class="text-center">{{ __('messages.empty_list', ['list' => strtolower(trans_choice('labels.item', 2))]) }}</p>
                </li>
                @endforelse

                @include('cart.template')
            </ul>
        </div>

        @if (count($carts) > 0)
        <div id="subtotal_div">
            <hr class="mb-2">
            <p class="text-muted font-weight-bold">
                {{ __('labels.sub_total') }}
                <span class="float-right" id="cart-subtotal">{{ $cart_currency . number_format($sub_total ?? 0, 2, '.', '') }}</span>
            </p>

            <a href="{{ route('checkout.index') }}" role="button" class="btn btn-block btn-outline-primary btn-rounded-corner text-center">
                {{ strtoupper(__('labels.check_out')) }}
            </a>
        </div>
        @endif
    </div>
</aside>