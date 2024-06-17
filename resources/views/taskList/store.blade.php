<div role="button" class="list-group-item list-group-item-action mt-1 rounded" aria-current="true">
    <div class="d-flex w-100 justify-content-between">
        <div class="col-8 row align-items-center"
            onclick="location.href='{{ route('taskLists.tasks.index', $taskList->id) }}';">
            <h5 class="col-9">{{ $taskList->name }}</h5>
            <div class="col">
                <small>{{ $taskList->created_at }}</small>
            </div>
        </div>
        <div class="col-2">
            <a href="{{ route('taskLists.edit', $taskList->id) }}"
                class="z-3 btn btn-link text-decoration-none text-primary">
                Редактировать
            </a>
        </div>
        <div class="col-2">
            <form action="{{ route('taskLists.destroy', $taskList->id) }}" method="post" class="deleteTaskListForm">
                @csrf
                @method('delete')
                <button class="z-3 btn btn-link text-decoration-none text-danger hover-overlay">
                    Удалить
                </button>
            </form>
        </div>
    </div>
</div>
