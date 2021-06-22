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
            method: 'post',
            addRemoveLinks: true,
            autoProcessQueue: false,
            uploadMultiple: true,
            maxFiles: max_files,
            parallelUploads: 100,
            maxFilesize: 10, //mb
            paramName: 'files',
            acceptedFiles: accepted_files,
            init: function() {

                let myDropzone = this;

                btn_submit.on('click', function (event) {
                    event.preventDefault();

                    dropzoneDiv.removeClass('is-invalid');
                    dropzoneDiv.parent().find('.invalid-feedback').remove();

                    if(myDropzone.getQueuedFiles().length < 1) {

                        let message = 'At least 1 image is required.';
                        customAlert(message, 'error');

                    } else {
                        myDropzone.processQueue();
                    }
                });

                this.on('maxfilesexceeded', function (file) {
                    this.removeFile(file);
                });

                this.on('sending', function (file, xhr, formData) {

                    let data = form.serializeArray();

                    $.each(data, function (key, el) {
                        formData.append(el.name, el.value);
                    });
                });

                this.on('success', function (file, response) {

                    this.removeAllFiles();

                    console.log(response);

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

                    console.log(response);

                    let errors = response.errors;

                    if(errors) {

                        this.removeAllFiles();

                        form.find('.is-invalid').removeClass('is-invalid');
                        form.find('.invalid-feedback').remove();

                        $.each(errors, function (name, message) {

                            let el = form.find('[name='+name+']');

                            el.addClass('is-invalid');
                            el.parent().append('<span class="invalid-feedback" role="alert"><strong>'+message+'</strong></span>');
                        });
                    }

                    alertHeader(message);
                    customAlert(response.message ?? response, 'error');
                });
            }
        });
    }
});