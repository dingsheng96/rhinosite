$('.filter-btn').on('click', function () {
    $('.filter-overlay').toggleClass('open');
});

$('.closebtn').on('click', function () {
    $('.filter-overlay').toggleClass('open');
});

$('body,html').on('mousedown', function (e) {
    var clicked = $(e.target);
    if (clicked.is('.filter-overlay') || clicked.parents().is('.filter-overlay')) {
        return;
    } else {
        $('.filter-overlay').removeClass('open');
    }
});