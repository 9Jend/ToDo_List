$('#createTaskListForm').submit(function () {
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: 'html',
        success: function (response) {
            console.log(response);
            $('#nameInput').val('');

            if ($('#nameInput').hasClass('is-invalid'))
                $('#nameInput').removeClass('is-invalid');

            if (!$('#emptyList').hasClass('visually-hidden'))
                $('#emptyList').addClass('visually-hidden');

            $('#taskLists').append(response);
            $("#createTaskListModalClose").trigger("click");
        },
        error: function (jqXHR, exception) {
            console.log(jqXHR);
            var $needAlert = true;
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 419) {
                msg = jqXHR.responseText;
                $needAlert = false;
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
            if($needAlert)
                alert('Произошла ошибка');
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
            $(this).closest('.list-group-item').remove();
            if (response.emptyList && $('#emptyList').hasClass('visually-hidden'))
                $('#emptyList').removeClass('visually-hidden');
        }.bind(this),
        error: function (jqXHR, exception) {
            alert('Произошла ошибка');
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
