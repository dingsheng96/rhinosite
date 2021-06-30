$(function () {

    let updateCurrencyModal = $('#updateCurrencyModal');

    if(updateCurrencyModal.length > 0) {
        updateCurrencyModal.on('show.bs.modal', function (event) {

            let obj     =   $(event.relatedTarget).data('object');
            let form    =   $(this).find('form');
            let action  =   form.attr('action');

            form.attr('action', action.toString().replace('__REPLACE__', obj.id));
            $(this).find('input#update_name').val(obj.name);
            $(this).find('input#update_code').val(obj.code);
        });
    }

    let updateCategoryModal = $('#updateCategoryModal');

    if(updateCategoryModal.length > 0) {
        updateCategoryModal.on('show.bs.modal', function (event) {

            let obj     =   $(event.relatedTarget).data('object');
            let form    =   $(this).find('form');
            let action  =   form.attr('action');

            form.attr('action', action.toString().replace('__REPLACE__', obj.id));
            $(this).find('input#update_name').val(obj.name);
            $(this).find('input#update_description').val(obj.description);
        });
    }


    let updatePriceModal = $('#updatePriceModal');

    if(updatePriceModal.length > 0) {
        updatePriceModal.on('show.bs.modal', function (event) {

            let obj     =   $(event.relatedTarget).data('object');
            let form    =   $(this).find('form');
            let action  =   form.attr('action');
            console.log(obj.currency_id);
            form.attr('action', action.toString().replace('__REPLACE__', obj.id));
            $(this).find('select#update_currency').val(obj.currency_id).trigger('change');
            $(this).find('input#update_unit_price').val(obj.unit_price);
            $(this).find('input#update_discount').val(obj.discount);
            $(this).find('p#update_discount_percentage').text(obj.discount_percentage);
            $(this).find('p#update_selling_price').text(obj.selling_price);
        });
    }

});