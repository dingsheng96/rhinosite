$(function () {

    let updateCurrencyModal = $('#updateCurrencyModal');

    if(updateCurrencyModal.length > 0) {
        updateCurrencyModal.on('show.bs.modal', function (event) {
            let link    =   $(event.relatedTarget);
            let obj     =   link.data('object');
            let route   =   link.data('route');

            $(this).find('form').attr('action', route);
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

});