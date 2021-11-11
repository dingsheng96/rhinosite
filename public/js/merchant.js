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

    if ($('.btn-reset-filter').length > 0) {

        $('body').on('click', '.btn-reset-filter', function (e) {

            let form = $(this).parents('form');

            form.find('input[type=radio]').prop('checked', false);
            form.submit();
        });
    }

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

    $('.btn-custom-wishlist').on('click', function () {

        let route = $(this).data('wishlist');
        let target = $(this).data('project');
        let btn_text = $(this).text();
        let new_btn_text = $(this).data('btn-text');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: route,
            type: "POST",
            data: {
                "project": target
            },
            success: xhr => {

                if (xhr.status) {

                    let data = xhr.data;
                    let icon = $(this).find('i');

                    if (icon.length > 0) {
                        if (data.liked) {
                            icon.removeClass('far').addClass('fas');
                        } else {
                            icon.removeClass('fas').addClass('far');
                        }
                    } else {

                        if (data.liked) {

                            $(this).addClass('bg-danger');
                            $(this).text(new_btn_text);
                            $(this).data('btn-text', btn_text);
                        } else {
                            $(this).removeClass('bg-danger');
                            $(this).text(new_btn_text);
                            $(this).data('btn-text', btn_text);
                        }
                    }

                    if (data.refresh_page) {
                        location.reload();
                    }
                }
            },
            error: xhr => {
                console.log(xhr);
            }
        });
    });

    let cookie = $.cookie("rhinosite_compare_collapse");
    let accordion = $(".compare.collapse, .btn-compare.collapse");

    if (cookie == 1) {
        accordion.collapse('show');
    }

    $(".btn-collapse").on('click', function () {

        var date = new Date();
        var minutes = 30;
        date.setTime(date.getTime() + (minutes * 60 * 1000));

        var status = cookie == 1 ? 0 : 1;

        $.cookie("rhinosite_compare_collapse", status, {
            expires: date
        });
        accordion.collapse('toggle');
    });

    $('.btn-compare').on('click', function () {

        let route = $(this).data('compare-route');
        let target = $(this).data('compare-target');
        let target_id = $(this).data('compare-target-id');
        let data_text = $(this).data('compare-text');
        let btn_text = $(this).text();
        let refresh = $(this).data('refresh');

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: route,
            type: "POST",
            data: {
                "target": target,
                "target_id": target_id,
            },
            success: xhr => {

                if (xhr.status) {

                    let data = xhr.data;

                    $(this).data('compare-text', btn_text);
                    $(this).text(data_text);
                    $(this).removeClass('bg-danger');

                    if ($.inArray(target_id, data.attached) !== -1) {
                        $(this).addClass('bg-danger');
                    }

                    $('.compare-count').text(data.selected);

                    if (data.selected > 1) {
                        $('.btn-view-result').removeAttr('disabled');
                    } else {
                        $('.btn-view-result').attr('disabled', true);
                    }

                    if (refresh) {
                        location.reload();
                    }

                } else {
                    alert(xhr.message);
                }
            }
        });
    });

    if ($('#ratingform ').length > 0) {

        let form = $('#ratingform');

        form.find("input[type=radio]").on('click', function () {

            let rating = $(this).val();
            let action = form.attr('action');
            let method = form.attr('method');
            let target = form.data('merchant');

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: action,
                type: method,
                data: {
                    "merchant": target,
                    "rating": rating
                },
                success: xhr => {

                    if (xhr.status) {

                        let data = xhr.data;

                        if (data.rated == true) {
                            $('#rating-stars').replaceWith('<p id="rating-stars">' + data.rating + '</p>');
                            alert(xhr.message);
                            return;
                        }
                    }
                }
            });
        });
    }
});
