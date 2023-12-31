$(function () {

    // loader
    $('form').on('submit', function () {
        $('.loading').show();
    });

    $(document).ajaxStart(function () {
        $('.loading').show();
    }).ajaxStop(function () {
        $('.loading').hide();
    });

    // initialize select2
    $(".select2").select2({
        theme: "bootstrap4"
    });

    $(".select2-disabled").select2({
        theme: "bootstrap4",
        disabled: true
    });

    $(".select2-multiple").select2({
        theme: "bootstrap4",
        multiple: true,
        allowClear: true,
        placeholder: $(this).data("placeholder")
    });

    // initialize summernote
    $(".summernote").summernote({
        height: 300
    });

    $(".summernote-plain, .summernote-disabled").summernote({
        height: 300,
        toolbar: false
    });

    $('.summernote-basic').summernote({
        disableDragAndDrop: true,
        height: 300,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['insert', ['link']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']]
        ]
    });

    $(".summernote-disabled").summernote("disable");

    // disabled form submit button when loading submission
    $("form").not(".loading").on("submit", function () {
        $(this).find(":submit").attr("disabled", "disabled");
    });

    // select all checkbox within container
    $(".select-all-toggle").on('click',
        function () {
            if ($(this).is(':checked')) {
                $(".select-all-container").find("input:checkbox")
                    .each(function () {
                        $(this).prop("checked", true);
                    });
            } else {
                $(".select-all-container").find("input:checkbox")
                    .each(function () {
                        $(this).prop("checked", false);
                    });
            }
        }
    );

    // lower case all
    $(".lcall").on("input", function () {
        let input = $(this)
            .val()
            .toLowerCase();

        $(this).val(input);
    });

    // upper case all
    $(".ucall").on("input", function () {
        let input = $(this)
            .val()
            .toUpperCase();

        $(this).val(input);
    });

    // upper case first letter
    $(".ucfirst").on("input", function () {
        let input = $(this)
            .val()
            .replace(/\b[a-z]/g, function (letter) {
                return letter.toUpperCase();
            });

        $(this).val(input);
    });

    // image-reader and preview
    $(".custom-img-input").on("change", function (e) {
        let file = e.target.files[0];

        if (file) {
            let reader = new FileReader();
            reader.onload = function () {
                $(".custom-img-preview").attr("src", reader.result);
            };
            reader.readAsDataURL(file);
        }
    });

    $(".custom-file-input").on("change", function (e) {
        let fileName = e.target.files[0].name;
        $(this).next(".custom-file-label")
            .text(fileName);
    });

    // price
    $('.uprice-input').on('input', function () {

        unit_price = $(this).val();
        discount = $('.disc-input').val();

        $('.disc-perc-input').val(calcDiscountPercentage(unit_price, discount));
        $('.sale-price-input').val(calcSellingPrice(unit_price, discount));
    });

    $('.disc-input').on('input', function () {

        discount = $(this).val();
        unit_price = $('.uprice-input').val();

        $('.disc-perc-input').val(calcDiscountPercentage(unit_price, discount));
        $('.sale-price-input').val(calcSellingPrice(unit_price, discount));
    });

    if ($('.btn-decrement').length > 0) {
        $('.btn-decrement').on('click', function () {

            let input = $(this).parents('.input-group').find('.quantity-input');
            let value = parseInt(input.val());

            if (value <= 0) {
                input.val(0);
            } else {
                input.val(value - 1);
            }
        });
    }

    if ($('.btn-increment').length > 0) {
        $('.btn-increment').on('click', function () {

            let input = $(this).parents('.input-group').find('.quantity-input');
            let value = parseInt(input.val());

            input.val(value + 1);
        });
    }

    if ($('.product-category-dropdown').length > 0) {

        $('.product-category-dropdown').on('change', function () {

            let data = $(this).find('option:selected').data('toggle-slot');

            if (data == 1) {
                $('#slot_panel').removeClass('d-none');
            } else {
                $('#slot_panel').addClass('d-none');
            }
        });
    }
});
