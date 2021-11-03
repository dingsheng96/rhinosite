$(function () {
    $('.slider-for').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        fade: true,
        asNavFor: '.slider-nav'
    });

    $('.slider-nav').slick({
        slidesToShow: 5,
        slidesToScroll: 5,
        asNavFor: '.slider-for',
        dots: false,
        centerMode: false,
        focusOnSelect: true,
        infinite: false,
        centerPadding: '0px',
        responsive: [{
                breakpoint: 1400,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            }, {
                breakpoint: 992,
                settings: {
                    slidesToShow: 5,
                    slidesToScroll: 5
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    dots: true,
                    arrows: false
                },
            }

        ]

    });

    $('.slider.compare').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 3,
        adaptiveHeight: true,
        responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true,
                }
            },

        ]
    });

    $('.slider-subscription').slick({
        slidesToShow: 3,
        slidesToScroll: 3,
        dots: true,
        infinite: true,
        centerMode: true,
        responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    dots: true,
                }
            },
        ]
    });

    $('.slider-home').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        dots: false,
        infinite: true,
        responsive: [{
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                }
            },
            {
                breakpoint: 599,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                }
            },
        ]
    });

    $('.slider-steps-title').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        adaptiveHeight: true,
        swipe: false,
        asNavFor: '.slider-steps'
    });

    $('.slider-steps').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        asNavFor: '.slider-steps-title',
        dots: true,
        infinite: true,
    });

    $('.slider-recommendation').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        centerMode: true,
        responsive: [{
            breakpoint: 1200,
            settings: {
                slidesToShow: 2,
                centerMode: false,
            }
        }, {
            breakpoint: 767,
            settings: {
                slidesToShow: 1,
                centerMode: false,
            }
        }, {
            breakpoint: 599,
            settings: {
                slidesToShow: 1,
                centerMode: false,
                adaptiveHeight: true,
            }
        }]
    });

    $('.slider-management').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        arrows: false,
        infinite: true,
        adaptiveHeight: true,
    });
});
