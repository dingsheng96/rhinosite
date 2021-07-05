$(function () {

    $('.btn-add-package').on('click', function (e) {

        e.preventDefault;

        let item = $(this).data('item');
        let form = $('#packageForm');
        console.log(form.find('[name="item[item_id]"]').length);
        form.find('[name="item[item_id]"]').val(item);
        form.serializeArray();
        form.submit();
    });

});