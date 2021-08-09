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

    if($(".ads-booster-filter").length > 0) {

        if($('.ads-booster-filter option:selected').val() != 0) {
            getAdsFromMerchant($('.ads-booster-filter option:selected').val());
        }

        $(document).on("change", '.ads-booster-filter', function() {
            getAdsFromMerchant($(this).val());
        });
    }

    if($(".project-ads-filter").length > 0) {

        if($('.project-ads-filter option:selected').val() != 0) {
            getProjectsAndAdsFromMerchant($('.project-ads-filter option:selected').val());
        }

        $(document).on("change", '.project-ads-filter', function() {
            getProjectsAndAdsFromMerchant($(this).val());
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

function getProjectsAndAdsFromMerchant(merchant_id) {

    let project_dropdown = $(".project-dropdown");
    let ads_dropdown = $(".ads-dropdown");

    removeChildOption(project_dropdown);
    removeChildOption(ads_dropdown);

    let project_url = project_dropdown.data("merchant-project-route").replace("__REPLACE__", merchant_id);
    let ads_url = ads_dropdown.data("merchant-ads-route").replace("__REPLACE__", merchant_id);

    if (merchant_id != null && merchant_id != "") {
        setDataIntoDropdown(project_url, project_dropdown, "title", "id");
        setDataIntoDropdown(ads_url, ads_dropdown, "name", "id");
    }
}

function getAdsFromMerchant(merchant_id) {

    let ads_dropdown = $('.ads-dropdown');

    removeChildOption(ads_dropdown);

    let ads_url = ads_dropdown.data("merchant-ads-route").replace("__REPLACE__", merchant_id);

    if (merchant_id != null && merchant_id != "") {

        setDataIntoDropdown(ads_url, ads_dropdown, "name", "id");
    }
}