@extends('layouts.app')

@section('content')
    <div class="container">

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskListModal">
            Добавить список
        </button>

        <div class="modal fade" id="createTaskListModal" tabindex="-1" aria-labelledby="createTaskListModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('taskLists.store') }}" method="post" id='createTaskListForm'>
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="createTaskListModalLabel">Создать список</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nameInput" class="form-label">Название списка</label>
                                <input type="text" class="form-control" name="name" id="nameInput">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id='createTaskListModalClose'>Закрыть</button>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="list-group mt-3" id="taskLists">
            @foreach ($taskLists as $taskList)
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
            @endforeach
        </div>
    </div>
@endsection
