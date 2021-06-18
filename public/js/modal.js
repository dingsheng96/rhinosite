$(function () {

    let updateCurrencyModal = $('#updateCurrencyModal');

    if(updateCurrencyModal.length > 0) {
        updateCurrencyModal.on('show.bs.modal', function (event) {
            let link    =   $(event.relatedTarget);
            let obj     =   link.data('object');
            let route   =   link.data('route');

            console.log(obj)

            $(this).find('form').attr('action', route);
            $(this).find('input#update_name').val(obj.name);
            $(this).find('input#update_code').val(obj.code);
        });
    }

});