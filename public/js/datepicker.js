$(function () {

    if($('.date-picker').length > 0) {

        let datepicker_input = $('.date-picker');

        datepicker_input.pikaday({
            format: 'YYYY-MM-DD',
            yearRange: [1900, new Date().getFullYear()],
        });
    }

    if($('.date-picker-plan').length > 0) {

        let datepicker_input = $('.date-picker-plan');

        datepicker_input.pikaday({
            format: 'YYYY-MM-DD',
            minDate: new Date()
        });
    }


    // let today = new Date();
    // let nextMonth = new Date();
    // nextMonth.setMonth(today.getMonth() + 1);

    // let datepicker = new Pikaday({
    //     field: $('.ads-date-picker')[0],
    //     minDate: today,
    //     maxDate: nextMonth,
    //     format: 'DD/MM/YYYY'
    // });

    // $(function () {

    //     if ($(".ads-slot-filter").length > 0) {
    //         if ($(".ads-slot-filter").val() != null && $(".ads-slot-filter").val() != "") {
    //             getAdsAvailableDate($(".ads-slot-filter"), $(".ads-slot-filter").val());
    //         }

    //         $(".ads-slot-filter").on("change", function() {
    //             getAdsAvailableDate($(this), $(this).val());
    //         });
    //     }
    // });

    // function getAdsAvailableDate(dropdown, ads_id) {

    //     let url = dropdown.data("filter-route").replace("__REPLACE__", ads_id);

    //     if (ads_id != null && ads_id != "") {
    //         $.ajax({
    //             url: url,
    //             type: "GET",
    //             success: xhr => {
    //                 if (xhr.status) {

    //                 }
    //             }
    //         });
    //     }
    // }
});