
$('#searchTaskByTag').autocomplete({
    source: function (request, response) {
        $.ajax({
            url: "/task/search",
            type: "GET",
            dataType: "json",
            data: {
                tagName: request.term
            },
            success: function (data) {
                response($.map(data, function (item) {
                    return {
                        label: item.name,
                        tasksId: item.id,
                        taskListsId: item.task_list_id
                    };
                }));
            }
        });
    },
    minLength: 3,
    select: function (event, ui) {
        event.preventDefault();
        console.log(ui);
        if (ui.item.taskListsId && ui.item.tasksId) {
            location.href = `/taskLists/${ui.item.taskListsId}/tasks/${ui.item.tasksId}`;
        }
    },
    open: function () {
        $(this).removeClass("ui-corner-all").addClass("ui-corner-top");
    },
    close: function () {
        $(this).removeClass("ui-corner-top").addClass("ui-corner-all");
    },
    response: function(event, ui) {
        if (!ui.content.length) {
            var message = { value:"",label:"Результатов не найдено" };
            ui.content.push(message);
        }
    }
});
