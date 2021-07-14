$(function() {

    let country_id;

    if ($(".country-state-filter").length > 0) {

        if ($(".country-state-filter option:selected").val() != 0) {

            country_id = $(".country-state-filter option:selected").val();
            getCountryStatesFromCountry($(".country-state-filter option:selected").val());
        }

        $(".country-state-filter").on("change", function() {

            country_id = $(this).val();
            getCountryStatesFromCountry(country_id);
        });
    }

    if ($(".country-state-dropdown").length > 0) {

        if ($(".country-state-dropdown option:selected").val() != 0) {
            getCitiesFromCountryState(country_id, $(".country-state-dropdown option:selected").val());
        }

        $(".country-state-dropdown").on("change", function() {
            getCitiesFromCountryState(country_id, $(this).val());
        });
    }

    if($(".sku-filter").length > 0) {

        if($('.sku-filter option:selected').val() != 0) {
            getSkuFromProduct($('.sku-filter'), $('.sku-filter option:selected').val());
        }

        $(document).on("change", '.sku-filter', function() {
            getSkuFromProduct($(this), $(this).val());
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

function getSkuFromProduct(parent, product_id) {

    let dropdown = parent.parents('tr').find('.sku-dropdown');

    removeChildOption(dropdown);

    let url = dropdown.data("sku-route").replace("__PRODUCT__", product_id);

    if (product_id != null && product_id != "") {
        setDataIntoDropdown(url, dropdown, "sku", "id");
    }
}