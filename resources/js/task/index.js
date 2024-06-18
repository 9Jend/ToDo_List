$(".deleteTaskForm").submit(function () {
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        dataType: 'JSON',
        data: $(this).serialize(),
        success: function (response) {
            console.log(response);
            $(`*[data-task-id="${response.taskId}"]`).remove();

            if ($('.list-group-item').length == 0 && $('#emptyList').hasClass('visually-hidden'))
                $('#emptyList').removeClass('visually-hidden');
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
