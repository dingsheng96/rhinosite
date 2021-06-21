function customAlert(message, status)
{
    Swal.fire({
        icon: status,
        title: message,
        timer: 2000,
        showConfirmButton: false
    });
}

function deleteAlert(title, message, redirectUrl)
{
    Swal.fire({
        title: title,
        text: message,
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-success btn-lg mx-2',
            cancelButton: 'btn btn-danger btn-lg mx-2'
        },
        buttonsStyling: false,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                url: redirectUrl,
                type: "POST",
                data: {
                    '_method': 'delete',
                    '_token': $('meta[name="csrf-token"]').attr('content')
                },
                success: (xhl) => {

                    Swal.fire({
                        icon: xhl.status ? 'success' : 'error',
                        title: xhl.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {

                        if(xhl.status) {
                            window.location.replace(xhl.data.redirect_to);
                        }
                    });
                },
                error: (xhl) => {
                    Swal.fire({
                        icon: 'error',
                        title: xhl.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }
    });
}

function logoutAlert(title)
{
    Swal.fire({
        title: title,
        icon: 'warning',
        showCancelButton: true,
        customClass: {
            confirmButton: 'btn btn-success btn-lg mx-2',
            cancelButton: 'btn btn-danger btn-lg mx-2'
        },
        buttonsStyling: false,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $('#logout-form').submit();
        }
    });
}

function setDataIntoDropdown(url, dropdown, option_text, option_value) {
    $.ajax({
        url: url,
        type: "GET",
        success: xhr => {
            if (xhr.status) {
                $.each(xhr.data, function(index, value) {
                    let new_value = getAssociateArrayData(option_text, value);
                    let newOption = new Option(
                        new_value,
                        value[option_value],
                        false,
                        false
                    );
                    dropdown.append(newOption).trigger("change");
                });

                setSelectedDataIntoDropdown(dropdown);
            }
        }
    });
}

function setSelectedDataIntoDropdown(dropdown) {

    let selected_data = dropdown.data("selected");

    if ($.isArray(selected_data)) {
        $.each(selected_data, function(index, value) {
            if (value != "" && value != null) {
                dropdown.val(value).trigger("change");
            }
        });
    } else {

        if((selected_data != "" && selected_data != null)) {
            dropdown.val(selected_data).trigger("change");
        }
    }
}

function getAssociateArrayData(text, value) {
    let str_split = text.split(".");
    let new_value;

    for (let i = 0; i < str_split.length; i++) {
        if (i == 0) {
            new_value = value[str_split[i]];
        } else {
            new_value = new_value[str_split[i]];
        }
    }

    return new_value;
}

function removeChildOption(dropdown)
{
    dropdown.children("option").not("[value=0]").remove();

    dropdown.val(0).trigger("change");
}