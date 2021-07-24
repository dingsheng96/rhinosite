$(function () {

    $('.filter-btn').on('click', function () {
        $('.filter-overlay').toggleClass('open');
    });

    $('.closebtn').on('click', function () {
        $('.filter-overlay').toggleClass('open');
    });

    $('body,html').on('mousedown', function (e) {

        let clicked = $(e.target);

        if (clicked.is('.filter-overlay') || clicked.parents().is('.filter-overlay')) {
            return;
        } else {
            $('.filter-overlay').removeClass('open');
        }
    });


    if ($('.btn-view-more').length > 0) {

        $('body').on('click', '.btn-view-more', function (e) {

            e.preventDefault();

            let current_text = $(this).text();
            let text_replace = $(this).data('text-replace');

            $(this).data('text-replace', current_text);
            $(this).text(text_replace);
            $(this).parents('ul').find('li.more').toggleClass('d-none');
        });
    }

    if($('.btn-reset-filter').length > 0) {

        $('body').on('click', '.btn-reset-filter', function (e) {

            let form = $(this).parents('form');

            form.find('input[type=radio]').prop('checked', false);
            form.submit();
        });
    }
});