@foreach ($taskList->users as $user)
    <tr>
        <td>{{ $user->name }}</td>
        <td>{{ $user->pivot->role }}</td>
        <td>
            @if ($user->id != Auth::user()->id)
                <form action="{{ route('taskLists.detach', $taskList->id) }}" method="post" class="deleteAccessForUsersForm">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="userId" value="{{ $user->id }}">
                    <button class="btn btn-link text-decoration-none text-danger">&#10006;</button>
                </form>
            @endif

        </td>
    </tr>
@endforeach
