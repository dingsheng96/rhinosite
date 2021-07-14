$(function () {

    let price_dynamic_form = $('#priceDynamicForm');
    let price_row_template = $('#priceCloneTemplate');

    if(price_dynamic_form.length > 0) {

        let row = 0;

        price_dynamic_form.on('click', '.btn-add-row', function (e) {

            row++;

            let newRow = price_row_template.clone(true)
                .removeClass('d-none')
                .removeAttr('id')
                .removeAttr("hidden")
                .removeAttr("aria-hidden");

            $(newRow).html(newRow.html().replaceAll('disabled', '').replaceAll('__REPLACE__', row));

            price_dynamic_form.find('tbody').append(newRow);
        });

        price_dynamic_form.on('click', '.btn-remove-row', function (e) {

            if(price_dynamic_form.find('tbody tr').not(':hidden').length > 1) {
                $(this).parents('tr').remove();
            } else {
                alert('At least one row is required.');
            }
        });
    }

    let attributeDynamicForm  = $('#attributeDynamicForm');
    let attributeCloneTemplate = $('#attributeCloneTemplate');

    if(attributeDynamicForm.length > 0) {

        let row = 0;

        attributeDynamicForm.on('click', '.btn-add-row', function (e) {

            row++;

            let newRow = attributeCloneTemplate.clone(true)
                .removeAttr('id')
                .removeAttr("hidden")
                .removeAttr("aria-hidden");

            $(newRow).html(newRow.html()
                .replaceAll('disabled', '')
                .replaceAll('__REPLACE__', row));

            attributeDynamicForm.find('tbody').append(newRow);
        });

        attributeDynamicForm.on('click', '.btn-remove-row', function (e) {

            if(attributeDynamicForm.find('tbody tr').not(':hidden').length > 1) {
                $(this).parents('tr').remove();
            } else {
                alert('At least one row is required.');
            }
        });
    }

    let packageItemDynamicForm  = $('#packageItemDynamicForm');
    let packageItemCloneTemplate = $('#packageItemCloneTemplate');

    if(packageItemDynamicForm.length > 0) {

        let row = packageItemDynamicForm.find('tbody tr').not(':hidden').length + 1;

        packageItemDynamicForm.on('click', '.btn-add-row', function (e) {

            row++;

            destroySelect2(packageItemCloneTemplate);

            let newRow = packageItemCloneTemplate.clone(true)
                .removeAttr('id')
                .removeAttr("hidden")
                .removeAttr("aria-hidden");

            $(newRow).html(newRow.html()
                .replaceAll('disabled', '')
                .replaceAll('__REPLACE__', row)
            );

            packageItemDynamicForm.find('tbody').append(newRow);

            reInitSelect2();
        });

        packageItemDynamicForm.on('click', '.btn-remove-row', function (e) {

            if(packageItemDynamicForm.find('tbody tr').not(':hidden').length > 1) {
                $(this).parents('tr').remove();
            } else {
                alert('At least one row is required.');
            }
        });
    }
});

function reInitSelect2()
{
    $('.select2').select2({
        theme: 'bootstrap4'
    });
}

function destroySelect2(target)
{
    target.find('select.select2').select2('destroy');
}