Dropzone.autoDiscover = false;

$(function () {

    let dropzoneDiv = $('div#myDropzone');

    if(dropzoneDiv.length > 0) {

        let max_files       =   dropzoneDiv.data('max-files');
        let accepted_files  =   dropzoneDiv.data('accepted-files');
        let form            =   dropzoneDiv.parents('form');
        let form_url        =   form.attr('action');
        let btn_submit      =   form.find(':submit');

        dropzoneDiv.dropzone({
            url: form_url,
            addRemoveLinks: true,
            autoProcessQueue: false,
            uploadMultiple: true,
            maxFiles: max_files,
            parallelUploads: 100,
            maxFilesize: 10, //mb
            paramName: 'files',
            acceptedFiles: accepted_files,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            init: function() {

                let myDropzone = this;

                btn_submit.on('click', function (event) {
                    event.preventDefault();
                    myDropzone.processQueue();
                });

                this.on('sending', function (file, xhr, formData) {

                    let data = form.serializeArray();

                    $.each(data, function (key, element) {
                        formData.append(element.name, element.value);
                    });
                });

                this.on('success', function (file, response) {
                    Swal.fire({
                        icon: response.status ? 'success' : 'error',
                        title: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {

                        if(response.status) {
                            window.location.replace(response.data.redirect_to);
                        }
                    });
                });

                this.on('error', function (file, response) {
                    Swal.fire({
                        icon: 'error',
                        title: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    console.log(response);
                });
            }
        });
    }

});