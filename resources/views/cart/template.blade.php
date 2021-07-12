<li class="list-group-item px-0 py-2 d-none" id="cart-item-list-template">

    <div class="row">
        <div class="col-10">
            <span class="font-weight-bold">__REPLACE_ITEM_NAME__</span>
        </div>
        <div class="col-2">
            <a href="#" role="button" class="float-right btn-delete-cart-item" data-delete-route="{{ route('carts.destroy', ['cart' => '__REPLACE_ITEM_ID__']) }}">
                <i class="fas fa-trash text-red"></i>
            </a>
        </div>
    </div>


    <div class="row mt-3" id="enable_quantity_input_template">
        <div class="col-6">
            <div class="input-group input-group-sm" data-qty-route="{{ route('carts.update', ['cart' => '__REPLACE_ITEM_ID__']) }}">
                <div class="input-group-prepend">
                    <button class="btn btn-outline-secondary btn-qty-decrement" type="button">-</button>
                </div>
                <input type="text" value="__REPLACE_ITEM_QUANTITY__" class="form-control form-control-sm text-center disable-spinbox bg-white" disabled>
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary btn-qty-increment" type="button">+</button>
                </div>
            </div>
        </div>
        <div class="col-6">
            <p class="text-muted">
                <span class="float-right">__REPLACE_ITEM_PRICE_WITH_CURRENCY__</span>
            </p>
        </div>
    </div>

    <p class="text-muted mb-0" id="disable_quantity_input_template">
        {{ __('labels.quantity') . ': __REPLACE_ITEM_QUANTITY__' }}
        <span class="float-right">
            __REPLACE_ITEM_PRICE_WITH_CURRENCY__
        </span>
    </p>

</li>

<li class="list-group-item px-0 py-2 d-none" id="cart-item-empty-list-template">
    <p class="text-center">{{ __('messages.empty_list', ['list' => strtolower(trans_choice('labels.item', 2))]) }}</p>
</li>