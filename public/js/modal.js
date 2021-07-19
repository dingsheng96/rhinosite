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

    let updateServiceModal = $('#updateServiceModal');

    if(updateServiceModal.length > 0) {
        updateServiceModal.on('show.bs.modal', function (event) {

            let obj     =   $(event.relatedTarget).data('object');
            let form    =   $(this).find('form');
            let action  =   form.attr('action');

            form.attr('action', action.toString().replace('__REPLACE__', obj.id));
            $(this).find('input#update_name').val(obj.name);
            $(this).find('textarea#update_description').text(obj.description);
        });
    }

});