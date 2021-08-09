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

    if ($(".ads-date-filter").length > 0 && $('.ads-date-picker').length > 0) {

        let date_filter     =   $(".ads-date-filter");
        let datepicker      =   $('.ads-date-picker');
        let minDate         =   new Date(new Date().setDate(new Date().getDate() + 1));
        let maxDate         =   new Date(new Date().setMonth(new Date().getMonth() + 6));
        let disabled_dates  =   [];

        date_filter.on("change", function() {

            if ($(this).val() != null && $(this).val() != "") {

                let project = $('.project-dropdown').length > 0 ? $('.project-dropdown option:selected').val() : $('.ads-date-picker').data('project');

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: datepicker.data("ads-date-filter-route").replace("__REPLACE__", $(this).val()),
                    type: "POST",
                    data: {
                        "project": project
                    },
                    success: xhr => {

                        datepicker.removeAttr('disabled');
                        datepicker.attr('readonly', true);
                        datepicker.addClass('bg-white');

                        if (xhr.status) {

                            disabled_dates = xhr.data;

                            datepicker.pikaday({
                                format: 'YYYY-MM-DD',
                                minDate: minDate,
                                maxDate: maxDate,
                                disableDayFn: function (date) {

                                    if ($.inArray(moment(date).format("YYYY-MM-DD"), Object.values(disabled_dates)) !== -1) {
                                        return date;
                                    }
                                },
                            });
                        }
                    }
                });
            }
        });


    }

});