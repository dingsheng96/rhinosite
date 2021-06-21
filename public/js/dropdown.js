$(function() {

    let country_id;

    if ($(".country-state-filter").length > 0) {

        if ($(".country-state-filter option:selected").val() != 0) {
            getCountryStatesFromCountry($(".country-state-filter option:selected").val());
        }

        $(".country-state-filter").on("change", function() {

            country_id = $(this).val();
            getCountryStatesFromCountry(country_id);
        });
    }

    if ($(".city-filter").length > 0) {
        if ($(".city-filter option:selected").val() != 0) {
            getCitiesFromCountryState(country_id, $(".city-filter option:selected").val());
        }

        $(".city-filter").on("change", function() {
            getCitiesFromCountryState(country_id, $(this).val());
        });
    }

    if ($(".disabled-date-filter").length > 0) {
        if ($(".disabled-date-filter").val() != null && $(".disabled-date-filter").val() != "") {
            getDisableDate($(".disabled-date-filter").val());
        }

        $(".disabled-date-filter").on("change", function() {
            getDisableDate($(this).val());
        });
    }

});

function getCountryStatesFromCountry(country_id) {
    let dropdown = $(".country-state-dropdown");

    removeChildOption(dropdown);

    let url = dropdown.data("country-state-route").replace("__REPLACE__", country_id);

    if (country_id != null && country_id != "") {
        setDataIntoDropdown(url, dropdown, "name", "id");
    }
}

function getCitiesFromCountryState(country_id, state_id) {
    let dropdown = $(".city-dropdown");

    removeChildOption(dropdown);

    let url = dropdown.data("city-route").replace("__FIRST_REPLACE__", country_id).replace("__SECOND_REPLACE__", state_id);

    if (country_id != null && country_id != "" && state_id != null && state_id != "") {
        setDataIntoDropdown(url, dropdown, "name", "id");
    }
}

function getDisableDate(source_id) {
    let datepicker = $(".date-picker");

    let url = datepicker.data("data-disabled-date-route").replace("__REPLACE__", source_id);

    if (source_id != null && source_id != "") {
        $.ajax({
            url: url,
            type: "GET",
            success: xhr => {

                if (xhr.status) {

                    datepicker.daterangepicker({
                        isInvalidDate: function (date) {
                            $.each(xhr.data, function(index, value) {
                                if(date == value) {
                                    return true;
                                }
                            });
                        }
                    });
                }
            }
        });
    }
}