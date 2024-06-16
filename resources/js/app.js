import './bootstrap';

$('#createTaskListForm').submit(function () {
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: 'html',
        success: function (response) {
            console.log(response);

            if ($('#nameInput').hasClass('is-invalid'))
                $('#nameInput').removeClass('is-invalid');
            $('#nameInput').val('');

            $('#taskLists').append(response);
            $("#createTaskListModalClose").trigger( "click" );
        },
        error: function (jqXHR, exception) {
            console.log(jqXHR);
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 422) {
                msg = jqXHR.responseText;
                if (!$('#nameInput').hasClass('is-invalid'))
                    $('#nameInput').addClass('is-invalid');
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

$(document).on("submit", ".deleteTaskListForm", function () {
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: 'JSON',
        success: function (response) {
            console.log(response);
            $(this).closest('a').remove();
        }.bind(this),
        error: function (jqXHR, exception) {
            alert('error');
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
