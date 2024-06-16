<a href="# {{ $taskList->id }}" class="list-group-item list-group-item-action mt-1 rounded" aria-current="true">
    <div class="d-flex w-100 justify-content-between align-items-center">
        <h5 class="mb-1 w-75">{{ $taskList->name }}</h5>
        <small>{{$taskList->created_at}}</small>
        <form action="{{ route('taskLists.destroy', $taskList->id) }}" method="post" class="deleteTaskListForm">
            @csrf
            @method('delete')
            <button class="btn btn-link text-decoration-none text-danger hover-overlay">
                Удалить
            </button>
        </form>
    </div>
</a>
