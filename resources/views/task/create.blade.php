@extends('layouts.app')

@section('content')
    <div class="container">
        <form action="{{ route('taskLists.tasks.store', $taskList->id) }}" accept="image/png, image/jpeg, image/jpg" enctype="multipart/form-data" method="post" class="form-control">
            @csrf
            <input type="hidden" name="task_list_id" value="{{ $taskList->id }}">
            <div class="form-group">
                <label for="photo">Фото</label>
                <input name="photo" type="file" class="form-control-file" id="photo">
            </div>
            <div class="form-group">
                <label for="name">Заголовок</label>
                <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                    required value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label for="content">Контент</label>
                <textarea name= "content" class="form-control @error('content') is-invalid @enderror" id="content" rows="10"
                    required>{{ old('content') }}</textarea>
            </div>
            <div class="form-group">
                <label for="tags" title="Введите теги через пробелы или с новой строки">Теги</label>
                <textarea name="tags" title="Введите теги через пробелы или с новой строки" class="form-control" id="tags"
                    rows="3">{{ old('tags') }}</textarea>
            </div>
            <br>
            <button class="btn btn-primary">Добавить</button>
        </form>
    </div>
    @error('content') {{ $message }}@enderror
@endsection
