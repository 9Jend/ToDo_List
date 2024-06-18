@extends('layouts.app')

@section('content')
    <div class="z-3 alert alert-success fade modal-alert mt-2" id="successToast">
        Запись успешно сохранена
    </div>
    <div class="z-3 alert alert-danger fade modal-alert mt-2" id="errorToast">
        Произошла ошибка
    </div>
    <div class="container">
        <form action="{{ route('taskLists.tasks.update', ['taskList' => $taskList->id, 'task' => $task->id]) }}"
            accept="image/png, image/jpeg, image/jpg" enctype="multipart/form-data" method="post" class="form-control"
            id="editTaskForm">
            @csrf
            @method('patch')
                <a class="link-primary" href="{{ route('taskLists.tasks.index', $taskList->id) }}">Вернутся к списку</a>
            <div class="form-group mt-2">
                <label for="photoInput">Фото</label>
                <input name="photo" type="file" class="mb-2 form-control-file" id="photoInput">
                <div class="photos d-flex">
                    <div id="newPhotoContainer" class="m-2 visually-hidden">
                        <h6>Новое фото</h6>
                        <img src="#" alt="photo" width="150px" height="150px"
                            class="rounded-circle flex-shrink-0 task-photo d-block" id="newPhoto">
                    </div>

                    <div id="oldPhotoContainer" class="m-2 @if (!$task->photo) visually-hidden @endif">
                        <h6>Текущее фото</h6>
                        <a class="text-decoration-none mt-2" href="{{ $task->photo }}" id="oldPhotoLink">
                            <img src="{{ $task->photo }}" alt="photo" width="150px" height="150px"
                                class="rounded-circle flex-shrink-0 task-photo d-block" id="oldPhoto">
                        </a>
                        <input name="removePhoto" class="form-check-input" type="checkbox" value="true"
                            id="removePhotoCheckbox">
                        <label class="form-check-label" for="removePhotoCheckbox">
                            Удалить текущее фото
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="form-group">
                    <label for="name">Заголовок</label>
                    <input name="name" type="text" class="form-control" id="name" required
                        value="{{ $task->name }}">
                </div>
                <div class="form-group">
                    <label for="content">Контент</label>
                    <textarea name= "content" class="form-control" id="content" rows="10" required>{{ $task->content }}</textarea>
                </div>
                <div class="form-group">
                    <label for="tags" title="Введите теги через пробелы или с новой строки">Теги</label>
                    <textarea name="tags" title="Введите теги через пробелы или с новой строки" class="form-control" id="tags"
                        rows="3">@foreach ($task->tags as $tag){{ $tag->name }} @endforeach</textarea>
                </div>
                <br>
                <button class="btn btn-primary">Сохранить</button>
                <a class="link-primary m-2" href="{{ route('taskLists.tasks.show', ['taskList' => $taskList->id, 'task' => $task->id]) }}">Просмотр</a>

        </form>
    </div>
@endsection
