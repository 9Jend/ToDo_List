@extends('layouts.app')

@section('content')
    <div class="z-3 alert alert-danger fade modal-alert mt-2" id="errorToast">
        Произошла ошибка
    </div>
    <div class="container">
        @if ($canEdit)
            <a href="{{ route('taskLists.tasks.create', $taskList->id) }}" class="btn btn-primary">Добавить элемент</a>
            <br>
        @endif

        <div class="list-group">
            @foreach ($tasks as $task)
                <div role="button" data-task-id="{{ $task->id }}" class="mt-2 list-group-item list-group-item-action d-flex gap-3 py-3 click-link"
                    aria-current="true" data-href="{{ route('taskLists.tasks.show', ['taskList' => $taskList->id, 'task' => $task->id]) }}">
                    @if ($task->photo)
                        <a class="text-decoration-none" href="{{ $task->photo }}">
                            <img src="{{ $task->photo }}" alt="photo" width="150px" height="150px"
                                class="rounded-circle flex-shrink-0 task-photo d-block">
                        </a>
                    @endif

                    <div class="d-flex gap-2 w-100 justify-content-between">
                        <div>
                            <h6 class="mb-0">{{ $task->name }}</h6>
                            <p class="mb-0 opacity-75 text-truncate text-wrap task-content">{{ $task->content }}</p>
                        </div>
                        @if ($canEdit)
                            <div class="d-flex flex-column justify-content-between">
                                <small class="opacity-50 text-nowrap text-center">{{ $task->created_at }}</small>
                                <form class="mt-auto deleteTaskForm"
                                    action="{{ route('taskLists.tasks.destroy', ['taskList' => $taskList->id, 'task' => $task->id]) }}"
                                    method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit"
                                        class="z-3 btn btn-link text-decoration-none text-danger hover-overlay">
                                        Удалить
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <h2 id="emptyList" class="@if (!$tasks->isEmpty()) visually-hidden @endif">
            Список элементов пуст
        </h2>
    </div>
@endsection
