function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#newPhoto').attr('src', e.target.result);
            $('#newPhotoContainer').removeClass('visually-hidden')
            $("#removePhotoCheckbox").prop('checked', false)
            if (!$('#removePhotoContainer').hasClass('visually-hidden'))
                $('#removePhotoContainer').addClass('visually-hidden');
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#photoInput").change(function () {
    readURL(this);
});

$("#editTaskForm").submit(function () {
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        dataType: 'JSON',
        data: new FormData(this),
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            $("#successToast").toast("show");
            $("#photoInput").val(null);
            $("#removePhotoCheckbox").prop('checked', false)
            if ($('#removePhotoContainer').hasClass('visually-hidden'))
                $('#removePhotoContainer').removeClass('visually-hidden');
            if (response.photo) {
                $('#oldPhoto').attr('src', response.photo);
                $('#oldPhotoLink').attr('href', response.photo);
                if (!$('#newPhotoContainer').hasClass('visually-hidden'))
                    $('#newPhotoContainer').addClass('visually-hidden');
                if ($('#oldPhotoContainer').hasClass('visually-hidden'))
                    $('#oldPhotoContainer').removeClass('visually-hidden');
            } else {
                if (!$('#oldPhotoContainer').hasClass('visually-hidden'))
                    $('#oldPhotoContainer').addClass('visually-hidden');
            }
        },
        error: function (jqXHR, exception) {
            $("#errorToast").toast("show");
            console.log(jqXHR);
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
            } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
            } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
            } else if (exception === 'timeout') {
                msg = 'Time out error.';
            } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
            } else {
                msg = 'Uncaught Error.\n' + jqXHR.responseText;
            }
            console.error(msg);
        },
    });
    return false;
});
