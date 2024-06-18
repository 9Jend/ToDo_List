<div role="button" class="list-group-item list-group-item-action mt-1 rounded click-link" aria-current="true"
    data-href="{{ route('taskLists.tasks.index', $taskList->id) }}">
    <div class="d-flex w-100 justify-content-between">
        <div class="col-sm-8 col-lg-9 row align-items-center">
            <h5 class="col-9">{{ $taskList->name }}</h5>
            <div class="col align-items-end">
                <small>{{ $taskList->created_at }}</small>
            </div>
        </div>
        <div class="d-flex row">
            <div class="col">
                <a href="{{ route('taskLists.edit', $taskList->id) }}"
                    class="z-3 btn btn-link text-decoration-none text-primary">
                    Редактировать
                </a>
            </div>
            <div class="col">
                <form action="{{ route('taskLists.destroy', $taskList->id) }}" method="post"
                    class="deleteTaskListForm">
                    @csrf
                    @method('delete')
                    <button class="z-3 btn btn-link text-decoration-none text-danger hover-overlay">
                        Удалить
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
