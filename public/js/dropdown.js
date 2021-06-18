$(function() {

    if ($(".city-filter").length > 0) {
        if ($(".city-filter option:selected").val() != 0) {
            getCitiesFromCountryState($(".city-filter option:selected").val());
        }

        $(".city-filter").on("change", function() {
            getCitiesFromCountryState($(this).val());
        });
    }

});