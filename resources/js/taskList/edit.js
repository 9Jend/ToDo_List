$('#userName').autocomplete({
    source: function (request, response) {
        $.ajax({
            url: "/user/search",
            type: "GET",
            dataType: "json",
            data: {
                userName: request.term
            },
            success: function (data) {
                response($.map(data, function (item) {
                    return {
                        label: item.name,
                        value: item.id
                    };
                }));
            }
        });
    },
    minLength: 3,
    select: function (event, ui) {
        event.preventDefault();
        $(event.target).val(ui.item.label);
        $('#userId').val(ui.item.value);
    },
    open: function () {
        $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
        $(this).autocomplete('widget').css('z-index', 10000);
    },
    close: function () {
        $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
    }
});

$("#addAccessForUsersForm").submit(function () {
    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: 'html',
        success: function (response) {
            $('#userAccessTable tbody').html(response);
            $("#addAccessForUsersModalClose").trigger("click");
            $("#userId").val();
            $("#userName").val();
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
                if (!$('#userName').hasClass('is-invalid'))
                    $('#userName').addClass('is-invalid');
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

$(document).on("submit", ".deleteAccessForUsersForm", function () {

    $.ajax({
        type: "POST",
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: 'html',
        success: function (response) {
            $('#userAccessTable tbody').html(response);
        },
        error: function (jqXHR, exception) {
            console.log(jqXHR);
            var msg = '';
            if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
            } else if (jqXHR.status == 419) {
                msg = jqXHR.responseText;
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
            alert('Произошла ошибка');
        },
    });
    return false;
});
