@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <a class="link-primary" href="{{ route('taskLists.tasks.index', $taskList->id) }}">Вернутся к списку</a>
                <div class="d-flex">
                    @if ($task->photo)
                        <div class="m-2">
                            <a class="text-decoration-none" href="{{ $task->photo }}">
                                <img src="{{ $task->photo }}" alt="photo" width="150px" height="150px"
                                    class="rounded-circle flex-shrink-0 task-photo d-block">
                            </a>
                        </div>
                    @endif
                    <div>
                        <h5 class="card-title">{{ $task->name }}</h5>
                        <p class="card-text">{{ $task->content }}</p>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if (!$task->tags->isEmpty())
                    <h5 class="card-title">Теги</h5>
                    <ul class="list-inline">
                        @foreach ($task->tags as $tag)
                            <li class="list-inline-item">#{{ $tag->name }}</li>
                        @endforeach
                    </ul>
                @endif
                <a class="link-primary"
                    href="{{ route('taskLists.tasks.edit', ['taskList' => $taskList->id, 'task' => $task->id]) }}">Редактировать</a>
            </div>
        </div>
    </div>
@endsection
