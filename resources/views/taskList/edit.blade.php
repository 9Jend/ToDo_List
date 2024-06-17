@extends('layouts.app')

@section('content')
    <div class="container">

        <h2>Редактирование списка</h2>
        <form action="{{ route('taskLists.update', $taskList->id) }}" method="POST">
            @csrf
            @method('patch')
            <label for="nameInput" class="form-label">Название списка</label>
            <input
                class="form-control w-25
                        @error('name')
                        is-invalid
                        @enderror"
                id="nameInput" type="text" name="name" value="{{ old('name', $taskList->name) }}" required>
            <br>
            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>
        @if ($isAdmin)
            <h3 class="mt-3">Пользователи которым доступен список</h3>
            <table class="table" id="userAccessTable">
                <thead>
                    <tr>
                        <th scope="col">Логин пользовтеля</th>
                        <th scope="col">Роль</th>
                        <th scope="col">Удалить</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($taskList->users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->pivot->role }}</td>
                            <td>
                                @if ($user->id != Auth::user()->id)
                                    <form action="{{ route('taskLists.detach', $taskList->id) }}" method="post">
                                        @csrf
                                        @method('patch')
                                        <input type="hidden" name="userId" value="{{ $user->id }}">
                                        <button class="btn btn-link text-decoration-none text-danger">&#10006;</button>
                                    </form>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAccessForUsersModal">
                Добавить доступ к списку
            </button>

            <div class="modal fade" id="addAccessForUsersModal" tabindex="-1" aria-labelledby="addAccessForUsersModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('taskLists.attach', $taskList->id) }}" method="post" id='addAccessForUsersForm'>
                        @csrf
                        @method('patch')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="addAccessForUsersModalLabel">Создать список</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <input type="hidden" name="userId" id="userId">
                                    <label for="userName" class="form-label">Имя пользователя</label>
                                    <input type="text" class="form-control" name="userName" id="userName">
                                </div>
                                <div class="mb-3">
                                    <label for="userRole" class="form-label">Выберите роль</label>
                                    <select name="userRole" id="userRole" class="form-select"
                                        aria-label="Default select example">
                                        <option value="{{ $taskRoleRead }}">{{ $taskRoleRead }}</option>
                                        <option value="{{ $taskRoleChange }}">{{ $taskRoleChange }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                    id='addAccessForUsersModalClose'>Закрыть</button>
                                <button type="submit" class="btn btn-primary">Сохранить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection
