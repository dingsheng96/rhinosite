$(function() {

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
        allowClear:true,
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

    $(".summernote-disabled").summernote("disable");

    // disabled form submit button when loading submission
    $("form").not(".loading").on("submit", function() {
        $(this).find(":submit").attr("disabled", "disabled");
    });

    // select all checkbox within container
    $(".select-all-toggle").on('click',
        function() {
            if($(this).is(':checked')) {
                $(".select-all-container").find("input:checkbox")
                .each(function() {
                    $(this).prop("checked", true);
                });
            } else {
                $(".select-all-container").find("input:checkbox")
                .each(function() {
                    $(this).prop("checked", false);
                });
            }
        }
    );

    // lower case all
    $(".lcall").on("input", function() {
        let input = $(this)
            .val()
            .toLowerCase();

        $(this).val(input);
    });

    // upper case all
    $(".ucall").on("input", function() {
        let input = $(this)
            .val()
            .toUpperCase();

        $(this).val(input);
    });

    // upper case first letter
    $(".ucfirst").on("input", function() {
        let input = $(this)
            .val()
            .replace(/\b[a-z]/g, function(letter) {
                return letter.toUpperCase();
            });

        $(this).val(input);
    });

    // image-reader and preview
    $(".custom-img-input").on("change", function(e) {
        let file = e.target.files[0];

        if (file) {
            let reader = new FileReader();
            reader.onload = function() {
                $(".custom-img-preview").attr("src", reader.result);
            };
            reader.readAsDataURL(file);
        }
    });

    $(".custom-file-input").on("change", function(e) {
        let fileName = e.target.files[0].name;
        $(this).next(".custom-file-label")
            .text(fileName);
    });

    $('.sluggable').on('input', function() {

        let input = $(this).val();

        let slug = input.toString().trim().toLowerCase()
            .replace(/\s+/g, "-")
            .replace(/[^\w\-]+/g, "")
            .replace(/\-\-+/g, "-")
            .replace(/^-+/, "")
            .replace(/-+$/, "");

        $('.sluggable-input').val(slug);
    });

    // price
    $('#create_unit_price').on('input', function () {

        unit_price      =   $(this).val();
        discount        =   $('#create_discount').val();

        $('#create_discount_percentage').text(calcDiscountPercentage(unit_price, discount));
        $('#create_selling_price').text(calcSellingPrice(unit_price, discount));
    });

    $('#create_discount').on('input', function () {

        discount    = $(this).val();
        unit_price  = $('#create_unit_price').val();

        $('#create_discount_percentage').text(calcDiscountPercentage(unit_price, discount));
        $('#create_selling_price').text(calcSellingPrice(unit_price, discount));
    });

    $('#update_unit_price').on('input', function () {

        unit_price      =   $(this).val();
        discount        =   $('#update_discount').val();

        $('#update_discount_percentage').text(calcDiscountPercentage(unit_price, discount));
        $('#update_selling_price').text(calcSellingPrice(unit_price, discount));
    });

    $('#update_discount').on('input', function () {

        discount    = $(this).val();
        unit_price  = $('#update_unit_price').val();

        $('#update_discount_percentage').text(calcDiscountPercentage(unit_price, discount));
        $('#update_selling_price').text(calcSellingPrice(unit_price, discount));
    });

    $('.uprice-input').on('input', function () {

        unit_price      =   $(this).val();
        discount        =   $('.disc-input').val();

        $('.disc-perc-input').val(calcDiscountPercentage(unit_price, discount));
        $('.sale-price-input').val(calcSellingPrice(unit_price, discount));
    });

    $('.disc-input').on('input', function () {

        discount    = $(this).val();
        unit_price  = $('.uprice-input').val();

        $('.disc-perc-input').val(calcDiscountPercentage(unit_price, discount));
        $('.sale-price-input').val(calcSellingPrice(unit_price, discount));
    });
});