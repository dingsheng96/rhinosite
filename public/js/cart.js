$(function () {

    let cart = $('#cart');

    if(cart.length > 0) {

        cart.on('click', '.btn-delete-cart-item', function (e) {

            e.preventDefault();

            let route   =   $(this).data('delete-route');
            let data    =   {
                '_method': 'delete',
                '_token': $('meta[name="csrf-token"]').attr('content')
            };

            updateCart(route, data, cart);
        });

        cart.on('click', '.btn-qty-decrement', function (e) {

            e.preventDefault();

            let route   =   $(this).parents('.input-group').data('qty-route');
            let data    =   {
                '_method': 'PUT',
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'action': 'decrement'
            };

            updateCart(route, data, cart);
        });

        cart.on('click', '.btn-qty-increment', function (e) {

            e.preventDefault();

            let route   =   $(this).parents('.input-group').data('qty-route');
            let data    =   {
                '_method': 'PUT',
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'action': 'increment'
            };

            updateCart(route, data, cart);
        });
    }

});

function updateCart(route, data, cart)
{
    $.ajax({
        url: route,
        type: "POST",
        data: data,
        success: (xhl) => {

            if(xhl.status) {

                let data = xhl.data;
                let items = data.items;
                let subtotal = data.sub_total;
                let currency = data.currency;
                let cart_list = cart.find('#cart-items-list');

                // remove all cart list item
                cart_list.find('.list-group-item')
                .not('#cart-item-list-template')
                .not('#cart-item-empty-list-template')
                .remove();

                if(items.length > 0) {
                    $.each(items, function(index, value) {

                        let template = cart.find('#cart-item-list-template').clone(true);

                        if (value.enable_quantity_input) {
                            template.find('#disable_quantity_input_template').remove();
                        } else {
                            template.find('#enable_quantity_input_template').remove();
                        }

                        template.removeAttr('id')
                        .removeClass('d-none')
                        .html(function (i, oldHtml) {
                            return oldHtml.replaceAll('__REPLACE_ITEM_NAME__', value.name)
                            .replaceAll('__REPLACE_ITEM_ID__', value.id)
                            .replaceAll('__REPLACE_ITEM_QUANTITY__', value.quantity)
                            .replaceAll('__REPLACE_ITEM_PRICE_WITH_CURRENCY__', value.currency + value.price);
                        }).appendTo('#cart-items-list');

                    });

                    cart.find('#cart-subtotal').text(currency + subtotal.toFixed(2));

                } else {

                    cart.find('#subtotal_div').remove();

                    cart.find('#cart-item-empty-list-template')
                    .clone(true)
                    .removeAttr('id')
                    .removeClass('d-none')
                    .appendTo('#cart-items-list');
                }
            }
        },
        error: (xhl) => {
            //
        }
    })
}